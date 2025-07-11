<?php

namespace App\Controllers;

use App\Controllers\Core\CrudController;
use App\Models\Core\Crud\Mnavigation;
use App\Models\Core\Crud\Mtable;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Admin extends CrudController {
	//protected static $PAGE_GROUP = 'admin';

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //only accessible to superadmin
        static::$ROLE_ID = ROLEID_ADMIN;

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

        //smarty: general setting
        $arr = $this->setting->list_group('ppdb');
        foreach($arr as $val) {
            $page_data[ $val['name'] ] = $val['value'];
        }
        
        $mconfig = new \App\Models\Ppdb\Mconfig();
        $page_data['daftarjenjang'] = $mconfig->tcg_jenjang();
        $page_data['jenjang'] = 3;  //SMP
        $page_data['daftarputaran'] = $mconfig->tcg_putaran();
        $page_data['putaran'] = $this->session->get('putaran_aktif');
		$page_data['daftarpenerapan'] = $mconfig->tcg_penerapan();
		$page_data['daftarkecamatan'] = $mconfig->tcg_kecamatan();
        
        $page_data['use_datatable'] = 1;
		$page_data['use_geo'] = 1;
		$page_data['use_select2'] = 1;
        $page_data['use_highchart'] = 1;
        
		$this->smarty->render_theme('/ppdb/admin/dashboard.tpl', $page_data);
	}

	public function api() {
        $action = $_POST["action"] ?? null; 
		if ($action=='generate_columns') {
			
            $values = $_POST["data"] ?? null; 
            if ($values == null) {
                $json['status'] = 0;
                $json['error'] = 'no-data';
			    echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
                return;
            }

            $mtable = new Mtable();
        
			$json['data'] = array();
			foreach ($values as $key => $valuepair) {
				$mtable->generate_columns($key);
            }

			$json['status'] = 1;
			echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
		}
        else if ($action == 'clone_table') {
			
            $values = $_POST["data"] ?? null; 
            if ($values == null) {
                $json['status'] = 0;
                $json['error'] = 'no-data';
			    echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
                return;
            }

            $mtable = new Mtable();

			$error_msg = "";
			$json['data'] = array();
			foreach ($values as $key => $valuepair) {
				$new_key = $mtable->clone($key);
                if ($new_key == 0) {
                    continue;
                }
                $json['data'][] = $mtable->detail($new_key);
            }

			$json['status'] = 1;
			echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
        }
    }
}
