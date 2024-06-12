<?php
namespace App\Libraries;

require_once ROOTPATH .'vendor/autoload.php';

use Smarty;
use CodeIgniter\CodeIgniter;
use App\Libraries\Setting;

class SmartyLibrary extends Smarty {

    protected $setting = null;
    protected $theme = 'default';
    protected $session = null;

    function __construct() {
        parent::__construct();

        // Define directories, used by Smarty:
        $this->setTemplateDir(APPPATH . 'views');
        $this->setCompileDir(APPPATH . 'cache/smarty_templates_cache');
        $this->setCacheDir(APPPATH . 'cache/smarty_cache');

        //base url
        $this->assign('base_url', base_url());
        $this->assign('site_url', site_url());

        $this->setting = new Setting();
        $arr = $this->setting->list_group('system');
        foreach($arr as $key => $val) {
            $this->assign($val['name'], $val['value']);
            if ($val['name'] == 'app_theme' && !empty($val['value'])) {
                $this->theme = $val['value'];
            }
        }

        //default currency setting
        $currency_prefix = "Rp";
        $thousand_separator = ".";
        $decimal_separator = ",";
        $decimal_precision = 0;
        
        //TODO: configure it in config or in configuration table
        $arr = $this->setting->list_group('currency');
        foreach($arr as $key => $val) {
            if ($val['name'] == "currency_prefix")  $currency_prefix = $val['value'];
            else if ($val['name'] == "currency_thousand_separator")     $thousand_separator = $val['value'];
            else if ($val['name'] == "currency_decimal_separator")      $decimal_separator = $val['value'];
            else if ($val['name'] == "currency_decimal_precision")      $decimal_precision = $val['value'];
        }

        $this->assign("currency_prefix", $currency_prefix);
        $this->assign("currency_thousand_separator", $thousand_separator);
        $this->assign("currency_decimal_separator", $decimal_separator);
        $this->assign("currency_decimal_precision", $decimal_precision);

        if (ENVIRONMENT === 'development') {
			$this->assign('version', 'CodeIgniter Version <strong>' .CodeIgniter::CI_VERSION. '</strong>');
		}
        else {
            $this->assign('version', '');
        }

        //config
        global $config;        
        if (!empty($config)) {
            foreach($config as $key => $val) {
                $this->assign($key, $val);
            }
        }

        $this->session = \Config\Services::session();

        helper("functions");
    }

    function render($template, $data = array()) {
        $router = \CodeIgniter\Config\Services::router();
        $session = \Config\Services::session();

        //failback in case controller is not set
        if (empty($data['controller'])) {
            $data['controller'] = strtolower(mb_basename($router->controllerName()));
        }

        //TODO
        // //assign form validation
        // $validation_error = validation_errors();
        // if (!empty($validation_error)) {
        //     $this->assign('validation_error', $validation_error);
        // }

        //error message
        $error = $session->getFlashdata('error');
        if (!empty($error)) {
            $this->assign('error_message', $error);
        }

        //success message
        $success = $session->getFlashdata('success');
        if (!empty($success)) {
            $this->assign('success_message', $success);
        }

        //page data
        foreach($data as $key => $val) {
            $this->assign($key, $val);
        }

        //userdata
        $this->assign('userdata', $session->get());

        //actual path to template files
        $template_path = APPPATH ."Views/". $template;

        //helper
        helper("gettext");

        //display the tempalte
        parent::display($template_path);
    }

    function render_theme($template, $data = array(), $theme = null) {
        $router = \CodeIgniter\Config\Services::router();
        $session = \Config\Services::session();

        if ($theme == null) {
            $theme = $this->theme;
        }

        //failback in case controller is not set
        if (empty($data['controller'])) {
            $data['controller'] = strtolower(mb_basename($router->controllerName()));
        }

        // //assign form validation
        // $validation_error = validation_errors();
        // if (!empty($validation_error)) {
        //     $this->assign('validation_error', $validation_error);
        // }

        //error message
        $error = $session->getFlashdata('error');
        if (!empty($error)) {
            $this->assign('error_message', $error);
        }

        //success message
        $success = $session->getFlashdata('success');
        if (!empty($success)) {
            $this->assign('success_message', $success);
        }

        //page data
        foreach($data as $key => $val) {
            $this->assign($key, $val);
        }

        //userdata
        $userdata = $session->get();
        $this->assign('userdata', $userdata);

        $theme_prefix = APPPATH ."Views/core/themes/$theme";
        $this->assign('theme', $theme);
        $this->assign('theme_prefix', $theme_prefix);

        //the actual template is the inner template
        if (substr( $template, 0, 1 ) == '/') {
            //start with '/' means it is not themeable template
            $inner_template = APPPATH ."Views" .$template;
        } 
        else {
            //important: now themes folder only contains theme specific files
            //           moreover, all custom tpl must be outside 'core' folder
            $inner_template = $template;
        }
        $this->assign('inner_template', $inner_template);

        //the master template is the index.tpl
        $template =  $theme_prefix .'/index.tpl';

        //helper
        helper("gettext");

        //display the tempalte
        parent::display($template);
    }

    function get_template_path($template, $theme = null) {
        if (empty($template))   return $template;
        if ($theme == null) {
            $theme = $this->theme;
        }

        $theme_prefix = APPPATH ."Views/core/themes/$theme";

        //the actual template is the inner template
        $template_path = "";
        if (substr( $template, 0, 1 ) == '/') {
            //start with '/' means it is not themeable template
            $template_path = APPPATH ."Views". $template;
        } 
        else {
            $template_path = $theme_prefix .'/'. $template;
        }
        
        return $template_path;
    }
}
