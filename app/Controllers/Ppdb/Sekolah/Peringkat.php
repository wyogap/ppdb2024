<?php
namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Mhome;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Peringkat extends PpdbController {

    protected static $ROLE_ID = ROLEID_SEKOLAH;      

    protected $Msekolah;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load model
        $this->Msekolah = new Mprofilsekolah();

    }

	function index()
	{
		$sekolah_id = $this->session->get("sekolah_id");
        if (empty($sekolah_id)) {
			return $this->notauthorized();
		}
        
        $mconfig = new \App\Models\Ppdb\Mconfig();
        $data['daftarputaran'] = $mconfig->tcg_putaran();

        $data['impersonasi_sekolah'] = $this->session->get("impersonasi_sekolah");
        if ($data['impersonasi_sekolah'] == 1) {
            $data['profil'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
        }
        
		$data['daftarpenerapan'] = $this->Msekolah->tcg_daftarpenerapan($sekolah_id);
        
        //pendaftar per penerapan
        $pendaftar = array();
        foreach($data['daftarpenerapan'] as $p) {
            $penerapan_id = $p['penerapan_id'];
            $penerapan = $this->Msekolah->tcg_pendaftaran_penerapanid($sekolah_id, $penerapan_id);
            for($i=0; $i<count($penerapan); $i++) {
                $penerapan[$i]['idx'] = $i+1;
                //mask nisn
                $penerapan[$i]['nisn'] = substr($penerapan[$i]['nisn'],0,6) .str_repeat("*", 4);
                $penerapan[$i]['skor'] = round($penerapan[$i]['skor'],2);
            }
            //var_dump($penerapan);
            $pendaftar[$penerapan_id] = $penerapan;
        }
        $data['pendaftar'] = $pendaftar;
        //exit;

        //semua pendaftar
        $data['show_all_pendaftar'] = 1;
		$semuapendaftar = $this->Msekolah->tcg_daftarpendaftaran($sekolah_id);
        for($i=0; $i<count($semuapendaftar); $i++) {
            $semuapendaftar[$i]['idx'] = $i+1;
            //mask nisn
            $semuapendaftar[$i]['nisn'] = substr($semuapendaftar[$i]['nisn'],0,6) .str_repeat("*", 4);
        }
        $data['semuapendaftar'] = $semuapendaftar;

		$data['action'] = 'peringkat';
		$data['sekolah_id'] = $sekolah_id;
		
		//terakhir kali peringkat diproses
		$data['last_execution_date'] = '';
		$data['next_execution_date'] = '';
        $mhome = new Mhome();
		$job = $mhome->tcg_job_peringkatpendaftaran();
		if ($job != null) {
			$data['last_execution_date'] = $job['last_execution_end'];
			$data['next_execution_date'] = $job['next_execution'];
        }
		
		//nggak perlu tampilin profil sekolah untuk admin sekolah
		if ($this->is_sekolah && $sekolah_id == $this->session->get('sekolah_id'))
			$data['show_profile_sekolah'] = 0;
		else
			$data['show_profile_sekolah'] = 1;

		$data['cek_waktupendaftaran'] = $this->Mconfig->tcg_cek_waktupendaftaran();
		$data['cek_waktuverifikasi'] = $this->Mconfig->tcg_cek_waktuverifikasi();
        if ($data['cek_waktupendaftaran'] != 1 && $data['cek_waktuverifikasi'] != 1) {
            $data['final_ranking'] = 1;
        }
        else {
            $data['final_ranking'] = 0;
        }

		// $data['inklusi']=0;
        // if ($data['profilsekolah'] != null) {
        //     $data['inklusi']=$data['profilsekolah']['inklusi'];
        // }

        // $data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
        
        $data['use_datatable'] = 1;

        //content template
        $data['content_template'] = 'peringkat.tpl';

		$data['page'] = 'peringkat';
		$data['page_title'] = 'Peringkat';
 
        $this->smarty->render('ppdb/sekolah/ppdbsekolah.tpl', $data);
	}



}
