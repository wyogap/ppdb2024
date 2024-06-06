<?php

namespace App\Controllers\Playground;

use App\Controllers\Ppdb\PpdbController;
use App\Libraries\QRCodeLibrary;
use App\Libraries\Uploader;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use App\Models\Ppdb\Mdropdown;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Psr\Log\LoggerInterface;

class Map extends PpdbController {

    function index() {
        $data['page_title'] = "Map";
        $this->smarty->render("playground/map.tpl", $data);
    }

    function create_admin_sekolah() {
        $msekolah = new \App\Models\Ppdb\Sekolah\Mprofilsekolah();
        $msekolah->tcg_create_admin_sekolah();
    }
}