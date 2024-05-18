<?php

namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Libraries\QRCodeLibrary;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Psr\Log\LoggerInterface;

class Daftarulang extends PpdbController {

    protected $Msekolah;

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

        //load library
        //$this->smarty = new SmartyLibrary();

        //load model
        $this->Msekolah = new Mprofilsekolah();
        
        // if($this->session->get('is_logged_in')==FALSE || $this->session->get('peran_id')!=ROLEID_SEKOLAH) {
		// 	redirect(site_url() .'auth');
		// }
    }

	function index()
	{
		$sekolah_id = $this->session->get('sekolah_id');

		do {
            $pendaftaran_id = $_GET["pendaftaran_id"] ?? null;
            $peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 
            if (!empty($pendaftaran_id) || !empty($peserta_didik_id)) {
                return "";
            }
    
			$data['daftarpenerapan'] = $this->Msekolah->tcg_daftarpenerapan($sekolah_id)->getResultArray();
            $pendaftarditerima = array();
            foreach($data['daftarpenerapan'] as $row) {
                $penerapan_id = $row['penerapan_id'];
                $daftarpendaftar = $this->Msekolah->tcg_pendaftarditerima($sekolah_id, $penerapan_id)->getResultArray();
                $pendaftarditerima[$penerapan_id] = $daftarpendaftar;
            }
            $data["pendaftarditerima"] = $pendaftarditerima;

			$data['waktudaftarulang'] = $this->Msetting->tcg_cek_waktudaftarulang();
			$data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
			// $data['daftarputaran'] = $this->Msetting->tcg_putaran();
			$data['daftartahunajaran'] = $this->Msetting->tcg_tahunajaran();
	
			$inklusi=0;
			foreach($data['profilsekolah']->getResult() as $row) {
				$inklusi=$row->inklusi;
			}
			$data['inklusi'] = $inklusi;
			
			$data['tahun_ajaran_aktif'] = $this->session->get('tahun_ajaran_aktif');
			$data['putaran_aktif'] = $this->session->get('putaran_aktif');
	
			$data['info'] = $this->session->getFlashdata('info');
		}
        while (false);

        //content template
        $data['content_template'] = 'daftarulang.tpl';

        $data['page_title'] = 'Daftar Ulang';
        $this->smarty->render('ppdb/sekolah/ppdbsekolah.tpl', $data);

    }

	// function json() {
	// 	$riwayat = $_GET["data"] ?? null; (("riwayat");
	// 	if ($riwayat == 1) {
	// 		$this->riwayat();
	// 		return;
	// 	}

	// 	$lengkap = $_GET["data"] ?? null; (("lengkap");
	// 	if ($lengkap == 1) {
	// 		$this->sudahdiverifikasi();
	// 		return;
	// 	}
	// 	else if ($lengkap == 0) {
	// 		$this->belumdiverifikasi();
	// 		return;
	// 	}
	// }

    function siswa() 
	{
		$pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 
		$peserta_didik_id = $this->Msekolah->tcg_pesertadidikid_pendaftaran($pendaftaran_id);

		$sekolah_id = $this->session->get("sekolah_id");

		$status_daftar_ulang = 0;
		$tanggal_daftar_ulang = "";
		$status_penerimaan = 0;

		$data['pendaftaran'] = $this->Msekolah->tcg_detailpendaftaran($peserta_didik_id, $pendaftaran_id);
		foreach($data['pendaftaran']->getResult() as $row) {
			$status_daftar_ulang = $row->status_daftar_ulang;
			$tanggal_daftar_ulang = $row->tanggal_daftar_ulang;
			$status_penerimaan = $row->status_penerimaan_final;
		}

		if ($status_penerimaan != 1 && $status_penerimaan != 3) {
			view('home/notauthorized',$data);
			return;
		}

		$data['status_daftar_ulang'] = $status_daftar_ulang;
		$data['tanggal_daftar_ulang'] = $tanggal_daftar_ulang;

		$data['pendaftaran_id'] = $pendaftaran_id;
		$data['peserta_didik_id'] = $peserta_didik_id;

		$data['settingdaftarulang'] = $this->Msetting->tcg_waktudaftarulang();

		$data['punya_nilai_un'] = 0;
		$data['punya_prestasi'] = 0;
		$data['punya_kip'] = 0;
		$data['masuk_bdt'] = 0;
		$data['kebutuhan_khusus'] = "Tidak ada";
		$data['lokasi_berkas'] = "";

		$data['profilsiswa'] = $this->Msekolah->tcg_profilsiswa_daftarulang($peserta_didik_id);
		foreach($data['profilsiswa']->getResult() as $row) {
			$data['punya_nilai_un'] = $row->punya_nilai_un;
			$data['punya_prestasi'] = $row->punya_prestasi;
			$data['punya_kip'] = $row->punya_kip;
			$data['masuk_bdt'] = $row->masuk_bdt;
			$data['kebutuhan_khusus'] = $row->kebutuhan_khusus;
			$data['lokasi_berkas'] = $row->lokasi_berkas;
		}
	
		$data['dokumenpendukung'] = $this->Msekolah->tcg_dokumen_pendukung($peserta_didik_id);
		foreach($data['dokumenpendukung']->getResult() as $row) {
			$row->path= base_url(). $row->path;
			$row->web_path= base_url(). $row->web_path;
			$row->thumbnail_path = base_url(). $row->thumbnail_path;
		}

		// foreach($data['profilsiswa']->getResult() as $row) {
		// 	$data['punya_nilai_un'] = $row->punya_nilai_un;
		// 	$data['punya_prestasi'] = $row->punya_prestasi;
		// 	$data['punya_kip'] = $row->punya_kip;
		// 	$data['masuk_bdt'] = $row->masuk_bdt;
		// 	$data['kebutuhan_khusus'] = $row->kebutuhan_khusus;
		// }
		
		$data['info'] = $this->session->getFlashdata('info');
		view('sekolah/daftarulangsiswa/index',$data);
	}

	function prosesdaftarulang()
	{
		$pengguna_id = $this->session->get('user_id');
		$sekolah_id = $this->session->get('sekolah_id');
		$peran_id = $this->session->get('peran_id');
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$peserta_didik_id = $_POST["peserta_didik_id"] ?? null; 
		$pendaftaran_id = $_POST["pendaftaran_id"] ?? null; 

		//TODO: only can verify within the specified timeframe
		$data['settingdaftarulang'] = $this->Msetting->tcg_waktudaftarulang();

		$kelengkapan_berkas = 1;
		$dokumen_id = 0;

		//verifikasi dokumen pendukung satu-satu
		foreach ($_POST as $key => $value) {
			if(substr($key,0,6)=="radio_"){
				if (empty($value)) {
					//ignore
					continue;
				}

				$dokumen_id = str_replace('radio_','',$key);

				//tidak ada perubahan
                $val = $_POST["radio_".$dokumen_id] ?? null; 
                $orig_val = $_POST["orig_radio_".$dokumen_id] ?? null; 
				if ($val == $orig_val) {
					if ($value != 1) {
						$kelengkapan_berkas = 2;
					}
					continue;
				}

				//verifikasi dokumen pendukung
				$this->Msekolah->tcg_daftarulang_dokumenpendukung($peserta_didik_id,$dokumen_id,$value,$pengguna_id);

				if ($value != 1) {
					$kelengkapan_berkas = 2;
				}
			}
		}

		// if ($kelengkapan_berkas == 1) {
		// 	$this->Msiswa->tcg_ubah_daftarulang($pendaftaran_id,$kelengkapan_berkas,$pengguna_id);
		// }
		$this->Msekolah->tcg_ubah_daftarulang($pendaftaran_id,$kelengkapan_berkas,$pengguna_id);

		//update lokasi berkas
		$this->Msekolah->tcg_ubah_lokasiberkas($peserta_didik_id,$sekolah_id);

		$this->session->setFlashdata('info', "<div class='alert alert-info alert-dismissable'>Data daftar ulang telah berhasil disimpan.</div>");
		return redirect()->to("sekolah/daftarulang/siswa?pendaftaran_id=$pendaftaran_id");
	}

	function buktipendaftaran() {

		$peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 
		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("user_id");
		}
	
		$tahun_ajaran_id = $_GET["tahun_ajaran_id"] ?? null; 
		if (empty($tahun_ajaran_id)) {
			$tahun_ajaran_id = $this->tahun_ajaran_id;
		}

        $qrcode = new QRCodeLibrary();
		$config['cacheable'] = true; //boolean, the default is true
        // $config['cachedir'] = './qrcode/'; //string, the default is application/cache/
        // $config['errorlog'] = './qrcode/'; //string, the default is application/logs/
        // $config['imagedir'] = './qrcode/images/'; //direktori penyimpanan qr code
        $config['quality'] = true; //boolean, the default is true
        $config['size'] = '1024'; //interger, the default is 1024
        $config['black'] = array(224,255,255); // array, default is array(255,255,255)
        $config['white'] = array(70,130,180); // array, default is array(0,0,0)
        $qrcode->initialize($config);

        $data['profilsiswa'] = $this->Msekolah->tcg_profilsiswa_daftarulang($peserta_didik_id);

		$username = "";
		$peran_id = 1;
		$nisn = "";
		foreach($data['profilsiswa']->getResult() as $row) {
			$nisn = $row->nisn;
			$username = $row->username;
			$peran_id = 1;
		}

		$params['data'] = $peserta_didik_id.",".$username.",".$peran_id.",".$nisn; //data yang akan di jadikan QR CODE
        $params['level'] = 'M'; //H=High
        $params['size'] = 10;
        $params['savename'] = $peserta_didik_id.'.png';
        $qrcode->generate($params); // fungsi untuk generate QR CODE
		
		$data['peserta_didik_id'] = $peserta_didik_id;
		$data['pendaftaran'] = $this->Msekolah->tcg_daftarpendaftaran($peserta_didik_id, $tahun_ajaran_id);
		$data['dokumenpendukung'] = $this->Msekolah->tcg_dokumen_pendukung($peserta_didik_id);
	
		$data['tahun_ajaran_aktif'] = $this->session->get('tahun_ajaran_aktif');

		$data['punya_nilai_un'] = 0;
		$data['punya_prestasi'] = 0;
		$data['punya_kip'] = 0;
		$data['masuk_bdt'] = 0;
		$data['kebutuhan_khusus'] = "Tidak ada";
		$data['lokasi_berkas'] = "";

		foreach($data['profilsiswa']->getResult() as $row) {
			$data['punya_nilai_un'] = $row->punya_nilai_un;
			$data['punya_prestasi'] = $row->punya_prestasi;
			$data['punya_kip'] = $row->punya_kip;
			$data['masuk_bdt'] = $row->masuk_bdt;
			$data['kebutuhan_khusus'] = $row->kebutuhan_khusus;
			$data['lokasi_berkas'] = $row->lokasi_berkas;
		}

        $view = \Config\Services::renderer();
        $html = $view->render('sekolah/daftarulang/buktidaftarulang',$data);
		
		$dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("BuktiDaftarUlang.pdf", array("Attachment"=>0));
	}

}
?>