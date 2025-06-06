<?php

namespace App\Controllers\Ppdb\Dapodik;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Daftarsiswa extends PpdbController {

    protected static $ROLE_ID = array(ROLEID_DAPODIK, ROLEID_DINAS, ROLEID_ADMIN, ROLEID_SYSADMIN);              

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

        $profil = $this->Msekolah->tcg_profilsekolah($sekolah_id);
        if (empty($profil)) {
            return $this->notauthorized();
        }
        $data['profilsekolah'] = $profil;

        //notifikasi tahapan
        $data['tahapan_aktif'] = $this->Mconfig->tcg_tahapan_pelaksanaan_aktif();
        $data['pengumuman'] = $this->Mconfig->tcg_pengumuman();

        $data['kabupaten'] = $this->Mconfig->tcg_kabupaten();

        $data['impersonasi_sekolah'] = $this->session->get("impersonasi_sekolah");
		$data['cek_waktusosialisasi'] = $this->Mconfig->tcg_cek_waktusosialisasi();
		$data['cek_waktuperbaikandata'] = $this->Mconfig->tcg_cek_waktuperbaikandata();

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
        $profil = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id);
        if ($profil == null) {
            print_json_error("Invalid userid");
        }

        $sekolah_id = $this->session->get("sekolah_id");
        if ($sekolah_id != $profil['sekolah_id']) {
            print_json_error("Bukan sekolah asal siswa");
        }

		//only can verify within the specified timeframe
		$cek_waktusosialisasi = $this->Mconfig->tcg_cek_waktusosialisasi();
        $cek_waktuperbaikandata = $this->Mconfig->tcg_cek_waktuperbaikandata();

        //debugging
        if (__DEBUGGING__) {
            $cek_waktusosialisasi = 1;
        }

        //hanya yang belum melakukan pendaftaran (kecuali dalam waktu sosialisasi)
        $jml_pendaftaran = $this->Msiswa->tcg_cek_pendaftaran($peserta_didik_id);
        
        $impersonasi_sekolah = $this->session->get("impersonasi_sekolah");
        if ($cek_waktusosialisasi != 1 && $cek_waktuperbaikandata != 1 && $profil['akses_ubah_data'] != 1 && $impersonasi_sekolah != 1 && $jml_pendaftaran > 0) {
            print_json_error("Sudah tidak diperbolehkan mengubah data DAPODIK.");
        }

        $updatedprofil = $data['profil'];
        //only save changed data
        $updated = array();
        foreach($updatedprofil as $key => $val) {
            //internally, decimal point is '.' not ','
            if ($key == 'lintang' || $key == 'bujur') {
                $val = str_replace(',', '.', $val);
            }
            $updated[$key] = $val;
        }

        if (empty($updated)) {
            print_json_error("Tidak ada data yang berubah");
        }

        //reset verifikasi status if necessary
        $keys = array_keys($updated);
        if(array_search('kode_wilayah', $keys) !== FALSE) {
            $updated['verifikasi_profil'] = 0;
        }

        if(array_search('lintang', $keys) !== FALSE || array_search('bujur', $keys) !== FALSE) {
            $updated['verifikasi_lokasi'] = 0;
        }
        
        if(array_search('nilai_semester', $keys) !== FALSE || array_search('nilai_kelulusan', $keys) !== FALSE || array_search('punya_nilai_un', $keys) !== FALSE || array_search('nilai_un', $keys) !== FALSE) {
            $updated['verifikasi_nilai'] = 0;
        }
        
        if(array_search('prestasi_skoring_id', $keys) !== FALSE || array_search('punya_prestasi', $keys) !== FALSE) {
            $updated['verifikasi_prestasi'] = 0;
        }
        
        if(array_search('punya_kip', $keys) !== FALSE || array_search('masuk_bdt', $keys) !== FALSE) {
            $updated['verifikasi_afirmasi'] = 0;
        }
        
        if(array_search('kebutuhan_khusus', $keys) !== FALSE) {
            $updated['verifikasi_inklusi'] = 0;
        }

        //update data siswa
        $detail = $this->Msiswa->tcg_update_siswa($peserta_didik_id, $updated);

        if ($detail == null)
            print_json_error("Tidak berhasil mengubah data siswa.");

        //audit trail
        audit_siswa($profil, "UBAH DATA", "Ubah data oleh Admin Dapodik an. " .$profil['nama'], array_keys($updated), $updated, $profil);

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