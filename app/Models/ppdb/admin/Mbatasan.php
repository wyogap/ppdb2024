<?php 
Class Mbatasan 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

	// function tcg_batasanperubahan(){
	// 	$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

	// 	$builder->select('cabut_berkas,hapus_pendaftaran,ubah_pilihan,ubah_sekolah,ubah_jalur,batal_verifikasi');
	// 	$builder = $this->db->table('ref_batasan_perubahan');
	// 	$builder->where(array('expired_date'=>NULL, 'tahun_ajaran_id'=>$tahun_ajaran_id));
	// 	return $builder->get();
	// }

	function tcg_batasanperubahan($tahun_ajaran_id) {
		$query = "select a.* from ref_batasan_perubahan a
				  where a.tahun_ajaran_id='$tahun_ajaran_id' and a.expired_date is null limit 1";
		return $this->db->query($query);
	}

	function tcg_update_batasan($batasan_id, $key, $value) {
		$query = "update ref_batasan_perubahan set $key='$value' where batasan_perubahan_id=$batasan_id";
		return $this->db->query($query);
	}

	function tcg_view_batasan($batasan_id) {
		$query = "select a.* from ref_batasan_perubahan a
				  where a.batasan_perubahan_id='$batasan_id'";
		return $this->db->query($query);
	}

	function tcg_batasanusia(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$bentuk_sekolah = $this->session->get('bentuk_sekolah_aktif');

		$query = "select bentuk_tujuan_sekolah,minimal_tanggal_lahir,maksimal_tanggal_lahir 
				  from ref_batasan_usia
				  where expired_date is null and tahun_ajaran_id='$tahun_ajaran_id'";
		
		if (!empty($bentuk_sekolah)) {
			$query .= " and bentuk_tujuan_sekolah='$bentuk_sekolah'";
		}

		return $this->db->query($query);
	}


}