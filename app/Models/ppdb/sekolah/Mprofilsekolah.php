<?php 
namespace App\Models\Ppdb\Sekolah;

Class Mprofilsekolah 
{
    protected $db;
    protected $session;
    
    protected $tahun_ajaran_id;
    protected $error_message;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");

        helper("functions");
    }

    // --

	function tcg_profilsekolah($sekolah_id){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('ref_sekolah a');
		$builder->select('a.sekolah_id,a.npsn,a.nama,a.bentuk as bentuk_pendidikan,a.bentuk,a.status,a.alamat_jalan,a.desa_kelurahan,a.kecamatan,a.kabupaten,a.lintang,a.bujur,a.inklusi');
        $builder->select('a.sekolah_id_lama as dapodik_id');
        $builder->select('coalesce(b.ikut_ppdb,0) as ikut_ppdb, coalesce(b.kuota_total,0) as kuota_total');
		$builder->join('cfg_kuota_sekolah b',"b.sekolah_id = a.sekolah_id and b.is_deleted=0 and b.tahun_ajaran_id='$tahun_ajaran_id' and b.putaran='$putaran'",'LEFT OUTER');
		$builder->where(array('a.sekolah_id'=>$sekolah_id, 'a.is_deleted'=>0));

		return $builder->get()->getRowArray();
	}

	function tcg_cabutberkas($peserta_didik_id, $pendaftaran_id, $keterangan)
	{
        $this->error_message = null;

		$pengguna_id = $this->session->get("user_id");

		$sql = "CALL " .SQL_CABUT_BERKAS. " (?,?,?,?)";
        $status = $this->db->query($sql, array($peserta_didik_id,$pendaftaran_id,$keterangan,$pengguna_id));
        if (!$status) return 0;

        $message = $status->getRowArray();
        if ($message != null) {
            $this->error_message = $message['message'];
            return 0;
        }

        //TODO: audit trail!

        return 1;
    }

	function tcg_pengajuan_akun(){
		// $pengguna_id = $_GET["pengguna_id"] ?? null; 

		$builder = $this->db->table('dbo_users a');
		$builder->select('a.user_id, a.user_name,a.pengguna_id,b.sekolah_id,c.npsn,c.nama AS sekolah,b.nik,b.nisn,b.nomor_ujian,b.nama,
                            b.jenis_kelamin,b.tempat_lahir,b.tanggal_lahir,b.nama_ibu_kandung,b.kebutuhan_khusus,b.alamat,
                            d.nama_desa AS desa_kelurahan,d.nama_kec AS kecamatan,d.nama_kab AS kabupaten,d.nama_prov AS provinsi,b.lintang,b.bujur,a.approval');
		$builder->join('tcg_peserta_didik b','a.pengguna_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_sekolah c','b.sekolah_id = c.sekolah_id','LEFT OUTER');
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
			'updated_on' => date("Y/m/d h:m:s"),
            'updated_by' => $approving_id
		);

		$builder = $this->db->table('dbo_users');
		$builder->where(array('user_id'=>$user_id,'role_id'=>ROLEID_SISWA,'is_deleted'=>0));
		$status = $builder->update($data);
        if ($status == null)    return 0;

        //TODO: audit trail

        return 1;
	}

    function tcg_daftarpenerapan($sekolah_id){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "
		select a.penerapan_id,c.jalur_id,a.nama AS jalur,a.tooltip,d.kuota, coalesce(e.tambahan_kuota,0) as tambahan_kuota, 
			   coalesce(e.memenuhi_syarat,0) as memenuhi_syarat, coalesce(e.masuk_kuota,0) as masuk_kuota, coalesce(e.daftar_tunggu,0) as daftar_tunggu, coalesce(e.diterima,0) as diterima,  coalesce(e.total_pendaftar,0) as total_pendaftar
		from cfg_penerapan a
		join ref_jalur c on a.jalur_id = c.jalur_id AND c.is_deleted=0
		join cfg_penerapan_sekolah d on a.penerapan_id = d.penerapan_id AND a.tahun_ajaran_id=d.tahun_ajaran_id AND d.is_deleted = 0
		left outer join v_rpt_sekolah_summary e on a.penerapan_id = e.penerapan_id AND a.tahun_ajaran_id=e.tahun_ajaran_id AND e.sekolah_id = d.sekolah_id
        join ref_sekolah f on f.sekolah_id=d.sekolah_id and ((f.inklusi=1 and a.jalur_id=7) or a.jalur_id != 7)
		where a.aktif=1 and a.is_deleted=0 and a.perankingan=1 and d.sekolah_id=? and a.tahun_ajaran_id=? and a.putaran=?
		order by a.urutan";

		return $this->db->query($query, array($sekolah_id, $tahun_ajaran_id, $putaran))->getResultArray();
	}

	function tcg_daftarkuota(){
		$sekolah_id = $this->session->get("sekolah_id");
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('cfg_penerapan_sekolah a');
		$builder->select('c.jalur_id,c.nama AS jalur,a.kuota,b.nama as penerapan');
		$builder->join('cfg_penerapan b','a.penerapan_id = b.penerapan_id AND b.aktif = 1 AND b.is_deleted=0 and b.perankingan=1');
		$builder->join('ref_jalur c','b.jalur_id = c.jalur_id AND c.is_deleted=0');
		$builder->where(array('a.sekolah_id'=>$sekolah_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.is_deleted'=>0));
		$builder->orderBy('b.urutan');
		return $builder->get()->getResultArray();
	}

	function tcg_daftarpendaftaran($sekolah_id, $filters = null){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,a.nomor_pendaftaran,a.kelengkapan_berkas,a.created_on');
        $builder->select('b.jenis_kelamin, b.tanggal_lahir, b.tanggal_verifikasi, k.nama as verifikasi_oleh');
		$builder->select('b.nisn,b.nama,a.jenis_pilihan,b.lintang,b.bujur,a.status_penerimaan,a.masuk_jenis_pilihan,a.status_penerimaan_final,a.skor,a.peringkat,a.peringkat_final');
		$builder->select('b.nilai_kelulusan,coalesce(b.nilai_un,0) as nilai_usbn, a.status_daftar_ulang');
        $builder->select('e.nama AS sekolah_asal,f.nama AS lokasi_berkas,g.keterangan as label_masuk_pilihan,h.keterangan as label_jenis_pilihan,i.nama as sedang_verifikasi');
        $builder->select('d.jalur_id,d.nama AS jalur');
        $builder->select('case when a.status_daftar_ulang = 1 then a.tanggal_daftar_ulang else NULL end as tanggal_daftar_ulang', false);
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('cfg_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1 and c.tahun_ajaran_id=a.tahun_ajaran_id and c.putaran=a.putaran AND c.is_deleted=0');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.is_deleted=0');
		$builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
		$builder->join('ref_sekolah f','b.lokasi_berkas = f.sekolah_id','LEFT OUTER');
		$builder->join('cfg_jenis_pilihan g','a.masuk_jenis_pilihan = g.jenis_pilihan and g.tahun_ajaran_id=a.tahun_ajaran_id and g.putaran=a.putaran AND g.is_deleted=0','LEFT OUTER');
		$builder->join('cfg_jenis_pilihan h','a.jenis_pilihan = h.jenis_pilihan and h.tahun_ajaran_id=a.tahun_ajaran_id and h.putaran=a.putaran AND h.is_deleted=0','LEFT OUTER');
        $builder->join('dbo_users i','i.user_id = b.sedang_verifikasi_oleh and i.is_deleted = 0','LEFT OUTER');		
        $builder->join('dbo_users k','k.user_id = b.verifikator_id and k.is_deleted = 0','LEFT OUTER');		
        $builder->where(array('a.cabut_berkas'=>0,'a.jenis_pilihan !='=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id));
        $builder->where('a.tahun_ajaran_id', $tahun_ajaran_id);
        $builder->where('a.putaran', $putaran);

        //additional filters
        if ($filters!=null) {
            $builder->where($filters);
        }

        // $sql = $builder->getCompiledSelect();
        // echo $sql; exit;

		$builder->orderBy('a.created_on');

		return $builder->get()->getResultArray();
 	}

	// function tcg_pendaftaran($sekolah_id, $peserta_didik_id) {
	// 	$sql = "select a.* from tcg_pendaftaran a where a.sekolah_id=? and a.peserta_didik_id=? and a.is_deleted=0";

	// 	return $this->db->query($sql, array($sekolah_id, $peserta_didik_id));
	// }

	function tcg_pendaftaran_penerapanid($sekolah_id, $penerapan_id){

        $filters = array("a.penerapan_id"=>$penerapan_id);
        return $this->tcg_daftarpendaftaran($sekolah_id, $filters);
	}

	function tcg_kandidatswasta($sekolah_id, $tahun_ajaran_id) {
		$tahun_ajaran_id = substr($tahun_ajaran_id, 0, 4);
		$sql = "select
					a.peserta_didik_id, b.nama, RPAD(SUBSTR(b.nisn, 1, 6), Length(b.nisn), '*') as nisn, c.nama as sekolah, 
					concat(d.nama_desa, ' ', d.nama_kec, ' ', d.nama_kab) as alamat, a.jarak
				from rpt_kandidat_swasta a
				join tcg_peserta_didik b on b.peserta_didik_id=a.peserta_didik_id and b.is_deleted=0
				left join ref_sekolah c on c.sekolah_id=b.sekolah_id and c.is_deleted=0
				join ref_wilayah d on d.kode_wilayah=b.kode_wilayah and d.is_deleted=0
				where a.sekolah_id=? and a.tahun_ajaran_id=?
		";

		return $this->db->query($sql, array($sekolah_id, $tahun_ajaran_id));
	}

	function tcg_pendaftarditerima($sekolah_id, $penerapan_id){

        //TODO: check if IN filter is working
        $filters = array("a.penerapan_id"=>$penerapan_id);
        $results = $this->tcg_daftarpendaftaran($sekolah_id, $filters);

        foreach($results as $k => $v) {
            if ($v['status_penerimaan_final'] != 1 && $v['status_penerimaan_final'] != 3) {
                unset($results[$k]);
            }
        }

        return $results;
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
        return $this->tcg_daftarpendaftaran($sekolah_id, $filters);
	}  

    function tcg_cari_siswa($query, $jenjang = null, $asaldata = null, $inklusi = null, $afirmasi = null, $search_columns = null) {
        $limit = 1000;;
        $offset = 0;

        if (empty($query))  return null;

        if ($search_columns == null) {
            //default searched columns
            $search_columns = ['nama', 'nisn', 'nik', 'alamat'];
        }

        $builder = $this->db->table('tcg_peserta_didik a');

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
            group by a.tahun_ajaran_id, a.peserta_didik_id
        ) a where a.rn=1
        ";

        $builder->join("($subquery) as e", "e.tahun_ajaran_id=a.tahun_ajaran_id AND e.peserta_didik_id=a.peserta_didik_id", 'LEFT OUTER');


        if (!empty($jenjang)) $builder->where("b.bentuk", $jenjang);
        if (!empty($asaldata)) $builder->where("a.asal_data", $jenjang);
        if (!empty($inklusi)) $builder->where("a.kebutuhan_khusus!='Tidak ada'");
        if (!empty($afirmasi)) $builder->where(array("a.punya_kip=>1", "a.masuk_bdt=>1"));

        $arr = $builder->get($limit, $offset)->getResultArray();
        if ($arr == null)       return $arr;

        return $arr;
    }

	function tcg_touch_verifikasi($pengguna_id, $peserta_didik_id, $aktif=1) {
		$valuepair = array();
		if ($aktif == 1) {
			$valuepair = array (
				'sedang_verifikasi_oleh' => $pengguna_id,
				'sedang_verifikasi_timestamp' => date("Y/m/d H:i:s")
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

		$builder = $this->db->table('tcg_pendaftaran');
		$builder->select('peserta_didik_id');
		$builder->where(array('pendaftaran_id'=>$pendaftaran_id,'is_deleted'=>0));

		$peserta_didik_id = "";
		foreach($builder->get()->getResult() as $row):
			$peserta_didik_id = $row->peserta_didik_id;
		endforeach;

		return $peserta_didik_id;
	}
	
    function tcg_ubah_profil($key, $valuepair) {
		//inject last update
		$valuepair['updated_on'] = date("Y/m/d H:i:s");

		$builder = $this->db->table('ref_sekolah');
        $builder->where('sekolah_id', $key);
        $status = $builder->update($valuepair);
        if (!$status)   return 0;

        //put in audit trail
        $this->tcg_audit_trail("ref_sekolah",$key,'update','Update profil sekolah',implode(';', array_keys($valuepair)),implode(';',$valuepair));

        return 1;
    }

	function tcg_audit_trail($table, $reference, $action, $description, $keys, $new_values=null, $old_values=null) {
        $user_id = $this->session->get('user_id');
        
        $valuepair = array (
            'module' => 'ppdb',
            'table_name' => $table,
            'reference_id' => $reference,
            'action_name' => $action,
            'long_description' => $description,
            'col_names' => $keys,
            'col_values' => $new_values,
            'old_values' => $old_values,
            'created_by' => $user_id
        );

        $builder = $this->db->table('dbo_audit_trails');
        $builder->insert($valuepair);
	}

	// function tcg_pendaftarditerima($sekolah_id, $penerapan_id){

    //     $filters = array("a.penerapan_id"=>$penerapan_id, "a.status_penerimaan_final in"=>"(1,3)");
    //     return $this->tcg_daftarpendaftaran($sekolah_id, $filters);

	// 	// $tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

	// 	// $sql = "SELECT 
	// 	// 			`a`.`pendaftaran_id`, `a`.`sekolah_id`, `a`.`nama_sekolah`, `a`.`asal_sekolah_id`, `a`.`asal_sekolah`, `a`.`peserta_didik_id`, 
	// 	// 			`a`.`nama`, `a`.`penerapan_id`, `a`.`jalur_id`, `a`.`jalur`, `a`.`nomor_pendaftaran`, `a`.`jenis_pilihan`, `a`.`masuk_jenis_pilihan`, 
	// 	// 			`a`.`peringkat`, `a`.`skor`, `a`.`nisn`, `a`.`status_penerimaan`, `a`.`status_daftar_ulang`, `a`.`lokasi_berkas`, `a`.`created_on`,
	// 	// 			`a`.`nilai_kelulusan`, `a`.`nilai_usbn`, `a`.`jenis_kelamin`, 
	// 	// 			case when `a`.`status_daftar_ulang` = 1 then `a`.`tanggal_daftar_ulang` else NULL end as tanggal_daftar_ulang
	// 	// 		FROM `v_rpt_pendaftaran_diterima` `a` 
	// 	// 		WHERE `sekolah_id`=? AND `penerapan_id`=? and tahun_ajaran_id=?
	// 	// 		ORDER BY 
	// 	// 			case when a.status_daftar_ulang>1 then 0 else a.status_daftar_ulang end desc, 
	// 	// 			`a`.`peringkat`";

	// 	// return $this->db->query($sql, array($sekolah_id, $penerapan_id, $tahun_ajaran_id));
	// }

	// function tcg_pendaftarditerima_per_putaran($sekolah_id, $penerapan_id, $tahun_ajaran_id, $putaran){
	// 	if (empty($tahun_ajaran_id)) {
	// 		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
	// 	}

	// 	$sql = "SELECT 
	// 				`a`.`pendaftaran_id`, `a`.`sekolah_id`, `a`.`nama_sekolah`, `a`.`asal_sekolah_id`, `a`.`asal_sekolah`, `a`.`peserta_didik_id`, 
	// 				`a`.`nama`, `a`.`penerapan_id`, `a`.`jalur_id`, `a`.`jalur`, `a`.`nomor_pendaftaran`, `a`.`jenis_pilihan`, `a`.`masuk_jenis_pilihan`, 
	// 				`a`.`peringkat`, `a`.`skor`, `a`.`nisn`, `a`.`status_penerimaan`, `a`.`status_daftar_ulang`, `a`.`lokasi_berkas`, `a`.`created_on`,
	// 				`a`.`nilai_kelulusan`, `a`.`nilai_usbn`, `a`.`jenis_kelamin`, 
	// 				case when `a`.`status_daftar_ulang` = 1 then `a`.`tanggal_daftar_ulang` else NULL end as tanggal_daftar_ulang
	// 			FROM `v_rpt_pendaftaran_diterima` `a` 
	// 			WHERE `sekolah_id`=? AND `penerapan_id`=? and tahun_ajaran_id=? ";
		
	// 	if (!empty($putaran)) {
	// 		$putaran = secure($putaran);
	// 		$sql += " and putaran=$putaran ";
	// 	}

	// 	$sql += "ORDER BY 
	// 				case when a.status_daftar_ulang>1 then 0 else a.status_daftar_ulang end desc, 
	// 				`a`.`peringkat`";

	// 	return $this->db->query($sql, array($sekolah_id,$penerapan_id,$tahun_ajaran_id));
	// }

	// function tcg_pendaftarbelumdiverifikasi($sekolah_id){
        
    //     $filters = array('a.kelengkapan_berkas'=>0,'a.pendaftaran'=>1);
    //     return $this->tcg_daftarpendaftaran($sekolah_id, $filters);

	// 	// $tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
	// 	// $putaran = $this->session->get('putaran_aktif');

	// 	// $builder = $this->db->table('tcg_pendaftaran a');
	// 	// $builder->select('a.pendaftaran_id,
	// 	// 					a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,a.jenis_pilihan,
	// 	// 					i.keterangan as label_jenis_pilihan,
	// 	// 					e.nama AS sekolah_asal,a.created_on,
	// 	// 					h.nama as sedang_verifikasi');
	// 	// $builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
	// 	// $builder->join('cfg_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
	// 	// $builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.is_deleted=0');
	// 	// $builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
	// 	// $builder->join('tcg_kelengkapan_pendaftaran f','a.pendaftaran_id = f.pendaftaran_id AND f.is_deleted = 0');
	// 	// $builder->join('cfg_kelengkapan_dokumen g','f.kelengkapan_dokumen_id = g.kelengkapan_dokumen_id AND g.perlu_verifikasi = 1 AND g.is_deleted=0');
	// 	// $builder->join('dbo_users h','h.user_id = b.sedang_verifikasi_oleh and h.is_deleted = 0','LEFT OUTER');
	// 	// $builder->join('cfg_jenis_pilihan i','i.jenis_pilihan=a.jenis_pilihan and i.tahun_ajaran_id=a.tahun_ajaran_id and i.putaran=a.putaran and i.is_deleted=0','LEFT OUTER');
	// 	// $builder->where(array('a.cabut_berkas'=>0,'a.jenis_pilihan !='=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran));
	// 	// $builder->where(array('a.kelengkapan_berkas'=>0,'a.pendaftaran'=>1));
	// 	// $builder->groupBy(array('a.pendaftaran_id','a.sekolah_id','a.peserta_didik_id','a.penerapan_id','d.nama','a.nomor_pendaftaran','b.nisn','b.nama','a.jenis_pilihan', 'e.nama','a.created_on'));
	// 	// $builder->orderBy('a.created_on desc');
	// 	// return $builder->get();
	// }

	// function tcg_pendaftarsudahdiverifikasi($sekolah_id){
	// 	$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
	// 	$putaran = $this->session->get('putaran_aktif');

	// 	$builder = $this->db->table('tcg_pendaftaran a');
	// 	$builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,a.jenis_pilihan,e.nama AS sekolah_asal,a.created_on,i.keterangan as label_jenis_pilihan');
	// 	$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
	// 	$builder->join('cfg_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
	// 	$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.is_deleted=0');
	// 	$builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
	// 	$builder->join('tcg_kelengkapan_pendaftaran f','a.pendaftaran_id = f.pendaftaran_id AND f.is_deleted = 0');
	// 	$builder->join('cfg_kelengkapan_dokumen g','f.kelengkapan_dokumen_id = g.kelengkapan_dokumen_id AND g.perlu_verifikasi = 1 AND g.is_deleted=0');
	// 	$builder->join('cfg_jenis_pilihan i','i.jenis_pilihan=a.jenis_pilihan and i.tahun_ajaran_id=a.tahun_ajaran_id and i.putaran=a.putaran and i.is_deleted=0','LEFT OUTER');
	// 	$builder->where(array('a.cabut_berkas'=>0,'a.jenis_pilihan !='=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran));
	// 	$builder->where(array('a.kelengkapan_berkas!='=>0,'a.pendaftaran'=>1));
	// 	$builder->groupBy(array('a.pendaftaran_id','a.sekolah_id','a.peserta_didik_id','a.penerapan_id','d.nama','a.nomor_pendaftaran','b.nisn','b.nama','a.jenis_pilihan', 'e.nama','a.created_on'));
	// 	$builder->orderBy('a.created_on desc');
	// 	return $builder->get();
	// }

	// function tcg_pendaftarbelumlengkap($sekolah_id){
        
    //     $filters = array('a.kelengkapan_berkas'=>2,'a.pendaftaran'=>1);
    //     return $this->tcg_daftarpendaftaran($sekolah_id, $filters);

    //     // $tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
	// 	// $putaran = $this->session->get('putaran_aktif');

	// 	// $builder = $this->db->table('tcg_pendaftaran a');
	// 	// $builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,a.jenis_pilihan,
	// 	// 					i.keterangan as label_jenis_pilihan,
	// 	// 					e.nama AS sekolah_asal,a.created_on,
    //     //                     h.nama as sedang_verifikasi,
    //     //                     ');
	// 	// $builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
	// 	// $builder->join('cfg_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
	// 	// $builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.is_deleted=0');
	// 	// $builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
	// 	// $builder->join('tcg_kelengkapan_pendaftaran f','a.pendaftaran_id = f.pendaftaran_id AND f.is_deleted = 0');
	// 	// $builder->join('cfg_kelengkapan_dokumen g','f.kelengkapan_dokumen_id = g.kelengkapan_dokumen_id AND g.perlu_verifikasi = 1 AND g.is_deleted=0');
	// 	// $builder->join('dbo_users h','h.user_id = b.sedang_verifikasi_oleh and h.is_deleted = 0','LEFT OUTER');
	// 	// $builder->join('cfg_jenis_pilihan i','i.jenis_pilihan=a.jenis_pilihan and i.tahun_ajaran_id=a.tahun_ajaran_id and i.putaran=a.putaran and i.is_deleted=0','LEFT OUTER');
	// 	// // $builder->join('dbo_sekolah j','j.sekolah_id = b.lokasi_berkas','LEFT OUTER');
	// 	// $builder->where(array('a.cabut_berkas'=>0,'a.jenis_pilihan !='=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran));
	// 	// $builder->where(array('a.kelengkapan_berkas'=>2,'a.pendaftaran'=>1));
	// 	// $builder->groupBy(array('a.pendaftaran_id','a.sekolah_id','a.peserta_didik_id','a.penerapan_id','d.nama','a.nomor_pendaftaran','b.nisn','b.nama','a.jenis_pilihan', 'e.nama','a.created_on'));
	// 	// $builder->orderBy('a.created_on desc');
	// 	// return $builder->get();
	// }

	// function tcg_pendaftarsudahlengkap($sekolah_id){
        
    //     $filters = array('a.kelengkapan_berkas'=>2,'a.pendaftaran'=>1);
    //     return $this->tcg_daftarpendaftaran($sekolah_id, $filters);

    //     // $tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
	// 	// $putaran = $this->session->get('putaran_aktif');

	// 	// $builder = $this->db->table('tcg_pendaftaran a');
	// 	// $builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,a.jenis_pilihan,e.nama AS sekolah_asal,a.created_on,
    //     //                     a.tanggal_verifikasi_berkas,a.verifikasi_berkas_oleh,h.nama as lokasi_berkas,i.keterangan as label_jenis_pilihan');
	// 	// $builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
	// 	// $builder->join('cfg_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
	// 	// $builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.is_deleted=0');
	// 	// $builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
	// 	// $builder->join('tcg_kelengkapan_pendaftaran f','a.pendaftaran_id = f.pendaftaran_id AND f.is_deleted = 0');
	// 	// $builder->join('cfg_kelengkapan_dokumen g','f.kelengkapan_dokumen_id = g.kelengkapan_dokumen_id AND g.perlu_verifikasi = 1 AND g.is_deleted=0');
	// 	// $builder->join('ref_sekolah h','h.sekolah_id = b.lokasi_berkas','LEFT OUTER');
	// 	// $builder->join('cfg_jenis_pilihan i','i.jenis_pilihan=a.jenis_pilihan and i.tahun_ajaran_id=a.tahun_ajaran_id and i.putaran=a.putaran and i.is_deleted=0','LEFT OUTER');
	// 	// $builder->where(array('a.cabut_berkas'=>0,'a.jenis_pilihan !='=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran));
	// 	// $builder->where(array('a.kelengkapan_berkas'=>1,'a.pendaftaran'=>1));
	// 	// $builder->groupBy(array('a.pendaftaran_id','a.sekolah_id','a.peserta_didik_id','a.penerapan_id','d.nama','a.nomor_pendaftaran','b.nisn','b.nama','a.jenis_pilihan', 'e.nama','a.created_on'));
	// 	// $builder->orderBy('a.created_on');
	// 	// return $builder->get();
	// }

	function tcg_daftar_siswa($sekolah_id, $tahun_ajaran_id) {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		//TODO: pass updated_on as UTC and convert on client side using moment.js
		$query = "SELECT a.sekolah_id, b.nama as sekolah, 
						a.peserta_didik_id, a.nama, a.jenis_kelamin, a.nisn, a.nik, a.tempat_lahir, a.tanggal_lahir, a.nama_ibu_kandung, a.nama_ayah,
						a.kode_wilayah, a.rt, a.rw, a.alamat, a.nama_dusun, a.desa_kelurahan, a.lintang, a.bujur, 
						CONVERT_TZ(a.updated_on, '+00:00', '+07:00') as updated_on
					FROM tcg_peserta_didik a
					join ref_sekolah b on a.sekolah_id=b.sekolah_id and a.is_deleted=0
					where a.sekolah_id=? and a.tahun_ajaran_id=? and a.is_deleted=0";

		return $this->db->query($query, array($sekolah_id, $tahun_ajaran_id));
	}


	// function tcg_daftar_penerapan($sekolah_id) {
	// 	$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");

	// 	$sql = "select a.* from v_rpt_sekolah_summary a where a.sekolah_id=? and a.tahun_ajaran_id=?";

	// 	return $this->db->query($sql, array($sekolah_id, $tahun_ajaran_id));

	// }

	// function tcg_detil_pendaftaran($pendaftaran_id) {
	// 	$sql = "select a.* from tcg_pendaftaran a where a.pendaftaran_id=? and a.is_deleted=0";

	// 	return $this->db->query($sql, array($pendaftaran_id));
	// }
    

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
		return $this->db->affectedRows();
	}

	// function tcg_detailpendaftaran($peserta_didik_id, $pendaftaran_id){

	// 	$builder = $this->db->table('tcg_pendaftaran a');
	// 	$builder->select('a.pendaftaran_id,a.penerapan_id,d.jalur_id,d.nama AS jalur,
	// 						a.nomor_pendaftaran,a.jenis_pilihan,g.keterangan as label_jenis_pilihan, a.status_penerimaan,a.cabut_berkas,a.masuk_jenis_pilihan,a.peringkat,
	// 						a.sekolah_id,b.npsn,b.nama AS sekolah,b.lintang AS lintang_sekolah,b.bujur AS bujur_sekolah, 
	// 						a.peserta_didik_id,e.nisn,e.nama,f.lintang AS lintang_siswa,f.bujur AS bujur_siswa,
	// 						f.sekolah_id as asal_sekolah_id,b.npsn as asal_sekolah_npsn,b.nama AS asal_sekolah, 
	// 						a.status_daftar_ulang,a.tanggal_daftar_ulang, a.status_penerimaan_final,a.peringkat_final,
	// 						a.created_on');
	// 	$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id');
	// 	$builder->join('cfg_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1 AND c.is_deleted=0');
	// 	$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.is_deleted=0');
	// 	$builder->join('tcg_peserta_didik e','a.peserta_didik_id = e.peserta_didik_id AND e.is_deleted = 0');
	// 	$builder->join('ref_sekolah f','e.sekolah_id = f.sekolah_id');
	// 	$builder->join('cfg_jenis_pilihan g','g.jenis_pilihan = a.jenis_pilihan AND g.tahun_ajaran_id=a.tahun_ajaran_id AND g.is_deleted=0');
	// 	$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.pendaftaran_id'=>$pendaftaran_id,'a.cabut_berkas'=>0,'a.is_deleted'=>0));
	// 	$builder->orderBy('a.created_on');
	// 	return $builder->get();
	// }    

	// function tcg_profilsiswa_daftarulang($peserta_didik_id){

	// 	$builder = $this->db->table('tcg_peserta_didik a');
	// 	$builder->select("a.peserta_didik_id,a.sekolah_id,b.npsn,b.nama AS sekolah,
	// 	a.nik,a.nisn,a.nomor_ujian,a.nama,a.jenis_kelamin,a.tempat_lahir,a.tanggal_lahir,a.nama_ibu_kandung,a.nama_ayah,a.nama_wali,a.kebutuhan_khusus,
	// 	a.rt,a.rw,a.alamat,a.kode_wilayah,
	// 	'' as kode_padukuhan,'' AS padukuhan,
	// 	c.kode_wilayah_desa as kode_desa, c.nama_desa AS desa_kelurahan,
	// 	c.kode_wilayah_kec as kode_kecamatan,c.nama_kec AS kecamatan,
	// 	c.kode_wilayah_kab as kode_kabupaten,c.nama_kab AS kabupaten,
	// 	c.kode_wilayah_prov,c.nama_prov AS provinsi,
	// 	a.lintang,a.bujur,a.asal_data, 
	// 	d.user_name as username,a.nomor_kontak,
	// 	coalesce(a.punya_nilai_un,0) as punya_nilai_un,coalesce(a.punya_prestasi,0) as punya_prestasi,
	// 	coalesce(a.punya_kip,0) as punya_kip,
	// 	coalesce(a.masuk_bdt,0) as masuk_bdt,
	// 	e.nama as lokasi_berkas
	// 	");
	// 	$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id','LEFT OUTER');
	// 	$builder->join('ref_wilayah c','a.kode_wilayah = c.kode_wilayah AND c.is_deleted=0','LEFT OUTER');
	// 	$builder->join('dbo_users d','a.peserta_didik_id = d.pengguna_id AND d.is_deleted = 0','LEFT OUTER');
	// 	$builder->join('ref_sekolah e','e.sekolah_id = a.lokasi_berkas','LEFT OUTER');
	// 	// $builder->join('ref_wilayah c4','c.kode_wilayah_desa = c4.kode_wilayah AND c4.is_deleted=0','LEFT OUTER');
	// 	// $builder->join('ref_wilayah c3','c.kode_wilayah_kec = c3.kode_wilayah AND c3.is_deleted=0','LEFT OUTER');
	// 	// $builder->join('ref_wilayah c2','c.kode_wilayah_kab = c2.kode_wilayah AND c2.is_deleted=0','LEFT OUTER');
	// 	// $builder->join('ref_wilayah c1','c.kode_wilayah_prov = c1.kode_wilayah AND c1.is_deleted=0','LEFT OUTER');
	// 	$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.is_deleted'=>0));

	// 	return $builder->get();
	// }

	// function tcg_dokumen_pendukung($peserta_didik_id) {
	// 	// daftar SEMUA dokumen pendukung
	// 	$query = "
	// 			  select a.dokumen_id, a.daftar_kelengkapan_id, b.nama, a.filename, a.path, a.web_path, a.thumbnail_path, a.verifikasi, a.catatan,
	// 				b.dokumen_fisik, b.placeholder, a.berkas_fisik
	// 			  from tcg_dokumen_pendukung a
	// 			  join ref_daftar_kelengkapan b on a.daftar_kelengkapan_id=b.daftar_kelengkapan_id and b.is_deleted=0
	// 			  where a.peserta_didik_id=? and a.is_deleted=0
	// 			  order by b.urutan";

	// 	return $this->db->query($query, array($peserta_didik_id));
	// }

	// function tcg_ubah_lokasiberkas($peserta_didik_id, $sekolah_id) {
	// 	$data = array(
	// 		'lokasi_berkas' => $sekolah_id,
	// 		'updated_on' => date("Y/m/d H:i:s")
	// 	);

    //     $builder = $this->db->table('tcg_peserta_didik');
	// 	$builder->where(array('peserta_didik_id'=>$peserta_didik_id,'is_deleted'=>0));
	// 	$retval = $builder->update($data);

	// 	if ($retval > 0) {
	// 		//put in audit trail
	// 		$this->tcg_audit_trail("tcg_peserta_didik",$peserta_didik_id,'update','Ubah lokasi berkas',implode(';', array_keys($data)),implode(';',$data));
	// 	}

	// 	return $retval;
	// }

	// function tcg_daftarulang_dokumenpendukung($peserta_didik_id, $dokumen_id, $status, $penerima_berkas_id) {
	// 	$query = "
	// 	update tcg_dokumen_pendukung a
	// 	set
	// 		a.berkas_fisik = ?,
	// 		a.penerima_berkas_id = ?,
	// 		a.tanggal_berkas = now(),
	// 		a.updated_on = now()
	// 	where a.peserta_didik_id=? and a.dokumen_id=? and a.is_deleted=0";

	// 	$this->db->query($query, array($status, $penerima_berkas_id, $peserta_didik_id, $dokumen_id));

	// 	$retval = $this->db->affectedRows();
	// 	if ($retval > 0) {
	// 		$keys = "dokumen_id;daftarulang;penerima_berkas_id";
	// 		$values = "$dokumen_id;$status;$penerima_berkas_id";

	// 		//put in audit trail
	// 		$this->tcg_audit_trail("tcg_dokumen_pendukung",$peserta_didik_id,'update','Daftar ulang berkas',$keys,$values);
	// 	}

	// 	return $retval;

	// }

	// function tcg_daftarpendaftaran($peserta_didik_id, $tahun_ajaran_id = null){
	// 	if (empty($tahun_ajaran_id)) {
	// 		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
	// 	}

	// 	$builder = $this->db->table('tcg_pendaftaran a');
	// 	$builder->select('a.pendaftaran_id,a.penerapan_id,d.jalur_id,d.nama AS jalur,
	// 						a.nomor_pendaftaran,a.jenis_pilihan,a.status_penerimaan,a.cabut_berkas,a.masuk_jenis_pilihan,
	// 						a.peringkat,a.skor,a.kelengkapan_berkas,
	// 						a.status_penerimaan_final,a.peringkat_final,
	// 						a.sekolah_id,b.npsn,b.nama AS sekolah,b.bentuk,
	// 						a.pendaftaran, e.keterangan as label_jenis_pilihan, f.keterangan as label_masuk_pilihan,
	// 						a.created_on');
	// 	$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id');
	// 	$builder->join('cfg_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1 AND c.is_deleted=0');
	// 	$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.is_deleted=0');
	// 	$builder->join('cfg_jenis_pilihan e','e.jenis_pilihan = a.jenis_pilihan AND e.tahun_ajaran_id=a.tahun_ajaran_id AND e.is_deleted=0');
	// 	$builder->join('cfg_jenis_pilihan f','f.jenis_pilihan = a.masuk_jenis_pilihan AND f.tahun_ajaran_id=a.tahun_ajaran_id AND f.is_deleted=0', 'left outer');
	// 	//$builder->join('tcg_skoring_pendaftaran g','a.pendaftaran_id = f.pendaftaran_id AND f.is_deleted = 0','LEFT OUTER');
	// 	$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.cabut_berkas'=>0,'a.is_deleted'=>0,'a.tahun_ajaran_id'=>$tahun_ajaran_id));
	// 	$builder->orderBy('a.jenis_pilihan');
	// 	return $builder->get();
	// }

	// function tcg_riwayat_verifikasi($peserta_didik_id) {
	// 	$sql = "SELECT a.riwayat_id, a.verifikator_id, b.nama, a.verifikasi, a.catatan_kekurangan, a.created_on 
	// 	from tcg_riwayat_verifikasi a
	// 	join dbo_users b on b.pengguna_id=a.verifikator_id and b.is_deleted=0
	// 	where a.is_deleted=0 and a.peserta_didik_id=?";

	// 	return $this->db->query($sql, array($peserta_didik_id));
	// }    

	// function tcg_daftar_prestasi($peserta_didik_id) {
	// 	$query = "
	// 	select a.prestasi_id, a.skoring_id, b.nama as prestasi, a.uraian, a.dokumen_pendukung,
	// 			c.filename as nama_dokumen, c.path, c.web_path, c.thumbnail_path, 
	// 			c.verifikasi, c.catatan,
	// 			c.created_on as tanggal_upload
	// 	from tcg_prestasi a
	// 	join ref_daftar_skoring b on a.skoring_id=b.skoring_id and b.is_deleted=0
	// 	left join tcg_dokumen_pendukung c on a.dokumen_pendukung=c.dokumen_id and c.is_deleted=0
	// 	where a.is_deleted=0 and a.peserta_didik_id=?";

	// 	return $this->db->query($query, array($peserta_didik_id));
	// }


	// function tcg_berkasdisekolah($sekolah_id){
	// 	$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

	// 	$timestamp = secure(date("Y/m/d H:i:s"));

	// 	$builder = $this->db->table('tcg_pendaftaran a');
	// 	$builder->select('a.pendaftaran_id,
	// 						a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,a.jenis_pilihan,
	// 						i.keterangan as label_jenis_pilihan,
	// 						e.nama AS sekolah_asal,a.created_on,a.tanggal_verifikasi_berkas,a.verifikasi_berkas_oleh,a.kelengkapan_berkas,
	// 						case when(sedang_verifikasi_timestamp is not null and date_add(sedang_verifikasi_timestamp, interval 15 minute)>=' .$timestamp. ') then h.nama else null end as sedang_verifikasi');
	// 	$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
	// 	$builder->join('cfg_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
	// 	$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.is_deleted=0');
	// 	$builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
	// 	// $builder->join('dbo_kelengkapan_pendaftaran f','a.pendaftaran_id = f.pendaftaran_id AND f.is_deleted = 0');
	// 	// $builder->join('cfg_kelengkapan_dokumen g','f.kelengkapan_dokumen_id = g.kelengkapan_dokumen_id AND g.perlu_verifikasi = 1 AND g.is_deleted=0');
	// 	$builder->join('dbo_users h','h.pengguna_id = b.sedang_verifikasi_oleh and h.is_deleted = 0','LEFT OUTER');
	// 	$builder->join('cfg_jenis_pilihan i','i.jenis_pilihan=a.jenis_pilihan and i.tahun_ajaran_id=a.tahun_ajaran_id and i.putaran=a.putaran and i.is_deleted=0','LEFT OUTER');
	// 	$builder->where(array('a.pendaftaran'=>1,'a.cabut_berkas'=>0,'a.jenis_pilihan !='=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id,'b.lokasi_berkas'=>$sekolah_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id));
	// 	// $builder->group_by(array('a.pendaftaran_id','a.sekolah_id','a.peserta_didik_id','a.penerapan_id','d.nama','a.nomor_pendaftaran','b.nisn','b.nama','a.jenis_pilihan', 'e.nama','a.created_on'));
	// 	$builder->orderBy('a.created_on');
	// 	return $builder->get();
	// }

	function tcg_nama_sekolah($sekolah_id){

		$builder = $this->db->table('ref_sekolah a');
		$builder->select('a.nama');
		$builder->where(array('a.sekolah_id'=>$sekolah_id, 'a.is_deleted'=>0));

		$result = $builder->get()->getResultArray();
		if ($result == null) return '';

		$sekolah = $result[0]['nama'];
		return $sekolah;
	}

    
	function tcg_pesertadidik_sd_diterima($sekolah_id) {
		$sql = "select b.peserta_didik_id, b.nama, b.nisn, b.nik, c.npsn as npsn_sekolah_asal, c.nama as asal_sekolah,
					   b.tempat_lahir, b.tanggal_lahir, b.nama_ibu_kandung, b.jenis_kelamin
				  from tcg_pendaftaran a
				  left join tcg_peserta_didik b on b.peserta_didik_id=a.peserta_didik_id
				  left join tcg_sekolah c on c.sekolah_id=b.sekolah_id
				  where a.is_deleted=0 and a.status_penerimaan_final=1 and a.sekolah_id=?";

		return $this->db->query($sql, array($sekolah_id))->getResultArray();
	}

	function tcg_calon_pesertadidik_sd($nama, $nisn, $nik, $sekolah_id, $jenis_kelamin, $kode_desa, $kode_kecamatan){
		$filter = 0;
		$query = "select a.peserta_didik_id, a.nama, a.nisn, a.nik, a.tempat_lahir, a.tanggal_lahir,
					  b.kode_wilayah_desa, b.nama_desa, b.kode_wilayah_kec, b.nama_kec, c.nama as sekolah,
					  e.nama as diterima_sekolah, a.jenis_kelamin
				  from tcg_peserta_didik a
				  left join ref_mst_wilayah b on a.kode_wilayah=b.kode_wilayah and b.is_deleted=0
				  left join tcg_sekolah c on c.sekolah_id=a.sekolah_id and c.is_deleted=0
				  left join tcg_pendaftaran d on d.peserta_didik_id=a.peserta_didik_id and d.status_penerimaan_final=1 and d.is_deleted=0
				  left join tcg_sekolah e on e.sekolah_id=d.sekolah_id and e.is_deleted=0 
				  ";

		$where = "a.is_deleted=0 and a.jenjang in ('TK', 'RA')";
		if (!empty($nama)) {
			$filter = 1;
			$where .= " AND a.nama like '%" . $nama . "%'";
		}
		if (!empty($jenis_kelamin)) {
			$filter = 1;
			$where .= " AND a.jenis_kelamin='" . $jenis_kelamin . "'";
		}
		if (!empty($nisn)) {
			$filter = 1;
			$where .= " AND a.nisn='" . $nisn . "'";
		}
		if (!empty($nik)) {
			$filter = 1;
			$where .= " AND a.nik='" . $nik . "'";
		}
		if (!empty($kode_kecamatan)) {
			$filter = 1;
			$where .= " AND a.kode_kecamatan=" . $kode_kecamatan;
		}
		if (!empty($kode_desa)) {
			$filter = 1;
			$where .= " AND a.kode_desa=" . $kode_desa;
		}
		if (!empty($sekolah_id)) {
			$filter = 1;
			$where .= " AND a.sekolah_id='" . $sekolah_id . "'";
		}

		if ($filter == 0) {
			//no aktif filter! dont show anything
			$where .= " AND 1=0";
		}

		$query .= " WHERE " . $where;

		return $this->db->query($query)->getResultArray();	
	}

	function tcg_terima_pesertadidik_sd($sekolah_id, $peserta_didik_id) {
		$pengguna_id = $this->session->get("user_id");

		$sql = "call usp_sd_tambah_penerimaan(?, ?, ?)";
		$query = $this->db->query($sql, array($sekolah_id, $peserta_didik_id, $pengguna_id))->getResultArray();;

		return $query;
	}

	function tcg_hapus_pesertadidik_sd($sekolah_id, $peserta_didik_id) {
		$pengguna_id = $this->session->get("user_id");

		$sql = "call usp_sd_hapus_penerimaan(?, ?, ?)";
		$query = $this->db->query($sql, array($sekolah_id, $peserta_didik_id, $pengguna_id))->getResultArray();;

		return $query;
	}

}