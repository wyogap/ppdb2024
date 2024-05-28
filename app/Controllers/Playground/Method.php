<?php

namespace App\Controllers\Playground;

use App\Controllers\Ppdb\PpdbController;
use App\Libraries\QRCodeLibrary;
use App\Libraries\Uploader;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use App\Models\Ppdb\Mdropdown;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Psr\Log\LoggerInterface;
use Config\App;

class Method extends PpdbController {

    function index() {
        $data['page_title'] = "Map";
        $this->smarty->render("playground/map.tpl", $data);
    }

    public function _remap($method, $param = null)
	{
        $uri = $this->request->getUri();
        $segments = $uri->getSegments();
        echo "Segments";
        var_dump($segments);

        echo "Method";
        var_dump($method);

        echo "this->method";
        var_dump($this->method);

        echo "this->params";
        var_dump($this->params);

        $method1 = $this->request->getMethod();
        echo "Request Method";
        var_dump($method1);

        $gets = $this->request->getGet();
        echo "Gets";
        var_dump($gets);

        $headers = $this->request->getHeaders();
        echo "Headers";
        var_dump($headers);

        // $args = $this->request->getArgs();
        // echo "Args";
        // var_dump($args);

        $path = $this->request->getPath();
        echo "Path";
        var_dump($path);

        exit;
    }

    protected function get_params() {
        $segments = $this->request->getUri()->getSegments();
        $total = count($segments);
        $controller = strtolower(mb_basename(get_class($this)));

        $method = "";
        $params = array();
        for($i=0; $i<$total; $i++) {
            $segment = strtolower($segments[$i]);
            if ($segment == $controller) {
                if ($i+1<$total) {
                    $method=$segments[$i+1];
                }
                else {
                    $method='index';
                }
                for($j=$i+2; $j<$total; $j++) {
                    $params[] = $segments[$j];
                }
                break;
            }
        }

        return $params;
    }    
}