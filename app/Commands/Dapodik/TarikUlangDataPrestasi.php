<?php

namespace App\Commands\Dapodik;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TarikUlangDataPrestasi extends BaseCommand
{
    protected $group       = 'Dapodik';
    protected $name        = 'tarikulangdataprestasi';
    protected $description = 'Tarik ulang data Prestasi dari dapodik (hanya untuk siswa yang belum memiliki data Prestasi)';

    public function run(array $params)
    {
        //output log to console
        //service('logger')->addStdoutHandler();

        $mdapodik = new \App\Models\Dapodik\MDapodik();
        $mdapodik->tarikulang_dataprestasi();
    }
}