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
            return site_url() ."ppdb/dapodik/beranda";
        } 
        else if ($role_id == ROLEID_DINAS) {
            return site_url() ."ppdb/dinas";
        } 
        else {
            return site_url() ."user";
        }
    }
}

?>