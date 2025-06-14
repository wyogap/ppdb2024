<?php

namespace App\Controllers\Core;

use App\Controllers\Core\BaseController;
use App\Libraries\Setting;
use App\Libraries\SmartyLibrary;
use App\Libraries\Uploader;
use App\Models\Core\Crud\Mauth;
use App\Models\Core\Crud\Mnavigation;
use App\Models\Core\Crud\Mpages;
use App\Models\Core\Crud\Mpermission;
use App\Models\Core\Crud\Msession;
use App\Models\Core\Crud\Mtable;
use App\Models\Core\Ext\MWhatsApp;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Validation\Validation;
use Config\App;
use Psr\Log\LoggerInterface;

//defined('BASEPATH') OR exit('No direct script access allowed');

abstract class WAController extends BaseController
{
    protected static $DEFAULT_PROFILE_IMAGE = "assets/image/user.png";
    protected static $LOGIN_PAGE = "";

    protected Validation $validation;

    protected $Mauth;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        $this->validation = \Config\Services::validation();;

        //load model
        $this->Mauth = new Mauth();

        //helper
        helper('gettext');
        helper('functions');
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        return redirect()->to(BASE_URL);
    }
    
    /**
     * WHATSAPP sender 
     */
    protected function send_whatsapp($wa_number, $message_out) {
        $dataSending = Array();
        $dataSending["api_key"] = WA_APIKEY;
        $dataSending["number_key"] = WA_NUMBERKEY;
        $dataSending["phone_no"] = $wa_number;
        $dataSending["message"] = $message_out;
        $dataSending["wait_until_send"] = "1"; //This is an optional parameter, if you use this parameter the response will appear after sending the message is complete
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.watzap.id/v1/send_message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($dataSending),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }

    public function webhook() {
        $entityBody = file_get_contents('php://input');

        $wa = new MWhatsApp();
        $wa->log_message($entityBody);

        $data = json_decode($entityBody);
        $is_from_me = $data->data->is_from_me;
        if ($is_from_me) {
            return;
        }

        $wa_number = $data->data->name;
        $message = $data->data->message_body;
        $timestamp = date('Y-m-d H:i:s');

        //check for valid nomor_wa
        $user = $this->Mauth->get_profile_handphone($wa_number);
        if (empty($user)) {
            //tidak terdaftar
            //print_json_error("tidak-terdaftar");
            return;
        }

        $message_out = $wa->process_message($user, $message, $timestamp);

        $app_name = $this->setting->get('app_name');
        $message_out .= " [" .$app_name. "]";

        $this->send_whatsapp($user, $message_out);
    }

    public function setwebhook() {
        $dataSending = Array();
        $dataSending["api_key"] = WA_APIKEY;
        $dataSending["number_key"] = WA_NUMBERKEY;
        $dataSending["endpoint_url"] = site_url() ."webhook";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.watzap.id/v1/set_webhook',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($dataSending),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }

    public function cekapi() {
        $dataSending = Array();
        $dataSending["api-key"] = WA_APIKEY;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.watzap.id/v1/checking_key',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($dataSending),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }

    public function getwebhook() {
        $dataSending = Array();
        $dataSending["api_key"] = WA_APIKEY;
        $dataSending["number_key"] = WA_NUMBERKEY;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.watzap.id/v1/get_webhook',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($dataSending),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }

}

?>