<?php
namespace App\Libraries;

require_once ROOTPATH .'vendor/autoload.php';

class AuditTrail {
    protected $db = null;
    protected $session = null;

    public function __construct()
    {
        //init
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

	function trail($table, $reference, $action, $description, $keys = null, $values = null) {
        $user_id = $this->session->get("user_id");

        $str_keys = "";
        $str_values = "";

        if (is_array($keys) && $values == null) {
            unset($keys[COL_CREATED_ON]);
            unset($keys[COL_CREATED_BY]);
            unset($keys[COL_UPDATED_ON]);
            unset($keys[COL_UPDATED_BY]);
            unset($keys[COL_SOFT_DELETE]);
        }

        //for consistency
        if ($keys == null)      $values = null;

        if ($values == null) {
            if (is_array($keys)) {
                $str_values = implode(',', array_values($keys));
                $str_keys = implode(',', array_keys($keys));
            } else {
                $str_values = $keys;
                $str_keys = $keys;
            }
        }
        else {
            if (is_array($keys)) {
                $str_keys = implode(',', array_values($keys));
            }
            else {
                $str_keys = $keys;
            }

            if (is_array($values)) {
                $str_values = implode(',', array_values($values));
            }
            else {
                $str_values = $values;
            }
        }

        $valuepair = array (
            'table_name' => $table,
            'reference_id' => $reference,
            'action_name' => $action,
            'long_description' => $description,
            'col_names' => $str_keys,
            'col_values' => $str_values,
            'created_by' => $user_id
        );

        $builder = $this->db->table('dbo_audit_trails');
        $builder->insert($valuepair);
    }

    function insert($table, $reference, $keys, $values = null) {
        $user_id = $this->session->get("user_id");

        $action = "INSERT";
        $description = "Insert row";
        $str_keys = "";
        $str_values = "";

        if (is_array($keys) && $values == null) {
            unset($keys['created_on']);
            unset($keys['created_by']);
            unset($keys['updated_on']);
            unset($keys['updated_by']);
            unset($keys['is_deleted']);
        }

        if ($values == null) {
            if (is_array($keys)) {
                $str_values = implode(',', array_values($keys));
                $str_keys = implode(',', array_keys($keys));
            } else {
                $str_values = $keys;
                $str_keys = $keys;
            }
        }
        else {
            if (is_array($keys)) {
                $str_keys = implode(',', array_values($keys));
            }
            if (is_array($values)) {
                $str_values = implode(',', array_values($values));
            }
        }

        $valuepair = array (
            'table_name' => $table,
            'reference_id' => $reference,
            'action_name' => $action,
            'long_description' => $description,
            'col_names' => $str_keys,
            'col_values' => $str_values,
            'created_by' => $user_id
        );

        $builder = $this->db->table('dbo_audit_trails');
        $builder->insert($valuepair);
    }

    function update($table, $reference, $keys, $values = null) {
        $user_id = $this->session->get("user_id");

        $action = "UPDATE";
        $description = "Update row";
        $str_keys = "";
        $str_values = "";

        if (is_array($keys) && $values == null) {
            unset($keys['created_on']);
            unset($keys['created_by']);
            unset($keys['updated_on']);
            unset($keys['updated_by']);
            unset($keys['is_deleted']);
        }

        if ($values == null) {
            if (is_array($keys)) {
                $str_values = implode(',', array_values($keys));
                $str_keys = implode(',', array_keys($keys));
            } else {
                $str_values = $keys;
                $str_keys = $keys;
            }
        }
        else {
            if (is_array($keys)) {
                $str_keys = implode(',', array_values($keys));
            }
            if (is_array($values)) {
                $str_values = implode(',', array_values($values));
            }
        }

        $valuepair = array (
            'table_name' => $table,
            'reference_id' => $reference,
            'action_name' => $action,
            'long_description' => $description,
            'col_names' => $str_keys,
            'col_values' => $str_values,
            'created_by' => $user_id
        );

        $builder = $this->db->table('dbo_audit_trails');
        $builder->insert($valuepair);
    }

    function delete($table, $reference) {
        $user_id = $this->session->get("user_id");
 
         $action = "DELETE";
         $description = "Delete row";
         $str_keys = "";
         $str_values = "";
 
         $valuepair = array (
             'table_name' => $table,
             'reference_id' => $reference,
             'action_name' => $action,
             'long_description' => $description,
             'col_names' => $str_keys,
             'col_values' => $str_values,
             'created_by' => $user_id
         );
 
         $builder = $this->db->table('dbo_audit_trails');
         $builder->insert($valuepair);
    }
 
    function softdelete($table, $reference) {
        $user_id = $this->session->get("user_id");

        $action = "SOFT DELETE";
        $description = "Soft-delete row";
        $str_keys = "";
        $str_values = "";

        $valuepair = array (
            'table_name' => $table,
            'reference_id' => $reference,
            'action_name' => $action,
            'long_description' => $description,
            'col_names' => $str_keys,
            'col_values' => $str_values,
            'created_by' => $user_id
        );

        $builder = $this->db->table('dbo_audit_trails');
        $builder->insert($valuepair);
    }
}