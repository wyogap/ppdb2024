<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Skoring extends MY_Controller {
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

		$data['daftar_skoring'] = $this->Mdinas->tcg_daftarskoring($tahun_ajaran_id);
		$data['tahun_ajaran'] = $this->Msetting->tcg_tahunajaran();
		$data['tahun_ajaran_aktif'] = $tahun_ajaran_id;

		$data['page'] = "konfigurasi-skoring";
		view('admin/skoring/index',$data);
	}

	function json() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$this->load->model(array('Mdinas','Mdropdown'));

			$data['data'] = $this->Mdinas->tcg_daftarskoring($tahun_ajaran_id)->getResultArray(); 
			$data['options']['jalur_id'] = $this->Mdropdown->tcg_select_jalur()->getResultArray(); 
			$data['options']['jalur_id_baru'] = $data['options']['jalur_id']; 
			$data['options']['tipe_skoring_id'] = $this->Mdropdown->tcg_select_tipeskoring()->getResultArray(); 
			$data['options']['tipe_skoring_id_baru'] = $data['options']['tipe_skoring_id']; 
			echo json_encode($data);	
		}
		else if ($action=='edit'){
			$value = $_POST["data"] ?? null; (("data");

			$data = array();
			$this->load->model(array('Mdinas','Msetting'));
			foreach ($value as $key => $valuepair) {
				foreach($valuepair as $field => $value) {
					if ($field == 'nilai' && !empty($value) && $value!=0) {
						$this->Mdinas->tcg_ubah_nilai_skoring($key, 'nilai', $valuepair['nilai']);
					}
					else if ($field == 'urutan' && !empty($value) && $value!=0) {
						$this->Mdinas->tcg_ubah_nilai_skoring($key, 'urutan', $valuepair['urutan']);
					}
					else if ($field == 'nama' && !empty($value)) {
						$this->Mdinas->tcg_ubah_skoring($key, 'nama', $valuepair['nama']);
						$data['status'] = 'update name';
					}
					else if ($field == 'tipe_skoring_id' && !empty($value) && $value!=0) {
						$this->Mdinas->tcg_ubah_skoring($key, 'tipe_skoring_id', $valuepair['tipe_skoring_id']);
					}
					else if ($field == 'jalur_id' && !empty($value) && $value!=0) {
						$this->Mdinas->tcg_ubah_skoring($key, 'jalur_id', $valuepair['jalur_id']);
					}
				}
				$data['data'] = $this->Mdinas->tcg_skoring_detil($key)->getResultArray(); 
			}

			echo json_encode($data);	
		}
		else if ($action=='remove') {
			$values = $_POST["data"] ?? null; (("data");

			$this->load->model(array('Mdinas','Msetting'));
            $error_msg = "";
			foreach ($values as $key => $valuepair) {
				$retval = $this->Mdinas->tcg_hapus_skoring($key);
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

			$this->load->model(array('Mdinas','Msetting'));
			do {
				$key = 0;
				if (!empty($values[0]['skoring_id']) || $values[0]['skoring_id'] != 0) {
					$key = $this->Mdinas->tcg_nilai_skoring_baru($tahun_ajaran_id, $values[0]['skoring_id'], $values[0]['nilai'], $values[0]['urutan']);
				}
				else if ((!empty($values[0]['jalur_id_baru']) || $values[0]['jalur_id_baru'] != 0)) {
					$key = $this->Mdinas->tcg_skoring_baru($tahun_ajaran_id, $values[0]['jalur_id_baru'], $values[0]['tipe_skoring_id_baru'], $values[0]['nama_baru'], $values[0]['nilai_baru'], $values[0]['urutan_baru']);
				}
				else {
					$data['error'] = "Pilih dari daftar skoring yang sudah ada atau buat baru.";
					break;
				}

				if ($key == 0) {
					$error = $builder->error();
					if (empty($error['message'])) {
						$data['error'] = 'Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.';
					} else {
						$data['error'] = $error['message'];
					}
				} else {
					$data['data'] = $this->Mdinas->tcg_skoring_detil($key)->getResultArray(); 
				}
	
			} while(false);

            echo json_encode($data);
        }
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}

	}

	function daftarskoring() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$jalur_id = $_GET["data"] ?? null; (("jalur_id");
		if (empty($jalur_id)) {
			$jalur_id = 0;
		}

		$this->load->model(array('Mdinas','Mdropdown'));
		$data['data'] = $this->Mdropdown->tcg_select_daftarskoring($jalur_id)->getResultArray();

		echo json_encode($data);
	}
}
?>