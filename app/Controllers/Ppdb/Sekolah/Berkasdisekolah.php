<?php

namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Berkasdisekolah extends PpdbController {

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
    
    // public function __construct()
	// {
	// 	parent::__construct();
	// 	if($this->session->get('isLogged')==FALSE||$this->session->get('peran_id')!=2) {
	// 		return redirect()->to("akun/login");
	// 	}
	// }

	function index()
	{
		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		$data['sekolah_id'] = $sekolah_id;
		
		$data['waktuverifikasi'] = $this->Msetting->tcg_cek_waktuverifikasi();

        //Debug
        $data['nama_pengguna'] = "Wahyu Yoga Pratama";
        $data['username'] = "wyogap@gmail.com";
        //END DEBUG

        //content template
        $data['content_template'] = 'beranda.tpl';

        $data['page_title'] = 'Berkas Di Sekolah';
        $this->smarty->render('ppdb/sekolah/ppdbsekolah.tpl', $data);
	}

	function json() {
		// $tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		// if (empty($tahun_ajaran_id))
		// 	$tahun_ajaran_id = $this->tahun_ajaran_id;

		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		$action = $_POST["action"] ?? null; 
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Msekolah->tcg_berkasdisekolah($sekolah_id)->getResultArray(); 
			echo json_encode($data);	
		}
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}

	}

	
}
?>