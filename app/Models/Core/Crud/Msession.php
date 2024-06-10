<?php

namespace App\Models\Core\Crud;

class Msession
{
    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    
    public function get_session() {

        //table metas
        $builder = $this->db->table('dbo_sessions a');
        $builder->select('a.*');
        $builder->orderBy('a.order_no asc');

        $builder->where('a.is_deleted', 0);

        $arr = $builder->get()->getResultArray();
        if ($arr == null) {
            return false;
        }

        return $arr;
    }

    public function get_user_session($user_id) {
        $builder = $this->db->table('dbo_user_sessions a');
        $builder->select('a.*');

        $builder->where('a.user_id', $user_id);
        $builder->where('a.is_deleted', 0);

        $arr = $builder->get()->getResultArray();
        if ($arr == null) {
            return false;
        }

        return $arr;
    }
}