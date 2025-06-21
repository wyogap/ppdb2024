<?php
namespace App\Controllers\Ppdb\Dapodik;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use App\Models\Core\Crud\Mauth;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Profil extends PpdbController {

    protected static $ROLE_ID = ROLEID_DAPODIK;      

    protected $Mauth;
    protected $Msekolah;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        //$this->smarty = new SmartyLibrary();

        //load model
        $this->Mauth = new Mauth();
        $this->Msekolah = new Mprofilsekolah();
    }

	function index()
	{
		$user_id = $this->session->get("user_id");
        if (empty($user_id)) {
			return $this->notauthorized();
		}

        $sekolah_id = $this->session->get("sekolah_id");
        if (empty($sekolah_id)) {
			return $this->notauthorized();
		}

        $data['profilsekolah'] = $this->session->get("profilsekolah");
        $data['impersonasi_sekolah'] = $this->session->get("impersonasi_sekolah");
        if ($data['impersonasi_sekolah'] == 1) {
            $data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
        }

        $jenjang_id = $this->session->get('jenjang_aktif');
        $data['profil'] = $this->Mauth->get_profile($user_id);
        $data['daftarputaran'] = $this->Mconfig->tcg_putaran($jenjang_id);
        
        //content template
        $data['content_template'] = 'profil.tpl';
        $data['js_template'] = '_profil.tpl';

		$data['page'] = 'profil';
		$data['page_title'] = 'Profil';
        $this->smarty->render('ppdb/dapodik/ppdbdapodik.tpl', $data);
	}

    public function simpan() {
        $sess_user_id = $this->session->get('user_id');
        $user_id = $this->request->getGetPost('user_id');
        if ($user_id != $sess_user_id) {
            print_json_error("not-authorized");
            return;
        }

        $data = array();
        $data['nama'] = $this->request->getGetPost('nama');
        $data['email'] = $this->request->getGetPost('email');
        $data['handphone'] = $this->request->getGetPost('handphone');

        $mauth = new Mauth();
        $profil = $mauth->update_profile($user_id, $data);
        if (!$profil) {
            print_json_error("Tidak berhasil memperbaharui profil.");
            return;
        }

        print_json_output($profil);
    }
      
}
