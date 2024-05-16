<?php 

namespace App\Models\Ppdb\Siswa;

Class Mprofilsiswa 
{

    protected $db;
    protected $session;
    protected $tahun_ajaran_id;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");
    }
    
	function tcg_profilsekolah($sekolah_id){
		//$sekolah_id = $this->session->userdata("sekolah_id");
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_id");
		if (empty($tahun_ajaran_id)) {
			$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		}

		$builder = $this->db->table('ref_sekolah a');
		$builder->select('a.sekolah_id,a.npsn,a.nama,a.bentuk as bentuk_pendidikan,a.bentuk,a.status,a.alamat_jalan,a.desa_kelurahan,a.kecamatan,a.kabupaten,a.lintang,a.bujur,a.inklusi,b.kuota_total');
		$builder->join('tcg_kuota_sekolah b','b.sekolah_id = a.sekolah_id and b.is_deleted=0 and b.tahun_ajaran_id='.$tahun_ajaran_id);
		$builder->where(array('a.sekolah_id'=>$sekolah_id, 'a.is_deleted'=>0));

		return $builder->get();
	}

	function tcg_audit_trail($table, $reference, $action, $description, $old_values, $new_values) {
		$pengguna_id = $this->session->get("pengguna_id");

		$query = "CALL usp_audit_trail(?,?,?,?,?,?,?,?)";
		return $this->db->query($query, array($table,$reference,$action,$pengguna_id,$description,null,$new_values,$old_values));
	}

	function tcg_daftarpendaftaran($peserta_didik_id){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
        $putaran = $this->session->get('putaran_aktif');

		$tahun_ajaran_id = $_GET["tahun_ajaran_id"] ?? null;
		if (empty($tahun_ajaran_id)) {
			$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		}

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.penerapan_id,d.jalur_id,d.nama AS jalur,
							a.nomor_pendaftaran,a.jenis_pilihan,a.status_penerimaan,a.cabut_berkas,a.masuk_jenis_pilihan,
							a.peringkat,a.skor,a.kelengkapan_berkas,
							a.status_penerimaan_final,a.peringkat_final,
							a.sekolah_id,b.npsn,b.nama AS sekolah,b.bentuk,b.status as status_sekolah,
							a.pendaftaran, e.keterangan as label_jenis_pilihan, f.keterangan as label_masuk_pilihan,
							a.created_on, a.status_daftar_ulang, a.pendaftaran');
		$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id');
		$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.tahun_ajaran_id=a.tahun_ajaran_id and c.putaran=a.putaran AND c.aktif = 1 AND c.expired_date is NULL');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.expired_date IS NULL');
		$builder->join('ref_jenis_pilihan e','e.jenis_pilihan = a.jenis_pilihan AND e.tahun_ajaran_id=a.tahun_ajaran_id and e.putaran=a.putaran AND e.expired_date IS NULL','LEFT OUTER');
		$builder->join('ref_jenis_pilihan f','f.jenis_pilihan = a.masuk_jenis_pilihan AND f.tahun_ajaran_id=a.tahun_ajaran_id and f.putaran=a.putaran AND f.expired_date IS NULL','LEFT OUTER');
		//$builder->join('tcg_skoring_pendaftaran g','a.pendaftaran = f.pendaftaran_id AND f.is_deleted = 0','LEFT OUTER');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.cabut_berkas'=>0,'a.is_deleted'=>0));
        $builder->where('a.tahun_ajaran_id', $tahun_ajaran_id);
        $builder->where('a.putaran', $putaran);
		$builder->orderBy('a.jenis_pilihan');

        //echo $builder->getQuery(); exit;

		return $builder->get();

        // echo $this->db->getLastQuery(); exit;
	}

	function tcg_prestasi_detil($peserta_didik_id, $key) {
		$query = "
		select a.prestasi_id, a.skoring_id, b.nama as prestasi, a.uraian, a.dokumen_pendukung,
				c.filename, c.filesize, c.path, c.web_path, c.thumbnail_path, c.created_on as tanggal_upload,
				c.path as path, c.catatan
		from tcg_prestasi a
		join ref_daftar_skoring b on a.skoring_id=b.skoring_id and b.expired_date is null
		left join tcg_dokumen_pendukung c on a.dokumen_pendukung=c.dokumen_id and c.is_deleted=0
		where a.is_deleted=0 and a.prestasi_id=?";

		return $this->db->query($query, array($key));
	}

	function tcg_riwayat_verifikasi($peserta_didik_id) {
		$sql = "SELECT a.riwayat_id, a.verifikator_id, b.nama, a.verifikasi, a.catatan_kekurangan, a.created_on 
		from tcg_riwayat_verifikasi a
		join dbo_users b on b.pengguna_id=a.verifikator_id and b.is_deleted=0
		where a.is_deleted=0 and a.peserta_didik_id=?";

		return $this->db->query($sql, array($peserta_didik_id));
	}    

	function tcg_daftar_prestasi($peserta_didik_id) {
		$query = "
		select a.prestasi_id, a.skoring_id, b.nama as prestasi, a.uraian, a.dokumen_pendukung,
				c.filename as nama_dokumen, c.path, c.web_path, c.thumbnail_path, 
				c.verifikasi, c.catatan,
				c.created_on as tanggal_upload
		from tcg_prestasi a
		join ref_daftar_skoring b on a.skoring_id=b.skoring_id and b.expired_date is null
		left join tcg_dokumen_pendukung c on a.dokumen_pendukung=c.dokumen_id and c.is_deleted=0
		where a.is_deleted=0 and a.peserta_didik_id=?";

		return $this->db->query($query, array($peserta_didik_id));
	}

	function tcg_detailpendaftaran($peserta_didik_id, $pendaftaran_id){

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.penerapan_id,d.jalur_id,d.nama AS jalur,
							a.nomor_pendaftaran,a.jenis_pilihan,g.keterangan as label_jenis_pilihan, a.status_penerimaan,a.cabut_berkas,a.masuk_jenis_pilihan,a.peringkat,
							a.sekolah_id,b.npsn,b.nama AS sekolah,b.lintang AS lintang_sekolah,b.bujur AS bujur_sekolah, 
							a.peserta_didik_id,e.nisn,e.nama,f.lintang AS lintang_siswa,f.bujur AS bujur_siswa,
							f.sekolah_id as asal_sekolah_id,b.npsn as asal_sekolah_npsn,b.nama AS asal_sekolah, 
							a.status_daftar_ulang,a.tanggal_daftar_ulang, a.status_penerimaan_final,a.peringkat_final,
							a.created_on');
		$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id');
		$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1 AND c.expired_date is NULL');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.expired_date IS NULL');
		$builder->join('tcg_peserta_didik e','a.peserta_didik_id = e.peserta_didik_id AND e.is_deleted = 0');
		$builder->join('ref_sekolah f','e.sekolah_id = f.sekolah_id');
		$builder->join('ref_jenis_pilihan g','g.jenis_pilihan = a.jenis_pilihan AND g.tahun_ajaran_id=a.tahun_ajaran_id AND g.expired_date is NULL');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.pendaftaran_id'=>$pendaftaran_id,'a.cabut_berkas'=>0,'a.is_deleted'=>0));
		$builder->orderBy('a.created_on');
		return $builder->get();
	}    

	function tcg_profilsiswa_daftarulang($peserta_didik_id){

		$builder = $this->db->table('tcg_peserta_didik a');
		$builder->select("a.peserta_didik_id,a.sekolah_id,b.npsn,b.nama AS sekolah,
		a.nik,a.nisn,a.nomor_ujian,a.nama,a.jenis_kelamin,a.tempat_lahir,a.tanggal_lahir,a.nama_ibu_kandung,a.nama_ayah,a.nama_wali,a.kebutuhan_khusus,
		a.rt,a.rw,a.alamat,a.kode_wilayah,
		'' as kode_padukuhan,'' AS padukuhan,
		c.kode_wilayah_desa as kode_desa, c.nama_desa AS desa_kelurahan,
		c.kode_wilayah_kec as kode_kecamatan,c.nama_kec AS kecamatan,
		c.kode_wilayah_kab as kode_kabupaten,c.nama_kab AS kabupaten,
		c.kode_wilayah_prov,c.nama_prov AS provinsi,
		a.lintang,a.bujur,a.asal_data, 
		d.user_name as username,a.nomor_kontak,
		coalesce(a.punya_nilai_un,0) as punya_nilai_un,coalesce(a.punya_prestasi,0) as punya_prestasi,
		coalesce(a.punya_kip,0) as punya_kip,
		coalesce(a.masuk_bdt,0) as masuk_bdt,
		e.nama as lokasi_berkas
		");
		$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id','LEFT OUTER');
		$builder->join('ref_wilayah c','a.kode_wilayah = c.kode_wilayah AND c.expired_date IS NULL','LEFT OUTER');
		$builder->join('dbo_users d','a.peserta_didik_id = d.pengguna_id AND d.is_deleted = 0','LEFT OUTER');
		$builder->join('ref_sekolah e','e.sekolah_id = a.lokasi_berkas','LEFT OUTER');
		// $builder->join('ref_wilayah c4','c.kode_wilayah_desa = c4.kode_wilayah AND c4.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah c3','c.kode_wilayah_kec = c3.kode_wilayah AND c3.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah c2','c.kode_wilayah_kab = c2.kode_wilayah AND c2.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah c1','c.kode_wilayah_prov = c1.kode_wilayah AND c1.expired_date IS NULL','LEFT OUTER');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.is_deleted'=>0));

		return $builder->get();
	}

	function tcg_dokumen_pendukung($peserta_didik_id) {
		// daftar SEMUA dokumen pendukung
		$query = "
				  select a.dokumen_id, a.daftar_kelengkapan_id, b.nama, a.filename, a.path, a.web_path, a.thumbnail_path, a.verifikasi, a.catatan,
					b.dokumen_fisik, b.placeholder, a.berkas_fisik
				  from tcg_dokumen_pendukung a
				  join ref_daftar_kelengkapan b on a.daftar_kelengkapan_id=b.daftar_kelengkapan_id and b.expired_date is null
				  where a.peserta_didik_id=? and a.is_deleted=0
				  order by b.urutan";

		return $this->db->query($query, array($peserta_didik_id));
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
			//put in audit trail
			$this->tcg_audit_trail("tcg_peserta_didik",$peserta_didik_id,'update','Ubah lokasi berkas',implode(';', array_keys($data)),implode(';',$data));
		}

		return $retval;
	}

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
			$keys = "dokumen_id;daftarulang;penerima_berkas_id";
			$values = "$dokumen_id;$status;$penerima_berkas_id";

			//put in audit trail
			$this->tcg_audit_trail("tcg_dokumen_pendukung",$peserta_didik_id,'update','Daftar ulang berkas',$keys,$values);
		}

		return $retval;

	}

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
	// 	$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1 AND c.expired_date is NULL');
	// 	$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.expired_date IS NULL');
	// 	$builder->join('ref_jenis_pilihan e','e.jenis_pilihan = a.jenis_pilihan AND e.tahun_ajaran_id=a.tahun_ajaran_id AND e.expired_date IS NULL');
	// 	$builder->join('ref_jenis_pilihan f','f.jenis_pilihan = a.masuk_jenis_pilihan AND f.tahun_ajaran_id=a.tahun_ajaran_id AND f.expired_date IS NULL', 'left outer');
	// 	//$builder->join('tcg_skoring_pendaftaran g','a.pendaftaran_id = f.pendaftaran_id AND f.is_deleted = 0','LEFT OUTER');
	// 	$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.cabut_berkas'=>0,'a.is_deleted'=>0,'a.tahun_ajaran_id'=>$tahun_ajaran_id));
	// 	$builder->orderBy('a.jenis_pilihan');
	// 	return $builder->get();
	// }    

	function tcg_profilsiswa_detil($peserta_didik_id){
		//$peserta_didik_id = $this->session->get("pengguna_id");

		$builder = $this->db->table('tcg_peserta_didik a');
		$builder->select("a.peserta_didik_id,a.sekolah_id,b.npsn,b.nama AS sekolah,
		a.nik,a.nisn,a.nomor_ujian,a.nama,a.jenis_kelamin,a.tempat_lahir,a.tanggal_lahir,a.nama_ibu_kandung,a.nama_ayah,a.nama_wali,
		case when (trim(a.kebutuhan_khusus) = '' or a.kebutuhan_khusus = '0') then 'Tidak ada' else a.kebutuhan_khusus end as kebutuhan_khusus,
		a.alamat,a.kode_wilayah,
		'' as kode_padukuhan, a.nama_dusun AS padukuhan,
		c.kode_wilayah_desa as kode_desa, coalesce(c.nama_desa,a.desa_kelurahan) AS desa_kelurahan,
		c.kode_wilayah_kec as kode_kecamatan,c.nama_kec AS kecamatan,
		c.kode_wilayah_kab as kode_kabupaten,c.nama_kab AS kabupaten,
		c.kode_wilayah_prov,c.nama_prov AS provinsi,
		a.lintang,a.bujur,a.asal_data, 
		d.user_name as username,a.nomor_kontak,
		a.cabut_berkas,a.hapus_pendaftaran,a.ubah_pilihan,a.ubah_sekolah,a.batal_verifikasi,
		a.verifikasi_profil,a.verifikasi_lokasi,a.verifikasi_nilai,a.verifikasi_prestasi,a.verifikasi_afirmasi,a.verifikasi_inklusi,a.verifikasi_dokumen,
		a.catatan_profil,a.catatan_lokasi,a.catatan_nilai,a.catatan_prestasi,a.catatan_afirmasi,a.catatan_inklusi,
		a.verifikator_id,e.nama as nama_verifikator,a.tanggal_verifikasi,
		a.konfirmasi_profil,a.konfirmasi_lokasi,a.konfirmasi_nilai,a.konfirmasi_prestasi,a.konfirmasi_afirmasi,a.konfirmasi_inklusi,
		coalesce(a.punya_nilai_un,0) as punya_nilai_un,coalesce(a.punya_prestasi,0) as punya_prestasi,
		coalesce(a.punya_kip,0) as punya_kip,
		coalesce(a.masuk_bdt,0) as masuk_bdt,
		coalesce(a.no_kip,'') as nomor_kip,coalesce(a.no_bdt,'') as nomor_bdt,
		a.surat_pernyataan_kebenaran_dokumen,f.filename as nama_surat_pernyataan,
		f.path as path_surat_pernyataan,f.web_path as img_surat_pernyataan,f.thumbnail_path as thumbnail_surat_pernyataan,f.created_on as tanggal_surat_pernyataan,
		g.nilai as nilai_un, coalesce(g.bin, '') as nilai_bin, coalesce(g.mat, '') as nilai_mat, coalesce(g.ipa, '') as nilai_ipa,
		h.nilai as nilai_semester, h.kelas4_sem1, h.kelas4_sem2, h.kelas5_sem1, h.kelas5_sem2, h.kelas6_sem1, h.kelas6_sem2,
		j.nilai as nilai_lulus,
		i.nama as lokasi_berkas, d.konfirmasi_akun, d.ganti_password, a.tutup_akses
		");
		$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id','LEFT OUTER');
		$builder->join('ref_wilayah c','a.kode_wilayah = c.kode_wilayah AND c.expired_date IS NULL','LEFT OUTER');
		$builder->join('dbo_users d','a.peserta_didik_id = d.pengguna_id AND d.is_deleted = 0','LEFT OUTER');
		$builder->join('dbo_users e','a.verifikator_id = e.pengguna_id AND e.is_deleted = 0','LEFT OUTER');
		$builder->join('tcg_dokumen_pendukung f','a.surat_pernyataan_kebenaran_dokumen = f.dokumen_id AND a.peserta_didik_id=f.peserta_didik_id AND f.is_deleted = 0','LEFT OUTER');
		$builder->join('tcg_nilai_usbn g','a.peserta_didik_id = g.peserta_didik_id AND g.is_deleted = 0','LEFT OUTER');
		$builder->join('tcg_nilai_semester h','a.peserta_didik_id = h.peserta_didik_id AND h.is_deleted = 0','LEFT OUTER');
		$builder->join('ref_sekolah i','i.sekolah_id = a.lokasi_berkas','LEFT OUTER');
		$builder->join('tcg_nilai_kelulusan j','a.peserta_didik_id = j.peserta_didik_id AND j.is_deleted = 0','LEFT OUTER');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.is_deleted'=>0));

		return $builder->get();
	}   

	function tcg_profilsiswa_klarifikasidinas($peserta_didik_id){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$sql = "select a.peserta_didik_id,
						case when (b.tipe_data = 'profil') then 1 else 0 end as klarifikasi_dinas_profil,
						case when (b.tipe_data = 'profil') then b.catatan_dinas else null end as catatan_dinas_profil,
						case when (b.tipe_data = 'lokasi') then 1 else 0 end as klarifikasi_dinas_lokasi,
						case when (b.tipe_data = 'lokasi') then b.catatan_dinas else null end as catatan_dinas_lokasi
				from tcg_peserta_didik a
				left join (
					select 
						b.peserta_didik_id, b.tipe_data,
						group_concat(catatan_dinas, '<br>') as catatan_dinas
					from tcg_verifikasi_dinas b 
					where b.peserta_didik_id=? and b.tahun_ajaran_id=? and b.verifikasi=1 and b.is_deleted=0
					group by b.peserta_didik_id, b.tipe_data
				) as b on a.peserta_didik_id=b.peserta_didik_id
				where 
					a.peserta_didik_id=? and a.tahun_ajaran_id=? and a.is_deleted=0
			";

		return $this->db->query($sql, array($peserta_didik_id, $tahun_ajaran_id, $peserta_didik_id, $tahun_ajaran_id));
	}    

	function tcg_dokumen_pendukung_tambahan($peserta_didik_id) {
		$query = "select a.dokumen_id, a.daftar_kelengkapan_id, b.nama, a.filename, a.path, a.web_path, a.thumbnail_path, a.verifikasi, a.catatan
				  from tcg_dokumen_pendukung a
				  join ref_daftar_kelengkapan b on a.daftar_kelengkapan_id=b.daftar_kelengkapan_id and b.expired_date is null
				  where a.peserta_didik_id=? and a.is_deleted=0 and a.tambahan=1";

		return $this->db->query($query, array($peserta_didik_id));
	}

	function tcg_berkas_fisik($peserta_didik_id) {
		$query = "select a.dokumen_id, a.daftar_kelengkapan_id, b.nama, 
					a.berkas_fisik, a.penerima_berkas_id, a.tanggal_berkas,
					c.nama as penerima_berkas, d.nama as sekolah
				  from tcg_dokumen_pendukung a
				  join ref_daftar_kelengkapan b on a.daftar_kelengkapan_id=b.daftar_kelengkapan_id and b.expired_date is null
				  left join dbo_users c on c.is_deleted=0 and c.pengguna_id=a.penerima_berkas_id
				  left join ref_sekolah d on d.is_deleted=0 and d.sekolah_id=c.sekolah_id
				  where a.peserta_didik_id=? and a.is_deleted=0 and b.dokumen_fisik=1";

		return $this->db->query($query, array($peserta_didik_id));
	}

	function tcg_dokumen_pendukung_kelengkapan_id($peserta_didik_id, $daftar_kelengkapan_id) {
		$query = "
				  select a.dokumen_id, a.daftar_kelengkapan_id, b.nama, a.filename, a.path, a.web_path, a.thumbnail_path, a.verifikasi, a.catatan,
					b.dokumen_fisik, b.placeholder, a.berkas_fisik
				  from tcg_dokumen_pendukung a
				  join ref_daftar_kelengkapan b on a.daftar_kelengkapan_id=b.daftar_kelengkapan_id and b.expired_date is null
				  where a.peserta_didik_id=? and a.is_deleted=0 and a.daftar_kelengkapan_id=?";

		return $this->db->query($query, array($peserta_didik_id, $daftar_kelengkapan_id));
	}

	function tcg_prestasi($peserta_didik_id) {
		// $builder->select("distinct(a.prestasi_skoring_id) as daftar_nilai_skoring_id, b.nama, b.nilai, 1 as verifikasi, '' as verifikator_id");
		// $builder = $this->db->table('tcg_pendaftaran a');
		// $builder->join('ref_daftar_nilai_skoring b','b.penerapan_id = a.penerapan_id and b.daftar_nilai_skoring_id = a.prestasi_skoring_id and b.expired_date is null and b.nilai > 0');
		// $builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.is_deleted'=>0,'a.cabut_berkas'=>0,'a.prestasi_skoring_id !='=>null,'a.prestasi_skoring_id!='=>0));

		// return $builder->get();

		$query = "SELECT distinct(a.prestasi_skoring_id) as daftar_nilai_skoring_id, `b`.`nama`, coalesce(`c`.`nilai`,0) as nilai, 1 as `verifikasi`, '' as verifikator_id 
		FROM `tcg_pendaftaran` `a` 
		JOIN `ref_daftar_skoring` `b` ON `b`.`skoring_id` = `a`.`prestasi_skoring_id` and `b`.`expired_date` is null 
		left outer join `ref_daftar_nilai_skoring` `c` on `c`.`daftar_nilai_skoring_id` = `a`.`prestasi_skoring_id` and `c`.`tahun_ajaran_id`=`a`.`tahun_ajaran_id` and `c`.`nilai` > 0 and `c`.`expired_date` is null 
		WHERE `a`.`peserta_didik_id` = ? AND `a`.`is_deleted` = 0 AND `a`.`cabut_berkas` = 0 
		AND `a`.`prestasi_skoring_id` IS NOT NULL AND `a`.`prestasi_skoring_id` != 0";

		return $this->db->query($query, array($peserta_didik_id));
	}
	function tcg_verifikasi_dokumenpendukung($peserta_didik_id, $dokumen_id, $verifikasi, $catatan, $verifikator_id) {

		// $peserta_didik_id = secure($peserta_didik_id);
		// $dokumen_id = secure($dokumen_id);
		// $verifikasi = secure($verifikasi);
		// $catatan = secure($catatan);
		// $verifikator_id = secure($verifikator_id);

		//$tanggal_verifikasi = date("Y/m/d H:i:s");

		$query = "
		update tcg_dokumen_pendukung a
		set
			a.verifikasi = ?,
			a.catatan = ?,
			a.verifikator_id = ?,
			a.tanggal_verifikasi = now(),
			a.updated_on = now()
		where a.peserta_didik_id=? and a.dokumen_id=? and a.is_deleted=0";

		$this->db->query($query, array($verifikasi, $catatan, $verifikator_id, $peserta_didik_id, $dokumen_id));

		$retval = $this->db->affectedRows();
		if ($retval > 0) {
			$keys = "dokumen_id;verifikasi;verifikator_id";
			$values = "$dokumen_id;$verifikasi;$verifikator_id";

			//put in audit trail
			$this->tcg_audit_trail("tcg_dokumen_pendukung",$peserta_didik_id,'update','Verifikasi berkas',$keys,$values);
		}

		return $retval;

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
			$keys = "dokumen_id;berkas_fisik;penerima_berkas_id";
			$values = "$dokumen_id;$value;$verifikator_id";

			//put in audit trail
			$this->tcg_audit_trail("tcg_dokumen_pendukung",$peserta_didik_id,'update','Verifikasi berkas fisik',$keys,$values);
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

		$this->db->query($query, array($verifikasi, $verifikator_id, $peserta_didik_id, $tahun_ajaran_id, $dokumen_id));

		$retval = $this->db->affectedRows();
		if ($retval > 0) {
			$keys = "dokumen_id;verifikasi;verifikator_id";
			$values = "$dokumen_id;$verifikasi;$verifikator_id";

			//put in audit trail
			$this->tcg_audit_trail("tcg_kelengkapan_pendaftaran",$peserta_didik_id,'update','Verifikasi berkas',$keys,$values);
		}

		return $retval;

	}

	function tcg_verifikasi_siswa($peserta_didik_id, $valuepair, $pengguna_id) {	
		//enforce last-update
		$valuepair['verifikator_id'] = $pengguna_id;
		$valuepair['tanggal_verifikasi'] = date("Y/m/d H:i:s");
		$valuepair['updated_on'] = date("Y/m/d H:i:s");

        $builder = $this->db->table('tcg_peserta_didik');
		$builder->where(array('peserta_didik_id'=>$peserta_didik_id,'is_deleted'=>0));
		$retval = $builder->update($valuepair);

		if ($retval > 0) {
			//put in audit trail
			$this->tcg_audit_trail("tcg_peserta_didik",$peserta_didik_id,'update','Update status verifikasi',implode(';', array_keys($valuepair)),implode(';',$valuepair));
		}

		return $retval;	
	}

	function tcg_tambah_riwayatverifikasi($peserta_didik_id, $pengguna_id, $verifikasi, $catatan) {
		$valuepair = array(
			'peserta_didik_id' => $peserta_didik_id,
			//'tahun_ajaran_id' => $tahun_ajaran_id,
			'verifikator_id' => $pengguna_id,
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
				//'tahun_ajaran_id' => $tahun_ajaran_id,
				'is_deleted' => 0,
				'cabut_berkas' => 0
			);	
			$valuepair = array(
				'terakhir_verifikasi_oleh' => $pengguna_id,
				'terakhir_verifikasi_timestamp' => $timestamp,
				'updated_on' => $timestamp
			);	
            $builder = $this->db->table('tcg_peserta_didik');
            $builder->where($filter);
			$builder->update($valuepair);
            //return the id
            return $key;
        } else {
            return 0;
        }
	}   

	function tcg_ubah_kelengkapanberkas($peserta_didik_id, $tahun_ajaran_id, $value, $pengguna_id) {

		$query = "CALL " .SQL_UBAH_KELENGKAPANBERKAS. "(?, ?, ?)";

		$retval = $this->db->query($query, array($peserta_didik_id, $tahun_ajaran_id, $pengguna_id));
		if ($retval) {
			$this->tcg_audit_trail("tcg_pendaftaran",$peserta_didik_id,'update','Kelengkapan berkas',"kelengkapan_berkas", $value);
		}
	}    

	function tcg_dokumen_pendukung_hilang($peserta_didik_id, $daftar_kelengkapan_id, $pengguna_id, $catatan) {
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
			'verifikator_id' => $pengguna_id,
			'tanggal_verifikasi' => date("Y/m/d H:i:s"),
			'catatan' => $catatan
		);

        $builder = $this->db->table('tcg_dokumen_pendukung');
		$builder->insert($valuepair);
	}

	function tcg_verifikasidinas_baru($peserta_didik_id, $tipe_data, $kelengkapan_id, $catatan) {
		$pengguna_id = $this->session->get('pengguna_id');
		$sekolah_id = $this->session->get('sekolah_id');
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$valuepair = array(
			'peserta_didik_id' 	=> $peserta_didik_id,
			'tahun_ajaran_id'	=> $tahun_ajaran_id,
			'pengguna_sekolah'	=> $pengguna_id,
			'sekolah_id'		=> $sekolah_id,
			'tipe_data'			=> $tipe_data,
			'daftar_kelengkapan_id'	=> $kelengkapan_id,
			'catatan_sekolah'	=> $catatan
		);

        $builder = $this->db->table('dbo_verifikasi_dinas');
		$query = $builder->insert($valuepair);
		if ($query) {
			$key = $this->db->insertID();
			return $key;
		}

		return 0;
	}    

    function tcg_updatekebutuhankhusus($peserta_didik_id, $kebutuhan_khusus, $pengguna_id) {
        //TODO
    }

    function tcg_profilsiswa($peserta_didik_id){
		//$peserta_didik_id = $this->session->userdata("pengguna_id");

		$builder = $this->db->table('tcg_peserta_didik a');
		$builder->select("a.peserta_didik_id,a.sekolah_id,b.npsn,b.nama AS sekolah,
		a.nik,a.nisn,a.nomor_ujian,a.nama,a.jenis_kelamin,a.tempat_lahir,a.tanggal_lahir,a.nama_ibu_kandung,a.nama_ayah,a.nama_wali,
		a.rt,a.rw,a.alamat,a.kode_wilayah,
		'' as kode_padukuhan, a.nama_dusun AS padukuhan,
		c.kode_wilayah_desa as kode_desa, coalesce(c.nama_desa,a.desa_kelurahan) AS desa_kelurahan,
		c.kode_wilayah_kec as kode_kecamatan,c.nama_kec AS kecamatan,
		c.kode_wilayah_kab as kode_kabupaten,c.nama_kab AS kabupaten,
		c.kode_wilayah_prov,c.nama_prov AS provinsi,
		a.lintang,a.bujur,a.asal_data, 
		d.username,a.nomor_kontak,
		coalesce(a.punya_nilai_un,0) as punya_nilai_un,coalesce(a.punya_prestasi,0) as punya_prestasi,
		coalesce(a.punya_kip,0) as punya_kip,
		coalesce(a.masuk_bdt,0) as masuk_bdt,a.kebutuhan_khusus,
		a.cabut_berkas,a.hapus_pendaftaran,a.ubah_pilihan,a.ubah_sekolah,a.batal_verifikasi");
		$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id','LEFT OUTER');
		$builder->join('ref_wilayah c','a.kode_wilayah = c.kode_wilayah AND c.expired_date IS NULL','LEFT OUTER');
		$builder->join('tcg_pengguna d','a.peserta_didik_id = d.pengguna_id AND d.is_deleted = 0','LEFT OUTER');
		//case when (a.no_kip is null or trim(a.no_kip) = '' or a.no_kip = '0' or a.no_kip = '-') then 0 else 1 end as punya_kip,
		// $builder->join('ref_wilayah c4','c.kode_wilayah_desa = c4.kode_wilayah AND c4.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah c3','c.kode_wilayah_kec = c3.kode_wilayah AND c3.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah c2','c.kode_wilayah_kab = c2.kode_wilayah AND c2.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah c1','c.kode_wilayah_prov = c1.kode_wilayah AND c1.expired_date IS NULL','LEFT OUTER');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.is_deleted'=>0));

		return $builder->get();
	}

	function tcg_profilsiswa_status($peserta_didik_id){
		//$peserta_didik_id = $this->session->userdata("pengguna_id");

		$builder = $this->db->table('tcg_peserta_didik a');
		$builder->select("a.verifikasi_profil,a.verifikasi_lokasi,a.verifikasi_nilai,a.verifikasi_prestasi,a.verifikasi_afirmasi,a.verifikasi_inklusi,a.verifikasi_dokumen,
		a.verifikator_id,e.nama as nama_verifikator,a.tanggal_verifikasi,
		a.konfirmasi_profil,a.konfirmasi_lokasi,a.konfirmasi_nilai,a.konfirmasi_prestasi,a.konfirmasi_afirmasi,a.konfirmasi_inklusi,
		coalesce(a.punya_nilai_un,0) as punya_nilai_un,coalesce(a.punya_prestasi,0) as punya_prestasi,
		coalesce(a.punya_kip,0) as punya_kip,
		coalesce(a.masuk_bdt,0) as masuk_bdt,
		a.nomor_kontak,a.surat_pernyataan_kebenaran_dokumen,
		f.filename as nama_surat_pernyataan,
		f.path as path_surat_pernyataan,
		f.web_path as img_surat_pernyataan,
		f.thumbnail_path as thumbnail_surat_pernyataan,
		f.created_on as tanggal_surat_pernyataan
		");
		$builder->join('tcg_pengguna e','a.verifikator_id = e.pengguna_id AND e.is_deleted = 0','LEFT OUTER');
		$builder->join('tcg_dokumen_pendukung f','a.surat_pernyataan_kebenaran_dokumen = f.dokumen_id AND (a.peserta_didik_id=f.peserta_didik_id or a.surat_pernyataan_kebenaran_dokumen=1) AND f.is_deleted = 0','LEFT OUTER');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.is_deleted'=>0));

		return $builder->get();
	}    

	function tcg_kelengkapanpendaftaran($pendaftaran_id){
		$builder = $this->db->table('tcg_kelengkapan_pendaftaran a');
		$builder->select('a.dokumen_id,c.nama AS kelengkapan,a.verifikasi,b.kondisi_khusus,b.wajib');
		$builder->join('ref_kelengkapan_penerapan b','a.kelengkapan_penerapan_id = b.kelengkapan_penerapan_id AND b.expired_date IS NULL');
		$builder->join('ref_daftar_kelengkapan c','b.daftar_kelengkapan_id = c.daftar_kelengkapan_id AND c.expired_date IS NULL');
		$builder->where(array('a.pendaftaran_id'=>$pendaftaran_id,'a.is_deleted'=>0));
		$builder->orderBy('c.daftar_kelengkapan_id');
		return $builder->get();
	}

    function tcg_kelengkapanpendaftaran_berkasfisik($pendaftaran_id){
		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('b.dokumen_id,c.nama AS kelengkapan,b.berkas_fisik,d.kondisi_khusus,d.wajib');
		$builder->join('tcg_dokumen_pendukung b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_daftar_kelengkapan c','b.daftar_kelengkapan_id = c.daftar_kelengkapan_id AND c.expired_date IS NULL');
		$builder->join('ref_kelengkapan_penerapan d','d.daftar_kelengkapan_id = c.daftar_kelengkapan_id AND d.penerapan_id=a.penerapan_id AND d.expired_date IS NULL');
		$builder->where(array('a.pendaftaran_id'=>$pendaftaran_id,'a.is_deleted'=>0,'c.dokumen_fisik'=>1));
		$builder->orderBy('c.daftar_kelengkapan_id');
		return $builder->get();
	}

    function tcg_nilaiskoring($pendaftaran_id){

		$builder = $this->db->table('tcg_skoring_pendaftaran a');
		$builder->select('a.skoring_pendaftaran_id,c.nama AS keterangan,round(a.nilai,2) as nilai');
		$builder->join('tcg_pendaftaran b','a.pendaftaran_id = b.pendaftaran_id AND b.cabut_berkas = 0 AND b.is_deleted = 0');
		$builder->join('ref_daftar_skoring c','a.skoring_id = c.skoring_id AND c.expired_date IS NULL');
		//$builder->join('ref_daftar_nilai_skoring d','a.skoring_id = d.skoring_id and b.tahun_ajaran_id=d.tahun_ajaran_id AND c.expired_date IS NULL');
		$builder->where(array('a.pendaftaran_id'=>$pendaftaran_id,'a.is_deleted'=>0));
		$builder->orderBy('c.nama');
		return $builder->get();

	}

    function tcg_batasansiswa($peserta_didik_id){
		$builder = $this->db->table('tcg_peserta_didik');
		$builder->select('cabut_berkas,hapus_pendaftaran,ubah_pilihan,ubah_sekolah,ubah_jalur');
		$builder->where(array('peserta_didik_id'=>$peserta_didik_id,'is_deleted'=>0));
		return $builder->get();
	}


	function tcg_daftar_prestasi_files($peserta_didik_id) {
		$query = "
		select a.dokumen_pendukung as id,
				c.filename, c.filesize, c.path, c.web_path, c.thumbnail_path, c.created_on as tanggal_upload
		from tcg_prestasi a
		join ref_daftar_skoring b on a.skoring_id=b.skoring_id and b.expired_date is null
		left join tcg_dokumen_pendukung c on a.dokumen_pendukung=c.dokumen_id and c.is_deleted=0
		where a.is_deleted=0 and a.peserta_didik_id=?";

		return $this->db->query($query, array($peserta_didik_id));
	}

	function tcg_hapus_prestasi($peserta_didik_id, $key) {
		$file_path = "";
		$thumbnail_path = "";
		$dokumen_id = 0;

		$query = $this->tcg_prestasi_detil($peserta_didik_id, $key);
		foreach($query->getResult() as $row) {
			$file_path = $row->web_path;
			$thumbnail_path = $row->thumbnail_path;
			$dokumen_id = $row->dokumen_pendukung;
		}

		// //delete the file in /upload/path
		// if (!empty($file_path) && file_exists(FCPATH. $file_path))
		// 	unlink(FCPATH . $file_path);

		// //delete the file in /thumbnail/path
		// if (!empty($thumbnail_path) && file_exists(FCPATH. $thumbnail_path)
		// 	unlink(FCPATH . $thumbnail_path);

		//delete entry in tcg_dokumen_pendukung
		if (!empty($dokumen_id)) {
            $builder = $this->db->table('tcg_dokumen_pendukung');
			$builder->where('dokumen_id', $dokumen_id);
			$builder->delete();
		}

		//delete entry in tcg_prestasi
        $builder = $this->db->table('tcg_prestasi');
		$builder->where('prestasi_id', $key);
		$builder->delete();

	}

	function tcg_prestasi_baru($peserta_didik_id, $valuepair) {
		//dokumen bukti prestasi
		$kelengkapan_id = 8;

		//inject peserta_didik_id
        $valuepair['peserta_didik_id'] = $peserta_didik_id;

        $builder = $this->db->table('tcg_prestasi');
        if ($builder->insert($valuepair)) {
            $key = $this->db->insertID();
			//simpan dokumen pendukung
            if (!empty($valuepair['dokumen_pendukung'])) {
                $dokumen_pendukung = $valuepair['dokumen_pendukung'];
                if (!empty($dokumen_pendukung) && !empty($key)) {
                    $query = "call usp_simpan_dokumen_pendukung_2021(?,?,?,0,1,0)";
                    $this->db->query($query, array($dokumen_pendukung,$peserta_didik_id,$kelengkapan_id));
                }
            }
            //return the id
            return $key;
        } else {
            return 0;
        }
	}

	function tcg_daftarjalur($kode_wilayah, $kebutuhan_khusus=0, $afirmasi=-1, $selain_penerapan_id=0){
		// $bentuk = $this->session->userdata("bentuk");
		// $kode_wilayah = $this->session->userdata("kode_wilayah");
		// $tanggal_lahir = $this->session->userdata("tanggal_lahir");
		// $asal_data = $this->session->userdata("asal_data");
		// $kebutuhan_khusus = $this->session->userdata("kebutuhan_khusus");
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");
		$putaran = $this->session->get("putaran_aktif");
		$kode_wilayah_aktif = $this->session->get("kode_wilayah_aktif");
		//$bentuk_sekolah = secure("SMP");

		$builder = $this->db->table('ref_penerapan a');
		$builder->select('a.penerapan_id,a.nama,a.keterangan,c.jalur_id,c.nama AS jalur,a.sekolah_negeri,a.sekolah_swasta,a.kategori_susulan,a.kategori_inklusi');
		$builder->join('ref_jalur c','a.jalur_id = c.jalur_id AND c.expired_date IS NULL');
		$builder->where(array('a.pendaftaran'=>1,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.expired_date'=>NULL));
		
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
		
		if ($afirmasi==0) {
			$builder->where('a.kategori_afirmasi',0);
			$builder->where('c.jalur_id !=',9);
		} 
        else if ($afirmasi==1) {
			$builder->groupStart();
			$builder->where('a.kategori_afirmasi',1);
			$builder->orWhere('c.jalur_id',9);			//jalur afirmasi
			$builder->groupEnd();
        }

		if ($selain_penerapan_id > 0) {
			$builder->where('a.penerapan_id !=',$selain_penerapan_id);
		}

		$builder->orderBy('a.urutan');
        $result = $builder->get();

        //echo $this->db->getLastQuery(); exit;

		return $result;
	}    
}

