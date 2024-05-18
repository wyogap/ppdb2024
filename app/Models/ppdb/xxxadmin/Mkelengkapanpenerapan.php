<?php 
Class Mkelengkapanpenerapan 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
	
	function list($tahun_ajaran_id) {
		$tahun_ajaran_id = secure($tahun_ajaran_id);
		$query = "select a.*, b.nama as daftar_kelengkapan, c.nama as penerapan, b.urutan, b.dokumen_fisik, b.daftar_ulang
				 	from tcg_kelengkapan_dokumen a
					join ref_daftar_kelengkapan b on b.daftar_kelengkapan_id=a.daftar_kelengkapan_id and b.is_deleted=0
					join ref_penerapan c on c.penerapan_id=a.penerapan_id and c.is_deleted=0
				 where a.is_deleted=0 and a.tahun_ajaran_id=$tahun_ajaran_id order by a.penerapan_id, b.urutan";
		return $this->db->query($query);
	}

	function detail($kelengkapan_id) {
		$kelengkapan_id = secure($kelengkapan_id);
		$query = "select a.*, b.nama as daftar_kelengkapan, c.nama as penerapan, b.urutan, b.dokumen_fisik, b.daftar_ulang
				 	from tcg_kelengkapan_dokumen a
				  	join ref_daftar_kelengkapan b on b.daftar_kelengkapan_id=a.daftar_kelengkapan_id and b.is_deleted=0
				  	join ref_penerapan c on c.penerapan_id=a.penerapan_id and c.is_deleted=0
				  where a.daftar_kelengkapan_id=$kelengkapan_id and a.is_deleted=0 order by a.penerapan_id, b.urutan";
		return $this->db->query($query);
	}

	function update($kelengkapan_id, $valuepair) {
		$filter = array();
		$filter['kelengkapan_dokumen_id'] = $kelengkapan_id;

		//inject updated 
        $valuepair['updated_on'] = date('Y/m/d H:i:s');

        $builder = $this->db->table('tcg_kelengkapan_dokumen');
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

	function add($tahun_ajaran_id, $valuepair) {
		//inject updated 
        $valuepair['tahun_ajaran_id'] = $tahun_ajaran_id;
        $valuepair['created_on'] = date('Y/m/d H:i:s');

        $builder = $this->db->table('tcg_kelengkapan_dokumen');
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
        $filter['kelengkapan_dokumen_id'] = $kelengkapan_id;

		$valuepair = array (
			'expired_date' => date('Y/m/d H:i:s')
		);

        $builder = $this->db->table('tcg_kelengkapan_dokumen');
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