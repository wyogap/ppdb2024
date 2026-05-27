<?php 

namespace App\Models\Dapodik;

use Exception;
use App\Models\Core\MCrud5;

class MDapodikTka extends MCrud5
{
    protected $TABLE_NAME = "dapodik_tka";
    protected $COLUMNS = array('nisn', 'npsn_asal', 'ikut_tka', 'nopes', 'tgl_tes', 'tgl_terbit', 'nm_mapel_1', 
                                'nm_mapel_2', 'nilai_1', 'nilai_2');
    protected $INSERT_COLUMNS = array('nisn', 'npsn_asal', 'ikut_tka', 'nopes', 'tgl_tes', 'tgl_terbit', 'nm_mapel_1', 
                                'nm_mapel_2', 'nilai_1', 'nilai_2');
    protected $COMPULSORY_COLUMNS = array('nisn', 'npsn_asal', 'ikut_tka');

    function __construct() {
        parent::__construct();
    }

    function truncate() {
        $builder = $this->db->table($this->TABLE_NAME);
        $builder->truncate();
    }
}

  