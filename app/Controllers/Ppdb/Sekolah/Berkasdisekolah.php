<?php

namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Berkasdisekolah extends PpdbController {

    protected static $ROLE_ID = ROLEID_SEKOLAH;      

    protected $Msekolah;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load model
        $this->Msekolah = new Mprofilsekolah();
    }
    
	function index()
	{
		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

        $mconfig = new \App\Models\Ppdb\Mconfig();
        $data['daftarputaran'] = $mconfig->tcg_putaran();

        $data['sekolah_id'] = $sekolah_id;
		$data['waktuverifikasi'] = $this->Mconfig->tcg_cek_waktuverifikasi();
        $data['impersonasi_sekolah'] = $this->session->get("impersonasi_sekolah");
        if ($data['impersonasi_sekolah'] == 1) {
            $data['profil'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
        }
	
        $data['use_datatable'] = 1;

        //content template
        $data['content_template'] = 'berkasdisekolah.tpl';

        $data['page_title'] = 'Berkas Di Sekolah';
        $this->smarty->render('ppdb/sekolah/ppdbsekolah.tpl', $data);
	}

	function json() {
		// $tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		// if (empty($tahun_ajaran_id))
		// 	$tahun_ajaran_id = $this->tahun_ajaran_id;

		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		$action = $_POST["action"] ?? null; 
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Msekolah->tcg_berkasdisekolah($sekolah_id)->getResultArray(); 
			echo json_encode($data);	
		}
		else if ($action=='cabutberkas') {
            $peserta_didik_id = $this->request->getGetPost("peserta_didik_id");
            $keterangan = $this->request->getGetPost("keterangan");
            $this->Msekolah->tcg_cabutberkas($peserta_didik_id, $keterangan);
            $data['status'] = 1;
            echo json_encode($data);
        }
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}

	}

    function resetpassword() {
        $data = $this->request->getPost("data");

        $status = array();
        foreach($data as $k => $v) {
            $pin1 = $v["pwd1"];
            $pin2 = $v["pwd2"];
            if ($pin1 != $pin2) {
                print_json_error("Password baru tidak sama. Silahkan ulangi lagi.");
            }
    
            $sekolah_id = $this->session->get("sekolah_id");
    
            $msiswa = new \App\Models\Ppdb\Siswa\Mprofilsiswa();
            $profil = $msiswa->tcg_profilsiswa($k);
            if ($profil['lokasi_berkas'] != $sekolah_id) {
                print_json_error("Tidak diperbolehkan mengubah Password.");
            }
    
            $user_id = $this->Msekolah->tcg_userid_from_pesertadidikid($k);
    
            $mauth = new \App\Models\Core\Crud\Mauth();
            if($mauth->reset_password($user_id, $pin1) == 0) {
                print_json_error("Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.");
            }
            
            //audit trail
            audit_siswa($profil, "RESET PASSWORD", "Reset Password oleh Panitia SMP");
        }

        print_json_output(null);
    }	
}
?>