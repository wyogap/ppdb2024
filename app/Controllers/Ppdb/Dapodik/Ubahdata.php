<?php

namespace App\Controllers\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Mdropdown;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

defined('BASEPATH') OR exit('No direct script access allowed');

class Ubahdata extends PpdbController {

    protected $Msiswa;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        //$this->smarty = new SmartyLibrary();

        //load model
        $this->Msiswa = new Mprofilsiswa();
        
        if($this->session->get('is_logged_in')==FALSE || $this->session->get('peran_id')!=ROLEID_DAPODIK) {
			redirect(site_url() .'auth');
		}
    }
    
    // public function __construct()
	// {
	// 	parent::__construct();
	// 	if($this->session->get('isLogged')==FALSE||
	// 			($this->session->get('peran_id')!=4&&$this->session->get('peran_id')!=5)) {
	// 		return redirect()->to("akun/login");
	// 	}
	// }

	function index()
	{
		$peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 
		$redirect = $_GET["redirect"] ?? null; 
		if (empty($redirect)) {
			$redirect = "dapodik/beranda";
		}

		$mdropdown = new Mdropdown();
		$data['kabupaten'] = $mdropdown->tcg_kabupaten();
		$data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);
		$data['redirect'] = $redirect;
		
		view('dapodik/ubahdata/index',$data);
	}

	function simpan()
	{
		$pengguna_id = $this->session->get("pengguna_id");

		$peserta_didik_id = $_POST["peserta_didik_id"] ?? null; 
		$kode_wilayah = $_POST["kode_wilayah"] ?? null; 
		if($kode_wilayah==''){
			$kode_wilayah = $_POST["kode_desa"] ?? null; 
		}
		$tanggal_lahir = $_POST["tanggal_lahir"] ?? null; 
		$lintang = $_POST["lintang"] ?? null; 
		$bujur = $_POST["bujur"] ?? null; 
		$keterangan_approval = $_POST["keterangan_approval"] ?? null; 
		$asal_data = $_POST["asal_data"] ?? null; 

		$nik = $_POST["nik"] ?? null; 
		$nik_lama = $_POST["nik_lama"] ?? null; 
		$nisn = $_POST["nisn"] ?? null; 
		$nisn_lama = $_POST["nisn_lama"] ?? null; 
		$kebutuhan_khusus = $_POST["kebutuhan_khusus"] ?? null; 
		$kebutuhan_khusus_lama = $_POST["kebutuhan_khusus_lama"] ?? null; 

		$tempat_lahir = $_POST["tempat_lahir"] ?? null; 
		$tempat_lahir_lama = $_POST["tempat_lahir_lama"] ?? null; 
		$jenis_kelamin = $_POST["jenis_kelamin"] ?? null; 
		$jenis_kelamin_lama = $_POST["jenis_kelamin_lama"] ?? null; 
		$rt = $_POST["rt"] ?? null; 
		$rt_lama = $_POST["rt_lama"] ?? null; 
		$rw = $_POST["rw"] ?? null;
		$rw_lama = $_POST["rw_lama"] ?? null; 

		$nama = $_POST["nama"] ?? null; 
		$nama_lama = $_POST["nama_lama"] ?? null; 
		$nama_ibu = $_POST["nama_ibu"] ?? null; 
		$nama_ibu_lama = $_POST["nama_ibu_lama"] ?? null; 
		$nama_ayah = $_POST["nama_ayah"] ?? null;
		$nama_ayah_lama = $_POST["nama_ayah_lama"] ?? null;

		$approval = 1;
		$keterangan_approval = "Perubahan data oleh Admin DAPODIK";

		do {
			if(!$this->Msiswa->tcg_ubahdata($peserta_didik_id, $approval, $keterangan_approval, $kode_wilayah, $tanggal_lahir, $lintang, $bujur, $pengguna_id)){
				$this->session->setFlashdata('error', "Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.");
				break;
			}

			$valuepair = array();
			
			if($kebutuhan_khusus!=$kebutuhan_khusus_lama){
				$valuepair['kebutuhan_khusus'] = $kebutuhan_khusus;
			}

			if ($nisn != $nisn_lama) {
				$valuepair['nisn'] = $nisn;
			}

			if ($nik != $nik_lama) {
				$valuepair['nik'] = $nik;
			}

			if ($tempat_lahir != $tempat_lahir_lama) {
				$valuepair['tempat_lahir'] = $tempat_lahir;
			}

			if ($jenis_kelamin != $jenis_kelamin_lama) {
				$valuepair['jenis_kelamin'] = $jenis_kelamin;
			}

			if ($rt != $rt_lama) {
				$valuepair['rt'] = $rt;
			}

			if ($rw != $rw_lama) {
				$valuepair['rw'] = $rw;
			}

			if (!empty($nama) && $nama != $nama_lama) {
				$valuepair['nama'] = $nama;
			}

			if (!empty($nama_ibu) && $nama_ibu != $nama_ibu_lama) {
				$valuepair['nama_ibu_kandung'] = $nama_ibu;
			}

			if (!empty($nama_ayah) && $nama_ayah != $nama_ayah_lama) {
				$valuepair['nama_ayah'] = $nama_ayah;
			}

			if (count($valuepair) > 0) {
				if (!$this->Msiswa->tcg_ubah_profil_siswa($peserta_didik_id, $valuepair)) {
					$this->session->setFlashdata('error', "Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.");
					break;
				}
			}

			$this->session->setFlashdata('success', "Perubahan data telah berhasil disimpan.");

		} while(false);

		$redirect = $_GET["redirect"] ?? null; 
		if (empty($redirect)) {
			$redirect = "dapodik/beranda";
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

	// 	$this->load->model(array('Mdinas','Msetting'));
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