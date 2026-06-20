<?php 

namespace App\Models\Dapodik;

use Exception;
use App\Models\Core\MCrud5;

class MSekolah extends MCrud5
{
    protected $TABLE_NAME = "ref_sekolah";
    protected $COLUMNS = array('sekolah_id', 'dapodik_id', 'nama', 'npsn', 'bentuk', 'jenjang_id', 'status', 
                                'nama_dusun', 'desa_kelurahan', 'kode_wilayah', 'kecamatan', 'kode_wilayah_kec', 
                                'kabupaten', 'kode_wilayah_kab');
    protected $INSERT_COLUMNS = array('dapodik_id', 'nama', 'npsn', 'bentuk', 'jenjang_id', 'status', 
                                'nama_dusun', 'desa_kelurahan', 'kode_wilayah', 'kecamatan', 'kode_wilayah_kec', 
                                'kabupaten', 'kode_wilayah_kab');
    protected $COMPULSORY_COLUMNS = array('nama', 'npsn');

    protected $PRIMARY_KEY = "sekolah_id";

    function __construct() {
        parent::__construct();
    }

    function truncate() {
        $builder = $this->db->table($this->TABLE_NAME);
        $builder->truncate();
    }

    function getJenjangId($jenjang) {
        $jenjang_id = null;

        $sql = "select jenjang_id from ref_jenjang where nama_jenjang=?";
        $query = $this->db->query($sql, [$jenjang]);
        if (!empty($query)) {
            $row = $query->getRowArray();
            if (!empty($row)) {
                $jenjang_id = $row['jenjang_id'];
            }
        }

        return $jenjang_id;
    }

    function getSekolahIdByNpsn($npsn) {
        $sekolah_id = null;

        $sql = "select sekolah_id from ref_sekolah where npsn=?";
        $query = $this->db->query($sql, [$npsn]);
        if (!empty($query)) {
            $row = $query->getRowArray();
            if (!empty($row)) {
                $sekolah_id = $row['sekolah_id'];
            }
        }

        return $sekolah_id;
    }

}

  