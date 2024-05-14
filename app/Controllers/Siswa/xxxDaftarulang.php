<?php

namespace App\Controllers\Siswa;

use App\Controllers\Ppdb\PpdbController;
use App\Libraries\QRCodeLibrary;
use App\Libraries\Uploader;
use App\Models\Ppdb\Admin\Msetting;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Psr\Log\LoggerInterface;

defined('BASEPATH') OR exit('No direct script access allowed');

class Daftarulang extends PpdbController {
    
    protected $Msiswa;

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
        $this->Msiswa = new Mprofilsiswa();
        
        if($this->session->get('is_logged_in')==FALSE || $this->session->get('peran_id')!=ROLEID_SISWA) {
			redirect(site_url() .'auth');
		}
    }

    function index()
	{
		$peserta_didik_id = $this->session->get('pengguna_id');
		$tahun_ajaran_id = $_GET["tahun_ajaran_id"] ?? null; 
		if (empty($tahun_ajaran_id)) {
			$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		}

		do {
			$data['cek_waktudaftarulang'] = $this->Msetting->tcg_cek_waktudaftarulang();
			if ($data['cek_waktudaftarulang'] == 0) {
				$data['waktudaftarulang'] = $this->Msetting->tcg_waktudaftarulang();
				$data['waktupendaftaran'] = $this->Msetting->tcg_waktupendaftaran();
				break;
			}

			$diterima = 0;
			$sekolah_id = "";
			$sekolah = "";
			$peringkat = 9999;
			$pilihan = "";
			$jalur = "";
			$kuota_sekolah = 0;
			$pendaftaran = 0;

			$query = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id);			
			foreach($query->getResult() as $row) {
				if ($row->status_penerimaan_final == 1 || $row->status_penerimaan_final == 3) {
					$diterima = 1;
					$sekolah_id = $row->sekolah_id;
					$sekolah = $row->sekolah;
					$peringkat = $row->peringkat_final;
					$pilihan = $row->jenis_pilihan;
					$jalur = $row->jalur;
					break;
				}
			}
			$pendaftaran = $query->num_rows();

			if ($diterima == 0) {
				$data['pendaftaran'] = $pendaftaran;
				$data['diterima'] = $diterima;
				$data['waktupendaftaran'] = $this->Msetting->tcg_waktupendaftaran();
				$data['waktupendaftaransusulan'] = $this->Msetting->tcg_waktupendaftaransusulan();
				break;
			}

			$data['waktudaftarulang'] = $this->Msetting->tcg_waktudaftarulang();

			$query = $this->Msiswa->tcg_profilsekolah($sekolah_id);
			foreach($query->getResult() as $row) {
				$kuota_sekolah = $row->kuota_total;
			}

			$data['dokumenpendukung'] = $this->Msiswa->tcg_dokumen_pendukung($peserta_didik_id);
			foreach($data['dokumenpendukung']->getResult() as $row) {
				$row->web_path = base_url(). $row->web_path;
				$row->thumbnail_path = base_url(). $row->thumbnail_path;
			}

			$data['pendaftaran'] = $pendaftaran;
			$data['diterima'] = $diterima;
			$data['sekolah_id'] = $sekolah_id;
			$data['sekolah'] = $sekolah;
			$data['peringkat'] = $peringkat;
			$data['pilihan'] = $pilihan;
			$data['jalur'] = $jalur;
			$data['kuota_sekolah'] = $kuota_sekolah;

			$data['punya_nilai_un'] = 0;
			$data['punya_prestasi'] = 0;
			$data['punya_kip'] = 0;
			$data['masuk_bdt'] = 0;
			$data['kebutuhan_khusus'] = "Tidak ada";
	
			$query = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);
			foreach($query->getResult() as $row) {
				$data['punya_nilai_un'] = $row->punya_nilai_un;
				$data['punya_prestasi'] = $row->punya_prestasi;
				$data['punya_kip'] = $row->punya_kip;
				$data['masuk_bdt'] = $row->masuk_bdt;
				$data['kebutuhan_khusus'] = $row->kebutuhan_khusus;
			}

		}
        while (false);
   
		$data['page'] = 'siswa-daftarulang';
		view('siswa/daftarulang/index',$data);    
    }

	function buktipendaftaran() {

		$peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 
		if (empty($peserta_didik_id)) {
			$peserta_didik_id = $this->session->get("pengguna_id");
		}
	
		$tahun_ajaran_id = $_GET["tahun_ajaran_id"] ?? null; 
		if (empty($tahun_ajaran_id)) {
			$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
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
 
		$data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa_daftarulang($peserta_didik_id);

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
		$data['pendaftaran'] = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id);
		$data['dokumenpendukung'] = $this->Msiswa->tcg_kelengkapanberkas($peserta_didik_id);
		
		$data['tahun_ajaran_aktif'] = $this->session->get('tahun_ajaran_aktif');

        $view = \Config\Services::renderer();
        $html = $view->render('sekolah/daftarulang/buktipendaftaran',$data);
		
		$dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("BuktiPendaftaran.pdf", array("Attachment"=>0));
	}

}
?>