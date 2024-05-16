<?php

namespace App\Controllers;

use App\Controllers\Core\CrudController;
use App\Models\Core\Crud\Mnavigation;
use App\Models\Core\Crud\Mtable;
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

    }

    public function index($params = array())
	{
		echo "not-authorized";
	}
}
