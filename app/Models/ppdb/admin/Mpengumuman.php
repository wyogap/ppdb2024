<?php 
Class Mpengumuman 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

	function tcg_pengumuman($tahun_ajaran_id) {
		if (empty($tahun_ajaran_id)) {
			$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');	
		}

		$sql = "select * from tcg_pengumuman where tahun_ajaran_id='$tahun_ajaran_id' and soft_delete=0";

		return $this->db->query($sql);
	}

	function tcg_pengumuman_aktif(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$query = "select a.tipe, a.css, a.text, a.bisa_ditutup 
				  from tcg_pengumuman a
				  where a.tahun_ajaran_id='$tahun_ajaran_id' and a.soft_delete=0
						and (a.tanggal_mulai <= now() or a.tanggal_mulai is null)
						and (a.tanggal_selesai >= now() or a.tanggal_selesai is null)
				  order by a.tanggal_mulai asc";

		return $this->db->query($query);
	}


	function tcg_detil_pengumuman($key) {
		$sql = "select * from tcg_pengumuman where pengumuman_id=$key and soft_delete=0";

		return $this->db->query($sql);
	}

	function tcg_ubah_pengumuman($key, $valuepair) {
		$valuepair['last_update'] =date("Y/m/d H:i:s");

		$builder = $this->db->table('tcg_pengumuman');
		$builder->where(array('pengumuman_id'=>$key,'soft_delete'=>0));
		return $builder->update($valuepair);	
	}

	function tcg_hapus_pengumuman($key) {
		$sql = "update tcg_pengumuman set soft_delete=1 where pengumuman_id=$key";

		return $this->db->query($sql);
	}

	function tcg_pengumuman_baru($tahun_ajaran_id, $valuepair) {
		$valuepair['tahun_ajaran_id'] = $tahun_ajaran_id;

		$builder = $this->db->table('tcg_pengumuman');
		if ($builder->insert($valuepair)) {
			return $this->db->insertID();
		}
		
		return 0;	
	}


}