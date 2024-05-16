<?php 
Class Mpesertadidik 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    // function profilsiswa($pendaftaran_id){
	// 	// $pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 

	// 	$builder = $this->db->table('tcg_peserta_didik a');
	// 	$builder->select('a.peserta_didik_id,a.sekolah_id,b.npsn,b.nama AS sekolah,a.nik,a.nisn,a.nomor_ujian,a.nama,a.jenis_kelamin,a.tempat_lahir,a.tanggal_lahir,a.nama_ibu_kandung,a.kebutuhan_khusus,a.alamat,c.nama_desa AS desa_kelurahan,c.nama_kec AS kecamatan,c.nama_kab AS kabupaten,c.nama_prov AS provinsi,a.lintang,a.bujur,g.jenis_pilihan,c.kode_wilayah,c.kode_wilayah_kec AS kode_kecamatan,c.kode_wilayah_kab AS kode_kabupaten');
	// 	$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id','LEFT OUTER');
	// 	$builder->join('ref_wilayah c','a.kode_wilayah = c.kode_wilayah AND c.expired_date IS NULL','LEFT OUTER');
	// 	$builder->join('tcg_pendaftaran g','a.peserta_didik_id = g.peserta_didik_id AND g.cabut_berkas = 1 AND g.pilihan != 0 AND g.is_deleted = 0');
	// 	$builder->where(array('g.pendaftaran_id'=>$pendaftaran_id,'a.is_deleted'=>0));
	// 	return $builder->get();
	// }


	// function carisiswa(){
	// 	$nisn = $_POST["data"] ?? null; (("nisn");
	// 	$nik = $_POST["data"] ?? null; (("nik");
	// 	$nama = $_POST["data"] ?? null; (("nama");
	// 	$jenis_kelamin = $_POST["data"] ?? null; (("jenis_kelamin");
	// 	$tempat_lahir = $_POST["data"] ?? null; (("tempat_lahir");
	// 	$tanggal_lahir = $_POST["data"] ?? null; (("tanggal_lahir");
	// 	$nama_ibu_kandung = $_POST["data"] ?? null; (("nama_ibu_kandung");
	// 	$array = array('nama'=>$nama,'tempat_lahir'=>$tempat_lahir,'nama_ibu_kandung'=>$nama_ibu_kandung,FALSE);
	// 	$builder->select('peserta_didik_id,nik,nisn,nomor_ujian,nama,jenis_kelamin,tempat_lahir,tanggal_lahir,nama_ibu_kandung,alamat');
	// 	$builder = $this->db->table('tcg_peserta_didik');
	// 	$builder->where(array('jenis_kelamin'=>$jenis_kelamin,'is_deleted'=>0));
	// 	$builder->like($array);
	// 	if($nisn!=""){
	// 		$builder->where('nisn',$nisn);
	// 	}
	// 	if($nik!=""){
	// 		$builder->where('nik',$nik);
	// 	}
	// 	if($tanggal_lahir!=""){
	// 		$builder->where('tanggal_lahir',$tanggal_lahir);
	// 	}
	// 	return $builder->get();
	// }

	function tcg_cari_pesertadidik($nama, $nisn, $nik, $sekolah_id, $jenis_kelamin, $kode_desa, $kode_kecamatan){

		$query = "select a.peserta_didik_id, a.nama, a.nisn, a.nik, 
					  b.kode_wilayah_desa, b.nama_desa, b.kode_wilayah_kec, b.nama_kec, c.nama as sekolah,
					  a.lintang, a.bujur,
					  a.cabut_berkas, a.hapus_pendaftaran, a.ubah_pilihan, a.ubah_sekolah, a.ubah_jalur, a.tutup_akses,
					  a.verifikasi_profil, a.verifikasi_lokasi, a.verifikasi_nilai, a.verifikasi_prestasi, a.verifikasi_afirmasi, a.verifikasi_inklusi,
					  a.surat_pernyataan_kebenaran_dokumen
				  from tcg_peserta_didik a
				  left join ref_wilayah b on a.kode_wilayah=b.kode_wilayah and b.expired_date is null
				  join ref_sekolah c on c.sekolah_id=a.sekolah_id and c.expired_date is null
				  join dbo_users d on d.pengguna_id=a.peserta_didik_id and d.is_deleted=0
				  ";

		$where = "a.is_deleted=0 and a.nama like '%" . $nama . "%'";
		if (!empty($jenis_kelamin))
			$where .= " AND a.jenis_kelamin='" . $jenis_kelamin . "'";
		if (!empty($nisn))
			$where .= " AND a.nisn='" . $nisn . "'";
		if (!empty($nik))
			$where .= " AND a.nik='" . $nik . "'";
		if (!empty($kode_kecamatan))
			$where .= " AND a.kode_kecamatan=" . $kode_kecamatan;
		if (!empty($kode_desa))
			$where .= " AND a.kode_desa=" . $kode_desa;
		if (!empty($sekolah_id))
			$where .= " AND a.sekolah_id='" . $sekolah_id . "'";

		$query .= " WHERE " . $where;

		return $this->db->query($query);	
	}

    function tcg_ubah_pesertadidik($key, $valuepair) {
        $builder = $this->db->table('tcg_peserta_didik');
        $builder->where('peserta_didik_id', $key);
        $builder->update($valuepair);

        return 1;
    }

	function tcg_detil_pesertadidik($key){

		$query = "select a.peserta_didik_id, a.nama, a.nisn, a.nik, 
					  b.kode_wilayah_desa, b.nama_desa, b.kode_wilayah_kec, b.nama_kec, c.nama as sekolah,
					  a.lintang, a.bujur,
					  a.cabut_berkas, a.hapus_pendaftaran, a.ubah_pilihan, a.ubah_sekolah, a.ubah_jalur, a.tutup_akses,
					  a.verifikasi_profil, a.verifikasi_lokasi, a.verifikasi_nilai, a.verifikasi_prestasi, a.verifikasi_afirmasi, a.verifikasi_inklusi,
					  a.surat_pernyataan_kebenaran_dokumen
				  from tcg_peserta_didik a
				  join ref_wilayah b on a.kode_wilayah=b.kode_wilayah and b.expired_date is null
				  join ref_sekolah c on c.sekolah_id=a.sekolah_id and c.expired_date is null
				  where a.is_deleted=0 and a.peserta_didik_id='$key'";

		return $this->db->query($query);	
	}


}