<?php

namespace App\Commands\Dapodik;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TarikUlangDataTka extends BaseCommand
{
    protected $group       = 'Dapodik';
    protected $name        = 'tarikulangdatatka';
    protected $description = 'Tarik ulang data TKA dari dapodik (hanya untuk siswa yang belum memiliki data TKA)';

    public function run(array $params)
    {
        //output log to console
        //service('logger')->addStdoutHandler();

        $mdapodik = new \App\Models\Dapodik\MDapodik();
        $mdapodik->tarikulang_datatka();
    }
}