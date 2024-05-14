<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		if($this->session->get('isLogged')==FALSE||$this->session->get('peran_id')!=4) {
			return redirect()->to("akun/login");
		}
	}

	function index()
	{
		$this->load->model('Mdinas');

		$data['daftarpenerapan'] = $this->Mdinas->tcg_daftarpenerapan();
		$data['dashboardwilayah'] = $this->Mdinas->tcg_dashboardwilayah();
		$data['dashboardpenerapan'] = $this->Mdinas->dashboardpenerapan();
		$data['dashboardline'] = $this->Mdinas->dashboardline();

		$data['page'] = "dashboard";
		view('admin/beranda/index',$data);
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
	
	function pendaftaran() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id)) {
			$tahun_ajaran_id = $this->tahun_ajaran_id;
		}

		$this->load->model(array('Mdinas','Msetting'));
		$daftar = $this->Mdinas->tcg_daftar_pendaftaran($tahun_ajaran_id);

		//manual echo json file to avoid memory exhausted
		echo '{"data":[';
		$first = true;
		while ($row = $daftar->unbuffered_row())
		{
			if ($first) {
				echo json_encode($row);
				$first = false;
			} else {
				echo ",". json_encode($row);
			}
		}
		echo ']}';
	}
}
?>