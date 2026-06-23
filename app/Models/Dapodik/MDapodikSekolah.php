<?php 

namespace App\Models\Dapodik;

use Exception;
use App\Models\Core\MCrud5;

class MDapodikSekolah extends MCrud5
{
    protected $TABLE_NAME = "ppdb2026.dapodik_sekolah";
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

    function sekolah_tidakadasiswa() {
        $sql = "
            select a.*
            from (
                SELECT a.sekolah_id, a.bentuk_pendidikan_id, a.npsn, a.nama, c.nama_kec, c.nama_desa, coalesce(b.cnt,0) as cnt
                FROM ppdb2026.dapodik_sekolah a
                left join (
                    select sekolah_id, count(*) cnt
                    from ppdb2026.dapodik_siswa
                    group by sekolah_id
                ) b on b.sekolah_id=a.sekolah_id
                left join ppdb_2026.ref_wilayah c on c.kode_wilayah=a.kode_wilayah
            ) a
            where a.cnt=0 and a.bentuk_pendidikan_id!=6
            ;";

        $query = $this->db->query($sql);
        if (empty($query)) return null;

        return $query->getResultArray();
    }

    function sekolah_tidakadatka() {
        $sql = "
            select a.*
            from (
                SELECT a.sekolah_id, a.bentuk_pendidikan_id, a.npsn, a.nama, c.nama_kec, c.nama_desa
                    , coalesce(b.cnt,0) as cnt_tka, coalesce(d.cnt,0) as cnt_siswa
                FROM ppdb_2026.dapodik_sekolah a
                left join (
                    select b.sekolah_id, count(*) cnt
                    from ppdb_2026.dapodik_tka a
                    join ppdb_2026.dapodik_siswa b on b.nisn=a.nisn
                    group by sekolah_id
                ) b on b.sekolah_id=a.sekolah_id
                left join ppdb_2026.ref_wilayah c on c.kode_wilayah=a.kode_wilayah
                left join (
                    select sekolah_id, count(*) cnt
                    from ppdb_2026.dapodik_siswa
                    group by sekolah_id
                ) d on d.sekolah_id=a.sekolah_id
                where a.bentuk_pendidikan_id in (5,9)
            ) a
            where a.cnt_tka=0 
            ;
        ";

        $query = $this->db->query($sql);
        if (empty($query)) return null;
        
        return $query->getResultArray();
    }

    function missing_sekolah() {
        $sql = "
            select rs.npsn, rs.nama, rs.kode_wilayah
            from ppdb_2026.ref_sekolah rs 
            join ppdb_2026.ref_wilayah rw on rw.kode_wilayah=rs.kode_wilayah 
            left join ppdb2026.dapodik_sekolah ds on ds.npsn=rs.npsn 
            left join (
                select sekolah_id, count(*) cnt
                from ppdb2026.dapodik_siswa
                group by sekolah_id
            ) b on b.sekolah_id=rs.dapodik_id
            where rw.kode_wilayah_kab = '030500'
                and ds.npsn is null
                and rs.bentuk not in ('SMP', 'MTs')
                and coalesce(b.cnt, 0)=0
        ";

        $query = $this->db->query($sql);
        if (empty($query)) return null;

        return $query->getResultArray();
    }
}

  