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

		echo json_encode($json, JSON_INVALID_UTF8_IGNORE);
    }
	
}
?>