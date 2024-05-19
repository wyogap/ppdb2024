<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Daftarkelengkapan extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		if($this->session->get('isLogged')==FALSE||$this->session->get('peran_id')!=4) {
			return redirect()->to("akun/login");
		}
	}

	function index()
	{
		// $tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		// if (empty($tahun_ajaran_id))
		// 	$tahun_ajaran_id = $this->tahun_ajaran_id;

		
		// $this->load->model(array('Mdinas','Msetting'));

		// // $data['jalur_penerimaan'] = $this->Mdinas->tcg_jalurpenerimaan($tahun_ajaran_id);
		// $data['tahun_ajaran'] = $this->Msetting->tcg_tahunajaran();
		// $data['tahun_ajaran_aktif'] = $tahun_ajaran_id;

		$data['page'] = "konfigurasi-kelengkapan";
		view('admin/daftarkelengkapan/index',$data);

	}

	function json() {
		// $tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		// if (empty($tahun_ajaran_id))
		// 	$tahun_ajaran_id = $this->tahun_ajaran_id;

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$this->load->model(array('admin/Mdaftarkelengkapan'));

			$data['data'] = $this->Mdaftarkelengkapan->list()->getResultArray(); 
			$data['options']['master_kelengkapan_id'] = $this->Mdaftarkelengkapan->lookup()->getResultArray(); 
			array_unshift($data['options']['master_kelengkapan_id'], array('value'=>'0', 'label'=>'-- Kelengkapan --'));
			echo json_encode($data);	

		}
		else if ($action=='edit'){
			$data = $_POST["data"] ?? null; (("data");

			$this->load->model(array('admin/Mdaftarkelengkapan'));
			foreach ($data as $key => $valuepair) {
				if (isset($valuepair['master_kelengkapan_id']) && empty($valuepair['master_kelengkapan_id'])) {
					$valuepair['master_kelengkapan_id'] = null;
				}
				$this->Mdaftarkelengkapan->update($key, $valuepair);
			}

			$data['data'] = $this->Mdaftarkelengkapan->detail($key)->getResultArray(); 
			echo json_encode($data);	
		}
		else if ($action=='remove') {
			$values = $_POST["data"] ?? null; (("data");

			$this->load->model(array('admin/Mdaftarkelengkapan'));
			$error_msg = "";
			foreach ($values as $key => $valuepair) {
				$retval = $this->Mdaftarkelengkapan->delete($key);
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

			$this->load->model(array('admin/Mdaftarkelengkapan'));
			do {

				if (isset($values[0]['master_kelengkapan_id']) && empty($values[0]['master_kelengkapan_id'])) {
					$values[0]['master_kelengkapan_id'] = null;
				}
				$key = $this->Mdaftarkelengkapan->add($values[0]);
				if ($key == 0) {
					$error = $builder->error();
					if (empty($error['message'])) {
						$data['error'] = 'Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.';
					} else {
						$data['error'] = $error['message'];
					}
				} else {
					$data['data'] = $this->Mdaftarkelengkapan->detail($key)->getResultArray(); 
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