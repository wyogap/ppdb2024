<?php

namespace App\Controllers\Ppdb;

use App\Controllers\Ppdb\PpdbController;
use App\Libraries\QRCodeLibrary;
use App\Libraries\Uploader;
use App\Models\Ppdb\Mconfig;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use App\Models\Ppdb\Mdropdown;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Psr\Log\LoggerInterface;

class Siswa extends PpdbController {

    //only accessible to superadmin
    protected static $ROLE_ID = ROLEID_SISWA;      

    protected $Msiswa;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load model
        $this->Msiswa = new Mprofilsiswa();

    }

    function index() {
        $peserta_didik_id = $this->peserta_didik_id;
        if ($this->is_dinas) {
            $peserta_didik_id = $_GET["peserta_didik_id"] ?? null;
        }

        $tahun_ajaran_id = $this->tahun_ajaran_id;
        $upload_dokumen = $this->setting->get('upload_dokumen');

        $diterima = $this->session->get("diterima") ?? 0;
        $tutup_akses = $this->session->get("tutup_akses") ?? 1;
        
        //waktu pelaksanaan
        $waktudaftarulang = $this->Mconfig->tcg_waktudaftarulang();
        $waktupendaftaran = $this->Mconfig->tcg_waktupendaftaran();
        $waktusosialisasi = $this->Mconfig->tcg_waktusosialisasi();
        
        //flag: waktu daftar ulang
        if (empty($waktudaftarulang)) {
            $cek_waktudaftarulang = 0;
        }
        else {
            $cek_waktudaftarulang = ($waktudaftarulang['aktif'] == 1) ? 1 : 0;
        }

        //flag: waktu pendaftaran
        if (empty($waktupendaftaran)) {
            $cek_waktupendaftaran = 0;
        }
        else {
            $cek_waktupendaftaran = ($waktupendaftaran['aktif'] == 1) ? 1 : 0;
        }

        //flag: waktu sosialisasi
        if (empty($waktusosialisasi)) {
            $cek_waktusosialisasi = 0;
        }
        else {
            $cek_waktusosialisasi = ($waktusosialisasi['aktif'] == 1) ? 1 : 0;
        }

        //profil siswa
        $profil = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id);
        if (empty($profil)) {
            //invalid session. just re-login
            $this->session->setFlashdata("error", "Invalid session!");
            return redirect()->to(site_url() ."auth/logout");
        }

        $jenjang_id = 0;
        if ($profil['bentuk_sekolah'] == 'SD' || $profil['bentuk_sekolah'] == 'MI') {
            $jenjang_id = JENJANGID_SMP;
        }
        else if ($profil['bentuk_sekolah'] == 'TK' || $profil['bentuk_sekolah'] == 'RA' || $profil['bentuk_sekolah'] == 'KB') {
            $jenjang_id = JENJANGID_SD;
        }
        else {
            $jenjang_id = JENJANGID_TK;
        }
        $this->session->set("jenjang_aktif", $jenjang_id);

        //daftar pendaftaran
        $pendaftaran = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id);

        $cek_pendaftaran_aktif = (count($pendaftaran) > 0);
        $jumlahpendaftarannegeri = 0;
        $jumlahpendaftaranswasta = 0;
        foreach($pendaftaran as $row) {
            //jumlah pendaftaran
            if ($row['status_sekolah'] == 'N' && $row['pendaftaran']) {
                $jumlahpendaftarannegeri++;
            }
            if ($row['status_sekolah'] == 'S' && $row['pendaftaran']) {
                $jumlahpendaftaranswasta++;
            }
        }
        
        //pendaftaran diterima
        $pendaftaran_diterima = $this->Msiswa->tcg_pendaftaran_diterima($peserta_didik_id);
        $cek_pendaftaran_diterima = ($pendaftaran_diterima != null);
        // $cek_daftar_ulang = 0;
        // if ($pendaftaran_diterima != null) {
        //     $cek_daftar_ulang = $pendaftaran_diterima['status_daftar_ulang'];
        // }

        //profil siswa | profil status
        $kelengkapan_data = 1;
        if ($profil['konfirmasi_profil'] != 1 || $profil['verifikasi_profil'] == 2) { $kelengkapan_data = 0; }
        else if ($profil['konfirmasi_lokasi'] != 1 || $profil['verifikasi_lokasi'] == 2) { $kelengkapan_data = 0; }
        else if ($profil['konfirmasi_nilai'] != 1 || $profil['verifikasi_nilai'] == 2) { $kelengkapan_data = 0; }
        else if ($profil['konfirmasi_prestasi'] != 1 || $profil['verifikasi_prestasi'] == 2) { $kelengkapan_data = 0; }
        else if ($profil['konfirmasi_afirmasi'] != 1 || $profil['verifikasi_afirmasi'] == 2) { $kelengkapan_data = 0; }
        else if ($profil['konfirmasi_inklusi'] != 1 || $profil['verifikasi_inklusi'] == 2) { $kelengkapan_data = 0; }
        else if (empty($profil['nomor_kontak'])) { $kelengkapan_data = 0; }
        else if ($upload_dokumen && empty($profil['path_surat_pernyataan'])) { $kelengkapan_data = 0; };

        //daftar dokumen
        $dokumen = array();
        $jml_dokumen_tambahan = 0;

        $pernyataan_verifikasi = 0;
        $pernyataan_file = null;
        $pernyataan_tanggal = null;
        $verifikasi_dok = 1;
        $verifikasi_dokumen_tambahan = 1;

        if (!$upload_dokumen) {
            //no dok upload
            $pernyataan_verifikasi = 1;
            $pernyataan_file = "no-upload";
            $pernyataan_tanggal = "no-upload";
        }

        $dok_surat_pernyataan = null;

        $dokumen = $this->Msiswa->tcg_dokumenpendukung($peserta_didik_id);
        foreach($dokumen as $row) {
            $row['catatan'] = nl2br(trim($row['catatan']));
            if ($row['daftar_kelengkapan_id'] == DOCID_SUKET_KEBENARAN_DOK) {
                $dok_surat_pernyataan = $row;
            }
        }

        //verifikasi dokumen tambahan -> the metadata is already included in $dokumen
        $verifikasi_dokumen_tambahan = 1;

        foreach($dokumen as $row) {
            if ($row['tambahan'] != 1)  continue;

            $jml_dokumen_tambahan++;
            if ($row['verifikasi'] == 2) {
                $verifikasi_dokumen_tambahan = 2;
            }
            else if ($row['verifikasi'] == 0 && $verifikasi_dokumen_tambahan == 0) {
                $verifikasi_dokumen_tambahan = 0;
            }
        }

        //verifikasi suket kebenaran dok
        if (!empty($dok_surat_pernyataan)) {
            $pernyataan_verifikasi = $dok_surat_pernyataan['verifikasi'];
            $pernyataan_file = $dok_surat_pernyataan['path'];
            $pernyataan_tanggal = $dok_surat_pernyataan['tanggal_berkas'];    
        }
        else {
            $pernyataan_verifikasi = 1;
            $pernyataan_file = 1;
            $pernyataan_tanggal = '';    
        }
    
        //verifikasi dokumen
        if (!empty($dokumen[DOCID_AKTE]) && $dokumen[DOCID_AKTE]['verifikasi'] == 2) { $verifikasi_dok = 0; }
        else if (!empty($dokumen[DOCID_KK]) && $dokumen[DOCID_KK]['verifikasi'] == 2) { $verifikasi_dok = 0; }
        else if (!empty($dokumen[DOCID_SUKET_DOMISILI]) && $dokumen[DOCID_SUKET_DOMISILI]['verifikasi'] == 2) { $verifikasi_dok = 0; }
        else if (!empty($dokumen[DOCID_IJAZAH_SKL]) && $dokumen[DOCID_IJAZAH_SKL]['verifikasi'] == 2) { $verifikasi_dok = 0; }
        else if (!empty($dokumen[DOCID_HASIL_UN]) && $dokumen[DOCID_HASIL_UN]['verifikasi'] == 2) { $verifikasi_dok = 0; }
        else if (!empty($dokumen[DOCID_PRESTASI]) && $dokumen[DOCID_PRESTASI]['verifikasi'] == 2) { $verifikasi_dok = 0; }
        else if (!empty($dokumen[DOCID_KIP]) && $dokumen[DOCID_KIP]['verifikasi'] == 2) { $verifikasi_dok = 0; }
        else if (!empty($dokumen[DOCID_SUKET_BDT]) && $dokumen[DOCID_SUKET_BDT]['verifikasi'] == 2) { $verifikasi_dok = 0; }
        else if (!empty($dokumen[DOCID_SUKET_INKLUSI]) && $dokumen[DOCID_SUKET_INKLUSI]['verifikasi'] == 2) { $verifikasi_dok = 0; }
        else if ($verifikasi_dokumen_tambahan == 2) { $verifikasi_dok = 2; }

        //data afirmasi
        // $afirmasi = $this->Msiswa->tcg_get_dataafirmasi($profil['nik']);
        // if (!$afirmasi) {
        //     $profil['masuk_bdt'] = 0;
        //     $profil['sumber_bdt'] = null;
        // }
        // else {
        //     $profil['masuk_bdt'] = 1;
        //     $profil['sumber_bdt'] = $afirmasi['sources'];
        // }

        //debugging
        // if (__DEBUGGING__) {
        //     $profil['masuk_bdt'] = 1;
        //     $profil['sumber_bdt'] = "TestDB";
        // }

        //Output
        $data = array();

        //tab yang aktif
        //belum waktu_pendaftaran
        if ($diterima) {
            $data['aktif'] = 'daftarulang';
        } 
        else if ($cek_waktusosialisasi) {
            $data['aktif'] = "kelengkapan";
        }
        else if (!$cek_waktupendaftaran && !$cek_waktudaftarulang) {
            $data['aktif'] = "kelengkapan";
        }
        //waktu_daftar_ulang and pendaftaran_aktif_diterima, go to daftar-ulang
        else if ($cek_waktudaftarulang && $cek_pendaftaran_diterima) {
            $data['aktif'] = 'daftarulang';
        }
        //kelengkapan_data!=1 atau verifikasi_dok!=1, go to kelengkapan-data
        else if (!$kelengkapan_data || !$verifikasi_dok) {
            $data['aktif'] = 'kelengkapan';
        }
        //pendaftaran_aktif, go to hasil-pendaftaran
        else if ($cek_pendaftaran_aktif) {
            $data['aktif'] = 'hasil';
        }
        //data-lengkap and dok-terverifikasi and no pendaftaran_aktif, go to pendaftaran
        else if ($cek_waktupendaftaran && $kelengkapan_data && $verifikasi_dok && !$cek_pendaftaran_aktif) {
            $data['aktif'] = 'pendaftaran';
        }
        //default
        else {
            $data['aktif'] = 'kelengkapan';
        }

        //upload dok?
        $data['flag_upload_dokumen'] = $upload_dokumen;
        
        //notifikasi tahapan
        $data['tahapan_aktif'] = $this->Mconfig->tcg_tahapan_pelaksanaan_aktif();
        $data['pengumuman'] = $this->Mconfig->tcg_pengumuman();

        //data for view
        $data['peserta_didik_id'] = $peserta_didik_id;
        $data['tahun_ajaran'] = $tahun_ajaran_id;
        $data['profilsiswa'] = $profil;

        //dokumen pendukung
        $data['dokumen'] = $dokumen;   
        $data['jml_dokumen_tambahan'] = $jml_dokumen_tambahan;
        $data['verifikasi_dokumen'] = $verifikasi_dok;
        //$data['dokumen_tambahan'] = $dokumen_tambahan;
        $data['verifikasi_dokumen_tambahan'] = $verifikasi_dokumen_tambahan;

        $data['pernyataan_verifikasi'] = $pernyataan_verifikasi;
        $data['pernyataan_file'] = $pernyataan_file;
        $data['pernyataan_tanggal'] = $pernyataan_tanggal;
                  
		$data['waktupendaftaran'] = $waktupendaftaran;
		$data['cek_waktupendaftaran'] = $cek_waktupendaftaran;
		$data['waktudaftarulang'] = $waktudaftarulang;
		$data['cek_waktudaftarulang'] = $cek_waktudaftarulang;

        $data['waktusosialisasi'] = $waktusosialisasi;
        $data['cek_waktusosialisasi'] = $cek_waktusosialisasi;

        $data['satu_zonasi_satu_jalur'] = $this->setting->get('satu_zonasi_satu_jalur');

		$data['jumlahpendaftaran'] = count($pendaftaran);
		$data['jumlahpendaftarannegeri'] = $jumlahpendaftarannegeri;
		$data['jumlahpendaftaranswasta'] = $jumlahpendaftaranswasta;

		$data['pendaftaranditerima'] = $pendaftaran_diterima;

        $data['daftarskoring_prestasi'] = $this->Mconfig->tcg_lookup_daftarskoring_prestasi();
        $data['daftarskoring_organisasi'] = $this->Mconfig->tcg_lookup_daftarskoring_organisasi();
        $data['daftarskoring_akademik'] = $this->Mconfig->tcg_lookup_daftarskoring_akademik();

        # PENDAFTARAN
        $data['tutup_akses'] = ($tutup_akses || $diterima) ? 1 : 0;
        $data['diterima'] = $diterima;

        $batasan['hapus_pendaftaran'] = $profil['hapus_pendaftaran'];
        $batasan['ubah_pilihan'] = $profil['ubah_pilihan'];
        $batasan['ubah_sekolah'] = $profil['ubah_sekolah'];
        $batasan['ubah_jalur'] = $profil['ubah_jalur'];
        $data['batasansiswa'] = $batasan;

        $data['batasanperubahan'] = $this->Mconfig->tcg_batasanperubahan();
        //$data['batasansiswa'] = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);

        $data['batasanusia'] = $this->Mconfig->tcg_batasanusia($jenjang_id);
        $data['cek_batasanusia'] = ($data['batasanusia']['maksimal_tanggal_lahir'] < $profil['tanggal_lahir'] && $data['batasanusia']['minimal_tanggal_lahir'] > $profil['tanggal_lahir']) ? 1 : 0;

        $kebutuhan_khusus = 1;
        if (empty($profil['kebutuhan_khusus']) || $profil['kebutuhan_khusus']=="0" || $profil['kebutuhan_khusus']=='Tidak ada') {
            $kebutuhan_khusus = 0;
        }
        
        if ($kebutuhan_khusus) {
            //kebutuhan khusus tidak dibatasi usia
            $data['cek_batasanusia'] = 1;
        }

        if (!$data['cek_batasanusia'] && !$kebutuhan_khusus) {
            //di luar batasan usia dan bukan kebutuhan khusus
            $data['daftarpenerapan'] = array();
        }
        else {
            //dalam batasan usia atau kebutuhan khusus (kebutuhan khusus tidak dibatasi usia)
            $data['daftarpenerapan'] = $this->Msiswa->tcg_daftarpenerapan($profil['kode_wilayah'], $kebutuhan_khusus, $profil['masuk_bdt']);
        }
        
        $daftarpilihan = $this->Mconfig->tcg_daftarpilihan();
        $data['maxpilihan'] = count($daftarpilihan);
        $data['maxpilihanumum'] = 0;
        $data['maxpilihannegeri'] = 0;
        $data['maxpilihanswasta'] = 0;
        foreach($daftarpilihan as $row) {
            if ($row['sekolah_negeri'] == 1 && $row['sekolah_swasta'] == 1) {
                $data['maxpilihanumum']++;
            }
            else if ($row['sekolah_negeri'] == 1) {
                $data['maxpilihannegeri']++;
            }
            else if ($row['sekolah_swasta'] == 1) {
                $data['maxpilihanswasta']++;
            }
        }
            
        if ($data['satu_zonasi_satu_jalur'] == 1) {
            $data['pendaftaran_dalam_zonasi'] = $this->Msiswa->tcg_jalur_pendaftaran_dalam_zonasi($peserta_didik_id, $tahun_ajaran_id);
        }

        ## DAFTAR PENDAFTARAN
        //berkas dok  
        $pendaftaran = update_daftarpendaftaran($pendaftaran, $data['batasanperubahan'], $data['batasansiswa']);     

        $data['daftarpendaftaran'] = $pendaftaran;

        $data['use_select2'] = 1;
        $data['use_leaflet'] = 1;
        $data['use_datatable'] = 1;

        //debugging
        if (__DEBUGGING__) {
            $data['cek_waktupendaftaran'] = 1;
            $data['cek_waktusosialisasi'] = 1;
            $data['cek_waktudaftarulang'] = 1;
            //$data['kebutuhan_khusus'] = 1;
            //$data['satu_zonasi_satu_jalur'] = 0;
            $data['profilsiswa']['tutup_akses'] = 0;

            //$data['aktif'] = 'pendaftaran';
        }
        //end debugging

        $data['page_title'] = 'Profil Siswa';
        $this->smarty->render('ppdb/siswa/ppdbsiswa.tpl', $data);
    }

	function suratpernyataan() {
		$peserta_didik_id = $this->session->get("peserta_didik_id");
        if ($this->session->get('peran_id') == 4) {
            $peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 
        }

        $username = $this->session->get("user_name");
		$peran_id = $this->session->get("peran_id");
		$nisn = $this->session->get("nisn");
		
        $qrcode = new QRCodeLibrary();
		$config['cacheable'] = true; //boolean, the default is true
        // $config['cachedir'] = './qrcode/'; //string, the default is application/cache/
        // $config['errorlog'] = './qrcode/'; //string, the default is application/logs/
        // $config['imagedir'] = FCPATH .'qrcodes/'; //direktori penyimpanan qr code
        $config['quality'] = true; //boolean, the default is true
        $config['size'] = '1024'; //interger, the default is 1024
        $config['black'] = array(224,255,255); // array, default is array(255,255,255)
        $config['white'] = array(70,130,180); // array, default is array(0,0,0)
        $qrcode->initialize($config);
 
        $params['data'] = $peserta_didik_id.",".$username.",".$peran_id.",".$nisn; //data yang akan di jadikan QR CODE
        $params['level'] = 'M'; //H=High
        $params['size'] = 10;
        $params['savename'] = $peserta_didik_id.'.png';
        $qrcode->generate($params); // fungsi untuk generate QR CODE
		
        $data['peserta_didik_id'] = $peserta_didik_id;
		$data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id);
        //$data['daftarpendaftaran'] = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id);
        
        $tgl_mulai_pendaftaran = gmdate('Y-m-d');
        $result = $this->Mconfig->tcg_waktupendaftaran();
        $tgl = gmdate( 'Y-m-d', strtotime($result['tanggal_mulai_aktif']));
        if ($tgl > $tgl_mulai_pendaftaran) {
            $tgl_mulai_pendaftaran = $tgl;
        }

        $data['tanggal_pernyataan'] = $tgl_mulai_pendaftaran;

        $view = \Config\Services::renderer();
		$html = $view->setData($data)->render('ppdb/siswa/_suratpernyataan');
		
		$dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("SuratPernyataan.pdf", array("Attachment"=>0));
        exit(); 
	}

	function suratkesanggupan() {
		$peserta_didik_id = $this->session->get("peserta_didik_id");
        if ($this->session->get('peran_id') == 4) {
            $peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 
        }

        $username = $this->session->get("user_name");
		$peran_id = $this->session->get("peran_id");
		$nisn = $this->session->get("nisn");
		
        $qrcode = new QRCodeLibrary();
		$config['cacheable'] = true; //boolean, the default is true
        // $config['cachedir'] = './qrcode/'; //string, the default is application/cache/
        // $config['errorlog'] = './qrcode/'; //string, the default is application/logs/
        // $config['imagedir'] = FCPATH .'qrcodes/'; //direktori penyimpanan qr code
        $config['quality'] = true; //boolean, the default is true
        $config['size'] = '1024'; //interger, the default is 1024
        $config['black'] = array(224,255,255); // array, default is array(255,255,255)
        $config['white'] = array(70,130,180); // array, default is array(0,0,0)
        $qrcode->initialize($config);
 
        $params['data'] = $peserta_didik_id.",".$username.",".$peran_id.",".$nisn; //data yang akan di jadikan QR CODE
        $params['level'] = 'M'; //H=High
        $params['size'] = 10;
        $params['savename'] = $peserta_didik_id.'.png';
        $qrcode->generate($params); // fungsi untuk generate QR CODE
		
        $data['peserta_didik_id'] = $peserta_didik_id;
		$data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id);
        //$data['daftarpendaftaran'] = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id);
        
        $tgl_mulai_pendaftaran = gmdate('Y-m-d');
        $result = $this->Mconfig->tcg_waktupendaftaran();
        $tgl = gmdate( 'Y-m-d', strtotime($result['tanggal_mulai_aktif']));
        if ($tgl > $tgl_mulai_pendaftaran) {
            $tgl_mulai_pendaftaran = $tgl;
        }

        $data['tanggal_pernyataan'] = $tgl_mulai_pendaftaran;

        $view = \Config\Services::renderer();
		$html = $view->setData($data)->render('ppdb/siswa/_suratkesanggupan');
		
		$dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("SuratKesanggupan.pdf", array("Attachment"=>0));
        exit(); 
	}

    //riwayat verifikasi (view only)
	function riwayat() {
        $peserta_didik_id = $this->session->get("peserta_didik_id");
        if ($this->session->get('peran_id') == 4) {
            $peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 
        }

		$action = $_POST["action"] ?? null; 
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Msiswa->tcg_riwayat_verifikasi($peserta_didik_id); 
			echo json_encode($data);	
		}
		else {
			$data['error'] = "not-implemented";
			echo json_encode($data);	
		}
	}
    
    // //daftar prestasi (view/edit/add/delete)
    // function prestasi() {
    //     $peserta_didik_id = $this->session->get("user_id");
	// 	$tahun_ajaran_id = $this->tahun_ajaran_id;

    //     $flag_upload_dokumen = $this->setting->get('upload_dokumen');
    //     $mdropdown = new Mconfig();

    //     $action = $_POST["action"] ?? null; 
	// 	if (empty($action) || $action=='view') {
    //         $data['data'] = $this->Msiswa->tcg_daftar_prestasi($peserta_didik_id)->getResultArray();
    //         //var_dump($data['data']);
    //         foreach($data['data'] as $idx=>$row) {
    //             if (!empty($row['catatan'])) {
    //                 $data['data'][$idx]['catatan'] = nl2br(trim($row['catatan']));
    //             }
    //             else {
    //                 $data['data'][$idx]['catatan'] = "";
    //             }
    //         }

    //         $data['options']['skoring_id'] = $mdropdown->tcg_select_prestasi($tahun_ajaran_id)->getResultArray();

    //         $files = $this->Msiswa->tcg_daftar_prestasi_files($peserta_didik_id)->getResultArray();
    //         $data['files']['files'] = array();
    //         foreach($files as $row) {
    //             $id = $row['id'];
    //             $data['files']['files'][$id] = $row;
    //             $data['files']['files'][$id]['file_path'] = base_url(). $row['path'];
    //             $data['files']['files'][$id]['web_path'] = base_url(). $row['web_path'];
    //             $data['files']['files'][$id]['thumbnail_path'] = base_url(). $row['thumbnail_path'];
    //         }

    //         echo json_encode($data);
    //     }
	// 	else if ($action=='edit'){
	// 		$values = $_POST["data"] ?? null; 
    //         if ($values == null) {
    //             $data['data'] = array();
    //             $data['error'] = "no-data";
    //             echo json_encode($data);
    //             return;
    //         }

    //         $error_msg = "";
	// 		foreach ($values as $key => $valuepair) {
    //             if (empty($valuepair['dokumen_pendukung'])) {
    //                 continue;
    //             };
    //             $doc_id = $valuepair['dokumen_pendukung'];

    //             $status = $this->Msiswa->tcg_ubah_dokumen_prestasi($key, $doc_id);
    //             if ($status == 1) {
    //                 $data['data'] = $this->Msiswa->tcg_prestasi_detil($peserta_didik_id, $key)->getResultArray(); 
    //             }
    //             else {
    //                 $data['data'] = "error";
    //             }
	// 		}

    //         echo json_encode($data);	
    //     }
    //     else if ($action=='remove') {
	// 		$values = $_POST["data"] ?? null; 
    //         if ($values == null) {
    //             $data['data'] = array();
    //             $data['error'] = "no-data";
    //             echo json_encode($data);
    //             return;
    //         }

    //         $error_msg = "";
	// 		foreach ($values as $key => $valuepair) {
    //             $data['data'] = $this->Msiswa->tcg_hapus_prestasi($peserta_didik_id, $key);
	// 		}

    //         $data['data'] = array(); 
	// 		echo json_encode($data);	
    //     }
    //     else if ($action=='create') {
    //         $values = $_POST["data"] ?? null; 
    //         if ($values == null) {
    //             $data['data'] = array();
    //             $data['error'] = "no-data";
    //             echo json_encode($data);
    //             return;
    //         }

    //         if (empty($values[0]['skoring_id']) || empty($values[0]['uraian'])) {
    //             $data['error'] = "Data wajib belum diisi";
    //             echo json_encode($data);
    //             return;
    //         }

    //         if ($flag_upload_dokumen && empty($values[0]['dokumen_pendukung'])) {
    //             $data['error'] = "Data wajib belum diisi";
    //             echo json_encode($data);
    //             return;
    //         }

    //         $key = $this->Msiswa->tcg_prestasi_baru($peserta_didik_id, $values[0]);
    //         if ($key == 0) {
    //             $data['error'] = $this->Msiswa->error();
    //         } else {
    //             $data['data'] = $this->Msiswa->tcg_prestasi_detil($peserta_didik_id, $key)->getResultArray(); 
    //         }

    //         echo json_encode($data);
    //     }
    //     else if ($action == "upload") {

    //         $kelengkapan_id = 8;

    //         $uploader = new Uploader();
    //         $fileObj = $uploader->upload($_FILES['upload']);

    //         $data = array();
    //         if(!empty($fileObj['error'])) {
    //             $data['error'] = $fileObj['error'];
    //         } else {
    //             $data = array("data"=>array(),"files"=>array("files"=>array($fileObj['id']=>$fileObj)),"upload"=>array("id"=>$fileObj['id']));
    //         }

    //         echo json_encode($data);
    //         return;
    //     }   

    // } 

    function updateprofil() {
        $data = $this->request->getPost("data");
        if (empty($data))   
            print_json_error("Invalid data");

        $peserta_didik_id = $this->session->get('peserta_didik_id');

        //$keys = array_keys($data);

        // $toggle = false;
        // $colname = "";
        // if (count($keys) == 1 && substr($keys[0],0,11) == "konfirmasi_") {
        //     $toggle = true;
        //     $colname = $keys[0];
        // }

        // //oldvalues
        // $oldvalues = null;
        // if (!$toggle) {
        //     $oldvalues = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id);
        //     if ($oldvalues == null) {
        //         print_json_error("Invalid userid");
        //     }
        // }

        $oldvalues = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id);
        if ($oldvalues == null) {
            print_json_error("Invalid userid");
        }

        //kalau sudah diverifikasi, kunci profil
        if ($oldvalues['diverifikasi']) {
            print_json_error("Sudah diverifikasi di sekolah tujuan. Silahkan menghubungi sekolah tujuan untuk perbaikan data.");
        }

        //internally, decimal point is '.' not ','
        foreach($data as $key => $val) {
            if ($key == 'nilai_semester' || $key == 'nilai_kelulusan' || $key == 'nilai_bin' || $key == 'nilai_mat' || $key == 'nilai_ipa') {
                $val = str_replace(',', '.', $val);
                $data[$key] = $val;
            }
        }

        //only updated val
        $updated = array();
        foreach ($data as $k => $v) {
            $old = $oldvalues[$k];
            if ($old != $v) {
                $updated[$k] = $v;
            }
        }
    
        // if ($toggle) {
        //     $updated = $data;
        // }
        // else {
        //     foreach ($data as $k => $v) {
        //         $old = $oldvalues[$k];
        //         if ($old != $v) {
        //             $updated[$k] = $v;
        //         }
        //     }
        // }

        //reset verifikasi status if necessary
        $keys = array_keys($updated);
        if(array_search('konfirmasi_profil', $keys) !== FALSE || array_search('kode_wilayah', $keys) !== FALSE) {
            $updated['verifikasi_profil'] = 0;
        }

        if(array_search('konfirmasi_lokasi', $keys) !== FALSE || array_search('lintang', $keys) !== FALSE || array_search('bujur', $keys) !== FALSE) {
            $updated['verifikasi_lokasi'] = 0;
        }
        
        if(array_search('konfirmasi_nilai', $keys) !== FALSE || array_search('nilai_semester', $keys) !== FALSE 
                || array_search('nilai_kelulusan', $keys) !== FALSE || array_search('punya_nilai_un', $keys) !== FALSE 
                || array_search('nilai_un', $keys) !== FALSE || array_search('akademik_skoring_id', $keys) !== FALSE) {
            $updated['verifikasi_nilai'] = 0;
        }
        
        if(array_search('konfirmasi_prestasi', $keys) !== FALSE || array_search('punya_prestasi', $keys) !== FALSE 
                || array_search('prestasi_skoring_id', $keys) !== FALSE) {
            $updated['verifikasi_prestasi'] = 0;
        }
        
        if(array_search('konfirmasi_afirmasi', $keys) !== FALSE || array_search('punya_kip', $keys) !== FALSE 
                || array_search('masuk_bdt', $keys) !== FALSE) {
            $updated['verifikasi_afirmasi'] = 0;
        }
        
        if(array_search('konfirmasi_inklusi', $keys) !== FALSE || array_search('kebutuhan_khusus', $keys) !== FALSE) {
            $updated['verifikasi_inklusi'] = 0;
        }
        
        //catatan verifikasi
        $message = array();
        if (isset($updated['verifikasi_profil']) && $updated['verifikasi_profil'] != $oldvalues['verifikasi_profil']) {
            $message[] = "Perubahan data Identitas Siswa."; 
        }
        if (isset($updated['verifikasi_lokasi']) && $updated['verifikasi_lokasi'] != $oldvalues['verifikasi_lokasi']) {
            $message[] = "Perubahan data Lokasi."; 
        }
        if (isset($updated['verifikasi_nilai']) && $updated['verifikasi_nilai'] != $oldvalues['verifikasi_nilai']) {
            $message[] = "Perubahan data Nilai/Prestasi Akademik."; 
        }
        if (isset($updated['verifikasi_prestasi']) && $updated['verifikasi_prestasi'] != $oldvalues['verifikasi_prestasi']) {
            $message[] = "Perubahan data Pengalaman Organisasi/Kejuaraan."; 
        }
        if (isset($updated['verifikasi_afirmasi']) && $updated['verifikasi_afirmasi'] != $oldvalues['verifikasi_afirmasi']) {
            $message[] = "Perubahan data Afirmasi."; 
        }
        if (isset($updated['verifikasi_inklusi']) && $updated['verifikasi_inklusi'] != $oldvalues['verifikasi_inklusi']) {
            $message[] = "Perubahan data Inklusi."; 
        }

        if (count($message)>0) {
            //recalc status kelengkapan berkas
            //$status_verifikasi = $this->Msiswa->tcg_update_kelengkapanberkas($peserta_didik_id);
            $status_verifikasi = 0;

            //riwayat verifikasi
            $catatan_verifikasi = "";
            foreach ($message as $val) {
                if ($catatan_verifikasi != "")  $catatan_verifikasi .= "<br>";
                $catatan_verifikasi .= $val;
            }
            
            //update riwayat verifikasi
            $key = $this->Msiswa->tcg_tambah_riwayatverifikasi($peserta_didik_id, $status_verifikasi, $catatan_verifikasi);
        }

        //update profil siswa
        $detail = $this->Msiswa->tcg_update_siswa($peserta_didik_id, $updated);
        if ($detail == null)
            print_json_error("Tidak berhasil mengubah data siswa.");

        //audit trail
        audit_siswa($oldvalues, "UPDATE PROFIL", "Ubah data siswa", $keys, $updated, $oldvalues);
        // if ($toggle) {
        //     //audit_siswa($peserta_didik_id, "UPDATE PROFIL", "Toggle status " .$colname, $colname, $data[$colname], null);
        // }
        // else {
        //     audit_siswa($oldvalues, "UPDATE PROFIL", "Ubah data siswa", $keys, $updated, $oldvalues);
        // }

        print_json_output($detail);
    }

    //upload dokumen
    function upload() {

        $diterima = $this->session->get("diterima");
        if ($diterima) {
            print_json_error("Sudah diterima.");
        }

        $tutup_akses = $this->session->get("tutup_akses");
        if ($tutup_akses) {
            print_json_error("Akses ditutup.");
        }

        $peserta_didik_id = $this->session->get("peserta_didik_id");
        $doc_id = $this->request->getPostGet("doc_id");
        if (empty($doc_id)) {
            print_json_error("Kode kelengkapan dokumen tidak dikenal!");
        }

        //upload file
        $uploader = new \App\Libraries\Uploader();
        $fileObj = $uploader->upload($_FILES['upload']);

        //store dok metadata
        $toreplace = 1;
        $dok_prestasi = ($doc_id == DOCID_PRESTASI);
        $this->Msiswa->tcg_simpan_dokumenpendukung($peserta_didik_id, $fileObj['id'], $doc_id, $toreplace, $dok_prestasi);

        //audit trail
        $nama_dok = $this->Msiswa->tcg_nama_dokumen($doc_id);
        audit_siswa($peserta_didik_id, "UPLOAD DOK", "Upload dokumen " .$nama_dok, "doc_id,upload_id", "" .$doc_id. "," .$fileObj['id']. "", null);

        $data = array("data"=>array(),"files"=>array("files"=>array($fileObj['id']=>$fileObj)),"upload"=>array("id"=>$fileObj['id']));

        print_json_output($data);  
    }

    //upload dokumen
    function hapusupload() {
        $peserta_didik_id = $this->session->get("peserta_didik_id");
        $doc_id = $this->request->getPostGet("doc_id");
        if (empty($doc_id)) {
            print_json_error("Kode kelengkapan dokumen tidak dikenal!");
        }

        $this->Msiswa->tcg_hapus_dokumenpendukung($peserta_didik_id, $doc_id);

        //audit trail
        $nama_dok = $this->Msiswa->tcg_nama_dokumen($doc_id);
        audit_siswa($peserta_didik_id, "HAPUS DOK", "Hapus dokumen " .$nama_dok, "doc_id", $doc_id, null);

        print_json_output(array());  
    }
    
    //pilihan untuk perubahan
    function pilihan() {
        //ubahpilihan | ubahsekolah | ubahjalur | jenis_pilihan | sekolah
        $tipe = $this->request->getPostGet("tipe");
        $peserta_didik_id = $this->session->get('peserta_didik_id');

        $data = null;
        if ($tipe == 'jenis_pilihan') {
            //daftar jenis pilihan
            $penerapan_id = $this->request->getPostGet("penerapan_id");
            $data = $this->Msiswa->tcg_jenispilihan($peserta_didik_id, $penerapan_id);
            if ($data == null) {
                print_json_error("Tidak ada pilihan yang bisa dipakai.");
            }        
        }
        else if ($tipe == 'sekolah') {
            //daftar sekolah sesuai penerapan dan jenis-pilihan
            $penerapan_id = $this->request->getPostGet("penerapan_id");
            $jenis_pilihan = $this->request->getPostGet("jenis_pilihan");
            $data = $this->Msiswa->tcg_pilihansekolah($peserta_didik_id, $penerapan_id, $jenis_pilihan);
        }
        else if ($tipe == 'ubahpilihan') {
            //daftar jenis-pilihan selain pilihan sekarang
            $pendaftaran_id = $this->request->getPostGet("pendaftaran_id");
            $data = $this->Msiswa->tcg_jenispilihan_perubahan($peserta_didik_id, $pendaftaran_id);
        }
        else if ($tipe == 'ubahsekolah') {
            //daftar sekolah selain sekolah sekarang
            $pendaftaran_id = $this->request->getPostGet("pendaftaran_id");
            $pendaftaran = $this->Msiswa->tcg_pendaftaran($peserta_didik_id, $pendaftaran_id);
            if ($pendaftaran == null) {
                print_json_error("Data pendaftaran tidak ditemukan");
            }

            $penerapan_id = $pendaftaran['penerapan_id'];
            $jenis_pilihan = $pendaftaran['jenis_pilihan'];
            $data = $this->Msiswa->tcg_pilihansekolah($peserta_didik_id, $penerapan_id, $jenis_pilihan);
            if ($data == null) {
                print_json_error("Tidak berhasil mendapatkan data pilihan.");
            }

            //remove existing sekolah
            $sekolah_id_lama = $pendaftaran['sekolah_id'];
            foreach($data as $key => $value) {
                if ($value['sekolah_id'] == $sekolah_id_lama) {
                    unset($data[$key]);
                    break;
                }
            }
        }
        else if ($tipe == 'ubahjalur') {
            $pendaftaran_id = $this->request->getPostGet("pendaftaran_id");

            //penerapan yang lama
            $pendaftaran = $this->Msiswa->tcg_pendaftaran_detil($peserta_didik_id, $pendaftaran_id);
            if ($pendaftaran == null) {
                print_json_error("Data pendaftaran tidak ditemukan");
            }
            $penerapan_id = $pendaftaran['penerapan_id'];
            $parent_penerapan_id = $pendaftaran['parent_penerapan_id'];

            //profil siswa
            $profil = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);
            if ($profil == null) {
                return null;
            }
    
            //tidak dalam zonasi. daftar semua penerapan kecuali penerapan awal
            $tanggal_lahir = $profil['tanggal_lahir'];
            $kode_wilayah = $profil['kode_wilayah'];
    
            $kebutuhan_khusus = 1;
            if (empty($profil['kebutuhan_khusus']) || $profil['kebutuhan_khusus']=="0" || $profil['kebutuhan_khusus']=='Tidak ada') {
                $kebutuhan_khusus = 0;
            }
    
            if ($parent_penerapan_id) {
                $data = $this->Msiswa->tcg_daftarpenerapan($kode_wilayah, $kebutuhan_khusus, $profil['masuk_bdt'], $parent_penerapan_id);
            }
            else {
                $data = $this->Msiswa->tcg_daftarpenerapan($kode_wilayah, $kebutuhan_khusus, $profil['masuk_bdt'], $penerapan_id);
            }
            
            //check kuota di sekolah tujuan
            $kuota = $this->Msiswa->tcg_kuotasekolah($pendaftaran['sekolah_id']);
            if ($kuota == null) {
                print_json_error("Tidak ada jalur pendaftaran aktif di sekolah ini");
            }

            $lookup = [];
            foreach($kuota as $k) {
                $lookup[ $k['penerapan_id'] ] = $k['kuota'];
            }

            $valid = array();
            foreach($data as $p) {
                $penerapan_id = $p['penerapan_id'];
                if (!empty($lookup[$penerapan_id])) {
                    $valid[] = $p;
                }
            }

            $data = $valid;

            // //remove parent penerapan-id as well
            // $penerapan = array();
            // foreach ($data as $k => $v) {
            //     if ($v['penerapan_id'] != $penerapan_id && $v['penerapan_id'] != $parent_penerapan_id) {
            //        $penerapan[] = $v;
            //     }
            // }

            // $data = $penerapan;
        }
        else {
            print_json_error("not-implemented");
        }

        if ($data == null) {
            print_json_error("Tidak berhasil mendapatkan data pilihan.");
        }

        print_json_output($data);
    }

    //ubah pendaftaran
    function ubah() {
        //ubahpilihan | ubahsekolah | ubahjalur 
        $tipe = $this->request->getPostGet("tipe");
        $pendaftaran_id = $this->request->getPostGet("pendaftaran_id");
        $peserta_didik_id = $this->session->get('peserta_didik_id');

        $diterima = $this->session->get("diterima");
        if ($diterima) {
            print_json_error("Sudah diterima.");
        }

        $tutup_akses = $this->session->get("tutup_akses");
        if ($tutup_akses) {
            print_json_error("Akses ditutup.");
        }

        $pendaftaran_lama = $this->Msiswa->tcg_pendaftaran_detil($peserta_didik_id, $pendaftaran_id);
        $audit_action_type = "";
        $audit_action_desc = '';
        $nama = $pendaftaran_lama['nama'];

		$batasanperubahan = $this->Mconfig->tcg_batasanperubahan();
		$batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);

        if ($tipe == 'ubahpilihan') {
            if ($batasansiswa['ubah_pilihan'] >= $batasanperubahan['ubah_pilihan']) {
                print_json_error("Data tidak tersimpan. Sudah melebihi batasan yang diperbolehkan.");
            }

            $audit_action_type = "UBAH PILIHAN";
            $audit_action_desc = "Ubah urutan pendaftaran (jenis pilihan) an. " .$nama;
            $audit_keys = array("jenis_pilihan","label_jenis_pilihan");

            //ubah data
            $jenis_pilihan_baru = $this->request->getPostGet("jenis_pilihan_baru");

            $data = $this->Msiswa->tcg_ubah_jenispilihan($peserta_didik_id, $pendaftaran_id, $jenis_pilihan_baru);

            $batasansiswa['ubah_pilihan']++;
        }
        else if ($tipe == 'ubahsekolah') {
            if ($batasansiswa['ubah_sekolah'] >= $batasanperubahan['ubah_sekolah']) {
                print_json_error("Data tidak tersimpan. Sudah melebihi batasan yang diperbolehkan.");
            }

            $audit_action_type = "UBAH SEKOLAH";
            $audit_action_desc = "Ubah pilihan sekolah an. " .$nama;
            $audit_keys = array("sekolah_id","sekolah");
            
            //daftar jenis-pilihan selain pilihan sekarang
            $sekolah_id_baru = $this->request->getPostGet("sekolah_id_baru");

            //cek di sekolah tsb
            $penerapan_id = $pendaftaran_lama['penerapan_id'];
            $existing = $this->Msiswa->tcg_cek_pendaftaran_sekolah($peserta_didik_id, $penerapan_id, $sekolah_id_baru);
			if ($existing > 0) {
                print_json_error("Data tidak tersimpan. Sudah mendaftar di sekolah yang dipilih dengan jalur yang sama.");
			}

            $data = $this->Msiswa->tcg_ubah_pilihansekolah($peserta_didik_id, $pendaftaran_id, $sekolah_id_baru);

            $batasansiswa['ubah_sekolah']++;
        }
        else if ($tipe == 'ubahjalur') {
            if ($batasansiswa['ubah_jalur'] >= $batasanperubahan['ubah_jalur']) {
                print_json_error("Data tidak tersimpan. Sudah melebihi batasan yang diperbolehkan.");
            }

            $audit_action_type = "UBAH JALUR";
            $audit_action_desc = "Ubah jalur pendaftaran an. " .$nama;
            $audit_keys = array("penerapan_id","jalur");

            //daftar jenis-pilihan selain pilihan sekarang
            $penerapan_id_baru = $this->request->getPostGet("penerapan_id_baru");

            //cek di sekolah tsb
            $sekolah_id = $pendaftaran_lama['sekolah_id'];
            $existing = $this->Msiswa->tcg_cek_pendaftaran_sekolah($peserta_didik_id, $penerapan_id_baru, $sekolah_id);
			if ($existing > 0) {
                print_json_error("Data tidak tersimpan. Sudah mendaftar di sekolah yang dipilih dengan jalur yang sama.");
			}

            $data = $this->Msiswa->tcg_ubah_jalur($peserta_didik_id, $pendaftaran_id, $penerapan_id_baru);

            $batasansiswa['ubah_jalur']++;
        }
        else {
            print_json_error("not-implemented");
        }

        if ($data == null) {
            print_json_error("Tidak berhasil mendapatkan data pilihan.");
        }

        //audit trail
        audit_pendaftaran($pendaftaran_lama, $audit_action_type, $audit_action_desc, $audit_keys, $data, $pendaftaran_lama);

        //get list of existing pendaftaran
        $data = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id);

        //update with additional data
        $batasanperubahan = $this->Mconfig->tcg_batasanperubahan();
        // $batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);

        $data = update_daftarpendaftaran($data, $batasanperubahan, $batasansiswa);
        
        print_json_output($data);
    }

    //lakukan pendaftaran
    function daftar() {
		$peserta_didik_id = $this->session->get("peserta_didik_id");
		$sekolah_id = $this->request->getPostGet("sekolah_id");
		$penerapan_id = $this->request->getPostGet("penerapan_id");
		$jenis_pilihan = $this->request->getPostGet("jenis_pilihan");

        $diterima = $this->session->get("diterima");
        if ($diterima) {
            print_json_error("Sudah diterima.");
        }

        $tutup_akses = $this->session->get("tutup_akses");
        if ($tutup_akses) {
            print_json_error("Akses ditutup.");
        }

        //simpan dokumen pendukung tambahan
		foreach($_POST as $key => $value)
		{
			if (substr($key, 0, 8) == "dokumen_")
			{
				$arr = explode("_", $key);
				$kelengkapan_id = $arr[1];

				if ($value == "") {
					$this->Msiswa->tcg_hapus_dokumenpendukung($peserta_didik_id, $kelengkapan_id);
				}
				else {
					$this->Msiswa->tcg_simpan_dokumenpendukung($value, $peserta_didik_id, $kelengkapan_id,1,0,1);
				}
			}
		}
		
        $cek_penerapan = $this->Msiswa->tcg_cek_penerapan($penerapan_id);
        if ($cek_penerapan == 0) {
            print_json_error("Jalur penerapan ini tidak dibuka di putaran ini.");
        }

		$flag = 0;
        $data = null;
		do {
			$existing = $this->Msiswa->tcg_cek_pendaftaran_sekolah($peserta_didik_id, $penerapan_id, $sekolah_id);
			if ($existing > 0) {
                print_json_error("Data tidak tersimpan. Sudah mendaftar di sekolah yang dipilih dengan jalur yang sama.");
			}

            //Jenis pilihan sudah terpakai
            $existing = $this->Msiswa->tcg_cek_pendaftaran_jenispilihan($peserta_didik_id, $jenis_pilihan);
			if ($existing > 0) {
                print_json_error("Data tidak tersimpan. Jenis pilihan sudah terpakai.");
			}

			$satu_zonasi_satu_jalur = $this->setting->get('satu_zonasi_satu_jalur');
			if(!empty($satu_zonasi_satu_jalur)) {
				$flag = 1;
				//TODO: fix usp
				// $query = $this->Msiswa->tcg_cek_satu_zonasi_satu_jalur($peserta_didik_id, $sekolah_id, $penerapan_id);
				// foreach($query->result() as $row) {
				// 	$flag = $row->value;
				// 	if ($flag == 0) {
				// 		$this->session->set_flashdata('error', "Data tidak tersimpan. Sudah mendaftar di zonasi Kec. ". $row->nama_zona. " menggunakan jalur ". $row->nama_penerapan. ". Anda hanya bisa mendaftar dengan satu jalur yang sama pada satu zonasi.");	
				// 		break;
				// 	}
				// }
	
				if ($flag == 0) {
					break;
				}
			}
	
			// $pilihan_pertama_harus_zonasi = $this->setting->get('pilihan_pertama_harus_zonasi');
			// if(!empty($pilihan_pertama_harus_zonasi)) {
			// 	if ($jenis_pilihan==2 && $this->Msiswa->tcg_cek_sekolah_dalam_zonasi($peserta_didik_id, $sekolah_id) == 0) {
			// 		//pilihan ke 2 di luar zonasi. pilihan 1 harus zonasi
			// 		$jalur_id = 1;
			// 		$query = $this->Msiswa->tcg_pendaftaran_jenis_pilihan($peserta_didik_id, 1);
			// 		foreach($query->result() as $row) {
			// 			$jalur_id = $row->jalur_id;
			// 		}

			// 		if ($jalur_id != 1) {
			// 			$this->session->set_flashdata('error', "Data tidak tersimpan. Untuk memilih sekolah di luar zonasi untuk pilihan 2, pilihan 1 harus dalam zonasi dengan jalur zonasi.");	
			// 			break;
			// 		}
			// 	}
			// }

			$maxpilihan = 0; $jumlahpendaftaran=0;
			$sekolah = $this->Msiswa->tcg_profilsekolah($sekolah_id);
            if ($sekolah['status'] == 'N') {
                $maxpilihan=$this->Msiswa->tcg_batasanpilihan_negeri();
                $jumlahpendaftaran = $this->Msiswa->tcg_jumlahpendaftaran_negeri($peserta_didik_id);
            } else {
                $maxpilihan=$this->Msiswa->tcg_batasanpilihan_swasta();
                $jumlahpendaftaran = $this->Msiswa->tcg_jumlahpendaftaran_swasta($peserta_didik_id);
            }
	
			//pilihan untuk either negeri atau swasta
			$maxpilihan_all=$this->Msiswa->tcg_batasanpilihan();
			$jumlahpendaftaran_all = $this->Msiswa->tcg_jumlahpendaftaran($peserta_didik_id);
	
			if(($jumlahpendaftaran>=$maxpilihan && $jumlahpendaftaran_all>=$maxpilihan_all)) {
				print_json_error("Data tidak tersimpan. Jumlah pilihan melebihi batas yang ditentukan.");	
			}
            
            $data = $this->Msiswa->tcg_daftar($peserta_didik_id, $penerapan_id, $sekolah_id, $jenis_pilihan);
            if ($data == null) {
                print_json_error("Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.");	
            }

            //update with additional data
            $batasanperubahan = $this->Mconfig->tcg_batasanperubahan();
            $batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);
    
            $data = update_daftarpendaftaran($data, $batasanperubahan, $batasansiswa);

		} while(false);

        //audit trail
        foreach($data as $pendaftaran) {
            $pendaftaran_id = $pendaftaran['pendaftaran_id'];
            $nama = $pendaftaran['nama'];
            $sekolah = $pendaftaran['sekolah'];
            $pilihan = $pendaftaran['label_jenis_pilihan'];
            $jalur = $pendaftaran['jalur'];

            $flag = $pendaftaran['pendaftaran'];
            if ($flag == 1) {
                audit_pendaftaran($pendaftaran, "PENDAFTARAN", "Pendaftaran an " .$nama. " di " .$sekolah. " jalur " .$jalur. "(" .$pilihan. ")");
            }
            else {
                audit_pendaftaran($pendaftaran, "PENDAFTARAN", "Pendaftaran OTOMATIS an " .$nama. " di " .$sekolah. " jalur " .$jalur. "(" .$pilihan. ")");
            }
        }

        print_json_output($data);

    }

    //hapus pendaftaran
    function hapus() {

        $diterima = $this->session->get("diterima");
        if ($diterima) {
            print_json_error("Sudah diterima.");
        }

        $tutup_akses = $this->session->get("tutup_akses");
        if ($tutup_akses) {
            print_json_error("Akses ditutup.");
        }

        $pendaftaran_id = $this->request->getPostGet("pendaftaran_id");
		$keterangan = $this->request->getPostGet("keterangan");
		
		$peserta_didik_id = $this->session->get("peserta_didik_id");

		$batasanperubahan = $this->Mconfig->tcg_batasanperubahan();
		$batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);

        if ($batasansiswa['hapus_pendaftaran'] >= $batasanperubahan['hapus_pendaftaran']) {
            print_json_error("Data tidak tersimpan. Sudah melebihi batasan yang diperbolehkan.");
        }

        //pendaftaran lama
        $pendaftaran = $this->Msiswa->tcg_pendaftaran_detil($peserta_didik_id, $pendaftaran_id);
        if ($pendaftaran == null) {
            print_json_error("Pendaftaran tidak ditemukan.");
        }

        //hapus
        $status = $this->Msiswa->tcg_hapus_pendaftaran($peserta_didik_id, $pendaftaran_id, $keterangan);
        if (!$status) {
            print_json_error("Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.");
        }
       
        $batasansiswa['hapus_pendaftaran']++;

        //audit trail
        $sekolah = $pendaftaran['sekolah'];
        $pilihan = $pendaftaran['label_jenis_pilihan'];
        $jalur = $pendaftaran['jalur'];
        audit_pendaftaran($pendaftaran, "HAPUS PENDAFTARAN", "Hapus pendaftaran di " .$sekolah. " jalur " .$jalur. "(" .$pilihan. ")");

        //get list of existing pendaftaran
        $data = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id);

        //update with additional data
        $batasanperubahan = $this->Mconfig->tcg_batasanperubahan();
        // $batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);

        $data = update_daftarpendaftaran($data, $batasanperubahan, $batasansiswa);

        print_json_output($data);
    }

    //daftar sebaran sekolah
    function sebaransekolah() {
		$peserta_didik_id = $this->session->get("peserta_didik_id");
        $limit = $this->request->getPostGet("limit");
        if (empty($limit)) {
            $limit = 25;
        }

        $data = $this->Msiswa->tcg_sebaransekolah($peserta_didik_id, $limit);
        print_json_output($data);
    }


}
?>
