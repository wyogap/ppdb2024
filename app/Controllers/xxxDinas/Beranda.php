<?php
namespace App\Controllers\Dinas;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends PpdbController {
    //protected $Msekolah;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        if(empty($this->pengguna_id) || !$this->is_dinas) {
			redirect(site_url() .'auth');
		}
    }

	function index()
	{
		$mdinas = new Mdinas();
		$data['daftarpenerapan'] = $mdinas->tcg_daftarpenerapan();
		$data['dashboardwilayah'] = $mdinas->tcg_dashboardwilayah();
		$data['dashboardpenerapan'] = $mdinas->dashboardpenerapan();
		$data['dashboardline'] = $mdinas->dashboardline();
		$data['daftarpendaftar'] = $mdinas->dashboardpendaftar();

		return view('admin/beranda/index',$data);
	}

}
