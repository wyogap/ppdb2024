<?php

namespace App\Commands\Dapodik;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class UpdateRegistrasiSiswa extends BaseCommand
{
    protected $group       = 'Dapodik';
    protected $name        = 'updateregistrasisiswa';
    protected $description = 'Update data registrasi siswa dari dapodik';

    public function run(array $params)
    {
        //output log to console
        //service('logger')->addStdoutHandler();
        //echo "Update data registrasi siswa dari dapodik..." . PHP_EOL;

        $mdapodik = new \App\Models\Dapodik\MDapodik();
        $mdapodik->update_registrasisiswa();
    }
}