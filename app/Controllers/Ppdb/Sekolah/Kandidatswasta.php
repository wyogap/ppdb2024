<?php

namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Kandidatswasta extends PpdbController {

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
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null; 
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;
	

        //Debug
        $data['nama_pengguna'] = "Wahyu Yoga Pratama";
        $data['username'] = "wyogap@gmail.com";
        //END DEBUG

        //content template
        $data['content_template'] = 'kandidatswasta.tpl';

        $data['page_title'] = 'Kandidat Siswa';
        $this->smarty->render('ppdb2/sekolah/ppdbsekolah.tpl', $data);

	}

	function json() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null; 
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$sekolah_id = $this->session->get("sekolah_id");

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Msekolah->tcg_kandidatswasta($sekolah_id, $tahun_ajaran_id)->getResultArray(); 
			echo json_encode($data);	
		}
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}

	}

	
}
?>