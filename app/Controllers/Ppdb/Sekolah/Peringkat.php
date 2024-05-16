<?php
namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Mhome;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Peringkat extends PpdbController {
    protected $Msekolah;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        //$this->smarty = new SmartyLibrary();

        //load model
        $this->Msekolah = new Mprofilsekolah();
        
        //TODO: disable first for testing
        // if($this->session->get('is_logged_in')==FALSE || $this->session->get('peran_id')!=ROLEID_SISWA) {
		// 	redirect(site_url() .'auth');
		// }

    }

	function index()
	{
		$sekolah_id = $this->session->get("sekolah_id");

        //DEBUG
        $sekolah_id = '402D235A-2DF5-E011-A399-D7F6DAA2C8A5';
        $this->session->set("sekolah_id", $sekolah_id);
        $this->putaran = 2;
        $this->session->set('putaran_aktif', $this->putaran);
        $upload_dokumen = 0;
        //END DEBUG

        $data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id)->getRowArray();
		$data['daftarpenerapan'] = $this->Msekolah->tcg_daftarpenerapan($sekolah_id)->getResultArray();
        
        //pendaftar per penerapan
        $pendaftar = array();
        foreach($data['daftarpenerapan'] as $p) {
            $penerapan_id = $p['penerapan_id'];
            $penerapan = $this->Msekolah->tcg_pendaftaran_penerapan_id($sekolah_id, $penerapan_id)->getResultArray();
            for($i=0; $i<count($penerapan); $i++) {
                $penerapan[$i]['idx'] = $i+1;
                //mask nisn
                $penerapan[$i]['nisn'] = substr($penerapan[$i]['nisn'],0,6) .str_repeat("*", 4);
                $penerapan[$i]['skor'] = round($penerapan[$i]['skor'],2);
            }
            $pendaftar[$penerapan_id] = $penerapan;
        }
        $data['pendaftar'] = $pendaftar;

        //semua pendaftar
        $data['show_all_pendaftar'] = 1;
		$semuapendaftar = $this->Msekolah->tcg_pendaftar($sekolah_id)->getResultArray();
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
		$job = $mhome->tcg_job_peringkatpendaftaran()->getRowArray();
		if ($job != null) {
			$data['last_execution_date'] = $job['last_execution_end'];
			$data['next_execution_date'] = $job['next_execution'];
        }
		
		//nggak perlu tampilin profil sekolah untuk admin sekolah
		if ($this->is_sekolah && $sekolah_id == $this->session->get('sekolah_id'))
			$data['show_profile_sekolah'] = 0;
		else
			$data['show_profile_sekolah'] = 1;

		$data['cek_waktupendaftaran'] = $this->Msetting->tcg_cek_waktupendaftaran();

		$data['inklusi']=0;
        if ($data['profilsekolah'] != null) {
            $data['inklusi']=$data['profilsekolah']['inklusi'];
        }

        $data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
		// $data['daftarkuota'] = $this->Msekolah->tcg_daftarkuota();

        //Debug
        $data['nama_pengguna'] = "Wahyu Yoga Pratama";
        $data['username'] = "wyogap@gmail.com";
        $data['ganti_password'] = 0;
        //END DEBUG

        //content template
        $data['content_template'] = 'peringkat.tpl';

		$data['page_title'] = 'Peringkat';
        $this->smarty->render('ppdb/sekolah/ppdbsekolah.tpl', $data);
	}



}
