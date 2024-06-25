<?php

namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Kandidatswasta extends PpdbController {

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
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null; 
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;
	
        $sekolah_id = $this->session->get('sekolah_id');
        if (empty($sekolah_id)) {
			return $this->notauthorized();
		}

        $data['impersonasi_sekolah'] = $this->session->get("impersonasi_sekolah");
        if ($data['impersonasi_sekolah'] == 1) {
            $data['profil'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
        }
        $data['use_datatable'] = 1;

        $data['daftarputaran'] = $this->Mconfig->tcg_putaran(JENJANGID_SMP);

        //content template
        $data['content_template'] = 'kandidatswasta.tpl';

        $data['page_title'] = 'Kandidat Siswa';
        $this->smarty->render('ppdb/sekolah/ppdbsekolah.tpl', $data);

	}

	function json() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null; 
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$sekolah_id = $this->session->get("sekolah_id");

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Msekolah->tcg_kandidatswasta($sekolah_id, $tahun_ajaran_id)->getResultArray(); 
			echo json_encode($data);	
		}
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}

	}

	
}
?>