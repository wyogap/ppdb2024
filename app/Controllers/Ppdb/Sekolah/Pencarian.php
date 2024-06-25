<?php

namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Pencarian extends PpdbController {

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
        $sekolah_id = $this->session->get('sekolah_id');
        if (empty($sekolah_id)) {
			return $this->notauthorized();
		}

        $data['daftarjenjang'] = $this->Mconfig->tcg_lookup_jenjang();
        $data['daftarasaldata'] = $this->Mconfig->tcg_lookup_asaldata();
        $data['daftarputaran'] = $this->Mconfig->tcg_putaran(JENJANGID_SMP);

        $data['impersonasi_sekolah'] = $this->session->get("impersonasi_sekolah");
        if ($data['impersonasi_sekolah'] == 1) {
            $data['profil'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
        }

        $data['use_datatable'] = 1;
        
        //content template
        $data['content_template'] = 'pencarian.tpl';

        $data['page_title'] = 'Pencarian Siswa';
        $this->smarty->render('ppdb/sekolah/ppdbsekolah.tpl', $data);

    }

	function cari()
	{
        $search = $this->request->getPostGet("search");

        //filter
        $jenjang = $this->request->getPostGet("f_jenjang");
        $asaldata = $this->request->getPostGet("f_asaldata");
        $inklusi = $this->request->getPostGet("f_inklusi");
        $afirmasi = $this->request->getPostGet("f_afirmasi");
        $json = array();

        //get the data
		$data = $this->Msekolah->tcg_cari_siswa($search, $jenjang, $asaldata, $inklusi, $afirmasi);
        if ($data == null) {
            $json['status'] = 0;
            $json['error'] = "Tidak ada hasil pencarian";
        }
        else {
            $json['status'] = 1;
            $json['data'] = $data;
        }

		echo json_encode($json, JSON_INVALID_UTF8_IGNORE);
	}

	function json() {
		// $tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		// if (empty($tahun_ajaran_id))
		// 	$tahun_ajaran_id = $this->tahun_ajaran_id;
		
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