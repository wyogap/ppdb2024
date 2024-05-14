<?php 

namespace App\Models\Core\Crud;

use App\Models\Core\Mcrud_ext;

class Mpages extends Mcrud_ext
{
    protected static $TABLE_ID = 5;     //default table
    protected static $TABLE_NAME = "dbo_crud_pages";
    protected static $PRIMARY_KEY = "id";
    protected static $LOOKUP_KEY = "name";
    protected static $COLUMNS = array();

    protected static $SOFT_DELETE = true;
    
    function list($filter = null, $limit = null, $offset = null, $orderby = null) {
        $arr = parent::list($filter, $limit, $offset, $orderby);
        if ($arr == null)   return $arr;

        foreach($arr as $key => $row) {
            if (empty($row['page_title'])) {
                $arr[$key]['page_title'] = ucwords( str_replace('.', ' ', $row['name']) );
            }    
        }

        return $arr;
    }    

    function get_page($name, $page_group = null) {
        $table_name = static::$TABLE_NAME;
        $builder = $this->db->table($table_name);

        //name based
        $builder->where('name', $name);
        $builder->where('is_deleted', 0);

        if ($page_group != null) {
            $builder->groupStart();
            $builder->where('page_group', $page_group);
            $builder->orWhere('page_group', NULL);
            $builder->groupEnd();
        }

        $str = $table_name. '.*';
        if (count(static::$COLUMNS) > 0) {
            $str =  $table_name. "." .implode(', ' .$table_name. '.', static::$COLUMNS);
        }
        $builder->select($str);

        //get the data
        $arr = $builder->get()->getRowArray();
        if ($arr == null)   return $arr;

        if (empty($arr['page_title'])) {
            $arr['page_title'] = ucwords( str_replace('.', ' ', $arr['name']) );
        }    
        
        return $arr;
    }

    function get_api_page($name, $enable_crud_api = true) {
        $filter = array();

        //only show api page
        if (!$enable_crud_api) {
            $filter['page_type'] = 'api';
        }

        //name based
        $filter['name'] = $name;

        $arr = parent::list($filter);
        if ($arr == null)   return $arr;

        //just get the first entry
        $arr = $arr[0];

        if (empty($arr['page_title'])) {
            $arr['page_title'] = ucwords( str_replace('.', ' ', $arr['name']) );
        }    
        
        return $arr;
    }

    function subtables($id, $with_table_meta = false) {
        $filter = array();

        $filter['page_id'] = $id;
        $filter['is_deleted'] = 0;

        //use view if specified
        $table_name = 'dbo_crud_pages_subtables';
        $builder = $this->db->table($table_name);

        $builder->select('*');
        $builder->orderBy('order_no asc');
        $builder->where($filter);
        $arr = $builder->get()->getResultArray();

        if ($arr == null)   return $arr;

        if ($with_table_meta) {
            $subtable = new Mtable();

            foreach($arr as $key => $val) {
                if (!$subtable->init($val['subtable_id'], true)) {
                    continue;
                }
                $tablemeta = $subtable->tablemeta();

                //dont autoload
                $tablemeta['initial_load'] = false;            

                $arr[$key]['crud'] = $tablemeta;
            }
        }

        return $arr;
    }

    function subtable_detail($id, $subtable_id, $with_table_meta = false) {
        $filter = array();

        //use view if specified
        $table_name = 'dbo_crud_pages_subtables';
        $builder = $this->db->table($table_name);

        $filter[$table_name. '.page_id'] = $id;
        $filter[$table_name. '.subtable_id'] = $subtable_id;
        $filter[$table_name. '.is_deleted'] = 0;

        $builder->select($table_name. '.*, lookup2.name as subtable_name');
        //$builder->join('dbo_crud_tables lookup1', 'lookup1.id=dbo_crud_pages_subtables.table_id AND lookup1.is_deleted=0', 'LEFT OUTER');
        $builder->join('dbo_crud_tables lookup2', 'lookup2.id=' .$table_name. '.subtable_id AND lookup2.is_deleted=0', 'LEFT OUTER');
        $builder->orderBy($table_name. '.order_no asc');
        $builder->where($filter);
        $arr = $builder->get()->getRowArray();

        if ($arr == null)   return $arr;

        if ($with_table_meta) {
            $subtable = new Mtable();
    
            if ($subtable->init($arr['subtable_id'], true)) {
                $tablemeta = $subtable->tablemeta();
    
                //dont autoload
                $tablemeta['initial_load'] = false;            
    
                $arr['crud'] = $tablemeta;
            }
        }

        return $arr;

    }

    function page_navigations($id) {
        $builder = $this->db->table('dbo_crud_page_navigations a');

        $builder->select('a.*, b.name as page_name');
        $builder->orderBy('order_no asc');
        $builder->join('dbo_crud_pages b', 'b.id=a.nav_page_id and b.is_deleted=0', 'LEFT OUTER');

        $builder->where('a.page_id', $id);
        $builder->where('a.is_deleted', 0);

        $arr = $builder->get()->getResultArray();

        return $arr;
    }


}

  