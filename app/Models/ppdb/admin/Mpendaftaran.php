<?php 
Class Mpendaftaran 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

	function tcg_daftar_pendaftaran($tahun_ajaran_id){
		$sql = "
			SELECT 
				`a`.tahun_ajaran_id,
				`a`.`pendaftaran_id` AS `pendaftaran_id`,
				`a`.`sekolah_id` AS `sekolah_id`,
				`a`.`peserta_didik_id` AS `peserta_didik_id`,
				`a`.`penerapan_id` AS `penerapan_id`,
				`f`.`jalur_id` AS `jalur_id`,
				`f`.`nama` AS `jalur`,
				`a`.`nomor_pendaftaran` AS `nomor_pendaftaran`,
				`a`.`jenis_pilihan` AS `jenis_pilihan`,
				`a`.`kelengkapan_berkas` AS `status_verifikasi_berkas`,
				`a`.`status_penerimaan` AS `status_penerimaan`,
				`a`.`status_daftar_ulang` AS `status_daftar_ulang`,
				`a`.`cabut_berkas` AS `cabut_berkas`,
				`a`.`masuk_jenis_pilihan` AS `masuk_jenis_pilihan`,
				`a`.`peringkat` AS `peringkat`,
				`a`.`skor` AS `skor`,
				`d`.`npsn` AS `npsn`,
				`d`.`nama` AS `nama_sekolah`,
				`d`.`bentuk` AS `bentuk_sekolah`,
				`d`.`status` AS `status_sekolah`,
				`d`.`lintang` AS `lintang_sekolah`,
				`d`.`bujur` AS `bujur_sekolah`,
				`f`.`nama` AS `jalur`,
				`c`.`nama` AS `nama`,
				`c`.`alamat` AS `alamat`,
				`c`.`nama_dusun` AS `nama_dusun`,
				`c`.`nik` AS `nik`,
				`c`.`nisn` AS `nisn`,
				`c`.`nomor_ujian` AS `nomor_ujian`,
				`c`.`jenis_kelamin` AS `jenis_kelamin`,
				`c`.`tempat_lahir` AS `tempat_lahir`,
				`c`.`tanggal_lahir` AS `tanggal_lahir`,
				`c`.`nama_ibu_kandung` AS `nama_ibu_kandung`,
				`c`.`nama_ayah` AS `nama_ayah`,
				`c`.`nama_wali` AS `nama_wali`,
				`c`.`kebutuhan_khusus` AS `kebutuhan_khusus`,
				`c`.`no_KIP` AS `no_KIP`,
				`c`.`lintang` AS `lintang`,
				`c`.`bujur` AS `bujur`,
				`c`.`asal_data` AS `asal_data`,
				`c`.`cabut_berkas` AS `frekuensi_cabut_berkas`,
				`c`.`ubah_pilihan` AS `frekuensi_ubah_pilihan`,
				`c`.`ubah_sekolah` AS `frekuensi_ubah_sekolah`,
				`a`.`status_penerimaan_final` AS `status_penerimaan_final`,
				`a`.`peringkat_final` AS `peringkat_final`,
				`a`.`create_date` AS `create_date`
			FROM
				`tcg_pendaftaran` `a`
				JOIN `ref_penerapan` `b` ON (`a`.`penerapan_id` = `b`.`penerapan_id`)
					AND (`b`.`aktif` = 1)
					AND (`b`.`expired_date` IS NULL)
				JOIN `tcg_peserta_didik` `c` ON (`a`.`peserta_didik_id` = `c`.`peserta_didik_id`)
					AND (`c`.`soft_delete` = 0)
				JOIN `ref_sekolah` `d` ON (`a`.`sekolah_id` = `d`.`sekolah_id`)
				JOIN `ref_jalur` `f` ON (`b`.`jalur_id` = `f`.`jalur_id`)
					AND (`f`.`expired_date` IS NULL)
			WHERE
				(`a`.`kelengkapan_berkas` = 1)
				AND (`a`.`cabut_berkas` = 0)
				AND (`a`.`jenis_pilihan` <> 0)
				AND (`a`.`soft_delete` = 0)
				AND a.tahun_ajaran_id='$tahun_ajaran_id'";

		return $this->db->query($sql);
	}

	function tcg_resetpendaftaran($peserta_didik_id) {
		$pengguna_id = $this->session->get("pengguna_id");

		$query = "CALL " .SQL_RESET_PENDAFTARANSISWA. "('$peserta_didik_id', '$pengguna_id')";
		$this->db->query($query);

		return 1;
	}

	function tcg_cari_pendaftaran($nama, $nisn, $nik, $sekolah_id, $jenis_kelamin, $kode_desa, $kode_kecamatan, $sekolah_tujuan_id, $penerapan_id, $soft_delete){

		$query = "select a.peserta_didik_id, a.nama, a.nisn, a.nik, c.nama as sekolah_asal, e.nama as sekolah_tujuan, f.nama as penerapan, z.*
				  from tcg_pendaftaran z
				  join tcg_peserta_didik a on a.peserta_didik_id=z.peserta_didik_id and a.soft_delete=0
				  left join ref_wilayah b on a.kode_wilayah=b.kode_wilayah and b.expired_date is null
				  join ref_sekolah c on c.sekolah_id=a.sekolah_id and c.expired_date is null
				  join dbo_users d on d.pengguna_id=a.peserta_didik_id and d.is_deleted=0
				  join ref_sekolah e on e.sekolah_id=z.sekolah_id and e.expired_date is null
				  join ref_penerapan f on f.penerapan_id=z.penerapan_id and e.expired_date is null
				  ";

		$where = "a.nama like '%" . $nama . "%'";
		if (!empty($jenis_kelamin))
			$where .= " AND a.jenis_kelamin='" . $jenis_kelamin . "'";
		if (!empty($nisn))
			$where .= " AND a.nisn='" . $nisn . "'";
		if (!empty($nik))
			$where .= " AND a.nik='" . $nik . "'";
		if (!empty($kode_kecamatan))
			$where .= " AND a.kode_kecamatan='" . $kode_kecamatan . "'";
		if (!empty($kode_desa))
			$where .= " AND a.kode_desa='" . $kode_desa . "'";
		if (!empty($sekolah_id))
			$where .= " AND a.sekolah_id='" . $sekolah_id . "'";
		if (!empty($sekolah_tujuan_id))
			$where .= " AND z.sekolah_id='" . $sekolah_tujuan_id . "'";
		if (!empty($penerapan_id))
			$where .= " AND z.penerapan_id='" . $penerapan_id . "'";

		if (empty($soft_delete))
			$where .= " AND z.soft_delete=0";
		else 
			$where .= " AND z.soft_delete='$soft_delete'";

		$query .= " WHERE " . $where;

		return $this->db->query($query);	
	}

    function tcg_ubah_pendaftaran($key, $valuepair) {
		$builder = $this->db->table('tcg_pendaftaran');
        $builder->where('pendaftaran_id', $key);
        $builder->update($valuepair);

        return 1;
    }

    function tcg_hapus_pendaftaran($key) {
		$valuepair = array(
			'soft_delete' => 1,
			'last_update' => date("Y/m/d H:i:s")
		);

		$builder = $this->db->table('tcg_pendaftaran');
        $builder->where('pendaftaran_id', $key);
        $builder->update($valuepair);

        return 1;
    }

	function tcg_detil_pendaftaran($key){

		$query = "select a.peserta_didik_id, a.nama, a.nisn, a.nik, z.*
					from tcg_pendaftaran z
					join tcg_peserta_didik a on a.peserta_didik_id=z.peserta_didik_id and a.soft_delete=0
					left join ref_wilayah b on a.kode_wilayah=b.kode_wilayah and b.expired_date is null
					join ref_sekolah c on c.sekolah_id=a.sekolah_id and c.expired_date is null
					join dbo_users d on d.pengguna_id=a.peserta_didik_id and d.is_deleted=0
					join ref_sekolah e on e.sekolah_id=z.sekolah_id and e.expired_date is null
					join ref_penerapan f on f.penerapan_id=z.penerapan_id and e.expired_date is null
				  where z.pendaftaran_id='$key'";

		return $this->db->query($query);	
	}

	// function resetpendaftaran()
	// {
	// 	$peserta_didik_id = $_POST["data"] ?? null; (("peserta_didik_id");
	// 	$pengguna_id = $this->session->get("pengguna_id");

	// 	return $this->db->query("CALL " .SQL_RESET_PENDAFTARANSISWA. " ('$peserta_didik_id', '$pengguna_id')");
	// }
	

}