<?php 

namespace App\Models\Ppdb;

Class Mhome 
{
    protected $db;
    protected $session;
    protected $tahun_ajaran_id;
    protected $putaran;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");
		$this->putaran = $this->session->get("putaran_aktif");
    }

	function carisiswa($nama){
		$query = "select a.peserta_didik_id,a.nama,a.jenis_kelamin,b.nama AS sekolah
				  from dbo_peserta_didik a
				  join ref_sekolah b on a.sekolah_id = b.sekolah_id
				  join tcg_pendaftaran c on a.peserta_didik_id = c.peserta_didik_id and c.is_deleted=0
				  where a.is_deleted=0 and (a.nama like ?)
				  group by a.peserta_didik_id,a.nama,a.jenis_kelamin,b.nama";

		return $this->db->query($query, array("%$nama%"));
	}

	function tcg_daftarpenerapan(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$builder = $this->db->table('ref_penerapan a');
		$builder->select('a.penerapan_id,a.jalur_id,c.nama AS jalur');
		$builder->join('ref_jalur c','a.jalur_id = c.jalur_id AND c.expired_date IS NULL');
		$builder->where(array('a.aktif'=>1,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.expired_date'=>NULL));
		return $builder->get();
	}

	function tcg_dashboardwilayah(){
		$kode_wilayah = $this->session->get('kode_wilayah_aktif');
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$builder = $this->db->table('rpt_rekapitulasi_wilayah a');
		$builder->select('a.rekapitulasi_wilayah_id,
			a.kode_wilayah,
			a.nama,
			a.kuota_total,
			a.total_pendaftar,
			a.belum_diperingkat,
			a.masuk_kuota,
			a.daftar_tunggu,
			a.tidak_masuk_kuota,
			a.diterima_pilihan1,
			a.belum_diverifikasi,
			a.verifikasi_berkas_lengkap,
			a.verifikasi_berkas_tidak_lengkap,
			a.total_pendaftar_madrasah,
			a.total_pendaftar_sd,
			a.kuota_zonasi,
			a.pendaftar_zonasi,
			a.zonasi_diterima,
			a.zonasi_tidak_diterima,
			a.zonasi_belum_diperingkat,
			a.kuota_prestasi,
			a.pendaftar_prestasi,
			a.prestasi_diterima,
			a.prestasi_tidak_diterima,
			a.prestasi_belum_diperingkat,
			a.kuota_perpindahan_orang_tua,
			a.pendaftar_perpindahan_orang_tua,
			a.perpindahan_orang_tua_diterima,
			a.perpindahan_orang_tua_tidak_diterima,
			a.perpindahan_orang_tua_belum_diperingkat,
			a.kuota_khusus,
			a.pendaftar_khusus,
			a.khusus_diterima,
			a.khusus_tidak_diterima,
			a.khusus_belum_diperingkat,
			a.kuota_swasta,
			a.pendaftar_swasta,
			a.swasta_diterima,
			a.swasta_tidak_diterima,
			a.swasta_belum_diperingkat,
			a.kuota_olahraga_dan_bakat,
			a.pendaftar_olahraga_dan_bakat,
			a.olahraga_dan_bakat_diterima,
			a.olahraga_dan_bakat_tidak_diterima,
			a.olahraga_dan_bakat_belum_diperingkat,
			a.kuota_inklusi,
			a.pendaftar_inklusi,
			a.inklusi_diterima,
			a.inklusi_tidak_diterima,
			a.inklusi_belum_diperingkat,
			a.kuota_perbatasan,
			a.pendaftar_perbatasan,
			a.perbatasan_diterima,
			a.perbatasan_tidak_diterima,
			a.perbatasan_belum_diperingkat,
			a.kuota_afirmasi,
			a.pendaftar_afirmasi,
			a.afirmasi_diterima,
			a.afirmasi_tidak_diterima,
			a.afirmasi_belum_diperingkat,
			a.kuota_madrasah,
			a.pendaftar_madrasah,
			a.madrasah_diterima,
			a.madrasah_tidak_diterima,
			a.madrasah_belum_diperingkat,
			a.kode_wilayah_penerapan,
			a.tahun_ajaran_id
			');
        $builder->where('a.kode_wilayah',$kode_wilayah);
		$builder->where('a.tahun_ajaran_id',$tahun_ajaran_id);
		return $builder->get();
	}    

	// function tcg_dashboardpenerapan(){
	// 	//$bentuk = $this->input->get("bentuk");
		
	// 	$sekolah_id = $this->input->get("sekolah_id");
	// 	$builder->select('a.penerapan_id,c.nama AS jalur,d.kuota');
	// 	$builder = $this->db->table('ref_penerapan a');
	// 	$builder->join('ref_jalur c','a.jalur_id = c.jalur_id AND c.expired_date IS NULL');
	// 	$builder->join('dbo_penerapan_sekolah d','a.penerapan_id = d.penerapan_id AND d.is_deleted = 0');
	// 	$builder->where(array('a.aktif'=>1,'a.expired_date'=>NULL,'d.sekolah_id'=>$sekolah_id,'a.tahun_ajaran_id'=>$this->tahun_ajaran_id));
	// 	$builder->order_by('c.nama DESC');
	// 	return $builder->get();
	// }

	function tcg_dashboardline(){
		
		$day0 = date("Y-m-d") ;
		$nextday = date("Y-m-d", strtotime("+1 days"));
		$day1 = date("Y-m-d", strtotime("-1 days"));
		$day2 = date("Y-m-d", strtotime("-2 days"));
		$day3 = date("Y-m-d", strtotime("-3 days"));
		$day4 = date("Y-m-d", strtotime("-4 days"));
		$day5 = date("Y-m-d", strtotime("-5 days"));
		$day6 = date("Y-m-d", strtotime("-6 days"));
		$day7 = date("Y-m-d", strtotime("-7 days"));
		
		$query = "SELECT 
		'$day0' as hari_ini,
		SUM(CASE WHEN created_on BETWEEN '$day0' AND '$nextday' THEN 1 ELSE 0 END) AS day_0,
		SUM(CASE WHEN created_on BETWEEN '$day1' AND '$day0' THEN 1 ELSE 0 END) AS day_1,
		SUM(CASE WHEN created_on BETWEEN '$day2' AND '$day1' THEN 1 ELSE 0 END) AS day_2,
		SUM(CASE WHEN created_on BETWEEN '$day3' AND '$day2' THEN 1 ELSE 0 END) AS day_3,
		SUM(CASE WHEN created_on BETWEEN '$day4' AND '$day3' THEN 1 ELSE 0 END) AS day_4,
		SUM(CASE WHEN created_on BETWEEN '$day5' AND '$day4' THEN 1 ELSE 0 END) AS day_5, 
		SUM(CASE WHEN created_on BETWEEN '$day6' AND '$day5' THEN 1 ELSE 0 END) AS day_6, 
		SUM(CASE WHEN created_on BETWEEN '$day7' AND '$day6' THEN 1 ELSE 0 END) AS day_7 
		FROM tcg_pendaftaran
		where is_deleted=0 and cabut_berkas=0";

		return $this->db->query($query);
	}    

	function tcg_rapor_mutu($tahun_ajaran_id=null){
		if (empty($tahun_ajaran_id)) {
			$tahun_ajaran_id = $this->tahun_ajaran_id;
		}

		$builder = $this->db->table('tcg_nilai_mutu_sekolah a');
		$builder->select('a.*, b.nama');
		$builder->join('ref_sekolah b', 'a.sekolah_id=b.sekolah_id', 'left outer');
        $builder->where('tahun_ajaran_id', $tahun_ajaran_id);
		return $builder->get();
	}    

	function tcg_cek_registrasi($nama, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $nama_ibu_kandung){
		$builder = $this->db->table('dbo_peserta_didik a');
		$builder->select('COUNT(1) AS jumlah');
		$builder->join('dbo_pengguna b','a.peserta_didik_id = b.pengguna_id AND b.is_deleted = 0');
		$builder->where(array('a.nama'=>$nama,'a.jenis_kelamin'=>$jenis_kelamin,'a.tempat_lahir'=>$tempat_lahir,'a.tanggal_lahir'=>$tanggal_lahir,'a.nama_ibu_kandung'=>$nama_ibu_kandung,'a.is_deleted'=>0));

		$sudah_registrasi=0;
		foreach($builder->get()->getResult() as $row):
			$sudah_registrasi=$row->jumlah;
		endforeach;

		return $sudah_registrasi;
	}

	function tcg_cek_nisn($nisn){
		$builder = $this->db->table('dbo_peserta_didik a');
		$builder->select('COUNT(1) AS jumlah');
		$builder->join('dbo_pengguna b','a.peserta_didik_id = b.pengguna_id AND b.is_deleted = 0');
		$builder->where(array('a.nisn'=>$nisn,'a.is_deleted'=>0));

		$sudah_registrasi=0;
		foreach($builder->get()->getResult() as $row):
			$sudah_registrasi=$row->jumlah;
		endforeach;

		return $sudah_registrasi;
	}

	function tcg_cek_nik($nik){
		$builder = $this->db->table('dbo_peserta_didik a');
		$builder->select('COUNT(1) AS jumlah');
		$builder->join('dbo_pengguna b','a.peserta_didik_id = b.pengguna_id AND b.is_deleted = 0');
		$builder->where(array('a.nik'=>$nik,'a.is_deleted'=>0));

		$sudah_registrasi=0;
		foreach($builder->get()->getResult() as $row):
			$sudah_registrasi=$row->jumlah;
		endforeach;

		return $sudah_registrasi;
	}

	function tcg_registrasiuser($sekolah_id, $nik, $nisn, $nomor_ujian, $nama, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $nama_ibu_kandung, $kebutuhan_khusus, $alamat, $kode_wilayah, $lintang, $bujur, $nomor_kontak){

		$sekolah_id = secure($sekolah_id); 
		$nik = secure($nik); 
		$nisn = secure($nisn); 
		$nomor_ujian = secure($nomor_ujian); 
		$nama = secure($nama); 
		$jenis_kelamin = secure($jenis_kelamin); 
		$tempat_lahir = secure($tempat_lahir);
		$tanggal_lahir = secure($tanggal_lahir); 
		$nama_ibu_kandung = secure($nama_ibu_kandung); 
		$kebutuhan_khusus = secure($kebutuhan_khusus); 
		$alamat = secure($alamat);
		$kode_wilayah = secure($kode_wilayah);
		$lintang = secure($lintang); 
		$bujur = secure($bujur); 
		$nomor_kontak = secure($nomor_kontak);

		$sql = "CALL usp_registrasi_2020 ($sekolah_id,$nik,$nisn,$nomor_ujian,$nama,$jenis_kelamin,$tempat_lahir,$tanggal_lahir,$nama_ibu_kandung,$kebutuhan_khusus,$alamat,$kode_wilayah,$lintang,$bujur,$nomor_kontak)";

		$peserta_didik_id="";
		foreach($this->db->query($sql)->getResult() as $row):
			$peserta_didik_id=$row->peserta_didik_id;
		endforeach;

		return $peserta_didik_id;
	}

	function tcg_detailuser($username){
		// $username = $this->input->post("username",TRUE);
		// $password = $this->input->post("password",TRUE);

		//$peran_id = $this->input->post("peran_id",TRUE);
		$builder = $this->db->table('dbo_users a');
		$builder->select('a.pengguna_id,a.role_id as peran_id,a.sekolah_id,a.nama as nama_pengguna,a.user_name as username,a.approval,
                            b.bentuk,CONVERT(c.kode_wilayah,CHAR(8)) AS kode_wilayah,c.tanggal_lahir,c.asal_data,c.nisn,c.kebutuhan_khusus,c.tutup_akses');
		$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id','LEFT OUTER');
		$builder->join('dbo_peserta_didik c','a.pengguna_id = c.peserta_didik_id','LEFT OUTER');
		$builder->where(array('a.is_deleted'=>0));
		$builder->groupStart()->orWhere('a.username', "$username")->orWhere('c.nisn',"$username")->orWhere('c.nik',"$username")->groupEnd();

		return $builder->get();
	}

	function tcg_login($username, $password){
		$builder = $this->db->table('dbo_pengguna a');
		$builder->select('count(*) as jumlah');
		$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id','LEFT OUTER');
		$builder->join('dbo_peserta_didik c','a.pengguna_id = c.peserta_didik_id','LEFT OUTER');
		$builder->where(array('a.password'=>md5($password),'a.approval'=>1,'a.is_deleted'=>0));
		$builder->groupStart()->orWhere('a.username', "$username")->orWhere('c.nisn',"$username")->orWhere('c.nik',"$username")->groupEnd();

		$login=0;
		foreach($builder->get()->getResult() as $row):
			$login=$row->jumlah;
		endforeach;

		if ($login > 0) {
			$this->tcg_audit_trail("","",'login','Login','','');
		}

		return $login;
	}

	function tcg_cek_ikutppdb($sekolah_id, $tahun_ajaran_id) {
		// TODO: buka akses kalau nggak ikut ppdb tahap 2
		$builder = $this->db->table('dbo_kuota_sekolah a');
		$builder->select('a.ikut_ppdb');
		$builder->where(array('a.sekolah_id'=>$sekolah_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.is_deleted'=>0));

		$ikut_ppdb=0;
		foreach($builder->get()->getResult() as $row):
			$ikut_ppdb=$row->ikut_ppdb;
		endforeach;

		return $ikut_ppdb;
	}

	function tcg_ubahpassword($password)
	{
		$pengguna_id = $this->session->get("pengguna_id");

		$data = array(
			'password' => md5($password),
			'ganti_password' => 1,
			'updated_on' => date("Y/m/d")
		);

		$builder = $this->db->table('dbo_pengguna');
        $builder->where(array('pengguna_id' => $pengguna_id, 'is_deleted' => 0));
		$retval = $builder($data);

		if ($retval > 0) {
			//put in audit trail
			$this->tcg_audit_trail("dbo_pengguna",$pengguna_id,'update','Update password','','');
		}

		return $retval;
	}

	function tcg_resetpassword($pengguna_id, $password)
	{
		$data = array(
			'password' => md5($password),
			'ganti_password' => 0,
			'updated_on' => date("Y/m/d")
		);
        
		$builder = $this->db->table('dbo_pengguna');
		$builder->where(array('pengguna_id' => $pengguna_id, 'is_deleted' => 0));
		$retval = $builder->update($data);

		if ($retval > 0) {
			//put in audit trail
			$this->tcg_audit_trail("dbo_pengguna",$pengguna_id,'update','Update password','','');
		}

		return $retval;
	}

	function tcg_audit_trail($table, $reference, $action, $description, $old_values, $new_values) {
		$pengguna_id = $this->session->get("pengguna_id");

		$query = "CALL usp_audit_trail(?,?,?,?,?,?,?,?)";
		return $this->db->query($query, array($table,$reference,$action,$pengguna_id,$description,null,$new_values,$old_values));
	}

	function tcg_sekolah_baru($nama_sekolah,$kode_wilayah,$bentuk,$npsn,$status) {
		$uuid = $this->uuid();

        //get data wilayah
        $sql = "select * from ref_wilayah where kode_wilayah=? and is_deleted=0";
        $wilayah = $this->db->query($sql, array($kode_wilayah))->getRowArray();
        if ($wilayah == null)   return '';

		$valuepair = array (
			"sekolah_id" => $uuid,
			"nama" => $nama_sekolah,
			"kode_wilayah" => $kode_wilayah,
            "kecamatan" => $wilayah['nama_kec'],
            "kode_wilayah_kec" => $wilayah['kode_wilayah_kec'],
            "kabupaten" => $wilayah['nama_kab'],
            "kode_wilayah_kab" => $wilayah['kode_wilayah_kab'],
			"bentuk" => $bentuk,
			"npsn" => $npsn,
			"alamat_jalan" => "",
			"status" => $status,
			"updater_id" => "REGISTRASI"
		);

		$builder = $this->db->table('ref_sekolah');
        if (!$builder->insert($valuepair)) return "";


        return $uuid;
	}

	function tcg_job_peringkatpendaftaran() {
		$query = "select next_execution, last_execution_end from dbo_jobs where name='proses_peringkat_pendaftaran'";
		return $this->db->query($query);
	}

	function tcg_rekapitulasi_sekolah() {
		$sql = "select * from rpt_rekapitulasi_sekolah_publik where tahun_ajaran_id=? and putaran=? order by nama asc";

		return $this->db->query($sql, array($this->tahun_ajaran_id, $this->putaran));	
	}    

	function uuid(){
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); 
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); 
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}    
}