<?php 
Class Mpendaftaran 
{

    protected $db;
    protected $session;
    protected $tahun_ajaran_id;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");
    }

	// function detail_pendaftaran($pendaftaran_id) {
	// 	$builder->select('a.pendaftaran_id, a.penerapan_id, b.npsn, b.nama AS sekolah');
	// 	$builder = $this->db->table('tcg_pendaftaran a');
	// 	$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id');
	// 	//$builder->join('tcg_skoring_pendaftaran g','a.pendaftaran_id = f.pendaftaran_id AND f.is_deleted = 0','LEFT OUTER');
	// 	$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.cabut_berkas'=>0,'a.is_deleted'=>0));
	// 	$builder->orderBy('a.jenis_pilihan');
	// 	return $builder->get();
	// }

	function cek_pendaftaran($penerapan_id, $sekolah_id, $peserta_didik_id = null){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$builder = $this->db->table('tcg_pendaftaran');
		$builder->select('COUNT(1) AS jumlah');
		$builder->where(array('peserta_didik_id'=>$peserta_didik_id,'sekolah_id'=>$sekolah_id,'penerapan_id'=>$penerapan_id,'tahun_ajaran_id'=>$tahun_ajaran_id,'putaran'=>$putaran,'cabut_berkas'=>0,'is_deleted'=>0));

		$jmlpendaftaran=0;
		foreach($builder->get()->getResult() as $row):
			$jmlpendaftaran = $row->jumlah;
		endforeach;

		return $jmlpendaftaran;
	}

	function detail_pendaftaran($pendaftaran_id, $peserta_didik_id = null){
		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.penerapan_id,d.jalur_id,d.nama AS jalur,
							a.nomor_pendaftaran,a.jenis_pilihan,a.status_penerimaan,a.cabut_berkas,a.masuk_jenis_pilihan,a.peringkat,
							a.sekolah_id,b.npsn,b.nama AS sekolah,b.lintang AS lintang_sekolah,b.bujur AS bujur_sekolah, 
							a.peserta_didik_id,e.nisn,e.nama,f.lintang AS lintang_siswa,f.bujur AS bujur_siswa,
							f.sekolah_id as asal_sekolah_id,b.npsn as asal_sekolah_npsn,b.nama AS asal_sekolah, 
							a.status_daftar_ulang,a.tanggal_daftar_ulang, a.status_penerimaan_final,a.peringkat_final,
							a.created_on');
		$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id');
		$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1 AND c.is_deleted=0');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.is_deleted=0');
		$builder->join('tcg_peserta_didik e','a.peserta_didik_id = e.peserta_didik_id AND e.is_deleted = 0');
		$builder->join('ref_sekolah f','e.sekolah_id = f.sekolah_id');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.pendaftaran_id'=>$pendaftaran_id,'a.cabut_berkas'=>0,'a.is_deleted'=>0));
		$builder->orderBy('a.created_on');
		return $builder->get();
	}

	function daftar_pendaftaran($peserta_didik_id = null){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,a.penerapan_id,d.jalur_id,d.nama AS jalur,
							a.nomor_pendaftaran,a.jenis_pilihan,a.status_penerimaan,a.cabut_berkas,a.masuk_jenis_pilihan,
							a.peringkat,a.skor,a.kelengkapan_berkas,
							a.status_penerimaan_final,a.peringkat_final,
							a.sekolah_id,b.npsn,b.nama AS sekolah,b.bentuk,
							a.created_on');
		$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id');
		$builder->join('ref_penerapan c','a.penerapan_id = c.penerapan_id AND c.aktif = 1 AND c.is_deleted=0');
		$builder->join('ref_jalur d','c.jalur_id = d.jalur_id AND d.is_deleted=0');
		//$builder->join('tcg_skoring_pendaftaran g','a.pendaftaran_id = f.pendaftaran_id AND f.is_deleted = 0','LEFT OUTER');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.cabut_berkas'=>0,'a.is_deleted'=>0,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran));
		$builder->orderBy('a.jenis_pilihan');
		return $builder->get();
	}

	function hapus_pendaftaran($pendaftaran_id, $keterangan, $peserta_didik_id = null){
		$pengguna_id = $this->session->get("user_id");

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$query = "CALL " .SQL_HAPUS_PENDAFTARAN. " (?, ?, ?, ?)";
		return $this->db->query($query, array($peserta_didik_id, $pendaftaran_id, $keterangan, $pengguna_id));
	}

	function pendaftaran_baru($penerapan_id, $sekolah_id, $jenis_pilihan, $kategori_prestasi=0, $daftar_nilai_skoring_id_prestasi="", $peserta_didik_id = null){
		$pengguna_id = $this->session->get("user_id");

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $pengguna_id;
		}

		if($kategori_prestasi==1&&$daftar_nilai_skoring_id_prestasi!=""){
			$sql = "CALL " .SQL_PROCESS_PENDAFTARAN. " (?,?,?,?,?)";

			$query = $this->db->query($sql, array($penerapan_id, $sekolah_id, $jenis_pilihan, $pengguna_id, $daftar_nilai_skoring_id_prestasi));
		}else{
			$sql = "CALL " .SQL_PROCESS_PENDAFTARAN. " (?,?,?,?,NULL)";

			$query = $this->db->query($sql, array($penerapan_id, $sekolah_id, $jenis_pilihan, $pengguna_id));
		}

		$pendaftaran_id = 0;
		foreach($query->getResult() as $row) {
			$pendaftaran_id = $row->pendaftaran_id;
		}

		return $pendaftaran_id;
	}

	function kelengkapan_pendaftaran($pendaftaran_id){
		$builder = $this->db->table('tcg_kelengkapan_pendaftaran a');
		$builder->select('a.kelengkapan_pendaftaran_id,c.nama AS kelengkapan,a.verifikasi,b.kondisi_khusus,b.wajib');
		$builder->join('cfg_kelengkapan_dokumen b','a.kelengkapan_dokumen_id = b.kelengkapan_dokumen_id AND b.is_deleted=0');
		$builder->join('ref_daftar_kelengkapan c','b.daftar_kelengkapan_id = c.daftar_kelengkapan_id AND c.is_deleted=0');
		$builder->where(array('a.pendaftaran_id'=>$pendaftaran_id,'a.is_deleted'=>0));
		$builder->orderBy('c.daftar_kelengkapan_id');
		return $builder->get();
	}

	function nilai_skoring($pendaftaran_id){

		$builder = $this->db->table('tcg_skoring_pendaftaran a');
		$builder->select('a.skoring_pendaftaran_id,c.nama AS keterangan,a.nilai');
		$builder->join('tcg_pendaftaran b','a.pendaftaran_id = b.pendaftaran_id AND b.cabut_berkas = 0 AND b.is_deleted = 0');
		$builder->join('ref_daftar_skoring c','a.skoring_id = c.skoring_id AND c.is_deleted=0');
		//$builder->join('cfg_daftar_nilai_skoring d','a.skoring_id = d.skoring_id and b.tahun_ajaran_id=d.tahun_ajaran_id AND c.is_deleted=0');
		$builder->where(array('a.pendaftaran_id'=>$pendaftaran_id,'a.is_deleted'=>0));
		$builder->orderBy('c.nama');
		return $builder->get();
	}

	function pilihan_sekolah($penerapan_id, $peserta_didik_id = null){
		$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$query = "CALL usp_pendaftaran_sekolah (?,?)";
		return $this->db->query($query, array($peserta_didik_id, $penerapan_id));
	}

	function pilihan_sekolah_perubahan($pendaftaran_id, $peserta_didik_id = null){
		$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$query = "CALL usp_pendaftaran_sekolah_perubahan (?,?)";
		return $this->db->query($query, array($peserta_didik_id, $pendaftaran_id));
	}

	function ubah_pilihan_sekolah($pendaftaran_id, $sekolah_id_baru, $peserta_didik_id = null){
		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$query = "CALL " .SQL_UBAH_PILIHANSEKOLAH. " (?,?,?)";
		return $this->db->query($query, array($peserta_didik_id, $pendaftaran_id, $sekolah_id_baru));
	}

	/**
	 * Pilihan jenis-pilihan ketika pertama kali melakukan pendaftaran
	 */
	function jenis_pilihan($penerapan_id, $peserta_didik_id = null){
		$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$query = "CALL usp_pendaftaran_jenispilihan (?,?)";
		return $this->db->query($query, array($peserta_didik_id, $penerapan_id));
	}

	/**
	 * Pilihan jenis-pilihan yang bisa dipilih untuk perubahan
	 */
	function jenis_pilihan_perubahan($pendaftaran_id, $peserta_didik_id = null){
		$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$query = "CALL usp_pendaftaran_jenispilihan_perubahan (?,?)";
		return $this->db->query($query, array($peserta_didik_id, $pendaftaran_id));

	}

	function ubah_jenis_pilihan($pendaftaran_id, $jenis_pilihan_baru, $peserta_didik_id = null){
		$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$sql = "CALL " .SQL_UBAH_JENISPILIHAN. " (?,?,?)";
		return $this->db->query($sql, array($peserta_didik_id, $pendaftaran_id, $jenis_pilihan_baru));
	}

	function jalur_penerimaan($peserta_didik_id = null){
		$kode_wilayah_aktif = $this->session->get("kode_wilayah_aktif");
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
			$kode_wilayah_zonasi = substr($this->session->get("kode_wilayah"),0,4);
			$tanggal_lahir = $this->session->get("tanggal_lahir");
			$kebutuhan_khusus = $this->session->get("kebutuhan_khusus");
		}
		else {
			//get info peserta_didik
			$builder = $this->db->table('tcg_peserta_didik a');
			$builder->select('CONVERT(a.kode_wilayah,CHAR(8)) AS kode_wilayah, a.tanggal_lahir, a.kebutuhan_khusus');
			$builder->where(array('a.is_deleted'=>0,'a.peserta_didik_id'=>$peserta_didik_id));

			$query = $builder->get();
			
			$kode_wilayah_zonasi = "";
			$tanggal_lahir = "1970-01-01";
			$kebutuhan_khusus = "";
			foreach($query->getResult() as $row) {
				$kode_wilayah_zonasi = $row->kode_wilayah;
				$tanggal_lahir = $row->tanggal_lahir;
				$kebutuhan_khusus = $row->kebutuhan_khusus;
			}
		}

		$bentuk_sekolah = $this->jenjang_pendaftaran();

		//get the list
		$builder->select('a.penerapan_id,a.nama,a.keterangan,c.jalur_id,c.nama AS jalur,a.sekolah_negeri,a.sekolah_swasta,a.batasan_tampilan,a.kategori_jarak,a.kategori_prestasi,a.kategori_usia,a.kategori_zona,a.kategori_inklusi,a.luar_wilayah_administrasi,a.kategori_susulan');
		$builder = $this->db->table('ref_penerapan a');
		$builder->join('ref_jalur c','a.jalur_id = c.jalur_id AND c.is_deleted=0');
		$builder->join('cfg_batasan_usia f',"f.is_deleted=0 and f.bentuk_tujuan_sekolah='$bentuk_sekolah' and f.tahun_ajaran_id=a.tahun_ajaran_id");
		$builder->where(array('a.aktif'=>1,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.expired_date'=>NULL));
		$builder->orderBy('a.urutan');
		
		if(substr($kode_wilayah_zonasi,0,4)!=substr($kode_wilayah_aktif,0,4)){
			$builder->where('a.luar_wilayah_administrasi',1);
		}else{
			$builder->where('a.dalam_wilayah_administrasi',1);
		}

		if($kebutuhan_khusus!="Tidak ada"){
			$builder->where('a.kategori_inklusi',1);
		}else{
			$builder->where('a.kategori_inklusi',0);
			$builder->where('f.minimal_tanggal_lahir >=', $tanggal_lahir);
			$builder->where('f.maksimal_tanggal_lahir <=', $tanggal_lahir);
		}
		
		$builder->orderBy('a.urutan');
		return $builder->get();
	}

	/**
	 * Detil jalur penerapan
	 */
	function detail_jalur_penerimaan($penerapan_id, $peserta_didik_id = null){
		
		$kode_wilayah_aktif = $this->session->get("kode_wilayah_aktif");
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
			$kode_wilayah_zonasi = substr($this->session->get("kode_wilayah"),0,4);
			$tanggal_lahir = $this->session->get("tanggal_lahir");
			$kebutuhan_khusus = $this->session->get("kebutuhan_khusus");
		}
		else {
			//get info peserta_didik
			$builder = $this->db->table('tcg_peserta_didik a');
			$builder->select('CONVERT(a.kode_wilayah,CHAR(8)) AS kode_wilayah, a.tanggal_lahir, a.kebutuhan_khusus');
			$builder->where(array('a.is_deleted'=>0,'a.peserta_didik_id'=>$peserta_didik_id));

			$query = $builder->get();
			
			$kode_wilayah_zonasi = "";
			$tanggal_lahir = "1970-01-01";
			$kebutuhan_khusus = "";
			foreach($query->getResult() as $row) {
				$kode_wilayah_zonasi = $row->kode_wilayah;
				$tanggal_lahir = $row->tanggal_lahir;
				$kebutuhan_khusus = $row->kebutuhan_khusus;
			}
		}

		$bentuk_sekolah = $this->jenjang_pendaftaran();

		$builder->select('a.penerapan_id,a.nama,a.keterangan,c.jalur_id,c.nama AS jalur,a.sekolah_negeri,a.sekolah_swasta,a.batasan_tampilan,a.kategori_jarak,a.kategori_prestasi,a.kategori_usia,a.kategori_zona,a.kategori_inklusi,a.luar_wilayah_administrasi,a.kategori_susulan');
		$builder = $this->db->table('ref_penerapan a');
		$builder->join('ref_jalur c','a.jalur_id = c.jalur_id AND c.is_deleted=0');
		$builder->join('cfg_batasan_usia f',"f.is_deleted=0 and f.bentuk_tujuan_sekolah='$bentuk_sekolah'");
		$builder->where(array('a.penerapan_id'=>$penerapan_id,'a.aktif'=>1,'a.expired_date'=>NULL,'a.tahun_ajaran_id'=>$this->tahun_ajaran_id));
		
		if(substr($kode_wilayah_zonasi,0,4)!=substr($kode_wilayah_aktif,0,4)){
			$builder->where('a.luar_wilayah_administrasi',1);
		}else{
			$builder->where('a.dalam_wilayah_administrasi',1);
		}
		if($kebutuhan_khusus!="Tidak ada"){
			$builder->where('a.kategori_inklusi',1);
		}else{
			$builder->where('a.kategori_inklusi',0);
			$builder->where('f.minimal_tanggal_lahir >=', $tanggal_lahir);
			$builder->where('f.maksimal_tanggal_lahir <=', $tanggal_lahir);
		}	
		
		return $builder->get();
	}

	function jalur_penerimaan_perubahan($pendaftaran_id, $peserta_didik_id = null) {
		$kode_wilayah_aktif = $this->session->get("kode_wilayah_aktif");
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
			$kode_wilayah_zonasi = substr($this->session->get("kode_wilayah"),0,4);
			$tanggal_lahir = $this->session->get("tanggal_lahir");
			$kebutuhan_khusus = $this->session->get("kebutuhan_khusus");
		}
		else {
			//get info peserta_didik
			$builder = $this->db->table('tcg_peserta_didik a');
			$builder->select('CONVERT(a.kode_wilayah,CHAR(8)) AS kode_wilayah, a.tanggal_lahir, a.kebutuhan_khusus');
			$builder->where(array('a.is_deleted'=>0,'a.peserta_didik_id'=>$peserta_didik_id));

			$query = $builder->get();
			
			$kode_wilayah_zonasi = "";
			$tanggal_lahir = "1970-01-01";
			$kebutuhan_khusus = "";
			foreach($query->getResult() as $row) {
				$kode_wilayah_zonasi = $row->kode_wilayah;
				$tanggal_lahir = $row->tanggal_lahir;
				$kebutuhan_khusus = $row->kebutuhan_khusus;
			}
		}

		$bentuk_sekolah = $this->jenjang_pendaftaran();

		//get current pendaftaran
		$builder->select('a.penerapan_id');
		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->where(array('a.pendaftaran_id'=>$pendaftaran_id,'a.cabut_berkas'=>0,'a.is_deleted'=>0));
		$query = $builder->get();

		$penerapan_id = 0;
		foreach($query->getResult() as $row) {
			$penerapan_id = $row->penerapan_id;
		}

		//get the list
		$builder->select('a.penerapan_id,a.nama,a.keterangan,c.jalur_id,c.nama AS jalur,a.sekolah_negeri,a.sekolah_swasta,a.batasan_tampilan,a.kategori_jarak,a.kategori_prestasi,a.kategori_usia,a.kategori_zona,a.kategori_inklusi,a.luar_wilayah_administrasi,a.kategori_susulan');
		$builder = $this->db->table('ref_penerapan a');
		$builder->join('ref_jalur c','a.jalur_id = c.jalur_id AND c.is_deleted=0');
		$builder->join('cfg_batasan_usia f',"f.is_deleted=0 and f.bentuk_tujuan_sekolah='$bentuk_sekolah' and f.tahun_ajaran_id=a.tahun_ajaran_id");
		$builder->where(array('a.aktif'=>1,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.expired_date'=>NULL,'a.penerapan_id!='=>$penerapan_id));
		$builder->orderBy('a.urutan');
		
		if(substr($kode_wilayah_zonasi,0,4)!=substr($kode_wilayah_aktif,0,4)){
			$builder->where('a.luar_wilayah_administrasi',1);
		}else{
			$builder->where('a.dalam_wilayah_administrasi',1);
		}

		if($kebutuhan_khusus!="Tidak ada"){
			$builder->where('a.kategori_inklusi',1);
		}else{
			$builder->where('a.kategori_inklusi',0);
			$builder->where('f.minimal_tanggal_lahir >=', $tanggal_lahir);
			$builder->where('f.maksimal_tanggal_lahir <=', $tanggal_lahir);
		}
		
		$builder->orderBy('a.urutan');
		return $builder->get();
	}

	function batasan_siswa($peserta_didik_id = null){
		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$builder = $this->db->table('tcg_peserta_didik');
		$builder->select('cabut_berkas,hapus_pendaftaran,ubah_pilihan,ubah_sekolah,ubah_jalur');
		$builder->where(array('peserta_didik_id'=>$peserta_didik_id,'is_deleted'=>0));
		return $builder->get();
	}

	function batasan_pilihan(){
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");

		$builder = $this->db->table('cfg_jenis_pilihan');
		$builder->select('COUNT(1) AS jumlah');
		$builder->where(array('aktif'=>1,'expired_date'=>NULL,'sekolah_negeri'=>1,'sekolah_swasta'=>1,'tahun_ajaran_id'=>$tahun_ajaran_id));

		$maxjenispilihan=0;
		foreach($builder->get()->getResult() as $row):
			$maxjenispilihan = $row->jumlah;
		endforeach;

		return $maxjenispilihan;
	}

	function jumlah_pendaftaran($peserta_didik_id = null){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('COUNT(1) AS jumlah');
		$builder->join('ref_penerapan b','a.penerapan_id = b.penerapan_id AND b.is_deleted=0 AND b.aktif = 1');
		$builder->where(array('peserta_didik_id'=>$peserta_didik_id,'cabut_berkas'=>0,'is_deleted'=>0,'a.tahun_ajaran_id'=>$this->tahun_ajaran_id,'a.putaran'=>$putaran));

		$jmlpendaftaran=0;
		foreach($builder->get()->getResult() as $row):
			$jmlpendaftaran = $row->jumlah;
		endforeach;

		return $jmlpendaftaran;
	}

	function batasan_pilihan_negeri(){
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");

		$builder = $this->db->table('cfg_jenis_pilihan');
		$builder->select('COUNT(1) AS jumlah');
		$builder->where(array('aktif'=>1,'expired_date'=>NULL,'sekolah_negeri'=>1,'sekolah_swasta'=>0,'tahun_ajaran_id'=>$tahun_ajaran_id));

		$maxjenispilihan=0;
		foreach($builder->get()->getResult() as $row):
			$maxjenispilihan = $row->jumlah;
		endforeach;

		return $maxjenispilihan;
	}

	function jumlah_pendaftaran_negeri($peserta_didik_id = null){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('COUNT(1) AS jumlah');
		$builder->join('ref_penerapan b','a.penerapan_id = b.penerapan_id AND b.is_deleted=0 AND b.aktif = 1');
		$builder->where(array('peserta_didik_id'=>$peserta_didik_id,'cabut_berkas'=>0,'is_deleted'=>0, 'sekolah_negeri'=>1,'a.tahun_ajaran_id'=>$this->tahun_ajaran_id,'a.putaran'=>$putaran));

		$jmlpendaftaran=0;
		foreach($builder->get()->getResult() as $row):
			$jmlpendaftaran = $row->jumlah;
		endforeach;

		return $jmlpendaftaran;
	}

	function batasan_pilihan_swasta(){
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");

		$builder = $this->db->table('cfg_jenis_pilihan');
		$builder->select('COUNT(1) AS jumlah');
		$builder->where(array('aktif'=>1,'expired_date'=>NULL,'sekolah_swasta'=>1,'sekolah_negeri'=>0,'tahun_ajaran_id'=>$tahun_ajaran_id));

		$maxjenispilihan=0;
		foreach($builder->get()->getResult() as $row):
			$maxjenispilihan = $row->jumlah;
		endforeach;

		return $maxjenispilihan;
	}

	function jumlah_pendaftaran_swasta($peserta_didik_id = null){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('COUNT(1) AS jumlah');
		$builder->join('ref_penerapan b','a.penerapan_id = b.penerapan_id AND b.is_deleted=0 AND b.aktif = 1');
		$builder->where(array('peserta_didik_id'=>$peserta_didik_id,'cabut_berkas'=>0,'is_deleted'=>0, 'sekolah_swasta'=>1,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran));

		$jmlpendaftaran=0;
		foreach($builder->get()->getResult() as $row):
			$jmlpendaftaran = $row->jumlah;
		endforeach;

		return $jmlpendaftaran;
	}

	function jenjang_pendaftaran() {
		$bentuk_sekolah = $this->session->get("bentuk");

		if ($bentuk_sekolah == "SD" || $bentuk_sekolah == "MI") {
			return "SMP";
		}
		else {
			return "SD";
		}
	}

	function peserta_didik_id($pendaftaran_id) {

		$builder = $this->db->table('tcg_pendaftaran');
		$builder->select('peserta_didik_id');
		$builder->where(array('pendaftaran_id'=>$pendaftaran_id,'is_deleted'=>0));

		$peserta_didik_id = "";
		foreach($builder->get()->getResult() as $row):
			$peserta_didik_id = $row->peserta_didik_id;
		endforeach;

		return $peserta_didik_id;
	}

	function kelengkapan_berkas($peserta_didik_id = null) {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$builder = $this->db->table('tcg_kelengkapan_pendaftaran a');
		$builder->select('b.daftar_kelengkapan_id, max(a.verifikasi) as verifikasi, 
							max(case when a.verifikasi=0 then null else a.verifikator_id end) as verifikator_id, 
							c.nama as kelengkapan, e.nama as nama_verifikator, 
							f.dokumen_id, max(f.web_path) as web_path, max(f.thumbnail_path) as thumbnail_path, max(f.filename) as filename,
							max(dokumen_fisik) as dokumen_fisik, max(placeholder) as placeholder');
		$builder->join('cfg_kelengkapan_dokumen b','a.kelengkapan_dokumen_id = b.kelengkapan_dokumen_id and b.is_deleted=0');
		$builder->join('ref_daftar_kelengkapan c','b.daftar_kelengkapan_id=c.daftar_kelengkapan_id and c.is_deleted=0');
		$builder->join('tcg_pendaftaran d','a.pendaftaran_id=d.pendaftaran_id and d.is_deleted=0 and d.cabut_berkas=0');
		$builder->join('dbo_users e','a.verifikator_id=e.pengguna_id and e.is_deleted=0','LEFT OUTER');
		$builder->join('tcg_dokumen_pendukung f','f.dokumen_id=a.dokumen_Id and f.is_deleted=0','LEFT OUTER');
		$builder->where(array('d.peserta_didik_id'=>$peserta_didik_id,'d.tahun_ajaran_id'=>$tahun_ajaran_id,'a.is_deleted'=>0));
		$builder->groupBy(array('b.daftar_kelengkapan_id', 'c.nama', 'e.nama', 'f.dokumen_id'));
		$builder->orderBy('b.daftar_kelengkapan_id');

		return $builder->get();
	}

	function verifikasi_dokumen_pendukung($peserta_didik_id, $dokumen_id, $verifikasi, $catatan) {
		$verifikator_id = $this->session->get("user_id");

		$query = "
		update tcg_dokumen_pendukung a
		set
			a.verifikasi = ?,
			a.catatan = ?,
			a.verifikator_id = ?,
			a.tanggal_verifikasi = now(),
			a.updated_on = now()
		where a.peserta_didik_id=? and a.dokumen_id=? and a.is_deleted=0";

		$this->db->query($query, array($verifikasi, $catatan, $verifikator_id, $peserta_didik_id, $dokumen_id));

		$retval = $this->db->affectedRows();
		if ($retval > 0) {
			$keys = "dokumen_id;verifikasi;verifikator_id";
			$values = "$dokumen_id;$verifikasi;$verifikator_id";

			//put in audit trail
			$this->tcg_audit_trail("tcg_dokumen_pendukung",$peserta_didik_id,'update','Verifikasi berkas',$keys,$values);
		}

		return $retval;

	}

	function verifikasi_kelengkapan_berkas($peserta_didik_id, $dokumen_id, $verifikasi) {
		$verifikator_id = $this->session->get("user_id");

		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$query = "
		update tcg_kelengkapan_pendaftaran a
		join tcg_pendaftaran d on a.pendaftaran_id=d.pendaftaran_id and d.is_deleted=0 and d.cabut_berkas=0
		set
			a.verifikasi = ?,
			a.verifikator_id = ?,
			a.updated_on = now()
		where d.peserta_didik_id=? and d.tahun_ajaran_id=? and a.dokumen_id=? and a.is_deleted=0";

		$this->db->query($query, array($verifikasi, $verifikator_id, $peserta_didik_id, $tahun_ajaran_id, $dokumen_id));

		$retval = $this->db->affectedRows();
		if ($retval > 0) {
			$keys = "dokumen_id;verifikasi;verifikator_id";
			$values = "$dokumen_id;$verifikasi;$verifikator_id";

			//put in audit trail
			$this->tcg_audit_trail("tcg_kelengkapan_pendaftaran",$peserta_didik_id,'update','Verifikasi berkas',$keys,$values);
		}

		return $retval;

	}

	function verifikasi_profil($peserta_didik_id, $valuepair) {	
		$pengguna_id = $this->session->get("user_id");

		//enforce last-update
		$valuepair['verifikator_id'] = $pengguna_id;
		$valuepair['tanggal_verifikasi'] = date("Y/m/d H:i:s");
		$valuepair['updated_on'] = date("Y/m/d H:i:s");

		$builder = $this->db->table('tcg_peserta_didik');
		$builder->where(array('peserta_didik_id'=>$peserta_didik_id,'is_deleted'=>0));
		$retval = $builder->update($valuepair);

		if ($retval > 0) {
			//put in audit trail
			$this->tcg_audit_trail("tcg_peserta_didik",$peserta_didik_id,'update','Update status verifikasi',implode(';', array_keys($valuepair)),implode(';',$valuepair));
		}

		return $retval;	
	}

	function cek_kelengkapan_profil($peserta_didik_id = null) {
		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$sql = "SELECT verifikasi_profil, verifikasi_lokasi, verifikasi_nilai, verifikasi_prestasi, verifikasi_afirmasi, verifikasi_inklusi 
		from tcg_peserta_didik
		where peserta_didik_id=? and is_deleted=0";

		$kelengkapan_profil = 0;
		foreach($this->db->query($sql, array($peserta_didik_id))->getResult() as $row) {
			if ($row->verifikasi_profil == 1 && $row->verifikasi_lokasi == 1
					&& $row->verifikasi_nilai == 1 && $row->verifikasi_prestasi == 1
					&& $row->verifikasi_afirmasi == 1 && $row->verifikasi_inklusi == 1) {
				$kelengkapan_profil = 1;
			}
			else {
				$kelengkapan_profil = 2;
			}
		}

		return $kelengkapan_profil;
	}

	//update kelengkapan berkas semua pendaftaran sesuai data
	function update_kelengkapan_berkas_pendaftaran($peserta_didik_id, $tahun_ajaran_id){
		$pengguna_id = $this->session->get("user_id");

		$query = "CALL " .SQL_UBAH_KELENGKAPANBERKAS. "(?, ?, ?)";

		$this->db->query($query, array($peserta_didik_id, $tahun_ajaran_id, $pengguna_id));
	}

	function tcg_riwayat_verifikasi($peserta_didik_id = null) {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$sql = "SELECT a.riwayat_id, a.verifikator_id, b.nama, a.verifikasi, a.catatan_kekurangan, a.created_on 
		from tcg_riwayat_verifikasi a
		join dbo_users b on b.pengguna_id=a.verifikator_id and b.is_deleted=0
		where a.is_deleted=0 and a.peserta_didik_id=?";

		return $this->db->query($sql, array($peserta_didik_id));
	}

	function riwayat_verifikasi_baru($peserta_didik_id, $verifikasi, $catatan) {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$pengguna_id = $this->session->get("user_id");

		$valuepair = array(
			'peserta_didik_id' => $peserta_didik_id,
			'tahun_ajaran_id' => $tahun_ajaran_id,
			'verifikator_id' => $pengguna_id,
			'verifikasi' => $verifikasi,
			'catatan_kekurangan' => $catatan
		);

		$builder = $this->db->table('tcg_riwayat_verifikasi');
        if ($builder->insert($valuepair)) {
            $key = $this->db->insertID();
            //return the id
            return $key;
        } else {
            return 0;
        }
	}

	function lokasi_berkas($peserta_didik_id = null) {
		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$builder = $this->db->table('tcg_peserta_didik a');
		$builder->select('a.lokasi_berkas, b.nama as nama_lokasi');
		$builder->join('ref_sekolah b','a.lokasi_berkas = b.sekolah_id and b.is_deleted=0', 'LEFT OUTER');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.is_deleted'=>0));

		$nama_lokasi = "";
		foreach($builder->get()->getResult() as $row):
			$nama_lokasi = $row->nama_lokasi;
		endforeach;

		return $nama_lokasi;
	}

	function ubah_lokasi_berkas($peserta_didik_id, $sekolah_id) {

		$data = array(
			'lokasi_berkas' => $sekolah_id,
			'updated_on' => date("Y/m/d H:i:s")
		);

		$builder = $this->db->table('tcg_peserta_didik a');
		$builder->where(array('peserta_didik_id'=>$peserta_didik_id,'is_deleted'=>0));
		$retval = $builder->update($data);

		if ($retval > 0) {
			//put in audit trail
			$this->tcg_audit_trail("tcg_peserta_didik",$peserta_didik_id,'update','Ubah lokasi berkas',implode(';', array_keys($data)),implode(';',$data));
		}

		return $retval;
	}

	function rekapitulasi_sekolah_dalam_zonasi($peserta_didik_id = null) {
		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$query = "call usp_rekapitulasi_sekolah_dalam_zonasi('$peserta_didik_id')";

		return $this->db->query($query, array($peserta_didik_id));
	}

	function jalur_pendaftaran_dalam_zonasi($peserta_didik_id = null) {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');
		
		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$sql = "
		select 
			d.kode_zona, max(d.nama) as nama, 
			e.penerapan_id, max(e.jalur_id) as jalur_id, max(e.nama) as jalur  
		from tcg_pendaftaran a
		JOIN ref_sekolah b ON b.sekolah_id = a.sekolah_id and b.is_deleted=0 and b.status='N'
		join tcg_peserta_didik c on c.peserta_didik_id = a.peserta_didik_id and c.is_deleted=0
		join v_zona_wilayah d on d.kode_zona=LEFT(c.kode_wilayah, 6) and d.kode_wilayah = b.kode_wilayah_kec 
		join ref_penerapan e on e.penerapan_id=a.penerapan_id and e.is_deleted=0
		where a.peserta_didik_id=? and a.is_deleted=0 and a.tahun_ajaran_id=? and a.putaran=?
		group by d.kode_zona, e.penerapan_id";

		return $this->db->query($sql, array($peserta_didik_id, $tahun_ajaran_id, $putaran));
	}

	function jalur_pendaftaran_luar_zonasi($peserta_didik_id = null) {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');
		
		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$sql = "
		select 
			d2.kode_zona, max(d2.nama) as nama, 
			e.penerapan_id, max(e.jalur_id) as jalur_id, max(e.nama) as jalur 
		from tcg_pendaftaran a
		JOIN ref_sekolah b ON b.sekolah_id = a.sekolah_id and b.is_deleted=0 and b.status='N'
		join tcg_peserta_didik c on c.peserta_didik_id = a.peserta_didik_id and c.is_deleted=0
		left join v_zona_wilayah d on d.kode_zona=LEFT(c.kode_wilayah, 6) and d.kode_wilayah = b.kode_wilayah_kec 
		join v_zona_wilayah d2 on d2.kode_zona=b.kode_wilayah_kec and d2.kode_wilayah = b.kode_wilayah_kec 
		join ref_penerapan e on e.penerapan_id=a.penerapan_id and e.is_deleted=0
		where a.peserta_didik_id=? and a.is_deleted=0 and a.tahun_ajaran_id=? and a.putaran=? and d.kode_zona is null
		group by d2.kode_zona, e.penerapan_id";

		return $this->db->query($sql, array($peserta_didik_id, $tahun_ajaran_id, $putaran));
	}

	function cek_satu_zonasi_satu_jalur($sekolah_id, $penerapan_id, $peserta_didik_id = null) {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');
		
		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		//TODO: cek tahun ajaran
		$sql = "CALL usp_cek_satu_zonasi_satu_jalur(?, ?, ?)";

		return $this->db->query($sql, array($peserta_didik_id, $sekolah_id, $penerapan_id));
	}

	function cek_sekolah_dalam_zonasi($sekolah_id, $peserta_didik_id = null) {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		
		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$sql = "
		select 
			count(*) as cnt 
		from ref_sekolah b
		join tcg_peserta_didik c on c.peserta_didik_id=? and c.is_deleted=0
		join ref_zona d on d.kode_zona=LEFT(c.kode_wilayah, 6) and d.is_deleted=0
		left join cfg_zona_wilayah e on e.zona_wilayah_id=d.zona_id and e.is_deleted=0 and e.kode_wilayah = b.kode_wilayah_kec and e.tahun_ajaran_id=?
		where b.sekolah_id=? and b.is_deleted=0 
			and (e.zona_wilayah_id is not null or b.status='S')";

		$query = $this->db->query($sql, array($peserta_didik_id, $tahun_ajaran_id, $sekolah_id));

		$flag = 0;
		foreach($query->getResult() as $row) {
			$flag = $row->cnt;
		}

		return ($flag > 0 ? 1 : 0);
	}

	function daftar_ulang($pendaftaran_id,$status) {
		$pengguna_id = $this->session->get("user_id");

		$query = "
			update tcg_pendaftaran a
			set
				a.status_daftar_ulang = ?,
				a.tanggal_daftar_ulang = now(),
				a.daftar_ulang_oleh = ?,
				a.updated_on = now()
			where a.pendaftaran_id=?";

		$this->db->query($query, array($status, $pengguna_id, $pendaftaran_id));
		return $this->db->affectedRows();
	}

	function daftar_ulang_dokumen_pendukung($peserta_didik_id, $dokumen_id, $status) {
		$penerima_berkas_id = $this->session->get("user_id");

		$query = "
		update tcg_dokumen_pendukung a
		set
			a.berkas_fisik = ?,
			a.penerima_berkas_id = ?,
			a.tanggal_berkas = now(),
			a.updated_on = now()
		where a.peserta_didik_id=? and a.dokumen_id=? and a.is_deleted=0";

		$this->db->query($query, array($status, $penerima_berkas_id, $peserta_didik_id, $dokumen_id));

		$retval = $this->db->affectedRows();
		if ($retval > 0) {
			$keys = "dokumen_id;daftarulang;penerima_berkas_id";
			$values = "$dokumen_id;$status;$penerima_berkas_id";

			//put in audit trail
			$this->tcg_audit_trail("tcg_dokumen_pendukung",$peserta_didik_id,'update','Daftar ulang berkas',$keys,$values);
		}

		return $retval;

	}

	function daftar_pendaftaran_sekolah($peserta_didik_id = null){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.pendaftaran_id,b.npsn,b.nama AS sekolah');
		$builder->join('ref_sekolah b','a.sekolah_id = b.sekolah_id');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.cabut_berkas'=>0,'a.is_deleted'=>0));
		$builder->orderBy('a.jenis_pilihan');
		return $builder->get();
	}

	function daftar_pendaftaran_jenis_pilihan($jenis_pilihan, $peserta_didik_id = null) {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		// $sql = "
		// select a.*, b.jalur_id 
		// from tcg_pendaftaran a
		// join ref_penerapan b on b.penerapan_id=a.penerapan_id
		// where a.peserta_didik_id='$peserta_didik_id' and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran' and a.jenis_pilihan=$jenis_pilihan and a.is_deleted=0 and a.cabut_berkas=0";

		// return $this->db->query($sql);

		$builder = $this->db->table('tcg_pendaftaran a');
		$builder->select('a.*, b.jalur_id');
		$builder->join('ref_penerapan b','b.penerapan_id = a.penerapan_id');
		$builder->where(array('a.peserta_didik_id'=>$peserta_didik_id,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.cabut_berkas'=>0,'a.is_deleted'=>0,'a.jenis_pilihan'=>$jenis_pilihan));
		
		return $builder->get();
	}

	function daftar_prestasi_pendaftaran($peserta_didik_id = null) {
		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}

		$query = "SELECT distinct(a.prestasi_skoring_id) as daftar_nilai_skoring_id, `b`.`nama`, coalesce(`c`.`nilai`,0) as nilai, 1 as `verifikasi`, '' as verifikator_id 
		FROM `tcg_pendaftaran` `a` 
		JOIN `ref_daftar_skoring` `b` ON `b`.`skoring_id` = `a`.`prestasi_skoring_id` and `b`.`expired_date` is null 
		left outer join `cfg_daftar_nilai_skoring` `c` on `c`.`daftar_nilai_skoring_id` = `a`.`prestasi_skoring_id` and `c`.`tahun_ajaran_id`=`a`.`tahun_ajaran_id` and `c`.`nilai` > 0 and `c`.`expired_date` is null 
		WHERE `a`.`peserta_didik_id` = ? AND `a`.`is_deleted` = 0 AND `a`.`cabut_berkas` = 0 
		AND `a`.`prestasi_skoring_id` IS NOT NULL AND `a`.`prestasi_skoring_id` != 0";

		return $this->db->query($query, array($peserta_didik_id));
	}

	function tcg_audit_trail($table, $reference, $action, $description, $old_values, $new_values) {
		$pengguna_id = $this->session->get("user_id");

		$query = "CALL usp_audit_trail(?,?,?,?,?,?,?,?)";
		return $this->db->query($query, array($table,$reference,$action,$pengguna_id,$description,null,$new_values,$old_values));
	}

}

