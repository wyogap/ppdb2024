<?php
namespace App\Controllers\Ppdb;

use App\Controllers\Core\BaseController;

use App\Models\Ppdb\Mconfig;
use App\Models\Core\Crud\Mauth;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class PpdbController extends BaseController {

	protected static $AUTHENTICATED = true;
	protected static $ROLE_ID = 0;              //$ROLE_ID can be an array!

    protected $Mconfig;

    protected $nama_wilayah = "";
    protected $kode_wilayah = "";
    protected $nama_tahun_ajaran = "";
    protected $tahun_ajaran_id = "";
    protected $nama_putaran = "";
    protected $putaran = "";

    protected $user_id = null;
    protected $peran_id = 0;
    //protected $pengguna_id = null;
    protected $peserta_didik_id = null;
    protected $nama_pengguna = null;
    protected $is_siswa = false;
    protected $is_sekolah = false;
    protected $is_dinas = false;
    protected $is_dapodik = false;

    protected $error_message = "";
    protected $info_message = "";
    protected $success_message = "";

    protected $waktu_verifikasi = 0;
    protected $waktu_daftarulang = 0;

    protected $method = "";
    protected $params = array();
    protected $url = null;
    protected $is_json = false;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        helper("ppdb");
        helper('functions');
                
        //load model
        $this->Mconfig = new Mconfig();

        //URI params
        $segments = $this->request->getUri()->getSegments();
        $total = count($segments);
        $controller = strtolower(mb_basename(get_class($this)));

        $this->method = "";
        $this->params = array();
        for($i=0; $i<$total; $i++) {
            $segment = strtolower($segments[$i]);
            if ($segment == $controller) {
                if ($i+1<$total) {
                    $this->method=$segments[$i+1];
                }
                else {
                    $this->method='index';
                }
                for($j=$i+2; $j<$total; $j++) {
                    $this->params[] = $segments[$j];
                }
                break;
            }
        }

        //build current url including the query
        $this->url = current_url() ."?";
        $getparams = $this->request->getGet();
        if (!empty($getparams)) {
            foreach($getparams as $k => $v) {
                if ($k == 'putaran') continue;
                $this->url .= $k ."=". $v ."&";
            }
        }

        //is-json
        if (!$this->is_json) {
            //other than the index, all are json call
            $this->is_json = ($this->method != '') && ($this->method != 'index');
        }

        //logged-in user
        $this->user_id = $this->session->get("user_id");
        if ($this->user_id) {
            $this->peran_id = $this->session->get('role_id');
            //$this->pengguna_id = $this->session->get("user_id");
            $this->peserta_didik_id = $this->session->get("peserta_didik_id");
            $this->nama_pengguna = $this->session->get("nama");
            $this->is_siswa = ($this->peran_id == ROLEID_SISWA);
            $this->is_sekolah = ($this->peran_id == ROLEID_SEKOLAH);
            $this->is_dinas = ($this->peran_id == ROLEID_DINAS);
            $this->is_dapodik = ($this->peran_id == ROLEID_DAPODIK);
        }

        //smarty: general setting -> can be overriden below.
        $arr = $this->setting->list_group('ppdb');
        foreach($arr as $val) {
            $this->smarty->assign($val['name'], $val['value']);
        }

        // //common vars
        // $nama_wilayah = "";
		// $kode_wilayah = "";

		// //get from GET or POST
		// $kode_wilayah = $this->request->getPostGet("kode_wilayah"); 

        // //get from session if necessary
        // if (empty($kode_wilayah)) {
        //     $kode_wilayah = $this->session->get('kode_wilayah_aktif');
		// 	$nama_wilayah = $this->session->get('nama_wilayah_aktif');
        // }

        // //get from database
        // if (empty($kode_wilayah)) {
        //     $kode_wilayah = $this->setting->get("kode_wilayah");
        //     //make sure it is kabupaten level
        //     $kode_wilayah = substr($kode_wilayah, 0, 4) ."00";
        //     $nama_wilayah = $this->Mconfig->tcg_nama_wilayah($kode_wilayah);
        // }

        // if (!empty($kode_wilayah) && empty($nama_wilayah)) {
        //     $nama_wilayah = $this->Mconfig->tcg_nama_wilayah($kode_wilayah);
        // }

		// //set from session if necessary
		// if ($this->session->get('kode_wilayah_aktif') != $kode_wilayah) {
        //     $this->session->set('kode_wilayah_aktif', $kode_wilayah);
        //     $this->session->set('nama_wilayah_aktif', $nama_wilayah);
		// }

		// //replace global var if necessary
        // $this->kode_wilayah = $kode_wilayah;
        // $this->nama_wilayah = $nama_wilayah;

		// //tahun ajaran
		// $nama_tahun_ajaran = "";
		// $tahun_ajaran_id = "";

        // //get from GET or POST
        // $tahun_ajaran_id = $this->request->getPostGet("tahun_ajaran"); 
 
        // //get from session if necessary
        // if (empty($tahun_ajaran_id)) {
        //     $tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		// 	$nama_tahun_ajaran = $this->session->get('nama_tahun_ajaran_aktif');
        // }

		// //get from database
		// if (empty($tahun_ajaran_id)) {
		// 	$tahun_ajaran_id = $this->setting->get("tahun_ajaran");
		// }

        // if (!empty($tahun_ajaran_id) && empty($nama_tahun_ajaran)) {
        //     $nama_tahun_ajaran = $this->Mconfig->tcg_nama_tahunajaran($tahun_ajaran_id);
        // }

		// if ($this->session->get('tahun_ajaran_aktif') != $tahun_ajaran_id) {
        //     $this->session->set('tahun_ajaran_aktif', $tahun_ajaran_id);
        //     $this->session->set('nama_tahun_ajaran_aktif', $nama_tahun_ajaran);
		// }

		// //replace global var if necessary
        // $this->tahun_ajaran_id = $tahun_ajaran_id;
        // $this->nama_tahun_ajaran = $nama_tahun_ajaran;

		//replace global var if necessary
        $this->kode_wilayah = $this->session->get('kode_wilayah_aktif');
        $this->nama_wilayah = $this->session->get('nama_wilayah_aktif');
        $this->tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
        $this->nama_tahun_ajaran = $this->session->get('nama_tahun_ajaran_aktif');

        $nama_putaran = "";
		$putaran = "";

        //get from GET or POST
		$putaran = $this->request->getPostGet("putaran"); 
 
        //get from session if necessary
        if (empty($putaran)) {
            $putaran = $this->session->get('putaran_aktif');
			$nama_putaran = $this->session->get('nama_putaran_aktif');
        }

		//get from database
		if (empty($putaran)) {
			$putaran = $this->setting->get("putaran");
		}

        if (!empty($putaran) && empty($nama_putaran)) {
            $nama_putaran = $this->Mconfig->tcg_nama_putaran($putaran);
        }

		if ($this->session->get('putaran_aktif') != $putaran) {
            $this->session->set('putaran_aktif', $putaran);
            $this->session->set('nama_putaran_aktif', $nama_putaran);
		}

		//replace global var if necessary
        $this->putaran = $putaran;
        $this->nama_putaran = $nama_putaran;

        //smarty: vars
        $this->smarty->assign('kode_wilayah', $this->kode_wilayah);
        $this->smarty->assign('nama_wilayah', $this->nama_wilayah);
        $this->smarty->assign('tahun_ajaran_id', $this->tahun_ajaran_id);
        $this->smarty->assign('nama_tahun_ajaran', $this->nama_tahun_ajaran);
        $this->smarty->assign('putaran', $this->putaran);
        $this->smarty->assign('nama_putaran', $this->nama_putaran);
        $this->smarty->assign('url', $this->url);

        //var_dump($this->putaran); exit; 

        //smarty: logged-in user
        if ($this->user_id) {
            $this->smarty->assign('user_id', $this->user_id);
            $this->smarty->assign('nama_pengguna', $this->nama_pengguna);
            $this->smarty->assign('user_name', $this->session->get('user_name'));
            // $this->smarty->assign('peran_id', $this->peran_id);
            // $this->smarty->assign('is_siswa', $this->is_siswa);    
            // $this->smarty->assign('is_sekolah', $this->is_sekolah);    
            // $this->smarty->assign('is_dapodik', $this->is_dapodik);    
            // $this->smarty->assign('is_dinas', $this->is_dinas);    
        }
        // $this->smarty->assign('peserta_didik_id', $this->peserta_didik_id);

        //smarty: flashdata
        $this->error_message = $this->session->getFlashdata('error');
        $this->success_message = $this->session->getFlashdata('success');
        $this->info_message = $this->session->getFlashdata('info');

        $this->smarty->assign('error_message', $this->error_message);
        $this->smarty->assign('success_message', $this->success_message);
        $this->smarty->assign('info_message', $this->info_message);
	}

	public function _remap($method, $param = null)
	{
        //must be authenticated? 
		$isLoggedIn = !empty($this->session->get('user_id'));
		if (static::$AUTHENTICATED && !$isLoggedIn) {
			if ($this->is_json) {
				print_json_error("not-login");
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
				print_json_error("not-authorized");
			} else {
                return $this->notauthorized();		//not-authorized
            }
		}

		if (empty($method)) {
			return $this->index();
		}

		if (method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), array());
		}
 
        return $this->index();
	}

	protected function index()
	{
		return $this->notfound();
	}


    protected function notauthorized()
    {
        // //content template
        $data['content_template'] = 'error-403.tpl';

		$data['page'] = 'error403';
 		$data['page_title'] = 'Terlarang';
 
        $this->smarty->render('ppdb/home/ppdbhome.tpl', $data);	
        
    }

    protected function notfound()
    {
        // //content template
        $data['content_template'] = 'error-404.tpl';

		$data['page'] = 'error404';
 		$data['page_title'] = 'Tidak Ditemukan';
 
        $this->smarty->render('ppdb/home/ppdbhome.tpl', $data);	
        
    }
}
