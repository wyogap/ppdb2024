<?php

namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Mdropdown;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Ubahprofil extends PpdbController {

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
		$sekolah_id = $this->session->get('sekolah_id');
        if (empty($sekolah_id)) {
			return $this->notauthorized();
		}

        $data['impersonasi_sekolah'] = $this->session->get("impersonasi_sekolah");
 
        //untuk ubah data -> always get latest value from db
        $data['profil'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
        $this->session->set("profilsekolah", $data['profil']);

        $data['daftarputaran'] = $this->Mconfig->tcg_putaran(JENJANGID_SMP);

		// $redirect = $_GET["redirect"] ?? null; 
		// if (empty($redirect)) {
		// 	$redirect = "Clogin";
		// }

		$mdropdown = new \App\Models\Ppdb\Mconfig();
		$data['kabupaten'] = $mdropdown->tcg_kabupaten();
		// $data['redirect'] = $redirect;

        $data['use_leaflet'] = 1;

        //content template
        $data['content_template'] = 'ubahprofil.tpl';

        $data['page_title'] = 'Daftar Ulang';
        $this->smarty->render('ppdb/sekolah/ppdbsekolah.tpl', $data);
	}

	function simpan()
	{
		$pengguna_id = $this->session->get("user_id");
		$peran_id = $this->session->get('role_id');

		$sekolah_id = $_POST["sekolah_id"] ?? null;
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		if ($peran_id==ROLEID_SEKOLAH && $sekolah_id != $this->session->get('sekolah_id')) {
			print_json_error('not-authorized');
		}

		$kode_wilayah = $_POST["kode_wilayah"] ?? null; 
		if($kode_wilayah==''){
			$kode_wilayah = $_POST["kode_desa"] ?? null; 
		}
		$lintang_lama = $_POST["lintang_lama"] ?? null; 
		$bujur_lama = $_POST["bujur_bujur"] ?? null; 
		$lintang = $_POST["lintang"] ?? null; 
		$bujur = $_POST["bujur"] ?? null; 

		$npsn = $_POST["npsn"] ?? null; 
		$npsn_lama = $_POST["npsn_lama"] ?? null; 
		$inklusi = $_POST["inklusi"] ?? null; 
		$inklusi_lama = $_POST["inklusi_lama"] ?? null; 
		$alamat_jalan = $_POST["alamat_jalan"] ?? null; 
		$alamat_jalan_lama = $_POST["alamat_jalan_lama"] ?? null; 

		do {
			$valuepair = array();
			
			if(!empty($npsn) && $npsn!=$npsn_lama){
				$valuepair['npsn'] = $npsn;
			}

			if ($inklusi != $inklusi_lama) {
				$valuepair['inklusi'] = $inklusi;
			}

			if (!empty($alamat_jalan) && $alamat_jalan != $alamat_jalan_lama) {
				$valuepair['alamat_jalan'] = $alamat_jalan;
			}

			if ($lintang != $lintang_lama) {
				$valuepair['lintang'] = $lintang;
			}

			if ($bujur != $bujur_lama) {
				$valuepair['bujur'] = $bujur;
			}

			if (count($valuepair) > 0) {
				if (!$this->Msekolah->tcg_ubah_profil($sekolah_id, $valuepair)) {
					print_json_error("Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.");
					break;
				}
			}

		} while(false);

		$profil = $this->Msekolah->tcg_profilsekolah($sekolah_id);

		print_json_output($profil);
	}
	
}
?>