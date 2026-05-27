<?php

namespace App\Commands\Dapodik;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestDbConnection extends BaseCommand
{
    protected $group       = 'Dapodik';
    protected $name        = 'testdbconnection';
    protected $description = 'Test database connection to cloud sql';

    public function run(array $params)
    {
        //output log to console
        //service('logger')->addStdoutHandler();

        $msetting = new \App\Models\Core\Crud\MSetting();
        $cnt = $msetting->count();

        if ($cnt >= 0) {
            log_message('info', "Database connection successful.");
        } else {
            log_message('error', "Database connection failed.");
        }

    }
}