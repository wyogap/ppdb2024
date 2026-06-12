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
        $logger = new \App\Libraries\Monolog();

        $msetting = new \App\Models\Core\Crud\MSetting();
        $cnt = $msetting->count();

        if ($cnt >= 0) {
            $logger->info("Database connection successful.");
        } else {
            $logger->error("Database connection failed.");
        }

    }
}