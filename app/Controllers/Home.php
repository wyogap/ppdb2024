<?php

namespace App\Controllers;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Mhome;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Home extends PpdbController
{
    protected $Mhome;

	// public function __construct()
	// {
	// 	parent::__construct();
	// 	//return redirect()->to("Cinfo");
	// 	if($this->session->get('isLogged')==FALSE||$this->session->get('peran_id')!=1) {
	// 		return redirect()->to("akun/login");
	// 	}
	// }

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load model
        $this->Mhome = new Mhome();
    }

	function index()
	{	
        setlocale(LC_ALL, APP_LOCALE);
        date_default_timezone_set(APP_TIMEZONE);

		$data['tahapan_pelaksanaan'] = $this->Mconfig->tcg_tahapan_pelaksanaan();
        for($i=0; $i<count($data['tahapan_pelaksanaan']); $i++) {
            $tahapan = $data['tahapan_pelaksanaan'][$i];
            //$data['tahapan_pelaksanaan'][$i]->tanggal_mulai = date_format(date_create($row->tanggal_mulai),"d F Y H:i");
            $tahapan['tanggal_mulai'] = strftime("%d %B %Y %H:%M", strtotime($tahapan['tanggal_mulai']));
            $tahapan['tanggal_selesai'] = strftime("%d %B %Y %H:%M", strtotime($tahapan['tanggal_selesai']));
        }

        $data['tahapan_aktif'] = $this->Mconfig->tcg_tahapan_pelaksanaan_aktif();
        $data['pengumuman'] = $this->Mconfig->tcg_pengumuman();
		$data['petunjuk_pelaksanaan'] = $this->Mconfig->tcg_petunjuk_pelaksanaan();

        $this->smarty->render("ppdb/home/home.tpl", $data);
	}
	
	function carisiswa()
	{
        $nama = $_POST["peserta_didik_id"] ?? null; 
        if (empty($nama)) {
            $nama = $_GET["peserta_didik_id"] ?? null; 
        }

		$data['daftar'] = $this->Mhome->carisiswa();
		view('home/daftarpencarian',$data);
	}

	function notauthorized()
	{
		$data['home'] = $_GET["home"] ?? null; 
		$data['page'] = 'notauthorized';
		view('home/notauthorized',$data);
	}
	
	function detailpendaftaran()
	{
		$peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 

        $msiswa = new Mprofilsiswa();
		$data['profilsiswa'] = $msiswa->tcg_profilsiswa($peserta_didik_id);
		$data['daftarpendaftaran'] = $msiswa->tcg_daftarpendaftaran($peserta_didik_id);

        //berkas dok
        $kelengkapan = array();
        foreach($data['daftarpendaftaran'] as $p) {
            $pendaftaran_id = $p['pendaftaran_id'];
            $berkas = $msiswa->tcg_kelengkapanpendaftaran($pendaftaran_id);
            $kelengkapan[$pendaftaran_id] = $berkas;
        }
        $data['kelengkapan'] = $kelengkapan;

        // $berkasfisik = array();
        // foreach($data['daftarpendaftaran'] as $p) {
        //     $pendaftaran_id = $p['pendaftaran_id'];
        //     $berkas = $msiswa->tcg_kelengkapanpendaftaran_berkasfisik($pendaftaran_id);
        //     $berkasfisik[$pendaftaran_id] = $berkas;
        // }
        // $data['berkasfisik'] = $berkasfisik;        

        //skoring
        $nilaiskoring = array();
        foreach($data['daftarpendaftaran'] as $p) {
            $pendaftaran_id = $p['pendaftaran_id'];
            $skoring = $msiswa->tcg_nilaiskoring($pendaftaran_id);
            $nilaiskoring[$pendaftaran_id] = $skoring;
        }
        $data['nilaiskoring'] = $nilaiskoring;        

        //kelengkapan data
		$data['statusprofil'] = $msiswa->tcg_profilsiswa_status($peserta_didik_id);
        $kelengkapan_data = 1;
        if ($data['statusprofil']['konfirmasi_profil'] != 1 || $data['statusprofil']['konfirmasi_lokasi'] != 1 
                || $data['statusprofil']['konfirmasi_nilai'] != 1 || $data['statusprofil']['konfirmasi_prestasi'] != 1 
                || $data['statusprofil']['konfirmasi_afirmasi'] != 1 || $data['statusprofil']['konfirmasi_inklusi'] != 1 
                || empty($data['statusprofil']['nomor_kontak']) 
                || empty($data['statusprofil']['surat_pernyataan_kebenaran_dokumen'])) {
            $kelengkapan_data = 0;
        }		
        $data['kelengkapan_data'] = $kelengkapan_data;

        //batasan perubahan
		$data['batasanperubahan'] = $this->Mconfig->tcg_batasanperubahan();
		$data['batasansiswa'] = $msiswa->tcg_batasansiswa($peserta_didik_id);

        //allow edit?
        $data['tutup_akses'] = $this->session->get("tutup_akses");
		$data['cek_waktupendaftaran'] = $this->Mconfig->tcg_cek_waktupendaftaran();
		$data['cek_waktusosialisasi'] = $this->Mconfig->tcg_cek_waktusosialisasi();

        //bukan punya si login
        if ($this->peserta_didik_id != $peserta_didik_id) {
            $data['tutup_akses'] = 1;
        }

        //content template
        $data['content_template'] = '../siswa/daftarpendaftaran.tpl';
        $data['js_template'] = '../sekolah/_peringkat.tpl';

		$data['page_title'] = 'Peringkat';
 
        $this->smarty->render('ppdb/home/ppdbhome.tpl', $data);	

		// $data['page'] = 'detailpendaftaran';
        // $data['page_title'] = 'Detail Pendaftaran';
		// $this->smarty->render('ppdb/home/detailpendaftaran.tpl',$data);
	}

	function peringkat() {
		$sekolah_id = $this->request->getPostGet("sekolah_id");
        $msekolah = new \App\Models\Ppdb\Sekolah\Mprofilsekolah();

        $data['profilsekolah'] = $msekolah->tcg_profilsekolah($sekolah_id);
		$data['daftarpenerapan'] = $msekolah->tcg_daftarpenerapan($sekolah_id);
        
        //pendaftar per penerapan
        $pendaftar = array();
        foreach($data['daftarpenerapan'] as $p) {
            $penerapan_id = $p['penerapan_id'];
            $penerapan = $msekolah->tcg_pendaftaran_penerapanid($sekolah_id, $penerapan_id);
            for($i=0; $i<count($penerapan); $i++) {
                $penerapan[$i]['idx'] = $i+1;
                //mask nisn
                $penerapan[$i]['nisn'] = substr($penerapan[$i]['nisn'],0,6) .str_repeat("*", 4);
                $penerapan[$i]['skor'] = round($penerapan[$i]['skor'],2);
            }
            //var_dump($penerapan);
            $pendaftar[$penerapan_id] = $penerapan;
        }
        $data['pendaftar'] = $pendaftar;
        //exit;

        //semua pendaftar
        $data['show_all_pendaftar'] = 1;
		$semuapendaftar = $msekolah->tcg_daftarpendaftaran($sekolah_id);
        for($i=0; $i<count($semuapendaftar); $i++) {
            $semuapendaftar[$i]['idx'] = $i+1;
            //mask nisn
            $semuapendaftar[$i]['nisn'] = substr($semuapendaftar[$i]['nisn'],0,6) .str_repeat("*", 4);
        }
        $data['semuapendaftar'] = $semuapendaftar;

		$data['action'] = 'peringkat';
		$data['sekolah_id'] = $sekolah_id;
		
		//terakhir kali peringkat diproses
		$data['last_execution_date'] = '';
		$data['next_execution_date'] = '';
        $mhome = new Mhome();
		$job = $mhome->tcg_job_peringkatpendaftaran()->getRowArray();
		if ($job != null) {
			$data['last_execution_date'] = $job['last_execution_end'];
			$data['next_execution_date'] = $job['next_execution'];
        }
		
		//nggak perlu tampilin profil sekolah untuk admin sekolah
		if ($this->is_sekolah && $sekolah_id == $this->session->get('sekolah_id'))
			$data['show_profile_sekolah'] = 0;
		else
			$data['show_profile_sekolah'] = 1;

		$data['cek_waktupendaftaran'] = $this->Mconfig->tcg_cek_waktupendaftaran();

		$data['inklusi']=0;
        if ($data['profilsekolah'] != null) {
            $data['inklusi']=$data['profilsekolah']['inklusi'];
        }

        $data['profilsekolah'] = $msekolah->tcg_profilsekolah($sekolah_id);

        //content template
        $data['content_template'] = '../sekolah/peringkat.tpl';
        $data['js_template'] = '../sekolah/_peringkat.tpl';

		$data['page_title'] = 'Peringkat';
 
        $this->smarty->render('ppdb/home/ppdbhome.tpl', $data);	
    }

	function peringkatfinal() {
		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id) && $this->is_sekolah) {
			$sekolah_id=$this->session->get('sekolah_id');
        }

        $msekolah = new Mprofilsekolah();
        $data['profilsekolah'] = $msekolah->tcg_profilsekolah($sekolah_id);
		$data['daftarpenerapan'] = $msekolah->tcg_daftarpenerapan($sekolah_id);
        
        //pendaftar per penerapan
        $pendaftar = array();
        foreach($data['daftarpenerapan'] as $p) {
            $penerapan_id = $p['penerapan_id'];
            $penerapan = $msekolah->tcg_pendaftaran_penerapanid($sekolah_id, $penerapan_id);
            for($i=0; $i<count($penerapan); $i++) {
                $penerapan[$i]['idx'] = $i+1;
                //mask nisn
                $penerapan[$i]['nisn'] = substr($penerapan[$i]['nisn'],0,6) .str_repeat("*", 4);
                $penerapan[$i]['skor'] = round($penerapan[$i]['skor'],2);
            }
            $pendaftar[$penerapan_id] = $penerapan;
        }
        $data['pendaftar'] = $pendaftar;

        //semua pendaftar
        $data['show_all_pendaftar'] = 1;
		$semuapendaftar = $msekolah->tcg_daftarpendaftaran($sekolah_id);
        for($i=0; $i<count($semuapendaftar); $i++) {
            $semuapendaftar[$i]['idx'] = $i+1;
            //mask nisn
            $semuapendaftar[$i]['nisn'] = substr($semuapendaftar[$i]['nisn'],0,6) .str_repeat("*", 4);
        }
        $data['semuapendaftar'] = $semuapendaftar;

		$data['action'] = 'peringkat';
		$data['sekolah_id'] = $sekolah_id;
		
		//nggak perlu tampilin profil sekolah untuk admin sekolah
		if ($this->is_sekolah && $sekolah_id == $this->session->get('sekolah_id'))
			$data['show_profile_sekolah'] = 0;
		else
			$data['show_profile_sekolah'] = 1;

		$data['cek_waktupendaftaran'] = $this->Mconfig->tcg_cek_waktupendaftaran();

		$data['inklusi']=0;
        if ($data['profilsekolah'] != null) {
            $data['inklusi']=$data['profilsekolah']['inklusi'];
        }

        $data['use_datatable'] = 1;
        $data['final_ranking'] = 1;

		$data['page'] = "peringkat";
        $data["page_title"] = "Peringkat Pendaftaran (Final)";
		$this->smarty->render('ppdb/home/peringkat.tpl',$data);
	}    

	function rapormutu() {
        $json = $_GET["json"] ?? null; 
        if (!empty($json)) {
            return $this->rapormutu_json();
        }

        $data['use_datatable'] = 1;

		$data['page'] = "rapormutu";
		$data['page_title'] = "Rapor Mutu";
		$this->smarty->render('ppdb/home/rapormutu.tpl',$data);

	}

	protected function rapormutu_json() {
		$action = $_POST["action"] ?? null; 
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Mhome->tcg_rapor_mutu($this->tahun_ajaran_id); 
			echo json_encode($data);	
		}
	}    

	function login() {
		if(!empty($this->user_id)) {
			if($this->is_siswa){
				return redirect()->to('siswa/profil');
			}else if($this->is_sekolah){
				return redirect()->to('sekolah/beranda');
			}else if($this->is_dinas){
				return redirect()->to('dinas/beranda');
			}else if($this->is_dapodik){
				return redirect()->to('dapodik/beranda');
			}else{
				return redirect()->to('home/logout');
			}
			return;
		}

		$this->showlogin();
    }

	protected function showlogin() {		
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null; 
		if (empty($tahun_ajaran_id)) {
			$tahun_ajaran_id = $this->tahun_ajaran_id;
		}
		
		$kode_wilayah_aktif = $_GET["kode_wilayah"] ?? null; 
		if (empty($kode_wilayah_aktif)) {
			$kode_wilayah_aktif = $this->kode_wilayah;
		}
		
		$bentuk_sekolah_aktif = "";
		
		$sessiondata = array(
			'tahun_ajaran_aktif'=>$tahun_ajaran_id,
			'kode_wilayah_aktif'=>$kode_wilayah_aktif,
			'bentuk_sekolah_aktif'=>$bentuk_sekolah_aktif
		);	
		$this->session->set($sessiondata);

		$data['cek_captcha'] = $this->Mconfig->tcg_cek_captcha();
		$data['cek_registrasi'] = $this->Mconfig->tcg_cek_wakturegistrasi();

		$data['tahapan_aktif'] = $this->Mconfig->tcg_tahapan_pelaksanaan_aktif();
        foreach($data['tahapan_aktif'] as $tahapan) {
            if ($tahapan->tahapan_id == 0 || $tahapan->tahapan_id == 99) {
                $data['cek_registrasi'] = 0;
                break;
            }
        }
		$data['pengumuman'] = $this->Mconfig->tcg_pengumuman();

        if ($data['cek_captcha']) {
            $sitekey="6LfUN-oUAAAAAAEiaEPyE-S-d3NRbzXZVoNo51-x";
            if(strpos(base_url(), 'localhost')) {
                $sitekey="6LdDOOoUAAAAABvtPcoIZ4RHTm545Wb9lgD8j2Ab";
            }
            $data['captcha_sitekey'] = $sitekey;
        }

		$this->smarty->render('ppdb/home/login.tpl',$data);
	}

	function dologin() {
        //validation
        $validation = \Config\Services::validation();;
        $validation->setRule('username','Username','trim|required|max_length[100]');
		$validation->setRule('password','Password','trim');

		if($validation->run($_POST) == false) {
			$this->session->setFlashdata('error', 'Periksa kembali Username dan Password.');
			return redirect()->to("akun/login");
			return;
		}

        //session data
		$tahun_ajaran_id = $_POST["tahun_ajaran"] ?? null; 
		if (empty($tahun_ajaran_id)) {
			$tahun_ajaran_id = $this->tahun_ajaran_id;
		}

		$kode_wilayah_aktif = $_GET["kode_wilayah"] ?? null; 
		if (empty($kode_wilayah_aktif)) {
			$kode_wilayah_aktif = $this->kode_wilayah;
		}
		
		$bentuk_sekolah_aktif = "";
		
		$sessiondata = array(
			'tahun_ajaran_aktif'=>$tahun_ajaran_id,
			'kode_wilayah_aktif'=>$kode_wilayah_aktif,
			'bentuk_sekolah_aktif'=>$bentuk_sekolah_aktif
		);
		$this->session->set($sessiondata);

        //login
		$username = $_POST["username"] ?? null; 
		$password = $_POST["password"] ?? null; 
		$captcha = $_POST["g-recaptcha-response"] ?? null; 
	
		$cek_captcha = $this->Mconfig->tcg_cek_captcha();
		if($cek_captcha == 1 && $this->check_recaptcha_v2($captcha) == 0){
			$this->session->setFlashdata('error', "Kode Captcha yang Anda masukkan salah.");
			return redirect()->to("akun/login");
			return;
		}

		$detailuser = $this->Mhome->tcg_detailuser($username);
		if(empty($detailuser)){
			$this->session->setFlashdata('error', "Username yang dimasukkan tidak terdaftar.");
			return redirect()->to("akun/login");
			return;
		}

        if ($detailuser['tutup_akses'] == 1) {
			$this->session->setFlashdata('error', "Mohon maaf akses anda untuk sementara ditutup.");
			return redirect()->to("home/login");
			return;
		}

		if ($detailuser['approval'] != 1) {
			$this->session->setFlashdata('error', "Pengajuan akun anda belum disetujui oleh Admin");
			return redirect()->to("home/login");
			return;
		}

		$tahapan = $this->Mconfig->tcg_tahapan_pelaksanaan_aktif();
		if (($tahapan == 0 || $tahapan == 99) && $detailuser['peran_id'] != ROLEID_DINAS) {
			$this->session->setFlashdata('error', "Pendaftaran PPDB Online belum dibuka.");
			return redirect()->to("home/login");
			return;
		}

		if ($detailuser['peran_id'] == ROLEID_SEKOLAH && $this->Mhome->tcg_cek_ikutppdb($detailuser['sekolah_id'], $tahun_ajaran_id) == 0) {
			$this->session->setFlashdata('error', "Sekolah anda tidak ikut PPDBOnline tahun ajaran $tahun_ajaran_id.");
			return redirect()->to("home/login");
			return;
		}

        if ($this->Mhome->tcg_login($username, $password) == 0) {
			$this->session->setFlashdata('error', "Username dan Password tidak terdaftar.");
			return redirect()->to("home/login");
			return;
		}

		$luar_daerah = 0;
		if (substr($detailuser['kode_wilayah'],0,4) != substr($this->kode_wilayah,0,4)) {
			$luar_daerah = 1;
        }

		$bentuk_sekolah_aktif = "SMP";
		if ($detailuser['bentuk'] == "SD" || $detailuser['bentuk'] == "MI") {
			$bentuk_sekolah_aktif = "SMP";
		}

        $detailuser['is_logged_in'] = true;
        $detailuser['luar_daerah'] = $luar_daerah;
        $detailuser['tahun_ajaran_aktif'] = $tahun_ajaran_id;
        $detailuser['kode_wilayah_aktif'] = $kode_wilayah_aktif;
        $detailuser['bentuk_sekolah_aktif'] = $bentuk_sekolah_aktif;
   
		$this->session->set($detailuser);

        $this->peran_id = $detailuser['peran_id'];
        $this->user_id = $detailuser['pengguna_id'];

        $this->is_siswa = ($this->peran_id == ROLEID_SISWA);
        $this->is_sekolah = ($this->peran_id == ROLEID_SEKOLAH);
        $this->is_dinas = ($this->peran_id == ROLEID_DINAS);
        $this->is_dapodik = ($this->peran_id == ROLEID_DAPODIK);

        if($this->is_siswa){
            return redirect()->to('siswa/profil');
        }else if($this->is_sekolah){
            return redirect()->to('sekolah/beranda');
        }else if($this->is_dinas){
            return redirect()->to('dinas/beranda');
        }else if($this->is_dapodik){
            return redirect()->to('dapodik/beranda');
        }else{
            $this->session->setFlashdata('error', "Username dan Password tidak terdaftar.");
			return redirect()->to("akun/login");
			return;
        }

	}    

	protected function check_recaptcha_v2($captcha) {

		if (empty($captcha))
			return false;
			
		//this is the proper way of checking it
		if(strpos(base_url(), 'localhost')) {
			//localhost
			$secret = '6LdDOOoUAAAAADGh9tqM6i4Yni5TtX1oVJbdkXey';
		} else {
			//ppdb.disdik.kebumenkab.go.id
			$secret = '6LfUN-oUAAAAACjqW4BAD7WpRiJgIxqEHwCHbm9L';
		}

		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array(
			'secret' => $secret,
			'response' => $captcha
		);
		$options = array(
		    "ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
			'http' => array (
				'method' => 'POST',
				'content' => http_build_query($data),
				'header' => 'Content-Type: application/x-www-form-urlencoded',
			)
		);

		$context  = stream_context_create($options);
		$verify = file_get_contents($url, false, $context);
		$captcha_success=json_decode($verify);
	
		if ($captcha_success->success==false) {
			return 0;
		} else if ($captcha_success->success==true) {
			return 1;
		}
		
	}    

    function logout() {
		$this->session->destroy();
		return redirect()->to('home');
    }

    function registrasi() {
		$mdropdown = new \App\Models\Ppdb\Mdropdown();
		$data['cek_registrasi'] = $this->Mconfig->tcg_cek_wakturegistrasi();
        $data['kabupaten'] = $mdropdown->tcg_kabupaten();

        $batasanusia = $this->Mconfig->tcg_batasanusia("SMP");
        if (!empty($batasanusia)) {
            $data['maxtgllahir'] = $batasanusia['maksimal_tanggal_lahir'];
            $data['mintgllahir'] = $batasanusia['minimal_tanggal_lahir'];
        }

        $data['use_leaflet'] = 1;

        $data["page_title"] = "Registrasi";
		$this->smarty->render('ppdb/home/registrasi.tpl',$data);
    }

	function doregistrasi() {   
        $data = array();
		$data['kode_kabupaten_sekolah'] = $_POST["kode_kabupaten_sekolah"] ?? "";
		$data['sekolah_id'] = $_POST["sekolah_id"] ?? "";
		$data['bentuk_sekolah'] = $_POST["bentuk"] ?? "";
		$data['nama_sekolah'] = $_POST["nama_sekolah"] ?? "";

		$data['nik'] = $_POST["nik"] ?? "";
		$data['nisn'] = $_POST["nisn"] ?? "";
		$data['nomor_ujian'] = $_POST["nomor_ujian"] ?? "";
		$data['nama'] = $_POST["nama"] ?? "";
		$data['jenis_kelamin'] = $_POST["jenis_kelamin"] ?? "";
		$data['tempat_lahir'] = $_POST["tempat_lahir"] ?? "";
		$data['tanggal_lahir'] = $_POST["tanggal_lahir"] ?? "";
		$data['nama_ibu_kandung'] = $_POST["nama_ibu_kandung"] ?? "";
		$data['kebutuhan_khusus'] = $_POST["kebutuhan_khusus"] ?? "";
		$data['alamat'] = $_POST["alamat"] ?? "";
		$data['kode_kabupaten'] = $_POST["kode_kabupaten"] ?? "";
		$data['kode_kecamatan'] = $_POST["kode_kecamatan"] ?? "";
		$data['kode_desa'] = $_POST["kode_desa"] ?? "";
		$data['kode_wilayah'] = $_POST["kode_wilayah"] ?? "";
		if(empty($data['kode_wilayah'])){
			$data['kode_wilayah'] = $data['kode_desa'];
		}
		$data['lintang'] = $_POST["lintang"] ?? "";
        $data['bujur'] = $_POST["bujur"] ?? "";

		$data['nomor_kontak'] = $_POST["nomor_kontak"] ?? "";

		//load the entered data again
        do {
            $jumlah = $this->Mhome->tcg_cek_registrasi($data['nama'], $data['jenis_kelamin'], $data['tempat_lahir'], $data['tanggal_lahir'], $data['nama_ibu_kandung']);
			if($jumlah>0){
                $this->session->setFlashdata('error', "Data siswa tersebut sudah ada sebelumnya, silahkan koordinasi dengan Sekolah Tujuan atau Sekolah Tempat Registrasi Akun untuk pengelolaan akun siswa dengan membawa berkas pendukung.");
                break;
			}
		   
            $jumlah = $this->Mhome->tcg_cek_nik($data['nik']);
			if($jumlah>0){
                $this->session->setFlashdata('error', "Nomor NIK invalid / sama dengan siswa lain.");
                break;
			}

            $jumlah = $this->Mhome->tcg_cek_nisn($data['nisn']);
			if($jumlah>0){
                $this->session->setFlashdata('error', "Nomor NISN invalid / sama dengan siswa lain.");
                break;
			}

			if (empty($data['sekolah_id']) && !empty($data['nama_sekolah'])) {
				$npsn_sekolah = "00000000";
				$status_sekolah = 'S';
				//create sekolah id first
				$data['sekolah_id'] = $this->Mhome->tcg_sekolah_baru($data['nama_sekolah'],$data['kode_kabupaten_sekolah'],$data['bentuk_sekolah'],$npsn_sekolah,$status_sekolah);
				if ($data['sekolah_id'] == "") {
                    $this->session->setFlashdata('error', "Tidak berhasil menambahkan data sekolah.");
					break;
				}
			}

            $peserta_didik_id = $this->Mhome->tcg_registrasiuser($data['sekolah_id'], $data['nik'], $data['nisn'], $data['nomor_ujian'], $data['nama'], $data['jenis_kelamin'], 
                                                                    $data['tempat_lahir'], $data['tanggal_lahir'], $data['nama_ibu_kandung'], $data['kebutuhan_khusus'], 
                                                                    $data['alamat'], $data['kode_wilayah'], $data['lintang'], $data['bujur'], $data['nomor_kontak']);
            if ($peserta_didik_id == "") {						
                $this->session->setFlashdata('error', "Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.");
                break;
            }

            $data['info'] = "<div class='alert alert-info'>Registrasi berhasil. Silahkan tunggu pemberitahuan persetujuan akun melalui nomor kontak yang anda berikan. Apabila setelah 1x24 jam anda belum menerima pemberitahuan persetujuan, silahkan menghubungi nomor bantuan yang ada di halaman utama.</div>
            <div class='alert alert-info'>Anda bisa melakukan masuk ke sistem PPDB Online menggunakan nomor NISN/NIK anda. </div>
            <div class='alert alert-info'>Segera masuk dan ganti PIN anda sekarang juga. <br>Gunakan akun berikut untuk masuk ke sistem: <br><ul><li>Nama Pengguna: " .$data['nisn']. "</li><li>PIN: " .$data['nisn']. "</li></ol></div>";

            $data["sukses"] = 1;

        } while (false);

		$mdropdown = new \App\Models\Ppdb\Mdropdown();
		$data['cek_registrasi'] = $this->Mconfig->tcg_cek_wakturegistrasi();
        $data['kabupaten'] = $mdropdown->tcg_kabupaten();

        $batasanusia = $this->Mconfig->tcg_batasanusia("SMP");
        if (!empty($batasanusia)) {
            $data['maxtgllahir'] = $batasanusia['maksimal_tanggal_lahir'];
            $data['mintgllahir'] = $batasanusia['minimal_tanggal_lahir'];
        }

        $data['use_leaflet'] = 1;

        $data["page_title"] = "Registrasi";
		$this->smarty->render('ppdb/home/registrasi.tpl',$data);
	}

	function dropdownsekolah()
	{
        $kode_wilayah = $_POST["kode_wilayah"] ?? null; 
        $bentuk = $_POST["bentuk"] ?? null; 
		$mdropdown = new \App\Models\Ppdb\Mdropdown();
		$data['sekolah'] = $mdropdown->tcg_sekolah($kode_wilayah, $bentuk);
		return view('ppdb/home/_dropdownsekolah',$data);
	}

	function dropdownkecamatan()
	{
        $kode_wilayah = $_POST["kode_wilayah"] ?? null; 
		$mdropdown = new \App\Models\Ppdb\Mdropdown();
		$data['kecamatan'] = $mdropdown->tcg_kecamatan($kode_wilayah);
		return view('ppdb/home/_dropdownkecamatan',$data);
	}

	function dropdowndesa()
	{
        $kode_wilayah = $_POST["kode_wilayah"] ?? null; 
		$mdropdown = new \App\Models\Ppdb\Mdropdown();
		$data['desa'] = $mdropdown->tcg_desa($kode_wilayah);
		return view('ppdb/home/_dropdowndesa',$data);
	}

	function dropdownpadukuhan()
	{
        $kode_wilayah = $_POST["kode_wilayah"] ?? null; 
		$mdropdown = new \App\Models\Ppdb\Mdropdown();
		$data['padukuhan'] = $mdropdown->tcg_padukuhan($kode_wilayah);
		return view('ppdb/home/_dropdownpadukuhan',$data);
	}

	// function tanggalRA()
	// {
	// 	$this->load->model(array('Mconfig'));
	// 	$this->load->view('dropdown/tanggalRA');
	// }
	// function tanggalMI()
	// {
	// 	$this->load->model(array('Mconfig'));
	// 	$this->load->view('dropdown/tanggalMI');
	// }    

    function rekapitulasi()
	{
		$data['daftarsekolah'] = $this->Mhome->tcg_rekapitulasi_sekolah();
			
        $data['use_datatable'] = 1;

		$data['page'] = "rekapitulasi";
        $data['page_title'] = "Rekapitulasi";
		$this->smarty->render('ppdb/home/rekapitulasi.tpl',$data);
	}
}
