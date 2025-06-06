<?php 
namespace App\Models\Ppdb\Sekolah;

use App\Libraries\AuditTrail;

Class Mprofilsekolah 
{
    protected $db;
    protected $ro;
    protected $session;
    protected $audittrail;
    
    protected $tahun_ajaran_id;
    protected $error_message;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->ro = \Config\Database::connect('ro');
        $this->session = \Config\Services::session();
        $this->tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");

        $this->audittrail = new \App\Libraries\AuditTrail();

        helper("functions");
    }

    function get_error_message() {
        return $this->error_message;
    }

    // --

	function tcg_profilsekolah($sekolah_id, $putaran=0){
        if ($putaran == 0) {
            $putaran = $this->session->get('putaran_aktif');
        }

        $subquery = "
            select a.tahun_ajaran_id, a.sekolah_id,
                    sum(a.kuota_total) as kuota_total, max(ikut_ppdb) as ikut_ppdb
            from cfg_kuota_sekolah a
            where a.tahun_ajaran_id=" .secure($this->tahun_ajaran_id). "
            group by a.tahun_ajaran_id, a.sekolah_id";

		$builder = $this->ro->table('ref_sekolah a');
		$builder->select('a.sekolah_id,a.npsn,a.nama,a.bentuk as bentuk_pendidikan,a.bentuk,a.status,a.alamat_jalan,a.desa_kelurahan,a.kecamatan,a.kabupaten,a.lintang,a.bujur,a.inklusi');
        $builder->select('a.dapodik_id');
        $builder->select('coalesce(b.ikut_ppdb,0) as ikut_ppdb, coalesce(b.kuota_total,0) as kuota_total');
        $builder->join("($subquery) as b", "b.sekolah_id = a.sekolah_id",'LEFT OUTER');
		//$builder->join('cfg_kuota_sekolah b',"b.sekolah_id = a.sekolah_id and b.is_deleted=0 and b.tahun_ajaran_id='$this->tahun_ajaran_id' and b.putaran='$putaran'",'LEFT OUTER');
		$builder->where(array('a.sekolah_id'=>$sekolah_id, 'a.is_deleted'=>0));

		return $builder->get()->getRowArray();
	}

	function tcg_cabutberkas($peserta_didik_id, $keterangan)
	{
        $this->error_message = null;

		$pengguna_id = $this->session->get("user_id");

		$sql = "CALL " .SQL_CABUT_BERKAS. " (?,?,?)";
        $status = $this->db->query($sql, array($peserta_didik_id,$keterangan,$pengguna_id));
        if (!$status) return 0;

        $message = $status->getRowArray();
        if ($message != null) {
            $this->error_message = $message['message'];
            return 0;
        }

        //audit trail -> di sp

        return 1;
    }

	function tcg_pengajuan_akun(){
		// $pengguna_id = $_GET["pengguna_id"] ?? null; 

		$builder = $this->ro->table('dbo_users a');
		$builder->select('a.user_id, a.user_name,a.peserta_didik_id,b.sekolah_id,c.npsn,c.nama AS sekolah,b.nik,b.nisn,b.nomor_ujian,b.nama,
                            b.jenis_kelamin,b.tempat_lahir,b.tanggal_lahir,b.nama_ibu_kandung,b.kebutuhan_khusus,b.alamat,
                            d.nama_desa AS desa_kelurahan,d.nama_kec AS kecamatan,d.nama_kab AS kabupaten,d.nama_prov AS provinsi,b.lintang,b.bujur,a.approval');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_sekolah c','b.sekolah_id = c.sekolah_id and c.is_deleted=0','LEFT OUTER');
		$builder->join('ref_wilayah d','b.kode_wilayah = d.kode_wilayah AND d.is_deleted=0','LEFT OUTER');
		$builder->where(array('a.approval'=>0,'a.role_id'=>ROLEID_SISWA,'a.is_deleted'=>0));

        // $sql = $builder->getCompiledSelect();
        // echo $sql; exit;

		return $builder->get()->getResultArray();
	}

	function tcg_approve_akun($user_id, $approval = 1)
	{
        $approving_id = $this->session->get('user_id');

		$data = array(
			'approval' => $approval,
			'updated_on' => gmdate("Y/m/d h:m:s"),
            'updated_by' => $approving_id
		);

		$builder = $this->db->table('dbo_users');
		$builder->where(array('user_id'=>$user_id,'role_id'=>ROLEID_SISWA,'is_deleted'=>0));
		$status = $builder->update($data);
        if ($status == null)    return 0;

        //audit trail
        $this->audittrail->trail("dbo_user", $user_id, "UPDATE", "Approve akun");

        return 1;
	}

    function tcg_daftarpenerapan($sekolah_id){
		$putaran = $this->session->get('putaran_aktif');

		$query = "
		select a.penerapan_id,c.jalur_id,a.nama AS jalur,a.tooltip,d.kuota, coalesce(e.tambahan_kuota,0) as tambahan_kuota, 
			   coalesce(e.memenuhi_syarat,0) as memenuhi_syarat, coalesce(e.masuk_kuota,0) as masuk_kuota, coalesce(e.daftar_tunggu,0) as daftar_tunggu, 
               coalesce(e.diterima,0) as diterima,  coalesce(e.total_pendaftar,0) as total_pendaftar,
			   ((d.kuota + coalesce(e.tambahan_kuota,0)) - coalesce(e.masuk_kuota,0)) as sisa_kuota
		from cfg_penerapan a
		join ref_jalur c on a.jalur_id = c.jalur_id AND c.is_deleted=0
		join cfg_penerapan_sekolah d on a.penerapan_id = d.penerapan_id AND a.tahun_ajaran_id=d.tahun_ajaran_id AND d.is_deleted = 0
		left outer join rpt_sekolah_summary e on a.penerapan_id = e.penerapan_id AND a.tahun_ajaran_id=e.tahun_ajaran_id AND e.putaran=a.putaran AND e.sekolah_id = d.sekolah_id
        join ref_sekolah f on f.sekolah_id=d.sekolah_id and ((f.inklusi=1 and a.jalur_id=7) or a.jalur_id != 7)
		where a.aktif=1 and a.is_deleted=0 and a.perankingan=1 and d.sekolah_id=? and a.tahun_ajaran_id=? and a.putaran=?
		order by a.urutan";

		return $this->ro->query($query, array($sekolah_id, $this->tahun_ajaran_id, $putaran))->getResultArray();
	}

	function tcg_daftarkuota($sekolah_id){
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->ro->table('cfg_penerapan_sekolah a');
		$builder->select('c.jalur_id,c.nama AS jalur,a.kuota,b.nama as penerapan');
		$builder->join('cfg_penerapan b','a.penerapan_id = b.penerapan_id AND b.aktif = 1 AND b.is_deleted=0 and b.perankingan=1');
		$builder->join('ref_jalur c','b.jalur_id = c.jalur_id AND c.is_deleted=0');
		$builder->where(array('a.sekolah_id'=>$sekolah_id,'a.tahun_ajaran_id'=>$this->tahun_ajaran_id,'a.putaran'=>$putaran,'a.is_deleted'=>0));
		$builder->orderBy('b.urutan');
		return $builder->get()->getResultArray();
	}

	function tcg_daftarpendaftaran($sekolah_id, $filters = null, $orderby = null){
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->ro->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,a.nomor_pendaftaran,a.kelengkapan_berkas,a.created_on');
        $builder->select('b.jenis_kelamin, b.tanggal_lahir, b.terakhir_verifikasi_timestamp as tanggal_verifikasi, k.nama as verifikasi_oleh');
		$builder->select('b.nisn,b.nama,a.jenis_pilihan,b.lintang,b.bujur,a.status_penerimaan,a.masuk_jenis_pilihan,a.status_penerimaan_final,a.skor,a.peringkat,a.peringkat_final');
		$builder->select('b.nilai_kelulusan,coalesce(b.nilai_un,0) as nilai_usbn, a.status_daftar_ulang');
        $builder->select('e.nama AS sekolah_asal,f.nama AS lokasi_berkas,g.keterangan as label_masuk_pilihan,h.keterangan as label_jenis_pilihan,i.nama as sedang_verifikasi');
        $builder->select('d.jalur_id,d.nama AS jalur, b.nomor_kontak');
        $builder->select('case when a.status_daftar_ulang = 1 then a.tanggal_daftar_ulang else NULL end as tanggal_daftar_ulang', false);
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('cfg_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1 and c.tahun_ajaran_id=a.tahun_ajaran_id and c.putaran=a.putaran AND c.is_deleted=0','LEFT OUTER');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.is_deleted=0','LEFT OUTER');
		$builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
		$builder->join('ref_sekolah f','b.lokasi_berkas = f.sekolah_id','LEFT OUTER');
		$builder->join('cfg_jenis_pilihan g','a.masuk_jenis_pilihan = g.jenis_pilihan and g.tahun_ajaran_id=a.tahun_ajaran_id and g.putaran=a.putaran AND g.is_deleted=0','LEFT OUTER');
		$builder->join('cfg_jenis_pilihan h','a.jenis_pilihan = h.jenis_pilihan and h.tahun_ajaran_id=a.tahun_ajaran_id and h.putaran=a.putaran AND h.is_deleted=0','LEFT OUTER');
        $builder->join('dbo_users i','i.user_id = b.sedang_verifikasi_oleh and i.is_deleted = 0','LEFT OUTER');		
        $builder->join('dbo_users k','k.user_id = b.terakhir_verifikasi_oleh and k.is_deleted = 0','LEFT OUTER');		
        $builder->where(array('a.cabut_berkas'=>0,'a.jenis_pilihan !='=>0,'a.is_deleted'=>0));
        $builder->where('a.tahun_ajaran_id', $this->tahun_ajaran_id);

        if (!empty($sekolah_id)) {
            $builder->where('a.sekolah_id', $sekolah_id);
        }

        //additional filters
        if ($filters!=null) {
            $builder->where($filters);
        }

        //can override putaran-aktif untuk jenjang SD
        if (empty($filters) || empty($filters['a.putaran'])) {
            $builder->where('a.putaran', $putaran);
        }

        // $sql = $builder->getCompiledSelect();
        // echo $sql; exit;

        if (empty($orderby)) {
            $builder->orderBy('a.created_on');
        }
        else {
            $builder->orderBy($orderby);
        }

		return $builder->get()->getResultArray();
 	}

	function tcg_pendaftaran_penerapanid($sekolah_id, $penerapan_id){

        $filters = array("a.penerapan_id"=>$penerapan_id);
        $orderby = "a.skor desc";
        return $this->tcg_daftarpendaftaran($sekolah_id, $filters, $orderby);
	}

	function tcg_pendaftaran_detail($sekolah_id, $pendaftaran_id){

        $filters = array("a.pendaftaran_id"=>$pendaftaran_id);
        $orderby = "a.skor desc";
        return $this->tcg_daftarpendaftaran($sekolah_id, $filters, $orderby);
	}

	function tcg_kandidatswasta($sekolah_id) {

        $sql = "select
					a.peserta_didik_id, b.nama, RPAD(SUBSTR(b.nisn, 1, 6), Length(b.nisn), '*') as nisn, c.nama as sekolah, 
					concat(d.nama_desa, ' ', d.nama_kec, ' ', d.nama_kab) as alamat, a.jarak
				from rpt_kandidat_swasta a
				join tcg_peserta_didik b on b.peserta_didik_id=a.peserta_didik_id and b.is_deleted=0
				left join ref_sekolah c on c.sekolah_id=b.sekolah_id and c.is_deleted=0
				join ref_wilayah d on d.kode_wilayah=b.kode_wilayah and d.is_deleted=0
				where a.sekolah_id=? and a.tahun_ajaran_id=?
		";

		return $this->ro->query($sql, array($sekolah_id, $this->tahun_ajaran_id));
	}

	function tcg_pendaftarditerima($sekolah_id, $penerapan_id){

        $filters = array("a.penerapan_id"=>$penerapan_id);
        $results = $this->tcg_daftarpendaftaran($sekolah_id, $filters);

        foreach($results as $k => $v) {
            if ($v['status_penerimaan_final'] != 1 && $v['status_penerimaan_final'] != 3) {
                unset($results[$k]);
            }
        }

        return $results;
    }

	function tcg_pendaftarditerima_all($sekolah_id){
		$builder = $this->ro->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,a.nomor_pendaftaran,a.kelengkapan_berkas,a.created_on');
        $builder->select('b.jenis_kelamin, b.tanggal_lahir, b.terakhir_verifikasi_timestamp as tanggal_verifikasi, k.nama as verifikasi_oleh');
		$builder->select('b.nisn,b.nama,a.jenis_pilihan,b.lintang,b.bujur,a.status_penerimaan,a.masuk_jenis_pilihan,a.status_penerimaan_final,a.skor,a.peringkat,a.peringkat_final');
		$builder->select('b.nilai_kelulusan,b.nilai_semester,coalesce(b.nilai_un,0) as nilai_usbn, a.status_daftar_ulang');
        $builder->select('e.nama AS sekolah_asal,f.nama AS lokasi_berkas,g.keterangan as label_masuk_pilihan,h.keterangan as label_jenis_pilihan,i.nama as sedang_verifikasi');
        $builder->select('d.jalur_id,d.nama AS jalur, b.nomor_kontak');
        $builder->select('case when a.status_daftar_ulang = 1 then a.tanggal_daftar_ulang else NULL end as tanggal_daftar_ulang', false);
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('cfg_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1 and c.tahun_ajaran_id=a.tahun_ajaran_id and c.putaran=a.putaran AND c.is_deleted=0','LEFT OUTER');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.is_deleted=0','LEFT OUTER');
		$builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
		$builder->join('ref_sekolah f','b.lokasi_berkas = f.sekolah_id','LEFT OUTER');
		$builder->join('cfg_jenis_pilihan g','a.masuk_jenis_pilihan = g.jenis_pilihan and g.tahun_ajaran_id=a.tahun_ajaran_id and g.putaran=a.putaran AND g.is_deleted=0','LEFT OUTER');
		$builder->join('cfg_jenis_pilihan h','a.jenis_pilihan = h.jenis_pilihan and h.tahun_ajaran_id=a.tahun_ajaran_id and h.putaran=a.putaran AND h.is_deleted=0','LEFT OUTER');
        $builder->join('dbo_users i','i.user_id = b.sedang_verifikasi_oleh and i.is_deleted = 0','LEFT OUTER');		
        $builder->join('dbo_users k','k.user_id = b.terakhir_verifikasi_oleh and k.is_deleted = 0','LEFT OUTER');		
        $builder->where(array('a.cabut_berkas'=>0,'a.jenis_pilihan !='=>0,'a.is_deleted'=>0));
        $builder->where('a.tahun_ajaran_id', $this->tahun_ajaran_id);

        if (!empty($sekolah_id)) {
            $builder->where('a.sekolah_id', $sekolah_id);
        }

        //diterima
        $builder->where("status_penerimaan_final in (1,3)");

        // $sql = $builder->getCompiledSelect();
        // echo $sql; exit;

        if (empty($orderby)) {
            $builder->orderBy('a.created_on');
        }
        else {
            $builder->orderBy($orderby);
        }

		return $builder->get()->getResultArray();
 	}

	function tcg_pendaftarbelumdiverifikasi($sekolah_id){
        
        $filters = array('a.kelengkapan_berkas'=>0,'a.pendaftaran'=>1);
        return $this->tcg_daftarpendaftaran($sekolah_id, $filters);
    }

	function tcg_pendaftarbelumlengkap($sekolah_id){
        
        $filters = array('a.kelengkapan_berkas'=>2,'a.pendaftaran'=>1);
        return $this->tcg_daftarpendaftaran($sekolah_id, $filters);
    }

	function tcg_pendaftarsudahlengkap($sekolah_id){
        
        $filters = array('a.kelengkapan_berkas'=>1,'a.pendaftaran'=>1);
        return $this->tcg_daftarpendaftaran($sekolah_id, $filters);
    }

	function tcg_berkasdisekolah($sekolah_id){
        
        $filters = array('b.lokasi_berkas'=>$sekolah_id,'a.pendaftaran'=>1);
        $result = $this->tcg_daftarpendaftaran(null, $filters);

        //filter unique peserta_didik
        $lookup = array();
        $arr = array();
        foreach($result as $row) {
            if (array_search($row['peserta_didik_id'], $lookup) !== FALSE)  continue;
            $arr[] = $row;
            $lookup[] = $row['peserta_didik_id'];
        }

        return $arr;
	}  

    function tcg_cari_siswa($query, $jenjang = null, $asaldata = null, $inklusi = null, $afirmasi = null, $search_columns = null) {
        $limit = 1000;;
        $offset = 0;

        if (empty($query))  return null;

        if ($search_columns == null) {
            //default searched columns
            $search_columns = ['nama', 'nisn', 'nik', 'alamat'];
        }

        $builder = $this->ro->table('tcg_peserta_didik a');

        //group search filter
        //use specified list of columns
        $builder->groupStart();
        foreach($search_columns as $key => $val) {
            $builder->orLike("a." .$val, $query);
        }
        $builder->groupEnd();

        $builder->where('a.is_deleted', 0);
        $builder->select("a.nama, a.nisn, a.nik, a.tanggal_lahir, a.tempat_lahir, a.jenis_kelamin, d.kelengkapan_berkas");
        $builder->select("b.nama as asal_sekolah");
        $builder->select("c.nama as lokasi_berkas");
        $builder->select("case when d.cnt=0 then 'Tidak Ada' 
                            when d.cnt>0 and e.masuk_jenis_pilihan is null then 'Tidak Masuk Kuota'
                            else e.keterangan end as status_pendaftaran");
        $builder->join("ref_sekolah b", "b.sekolah_id=a.sekolah_id and b.is_deleted=0", 'LEFT OUTER');
        $builder->join("ref_sekolah c", "c.sekolah_id=a.lokasi_berkas and c.is_deleted=0", 'LEFT OUTER');
       
        $subquery = "
        SELECT tahun_ajaran_id, peserta_didik_id, count(*) as cnt, max(kelengkapan_berkas) as kelengkapan_berkas 
        FROM tcg_pendaftaran 
        where is_deleted=0
        group by tahun_ajaran_id, peserta_didik_id";

        $builder->join("($subquery) as d", "d.tahun_ajaran_id=a.tahun_ajaran_id AND d.peserta_didik_id=a.peserta_didik_id", 'LEFT OUTER');

        $subquery = "
        select *
        from (
            select a.tahun_ajaran_id, a.peserta_didik_id, a.putaran, a.masuk_jenis_pilihan, b.keterangan,
                    row_number() over (partition by tahun_ajaran_id, peserta_didik_id order by pendaftaran_id) rn
            FROM tcg_pendaftaran a
            join cfg_jenis_pilihan b on b.jenis_pilihan=a.masuk_jenis_pilihan and b.tahun_ajaran_id=a.tahun_ajaran_id 
                and b.putaran=a.putaran and b.is_deleted=0
            where a.is_deleted=0 and a.masuk_jenis_pilihan!=0 
        ) a where a.rn=1
        ";

        $builder->join("($subquery) as e", "e.tahun_ajaran_id=a.tahun_ajaran_id AND e.peserta_didik_id=a.peserta_didik_id", 'LEFT OUTER');


        if (!empty($jenjang)) $builder->where("b.bentuk", $jenjang);
        if (!empty($asaldata)) $builder->where("a.asal_data", $asaldata);
        if ($inklusi != null && $inklusi != '') {
            if ($inklusi == 1) {
                $builder->where("a.kebutuhan_khusus!='Tidak ada'");
            }
            else {
                $builder->where("a.kebutuhan_khusus='Tidak ada'");
            }
        }
        if ($afirmasi != null && $afirmasi != '') {
            if ($afirmasi == 1) {
                $builder->groupStart();
                $builder->where("a.punya_kip",1);
                $builder->orWhere("a.masuk_bdt",1);
                $builder->groupEnd();
            }
            else {
                $builder->where("a.punya_kip",0);
                $builder->where("a.masuk_bdt",0);
            }
        }

        $arr = $builder->get($limit, $offset)->getResultArray();
        if ($arr == null)       return $arr;

        return $arr;
    }

	function tcg_touch_verifikasi($peserta_didik_id, $aktif=1) {
        $pengguna_id = $this->session->get('user_id');
        
		$valuepair = array();
		if ($aktif == 1) {
			$valuepair = array (
				'sedang_verifikasi_oleh' => $pengguna_id,
				'sedang_verifikasi_timestamp' => gmdate("Y/m/d H:i:s")
			);
		}
		else {
			$valuepair = array (
				'sedang_verifikasi_oleh' => null,
				'sedang_verifikasi_timestamp' => null
			);
		}

		$filter = array(
			'peserta_didik_id' => $peserta_didik_id,
			'is_deleted' => 0,
			'cabut_berkas' => 0
		);

        $builder = $this->db->table('tcg_peserta_didik');
        $builder->where($filter);
		$query = $builder->update($valuepair);
		
		return 1;
	} 
      
	function tcg_pesertadidikid_from_pendaftaranid($pendaftaran_id) {

		$builder = $this->ro->table('tcg_pendaftaran');
		$builder->select('peserta_didik_id');
		$builder->where(array('pendaftaran_id'=>$pendaftaran_id,'is_deleted'=>0));

		$peserta_didik_id = "";
		foreach($builder->get()->getResult() as $row):
			$peserta_didik_id = $row->peserta_didik_id;
		endforeach;

		return $peserta_didik_id;
	}
	
	function tcg_userid_from_pesertadidikid($peserta_didik_id) {

		$builder = $this->ro->table('dbo_users');
		$builder->select('user_id');
		$builder->where(array('peserta_didik_id'=>$peserta_didik_id,'is_deleted'=>0));

		$user_id = "";
		foreach($builder->get()->getResult() as $row):
			$user_id = $row->user_id;
		endforeach;

		return $user_id;
	}
	
    function tcg_ubah_profil($key, $valuepair) {
		//inject last update
		$valuepair['updated_on'] = gmdate("Y/m/d H:i:s");

		$builder = $this->db->table('ref_sekolah');
        $builder->where('sekolah_id', $key);
        $status = $builder->update($valuepair);
        if (!$status)   return 0;

        //put in audit trail
        $this->audittrail->trail("ref_sekolah",$key,'UPDATE','Update profil sekolah',array_keys($valuepair),$valuepair);

        return 1;
    }

	// function tcg_audit_trail($table, $reference, $action, $description, $keys, $new_values=null, $old_values=null) {
    //     $user_id = $this->session->get('user_id');
        
    //     $valuepair = array (
    //         'module' => 'ppdb',
    //         'table_name' => $table,
    //         'reference_id' => $reference,
    //         'action_name' => $action,
    //         'long_description' => $description,
    //         'col_names' => $keys,
    //         'col_values' => $new_values,
    //         'old_values' => $old_values,
    //         'created_by' => $user_id
    //     );

    //     $builder = $this->db->table('dbo_audit_trails');
    //     $builder->insert($valuepair);
	// }

	function tcg_buat_verifikasidinas($peserta_didik_id, $tipe_data, $kelengkapan_id, $catatan) {
		$pengguna_id = $this->session->get('user_id');
		$sekolah_id = $this->session->get('sekolah_id');

		$valuepair = array(
			'peserta_didik_id' 	=> $peserta_didik_id,
			'tahun_ajaran_id'	=> $this->tahun_ajaran_id,
			'pengguna_sekolah'	=> $pengguna_id,
			'sekolah_id'		=> $sekolah_id,
			'tipe_data'			=> $tipe_data,
			'daftar_kelengkapan_id'	=> $kelengkapan_id,
			'catatan_sekolah'	=> $catatan
		);

        $builder = $this->db->table('tcg_verifikasi_dinas');
		$query = $builder->insert($valuepair);
		if (!$query) return 0;

        $key = $this->db->insertID();

        //audit trail
        $valuepair['verifikasi_id'] = $key;
        $this->audittrail->trail("tcg_verifikasi_dinas",$peserta_didik_id,"INSERT", "Verifikasi dinas baru", array_keys($valuepair), $valuepair);

        return $key;
	}

	function tcg_penerimaan_sd($sekolah_id, $penerapan_id, $filter=null) {

        $builder = $this->ro->table("tcg_pendaftaran a");
        $builder->select("b.peserta_didik_id, b.nama, b.nisn, b.nik, b.tempat_lahir, b.tanggal_lahir, b.nama_ibu_kandung, b.jenis_kelamin, d.sekolah_id, d.nama as sekolah");
        $builder->select("a.pendaftaran_id, a.status_penerimaan_final, a.peringkat_final, a.skor, a.penerapan_id, e.jalur_id, e.nama as jalur");
        $builder->select("coalesce(b.npsn_sekolah_asal, c.npsn) as npsn_sekolah_asal, coalesce(b.nama_sekolah_asal, c.nama) as nama_sekolah_asal");
        $builder->select("row_number() over (order by b.nama) rn");
        $builder->join("tcg_peserta_didik b", "b.peserta_didik_id=a.peserta_didik_id and b.is_deleted=0");
        $builder->join("ref_sekolah c", "c.sekolah_id=b.sekolah_id and c.is_deleted=0", 'LEFT OUTER');
        $builder->join("ref_sekolah d", "d.sekolah_id=a.sekolah_id and d.is_deleted=0", 'LEFT OUTER');
        $builder->join("cfg_penerapan e", "e.penerapan_id=a.penerapan_id and e.is_deleted=0", 'LEFT OUTER');
        $builder->where("a.is_deleted=0");

        if (!empty($sekolah_id)) {
            $builder->where("a.sekolah_id", $sekolah_id);
        }

        if (!empty($penerapan_id)) {
            $builder->where("a.penerapan_id", $penerapan_id);
        }

        if (!empty($filter)) {
            $builder->where($filter);
        }

        return $builder->get()->getResultArray();

        // // return $result;
        // $sql = "select b.peserta_didik_id, b.nama, b.nisn, b.nik, 
        //                 coalesce(b.npsn_sekolah_asal, c.npsn) as npsn_sekolah_asal, coalesce(b.nama_sekolah_asal, c.nama) as nama_sekolah_asal,
        //                 b.tempat_lahir, b.tanggal_lahir, b.nama_ibu_kandung, b.jenis_kelamin, c.sekolah_id, c.nama as sekolah
		// 		  from tcg_pendaftaran a
		// 		  join tcg_peserta_didik b on b.peserta_didik_id=a.peserta_didik_id
		// 		  left join ref_sekolah c on c.sekolah_id=b.sekolah_id
		// 		  where a.is_deleted=0 and a.status_penerimaan_final=1 and a.sekolah_id=?";

		// return $this->ro->query($sql, array($sekolah_id))->getResultArray();
	}

	function tcg_penerimaan_sd_siswa($peserta_didik_id) {
        $filter = array( "a.peserta_didik_id" => $peserta_didik_id);

        $result = $this->tcg_penerimaan_sd(null, null, $filter);
        if($result == null) return null;

        return $result[0];

        // $sql = "select b.peserta_didik_id, b.nama, b.nisn, b.nik, 
        //                 coalesce(b.npsn_sekolah_asal, c.npsn) as npsn_sekolah_asal, coalesce(b.nama_sekolah_asal, c.nama) as nama_sekolah_asal,
		// 			    b.tempat_lahir, b.tanggal_lahir, b.nama_ibu_kandung, b.jenis_kelamin, c.sekolah_id, c.nama as sekolah
		// 		  from tcg_pendaftaran a
		// 		  join tcg_peserta_didik b on b.peserta_didik_id=a.peserta_didik_id
		// 		  left join ref_sekolah c on c.sekolah_id=b.sekolah_id
		// 		  where a.is_deleted=0 and a.status_penerimaan_final=1 and a.peserta_didik_id=?";

		// return $this->ro->query($sql, array($peserta_didik_id))->getRowArray();
	}

	function tcg_penerimaan_sd_detil($sekolah_id, $pendaftaran_id) {
        $filter = array( "a.pendaftaran_id" => $pendaftaran_id);

        $result = $this->tcg_penerimaan_sd($sekolah_id, null, $filter);
        if($result == null) return null;

        return $result[0];
	}

    function tcg_ubahjalur_sd($pendaftaran_id, $penerapan_id) {
		//inject last update
        $valuepair = array();
        $valuepair['penerapan_id'] = $penerapan_id;
		$valuepair['updated_on'] = gmdate("Y/m/d H:i:s");

		$builder = $this->db->table('tcg_pendaftaran');
        $builder->where('pendaftaran_id', $pendaftaran_id);
        $status = $builder->update($valuepair);
        if (!$status)   return 0;

        //put in audit trail
        $this->audittrail->trail("tcg_pendaftaran",$pendaftaran_id,'UPDATE','Update jalur pendaftaran SD',array_keys($valuepair),$valuepair);

        $filter = array( "a.pendaftaran_id" => $pendaftaran_id);

        $result = $this->tcg_penerimaan_sd(null, null, $filter);
        if($result == null) return null;

        return $result[0];
    }

	function tcg_calon_pesertadidik_sd($nama, $nisn, $sekolah_id, $nik, $limit=1000){
		$filter = 0;
		$query = "select a.peserta_didik_id, a.nama, a.nisn, a.nik, a.tempat_lahir, a.tanggal_lahir,
					  b.kode_wilayah_desa, b.nama_desa, b.kode_wilayah_kec, b.nama_kec, c.nama as sekolah,
					  e.nama as diterima_sekolah, a.jenis_kelamin, null as penerapan_id
				  from tcg_peserta_didik a
				  left join ref_wilayah b on a.kode_wilayah=b.kode_wilayah and b.is_deleted=0
				  left join ref_sekolah c on c.sekolah_id=a.sekolah_id and c.is_deleted=0
				  left join tcg_pendaftaran d on d.peserta_didik_id=a.peserta_didik_id and d.status_penerimaan_final=1 and d.is_deleted=0
				  left join ref_sekolah e on e.sekolah_id=d.sekolah_id and e.is_deleted=0 
				  ";

		$where = "a.is_deleted=0 and (a.jenjang in ('TK', 'RA', 'PAUD', 'KB', 'SPS', 'SKB', 'TPA') or a.asal_data=" .ASALDATA_PENERIMAANSD. ")";
		if (!empty($nama)) {
			$filter = 1;
			$where .= " AND a.nama like '%" . $nama . "%'";
		}
		if (!empty($nisn)) {
			$filter = 1;
			$where .= " AND a.nisn='" . $nisn . "'";
		}
		if (!empty($sekolah_id)) {
			$filter = 1;
			$where .= " AND a.sekolah_id='" . $sekolah_id . "'";
		}
		if (!empty($nik)) {
			$filter = 1;
			$where .= " AND a.nik='" . $nik . "'";
		}
		// if (!empty($jenis_kelamin)) {
		// 	$filter = 1;
		// 	$where .= " AND a.jenis_kelamin='" . $jenis_kelamin . "'";
		// }
		// if (!empty($kode_kecamatan)) {
		// 	$filter = 1;
		// 	$where .= " AND a.kode_kecamatan=" . $kode_kecamatan;
		// }
		// if (!empty($kode_desa)) {
		// 	$filter = 1;
		// 	$where .= " AND a.kode_desa=" . $kode_desa;
		// }

		// if ($filter == 0) {
		// 	//no aktif filter! dont show anything
		// 	$where .= " AND 1=0";
		// }

		$query .= " WHERE " . $where;
        $query .= " limit " . $limit;

        //echo $query; exit;

		return $this->ro->query($query)->getResultArray();	
	}

    function tcg_tambah_pesertadidik_sd($valuepair) {
        $this->error_message = '';

        if (empty($valuepair)) {
            $this->error_message = "Data siswa baru tidak lengkap";
            return 0;
        }

        $dapodik_id = uuid();
        $valuepair['dapodik_id'] = $dapodik_id;
        $valuepair['created_by'] = $this->session->get('user_id');

        //enforce asaldata
        $valuepair['asal_data'] = ASALDATA_PENERIMAANSD;

        $builder = $this->db->table("tcg_peserta_didik");
        $builder->insert($valuepair);

        $peserta_didik_id = $this->db->insertID();

        //audit trail
        $audittrail = new AuditTrail();
        $audittrail->insert("tcg_peserta_didik", $peserta_didik_id, $valuepair);

        return $peserta_didik_id;
    }

    // function tcg_tambah_pesertadidik_sd($valuepair) {
    //     $this->error_message = '';

    //     if (empty($valuepair)) {
    //         $this->error_message = "Data siswa baru tidak lengkap";
    //         return 0;
    //     }

    //     $values = array();
    //     $values[] = $valuepair['sekolah_id']; 
    //     $values[] = $valuepair['nama']; 
    //     $values[] = empty($valuepair['jenis_kelamin']) ? null : $valuepair['jenis_kelamin']; 
    //     $values[] = empty($valuepair['nisn']) ? null : $valuepair['nisn']; 
    //     $values[] = empty($valuepair['nik']) ? null : $valuepair['nik']; 
    //     $values[] = empty($valuepair['tempat_lahir']) ? null : $valuepair['tempat_lahir']; 
    //     $values[] = empty($valuepair['tanggal_lahir']) ? null : $valuepair['tanggal_lahir'];  
    //     $values[] = empty($valuepair['nama_ibu_kandung']) ? null : $valuepair['nama_ibu_kandung'];  
    //     $values[] = empty($valuepair['nama_ayah']) ? null : $valuepair['nama_ayah'];  
    //     $values[] = empty($valuepair['npsn_sekolah_asal']) ? null : $valuepair['npsn_sekolah_asal'];  
    //     $values[] = empty($valuepair['nama_sekolah_asal']) ? null : $valuepair['nama_sekolah_asal'];  
    //     $dapodik_id = uuid();
    //     $values[] = $dapodik_id;
    //     $values[] = $this->session->get('user_id');

        
	// 	$sql = "call " .SQL_TAMBAH_SISWA_SD. "(?, ?, ?, ?, ?, ? , ?, ?, ?, ?, ?, ?, ?)";
	// 	$status = $this->db->query($sql, $values);
    //     if (!$status) return null;

    //     $message = $status->getResultArray();
    //     if ($message != null) {
    //         $this->error_message = $message['message'];
    //         return null;
    //     }

    //     //audit trail -> di sp
        
    //     $filter = array( "b.dapodik_id" => $dapodik_id);

    //     $result = $this->tcg_penerimaan_sd(null, $filter);
    //     if($result == null) return null;

    //     return $result[0];

    //     // $sql = "select b.peserta_didik_id, b.nama, b.nisn, b.nik,
    //     //                 coalesce(b.npsn_sekolah_asal, c.npsn) as npsn_sekolah_asal, coalesce(b.nama_sekolah_asal, c.nama) as nama_sekolah_asal,
    //     //                 b.tempat_lahir, b.tanggal_lahir, b.nama_ibu_kandung, b.jenis_kelamin, c.sekolah_id, c.nama as sekolah
	// 	// 		  from tcg_pendaftaran a
	// 	// 		  join tcg_peserta_didik b on b.peserta_didik_id=a.peserta_didik_id
	// 	// 		  left join ref_sekolah c on c.sekolah_id=b.sekolah_id
	// 	// 		  where a.is_deleted=0 and a.status_penerimaan_final=1 and b.dapodik_id=?";

	// 	// return $this->ro->query($sql, array($dapodik_id))->getRowArray();;
    // }

	function tcg_terima_pesertadidik_sd($sekolah_id, $peserta_didik_id, $penerapan_id) {
		$pengguna_id = $this->session->get("user_id");

		$sql = "call " .SQL_PENERIMAAN_SD. "(?, ?, ?, ?)";
		$status = $this->db->query($sql, array($sekolah_id, $penerapan_id, $peserta_didik_id, $pengguna_id));
        if (!$status) return null;

        $message = $status->getRowArray();
        if ($message != null) {
            $this->error_message = $message['message'];
            return null;
        }

        $filter = array( "a.peserta_didik_id" => $peserta_didik_id);

        $result = $this->tcg_penerimaan_sd($sekolah_id, $penerapan_id, $filter);
        if($result == null) return null;

        return $result[0];
	}

	function tcg_hapus_pesertadidik_sd($sekolah_id, $peserta_didik_id) {
		$pengguna_id = $this->session->get("user_id");

        $keterangan = "Hapus pendaftaran oleh sekolah";
		$sql = "call " .SQL_HAPUS_PENERIMAAN_SD. "(?, ?, ?, ?)";
		$status = $this->db->query($sql, array($sekolah_id, $peserta_didik_id, $keterangan, $pengguna_id));
        if (!$status) return 0;

        $message = $status->getRowArray();
        if ($message != null) {
            $this->error_message = $message['message'];
            return 0;
        }

        //audit trail -> di sp
        
		return 1;
	}    

	function tcg_daftar_siswa($sekolah_id) {
        $putaran = $this->session->get('putaran_aktif');

		//pass updated_on as UTC and convert on client side using moment.js
		$query = "SELECT a.sekolah_id, b.nama as sekolah, c.nama_desa as desa_kelurahan, 
						a.peserta_didik_id, a.nama, a.jenis_kelamin, a.nisn, a.nik, a.tempat_lahir, a.tanggal_lahir, a.nama_ibu_kandung, a.nama_ayah,
						a.kode_wilayah, a.rt, a.rw, a.alamat, a.nama_dusun, a.lintang, a.bujur, a.akses_ubah_data,
						a.updated_on, coalesce(d.cnt,0) as jml_pendaftaran
					FROM tcg_peserta_didik a
					join ref_sekolah b on a.sekolah_id=b.sekolah_id and a.is_deleted=0
                    left join ref_wilayah c on c.kode_wilayah=a.kode_wilayah and c.is_deleted=0
                    left join (
                        select peserta_didik_id, count(*) as cnt from tcg_pendaftaran 
                        where is_deleted=0 and cabut_berkas=0 and penerapan_id!=301 and tahun_ajaran_id=? and putaran=?
                        group by peserta_didik_id    
                    ) d on d.peserta_didik_id=a.peserta_didik_id
					where a.sekolah_id=? and a.tahun_ajaran_id=? and a.is_deleted=0 and a.asal_data!=" .ASALDATA_PENERIMAANSD;

		return $this->ro->query($query, array($this->tahun_ajaran_id, $putaran, $sekolah_id, $this->tahun_ajaran_id))->getResultArray();
	}

	function tcg_ubah_daftarulang($pendaftaran_id,$status,$pengguna_id) {
		$query = "
			update tcg_pendaftaran a
			set
				a.status_daftar_ulang = ?,
				a.tanggal_daftar_ulang = now(),
				a.daftar_ulang_oleh = ?,
				a.updated_on = now()
			where a.pendaftaran_id=?";

		$this->db->query($query, array($status, $pengguna_id, $pendaftaran_id));
		$affected = $this->db->affectedRows();

        //audittrail
        $peserta_didik_id = $this->tcg_pesertadidikid_from_pendaftaranid($pendaftaran_id);
        $keys = array('pendaftaran_id', 'status_daftar_ulang', 'daftar_ulang_oleh');
        $values = array('pendaftaran_id'=>$pendaftaran_id, 'status_daftar_ulang'=>$status, 'daftar_ulang_oleh'=>$pengguna_id);
        $this->audittrail->trail("tcg_pendaftaran",$peserta_didik_id,"UPDATE","Daftar ulang", $keys, $values);

        return $affected;
	}

	function tcg_nama_sekolah($sekolah_id){

		$builder = $this->ro->table('ref_sekolah a');
		$builder->select('a.nama');
		$builder->where(array('a.sekolah_id'=>$sekolah_id, 'a.is_deleted'=>0));

		$result = $builder->get()->getResultArray();
		if ($result == null) return '';

		$sekolah = $result[0]['nama'];
		return $sekolah;
	}

    function tcg_create_admin_sekolah() {
        $sql = "select a.*, b.sekolah_id from tmp_usulan_admin_sekolah a join ref_sekolah b on b.npsn=a.npsn and b.is_deleted=0";
        $result = $this->ro->query($sql)->getResultArray();

        //create users
        foreach($result as $row) {
            $email = trim($row['email']);
            $nama = trim($row['nama']);
            $password = trim($row['password']);
            //get existing entry
            $sql = "select * from dbo_users where user_name=? and role_id=12";
            $result = $this->ro->query($sql, array($email))->getRowArray();
            if (empty($result)) {
                //create new entry
                $user = array(
                    'user_name' => $email,
                    'email' => $email,
                    'nama' => $nama,
                    'role_id' => 12,
                    'sekolah_id' => $row['sekolah_id'],
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                );
                $builder = $this->db->table("dbo_users");
                $query = $builder->insert($user);
                //logging
                echo "User an. " .$nama. " created. <br>";
            }
            else if ($result['is_deleted'] == 1) {
                $updated = array(
                    'is_deleted' => 1,
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                );
                $builder = $this->db->table("dbo_users");
                $query = $builder->update($updated);
                //logging
                echo "User an. " .$nama. " updated. <br>";
            }
        }
    }

    // function tcg_kuota_sd($sekolah_id) {
    //     return 28;

	// 	// $builder = $this->ro->table('cfg_penerapan_sekolah a');
    //     // $builder->select("a.kuota");
	// 	// $builder->where(array('a.sekolah_id'=>$sekolah_id, 'a.is_deleted'=>0));
    //     // $builder->where("a.tahun_ajaran_id", $this->tahun_ajaran_id);
    //     // $builder->where("a.penerapan_id", PENERAPANID_SD);
    //     // $builder->where("a.putaran", PUTARAN_SD);

    //     // $result = $builder->get()->getRowArray();
    //     // if ($result == null)    return 0;

    //     // return $result['kuota'];
    // }

    // function tcg_totalpendaftaran_sd($sekolah_id) {
    //     return 0;

	// 	// $builder = $this->ro->table('tcg_pendaftaran a');
    //     // $builder->select("count(a.pendaftaran_id) as cnt");
	// 	// $builder->where(array('a.sekolah_id'=>$sekolah_id, 'a.is_deleted'=>0));
    //     // $builder->where("a.tahun_ajaran_id", $this->tahun_ajaran_id);
    //     // $builder->where("a.penerapan_id", PENERAPANID_SD);
    //     // $builder->where("a.putaran", PUTARAN_SD);

    //     // $result = $builder->get()->getRowArray();
    //     // if ($result == null)    return 0;

    //     // return $result['cnt'];
    // }
    
    // function tcg_cek_jalurafirmasi($penerapan_id) {
    //     //TODO
    //     return 0;
    // }

    function tcg_cek_dataafirmasi($nik) {
        $builder = $this->ro->table("cfg_data_afirmasi a");
        $builder->select("count(*) as cnt");
        $builder->where("a.nik", $nik);
        $builder->where("a.is_deleted=0");

        $results = $builder->get()->getResultArray();
        if (!$results) return 0;

        if ($results[0]['cnt']>0)   return 1;
        
        return 0;
    }
}