<?php 
Class Mtahapan
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

	function tcg_tahapan_aktif(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "select a.tahapan_id, b.nama as tahapan, a.tanggal_mulai, a.tanggal_selesai, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah 
				  from tcg_waktu_pelaksanaan a
				  join ref_tahapan b on a.tahapan_id=b.tahapan_id and b.is_deleted=0
				  where a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran' and a.is_deleted=0
						and (a.tanggal_mulai <= now() or a.tanggal_mulai is null)
						and (a.tanggal_selesai >= now() or a.tanggal_selesai is null)
				  order by a.tahapan_id asc";

		return $this->db->query($query);
	}


	function tcg_tahapan($tahun_ajaran_id) {
		$query = "select a.*, b.nama as tahapan from tcg_waktu_pelaksanaan a
				  join ref_tahapan b on a.tahapan_id=b.tahapan_id and b.is_deleted=0
				  where a.tahun_ajaran_id='$tahun_ajaran_id' and a.is_deleted=0 order by a.tahapan_id";
		return $this->db->query($query);
	}

	function tcg_update_tahapan($waktu_pelaksanaan_id, $key, $value) {
		$query = "update tcg_waktu_pelaksanaan set $key='$value' where waktu_pelaksanaan_id=$waktu_pelaksanaan_id";
		return $this->db->query($query);
	}

	function tcg_view_tahapan($waktu_pelaksanaan_id) {
		$query = "select a.*, b.nama as tahapan from tcg_waktu_pelaksanaan a
				  join ref_tahapan b on a.tahapan_id=b.tahapan_id and b.is_deleted=0
				  where a.waktu_pelaksanaan_id='$waktu_pelaksanaan_id'";
		return $this->db->query($query);
	}


}