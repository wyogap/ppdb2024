<?php 
Class Mperubahandata 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

	function daftarpengajuanperubahandata(){
		$builder = $this->db->table('dbo_perubahan_data_siswa a');
		$builder->select('a.peserta_didik_id,a.approval,b.nisn,b.nama,e.sekolah_id,e.npsn,e.nama AS sekolah,b.nik,b.jenis_kelamin,b.kebutuhan_khusus,b.tempat_lahir,b.nama_ibu_kandung,b.alamat,
		a.tanggal_lahir AS tanggal_lahir_baru,a.tanggal_lahir_lama,
		c.nama AS desa_baru,d.nama AS desa_lama,
		a.kode_wilayah as kode_wilayah_baru, a.kode_wilayah_lama,
		a.lintang AS lintang_baru,a.lintang_lama,
		a.bujur AS bujur_baru,a.bujur_lama,
		a.created_on, a.keterangan_approval, f.nama as pengguna');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_wilayah c','a.kode_wilayah = c.kode_wilayah AND c.id_level_wilayah = 4 AND c.is_deleted=0','LEFT OUTER');
		$builder->join('ref_wilayah d','b.kode_wilayah = d.kode_wilayah AND d.id_level_wilayah = 4 AND d.is_deleted=0','LEFT OUTER');
		$builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
		$builder->join('dbo_users f','a.pengguna_id = f.user_id','LEFT OUTER');
		$builder->where(array('a.approval'=>0,'a.is_deleted'=>0));
		return $builder->get();
	}

	function daftarpersetujuanperubahandata(){

		$builder = $this->db->table('dbo_perubahan_data_siswa a');
		$builder->select('a.peserta_didik_id,a.approval,b.nisn,b.nama,e.sekolah_id,e.npsn,e.nama AS sekolah,b.nik,b.jenis_kelamin,b.kebutuhan_khusus,b.tempat_lahir,b.nama_ibu_kandung,b.alamat,
a.tanggal_lahir AS tanggal_lahir_baru,a.tanggal_lahir_lama,
c.nama AS desa_baru,d.nama AS desa_lama,
a.kode_wilayah as kode_wilayah_baru, a.kode_wilayah_lama,
a.lintang AS lintang_baru,a.lintang_lama,
a.bujur AS bujur_baru,a.bujur_lama,
a.created_on, a.keterangan_approval, f.nama as pengguna');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_wilayah c','a.kode_wilayah = c.kode_wilayah AND c.id_level_wilayah = 4 AND c.is_deleted=0','LEFT OUTER');
		$builder->join('ref_wilayah d','a.kode_wilayah_lama = d.kode_wilayah AND d.id_level_wilayah = 4 AND d.is_deleted=0','LEFT OUTER');
		$builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
		$builder->join('dbo_users f','a.pengguna_id = f.user_id','LEFT OUTER');
		$builder->where(array('a.approval !='=>0,'a.is_deleted'=>0));
		return $builder->get();
	}

	function detailperubahandatasiswa($peserta_didik_id){
		// $peserta_didik_id = $_GET["peserta_didik_id"] ?? null; (("peserta_didik_id");

		$builder = $this->db->table('dbo_perubahan_data_siswa a');
		$builder->select('a.peserta_didik_id,a.approval,b.nisn,b.nama,e.sekolah_id,e.npsn,e.nama AS sekolah,b.nik,b.jenis_kelamin,b.kebutuhan_khusus,b.tempat_lahir,b.nama_ibu_kandung,b.alamat,
a.tanggal_lahir AS tanggal_lahir_baru,b.tanggal_lahir AS tanggal_lahir_lama,
c.nama AS desa_baru,d.nama AS desa_lama,
a.lintang AS lintang_baru,b.lintang AS lintang_lama,
a.bujur AS bujur_baru,b.bujur AS bujur_lama,
a.tanggal_lahir,a.kode_wilayah,a.lintang,a.bujur,a.created_on,b.asal_data');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_wilayah c','a.kode_wilayah = c.kode_wilayah AND c.id_level_wilayah = 4 AND c.is_deleted=0','LEFT OUTER');
		$builder->join('ref_wilayah d','b.kode_wilayah = d.kode_wilayah AND d.id_level_wilayah = 4 AND d.is_deleted=0','LEFT OUTER');
		$builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.approval'=>0,'a.is_deleted'=>0));
		return $builder->get();
	}

	function pengajuanperubahandata($peserta_didik_id, $approval, $keterangan_approval, $kode_wilayah, $tanggal_lahir, $lintang, $bujur)
	{
		$user_id = $this->session->get("user_id");

		// $peserta_didik_id = $_POST["peserta_didik_id"] ?? null;
		// $approval = $_POST["approval"] ?? null; 
		// $kode_wilayah = $_POST["kode_wilayah"] ?? null;
		// if($kode_wilayah==''){
		// 	$kode_wilayah = $_POST["kode_desa"] ?? null; 
		// }
		// $tanggal_lahir = $_POST["tanggal_lahir"] ?? null; 
		// $lintang = $_POST["lintang"] ?? null; 
		// $bujur = $_POST["bujur"] ?? null; 
		// $keterangan_approval = $_POST["keterangan_approval"] ?? null; 
		// $asal_data = $_POST["asal_data"] ?? null; 
				
		return $this->db->query("CALL " .SQL_UBAH_DATA. " ('$peserta_didik_id',$approval,'$keterangan_approval','$kode_wilayah','$tanggal_lahir','$lintang','$bujur','$user_id')");
	}
	
}