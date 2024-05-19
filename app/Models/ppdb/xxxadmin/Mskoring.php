<?php 
Class Mskoring 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

	function tcg_daftarskoring($tahun_ajaran_id) {
		$query = "select a.*, b.nama as nama, b.jalur_id, b.tipe_skoring_id, d.nama as jalur, a.urutan, b.generated, a.tahun_ajaran_id 
				  from cfg_daftar_nilai_skoring a
				  join ref_daftar_skoring b on a.skoring_id=b.skoring_id and b.is_deleted=0
				  join ref_jalur d on b.jalur_id=d.jalur_id and d.is_deleted=0
				  where a.tahun_ajaran_id='$tahun_ajaran_id' and a.is_deleted=0 and b.kunci=0 
				  order by b.jalur_id, b.tipe_skoring_id, a.skoring_id asc, a.daftar_nilai_skoring_id desc";
		return $this->db->query($query);
	}

	function tcg_ubah_nilai_skoring($daftar_nilai_skoring_id, $key, $value) {
		$query = "update cfg_daftar_nilai_skoring set $key='$value' where daftar_nilai_skoring_id=$daftar_nilai_skoring_id";
		return $this->db->query($query);
	}

	function tcg_ubah_skoring($daftar_nilai_skoring_id, $key, $value) {
		$query = "update ref_daftar_skoring a 
		join cfg_daftar_nilai_skoring b on a.skoring_id=b.skoring_id and b.is_deleted=0
		set 
			a.$key='$value' 
		where b.daftar_nilai_skoring_id=$daftar_nilai_skoring_id";

		return $this->db->query($query);
	}

	function tcg_skoring_detil($daftar_nilai_skoring_id) {
		$query = "select a.*, b.nama as nama, b.jalur_id, b.tipe_skoring_id, d.nama as jalur, a.urutan, b.generated, a.tahun_ajaran_id 
				  from cfg_daftar_nilai_skoring a
				  join ref_daftar_skoring b on a.skoring_id=b.skoring_id and b.is_deleted=0
				  join ref_jalur d on b.jalur_id=d.jalur_id and d.is_deleted=0
				  where a.daftar_nilai_skoring_id='$daftar_nilai_skoring_id'" ;
		return $this->db->query($query);
	}

	function tcg_skoring_baru($tahun_ajaran_id, $jalur_id, $tipe_skoring_id, $nama, $nilai, $urutan) {
		//inject sekolah_id
		$valuepair = array (
			'jalur_id' => $jalur_id,
			'tipe_skoring_id' => $tipe_skoring_id,
			'nama' => $nama,
			'urutan' => $urutan,
			'generated' => 0,
			'kunci' => 0
		);

        //$builder->insert('tcg_transaksi', $valuepair);
        $builder = $this->db->table('ref_daftar_skoring');
        if ($builder->insert($valuepair)) {
            $key = $this->db->insertID();
			//insert nilai
			$valuepair = array (
				'skoring_id' => $key,
				'urutan' => $urutan,
				'tahun_ajaran_id' => $tahun_ajaran_id,
				'nilai' => $nilai
			);
            $builder = $this->db->table('cfg_daftar_nilai_skoring');
			if ($builder->insert($valuepair)) {
				return $this->db->insertID();
			}
		}

		return 0;
	}

	function tcg_nilai_skoring_baru($tahun_ajaran_id, $skoring_id, $nilai, $urutan) {
		//insert nilai
		$valuepair = array (
			'skoring_id' => $skoring_id,
			'urutan' => $urutan,
			'tahun_ajaran_id' => $tahun_ajaran_id,
			'nilai' => $nilai
		);

        $builder = $this->db->table('cfg_daftar_nilai_skoring');
		if ($builder->insert($valuepair)) {
			return $this->db->insertID();
		}

		return 0;
	}

	function tcg_hapus_skoring($daftar_nilai_skoring_id) {
		//soft-delete skoring
		$sql = "update ref_daftar_skoring a 
		join cfg_daftar_nilai_skoring b on a.skoring_id=b.skoring_id and b.is_deleted=0
		set 
			a.expired_date=now() 
		where b.daftar_nilai_skoring_id=$daftar_nilai_skoring_id and a.generated=0";
		if (!$this->db->query($sql)) {
			return 0;
		}

		//delete nilai
		$sql = "delete from cfg_daftar_nilai_skoring where daftar_nilai_skoring_id=$daftar_nilai_skoring_id";
		if (!$this->db->query($sql)) {
			return 0;
		}

		return 1;
	} 

}