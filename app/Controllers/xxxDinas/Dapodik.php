<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dapodik extends MY_Controller {
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

		//terakhir kali data ditarik ke staging
		$last_execution_date = '';
		$next_execution_date = '';
		$job = $this->Mdinas->tcg_pesertadidikstagingjob();
		foreach($job->getResult() as $row):
			$last_execution_date = $row->last_execution_end;
			$next_execution_date = $row->next_execution;
		endforeach;
		
		$data['last_execution_date'] = $last_execution_date;
		$data['next_execution_date'] = $next_execution_date;

		$data['page'] = "pengelolaan-dapodik";
		view('admin/dapodik/index',$data);

	}

	function tabledapodiknolocationsummary() {
		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$this->load->model(array('Mdinas','Msetting'));

			$data['data'] = $this->Mdinas->tcg_dapodik_nolocation_summary()->getResultArray(); 
			echo json_encode($data);	
		}

	}

	function tabledapodiknolocation() {
		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$this->load->model(array('Mdinas','Msetting'));

			$data['data'] = $this->Mdinas->tcg_dapodik_nolocation()->getResultArray(); 
			echo json_encode($data);	
		}

	}

	
}
?>