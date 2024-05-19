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
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Validation\Validation;
use Config\App;
use Psr\Log\LoggerInterface;

//defined('BASEPATH') OR exit('No direct script access allowed');

abstract class AuthController extends BaseController
{
    protected static $DEFAULT_PROFILE_IMAGE = "assets/image/user.png";

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
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $userid = $this->session->get('user_id');
        if(!empty($userid))
        {
            //to user page
            $redirect = $this->get_home();
            return redirect()->to($redirect);
        }

        $data['use_captcha'] = ($this->setting->get('use_captcha') == 1);
        $data['is_localhost'] = (strpos(base_url(), 'localhost') >= 0);

        $this->smarty->render('core/auth/login.tpl',$data);
        return;

    }
    
    
    /**
     * This function used to log in user
     */
    public function login()
    {
        helper("form");

        $json = $_POST["json"] ?? null;
        if (empty($json)) {
            $json = 0;
        }

        if (!$json) {
            $userid = $this->session->get('user_id');
            if(!empty($userid))
            {
                //to user page
                $redirect = $this->get_home();
                return redirect()->to($redirect);
            }
    
        }

        $this->validation->setRule('username', 'Username', 'required|max_length[128]|trim');
        $this->validation->setRule('password', 'Password', 'required|max_length[32]');
        
        $validation = $this->validation->run($_POST);
        if(!$validation)
        {
            $error = __('Silahkan periksa Username/password anda');
            if ($json == 1) {
                $data = array('status'=>'0', 'error'=>$error);
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
            }
            else {
                $this->session->setFlashdata('error', $error);	
                return redirect()->to(site_url() ."auth");
                //return redirect()->back()->withInput();
                //return $this->response->redirect();
            }
			return;
        }
        
		$username = $_POST["username"] ?? null; 
        $password = $_POST["password"] ?? null; 
		$captcha = $_POST["g-recaptcha-response"] ?? true; 
        
        if (!isset($username) && !isset($password)) {
            $error = __('Silahkan periksa Username/password anda');
            if ($json == 1) {
                $data = array('status'=>'0', 'error'=>$error);
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
            }
            else {
                $this->session->setFlashdata('error', $error);	
                return redirect()->back()->withInput();
            }
            return;
        }
		
        // $data['use_captcha'] = $use_captcha;
        // $data['is_localhost'] = (strpos(base_url(), 'localhost') >= 0) || (strpos(base_url(), '127.0.0.1') >= 0) || (strpos(base_url(), '::1') >= 0);

        $use_captcha = ($this->setting->get('use_captcha') == 1);        
		if($use_captcha == 1 && $this->check_recaptcha_v2($captcha) == 0){
            $error = __('Kode Captcha yang anda masukkan salah');
            if ($json == 1) {
                $data = array('status'=>'0', 'error'=>$error);
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
            }
            else {
                $this->session->setFlashdata('error', $error);	
                return redirect()->back()->withInput();
            }
			return;
		}

		$result = $this->Mauth->login($username, $password);	
		if($result == null)
		{
            $error = __('Username/password tidak ditemukan');
            if ($json == 1) {
                $data = array('status'=>'0', 'error'=>$error);
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
            }
            else {
                $this->session->setFlashdata('error', $error);		
                return redirect()->back()->withInput();
            }
			return;
		}

        if ($result['allow_login'] != '1') {
            $error = __('Akses login anda untuk sementara ditolak');
            if ($json == 1) {
                $data = array('status'=>'0', 'error'=>$error);
                echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
            }
            else {
                $this->session->setFlashdata('error', $error);	
                return redirect()->back()->withInput();
            }
			return;
        }
        
        if (!$this->do_additional_checks($result, $json)) {
            if ($json == 1) {
                return;
            }
            return redirect()->back()->withInput();
        }

        $result['profile_img'] = base_url(). (empty($result['profile_img']) ? static::$DEFAULT_PROFILE_IMAGE : $result['profile_img']);
        $result['is_logged_in'] = TRUE;

        $page_role = $this->session->get('page_role');
        if (!empty($result['page_role'])) {
            $page_role = $result['page_role'];
            $result['page_role'] = $page_role;
        }

        //force reset session
        $this->session->set($result);
                    
        $this->set_additional_sessions();
        
        $redirect_url = $this->get_home();
        if ($json == 1) {
            $data = array("status"=>1, "redirect"=>$redirect_url);
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
        } 
        else {
            //var_dump($redirect_url); exit;
            return redirect()->to($redirect_url);
        }
    }

    /**
     * Additional checks to perform
     */
    abstract protected function do_additional_checks($result, $json);

    /**
     * Additional session to set
     */
    abstract protected function set_additional_sessions();

    /**
     * Determine where to go after successful login
     */
    protected function get_home() {
        $role_id = $this->session->get('role_id');
        if ($role_id == ROLEID_SYSADMIN) {
            return site_url() ."sistem";
        } 
        else if ($role_id == ROLEID_ADMIN) {
            return site_url() ."admin";
        } 
        else {
            return site_url() ."user";
        }
    }

    /**
     * This function will log out a user
     */
	function logout() {
		$this->session->destroy();
		return redirect()->to(base_url());
	}

    function notfound() {
        $isLoggedIn = $this->session->get('is_logged_in');
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE) {
            return redirect()->to(base_url());
            return;
        }

        theme_404();
	}

    function notauthorized() {
        $isLoggedIn = $this->session->get('is_logged_in');
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE) {
            return redirect()->to(base_url());
            return;
        }

        theme_403();
	}

    function resetpassword() {
        
        $mpermission = new Mpermission();
        if (!$mpermission->is_admin()) {
            $data['error'] = 'not-authorized';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
        }

        $values = $_POST["data"] ?? null; 
        if ($values == null) {
            $data['error'] = 'no-data';
            echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
        }

        $error_msg = "";
        $data['data'] = array();
        foreach ($values as $key => $valuepair) {
            $user_id = $key;
            $pwd1 = $valuepair["pwd1"];
            $pwd2 = $valuepair["pwd2"];

            if ($pwd1 != $pwd2) {
                $data['error'] = __("Password baru tidak sama. Silahkan ulangi kembali.");
                continue;
            }

            if($this->Mauth->reset_password($user_id, $pwd1) == 0) {
                $data['error'] = __("Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.");
                continue;
            } else {
                $user = $this->Mauth->profile($user_id);
                if ($user != null)  $data['data'][] = $user; 
            }

        }

        echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
    }

	function check_recaptcha_v2($captcha) {
		if (empty($captcha))
			return false;
			
		//this is the proper way of checking it
		if(strpos(base_url(), 'localhost')) {
			//localhost
			$secret = '6LdDOOoUAAAAADGh9tqM6i4Yni5TtX1oVJbdkXey';
		} else {
			$secret = $this->setting->get('app_captcha_key');
		}

		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array(
			'secret' => $secret,
			'response' => $captcha
		);
		$options = array(
		    "ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
			'http' => array (
				'method' => 'POST',
				'content' => http_build_query($data),
				'header' => 'Content-Type: application/x-www-form-urlencoded',
			)
		);

		$context  = stream_context_create($options);
		$verify = file_get_contents($url, false, $context);
		$captcha_success=json_decode($verify);
	
		if ($captcha_success->success==false) {
			return 0;
		} else if ($captcha_success->success==true) {
			return 1;
		}
		
	}

    /**
     * This function used to load forgot password view
     */
    public function forgotPassword()
    {
        view('forgotPassword');
    }
    
    function resetPasswordEmail($email) {
        //TODO: send email
    }

    /**
     * This function used to generate reset password request link
     */
    function resetPasswordUser()
    {
        $status = '';
        
        $this->validation->setRule('login_email','Email','trim|required|valid_email|xss_clean');
                
        if($this->validation->run() == FALSE)
        {
            $this->forgotPassword();
        }
        else 
        {
            $email = $_POST["login_email"] ?? null;
            
            if($this->Mauth->checkEmailExist($email))
            {
                $encoded_email = urlencode($email);
                
                helper('string');
                $data['email'] = $email;
                $data['activation_id'] = random_string('alnum',15);
                $data['createdDtm'] = date('Y-m-d H:i:s');
                $data['agent'] = get_user_browser();
                $data['client_ip'] = $this->request->getIPAddress();
                
                $save = $this->Mauth->resetPasswordUser($data);                
                
                if($save)
                {
                    $data1['reset_link'] = site_url() . "/resetPasswordConfirmUser/" . $data['activation_id'] . "/" . $encoded_email;
                    $userInfo = $this->Mauth->getCustomerInfoByEmail($email);

                    if(!empty($userInfo)){
                        $data1["name"] = $userInfo[0]->name;
                        $data1["email"] = $userInfo[0]->email;
                        $data1["message"] = "Reset Password Anda";
                    }

                    $sendStatus = $this->resetPasswordEmail($data1);

                    if($sendStatus){
                        $status = "send"; 
                        $this->session->setFlashdata($status, "Reset password link sent successfully, please check mails.");
                    } else {
                        $status = "notsend";
                        $this->session->setFlashdata($status, "Email has been failed, try again.");
                    }
                }
                else
                {
                    $status = 'unable';
                    $this->session->setFlashdata($status, "It seems an error while sending your details, try again.");
                }
            }
            else
            {
                $status = 'invalid';
                $this->session->setFlashdata($status, "Username/email tidak terdaftar.");
            }
            return redirect()->to('/forgotPassword');
        }
    }

    // This function used to reset the password 
    function resetPasswordConfirmUser($activation_id, $email)
    {
        // Get email and activation code from URL values at index 3-4
        $email = urldecode($email);
        
        // Check activation id in database
        $is_correct = $this->Mauth->checkActivationDetails($email, $activation_id);
        
        $data['email'] = $email;
        $data['activation_code'] = $activation_id;
        
        if ($is_correct == 1)
        {
            view('newPassword', $data);
        }
        else
        {
            return redirect()->to('<?php echo site_url();?>/Auth');
        }
    }
    
    // This function used to create new password
    function createPasswordUser()
    {
        $status = '';
        $message = '';
        $email = $_POST["email"] ?? null; 
        $activation_id = $_POST["activation_code"] ?? null; 
                
        $this->validation->setRule('password','Password','required|max_length[20]');
        $this->validation->setRule('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
        
        if($this->validation->run() == FALSE)
        {
            $this->resetPasswordConfirmUser($activation_id, urlencode($email));
        }
        else
        {
            $password = $_POST["password"] ?? null; 
            $cpassword = $_POST["cpassword"] ?? null; 
            
            // Check activation id in database
            $is_correct = $this->Mauth->checkActivationDetails($email, $activation_id);
            
            if($is_correct == 1)
            {                
                $this->Mauth->createPasswordUser($email, $password);
                
                $status = 'success';
                $message = 'Password berhasil diubah';
            }
            else
            {
                $status = 'error';
                $message = 'Password tidak berhasil diubah';
            }
            
            $this->session->setFlashdata($status, $message);

            return redirect()->to("<?php echo site_url();?>/Auth");
        }
    }

}

?>