<?php

namespace App\Controllers;

use App\Controllers\Core\AuthController;

class Auth extends AuthController
{
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

            $msiswa = new \App\Models\Ppdb\Siswa\Mprofilsiswa();
            $siswa = $msiswa->tcg_profilsiswa($peserta_didik_id);
            
            //akses ditutup
            if ($siswa['tutup_akses'] == '1') {
                $error = __('Akses login anda untuk sementara ditolak');
                if ($json == 1) {
                    $data = array('status'=>'0', 'error'=>$error);
                    echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
                }
                else {
                    $this->session->setFlashdata('error', $error);	
                }
                return false;
            }
 
            //cabut berkas
            if ($siswa['cabut_berkas'] == '1') {
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

    }

    
    function resetpassword() {

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