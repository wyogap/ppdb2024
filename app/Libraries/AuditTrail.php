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

	function trail($table, $reference, $action, $description, $values = null) {
        $user_id = $this->session->get("user_id");

        $str_keys = "";
        $str_values = "";

        unset($values[COL_CREATED_ON]);
        unset($values[COL_CREATED_BY]);
        unset($values[COL_UPDATED_ON]);
        unset($values[COL_UPDATED_BY]);
        unset($values[COL_SOFT_DELETE]);

        $keys = array_keys($values);

        $str_keys = implode(',', array_values($keys));
        $str_values = json_encode($values, JSON_INVALID_UTF8_IGNORE);

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

    function insert($table, $reference, $values) {
        $user_id = $this->session->get("user_id");

        $action = "INSERT";
        $description = "Insert row";
        $str_keys = "";
        $str_values = "";

        unset($values[COL_CREATED_ON]);
        unset($values[COL_CREATED_BY]);
        unset($values[COL_UPDATED_ON]);
        unset($values[COL_UPDATED_BY]);
        unset($values[COL_SOFT_DELETE]);

        $keys = array_keys($values);

        $str_keys = implode(',', array_values($keys));
        $str_values = json_encode($values, JSON_INVALID_UTF8_IGNORE);

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

    function update($table, $reference, $values, $old_values = null) {
        $user_id = $this->session->get("user_id");

        $action = "UPDATE";
        $description = "Update row";
        $str_keys = "";
        $str_values = "";
        $str_oldvalues = "";

        unset($values[COL_CREATED_ON]);
        unset($values[COL_CREATED_BY]);
        unset($values[COL_UPDATED_ON]);
        unset($values[COL_UPDATED_BY]);
        unset($values[COL_SOFT_DELETE]);

        $keys = array_keys($values);

        $str_keys = implode(',', array_values($keys));
        $str_values = json_encode($values, JSON_INVALID_UTF8_IGNORE);

        if ($old_values != null) {
            $updated = array();
            foreach($keys as $k) {
                $updated[$k] = empty($old_values[$k]) ? '' : $old_values[$k];
            }

            $str_oldvalues = json_encode($updated, JSON_INVALID_UTF8_IGNORE);            
        }

        $valuepair = array (
            'table_name' => $table,
            'reference_id' => $reference,
            'action_name' => $action,
            'long_description' => $description,
            'col_names' => $str_keys,
            'col_values' => $str_values,
            'old_values' => $str_oldvalues,
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