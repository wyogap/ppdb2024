<?php

namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Pengajuanakun extends PpdbController {

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

        $data['impersonasi_sekolah'] = $this->session->get("impersonasi_sekolah");
        if ($data['impersonasi_sekolah'] == 1) {
            $data['profil'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
        }

        $data['use_datatable'] = 1;
        
        //content template
        $data['content_template'] = 'pengajuanakun.tpl';

        $data['page_title'] = 'Pengajuan Akun';
        $this->smarty->render('ppdb/sekolah/ppdbsekolah.tpl', $data);

    }

	function json() {
        $data = $this->Msekolah->tcg_pengajuan_akun();
        $json['status'] = 1;
        $json['data'] = $data;

		echo json_encode($json, JSON_INVALID_UTF8_IGNORE);
	}

    function approve() {
        $user_id = $this->request->getPostGet('userid');
        $this->Msekolah->tcg_approve_akun($user_id);
        $json['status'] = 1;

        //audittrail
        $msiswa = new \App\Models\Ppdb\Siswa\Mprofilsiswa();
        $profil = $msiswa->tcg_profilsiswa_from_userid($user_id);
        audit_siswa($profil, "PERSETUJUAN AKUN", "Akun an. " +$profil['nama']+ " disetujui.");
        
		echo json_encode($json, JSON_INVALID_UTF8_IGNORE);
    }
	
}
?>