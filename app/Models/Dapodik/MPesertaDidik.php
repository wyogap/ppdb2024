<?php 

namespace App\Models\Dapodik;

use Exception;
use App\Models\Core\MCrud5;

class MPesertaDidik extends MCrud5
{
    protected $TABLE_NAME = "tcg_peserta_didik";
    protected $COLUMNS = array('peserta_didik_id', 'sekolah_id', 'kode_wilayah', 'nama', 'rt', 'rw', 'alamat', 'nama_dusun', 'desa_kelurahan',
                                'jenis_kelamin', 'nisn', 'nik', 'tempat_lahir', 'tanggal_lahir', 'nama_ibu_kandung', 'kebutuhan_khusus',
                                'dapodik_id', 'sekolah_dapodik_id', 'npsn_sekolah_asal', 'nama_sekolah_asal');
    protected $INSERT_COLUMNS = array('sekolah_id', 'kode_wilayah', 'nama', 'rt', 'rw', 'alamat', 'nama_dusun', 'desa_kelurahan',
                                'jenis_kelamin', 'nisn', 'nik', 'tempat_lahir', 'tanggal_lahir', 'nama_ibu_kandung', 'kebutuhan_khusus',
                                'dapodik_id', 'sekolah_dapodik_id', 'npsn_sekolah_asal', 'nama_sekolah_asal');
    protected $COMPULSORY_COLUMNS = array('nama', 'tanggal_lahir', 'jenis_kelamin');

    protected $PRIMARY_KEY = "peserta_didik_id";

    function __construct() {
        parent::__construct();
    }

    function truncate() {
        $builder = $this->db->table($this->TABLE_NAME);
        $builder->truncate();
    }

    function siswa_registrasi() {
        $sql = "select * from tcg_peserta_didik where asal_data=1 and (sekolah_id is null or sekolah_id = '' or sekolah_id = 5421)";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();

        return $result;

        // $filters = [
        //     'asal_data' => 1
        // ];

        // return $this->list(0, null, $filters);
    }
}

  