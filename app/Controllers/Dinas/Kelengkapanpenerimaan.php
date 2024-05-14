<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kelengkapanpenerimaan extends MY_Controller {
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

		
		$this->load->model(array('Msetting'));

		// $data['jalur_penerimaan'] = $this->Mdinas->tcg_jalurpenerimaan($tahun_ajaran_id);
		$data['tahun_ajaran'] = $this->Msetting->tcg_tahunajaran();
		$data['tahun_ajaran_aktif'] = $tahun_ajaran_id;

		$data['page'] = "konfigurasi-kelengkapan-penerimaan";
		view('admin/kelengkapanpenerimaan/index',$data);

	}

	function json() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$this->load->model(array('admin/Mkelengkapanpenerapan', 'admin/Mdaftarkelengkapan', 'admin/Mpenerapan'));

			$data['data'] = $this->Mkelengkapanpenerapan->list($tahun_ajaran_id)->getResultArray(); 
			$data['options']['daftar_kelengkapan_id'] = $this->Mdaftarkelengkapan->lookup()->getResultArray(); 
			$data['options']['penerapan_id'] = $this->Mpenerapan->lookup($tahun_ajaran_id)->getResultArray(); 
			echo json_encode($data);	
		}
		else if ($action=='edit'){
			$data = $_POST["data"] ?? null; (("data");

			$this->load->model(array('admin/Mkelengkapanpenerapan'));
			foreach ($data as $key => $valuepair) {
				$this->Mkelengkapanpenerapan->update($key, $valuepair);
			}

			$data['data'] = $this->Mkelengkapanpenerapan->detail($key)->getResultArray(); 
			echo json_encode($data);	
		}
		else if ($action=='remove') {
			$values = $_POST["data"] ?? null; (("data");

			$this->load->model(array('admin/Mkelengkapanpenerapan'));
			$error_msg = "";
			foreach ($values as $key => $valuepair) {
				$retval = $this->Mkelengkapanpenerapan->delete($key);
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

			$this->load->model(array('admin/Mkelengkapanpenerapan'));
			do {

				$key = $this->Mkelengkapanpenerapan->add($tahun_ajaran_id, $values[0]);
				if ($key == 0) {
					$error = $builder->error();
					if (empty($error['message'])) {
						$data['error'] = 'Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.';
					} else {
						$data['error'] = $error['message'];
					}
				} else {
					$data['data'] = $this->Mkelengkapanpenerapan->detail($key)->getResultArray(); 
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