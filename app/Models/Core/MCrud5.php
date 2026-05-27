<?php 

namespace App\Models\Core;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Session\Session;
use App\Libraries\Uploader;
use App\Libraries\Setting;
use Exception;

class MCrud5 implements ICrudModel5
{
    protected $TABLE_NAME = "crud_table";
    protected $EDIT_TABLE_NAME = null;
    protected $PRIMARY_KEY = "ID";

    protected $COLUMNS = array();

    //to enforce insert/update
    protected $INSERT_COLUMNS = array();
    protected $UPDATE_COLUMNS = array();
    protected $COMPULSORY_COLUMNS = array();

    protected BaseConnection $db;
    protected Session $session;
 
    protected $error_code = 0;
    protected $error_message = null;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();

        if (empty($this->EDIT_TABLE_NAME))      $this->EDIT_TABLE_NAME = $this->TABLE_NAME;
        if (empty($this->INSERT_COLUMNS))       $this->INSERT_COLUMNS = $this->COLUMNS;
        if (empty($this->UPDATE_COLUMNS))       $this->UPDATE_COLUMNS = $this->INSERT_COLUMNS;
    }

    /**
     * Show number of records based on filters
     * 
     * $filters : additional filters to retrieve subset of data
     * 
     * @return int num of records if successful, -1 if error
     */
    function count($filters = null) {        
        //use view if specified
        $builder = $this->db->table($this->TABLE_NAME);

        //filter
        if (!empty($filters)) {
            foreach($filters as $key => $val) {
                $builder->where($key, $val);
            }
        }

        //select column
        $builder->select("count(*) as cnt");
 
        //build query
        $query = null;
        try {
            $query = $builder->get();
        }
        catch(Exception $ex) {
            //set error message
            $this->set_error('invalid query: ' .$ex->getMessage(), ERRNO_INVALID_QUERY, 1);
        }

        $result = $query->getRowArray();
        if ($result == null)       return -1;

        //TODO: special transformation -> move to child
        
        return $result['cnt'];
    }

    /**
     * Show active records
     * 
     * $pagenum : if pagesize is configured, the page number to retrieve. if not, it is ignored
     * $orderby : query to order the result
     * $filters : additional filters to retrieve subset of data
     * 
     * @return list of records or empty list if successful, null if error
     */
    function list($pagenum = 0, $orderby = null, $filters = null) {
        $this->reset_error();

        if ($filters == null)   $filters = array();
        
        return $this->select($this->COLUMNS, $filters, $pagenum, $orderby);
    }

    /**
     * Show a single record with the given id
     * 
     * @id          : record id based on the table primary key
     * @filters     : additional filters, can be used to enforce subset of data
     * @showdeleted : whether to show soft deleted record
     * 
     * @return a single record if the given id is found, null if not
     */
    function detail($id, $filters = null, $showdeleted = false) {
        $this->reset_error();

        if ($filters == null)   $filters = array();

        //specific key
        $filters[ $this->PRIMARY_KEY ] = $id;

        $results = $this->select($this->COLUMNS, $filters);
        if ($results == null)   return null;

        return $results[0];
    }

    /**
     * Update batch of records
     * 
     * @filters     : data filters
     * @valuepair   : list of columns and the value to update
     * 
     * @return list of records with updated data if successful, null if not
     */
    function updatebatch($filters, $valuepair) {
        if (empty($filters)) {
            $this->set_error("can not update whole table", ERRNO_INVALID_QUERY, 1);
        }

        if (empty($valuepair))     return null;

        //enforce edit columns
        $columns = array_keys($valuepair);
        foreach($columns as $key) {
            if (false === array_search($key, $this->UPDATE_COLUMNS)) {
                //invalid columns
                $this->set_error("read only column: " .$key, ERRNO_READONLY_COLUMN, 1);
            }
        }

        //enforce mandatory columns
        if (!empty($this->COMPULSORY_COLUMNS)) {
            foreach($this->COMPULSORY_COLUMNS as $key) {
                if (false !== array_search($key, $columns) && null === $valuepair[$key]) {
                    //mandatory columns is set to null
                    $this->set_error("mandatory column: " .$key, ERRNO_MANDATORY_COLUMN, 1);
                }
            }
        }

        //special transformation -> move to child

        //use internal table if specified
        $builder = $this->db->table($this->EDIT_TABLE_NAME);

        //filters
        foreach($filters as $key => $val) {
            $builder->where($key, $val);
        }

        $builder->update($valuepair);
        
        $affected = $this->db->affectedRows();
        $updatedrecords = $this->select($this->COLUMNS, $filters);

        return $updatedrecords;
    }

    /**
     * Update a single record
     * 
     * @id          : record id based on the table primary key
     * @valuepair   : list of columns and the value to update
     * 
     * @return a single record with updated data if successful, null if not
     */
    function update($id, $valuepair, $filters = null) {
        $this->reset_error();

        if (empty($valuepair))     return null;

        //enforce edit columns
        $columns = array_keys($valuepair);
        foreach($columns as $key) {
            if (false === array_search($key, $this->UPDATE_COLUMNS)) {
                //invalid columns
                $this->set_error("read only column: " .$key, ERRNO_READONLY_COLUMN, 1);
            }
        }

        //enforce mandatory columns
        if (!empty($this->COMPULSORY_COLUMNS)) {
            foreach($this->COMPULSORY_COLUMNS as $key) {
                if (false !== array_search($key, $columns) && null === $valuepair[$key]) {
                    //mandatory columns is set to null
                    $this->set_error("mandatory column: " .$key, ERRNO_MANDATORY_COLUMN, 1);
                }
            }
        }

        //special transformation -> move to child

        //use internal table if specified
        $builder = $this->db->table($this->EDIT_TABLE_NAME);

        //additional filters if any
        if (!empty($filters)) {
            foreach($filters as $key => $val) {
                $builder->where($key, $val);
            }
        }

        //specific key
        $builder->where($this->PRIMARY_KEY, $id);

        $builder->update($valuepair);
        
        $affected = $this->db->affectedRows();
 
        return $this->detail($id);
    }

    /**
     * Delete batch of records
     * 
     * @filters     : data filters
     * 
     * @return num of deleted records if successful, 0 is not
     */
    function deletebatch($filters) {
        if (empty($filters)) {
            $this->set_error("can not delete whole table", ERRNO_INVALID_QUERY, 1);
        }

        //use internal table if specified
        $builder = $this->db->table($this->EDIT_TABLE_NAME);

        //filters
        $builder->where($filters);
        // foreach($filters as $key => $val) {
        //     $builder->where($key, $val);
        // }

        //hard delete?
        $builder->delete();

        //update related tables
        $affected = $this->db->affectedRows();

        return $affected;
    }

    /**
     * Delete a single record
     * 
     * @id          : record id based on the table primary key
     * @filters     : additional filters, can be used to enforce subset of data
     * 
     * @return 1 if successful, 0 is not
     */
    function delete($id, $filter = null) {
        $this->reset_error();

        if (empty($id))     return null;

        // //oldvalues for logging
        // $oldvalues = $this->detail($id);
        // if ($oldvalues == null) {
        //     //not found => throw exception
        //     $this->set_error("invalid id: " .$id, ERRNO_INVALID_ID, 1);
        // }

        //use internal table if specified
        $builder = $this->db->table($this->EDIT_TABLE_NAME);

        //specific key
        $builder->where($this->PRIMARY_KEY, $id);

        //soft delete or hard delete?
        $builder->delete();

        $affected = $this->db->affectedRows();

        return $affected;
    }

    /**
     * Add a new record
     * 
     * @valuepair   : list of columns and the value to insert
     * 
     * @return single record of the newly inserted data if successful, null if not
     */
    function add($valuepair) {
        $this->reset_error();

        if (empty($valuepair))     return null;

        //enforce edit columns
        $columns = array_keys($valuepair);
        foreach($columns as $key) {
            if (false === array_search($key, $this->INSERT_COLUMNS)) {
                //readonly columns
                unset($valuepair[$key]);
            }
        }

        //empty data
        if (empty($valuepair)) {
            //throw exception
            $this->set_error("empty data", ERRNO_INSERT_ERROR, 1);
        }

        //enforce mandatory columns
        if (!empty($this->COMPULSORY_COLUMNS)) {
            foreach($this->COMPULSORY_COLUMNS as $key) {
                if (false === array_search($key, $columns)) {
                    //mandatory columns not provided => throw exception
                    $this->set_error("mandatory column: " .$key, ERRNO_MANDATORY_COLUMN, 1);
                }
            }
        }

        //special transformation -> moved to child

        //use view if specified
        $builder = $this->db->table($this->EDIT_TABLE_NAME);
        $builder->set($valuepair);

        // $str = $builder->getCompiledInsert();
        // echo ($str); exit;

        $query = $builder->insert();
        if (!$query) {
            //throw exception
            $this->set_error('insert-error', ERRNO_INSERT_ERROR, 1);
        }

        $id = $this->db->insertID();
        if (!$id) {
            //throw exception
            $this->set_error('insert-error', ERRNO_INSERT_ERROR, 1);
        } 

        return $this->detail($id);
    }

   /**
     * Add batch of records
     * 
     * @data   : list of records to insert
     * 
     * @return list of records of the newly inserted data if successful, null if not
     */
    function addbatch($data) {
        $this->reset_error();

        $values = $data;
        //$failed = array();

        if (empty($data))     return null;

        //enforce edit columns
        foreach($values as $k => $r) {
            $columns = array_keys($r);
            foreach($columns as $c) {
                if (false === array_search($c, $this->INSERT_COLUMNS)) {
                    //readonly columns
                    unset($values[$k][$c]);
                }
            }        
        }

        //empty data
        foreach($values as $k => $r) {
            if (empty($r)) {
                //remove
                unset($values[$k]);
            }
        }

        //enforce mandatory columns
        if (!empty($this->COMPULSORY_COLUMNS)) {
            foreach($values as $k => $r) {
                $columns = array_keys($r);
                foreach($this->COMPULSORY_COLUMNS as $c) {
                    if (false === array_search($c, $columns)) {
                        //mandatory columns not provided => remove
                        unset($values[$k]);
                    }
                }
            }
        }


        $builder = $this->db->table($this->TABLE_NAME);
        $result = $builder->insertBatch($values);
        if (empty($result)) return null;

        return $values;
    }

    public function get_error_code() {
        return $this->error_code;
    }

    public function get_error_message() {
        return $this->error_message;
    }

    public function get_total_count() {
        // TODO: implement
        return null;
    }

    protected function reset_error() {
        $this->error_code = 0;
        $this->error_message = null;
    }

    protected function set_error($message, $code, $throwexception = 0) {
        $this->error_code = $code;
        $this->error_message = $message;

        log_message('error', $message);

        if ($throwexception) {
            throw new Exception($message, $code);
        }
    }

    protected function select($columns, $filters = null, $pagenum = 0, $orderby = null, $limit = null, $offset = 0) {        
        //use view if specified
        $builder = $this->db->table($this->TABLE_NAME);

        //filter
        if (!empty($filters)) {
            foreach($filters as $key => $val) {
                $builder->where($key, $val);
            }
        }

        //select column
        if (empty($columns)) {
            $builder->select("*");
        }
        else {
            foreach($columns as $col) {
                $builder->select($col);
            }
        }

        //order by
        if(!empty($orderby)) {
            $builder->orderBy($orderby);
        }

        // $str = $builder->getCompiledSelect(false);
        // echo ($str . "<br>");
        // exit;

        //build query
        $query = null;
        try {
            $query = $builder->get($limit, $offset);
        }
        catch(Exception $ex) {
            //set error message
            $this->set_error('invalid query: ' .$ex->getMessage(), ERRNO_INVALID_QUERY, 1);
        }

        $result = $query->getResultArray();
        if ($result == null)       return null;

        //TODO: special transformation -> move to child
        
        return $result;
    }

};

