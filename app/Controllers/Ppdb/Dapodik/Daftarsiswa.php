<?php

namespace App\Controllers\Ppdb\Dapodik;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Daftarsiswa extends PpdbController {

    protected static $ROLE_ID = ROLEID_DAPODIK;              

    protected $Msekolah;
    protected $Msiswa;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        //$this->smarty = new SmartyLibrary();

        //load model
        $this->Msekolah = new Mprofilsekolah();
        $this->Msiswa = new Mprofilsiswa();
    }

	function index()
	{
		$sekolah_id = $this->session->get("sekolah_id");

        //notifikasi tahapan
        $data['tahapan_aktif'] = $this->Mconfig->tcg_tahapan_pelaksanaan_aktif();
        $data['pengumuman'] = $this->Mconfig->tcg_pengumuman();

        $data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
        $data['kabupaten'] = $this->Mconfig->tcg_kabupaten();

		$data['cek_waktusosialisasi'] = $this->Mconfig->tcg_cek_waktusosialisasi();

        $data['use_datatable'] = 1;
        $data['use_select2'] = 1;
        $data['use_leaflet'] = 1;

        //content template
        $data['content_template'] = 'daftarsiswa.tpl';
        $data['js_template'] = '_daftarsiswa.tpl';

        $data['page'] = 'daftarsiswa';
		$data['page_title'] = 'Daftar Siswa';
        $this->smarty->render('ppdb/dapodik/ppdbdapodik.tpl', $data);
	}

	function json() {
		$sekolah_id = $this->session->get('sekolah_id');
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Msekolah->tcg_daftar_siswa($sekolah_id); 
			echo json_encode($data);	
		}

	}

    function simpan() {
        $data = $this->request->getPost("data");
        if (empty($data))   
            print_json_error("Invalid data");

        $peserta_didik_id = $this->request->getPost('peserta_didik_id');

		//only can verify within the specified timeframe
		$cek_waktusosialisasi = $this->Mconfig->tcg_cek_waktusosialisasi();

        //debugging
        if (__DEBUGGING__) {
            $cek_waktusosialisasi = 1;
        }

        if ($cek_waktusosialisasi != 1) {
            print_json_error("Sudah tidak diperbolehkan mengubah data DAPODIK.");
        }

        //TODO
        $oldvalues = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id);
        if ($oldvalues == null) {
            print_json_error("Invalid userid");
        }

        $updatedprofil = $data['profil'];
        //only save changed data
        $updated = array();
        foreach($updatedprofil as $key => $val) {
            //echo $val ." - ". $siswa[$key];
            $updated[$key] = $updatedprofil[$key];
        }

        if (empty($updated)) {
            print_json_error("Tidak ada data yang berubah");
        }

        $detail = $this->Msiswa->tcg_update_siswa($peserta_didik_id, $updated);

        if ($detail == null)
            print_json_error("Tidak berhasil mengubah data siswa.");

        //audit trail
        audit_siswa($oldvalues, "UBAH DATA", "Ubah data oleh Admin Dapodik an. " .$oldvalues['nama'], array_keys($updated), $updated, $oldvalues);

        print_json_output($detail);
    }

    function profilsiswa() 
	{
		$pengguna_id = $this->session->get('user_id');

		$peserta_didik_id = $this->request->getPostGet("peserta_didik_id"); 

        $data = array();

		$profil = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id);
        if ($profil == null) {
            print_json_error("Profil tidak ditemukan.");
            return;
        }
        
        $data['profil'] = $profil;

        print_json_output($data);
	}

    function resetpassword() {
        $data = $this->request->getPost("data");

        $status = array();
        foreach($data as $k => $v) {
            $pin1 = $v["pwd1"];
            $pin2 = $v["pwd2"];
            if ($pin1 != $pin2) {
                print_json_error("Password baru tidak sama. Silahkan ulangi lagi.");
            }
    
            $sekolah_id = $this->session->get("sekolah_id");
    
            $profil = $this->Msiswa->tcg_profilsiswa($k);
            if ($profil['sekolah_id'] != $sekolah_id) {
                print_json_error("Tidak diperbolehkan mengubah Password.");
            }
    
            $user_id = $this->Msekolah->tcg_userid_from_pesertadidikid($k);
    
            $mauth = new \App\Models\Core\Crud\Mauth();
            if($mauth->reset_password($user_id, $pin1) == 0) {
                print_json_error("Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.");
            }
            
            //audit trail
            audit_siswa($profil, "RESET PASSWORD", "Reset Password oleh Admin Dapodik");
        }

        print_json_output(null);
    }

}
?>