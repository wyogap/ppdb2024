<?php

namespace App\Controllers\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Mdropdown;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

defined('BASEPATH') OR exit('No direct script access allowed');

class Detil extends PpdbController {

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

        $mdropdown = new Mdropdown();
		$data['kabupaten'] = $mdropdown->tcg_kabupaten();
		$data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);
		
		view('dapodik/ubahdata/index',$data);
	}

	function simpan()
	{
		$pengguna_id = $this->session->get("pengguna_id");

		$kebutuhan_khusus = $_POST["kebutuhan_khusus"] ?? null;
		$peserta_didik_id = $_POST["peserta_didik_id"] ?? null;
		$approval = $_POST["approval"] ?? null;
		$kode_wilayah = $_POST["kode_wilayah"] ?? null;
		if($kode_wilayah==''){
			$kode_wilayah = $_POST["kode_desa"] ?? null;
		}
		$tanggal_lahir = $_POST["tanggal_lahir"] ?? null;
		$lintang = $_POST["lintang"] ?? null;
		$bujur = $_POST["bujur"] ?? null;
		$keterangan_approval = $_POST["keterangan_approval"] ?? null;
		$asal_data = $_POST["asal_data"] ?? null;

		if($this->Msiswa->tcg_ubahdata($peserta_didik_id, $approval, $keterangan_approval, $kode_wilayah, $tanggal_lahir, $lintang, $bujur, $pengguna_id)){
			if($kebutuhan_khusus!="Tidak ada"||$kebutuhan_khusus!=""){
				$this->Msiswa->tcg_updatekebutuhankhusus($peserta_didik_id, $kebutuhan_khusus, $pengguna_id);
			}
			$data['info'] = "<div class='alert alert-info alert-dismissable'>Perubahan data telah berhasil disimpan.</div>";
		}else{
			$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.</div>";
		}
		view('admin/perubahandata/index',$data);
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