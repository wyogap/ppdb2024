<?php 
Class Msekolah 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

	function tcg_cari_sekolah($nama, $npsn, $bentuk_pendidikan, $status){

		$query = "select a.* from ref_sekolah a";
				
		$where = "a.is_deleted=0 and a.nama like '%" . $nama . "%'";
		if (!empty($npsn))
			$where .= " AND a.npsn='$npsn'";
		if (!empty($bentuk_pendidikan))
			$where .= " AND a.bentuk='$bentuk_pendidikan'";
		if (!empty($status))
			$where .= " AND a.status='$status'";

		$query .= " WHERE " . $where;

		return $this->db->query($query);						  
	}

	function tcg_detil_sekolah($key) {
		$query = "select a.* from ref_sekolah a
		WHERE a.sekolah_id = '$key' and a.is_deleted=0
		";

		return $this->db->query($query);
   }

    function tcg_ubah_sekolah($key, $valuepair) {
		$builder = $this->db->table('ref_sekolah');
        $builder->where('sekolah_id', $key);
        $builder->update($valuepair);

        return 1;
    }

	function tcg_kuotasekolahnegeri($tahun_ajaran_id) {
		$query = "select * from v_tcg_kuota_sekolah where status='N' and tahun_ajaran_id='$tahun_ajaran_id' and expired_date is null order by kode_wilayah";
		return $this->db->query($query);
	}

	function tcg_kuotasekolahswasta($tahun_ajaran_id) {
		$query = "select * from v_tcg_kuota_sekolah where status='S' and tahun_ajaran_id='$tahun_ajaran_id' and expired_date is null order by kode_wilayah";
		return $this->db->query($query);
	}

	function tcg_kuotasekolah($tahun_ajaran_id, $sekolah_id) {
		$query = "select * from v_tcg_kuota_sekolah where sekolah_id='$sekolah_id' and tahun_ajaran_id='$tahun_ajaran_id' and expired_date is null";
		return $this->db->query($query);
	}

	function tcg_ubahkuotanegeri($tahun_ajaran_id, $sekolah_id, $kuota_total, $kuota_zonasi, $kuota_prestasi, $kuota_afirmasi, $kuota_perpindahan, $kuota_inklusi, $kuota_susulan) {
		$query = "CALL usp_ubah_kuotanegeri_2022 ($tahun_ajaran_id, '$sekolah_id', $kuota_total, '$kuota_zonasi', '$kuota_prestasi', '$kuota_afirmasi', '$kuota_perpindahan', '$kuota_inklusi', '$kuota_susulan')";

		$retval = "0";
		if ($this->db->query($query)) {
			$retval = 1;
		}

		if ($retval > 0) {
			$keys = "tahun_ajaran_id;kuota_total;kuota_zonasi;kuota_prestasi;kuota_afirmasi;kuota_perpindahan;kuota_inklusi,kuota_susulan";
			$values = "$tahun_ajaran_id;$kuota_total;$kuota_zonasi;$kuota_prestasi;$kuota_afirmasi;$kuota_perpindahan;$kuota_inklusi;$kuota_susulan";

			//put in audit trail
			$this->tcg_audit_trail("tcg_penerapan_sekolah",$sekolah_id,'update','Update kuota sekolah',$keys,$values);
		}

		return $retval;
	}
	
	function tcg_ubahkuotaswasta($tahun_ajaran_id, $sekolah_id, $ikut_ppdb, $kuota_zonasi, $kuota_prestasi, $kuota_afirmasi, $kuota_perpindahan, $kuota_inklusi, $kuota_swasta) {
		$query = "CALL usp_ubah_kuotaswasta_2022 ($tahun_ajaran_id, '$sekolah_id', $ikut_ppdb, '$kuota_zonasi', '$kuota_prestasi', '$kuota_afirmasi', '$kuota_perpindahan', '$kuota_inklusi', '$kuota_swasta')";

		$retval = "0";
		if ($this->db->query($query)) {
			$retval = 1;
		}

		if ($retval > 0) {
			$keys = "tahun_ajaran_id;ikut_ppdb;kuota_zonasi;kuota_prestasi;kuota_afirmasi;kuota_perpindahan;kuota_inklusi;kuota_swasta";
			$values = "$tahun_ajaran_id;$ikut_ppdb;$kuota_zonasi;$kuota_prestasi;$kuota_afirmasi;$kuota_perpindahan;$kuota_inklusi;$kuota_swasta";

			//put in audit trail
			$this->tcg_audit_trail("tcg_penerapan_sekolah",$sekolah_id,'update','Update kuota sekolah',$keys,$values);
		}

		return $retval;
	}

	
	function profilsekolah(){
		$sekolah_id = $this->session->get("sekolah_id");

		$builder = $this->db->table('ref_sekolah a');
		$builder->select('a.sekolah_id,a.npsn,a.nama,a.bentuk,a.status,a.alamat_jalan,a.desa_kelurahan,c3.nama AS kecamatan,a.lintang,a.bujur');
		$builder->join('ref_wilayah c','a.kode_wilayah = c.kode_wilayah AND c.is_deleted=0','LEFT OUTER');
		$builder->join('ref_wilayah c3','c.kode_wilayah_kec = c3.kode_wilayah AND c3.is_deleted=0','LEFT OUTER');
		$builder->where('a.sekolah_id',$sekolah_id);
		return $builder->get();
	}

	function tcg_audit_trail($table, $reference, $action, $description, $old_values, $new_values) {
		$pengguna_id = $this->session->get("user_id");

		$query = "CALL usp_audit_trail(?,?,?,?,?,?,?,?)";
		return $this->db->query($query, array($table,$reference,$action,$pengguna_id,$description,null,$new_values,$old_values));
	}
}