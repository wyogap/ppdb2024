<?php

namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Mdropdown;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

defined('BASEPATH') OR exit('No direct script access allowed');

class Ubahdata extends PpdbController {

    protected $Msekolah;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        //$this->smarty = new SmartyLibrary();

        //load model
        $this->Msekolah = new Mprofilsekolah();
        
        if($this->session->get('is_logged_in')==FALSE || $this->session->get('peran_id')!=ROLEID_SEKOLAH) {
			redirect(site_url() .'auth');
		}
    }

	function index()
	{
		$pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 

		$query = $this->Msekolah->tcg_detil_pendaftaran($pendaftaran_id);

		$peserta_didik_id = "";
		$sekolah_id = "";
		foreach($query->getResult() as $row) {
			$peserta_didik_id = $row->peserta_didik_id;
			$sekolah_id = $row->sekolah_id;
		}
		
		if ($sekolah_id != $this->session->get("sekolah_id")) {
			view('home/notauthorized');
			return;
		}

		$redirect = $_GET["redirect"] ?? null; 
		if (empty($redirect)) {
			$redirect = "sekolah/pendaftaran";
		}

        $mdropdown = new Mdropdown();
		$data['kabupaten'] = $mdropdown->tcg_kabupaten();
		$data['profilsiswa'] = $this->Msekolah->tcg_profilsiswa($peserta_didik_id);
		$data['redirect'] = $redirect;
		
		view('sekolah/ubahdata/index',$data);
	}

	function simpan()
	{
		$pengguna_id = $this->session->get("user_id");
		$sekolah_id = $this->session->get("sekolah_id");

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

		$approval = 1;
		$keterangan_approval = "Perubahan data oleh Admin SMP";

		do {

			//TODO: make sure this admin have permission to change the data
			$query = $this->Msekolah->tcg_pendaftaran($sekolah_id, $peserta_didik_id);
			if ($query->num_rows() == 0) {
				$this->session->setFlashdata('error', "Anda tidak memiliki hak untuk mengubah data siswa ini.");
				break;
			}

			if(!$this->Msekolah->tcg_ubahdata($peserta_didik_id, $approval, $keterangan_approval, $kode_wilayah, $tanggal_lahir, $lintang, $bujur, $pengguna_id)){
				$this->session->setFlashdata('error', "Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.");
				break;
			}

			$this->session->setFlashdata('success', "Perubahan data telah berhasil disimpan.");

		} while(false);

		$redirect = $_GET["redirect"] ?? null; 
		if (empty($redirect)) {
			$redirect = "sekolah/pendaftaran";
		}

		echo "$redirect";
		view('home/notauthorized');

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
	
}
?>