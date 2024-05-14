<?php 
Class Mdaftarkelengkapan 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
	
	function lookup() {
		$query = "select a.daftar_kelengkapan_id as value, a.nama as label
				 	from ref_daftar_kelengkapan a
				  where a.expired_date is null order by a.urutan";
		return $this->db->query($query);
	}

	function list() {
		$query = "select a.*, b.nama as master_kelengkapan
				 	from ref_daftar_kelengkapan a
				  	left join ref_daftar_kelengkapan b on b.daftar_kelengkapan_id=a.master_kelengkapan_id and b.expired_date is null
				  where a.expired_date is null order by a.urutan";
		return $this->db->query($query);
	}

	function detail($kelengkapan_id) {
		$kelengkapan_id = secure($kelengkapan_id);
		$query = "select a.*, b.nama as master_kelengkapan 
				 	from ref_daftar_kelengkapan a
				  	left join ref_daftar_kelengkapan b on b.daftar_kelengkapan_id=a.master_kelengkapan_id and b.expired_date is null
				  where a.daftar_kelengkapan_id=$kelengkapan_id and a.expired_date is null order by a.urutan";
		return $this->db->query($query);
	}

	function update($kelengkapan_id, $valuepair) {
		$filter = array();
		$filter['daftar_kelengkapan_id'] = $kelengkapan_id;

		if (!empty($valuepair['master_kelengkapan_id']) && $valuepair['master_kelengkapan_id'] == $kelengkapan_id) {
			$valuepair['master_kelengkapan_id'] = null;
		}
		
		//inject updated 
        $valuepair['last_update'] = date('Y/m/d H:i:s');

        $builder = $this->db->table('ref_daftar_kelengkapan');
        $builder->where($filter);
        $builder->update($valuepair);
        
        $affected = $this->db->affectedRows();
        if ($affected > 0) {
            //TODO: audit trail
            //audittrail_update(static::$TABLE_NAME, $id, $valuepair);

			return $kelengkapan_id;
        }

		return 0;
	}

	function add($valuepair) {
		//inject updated 
        $valuepair['create_date'] = date('Y/m/d H:i:s');

        $builder = $this->db->table('ref_daftar_kelengkapan');
		if ($builder->insert($valuepair)) {
            $id = $this->db->insertID();

            //TODO: audit trail
            // audittrail_insert(static::$TABLE_NAME, $id, $valuepair);

            return $id;
		}

		return 0;
	}

	function delete($kelengkapan_id) {
		$filter = array();
        $filter['daftar_kelengkapan_id'] = $kelengkapan_id;

		$valuepair = array (
			'expired_date' => date('Y/m/d H:i:s')
		);

        $builder = $this->db->table('ref_daftar_kelengkapan');
        $builder->where($filter);
        $builder->update($valuepair);
        
        $affected = $this->db->affectedRows();
        if ($affected > 0) {
            //TODO: audit trail
            //audittrail_update(static::$TABLE_NAME, $id, $valuepair);

			return $affected;
        }

		return 0;
	}
 

}