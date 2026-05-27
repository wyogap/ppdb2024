<?php

namespace App\Commands\Dapodik;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TarikDataTka extends BaseCommand
{
    protected $group       = 'Dapodik';
    protected $name        = 'tarikdatatka';
    protected $description = 'Tarik data nilai TKA dari dapodik';

    public function run(array $params)
    {
        //output log to console
        //service('logger')->addStdoutHandler();

        $mdapodik = new \App\Models\Dapodik\MDapodik();
        $mdapodik->tarikdatatka();
    }
}