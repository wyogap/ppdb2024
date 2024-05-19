<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Penerimaan extends MY_Controller {
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

		// $data['jalur_penerimaan'] = $this->Mdinas->tcg_jalurpenerimaan($tahun_ajaran_id);
		$data['tahun_ajaran'] = $this->Msetting->tcg_tahunajaran();
		$data['tahun_ajaran_aktif'] = $tahun_ajaran_id;

		$data['page'] = "konfigurasi-penerapan";
		view('admin/jalurpenerimaan/index',$data);

	}

	function json() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$this->load->model(array('admin/Mpenerapan','Mdropdown'));

			$data['data'] = $this->Mpenerapan->list($tahun_ajaran_id)->getResultArray(); 
			$data['options']['jalur_id'] = $this->Mdropdown->tcg_select_jalur()->getResultArray(); 
			echo json_encode($data);	
		}
		else if ($action=='edit'){
			$data = $_POST["data"] ?? null; (("data");

			$this->load->model(array('admin/Mpenerapan','Msetting'));
			foreach ($data as $key => $valuepair) {
				foreach ($valuepair as $field => $value) {
					$this->Mpenerapan->update($key, $field, $value);
				}
			}

			$data['data'] = $this->Mpenerapan->detail($key)->getResultArray(); 
			echo json_encode($data);	
		}
		else if ($action=='remove') {
			$values = $_POST["data"] ?? null; (("data");

			$this->load->model(array('admin/Mpenerapan','Mdropdown'));
			$error_msg = "";
			foreach ($values as $key => $valuepair) {
				$retval = $this->Mpenerapan->delete($key);
				if($retval == 0) {
					$error = $builder->error();
					if (empty($error['message'])) {
						$data['error'] = 'Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.';
					} else {
						$data['error'] = $error['message'];
					}
				} else {
					$data['data'] = array(); 
				}
			}

			echo json_encode($data);	
		}
        else if ($action=='create') {
            $values = $_POST["data"] ?? null; (("data");

			$this->load->model(array('admin/Mpenerapan','Mdropdown'));
			do {

				$key = $this->Mpenerapan->add($tahun_ajaran_id, $values[0]);
				if ($key == 0) {
					$error = $builder->error();
					if (empty($error['message'])) {
						$data['error'] = 'Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.';
					} else {
						$data['error'] = $error['message'];
					}
				} else {
					$data['data'] = $this->Mpenerapan->detail($key)->getResultArray(); 
				}
	
			} while(false);

            echo json_encode($data);
        }
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}

	}

	
}
?>