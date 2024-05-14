<?php 

namespace App\Models\Core;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Session\Session;

class BaseModel
{
    protected BaseConnection $db;
    protected Session $session;

    public function __construct(BaseConnection &$db = null)
    {
        if ($db == null) {
            $this->db = \Config\Database::connect();
        }
        else {
            $this->db =& $db;
        }
        $this->session = \Config\Services::session();
    }
}