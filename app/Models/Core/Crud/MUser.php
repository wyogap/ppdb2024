<?php 

namespace App\Models\Core\Crud;

use Exception;
use App\Models\Core\MCrud5;

class MUser extends MCrud5
{
    protected $TABLE_NAME = "dbo_users";
    protected $COLUMNS = array('id', 'username', 'fullname', 'isadmin');
    protected $INSERT_COLUMNS = array('username', 'fullname', 'isadmin');
    protected $COMPULSORY_COLUMNS = array('username');

    function __construct() {
        parent::__construct();
    }

    function setpassword($user_id, $password) {
        $this->reset_error();

        $filter = array(
            'id' => $user_id
        );

        //hash password
        //Using the PASSWORD_BCRYPT as the algorithm, will result in the password parameter being truncated to a maximum length of 72 bytes.
        $valuepair = [
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ];

        //update values
        $builder = $this->db->table($this->TABLE_NAME);

        $builder->where($filter);
        $builder->update($valuepair);

        $affected = $this->db->affectedRows();
        if ($affected == 0) return 0;

        return 1;
    }

    function login($username, $password) {
        $this->reset_error();

        $filters = array(
            "username" => $username
        );

        $columns = array('id', 'username', 'fullname', 'isadmin', 'password');
        $user = $this->select($columns, $filters);
        if ($user == null)    return null;

        //Using the PASSWORD_BCRYPT as the algorithm, will result in the password parameter being truncated to a maximum length of 72 bytes.
        if (password_verify($password, $user['password'])
                || (empty($user['password']) && $username == $password)) {
            unset($user['password']);
            
            return $user;
        }
        
        return null;
    }
}

  