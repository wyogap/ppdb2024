<?php 

namespace App\Models\Dapodik;

use Exception;
use App\Models\Core\MCrud5;

class MDapodikSiswa extends MCrud5
{
    protected $TABLE_NAME = "dapodik_siswa";
    protected $COLUMNS = array('alamat_jalan', 'bujur', 'desa_kelurahan', 'jenis_kelamin', 'kebutuhan_khusus', 'kode_wilayah', 'last_update', 
                                'lintang', 'nama', 'nama_ayah', 'nama_dusun', 'nama_ibu_kandung', 'nama_wali', 'nik', 
                                'nisn', 'no_KIP', 'no_kk', 'pekerjaan_ayah', 'pekerjaan_ibu', 'peserta_didik_id',
                                'rt', 'rw', 'sekolah_id', 'tanggal_lahir', 'tempat_lahir');
    protected $INSERT_COLUMNS = array('alamat_jalan', 'bujur', 'desa_kelurahan', 'jenis_kelamin', 'kebutuhan_khusus', 'kode_wilayah', 'last_update', 
                                'lintang', 'nama', 'nama_ayah', 'nama_dusun', 'nama_ibu_kandung', 'nama_wali', 'nik', 
                                'nisn', 'no_KIP', 'no_kk', 'pekerjaan_ayah', 'pekerjaan_ibu', 'peserta_didik_id',
                                'rt', 'rw', 'sekolah_id', 'tanggal_lahir', 'tempat_lahir');
    protected $COMPULSORY_COLUMNS = array('nama', 'nisn', 'sekolah_id', 'peserta_didik_id', 'tanggal_lahir');

    function __construct() {
        parent::__construct();
    }

    function truncate() {
        $builder = $this->db->table($this->TABLE_NAME);
        $builder->truncate();
    }
}

  