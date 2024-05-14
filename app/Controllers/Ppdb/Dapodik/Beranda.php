<?php

namespace App\Controllers\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends PpdbController {

    protected $Msekolah;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        //$this->smarty = new SmartyLibrary();

        //load model
        $this->Msekolah = new Mprofilsekolah();
        
        if($this->session->get('is_logged_in')==FALSE || $this->session->get('peran_id')!=ROLEID_DAPODIK) {
			redirect(site_url() .'auth');
		}
    }
    
    // public function __construct()
	// {
	// 	parent::__construct();
	// 	if($this->session->get('isLogged')==FALSE||$this->session->get('peran_id')!=5) {
	// 		return redirect()->to("akun/login");
	// 	}
	// }

	function index()
	{
		// $data['daftarpenerapan'] = $this->Mdinas->daftarpenerapan();
		// $data['dashboardwilayah'] = $this->Mdinas->tcg_dashboardwilayah();
		// $data['dashboardpenerapan'] = $this->Mdinas->dashboardpenerapan();
		// $data['dashboardline'] = $this->Mdinas->dashboardline();

        $data['info'] = $this->session->getFlashdata('info');
        
        $data['page'] = "beranda";
		view('dapodik/beranda/index',$data);
	}

	function daftarsiswa() {
		$sekolah_id = $this->session->get('sekolah_id');
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Msekolah->tcg_daftar_siswa($sekolah_id, $tahun_ajaran_id)->getResultArray(); 
			echo json_encode($data);	
		}

	}

	function json() {
		
		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}
		else if ($action=='edit'){
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
        }
        else if ($action=='remove') {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
        }
        else if ($action=='create') {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
        }
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}

	}
	
	// function pendaftaran() {
	// 	$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
	// 	if (empty($tahun_ajaran_id)) {
	// 		$tahun_ajaran_id = $this->tahun_ajaran_id;
	// 	}

	// 	$this->load->model(array('Mdinas','Msetting'));
	// 	$daftar = $this->Mdinas->tcg_daftar_pendaftaran($tahun_ajaran_id);

	// 	//manual echo json file to avoid memory exhausted
	// 	echo '{"data":[';
	// 	$first = true;
	// 	while ($row = $daftar->unbuffered_row())
	// 	{
	// 		if ($first) {
	// 			echo json_encode($row);
	// 			$first = false;
	// 		} else {
	// 			echo ",". json_encode($row);
	// 		}
	// 	}
	// 	echo ']}';
	// }
}
?>