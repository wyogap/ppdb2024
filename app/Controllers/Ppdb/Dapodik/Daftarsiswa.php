<?php

namespace App\Controllers\Ppdb\Dapodik;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Daftarsiswa extends PpdbController {

    protected $Msekolah;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        //$this->smarty = new SmartyLibrary();

        //load model
        $this->Msekolah = new Mprofilsekolah();
        
        //role-based permission
        //static::$ROLE_ID = ROLEID_DAPODIK;
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
		$sekolah_id = $this->session->get("sekolah_id");

        //DEBUG
        $sekolah_id = 'C028FC55-2DF5-E011-9BB6-97E59C2D3588';
        $this->session->set("sekolah_id", $sekolah_id);
        $this->putaran = 2;
        $this->session->set('putaran_aktif', $this->putaran);
        $upload_dokumen = 0;
        //END DEBUG

        //notifikasi tahapan
        $data['tahapan_aktif'] = $this->Msetting->tcg_tahapan_pelaksanaan_aktif()->getResultArray();
        $data['pengumuman'] = $this->Msetting->tcg_pengumuman()->getResult();

        $data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
		// $data['daftarkuota'] = $this->Msekolah->tcg_daftarkuota();

        //Debug
        $data['nama_pengguna'] = "Wahyu Yoga Pratama";
        $data['username'] = "wyogap@gmail.com";
        $data['notif_ganti_password'] = 1;
        //END DEBUG

        //content template
        $data['content_template'] = 'daftarsiswa.tpl';

		$data['page_title'] = 'Daftar Siswa';
        $this->smarty->render('ppdb/dapodik/ppdbdapodik.tpl', $data);
	}

	function json() {
		$sekolah_id = $this->session->get('sekolah_id');
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Msekolah->tcg_daftar_siswa($sekolah_id, $tahun_ajaran_id)->getResultArray(); 
			echo json_encode($data);	
		}

	}

	// function json() {
		
	// 	$action = $_POST["action"] ?? null;
	// 	if (empty($action) || $action=='view') {
	// 		$data['error'] = "not-implemented"; 
	// 		echo json_encode($data);	
	// 	}
	// 	else if ($action=='edit'){
	// 		$data['error'] = "not-implemented"; 
	// 		echo json_encode($data);	
    //     }
    //     else if ($action=='remove') {
	// 		$data['error'] = "not-implemented"; 
	// 		echo json_encode($data);	
    //     }
    //     else if ($action=='create') {
	// 		$data['error'] = "not-implemented"; 
	// 		echo json_encode($data);	
    //     }
	// 	else {
	// 		$data['error'] = "not-implemented"; 
	// 		echo json_encode($data);	
	// 	}

	// }
	
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