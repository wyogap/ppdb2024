<?php
namespace App\Controllers\Ppdb;

use App\Controllers\Core\BaseController;

use App\Models\Ppdb\Mconfig;
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
    protected $is_json = false;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library

        //load model
        $this->Mconfig = new Mconfig();

        //URI params
        $segments = $this->request->getUri()->getSegments();
        $total = count($segments);
        $controller = strtolower(basename(get_class($this)));

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

        //is-json
        if (!$this->is_json) {
            //other than the index, all are json call
            $this->is_json = ($this->method != '') && ($this->method != 'index');
        }

        // //is-json
        // $json_segment = $this->method=='json' || (array_search('json', $segments) !== FALSE);
        // $json_param = !empty($this->request->getGetPost("json"));

        // $this->is_json = ($json_segment || $json_param);

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

        //common vars
        $nama_wilayah = "";
		$kode_wilayah = "";

		//get from GET or POST
		$kode_wilayah = $_POST["kode_wilayah"] ?? null; 
		if (empty($kode_wilayah)) {
			$kode_wilayah = $_GET["kode_wilayah"] ?? null; 
		}

        //get from session if necessary
        if (empty($kode_wilayah)) {
            $kode_wilayah = $this->session->get('kode_wilayah_aktif');
			$nama_wilayah = $this->session->get('nama_wilayah_aktif');
        }

        //get from database
        if (empty($kode_wilayah)) {
            $kode_wilayah = $this->setting->get("kode_wilayah");
            $nama_wilayah = $this->setting->get("nama_wilayah");
        }

        if (!empty($kode_wilayah) && empty($nama_wilayah)) {
            $nama_wilayah = $this->Mconfig->tcg_nama_wilayah($kode_wilayah);
        }

		//set from session if necessary
		if ($this->session->get('kode_wilayah_aktif') != $kode_wilayah) {
            $this->session->set('kode_wilayah_aktif', $kode_wilayah);
            $this->session->set('nama_wilayah_aktif', $nama_wilayah);
		}

		//replace global var if necessary
        $this->kode_wilayah = $kode_wilayah;
        $this->nama_wilayah = $nama_wilayah;

		//tahun ajaran
		$nama_tahun_ajaran = "";
		$tahun_ajaran_id = "";

        //get from GET or POST
		$tahun_ajaran_id = $_POST["tahun_ajaran"] ?? null; 
		if (empty($tahun_ajaran_id)) {
			$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null; 
		}
 
        //get from session if necessary
        if (empty($tahun_ajaran_id)) {
            $tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
			$nama_tahun_ajaran = $this->session->get('nama_tahun_ajaran_aktif');
        }

		//get from database
		if (empty($tahun_ajaran_id)) {
			$tahun_ajaran_id = $this->setting->get("tahun_ajaran");
		}

        if (!empty($tahun_ajaran_id) && empty($nama_tahun_ajaran)) {
            $nama_tahun_ajaran = $this->Mconfig->tcg_nama_tahunajaran($tahun_ajaran_id);
        }

		if ($this->session->get('tahun_ajaran_aktif') != $tahun_ajaran_id) {
            $this->session->set('tahun_ajaran_aktif', $tahun_ajaran_id);
            $this->session->set('nama_tahun_ajaran_aktif', $nama_tahun_ajaran);
		}

		//replace global var if necessary
        $this->tahun_ajaran_id = $tahun_ajaran_id;
        $this->nama_tahun_ajaran = $nama_tahun_ajaran;

		$nama_putaran = "";
		$putaran = "";

        //get from GET or POST
		$putaran = $_POST["putaran"] ?? null; 
		if (empty($putaran)) {
			$putaran = $_GET["putaran"] ?? null; 
		}
 
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

        //smarty: general setting
        $arr = $this->setting->list_group('ppdb');
        foreach($arr as $val) {
            $this->smarty->assign($val['name'], $val['value']);
        }

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

        // $this->waktu_verifikasi = $this->Msetting->tcg_cek_waktuverifikasi();
        // $this->waktu_daftarulang = $this->Msetting->tcg_cek_waktudaftarulang();

        // $this->smarty->assign('waktu_verifikasi', $this->waktu_verifikasi);
        // $this->smarty->assign('waktu_daftarulang', $this->waktu_daftarulang);
	}

	public function _remap($method, $param = null)
	{
        //must be authenticated? 
		$isLoggedIn = !empty($this->session->get('user_id'));
		if (static::$AUTHENTICATED && !$isLoggedIn) {
			if ($this->is_json) {
				$this->print_json_error("not-login");
			} else {
				return redirect()->to(site_url() .'auth');
			}
		}

        //must be role_id?
        $role_id = $this->session->get("role_id");
		if (!empty(static::$ROLE_ID) && 
                //current role is not static::$ROLE_ID
                ((!is_array(static::$ROLE_ID) && static::$ROLE_ID != $role_id) || 
                //static::$ROLE_ID can be an array
                (is_array(static::$ROLE_ID) && array_search($role_id,static::$ROLE_ID) === FALSE))) {
			if ($this->is_json) {
				$this->print_json_error("not-authorized");
			} else {
                return view('ppdb/home/notauthorized');		//not-authorized
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
		return view('ppdb/home/notauthorized');
	}

    protected function print_json_error($error_message, $error_no = -1) {
        $json = array();
        $json["status"] = 0;
        $json["error"] = $error_message;
        if (!empty($error_no)) {
            $json["errorno"] = $error_no;
        }

        //TODO: output properly
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE); 
        exit;
    }

    protected function print_json_output($data) {
        $json = array();
        $json["status"] = 1;
        if (!empty($data)) {
            $json['data'] = $data;
        }

        //TODO: output properly
        echo json_encode($json, JSON_INVALID_UTF8_IGNORE); 
        exit;
    }

    protected function audit_siswa($peserta_didik_id, $action_type, $action_description, $columns, $new_values, $old_values) {

    }

    protected function audit_pendaftaran($pendaftaran_id, $action_type, $action_description, $columns, $new_values, $old_values) {
        
    }

}
