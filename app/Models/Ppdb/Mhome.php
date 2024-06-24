<?php 

namespace App\Models\Ppdb;

use function PHPUnit\Framework\returnSelf;

Class Mhome 
{
    protected $db;
    protected $ro;
    protected $session;
    protected $tahun_ajaran_id;
    protected $putaran;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->ro = \Config\Database::connect('ro');
        $this->session = \Config\Services::session();
        $this->tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");
		$this->putaran = $this->session->get("putaran_aktif");
    }

	function carisiswa($nama){
		$query = "select a.peserta_didik_id,a.nama,a.jenis_kelamin,b.nama AS sekolah
				  from tcg_peserta_didik a
				  join ref_sekolah b on a.sekolah_id = b.sekolah_id
				  join tcg_pendaftaran c on a.peserta_didik_id = c.peserta_didik_id and c.is_deleted=0
				  where a.is_deleted=0 and (a.nama like ?)
				  group by a.peserta_didik_id,a.nama,a.jenis_kelamin,b.nama";

		return $this->ro->query($query, array("%$nama%"));
	}

	function tcg_dashboard_summary($jenjang_id, $status, $putaran, $penerapan_id, $kode_wilayah){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$builder = $this->ro->table('rpt_rekapitulasi_wilayah a');
		$builder->select('a.*');
		$builder->where('a.tahun_ajaran_id',$tahun_ajaran_id);

        if (!empty($jenjang_id)) {
            $builder->where('a.jenjang_id',$jenjang_id);
        }

        if (!empty($status)) {
            $builder->where('a.status',$status);
        }

        if (!empty($putaran)) {
            $builder->where('a.putaran',$putaran);
        }

        // if (!empty($penerapan_id)) {
        //     $builder->where('a.penerapan_id',$penerapan_id);
        // }

        if (!empty($kode_wilayah)) {
            $builder->where('a.kode_wilayah',$kode_wilayah);
        }

		$result = $builder->get()->getResultArray();
        
        $retval = array();
        if (count($result) > 0) {
            $keys = array_keys($result[0]);
            foreach($keys as $k => $v) {
                $retval[$v] = 0;
            }
        }

        //var_dump($result);
        //var_dump($retval); exit;
        $retval["tahun_ajaran_id"] = $tahun_ajaran_id;
        $retval["jenjang_id"] = $jenjang_id;
        $retval["putaran"] = $putaran;
        $retval["penerapan_id"] = $penerapan_id;
        $retval["kode_wilayah"] = $kode_wilayah;
        $retval["nama"] = '';

        //summary
        foreach($result as $r) {
            foreach($keys as $k => $v) {
                if ($v == "tahun_ajaran_id")  continue;
                if ($v == "jenjang_id")  continue;
                if ($v == "putaran")  continue;
                if ($v == "penerapan_id")  continue;
                if (($v == "kode_wilayah" || $v == 'nama')) {
                    //$retval[$v] = $r[$v];
                    continue;
                }

                //sum
                $retval[$v] += intval($r[$v]);
            }
        }

        return $retval;
	}

	function tcg_dashboard_pendaftarharian($jenjang_id, $status, $putaran, $penerapan_id, $kode_wilayah){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		
		$day0 = date("Y-m-d") ;
		$nextday = date("Y-m-d", strtotime("+1 days"));
		$day1 = date("Y-m-d", strtotime("-1 days"));
		$day2 = date("Y-m-d", strtotime("-2 days"));
		$day3 = date("Y-m-d", strtotime("-3 days"));
		$day4 = date("Y-m-d", strtotime("-4 days"));
		$day5 = date("Y-m-d", strtotime("-5 days"));
		$day6 = date("Y-m-d", strtotime("-6 days"));
		$day7 = date("Y-m-d", strtotime("-7 days"));

		$builder = $this->ro->table('tcg_pendaftaran a');
		$builder->select("
            '$day0' as hari_ini,
            SUM(CASE WHEN DATE_ADD(a.created_on, INTERVAL 7 HOUR) BETWEEN '$day0' AND '$nextday' THEN 1 ELSE 0 END) AS day_0,
            SUM(CASE WHEN DATE_ADD(a.created_on, INTERVAL 7 HOUR) BETWEEN '$day1' AND '$day0' THEN 1 ELSE 0 END) AS day_1,
            SUM(CASE WHEN DATE_ADD(a.created_on, INTERVAL 7 HOUR) BETWEEN '$day2' AND '$day1' THEN 1 ELSE 0 END) AS day_2,
            SUM(CASE WHEN DATE_ADD(a.created_on, INTERVAL 7 HOUR) BETWEEN '$day3' AND '$day2' THEN 1 ELSE 0 END) AS day_3,
            SUM(CASE WHEN DATE_ADD(a.created_on, INTERVAL 7 HOUR) BETWEEN '$day4' AND '$day3' THEN 1 ELSE 0 END) AS day_4,
            SUM(CASE WHEN DATE_ADD(a.created_on, INTERVAL 7 HOUR) BETWEEN '$day5' AND '$day4' THEN 1 ELSE 0 END) AS day_5, 
            SUM(CASE WHEN DATE_ADD(a.created_on, INTERVAL 7 HOUR) BETWEEN '$day6' AND '$day5' THEN 1 ELSE 0 END) AS day_6, 
            SUM(CASE WHEN DATE_ADD(a.created_on, INTERVAL 7 HOUR) BETWEEN '$day7' AND '$day6' THEN 1 ELSE 0 END) AS day_7
        ");
		$builder->join('cfg_putaran b',"b.putaran=a.putaran and b.is_deleted=0");
        $builder->join('ref_sekolah c',"c.sekolah_id=a.sekolah_id and c.is_deleted=0");
        $builder->where('a.tahun_ajaran_id',$tahun_ajaran_id);
		$builder->where(array('a.is_deleted'=>0, 'a.cabut_berkas'=>0));
 
        if (!empty($jenjang_id)) {
            $builder->where('b.jenjang_id',$jenjang_id);
        }

        if (!empty($status)) {
            $builder->where('c.status',$status);
        }

        if (!empty($putaran)) {
            $builder->where('a.putaran',$putaran);
        }

        if (!empty($penerapan_id)) {
            $builder->where('a.penerapan_id',$penerapan_id);
        }

        if (!empty($kode_wilayah)) {
            $builder->where('c.kode_wilayah_kec',$kode_wilayah);
        }
        
        $result = $builder->get()->getRowArray();
		return $result;
	}    

    function tcg_dashboard_daftarpendaftaran($jenjang_id, $status, $putaran, $penerapan_id, $kode_wilayah) {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		
		$builder = $this->ro->table('tcg_pendaftaran a');
		$builder->select("a.*, c.nama as sekolah, e.nama, e.lintang, e.bujur, f.nama as penerapan, g.nama as jalur");
		$builder->join('cfg_putaran b',"b.putaran=a.putaran and b.is_deleted=0");
        $builder->join('ref_sekolah c',"c.sekolah_id=a.sekolah_id and c.is_deleted=0");
        $builder->join(
            "(select x.pendaftaran_id, x.sekolah_id, x.peserta_didik_id,
                row_number() over (partition by x.sekolah_id, x.peserta_didik_id order by x.pendaftaran_id) rn
            from tcg_pendaftaran x) as d"
            , "d.pendaftaran_id=a.pendaftaran_id and d.rn=1");
        $builder->join('tcg_peserta_didik e',"e.peserta_didik_id=a.peserta_didik_id and e.is_deleted=0");
        $builder->join('cfg_penerapan f',"f.penerapan_id=a.penerapan_id and f.is_deleted=0");
        $builder->join('ref_jalur g',"g.jalur_id=f.jalur_id and g.is_deleted=0");
        $builder->where('a.tahun_ajaran_id',$tahun_ajaran_id);
		$builder->where(array('a.is_deleted'=>0, 'a.cabut_berkas'=>0));
 
        if (!empty($jenjang_id)) {
            $builder->where('b.jenjang_id',$jenjang_id);
        }

        if (!empty($status)) {
            $builder->where('c.status',$status);
        }

        if (!empty($putaran)) {
            $builder->where('a.putaran',$putaran);
        }

        if (!empty($penerapan_id)) {
            $builder->where('a.penerapan_id',$penerapan_id);
        }

        if (!empty($kode_wilayah)) {
            $builder->where('c.kode_wilayah_kec',$kode_wilayah);
        }
        
        //limit
        //$builder->limit(5000);

        $result = $builder->get()->getResultArray();
		return $result;
    }

	function tcg_rapor_mutu($tahun_ajaran_id=null){
		if (empty($tahun_ajaran_id)) {
			$tahun_ajaran_id = $this->tahun_ajaran_id;
		}

		$builder = $this->ro->table('cfg_nilai_mutu_sekolah a');
		$builder->select('a.*, b.nama');
		$builder->join('ref_sekolah b', 'a.sekolah_id=b.sekolah_id', 'left outer');
        $builder->where('tahun_ajaran_id', $tahun_ajaran_id);
		return $builder->get()->getResultArray();
	}    

	function tcg_cek_registrasi($nama, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $nama_ibu_kandung){
        if (empty($tanggal_lahir))  $tanggal_lahir = null;

		$builder = $this->ro->table('tcg_peserta_didik a');
		$builder->select('COUNT(1) AS jumlah');
		$builder->join('dbo_users b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->where(array('a.nama'=>$nama,'a.jenis_kelamin'=>$jenis_kelamin,'a.tempat_lahir'=>$tempat_lahir,'a.tanggal_lahir'=>$tanggal_lahir,'a.nama_ibu_kandung'=>$nama_ibu_kandung,'a.is_deleted'=>0));

        //echo $builder->getCompiledSelect(); exit;

		$sudah_registrasi=0;
		foreach($builder->get()->getResult() as $row):
			$sudah_registrasi=$row->jumlah;
		endforeach;

		return $sudah_registrasi;
	}

	function tcg_cek_nisn($nisn){
		$builder = $this->ro->table('tcg_peserta_didik a');
		$builder->select('COUNT(1) AS jumlah');
		$builder->join('dbo_users b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->where(array('a.nisn'=>$nisn,'a.is_deleted'=>0));

        // echo $builder->getCompiledSelect(); exit;

		$sudah_registrasi=0;
		foreach($builder->get()->getResult() as $row):
			$sudah_registrasi=$row->jumlah;
		endforeach;

		return $sudah_registrasi;
	}

	function tcg_cek_nik($nik){
		$builder = $this->ro->table('tcg_peserta_didik a');
		$builder->select('COUNT(1) AS jumlah');
		$builder->join('dbo_users b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->where(array('a.nik'=>$nik,'a.is_deleted'=>0));

		$sudah_registrasi=0;
		foreach($builder->get()->getResult() as $row):
			$sudah_registrasi=$row->jumlah;
		endforeach;

		return $sudah_registrasi;
	}

	function tcg_registrasiuser($sekolah_id, $nik, $nisn, $nomor_ujian, $nama, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $nama_ibu_kandung, $kebutuhan_khusus, $alamat, $kode_wilayah, $lintang, $bujur, $nomor_kontak){

        $username = $nisn; 
		// $sekolah_id = secure($sekolah_id); 
		// $nik = secure($nik);
		// $nisn = secure($nisn); 
		// $nomor_ujian = secure($nomor_ujian); 
		// $nama = secure($nama); 
		// $jenis_kelamin = secure($jenis_kelamin); 
		// $tempat_lahir = secure($tempat_lahir);
		// $tanggal_lahir = secure($tanggal_lahir); 
		// $nama_ibu_kandung = secure($nama_ibu_kandung); 
		// $kebutuhan_khusus = secure($kebutuhan_khusus); 
		// $alamat = secure($alamat);
		// $kode_wilayah = secure($kode_wilayah);
		// $lintang = (empty($lintang)) ? 'null' : secure($lintang);
		// $bujur = (empty($bujur)) ? 'null' : secure($bujur);
		// $nomor_kontak = secure($nomor_kontak);

		$sql = "CALL ppdb_2024." .SQL_REGISTRASI. " (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $query = $this->db->query($sql, array($sekolah_id,$nik,$nisn,$nomor_ujian,$nama,$jenis_kelamin,$tempat_lahir,$tanggal_lahir,$nama_ibu_kandung,$kebutuhan_khusus,$alamat,$kode_wilayah,$lintang,$bujur,$nomor_kontak));
        if ($query == null)     return null;

        //audittrail -> di sp

        $user = $this->tcg_detailuser($username);
		return $user;
	}

	function tcg_detailuser($username){
		// $username = $this->input->post("username",TRUE);
		// $password = $this->input->post("password",TRUE);

		//$peran_id = $this->input->post("peran_id",TRUE);
		$builder = $this->ro->table('dbo_users a');
		$builder->select('a.user_id,a.role_id as peran_id,a.nama as nama_pengguna,a.user_name as username,a.approval,
                            a.peserta_didik_id, c.nisn, a.sekolah_id, c.tanggal_lahir, c.asal_data, c.kebutuhan_khusus, c.tutup_akses, CONVERT(c.kode_wilayah,CHAR(8)) AS kode_wilayah, 
                            b.nama as sekolah, b.bentuk');
		$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id','LEFT OUTER');
		$builder->join('tcg_peserta_didik c','a.peserta_didik_id = c.peserta_didik_id','LEFT OUTER');
		$builder->where(array('a.is_deleted'=>0));
		$builder->groupStart()->orWhere('a.user_name', "$username")->orWhere('c.nisn',"$username")->orWhere('c.nik',"$username")->groupEnd();

        //echo $builder->getCompiledSelect(); exit;

		return $builder->get()->getRowArray();
	}

	// function tcg_login($username, $password){
	// 	$builder = $this->ro->table('dbo_users a');
	// 	$builder->select('count(*) as jumlah');
	// 	$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id','LEFT OUTER');
	// 	$builder->join('tcg_peserta_didik c','a.peserta_didik_id = c.peserta_didik_id','LEFT OUTER');
	// 	$builder->where(array('a.password'=>md5($password),'a.approval'=>1,'a.is_deleted'=>0));
	// 	$builder->groupStart()->orWhere('a.user_name', "$username")->orWhere('c.nisn',"$username")->orWhere('c.nik',"$username")->groupEnd();

	// 	$login=0;
	// 	foreach($builder->get()->getResult() as $row):
	// 		$login=$row->jumlah;
	// 	endforeach;

	// 	if ($login > 0) {
	// 		$this->tcg_audit_trail("","",'login','Login','','');
	// 	}

	// 	return $login;
	// }

	// function tcg_cek_ikutppdb($sekolah_id, $tahun_ajaran_id) {
	// 	// TODO: buka akses kalau nggak ikut ppdb tahap 2
	// 	$builder = $this->ro->table('cfg_kuota_sekolah a');
	// 	$builder->select('a.ikut_ppdb');
	// 	$builder->where(array('a.sekolah_id'=>$sekolah_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.is_deleted'=>0));

	// 	$ikut_ppdb=0;
	// 	foreach($builder->get()->getResult() as $row):
	// 		$ikut_ppdb=$row->ikut_ppdb;
	// 	endforeach;

	// 	return $ikut_ppdb;
	// }

	// function tcg_ubahpassword($password)
	// {
	// 	$user_id = $this->session->get("user_id");

	// 	$data = array(
	// 		'password' => md5($password),
	// 		'ganti_password' => 1,
	// 		'updated_on' => gmdate("Y/m/d")
	// 	);

	// 	$builder = $this->ro->table('dbo_users');
    //     $builder->where(array('user_id' => $user_id, 'is_deleted' => 0));
	// 	$retval = $builder($data);

	// 	if ($retval > 0) {
	// 		//put in audit trail
	// 		$this->tcg_audit_trail("dbo_users",$user_id,'update','Update password','','');
	// 	}

	// 	return $retval;
	// }

	// function tcg_resetpassword($user_id, $password)
	// {
	// 	$data = array(
	// 		'password' => md5($password),
	// 		'ganti_password' => 0,
	// 		'updated_on' => gmdate("Y/m/d")
	// 	);
        
	// 	$builder = $this->ro->table('dbo_pengguna');
	// 	$builder->where(array('user_id' => $user_id, 'is_deleted' => 0));
	// 	$retval = $builder->update($data);

	// 	if ($retval > 0) {
	// 		//put in audit trail
	// 		$this->tcg_audit_trail("dbo_users",$user_id,'update','Update password','','');
	// 	}

	// 	return $retval;
	// }

    // //dipakai untuk registrasi siswa
	// function tcg_audit_trail($table, $reference, $action, $description, $old_values, $new_values) {
	// 	$user_id = $this->session->get("user_id");

	// 	$query = "CALL usp_audit_trail(?,?,?,?,?,?,?,?)";
	// 	return $this->ro->query($query, array($table,$reference,$action,$user_id,$description,null,$new_values,$old_values));
	// }

    //dipakai untuk registrasi sisw
	function tcg_sekolah_baru($nama_sekolah,$kode_wilayah,$bentuk,$npsn,$status,$dapodik_id,$alamat) {
		$uuid = $this->uuid();

        //get data wilayah
        $sql = "select * from ref_wilayah where kode_wilayah=? and is_deleted=0";
        $wilayah = $this->ro->query($sql, array($kode_wilayah))->getRowArray();
        if ($wilayah == null)   return '';

		$valuepair = array (
			"nama" => $nama_sekolah,
			"kode_wilayah" => $kode_wilayah,
            "kecamatan" => $wilayah['nama_kec'],
            "kode_wilayah_kec" => $wilayah['kode_wilayah_kec'],
            "kabupaten" => $wilayah['nama_kab'],
            "kode_wilayah_kab" => $wilayah['kode_wilayah_kab'],
			"bentuk" => $bentuk,
			"npsn" => $npsn,
			"alamat_jalan" => $alamat,
            "dapodik_id" => $dapodik_id,
			"status" => $status,
			"created_by" => "1"
		);

		$builder = $this->db->table('ref_sekolah');
        if (!$builder->insert($valuepair)) return null;

        return $this->db->insertID();
	}

	function tcg_job_peringkatpendaftaran() {
		$query = "select next_execution, last_execution_end from dbo_jobs where name='proses_peringkat_pendaftaran'";
		return $this->ro->query($query)->getRowArray();
	}

	function tcg_rekapitulasi_sekolah() {
		$sql = "select * from rpt_rekapitulasi_sekolah_publik where tahun_ajaran_id=? and putaran=? order by nama asc";

		return $this->ro->query($sql, array($this->tahun_ajaran_id, $this->putaran))->getResultArray();	
	}    

    function tcg_kode_wilayah($nama_prov, $nama_kab, $nama_kec, $nama_desa) {
        $sql = "select kode_wilayah from ref_wilayah where nama_prov=? and nama_kab=? and nama_kec=? and nama_desa=? and is_deleted=0";

        $result = $this->ro->query($sql, array($nama_prov, $nama_kab, $nama_kec, $nama_desa))->getRowArray();
        if ($result == null) return null;

        return $result['kode_wilayah'];
    }

	function tcg_profilsekolah_from_npsn($npsn, $putaran=0){
        if ($putaran == 0) {
            $putaran = $this->session->get('putaran_aktif');
        }

		$builder = $this->ro->table('ref_sekolah a');
		$builder->select('a.sekolah_id,a.npsn,a.nama,a.bentuk as bentuk_pendidikan,a.bentuk,a.status,a.alamat_jalan,a.desa_kelurahan,a.kecamatan,a.kabupaten,a.lintang,a.bujur,a.inklusi');
        $builder->select('a.dapodik_id');
        $builder->select('coalesce(b.ikut_ppdb,0) as ikut_ppdb, coalesce(b.kuota_total,0) as kuota_total');
		$builder->join('cfg_kuota_sekolah b',"b.sekolah_id = a.sekolah_id and b.is_deleted=0 and b.tahun_ajaran_id='$this->tahun_ajaran_id' and b.putaran='$putaran'",'LEFT OUTER');
		$builder->where(array('a.npsn'=>$npsn, 'a.is_deleted'=>0));

		return $builder->get()->getRowArray();
	}

	function tcg_sekolahid_from_npsn($npsn){
		$builder = $this->ro->table('ref_sekolah a');
		$builder->select('a.sekolah_id');
        $builder->select('a.dapodik_id');
		$builder->where(array('a.npsn'=>$npsn, 'a.is_deleted'=>0));

		$result = $builder->get()->getRowArray();
        if ($result == null)    return 0;
        
        return $result['sekolah_id'];
	}

	function uuid(){
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); 
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); 
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}    
}