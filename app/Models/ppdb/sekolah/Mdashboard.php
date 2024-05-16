<?php 
Class Mdashboard 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    
    // function __construct()
    // {
	// 	$this->kode_wilayah_aktif = "030500";
	// }
	
	function profilsekolah(){
		$sekolah_id = $this->session->get("sekolah_id");

		$builder = $this->db->table('ref_sekolah a');
		$builder->select('a.sekolah_id,a.npsn,a.nama,a.bentuk,a.status,a.alamat_jalan,a.desa_kelurahan,c3.nama AS kecamatan,a.lintang,a.bujur');
		$builder->join('ref_wilayah c','a.kode_wilayah = c.kode_wilayah AND c.expired_date IS NULL','LEFT OUTER');
		$builder->join('ref_wilayah c3','c.kode_wilayah_kec = c3.kode_wilayah AND c3.expired_date IS NULL','LEFT OUTER');
		$builder->where('a.sekolah_id',$sekolah_id);
		return $builder->get();
	}

	function daftarkuota(){
		$sekolah_id = $this->session->get("sekolah_id");
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");

		$builder = $this->db->table('tcg_penerapan_sekolah a');
		$builder->select('c.jalur_id,c.nama AS jalur,a.kuota');
		$builder->join('ref_penerapan b','a.penerapan_id = b.penerapan_id AND b.aktif = 1 AND b.expired_date IS NULL');
		$builder->join('ref_jalur c','b.jalur_id = c.jalur_id AND c.expired_date IS NULL');
		$builder->where(array('a.sekolah_id'=>$sekolah_id,'a.is_deleted'=>0, 'a.tahun_ajaran_id'=>$tahun_ajaran_id));
		$builder->orderBy('a.kuota DESC','c.nama ASC');
		return $builder->get();
	}
	
	//TODO: fix it
	function daftarjalur(){
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");		

		$builder = $this->db->table('ref_penerapan a');
		$builder->select('a.penerapan_id,c.nama AS jalur');
		$builder->join('ref_jalur c','a.jalur_id = c.jalur_id AND c.expired_date IS NULL');
		$builder->where(array('a.aktif'=>1,'a.expired_date'=>NULL,'a.tahun_ajaran_id'=>$tahun_ajaran_id));
		return $builder->get();
	}
	
	function daftarberkasverifikasi($pendaftaran_id){
		$sekolah_id = $this->session->get("sekolah_id");
		//$pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 

		$builder = $this->db->table('tcg_kelengkapan_pendaftaran a');
		$builder->select('a.kelengkapan_pendaftaran_id,d.nama AS kelengkapan,a.verifikasi');
		$builder->join('tcg_pendaftaran b','a.pendaftaran_id = b.pendaftaran_id AND b.cabut_berkas = 0 AND b.jenis_pilihan != 0 AND b.is_deleted = 0');
		$builder->join('ref_kelengkapan_penerapan c','a.kelengkapan_penerapan_id = c.kelengkapan_penerapan_id AND c.perlu_verifikasi = 1 AND c.expired_date IS NULL');
		$builder->join('ref_daftar_kelengkapan d','c.daftar_kelengkapan_id = d.daftar_kelengkapan_id AND d.expired_date IS NULL');
		$builder->where(array('a.pendaftaran_id'=>$pendaftaran_id,'b.sekolah_id'=>$sekolah_id,'a.is_deleted'=>0));
		return $builder->get();
	}

	function waktuberkasverifikasi($pendaftaran_id){
		$sekolah_id = $this->session->get("sekolah_id");
		// $pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 

		$builder = $this->db->table('tcg_kelengkapan_pendaftaran');
		$builder->select('CONVERT(MIN(updated_on),DATETIME) AS waktu_verifikasi');
		$builder->where(array('pendaftaran_id'=>$pendaftaran_id,'is_deleted'=>0));
		return $builder->get();
	}

	// function prosesverifikasiberkasi($kelengkapan_pendaftaran_id,$verifikasi)
	// {
	// 	$verifikator_id = $this->session->get("pengguna_id");
	// 	$data = array(
	// 		'verifikasi' => $verifikasi,
	// 		'verifikator_id' => $verifikator_id,
	// 		'updated_on' => date("Y/m/d H:i:s")
	// 	);
	// 	$builder->where(array('kelengkapan_pendaftaran_id'=>$kelengkapan_pendaftaran_id,'is_deleted'=>0));
	// 	return $builder->update('tcg_kelengkapan_pendaftaran', $data);
	// }

	function checkkelengkapanberkas($pendaftaran_id) {
		$pengguna_id = $this->session->get("pengguna_id");

		return $this->db->query("CALL " .SQL_CEK_KELENGKAPANBERKAS. "('$pendaftaran_id', '$pengguna_id');");
	}

	function updatenomorkontak($peserta_didik_id, $nomor_kontak)
	{
		// $peserta_didik_id = $_POST["peserta_didik_id"] ?? null; 
		// $nomor_kontak = $_POST["nomor_kontak"] ?? null; 

		$data = array(
			'nomor_kontak' => $nomor_kontak
		);

		$builder = $this->db->table('tcg_peserta_didik');
        $builder->where(array('peserta_didik_id'=>$peserta_didik_id,'is_deleted'=>0));
		return $builder->update($data);
	}

	function daftarpendaftar(){
		$sekolah_id = $this->session->get("sekolah_id");
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.jalur_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,a.jenis_pilihan,e.nama AS sekolah_asal,b.lintang,b.bujur,a.kelengkapan_berkas,skor,a.created_on');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.expired_date IS NULL');
		$builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
		$builder->where(array('a.cabut_berkas'=>0,'a.jenis_pilihan !='=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id));
		$builder->orderBy('a.created_on');
		return $builder->get();
	}

	function cekperubahandatasiswa($peserta_didik_id){
		// $peserta_didik_id = $_POST["peserta_didik_id"] ?? null; 

		$builder = $this->db->table('dbo_perubahan_data_siswa');
		$builder->select('COUNT(1) AS jumlah');
		$builder->where(array('peserta_didik_id'=>$peserta_didik_id,'approval'=>0,'is_deleted'=>0));
		return $builder->get();
	}

	function perubahandatasiswa($peserta_didik_id, $tanggal_lahir, $lintang, $bujur, $kode_wilayah, $asal_data)
	{
		// $peserta_didik_id = $_POST["peserta_didik_id"] ?? null; 
		// $tanggal_lahir = $_POST["tanggal_lahir"] ?? null; 
		// $lintang = $_POST["bujurlintang"] ?? null; 
		// $bujur = $_POST["data"] ?? null; 
		// $kode_wilayah = $_POST["kode_wilayah"] ?? null; 
		// if($kode_wilayah==""){
		// 	$kode_wilayah = $_POST["dkode_desata"] ?? null; 
		// }
		// $asal_data = $_POST["asal_data"] ?? null; 

        $pengguna_id = $this->session->get("pengguna_id");
		$approval = 1;
		$keterangan_approval = "Update lewat sekolah";
        
		return $this->db->query("CALL " .SQL_UBAH_DATA. " ('$peserta_didik_id',$approval,'$keterangan_approval','$kode_wilayah','$tanggal_lahir','$lintang','$bujur','$pengguna_id')");
	}

	function daftarperubahandatasiswa(){
		$sekolah_id = $this->session->get("sekolah_id");

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('f.perubahan_data_siswa_id,a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,b.tanggal_lahir AS tanggal_lahir_lama,f.tanggal_lahir AS tanggal_lahir_baru,g.nama AS desa_lama,h.nama AS desa_baru,b.lintang AS lintang_lama,f.lintang AS lintang_baru,b.bujur AS bujur_lama,f.bujur AS bujur_baru,f.approval,f.created_on');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.expired_date IS NULL');
		$builder->join('ref_sekolah e','a.sekolah_id = e.sekolah_id');
		$builder->join('dbo_perubahan_data_siswa f','a.peserta_didik_id = f.peserta_didik_id AND f.is_deleted = 0');
		$builder->join('ref_wilayah g','b.kode_wilayah = g.kode_wilayah AND g.expired_date IS NULL AND g.id_level_wilayah = 4','LEFT OUTER');
		$builder->join('ref_wilayah h','f.kode_wilayah = h.kode_wilayah AND h.expired_date IS NULL AND h.id_level_wilayah = 4','LEFT OUTER');
		$builder->where(array('a.cabut_berkas'=>0,'a.jenis_pilihan !='=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id));
		$builder->orderBy('a.created_on');
		return $builder->get();
	}
	
	function detailperubahandatasiswa($pendaftaran_id){
		//TODO: remove g4 and h4, use kode_wilayah_kec
		$sekolah_id = $this->session->get("sekolah_id");
		// $pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select("f.perubahan_data_siswa_id,a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,b.tanggal_lahir AS tanggal_lahir_lama,f.tanggal_lahir AS tanggal_lahir_baru,'' AS padukuhan_lama,'' AS padukuhan_baru,g.nama_desa AS desa_lama,h.nama_desa AS desa_baru,b.lintang AS lintang_lama,f.lintang AS lintang_baru,b.bujur AS bujur_lama,f.bujur AS bujur_baru,f.approval,f.created_on");
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.expired_date IS NULL');
		$builder->join('ref_sekolah e','a.sekolah_id = e.sekolah_id');
		$builder->join('dbo_perubahan_data_siswa f','a.peserta_didik_id = f.peserta_didik_id AND f.is_deleted = 0');
		$builder->join('ref_wilayah g','b.kode_wilayah = g.kode_wilayah AND g.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah g4','g.kode_wilayah_desa = g4.kode_wilayah AND g4.expired_date IS NULL','LEFT OUTER');
		$builder->join('ref_wilayah h','f.kode_wilayah = h.kode_wilayah AND h.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah h4','f.kode_wilayah_desa = h4.kode_wilayah AND h4.expired_date IS NULL','LEFT OUTER');
		//$builder->join('ref_wilayah i','LEFT(b.kode_wilayah,10) = LEFT(i.kode_wilayah,10) AND i.expired_date IS NULL AND i.id_level_wilayah = 5','LEFT OUTER');
		//$builder->join('ref_wilayah j','LEFT(f.kode_wilayah,10) = LEFT(j.kode_wilayah,10) AND j.expired_date IS NULL AND j.id_level_wilayah = 5','LEFT OUTER');
		$builder->where(array('a.pendaftaran_id'=>$pendaftaran_id,'a.jenis_pilihan !='=>0,'a.cabut_berkas'=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id));
		return $builder->get();
	}
	function hapusperubahandatasiswa($perubahan_data_siswa_id, $peserta_didik_id)
	{
		$pengguna_id = $this->session->get("pengguna_id");
		// $perubahan_data_siswa_id = $_POST["perubahan_data_siswa_id"] ?? null; 
		// $peserta_didik_id = $_POST["peserta_didik_id"] ?? null; 

		$data = array(
			'pengguna_id' => $pengguna_id,
			'is_deleted' => 1,
			'updated_on' => date("Y/m/d")
		);

		$builder = $this->db->table('dbo_perubahan_data_siswa');
		$builder->where(array('perubahan_data_siswa_id'=>$perubahan_data_siswa_id,'peserta_didik_id'=>$peserta_didik_id,'is_deleted'=>0));
		return $builder->update($data);
	}

	function detailpendaftarpilihansatu($pendaftaran_id){
		$sekolah_id = $this->session->get("sekolah_id");
		// $pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,a.jenis_pilihan,e.nama AS sekolah_asal,a.created_on,a.updated_on');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.expired_date IS NULL');
		$builder->join('ref_sekolah e','a.sekolah_id = e.sekolah_id');
		$builder->where(array('a.pendaftaran_id'=>$pendaftaran_id,'a.jenis_pilihan'=>1,'a.cabut_berkas'=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id));
		$builder->orderBy('a.created_on');
		return $builder->get();
	}

	// function detailpendaftarpilihandua(){
	// 	$sekolah_id = $this->session->get("sekolah_id");
	// 	$pendaftaran_id = $_GET["data"] ?? null; (("pendaftaran_id");
	// 	$builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,a.jenis_pilihan,e.nama AS sekolah_asal,a.created_on,a.updated_on');
	// 	$builder = $this->db->table('tcg_pendaftaran a');
	// 	$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
	// 	$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
	// 	$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.expired_date IS NULL');
	// 	$builder->join('ref_sekolah e','a.sekolah_id = e.sekolah_id');
	// 	$builder->where(array('a.pendaftaran_id'=>$pendaftaran_id,'a.jenis_pilihan !='=>1,'a.cabut_berkas'=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id));
	// 	$builder->orderBy('a.created_on');
	// 	return $builder->get();
	// }

	function daftarpendaftarcabutberkas(){
		$sekolah_id = $this->session->get("sekolah_id");

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,a.jenis_pilihan,e.nama AS sekolah_asal,a.tanggal_cabut_berkas,a.created_on,a.updated_on');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.expired_date IS NULL');
		$builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
		$builder->where(array('a.jenis_pilihan'=>1,'a.cabut_berkas'=>1,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id));
		$builder->orderBy('a.created_on');
		return $builder->get();
	}

	function detailpendaftar($pendaftaran_id){
		$sekolah_id = $this->session->get("sekolah_id");
		// $pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,a.jenis_pilihan,e.nama AS sekolah_asal,a.created_on,a.updated_on');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.expired_date IS NULL');
		$builder->join('ref_sekolah e','a.sekolah_id = e.sekolah_id');
		$builder->where(array('a.pendaftaran_id'=>$pendaftaran_id,'a.cabut_berkas'=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id));
		$builder->orderBy('a.created_on');
		return $builder->get();
	}

	function cabutberkas($peserta_didik_id, $pendaftaran_id, $keterangan)
	{
		// $peserta_didik_id = $_POST["peserta_didik_id"] ?? null; 
		// $pendaftaran_id = $_POST["pendaftaran_id"] ?? null; 
		// $keterangan = $_POST["keterangan"] ?? null; 
		$pengguna_id = $this->session->get("pengguna_id");

		return $this->db->query("CALL " .SQL_CABUT_BERKAS. " ('$peserta_didik_id',$pendaftaran_id,'$keterangan','$pengguna_id')");
	}

	function hapuspendaftaran($peserta_didik_id, $pendaftaran_id, $keterangan)
	{
		// $peserta_didik_id = $_POST["peserta_didik_id"] ?? null; 
		// $pendaftaran_id = $_POST["pendaftaran_id"] ?? null; 
		// $keterangan = $_POST["keterangan"] ?? null; 
		$pengguna_id = $this->session->get("pengguna_id");

		return $this->db->query("CALL " .SQL_HAPUS_PENDAFTARAN. " ('$peserta_didik_id',$pendaftaran_id,'$keterangan','$pengguna_id')");
	}

	function penerapansummary($penerapan_id) {
		$sekolah_id = $this->session->get("sekolah_id");

		$query = "
		select 
			sekolah_id, penerapan_id, kuota, memenuhi_syarat, belum_diperingkat, masuk_kuota, tidak_masuk_kuota, daftar_tunggu, diterima_pilihan1, total_pendaftar, tambahan_kuota 
		from 
			v_rpt_sekolah_summary
		where 
			sekolah_id='$sekolah_id' and penerapan_id=$penerapan_id";

		return $this->db->query($query);
	}
	
	function dashboardsummary() {
		$sekolah_id = $this->session->get("sekolah_id");
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");
		$query = "
		select 
			a.sekolah_id, 
			b.nama,
			sum(a.kuota) as kuota, 
			sum(a.masuk_kuota) as masuk_kuota, 
			sum(a.tidak_masuk_kuota) as tidak_masuk_kuota, 
			sum(a.daftar_tunggu) as daftar_tunggu, 
			sum(a.total_pendaftar) as total_pendaftar, 
			sum(a.berkas_lengkap) as berkas_lengkap 
		from 
			v_rpt_sekolah_summary a
		join ref_sekolah b on b.sekolah_id=a.sekolah_id
		where 
			a.sekolah_id='$sekolah_id' and a.tahun_ajaran_id='$tahun_ajaran_id'
		group by a.sekolah_id";

		return $this->db->query($query);
	}

	function chartpendaftar() {
		$sekolah_id = $this->session->get("sekolah_id");
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");
		$query = "
		select 
			a.sekolah_id, 
			a.penerapan_id,
			d.nama as jalur,
			a.total_pendaftar
		from 
			v_rpt_sekolah_summary a
		join ref_penerapan c on c.penerapan_id=a.penerapan_id
		join ref_jalur d on d.jalur_id=c.jalur_id
		where 
			a.sekolah_id='$sekolah_id' and a.tahun_ajaran_id='$tahun_ajaran_id'";

		return $this->db->query($query);

	}

	function dashboardsekolah(){
		$sekolah_id = $this->session->get("sekolah_id");
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");	

		$builder = $this->db->table('rpt_rekapitulasi_sekolah');
		$builder->select('rekapitulasi_sekolah_id,
			sekolah_id,
			kuota_total,
			total_pendaftar,
			belum_diperingkat,
			masuk_kuota,
			daftar_tunggu,
			tidak_masuk_kuota,
			diterima_pilihan1,
			belum_diverifikasi,
			verifikasi_berkas_lengkap,
			verifikasi_berkas_tidak_lengkap,
			total_pendaftar_madrasah,
			total_pendaftar_sd,
			kuota_zonasi,
			pendaftar_zonasi,
			zonasi_diterima,
			zonasi_tidak_diterima,
			zonasi_belum_diperingkat,
			kuota_prestasi,
			pendaftar_prestasi,
			prestasi_diterima,
			prestasi_tidak_diterima,
			prestasi_belum_diperingkat,
			kuota_perpindahan_orang_tua,
			pendaftar_perpindahan_orang_tua,
			perpindahan_orang_tua_diterima,
			perpindahan_orang_tua_tidak_diterima,
			perpindahan_orang_tua_belum_diperingkat,
			kuota_khusus,
			pendaftar_khusus,
			khusus_diterima,
			khusus_tidak_diterima,
			khusus_belum_diperingkat,
			kuota_swasta,
			pendaftar_swasta,
			swasta_diterima,
			swasta_tidak_diterima,
			swasta_belum_diperingkat,
			kuota_olahraga_dan_bakat,
			pendaftar_olahraga_dan_bakat,
			olahraga_dan_bakat_diterima,
			olahraga_dan_bakat_tidak_diterima,
			olahraga_dan_bakat_belum_diperingkat,
			kuota_inklusi,
			pendaftar_inklusi,
			inklusi_diterima,
			inklusi_tidak_diterima,
			inklusi_belum_diperingkat,
			kuota_perbatasan,
			pendaftar_perbatasan,
			perbatasan_diterima,
			perbatasan_tidak_diterima,
			perbatasan_belum_diperingkat,
			kuota_afirmasi,
			pendaftar_afirmasi,
			afirmasi_diterima,
			afirmasi_tidak_diterima,
			afirmasi_belum_diperingkat,
			kuota_madrasah,
			pendaftar_madrasah,
			madrasah_diterima,
			madrasah_tidak_diterima,
			madrasah_belum_diperingkat,
			kode_wilayah_penerapan,
			tahun_ajaran_id
			');
		$builder->where(array('sekolah_id'=>$sekolah_id, 'tahun_ajaran_id'=>$tahun_ajaran_id));
		return $builder->get();
	}
	
	function dashboardline(){
		$sekolah_id = $this->session->get("sekolah_id");
		
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
		where is_deleted=0 and cabut_berkas=0 and sekolah_id = '$sekolah_id'";

		return $this->db->query($query);
	}
	
	function dashboardpendaftaran($penerapan_id){
		$sekolah_id = $this->session->get("sekolah_id");
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");

		$builder = $this->db->table('v_rpt_pendaftaran');
		$builder->select('pendaftaran_id,sekolah_id,peserta_didik_id,penerapan_id,jalur_id,jalur,nomor_pendaftaran,jenis_pilihan,status_verifikasi_berkas,status_penerimaan,status_daftar_ulang,cabut_berkas,masuk_jenis_pilihan,peringkat,skor,npsn,nama_sekolah,bentuk_sekolah,status_sekolah,kode_desa_sekolah,desa_kelurahan_sekolah,kode_kecamatan_sekolah,kecamatan_sekolah,lintang_sekolah,bujur_sekolah,jalur_penerapan,bentuk_sekolah_asal,nama,alamat,nama_dusun,kode_desa,desa_kelurahan,kode_kecamatan,kecamatan,nik,nisn,nomor_ujian,jenis_kelamin,tempat_lahir,tanggal_lahir,nama_ibu_kandung,nama_ayah,nama_wali,kebutuhan_khusus,no_KIP,lintang,bujur,asal_data,frekuensi_cabut_berkas,frekuensi_ubah_pilihan,frekuensi_ubah_sekolah,created_on');
		$builder->where(array('pendaftaran'=>1,'sekolah_id'=>$sekolah_id,'penerapan_id'=>$penerapan_id,'tahun_ajaran_id'=>$tahun_ajaran_id));
		$builder->orderBy('peringkat');
		return $builder->get();
	}

	//TODO: use v_rpt_sekolah_summary for consistency
	function dashboardpenerapan(){
		//$bentuk = $this->session->get("bentuk");
		$sekolah_id = $this->session->get("sekolah_id");
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");
		$query = "
		select a.penerapan_id,a.nama AS jalur,d.kuota,
			(
				select count(1) as cnt
				from tcg_pendaftaran z
				where z.penerapan_id = a.penerapan_id and z.sekolah_id='$sekolah_id' and z.cabut_berkas=0 and z.is_deleted=0
				and (z.masuk_jenis_pilihan=0 or z.masuk_jenis_pilihan=z.jenis_pilihan)
			) as jml_pendaftar
		from ref_penerapan a
		join ref_jalur c on a.jalur_id = c.jalur_id AND c.expired_date IS NULL
		join tcg_penerapan_sekolah d on a.penerapan_id = d.penerapan_id AND d.is_deleted = 0
		where a.aktif=1 and a.expired_date is null and 
		d.sekolah_id = '$sekolah_id' and a.tahun_ajaran_id='$tahun_ajaran_id'
		order by a.penerapan_id		
		";
		return $this->db->query($query);
		
	}
	
	//TODO: optimize by removing join g3/g4 and join i3/i4
	function dashboardpendaftar(){
		$sekolah_id = $this->session->get("sekolah_id");
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,f.jalur_id,f.nama AS jalur,a.nomor_pendaftaran,a.jenis_pilihan,
	a.kelengkapan_berkas AS status_verifikasi_berkas,a.status_penerimaan,a.status_daftar_ulang,a.cabut_berkas,a.masuk_jenis_pilihan,a.peringkat,a.skor,d.npsn,d.nama AS nama_sekolah,
	d.bentuk AS bentuk_sekolah,d.status AS status_sekolah,i.kode_wilayah_desa AS kode_desa_sekolah,i.nama_desa AS desa_kelurahan_sekolah,i.kode_wilayah_kec AS kode_kecamatan_sekolah,
	i.nama_kec AS kecamatan_sekolah,d.lintang AS lintang_sekolah,d.bujur AS bujur_sekolah,f.nama AS jalur_penerapan,
	k.bentuk AS bentuk_sekolah_asal,c.nama,c.alamat,c.nama_dusun,g.kode_wilayah_desa AS kode_desa,g.nama_desa AS desa_kelurahan,
	g.kode_wilayah_kec AS kode_kecamatan,g.nama_kec AS kecamatan,c.nik,c.nisn,c.nomor_ujian,c.jenis_kelamin,c.tempat_lahir,c.tanggal_lahir,c.nama_ibu_kandung,c.nama_ayah,
	c.nama_wali,c.kebutuhan_khusus,c.no_KIP,c.lintang,c.bujur,c.asal_data,c.cabut_berkas AS frekuensi_cabut_berkas,c.ubah_pilihan AS frekuensi_ubah_pilihan,
	c.ubah_sekolah AS frekuensi_ubah_sekolah,a.created_on');
		$builder->join('ref_penerapan b','a.penerapan_id = b.penerapan_id AND b.aktif = 1 AND b.expired_date IS NULL');
		$builder->join('tcg_peserta_didik c','a.peserta_didik_id = c.peserta_didik_id AND c.is_deleted = 0');
		$builder->join('ref_sekolah d','a.sekolah_id = d.sekolah_id');
		$builder->join('ref_jalur f','b.jalur_id = f.jalur_id AND f.expired_date IS NULL');
		$builder->join('ref_wilayah g','c.kode_wilayah = g.kode_wilayah AND g.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah g4','g.kode_wilayah_desa = g4.kode_wilayah AND g4.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah g3','g.kode_wilayah_kec = g3.kode_wilayah AND g3.expired_date IS NULL','LEFT OUTER');
		$builder->join('ref_wilayah i','d.kode_wilayah = i.kode_wilayah AND i.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah i4','i.kode_wilayah_desa = i4.kode_wilayah AND i4.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah i3','i.kode_wilayah_kec = i3.kode_wilayah AND i3.expired_date IS NULL','LEFT OUTER');
		$builder->join('ref_sekolah k','c.sekolah_id = k.sekolah_id','LEFT OUTER');
		$builder->where(array('a.sekolah_id'=>$sekolah_id,'a.cabut_berkas'=>0,'a.jenis_pilihan!='=>0,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.is_deleted'=>0));
		return $builder->get();
	}

	function daftarsiswagantiprestasi(){
		$sekolah_id = $this->session->get("sekolah_id");

		$builder = $this->db->table('tcg_peserta_didik a');
		$builder->select('b.pendaftaran_id,b.nomor_pendaftaran,b.penerapan_id,a.peserta_didik_id,a.nik,a.nisn,a.nomor_ujian,a.nama,a.jenis_kelamin,a.tempat_lahir,a.tanggal_lahir,a.nama_ibu_kandung,a.alamat,c.keterangan,b.jenis_pilihan,d.nama AS sekolah,e.nama AS sekolah_asal,a.created_on');
		$builder->join('tcg_pendaftaran b','a.peserta_didik_id = b.peserta_didik_id AND b.cabut_berkas = 0 AND b.is_deleted = 0 AND b.jenis_pilihan != 0');
		$builder->join('ref_penerapan c','b.penerapan_id = c.penerapan_id AND c.kategori_prestasi = 1 AND c.expired_date IS NULL');
		$builder->join('ref_sekolah d','b.sekolah_id = d.sekolah_id');
		$builder->join('ref_sekolah e','a.sekolah_id = e.sekolah_id');
		$builder->where(array('b.sekolah_id'=>$sekolah_id,'a.is_deleted'=>0));
		return $builder->get();
	}

	function detailgantiprestasi($pendaftaran_id){
		$sekolah_id = $this->session->get("sekolah_id");
		// $pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 

		$builder = $this->db->table('tcg_peserta_didik a');
		$builder->select('h.pendaftaran_id,a.peserta_didik_id,g.user_name as username,a.nisn,a.nama,b.sekolah_id,b.npsn,b.nama AS sekolah,a.nik,a.jenis_kelamin,a.kebutuhan_khusus,a.tempat_lahir,a.nama_ibu_kandung,a.alamat,a.tanggal_lahir,c.nama_desa AS desa_kelurahan,c.kode_wilayah_kec AS kode_kecamatan,c.nama_kec AS kecamatan,c.kode_wilayah_kab AS kode_kabupaten,c.nama_kab AS kabupaten,c.kode_wilayah_prov AS kode_provinsi,c.nama_prov AS provinsi,a.lintang,a.bujur,a.kode_wilayah,a.created_on,a.asal_data,h.prestasi_skoring_id,h.skor_prestasi');
		$builder->join('tcg_pendaftaran h','a.peserta_didik_id = h.peserta_didik_id AND h.is_deleted = 0 AND h.cabut_berkas = 0 AND h.jenis_pilihan != 0');
		$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id','LEFT OUTER');
		$builder->join('ref_wilayah c','a.kode_wilayah = c.kode_wilayah AND c.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah c4','c.kode_wilayah_desa = c4.kode_wilayah AND c4.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah c3','c.kode_wilayah_kec = c3.kode_wilayah AND c3.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah c2','c.kode_wilayah_kab = c2.kode_wilayah AND c2.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah c1','c.kode_wilayah_prov = c1.kode_wilayah AND c1.expired_date IS NULL','LEFT OUTER');
		$builder->join('dbo_users g','a.peserta_didik_id = g.pengguna_id AND g.role_id = 1 AND g.is_deleted = 0','LEFT OUTER');
		$builder->where(array('h.pendaftaran_id'=>$pendaftaran_id,'h.sekolah_id'=>$sekolah_id,'a.is_deleted'=>0));
		return $builder->get();
	}

	function detailnilaiprestasi($pendaftaran_id, $penerapan_id){
		// $pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 
		// $penerapan_id = $_GET["penerapan_id"] ?? null; 

		$builder = $this->db->table('tcg_skoring_pendaftaran a');
		$builder->select('a.skoring_pendaftaran_id,a.daftar_nilai_skoring_id,a.nilai');
		$builder->join('ref_daftar_nilai_skoring b','a.daftar_nilai_skoring_id = b.daftar_nilai_skoring_id AND b.expired_date IS NULL');
		$builder->join('ref_penerapan c','b.penerapan_id = c.penerapan_id AND c.aktif = 1 AND c.expired_date IS NULL AND c.kategori_prestasi = 1');
		$builder->join('ref_daftar_skoring d','b.daftar_skoring_id = d.daftar_skoring_id AND d.manual = 1 AND d.expired_date IS NULL');
		$builder->where(array('a.pendaftaran_id'=>$pendaftaran_id,'b.penerapan_id'=>$penerapan_id,'a.is_deleted'=>0));
		return $builder->get();
	}

	function ubahprestasi($pendaftaran_id, $penerapan_id, $daftar_nilai_skoring_id)
	{
		// $pendaftaran_id = $_POST["pendaftaran_id"] ?? null; 
		// $penerapan_id = $_POST["penerapan_id"] ?? null; 
		// $daftar_nilai_skoring_id = $_POST["daftar_nilai_skoring_id"] ?? null; 

		return $this->db->query("CALL " .SQL_UBAH_PRESTASI. " ($pendaftaran_id,$penerapan_id,$daftar_nilai_skoring_id)");
	}

	// //TODO: use Msiswa->daftarpendaftaran
	// function daftarpendaftaran(){
	// 	$peserta_didik_id = $_GET["data"] ?? null; (("peserta_didik_id");
	// 	$builder->select('a.pendaftaran_id,a.penerapan_id,a.sekolah_id,e.nama AS jalur,a.nomor_pendaftaran,b.npsn,b.nama AS sekolah,a.jenis_pilihan,a.status_penerimaan,a.cabut_berkas,a.masuk_jenis_pilihan,a.peringkat,SUM(f.nilai) AS skor,a.created_on,b.bentuk');
	// 	$builder = $this->db->table('tcg_pendaftaran a');
	// 	$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id');
	// 	$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1 AND c.expired_date is NULL');
	// 	$builder->join('ref_jalur e','c.jalur_id = e.jalur_id AND e.expired_date IS NULL');
	// 	$builder->join('tcg_skoring_pendaftaran f','a.pendaftaran_id = f.pendaftaran_id AND f.is_deleted = 0');
	// 	$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.cabut_berkas'=>0,'a.is_deleted'=>0));
	// 	$builder->groupBy('a.pendaftaran_id,a.penerapan_id,a.sekolah_id,d.nama,e.nama,a.nomor_pendaftaran,b.npsn,b.nama,a.jenis_pilihan,a.status_penerimaan,a.cabut_berkas,a.masuk_jenis_pilihan,a.peringkat,a.created_on,b.bentuk');
	// 	$builder->orderBy('a.jenis_pilihan');
	// 	return $builder->get();
	// }

	function caripengajuanakun($nama, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $approval){
		// $nama = trim($_POST["nama"] ?? null); 
		// $jenis_kelamin = $_POST["jenis_kelamin"] ?? null; 
		// $tempat_lahir = $_POST["tempat_lahir"] ?? null; 
		// $tanggal_lahir = $_POST["tanggal_lahir"] ?? null; 
		// $approval = $_POST["approval"] ?? null; 

		$query = "
		SELECT 
			a.peserta_didik_id,a.nik,a.nisn,a.nomor_ujian,a.nama,a.jenis_kelamin,a.tempat_lahir,a.tanggal_lahir,a.nama_ibu_kandung,a.alamat,b.approval 
		FROM tcg_peserta_didik a
		JOIN dbo_users b ON a.peserta_didik_id = b.pengguna_id AND b.is_deleted = 0
		";

		$where = "a.is_deleted=0";
		if (!empty($nama)) {
			$where .= " AND a.nama like '%$nama%'";
		}
		if (isset($approval)) {
			if(strlen($approval) > 0)
				$where .= " AND b.approval = $approval";
			else
				$where .= " AND b.approval != 1";
		}
		if (!empty($jenis_kelamin)) {
			$where .= " AND a.jenis_kelamin='$jenis_kelamin'";
		}
		if (!empty($tempat_lahir)) {
			$where .= " AND a.tempat_lahir=$tempat_lahir";
		}
		if (!empty($tanggal_lahir)) {
			$where .= " AND a.tanggal_lahir=$tanggal_lahir";
		}

		$query .= " WHERE $where";
		
		return $this->db->query($query);

		// $builder->select('a.peserta_didik_id,a.nik,a.nisn,a.nomor_ujian,a.nama,a.jenis_kelamin,a.tempat_lahir,a.tanggal_lahir,a.nama_ibu_kandung,a.alamat,b.approval');
		// $builder = $this->db->table('tcg_peserta_didik a');
		// $builder->join('dbo_users b','a.peserta_didik_id = b.pengguna_id AND b.is_deleted = 0');
		// $builder->where(array('a.nama'=>$nama,'a.jenis_kelamin'=>$jenis_kelamin,'a.tempat_lahir'=>$tempat_lahir,'a.tanggal_lahir'=>$tanggal_lahir,'a.is_deleted'=>0));
		// return $builder->get();
	}

	function detailpengajuanakun($pengguna_id){
		//$pengguna_id = $_GET["pengguna_id"] ?? null;

		$builder = $this->db->table('dbo_users a');
		$builder->select('a.user_name as username,a.pengguna_id,b.sekolah_id,c.npsn,c.nama AS sekolah,b.nik,b.nisn,b.nomor_ujian,b.nama,b.jenis_kelamin,b.tempat_lahir,b.tanggal_lahir,b.nama_ibu_kandung,b.kebutuhan_khusus,b.alamat,d.nama_desa AS desa_kelurahan,d.nama_kec AS kecamatan,d.nama_kab AS kabupaten,d.nama_prov AS provinsi,b.lintang,b.bujur,a.approval');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_sekolah c','b.sekolah_id = c.sekolah_id','LEFT OUTER');
		$builder->join('ref_wilayah d','b.kode_wilayah = d.kode_wilayah AND d.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah d4','d.kode_wilayah_desa = d4.kode_wilayah AND d4.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah d3','d.kode_wilayah_kec = d3.kode_wilayah AND d3.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah d2','d.kode_wilayah_kab = d2.kode_wilayah AND d2.expired_date IS NULL','LEFT OUTER');
		// $builder->join('ref_wilayah d1','d.kode_wilayah_prov = d1.kode_wilayah AND d1.expired_date IS NULL','LEFT OUTER');
		$builder->where(array('a.pengguna_id'=>$pengguna_id,'a.role_id'=>1,'a.is_deleted'=>0));
		return $builder->get();
	}

	function pengajuanakun($pengguna_id, $approval)
	{
		// $pengguna_id = $_POST["pengguna_id"] ?? null; 
		// $approval = $_POST["approval"] ?? null; 

		$data = array(
			'approval' => $approval,
			'updated_on' => date("Y/m/d")
		);

		$builder = $this->db->table('dbo_users');
		$builder->where(array('pengguna_id'=>$pengguna_id,'role_id'=>1,'is_deleted'=>0));
		return $builder->update($data);
	}

	// function caritarikdata(){
	// 	$nama = $_POST["data"] ?? null; (("nama");
	// 	$jenis_kelamin = $_POST["data"] ?? null; (("jenis_kelamin");
	// 	$tempat_lahir = $_POST["data"] ?? null; (("tempat_lahir");
	// 	$tanggal_lahir = $_POST["data"] ?? null; (("tanggal_lahir");
	// 	$alldata = $this->load->database('alldata', TRUE);
	// 	$alldata->select('peserta_didik_id,nik,nisn,nomor_ujian,nama,jenis_kelamin,tempat_lahir,tanggal_lahir,nama_ibu_kandung,alamat');
	// 	$alldata->from('tcg_peserta_didik');
	// 	$alldata->where(array('nama'=>$nama,'jenis_kelamin'=>$jenis_kelamin,'tempat_lahir'=>$tempat_lahir,'tanggal_lahir'=>$tanggal_lahir,'is_deleted'=>0));
	// 	return $alldata->get();
	// }

	// function detailtarikdata(){
	// 	$peserta_didik_id = $_GET["data"] ?? null; (("peserta_didik_id");
	// 	$alldata = $this->load->database('alldata', TRUE);
	// 	$alldata->select('a.peserta_didik_id,a.nisn,a.nama,a.sekolah_id,b.npsn,b.nama AS sekolah,a.nik,a.jenis_kelamin,a.kebutuhan_khusus,a.tempat_lahir,a.nama_ibu_kandung,a.alamat,a.tanggal_lahir,c4.nama AS desa_kelurahan,c3.kode_wilayah AS kode_kecamatan,c3.nama AS kecamatan,c2.kode_wilayah AS kode_kabupaten,c2.nama AS kabupaten,c1.kode_wilayah AS kode_provinsi,c1.nama AS provinsi,a.lintang,a.bujur,a.kode_wilayah,a.created_on');
	// 	$alldata->from('tcg_peserta_didik a');
	// 	$alldata->join('ref_sekolah b','a.sekolah_id = b.sekolah_id','LEFT OUTER');
	// 	$alldata->join('ref_wilayah c','a.kode_wilayah = c.kode_wilayah AND c.expired_date IS NULL','LEFT OUTER');
	// 	$alldata->join('ref_wilayah c4','c.kode_wilayah_desa = c4.kode_wilayah AND c4.expired_date IS NULL','LEFT OUTER');
	// 	$alldata->join('ref_wilayah c3','c.kode_wilayah_kec = c3.kode_wilayah AND c3.expired_date IS NULL','LEFT OUTER');
	// 	$alldata->join('ref_wilayah c2','c.kode_wilayah_kab = c2.kode_wilayah AND c2.expired_date IS NULL','LEFT OUTER');
	// 	$alldata->join('ref_wilayah c1','c.kode_wilayah_prov = c1.kode_wilayah AND c1.expired_date IS NULL','LEFT OUTER');
	// 	$alldata->where(array('peserta_didik_id'=>$peserta_didik_id,'is_deleted'=>0));
	// 	return $alldata->get();
	// }

	// function tarikdata()
	// {
	// 	$peserta_didik_id = $_POST["data"] ?? null; (("peserta_didik_id");
	// 	$sekolah_id = $_POST["sekolah_id"] ?? null;
	// 	$kode_wilayah = $_POST["data"] ?? null; (("kode_wilayah");
	// 	$nama = $_POST["data"] ?? null; (("nama");
	// 	$alamat = $_POST["data"] ?? null; (("alamat");
	// 	$nik = $_POST["data"] ?? null; (("nik");
	// 	$nisn = $_POST["data"] ?? null; (("nisn");
	// 	$jenis_kelamin = $_POST["data"] ?? null; (("jenis_kelamin");
	// 	$tempat_lahir = $_POST["data"] ?? null; (("tempat_lahir");
	// 	$tanggal_lahir = $_POST["data"] ?? null; (("tanggal_lahir");
	// 	$nama_ibu_kandung = $_POST["data"] ?? null; (("nama_ibu_kandung");
	// 	$kebutuhan_khusus = $_POST["data"] ?? null; (("kebutuhan_khusus");
	// 	$lintang = $_POST["data"] ?? null; (("lintang");
	// 	$bujur = $_POST["data"] ?? null; (("bujur");
	// 	$data = array(
	// 		'peserta_didik_id' => $peserta_didik_id,
	// 		'sekolah_id' => $sekolah_id,
	// 		'kode_wilayah' => $kode_wilayah,
	// 		'nama' => $nama,
	// 		'alamat' => $alamat,
	// 		'nik' => $nik,
	// 		'nisn' => $nisn,
	// 		'jenis_kelamin' => $jenis_kelamin,
	// 		'tempat_lahir' => $tempat_lahir,
	// 		'tanggal_lahir' => $tanggal_lahir,
	// 		'nama_ibu_kandung' => $nama_ibu_kandung,
	// 		'kebutuhan_khusus' => $kebutuhan_khusus,
	// 		'lintang' => $lintang,
	// 		'bujur' => $bujur,
	// 		'asal_data' => 2
	// 	);
	// 	return $builder->insert('tcg_peserta_didik',$data);
	// }

	// function buatakuntarikdata()
	// {
	// 	$peserta_didik_id = $_POST["data"] ?? null; (("peserta_didik_id");
	// 	$sekolah_id = $_POST["sekolah_id"] ?? null;
	// 	$nama = $_POST["data"] ?? null; (("nama");
	// 	$alamat = $_POST["data"] ?? null; (("alamat");
	// 	$nik = $_POST["data"] ?? null; (("nik");
	// 	$nisn = $_POST["data"] ?? null; (("nisn");
	// 	$jenis_kelamin = $_POST["data"] ?? null; (("jenis_kelamin");
	// 	$tempat_lahir = $_POST["data"] ?? null; (("tempat_lahir");
	// 	$tanggal_lahir = $_POST["data"] ?? null; (("tanggal_lahir");
	// 	$nama_ibu_kandung = $_POST["data"] ?? null; (("nama_ibu_kandung");
	// 	$kebutuhan_khusus = $_POST["data"] ?? null; (("kebutuhan_khusus");
	// 	$lintang = $_POST["data"] ?? null; (("lintang");
	// 	$bujur = $_POST["data"] ?? null; (("bujur");
	// 	return $this->db->query("CALL usp_buatakun ('$sekolah_id','$peserta_didik_id',1)");
	// }

	// function carisiswa(){
	// 	$nisn = $_POST["data"] ?? null; (("nisn");
	// 	$nik = $_POST["data"] ?? null; (("nik");
	// 	$nama = $_POST["data"] ?? null; (("nama");
	// 	$jenis_kelamin = $_POST["data"] ?? null; (("jenis_kelamin");
	// 	$tempat_lahir = $_POST["data"] ?? null; (("tempat_lahir");
	// 	$tanggal_lahir = $_POST["data"] ?? null; (("tanggal_lahir");
	// 	$nama_ibu_kandung = $_POST["data"] ?? null; (("nama_ibu_kandung");
	// 	$sekolah_id = $this->session->get("sekolah_id");

	// 	$query = "
	// 	SELECT 
	// 		a.peserta_didik_id,a.nik,a.nisn,a.nomor_ujian,a.nama,a.jenis_kelamin,a.tempat_lahir,a.tanggal_lahir,a.nama_ibu_kandung,a.alamat,
	// 		b.pendaftaran_id, b.penerapan_id 
	// 	FROM tcg_peserta_didik a
	// 	JOIN tcg_pendaftaran b ON a.peserta_didik_id = b.peserta_didik_id AND b.cabut_berkas = 0 AND b.is_deleted = 0 
	// 	";

	// 	//boleh ubah walaupun sudah diterima?
	// 	//AND b.status_penerimaan = 0

    //     $sekolah_id = secure($sekolah_id);
	// 	$where = "a.is_deleted=0 AND b.sekolah_id='$sekolah_id'";
	// 	if (isset($nama) && strlen($nama)>0 ) {
	// 		$where .= " AND a.nama like '%$nama%'";
	// 	}
	// 	if (isset($jenis_kelamin) && strlen($jenis_kelamin)>0 ) {
	// 		$where .= " AND a.jenis_kelamin='$jenis_kelamin'";
	// 	}
	// 	if (isset($tempat_lahir) && strlen($tempat_lahir)>0 ) {
	// 		$where .= " AND a.tempat_lahir=$tempat_lahir";
	// 	}
	// 	if (isset($tanggal_lahir) && strlen($tanggal_lahir)>0 ) {
	// 		$where .= " AND a.tanggal_lahir=$tanggal_lahir";
	// 	}

	// 	$query .= " WHERE $where";
		
	// 	return $this->db->query($query);

	// 	// $array = array('nama'=>$nama,'tempat_lahir'=>$tempat_lahir,'nama_ibu_kandung'=>$nama_ibu_kandung,FALSE);
	// 	// $builder->select('peserta_didik_id,nik,nisn,nomor_ujian,nama,jenis_kelamin,tempat_lahir,tanggal_lahir,nama_ibu_kandung,alamat');
	// 	// $builder = $this->db->table('tcg_peserta_didik a');
	// 	// $builder->where(array('a.nama'=>$nama,'a.jenis_kelamin'=>$jenis_kelamin,'a.tempat_lahir'=>$tempat_lahir,'a.tanggal_lahir'=>$tanggal_lahir,'a.is_deleted'=>0));
	// 	// return $builder->get();
	// }

	// function detailusername()
	// {
	// 	$peserta_didik_id = $_POST["data"] ?? null; (("peserta_didik_id");
	// 	$builder->select('username');
	// 	$builder = $this->db->table('dbo_users');
	// 	$builder->where(array('pengguna_id'=>$peserta_didik_id,'is_deleted'=>0));
	// 	return $builder->get();
	// }
	
	// function undurdiri()
	// {
		// $peserta_didik_id = $_POST["data"] ?? null; (("peserta_didik_id");
		// $keterangan = $_POST["data"] ?? null; (("keterangan");
		// $pengguna_id = $this->session->get("pengguna_id");
		// return $this->db->query("CALL usp_undur_diri ('$peserta_didik_id','$keterangan','$pengguna_id')");
	// }
	
	function dashboardpenerapanditerima(){
		//$bentuk = $this->session->get("bentuk");
		$sekolah_id = $this->session->get("sekolah_id");

		$query = "
		select a.penerapan_id,c.nama AS jalur,d.kuota,
			(
				select count(1) as cnt
				from v_rpt_pendaftaran_diterima z
				where z.penerapan_id = a.penerapan_id and z.sekolah_id='$sekolah_id'
			) as diterima
		from ref_penerapan a
		join ref_jalur c on a.jalur_id = c.jalur_id AND c.expired_date IS NULL
		join tcg_penerapan_sekolah d on a.penerapan_id = d.penerapan_id AND d.is_deleted = 0
		where a.aktif=1 and a.expired_date is null and 
		d.sekolah_id = '$sekolah_id'
		order by a.penerapan_id		
		";
		return $this->db->query($query);
		
	}

	// function pendaftarditerima($penerapan_id){
	// 	$sekolah_id = $this->session->get("sekolah_id");

	// 	$builder->select('pendaftaran_id,sekolah_id,nama_sekolah,asal_sekolah_id,asal_sekolah,peserta_didik_id,nama,penerapan_id,jalur_id,jalur,					nomor_pendaftaran,jenis_pilihan,masuk_jenis_pilihan,peringkat,skor,nisn,
	// 					   status_penerimaan,status_daftar_ulang,created_on');
	// 	$builder = $this->db->table('v_rpt_pendaftaran_diterima');
	// 	$builder->where(array('sekolah_id'=>$sekolah_id,'penerapan_id'=>$penerapan_id));
	// 	$builder->orderBy('peringkat');
	// 	return $builder->get();
	// }
	
	function resetdaftarulang()
	{	
		$sekolah_id = $this->session->get("sekolah_id");
		$data = array(
			'status_daftar_ulang' => 0,
			'updated_on' => date("Y/m/d H:i:s")
		);

		$builder = $this->db->table('tcg_pendaftaran');
		$builder->where(array('sekolah_id'=>$sekolah_id,'is_deleted'=>0));
		return $builder->update($data);
	}
	
	// //TODO: fix this. report should not be updated from program
	// function resetdaftarulangrpt()
	// {
	// 	$sekolah_id = $this->session->get("sekolah_id");
	// 	$data = array(
	// 		'status_daftar_ulang' => 0
	// 	);
	// 	$builder->where(array('sekolah_id'=>$sekolah_id));
	// 	return $builder->update('tcg_pendaftaran', $data);
	// }
	
	// function daftarulangcheck($pendaftaran_id,$nilai)
	// {
	// 	$status_daftar_ulang = 0;
	// 	if($nilai=="on"||$nilai==1){
	// 		$status_daftar_ulang = 1;
	// 	}else{
	// 		$status_daftar_ulang = 0;
	// 	}
	// 	$data = array(
	// 		'status_daftar_ulang' => $status_daftar_ulang,
	// 		'updated_on' => date("Y/m/d H:i:s")
	// 	);
	// 	$builder->where(array('pendaftaran_id'=>$pendaftaran_id,'is_deleted'=>0));
	// 	return $builder->update('tcg_pendaftaran', $data);
	// }
	
	// // TODO: fix this. report should not be updated from program
	// function daftarulangcheckrpt($pendaftaran_id,$nilai)
	// {
	// 	$status_daftar_ulang = 0;
	// 	if($nilai=="on"||$nilai==1){
	// 		$status_daftar_ulang = 1;
	// 	}else{
	// 		$status_daftar_ulang = 0;
	// 	}
	// 	$data = array(
	// 		'status_daftar_ulang' => $status_daftar_ulang
	// 	);
	// 	$builder->where(array('pendaftaran_id'=>$pendaftaran_id));
	// 	return $builder->update('tcg_pendaftaran', $data);
	// }

	// function caripendaftarsusulan(){
	// 	$nama = $_POST["data"] ?? null; (("nama");
	// 	$jenis_kelamin = $_POST["data"] ?? null; (("jenis_kelamin");
	// 	$tempat_lahir = $_POST["data"] ?? null; (("tempat_lahir");
	// 	$tanggal_lahir = $_POST["data"] ?? null; (("tanggal_lahir");
	// 	$builder->select('a.peserta_didik_id,a.nik,a.nisn,a.nomor_ujian,a.nama,a.jenis_kelamin,a.tempat_lahir,a.tanggal_lahir,a.nama_ibu_kandung,a.alamat');
	// 	$builder = $this->db->table('tcg_peserta_didik a');
	// 	$builder->join('tcg_pendaftaran b','a.peserta_didik_id = b.peserta_didik_id AND b.cabut_berkas = 0 AND b.is_deleted = 0 AND b.status_penerimaan = 0 AND b.peserta_didik_id NOT IN(SELECT peserta_didik_id FROM tcg_pendaftaran WHERE status_penerimaan = 1)');
	// 	$builder->where(array('a.nama'=>$nama,'a.jenis_kelamin'=>$jenis_kelamin,'a.tempat_lahir'=>$tempat_lahir,'a.tanggal_lahir'=>$tanggal_lahir,'a.is_deleted'=>0));
	// 	$builder->groupBy(array('a.peserta_didik_id','a.nik','a.nisn','a.nomor_ujian','a.nama','a.jenis_kelamin','a.tempat_lahir','a.tanggal_lahir','a.nama_ibu_kandung', 'a.alamat'));
	// 	return $builder->get();
	// }

	// function pendaftarsusulan()
	// {
	// 	$sekolah_id = $this->session->get("sekolah_id");
	// 	$peserta_didik_id = $_POST["data"] ?? null; (("peserta_didik_id");
	// 	return $this->db->query("CALL " .SQL_PROCESS_PENDAFTARAN. " (33,'$sekolah_id','$peserta_didik_id',1,NULL)");
	// }

	/* --
	**/

	function tcg_profilsekolah($sekolah_id){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('ref_sekolah a');
		$builder->select('a.sekolah_id,a.npsn,a.nama,a.bentuk as bentuk_pendidikan,a.bentuk,a.status,a.alamat_jalan,a.desa_kelurahan,a.kecamatan,a.kabupaten,a.lintang,a.bujur,a.inklusi,b.kuota_total');
		$builder->join('tcg_kuota_sekolah b',"b.sekolah_id = a.sekolah_id and b.is_deleted=0 and b.tahun_ajaran_id='$tahun_ajaran_id' and b.putaran='$putaran'",'LEFT OUTER');
		$builder->where(array('a.sekolah_id'=>$sekolah_id, 'a.is_deleted'=>0));

		return $builder->get();
	}

	function tcg_daftarpenerapan($sekolah_id){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "
		select a.penerapan_id,c.jalur_id,c.nama AS jalur,d.kuota, coalesce(e.tambahan_kuota,0) as tambahan_kuota, 
			   coalesce(e.memenuhi_syarat,0) as memenuhi_syarat, coalesce(e.masuk_kuota,0) as masuk_kuota, coalesce(e.daftar_tunggu,0) as daftar_tunggu, coalesce(e.diterima,0) as diterima,  coalesce(e.total_pendaftar,0) as total_pendaftar
		from ref_penerapan a
		join ref_jalur c on a.jalur_id = c.jalur_id AND c.expired_date IS NULL
		join tcg_penerapan_sekolah d on a.penerapan_id = d.penerapan_id AND d.is_deleted = 0
		left outer join rpt_sekolah_summary e on a.penerapan_id = e.penerapan_id and e.sekolah_id = d.sekolah_id and e.putaran=d.putaran
        join ref_sekolah f on f.sekolah_id=d.sekolah_id and ((f.inklusi=1 and a.jalur_id=7) or a.jalur_id != 7)
		where a.aktif=1 and a.expired_date is null and d.sekolah_id = '$sekolah_id' and a.tahun_ajaran_id='$tahun_ajaran_id' and d.putaran='$putaran'
		order by a.urutan";

		return $this->db->query($query);
	}

	function tcg_pendaftar($sekolah_id){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.jalur_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,a.jenis_pilihan,e.nama AS sekolah_asal,b.lintang,b.bujur,a.kelengkapan_berkas,a.status_penerimaan,a.masuk_jenis_pilihan,a.skor,a.status_penerimaan_final,a.created_on,f.nama AS lokasi_berkas');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.expired_date IS NULL');
		$builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
		$builder->join('ref_sekolah f','a.lokasi_berkas = f.sekolah_id','LEFT OUTER');
		$builder->where(array('a.cabut_berkas'=>0,'a.jenis_pilihan !='=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran));
		$builder->orderBy('a.created_on');

		return $builder->get();
	}

	function tcg_pendaftarditerima($sekolah_id, $penerapan_id){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$sql = "SELECT 
					`a`.`pendaftaran_id`, `a`.`sekolah_id`, `a`.`nama_sekolah`, `a`.`asal_sekolah_id`, `a`.`asal_sekolah`, `a`.`peserta_didik_id`, 
					`a`.`nama`, `a`.`penerapan_id`, `a`.`jalur_id`, `a`.`jalur`, `a`.`nomor_pendaftaran`, `a`.`jenis_pilihan`, `a`.`masuk_jenis_pilihan`, 
					`a`.`peringkat`, `a`.`skor`, `a`.`nisn`, `a`.`status_penerimaan`, `a`.`status_daftar_ulang`, `a`.`lokasi_berkas`, `a`.`created_on`,
					`a`.`nilai_kelulusan`, `a`.`nilai_usbn`, `a`.`jenis_kelamin`, 
					case when `a`.`status_daftar_ulang` = 1 then `a`.`tanggal_daftar_ulang` else NULL end as tanggal_daftar_ulang
				FROM `v_rpt_pendaftaran_diterima` `a` 
				WHERE `sekolah_id`='$sekolah_id' AND `penerapan_id`='$penerapan_id' and tahun_ajaran_id='$tahun_ajaran_id' and putaran='$putaran'
				ORDER BY 
					case when a.status_daftar_ulang>1 then 0 else a.status_daftar_ulang end desc, 
					`a`.`peringkat`";

		return $this->db->query($sql);
	}

	function tcg_pendaftarditerima_per_putaran($sekolah_id, $penerapan_id, $tahun_ajaran_id, $putaran){
		if (empty($tahun_ajaran_id)) {
			$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		}

		$sql = "SELECT 
					`a`.`pendaftaran_id`, `a`.`sekolah_id`, `a`.`nama_sekolah`, `a`.`asal_sekolah_id`, `a`.`asal_sekolah`, `a`.`peserta_didik_id`, 
					`a`.`nama`, `a`.`penerapan_id`, `a`.`jalur_id`, `a`.`jalur`, `a`.`nomor_pendaftaran`, `a`.`jenis_pilihan`, `a`.`masuk_jenis_pilihan`, 
					`a`.`peringkat`, `a`.`skor`, `a`.`nisn`, `a`.`status_penerimaan`, `a`.`status_daftar_ulang`, `a`.`lokasi_berkas`, `a`.`created_on`,
					`a`.`nilai_kelulusan`, `a`.`nilai_usbn`, `a`.`jenis_kelamin`, 
					case when `a`.`status_daftar_ulang` = 1 then `a`.`tanggal_daftar_ulang` else NULL end as tanggal_daftar_ulang
				FROM `v_rpt_pendaftaran_diterima` `a` 
				WHERE `sekolah_id`='$sekolah_id' AND `penerapan_id`='$penerapan_id' and tahun_ajaran_id='$tahun_ajaran_id' ";
		
		if (!empty($putaran)) {
			$sql += " and putaran='$putaran' ";
		}

		$sql += "ORDER BY 
					case when a.status_daftar_ulang>1 then 0 else a.status_daftar_ulang end desc, 
					`a`.`peringkat`";

		return $this->db->query($sql);
	}

	function tcg_pendaftarbelumdiverifikasi($sekolah_id){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,a.jenis_pilihan,e.nama AS sekolah_asal,a.created_on');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.expired_date IS NULL');
		$builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
		$builder->join('tcg_kelengkapan_pendaftaran f','a.pendaftaran_id = f.pendaftaran_id AND f.is_deleted = 0');
		$builder->join('ref_kelengkapan_penerapan g','f.kelengkapan_penerapan_id = g.kelengkapan_penerapan_id AND g.perlu_verifikasi = 1 AND g.expired_date IS NULL');
		$builder->where(array('a.cabut_berkas'=>0,'a.jenis_pilihan !='=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id,'a.kelengkapan_berkas'=>0,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran));
		$builder->groupBy(array('a.pendaftaran_id','a.sekolah_id','a.peserta_didik_id','a.penerapan_id','d.nama','a.nomor_pendaftaran','b.nisn','b.nama','a.jenis_pilihan', 'e.nama','a.created_on'));
		$builder->orderBy('a.created_on desc');
		return $builder->get();
	}

	function tcg_pendaftarbelumlengkap($sekolah_id){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,a.jenis_pilihan,e.nama AS sekolah_asal,a.created_on');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.expired_date IS NULL');
		$builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
		$builder->join('tcg_kelengkapan_pendaftaran f','a.pendaftaran_id = f.pendaftaran_id AND f.is_deleted = 0');
		$builder->join('ref_kelengkapan_penerapan g','f.kelengkapan_penerapan_id = g.kelengkapan_penerapan_id AND g.perlu_verifikasi = 1 AND g.expired_date IS NULL');
		$builder->where(array('a.cabut_berkas'=>0,'a.jenis_pilihan !='=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id,'a.kelengkapan_berkas'=>2,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran));
		$builder->groupBy(array('a.pendaftaran_id','a.sekolah_id','a.peserta_didik_id','a.penerapan_id','d.nama','a.nomor_pendaftaran','b.nisn','b.nama','a.jenis_pilihan', 'e.nama','a.created_on'));
		$builder->orderBy('a.created_on desc');
		return $builder->get();
	}

	function tcg_pendaftarsudahlengkap($sekolah_id){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.sekolah_id,a.peserta_didik_id,a.penerapan_id,d.nama AS jalur,a.nomor_pendaftaran,b.nisn,b.nama,a.jenis_pilihan,e.nama AS sekolah_asal,a.created_on,a.tanggal_verifikasi_berkas,a.verifikasi_berkas_oleh,h.nama as lokasi_berkas');
		$builder->join('tcg_peserta_didik b','a.peserta_didik_id = b.peserta_didik_id AND b.is_deleted = 0');
		$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.expired_date IS NULL');
		$builder->join('ref_sekolah e','b.sekolah_id = e.sekolah_id','LEFT OUTER');
		$builder->join('tcg_kelengkapan_pendaftaran f','a.pendaftaran_id = f.pendaftaran_id AND f.is_deleted = 0');
		$builder->join('ref_kelengkapan_penerapan g','f.kelengkapan_penerapan_id = g.kelengkapan_penerapan_id AND g.perlu_verifikasi = 1 AND g.expired_date IS NULL');
		$builder->join('ref_sekolah h','h.sekolah_id = b.lokasi_berkas','LEFT OUTER');
		$builder->where(array('a.cabut_berkas'=>0,'a.jenis_pilihan !='=>0,'a.is_deleted'=>0,'a.sekolah_id'=>$sekolah_id,'a.kelengkapan_berkas'=>1,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran));
		$builder->groupBy(array('a.pendaftaran_id','a.sekolah_id','a.peserta_didik_id','a.penerapan_id','d.nama','a.nomor_pendaftaran','b.nisn','b.nama','a.jenis_pilihan', 'e.nama','a.created_on'));
		$builder->orderBy('a.created_on');
		return $builder->get();
	}

	function tcg_daftar_siswa($sekolah_id, $tahun_ajaran_id) {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		//TODO: pass updated_on as UTC and convert on client side using moment.js
		$query = "SELECT a.sekolah_id, b.nama as sekolah, 
						a.peserta_didik_id, a.nama, a.jenis_kelamin, a.nisn, a.nik, a.tempat_lahir, a.tanggal_lahir, a.nama_ibu_kandung, a.nama_ayah,
						a.kode_wilayah, a.rt, a.rw, a.alamat, a.nama_dusun, a.desa_kelurahan, a.lintang, a.bujur, 
						CONVERT_TZ(a.updated_on, '+00:00', '+07:00') as updated_on
					FROM tcg_peserta_didik a
					join ref_sekolah b on a.sekolah_id=b.sekolah_id and a.is_deleted=0
					where a.sekolah_id='$sekolah_id' and a.tahun_ajaran_id='$tahun_ajaran_id' and a.is_deleted=0";

		return $this->db->query($query);
	}

	function tcg_daftarkuota(){
		$sekolah_id = $this->session->get("sekolah_id");
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('tcg_penerapan_sekolah a');
		$builder->select('c.jalur_id,c.nama AS jalur,a.kuota');
		$builder->join('ref_penerapan b','a.penerapan_id = b.penerapan_id AND b.aktif = 1 AND b.expired_date IS NULL');
		$builder->join('ref_jalur c','b.jalur_id = c.jalur_id AND c.expired_date IS NULL');
		$builder->where(array('a.sekolah_id'=>$sekolah_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.is_deleted'=>0));
		$builder->orderBy('a.kuota DESC','c.nama ASC');
		return $builder->get();
	}
	
    function tcg_ubah_profil($key, $valuepair) {
		//inject last update
		$valuepair['updated_on'] = date("Y/m/d H:i:s");

		$builder = $this->db->table('ref_sekolah');
        $builder->where('sekolah_id', $key);
        $retval = $builder->update($valuepair);

		if ($retval > 0) {
			//put in audit trail
			$this->tcg_audit_trail("ref_sekolah",$key,'update','Update profil sekolah',implode(';', array_keys($valuepair)),implode(';',$valuepair));
		}
       return 1;
    }

	function tcg_audit_trail($table, $reference, $action, $description, $old_values, $new_values) {
		$pengguna_id = $this->session->get("pengguna_id");

		$query = "CALL usp_audit_trail('$table','$reference','$action','$pengguna_id','$description',null,'$new_values','$old_values')";
		return $this->db->query($query);
	}

	function tcg_daftar_penerapan($sekolah_id) {
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");
		$putaran = $this->session->get('putaran_aktif');

		$sql = "select a.* from rpt_sekolah_summary a where a.sekolah_id='$sekolah_id' and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran'";

		return $this->db->query($sql);

	}

	function tcg_detil_pendaftaran($pendaftaran_id) {
		$sql = "select a.* from tcg_pendaftaran a where a.pendaftaran_id=$pendaftaran_id and a.is_deleted=0";

		return $this->db->query($sql);
	}

	function tcg_pendaftaran($sekolah_id, $peserta_didik_id) {
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");
		$putaran = $this->session->get('putaran_aktif');

		$sql = "select a.* from tcg_pendaftaran a where a.sekolah_id='$sekolah_id' and a.peserta_didik_id='$peserta_didik_id' and a.is_deleted=0 and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran'";

		return $this->db->query($sql);
	}

	function tcg_pendaftaran_penerapan_id($sekolah_id, $penerapan_id){
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");
		$putaran = $this->session->get('putaran_aktif');

		$sql = "select 
					a.pendaftaran_id, a.sekolah_id, a.penerapan_id, b.jalur_id, c.nama as jalur, a.nomor_pendaftaran, a.jenis_pilihan,
					a.lokasi_berkas, a.status_penerimaan, a.status_penerimaan_final, a.peringkat, a.peringkat_final, a.masuk_jenis_pilihan, a.skor,
					a.status_daftar_ulang, a.cabut_berkas,
					d.peserta_didik_id, d.nama, d.nik, d.nisn, d.nomor_ujian, d.jenis_kelamin, d.tempat_lahir, d.tanggal_lahir, d.nama_ibu_kandung, d.nama_ayah, d.nama_wali,
					d.kebutuhan_khusus, d.no_KIP, d.lintang, d.bujur, d.asal_data,
					e.sekolah_id, e.npsn, e.nama as nama_sekolah, e.bentuk as bentuk_sekolah, e.status as status_sekolah,
					a.created_on
				from tcg_pendaftaran a
				join ref_penerapan b on b.penerapan_id=a.penerapan_id and b.expired_date is null
				join ref_jalur c on c.jalur_id=b.jalur_id and c.expired_date is null
				join tcg_peserta_didik d on d.peserta_didik_id=a.peserta_didik_id and d.is_deleted=0
				left join ref_sekolah e on e.sekolah_id=d.sekolah_id and e.is_deleted=0
				where a.sekolah_id='$sekolah_id' and a.penerapan_id=$penerapan_id and a.is_deleted=0 and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran'
				order by a.skor desc, a.peringkat_final asc";

		return $this->db->query($sql);
	}

	function tcg_kandidatswasta($sekolah_id, $tahun_ajaran_id) {
		$tahun_ajaran_id = substr($tahun_ajaran_id, 0, 4);
		$sql = "select
					a.peserta_didik_id, b.nama, RPAD(SUBSTR(b.nisn, 1, 6), Length(b.nisn), '*') as nisn, c.nama as sekolah, 
					concat(d.nama_desa, ' ', d.nama_kec, ' ', d.nama_kab) as alamat, a.jarak
				from tcg_kandidat_swasta a
				join tcg_peserta_didik b on b.peserta_didik_id=a.peserta_didik_id and b.is_deleted=0
				left join ref_sekolah c on c.sekolah_id=b.sekolah_id and c.is_deleted=0
				join ref_wilayah d on d.kode_wilayah=b.kode_wilayah and d.expired_date is null
				where a.sekolah_id='$sekolah_id' and a.tahun_ajaran_id='$tahun_ajaran_id'
		";

		return $this->db->query($sql);
	}

}