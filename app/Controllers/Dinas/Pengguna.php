<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pengguna extends MY_Controller {
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

		$kode_wilayah = $this->session->get("kode_wilayah_aktif");
		//$kode_wilayah = $this->kode_wilayah_aktif;

		$this->load->model(array('Mdropdown','Msetting'));

		// $data['jalur_penerimaan'] = $this->Mdinas->tcg_jalurpenerimaan($tahun_ajaran_id);
		$data['daftarsekolah'] = $this->Mdropdown->tcg_sekolah($kode_wilayah);
		$data['daftarperan'] = $this->Mdropdown->tcg_peran();

		$data['page'] = "pengelolaan-pengguna";
		view('admin/pengguna/index',$data);

	}

	function debug()
	{
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		//echo "Kode Wilayah=$kode_wilayah";

		$this->load->model(array('Mdropdown','Msetting'));

		// $data['jalur_penerimaan'] = $this->Mdinas->tcg_jalurpenerimaan($tahun_ajaran_id);
		$data['daftarsekolah'] = $this->Mdropdown->tcg_sekolah($kode_wilayah);
		$data['daftarperan'] = $this->Mdropdown->tcg_peran();

		$data['page'] = "pengelolaan-pengguna";
		view('admin/pengguna/index',$data);

	}

	function cari() {
		$this->load->model(array('Mdinas'));

		$nama = $_POST["data"] ?? null; (("nama");
		$sekolah_id= $_POST["data"] ?? null; (("sekolah_id");
		$username= $_POST["data"] ?? null; (("username");
		$peran_id= $_POST["data"] ?? null; (("peran_id");

		$data['daftar'] = $this->Mdinas->tcg_cari_pengguna($nama, $username, $sekolah_id, $peran_id);
		view('admin/pengguna/daftarpencarian',$data);

	}

	function json() {
		// $tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		// if (empty($tahun_ajaran_id))
		// 	$tahun_ajaran_id = $this->tahun_ajaran_id;

		$this->load->model(array('Mdinas','Mdropdown','Mlogin'));
		
		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$nama = $_GET["data"] ?? null; (("nama");
			$sekolah_id= $_GET["data"] ?? null; (("sekolah_id");
			$username= $_GET["data"] ?? null; (("username");
			$peran_id= $_GET["data"] ?? null; (("peran_id");
	
			if (empty($nama) && empty($username) && empty($sekolah_id) && empty($peran_id)) {
				//no search
				$data['data'] = array();
				$data['options']['sekolah_id'] = $this->Mdropdown->tcg_select_smp($this->session->get("kode_wilayah_aktif"))->getResultArray();
				$data['options']['peran_id'] =  $this->Mdropdown->tcg_select_peran()->getResultArray();
				echo json_encode($data);	
			}
			else {
				//search
				$daftar = $this->Mdinas->tcg_cari_pengguna($nama, $username, $sekolah_id, $peran_id); 
				if (empty($sekolah_id)) {
					$sekolah = $this->Mdropdown->tcg_select_smp($this->session->get("kode_wilayah_aktif"));
				} else {
					$sekolah = $this->Mdropdown->tcg_select_sekolah_filter($sekolah_id);
				}
				$peran = $this->Mdropdown->tcg_select_peran();

				//manual echo json file to avoid memory exhausted
				echo '{"data":[';
				$first = true;
				while ($row = $daftar->unbuffered_row())
				{
					if ($first) {
						echo json_encode($row);
						$first = false;
					} else {
						echo ",". json_encode($row);
					}
				}
				echo '],"options":{"sekolah_id":[';
				$first = true;
				while ($row = $sekolah->unbuffered_row()) 
				{
					if ($first) {
						echo json_encode($row);
						$first = false;
					} else {
						echo ",". json_encode($row);
					}
				}
				echo '],"peran_id":[';
				$first = true;
				while ($row = $peran->unbuffered_row()) 
				{
					if ($first) {
						echo json_encode($row);
						$first = false;
					} else {
						echo ",". json_encode($row);
					}
				}
				echo ']}}';
			}
		}
		else if ($action=='edit'){
			$values = $_POST["data"] ?? null; (("data");

			$data['data'] = array();
			$data['flag'] = 1;

            $error_msg = "";
			foreach ($values as $key => $valuepair) {
				if (empty($valuepair['sekolah_id']) || $valuepair['sekolah_id'] == '0') {
					unset($valuepair['sekolah_id']);
				}
	
				if (empty($valuepair['username']) || $valuepair['username'] == '0') {
					unset($valuepair['username']);
				}
	
				if (!empty($valuepair['pwd1'])) {
					if ($valuepair['pwd1'] != $valuepair['pwd2']) {
						$data['error'] = 'PIN1 dan PIN2 tidak sama. Silahkan ulangi lagi.';
						break;
					}
					else {
						if($this->Mlogin->tcg_resetpassword($key, $valuepair['pwd1']) == 0) {
							$data['error'] = "Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.";
						} else {
							foreach($this->Mdinas->tcg_detil_pengguna($key)->getResult() as $row) {
								$data['data'][] = $row;
							}
						}
					}
					unset($valuepair['pwd1']);
					unset($valuepair['pwd2']);
				}

				$cnt = $this->Mdinas->tcg_cek_username($key, $valuepair['username']);
				if ($cnt > 0) {
					$data['error'] = 'Username sudah digunakan. Silahkan pilih username lain.';
					break;
				}

				$retval = $this->Mdinas->tcg_ubah_pengguna($key, $valuepair);
				if($retval == 0) {
					$error = $builder->error();
					if (empty($error['message'])) {
						$data['error'] = 'Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.';
					} else {
						$data['error'] = $error['message'];
					}
				} else {
					foreach($this->Mdinas->tcg_detil_pengguna($key)->getResult() as $row) {
						$data['data'][] = $row;
					}
				}
			}

			echo json_encode($data);	
        }
        else if ($action=='remove') {
			$values = $_POST["data"] ?? null; (("data");

            $error_msg = "";
			foreach ($values as $key => $valuepair) {
				$retval = $this->Mdinas->tcg_hapus_pengguna($key);
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

			if (empty($values[0]['sekolah_id']) || $values[0]['sekolah_id'] == '0') {
				unset($values[0]['sekolah_id']);
			}

			do {
				if (empty($values[0]['username']) || $values[0]['username'] == '0') {
					$data['error'] = 'Username tidak boleh kosong.';
					break;
				}

				if($values[0]['peran_id'] == 2 && !isset($values[0]['sekolah_id'])) {
					$data['error'] = 'Untuk Admin Sekolah, sekolah harus diisi.';
					break;
				}
	
				$cnt = $this->Mdinas->tcg_cek_username('', $values[0]['username']);
				if ($cnt > 0) {
					$data['error'] = 'Username sudah digunakan. Silahkan pilih username lain.';
					break;
				}

				$key = $this->Mdinas->tcg_pengguna_baru($values[0]);
				if ($key == '') {
					$error = $builder->error();
					if (empty($error['message'])) {
						$data['error'] = 'Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.';
					} else {
						$data['error'] = $error['message'];
					}
				} else {
					$data['data'] = $this->Mdinas->tcg_detil_pengguna($key)->getResultArray(); 
				}
	
			} while(false);

            echo json_encode($data);
        }
		else if ($action=='resetpassword') {			
			$pengguna_id = $_POST["data"] ?? null; (("pengguna_id");
            $pwd1 = $_POST["data"] ?? null; (("pin1");
            $pwd2 = $_POST["data"] ?? null; (("pin2");

			$this->load->model('Mlogin');
			do {
				if ($pwd1 != $pwd2) {
					$data['error'] = "PIN1 dan PIN2 tidak sama. Silahkan ulangi lagi.";
					break;
				}

				$arr = explode(',', $pengguna_id);
				foreach($arr as $key) {
					if($this->Mlogin->tcg_resetpassword($key, $pwd1) == 0) {
						$data['error'] = "Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.";
					} else {
						$data['data'] = $this->Msiswa->tcg_pengguna_detil($key)->getResultArray(); 
					}
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