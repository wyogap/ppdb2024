<?php

namespace App\Controllers;

use App\Controllers\Core\BaseController;
use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Mhome;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Home extends BaseController
{
	function index()
	{	
        setlocale(LC_ALL, APP_LOCALE);
        date_default_timezone_set(APP_TIMEZONE);

		$data = array();
        $this->smarty->render("welcome.tpl", $data);

        // return redirect()->to(site_url() ."auth");
	}
	

}
