<?php

namespace App\Commands\Dapodik;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TarikDataPrestasi extends BaseCommand
{
    protected $group       = 'Dapodik';
    protected $name        = 'tarikdataprestasi';
    protected $description = 'Tarik data prestasi dari dapodik';

    public function run(array $params)
    {
        //output log to console
        //service('logger')->addStdoutHandler();

        $mdapodik = new \App\Models\Dapodik\MDapodik();
        $mdapodik->tarikdataprestasi();
    }
}