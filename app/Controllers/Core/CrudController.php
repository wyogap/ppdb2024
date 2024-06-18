<?php

namespace App\Controllers\Core;

use App\Controllers\Core\BaseController;
use App\Libraries\SmartyLibrary;
use App\Libraries\Uploader;
use App\Models\Core\Crud\Mnavigation;
use App\Models\Core\Crud\Mpages;
use App\Models\Core\Crud\Mpermission;
use App\Models\Core\Crud\Msession;
use App\Models\Core\Crud\Mtable;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\App;
use Psr\Log\LoggerInterface;

//defined('BASEPATH') OR exit('No direct script access allowed');

abstract class CrudController extends BaseController {
	protected static $PAGE_GROUP = null;
	protected static $AUTHENTICATED = true;
	protected static $ROLE_ID = 0;

    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_NOT_MODIFIED = 304;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_NOT_ACCEPTABLE = 406;
    const HTTP_INTERNAL_ERROR = 500;

    protected $Mpages;
    protected $Mpermission;

    protected $is_json;
    protected $navigation;

    protected $controller = null;
    protected $method = "";
    protected $params = array();

	abstract function index($params = array());

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load model
        $this->Mpages = new Mpages();
        $this->Mpermission = new Mpermission();

        //helper
        helper('functions');

        //URI params
        $segments = $this->request->getUri()->getSegments();
        $total = count($segments);
        $this->controller = strtolower(mb_basename(get_class($this)));
        
        // var_dump($segments); 
        // var_dump($this->controller); 

        $this->method = "";
        $this->params = array();
        for($i=0; $i<$total; $i++) {
            $segment = strtolower($segments[$i]);
            if ($segment == $this->controller) {
                if ($i+1<$total) {
                    $this->method=$segments[$i+1];
                }
                else {
                    $this->method='';
                }
                for($j=$i+2; $j<$total; $j++) {
                    $this->params[] = $segments[$j];
                }
                break;
            }
        }

        //is-json
        $json_segment = $this->method=='json' || (array_search('json', $segments) != FALSE);
        $json_param = !empty($this->request->getGetPost("json"));

        $this->is_json = ($json_segment || $json_param);

        //TODO: need to tell upstream: no need to continue processing this request
        //initController is called in __construct()
        //But the constructor should not return anything - it only has property setting

    }
	
	public function _remap($method)
	{
        //$params = $this->get_params($method);

        //must be authenticated? 
		$isLoggedIn = !empty($this->session->get('user_id'));
		if (static::$AUTHENTICATED && !$isLoggedIn) {
			if ($this->is_json) {
				$this->json_not_login();
                return;
			} else {
				return redirect()->to(site_url() .'auth');
			}
		}

        //must be role_id?
        $role_id = $this->session->get("role_id");
        if (!empty(static::$ROLE_ID) && 
                //bukan admin
                (ROLEID_ADMIN != $role_id && ROLEID_SYSADMIN != $role_id) &&
                //current role is not static::$ROLE_ID
                ((!is_array(static::$ROLE_ID) && static::$ROLE_ID != $role_id) || 
                //static::$ROLE_ID can be an array
                (is_array(static::$ROLE_ID) && array_search($role_id,static::$ROLE_ID) === FALSE))) {
            if ($this->is_json) {
				$this->json_not_authorized();
                return;
			} else {
                //navigation
                $this->navigation = $this->get_navigation();
                theme_403_with_navigation($this->navigation);		//not-authorized
                return;
            }
		}

		if (empty($method)) {
			return $this->index($this->params);
		}

		if (method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), $this->params);
		}

        //navigation
        $this->navigation = $this->get_navigation();
        
        return $this->table($method, $this->params);
	}

	protected function table($name = '', $params = array())
	{
		if (empty($name)) {
			$this->index($params);
			return;
		}

		//check for permission
		if (!$this->Mpermission->can_view($name)) {
			if (isset($params) && count($params) > 0 && ($params[0] == 'lookup' || $params[0] == 'json')) {
                $this->json_not_authorized();
			}
			theme_403_with_navigation($this->navigation);		//not-authorized
			return;
		}
		
		$page = $this->Mpages->get_page($name, static::$PAGE_GROUP);
		if ($page == null) {
			if (isset($params) && count($params) > 0 && ($params[0] == 'lookup' || $params[0] == 'json')) {
                $this->json_invalid_page();
			}
			theme_404_with_navigation($this->navigation);
			return;
		}
        
        //update relative path for custom template
		if (!empty($page['header_view'])) 		$page['header_view'] = $this->smarty->get_template_path($page['header_view']);
		if (!empty($page['footer_view'])) 		$page['footer_view'] = $this->smarty->get_template_path($page['footer_view']);
		if (!empty($page['custom_view'])) 		$page['custom_view'] = $this->smarty->get_template_path($page['custom_view']);

        //call appropriate function
        if ($page['page_type']=='form' && !empty($page['crud_table_id'])&& (!isset($params) || count($params) == 0)) {
            if ($this->Mpermission->can_edit($page['name'])) {
                //edit
                $this->table_edit($page);
            }
            else {
                //detail
                $this->table_detail($page);
            }
            return;
        }

		if (!empty($page['crud_table_id']) && !empty($params)) {
            $action = $params[0];
       
			//CRUD page
			if ($action == 'add') {
				$this->table_add($page);
				return;
			}
            else if ($action == 'edit') {
				unset($params[0]);
	
				$id = array_shift($params);
				if ($id != null)		$this->table_edit($page, $id);
				else					$this->table_add($page);
				
				return;
			}
            else if ($action == 'detail') {
				unset($params[0]);

                $id = array_shift($params);
				$this->table_detail($page, $id);
				return;
			}
			else if ($action == 'lookup') {
				$this->table_lookup($page);
				return;
			}
            else if ($action == 'json') {
				$this->table_json($page);
				return;
			} 
            else if ($action == 'subtable') {
				unset($params[0]);
                
				$subtable_id = array_shift($params);
                //$parent_id = array_shift($params);
                $parent_key = array();
                $parent_key[] = $this->request->getGet("key");
                $parent_key[] = $this->request->getGet("key2");
				$this->table_subtable($page, $subtable_id, $parent_key);
				return;
			}	
		}

		//controller name
		if (!empty($this->session->get('page_role'))) {
			$controller = $this->session->get('page_role');
		}
		else {
			$controller = get_controller_path();
		}
        
		$page_data['controller'] = $controller;

		$page_data['page_name']              = $page['name'];
		$page_data['page_title']             = $page['page_title'];
		$page_data['page_icon']              = $page['page_icon'];
		$page_data['page_description']       = $page['page_description'];
		$page_data['page_tag']       		 = $page['page_tag'];
		$page_data['query_params']           = $params;

		$page_data['page_role']           	 = $this->session->get('page_role');

		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];
        $page_data['header_view']            = $page['header_view'];
        $page_data['footer_view']            = $page['footer_view'];
		
		//easy access
		$page_data['page']			 = $page; 
		$page_data['navigation']	 = $this->navigation;
		//$page_data['session']		 = $session;
		//$page_data['userdata']		 = $this->session->get();

		//dynamic loading
		if ($page['use_datatable'])			$page_data['use_datatable'] = 1;
		if ($page['use_editor'])			$page_data['use_editor'] = 1;
		if ($page['use_geo'])				$page_data['use_geo'] = 1;
		if ($page['use_upload'])			$page_data['use_upload'] = 1;
		if ($page['use_wysiwyg'])			$page_data['use_wysiwyg'] = 1;
		if ($page['use_calendar'])			$page_data['use_calendar'] = 1;
		if ($page['use_select2'])			$page_data['use_select2'] = 1;

		$page_type = $page['page_type'];
		if ($page_type == 'custom') {
			$template = $page['custom_view'];
			if (empty($template)) {
				//default template
				$template = 'welcome.tpl';
			}

			//for custom page, tablemeta is optional
			$model = null;
			if (!empty($page['crud_table_id'])) {
				$model = $this->get_model($page['crud_table_id']);
			}

			if ($model != null) {
				$tablemeta = $model->tablemeta();
				//easy access for everything
				$page_data['crud']			 = $tablemeta; 
			}

			$this->smarty->render_theme($template, $page_data);
			
			return;
		}

        //crud pages
        $model = $this->get_model($page['crud_table_id']);
        if ($model == null) {
            theme_404_with_navigation($this->navigation);
            return;
        }
	
		$tablemeta = $model->tablemeta();

		//pass on the GET params
        $getParams = $this->request->getGet();
		if (count($getParams) > 0) {
			$page_data['get_params'] = http_build_query($getParams);
		}

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = site_url() .$controller .'/'. $name;

		$tablemeta['ajax'] = $base_ajax_url .'/json';
		if (!empty($page_data['get_params'])) {
			$tablemeta['ajax'] .= '?' .$page_data['get_params'];
		}

		$tablemeta['crud_url'] = $base_ajax_url;

		//var_dump($tablemeta['crud_url']);
		//var_dump($tablemeta["columns"]);
		//var_dump($tablemeta["editor_columns"]);

		//upload columns
		foreach($tablemeta["editor_columns"] as $key => $col) {
			if ($col["edit_type"] == "tcg_upload") {
				//make sure ajax is correct
				if (empty($col['edit_attr'])) {
					$tablemeta["editor_columns"][$key]['edit_attr'] = array (
						"ajax"	=> $base_ajax_url .'/json'
					);
				}
				else {
					$tablemeta["editor_columns"][$key]['edit_attr']['ajax'] = $base_ajax_url .'/json';
				}
			}
		}

		//override paging size if necessary
		if (!empty($page['page_size'])) {
			$tablemeta['page_size'] = $page['page_size'];
		}

		if ($page_type == 'table') {
			$template = '/core/crud/table.tpl';
			//if it is crud page, always enable datatable
			$page_data['use_datatable'] = 1;
			$page_data['use_editor'] = 1;
			$page_data['use_select2'] = 1;
		}
		// else if ($page_type == 'form') {
		// 	$template = '/core/crud/form.tpl';
		// 	//get detail key
		// 	$key = $page['userdata_key'];
		// 	if (!empty($key)) {
		// 		//actual key is from userdata
		// 		$key = $this->session->get($key);
		// 		$detail = $model->detail($key);
		// 	}
		// 	else if (isset($params) && count($params) > 0) {
		// 		//get from param
		// 		$key = $params[0];
		// 		$detail = $model->detail($key);
		// 	}
		// 	else {
		// 		$detail = array();
		// 	}
		// 	$page_data['detail'] = $detail; 
		// 	//if it is crud page, always enable datatable
		// 	$page_data['use_datatable'] = 1;
		// 	$page_data['use_editor'] = 1;
		// 	$page_data['use_select2'] = 1;
		// }
		else {
			$template = '/core/crud/table.tpl';
		}

		//easy access for everything
		$page_data['crud']			 = $tablemeta; 

		//var_dump($tablemeta); exit;

		//get subtables if necessary
		//IMPORTANT: since Mpage instance is shared, the following function will modify the tablename inside Mpages
		$subtables = $this->Mpages->subtables($page['id'], true);
        
		foreach($subtables as $key => $val) {
			$subtables[$key]['crud']['ajax'] = $base_ajax_url .'/subtable/'. $val['subtable_id'];
			//override paging size if necessary
			if (!empty($val['page_size'])) {
				$subtables[$key]['crud']['page_size'] = $val['page_size'];
			}
			//override always autoload
			$subtables[$key]['crud']['initial_load'] = false;
            //since it is subtable, set filters to be the same as the parent
            //then this can be used in parameter replace
            $subtables[$key]['crud']['filter_columns'] = $tablemeta['filter_columns'];
		}

		//easy access
		$page_data['subtables']		 = $subtables;

		//get page navigation
		$page_navigations = $this->Mpages->page_navigations($page['id']);
		$page_data['page_navigations'] = $page_navigations;
		
		//permission
		$permissions = array (
			'allow_edit'	=> $this->Mpermission->can_edit($name)
		);
		$page_data['permissions']	= $permissions;

		//custom template
		if (!empty($page['custom_view'])) {
			$template = $page['custom_view'];
		}
	
		//echo json_encode($page_data);
		$this->smarty->render_theme($template, $page_data);
	}

	protected function table_add($page) {
		if (empty($page['crud_table_id'])) {
			theme_404_with_navigation($this->navigation);		//not-found
			return;
		}

		if (!$this->Mpermission->can_add($page['name'])) {
			theme_403_with_navigation($this->navigation);		//not-authorized
			return;
		}

		//controller name
		if (!empty($this->session->get('page_role'))) {
			$controller = $this->session->get('page_role');
		}
		else {
			$controller = get_controller_path();
		}
		$page_data['controller'] = $controller;

		$page_data['page_name']              = $page['name'];
		//$page_data['page_title']             = $page['page_title'];
		//$page_data['page_icon']              = $page['page_icon'];

		$page_data['page_role']           	 = $this->session->get('page_role');

		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $this->smarty->get_template_path($page['header_view']);
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $this->smarty->get_template_path($page['footer_view']);
		
		//easy access
		$page_data['page']			 = $page; 
		$page_data['navigation']	 = $this->navigation;

		//crud pages
		$model = $this->get_model($page['crud_table_id']);
		if ($model == null) {
			theme_404_with_navigation($this->navigation);
			return;
		}
		
		$tablemeta = $model->tablemeta();

		$page_data['page_title']             = __("Buat") ." ". $tablemeta['title'];
		$page_data['page_icon']              = $page['page_icon'];

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = site_url() .$controller .'/'. $page['name'];

		$tablemeta['ajax'] = $base_ajax_url .'/json';
		$tablemeta['crud_url'] = $base_ajax_url;

		//upload columns
		foreach($tablemeta["editor_columns"] as $key => $col) {
			if ($col["edit_type"] == "tcg_upload") {
				//make sure ajax is correct
				if (empty($col['edit_attr'])) {
					$tablemeta["editor_columns"][$key]['edit_attr'] = array (
						"ajax"	=> $base_ajax_url .'/json'
					);
				}
				else {
					$tablemeta["editor_columns"][$key]['edit_attr']['ajax'] = $base_ajax_url .'/json';
				}
			}
		}

		//show link back to table
        if ($page['page_type'] == 'form') {
            $page_data['show_table_link'] = false;
        }
        else {
            $page_data['show_table_link'] = true;
        }

		//override paging size if necessary
		if (!empty($page['page_size'])) {
			$tablemeta['page_size'] = $page['page_size'];
		}

		$page_data['crud']			 = $tablemeta; 

		// //get subtables if necessary
		// $subtables = $this->Mpages->subtables($page['id'], true);
		// foreach($subtables as $key => $val) {
		// 	$subtables[$key]['crud']['ajax'] = $base_ajax_url .'/subtable/'. $val['subtable_id'];
		// 	//override paging size if necessary
		// 	if (!empty($val['page_size'])) {
		// 		$subtables[$key]['crud']['page_size'] = $val['page_size'];
		// 	}
		// }

		//dynamic loading
		$page_data['use_datatable'] = 1;
		$page_data['use_editor'] = 1;
		if ($page['use_geo'])				$page_data['use_geo'] = 1;
		if ($page['use_upload'])			$page_data['use_upload'] = 1;
		if ($page['use_wysiwyg'])			$page_data['use_wysiwyg'] = 1;
		if ($page['use_calendar'])			$page_data['use_calendar'] = 1;
		if ($page['use_select2'])			$page_data['use_select2'] = 1;

		$page_data['detail'] = null; 
		$page_data['form_mode'] = 'add';

		$template = '/core/crud/form.tpl';
		$this->smarty->render_theme($template, $page_data);
	}

	protected function table_edit($page, $id=null) {
		if (empty($page['crud_table_id'])) {
			theme_404_with_navigation($this->navigation);		//not-found
			return;
		}

		if (!$this->Mpermission->can_edit($page['name'])) {
			theme_403_with_navigation($this->navigation);		//not-authorized
			return;
		}

		//controller name
		if (!empty($this->session->get('page_role'))) {
			$controller = $this->session->get('page_role');
		}
		else {
			$controller = get_controller_path();
		}
		$page_data['controller'] = $controller;

		$page_data['page_name']              = $page['name'];
		$page_data['page_role']           	 = $this->session->get('page_role');

		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $this->smarty->get_template_path($page['header_view']);
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $this->smarty->get_template_path($page['footer_view']);
		
		//easy access
		$page_data['page']			 = $page; 
		$page_data['navigation']	 = $this->navigation;

		//crud pages
		$model = $this->get_model($page['crud_table_id']);
		if ($model == null) {
			theme_404_with_navigation($this->navigation);
			return;
		}
		
		$tablemeta = $model->tablemeta();

		$page_data['page_title']             = __("Ubah") ." ". $tablemeta['title'];
		$page_data['page_icon']              = $page['page_icon'];

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = site_url() .$controller .'/'. $page['name'];

		$tablemeta['ajax'] = $base_ajax_url .'/json';
		$tablemeta['crud_url'] = $base_ajax_url;

		//upload columns
		foreach($tablemeta["editor_columns"] as $key => $col) {
			if ($col["edit_type"] == "tcg_upload") {
				//make sure ajax is correct
				if (empty($col['edit_attr'])) {
					$tablemeta["editor_columns"][$key]['edit_attr'] = array (
						"ajax"	=> $base_ajax_url .'/json'
					);
				}
				else {
					$tablemeta["editor_columns"][$key]['edit_attr']['ajax'] = $base_ajax_url .'/json';
				}
			}
		}

		//show link back to table
        if ($page['page_type'] == 'form') {
            $page_data['show_table_link'] = false;
        }
        else {
            $page_data['show_table_link'] = true;
        }

		//override paging size if necessary
		if (!empty($page['page_size'])) {
			$tablemeta['page_size'] = $page['page_size'];
		}

		$page_data['crud']			 = $tablemeta; 

		//get detail
		$detail = null;

		$key = $page['userdata_key'];
		if (!empty($key)) {
			//actual key is from userdata
			$key = $this->session->get($key);
			$detail = $model->detail($key);
		}
		else if ($id != null) {
			//get from param
			$detail = $model->detail($id);
		}
		
		$page_data['detail'] = $detail; 

		if ($detail != null) {
			$parent_key = $detail[ $tablemeta['key_column'] ];

			//get subtables if necessary
			$subtables = $this->Mpages->subtables($page['id'], true);
			foreach($subtables as $key => $val) {
				//override paging size if necessary
				if (!empty($val['page_size'])) {
					$subtables[$key]['crud']['page_size'] = $val['page_size'];
				}
				//override always autoload
				$subtables[$key]['crud']['initial_load'] = true;
				$subtables[$key]['crud']['parent_key'] = $parent_key;
				//override ajax with the parent key
				$subtables[$key]['crud']['ajax'] = $base_ajax_url .'/subtable/'. $val['subtable_id'] .'/'. $parent_key;

			}

			//easy access for everything
			$page_data['subtables']		 = $subtables;
		}

		//dynamic loading
		$page_data['use_datatable'] = 1;
		$page_data['use_editor'] = 1;
		if ($page['use_geo'])				$page_data['use_geo'] = 1;
		if ($page['use_upload'])			$page_data['use_upload'] = 1;
		if ($page['use_wysiwyg'])			$page_data['use_wysiwyg'] = 1;
		if ($page['use_calendar'])			$page_data['use_calendar'] = 1;
		if ($page['use_select2'])			$page_data['use_select2'] = 1;

		$page_data['form_mode'] = 'edit';

		$template = '/core/crud/form.tpl';
		$this->smarty->render_theme($template, $page_data);
	}

	protected function table_detail($page, $id = null) {
		if (empty($page['crud_table_id'])) {
			theme_404_with_navigation($this->navigation);		//not-found
			return;
		}

		if (!$this->Mpermission->can_edit($page['name'])) {
			theme_403_with_navigation($this->navigation);		//not-authorized
			return;
		}

        //crud pages
        $model = $this->get_model($page['crud_table_id']);
        if ($model == null) {
            theme_404_with_navigation($this->navigation);
            return;
        }

		//controller name
		if (!empty($this->session->get('page_role'))) {
			$controller = $this->session->get('page_role');
		}
		else {
			$controller = get_controller_path();
		}
		$page_data['controller'] = $controller;

		$page_data['page_name']              = $page['name'];
		
		$page_data['page_role']           	 = $this->session->get('page_role');

		$page_data['page_header'] 			 = $page['page_header'];
		$page_data['page_footer'] 			 = $page['page_footer'];

		if (!empty($page['header_view'])) 		$page_data['header_view'] = $this->smarty->get_template_path($page['header_view']);
		if (!empty($page['footer_view'])) 		$page_data['footer_view'] = $this->smarty->get_template_path($page['footer_view']);
		
		//easy access
		$page_data['page']			 = $page; 
		$page_data['navigation']	 = $this->navigation;

		//crud pages
		$model = $this->get_model($page['crud_table_id']);
		if ($model == null) {
			theme_404_with_navigation($this->navigation);
			return;
		}
		
		$tablemeta = $model->tablemeta();

		$page_data['page_title']             = $tablemeta['title'];
		$page_data['page_icon']              = $page['page_icon'];

		//ajax url for data loading
		//Important: we only provide the base path! ie. /crud/page-name
		$base_ajax_url = site_url() .$controller .'/'. $page['name'];

		$tablemeta['ajax'] = $base_ajax_url .'/json';
		$tablemeta['crud_url'] = $base_ajax_url;

		//upload columns
		foreach($tablemeta["editor_columns"] as $key => $col) {
			if ($col["edit_type"] == "tcg_upload") {
				//make sure ajax is correct
				if (empty($col['edit_attr'])) {
					$tablemeta["editor_columns"][$key]['edit_attr'] = array (
						"ajax"	=> $base_ajax_url .'/json'
					);
				}
				else {
					$tablemeta["editor_columns"][$key]['edit_attr']['ajax'] = $base_ajax_url .'/json';
				}
			}
		}

		//show link back to table
        if ($page['page_type'] == 'form') {
            $page_data['show_table_link'] = false;
        }
        else {
            $page_data['show_table_link'] = true;
        }

		//override paging size if necessary
		if (!empty($page['page_size'])) {
			$tablemeta['page_size'] = $page['page_size'];
		}

		$page_data['crud']			 = $tablemeta; 

		//get detail
		$detail = null;

		$key = $page['userdata_key'];
		if (!empty($key)) {
			//actual key is from userdata
			$key = $this->session->get($key);
			$detail = $model->detail($key);
		}
		else if ($id != null) {
			//get from param
			$detail = $model->detail($id);
		}
		
		$page_data['detail'] = $detail; 

		if ($detail != null) {
			$parent_key = $detail[ $tablemeta['key_column'] ];

			//get subtables if necessary
			$subtables = $this->Mpages->subtables($page['id'], true);
			foreach($subtables as $key => $val) {
				//override paging size if necessary
				if (!empty($val['page_size'])) {
					$subtables[$key]['crud']['page_size'] = $val['page_size'];
				}
				//override always autoload
				$subtables[$key]['crud']['initial_load'] = true;
				$subtables[$key]['crud']['parent_key'] = $parent_key;
				//override ajax with the parent key
				$subtables[$key]['crud']['ajax'] = $base_ajax_url .'/subtable/'. $val['subtable_id'] .'/'. $parent_key;

			}

			//easy access for everything
			$page_data['subtables']		 = $subtables;
		}

		//dynamic loading
		$page_data['use_datatable'] = 1;
		$page_data['use_editor'] = 1;
		if ($page['use_geo'])				$page_data['use_geo'] = 1;
		if ($page['use_upload'])			$page_data['use_upload'] = 1;
		if ($page['use_wysiwyg'])			$page_data['use_wysiwyg'] = 1;
		if ($page['use_calendar'])			$page_data['use_calendar'] = 1;
		if ($page['use_select2'])			$page_data['use_select2'] = 1;

		$page_data['form_mode'] = 'detail';

		$template = '/core/crud/form.tpl';
		$this->smarty->render_theme($template, $page_data);
	}

	protected function table_json($page) {
		if (empty($page['crud_table_id'])) {
			$this->json_invalid_page();
		}

        //crud pages
        $model = $this->get_model($page['crud_table_id']);
        if ($model == null) {
            $this->json_invalid_page();
        }
	
		// $table_name = $model->tablename();
		$db_table_name = $model->editable_table();
        $page_name = $page['name'];

        //build params
		$filters = array();
		foreach($this->request->getPost() as $key => $val)
		{
			if ($val == '') continue;
			if (substr($key, 0, 2) != "f_") continue;
			$filters[substr($key, 2)] = $val;
		}

		foreach($this->request->getGet() as $key => $val)
		{
			if ($val == '') continue;
			if (substr($key, 0, 2) != "f_") continue;
			$filters[substr($key, 2)] = $val;
		}

		//query string
		$search = $_POST["search"] ?? '';
        if (empty($search)) {
            $search = $_GET["search"] ?? '';
        }
		$search = trim($search);

		//var_dump($filters);
		$key_column = $model->key_column();

		$action = $_POST["action"] ?? null; 
		if (empty($action) || $action=='view') {

			if (!$this->Mpermission->can_view($page_name)) {
                $this->json_not_authorized();
			}
			
			if (empty($search)) {
				$json['data'] = $model->list($filters);
			}
			else {
				$json['data'] = $model->search($search, $filters);
			}
            

            echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
		}
		else if ($action=='edit'){

			if (!$this->Mpermission->can_edit($page_name)) {
				$this->json_not_authorized();
			}

			$values = $_POST["data"] ?? null; 
            if ($values == null) {
				$data['status'] = 0;
				$data['error'] = 'no-data';
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                return;
            }

			$errors = array();
			$data['data'] = array();
			foreach ($values as $key => $valuepair) {
				$key = $model->update($key, $valuepair, $filters);
				if (!$key)	{
					$errors[] = "$key: " .$model->get_error_message();
					continue;		
				}

				if (isset( $valuepair[$model->key_column()] )) {
					$key = $valuepair[$model->key_column()];
				}
				
				$detail = $model->detail($key, $filters); 

				if ($detail != null && count($detail) > 0)		$data['data'][] = $detail;
            }

            if (count($errors) > 0) {
				$data['status'] = 0;
                $data['error'] = implode(', ', $errors);
            } else {
				$data['status'] = 1;
				$data['affected'] = count($data['data']);
			}
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
        }
        else if ($action=='remove') {

			if (!$this->Mpermission->can_delete($page_name)) {
				$this->json_not_authorized();
			}

			$values = $_POST["data"] ?? null;
            if ($values == null) {
				$data['status'] = 0;
				$data['error'] = 'no-data';
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                return;
            }

            $error_msg = "";
			foreach ($values as $key => $valuepair) {
				$key = $valuepair[$key_column];
				$filters = array_merge($filters, $valuepair);
				unset($filters[$key_column]);
                $model->delete($key, $filters);
			}

            $data['data'] = array(); 
            if (strlen($error_msg) > 0) {
				$data['status'] = 0;
                $data['error'] = $error_msg;
            } else {
				$data['status'] = 1;
				$data['affected'] = count($values);
            }
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
        }
        else if ($action=='create') {

			if (!$this->Mpermission->can_add($page_name)) {
				$this->json_not_authorized();
			}

			$values = $_POST["data"] ?? null; 
            if ($values == null) {
				$data['status'] = 0;
				$data['error'] = 'no-data';
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                return;
            }

            $key = $model->add($values[0], $filters);
            if ($key == 0) {
				$data['status'] = 0;
				$data['error'] = $model->get_error_message();
            } else {
				$data['status'] = 1;
				$data['data'] = [];
				$detail = $model->detail($key, $filters); 
				if ($detail != null && count($detail) > 0)		$data['data'][] = $detail;
            }

            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
        }
        else if ($action == "upload") {

			if (!$this->Mpermission->can_edit($page_name)) {
				$this->json_not_authorized();
			}

			$key = $_POST["uploadField"] ?? null; 
            if ($key == null) {
				$data['status'] = 0;
				$data['error'] = 'no-data';
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                return;
            }
       
			$uploader = new Uploader();

			//use default
            // $uploader->file_types = array('jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf');
            // $uploader->max_dimension = 200;
            // $uploader->max_size_mb = 10;

            //prevent generation of pdf thumbnail
            Uploader::$GENERATE_PDF_THUMBNAIL = 0;

            $fileObj = $uploader->upload($_FILES['upload']);

            if(!empty($fileObj['error'])) {
                $data['error'] = $fileObj['error'];
            } else {
                $data = array("data"=>array(),"files"=>array("files"=>array($fileObj['id']=>$fileObj)),"upload"=>array("id"=>$fileObj['id']));
            }

            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
            return;
        }   
		else if ($action=='uploadFile') {

			if (!$this->Mpermission->can_edit($page_name)) {
				$this->json_not_authorized();
			}
            
            $key = $key = $_POST["field"] ?? null; 
            if ($key == null) {
				$data['status'] = 0;
				$data['error'] = 'invalid field';
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                return;
            }
       
			$uploader = new Uploader();

			//TODO: get parameter from columnmeta
			$uploader->load_setting();
            // $uploader->file_types = array();
            // $uploader->max_dimension = 1200;
            // $uploader->max_size_mb = 100;

            //prevent generation of pdf thumbnail
            Uploader::$GENERATE_PDF_THUMBNAIL = 0;

            $fileObj = $uploader->upload($_FILES['file'], $db_table_name);

            $data['files'] = array();
            if(!empty($fileObj['error'])) {
                $data['error'] = $fileObj['error'];
            } else {
                $data['status'] = 1;
                $data['files'][] = $fileObj;
            }

            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
            return;
		}
		else if ($action=='removeFile'){

			if (!$this->Mpermission->can_edit($page_name)) {
				$this->json_not_authorized();
			}

            $files = $_POST["files"] ?? null; 
            if ($files == null) {
				$data['status'] = 0;
				$data['error'] = 'invalid field';
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                return;
            }
            $files = explode(',', $files);

			$uploader = new Uploader();

            $error_msg = "";
			foreach ($files as $key) {
                $uploader->remove($key, $db_table_name);
            }

            $data['status'] = 1;
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
        }
        else if ($action=='listFile') {
            $files = $_POST["files"] ?? null; 
            if ($files == null) {
				$data['status'] = 0;
				$data['error'] = 'invalid field';
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                return;
            }
            $files = explode(',', $files);

            //var_dump($files);

			$uploader = new Uploader();

            $error_msg = "";
            $data['files'] = array();
			foreach ($files as $key=>$value) {
                $fileObj = $uploader->detail($value);
                if ($fileObj != null) {
                    $data['files'][] = $fileObj;
                }
            }

            $data['status'] = 1;
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
        }
        else if ($action == "import") {

			if (!$this->Mpermission->can_edit($page_name) || !$this->Mpermission->can_add($page_name)) {
				$this->json_not_authorized();
			}

            $fkey_column = $_POST["fkey_column"] ?? null; 
            $fkey_value = $_POST["fkey_value"] ?? null; 
            $filters = array();
            if (!empty($fkey_column)) {
                $filters[$fkey_column] = $fkey_value;
            }

			$status = $model->import($_FILES['upload'], $filters);

            if($status == 0) {
				$data['status'] = 0;
                $data['error'] = $model->get_error_message();
            } else {
                $data['status'] = $status;
            }

            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
            return;
        }   
        else {
            $this->json_not_implemented();
        }

		return;
	}

	protected function table_lookup($page) {
		if (empty($page['crud_table_id'])) {
			$this->json_invalid_page();
		}

        //crud pages
        $model = $this->get_model($page['crud_table_id']);
        if ($model == null) {
            $this->json_invalid_page();
        }
	
		// $table_name = $model->tablename();
		// $db_table_name = $model->editable_table();
        // $page_name = $page['name'];
			
		//build params
		$filters = array();
		foreach($this->request->getPost() as $key => $val)
		{
			if ($val == '') continue;
			if (substr($key, 0, 2) != "f_") continue;
			$filters[substr($key, 2)] = $val;
		}

		//get param override pos param (if any)
		foreach($this->request->getGet() as $key => $val)
		{
			if ($val == '') continue;
			if (substr($key, 0, 2) != "f_") continue;
			$filters[substr($key, 2)] = $val;
		}
		
		$json['data'] = $model->lookup($filters);

		echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
	}

	protected function table_subtable($page, $subtable_id, $parent_key) {
		if (empty($page['crud_table_id'])) {
			$this->json_invalid_page();
		}

		// $table_name = $model->tablename();
		// $db_table_name = $model->editable_table();
        $page_id = $page['id'];

		$subtable = $this->Mpages->subtable_detail($page_id, $subtable_id);
		if($subtable == null) {
			$data['error'] = 'Invalid table-id';
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
			return;
		}

        //crud pages
        $model = $this->get_model($subtable['subtable_id']);
        if ($model == null) {
            $this->json_invalid_page();
        }
	
		//build params
		$filters = array();
		foreach($this->request->getPost() as $key => $val)
		{
			if ($val == '') continue;
			if (substr($key, 0, 2) != "f_") continue;
			$filters[substr($key, 2)] = $val;
		}

		foreach($this->request->getGet() as $key => $val)
		{
			if ($val == '') continue;
			if (substr($key, 0, 2) != "f_") continue;
			$filters[substr($key, 2)] = $val;
		}

		$action = $_POST["action"] ?? null; 
		if (empty($action) || $action=='view') {

			if (!$this->Mpermission->can_view_table_id($subtable_id)) {
				$this->json_not_authorized();
			}
			
            //filter by parent key
			$filters[ $subtable['subtable_fkey_column'] ] = $parent_key[0];
            if (!empty($parent_key[1])){
                $filters[ $subtable['subtable_fkey_column2'] ] = $parent_key[1];
            }
			
			$json['data'] = $model->list($filters);

            echo json_encode($json, JSON_INVALID_UTF8_IGNORE);	
		}
		else if ($action=='edit'){

			if (!$this->Mpermission->can_edit_table_id($subtable_id)) {
				$this->json_not_authorized();
			}

			$values = $_POST["data"] ?? null; 
            if ($values == null) {
                $data['status'] = 0;
                $data['error'] = 'no-data';
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
                return;
            }

			$error_msg = "";
			$data['data'] = array();
			foreach ($values as $key => $valuepair) {
				//enforce the parent key
				$valuepair[ $subtable['subtable_fkey_column'] ] = $parent_key[0];
                if (!empty($parent_key[1])){
                    $valuepair[ $subtable['subtable_fkey_column2'] ] = $parent_key[1];
                }
    
				$key = $model->update($key, $valuepair, $filters);
				if (!$key)	continue;		//TODO: catch error message

				$detail = $model->detail($key, $filters); 
			
				if ($detail != null && count($detail) > 0)		$data['data'][] = $detail;
            }

            if (strlen($error_msg) > 0) {
                $data['error'] = $error_msg;
            }
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
        }
        else if ($action=='remove') {

			if (!$this->Mpermission->can_delete_table_id($subtable_id)) {
				$this->json_not_authorized();
			}

			$values = $_POST["data"] ?? null; 
            if ($values == null) {
                $data['status'] = 0;
                $data['error'] = 'no-data';
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
                return;
            }

            //can only delete children
            $filters[ $subtable['subtable_fkey_column'] ] = $parent_key[0];
            if (!empty($parent_key[1])){
                $filters[ $subtable['subtable_fkey_column2'] ] = $parent_key[1];
            }

            $error_msg = "";
			foreach ($values as $key => $valuepair) {
				$filters = array_merge($filters, $valuepair);
                $model->delete($key, $filters);
			}

            $data['data'] = array(); 
            if (strlen($error_msg) > 0) {
                $data['error'] = $error_msg;
            }
			echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
        }
        else if ($action=='create') {

			if (!$this->Mpermission->can_add_table_id($subtable_id)) {
				$this->json_not_authorized();
			}

			$values = $_POST["data"] ?? null; 
            if ($values == null) {
                $data['status'] = 0;
                $data['error'] = 'no-data';
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
                return;
            }

            //enforce parent key
			$values[0][ $subtable['subtable_fkey_column'] ] = $parent_key[0];
            if (!empty($parent_key[1])){
                $filters[ $subtable['subtable_fkey_column2'] ] = $parent_key[1];
            }
			
			$key = $model->add($values[0], $filters);
            if ($key == 0) {
                $data['error'] = "database-error"; //$model->error()['message'];
            } else {
				$data['data'] = [];
				$detail = $model->detail($key, $filters); 
				if ($detail != null && count($detail) > 0)		$data['data'][] = $detail;
            }

            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
        }
        else if ($action == "upload") {

			if (!$this->Mpermission->can_edit_table_id($subtable_id)) {
				$this->json_not_authorized();
			}

			$key = $_POST["uploadField"] ?? null; 
            if ($key == null) {
                $data['status'] = 0;
                $data['error'] = 'invalid-field';
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);	
                return;
            }
       
			$uploader = new Uploader();

			//use default
            // $uploader->file_types = array('jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf');
            // $uploader->max_dimension = 200;
            // $uploader->max_size_mb = 10;

            //prevent generation of pdf thumbnail
            Uploader::$GENERATE_PDF_THUMBNAIL = 0;

            $fileObj = $uploader->upload($_FILES['upload']);

            if(!empty($fileObj['error'])) {
                $data['error'] = $fileObj['error'];
            } else {
                $data = array("data"=>array(),"files"=>array("files"=>array($fileObj['id']=>$fileObj)),"upload"=>array("id"=>$fileObj['id']));
            }

            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
            return;
        }   
        else if ($action == "import") {

			if (!$this->Mpermission->can_add_table_id($subtable_id) || !$this->Mpermission->can_edit_table_id($subtable_id)) {
				$this->json_not_authorized();
			}

            $fkey_column = $_POST["fkey_column"] ?? null;
            $fkey_value = $_POST["fkey_value"] ?? null; 
            $fkey_column2 = $_POST["fkey_column2"] ?? null;
            $fkey_value2 = $_POST["fkey_value2"] ?? null; 

            $filters = array();
            if (!empty($fkey_column)) {
                $filters[$fkey_column] = $fkey_value;
            }
            if (!empty($fkey_column2)) {
                $filters[$fkey_column2] = $fkey_value2;
            }
            
			$status = $model->import($_FILES['upload'], $filters);

            if($status == 0) {
                $data['error'] = $model->get_error_message();
            } else {
                $data['status'] = $status;
            }

            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
            return;
        }   
        else {
            $this->json_not_implemented();
        }

		return;
	}

	protected function get_model($table_id) {
        $mtable = new Mtable();
		if (!$mtable->init($table_id, true)) {
			return null;
		}

		return $mtable;
	}

	protected function get_model_for_lookup($table_id) {
        $mtable = new Mtable();
		if (!$mtable->init_for_lookup($table_id, true)) {
			return null;
		}

		return $mtable;
	}

	protected function get_navigation() {
        $mnavigation = new Mnavigation();
		return $mnavigation->get_navigation($this->session->get('role_id'), static::$PAGE_GROUP);
	}

	protected function get_session() {
        $msession = new Msession();
		$sessions = $msession->get_session();

		for($i=0; $i<count($sessions); $i++) {
			$sess = $sessions[$i];

			$sess_name = $sess['name'] . '-label';
			if (!empty($this->session->get($sess_name))) {
				$sess['value'] = $this->session->get($sess_name);
			}
			else if (!empty($this->session->get($sess['name']))) {
				$sess['value'] = $this->session->get($sess['name']);
			}
			else if ($sess['optional'] == 0) {
				if (!empty($sess['label_for_none'])) {
					$sess['value'] = $sess['label_for_none'];
				}
				else {
					$sess['value'] = 'ALL';
				}
			}
			else {
				$sess['value'] = null;
			}

			$sessions[$i]['value'] = $sess['value'];
		}

		return $sessions;
	}

    protected function json_not_login() {
        $data['error'] = 'not-login';
        $this->json_response($data, self::HTTP_FORBIDDEN);
    }

    protected function json_not_authorized() {
        $data['error'] = 'not-authorized';
        $this->json_response($data, self::HTTP_UNAUTHORIZED);
    }

    protected function json_not_implemented() {
        $data['error'] = 'not-implemented';
        $this->json_response($data, self::HTTP_OK);
    }

    protected function json_invalid_page() {
        $data['error'] = "invalid-page";
        $this->json_response($data, self::HTTP_OK);
    }

    protected function json_response($data = null, $http_code = self::HTTP_OK, $continue = false)
    {
        if (!isset($this->response)) {
            //most probably called from constructor, when initialization is not yet completed
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE); exit;
        }

        ob_start();
        // If the HTTP status is not NULL, then cast as an integer
        if ($http_code !== null) {
            // So as to be safe later on in the process
            $http_code = (int) $http_code;
        }

        // Set the output as NULL by default
        $output = null;

        // If data is NULL and no HTTP status code provided, then display, error and exit
        if ($data === null && $http_code === null) {
            $http_code = self::HTTP_NOT_FOUND;
        }

        // // If data is not NULL and a HTTP status code provided, then continue
        // elseif ($data !== null) {
        //     // Parse as a json, so as to be a 'string'
        //     $output = json_encode($data, JSON_INVALID_UTF8_IGNORE);
        //     echo $output;
        // }

        // If not greater than zero, then set the HTTP status code as 200 by default
        // Though perhaps 500 should be set instead, for the developer not passing a
        // correct HTTP status code
        $http_code > 0 || $http_code = self::HTTP_OK;

        $this->response->setStatusCode($http_code);
        $this->response->setContentType('application/json');

        // Output the data
        $this->response->setJson($data);

        if ($continue === false) {
            // Display the data and exit execution
            $this->response->send();
            exit;
        } else {
            if (is_callable('fastcgi_finish_request')) {
                // Terminates connection and returns response to client on PHP-FPM.
                $this->response->send();
                ob_end_flush();
                fastcgi_finish_request();
                ignore_user_abort(true);
            } else {
                // Legacy compatibility.
                ob_end_flush();
            }
        }
        ob_end_flush();
    }    

}
