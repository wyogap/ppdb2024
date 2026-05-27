<?php

namespace App\Models\Core;

/**
 * Class CrudController
 *
 */
interface ICrudModel5
{
    // /**
    //  * Search for records
    //  *
    //  * $query   : free text to search, if empty then it is used for filtering
    //  * $filters : list of filter columns and filter value
    //  * $pagenum : if pagesize is configured, the page number to retrieve. if not, it is ignored
    //  * $orderby : query to order the result
    //  * 
    //  * @return list of records matching the $query string and $filters columns, empty list if no matching records, null if error
    //  */
    // function search($query = null, $filters = null, $pagenum = 0, $orderby = null);

    /**
     * Show active records
     * 
     * $pagenum : if pagesize is configured, the page number to retrieve. if not, it is ignored
     * $orderby : query to order the result
     * 
     * @return list of records or empty list if successful, null if error
     */
    function list($pagenum = 0, $orderby = null);

    // /**
    //  * If soft delete is enabled, show deleted records. If not, always return empty list
    //  * 
    //  * $pagenum : if pagesize is configured, the page number to retrieve. if not, it is ignored
    //  * $orderby : query to order the result
    //  * 
    //  * @return list of records or empty list if successful, null if error
    //  */
    // function deletedlist($pagenum = 0, $orderby = null);

    /**
     * Show a single record with the given id
     * 
     * @id          : record id based on the table primary key
     * @filters     : additional filters, can be used to enforce subset of data
     * @showdeleted : whether to show soft deleted record
     * 
     * @return a single record if the given id is found, null if not
     */
    function detail($id, $filters = null, $showdeleted = false);

    /**
     * Update batch of records
     * 
     * @filters     : data filters
     * @valuepair   : list of columns and the value to update
     * 
     * @return list of records with updated data if successful, null if not
     */
    function updatebatch($filters, $valuepair);
    
    /**
     * Update a single record
     * 
     * @id          : record id based on the table primary key
     * @valuepair   : list of columns and the value to update
     * @filters     : additional filters, can be used to enforce subset of data
     * 
     * @return a single record with updated data if successful, null if not
     */
    function update($id, $valuepair, $filters = null);

    /**
     * Delete a single record
     * 
     * @id          : record id based on the table primary key
     * 
     * @return 1 if successful, 0 is not
     */
    function delete($id);

    /**
     * Add a new record
     * 
     * @valuepair   : list of columns and the value to insert
     * 
     * @return a single record of the newly inserted data if successful, null if not
     */
    function add($valuepair);

    // /**
    //  * Lookup list of value-label pair from table join. Use to input the corresponding column in select input.
    //  * 
    //  * @tablename   : the name of the join table, if null then current table as lookup
    //  * @filters     : additional filter columns and the filter value
    //  * $pagenum     : if pagesize is configured, the page number to retrieve. if not, it is ignored
    //  * $orderby     : query to order the result
    //  * 
    //  * @return list of value-label pair if successful, null if not
    //  */
    // function lookup($tablename = null, $filters = null, $pagenum = 0, $orderby = null);

    // /**
    //  * Import data from XLS file
    //  * 
    //  * @file        : path to the file
    //  * @defaults    : list of columns and the default value, used to set value for columns not in the file
    //  * 
    //  * @return number of imported records
    //  */
    // function import($file, $defaults = null);

    // /**
    //  * Clone/duplicate a specific record
    //  * 
    //  * @id          : record id based on the table primary key
    //  * 
    //  * @return a single row of the new record if successful, null if not
    //  */
    // function clone($id);

    /**
     * Get last error code
     * 
     * @return last error code
     */
    function get_error_code();

    /**
     * Get last error message
     * 
     * @return last error message
     */
    function get_error_message();

    /**
     * Get actual total count of last list/search query. If last query is not list/search/lookup, return null
     * 
     * @return total count of last list/search query or null if last query is not list/search/lookup
     */
    function get_total_count();
}


