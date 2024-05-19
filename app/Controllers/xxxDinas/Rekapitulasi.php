<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rekapitulasi extends MY_Controller {
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
		//$data['rekapitulasisd'] = $this->Mdinas->rekapitulasisd();
		$data['rekapitulasismp'] = $this->Mdinas->tcg_rekapitulasismp();
		$data['rekapitulasismpswasta'] = $this->Mdinas->tcg_rekapitulasismpswasta();
		view('admin/rekapitulasi/index',$data);
	}


	
}
?>