<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Test extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$data['page'] = "test";
		view('admin/test/magnifier',$data);

	}

	function magnifier()
	{
		$data['page'] = "test-magnifier";
		view('admin/test/magnifier',$data);

	}

	function rotate()
	{
		$data['page'] = "test-rotate";
		view('admin/test/rotate',$data);

	}


	
}
?>