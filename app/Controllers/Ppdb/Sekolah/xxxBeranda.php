<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Beranda extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		if($this->session->get('isLogged')==FALSE||$this->session->get('peran_id')!=2) {
			return redirect()->to("akun/login");
		}
	}

	function index()
	{
		$sekolah_id = $this->session->get("sekolah_id");

		$this->load->model('Msekolah');
		$data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
		$data['daftarkuota'] = $this->Msekolah->tcg_daftarkuota();

		$data['page'] = 'beranda';
		view('sekolah/beranda/index',$data);
	}

	function json() {
		// $tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		// if (empty($tahun_ajaran_id))
		// 	$tahun_ajaran_id = $this->tahun_ajaran_id;

		$this->load->model(array('Mdinas','Mdropdown'));
		
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
	
}
?>