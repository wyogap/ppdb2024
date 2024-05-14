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
        else {
            return site_url() ."user";
        }
    }
}

?>