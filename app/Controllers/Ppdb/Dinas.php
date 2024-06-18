<?php

namespace App\Controllers\Ppdb;

use App\Controllers\Core\CrudController;
use App\Models\Core\Crud\Mtable;
use App\Models\Core\Crud\Mnavigation;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Dinas extends CrudController {

    //only accessible to superadmin
    protected static $ROLE_ID = ROLEID_DINAS;      

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
    }
    
	public function index($params = array())
	{
		$this->home($params);
	}

	public function home() {
		$page_data['page_name']              = 'home';
		$page_data['page_title']             = 'Home';
		$page_data['page_icon']              = "mdi-view-dashboard-outline";
		$page_data['page_description']       = null;
		$page_data['query_params']           = null;

		$page_data['page_role']           	 = $this->session->get('page_role');;

		$mnavigation = new Mnavigation();
		$navigation = $mnavigation->get_navigation($this->session->get('role_id'));
		$page_data['navigation']	 = $navigation;

		//controller name
		if (!empty($this->session->get('page_role'))) {
			$controller = $this->session->get('page_role');
		}
		else {
			$controller = get_controller_path();
		}
		$page_data['controller'] = $controller;

        $page_data['use_datatable'] = 1;
		$page_data['use_geo'] = 1;
		$page_data['use_select2'] = 1;
		
		$this->smarty->render_theme('/welcome_message.tpl', $page_data);
	}

    function impersonasi() {
        $impersonasi_sekolah_id = $this->request->getPostGet("sekolah_id");
        $roleid = $this->session->get("role_id");
        if (!empty($impersonasi_sekolah_id) && ($roleid == ROLEID_DINAS || $roleid == ROLEID_ADMIN || $roleid == ROLEID_SYSADMIN)) {
            $this->session->set("sekolah_id", $impersonasi_sekolah_id);
            $this->session->set("impersonasi_sekolah", 1);
        }

        $msekolah = new \App\Models\Ppdb\Sekolah\Mprofilsekolah();
        $profilsekolah = $msekolah->tcg_profilsekolah($impersonasi_sekolah_id);
        if ($profilsekolah['bentuk'] == 'SD' or $profilsekolah['bentuk'] == 'MI') {
            return redirect()->to(site_url() ."ppdb/dapodik/penerimaan");
        }
        else if ($profilsekolah['bentuk'] == 'SMP') {
            return redirect()->to(site_url() ."ppdb/sekolah/verifikasi");
        }
        else {
            theme_404_with_navigation($this->navigation);
            return;
        }
	}

    function dashboard() {
		$mhome = new \App\Models\Ppdb\Mhome();

        $jenjang = $this->request->getPostGet("jenjang_id");
        $jenjang_id = 0;
        $status = "";
        if ($jenjang == "smp-negeri") {
            $jenjang_id = 3; $status = 'N';
        }
        else if ($jenjang == "smp-swasta") {
            $jenjang_id = 3; $status = 'S';
        }
        else if ($jenjang == "sd-negeri") {
            $jenjang_id = 2; $status = 'N';
        }
        else if ($jenjang == "sd-swasta") {
            $jenjang_id = 2; $status = 'S';
        }
        $putaran = $this->request->getPostGet("putaran");
        $penerapan_id = $this->request->getPostGet("penerapan_id");
        $kode_wilayah = $this->request->getPostGet("kode_wilayah");

        $data = array();
		$data['dashboardsummary'] = $mhome->tcg_dashboard_summary($jenjang_id, $status, $putaran, $penerapan_id, $kode_wilayah);
		$data['dashboardharian'] = $mhome->tcg_dashboard_pendaftarharian($jenjang_id, $status, $putaran, $penerapan_id, $kode_wilayah);

        echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
	}
	
	function pendaftaran() {
        $jenjang = $this->request->getPostGet("jenjang_id");
        $jenjang_id = 0;
        $status = "";
        if ($jenjang == "smp-negeri") {
            $jenjang_id = 3; $status = 'N';
        }
        else if ($jenjang == "smp-swasta") {
            $jenjang_id = 3; $status = 'S';
        }
        else if ($jenjang == "sd-negeri") {
            $jenjang_id = 2; $status = 'N';
        }
        else if ($jenjang == "sd-swasta") {
            $jenjang_id = 2; $status = 'S';
        }
        $putaran = $this->request->getPostGet("putaran");
        $penerapan_id = $this->request->getPostGet("penerapan_id");
        $kode_wilayah = $this->request->getPostGet("kode_wilayah");

		$mhome = new \App\Models\Ppdb\Mhome();
		$daftar = $mhome->tcg_dashboard_daftarpendaftaran($jenjang_id, $status, $putaran, $penerapan_id, $kode_wilayah);

		//manual echo json file to avoid memory exhausted
		echo '{"data":[';
		$first = true;
        foreach($daftar as $row) {
			if ($first) {
				echo json_encode($row);
				$first = false;
			} else {
				echo ",". json_encode($row);
			}
        }
		// while ($row = $daftar->unbuffered_row())
		// {
		// 	if ($first) {
		// 		echo json_encode($row);
		// 		$first = false;
		// 	} else {
		// 		echo ",". json_encode($row);
		// 	}
		// }
		echo ']}';
	}
}
