<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pesertadidik extends MY_Controller {
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
		
		$this->load->model(array('Mdropdown','Msetting'));

		$data['daftarsekolah'] = $this->Mdropdown->tcg_sekolah_sd_mi($kode_wilayah);
		$data['daftarkecamatan'] = $this->Mdropdown->tcg_kecamatan(null);

		$data['page'] = "pengelolaan-pesertadidik";
		view('admin/pesertadidik/index',$data);

	}

	function cari() {
		$nama = $_POST["data"] ?? null; (("nama");
		$nisn= $_POST["data"] ?? null; (("nisn");
		$nik= $_POST["data"] ?? null; (("nik");
		$sekolah_id= $_POST["data"] ?? null; (("sekolah_id");
		$jenis_kelamin= $_POST["data"] ?? null; (("jenis_kelamin");
		$kode_desa= $_POST["data"] ?? null; (("kode_desa");
		$kode_kecamatan= $_POST["data"] ?? null; (("kode_kecamatan");

		$this->load->model(array('Mdinas','Mdropdown'));

		$data['daftar'] = $this->Mdinas->tcg_cari_pesertadidik($nama, $nisn, $nik, $sekolah_id, $jenis_kelamin, $kode_desa, $kode_kecamatan);
		view('admin/pesertadidik/daftarpencarian',$data);
	}

	function json() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$this->load->model(array('Mdinas','Mdropdown','Mlogin', 'Msiswa'));
		
		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$nama = $_GET["data"] ?? null; (("nama");
			$nisn= $_GET["data"] ?? null; (("nisn");
			$nik= $_GET["data"] ?? null; (("nik");
			$sekolah_id= $_GET["data"] ?? null; (("sekolah_id");
			$jenis_kelamin= $_GET["data"] ?? null; (("jenis_kelamin");
			$kode_desa= $_GET["data"] ?? null; (("kode_desa");
			$kode_kecamatan= $_GET["data"] ?? null; (("kode_kecamatan");
	
			if (empty($nama) && empty($nisn) && empty($nik) && empty($sekolah_id) && empty($jenis_kelamin) && empty($kode_desa) && empty($kode_kecamatan)) {
				//no search
				$data['data'] = array();
				echo json_encode($data);
			}
			else {
				//search
				$daftar = $this->Mdinas->tcg_cari_pesertadidik($nama, $nisn, $nik, $sekolah_id, $jenis_kelamin, $kode_desa, $kode_kecamatan);
				$sekolah = $this->Mdropdown->tcg_select_sd_mi($this->session->get("kode_wilayah_aktif"));

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
				echo ']}}';
			}
		}
		else if ($action=='edit'){
			$values = $_POST["data"] ?? null; (("data");

			$data['data'] = array();
            $error_msg = "";
			foreach ($values as $key => $valuepair) {
				//$data['data'][$key] = $valuepair;

				if(!empty($valuepair['pwd1'])) {
					if ($valuepair['pwd1'] != $valuepair['pwd2']) {
						$data['error'] = 'PIN1 dan PIN2 tidak sama. Silahkan ulangi lagi.';
						break;
					}
					else {
						if($this->Mlogin->tcg_resetpassword($key, $valuepair['pwd1']) == 0) {
							$data['error'] = "Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.";
						} else {
							foreach($this->Mdinas->tcg_detil_pesertadidik($key)->getResult() as $row) {
								$data['data'][] = $row;
							}
						}
					}
					unset($valuepair['pwd1']);
					unset($valuepair['pwd2']);
				}

				unset($valuepair['username']);
				unset($valuepair['sekolah_id']);
				unset($valuepair['peran_id']);
				
				if (count($valuepair) > 0) {
					if ($this->Msiswa->tcg_ubah_profil($key, $valuepair)) {
						foreach($this->Mdinas->tcg_detil_pesertadidik($key)->getResult() as $row) {
							$data['data'][] = $row;
						}
					}
				}

			}

			echo json_encode($data);	
        }
        // else if ($action=='remove') {
		// 	$values = $_POST["data"] ?? null; (("data");

        //     $error_msg = "";
		// 	foreach ($values as $key => $valuepair) {
		// 		$retval = $this->Mdinas->tcg_hapus_pesertadidik($key);
		// 		if($retval == 0) {
		// 			$data['error'] = $builder->error();
		// 		} else {
		// 			$data['data'] = array(); 
		// 		}
		// 	}

		// 	echo json_encode($data);	
        // }
		else if ($action=='resetpassword') {			
			$peserta_didik_id = $_POST["data"] ?? null; (("peserta_didik_id");
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
						$data['data'][] = $this->Mdinas->tcg_detil_pesertadidik($key)->getResultArray(); 
					}
				}

			} while(false);

            echo json_encode($data);
        }
		else if ($action=='resetpendaftaran') {			
			$peserta_didik_id = $_POST["data"] ?? null; (("peserta_didik_id");

			$this->load->model('Mdinas');
			do {

				if($this->Mdinas->tcg_resetpendaftaran($peserta_didik_id) == 0) {
					$data['error'] = "Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.";
				} else {
					$data['data'][] = $this->Mdinas->tcg_detil_pesertadidik($key)->getResultArray(); 
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