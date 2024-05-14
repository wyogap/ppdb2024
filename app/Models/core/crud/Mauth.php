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

        if (password_verify($password, $user['password'])) {
            unset($user['password']);
            unset($user['created_on']);
            unset($user['created_by']);
            unset($user['updated_on']);
            unset($user['updated_by']);
            unset($user['is_deleted']);
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

    function reset_password($user_id, $password) {
        $filter = array(
            'user_id' => $user_id,
            'is_deleted' => 0
        );

        $valuepair = array(
            'password' => password_hash($password, PASSWORD_DEFAULT)
        );

        $table_name = 'dbo_users';
        $builder = $this->db->table($table_name);

        $builder->where($filter);
        $builder->update($valuepair);
        $affected = $this->db->affectedRows();
        if ($affected > 0) {
            //audit trail
            $this->audittrail->trail($table_name, $user_id, "PASSWORD RESET", "Password reset by administrator");
            return $user_id;
        }

        return 0;
    }

    function profile($user_id)
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
    
}

?>
