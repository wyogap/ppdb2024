<?php 
Class Mbatasan 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

	function cfg_petunjuk_pelaksanaan($tahun_ajaran_id) {

		$builder = $this->db->table('cfg_petunjuk_pelaksanaan a');
		$builder->select('a.jadwal_pelaksanaan,a.persyaratan,a.tata_cara_pendaftaran,a.jalur_pendaftaran,a.proses_seleksi,a.konversi_nilai,a.embedded_script');
		$builder->where(array('a.is_deleted'=>0,'a.tahun_ajaran_id'=>$tahun_ajaran_id));

		return $builder->get();
	}

	function tcg_ubah_petunjuk_pelaksanaan($tahun_ajaran_id, $valuepair) {
		//inject last update
		$valuepair['updated_on'] = date("Y/m/d H:i:s");

		$builder = $this->db->table('cfg_petunjuk_pelaksanaan');
		$builder->where(array('tahun_ajaran_id'=>$tahun_ajaran_id,'is_deleted'=>0));
		$query = $builder->update($valuepair);
		return $this->db->affectedRows();
	}



}