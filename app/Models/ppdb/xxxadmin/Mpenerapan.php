<?php 
Class Mpenerapan 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

	function tcg_daftarpenerapan($tahun_ajaran_id){
		$builder = $this->db->table('ref_penerapan a');
		$builder->select('a.penerapan_id,a.jalur_id,c.nama AS jalur');
		$builder->join('ref_jalur c','a.jalur_id = c.jalur_id AND c.is_deleted=0');
		$builder->where(array('a.aktif'=>1,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.expired_date'=>NULL));
		return $builder->get();
	}
	
	function lookup($tahun_ajaran_id) {
		$tahun_ajaran_id = secure($tahun_ajaran_id);
		$query = "select a.penerapan_id as value, a.nama as label
				  from ref_penerapan a
				  where a.tahun_ajaran_id=$tahun_ajaran_id and a.is_deleted=0 order by a.aktif desc, a.urutan";
		return $this->db->query($query);
	}

	function list($tahun_ajaran_id) {
		$query = "select a.*, b.nama as jalur from ref_penerapan a
				  join ref_jalur b on a.jalur_id=b.jalur_id and b.is_deleted=0
				  where a.tahun_ajaran_id='$tahun_ajaran_id' and a.is_deleted=0 order by a.aktif desc, a.urutan";
		return $this->db->query($query);
	}

	function detail($penerapan_id) {
		$query = "select a.*, b.nama as jalur from ref_penerapan a
				  join ref_jalur b on a.jalur_id=b.jalur_id and b.is_deleted=0
				  where a.penerapan_id='$penerapan_id'";
		return $this->db->query($query);
	}

	function update($penerapan_id, $key, $value) {
		$query = "update ref_penerapan set $key='$value' where penerapan_id=$penerapan_id";
		return $this->db->query($query);
	}

	function add($tahun_ajaran_id, $valuepair) {
		//inject tahun ajaran
		$valuepair['tahun_ajaran_id'] = $tahun_ajaran_id;

		$builder = $this->db->table('ref_penerapan');
		if ($builder->insert($valuepair)) {
			return $this->db->insertID();
		}

		return 0;
	}

	function delete($penerapan_id) {
		//soft-delete skoring
		$sql = "update ref_penerapan a 
		set 
			a.expired_date=now() 
		where a.penerapan_id=$penerapan_id";
		if (!$this->db->query($sql)) {
			return 0;
		}

		return 1;
	}
 
	function tcg_jalur_pendaftaran($tahun_ajaran_id) {
		$query = "select a.*, b.nama as jalur from ref_penerapan a
				  join ref_jalur b on a.jalur_id=b.jalur_id and b.is_deleted=0
				  where a.tahun_ajaran_id='$tahun_ajaran_id' and a.is_deleted=0 and a.pendaftaran=1 order by a.aktif desc, a.urutan";
		return $this->db->query($query);
	}

	// function tcg_kuota_pendaftaran($tahun_ajaran_id) {
	// 	$query = "select a., b.nama as jalur from ref_penerapan a
	// 			  join ref_jalur b on a.jalur_id=b.jalur_id and b.is_deleted=0
	// 			  where a.tahun_ajaran_id='$tahun_ajaran_id' and a.is_deleted=0 and a.perankingan=1 
	// 			  group by a.jalur_id, b.nama, 
	// 			  order by a.aktif desc, a.urutan";
	// 	return $this->db->query($query);
	// }
 
	
	function tcg_jalur_perankingan($tahun_ajaran_id) {
		$query = "select a.*, b.nama as jalur from ref_penerapan a
				  join ref_jalur b on a.jalur_id=b.jalur_id and b.is_deleted=0
				  where a.tahun_ajaran_id='$tahun_ajaran_id' and a.is_deleted=0 and a.perankingan=1 order by a.aktif desc, a.urutan";
		return $this->db->query($query);
	}


}