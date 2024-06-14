<?php 

namespace App\Models\Ppdb\Siswa;

Class Mprofilsiswa 
{

    protected $db;
    protected $session;
    protected $audittrail;
    protected $tahun_ajaran_id;

    protected $error_message = null;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");

        $this->audittrail = new \App\Libraries\AuditTrail();

        helper("functions");
    }

    function get_error_message() {
        return $this->error_message;
    }
    
    function tcg_update_siswa($peserta_didik_id, $values) {
        $this->error_message = null;

        $oldvalues = $this->tcg_profilsiswa($peserta_didik_id);

        $builder = $this->db->table("tcg_peserta_didik");
        $values['created_by'] = $this->session->get('user_id');

        $builder->where("peserta_didik_id", $peserta_didik_id);
        $builder->update($values);

        //update skoring
        if (!empty($values['lintang']) || !empty($values['bujur'])) {
            $sql = "select pendaftaran_id from tcg_pendaftaran where is_deleted=0 and cabut_berkas=0 and peserta_didik_id=?";
            $pendaftaran = $this->db->query($sql, array($peserta_didik_id))->getResultArray();
            //var_dump($pendaftaran); exit;
            foreach($pendaftaran as $row) {
                $sql = "call " .SQL_HITUNGSKOR. " (?)";
                $this->db->query($sql, array( $row['pendaftaran_id'] ));
            }
        }

        //audit trail
        $this->audittrail->update('tcg_peserta_didik', $peserta_didik_id, array_keys($values), $values, $oldvalues);

        return $this->tcg_profilsiswa_detil($peserta_didik_id);
    }

	function tcg_profilsiswa_detil($peserta_didik_id){
        $this->error_message = null;
        
		$builder = $this->db->table('tcg_peserta_didik a');
		$builder->select("
        a.peserta_didik_id,a.sekolah_id,b.npsn,b.nama AS sekolah,
		a.nik,a.nisn,a.nomor_ujian,a.nama,a.jenis_kelamin,a.tempat_lahir,a.tanggal_lahir,a.nama_ibu_kandung,a.nama_ayah,a.nama_wali,
		a.alamat,a.kode_wilayah,a.lintang,a.bujur,a.asal_data,a.nomor_kontak,a.rt,a.rw,
		a.cabut_berkas,a.hapus_pendaftaran,a.ubah_pilihan,a.ubah_sekolah,a.batal_verifikasi,
		a.verifikasi_profil,a.verifikasi_lokasi,a.verifikasi_nilai,a.verifikasi_prestasi,a.verifikasi_afirmasi,a.verifikasi_inklusi,a.verifikasi_dokumen,
		a.catatan_profil,a.catatan_lokasi,a.catatan_nilai,a.catatan_prestasi,a.catatan_afirmasi,a.catatan_inklusi,
		a.verifikator_id,e.nama as nama_verifikator,a.tanggal_verifikasi,
		a.konfirmasi_profil,a.konfirmasi_lokasi,a.konfirmasi_nilai,a.konfirmasi_prestasi,a.konfirmasi_afirmasi,a.konfirmasi_inklusi,
		coalesce(a.punya_nilai_un,0) as punya_nilai_un,coalesce(a.punya_prestasi,0) as punya_prestasi,
		coalesce(a.punya_kip,0) as punya_kip,coalesce(a.masuk_bdt,0) as masuk_bdt,coalesce(a.no_kip,'') as no_kip,coalesce(a.no_bdt,'') as no_bdt,		
		a.nilai_un, a.nilai_bin, a.nilai_mat, a.nilai_ipa,
		a.nilai_semester, a.nilai_kelas4_sem1, a.nilai_kelas4_sem2, a.nilai_kelas5_sem1, a.nilai_kelas5_sem2, a.nilai_kelas6_sem1, a.nilai_kelas6_sem2,
		a.nilai_kelulusan, a.prestasi_skoring_id, a.uraian_prestasi, g.nama as prestasi_skoring_label,
		i.nama as lokasi_berkas, a.tutup_akses, a.akses_ubah_data
		");
        $builder->select("'' as kode_padukuhan, a.nama_dusun AS padukuhan,
                            c.kode_wilayah_desa as kode_desa, coalesce(c.nama_desa,a.desa_kelurahan) AS desa_kelurahan,
		                    c.kode_wilayah_kec as kode_kecamatan,c.nama_kec AS kecamatan,
		                    c.kode_wilayah_kab as kode_kabupaten,c.nama_kab AS kabupaten,
		                    c.kode_wilayah_prov,c.nama_prov AS provinsi");
        $builder->select("d.user_name as username,d.konfirmasi_akun, d.ganti_password");
        $builder->select("case when (a.kebutuhan_khusus is null or trim(a.kebutuhan_khusus) = '' or a.kebutuhan_khusus = '0') then 'Tidak ada' 
                            else a.kebutuhan_khusus end as kebutuhan_khusus", false);
        $builder->select('case when f.dokumen_id is null then 0 else a.surat_pernyataan_kebenaran_dokumen end as surat_pernyataan_kebenaran_dokumen,
                            f.filename as nama_surat_pernyataan, f.path as path_surat_pernyataan,f.web_path as img_surat_pernyataan,
                            f.thumbnail_path as thumbnail_surat_pernyataan,f.created_on as tanggal_surat_pernyataan', false);
		$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id','LEFT OUTER');
		$builder->join('ref_wilayah c','a.kode_wilayah = c.kode_wilayah AND c.is_deleted=0','LEFT OUTER');
		$builder->join('dbo_users d','a.peserta_didik_id = d.peserta_didik_id AND d.is_deleted = 0','LEFT OUTER');
		$builder->join('dbo_users e','a.verifikator_id = e.user_id AND e.is_deleted = 0','LEFT OUTER');
		$builder->join('tcg_dokumen_pendukung f','a.surat_pernyataan_kebenaran_dokumen = f.dokumen_id AND a.peserta_didik_id=f.peserta_didik_id AND f.is_deleted = 0','LEFT OUTER');
		$builder->join('ref_daftar_skoring g','g.skoring_id = a.prestasi_skoring_id and g.is_deleted=0','LEFT OUTER');
		$builder->join('ref_sekolah i','i.sekolah_id = a.lokasi_berkas','LEFT OUTER');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.is_deleted'=>0));

        // $sql = $builder->getCompiledSelect();
        // echo $sql; exit;

		return $builder->get()->getRowArray();
	}   

    function tcg_profilsiswa($peserta_didik_id){
        $this->error_message = null;
        
		$builder = $this->db->table('tcg_peserta_didik a');
		$builder->select('a.*');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.is_deleted'=>0));
		return $builder->get()->getRowArray();
    }

    function tcg_profilsiswa_from_userid($user_id) {
        $sql = "select peserta_didik_id from dbo_users where is_deleted=0 and user_id=?";
        $result = $this->db->query($sql, array($user_id))->getRowArray();
        if ($result == null) return null;

        return $this->tcg_profilsiswa($result['peserta_didik_id']);
    }

	function tcg_jenispilihan($peserta_didik_id, $penerapan_id){
        $this->error_message = null;
        
		$query = "CALL " .SQL_PILIHSEKOLAH_JENISPILIHAN. " (?, ?)";
		return $this->db->query($query, array($peserta_didik_id, $penerapan_id))->getResultArray();
	}

	function tcg_pilihansekolah($peserta_didik_id, $penerapan_id, $jenis_pilihan=0){
        $this->error_message = null;
        
		$query = "CALL " .SQL_PILIHSEKOLAH_SEKOLAH. " (?, ?, ?)";
		return $this->db->query($query, array($peserta_didik_id, $penerapan_id, $jenis_pilihan))->getResultArray();
	}

	function tcg_jenispilihan_perubahan($peserta_didik_id, $pendaftaran_id){
        $this->error_message = null;
        
		$query = "CALL " .SQL_UBAHPILIHAN_JENISPILIHAN. " (?, ?)";
		return $this->db->query($query, array($peserta_didik_id, $pendaftaran_id))->getResultArray();
	}

	function tcg_daftarpenerapan($kode_wilayah, $kebutuhan_khusus=0, $selain_penerapan_id=0){
        $this->error_message = null;
        
        //IMPORTANT: Perhitungan berdasarkan tanggal lahir sekarang dilakukan secara global!

		$putaran = $this->session->get("putaran_aktif");
		$kode_wilayah_aktif = $this->session->get("kode_wilayah_aktif");
		//$bentuk_sekolah = secure("SMP");

		$builder = $this->db->table('cfg_penerapan a');
		$builder->select('a.penerapan_id,a.nama,a.keterangan,c.jalur_id,c.nama AS jalur,a.sekolah_negeri,a.sekolah_swasta,a.kategori_susulan,a.kategori_inklusi');
		$builder->join('ref_jalur c','a.jalur_id = c.jalur_id AND c.is_deleted=0');
		$builder->where(array('a.pendaftaran'=>1,'a.tahun_ajaran_id'=>$this->tahun_ajaran_id,'a.putaran'=>$putaran,'a.is_deleted'=>0));
		
		if(substr($kode_wilayah,0,4)!=substr($kode_wilayah_aktif,0,4)){
			$builder->where('a.luar_wilayah_administrasi',1);
		}else{
			$builder->where('a.dalam_wilayah_administrasi',1);
		}

		if($kebutuhan_khusus==1){
			$builder->groupStart();
			$builder->where('a.kategori_inklusi',1);
			$builder->orWhere('c.jalur_id',7);			//jalur inklusi
			$builder->groupEnd();
		}
		
 		// if ($afirmasi==0) {
		// 	$builder->where('a.kategori_afirmasi',0);
		// 	$builder->where('c.jalur_id !=',9);
		// } 
        // else if ($afirmasi==1) {
		// 	$builder->groupStart();
		// 	$builder->where('a.kategori_afirmasi',1);
		// 	$builder->orWhere('c.jalur_id',9);			//jalur afirmasi
		// 	$builder->groupEnd();
        // }

		if ($selain_penerapan_id > 0) {
			$builder->where('a.penerapan_id !=',$selain_penerapan_id);
		}

        // $sql = $builder->getCompiledSelect();
        // echo $sql; exit;

		$builder->orderBy('a.urutan');
        return $builder->get()->getResultArray();
	}    

    function tcg_cek_penerapan($penerapan_id) {
		$putaran = $this->session->get("putaran_aktif");

		$builder = $this->db->table('cfg_penerapan a');
		$builder->select('count(*) as jumlah');
		$builder->where(array('a.pendaftaran'=>1,'a.tahun_ajaran_id'=>$this->tahun_ajaran_id,'a.putaran'=>$putaran,'a.is_deleted'=>0));
        $builder->where("penerapan_id", $penerapan_id);

        $result = $builder->get()->getRowArray();
        if ($result == null)    return 0;

        return $result['jumlah'];
    }

	function tcg_daftarpendaftaran($peserta_didik_id, $filters = null){
        $this->error_message = null;
        
        $putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.penerapan_id,d.jalur_id,d.nama AS jalur,
							a.nomor_pendaftaran,a.jenis_pilihan,a.status_penerimaan,a.cabut_berkas,a.masuk_jenis_pilihan,
							a.peringkat,a.skor,a.kelengkapan_berkas,a.status_penerimaan_final,a.peringkat_final,
							a.sekolah_id,b.npsn,b.nama AS sekolah,b.bentuk,b.status as status_sekolah,
							a.pendaftaran, e.keterangan as label_jenis_pilihan, f.keterangan as label_masuk_pilihan,
							a.created_on, a.status_daftar_ulang, a.pendaftaran, a.tag,
                            g.nama as nama, g.nisn, c.nama as penerapan, c.parent_id as parent_penerapan_id');
		$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id');
		$builder->join('cfg_penerapan c','a.penerapan_id = c.penerapan_id AND c.tahun_ajaran_id=a.tahun_ajaran_id and c.putaran=a.putaran AND c.aktif = 1 AND c.is_deleted=0','LEFT OUTER');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.is_deleted=0','LEFT OUTER');
		$builder->join('cfg_jenis_pilihan e','e.jenis_pilihan = a.jenis_pilihan AND e.tahun_ajaran_id=a.tahun_ajaran_id and e.putaran=a.putaran AND e.is_deleted=0','LEFT OUTER');
		$builder->join('cfg_jenis_pilihan f','f.jenis_pilihan = a.masuk_jenis_pilihan AND f.tahun_ajaran_id=a.tahun_ajaran_id and f.putaran=a.putaran AND f.is_deleted=0','LEFT OUTER');
		$builder->join('tcg_peserta_didik g','g.peserta_didik_id = a.peserta_didik_id AND g.is_deleted = 0','LEFT OUTER');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.cabut_berkas'=>0,'a.is_deleted'=>0));
        $builder->where('a.tahun_ajaran_id', $this->tahun_ajaran_id);

        //additional filters
        if ($filters!=null) {
            $builder->where($filters);
        }

        //can override putaran-aktif untuk jenjang SD
        if (empty($filters) || empty($filters['a.putaran'])) {
            $builder->where('a.putaran', $putaran);
        }

		$builder->orderBy('a.jenis_pilihan');

        //echo $builder->getCompiledSelect(); exit;

		return $builder->get()->getResultArray();

        // echo $this->db->getLastQuery(); exit;
	}

    function tcg_pendaftaran($peserta_didik_id, $pendaftaran_id){
        $this->error_message = null;
        
		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.*');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.pendaftaran_id'=>$pendaftaran_id,'a.cabut_berkas'=>0,'a.is_deleted'=>0));
		$builder->orderBy('a.created_on');
		return $builder->get()->getRowArray();
    }

	function tcg_pendaftaran_detil($peserta_didik_id, $pendaftaran_id){
        $this->error_message = null;
        
        $filters = array("a.pendaftaran_id"=>$pendaftaran_id);

        $result = $this->tcg_daftarpendaftaran($peserta_didik_id, $filters);
        if($result == null) return null;

        return $result[0];
	}    

	function tcg_pendaftaran_tambahan($peserta_didik_id, $pendaftaran_id){
        $this->error_message = null;
        
        $filters = array("a.ref_pendaftaran_id"=>$pendaftaran_id);

        $result = $this->tcg_daftarpendaftaran($peserta_didik_id, $filters);
        if($result == null) return null;

        return $result;
	}    

	function tcg_pendaftaran_from_tag($peserta_didik_id, $tag){
        $this->error_message = null;
        
        $filters = array("a.tag"=>$tag);

        $result = $this->tcg_daftarpendaftaran($peserta_didik_id, $filters);
        if($result == null) return null;

        return $result;
	}    

	function tcg_pendaftaran_diterima_sd($peserta_didik_id){
        $this->error_message = null;
        
        $filters = array("a.status_penerimaan_final"=>1, "a.putaran"=>PUTARAN_SD);

        $result = $this->tcg_daftarpendaftaran($peserta_didik_id, $filters);
        if($result == null) return null;

        return $result[0];
	}    
  
	function tcg_ubah_pilihansekolah($peserta_didik_id, $pendaftaran_id, $sekolah_id_baru){
        $this->error_message = null;
        $user_id = $this->session->get('user_id');
        
		$query = "CALL " .SQL_UBAH_PILIHANSEKOLAH. " (?, ?, ?, ?)";
		$status = $this->db->query($query, array($peserta_didik_id, $pendaftaran_id, $sekolah_id_baru, $user_id));
        if (!$status) return null;

        $message = $status->getRowArray();
        if ($message != null) {
            $this->error_message = $message['message'];
            return null;
        }

        //audit-trail: sudah di SP

        return $this->tcg_pendaftaran_detil($peserta_didik_id, $pendaftaran_id);
	}

	function tcg_ubah_jenispilihan($peserta_didik_id, $pendaftaran_id, $jenis_pilihan_baru){
        $this->error_message = null;
        $user_id = $this->session->get('user_id');

		$query = "CALL " .SQL_UBAH_JENISPILIHAN. " (?, ?, ?, ?)";
		$status = $this->db->query($query, array($peserta_didik_id, $pendaftaran_id, $jenis_pilihan_baru, $user_id));
        if (!$status) return null;

        $message = $status->getRowArray();
        if ($message != null) {
            $this->error_message = $message['message'];
            return null;
        }

        //audit-trail: sudah di SP

        return $this->tcg_pendaftaran_detil($peserta_didik_id, $pendaftaran_id);
	}

	function tcg_ubah_jalur($peserta_didik_id, $pendaftaran_id, $penerapan_id_baru) {
        $this->error_message = null;
        
        // //get tag to get the new pendaftaran
        // $pendaftaran = $this->tcg_pendaftaran($peserta_didik_id, $pendaftaran_id);
        // $tag = $pendaftaran['tag'];

        //ubah jalur
        $user_id = $this->session->get('user_id');
		$sql = "CALL " .SQL_UBAH_JALUR. "(?, ?, ?, ?)";
		$status = $this->db->query($sql, array($peserta_didik_id, $pendaftaran_id, $penerapan_id_baru, $user_id));
        if (!$status) return null;

        $message = $status->getRowArray();
        if ($message != null) {
            $this->error_message = $message['message'];
            return null;
        }

        //audit-trail: sudah di SP

        return $this->tcg_pendaftaran_from_tag($peserta_didik_id, $pendaftaran_id);
	}

	function tcg_cek_pendaftaran($peserta_didik_id, $filters = null){
        $this->error_message = null;
        $putaran = $this->session->get('putaran_aktif'); 
        
		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('COUNT(1) AS jumlah');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id, 'a.cabut_berkas'=>0,'a.is_deleted'=>0));
        $builder->where("a.tahun_ajaran_id", $this->tahun_ajaran_id);
        $builder->where("a.putaran", $putaran);

        if ($filters != null) {
            $builder->where($filters);
        }

        $result = $builder->get()->getRowArray();
        if ($result == null)    return 0;

		return $result['jumlah'];
	}

	function tcg_cek_pendaftaran_sekolah($peserta_didik_id, $penerapan_id, $sekolah_id){
        $this->error_message = null;
        $putaran = $this->session->get('putaran_aktif'); 
        
		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('COUNT(1) AS jumlah');
        $builder->join('cfg_penerapan b', 'b.penerapan_id=a.penerapan_id and b.is_deleted=0', 'LEFT OUTER');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id, 'a.cabut_berkas'=>0, 'a.is_deleted'=>0));
        $builder->where("a.sekolah_id", $sekolah_id);
        $builder->where("a.tahun_ajaran_id", $this->tahun_ajaran_id);
        $builder->where("a.putaran", $putaran);

        //penerapan pendaftaran mungkin beda dengan perangkingan nya
        //zonasi (101) otomatis dipindah ke zonasi1/zonasi2/zonasi3/zonasi4 (201, 202, 203, 204)
        //zonasi (101) otomatis didaftarkan di prestasi (102)
        $builder->groupStart();
        $builder->where("a.penerapan_id", $penerapan_id);
        $builder->orWhere("b.parent_id", $penerapan_id);
        $builder->groupEnd();

        $result = $builder->get()->getRowArray();
        if ($result == null)    return 0;

		return $result['jumlah'];
	}

	function tcg_cek_pendaftaran_sekolah_sd($peserta_didik_id, $sekolah_id){
        $this->error_message = null;
        
        $filters = array('a.sekolah_id'=>$sekolah_id);
        return $this->tcg_cek_pendaftaran($peserta_didik_id, $filters);
	}

	function tcg_cek_pendaftaran_jenispilihan($peserta_didik_id, $jenis_pilihan){
        $this->error_message = null;
        
        $filters = array('a.jenis_pilihan'=>$jenis_pilihan);
        return $this->tcg_cek_pendaftaran($peserta_didik_id, $filters);
	}

	function tcg_profilsekolah($sekolah_id){
        $this->error_message = null;
        
		$builder = $this->db->table('ref_sekolah a');
		$builder->select('a.sekolah_id,a.npsn,a.nama,a.bentuk as bentuk_pendidikan,a.bentuk,a.status,a.alamat_jalan,a.desa_kelurahan,a.kecamatan,a.kabupaten,a.lintang,a.bujur,a.inklusi,b.kuota_total');
		$builder->join('cfg_kuota_sekolah b','b.sekolah_id = a.sekolah_id and b.is_deleted=0 and b.tahun_ajaran_id='.$this->tahun_ajaran_id);
		$builder->where(array('a.sekolah_id'=>$sekolah_id, 'a.is_deleted'=>0));

		return $builder->get()->getRowArray();
	}    

	function tcg_batasanpilihan_negeri(){
        $this->error_message = null;
        
		$builder = $this->db->table('cfg_jenis_pilihan');
		$builder->select('COUNT(1) AS jumlah');
		$builder->where(array('pendaftaran'=>1,'expired_date'=>NULL,'sekolah_negeri'=>1,'sekolah_swasta'=>0,'tahun_ajaran_id'=>$this->tahun_ajaran_id));

        $result = $builder->get()->getRowArray();
        if ($result == null)    return 0;

        return $result['jumlah'];
	}

	function tcg_jumlahpendaftaran_negeri($peserta_didik_id){
        $this->error_message = null;
        
		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('COUNT(1) AS jumlah');
		$builder->join('ref_sekolah c','a.sekolah_id = c.sekolah_id AND c.status = "N"', 'inner join');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.cabut_berkas'=>0,'a.is_deleted'=>0, 'a.pendaftaran'=>1,'a.tahun_ajaran_id'=>$this->tahun_ajaran_id));

        $result = $builder->get()->getRowArray();
        if ($result == null)    return 0;

        return $result['jumlah'];
	}

	function tcg_batasanpilihan_swasta(){
        $this->error_message = null;
        
		$builder = $this->db->table('cfg_jenis_pilihan');
		$builder->select('COUNT(1) AS jumlah');
		$builder->where(array('pendaftaran'=>1,'expired_date'=>NULL,'sekolah_swasta'=>1,'sekolah_negeri'=>0,'tahun_ajaran_id'=>$this->tahun_ajaran_id));

        $result = $builder->get()->getRowArray();
        if ($result == null)    return 0;

        return $result['jumlah'];
	}

	function tcg_jumlahpendaftaran_swasta($peserta_didik_id){
        $this->error_message = null;
        
		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('COUNT(1) AS jumlah');
		$builder->join('ref_sekolah c','a.sekolah_id = c.sekolah_id AND c.status = "S"', 'inner join');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.cabut_berkas'=>0,'a.is_deleted'=>0, 'a.pendaftaran'=>1,'a.tahun_ajaran_id'=>$this->tahun_ajaran_id));

        $result = $builder->get()->getRowArray();
        if ($result == null)    return 0;

        return $result['jumlah'];
	}    

	function tcg_batasanpilihan(){
        $this->error_message = null;
        
		$builder = $this->db->table('cfg_jenis_pilihan');
		$builder->select('COUNT(1) AS jumlah');
		$builder->where(array('pendaftaran'=>1,'expired_date'=>NULL,'tahun_ajaran_id'=>$this->tahun_ajaran_id));

        $result = $builder->get()->getRowArray();
        if ($result == null)    return 0;

        return $result['jumlah'];
	}

	function tcg_jumlahpendaftaran($peserta_didik_id){
        $this->error_message = null;
        
		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('COUNT(1) AS jumlah');
		$builder->join('cfg_penerapan b','a.penerapan_id = b.penerapan_id AND b.expired_date IS NULL AND b.pendaftaran = 1', 'inner join');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.cabut_berkas'=>0,'a.is_deleted'=>0,'a.tahun_ajaran_id'=>$this->tahun_ajaran_id));

        $result = $builder->get()->getRowArray();
        if ($result == null)    return 0;

        return $result['jumlah'];
	}

	function tcg_daftar($peserta_didik_id, $penerapan_id, $sekolah_id, $jenis_pilihan){
        $this->error_message = null;
        
        $user_id = $this->session->get('user_id');
        $tag = uuid();

		$sql = "CALL " .SQL_PROCESS_PENDAFTARAN. " (?,?,?,?,?,?)";
		$status = $this->db->query($sql, array($penerapan_id,$sekolah_id,$peserta_didik_id,$jenis_pilihan,$user_id, $tag));

        $message = $status->getRowArray();
        if ($message != null) {
            $this->error_message = $message['message'];
            return null;
        }

        //audit-trail: sudah di SP

        return $this->tcg_pendaftaran_from_tag($peserta_didik_id, $tag);
	}

    function tcg_batasansiswa($peserta_didik_id){
        $this->error_message = null;
        
		$builder = $this->db->table('tcg_peserta_didik');
		$builder->select('cabut_berkas,hapus_pendaftaran,ubah_pilihan,ubah_sekolah,ubah_jalur');
		$builder->where(array('peserta_didik_id'=>$peserta_didik_id,'is_deleted'=>0));
		return $builder->get()->getRowArray();
	}

	function tcg_hapus_pendaftaran($peserta_didik_id, $pendaftaran_id, $keterangan) {
        $this->error_message = null;
        
        $pengguna_id = $this->session->get('user_id');
        
		$query = "CALL " .SQL_HAPUS_PENDAFTARAN. " (?, ?, ?, ?)";
		$status = $this->db->query($query, array($peserta_didik_id, $pendaftaran_id, $keterangan, $pengguna_id));
        if ($status == null)    return 0;
        
        $message = $status->getRowArray();
        if ($message != null) {
            $this->error_message = $message['message'];
            return 0;
        }

        //audit-trail: sudah di SP

        return 1;
	}

	// function tcg_simpan_dokumenpendukung($peserta_didik_id,$upload_id,$kelengkapan_id, $replace=1, $prestasi=0, $tambahan=0){
    //     $this->error_message = null;
        
    //     $user_id = $this->session->get('user_id');
	// 	$query = "call " .SQL_SIMPAN_DOKUMEN. "(?, ?, ?, ?, ?, ?, ?)";
	// 	$status = $this->db->query($query, array($upload_id,$peserta_didik_id,$kelengkapan_id,$replace,$prestasi,$tambahan,$user_id));
        
    //     $message = $status->getRowArray();
    //     if ($message != null) {
    //         $this->error_message = $message['message'];
    //         return 0;
    //     }

    //     $filters = array("a.dokumen_id"=>$upload_id);
    //     $result = $this->tcg_dokumenpendukung($peserta_didik_id, $filters);
    //     if ($result == null)    return null;

	// 	return $result[0];
	// }

	function tcg_hapus_dokumenpendukung($peserta_didik_id,$kelengkapan_id){
        $this->error_message = null;
        
        $sql = "select dokumen_id from tcg_dokumen_pendukung where peserta_didik_id=? and daftar_kelengkapan_id=? and is_deleted=0";
        $result = $this->db->query($sql, array($peserta_didik_id, $kelengkapan_id))->getResultArray();
        if ($result == null || count($result)==0) return 0;

		$sql = "update tcg_dokumen_pendukung set is_deleted=1 where peserta_didik_id=? and daftar_kelengkapan_id=? and is_deleted=0";
		$this->db->query($sql, array($peserta_didik_id, $kelengkapan_id));

        //audit-trail
        foreach($result as $k) {
            $this->audittrail->delete('tcg_dokumen_pendukung', $k['dokumen_id']);
        }

		return count($result);
	}

    function tcg_nama_dokumen($doc_id) {
        $this->error_message = null;
        
        $sql = "select nama from ref_daftar_kelengkapan where daftar_kelengkapan_id=? and is_deleted=0";
        $query = $this->db->query($sql, array($doc_id));
        if (!$query)     return null;

        $result = $query->getRowArray();
        return $result['nama'];
    }	
    
    function tcg_dokumenpendukung($peserta_didik_id, $filters=null) {
        $this->error_message = null;
        
		// daftar SEMUA dokumen pendukung
        $builder = $this->db->table("tcg_dokumen_pendukung a");
        $builder->select("a.dokumen_id, a.daftar_kelengkapan_id, b.nama, a.filename, a.path, a.web_path, a.thumbnail_path, a.verifikasi, a.catatan");
        $builder->select("b.dokumen_fisik, b.placeholder, a.berkas_fisik, a.tambahan, coalesce(a.tanggal_berkas, a.created_on) as tanggal_berkas");
        $builder->join("ref_daftar_kelengkapan b", "a.daftar_kelengkapan_id=b.daftar_kelengkapan_id and b.is_deleted=0");
        $builder->where("a.peserta_didik_id", $peserta_didik_id);
        $builder->where("a.is_deleted=0");

        //additional filters
        if ($filters != null) {
            $builder->where($filters);
        }
        
        return $builder->get()->getResultArray();
	}


	function tcg_dokumenpendukung_tambahan($peserta_didik_id) {
        $this->error_message = null;
        
        $filters = array("a.tambahan"=>"1");
        return $this->tcg_dokumenpendukung($peserta_didik_id, $filters);
	}


	function tcg_dokumenpendukung_from_kelengkapanid($peserta_didik_id, $daftar_kelengkapan_id) {
        $this->error_message = null;
        
        $filters = array("a.daftar_kelengkapan_id"=>"1");
        $result = $this->tcg_dokumenpendukung($peserta_didik_id, $filters);
        if ($result == null)    return null;
        
        return $result[0];
	}

	function tcg_nama_dokumenpendukung($daftar_kelengkapan_id) {
        $this->error_message = null;
        
        $sql = "select nama FROM ref_daftar_kelengkapan where is_deleted=0 and daftar_kelengkapan_id=?";
        $result = $this->db->query($sql, array($daftar_kelengkapan_id));
        if ($result == null)    return null;

        return $result->getRowArray()['nama'];
	}

	function tcg_verifikasi_dokumenpendukung($peserta_didik_id, $dokumen_id, $verifikasi, $catatan) {
        $user_id = $this->session->get('user_id');

		$query = "
		update tcg_dokumen_pendukung a
		set
			a.verifikasi = ?,
			a.catatan = ?,
			a.verifikator_id = ?,
			a.tanggal_verifikasi = now(),
			a.updated_on = now()
		where a.peserta_didik_id=? and a.dokumen_id=? and a.is_deleted=0";

		$status = $this->db->query($query, array($verifikasi, $catatan, $user_id, $peserta_didik_id, $dokumen_id));
        if ($status == null)    return 0;

        $retval = $this->db->affectedRows();

        //crud audit trail
        if ($retval > 0) {
			$keys = array("peserta_didik_id","dokumen_id","verifikasi","catatan","verifikator_id");
			$values = array("peserta_didik_id"=>$peserta_didik_id,"dokumen_id"=>$dokumen_id,"verifikasi"=>$verifikasi,"catatan"=>$catatan,"verifikator_id"=>$user_id);

			//put in audit trail
			$this->audittrail->trail("tcg_dokumen_pendukung",$peserta_didik_id,"UPDATE", "Verifikasi dokumen pendukung", $keys,$values);
		}

		return $retval;

	}
        
	function tcg_daftarprestasi($peserta_didik_id) {
        $this->error_message = null;
        
		$query = "
		select a.prestasi_id, a.skoring_id, b.nama as prestasi, a.uraian, a.dokumen_pendukung,
				c.filename as nama_dokumen, c.path, c.web_path, c.thumbnail_path, 
				c.verifikasi, c.catatan, c.created_on as tanggal_upload,
                coalesce(d.nilai,0) as nilai
		from tcg_prestasi a
		join ref_daftar_skoring b on a.skoring_id=b.skoring_id and b.is_deleted=0
		left join tcg_dokumen_pendukung c on a.dokumen_pendukung=c.dokumen_id and c.is_deleted=0
        left join cfg_daftar_nilai_skoring d on d.daftar_nilai_skoring_id = a.skoring_id 
            and d.tahun_ajaran_id=a.tahun_ajaran_id and d.nilai > 0 and d.is_deleted=0 
		where a.is_deleted=0 and a.peserta_didik_id=?";

		return $this->db->query($query, array($peserta_didik_id))->getResultArray();
	}

	function tcg_riwayat_verifikasi($peserta_didik_id) {
        $this->error_message = null;
        
		$sql = "SELECT a.riwayat_id, a.verifikator_id, b.nama, a.verifikasi, a.catatan_kekurangan, a.created_on 
		from tcg_riwayat_verifikasi a
		join dbo_users b on b.user_id=a.verifikator_id and b.is_deleted=0
		where a.is_deleted=0 and a.peserta_didik_id=?";

		return $this->db->query($sql, array($peserta_didik_id))->getResultArray();
	}        

	function tcg_tambah_riwayatverifikasi($peserta_didik_id, $verifikasi, $catatan) {
        $user_id = $this->session->get('user_id');

		$valuepair = array(
			'peserta_didik_id' => $peserta_didik_id,
			//'tahun_ajaran_id' => $this->tahun_ajaran_id,
			'verifikator_id' => $user_id,
			'verifikasi' => $verifikasi,
			'catatan_kekurangan' => $catatan
		);

        $builder = $this->db->table('tcg_riwayat_verifikasi');
        if ($builder->insert($valuepair)) {
            $key = $this->db->insertID();
			//update last verification flag
			$timestamp = date("Y/m/d H:i:s");
			$filter = array(
				'peserta_didik_id' => $peserta_didik_id,
				//'tahun_ajaran_id' => $this->tahun_ajaran_id,
				'is_deleted' => 0,
				'cabut_berkas' => 0
			);	
			$valuepair = array(
				'terakhir_verifikasi_oleh' => $user_id,
				'terakhir_verifikasi_timestamp' => $timestamp,
				'updated_on' => $timestamp
			);	
            $builder = $this->db->table('tcg_peserta_didik');
            $builder->where($filter);
			$builder->update($valuepair);

            //audittrail -> nggak perlu karena ini juga logging

            //return the id
            return $key;
        } else {
            return 0;
        }
	}   

	function tcg_update_kelengkapanberkas($peserta_didik_id, $value=null) {
        $user_id = $this->session->get('user_id');

		$query = "CALL " .SQL_UBAH_KELENGKAPANBERKAS. "(?, ?, ?)";
        
		$retval = $this->db->query($query, array($peserta_didik_id, $value, $user_id));
		if ($retval == null)    return 2;       //default : Belum lengkap

        //audit trail -> sudah di SP

        return $this->tcg_status_kelengkapanberkas($peserta_didik_id);
	}    

    function tcg_status_kelengkapanberkas($peserta_didik_id) {
        $sql = "select max(kelengkapan_berkas) as kelengkapan_berkas from tcg_pendaftaran where is_deleted=0 and cabut_berkas=0 and peserta_didik_id=?";

        $query = $this->db->query($sql, array($peserta_didik_id));
        if ($query == null)  return 0;

        return $query->getRowArray()['kelengkapan_berkas'];
    }

	function tcg_ubah_lokasiberkas($peserta_didik_id, $sekolah_id) {
		$data = array(
			'lokasi_berkas' => $sekolah_id,
			'updated_on' => date("Y/m/d H:i:s")
		);

        $builder = $this->db->table('tcg_peserta_didik');
		$builder->where(array('peserta_didik_id'=>$peserta_didik_id,'is_deleted'=>0));
		$retval = $builder->update($data);

		if ($retval > 0) {
			//audit trail
            $this->audittrail->trail("tcg_peserta_didik",$peserta_didik_id,'UPDATE','Ubah lokasi berkas', array_keys($data), $data);
		}

		return $retval;
	}

    function tcg_sebaransekolah($peserta_didik_id, $limit = 25) {
        $this->error_message = null;
        
		$query = "CALL " .SQL_SEBARAN_SEKOLAH. " (?, ?)";
		return $this->db->query($query, array($peserta_didik_id, $limit))->getResultArray();
    }

    function tcg_nilaiskoring($pendaftaran_id){

		$builder = $this->db->table('tcg_skoring_pendaftaran a');
		$builder->select('a.skoring_pendaftaran_id,c.nama AS keterangan,round(a.nilai,2) as nilai');
		$builder->join('tcg_pendaftaran b','a.pendaftaran_id = b.pendaftaran_id AND b.cabut_berkas = 0 AND b.is_deleted = 0');
		$builder->join('ref_daftar_skoring c','a.skoring_id = c.skoring_id AND c.is_deleted=0');
		//$builder->join('cfg_daftar_nilai_skoring d','a.skoring_id = d.skoring_id and b.tahun_ajaran_id=d.tahun_ajaran_id AND c.is_deleted=0');
		$builder->where(array('a.pendaftaran_id'=>$pendaftaran_id,'a.is_deleted'=>0));
		$builder->orderBy('c.nama');
		return $builder->get()->getResultArray();

	}

	function tcg_kelengkapanpendaftaran($pendaftaran_id){
		$builder = $this->db->table('tcg_kelengkapan_pendaftaran a');
		$builder->select('a.dokumen_id,c.nama AS kelengkapan,a.verifikasi');
		$builder->select('case when c.wajib=1 or coalesce(b.wajib,1)=1 then 1 else 0 end as wajib', false);
		$builder->select('coalesce(b.kondisi_khusus,0) as kondisi_khusus');
		$builder->select('d.deskripsi,d.filename,d.web_path,d.thumbnail_path');
		$builder->join('cfg_kelengkapan_penerapan b','b.kelengkapan_penerapan_id = a.kelengkapan_penerapan_id AND b.is_deleted=0', 'LEFT OUTER');
		$builder->join('ref_daftar_kelengkapan c','c.daftar_kelengkapan_id = a.daftar_kelengkapan_id AND c.is_deleted=0');
		$builder->join('tcg_dokumen_pendukung d','d.dokumen_id = a.dokumen_id AND d.is_deleted=0');
		$builder->where(array('a.pendaftaran_id'=>$pendaftaran_id,'a.is_deleted'=>0));
		$builder->orderBy('c.daftar_kelengkapan_id');

        // $sql = $builder->getCompiledSelect();
        // echo $sql;

		return $builder->get()->getResultArray();
	}

    function tcg_cek_nik($nik) {
        $sql = "select count(*) as jumlah from tcg_peserta_didik where is_deleted=0 and nik=?";

        $result = $this->db->query($sql, array($nik))->getRowArray();
        if ($result == null)    return 0;

        return $result['jumlah'];
    }

    function tcg_cek_nisn($nisn) {
        if (strtoupper($nisn) == 'NA') {
            //special case: siswa yang belum punya nisn
            return 0;
        }

        $sql = "select count(*) as jumlah from tcg_peserta_didik where is_deleted=0 and nisn=?";

        $result = $this->db->query($sql, array($nisn))->getRowArray();
        if ($result == null)    return 0;

        return $result['jumlah'];
    }


    //---


    // function tcg_prestasi($peserta_didik_id) {

	// 	$query = "SELECT distinct(a.prestasi_skoring_id) as daftar_nilai_skoring_id, `b`.`nama`, coalesce(`c`.`nilai`,0) as nilai, 1 as `verifikasi`, '' as verifikator_id 
	// 	FROM `tcg_pendaftaran` `a` 
	// 	JOIN `ref_daftar_skoring` `b` ON `b`.`skoring_id` = `a`.`prestasi_skoring_id` and `b`.`expired_date` is null 
	// 	left outer join `cfg_daftar_nilai_skoring` `c` on `c`.`daftar_nilai_skoring_id` = `a`.`prestasi_skoring_id` and `c`.`tahun_ajaran_id`=`a`.`tahun_ajaran_id` and `c`.`nilai` > 0 and `c`.`expired_date` is null 
	// 	WHERE `a`.`peserta_didik_id` = ? AND `a`.`is_deleted` = 0 AND `a`.`cabut_berkas` = 0 
	// 	AND `a`.`prestasi_skoring_id` IS NOT NULL AND `a`.`prestasi_skoring_id` != 0";

	// 	return $this->db->query($query, array($peserta_didik_id));
	// }


	// function tcg_audit_trail($table, $reference, $action, $description, $old_values, $new_values) {
	// 	$user_id = $this->session->get("user_id");

    //     //TODO
	// 	// $query = "CALL usp_audit_trail(?,?,?,?,?,?,?,?)";
	// 	// return $this->db->query($query, array($table,$reference,$action,$user_id,$description,null,$new_values,$old_values));
	// }

	function tcg_prestasi_detil($peserta_didik_id, $key) {
		$query = "
		select a.prestasi_id, a.skoring_id, b.nama as prestasi, a.uraian, a.dokumen_pendukung,
				c.filename, c.filesize, c.path, c.web_path, c.thumbnail_path, c.created_on as tanggal_upload,
				c.path as path, c.catatan
		from tcg_prestasi a
		join ref_daftar_skoring b on a.skoring_id=b.skoring_id and b.is_deleted=0
		left join tcg_dokumen_pendukung c on a.dokumen_pendukung=c.dokumen_id and c.is_deleted=0
		where a.is_deleted=0 and a.prestasi_id=?";

		return $this->db->query($query, array($key));
	}


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
	// 	$builder->join('dbo_users d','a.peserta_didik_id = d.peserta_didik_id AND d.is_deleted = 0','LEFT OUTER');
	// 	$builder->join('ref_sekolah e','e.sekolah_id = a.lokasi_berkas','LEFT OUTER');
	// 	// $builder->join('ref_wilayah c4','c.kode_wilayah_desa = c4.kode_wilayah AND c4.is_deleted=0','LEFT OUTER');
	// 	// $builder->join('ref_wilayah c3','c.kode_wilayah_kec = c3.kode_wilayah AND c3.is_deleted=0','LEFT OUTER');
	// 	// $builder->join('ref_wilayah c2','c.kode_wilayah_kab = c2.kode_wilayah AND c2.is_deleted=0','LEFT OUTER');
	// 	// $builder->join('ref_wilayah c1','c.kode_wilayah_prov = c1.kode_wilayah AND c1.is_deleted=0','LEFT OUTER');
	// 	$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.is_deleted'=>0));

	// 	return $builder->get();
	// }

	function tcg_daftarulang_dokumenpendukung($peserta_didik_id, $dokumen_id, $status, $penerima_berkas_id) {
		$query = "
		update tcg_dokumen_pendukung a
		set
			a.berkas_fisik = ?,
			a.penerima_berkas_id = ?,
			a.tanggal_berkas = now(),
			a.updated_on = now()
		where a.peserta_didik_id=? and a.dokumen_id=? and a.is_deleted=0";

		$this->db->query($query, array($status, $penerima_berkas_id, $peserta_didik_id, $dokumen_id));

		$retval = $this->db->affectedRows();
		if ($retval > 0) {
			$keys = array("peserta_didik_id","dokumen_id","daftarulang","penerima_berkas_id");
			$values = array("peserta_didik_id"=>$peserta_didik_id,"dokumen_id"=>$dokumen_id,"daftarulang"=>$status,"penerima_berkas_id">$penerima_berkas_id);

			//put in audit trail
            $this->audittrail->trail("tcg_dokumen_pendukung", $peserta_didik_id, "UPDATE", "Daftar ulang dokumen fisik", $keys, $values);
		}

		return $retval;

	} 

	// function tcg_profilsiswa_klarifikasidinas($peserta_didik_id){
	// 	$this->tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

	// 	$sql = "select a.peserta_didik_id,
	// 					case when (b.tipe_data = 'profil') then 1 else 0 end as klarifikasi_dinas_profil,
	// 					case when (b.tipe_data = 'profil') then b.catatan_dinas else null end as catatan_dinas_profil,
	// 					case when (b.tipe_data = 'lokasi') then 1 else 0 end as klarifikasi_dinas_lokasi,
	// 					case when (b.tipe_data = 'lokasi') then b.catatan_dinas else null end as catatan_dinas_lokasi
	// 			from tcg_peserta_didik a
	// 			left join (
	// 				select 
	// 					b.peserta_didik_id, b.tipe_data,
	// 					group_concat(catatan_dinas, '<br>') as catatan_dinas
	// 				from tcg_verifikasi_dinas b 
	// 				where b.peserta_didik_id=? and b.tahun_ajaran_id=? and b.verifikasi=1 and b.is_deleted=0
	// 				group by b.peserta_didik_id, b.tipe_data
	// 			) as b on a.peserta_didik_id=b.peserta_didik_id
	// 			where 
	// 				a.peserta_didik_id=? and a.tahun_ajaran_id=? and a.is_deleted=0
	// 		";

	// 	return $this->db->query($sql, array($peserta_didik_id, $this->tahun_ajaran_id, $peserta_didik_id, $this->tahun_ajaran_id));
	// }    

	function tcg_berkas_fisik($peserta_didik_id) {
		$query = "select a.dokumen_id, a.daftar_kelengkapan_id, b.nama, 
					a.berkas_fisik, a.penerima_berkas_id, a.tanggal_berkas,
					c.nama as penerima_berkas, d.nama as sekolah
				  from tcg_dokumen_pendukung a
				  join ref_daftar_kelengkapan b on a.daftar_kelengkapan_id=b.daftar_kelengkapan_id and b.is_deleted=0
				  left join dbo_users c on c.is_deleted=0 and c.user_id=a.penerima_berkas_id
				  left join ref_sekolah d on d.is_deleted=0 and d.sekolah_id=c.sekolah_id
				  where a.peserta_didik_id=? and a.is_deleted=0 and b.dokumen_fisik=1";

		return $this->db->query($query, array($peserta_didik_id));
	}

	function tcg_verifikasi_berkas_fisik($peserta_didik_id, $dokumen_id, $value, $verifikator_id) {
		// $peserta_didik_id = secure($peserta_didik_id);
		// $dokumen_id = secure($dokumen_id);
		// $value = secure($value);
		// $verifikator_id = secure($verifikator_id);

		// $tanggal_verifikasi = secure(date("Y/m/d H:i:s"));

		if ($value == 1) {
			$query = "
			update tcg_dokumen_pendukung a
			set
				a.berkas_fisik = 1,
				a.penerima_berkas_id = ?,
				a.tanggal_berkas = now(),
				a.updated_on = now()
			where a.peserta_didik_id=? and a.dokumen_id=? and a.is_deleted=0";

			$this->db->query($query, array($verifikator_id, $peserta_didik_id, $dokumen_id));
		}
		else {
			$verifikator_id = null;
			$query = "
			update tcg_dokumen_pendukung a
			set
				a.berkas_fisik = 0,
				a.penerima_berkas_id = null,
				a.tanggal_berkas = null,
				a.updated_on = now()
			where a.peserta_didik_id=? and a.dokumen_id=? and a.is_deleted=0";

			$this->db->query($query, array($peserta_didik_id, $dokumen_id));
		}

		// $this->db->query($query);

		$retval = $this->db->affectedRows();
		if ($retval > 0) {
			$keys = array("peserta_didik_id","dokumen_id","berkas_fisik","penerima_berkas_id");
			$values = array("peserta_didik_id"=>$peserta_didik_id,"dokumen_id"=>$dokumen_id,"berkas_fisik"=>$value,"penerima_berkas_id"=>$verifikator_id);

			//put in audit trail
			$this->audittrail->trail("tcg_dokumen_pendukung",$peserta_didik_id,'UPDATE','Verifikasi berkas fisik',$keys,$values);
		}

		return $retval;

	}

	function tcg_verifikasi_berkas($peserta_didik_id, $tahun_ajaran_id, $dokumen_id, $verifikasi, $verifikator_id) {
		$query = "
		update tcg_kelengkapan_pendaftaran a
		join tcg_pendaftaran d on a.pendaftaran_id=d.pendaftaran_id and d.is_deleted=0 and d.cabut_berkas=0
		set
			a.verifikasi = ?,
			a.verifikator_id = ?,
			a.updated_on = now()
		where d.peserta_didik_id=? and d.tahun_ajaran_id=? and a.dokumen_id=? and a.is_deleted=0";

		$this->db->query($query, array($verifikasi, $verifikator_id, $peserta_didik_id, $this->tahun_ajaran_id, $dokumen_id));

		$retval = $this->db->affectedRows();
		if ($retval > 0) {
			$keys = array("peserta_didik_id","dokumen_id","verifikasi","verifikator_id");
			$values = array("peserta_didik_id"=>$peserta_didik_id,"dokumen_id"=>$dokumen_id,"verifikasi"=>$verifikasi,"verifikator_id"=>$verifikator_id);

			//put in audit trail
			$this->audittrail->trail("tcg_kelengkapan_pendaftaran",$peserta_didik_id,'UPDATE','Verifikasi berkas',$keys,$values);
		}

		return $retval;

	}

	function tcg_verifikasi_siswa($peserta_didik_id, $valuepair, $user_id) {	
		//enforce last-update
		$valuepair['verifikator_id'] = $user_id;
		$valuepair['tanggal_verifikasi'] = date("Y/m/d H:i:s");
		$valuepair['updated_on'] = date("Y/m/d H:i:s");

        $builder = $this->db->table('tcg_peserta_didik');
		$builder->where(array('peserta_didik_id'=>$peserta_didik_id,'is_deleted'=>0));
		$retval = $builder->update($valuepair);

		if ($retval > 0) {
			//put in audit trail
			$this->audittrail->trail("tcg_peserta_didik",$peserta_didik_id,'UPDATE','Update status verifikasi',array_keys($valuepair),$valuepair);
		}

		return $retval;	
	}



	function tcg_dokumen_pendukung_hilang($peserta_didik_id, $daftar_kelengkapan_id, $user_id, $catatan) {
		// $query = $this->tcg_dokumen_pendukung_kelengkapan_id($peserta_didik_id, $daftar_kelengkapan_id);
		// if ($query->num_rows() > 0) {
		// 	return;
		// }

		$valuepair = array(
			'peserta_didik_id' => $peserta_didik_id,		
			'daftar_kelengkapan_id' => $daftar_kelengkapan_id,
			'filename' => '',
			'filesize' => 0,
			'path' => '',
			'thumbnail_path' => '',
			'web_path' => '',
			'verifikasi' => 2,
			'verifikator_id' => $user_id,
			'tanggal_verifikasi' => date("Y/m/d H:i:s"),
			'catatan' => $catatan
		);

        $builder = $this->db->table('tcg_dokumen_pendukung');
		$builder->insert($valuepair);

        $dokumen_id = $this->db->insertID();
        $valuepair["dokumen_id"] = $dokumen_id;

        $this->audittrail->trail('tcg_dokumen_pendukung',$peserta_didik_id,'INSERT', "Insert dokumen hilang", array_keys($valuepair), $valuepair);
	}

	function tcg_verifikasidinas_baru($peserta_didik_id, $tipe_data, $kelengkapan_id, $catatan) {
		$user_id = $this->session->get('user_id');
		$sekolah_id = $this->session->get('sekolah_id');
		$this->tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$valuepair = array(
			'peserta_didik_id' 	=> $peserta_didik_id,
			'tahun_ajaran_id'	=> $this->tahun_ajaran_id,
			'pengguna_sekolah'	=> $user_id,
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
        $this->audittrail->trail('tcg_verifikasi_dinas',$key,'INSERT', "Verifikasi dinas baru", array_keys($valuepair), $valuepair);

        return $key;
	}    

    function tcg_updatekebutuhankhusus($peserta_didik_id, $kebutuhan_khusus, $user_id) {
        //TODO
    }

    // function tcg_profilsiswa($peserta_didik_id){

	// 	$builder = $this->db->table('tcg_peserta_didik a');
	// 	$builder->select("a.peserta_didik_id,a.sekolah_id,b.npsn,b.nama AS sekolah,
	// 	a.nik,a.nisn,a.nomor_ujian,a.nama,a.jenis_kelamin,a.tempat_lahir,a.tanggal_lahir,a.nama_ibu_kandung,a.nama_ayah,a.nama_wali,
	// 	a.rt,a.rw,a.alamat,a.kode_wilayah,
	// 	'' as kode_padukuhan, a.nama_dusun AS padukuhan,
	// 	c.kode_wilayah_desa as kode_desa, coalesce(c.nama_desa,a.desa_kelurahan) AS desa_kelurahan,
	// 	c.kode_wilayah_kec as kode_kecamatan,c.nama_kec AS kecamatan,
	// 	c.kode_wilayah_kab as kode_kabupaten,c.nama_kab AS kabupaten,
	// 	c.kode_wilayah_prov,c.nama_prov AS provinsi,
	// 	a.lintang,a.bujur,a.asal_data, 
	// 	d.user_name,a.nomor_kontak,
	// 	coalesce(a.punya_nilai_un,0) as punya_nilai_un,coalesce(a.punya_prestasi,0) as punya_prestasi,
	// 	coalesce(a.punya_kip,0) as punya_kip,
	// 	coalesce(a.masuk_bdt,0) as masuk_bdt,a.kebutuhan_khusus,
	// 	a.cabut_berkas,a.hapus_pendaftaran,a.ubah_pilihan,a.ubah_sekolah,a.batal_verifikasi");
	// 	$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id','LEFT OUTER');
	// 	$builder->join('ref_wilayah c','a.kode_wilayah = c.kode_wilayah AND c.is_deleted=0','LEFT OUTER');
	// 	$builder->join('dbo_users d','a.peserta_didik_id = d.peserta_didik_id AND d.is_deleted = 0','LEFT OUTER');
	// 	//case when (a.no_kip is null or trim(a.no_kip) = '' or a.no_kip = '0' or a.no_kip = '-') then 0 else 1 end as punya_kip,
	// 	// $builder->join('ref_wilayah c4','c.kode_wilayah_desa = c4.kode_wilayah AND c4.is_deleted=0','LEFT OUTER');
	// 	// $builder->join('ref_wilayah c3','c.kode_wilayah_kec = c3.kode_wilayah AND c3.is_deleted=0','LEFT OUTER');
	// 	// $builder->join('ref_wilayah c2','c.kode_wilayah_kab = c2.kode_wilayah AND c2.is_deleted=0','LEFT OUTER');
	// 	// $builder->join('ref_wilayah c1','c.kode_wilayah_prov = c1.kode_wilayah AND c1.is_deleted=0','LEFT OUTER');
	// 	$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.is_deleted'=>0));

	// 	return $builder->get();
	// }

	// function tcg_profilsiswa_status($peserta_didik_id){

	// 	$builder = $this->db->table('tcg_peserta_didik a');
	// 	$builder->select("a.verifikasi_profil,a.verifikasi_lokasi,a.verifikasi_nilai,a.verifikasi_prestasi,a.verifikasi_afirmasi,a.verifikasi_inklusi,a.verifikasi_dokumen,
	// 	a.verifikator_id,e.nama as nama_verifikator,a.tanggal_verifikasi,
	// 	a.konfirmasi_profil,a.konfirmasi_lokasi,a.konfirmasi_nilai,a.konfirmasi_prestasi,a.konfirmasi_afirmasi,a.konfirmasi_inklusi,
	// 	coalesce(a.punya_nilai_un,0) as punya_nilai_un,coalesce(a.punya_prestasi,0) as punya_prestasi,
	// 	coalesce(a.punya_kip,0) as punya_kip,
	// 	coalesce(a.masuk_bdt,0) as masuk_bdt,
	// 	a.nomor_kontak,a.surat_pernyataan_kebenaran_dokumen,
	// 	f.filename as nama_surat_pernyataan,
	// 	f.path as path_surat_pernyataan,
	// 	f.web_path as img_surat_pernyataan,
	// 	f.thumbnail_path as thumbnail_surat_pernyataan,
	// 	f.created_on as tanggal_surat_pernyataan
	// 	");
	// 	$builder->join('dbo_users e','a.verifikator_id = e.user_id AND e.is_deleted = 0','LEFT OUTER');
	// 	$builder->join('tcg_dokumen_pendukung f','a.surat_pernyataan_kebenaran_dokumen = f.dokumen_id AND (a.peserta_didik_id=f.peserta_didik_id or a.surat_pernyataan_kebenaran_dokumen=1) AND f.is_deleted = 0','LEFT OUTER');
	// 	$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.is_deleted'=>0));

	// 	return $builder->get()->getRowArray();
	// }    


    // function tcg_kelengkapanpendaftaran_berkasfisik($pendaftaran_id){
	// 	$builder = $this->db->table('tcg_pendaftaran a');
	// 	$builder->select('b.dokumen_id,c.nama AS kelengkapan,b.berkas_fisik,d.kondisi_khusus,d.wajib');
	// 	$builder->join('tcg_dokumen_pendukung b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
	// 	$builder->join('ref_daftar_kelengkapan c','b.daftar_kelengkapan_id = c.daftar_kelengkapan_id AND c.is_deleted=0');
	// 	$builder->join('cfg_kelengkapan_penerapan d','d.daftar_kelengkapan_id = c.daftar_kelengkapan_id AND d.is_deleted=0');
	// 	$builder->where(array('a.pendaftaran_id'=>$pendaftaran_id,'a.is_deleted'=>0,'c.dokumen_fisik'=>1));
	// 	$builder->orderBy('c.daftar_kelengkapan_id');
	// 	return $builder->get();
	// }




	// function tcg_daftar_prestasi_files($peserta_didik_id) {
	// 	$query = "
	// 	select a.dokumen_pendukung as id,
	// 			c.filename, c.filesize, c.path, c.web_path, c.thumbnail_path, c.created_on as tanggal_upload
	// 	from tcg_prestasi a
	// 	join ref_daftar_skoring b on a.skoring_id=b.skoring_id and b.is_deleted=0
	// 	left join tcg_dokumen_pendukung c on a.dokumen_pendukung=c.dokumen_id and c.is_deleted=0
	// 	where a.is_deleted=0 and a.peserta_didik_id=?";

	// 	return $this->db->query($query, array($peserta_didik_id));
	// }

	// function tcg_hapus_prestasi($peserta_didik_id, $key) {
	// 	$file_path = "";
	// 	$thumbnail_path = "";
	// 	$dokumen_id = 0;

	// 	$query = $this->tcg_prestasi_detil($peserta_didik_id, $key);
	// 	foreach($query->getResult() as $row) {
	// 		$file_path = $row->web_path;
	// 		$thumbnail_path = $row->thumbnail_path;
	// 		$dokumen_id = $row->dokumen_pendukung;
	// 	}

	// 	//delete entry in tcg_dokumen_pendukung
	// 	if (!empty($dokumen_id)) {
    //         $builder = $this->db->table('tcg_dokumen_pendukung');
	// 		$builder->where('dokumen_id', $dokumen_id);
	// 		$builder->delete();
	// 	}

	// 	//delete entry in tcg_prestasi
    //     $builder = $this->db->table('tcg_prestasi');
	// 	$builder->where('prestasi_id', $key);
	// 	$builder->delete();

	// }

	// function tcg_prestasi_baru($peserta_didik_id, $valuepair) {
	// 	//dokumen bukti prestasi
	// 	$kelengkapan_id = 8;

	// 	//inject peserta_didik_id
    //     $valuepair['peserta_didik_id'] = $peserta_didik_id;

    //     $builder = $this->db->table('tcg_prestasi');
    //     if ($builder->insert($valuepair)) {
    //         $key = $this->db->insertID();
	// 		//simpan dokumen pendukung
    //         if (!empty($valuepair['dokumen_pendukung'])) {
    //             $dokumen_pendukung = $valuepair['dokumen_pendukung'];
    //             if (!empty($dokumen_pendukung) && !empty($key)) {
    //                 $query = "call usp_simpan_dokumen_pendukung_2021(?,?,?,0,1,0)";
    //                 $this->db->query($query, array($dokumen_pendukung,$peserta_didik_id,$kelengkapan_id));
    //             }
    //         }
    //         //return the id
    //         return $key;
    //     } else {
    //         return 0;
    //     }
	// }


}

