<?php

namespace App\Commands\Dapodik;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestApiConnection extends BaseCommand
{
    protected $group       = 'Dapodik';
    protected $name        = 'testapiconnection';
    protected $description = 'Test API connection to dapodik';

    public function run(array $params)
    {
        //output log to console
        //service('logger')->addStdoutHandler();

        $mdapodik = new \App\Models\Dapodik\MDapodik();
        $token = $mdapodik->getToken();

        log_message('info', "Token: " . $token);    
    }
}