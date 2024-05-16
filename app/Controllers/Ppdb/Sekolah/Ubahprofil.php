<?php

namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Mdropdown;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Ubahprofil extends PpdbController {

    protected $Msekolah;

	// public function __construct()
	// {
	// 	parent::__construct();
	// 	//return redirect()->to("Cinfo");
	// 	if($this->session->get('isLogged')==FALSE||$this->session->get('peran_id')!=1) {
	// 		return redirect()->to("akun/login");
	// 	}
	// }

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        //$this->smarty = new SmartyLibrary();

        //load model
        $this->Msekolah = new Mprofilsekolah();
        
        // if($this->session->get('is_logged_in')==FALSE || $this->session->get('peran_id')!=ROLEID_SEKOLAH) {
		// 	redirect(site_url() .'auth');
		// }
    }

	function index()
	{
		$peran_id = $this->session->get('peran_id');
		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id) || $peran_id==2) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		if (empty($sekolah_id)) {
			view('home/notauthorized');
			return;
		}

		$redirect = $_GET["redirect"] ?? null; 
		if (empty($redirect)) {
			$redirect = "Clogin";
		}

		$mdropdown = new Mdropdown();
		$data['kabupaten'] = $mdropdown->tcg_kabupaten();
		$data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
		$data['redirect'] = $redirect;

        //Debug
        $data['nama_pengguna'] = "Wahyu Yoga Pratama";
        $data['username'] = "wyogap@gmail.com";
        //END DEBUG

        //content template
        $data['content_template'] = 'beranda.tpl';

        $data['page_title'] = 'Daftar Ulang';
        $this->smarty->render('ppdb/sekolah/ppdbsekolah.tpl', $data);
	}

	function simpan()
	{
		$pengguna_id = $this->session->get("pengguna_id");
		$peran_id = $this->session->get('peran_id');

		$sekolah_id = $_POST["sekolah_id"] ?? null;
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		if ($peran_id==2 && $sekolah_id != $this->session->get('sekolah_id')) {
			view('home/notauthorized');
			return;
		}

		$kode_wilayah = $_POST["kode_wilayah"] ?? null; 
		if($kode_wilayah==''){
			$kode_wilayah = $_POST["kode_desa"] ?? null; 
		}
		$lintang_lama = $_POST["lintang_lama"] ?? null; 
		$bujur_lama = $_POST["bujur_bujur"] ?? null; 
		$lintang = $_POST["lintang"] ?? null; 
		$bujur = $_POST["bujur"] ?? null; 

		$npsn = $_POST["npsn"] ?? null; 
		$npsn_lama = $_POST["npsn_lama"] ?? null; 
		$inklusi = $_POST["inklusi"] ?? null; 
		$inklusi_lama = $_POST["inklusi_lama"] ?? null; 
		$alamat_jalan = $_POST["alamat_jalan"] ?? null; 
		$alamat_jalan_lama = $_POST["alamat_jalan_lama"] ?? null; 

		do {
			$valuepair = array();
			
			if(!empty($npsn) && $npsn!=$npsn_lama){
				$valuepair['npsn'] = $npsn;
			}

			if ($inklusi != $inklusi_lama) {
				$valuepair['inklusi'] = $inklusi;
			}

			if (!empty($alamat_jalan) && $alamat_jalan != $alamat_jalan_lama) {
				$valuepair['alamat_jalan'] = $alamat_jalan;
			}

			if ($lintang != $lintang_lama) {
				$valuepair['lintang'] = $lintang;
			}

			if ($bujur != $bujur_lama) {
				$valuepair['bujur'] = $bujur;
			}

			if (count($valuepair) > 0) {
				if (!$this->Msekolah->tcg_ubah_profil($sekolah_id, $valuepair)) {
					$this->session->setFlashdata('error', "Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.");
					break;
				}
			}

			$this->session->setFlashdata('success', "Perubahan data telah berhasil disimpan.");

		} while(false);

		$redirect = $_GET["redirect"] ?? null; 
		if (empty($redirect)) {
			$redirect = "Csekolah";
		}

		redirect($redirect);
	}

	function json() {
		
		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}
		else if ($action=='edit'){
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
        }
        else if ($action=='remove') {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
        }
        else if ($action=='create') {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
        }
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}

	}
	
	// function pendaftaran() {
	// 	$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
	// 	if (empty($tahun_ajaran_id)) {
	// 		$tahun_ajaran_id = $this->tahun_ajaran_id;
	// 	}

	// 	$daftar = $this->Mdinas->tcg_daftar_pendaftaran($tahun_ajaran_id);

	// 	//manual echo json file to avoid memory exhausted
	// 	echo '{"data":[';
	// 	$first = true;
	// 	while ($row = $daftar->unbuffered_row())
	// 	{
	// 		if ($first) {
	// 			echo json_encode($row);
	// 			$first = false;
	// 		} else {
	// 			echo ",". json_encode($row);
	// 		}
	// 	}
	// 	echo ']}';
	// }
}
?>