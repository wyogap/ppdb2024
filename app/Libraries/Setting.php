<?php
namespace App\Libraries;

class Setting
{
    protected static $SETTING_TABLE = 'dbo_settings';

    protected $db = null;
    protected $session = null;

    public function __construct()
    {
        //init
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    public function get($name, $default="", $group=null) {
        $filters = array (
            'name'          => $name,
            'is_deleted'    => 0
        );
        if ($group != null)     $filters['group'] = $group;

        $builder = $this->db->table(static::$SETTING_TABLE);
        $query = $builder->select('value')->getWhere($filters);
        $arr = $query->getRowArray();
        if ($arr == null) {
            return $default;
        }

        return $arr['value'];
    }

    public function set($name, $value, $group=null) {        
        $values = array (
            'value'         => $value,
            'updated_on'    => date('Y/m/d H:i:s'),     //utc
            'updated_by'    => $this->session->get('user_id')
        );

        $filters = array (
            'name'          => $name,
            'is_deleted'    => 0
        );
        if ($group != null)     $filters['group'] = $group;

        $builder = $this->db->table(static::$SETTING_TABLE);
        $builder->update($values, $filters);
    }

    public function list() {
        $filters = array (
            'is_deleted'    => 0
        );

        $builder = $this->db->table(static::$SETTING_TABLE);
        $query = $builder->select('name, group, value, description, autoload')->getWhere($filters);
        $arr = $query->getResultArray();

        return $arr;
    }

    public function list_group($group) {
        $filters = array (
            'is_deleted'    => 0,
            'group'         => $group
        );

        $builder = $this->db->table(static::$SETTING_TABLE);
        $query = $builder->select('name, group, value, description')->getWhere($filters);
        $arr = $query->getResultArray();

        return $arr;
    }
}