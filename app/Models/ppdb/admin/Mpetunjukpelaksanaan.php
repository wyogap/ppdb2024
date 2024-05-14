<?php 
Class Mbatasan 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

	function tcg_petunjuk_pelaksanaan($tahun_ajaran_id) {

		$builder = $this->db->table('tcg_petunjuk_pelaksanaan a');
		$builder->select('a.jadwal_pelaksanaan,a.persyaratan,a.tata_cara_pendaftaran,a.jalur_pendaftaran,a.proses_seleksi,a.konversi_nilai,a.embedded_script');
		$builder->where(array('a.soft_delete'=>0,'a.tahun_ajaran_id'=>$tahun_ajaran_id));

		return $builder->get();
	}

	function tcg_ubah_petunjuk_pelaksanaan($tahun_ajaran_id, $valuepair) {
		//inject last update
		$valuepair['last_update'] = date("Y/m/d H:i:s");

		$builder = $this->db->table('tcg_petunjuk_pelaksanaan');
		$builder->where(array('tahun_ajaran_id'=>$tahun_ajaran_id,'soft_delete'=>0));
		$query = $builder->update($valuepair);
		return $this->db->affectedRows();
	}



}