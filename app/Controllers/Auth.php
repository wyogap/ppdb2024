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
}

?>