<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->add('/ppdb/sekolah', '\App\Controllers\Ppdb\Sekolah\Beranda::index');
$routes->add('/ppdb/dapodik', '\App\Controllers\Ppdb\Dapodik\Daftarsiswa::index');
