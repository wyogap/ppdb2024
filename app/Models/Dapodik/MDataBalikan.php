<?php 

namespace App\Models\Dapodik;

use Exception;
use App\Models\Core\MCrud5;

class MDataBalikan extends MCrud5
{
    protected $TABLE_NAME = "ppdb_2026.tcg_data_balikan";
    protected $COLUMNS = array('peserta_didik_id', 'nik', 'nama', 'tempat_lahir', 'tanggal_lahir',
	                            'jenis_kelamin', 'nama_ibu_kandung', 'alamat_jalan', 'rt', 'rw',
	                            'kode_wilayah_siswa', 'agama_id', 'npsn_sekolah_tujuan', 'nama_sekolah_tujuan', 'agama_id',
                                'nisn');
    protected $INSERT_COLUMNS = array();
    protected $UPDATE_COLUMNS = array('sync_status', 'return_id');
    protected $COMPULSORY_COLUMNS = array();

    function __construct() {
        parent::__construct();
    }

    // function truncate() {
    //     $builder = $this->db->table($this->TABLE_NAME);
    //     $builder->truncate();
    // }

    function siswa_belumsync() {
        $filter = array(
            'sync_status' => 0,
        );

        return $this->list(0, null, $filter);
    }    


    function siswa_agamainvalid() {
        $filter = array(
            'agama_id' => null,
        );

        return $this->list(0, null, $filter);
    }    


    function update_syncstatus($id, $sync_status, $return_id) {

        $sql = "update ppdb_2026.tcg_data_balikan set sync_status=?, return_id=? where peserta_didik_id=?";
        $query = $this->db->query($sql, array($sync_status, $return_id, $id));

        return 1;
    }

    function update_agama($id, $agama_id) {

        $sql = "update ppdb_2026.tcg_data_balikan set agama_id=? where peserta_didik_id=?";
        $query = $this->db->query($sql, array($agama_id, $id));

        return 1;
    }

}

  