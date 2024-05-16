<?php 

namespace App\Models\Core;

/**
 * Mcrud
 * 
 * This is the base class for CRUD Model used for internal usage.
 * This should NOT be used for data model used to build form display. For data model to build form display, use Mcrud_tablemeta.
 */

use App\Models\Core\Mtablemeta;
use App\Models\Core\BaseModel;
use App\Models\Core\ICrudModel;
use App\Libraries\AuditTrail;
use CodeIgniter\Database\ConnectionInterface;

class Mcrud implements ICrudModel
{
    protected static $TABLE_NAME = "table";
    protected static $PRIMARY_KEY = "id";
    protected static $COLUMNS = array();
    protected static $FILTERS = array();
    protected static $SEARCHES = array();
    protected static $COL_LABEL = 'label';
    protected static $COL_VALUE = 'value';

    protected static $VIEW_TABLE_NAME = null;
    protected static $SOFT_DELETE = true;

    protected static $JOIN_TABLES = array();

    protected static $DEF_PAGE_SIZE = 25;
    
    protected $name = "";
    protected $table_id = 0;
    protected $table_name = '';
    protected $initialized = false;
    protected $table_metas = null;
    
    protected $level1_filter = array();

    protected $db;
    protected $session;
    protected $audittrail;

    function __construct() {
        if (isset(static::$COLUMNS) && is_array(static::$COLUMNS) && count(static::$COLUMNS) > 0) {
            if (!isset(static::$FILTERS) || !is_array(static::$FILTERS) || count(static::$FILTERS) == 0) {
                static::$FILTERS = static::$COLUMNS;
            }
        }
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->audittrail = new AuditTrail();
    }

    function distinct_lookup($column_name, $filter = null) {
        if (empty(static::$COL_LABEL) || empty(static::$COL_VALUE)) return null;

        if ($filter == null || !is_array($filter)) {
            $filter = array();
        }

        //clean up non existing filter columns
        if (isset(static::$FILTERS) && is_array(static::$FILTERS) && count(static::$FILTERS) > 0 && count($filter)) {
            foreach($filter as $id => $value) {
                if (false === array_search($id, static::$FILTERS)) {
                    //invalid filter columns
                    unset($filter[$id]);
                }
            }
        }

        if (static::$SOFT_DELETE)   $filter['is_deleted'] = 0;

        //use view if specified
        $table_name = static::$VIEW_TABLE_NAME != null ? static::$VIEW_TABLE_NAME : static::$TABLE_NAME;

        $builder = $this->db->table($table_name);
        $builder->distinct();
        $builder->select($column_name .' as value ');
        $builder->where($filter);
        $arr = $builder->get()->getResultArray();
        if ($arr == null)       return $arr;

        foreach($arr as $key => $row) {
            $arr[$key]['label'] = $row['value'];
        }

        return $arr;
    }
    
    function lookup($filter = null) {
        if (empty(static::$COL_LABEL) || empty(static::$COL_VALUE)) return null;

        if ($filter == null || !is_array($filter)) {
            $filter = array();
        }

        //clean up non existing filter columns
        if (isset(static::$FILTERS) && is_array(static::$FILTERS) && count(static::$FILTERS) > 0 && count($filter)) {
            foreach($filter as $id => $value) {
                if (false === array_search($id, static::$FILTERS)) {
                    //invalid filter columns
                    unset($filter[$id]);
                }
            }
        }

        if (static::$SOFT_DELETE)   $filter['is_deleted'] = 0;

        //use view if specified
        $table_name = static::$VIEW_TABLE_NAME != null ? static::$VIEW_TABLE_NAME : static::$TABLE_NAME;

        $builder = $this->db->table($table_name);
        $builder->select(static::$COL_LABEL .' as label, '. static::$COL_VALUE .' as value');
        $builder->where($filter);
        return $builder->get()->getResultArray();
    }

    function search($query, $filter = null, $limit = null, $offset = null, $orderby = null) {
        if ($filter == null) $filter = array();

        //use view if specified
        $table_name = static::$VIEW_TABLE_NAME != null ? static::$VIEW_TABLE_NAME : static::$TABLE_NAME;
        $builder = $this->db->table($table_name);

        //clean up non existing filter columns
        if ($query != "" && $query != null) {
            $builder->groupStart();
            foreach($this->SEARCHES as $key => $val) {
                $builder->orLike($val, $query);
            }
            //if no column list specified for search, at least search in label an value column
            if (count($this->SEARCHES) == 0) {
                if (!empty(static::$COL_LABEL)) {
                    $builder->orLike(static::$COL_LABEL, $query);
                }
                if (!empty(static::$COL_VALUE)) {
                    $builder->orLike(static::$COL_VALUE, $query);
                }
            }
            $builder->groupEnd();
        }

        if ($filter != null && count($filter) > 0) {
            foreach($filter as $key => $val) {
                if (count(static::$FILTERS) == 0 || false !== array_search($key, static::$FILTERS)) {
                    $builder->where($key, $val);
                }
            }
        }

        if (static::$SOFT_DELETE)   $builder->where('is_deleted', 0);

        $str = $table_name. '.*';
        if (count(static::$COLUMNS) > 0) {
            $str =  $table_name. "." .implode(', ' .$table_name. '.', static::$COLUMNS);
        }

        $builder->select($str);
        // $builder = $this->db->table($table_name);

        //order by
        if (!empty($orderby)) {
            if (is_array($orderby)) {
                foreach($orderby as $value) {
                    $builder->orderBy($value);
                }
            }
            else {
                $builder->orderBy($orderby);
            }
        }

        $builder->limit($limit, $offset);
        return $builder->get()->getResultArray();
    }

    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        if ($filter == null) $filter = array();

        //use view if specified
        $table_name = static::$VIEW_TABLE_NAME != null ? static::$VIEW_TABLE_NAME : static::$TABLE_NAME;
        $builder = $this->db->table($table_name);

        //clean up non existing filter columns
        foreach($filter as $key => $val) {
            if (count(static::$FILTERS) == 0 || false !== array_search($key, static::$FILTERS)) {
                $builder->where($key, $val);
            }
        }

        if (static::$SOFT_DELETE)   $builder->where('is_deleted', 0);

        $str = $table_name. '.*';
        if (count(static::$COLUMNS) > 0) {
            $str =  $table_name. "." .implode(', ' .$table_name. '.', static::$COLUMNS);
        }

        $builder->select($str);
        // $builder = $this->db->table($table_name);

        //order by
        if (!empty($orderby)) {
            if (is_array($orderby)) {
                foreach($orderby as $value) {
                    $builder->orderBy($value);
                }
            }
            else {
                $builder->orderBy($orderby);
            }
        }

        $builder->limit($limit, $offset);
        return $builder->get()->getResultArray();
    }

    function detail($id, $filter = null) {
        if (!isset($filter)) {
            $filter = array();
        }

        $filter[static::$PRIMARY_KEY] = $id;
        if (static::$SOFT_DELETE)   $filter['is_deleted'] = 0;

        //use view if specified
        $table_name = static::$VIEW_TABLE_NAME != null ? static::$VIEW_TABLE_NAME : static::$TABLE_NAME;
        $builder = $this->db->table($table_name);

        $str = $table_name. '.*';
        if (count(static::$COLUMNS) > 0) {
            $str =  $table_name. "." .implode(', ' .$table_name. '.', static::$COLUMNS);
        }

        $builder->select($str);
        $builder->where($filter);

        return $builder->get()->getRowArray();       
    }

    function update($id, $valuepair, $filter = null, $enforce_edit_columns = true) {
        if (!isset($filter)) {
            $filter = array();
        }

        $filter[static::$PRIMARY_KEY] = $id;

        //clean up non existing columns
        if ($enforce_edit_columns) {
            if (isset(static::$COLUMNS) && is_array(static::$COLUMNS) && count(static::$COLUMNS) > 0) {
                foreach(array_keys($valuepair) as $key1) {
                    if (false === array_search($key1, static::$COLUMNS)) {
                        //invalid columns
                        unset($valuepair[$key1]);
                    }
                }
            }
        }

        //inject updated 
        $valuepair['updated_on'] = date('Y/m/d H:i:s');
        $valuepair['updated_by'] = $this->session->get('user_id');

        $builder = $this->db->table(static::$TABLE_NAME);
        $builder->where($filter);
        $builder->update($valuepair);
        
        $affected = $this->db->affectedRows();
        if ($affected > 0) {
            //audit trail
            $this->audittrail->update(static::$TABLE_NAME, $id, $valuepair);
        }

        return $id;
    }

    function delete($id, $filter = null) {
        if (!isset($filter)) {
            $filter = array();
        }

        $filter[static::$PRIMARY_KEY] = $id;

        //var_dump($filter); exit;

        if (static::$SOFT_DELETE) {
            $valuepair = array (
                'is_deleted' => 1,
                'updated_on' => date('Y/m/d H:i:s'),
                'updated_by' => $this->session->get('user_id')
            );

            $builder = $this->db->table(static::$TABLE_NAME);
            $builder->where($filter);
            $builder->update($valuepair);
        }
        else {
            $builder = $this->db->table(static::$TABLE_NAME);
            $builder->where($filter);
            $builder->delete();
        }

        $affected = $this->db->affectedRows();
        if ($affected > 0) {
            //audit trail
            $this->audittrail->delete(static::$TABLE_NAME, $id);
        }

        return $affected;
    }

    function add($valuepair, $enforce_edit_columns = true) {
        //clean up non existing columns
        if ($enforce_edit_columns) {
            if (isset(static::$COLUMNS) && is_array(static::$COLUMNS) && count(static::$COLUMNS) > 0) {
                foreach(array_keys($valuepair) as $id) {
                    if (false === array_search($id, static::$COLUMNS)) {
                        //invalid columns
                        unset($valuepair[$id]);
                    }
                }
            }
        }

        //inject
        $valuepair['created_by'] = $this->session->get('user_id');

        $builder = $this->db->table(static::$TABLE_NAME);
        $builder->insert($valuepair);

        $id = $this->db->insertID();
        if ($id > 0) {
            //audit trail
            $this->audittrail->insert(static::$TABLE_NAME, $id, $valuepair);

            return $id;
        } else {
            return 0;
        } 
    }

    function import($file, $filters = null) {
        //not-implemented yet
        return 0;
    }

    function init_with_tablemeta($arr) {
        //table name
        $this->table_id = $arr['id'];
        $this->name = $arr['name'];
        $this->table_name = $arr['table_name'];

        //table info
        $this->table_metas = Mtablemeta::$TABLE;
        $this->table_metas['name'] = $this->name;
        $this->table_metas['id'] = $arr['id'];

        $this->table_metas['ajax'] = site_url('crud/'.$this->table_name);
        $this->table_metas['table_id'] = 'tdata_'.$arr['id'];
        $this->table_metas['key_column'] = $arr['key_column'];
        $this->table_metas['initial_load'] = ($arr['initial_load'] == 1);
        $this->table_metas['row_id_column'] = ($arr['row_id_column'] == 1);
        $this->table_metas['row_select_column'] = ($arr['row_select_column'] == 1);

        $this->table_metas['lookup_column'] = $arr['lookup_column'];
        if (empty($this->table_metas['lookup_column'])) {
            $this->table_metas['lookup_column'] = $this->table_metas['key_column'];
        }

        $this->table_metas['table_name'] = $arr['table_name'];
        $this->table_metas['editable_table_name'] = $arr['editable_table_name'];
        if (empty($this->table_metas['editable_table_name'])) {
            $this->table_metas['editable_table_name'] = $this->table_metas['table_name'];
        }

        $this->table_metas['where_clause'] = "";
        if (!empty($arr['where_clause'])) {
            $this->table_metas['where_clause'] = $this->replace_parameters($arr['where_clause']);
        }

        $this->table_metas['orderby_clause'] = $arr['orderby_clause'];
        $this->table_metas['limit_selection'] = $arr['limit_selection'];
        $this->table_metas['soft_delete'] = ($arr['soft_delete'] == 1);

        $this->table_metas['page_size'] = $arr['page_size'];
        if (empty($this->table_metas['page_size'])) {
            $this->table_metas['page_size'] = static::$DEF_PAGE_SIZE;
        }
        
        $this->table_metas['custom_css'] = $arr['custom_css'];
        $this->table_metas['custom_js'] = $arr['custom_js'];

        $this->table_metas['search'] = ($arr['search'] == 1);
        $this->table_metas['filter'] = ($arr['filter'] == 1);
        $this->table_metas['edit'] = ($arr['allow_add'] == 1 || $arr['allow_edit'] == 1 || $arr['allow_delete'] == 1);

        // if no filter -> always autoload
        if (!$this->table_metas['filter'])  $this->table_metas['initial_load'] = true;

        $this->table_metas['columns'] = array();
        $this->table_metas['editor_columns'] = array();
        $this->table_metas['filter_columns'] = array();
        $this->table_metas['table_actions'] = array();
        $this->table_metas['custom_actions'] = array();
        $this->table_metas['row_actions'] = array();
        $this->table_metas['join_tables'] = array();

        //var_dump($this->table_metas); exit;

        return true;
    }

    function tablemeta() {
        return $this->table_metas;
    }

    function tablename() {
        return $this->table_name;   
    }

    function key_column() {
        return $this->table_metas['key_column'];
    }

    function filter_columns() {
        //not-implemented yet        
        return null;
    }

    function get_error_message() {
        //not-implemented yet
        return null;
    }    

    public function set_level1_filter($column_name, $value = null) {
        if ($value == null) {
            unset($this->level1_filter[$column_name]);
        }
        else {
            $this->level1_filter[$column_name] = $value;
        }
    }

    function replace_parameters($str) {
        if(empty($str))     return $str;

        return replace_parameters($str, 'filter', $this->level1_filter);
    }
    
}

  