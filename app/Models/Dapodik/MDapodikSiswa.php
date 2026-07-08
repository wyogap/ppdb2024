<?php 

namespace App\Models\Dapodik;

use Exception;
use App\Models\Core\MCrud5;

class MDapodikSiswa extends MCrud5
{
    protected $TABLE_NAME = "ppdb2026.dapodik_siswa";
    protected $COLUMNS = array('alamat_jalan', 'bujur', 'desa_kelurahan', 'jenis_kelamin', 'kebutuhan_khusus', 'kode_wilayah', 'last_update', 
                                'lintang', 'nama', 'nama_ayah', 'nama_dusun', 'nama_ibu_kandung', 'nama_wali', 'nik', 
                                'nisn', 'no_KIP', 'no_kk', 'pekerjaan_ayah', 'pekerjaan_ibu', 'peserta_didik_id',
                                'rt', 'rw', 'sekolah_id', 'tanggal_lahir', 'tempat_lahir', 'agama_id');
    protected $INSERT_COLUMNS = array('alamat_jalan', 'bujur', 'desa_kelurahan', 'jenis_kelamin', 'kebutuhan_khusus', 'kode_wilayah', 'last_update', 
                                'lintang', 'nama', 'nama_ayah', 'nama_dusun', 'nama_ibu_kandung', 'nama_wali', 'nik', 
                                'nisn', 'no_KIP', 'no_kk', 'pekerjaan_ayah', 'pekerjaan_ibu', 'peserta_didik_id',
                                'rt', 'rw', 'sekolah_id', 'tanggal_lahir', 'tempat_lahir', 'agama_id');
    protected $COMPULSORY_COLUMNS = array('nama', 'nik', 'sekolah_id', 'peserta_didik_id', 'tanggal_lahir');

    function __construct() {
        parent::__construct();
    }

    function truncate() {
        $builder = $this->db->table($this->TABLE_NAME);
        $builder->truncate();
    }
   
    function siswa_tidakadatka() {
        $sql = "
            select a.peserta_didik_id, a.nisn, a.nik, a.nama, a.jenis_kelamin, a.tanggal_lahir
            from (
                SELECT 
                    a.peserta_didik_id, a.nisn, a.nik, a.nama, a.jenis_kelamin, a.tanggal_lahir,
                    a.sekolah_id, b.nama as nama_sekolah, c.nama_kec as kec_sekolah, c.nama_desa as desa_sekolah,
                    d.ikut_tka, d.nopes, d.nm_mapel_1, d.nm_mapel_2, d.nilai_1, d.nilai_2 
                FROM ppdb2026.dapodik_siswa a
                join ppdb_2026.ref_sekolah b on b.dapodik_id=a.sekolah_id
                    and b.bentuk in ('SD','MI')
                left join ppdb_2026.ref_wilayah c on c.kode_wilayah=b.kode_wilayah
                left join ppdb2026.dapodik_tka d on d.nisn=a.nisn
            ) a
            where a.ikut_tka is null
        ";

        $query = $this->db->query($sql);
        if (empty($query)) return null;
        
        return $query->getResultArray();
    }    

    function siswa_belumtarikprestasi() {
        $sql = "
            select a.peserta_didik_id, a.nisn, a.nik, a.nama, a.jenis_kelamin, a.tanggal_lahir, a.npsn
            from (
                SELECT 
                    a.peserta_didik_id, a.nisn, a.nik, a.nama, a.jenis_kelamin, a.tanggal_lahir, b.npsn,
                    coalesce(d.cnt, 0) as cnt
                FROM ppdb2026.dapodik_siswa a
                join ppdb_2026.ref_sekolah b on b.dapodik_id=a.sekolah_id
                    and b.bentuk in ('SD','MI')
                left join ppdb_2026.ref_wilayah c on c.kode_wilayah=b.kode_wilayah
                left join (
                    select a.nisn, count(*) as cnt from ppdb2026.dapodik_prestasi a group by a.nisn
                ) d on d.nisn=a.nisn
            ) a
            where a.cnt = 0
        ";

        $query = $this->db->query($sql);
        if (empty($query)) return null;
        
        return $query->getResultArray();
    }    
}

  