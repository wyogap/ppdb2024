<?php 

namespace App\Models\Core;

/**
 * Mcrud_tablemeta
 * 
 * This is the base class for CRUD Model which retrieve table definition from dabatase (TableMeta).
 * This should be used for data model used to build form display.
 * 
 * This class is by default REUSABLE. Just re-init with the current tableid.
 */

use App\Models\Core\Mtablemeta;
use App\Models\Core\BaseModel;
use App\Models\Core\ICrudModel;
use App\Libraries\AuditTrail;
use App\Libraries\Setting;
use App\Libraries\Uploader;
use CodeIgniter\Database\ConnectionInterface;
use Exception;

class Mcrud_tablemeta implements ICrudModel
{
    protected static $DEF_TABLE_ID = 0;     //default table

    protected static $DEF_PAGE_SIZE = 25;
    protected static $REGEX_USERDATA = '/{{(\w+)}}/';

    public const INVALID_VALUE = "null";

    protected $name = '';
    protected $table_id = 0;
    protected $table_name = '';
    protected $initialized = false;
    
    /* Data Structure */
    protected $table_metas = null;
    protected $column_metas = null;     //equivalent to table_metas.columns
    protected $editor_metas = null;     //equivalent to table_metas.editor_columns. also stored directly in table_metas.column.editor
    protected $filter_metas = null;     //equivalent to table_metas.filter_columns
    protected $sorting_metas = null;    //equivalent to table_metas.sorting_columns

    protected $join_tables = null;

    protected $table_actions = null;
    protected $row_actions = null;
    protected $custom_actions = null;

    /* Raw list/array */
    protected $columns = null;                  //list of column names
    protected $edit_columns = null;             //list of editable column names
    protected $select_columns = null;           //list of select column names
    protected $filter_columns = null;           //list of filter fields (NOT filterable columns!!!)
    protected $search_columns = null;           //list of searchable column names
    protected $sorting_columns = null;          //list of sorting column names
 
    protected $lookup_columns = null;

    protected $level1_filter = array();

    protected $error_code = 0;
    protected $error_message = null;

    protected $column_groupings = null;
    protected $column_grouping_map = null;

    //public $error = array();

    public static $XLSX_FILE_TYPE = "Xlsx";
    public static $XLS_FILE_TYPE = "Xls";

    protected $db;
    protected $session;
    protected $audittrail;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->audittrail = new AuditTrail();

        //helper
        helper('gettext');
        helper('functions');
    }

    function init_for_lookup($name_or_id, $is_table_id = false) {
        $this->reset_error();

        if ($this->initialized && 
            (($is_table_id && $this->table_id == $name_or_id) 
                || (!$is_table_id && $this->table_name == $name_or_id))
            ) {
            return true;
        }

        $this->initialized = false;
                
        //table metas
        $filter = null;
        if ($is_table_id) {
            $filter = array('id'=>$name_or_id, 'is_deleted'=>0);
        }
        else {
            $filter = array('name'=>$name_or_id, 'is_deleted'=>0);
        }

        $builder = $this->db->table('dbo_crud_tables');
        $builder->select('*');
        $builder->where($filter);
        $arr = $builder->get()->getRowArray();
        if ($arr == null) {
            return false;
        }

        if (!$this->init_with_tablemeta_for_lookup($arr)) {
            return false;
        }

        $this->initialized = true;

        return true;
    }

    function init($name_or_id, $is_table_id = false, $level1_column = null, $level1_value = null) {
        $this->reset_error();

        if ($this->initialized && 
            (($is_table_id && $this->table_id == $name_or_id) 
                || (!$is_table_id && $this->table_name == $name_or_id))
            ) {
            return true;
        }

        $this->initialized = false;
                
        //table metas
        $filter = null;
        if ($is_table_id) {
            $filter = array('id'=>$name_or_id, 'is_deleted'=>0);
        }
        else {
            $filter = array('name'=>$name_or_id, 'is_deleted'=>0);
        }

        $builder = $this->db->table('dbo_crud_tables');
        $builder->select('*');
        $builder->where($filter);
        $arr = $builder->get()->getRowArray();
        if ($arr == null) {
            return false;
        }

        if (!$this->init_with_tablemeta($arr, $level1_column, $level1_value)) {
            return false;
        }

        $this->initialized = true;

        return true;
    }

    function init_with_tablemeta_for_lookup($arr) {
        $this->reset_error();
        
        if ($this->initialized && $this->table_id == $arr['id']) {
            return true;
        }
        
        $this->initialized = false;
        
        //table name
        $this->table_id = $arr['id'];
        $this->name = $arr['name'];
        $this->table_name = $arr['table_name'];

        //table info
        $this->table_metas = Mtablemeta::$TABLE;
        $this->table_metas['name'] = $this->name;
        $this->table_metas['id'] = $arr['id'];
        $this->table_metas['table_id'] = 'tdata_'.$arr['id'];
        $this->table_metas['key_column'] = $arr['key_column'];

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

        $this->column_metas = array();
        $this->columns = array();

        //columns metas
        do {
            $builder = $this->db->table('dbo_crud_columns');
            $builder->select('*');
            $builder->orderBy('order_no asc');
            $builder->where(array('table_id'=>$this->table_id, 'is_deleted'=>0));
            $arr = $builder->get()->getResultArray();
            if ($arr == null) {
                break;
            }
    
            foreach($arr as $row) {
                $col = Mtablemeta::$COLUMN;
                $col['name'] = trim($row['name']);
                $col['ci_name'] = strtoupper($col['name']);     //case-insensitive name => all capital
                $col['label'] = __($row['label']);
                $col['column_name'] = $row['column_name'];
                if (empty($col['column_name'])) {
                    $col['column_name'] = $this->table_name. "." .$col['name'];
                }

                //if already exist, ignore. prevent duplicate
                if (false !== array_search($col['ci_name'], $this->columns)) {
                   continue;
                }

                $this->column_metas[] = $col;
                $this->columns[] = $col['ci_name'];
            }                    
        } 
        while (false);

        if (empty($this->table_metas['key_column']) && count($this->column_metas)>0) {
            $this->table_metas['key_column'] = $this->column_metas[0]['name'];
        }

        if (empty($this->table_metas['lookup_column']) && count($this->column_metas)>0) {
            $this->table_metas['lookup_column'] = $this->column_metas[0]['name'];
        }
        
        //always add lookup-column
        $ci_name = strtoupper($this->table_metas['lookup_column']);
        if (false === array_search($ci_name, $this->columns)) {
            $col = Mtablemeta::$COLUMN;
            $col['name'] = $this->table_metas['lookup_column'];
            $col['ci_name'] = $ci_name;
            $col['label'] = __('Lookup');

            //add to beginning
            array_unshift($this->column_metas, $col);
            array_unshift($this->columns, $ci_name);
        }

        //always add key-column
        $ci_name = strtoupper($this->table_metas['key_column']);
        if (false === array_search($ci_name, $this->columns)) {
            $col = Mtablemeta::$COLUMN;
            $col['name'] = $this->table_metas['key_column'];
            $col['ci_name'] = $ci_name;
            $col['label'] = __('Key');

            //add to beginning
            array_unshift($this->column_metas, $col);
            array_unshift($this->columns, $ci_name);
        }

        $this->table_metas['columns'] = $this->column_metas;

        $this->initialized = true;

        return true;
    }

    function init_with_tablemeta($arr, $level1_column = null, $level1_value = null) {
        $this->reset_error();
        
        if ($this->initialized && $this->table_id == $arr['id']) {
            return true;
        }
        
        //var_dump($arr);
        //d($arr['name']); d($arr['initial_load']); 

        $this->initialized = false;
        
        //level1 filter
        if ($level1_column !== null && $level1_value !== null) {
            $this->level1_filter[$level1_column] = $level1_value;
        }

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
        $this->table_metas['detail_template'] = $arr['detail_template'];
        $this->table_metas['editor_template'] = $arr['editor_template'];

        $this->table_metas['filter'] = ($arr['filter'] == 1);
        $this->table_metas['column_filter'] = ($arr['column_filter'] == 1);

        $this->table_metas['edit'] = ($arr['allow_add'] == 1 || $arr['allow_edit'] == 1 || $arr['allow_delete'] == 1);

        $this->table_metas['title'] = empty($arr['title']) ? $this->name : $arr['title'];

        $this->table_metas['search'] = ($arr['search'] == 1);
        $this->table_metas['search_max_result'] = empty($arr['search_max_result']) ? 0 : $arr['search_max_result'];

        $this->table_metas['orderby_clause'] = empty($arr['orderby_clause']) ? null : $arr['orderby_clause'];
        $this->table_metas['limit_selection'] = empty($arr['limit_selection']) ? null : $arr['limit_selection'];

        $this->table_metas['row_reorder'] = ($arr['row_reorder'] == 1);
        $this->table_metas['row_reorder_column'] = empty($arr['row_reorder_column']) ? $arr['key_column'] : $arr['row_reorder_column'];
        $this->table_metas['multi_row_selection'] = ($arr['multi_row_selection'] == 1);
        $this->table_metas['show_paging_options'] = ($arr['show_paging_options'] == 1);
        $this->table_metas['multi_edit'] = ($arr['multi_edit'] == 1);
        $this->table_metas['inline_edit'] = ($arr['inline_edit'] == 1);

        $this->table_metas['client_side_query'] = ($arr['client_side_query'] == 1);
        $this->table_metas['client_side_filter'] = ($arr['client_side_filter'] == 1);

        $this->table_metas['add_custom_js'] = $arr['add_custom_js'];
        $this->table_metas['edit_custom_js'] = $arr['edit_custom_js'];
        $this->table_metas['delete_custom_js'] = $arr['delete_custom_js'];
        $this->table_metas['on_add_custom_js'] = $arr['on_add_custom_js'];
        $this->table_metas['on_edit_custom_js'] = $arr['on_edit_custom_js'];
        $this->table_metas['on_delete_custom_js'] = $arr['on_delete_custom_js'];
        $this->table_metas['on_select_custom_js'] = $arr['on_select_custom_js'];
        $this->table_metas['on_submit_custom_js'] = $arr['on_submit_custom_js'];

        $this->table_metas['export_custom_js'] = $arr['export_custom_js'];
        $this->table_metas['import_custom_js'] = $arr['import_custom_js'];

        //table actions
        $this->table_actions = Mtablemeta::$TABLE_ACTION;
        $this->table_actions['add'] = ($arr['allow_add'] == 1);
        $this->table_actions['edit'] = ($arr['allow_edit'] == 1);
        $this->table_actions['delete'] = ($arr['allow_delete'] == 1);
        $this->table_actions['export'] = ($arr['allow_export'] == 1);
        $this->table_actions['import'] = ($arr['allow_import'] == 1);
        $this->table_actions['add_custom_js'] = $arr['add_custom_js'];
        $this->table_actions['edit_custom_js'] = $arr['edit_custom_js'];
        $this->table_actions['delete_custom_js'] = $arr['delete_custom_js'];
        $this->table_actions['export_custom_js'] = $arr['export_custom_js'];
        $this->table_actions['import_custom_js'] = $arr['import_custom_js'];

        $this->table_actions['edit_row_action'] = ($arr['edit_row_action'] == 1);
        $this->table_actions['delete_row_action'] = ($arr['delete_row_action'] == 1);
        $this->table_actions['edit_conditional_js'] = $arr['edit_conditional_js'];
        $this->table_actions['delete_conditional_js'] = $arr['delete_conditional_js'];

        $this->table_metas['edit_row_action'] = ($arr['edit_row_action'] == 1);
        $this->table_metas['delete_row_action'] = ($arr['delete_row_action'] == 1);
        $this->table_metas['edit_conditional_js'] = $arr['edit_conditional_js'];
        $this->table_metas['delete_conditional_js'] = $arr['delete_conditional_js'];

        $this->column_metas = array();
        $this->editor_metas = array();
        $this->filter_metas = array();
        $this->sorting_metas = array();
        $this->join_tables = array();
        $this->row_actions = array();
        $this->custom_actions = array();

        $this->columns = array();
        $this->edit_columns = array();
        $this->select_columns = array();
        //$this->filter_columns = array();
        $this->search_columns = array();
        $this->sorting_columns = array();

        $this->column_groupings = array();
        $this->column_grouping_map = array();
        
        $this->column_groupings = array();

        // var_dump($arr);
        // var_dump($this->table_actions);
        //inline edit row
        if ($this->table_actions['edit_row_action'] && $this->table_actions['edit']) {
            $row_action = Mtablemeta::$ROW_ACTION;
            $row_action['label'] = "Edit";
            $row_action['icon'] = "fa fa-edit fas";
            $row_action['icon_only'] = true;
            $row_action['css'] = "btn-inline-edit btn-info";
            $row_action['onclick_js'] = "dt_tdata_".$this->table_id."_edit_row";
            if(!empty($this->table_actions['edit_conditional_js'])) {
                $row_action['conditional_js'] = $this->table_actions['edit_conditional_js'];
            }
            $row_action['tooltip'] = "Edit";
            $this->row_actions[] = $row_action;
        }

        //inline delete row
        if ($this->table_actions['delete_row_action'] && $this->table_actions['delete']) {
            $row_action = Mtablemeta::$ROW_ACTION;
            $row_action['label'] = "Delete";
            $row_action['icon'] = "fa fa-trash fas";
            $row_action['icon_only'] = true;
            $row_action['css'] = "btn-inline-delete btn-danger";
            $row_action['onclick_js'] = "dt_tdata_".$this->table_id."_delete_row";
            if(!empty($this->table_actions['delete_conditional_js'])) {
                $row_action['conditional_js'] = $this->table_actions['delete_conditional_js'];
            }
            $row_action['tooltip'] = "Hapus";
            $this->row_actions[] = $row_action;
        }

        //columns grouping
        do {
            $builder = $this->db->table('dbo_crud_column_groupings');
            $builder->select('*');
            $builder->orderBy('order_no asc');
            $builder->where(array('table_id'=>$this->table_id, 'is_deleted'=>0));
            $arr = $builder->get()->getResultArray();
            if ($arr == null) {
                break;
            }

            $idx = 0;
            foreach($arr as $row) {
                $grp = Mtablemeta::$COLUMN_GROUPING;

                $grp['id'] = $row['id'];
                $grp['label'] = $row['label'];
                $grp['icon'] = $row['icon'];
                $grp['icon_only'] = ($row['icon_only'] == 1);
                $grp['css'] = $row['css'];
                $grp['columns'] = array();
                $grp['idx'] = $idx++;

                //add into list
                $this->column_groupings[] = $grp;
                $this->column_grouping_map[ $grp['id'] ] = $grp;
            }

        } while(0);

        //column idx
        $column_idx = 0;

        //lookup alias
        $lookup_idx = 1;

        //column filter
        $column_filter = 0;

        //columns metas
        do {
            $builder = $this->db->table('dbo_crud_columns');
            $builder->select('*');
            $builder->orderBy('order_no asc');
            $builder->where(array('table_id'=>$this->table_id, 'is_deleted'=>0));
            $arr = $builder->get()->getResultArray();
            if ($arr == null) {
                break;
            }
    
            foreach($arr as $row) {
                //skip level1 column
                if ($level1_column == $row['name']) continue;
                
                $col = Mtablemeta::$COLUMN;
                $col['name'] = trim($row['name']);
                $col['ci_name'] = strtoupper($col['name']);     //case-insensitive name => all capital
                $col['label'] = __($row['label']);
                $col['visible'] = ($row['visible'] == 1);
                $col['export'] = ($row['export'] == 1);
                $col['total_row'] = ($row['total_row'] == 1);
                if ($col['total_row']) {
                    $this->table_metas['footer_row'] = true;
                }
                $col['css'] = $row['css'];
                $col['type'] = $row['column_type'];
                $col['data_priority'] = $row['data_priority'];
                $col['column_name'] = $row['column_name'];
                if (empty($col['column_name'])) {
                    $col['column_name'] = $this->table_name. "." .$col['name'];
                }

                //add link to open in new tab
                $col['open_url'] = $row['open_url'];
                if ( !empty($col['open_url']) && strpos($col['open_url'], 'http') !== 0 ) {
                    $col['open_url'] = site_url() .$col['open_url'];
                }
                $col['open_url_label'] = $row['open_url_label'];
                if (empty($col['open_url_label'])) {
                    $col['open_url_label'] = "External Link";
                }

                // var_dump($col['column_name']);
                // var_dump($this->select_columns);

                //if already exist, ignore. prevent duplicate
                if (false !== array_search($col['ci_name'], $this->columns)) {
                    continue;
                 }
 
                //if already exist, ignore. prevent duplicate
                if (false !== array_search($col['column_name'], $this->select_columns)) {
                   continue;
                }

                //if it is virtual, disallow filter and search
                if ($col['type'] == 'virtual') {
                    $row['foreign_key'] = 0;
                    $row['allow_filter'] = 0;
                    $row['allow_search'] = 0;
                    $col['options'] = null;
                    $row['options_data_url'] = null;
                }

                $col['display_format_js'] = $row['display_format_js'];

                $col['foreign_key'] = ($row['foreign_key'] == 1);
                $col['reference_controller'] = $row['reference_controller'];
                $col['reference_show_link'] = ($row['reference_show_link'] == 1);

                $col['allow_insert'] = ($row['allow_insert'] == 1);
                $col['allow_edit'] = ($row['allow_edit'] == 1);

                //allow filter only if it is enabled in table level
                $col['allow_filter'] = false;
                if ($this->table_metas['filter'])   $col['allow_filter'] = true;

                //allow search only if it is enabled in table level
                $col['allow_search'] = false;
                if ($this->table_metas['search'])   $col['allow_search'] = (isset($row['allow_search']) && $row['allow_search'] == 1);

                //show filtering in column header
                $col['column_filter'] = ($row['column_filter'] == 1);
                if ($col['column_filter'])  $column_filter = 1;

                //hack for backward compatibility
                if (!isset($row['allow_sort'])) $col['allow_sort'] = true;
                else $col['allow_sort'] = ($row['allow_sort'] == 1);
                
                //default: no bubble edit
                $col['edit_bubble'] = false;

                if (!empty($row['options_array'])) {
                    $col['options'] = json_decode($row['options_array'], true);
                    if ($col['options'] != null) {
                        $options = array();
                        foreach($col['options'] as $idx => $value) {
                            $options[] = array (
                                'label' => $value,
                                'value' => $idx
                            );
                        }
                        $col['options'] = $options;
                    }
                    // var_dump($row['options_array']);
                    // var_dump($col['options']);
                }
                else if (!empty($row['options_data_model'])) {
                    $model = null;
                    try {
                        if (strpos($row['options_data_model'], 'Mcrud_tablemeta') !== false) {
                            $model = $this->get_dynamic_model($row['options_data_model']);
                        }
                        else {
                            $model = $this->get_model($row['options_data_model']);
                        }
                        //set level1 filter if any
                        if ($level1_column !== null && $level1_value !== null) {
                            $model->set_level1_filter($level1_column, $level1_value);
                        }
                    }
                    catch (Exception $e) {
                        //ignore
                    }

                    if ($model != null) {
                        $col['options_data_model'] = $model;
                        $col['options'] = $model->lookup();
                    }

                    // var_dump($level1_column);
                    // var_dump($level1_value);
                    // var_dump($col['options']);
                }

                $col['options_data_url_params'] = array();
                if (empty($row['options_data_url'])) {
                    $col['options_data_url'] = '';
                }
                else {
                    $url = $row['options_data_url'];
                    $params = null;
                    $matches = null;

                    preg_match_all('{{{[\w]*}}}', $url, $matches);
                    if ($matches != null && count($matches) > 0) {
                        $params = array();
                        foreach($matches[0] as $m) {
                            $colname = substr($m, 2, strlen($m)-4);
                            if ($colname == $level1_column) {
                                $url = str_replace($m, $level1_value, $url);
                            }
                            else {
                                $params[] = $colname;
                            }
                        }
                    }

                    $col['options_data_url'] = $url;
                    $col['options_data_url_params'] = $params;
                }
                
                $col['edit_field'] = $row['edit_field'];
                if (empty($col['edit_field'])) {
                    $col['edit_field'] = $col['name'];
                }

                //split as array
                $col['edit_field'] = array_map('trim', explode(',', $col['edit_field']));
                
                //var_dump($col['edit_field']);
                
                // //if type=upload, force foreign key lookup
                // if ($col['type'] == "tcg_upload") {
                //     //link as foreign key
                //     $col['foreign_key'] = true;
                //     $row['reference_table_name'] = "dbo_uploads";
                //     $row['reference_fkey_column'] = "id";
                //     $row['reference_lookup_column'] = "filename";
                //     $row['reference_soft_delete'] = 1;
                // }

                //search column
                if ($this->table_metas['search'] && $col['allow_search']) {
                    $this->search_columns[] = $col['column_name'];
                }

                if ($col['foreign_key']) {
                    $ref = Mtablemeta::$TABLE_JOIN;
                    $ref['name'] = $col['name'];
                    $ref['column_name'] = $col['column_name'];

                    //reference to table
                    $ref['reference_table_name'] = $row['reference_table_name'];
                    $ref['reference_soft_delete'] = ($row['reference_soft_delete'] == 1);
                    $ref['reference_where_clause'] = $row['reference_where_clause'];
                    if (!empty($ref['reference_where_clause'])) {
                        $ref['reference_where_clause'] = $this->replace_parameters($ref['reference_where_clause']);
                    }

                    //reference to subquery
                    $ref['reference_custom_query'] = $this->replace_parameters($row['reference_custom_query']);

                    //lookup key
                    $ref['reference_fkey_column'] = $row['reference_fkey_column'];
                    $ref['reference_lookup_column'] = $row['reference_lookup_column'];
                    if (empty($ref['reference_lookup_column'])) {
                        $ref['reference_lookup_column'] = $ref['reference_fkey_column'];
                    }
                    //secondary lookup key
                    $ref['reference_key2_column'] = $row['reference_key2_column'];
                    $ref['reference_fkey2_column'] = $row['reference_key2_column'];
            
                    //use alias in case multiple reference of the same table (ie. lookup table)
                    $ref['reference_alias'] = $row['reference_alias'];
                    if (empty($ref['reference_alias'])) {
                        $ref['reference_alias'] = 'lookup_' .$lookup_idx++;
                    }

                    if ($col['type'] == 'tcg_multi_select') {
                        $subquery = "select group_concat(" .$ref['reference_lookup_column']. " separator ', ') from " .$ref['reference_table_name']. " where find_in_set(" .$ref['reference_fkey_column']. ", " .$ref['column_name']. ") > 0";

                        if ($ref['reference_soft_delete']) {
                            $subquery .= " AND is_deleted=0";
                        }

                        //add into select
                        $col['label_column'] = "(" .$subquery. ")";
                        $col['label_type'] = "subquery";

                        $this->select_columns[] = "(" .$subquery. ") as ".$ref['name']."_label";
                    }
                    else {
                        //default: tcg_select2
                        $this->join_tables[ $col['name'] ] = $ref;

                        //add into select
                        $col['label_column'] = $ref['reference_alias'].".".$ref['reference_lookup_column'];
                        $col['label_type'] = "join";

                        $this->select_columns[] = $ref['reference_alias'].".".$ref['reference_lookup_column']." as ".$ref['name']."_label";
                    }

                    // IMPORTANT: only retrieve pre-loaded options when edit/add is allowed
                    //get lookup if not specified manually (only if edit/add is allowed)
                    if (($this->table_actions['add'] || $this->table_actions['edit']) && ($col['allow_insert'] || $col['allow_edit']) &&
                            empty($col['options']) && empty($col['options_data_url']) && 
                            ($row['edit_type'] == 'tcg_select2')) {
                        if (!empty($ref['reference_custom_query'])) {
                            $col['options'] = $this->get_lookup_options_from_query($ref['reference_custom_query']);
                        }
                        else {
                            $col['options'] = $this->get_lookup_options($ref['reference_table_name'], $ref['reference_fkey_column'], $ref['reference_lookup_column']
                                                                        , $ref['reference_key2_column']
                                                                        , $ref['reference_soft_delete'], $ref['reference_where_clause'], $ref['reference_alias']);
                        }
                    }

                    //search column -> search lookup
                    //var_dump($col['allow_search']);
                    if (!empty($col['allow_search'])) {
                        $this->search_columns[] = $ref['reference_alias'].".".$ref['reference_lookup_column'];
                    }
                }

                if ($this->table_metas['edit'] && ($col['allow_insert'] || $col['allow_edit'])) {
                    $editor = Mtablemeta::$EDITOR;
                    $editor['name'] = $row['name'];
                    $editor['allow_insert'] = $col['allow_insert'];
                    $editor['allow_edit'] = $col['allow_edit'];
                    $editor['edit_field'] = $col['edit_field'];

                    $editor['edit_label'] = __($row['edit_label']);
                    if (empty($editor['edit_label'])) {
                        $editor['edit_label'] = ucwords($col['label']);
                    }
                                   
                    $editor['edit_css'] = $row['edit_css'];
                    $editor['edit_compulsory'] = ($row['edit_compulsory'] == 1);
                    $editor['edit_info'] = $row['edit_info'];
                    if (!empty($editor['edit_info'])) {
                        $editor['edit_info'] = str_replace('"', '\"', $editor['edit_info']);
                        $editor['edit_info'] = str_replace("'", "\'", $editor['edit_info']);
                        $editor['edit_info'] = htmlspecialchars($editor['edit_info']);
                    }
                    $editor['edit_onchange_js'] = $row['edit_onchange_js'];
                    $editor['edit_validation_js'] = $row['edit_validation_js'];
                    $editor['edit_bubble'] = ($row['edit_bubble'] == 1);
                    $editor['edit_vertical'] = ($row['edit_vertical'] == 1);

                    //$editor['edit_readonly'] = !$col['allow_edit'];
                    $editor['edit_readonly'] = ($row['edit_readonly'] == 1);

                    //store in column metas
                    $col['edit_bubble'] = $editor['edit_bubble'];

                    $editor['edit_def_value'] = $row['edit_def_value'];

                    if (!empty($editor['edit_def_value'])) {
                        $editor['edit_def_value'] = $this->replace_parameters(trim($editor['edit_def_value']));
                    }

                    if (!empty($row['edit_options_array'])) {
                        $editor['edit_options'] = json_decode($row['edit_options_array']);
                    } else if (!empty($col['options'])) {
                        $editor['edit_options'] = $col['options'];
                    }

                    if (!empty($row['edit_attr_array'])) {
                        $editor['edit_attr'] = json_decode($row['edit_attr_array'], true); 
                        //force readonly if necessary
                        if (!empty($editor['edit_attr']['readonly'])) {
                            $col["readonly"] = true;
                        }
                    }
                    else {
                        $editor['edit_attr'] = array();
                    }

                    //force select2
                    $editor['edit_type'] = $row['edit_type'];
                    if (!empty($editor['edit_options']) && empty($editor['edit_type'])) {
                        $editor['edit_type'] = 'tcg_select2';
                    }
                    if (empty($editor['edit_type'])) {
                        $editor['edit_type'] = $col['type'];
                    }

                    //option_url
                    if ($editor['edit_type'] == 'tcg_select2' && !empty($col['options_data_url'])) {
                        $editor['options_data_url'] = $col['options_data_url'];
                        $editor['options_data_url_params'] = $col['options_data_url_params'];
                    }

                    if ($editor['edit_type'] == 'tcg_table' && !empty($row['subtable_id'])) {
                        $editor['subtable_id'] = $row['subtable_id'];
                        $editor['subtable_name'] = $this->get_subtable_name($row['subtable_id']);
                        $editor['subtable_key_column'] = $row['subtable_key_column'];
                        $editor['subtable_fkey_column'] = $row['subtable_fkey_column'];
                        $editor['subtable_key_column2'] = $row['subtable_key_column2'];
                        $editor['subtable_fkey_column2'] = $row['subtable_fkey_column2'];
                        $editor['subtable_order'] = $row['subtable_order'];
                        // if (!empty($editor['subtable_order'])) {
                        //     $editor['edit_attr']['rowOrder'] = json_decode($editor['subtable_order'], true);
                        // }
                        $editor['subtable_row_reorder_column'] = $row['subtable_row_reorder_column'];
                        if (!empty($editor['subtable_row_reorder_column'])) {
                            $editor['edit_attr']['rowReorderColumn'] = $editor['subtable_row_reorder_column'];
                        }
                        
                        $editor['subtable_columns'] = $this->get_subtable_columns($editor['subtable_id']);
                    }

                    foreach($editor['edit_field'] as $idx => $f) {
                        if (empty($f))  continue;

                        $col_name = $this->table_name. "." .$f;
                        if ($col_name != $col['column_name']) {
                            $this->select_columns[] = $col_name." as ".$f;
                        }
                    }

                    $this->editor_metas[] = $editor;
                    $this->edit_columns[] = $col['name'];

                    //column grouping
                    if (count($this->column_groupings) > 1) {
                        $grp_id = $row['edit_column_grouping'];

                        if (empty($grp_id)) {
                            $grp = $this->column_groupings[0];
                        } else {
                            $grp = $this->column_grouping_map[$grp_id];
                        }
                        
                        if (empty($grp)) {
                            $grp = $this->column_groupings[0];
                        }

                        //update column list in grouping
                        $this->column_groupings[ $grp['idx'] ]['editors'][] = $editor;
                        $this->column_grouping_map[ $grp['id'] ]['editors'][] = $editor;
                    }

                    //stored for easy access
                    $col["editor"] = $editor;
                }

                // 2024-03-25: FILTER is now moved to separate table
                // if ($this->table_metas['filter'] && $col['allow_filter']) {
                //     $filter = Mtablemeta::$FILTER;
                //     $filter['name'] = $row['name'];
                //     $filter['allow_filter'] = $col['allow_filter'];

                //     $filter['filter_label'] = __($row['filter_label']);
                //     if (empty($filter['filter_label'])) {
                //         $filter['filter_label'] = ucwords($col['label']);
                //     }

                //     $filter['filter_css'] = $row['filter_css'];
                //     $filter['filter_onchange_js'] = $row['filter_onchange_js'];

                //     if (!empty($row['filter_attr_array'])) {
                //         $filter['filter_attr'] = json_decode($row['filter_attr_array']);
                //     }

                //     $row['filter_data_url'] = '';
                //     if (!empty($col['options_data_url'])) {
                //         $row['filter_data_url'] = $col['options_data_url'];
                //     }

                //     if (!empty($row['filter_data_url'])) {
                //         if (!isset($filter['filter_attr'])) {
                //             $filter['filter_attr'] = array();
                //         }
                //         $filter['filter_attr']['ajax'] = $row['filter_data_url'];
                //     } else if (!empty($row['filter_options_array'])) {
                //         $filter['filter_options'] = json_decode($row['filter_options_array']);
                //     } else if (!empty($col['options'])) {
                //         $filter['filter_options'] = $col['options'];
                //     }

                //     //force select2
                //     $filter['filter_type'] = $row['filter_type'];
                //     if (!empty($filter['filter_options']) && empty($filter['filter_type'])) {
                //         $filter['filter_type'] = 'tcg_select2';
                //     }
                //     if (empty($filter['filter_type'])) {
                //         $filter['filter_type'] = $col['type'];
                //     }

                //     $filter['filter_invalid_value'] = ($row['filter_invalid_value'] == 1);

                //     $this->filter_metas[] = $filter;
                //     $this->filter_columns[] = $col['name'];
                // }
    
                //bubble editor 
                $col['edit_bubble'] = ($row['edit_bubble'] == 1);
                $col['column_no'] = $column_idx;

                //add into the collection
                $this->column_metas[] = $col;
                if ($col['type'] == 'virtual') {
                    $this->select_columns[] = "'' as ".$col['name'];
                }
                else {
                    $this->select_columns[] = $col['column_name']." as ".$col['name'];
                }
                $this->columns[] = $col['ci_name'];

                // 2024-03-25: upload column is now implemented as a single column. Metainfo is stored in dbo_uploads table
                // //if type=upload, add related columns
                // if ($col['type'] == "tcg_upload") {
                //     $col_name = $col['name'];
                //     $col_label = $col['label'];

                //     //filenames
                //     $col = Mtablemeta::$COLUMN;
                //     $col['name'] = $col_name .'_filename';
                //     $col['ci_name'] = strtoupper($col['name']);
                //     $col['label'] = $col_label .' (File Name)';
                //     $col['column_name'] = $this->table_name. "." .$col['name'];
                //     $col['visible'] = false;
    
                //     $col['foreign_key'] = false;
                //     $col['allow_insert'] = false;
                //     $col['allow_edit'] = false;
                //     $col['allow_filter'] = false;
                //     $col['allow_sort'] = false;

                //     $this->column_metas[] = $col;
                //     $this->select_columns[] = $col['column_name']." as ".$col['name'];
                //     $this->columns[] = $col['ci_name'];

                //     //path
                //     $col = Mtablemeta::$COLUMN;
                //     $col['name'] = $col_name .'_path';
                //     $col['ci_name'] = strtoupper($col['name']);
                //     $col['label'] = $col_label .' (Path)';
                //     $col['column_name'] = $this->table_name. "." .$col['name'];
                //     $col['visible'] = false;
    
                //     $col['foreign_key'] = false;
                //     $col['allow_insert'] = false;
                //     $col['allow_edit'] = false;
                //     $col['allow_filter'] = false;
                //     $col['allow_sort'] = false;

                //     $this->column_metas[] = $col;
                //     $this->select_columns[] = $col['column_name']." as ".$col['name'];
                //     $this->columns[] = $col['ci_name'];

                //     //thumbnail
                //     $col = Mtablemeta::$COLUMN;
                //     $col['name'] = $col_name .'_thumbnail';
                //     $col['ci_name'] = strtoupper($col['name']);
                //     $col['label'] = $col_label .' (Thumbnail)';
                //     $col['column_name'] = $this->table_name. "." .$col['name'];
                //     $col['visible'] = false;
    
                //     $col['foreign_key'] = false;
                //     $col['allow_insert'] = false;
                //     $col['allow_edit'] = false;
                //     $col['allow_filter'] = false;
                //     $col['allow_sort'] = false;

                //     $this->column_metas[] = $col;
                //     $this->select_columns[] = $col['column_name']." as ".$col['name'];
                //     $this->columns[] = $col['ci_name'];
                // }
            
                //default column sorting
                if ($col['allow_sort'] && !empty($row['default_sort_no'])) {
                    $sort = Mtablemeta::$SORTING;
                    $sort['name'] = $col['ci_name'];
                    $sort['column_no'] = $column_idx;
                    $sort['sort_no'] = $row['default_sort_no'];
                    $sort['sort_asc'] = $row['default_sort_asc'];

                    $this->sorting_metas[] = $sort;
                    $this->sorting_columns[] = $col['ci_name'];
                }

                $column_idx++;
            }         
        } 
        while (false);
        
        //if no column-filter is enabled in the column, ignore the table level setting
        if ($column_filter == 0) {
            $this->table_metas['column_filter'] = false;
        }

        //default key column is first column
        if (empty($this->table_metas['key_column']) && count($this->column_metas)>0) {
            $this->table_metas['key_column'] = $this->column_metas[0]['name'];
        }

        //default lookup column is first column
        if (empty($this->table_metas['lookup_column']) && count($this->column_metas)>0) {
            $this->table_metas['lookup_column'] = $this->column_metas[0]['name'];
        }
        
        //always add lookup-column
        $key_column_name = $this->table_name. "." .$this->table_metas['lookup_column']. ' as ' .$this->table_metas['lookup_column'];       
        if (false === array_search($key_column_name, $this->select_columns)) {
            $col = Mtablemeta::$COLUMN;
            $col['name'] = $this->table_metas['lookup_column'];
            $col['label'] = __('Lookup');
            $col['visible'] = true;
            $col['data_priority'] = 10;
            $col['column_name'] = $key_column_name;

            $col['allow_insert'] = true;
            $col['allow_edit'] = true;
            $col['allow_filter'] = false;

            //add to beginning
            array_unshift($this->column_metas, $col);
            array_unshift($this->select_columns, $key_column_name);

            //update column idx
            for($i=0; $i<count($this->column_metas); $i++) {
                $this->column_metas[$i]['column_no'] = $i;
            }

            //editable? -> need to add to var editor
        }

        //always add key-column
        $key_column_name = $this->table_name. "." .$this->table_metas['key_column']. ' as ' .$this->table_metas['key_column'];       
        if (false === array_search($key_column_name, $this->select_columns)) {
            $col = Mtablemeta::$COLUMN;
            $col['name'] = $this->table_metas['key_column'];
            $col['label'] = __('Key');
            $col['visible'] = true;
            $col['data_priority'] = 1;
            $col['column_name'] = $key_column_name;

            $col['allow_insert'] = false;
            $col['allow_edit'] = false;
            $col['allow_filter'] = false;

            //add to beginning
            array_unshift($this->column_metas, $col);
            array_unshift($this->select_columns, $key_column_name);

            //update column idx
            for($i=0; $i<count($this->column_metas); $i++) {
                $this->column_metas[$i]['column_no'] = $i;
            }
        }

        //custom actions
        do {
            $builder = $this->db->table('dbo_crud_custom_actions a');
            $builder->select('a.*');
            $builder->orderBy('a.order_no asc');
            $builder->where(array('a.table_id'=>$this->table_id, 'a.is_deleted'=>0));
            $arr = $builder->get()->getResultArray();
            if ($arr == null) {
                break;
            }

            foreach($arr as $row) {
                $action = Mtablemeta::$CUSTOM_ACTION;
                $action['label'] = $row['label'];
                $action['icon'] = $row['icon'];
                $action['icon_only'] = ($row['icon_only'] == 1);
                $action['css'] = $row['css'];
                $action['onclick_js'] = $row['onclick_js'];
                $action['selected'] = $row['selected'];

                $this->custom_actions[] = $action;
            }

        } while (false);
 
        //row actions
        do {
            $builder = $this->db->table('dbo_crud_row_actions');
            $builder->select('*');
            $builder->orderBy('order_no asc');
            $builder->where(array('table_id'=>$this->table_id, 'is_deleted'=>0));
            $arr = $builder->get()->getResultArray();
            if ($arr == null) {
                break;
            }

            foreach($arr as $row) {
                $action = Mtablemeta::$ROW_ACTION;
                $action['label'] = $row['label'];
                $action['icon'] = $row['icon'];
                $action['icon_only'] = ($row['icon_only'] == 1);
                $action['css'] = $row['css'];
                $action['onclick_js'] = $row['onclick_js'];
                $action['conditional_js'] = $row['conditional_js'];
                $action['open_url'] = $row['open_url'];
                if ( !empty($action['open_url']) && strpos($action['open_url'], 'http') !== 0 ) {
                    $action['open_url'] = site_url() .$action['open_url'];
                }
                $action['tooltip'] = $row['tooltip'];
                if ($action['icon_only'] && empty($action['tooltip'])) {
                    $action['tooltip'] = $action['label'];
                }

                $this->row_actions[] = $action;
            }

        } while (false);

        //$this->table_metas['filter_columns'] = $this->filter_metas;

        //filters
        $this->filter_metas = array();
        $this->filter_columns = array();

        if ($this->table_metas['filter']) {
            do {
                $builder = $this->db->table('dbo_crud_filters a');
                $builder->select('a.*, b.name as column_name');
                $builder->orderBy('a.order_no asc');
                $builder->join("dbo_crud_columns b","b.id=a.column_id and b.is_deleted=0","LEFT OUTER");
                $builder->where(array('a.table_id'=>$this->table_id, 'a.is_deleted'=>0));
                $arr = $builder->get()->getResultArray();
                if ($arr == null) {
                    break;
                }
        
                foreach($arr as $row) {
                    $name = trim($row['name']);

                    //if already exist, ignore. prevent duplicate
                    if (false !== array_search($name, $this->filter_columns)) {
                        continue;
                    }
    
                    $filter = Mtablemeta::$FILTER;
                    $filter['name'] = $name;
                    $filter['label'] = __($row['label']);
                    $filter['type'] = trim($row['type']);
                    $filter['css'] = trim($row['css']);
                    $filter['onchange_js'] = trim($row['onchange_js']);
                    $filter['invalid_value'] = ($row['invalid_value'] == 1);

                    $filter['controller_id'] = $row['controller_id'];
                    $filter['controller_params'] = trim($row['controller_params']);
                    $filter['options_data_url'] = trim($row['options_data_url']);

                    $filter['column_id'] = $row['column_id'];
                    $filter['column_name'] = $row['column_name'];

                    $filter['attr_array'] = "";
                    if (!empty($row['attr_array'])) {
                        $filter['attr'] = json_decode($row['attr_array']);
                    } 

                    $filter['options_array'] = "";
                    if (!empty($row['options_array'])) {
                        $filter['options'] = json_decode($row['options_array']);
                    } 

                    //search in select columns
                    $col = null;
                    $col_idx = array_search(strtoupper($filter['column_name']), $this->columns);
                    if (false !== $col_idx) {
                        $col = $this->column_metas[$col_idx];
                        if (!empty($col['options'])) {
                            $filter['options'] = $col['options'];
                        }
                    };

                    //force select2
                    if (!empty($filter['options']) && empty($filter['type'])) {
                        $filter['type'] = 'tcg_select2';
                    }
                    if (empty($filter['type']) && $col != null) {
                        $filter['type'] = $col['type'];
                    }

                    if (!empty($filter['controller_id'])) {
                        $controller = $this->get_controller_meta($filter['controller_id']);
                        if ($controller != null) {
                            $filter['options_table_name'] = $controller['name'];
                            $filter['options_key_column'] = $controller['key_column'];
                            $filter['options_label_column'] = $controller['lookup_column'];
                            $filter['soft_delete'] = ($controller['soft_delete'] == 1);
                            if (empty($filter['options_data_url'])) {
                                $filter['options_data_url']= 'crud/'. $controller['controller_name'] .'/lookup';
                                if (!empty($filter['controller_params'])) {
                                    $filter['options_data_url'] .= "?" .$filter['controller_params'];
                                }
                            }
                        }
                    }

                    //parse the parameter
                    $filter['options_data_url_params'] = array();
                    $filter['cascading_filters'] = array();
                    if (!empty($row['options_data_url'])) {
                        $url = $row['options_data_url'];
                        $matches = null;

                        preg_match_all('{{{[\w]*}}}', $url, $matches);
                        if ($matches != null && count($matches) > 0) {

                            foreach($matches[0] as $m) {
                                $colname = substr($m, 2, strlen($m)-4);
                                if ($colname == $level1_column) {
                                    $url = str_replace($m, $level1_value, $url);
                                }
                                //add cascading filter
                                else if(substr($colname, 0, 2) == 'f_') {
                                    //dont reference itself
                                    if ($colname == 'f_'.$filter['name'])   continue;
                                    $filter['cascading_filters'][] = $colname;
                                }
                                //other params
                                else {
                                    $filter['options_data_url_params'][] = $colname;
                                }
                            }
                        }
                    }
                    
                    $this->filter_metas[] = $filter;
                    $this->filter_columns[] = $name;
                }                    
            } 
            while (false);
        }

        //var_dump($this->filter_metas); exit;

        //sortings
        $this->sorting_metas = array();
        $this->sorting_columns = array();

        do {
            $builder = $this->db->table('dbo_crud_column_orderings a');
            $builder->select('b.name, a.*');
            $builder->orderBy('a.order_no asc');
            $builder->join('dbo_crud_columns b', 'b.id=a.column_id and b.is_deleted=0', 'INNER');
            $builder->where(array('a.table_id'=>$this->table_id, 'a.is_deleted'=>0));
            $arr = $builder->get()->getResultArray();
            if ($arr == null) {
                break;
            }

            foreach($arr as $row) {
                $name = trim($row['name']);

                //if already exist, ignore. prevent duplicate
                $col_idx = array_search($name, $this->sorting_columns);
                if (false !== $col_idx)   continue;

                //search in select columns
                $col_idx = array_search(strtoupper($name), $this->columns);
                if (false === $col_idx) continue;

                $sort = Mtablemeta::$SORTING;
                $sort['name'] = $name;
                $sort['column_no'] = $col_idx;
                $sort['sort_no'] = $row['order_no'];
                $sort['sort_asc'] = ($row['ascending'] == 1);;

                $this->sorting_metas[] = $sort;
                $this->sorting_columns[] = $name;
            }                    
        } 
        while (false);

        //always add default sort from table definition
        $ci_name = null;
        if (!empty($this->table_metas['orderby_clause'])) {
            $sort_idx = 1;

            $arr1 = explode(',', $this->table_metas['orderby_clause']);
            foreach($arr1 as $col) {
                $arr2 = explode(' ', trim($col));

                $ci_name = strtoupper(trim($arr2[0]));
                if (empty($ci_name))   continue;

                //already in sort-columns
                if (false !== array_search($ci_name, $this->sorting_columns))   continue;

                //search in select columns
                $col_idx = array_search($ci_name, $this->columns);
                if (false === $col_idx) continue;
                
                //asc or desc
                $col_asc = true;
                if (count($arr2)>1) {
                    $txt = substr(trim($arr2[1]), 0, 4);
                    if (strtoupper($txt) == 'DESC') {
                        $col_asc = false;
                    }
                }

                //add to sorting column
                $sort = Mtablemeta::$SORTING;
                $sort['name'] = $ci_name;
                $sort['column_no'] = $col_idx;
                $sort['sort_no'] = $sort_idx;
                $sort['sort_asc'] = $col_asc;

                $this->sorting_metas[] = $sort;
                $this->sorting_columns[] = $ci_name;

                $sort_idx++;
            }
        }

        //sort sorting columns
        if (count($this->sorting_metas) > 0) {
            $arr1 = array();
            $arr2 = array();
            foreach($this->sorting_metas as $col) {
                $arr1[ $col['name'] ] = $col;
                $arr2[ $col['name'] ] = $col['sort_no'];
            }

            //sort
            asort($arr2);

            //copy back
            $this->sorting_metas = array();
            foreach($arr2 as $key => $val) {
                $col = $arr1[$key];
                $this->sorting_metas[] = $col;
            }
        }

        //if no sorting defined, sort based on key column (only if it is visible))
        if (count($this->sorting_metas) == 0) {
            $ci_name = strtoupper($this->table_metas['key_column']);

            $col_idx = array_search($ci_name, $this->columns);
            if (false !== $col_idx) {
                $col = $this->column_metas[$col_idx];
                if ($col['visible']) {
                    //add to sorting column
                    $sort = Mtablemeta::$SORTING;
                    $sort['name'] = $ci_name;
                    $sort['column_no'] = $col_idx;
                    $sort['sort_no'] = 1;
                    $sort['sort_asc'] = true;

                    $this->sorting_metas[] = $sort;
                    $this->sorting_columns[] = $ci_name;                
                }
            }
        }

        $this->table_metas['columns'] = $this->column_metas;
        //2024-03-14: editor_columns is now also stored directly in table.column.editor
        $this->table_metas['editor_columns'] = $this->editor_metas;
        $this->table_metas['table_actions'] = $this->table_actions;
        $this->table_metas['custom_actions'] = $this->custom_actions;
        $this->table_metas['row_actions'] = $this->row_actions;
        $this->table_metas['join_tables'] = $this->join_tables;

        //disable editor if no edit columns
        if (count($this->editor_metas) > 0) {
            $this->table_metas['editor'] = true;
        }
        else {
            $this->table_metas['editor'] = false;
        }

        //disable filter if no filter columns
        if (count($this->filter_metas) == 0) {
            $this->table_metas['filter'] = false;
        }

        // list of filters
        $this->table_metas['filter_columns'] = $this->filter_metas;

        //disable search if no search columns
        // var_dump($this->table_metas['search']);
        // var_dump($this->search_columns); exit;
        if (count($this->search_columns) == 0) {
            $this->table_metas['search'] = false;
        }

        //var_dump($this->table_metas['name']); var_dump($this->search_columns);

        // if no filter -> always autoload
        if (!$this->table_metas['filter'] && !$this->table_metas['search'])  $this->table_metas['initial_load'] = true;

        //column groupings
        $this->table_metas['column_groupings'] = $this->column_groupings;
        $this->table_metas['column_grouping_map'] = $this->column_grouping_map;

        //table default sorting
        $this->table_metas['sorting_columns'] = $this->sorting_metas;

        //TODO: re-calculate column-idx both in $this->column_metas and in $this->sorting_metas

        // //always include key-column in column search
        // if (false === array_search($this->table_metas['key_column'], $this->search_columns)) {
        //     $this->search_columns[] = $this->table_metas['key_column'];
        // }

        // //always include lookup-column in column search
        // if (false === array_search($this->table_metas['lookup_column'], $this->search_columns)) {
        //     $this->search_columns[] = $this->table_metas['lookup_column'];
        // }

        // //disable editor if no edit columns
        // if (count($this->table_metas['editor_columns']) == 0) {
        //     $this->table_metas['editor'] = false;
        // }

        // //disable filter if no filter columns
        // if (count($this->table_metas['filter_columns']) == 0) {
        //     $this->table_metas['filter'] = false;
        // }

        $this->initialized = true;

        //var_dump($this->table_metas['name']); var_dump($this->table_metas['initial_load']); 

        //initialized the distinct filter options
        //must be performed after initialization is completed
        foreach ($this->table_metas['filter_columns'] as $key => $row) {
            if($row['type'] != 'distinct')   continue;

            $col = empty($row['column_name']) ? $row['name'] : $row['column_name'];
            ($this->table_metas['filter_columns'])[$key]['options'] = $this->distinct_lookup($col);
        }

        return true;
    }

    function is_initialized() {
        return $this->initialized;
    }

    function tablemeta() {
        if (!$this->initialized)   return null;
        return $this->table_metas;
    }

    function tablename() {
        if (!$this->initialized)   return null;
        return $this->table_name;   
    }

    function editable_table() {
        if (!$this->initialized)   return null;
        return $this->table_metas['editable_table_name'];   
    }

    function key_column() {
        return $this->table_metas['key_column'];
    }

    function filter_columns() {
        //return $this->filter_columns;
        return $this->filter_columns;
    }

    function distinct_lookup($column, $filter = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return null;

        if ($filter == null)    $filter = array();

        //use dynamic crud
        //use view if specified
        $table_name = $this->table_metas['table_name'];
        $builder = $this->db->table($table_name);

        //clean up non existing filter columns
        $ci_name = null;
        foreach($filter as $key => $val) {
            //TODO: use columnmeta[$key] and use $column_name
            $ci_name = strtoupper($key);
            if (false !== array_search($ci_name, $this->columns)) {
                $builder->where("$table_name.$key", $val);
            }
        }

        //level1 filter if any
        if (!empty($this->level1_filter) && count($this->level1_filter) > 0) {
            foreach($this->level1_filter as $key => $val) {
                $builder->where("$table_name.$key", $val);
            }
        }

        if ($this->table_metas['soft_delete'])   $builder->where('is_deleted', 0);
        if (!empty($this->table_metas['where_clause']))   
            $builder->where($this->table_metas['where_clause']);

        $builder->orderBy($column);
        $builder->distinct();
        $builder->select($column .' as value');
        $arr = $builder->get()->getResultArray();
        if ($arr == null)       return $arr;

        foreach($arr as $key => $row) {
            $arr[$key]['label'] = $row['value'];
        }

        //var_dump($arr);

        return $arr;
    }

    function lookup($filter = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return null;

        if ($filter == null)    $filter = array();

        //use dynamic crud
        //use view if specified
        $table_name = $this->table_metas['table_name'];
        $builder = $this->db->table($table_name);

        //clean up non existing filter columns
        $ci_name = null;
        foreach($filter as $key => $val) {
            //TODO: use columnmeta[$key] and use $column_name
            $ci_name = strtoupper($key);
            if (false !== array_search($ci_name, $this->columns)) {
                $builder->where("$table_name.$key", $val);
            }
        }

        //level1 filter if any
        if (!empty($this->level1_filter) && count($this->level1_filter) > 0) {
            foreach($this->level1_filter as $key => $val) {
                $builder->where("$table_name.$key", $val);
            }
        }

        if ($this->table_metas['soft_delete'])   $builder->where('is_deleted', 0);
        if (!empty($this->table_metas['where_clause']))   
            $builder->where($this->table_metas['where_clause']);

        $builder->select($this->table_metas['lookup_column'] .' as label, '. $this->table_metas['key_column'] .' as value');
        return $builder->get()->getResultArray();
    }

    function search($query, $filter = null, $limit = null, $offset = 0, $orderby = null, $search_columns = null) {
        $this->reset_error();

        //hack
        if($offset == null) $offset = 0;

        if (!$this->initialized)   return null;

        if (empty($limit) && $this->table_metas['search_max_result'] > 0) {
            $limit = $this->table_metas['search_max_result'];
        }

        //use dynamic crud
        //use view if specified
        $table_name = $this->table_metas['table_name'];
        $builder = $this->db->table($table_name);

        //group search filter
        if (!empty($query)) {
            if (!empty($search_columns) && is_array($search_columns)) {
                //use specified list of columns
                $builder->groupStart();
                foreach($search_columns as $key => $val) {
                    $builder->orLike($val, $query);
                }
                $builder->groupEnd();
            }
            else if (count($this->search_columns)>0) {
                //use predefined list of columns
                $builder->groupStart();
                foreach($this->search_columns as $key => $val) {
                    $builder->orLike($val, $query);
                }
                $builder->groupEnd();
            }
        }

        //add filter
        $ci_name = null;
        if ($filter != null && count($filter) > 0) {
            foreach($filter as $key => $val) {
                //TODO: use columnmeta[$key] and use $column_name
                $ci_name = strtoupper($key);
                if (false !== array_search($ci_name, $this->columns)) {
                    if ($val == Mcrud_tablemeta::INVALID_VALUE) {
                        $fkey = isset($this->join_tables[$key]) ? $this->join_tables[$key] : null;
                        if ($fkey != null) {
                            $builder->where($fkey['reference_alias'].".".$fkey['reference_lookup_column'], NULL);
                        }
                        else {
                            $builder->groupStart();
                            $builder->where("$table_name.$key", NULL);
                            $builder->orWhere("trim($table_name.$key)", '');
                            $builder->groupEnd();
                        }
                    } else {
                        $builder->where("$table_name.$key", $val);
                    }
                }
            }
        }

        //level1 filter if any
        if (!empty($this->level1_filter) && count($this->level1_filter) > 0) {
            foreach($this->level1_filter as $key => $val) {
                $builder->where("$table_name.$key", $val);
            }
        }

        if ($this->table_metas['soft_delete'])   $builder->where($table_name. '.is_deleted', 0);
        if (!empty($this->table_metas['where_clause']))   
            $builder->where($this->table_metas['where_clause']);

        $select_str = implode(', ', $this->select_columns);

        $builder->select($select_str);
        // $builder = $this->db->table($table_name);

        //join table
        foreach($this->join_tables as $row) {
            //join query
            if (empty($row['reference_custom_query'])) {
                //refer to table directly
                $join_query = $row['reference_table_name'] .' as '. $row['reference_alias'];
            } 
            else {
                //refer to custom subquery
                $join_query = "(" .$row['reference_custom_query']. ") as ". $row['reference_alias'];
            }
            //conditional join
            $where_clause = $row['column_name']."=".$row['reference_alias'].".".$row['reference_fkey_column'];
            //secondary lookup key
            if (!empty($row['reference_fkey2_column'])) {
                $where_clause .= " AND $table_name." .$row['reference_key2_column']."=".$row['reference_alias'].".".$row['reference_fkey2_column'];
            }
            //soft delete and additional where clause only applicable when we are referring to table directly
            if (empty($row['reference_custom_query'])) {
                //soft delete in lookup table
                if ($row['reference_soft_delete']) {
                    $where_clause .= " AND " .$row['reference_alias']. ".is_deleted=0";
                }
                //additional where clause
                if (!empty($row['reference_where_clause'])) {
                    $str = str_replace($row['reference_table_name'] .'.', $row['reference_alias'] .'.', $row['reference_where_clause']);

                    $where_clause .= " AND " .$str;
                }
            }
            //join
            $builder->join($join_query, $where_clause, 'LEFT OUTER');
        }

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

        // $str = $builder->getCompiledSelect();
        // echo($str); exit;

        $arr = $builder->get($limit, $offset)->getResultArray();
        if ($arr == null)       return $arr;

        //special transformation
        foreach($this->table_metas['columns'] as $key => $col) {
            if ($col['type'] == "tcg_multi_select") {
                foreach($arr as $idx => $row) {
                    if (isset( $row[$col['name']] )) {
                        $arr[$idx][$col['name']] = explode(',', $row[$col['name']]);
                    }
                }
            }
        }
        
        return $arr;
    }

    function list($filter = null, $limit = null, $offset = 0, $orderby = null, $deleted=false) {
        $this->reset_error();

        //hack
        if($offset == null) $offset = 0;
        
        if (!$this->initialized)   return null;

        if ($filter == null) $filter = array();

        if (empty($limit) && $this->table_metas['search_max_result'] > 0) {
            $limit = $this->table_metas['search_max_result'];
        }
        
        //use dynamic crud
        //use view if specified
        $table_name = $this->table_metas['table_name'];
        $builder = $this->db->table($table_name);

        //clean up non existing filter columns
        $ci_name = null;
        foreach($filter as $key => $val) {
            //TODO: use columnmeta[$key] and use $column_name
            $ci_name = strtoupper($key);
            if (false !== array_search($ci_name, $this->columns)) {
                if ($val == Mcrud_tablemeta::INVALID_VALUE) {
                    $fkey = isset($this->join_tables[$key]) ? $this->join_tables[$key] : null;
                    if ($fkey != null) {
                        $builder->where($fkey['reference_alias'].".".$fkey['reference_lookup_column'], NULL);
                    }
                    else {
                        $builder->groupStart();
                        $builder->where("$table_name.$key", NULL);
                        $builder->orWhere("trim($table_name.$key)", '');
                        $builder->groupEnd();
                    }
                } else {
                    $builder->where("$table_name.$key", $val);
                }
           }
        }

        //level1 filter if any
        if (!empty($this->level1_filter) && count($this->level1_filter) > 0) {
            foreach($this->level1_filter as $key => $val) {
                $builder->where("$table_name.$key", $val);
            }
        }

        //cek if we want to show deleted items
        if ($this->table_metas['soft_delete'] && !$deleted)   $builder->where($table_name. '.is_deleted', 0);

        if (!empty($this->table_metas['where_clause']))  { 
            $builder->groupStart();
            $builder->where($this->table_metas['where_clause']);
            $builder->groupEnd();
        }

        $select_str = implode(', ', $this->select_columns);

        $builder->select($select_str);
        //$builder = $this->db->table($table_name);

        //join table
        foreach($this->join_tables as $row) {
            //join query
            if (empty($row['reference_custom_query'])) {
                //refer to table directly
                $join_query = $row['reference_table_name'] .' as '. $row['reference_alias'];
            } 
            else {
                //refer to custom subquery
                $join_query = "(" .$row['reference_custom_query']. ") as ". $row['reference_alias'];
            }
            //conditional join
            $where_clause = $row['column_name']."=".$row['reference_alias'].".".$row['reference_fkey_column'];
            //secondary lookup key
            if (!empty($row['reference_fkey2_column'])) {
                $where_clause .= " AND $table_name." .$row['reference_key2_column']."=".$row['reference_alias'].".".$row['reference_fkey2_column'];
            }
            //soft delete and additional where clause only applicable when we are referring to table directly
            if (empty($row['reference_custom_query'])) {
                //soft delete in lookup table
                if ($row['reference_soft_delete']) {
                    $where_clause .= " AND " .$row['reference_alias']. ".is_deleted=0";
                }
                //additional where clause
                if (!empty($row['reference_where_clause'])) {
                    $str = str_replace($row['reference_table_name'] .'.', $row['reference_alias'] .'.', $row['reference_where_clause']);

                    $where_clause .= " AND " .$str;
                }
            }
            //join
            $builder->join($join_query, $where_clause, 'LEFT OUTER');
        }

        //TODO: join to dbo_uploads using FIND_IN_SET() for upload type

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

        // $str = $builder->getCompiledSelect();
        // echo($str); exit;

        $arr = $builder->get($limit, $offset)->getResultArray();
        if ($arr == null)       return $arr;

        //special transformation
        foreach($this->table_metas['columns'] as $key => $col) {
            if ($col['type'] == "tcg_multi_select") {
                foreach($arr as $idx => $row) {
                    if (isset( $row[$col['name']] )) {
                        $arr[$idx][$col['name']] = explode(',', $row[$col['name']]);
                    }
                }
            }
        }
        
        return $arr;
    }

    function detail($id, $filter = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return null;

        if ($filter == null) $filter = array();

        //convert as string to make sure no overload of string
        $id = strval($id);

        //use dynamic crud
        //use view if specified
        $table_name = $this->table_metas['table_name'];
        $builder = $this->db->table($table_name);

        $builder->where($table_name. '.' .$this->table_metas['key_column'], $id);
        if ($this->table_metas['soft_delete'])   $builder->where($table_name. '.is_deleted', 0);

        //assume $id is unique/primary key => ignore other filters
        // if (!empty($this->table_metas['where_clause']))   
        //     $builder->where($this->table_metas['where_clause']);

        //level1 filter if any
        if (!empty($this->level1_filter) && count($this->level1_filter) > 0) {
            foreach($this->level1_filter as $key => $val) {
                $builder->where("$table_name.$key", $val);
            }
        }

        $select_str = implode(', ', $this->select_columns);

        $builder->select($select_str);

        //join table
        foreach($this->join_tables as $row) {
            //join query
            if (empty($row['reference_custom_query'])) {
                //refer to table directly
                $join_query = $row['reference_table_name'] .' as '. $row['reference_alias'];
            } 
            else {
                //refer to custom subquery
                $join_query = "(" .$row['reference_custom_query']. ") as ". $row['reference_alias'];
            }
            //conditional join
            $where_clause = $row['column_name']."=".$row['reference_alias'].".".$row['reference_fkey_column'];
            //secondary lookup key
            if (!empty($row['reference_fkey2_column'])) {
                $where_clause .= " AND $table_name." .$row['reference_key2_column']."=".$row['reference_alias'].".".$row['reference_fkey2_column'];
            }
            //soft delete and additional where clause only applicable when we are referring to table directly
            if (empty($row['reference_custom_query'])) {
                //soft delete in lookup table
                if ($row['reference_soft_delete']) {
                    $where_clause .= " AND " .$row['reference_alias']. ".is_deleted=0";
                }
                //additional where clause
                if (!empty($row['reference_where_clause'])) {
                    $str = str_replace($row['reference_table_name'] .'.', $row['reference_alias'] .'.', $row['reference_where_clause']);

                    $where_clause .= " AND " .$str;
                }
            }
            //join
            $builder->join($join_query, $where_clause, 'LEFT OUTER');
        }
        
        $arr = $builder->get()->getRowArray();       
        if ($arr == null)       return $arr;

        //special transformation
        foreach($this->table_metas['columns'] as $key => $col) {
            if ($col['type'] == "tcg_multi_select") {
                if (isset( $arr[$col['name']] )) {
                    $arr[$col['name']] = explode(',', $arr[$col['name']]);
                }
            }
        }
        
        return $arr;
    }

    function update($id, $valuepair, $filter = null, $enforce_edit_columns = true) {
        $this->reset_error();
        
        if (!$this->initialized)   return 0;

        if ($enforce_edit_columns && !$this->table_actions['edit'])    return 0;

        if ($filter == null) $filter = array();

        //convert as string to make sure no overload of string
        $id = strval($id);

        //use dynamic crud
        //clean up non existing columns
        if ($enforce_edit_columns) {
            foreach(array_keys($valuepair) as $key) {
                if (false === array_search($key, $this->edit_columns)) {
                    //invalid columns
                    unset($valuepair[$key]);
                }
            }
        }
        
        if (count($valuepair) == 0)     return 0;

        //special transformation
        foreach($this->table_metas['columns'] as $key => $col) {
            if ($col['type'] == "tcg_multi_select") {
                if (empty($valuepair[ $col['name'] ]))  continue;
                $val =  $valuepair[ $col['name'] ];
                if (is_array($val)) {
                    $val = implode(",", $val);
                }
                $valuepair[ $col['name'] ] = $val;
            }
            // else if ($col['type'] == "tcg_upload") {
            //     if (empty($valuepair[ $col['name'] ]))  continue;
            //     $val = $this->get_upload_list($valuepair[ $col['name'] ]);
            //     if ($val != null) {
            //         $valuepair[ $col['name'] ] = $val['upload_id'];
            //         $valuepair[ $col['name'] .'_filename' ] = $val['filename'];
            //         $valuepair[ $col['name'] .'_path' ] = $val['web_path'];
            //         $valuepair[ $col['name'] .'_thumbnail' ] = $val['thumbnail_path'];
            //     }
            // }
        }

        //use internal table if specified
        $table_name = $this->table_metas['editable_table_name'];
        $builder = $this->db->table($table_name);

        //level1 filter if any
        if (!empty($this->level1_filter) && count($this->level1_filter) > 0) {
            foreach($this->level1_filter as $key => $val) {
                $builder->where("$table_name.$key", $val);
            }
        }

        $builder->where($this->table_metas['key_column'], $id);

        //assume $id is unique/primary key => ignore other filters
        // if (!empty($this->table_metas['where_clause']))   
        //     $builder->where($this->table_metas['where_clause']);

        //inject updated 
        $valuepair['updated_on'] = gmdate('Y/m/d H:i:s');
        $valuepair['updated_by'] = $this->session->get('user_id');

        $builder->update($valuepair);
        
        $affected = $this->db->affectedRows();
        if ($affected > 0) {
            //update upload list
            foreach($this->table_metas['columns'] as $key => $col) {
                if ($col['type'] == "tcg_upload") {
                    if (empty($valuepair[ $col['name'] ]))  continue;
                    $val = $this->update_upload_list($valuepair[ $col['name'] ], $table_name, $id, $col['name']);
                }
            }
    
            //audit trail
            $this->audittrail->update($table_name, $id, $valuepair);
        }

        return $id;
    }

    function delete($id, $filter = null) {
        $this->reset_error();
        
        if (!$this->initialized)   return 0;

        if (!$this->table_actions['delete'])    return 0;

        if ($filter == null) $filter = array();

        //convert as string to make sure no overload of string
        $id = strval($id);

        //use dynamic crud
        $filter[ $this->table_metas['key_column'] ] = $id;

        //assume $id is unique/primary key => ignore other filters
        // if (!empty($this->table_metas['where_clause']))   
        //     $builder->where($this->table_metas['where_clause']);

        //use view if specified
        $table_name = $this->table_metas['editable_table_name'];
        $builder = $this->db->table($table_name);

        //level1 filter if any
        if (!empty($this->level1_filter) && count($this->level1_filter) > 0) {
            foreach($this->level1_filter as $key => $val) {
                $builder->where("$table_name.$key", $val);
            }
        }

        if ($this->table_metas['soft_delete']) {
            $valuepair = array (
                'is_deleted' => 1,
                'updated_on' => gmdate('Y/m/d H:i:s'),
                'updated_by' => $this->session->get('user_id')
            );

            $builder->where($filter);
            $builder->update($valuepair);   
        }
        else {
            $builder->where($filter);
            $builder->delete();
        }

        $affected = $this->db->affectedRows();
        if ($affected > 0) {
            //audit trail
            $this->audittrail->delete($table_name, $id);
        }

        return $affected;
    }

    function add($valuepair, $enforce_edit_columns = true) {
        $this->reset_error();
        
        if (!$this->initialized)   return 0;

        if ($enforce_edit_columns && !$this->table_actions['add'])    return 0;

        //use dynamic crud
        //clean up non existing columns
        if ($enforce_edit_columns) {
            foreach(array_keys($valuepair) as $key) {
                if (false === array_search($key, $this->edit_columns)) {
                    //invalid columns
                    unset($valuepair[$key]);
                }
            }
        }

        //enforce level1 filter if any
        if (!empty($this->level1_filter) && count($this->level1_filter) > 0) {
            foreach($this->level1_filter as $key => $val) {
                $valuepair[$key] = $val;
            }
        }

        //special transformation
        foreach($this->table_metas['columns'] as $key => $col) {
            if ($col['type'] == "tcg_multi_select") {
                if (empty($valuepair[ $col['name'] ]))  continue;
                $val =  $valuepair[ $col['name'] ];
                if (is_array($val)) {
                    $val = implode(",", $val);
                }
                $valuepair[ $col['name'] ] = $val;
            }
            // else if ($col['type'] == "tcg_upload") {
            //     if (empty($valuepair[ $col['name'] ]))  continue;
            //     $val = $this->get_upload_list($valuepair[ $col['name'] ]);
            //     if ($val != null) {
            //         $valuepair[ $col['name'] ] = $val['upload_id'];
            //         $valuepair[ $col['name'] .'_filename' ] = $val['filename'];
            //         $valuepair[ $col['name'] .'_path' ] = $val['web_path'];
            //         $valuepair[ $col['name'] .'_thumbnail' ] = $val['thumbnail_path'];
            //     }
            // }
        }

        //inject
        $valuepair['created_by'] = $this->session->get('user_id');

        //use view if specified
        $table_name = $this->table_metas['editable_table_name'];
        $builder = $this->db->table($table_name);

        $id = 0;
        
        $query = $builder->insert($valuepair);
        if ($query) {
            $id = $this->db->insertID();

            //update upload list
            foreach($this->table_metas['columns'] as $key => $col) {
                if ($col['type'] == "tcg_upload") {
                    if (empty($valuepair[ $col['name'] ]))  continue;
                    $val = $this->update_upload_list($valuepair[ $col['name'] ], $table_name, $id, $col['name']);
                }
            }

            //audit trail
            $this->audittrail->insert($table_name, $id, $valuepair);
        } 

        return $id;
    }

    function import($file, $filters = null) {
        $this->reset_error();

        if (!$this->initialized)   return 0;

        if (!$this->table_actions['import'])    return 0;

        if ($filters == null) $filters = array();
        
        $table_id = $this->table_id;
        $table_name = $this->table_name;
        $columns = $this->table_metas['columns'];
        $key_col_name = $this->table_metas['key_column'];
        $join_tables = $this->table_metas['join_tables'];

        foreach($filters as $col=>$val) {
            $found = 0;
            for($i=0; $i<count($columns); $i++) {
                if ($columns[$i]['name'] == $col) {
                    $columns[$i]['visible'] = 1;
                    $columns[$i]['allow_insert'] = 1;
                    $columns[$i]['allow_edit'] = 1;
                    $found = 1;
                }
            }
            //add invalid filter
            if ($found == 0) {
                $column = array();
                $column['name'] = $col;
                $column['visible'] = 1;
                $column['allow_insert'] = 1;
                $column['allow_edit'] = 1;
                $column['type'] == 'tcg_text';
                $columns[] = $column;
            }
        }

        //custom columns
        $columns = $this->__get_custom_column($columns);

        //upload file
        $data = $this->__upload_xls($file, $table_id, $table_name);
        if ($data == null) {
            return 0;
        }
        $import_id = $data['import_id'];
        $filepath = $data['filepath'];

        //create temporary table
        $data = $this->__create_temp_table($table_name, $columns);
        if ($data == null) {
            $sql = "update dbo_imports set status='" .$this->error_message. "' where id=?";
            $this->db->query($sql, array($import_id));
            return 0;
        }
        $temp_table_name = $data['temp_table_name'];
        $export_columns = $data['export'];
        $import_columns = $data['import'];
        $column_types = $data['type'];
        $upload_columns = $data['upload'];

        //make sure reference col is in export col
        //in case when col is set display=0 but edit=1, then it will not be in list of export col but will be in reference col (for editing purpose)
        $arr = array();
        foreach($join_tables as $idx => $tbl) {
            $col_name = $tbl['name'];
            if (in_array($col_name, $import_columns)) {
                $arr[] = $tbl;
            }
        }
        $join_tables = $arr;

        // var_dump($import_columns);
        // var_dump($join_tables); exit();

        //import xls
        $status = $this->__import_xls($filepath, $import_id, $temp_table_name, $export_columns, $column_types);
        if($status == 0) {
            $sql = "update dbo_imports set status='" .$this->error_message. "', processing_finish_on=now() where id=?";
            $this->db->query($sql, array($import_id));
            return 0;
        }

        //enforce filter value
        if ($filters != null && count($filters)>0) {
            $builder = $this->db->table($temp_table_name);
            $builder->update($filters);
        }
        
        //check if it is an update
        $sql = "update " .$temp_table_name. " a join " .$table_name. " b on b." .$key_col_name. "=a." .$key_col_name;
        foreach($filters as $col=>$val) {
            $sql .= " and b." .$col. "=a." .$col;
        }
        $sql .= " set a._update_=1";
        //enforce level1 filter if any
        if (!empty($this->level1_filter) && count($this->level1_filter) > 0) {
            $level1_filter = '';
            foreach($this->level1_filter as $key => $val) {
                if ($level1_filter == '') {
                    $level1_filter = "b.$key=$val";
                }
                else {
                    $level1_filter .= " AND b.$key=$val";
                }
            }
            $sql .= ' where ' .$level1_filter; 
        }
        $this->db->query($sql);
        
        //custom processing
        $this->__update_custom_column($temp_table_name);

        // 2024-04-01: There is no point of importing via xls the upload file
        // //intermediate process
        // if (count($upload_columns)) {
        //     $this->__update_upload_columns($temp_table_name, $upload_columns);
        // }

        //process import
        $this->__process_import($table_name, $key_col_name, $temp_table_name, $import_columns, $join_tables);

        //drop temporary table
        $this->db->query("DROP TEMPORARY TABLE $temp_table_name;");

        //update status
        $sql = "update dbo_imports set status='completed', processing_finish_on=now() where id=?";
        $this->db->query($sql, array($import_id));

        //audit trail
        $this->audittrail->trail($table_name, $import_id, "IMPORT", "Import from file " .$filepath);

        return 1;
    }

    protected function __get_custom_column($columns) {
        //nothing to do
        return $columns;
    }

    protected function __upload_xls($file, $table_id, $table_name) {
        $this->reset_error();

        $uploader = new Uploader();

        $uploader->file_types = array("xls", "xlsx");
        $upload = $uploader->upload($file, "dbo_imports");
        if ($upload == null || !empty($upload["error"])) {
            //error uploading
            $this->set_error(-1, "Gagal mengunggah fail.");
            return null;
        }

        //get upload detail
        $upload_id = $upload['id'];
        $sql = "select * from dbo_uploads where id=? and is_deleted=0";
        $upload = $this->db->query($sql, array($upload_id))->getRowArray();
        if ($upload == null) {
            $this->set_error(-1, "Gagal mengunggah fail.");
            return null;
        }

        $userid = $this->session->get("user_id");

        //copy to dbo_imports
        $import_id = 0;
        $sql = "insert into dbo_imports(table_id, filename, file_path, web_path, thumbnail_path, status, processing_start_on, created_by) " .
                "select ?, filename, path, web_path, thumbnail_path, ?, now(), ? from dbo_uploads where is_deleted=0 and id=?";
        $query = $this->db->query($sql, array($table_id, "started", $userid, $upload_id));
        if ($query) {
            $import_id = $this->db->insertID();
        } 

        if ($import_id == 0) {
            //error copying
            $this->set_error(-1, "Gagal mengunggah fail.");
            return null;
        }

        //update upload ref_id
        $sql = "update dbo_uploads set ref_id=" .$import_id. " where id=" .$upload_id;
        $query = $this->db->query($sql);

        $retval = array(
            'import_id'     => $import_id,
            'filepath'      => $upload['path']
        );
        return $retval;
    }

    protected function __create_temp_table($table_name, $columns) {
        $this->reset_error();

        //Note: Ideally, all editable columns (regardless visible or not) should be exported and can be imported.
        //      But since, we are using internal datatable export function, we can only export visible columns.

        $temp_table_name = "temp_" .$table_name;

        $column_def = array();
        $column_names = array();

        $import_columns = array();
        $upload_columns = array();
        $export_columns = array();
        $column_type = array();
        
        //id column
        $column_def[] = "__id__ int(11) NOT NULL AUTO_INCREMENT";

        //import id
        $column_def[] = "__import_id__ int(11) NOT NULL";

        foreach($columns as $key => $col) {
            if ($col['visible'] == 0 && $col['export'] == 0)    continue;

            //prevent double columm
            if (in_array($col['name'],$column_names))   continue;
            $column_names[] = $col['name'];

            //column definition
            if($col['type'] == 'tcg_text') {
                $column_def[] = $col['name'] ." varchar(250)";
            }
            else if($col['type'] == 'tcg_textarea') {
                $column_def[] = $col['name'] ." longtext";
            }
            else if($col['type'] == 'tcg_number') {
                $column_def[] = $col['name'] ." int";
            }
            else if($col['type'] == 'tcg_date') {
                $column_def[] = $col['name'] ." date";
            }
            else if($col['type'] == 'tcg_datetime') {
                $column_def[] = $col['name'] ." datetime";
            }
            else if($col['type'] == 'tcg_select') {
                $column_def[] = $col['name'] ." varchar(100)";
            }
            else if($col['type'] == 'tcg_select2') {
                $column_def[] = $col['name'] ." varchar(100)";
            }
            else if($col['type'] == 'tcg_currency') {
                $column_def[] = $col['name'] ." varchar(50)";
            }
            else if($col['type'] == 'tcg_toggle') {
                $column_def[] = $col['name'] ." smallint";
            }
            else if($col['type'] == 'tcg_upload') {
                $column_def[] = $col['name'] ." varchar(50)";
                // $column_def[] = $col['name'] ."_filename longtext";
                // $column_def[] = $col['name'] ."_path longtext";
                // $column_def[] = $col['name'] ."_thumbnail longtext";
                // //prevent double columns
                // $column_names[] = $col['name'] ."_filename";
                // $column_names[] = $col['name'] ."_path";
                // $column_names[] = $col['name'] ."_thumbnail";
            }
            else {
                $column_def[] = $col['name'] ." varchar(250)";
            }
            //exported columns
            if ($col['export']) {
                $export_columns[] = $col['name'];
                $column_type[] = $col['type'];
            }
            //imported columns
            if ($col['allow_insert'] && $col['allow_edit']) {
                $import_columns[] = $col['name'];
            }
        }

        if (count($import_columns) == 0) {
            $this->set_error(-1, "Tidak ada kolom untuk diimpor");
            return null;
        }

        //always drop first
        $this->db->query("DROP TEMPORARY TABLE IF EXISTS $temp_table_name;");

        //add status columns
        $column_def[] = "_update_ varchar(100) default 0";
        $column_def[] = "_tag2_ varchar(100) default null";
        $column_def[] = "_tag3_ varchar(100) default null";

        //enforce level1 filter if any
        if (!empty($this->level1_filter) && count($this->level1_filter) > 0) {
            foreach($this->level1_filter as $key => $val) {
                $column_def[] = "$key varchar(100) default $val";
            }
        }

        //var_dump($column_def); exit();

        //create the table
        $sql = "create temporary table " .$temp_table_name. "(" .implode(',', $column_def). ", PRIMARY KEY (`__id__`))";
        $query = $this->db->query($sql);
        if (!$query) {
            $this->set_error(-1, "Gagal membuat temporary table");
            return null;
        }

        $retval = array(
            'temp_table_name'   => $temp_table_name,
            'export'    => $export_columns,
            'type'      => $column_type,
            'import'    => $import_columns,
            'upload'    => $upload_columns
        );
        return $retval;
    }

    protected function __import_xls($file, $import_id, $temp_table_name, $export_columns, $column_types) {

        $this->reset_error();
		$reader = null;

        $path_parts = pathinfo($file);
        if (!isset($path_parts['extension'])) {
            $this->set_error(-1, "Tipe file tidak diketahui.");
            return 0;
        }

        if (strtolower($path_parts['extension']) == strtolower(static::$XLSX_FILE_TYPE)) {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(static::$XLSX_FILE_TYPE);
            $reader->setLoadSheetsOnly(["Sheet1"]);
            $reader->setReadDataOnly(true);
        }
        else if (strtolower($path_parts['extension']) == strtolower(static::$XLS_FILE_TYPE)) {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(static::$XLS_FILE_TYPE);
            $reader->setLoadSheetsOnly(["Sheet1"]);
            $reader->setReadDataOnly(true);
        }
        else {
            $this->set_error(-1, "Tipe file ." .$path_parts['extension']. " tidak bisa diimpor.");
            return 0;
        }

        $spreadsheet = $reader->load($file);
        if ($spreadsheet == null) {
            $this->set_error(-1, 'File tidak ditemukan.');
            return 0;
        }

        $sheet = $spreadsheet->getSheetByName('Sheet1');
        if ($sheet == null) {
            $this->set_error(-1, 'Lembar Sheet1 tidak ditemukan.');
            return 0;
        }

        $import_values = array();

        $rows = $sheet->toArray();
        
        //sanity check: number of columns is as expected
        $col_labels = array_filter($rows[1]);     //row 2 is column label

        if (count($col_labels) != count($export_columns)) {
            $this->set_error(-1, 'Jumlah kolom tidak sesuai.');
            return 0;
        }

        $currency_prefix = "Rp";
        $thousand_separator = ",";
        $decimal_separator = ".";
        $decimal_precision = 0;
        
        $setting = new Setting();
        $arr = $setting->list_group('currency');
        foreach($arr as $key => $val) {
            if ($val['name'] == "currency_prefix")  $currency_prefix = $val['value'];
            else if ($val['name'] == "currency_thousand_separator")     $thousand_separator = $val['value'];
            else if ($val['name'] == "currency_decimal_separator")      $decimal_separator = $val['value'];
            else if ($val['name'] == "currency_decimal_precision")      $decimal_precision = $val['value'];
        }

        //insert row by row
        $dateFormat = 'Y-m-d';        
        $dateTimeFormat = 'Y-m-d h:i:s';        
        
        foreach ($rows as $rowid => $row) {
            //only read from 3 onward
            if ($rowid < 2) continue;

            //skip empty rows
            if ( empty( trim($row[0]) ) && empty( trim($row[1]) ) && empty( trim($row[2]) ) && empty( trim($row[3]) ) && empty( trim($row[4])))   continue;

            $value = array();

            //import id
            $value['__import_id__'] = $import_id;

            //actual value
            foreach($export_columns as $idx => $col) {
                //for date, convert to string
                if ($column_types[$idx] == 'tcg_date') {
                    $val = trim($row[$idx]);
                    $ts = strtotime($val);
                    if (empty($ts)) {
                        $ts = intval($val);
                        if ($ts > 0) {
                            $ts = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($ts);
                            $val = gmdate( $dateFormat, $ts);
                        }
                        else {
                            $val = null;
                        }
                    }
                    $value[ $col ] = $val;
                }
                else if ($column_types[$idx] == 'tcg_datetime') {
                    $val = trim($row[$idx]);
                    $ts = strtotime($val);
                    if (empty($ts)) {
                        $ts = intval($val);
                        if ($ts > 0) {
                            $ts = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($ts);
                            $val = gmdate( $dateTimeFormat, $ts);
                        }
                        else {
                            $val = null;
                        }
                    }
                    $value[ $col ] = $val;
                }
                else if ($column_types[$idx] == 'tcg_currency') {
                    $val = trim($row[$idx]);
                    if (!empty($val)) {
                        $val = str_replace($currency_prefix,'',$val);
                        $val = str_replace($thousand_separator,'',$val);
                        $val = str_replace($decimal_separator,'.',$val);
                    }
                    $value[ $col ] = $val;
                }
                else if ($column_types[$idx] == 'tcg_upload') {
                    $val = trim($row[$idx]);
                    if (!empty($val)) {
                        $val = str_replace(', ',',', $val);
                    }
                    $value[ $col ] = $val;
                }
                // else if ($column_types[$idx] == 'tcg_upload') {
                //     $val = trim($row[$idx]);
                //     $filename = null;
                //     $path = null;
                //     $thumbnail = null;
                //     if (!empty($val)) {
                //         $arr = $this->get_upload_list($val);
                //         if($arr != null) {
                //             $val = $arr['upload_id'];
                //             $filename = $arr['filename'];
                //             $path = $arr['web_path'];
                //             $thumbnail = $arr['thumbnail_path'];         
                //         }
                //     }
                //     $value[ $col ] = $val;
                //     $value[ $col .'_filename' ] = $filename;
                //     $value[ $col .'_path' ] = $path;
                //     $value[ $col .'_thumbnail' ] = $thumbnail;        
                // }
                else {
                    $value[ $col ] = trim($row[$idx]);
                }
            }

            $import_values[] = $value;
        }

        if(count($import_values) == 0) {
            //error message
            $this->set_error(-1, 'No-data');
            return 0;
        }

        //batch insert
        $builder = $this->db->table($temp_table_name);
        $result = $builder->insertBatch($import_values);
        if(!$result) {
            //error message
            $this->set_error(-1, $this->db->error()['message']);
            return 0;
        }

        return 1;
    }

    // /**
    //  * Update upload column
    //  * 
    //  * One upload column definition must be implemented as 4 separate column in the database table:
    //  * - <column_name>              : the main upload column. contains list of upload_id in table tcg_uploads.
    //  *                                The informastion related individual uploaded file is stored in table tcg_uploads
    //  * - <column_name>_filename     : contains list of file names of uploaded file
    //  * - <column_name>_web_path     : contains list of url to access the uploaded file
    //  * - <column_name>_thumbnail_path   : contains list of url to access thumbnail of uploaded file
    //  * 
    //  * The informastion related individual uploaded file is stored in table tcg_uploads  
    //  */
    // protected function __update_upload_columns($temp_table_name, $upload_columns) {
    //     //create secondary temp table for subquery because we cannot open temporary table > 1 in the same query
    //     //this issue should be fixed in MariaDb 10.2

    //     $upload_table_name = $temp_table_name .'_v';

    //     //always drop first
    //     $this->db->query("DROP TEMPORARY TABLE IF EXISTS $upload_table_name;");

    //     $sql = "create temporary table " .$upload_table_name. " (__id__ int, col_name varchar(1000), upload_id varchar(1000), filename longtext, web_path longtext, thumbnail_path longtext)";
    //     $this->db->query($sql);

    //     foreach($upload_columns as $col_name) {
    //         $this->db->query("truncate table " .$upload_table_name);

    //         $sql = "
    //         insert into ".$upload_table_name." (__id__, col_name, upload_id, filename, web_path, thumbnail_path)
    //         select
    //             c.id,
    //             c." .$col_name. ",
    //             group_concat(x.id separator ',') as upload_id,
    //             group_concat(x.filename separator ';') as filename,
    //             group_concat(x.web_path separator ';') as web_path,
    //             group_concat(x.thumbnail_path separator ';') as thumbnail_path
    //         from " .$temp_table_name. " c
    //         left join dbo_uploads x on x.is_deleted=0 and find_in_set(x.id, c." .$col_name. ") > 0
    //         where 
    //             c." .$col_name. " is not null and c." .$col_name. " != '' and c." .$col_name. " != 0
    //         group by c.id
    //         ";

    //         $this->db->query($sql);

    //         $sql = "
    //         update " .$temp_table_name. " a 
    //         join " .$upload_table_name. " b on b.__id__=a.id
    //         set
    //             a." .$col_name. " = b.upload_id,
    //             a." .$col_name. "_filename = b.filename,
    //             a." .$col_name. "_path = b.web_path,
    //             a." .$col_name. "_thumbnail = b.thumbnail_path
    //         where 
    //             a." .$col_name. " is not null and a." .$col_name. " != '' and a." .$col_name. " != 0
    //         ";

    //         $this->db->query($sql);
    //     }

    //     $this->db->query("DROP TEMPORARY TABLE $upload_table_name;");
    // }

    protected function __update_custom_column($temp_table_name) {
        //nothing to do
    }

    protected function __process_import($table_name, $key_column_name, $temp_table_name, $import_columns, $join_tables) {        

        //match foreign key
        foreach($join_tables as $idx => $tbl) {
            //use left join here so that those that cannot find the reference will be set to null
            $sql = "update " .$temp_table_name. " a left join " .$tbl['reference_table_name']. " b on lower(b." .$tbl['reference_lookup_column']. ")=lower(a." .$tbl['name']. ") and b.is_deleted=0 set a." .$tbl['name']. "=b." .$tbl['reference_fkey_column'];
            $this->db->query($sql);
        }

        $userid = $this->session->get("user_id");

        //insert new entry
        if ($this->table_actions['add']) {
            $column_list = implode(',', $import_columns);
            //enforce level1 filter if any
            if (!empty($this->level1_filter) && count($this->level1_filter) > 0) {
                foreach($this->level1_filter as $key => $val) {
                    $column_list .= ',$key';
                }
            }
            $sql = "insert into " .$table_name. "(" .$column_list. ", created_by) select " .$column_list. ", ? from " .$temp_table_name. " where _update_ != 1";
            $this->db->query($sql, array($userid));
        }

        //update entry
        if ($this->table_actions['edit']) {
            //dont update key column name!
            if (($key = array_search($key_column_name, $import_columns)) !== false) {
                unset($import_columns[$key]);
            }
            //update list
            $update_list = implode(','
                                , array_map(
                                    function($val) { 
                                        return 'a.'.$val.'=b.'.$val; 
                                    }
                                    , $import_columns
                                )
                            );

            $timestamp = gmdate('Y/m/d H:i:s');
            //update entry
            $sql = "update " .$table_name. " a join " .$temp_table_name. " b on b." .$key_column_name. "=a." .$key_column_name. " set " .$update_list. ", a.is_deleted=0, a.updated_by=" .$userid. ", a.updated_on='" .$timestamp. "' where b._update_=1";

            $this->db->query($sql);
        }

    }

    protected function get_model($path) {
        $model = new $path();
        return $model;

	}

    protected function get_dynamic_model($path) {
        $model = new Mcrud_tablemeta();
        $template = str_ireplace('Mcrud_tablemeta/', '', $path);

        if (!$model->init($template, false)) {
            return null;
        }

        return $model;

	}

    protected function get_lookup_options_from_query($query) {
        $arr = $this->db->query($query)->getResultArray();
        return $arr;
    }

    protected function get_lookup_options($table_name, $key_column, $lookup_column, $key2_column = null, $soft_delete = true, $where_clause = null, $alias_name = '') {
        if (!empty($alias_name)) {
            //legacy. some old configuration is wrong
            $where_clause = str_replace($table_name .".", $alias_name .".", $where_clause);
            $where_clause = str_replace("`$table_name`" .".", $alias_name .".", $where_clause);
            //use alias
            $table_name = $table_name ." as ". $alias_name;
        }

        $builder = $this->db->table($table_name);
        if ($soft_delete)           $builder->where('is_deleted', 0);
        if (!empty($where_clause))  $builder->where($where_clause);

        $builder->select($lookup_column .' as label, '. $key_column .' as value');
        if (!empty($key2_column)) {
            $builder->select($key2_column);
        }

        /**
         * IMPORTANT: Limit the lookup options loaded to 1000 rows. If the lookups is more that 1000, consider using AJAX dynamic loading
         */
        $limit = 1000;  

        $arr = $builder->get($limit)->getResultArray();
        if ($arr == null)   return $arr;

        if (count($arr) == 1000) {
            $arr[] = array ("value"=>'-1', 'label'=>'Terlalu banyak pilihan...');
        }

        return $arr;
	}

    protected function get_upload_list($values) {
        $sql = "
        select 
            group_concat(x.id separator ',') as upload_id,
            group_concat(x.filename separator ';') as filename,
            group_concat(x.web_path separator ';') as web_path,
            group_concat(x.thumbnail_path separator ';') as thumbnail_path
        from dbo_uploads x 
        where x.is_deleted=0 and find_in_set(x.id, ?) > 0
        ";

        return $this->db->query($sql, array($values))->getRowArray();
    }

    protected function update_upload_list($values, $table_name, $ref_id, $ref_field) {
        $sql = "
        update dbo_uploads x 
        set
            x.ref_table=?,
            x.ref_id=?,
            x.ref_field=?
        where x.is_deleted=0 and find_in_set(x.id, ?) > 0
        ";

        $this->db->query($sql, array($table_name, $ref_id, $ref_field, $values));
    }

    public function set_level1_filter($column_name, $value = null) {
        $this->reset_error();
        
        // var_dump($column_name);
        // var_dump($value);

        if ($value == null) {
            unset($this->level1_filter[$column_name]);
        }
        else {
            $this->level1_filter[$column_name] = $value;
            foreach($this->table_metas['columns'] as $key=>$val) {
                if (empty($val['options_data_model']))   continue;

                $model = $val['options_data_model'];
                try {
                    $model->set_level1_filter($column_name, $value);
                }
                catch (Exception $e) {
                    //ignore
                    continue;
                }

                //get filtered lookup
                $lookup = $model->lookup();

                //var_dump($lookup);

                //update lookup
                $this->table_metas['columns'][$key]['options'] = $lookup;

                //get column name
                $name = $val['name'];

                //find matching editor column if any
                foreach($this->table_metas['editor_columns'] as $key=>$editor) {
                    if ($editor['name'] !== $name)      continue;

                    //var_dump($editor);

                    //update editor lookup
                    if ($editor['edit_type'] == 'tcg_select2') {
                        $this->table_metas['editor_columns'][$key]['edit_options'] = $lookup;
                    }
                }

                // //find matching filter column if any
                // foreach($this->table_metas['filter_columns'] as $key=>$filter) {
                //     if ($filter['name'] !== $name)      continue;

                //     //var_dump($filter);

                //     //update filter lookup
                //     if ($filter['filter_type'] == 'tcg_select2') {
                //         $this->table_metas['filter_columns'][$key]['filter_options'] = $lookup;
                //     }          
                // }

                //get column id
                $id = $val['id'];

                //find matching filter column if any
                foreach($this->table_metas['filters'] as $key=>$filter) {
                    if ($filter['column_id'] !== $id)      continue;

                    //update filter lookup
                    if ($filter['filter_type'] == 'tcg_select2') {
                        $this->table_metas['filters'][$key]['filter_options'] = $lookup;
                    }          
                }

            }   //foreach editor column

            $matches = null;
            foreach($this->table_metas['columns'] as $key=>$val) {
                if (empty($val['options_data_url']))   continue;

                $url = $val['options_data_url'];
                $params = null;

                preg_match_all('{{{[\w]*}}}', $url, $matches);
                if ($matches != null && count($matches) > 0) {
                    $params = array();
                    foreach($matches[0] as $m) {
                        $colname = substr($m, 2, strlen($m)-4);
                        if ($colname == $column_name) {
                            $url = str_replace($m, $value, $url);
                        }
                        else {
                            $params[] = $colname;
                        }
                    }
                }

                $this->table_metas['columns'][$key]['options_data_url'] = $url;
                $this->table_metas['columns'][$key]['options_data_url_params'] = $params;

                //get column name
                $name = $val['name'];

                //find matching editor column if any
                foreach($this->table_metas['editor_columns'] as $key=>$editor) {
                    if ($editor['name'] !== $name)      continue;

                    //var_dump($editor);

                    //update editor lookup
                    if ($editor['edit_type'] == 'tcg_select2') {
                        $this->table_metas['editor_columns'][$key]['options_data_url'] = $url;
                        $this->table_metas['editor_columns'][$key]['options_data_url_params'] = $params;
                    }
                }
            }
        }   //$value !== null
    }

    public function get_error_code() {
        return $this->error_code;
    }

    public function get_error_message() {
        return $this->error_message;
    }

    protected function reset_error() {
        $this->error_code = 0;
        $this->error_message = null;
    }

    protected function set_error($code, $message) {
        $this->error_code = $code;
        $this->error_message = $message;
    }

    protected function get_controller_meta($controller_id) {
        $builder = $this->db->table('dbo_crud_pages a');
        $builder->select('a.id as controller_id, a.name as controller_name, b.*');
        $builder->join('dbo_crud_tables b', "b.id=a.crud_table_id and b.is_deleted=0", 'INNER');
        $builder->where(array('a.id'=>$controller_id, 'a.is_deleted'=>0));
        $arr = $builder->get()->getRowArray();
        if ($arr == null) {
            return false;
        }
       
        return $arr;        
    }

    protected function get_subtable_name($subtable_id) {
        $builder = $this->db->table('dbo_crud_tables');
        $builder->select('*');
        $builder->where(array('id'=>$subtable_id, 'is_deleted'=>0));
        $arr = $builder->get()->getRowArray();
        if ($arr == null) {
            return false;
        }
       
        return $arr['name'];
    }

    protected function get_subtable_columns($subtable_id) {
        $builder = $this->db->table('dbo_crud_columns');
        $builder->select('*');
        $builder->orderBy('order_no asc');
        $builder->where(array('table_id'=>$subtable_id, 'is_deleted'=>0));
        $arr = $builder->get()->getResultArray();
        if ($arr == null) {
            return null;
        }

        $columns = array();
        foreach($arr as $row) {
            if ($row['visible'] != 1) continue;
                             
            $col = Mtablemeta::$COLUMN;
            $col['name'] = $row['name'];
            $col['label'] = __($row['label']);
            $col['visible'] = ($row['visible'] == 1);
            $col['css'] = $row['css'];
            $col['type'] = $row['column_type'];
            $col['data_priority'] = $row['data_priority'];
            $col['column_name'] = $row['column_name'];
            if (empty($col['column_name'])) {
                $col['column_name'] = $this->table_name. "." .$col['name'];
            }

            //if already exist, ignore. prevent duplicate
            if (true === array_search($col['column_name'], $this->select_columns)) {
               continue;
            }

            $col['display_format_js'] = $row['display_format_js'];

            $col['foreign_key'] = ($row['foreign_key'] == 1);
            $col['allow_insert'] = ($row['allow_insert'] == 1);
            $col['allow_edit'] = ($row['allow_edit'] == 1);
            $col['allow_filter'] = ($row['allow_filter'] == 1);
            
            //default: no bubble edit
            $col['edit_bubble'] = false;
            
            $col['edit_field'] = $row['edit_field'];
            if (empty($col['edit_field'])) {
                $col['edit_field'] = $col['name'];
            }

            //split as array
            $col['edit_field'] = array_map('trim', explode(',', $col['edit_field']));

            $ref = array();
            if ($col['foreign_key']) {
                $ref = Mtablemeta::$TABLE_JOIN;
                $ref['name'] = $col['name'];
                $ref['column_name'] = $col['column_name'];
                $ref['reference_table_name'] = $row['reference_table_name'];
                $ref['reference_fkey_column'] = $row['reference_fkey_column'];
                $ref['reference_lookup_column'] = $row['reference_lookup_column'];
                if (empty($ref['reference_lookup_column'])) {
                    $ref['reference_lookup_column'] = $ref['reference_fkey_column'];
                }
                $ref['reference_key2_column'] = $row['reference_key2_column'];
                $ref['reference_fkey2_column'] = $row['reference_fkey2_column'];
                $ref['reference_soft_delete'] = ($row['reference_soft_delete'] == 1);

                $ref['reference_custom_query'] = $this->replace_parameters($row['reference_custom_query']);

                $ref['reference_where_clause'] = $row['reference_where_clause'];
                if (!empty($ref['reference_where_clause'])) {
                    $ref['reference_where_clause'] = $this->replace_parameters($ref['reference_where_clause']);
                }

                //get lookup if not specified manually
                if (($col['allow_insert'] || $col['allow_edit']) &&
                        empty($col['options']) && empty($col['options_data_url']) 
                        && ($row['edit_type'] == 'tcg_select2')) {
                    if (!empty($ref['reference_custom_query'])) {
                        $col['options'] = $this->get_lookup_options_from_query($ref['reference_custom_query']);
                    }
                    else {
                        $col['options'] = $this->get_lookup_options($ref['reference_table_name'], $ref['reference_fkey_column'], $ref['reference_lookup_column']
                                                        , $ref['reference_key2_column']
                                                        , $ref['reference_soft_delete'], $ref['reference_where_clause']);
                    }
                }
            }

            $editor = array();
            if ($col['allow_insert']) {
                $editor = Mtablemeta::$EDITOR;
                $editor['name'] = $row['name'];
                $editor['allow_insert'] = $col['allow_insert'];
                $editor['allow_edit'] = $col['allow_edit'];
                $editor['edit_field'] = $col['edit_field'];

                $editor['edit_label'] = __($row['edit_label']);
                if (empty($editor['edit_label'])) {
                    $editor['edit_label'] = ucwords($col['label']);
                }
                               
                $editor['edit_css'] = $row['edit_css'];
                $editor['edit_compulsory'] = ($row['edit_compulsory'] == 1);
                $editor['edit_info'] = $row['edit_info'];
                $editor['edit_onchange_js'] = $row['edit_onchange_js'];
                $editor['edit_validation_js'] = $row['edit_validation_js'];
                $editor['edit_bubble'] = ($row['edit_bubble'] == 1);
                $editor['edit_readonly'] = !$col['allow_edit'];

                //store in column metas
                $col['edit_bubble'] = $editor['edit_bubble'];

                $editor['edit_def_value'] = $row['edit_def_value'];

                if (!empty($editor['edit_def_value'])) {
                    $editor['edit_def_value'] = $this->replace_parameters(trim($editor['edit_def_value']));
                }

                if (!empty($row['edit_options_array'])) {
                    $editor['edit_options'] = json_decode($row['edit_options_array']);
                } else if (!empty($col['options'])) {
                    $editor['edit_options'] = $col['options'];
                }

                if (!empty($row['edit_attr_array'])) {
                    //echo ($row['edit_attr_array']);
                    //$row['edit_attr_array'] = '{"a": true,"b":2,"c":3,"d":4,"e":5}';
                    //echo ($row['edit_attr_array']);
                    $editor['edit_attr'] = json_decode($row['edit_attr_array']);
                    //TODO: append base url for ajax parameter
                }

                //force select2
                $editor['edit_type'] = $row['edit_type'];
                if (!empty($editor['edit_options']) && empty($editor['edit_type'])) {
                    $editor['edit_type'] = 'tcg_select2';
                }
                if (empty($editor['edit_type'])) {
                    $editor['edit_type'] = $col['type'];
                }

                //option_url
                if ($editor['edit_type'] == 'tcg_select2' && !empty($col['options_data_url'])) {
                    $editor['options_data_url'] = $col['options_data_url'];
                    $editor['options_data_url_params'] = $col['options_data_url_params'];
                }

            }
            else {
                $editor = Mtablemeta::$EDITOR;
                $editor['name'] = $col['name'];
                $editor['allow_insert'] = false;
                $editor['allow_edit'] = false;
                $editor['edit_field'] = $col['edit_field'];

                $editor['edit_label'] = $col['label'];
                $editor['edit_type'] = "tcg_readonly";
            }

            $col = array_merge($col, $ref, $editor);

            $columns[] = $col;
        }

        return $columns;
    }

    function replace_parameters($str) {
        if(empty($str))     return $str;

        return replace_parameters($str, 'filter', $this->level1_filter);
    }
}

  