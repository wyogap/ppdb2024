<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Konfigurasi extends MY_Controller {
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

		$data['settings'] = $this->Mdinas->dbo_settings();

		$data['page'] = "konfigurasi-sistem";
		view('admin/konfigurasi/index',$data);
	}

	function json() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$this->load->model(array('Mdinas','Msetting'));

			$data['data'] = $this->Mdinas->tcg_tahapan($tahun_ajaran_id)->getResultArray(); 
			echo json_encode($data);	
		}
		else if ($action=='edit'){
			$data = $_POST["data"] ?? null; (("data");

			$this->load->model(array('Mdinas','Msetting'));
			foreach ($data as $key => $valuepair) {
				$this->Mdinas->tcg_ubah_setting($key, $valuepair);
			}

			$data['data'] = $this->Mdinas->tcg_detil_setting($key)->getResultArray(); 
			echo json_encode($data);	
		}

	}

	
}
?>