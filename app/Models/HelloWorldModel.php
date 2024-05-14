<?php 

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class HelloWorldModel
{
    protected $db;

    public function __construct(ConnectionInterface &$db)
    {
        $this->db =& $db;
    }
}