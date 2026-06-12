<?php

namespace App\Commands\Dapodik;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TarikUlangDataSiswa extends BaseCommand
{
    protected $group       = 'Dapodik';
    protected $name        = 'tarikulangdatasiswa';
    protected $description = 'Tarik ulang data siswa dari dapodik (hanya untuk sekolah yang belum memiliki data siswa)';

    public function run(array $params)
    {
        //output log to console
        //service('logger')->addStdoutHandler();

        $mdapodik = new \App\Models\Dapodik\MDapodik();
        $mdapodik->tarikulang_datasiswa();
    }
}