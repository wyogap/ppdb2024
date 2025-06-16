<?php

namespace App\Controllers;

use App\Controllers\Core\CrudController;
use App\Models\Core\Crud\Mnavigation;
use App\Models\Core\Crud\Mtable;
use App\Models\Ppdb\Crud\Mpenerapansekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

//TODO: dont use general CRUD controller
class Crud extends CrudController {
	//protected static $PAGE_GROUP = 'admin';

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        
        //load library
        helper("ppdb");
        helper('functions');

    }

    public function index($params = array())
	{
		echo "not-authorized";
	}

    public function generatepenerapan() {
        //must be authenticated? 
		$isLoggedIn = !empty($this->session->get('user_id'));
		if (static::$AUTHENTICATED && !$isLoggedIn) {
			print_json_error("not-login");
            return;
		}

        //must be role_id? 
        $role_id = $this->session->get("role_id");
        if ($role_id != ROLEID_ADMIN && $role_id != ROLEID_SYSADMIN) {
            print_json_error("not-authorized");
            return;
        }

        $kuota_sekolah_id = $this->request->getGetPost("kuotaid");
        if (empty($kuota_sekolah_id)) {
            print_json_error("invalid-quota-id");
            return;
        }
        $model = new Mpenerapansekolah();
        $model->generate_penerapan($kuota_sekolah_id);

        $json = array();
        $json['success'] = 1;
        print_json_output($json);
    }
}
