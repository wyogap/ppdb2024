<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Scheduler extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		if($this->session->get('isLogged')==FALSE||$this->session->get('peran_id')!=4) {
			return redirect()->to("akun/login");
		}
	}

	function index()
	{
		$this->load->model(array('Mdinas','Msetting'));

		$data['jobs'] = $this->Mdinas->tcg_schedulers();

		$data['page'] = "konfigurasi-scheduler";
		view('admin/scheduler/index',$data);
	}

	function executejob() {
		$json = $_GET["data"] ?? null; (("json");
		if ($json == 1) {
			$this->ajax_executejob();
			return;
		}

		$peran_id = $this->session->get("peran_id");
		if ($peran_id != 4) {
			view('home/notauthorized');
			return;
		}

		$job_id = $_GET["data"] ?? null; (("id");
		$source = $_GET["data"] ?? null; (("source");

		$this->load->model(array('Mdinas','Msetting'));
		$data['result'] = $this->Mdinas->tcg_executejob($source, $job_id);

		$data['jobs'] = $this->Mdinas->tcg_schedulers();

		view('admin/scheduler/index',$data);
	}

	function ajax_executejob() {
		$peran_id = $this->session->get("peran_id");
		if ($peran_id != 4) {
			//view('home/notauthorized');
			$data['result'] = -1;
			echo json_encode($data);
			return;
		}

		$job_id = $_POST["data"] ?? null; (("id");
		$source = $_POST["data"] ?? null; (("source");

		$this->load->model(array('Mdinas','Msetting'));
		$data['result'] = $this->Mdinas->tcg_executejob($source, $job_id);

		if ($data['result'] > 0) {
			$data['data'] = $this->Mdinas->tcg_view_job($source, $job_id)->getResultArray();
		}
		
		echo json_encode($data);	
	}

	
}
?>