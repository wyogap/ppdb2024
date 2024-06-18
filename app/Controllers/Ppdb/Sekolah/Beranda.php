<?php
namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Beranda extends PpdbController {

    protected static $ROLE_ID = ROLEID_SEKOLAH;      

    protected $Msekolah;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        //$this->smarty = new SmartyLibrary();

        //load model
        $this->Msekolah = new Mprofilsekolah();

    }

	function index()
	{
		$sekolah_id = $this->session->get("sekolah_id");
        if (empty($sekolah_id)) {
			return $this->notauthorized();
		}

        $data['impersonasi_sekolah'] = $this->session->get("impersonasi_sekolah");
        if ($data['impersonasi_sekolah'] == 1) {
            $data['profil'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
        }
        
        //notifikasi tahapan
        $data['tahapan_aktif'] = $this->Mconfig->tcg_tahapan_pelaksanaan_aktif();
        $data['pengumuman'] = $this->Mconfig->tcg_pengumuman();

		$data['daftarkuota'] = $this->Msekolah->tcg_daftarkuota($sekolah_id);
		
        $data['use_leaflet'] = 1;

        //content template
        $data['content_template'] = 'beranda.tpl';

		$data['page'] = 'beranda';
		$data['page_title'] = 'Beranda';
        $this->smarty->render('ppdb/sekolah/ppdbsekolah.tpl', $data);
	}

	function dashboard()
	{
		$sekolah_id = $this->session->get("sekolah_id");

		$data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
		$data['daftarpenerapan'] = $this->Msekolah->tcg_daftar_penerapan($sekolah_id);

		$data['daftarkuota'] = $this->Msekolah->daftarkuota();
		$data['dashboardsekolah'] = $this->Msekolah->dashboardsekolah();
		$data['dashboardpenerapan'] = $this->Msekolah->dashboardpenerapan();
		$data['dashboardline'] = $this->Msekolah->dashboardline();
		$data['daftarpendaftar'] = $this->Msekolah->dashboardpendaftar();

		return view('ppdb/sekolah/dashboard/index',$data);
	}
      
}
