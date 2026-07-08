<?php

namespace App\Commands\Dapodik;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class KirimDataBalikan extends BaseCommand
{
    protected $group       = 'Dapodik';
    protected $name        = 'kirimdatabalikan';
    protected $description = 'Kirim data balikan ke dapodik';

    public function run(array $params)
    {
        //output log to console
        //service('logger')->addStdoutHandler();

        $mdapodik = new \App\Models\Dapodik\MDapodik();
        $mdapodik->kirimdatabalikan();
    }
}