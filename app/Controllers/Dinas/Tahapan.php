<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Tahapan extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		if($this->session->get('isLogged')==FALSE||$this->session->get('peran_id')!=4) {
			return redirect()->to("akun/login");
		}
	}

	function index()
	{
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$this->load->model(array('Mdinas','Msetting'));

		// $data['tahapan_penerimaan'] = $this->Mdinas->tcg_tahapan($tahun_ajaran_id);
		$data['tahun_ajaran'] = $this->Msetting->tcg_tahunajaran();
		$data['tahun_ajaran_aktif'] = $tahun_ajaran_id;

		$data['page'] = "konfigurasi-tahapan";
		view('admin/tahapan/index',$data);
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
			foreach ($data as $i => $valuepair) {
				foreach ($valuepair as $j => $value) {
					$this->Mdinas->tcg_update_tahapan($i, $j, $value);
					// if ($j == 'tanggal_mulai') {
					// 	$this->Mdinas->tcg_update_tahapan($i, $j, $value);
					// }
					// else if ($j == 'tanggal_selesai') {
					// 	$this->Mdinas->tcg_update_tahapan($i, $j, $value);
					// }
				}
			}

			$data['data'] = $this->Mdinas->tcg_view_tahapan($i)->getResultArray(); 
			echo json_encode($data);	
		}

	}
	
}
?>