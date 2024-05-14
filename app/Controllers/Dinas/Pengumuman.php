<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pengumuman extends MY_Controller {
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

		$data['page'] = "konfigurasi-pengumuman";
		view('admin/pengumuman/index',$data);

	}

	function json() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$this->load->model(array('Mdinas','Mdropdown'));

			$data['data'] = $this->Mdinas->tcg_pengumuman($tahun_ajaran_id)->getResultArray(); 
			echo json_encode($data);	
		}
		else if ($action=='edit'){
			$data = $_POST["data"] ?? null; (("data");

			$this->load->model(array('Mdinas','Msetting'));
			foreach ($data as $key => $valuepair) {
				$this->Mdinas->tcg_ubah_pengumuman($key, $valuepair);
			}

			$data['data'] = $this->Mdinas->tcg_detil_pengumuman($key)->getResultArray(); 
			echo json_encode($data);	
		}
		else if ($action=='remove') {
			$values = $_POST["data"] ?? null; (("data");

			$this->load->model(array('Mdinas','Mdropdown'));
			$error_msg = "";
			foreach ($values as $key => $valuepair) {
				$retval = $this->Mdinas->tcg_hapus_pengumuman($key);
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

			$this->load->model(array('Mdinas','Mdropdown'));
			do {

				$key = $this->Mdinas->tcg_pengumuman_baru($tahun_ajaran_id, $values[0]);
				if ($key == 0) {
					$error = $builder->error();
					if (empty($error['message'])) {
						$data['error'] = 'Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.';
					} else {
						$data['error'] = $error['message'];
					}
				} else {
					$data['data'] = $this->Mdinas->tcg_detil_pengumuman($key)->getResultArray(); 
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