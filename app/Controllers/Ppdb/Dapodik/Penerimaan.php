<?php

namespace App\Controllers\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Mdropdown;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

defined('BASEPATH') OR exit('No direct script access allowed');

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class Penerimaan extends PpdbController {

    protected $Msekolah;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        //$this->smarty = new SmartyLibrary();

        //load model
        $this->Msekolah = new Mprofilsekolah();
        
        if($this->session->get('is_logged_in')==FALSE || $this->session->get('peran_id')!=ROLEID_DAPODIK) {
			redirect(site_url() .'auth');
		}
    }
    
    // public function __construct()
	// {
	// 	parent::__construct();
	// 	if($this->session->get('isLogged')==FALSE||$this->session->get('peran_id')!=5) {
	// 		return redirect()->to("akun/login");
	// 	}
	// }

	function index()
	{
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

        $mdropdown = new Mdropdown();
		$data['daftarsekolah'] = $mdropdown->tcg_sekolah_tk_ra($this->kode_wilayah);

		$sekolah_id = $this->session->get("sekolah_id");
		$nama_sekolah = $this->Msekolah->tcg_nama_sekolah($sekolah_id);
		$data['page_title'] = "Penerimaan " .$nama_sekolah;

		$data['page'] = "ppdb-sd";

		view('dapodik/penerimaan/index',$data);

	}

	// function cari() {
	// 	$nama = $_POST["data"] ?? null; (("nama");
	// 	$nisn= $_POST["data"] ?? null; (("nisn");
	// 	$nik= $_POST["data"] ?? null; (("nik");
	// 	$sekolah_id= $_POST["data"] ?? null; (("sekolah_id");
	// 	$jenis_kelamin= $_POST["data"] ?? null; (("jenis_kelamin");
	// 	$kode_desa= $_POST["data"] ?? null; (("kode_desa");
	// 	$kode_kecamatan= $_POST["data"] ?? null; (("kode_kecamatan");

	// 	$this->load->model(array('Mdinas','Mdropdown'));

	// 	$data['daftar'] = $this->Mdinas->tcg_cari_pesertadidik($nama, $nisn, $nik, $sekolah_id, $jenis_kelamin, $kode_desa, $kode_kecamatan);
	// 	view('admin/pesertadidik/daftarpencarian',$data);
	// }

	function json() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$sekolah_id = $this->session->get("sekolah_id");

			$data = array();

			$result = $this->Msekolah->tcg_pesertadidik_sd_diterima($sekolah_id);
			if ($result == null) {
				$data['data'] = array();
			}
			else if (!empty($result['status'])) {
				$data['error'] = $result['message'];
			}
			else {
				$data['data'] = $result;
			}

			echo json_encode($data);
		}
		// else if ($action=='create'){
		// 	$sekolah_id = $this->session->get("sekolah_id");
        //     $values = $_POST["data"] ?? null; 

		// 	//TODO
		// 	do {
		// 		if (empty($values[0]['username']) || $values[0]['username'] == '0') {
		// 			$data['error'] = 'Username tidak boleh kosong.';
		// 			break;
		// 		}

		// 		if($values[0]['peran_id'] == 2 && !isset($values[0]['sekolah_id'])) {
		// 			$data['error'] = 'Untuk Admin Sekolah, sekolah harus diisi.';
		// 			break;
		// 		}
	
		// 		$cnt = $this->Mdinas->tcg_cek_username('', $values[0]['username']);
		// 		if ($cnt > 0) {
		// 			$data['error'] = 'Username sudah digunakan. Silahkan pilih username lain.';
		// 			break;
		// 		}

		// 		$key = $this->Mdinas->tcg_pengguna_baru($values[0]);
		// 		if ($key == '') {
		// 			$error = $builder->error();
		// 			if (empty($error['message'])) {
		// 				$data['error'] = 'Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.';
		// 			} else {
		// 				$data['error'] = $error['message'];
		// 			}
		// 		} else {
		// 			$data['data'] = $this->Mdinas->tcg_detil_pengguna($key)->getResultArray(); 
		// 		}
	
		// 	} while(false);

        //     echo json_encode($data);
		// }
		// else if ($action=='edit'){
		// 	$sekolah_id = $this->session->get("sekolah_id");
		// 	$values = $_POST["data"] ?? null;

		// 	//TODO
		// 	$data['data'] = array();
		// 	$data['flag'] = 1;

        //     $error_msg = "";
		// 	foreach ($values as $key => $valuepair) {
		// 		if (empty($valuepair['sekolah_id']) || $valuepair['sekolah_id'] == '0') {
		// 			unset($valuepair['sekolah_id']);
		// 		}
	
		// 		if (empty($valuepair['username']) || $valuepair['username'] == '0') {
		// 			unset($valuepair['username']);
		// 		}
	
		// 		if (!empty($valuepair['pwd1'])) {
		// 			if ($valuepair['pwd1'] != $valuepair['pwd2']) {
		// 				$data['error'] = 'PIN1 dan PIN2 tidak sama. Silahkan ulangi lagi.';
		// 				break;
		// 			}
		// 			else {
		// 				if($this->Mlogin->tcg_resetpassword($key, $valuepair['pwd1']) == 0) {
		// 					$data['error'] = "Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.";
		// 				} else {
		// 					foreach($this->Mdinas->tcg_detil_pengguna($key)->getResult() as $row) {
		// 						$data['data'][] = $row;
		// 					}
		// 				}
		// 			}
		// 			unset($valuepair['pwd1']);
		// 			unset($valuepair['pwd2']);
		// 		}

		// 		$cnt = $this->Mdinas->tcg_cek_username($key, $valuepair['username']);
		// 		if ($cnt > 0) {
		// 			$data['error'] = 'Username sudah digunakan. Silahkan pilih username lain.';
		// 			break;
		// 		}

		// 		$retval = $this->Mdinas->tcg_ubah_pengguna($key, $valuepair);
		// 		if($retval == 0) {
		// 			$error = $builder->error();
		// 			if (empty($error['message'])) {
		// 				$data['error'] = 'Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.';
		// 			} else {
		// 				$data['error'] = $error['message'];
		// 			}
		// 		} else {
		// 			foreach($this->Mdinas->tcg_detil_pengguna($key)->getResult() as $row) {
		// 				$data['data'][] = $row;
		// 			}
		// 		}
		// 	}

		// 	echo json_encode($data);	
		// }
		// else if ($action=='remove'){
		// 	$sekolah_id = $this->session->get("sekolah_id");
		// 	$peserta_didik_id= $_POST["peserta_didik_id"] ?? null; 

		// 	$data = array();
		// 	$result = $this->Msekolah->tcg_hapus_pesertadidik_sd($sekolah_id, $peserta_didik_id)[0];
		// 	if ($result == null) {
		// 		$data['data'] = array();
		// 	}
		// 	else if (!empty($result['status'])) {
		// 		$data['error'] = $result['message'];
		// 	}
		// 	else {
		// 		$data['data'] = array();
		// 	}

		// 	echo json_encode($data);	
        // }
		else if ($action=='accept'){
			$sekolah_id = $this->session->get("sekolah_id");
			$peserta_didik_id= $_POST["peserta_didik_id"] ?? null; 

			$data = array();
			$result = $this->Msekolah->tcg_terima_pesertadidik_sd($sekolah_id, $peserta_didik_id)[0];
			if ($result == null) {
				$data['data'] = array();
			}
			else if (!empty($result['status'])) {
				$data['error'] = $result['message'];
			}
			else {
				$data['data'] = $result;
			}

			echo json_encode($data);	
        }
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}
	}

	function search() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$mdropdown = new Mdropdown();
		
		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='search') {
			$nama = $_GET["nama"] ?? null; 
			$nisn= $_GET["nisn"] ?? null; 
			$nik= null;
			$sekolah_id= $_GET["sekolah_id"] ?? null; 
			$jenis_kelamin= null;
			$kode_desa= null;
			$kode_kecamatan= null;
	
			if (empty($nama) && empty($nisn) && empty($nik) && empty($sekolah_id) && empty($jenis_kelamin) && empty($kode_desa) && empty($kode_kecamatan)) {
				//no search
				$data['data'] = array();
				echo json_encode($data);
			}
			else {
				//search
                $mdropdown = new Mdropdown();
				$sekolah = $mdropdown->tcg_sekolah_tk_ra($this->kode_wilayah);
				$daftar = $this->Msekolah->tcg_calon_pesertadidik_sd($nama, $nisn, $nik, $sekolah_id, $jenis_kelamin, $kode_desa, $kode_kecamatan);

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
				while ($row = $sekolah->getUnbufferedRow()) 
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
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}
	}

}
?>