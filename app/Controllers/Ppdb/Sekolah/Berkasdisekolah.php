<?php

namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Berkasdisekolah extends PpdbController {

    protected static $ROLE_ID = ROLEID_SEKOLAH;      

    protected $Msekolah;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load model
        $this->Msekolah = new Mprofilsekolah();
    }
    
	function index()
	{
		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		$data['sekolah_id'] = $sekolah_id;
		$data['waktuverifikasi'] = $this->Mconfig->tcg_cek_waktuverifikasi();
	
        $data['use_datatable'] = 1;

        //content template
        $data['content_template'] = 'berkasdisekolah.tpl';

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