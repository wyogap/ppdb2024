<?php

namespace App\Controllers\Siswa;

use App\Controllers\Ppdb\PpdbController;
use App\Libraries\QRCodeLibrary;
use App\Libraries\Uploader;
use App\Models\Ppdb\Mdropdown;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Psr\Log\LoggerInterface;

class Prestasi extends PpdbController {

    protected $Msiswa;

	// public function __construct()
	// {
	// 	parent::__construct();
	// 	//return redirect()->to("Cinfo");
	// 	if($this->session->get('isLogged')==FALSE||
    //         ($this->session->get('peran_id')!=1&&$this->session->get('peran_id')!=4)) {
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
        $this->Msiswa = new Mprofilsiswa();
        
        if($this->session->get('is_logged_in')==FALSE || $this->session->get('peran_id')!=ROLEID_SISWA) {
			redirect(site_url() .'auth');
		}
    }

	function index()
	{
        return redirect()->to("siswa/profile");
    }

    function json() {
        $peserta_didik_id = $this->session->get("user_id");
		$tahun_ajaran_id = $this->tahun_ajaran_id;

        $mdropdown = new Mdropdown();

        $action = $_POST["action"] ?? null; 
		if (empty($action) || $action=='view') {
            $data['data'] = $this->Msiswa->tcg_daftar_prestasi($peserta_didik_id)->getResultArray();
            //var_dump($data['data']);
            foreach($data['data'] as $idx=>$row) {
                if (!empty($row['catatan'])) {
                    $data['data'][$idx]['catatan'] = nl2br(trim($row['catatan']));
                }
                else {
                    $data['data'][$idx]['catatan'] = "";
                }
            }

            $data['options']['skoring_id'] = $mdropdown->tcg_select_prestasi($tahun_ajaran_id)->getResultArray();

            $files = $this->Msiswa->tcg_daftar_prestasi_files($peserta_didik_id)->getResultArray();
            $data['files']['files'] = array();
            foreach($files as $row) {
                $id = $row['id'];
                $data['files']['files'][$id] = $row;
                $data['files']['files'][$id]['file_path'] = base_url(). $row['path'];
                $data['files']['files'][$id]['web_path'] = base_url(). $row['web_path'];
                $data['files']['files'][$id]['thumbnail_path'] = base_url(). $row['thumbnail_path'];
            }

            echo json_encode($data);
        }
		else if ($action=='edit'){
			$values = $_POST["data"] ?? null; 
            if ($values == null) {
                $data['data'] = array();
                $data['error'] = "no-data";
                echo json_encode($data);
                return;
            }

            $error_msg = "";
			foreach ($values as $key => $valuepair) {
                if (empty($valuepair['dokumen_pendukung'])) {
                    continue;
                };
                $doc_id = $valuepair['dokumen_pendukung'];

                $status = $this->Msiswa->tcg_ubah_dokumen_prestasi($key, $doc_id);
                if ($status == 1) {
                    $data['data'] = $this->Msiswa->tcg_prestasi_detil($peserta_didik_id, $key)->getResultArray(); 
                }
                else {
                    $data['data'] = "error";
                }
			}

            echo json_encode($data);	
        }
        else if ($action=='remove') {
			$values = $_POST["data"] ?? null; 
            if ($values == null) {
                $data['data'] = array();
                $data['error'] = "no-data";
                echo json_encode($data);
                return;
            }

            $error_msg = "";
			foreach ($values as $key => $valuepair) {
                $data['data'] = $this->Msiswa->tcg_hapus_prestasi($peserta_didik_id, $key);
			}

            $data['data'] = array(); 
			echo json_encode($data);	
        }
        else if ($action=='create') {
            $values = $_POST["data"] ?? null; 
            if ($values == null) {
                $data['data'] = array();
                $data['error'] = "no-data";
                echo json_encode($data);
                return;
            }

            if (empty($values[0]['skoring_id']) || empty($values[0]['uraian']) || empty($values[0]['dokumen_pendukung'])) {
                $data['error'] = "Data wajib belum diisi";
                echo json_encode($data);
                return;
            }

            $key = $this->Msiswa->tcg_prestasi_baru($peserta_didik_id, $values[0]);
            if ($key == 0) {
                $data['error'] = $this->Msiswa->error();
            } else {
                $data['data'] = $this->Msiswa->tcg_prestasi_detil($peserta_didik_id, $key)->getResultArray(); 
            }

            echo json_encode($data);
        }
        else if ($action == "upload") {

            $kelengkapan_id = 8;

            $uploader = new Uploader();
            $fileObj = $uploader->upload($_FILES['upload']);

            $data = array();
            if(!empty($fileObj['error'])) {
                $data['error'] = $fileObj['error'];
            } else {
                $data = array("data"=>array(),"files"=>array("files"=>array($fileObj['id']=>$fileObj)),"upload"=>array("id"=>$fileObj['id']));
            }

            echo json_encode($data);
            return;
        }   

    }

	function suratpernyataan() {
		$peserta_didik_id = $this->session->get("user_id");
		$username = $this->session->get("username");
		$peran_id = $this->session->get("peran_id");
		$nisn = $this->session->get("nisn");
		
		$qrcode = new QRCodeLibrary();
		$config['cacheable'] = true; //boolean, the default is true
        // $config['cachedir'] = './qrcode/'; //string, the default is application/cache/
        // $config['errorlog'] = './qrcode/'; //string, the default is application/logs/
        // $config['imagedir'] = './qrcode/'; //direktori penyimpanan qr code
        $config['quality'] = true; //boolean, the default is true
        $config['size'] = '1024'; //interger, the default is 1024
        $config['black'] = array(224,255,255); // array, default is array(255,255,255)
        $config['white'] = array(70,130,180); // array, default is array(0,0,0)
        $qrcode->initialize($config);
 
        $params['data'] = $peserta_didik_id.",".$username.",".$peran_id.",".$nisn; //data yang akan di jadikan QR CODE
        $params['level'] = 'M'; //H=High
        $params['size'] = 10;
        $params['savename'] = $peserta_didik_id.'.png';
        $qrcode->generate($params); // fungsi untuk generate QR CODE
		
		$data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);
        $data['tahun_ajaran_aktif'] = $this->session->get('tahun_ajaran_aktif');
		
        $view = \Config\Services::renderer();
		$html = $view->render('siswa/beranda/suratpernyataan',$data);
		
		$dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("SuratPernyataan.pdf", array("Attachment"=>0));
	}

}
?>