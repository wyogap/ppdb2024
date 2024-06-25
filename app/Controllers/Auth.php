<?php

namespace App\Controllers;

use App\Controllers\Core\AuthController;
use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Mhome;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Auth extends AuthController
{
    protected static $LOGIN_PAGE = "";

    protected $siswa = null;
    
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        helper("ppdb");
    }

    function index() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null; 
		if (empty($tahun_ajaran_id)) {
			$tahun_ajaran_id = $this->setting->get('tahun_ajaran');
		}
		
		$putaran = $_GET["putaran"] ?? null; 
		if (empty($putaran)) {
			$putaran = $this->setting->get('putaran');
		}
		
		$kode_wilayah_aktif = $_GET["kode_wilayah"] ?? null; 
		if (empty($kode_wilayah_aktif)) {
			$kode_wilayah_aktif = $this->setting->get('kode_wilayah');
		}
		
		$bentuk_sekolah_aktif = "SMP";

		$mconfig = new \App\Models\Ppdb\Mconfig();
        $nama_tahun_ajaran = $mconfig->tcg_nama_tahunajaran($tahun_ajaran_id);
        $nama_wilayah = $mconfig->tcg_nama_wilayah($kode_wilayah_aktif);
        $nama_putaran = $mconfig->tcg_nama_putaran($putaran);
        
		$sessiondata = array(
			'tahun_ajaran_aktif'=>$tahun_ajaran_id,
            'nama_tahun_ajaran_aktif'=>$nama_tahun_ajaran,
			'kode_wilayah_aktif'=>$kode_wilayah_aktif,
            'nama_wilayah_aktif'=>$nama_wilayah,
            'putaran_aktif'=>$putaran,
            'nama_putaran_aktif'=>$nama_putaran,
			'bentuk_sekolah_aktif'=>$bentuk_sekolah_aktif
		);	
		$this->session->set($sessiondata);

        $data['kode_wilayah']=$kode_wilayah_aktif;
        $data['nama_wilayah']=$nama_wilayah;
        $data['tahun_ajaran_id']=$tahun_ajaran_id;
        $data['nama_tahun_ajaran']=$nama_tahun_ajaran;
        $data['putaran']=$putaran;
        $data['nama_putaran']=$nama_putaran;

		$data['cek_registrasi'] = $mconfig->tcg_cek_wakturegistrasi();
		$data['cek_sosialisasi'] = $mconfig->tcg_cek_waktusosialisasi();

		$data['tahapan_aktif'] = $mconfig->tcg_tahapan_pelaksanaan_aktif();
        foreach($data['tahapan_aktif'] as $tahapan) {
            if ($tahapan['tahapan_id'] == 0 || $tahapan['tahapan_id'] == 99) {
                $data['cek_registrasi'] = 0;
                break;
            }
        }
		$data['pengumuman'] = $mconfig->tcg_pengumuman();

        $putaran = $mconfig->tcg_putaran();
        foreach($putaran as $k => $p) {
            $putaran[$k]['tahapan'] = $mconfig->tcg_tahapan_pelaksanaan($p['putaran']);
        }
        $data['putaran'] = $putaran;

        $petunjuk = $mconfig->tcg_petunjuk_pelaksanaan();
        $data['petunjuk_pelaksanaan'] = array();
        if (!empty($petunjuk)) {
            // $data['petunjuk_pelaksanaan'][] = array("id"=>1, "title"=>"JADWAL PELAKSANAAN", "text"=>$petunjuk['jadwal_pelaksanaan']);
            $data['petunjuk_pelaksanaan'][] = array("id"=>2, "title"=>"PERSYARATAN", "text"=>$petunjuk['persyaratan']);
            $data['petunjuk_pelaksanaan'][] = array("id"=>3, "title"=>"TATA CARA PENDAFTARAN", "text"=>$petunjuk['tata_cara_pendaftaran']);
            $data['petunjuk_pelaksanaan'][] = array("id"=>4, "title"=>"JALUR PENDAFTARAN", "text"=>$petunjuk['jalur_pendaftaran']);
            $data['petunjuk_pelaksanaan'][] = array("id"=>5, "title"=>"PROSES SELEKSI", "text"=>$petunjuk['proses_seleksi']);
            $data['petunjuk_pelaksanaan'][] = array("id"=>6, "title"=>"KONVERSI NILAI", "text"=>$petunjuk['konversi_nilai']);
        }
        
        // if ($data['cek_captcha']) {
        //     $sitekey="6LfUN-oUAAAAAAEiaEPyE-S-d3NRbzXZVoNo51-x";
        //     if(strpos(base_url(), 'localhost')) {
        //         $sitekey="6LdDOOoUAAAAABvtPcoIZ4RHTm545Wb9lgD8j2Ab";
        //     }
        //     $data['captcha_sitekey'] = $sitekey;
        // }

        $user_id = $this->session->get('user_id');
        if (!empty($user_id)) {
            $data['nama_pengguna'] = $this->session->get('nama');
            $data['user_name'] = $this->session->get('user_name');
        }
        $data['user_id'] = $user_id;

        $data['login_page'] = site_url() .static::$LOGIN_PAGE;

        //dont show standard footer text
        $data['show_footer'] = 0;
        $data['enforce_no_dark_theme'] = 1;

        $rekapitulasi = intval($this->setting->get('rekapitulasi', '0'));
        $data['rekapitulasi'] = $rekapitulasi;

        if (__DEBUGGING__) {
            $data['cek_registrasi'] = 1;
        }

		$this->smarty->render('ppdb/home/login.tpl',$data);
    }

    protected function get_home() {
        $role_id = $this->session->get('role_id');
        if ($role_id == ROLEID_SYSADMIN) {
            return site_url() ."sistem";
        } 
        else if ($role_id == ROLEID_ADMIN) {
            return site_url() ."admin";
        } 
        else if ($role_id == ROLEID_SISWA) {
            return site_url() ."ppdb/siswa";
        } 
        else if ($role_id == ROLEID_SEKOLAH) {
            return site_url() ."ppdb/sekolah/beranda";
        } 
        else if ($role_id == ROLEID_DAPODIK) {
            return site_url() ."ppdb/dapodik/daftarsiswa";
        } 
        else if ($role_id == ROLEID_DINAS) {
            return site_url() ."ppdb/dinas";
        } 
        else {
            return site_url() ."user";
        }
    }

    protected function do_additional_checks($result, $json) {
        $role_id = $result['role_id'];

        if ($role_id == ROLEID_SISWA) {
            $peserta_didik_id = $result['peserta_didik_id'];

            if (empty($this->siswa) || $this->siswa['peserta_didik_id'] != $peserta_didik_id) {
                $msiswa = new \App\Models\Ppdb\Siswa\Mprofilsiswa();
                $this->siswa = $msiswa->tcg_profilsiswa($peserta_didik_id);
            }
            
            if (empty($this->siswa)) {
                $error = 'Akun anda tidak terkonfigurasi dengan benar. Silahkan hubungi Admin Dinas.';
                if ($json == 1) {
                    $data = array('status'=>'0', 'error'=>$error);
                    echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                }
                else {
                    $this->session->setFlashdata('error', $error);	
                }
                return false;
            }
            
            // //akses ditutup
            // if ($siswa['tutup_akses'] == '1') {
            //     $error = __('Akses login anda untuk sementara ditolak');
            //     if ($json == 1) {
            //         $data = array('status'=>'0', 'error'=>$error);
            //         echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
            //     }
            //     else {
            //         $this->session->setFlashdata('error', $error);	
            //     }
            //     return false;
            // }
 
            //cabut berkas
            if ($this->siswa['cabut_berkas'] == '1') {
                $error = __('Anda sudah melakukan cabut berkas. Akses login anda ditolak!');
                if ($json == 1) {
                    $data = array('status'=>'0', 'error'=>$error);
                    echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                }
                else {
                    $this->session->setFlashdata('error', $error);	
                }
                return false;
            }
            
        }
        else if ($role_id == ROLEID_SEKOLAH) {
            $sekolah_id = $result['sekolah_id'];

            $msekolah = new \App\Models\Ppdb\Sekolah\Mprofilsekolah();
            $sekolah = $msekolah->tcg_profilsekolah($sekolah_id);
            
            if (empty($sekolah)) {
                $error = 'Akun anda tidak terkonfigurasi dengan benar. Silahkan hubungi Admin Dinas.';
                if ($json == 1) {
                    $data = array('status'=>'0', 'error'=>$error);
                    echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                }
                else {
                    $this->session->setFlashdata('error', $error);	
                }
                return false;
            }

            if ($sekolah['ikut_ppdb'] != '1') {
                $error = __('Sekolah anda tidak ikut PPDB Online');
                if ($json == 1) {
                    $data = array('status'=>'0', 'error'=>$error);
                    echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                }
                else {
                    $this->session->setFlashdata('error', $error);	
                }
                return false;
            }
            
        }
        else if ($role_id == ROLEID_DAPODIK) {
            $sekolah_id = $result['sekolah_id'];

            $msekolah = new \App\Models\Ppdb\Sekolah\Mprofilsekolah();
            $sekolah = $msekolah->tcg_profilsekolah($sekolah_id);
            
            if (empty($sekolah)) {
                $error = 'Akun anda tidak terkonfigurasi dengan benar. Silahkan hubungi Admin Dinas.';
                if ($json == 1) {
                    $data = array('status'=>'0', 'error'=>$error);
                    echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                }
                else {
                    $this->session->setFlashdata('error', $error);	
                }
                return false;
            }
        }
        
        return true;
    }

    protected function set_additional_sessions() {
        $settings = $this->setting->list("ppdb");
        if ($settings == null)  return;

        foreach($settings as $s) {
            if ($s['autoload'] != '1')   continue;
            if ($s['name'] == 'batasan_peta_polygon')   continue;
            if ($s['name'] == 'tahun_ajaran')   $this->session->set('tahun_ajaran_aktif', $s['value']);
            if ($s['name'] == 'kode_wilayah')   $this->session->set('kode_wilayah_aktif', $s['value']);
            $this->session->set($s['name'], $s['value']);
        }

        $role_id = $this->session->get('role_id');
        if ($role_id == ROLEID_SISWA) {
            $peserta_didik_id = $this->session->get('peserta_didik_id');

            if (empty($this->siswa) || $this->siswa['peserta_didik_id'] != $peserta_didik_id) {
                $msiswa = new \App\Models\Ppdb\Siswa\Mprofilsiswa();
                $this->siswa = $msiswa->tcg_profilsiswa($peserta_didik_id);
            }

            $data['diterima'] = 1;
            $data['tutup_akses'] = 1;
            if (!empty($this->siswa)) {
                $data['diterima'] = $this->siswa['diterima'];
                $data['tutup_akses'] = $this->siswa['tutup_akses'];
            }

            $this->session->set($data);
        }
    }
 
    function changepassword() {

        $json = array();

        $data = $this->request->getGetPost('data');
        if ($data == null) {
            $json['error'] = 'no-data';
            echo json_encode($json, JSON_INVALID_UTF8_IGNORE);
            return;
        }
         
        $key = array_keys($data)[0];
        $userid = $this->session->get('user_id');
        $mpermission = new \App\Models\Core\Crud\Mpermission();
        if (!$mpermission->is_admin() && $key != $userid) {
            $json['error'] = 'not-authorized';
            echo json_encode($json, JSON_INVALID_UTF8_IGNORE);
            return;
        }

        $error_msg = "";
        foreach ($data as $key => $valuepair) {
            $user_id = $key;
            $pwd1 = $valuepair['pwd1'];
            $pwd2 = $valuepair['pwd2'];

            if ($pwd1 != $pwd2) {
                $json['error'] = __("Password baru tidak sama. Silahkan ulangi kembali.");
                continue;
            }

            if($this->Mauth->reset_password($user_id, $pwd1, array('ganti_password'=>1)) == 0) {
                $json['error'] = __("Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.");
                continue;
            } else {
                $user = $this->Mauth->profile($user_id);
                if ($user != null) {
                    $json['data'] = array();
                    $json['data'][] = $user; 
                } 
            }
        }

        echo json_encode($json, JSON_INVALID_UTF8_IGNORE);
    }

}

?>