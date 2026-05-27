<?php 

namespace App\Models\Dapodik;

use Exception;
use App\Models\Core\MCrud5;

class MDapodikSekolah extends MCrud5
{
    protected $TABLE_NAME = "dapodik_sekolah";
    protected $COLUMNS = array('alamat_jalan', 'bentuk_pendidikan_id', 'bujur', 'desa_kelurahan', 'kode_wilayah', 'last_update', 
                                'lintang', 'nama', 'npsn', 'rt', 'rw', 'sekolah_id', 'status_sekolah');
    protected $INSERT_COLUMNS = array('alamat_jalan', 'bentuk_pendidikan_id', 'bujur', 'desa_kelurahan', 'kode_wilayah', 'last_update', 
                                'lintang', 'nama', 'npsn', 'rt', 'rw', 'sekolah_id', 'status_sekolah');
    protected $COMPULSORY_COLUMNS = array('nama', 'npsn', 'sekolah_id', 'bentuk_pendidikan_id', 'kode_wilayah');

    function __construct() {
        parent::__construct();
    }

    function truncate() {
        $builder = $this->db->table($this->TABLE_NAME);
        $builder->truncate();
    }

}

  