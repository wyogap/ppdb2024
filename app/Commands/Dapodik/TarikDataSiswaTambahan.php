<?php

namespace App\Commands\Dapodik;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TarikDataSiswaTambahan extends BaseCommand
{
    protected $group       = 'Dapodik';
    protected $name        = 'tarikdatasiswatambahan';
    protected $description = 'Tarik data siswa tambahandari dapodik';

    public function run(array $params)
    {
        //output log to console
        //service('logger')->addStdoutHandler();

        $mdapodik = new \App\Models\Dapodik\MDapodik();
        $mdapodik->tarikdatasiswa_tambahan();
    }
}