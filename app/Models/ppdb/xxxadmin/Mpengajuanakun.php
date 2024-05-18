<?php 
Class Mpengajuanakun 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

	function daftarpengajuanakun(){
		$builder = $this->db->table('dbo_users a');
		$builder->select('a.user_id,d.npsn,d.sekolah_id,d.nama AS sekolah,b.nisn,a.nama,c.nama_kab AS kabupaten,a.user_name as username');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_wilayah c','b.kode_wilayah = c.kode_wilayah AND c.is_deleted=0','LEFT OUTER');
		$builder->join('ref_sekolah d','b.sekolah_id = d.sekolah_id','LEFT OUTER');
		$builder->where(array('a.approval'=>0,'a.role_id'=>1,'a.registrasi'=>1,'a.is_deleted'=>0));
		return $builder->get();
	}

	function daftarpersetujuanakun(){
		$builder = $this->db->table('dbo_users a');
		$builder->select('a.user_id,d.npsn,d.sekolah_id,d.nama AS sekolah,b.nisn,a.nama,c2.nama AS kabupaten,a.user_name as username,a.approval');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_wilayah c','b.kode_wilayah = c.kode_wilayah AND c.is_deleted=0','LEFT OUTER');
		$builder->join('ref_wilayah c2','c.kode_wilayah_kab = c2.kode_wilayah AND c2.is_deleted=0','LEFT OUTER');
		$builder->join('ref_sekolah d','b.sekolah_id = d.sekolah_id','LEFT OUTER');
		$builder->where(array('a.approval'=>1,'a.role_id'=>1,'a.registrasi'=>1,'a.is_deleted'=>0));
		return $builder->get();
	}
	function daftartidaksetujuakun(){
		$builder = $this->db->table('dbo_users a');
		$builder->select('a.user_id,d.npsn,d.sekolah_id,d.nama AS sekolah,b.nisn,a.nama,c.nama_kab AS kabupaten,a.user_name as username,a.approval');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_wilayah c','b.kode_wilayah = c.kode_wilayah AND c.is_deleted=0','LEFT OUTER');
		$builder->join('ref_sekolah d','b.sekolah_id = d.sekolah_id','LEFT OUTER');
		$builder->where(array('a.approval'=>2,'a.role_id'=>1,'a.registrasi'=>1,'a.is_deleted'=>0));
		return $builder->get();
	}

	function detailpengajuanakun($user_id){
		//$user_id = $_GET["user_id"] ?? null; 

		$builder = $this->db->table('dbo_users a');
		$builder->select('a.user_id,a.user_name as username,b.sekolah_id,c.npsn,c.nama AS sekolah,b.nik,b.nisn,b.nomor_ujian,b.nama,b.jenis_kelamin,b.tempat_lahir,b.tanggal_lahir,b.nama_ibu_kandung,b.kebutuhan_khusus,b.alamat,d.nama_desa AS desa_kelurahan,d.nama_kec AS kecamatan,d.nama_kab AS kabupaten,d.nama_prov AS provinsi,b.lintang,b.bujur');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_sekolah c','b.sekolah_id = c.sekolah_id','LEFT OUTER');
		$builder->join('ref_wilayah d','b.kode_wilayah = d.kode_wilayah AND d.is_deleted=0','LEFT OUTER');
		$builder->where(array('a.user_id'=>$user_id,'a.approval'=>0,'a.role_id'=>1,'a.registrasi'=>1,'a.is_deleted'=>0));
		return $builder->get();
	}
	
	function pengajuanakun($user_id, $approval)
	{
		// $user_id = $_POST["user_id"] ?? null; 
		// $approval = $_POST["approval"] ?? null; 

		$data = array(
			'approval' => $approval,
			'updated_on' => date("Y/m/d H:i:s")
		);

		$builder = $this->db->table('dbo_users');
		$builder->where(array('user_id'=>$user_id,'role_id'=>1,'is_deleted'=>0));
		return $builder->update($data);
	}
	
}