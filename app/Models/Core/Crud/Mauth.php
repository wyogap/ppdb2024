<?php 

namespace App\Models\Core\Crud;

use App\Libraries\AuditTrail;

class Mauth
{

    protected $db;
    protected $session;
    protected $audittrail;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->audittrail = new AuditTrail();
    }
    
    /**
     * This function used to check the login credentials of the user
     * @param string $username : This is username/email of the user
     * @param string $password : This is encrypted password of the user
     */
    function login($username, $password)
    {
        $builder = $this->db->table('dbo_users as Users');
        $builder->select('Users.*, Roles.role, Roles.page_role, Roles.admin as is_admin');
        $builder->join('dbo_roles as Roles','Roles.role_id = Users.role_id');
        //$builder->join('dbo_uploads as Upload','Upload.id = Users.profile_img', "LEFT OUTER");
		$builder->groupStart();
        $builder->where('Users.email', $username);
        $builder->orWhere('Users.user_name', $username);
		$builder->groupEnd();
        $builder->where('Users.is_deleted', 0);
        $query = $builder->get();
        
        $user = $query->getRowArray();
        if ($user == null)  return $user;
        
        //Using the PASSWORD_BCRYPT as the algorithm, will result in the password parameter being truncated to a maximum length of 72 bytes.
        if (password_verify($password, $user['password'])
                || (empty($user['password']) && $username == $password)) {
            unset($user['password']);
            unset($user['created_on']);
            unset($user['created_by']);
            unset($user['updated_on']);
            unset($user['updated_by']);
            unset($user['is_deleted']);

            //audit trail
            
            return $user;
        }

        return null;
    }

    /**
     * This function used to check email exists or not
     * @param {string} $email : This is users email id
     * @return {boolean} $result : TRUE/FALSE
     */
    function checkEmailExist($email)
    {
        $builder = $this->db->table('dbo_users');
        $builder->select('user_id');
        $builder->where('email', $email);
        $builder->where('is_deleted', 0);
        $query = $builder->get();

        if ($query->getNumRows() > 0){
            return true;
        } else {
            return false;
        }
    }


    /**
     * This function used to insert reset password data
     * @param {array} $data : This is reset password data
     * @return {boolean} $result : TRUE/FALSE
     */
    function resetPasswordUser($data)
    {
        $builder = $this->db->table('dbo_reset_password');
        $result = $builder->insert($data);

        if($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * This function is used to get customer information by email-id for forget password email
     * @param string $email : Email id of customer
     * @return object $result : Information of customer
     */
    function getCustomerInfoByEmail($email)
    {
        $builder = $this->db->table('dbo_users');
        $builder->select('userId, email, name');
        $builder->where('is_deleted', 0);
        $builder->where('email', $email);
        $query = $builder->get();

        return $query->getResult();
    }

    /**
     * This function used to check correct activation deatails for forget password.
     * @param string $email : Email id of user
     * @param string $activation_id : This is activation string
     */
    function checkActivationDetails($email, $activation_id)
    {
        $builder = $this->db->table('dbo_reset_password');
        $builder->select('id');
        $builder->where('email', $email);
        $builder->where('activation_id', $activation_id);
        $query = $builder->get();
        return $query->getNumRows();
    }

    // This function used to create new password by reset link
    function createPasswordUser($email, $password)
    {
        //Using the PASSWORD_BCRYPT as the algorithm, will result in the password parameter being truncated to a maximum length of 72 bytes.
		$data = array(
					'password'=>password_hash($password, PASSWORD_BCRYPT)
				);
				
        $builder = $this->db->table('dbo_users');
        $builder->where('email', $email);
        $builder->where('is_deleted', 0);
        $builder->update($data);
		
        $builder = $this->db->table('dbo_reset_password');
        $builder->where('email', $email);
        $builder->delete();
    }

    function reset_password($user_id, $password, $additionalValues = null) {
        $filter = array(
            'user_id' => $user_id,
            'is_deleted' => 0
        );

        //hash password
        //Using the PASSWORD_BCRYPT as the algorithm, will result in the password parameter being truncated to a maximum length of 72 bytes.
        $valuepair = [
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ];

        //set additional values
        if ($additionalValues != null) {
            $valuepair = array_merge($valuepair, $additionalValues);
        }

        //update values
        $table_name = 'dbo_users';
        $builder = $this->db->table($table_name);

        $builder->where($filter);
        $builder->update($valuepair);
        $affected = $this->db->affectedRows();
        if ($affected > 0) {
            //audit trail
            $this->audittrail->trail($table_name, $user_id, "PASSWORD RESET", "Password reset by administrator or ownself");
            return $user_id;
        }

        return 0;
    }

    function get_profile($user_id)
    {
        $builder = $this->db->table('dbo_users as Users');
        $builder->select('Users.*, Roles.role, Roles.page_role');
        $builder->join('dbo_roles as Roles','Roles.role_id = Users.role_id');
        $builder->where('Users.user_id', $user_id);
        $builder->where('Users.is_deleted', 0);
        $query = $builder->get();
        
        $user = $query->getRowArray();
        if ($user == null)  return $user;
        
        if ($user != null) {
            unset($user['password']);
            unset($user['created_on']);
            unset($user['created_by']);
            unset($user['updated_on']);
            unset($user['updated_by']);
            unset($user['is_deleted']);
        }
 
        return $user;
    }

    function get_profile_username_or_email($username, $email)
    {
        $builder = $this->db->table('dbo_users as Users');
        $builder->select('Users.*, Roles.role, Roles.page_role');
        $builder->join('dbo_roles as Roles','Roles.role_id = Users.role_id');
        $builder->where('Users.is_deleted', 0);
        $builder->groupStart();
        $builder->where('Users.user_name', $username);
        $builder->orWhere('Users.email', $email);
        $builder->groupEnd();
        $query = $builder->get();
        if ($query->getNumRows()>1) return null;

        $user = $query->getResultArray();
        if ($user == null)  return null;
 
        $user = $user[0];
        if ($user != null) {
            unset($user['password']);
            unset($user['created_on']);
            unset($user['created_by']);
            unset($user['updated_on']);
            unset($user['updated_by']);
            unset($user['is_deleted']);
        }
 
        return $user;
    }

    function has_resetcode($user_id) {
        $builder = $this->db->table("dbo_reset_password");
        $builder->select("activation_id");
        $builder->where('user_id', $user_id);
        $builder->where('expired_date>', date('Y/m/d H:i:s'));
        $builder->where('is_deleted=0');

        // $sql = $builder->getCompiledSelect();
        // echo "SQL:" .$sql; exit;

        $query = $builder->get();
        if (!$query) return false;

        return ($query->getNumRows()>0 ? true : false);
    }
     
    function check_resetcode($user_id, $code) {
        $builder = $this->db->table("dbo_reset_password");
        $builder->select("user_id");
        $builder->where('activation_id', $code);
        $builder->where('user_id', $user_id);
        $builder->where('expired_date>', date('Y/m/d H:i:s'));
        $builder->where('is_deleted=0');
        $query = $builder->get();
        if (!$query || $query->getNumRows()>1) return null;

        $result = $query->getRowArray();
        if (!$result) return null;

        return $result['user_id'];
    }

    function generate_resetcode($user_id) {
        $code = $this->generate_randomstring(6);

        //disable existing code for this user
        $sql = "update dbo_reset_password set is_deleted=1 where user_id=?";
        $this->db->query($sql, array($user_id));

        //create new entry
        $valuepair = array();
        $valuepair['user_id'] = $user_id;
        $valuepair['activation_id'] = $code;

        //expired in 1 hour
        $timestamp = time() + 60*60;
        $valuepair['expired_date'] = date('Y/m/d H:i:s', $timestamp);

        $builder = $this->db->table("dbo_reset_password");
        $result = $builder->insert($valuepair);
        if (!$result)   return null;

        return $code;
    }

    private function generate_randomstring($length = 6) {
        $characters = '0123456789bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ';
        $len = strlen($characters);
        $str = '';
    
        for ($i = 0; $i < $length; $i++) {
            $str .= $characters[random_int(0, $len - 1)];
        }
    
        return $str;
    }    

    function disable_resetcode($userid, $code) {
        $builder = $this->db->table("dbo_reset_password");
        $valuepair = [
            'is_deleted' => 1
        ];
        $filter = [
            'user_id' => $userid,
            'activation_id' => $code    
        ];

        $builder->where('user_id', $userid);
        $builder->where('activation_id', $code);
        $builder->update($valuepair);
    }

    function update_profile($userid, $data) {
        unset($data['password']);
        unset($data['user_id']);
        unset($data['user_name']);

        $builder = $this->db->table("dbo_users");

        $builder->where('user_id', $userid);
        $status = $builder->update($data);
        if (!$status)   return null;
        
        return $this->get_profile($userid);
    }
}

?>
