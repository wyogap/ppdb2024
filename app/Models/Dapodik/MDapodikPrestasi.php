<?php 

namespace App\Models\Dapodik;

use Exception;
use App\Models\Core\MCrud5;

class MDapodikPrestasi extends MCrud5
{
    protected $TABLE_NAME = "ppdb2026.dapodik_prestasi";
    protected $COLUMNS = array('nisn', 'npsn', 'nama_ajang', 'penyelenggara', 'ajang_puspresnas', 'nama_cabang', 
                                'bidang', 'sub_bidang', 'achievement_description', 'predikat', 'rating_ajang', 'tingkat', 
                                'tahun', 'link_cv');
    protected $INSERT_COLUMNS = array('nisn', 'npsn', 'nama_ajang', 'penyelenggara', 'ajang_puspresnas', 'nama_cabang', 
                                'bidang', 'sub_bidang', 'achievement_description', 'predikat', 'rating_ajang', 'tingkat', 
                                'tahun', 'link_cv');
    protected $COMPULSORY_COLUMNS = array('nisn', 'npsn', 'nama_ajang', 'achievement_description', 'tingkat');

    function __construct() {
        parent::__construct();
    }

    function truncate() {
        $builder = $this->db->table($this->TABLE_NAME);
        $builder->truncate();
    }
}

  