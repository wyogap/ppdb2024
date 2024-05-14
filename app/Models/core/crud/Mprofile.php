<?php 

namespace App\Models\Core\Crud;

use App\Models\Core\Mcrud_ext;

class Mprofile extends Mcrud_ext
{
    protected static $TABLE_ID = 19;     //default table
    protected static $TABLE_NAME = "dbo_users";
    protected static $PRIMARY_KEY = "user_id";
    protected static $LOOKUP_KEY = "nama";
    protected static $COLUMNS = array();

    protected static $SOFT_DELETE = true;

    // function update($id, $valuepair, $filter = null, $enforce_edit_columns = true) {
    //     $this->reset_error();
    
    //     $result = parent::update($id, $valuepair, $filter, $enforce_edit_columns);

    //     $user_id = $this->session->get('user_id');
    //     if ($result > 0 && $user_id == $id) {
    //         if (isset($valuepair['siteid'])) {
    //             //update session
    //             $this->session->set('siteid', $valuepair['siteid']);
    //         }
    //         if (isset($valuepair['itemtypeid'])) {
    //             //update session
    //             $this->session->set('itemtypeid', $valuepair['itemtypeid']);
    //         }
    //     }

    //     return $result;
    // }

}

  