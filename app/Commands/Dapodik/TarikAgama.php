<?php

namespace App\Commands\Dapodik;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TarikAgama extends BaseCommand
{
    protected $group       = 'Dapodik';
    protected $name        = 'tarikagama';
    protected $description = 'Tarik data agama dari dapodik (hanya untuk siswa yang belum memiliki data agama)';

    public function run(array $params)
    {
        //output log to console
        //service('logger')->addStdoutHandler();

        $mdapodik = new \App\Models\Dapodik\MDapodik();
        $mdapodik->tarik_agama();
    }
}