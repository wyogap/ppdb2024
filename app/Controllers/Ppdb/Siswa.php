<?php

namespace App\Controllers\Ppdb;

use App\Controllers\Ppdb\PpdbController;
use App\Libraries\QRCodeLibrary;
use App\Libraries\Uploader;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use App\Models\Ppdb\Mdropdown;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Psr\Log\LoggerInterface;

class Siswa extends PpdbController {

    protected $Msiswa;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        //$this->smarty = new SmartyLibrary();

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
        
        //waktu pelaksanaan
        $waktudaftarulang = $this->Mconfig->tcg_waktudaftarulang();
        $waktupendaftaran = $this->Mconfig->tcg_waktupendaftaran();
        $waktusosialisasi = $this->Mconfig->tcg_waktusosialisasi();
        $cek_waktudaftarulang = ($waktudaftarulang['aktif'] == 1);
        $cek_waktupendaftaran = ($waktupendaftaran['aktif'] == 1);
        $cek_waktusosialisasi = ($waktusosialisasi['aktif'] == 1);

        $profil = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id);
        if (empty($profil)) {
            //invalid session. just re-login
            $this->session->setFlashdata("error", "Invalid session!");
            return redirect()->to(site_url() ."auth/logout");
        }

        //daftar pendaftaran
        $pendaftaran = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id);

        $cek_pendaftaran_aktif = (count($pendaftaran) > 0);
        $pendaftaran_diterima = null;
        $jumlahpendaftarannegeri = 0;
        $jumlahpendaftaranswasta = 0;
        foreach($pendaftaran as $row) {
            //pendaftaran diterima
            if ($row['status_penerimaan_final'] == 1 || $row['status_penerimaan_final'] == 3) {
                $pendaftaran_diterima = $row;
            }
            //jumlah pendaftaran
            if ($row['status_sekolah'] == 'N' && $row['pendaftaran']) {
                $jumlahpendaftarannegeri++;
            }
            if ($row['status_sekolah'] == 'S' && $row['pendaftaran']) {
                $jumlahpendaftaranswasta++;
            }
        }

        $cek_pendaftaran_diterima = ($pendaftaran_diterima != null);
        $cek_daftar_ulang = 0;
        if ($pendaftaran_diterima != null) {
            $cek_daftar_ulang = $pendaftaran_diterima['status_daftar_ulang'];
        }

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

        // //a hack for consistent logic
        // $dokumen[DOCID_PRESTASI] = array();
        // $dokumen[DOCID_PRESTASI]['verifikasi'] = 1;

        if ($upload_dokumen) {
            $dokumen = $this->Msiswa->tcg_dokumenpendukung($peserta_didik_id);
            foreach($dokumen as $row) {
                $row['catatan'] = nl2br(trim($row['catatan']));
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
            $pernyataan_verifikasi = $dokumen[DOCID_SUKET_KEBENARAN_DOK]['verifikasi'];
            $pernyataan_file = $dokumen[DOCID_SUKET_KEBENARAN_DOK]['path_surat_pernyataan'];
            $pernyataan_tanggal = $dokumen[DOCID_SUKET_KEBENARAN_DOK]['tanggal_surat_pernyataan'];
        
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
        }

        //Output
        $data = array();

        //tab yang aktif
        //belum waktu_pendaftaran
        if ($cek_waktusosialisasi) {
            $data['aktif'] = "profil";
        }
        else if (!$cek_waktupendaftaran && !$cek_waktudaftarulang) {
            $data['aktif'] = "profil";
        }
        //waktu_daftar_ulang and pendaftaran_aktif_diterima, go to daftar-ulang
        else if ($cek_waktudaftarulang && $cek_pendaftaran_diterima) {
            $data['aktif'] = 'daftarulang';
        }
        //kelengkapan_data!=1 atau verifikasi_dok!=1, go to kelengkapan-data
        else if (!$kelengkapan_data || !$verifikasi_dok) {
            $data['aktif'] = 'profil';
        }
        //pendaftaran_aktif, go to hasil-pendaftaran
        else if ($cek_pendaftaran_aktif) {
            $data['aktif'] = 'hasilpendaftaran';
        }
        //data-lengkap and dok-terverifikasi and no pendaftaran_aktif, go to pendaftaran
        else if ($cek_waktupendaftaran && $kelengkapan_data && $verifikasi_dok && !$cek_pendaftaran_aktif) {
            $data['aktif'] = 'pendaftaran';
        }
        //default
        else {
            $data['aktif'] = 'profil';
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
        $data['kelengkapan_data'] = $kelengkapan_data;
        $data['verifikasi_dok'] = $verifikasi_dok;

        //var_dump($dokumen); exit;

        //dokumen pendukung
        $data['dokumen'] = $dokumen;   
        $data['jml_dokumen_tambahan'] = $jml_dokumen_tambahan;
        $data['verifikasi_dokumen'] = $verifikasi_dok;
        //$data['dokumen_tambahan'] = $dokumen_tambahan;
        $data['verifikasi_dokumen_tambahan'] = $verifikasi_dokumen_tambahan;

        $data['pernyataan_verifikasi'] = $pernyataan_verifikasi;
        $data['pernyataan_file'] = $pernyataan_file;
        $data['pernyataan_tanggal'] = $pernyataan_tanggal;
                  
		// $kebutuhan_khusus = 1;
		// if (empty($profil['kebutuhan_khusus']) || $profil['kebutuhan_khusus']=="0" || $profil['kebutuhan_khusus']=='Tidak ada') {
		// 	$kebutuhan_khusus = 0;
		// }

		// $afirmasi = 1;
		// if ((empty($profil['punya_kip']) || $profil['punya_kip']=="0") && (empty($profil['masuk_bdt']) || $profil['masuk_bdt']=="0")) {
		// 	$afirmasi = 0;
		// }

		// $data['kebutuhan_khusus'] = $kebutuhan_khusus;
		// $data['afirmasi'] = $afirmasi;

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

        $data['daftarskoring'] = $this->Mconfig->tcg_lookup_daftarskoring_prestasi();

        //data tambahan
        # PROFIL
        // $data['konfirmasiprofil'] = array (
        //     "nomer-hp" => (empty($profil['nomor_kontak']) || strlen($profil['nomor_kontak']) < 7 || strlen($profil['nomor_kontak']) > 14) ? 0 : 1,
        //     'profil' => (empty($profil['konfirmasi_profil'])) ? 0 : 1,
        //     'lokasi' => (empty($profil['konfirmasi_lokasi'])) ? 0 : 1,
        //     'nilai' => (empty($profil['konfirmasi_nilai'])) ? 0 : 1,
        //     'prestasi' => (empty($profil['konfirmasi_prestasi'])) ? 0 : 1,
        //     'afirmasi' => (empty($profil['konfirmasi_afirmasi'])) ? 0 : 1,
        //     'inklusi' => (empty($profil['konfirmasi_inklusi'])) ? 0 : 1,
        //     'surat-pernyataan' => (empty($pernyataan_file)) ? 0 : 1
        // );

        // $data['verifikasiprofil'] = array (
        //     'nomer-hp' => 1,
        //     'profil' => (empty($profil['verifikasi_profil'])) ? 0 : $profil['verifikasi_profil'],
        //     'lokasi' => (empty($profil['verifikasi_lokasi'])) ? 0 : $profil['verifikasi_lokasi'],
        //     'nilai' => (empty($profil['verifikasi_nilai'])) ? 0 : $profil['verifikasi_nilai'],
        //     'prestasi' => (empty($profil['verifikasi_prestasi'])) ? 0 : $profil['verifikasi_prestasi'],
        //     'afirmasi' => (empty($profil['verifikasi_afirmasi'])) ? 0 : $profil['verifikasi_afirmasi'],
        //     'inklusi' => (empty($profil['verifikasi_inklusi'])) ? 0 : $profil['verifikasi_inklusi'],
        //     'surat-pernyataan' => $pernyataan_verifikasi
        // );
    
        // $data['profilflag'] = array (
        //     'nilai-un' => (empty($profil['punya_nilai_un'])) ? 0 : 1,
        //     'prestasi' => (empty($profil['punya_prestasi'])) ? 0 : 1,
        //     'kip' => (empty($profil['punya_kip'])) ? 0 : 1,
        //     'bdt' => (empty($profil['masuk_bdt'])) ? 0 : 1,
        //     'inklusi' => ($profil['kebutuhan_khusus'] == 'Tidak ada') ? 0 : 1
        // );

        //$data['berkas_fisik'] = $this->Msiswa->tcg_berkas_fisik($peserta_didik_id)->getResultArray(); 

        # PENDAFTARAN
        $data['global_tutup_akses'] = ($this->session->get("tutup_akses") ?? 0);
        // $data['pendaftarandikunci'] = (!$cek_waktupendaftaran && !$cek_waktusosialisasi) || $global_tutup_akses;

        $data['batasanperubahan'] = $this->Mconfig->tcg_batasanperubahan();
        $data['batasansiswa'] = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);

        $data['batasanusia'] = $this->Mconfig->tcg_batasanusia("SMP");
        $data['cek_batasanusia'] = ($data['batasanusia']['maksimal_tanggal_lahir'] < $profil['tanggal_lahir'] || $data['batasanusia']['minimal_tanggal_lahir'] > $profil['tanggal_lahir']);

        $data['daftarpenerapan'] = $this->Msiswa->tcg_daftarpenerapan($profil['kode_wilayah'], !($profil['kebutuhan_khusus'] == 'Tidak ada'));
        
        // // var_dump($data['daftarpenerapan']); exit;
        // echo "--" .$profil['kebutuhan_khusus']. '--'; 
        // var_dump(($profil['kebutuhan_khusus'] == 'Tidak ada')); exit;

        $daftarpilihan = $this->Mconfig->tcg_daftarpilihan();
        $data['maxpilihan'] = count($daftarpilihan);
        $data['maxpilihannegeri'] = 0;
        $data['maxpilihanswasta'] = 0;
        foreach($daftarpilihan as $row) {
            if ($row['sekolah_negeri'] == 1) {
                $data['maxpilihannegeri']++;
            }
            if ($row['sekolah_swasta'] == 1) {
                $data['maxpilihanswasta']++;
            }
        }
            
        //$data['statusprofil'] = $this->Msiswa->tcg_profilsiswa_status($peserta_didik_id);
        if ($data['satu_zonasi_satu_jalur'] == 1) {
            $data['pendaftaran_dalam_zonasi'] = $this->Msiswa->tcg_jalur_pendaftaran_dalam_zonasi($peserta_didik_id, $tahun_ajaran_id);
        }

        ## DAFTAR PENDAFTARAN
        //berkas dok  
        $pendaftaran = $this->_update_daftarpendaftaran($pendaftaran, $data['batasanperubahan'], $data['batasansiswa']);     

        $data['daftarpendaftaran'] = $pendaftaran;

        ## END DAFTAR PENDAFTARAN

        ## DAFTAR ULANG

        ## END DAFTAR ULANG

        //debugging
        if (__DEBUGGING__) {
            $data['cek_waktupendaftaran'] = 1;
            $data['cek_waktusosialisasi'] = 1;
            $data['cek_waktudaftarulang'] = 1;
            //$data['kebutuhan_khusus'] = 1;
            $data['satu_zonasi_satu_jalur'] = 1;
            $data['profilsiswa']['tutup_akses'] = 0;

            // foreach($data['profilflag'] as $key => $value) {
            //     $data['profilflag'][$key] = 1;
            // }
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
        
        $tgl_mulai_pendaftaran = date('Y-m-d');
        $result = $this->Mconfig->tcg_waktupendaftaran();
        $tgl = date( 'Y-m-d', strtotime($result['tanggal_mulai_aktif']));
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
        
        $tgl_mulai_pendaftaran = date('Y-m-d');
        $result = $this->Mconfig->tcg_waktupendaftaran();
        $tgl = date( 'Y-m-d', strtotime($result['tanggal_mulai_aktif']));
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
        $peserta_didik_id = $this->session->get("user_id");
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
    
    //daftar prestasi (view/edit/add/delete)
    function prestasi() {
        $peserta_didik_id = $this->session->get("user_id");
		$tahun_ajaran_id = $this->tahun_ajaran_id;

        $flag_upload_dokumen = $this->setting->get('upload_dokumen');
        $mdropdown = new Mdropdown();

        $action = $_POST["action"] ?? null; 
		if (empty($action) || $action=='view') {
            $data['data'] = $this->Msiswa->tcg_daftar_prestasi($peserta_didik_id)->getResultArray();
            //var_dump($data['data']);
            foreach($data['data'] as $idx=>$row) {
                if (!empty($row['catatan'])) {
                    $data['data'][$idx]['catatan'] = nl2br(trim($row['catatan']));
                }
                else {
                    $data['data'][$idx]['catatan'] = "";
                }
            }

            $data['options']['skoring_id'] = $mdropdown->tcg_select_prestasi($tahun_ajaran_id)->getResultArray();

            $files = $this->Msiswa->tcg_daftar_prestasi_files($peserta_didik_id)->getResultArray();
            $data['files']['files'] = array();
            foreach($files as $row) {
                $id = $row['id'];
                $data['files']['files'][$id] = $row;
                $data['files']['files'][$id]['file_path'] = base_url(). $row['path'];
                $data['files']['files'][$id]['web_path'] = base_url(). $row['web_path'];
                $data['files']['files'][$id]['thumbnail_path'] = base_url(). $row['thumbnail_path'];
            }

            echo json_encode($data);
        }
		else if ($action=='edit'){
			$values = $_POST["data"] ?? null; 
            if ($values == null) {
                $data['data'] = array();
                $data['error'] = "no-data";
                echo json_encode($data);
                return;
            }

            $error_msg = "";
			foreach ($values as $key => $valuepair) {
                if (empty($valuepair['dokumen_pendukung'])) {
                    continue;
                };
                $doc_id = $valuepair['dokumen_pendukung'];

                $status = $this->Msiswa->tcg_ubah_dokumen_prestasi($key, $doc_id);
                if ($status == 1) {
                    $data['data'] = $this->Msiswa->tcg_prestasi_detil($peserta_didik_id, $key)->getResultArray(); 
                }
                else {
                    $data['data'] = "error";
                }
			}

            echo json_encode($data);	
        }
        else if ($action=='remove') {
			$values = $_POST["data"] ?? null; 
            if ($values == null) {
                $data['data'] = array();
                $data['error'] = "no-data";
                echo json_encode($data);
                return;
            }

            $error_msg = "";
			foreach ($values as $key => $valuepair) {
                $data['data'] = $this->Msiswa->tcg_hapus_prestasi($peserta_didik_id, $key);
			}

            $data['data'] = array(); 
			echo json_encode($data);	
        }
        else if ($action=='create') {
            $values = $_POST["data"] ?? null; 
            if ($values == null) {
                $data['data'] = array();
                $data['error'] = "no-data";
                echo json_encode($data);
                return;
            }

            if (empty($values[0]['skoring_id']) || empty($values[0]['uraian'])) {
                $data['error'] = "Data wajib belum diisi";
                echo json_encode($data);
                return;
            }

            if ($flag_upload_dokumen && empty($values[0]['dokumen_pendukung'])) {
                $data['error'] = "Data wajib belum diisi";
                echo json_encode($data);
                return;
            }

            $key = $this->Msiswa->tcg_prestasi_baru($peserta_didik_id, $values[0]);
            if ($key == 0) {
                $data['error'] = $this->Msiswa->error();
            } else {
                $data['data'] = $this->Msiswa->tcg_prestasi_detil($peserta_didik_id, $key)->getResultArray(); 
            }

            echo json_encode($data);
        }
        else if ($action == "upload") {

            $kelengkapan_id = 8;

            $uploader = new Uploader();
            $fileObj = $uploader->upload($_FILES['upload']);

            $data = array();
            if(!empty($fileObj['error'])) {
                $data['error'] = $fileObj['error'];
            } else {
                $data = array("data"=>array(),"files"=>array("files"=>array($fileObj['id']=>$fileObj)),"upload"=>array("id"=>$fileObj['id']));
            }

            echo json_encode($data);
            return;
        }   

    } 

    function updateprofil() {
        $data = $this->request->getPost("data");
        if (empty($data))   
            $this->print_json_error("Invalid data");

        $peserta_didik_id = $this->session->get('peserta_didik_id');

        $keys = array_keys($data);

        $toggle = false;
        $colname = "";
        if (count($keys) == 1 && substr($keys[0],0,11) == "konfirmasi_") {
            $toggle = true;
            $colname = $keys[0];
        }

        //oldvalues
        $oldvalues = null;
        if (!$toggle) {
            $oldvalues = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id);
            if ($oldvalues == null) {
                $this->print_json_error("Invalid userid");
            }
        }

        $detail = $this->Msiswa->tcg_update_siswa($peserta_didik_id, $data);
        if ($detail == null)
            $this->print_json_error("Tidak berhasil mengubah data siswa.");

        //audit trail
        if ($toggle) {
            //$this->audit_siswa($peserta_didik_id, "UPDATE PROFIL", "Toggle status " .$colname, $colname, $data[$colname], null);
        }
        else {
            $this->audit_siswa($peserta_didik_id, "UPDATE PROFIL", "Ubah data siswa", $keys, $data, $oldvalues);
        }

        $this->print_json_output($detail);
    }

    //upload dokumen
    function upload() {
        $peserta_didik_id = $this->session->get("peserta_didik_id");
        $doc_id = $this->request->getPostGet("doc_id");
        if (empty($doc_id)) {
            $this->print_json_error("Kode kelengkapan dokumen tidak dikenal!");
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
        $this->audit_siswa($peserta_didik_id, "UPLOAD DOK", "Upload dokumen " .$nama_dok, "doc_id,upload_id", "" .$doc_id. "," .$fileObj['id']. "", null);

        $data = array("data"=>array(),"files"=>array("files"=>array($fileObj['id']=>$fileObj)),"upload"=>array("id"=>$fileObj['id']));

        $this->print_json_output($data);  
    }

    //upload dokumen
    function hapusupload() {
        $peserta_didik_id = $this->session->get("peserta_didik_id");
        $doc_id = $this->request->getPostGet("doc_id");
        if (empty($doc_id)) {
            $this->print_json_error("Kode kelengkapan dokumen tidak dikenal!");
        }

        $this->Msiswa->tcg_hapus_dokumenpendukung($peserta_didik_id, $doc_id);

        //audit trail
        $nama_dok = $this->Msiswa->tcg_nama_dokumen($doc_id);
        $this->audit_siswa($peserta_didik_id, "HAPUS DOK", "Hapus dokumen " .$nama_dok, "doc_id", $doc_id, null);

        $this->print_json_output(array());  
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
                $this->print_json_error("Tidak ada pilihan yang bisa dipakai.");
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
                $this->print_json_error("Data pendaftaran tidak ditemukan");
            }

            $penerapan_id = $pendaftaran['penerapan_id'];
            $jenis_pilihan = $pendaftaran['jenis_pilihan'];
            $data = $this->Msiswa->tcg_pilihansekolah($peserta_didik_id, $penerapan_id, $jenis_pilihan);
            if ($data == null) {
                $this->print_json_error("Tidak berhasil mendapatkan data pilihan.");
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
                $this->print_json_error("Data pendaftaran tidak ditemukan");
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
    
            // $afirmasi = 1;
            // if (empty($profil['punya_kip']) && empty($profil['masuk_bdt'])) {
            //     $afirmasi = 0;
            // }
    
            if ($parent_penerapan_id) {
                $data = $this->Msiswa->tcg_daftarpenerapan($kode_wilayah, $kebutuhan_khusus, $parent_penerapan_id);
            }
            else {
                $data = $this->Msiswa->tcg_daftarpenerapan($kode_wilayah, $kebutuhan_khusus, $penerapan_id);
            }
            
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
            $this->print_json_error("not-implemented");
        }

        if ($data == null) {
            $this->print_json_error("Tidak berhasil mendapatkan data pilihan.");
        }

        $this->print_json_output($data);
    }

    //ubah pendaftaran
    function ubah() {
        //ubahpilihan | ubahsekolah | ubahjalur 
        $tipe = $this->request->getPostGet("tipe");
        $pendaftaran_id = $this->request->getPostGet("pendaftaran_id");
        $peserta_didik_id = $this->session->get('peserta_didik_id');

        $pendaftaran_lama = $this->Msiswa->tcg_pendaftaran_detil($peserta_didik_id, $pendaftaran_id);
        $audit_action_type = "";
        $audit_action_desc = '';
        $nama = $pendaftaran_lama['nama'];

		$batasanperubahan = $this->Mconfig->tcg_batasanperubahan();
		$batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);

        if ($tipe == 'ubahpilihan') {
            if ($batasansiswa['ubah_pilihan'] >= $batasanperubahan['ubah_pilihan']) {
                $this->print_json_error("Data tidak tersimpan. Sudah melebihi batasan yang diperbolehkan.");
            }

            $audit_action_type = "UBAH PILIHAN";
            $audit_action_desc = "Ubah urutan pendaftaran (jenis pilihan) an. " .$nama;
            $audit_keys = array("jenis_pilihan","label_jenis_pilihan");

            //ubah data
            $jenis_pilihan_baru = $this->request->getPostGet("jenis_pilihan_baru");

            $data = $this->Msiswa->tcg_ubah_jenispilihan($peserta_didik_id, $pendaftaran_id, $jenis_pilihan_baru);
        }
        else if ($tipe == 'ubahsekolah') {
            if ($batasansiswa['ubah_sekolah'] >= $batasanperubahan['ubah_sekolah']) {
                $this->print_json_error("Data tidak tersimpan. Sudah melebihi batasan yang diperbolehkan.");
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
                $this->print_json_error("Data tidak tersimpan. Sudah mendaftar di sekolah yang dipilih dengan jalur yang sama.");
			}

            $data = $this->Msiswa->tcg_ubah_pilihansekolah($peserta_didik_id, $pendaftaran_id, $sekolah_id_baru);
        }
        else if ($tipe == 'ubahjalur') {
            if ($batasansiswa['ubah_jalur'] >= $batasanperubahan['ubah_jalur']) {
                $this->print_json_error("Data tidak tersimpan. Sudah melebihi batasan yang diperbolehkan.");
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
                $this->print_json_error("Data tidak tersimpan. Sudah mendaftar di sekolah yang dipilih dengan jalur yang sama.");
			}

            $data = $this->Msiswa->tcg_ubah_jalur($peserta_didik_id, $pendaftaran_id, $penerapan_id_baru);
        }
        else {
            $this->print_json_error("not-implemented");
        }

        if ($data == null) {
            $this->print_json_error("Tidak berhasil mendapatkan data pilihan.");
        }

        //audit trail
        $this->audit_pendaftaran($pendaftaran_id, $audit_action_type, $audit_action_desc, $audit_keys, $data, $pendaftaran_lama);

        //get list of existing pendaftaran
        $data = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id);

        //update with additional data
        $batasanperubahan = $this->Mconfig->tcg_batasanperubahan();
        $batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);

        $data = $this->_update_daftarpendaftaran($data, $batasanperubahan, $batasansiswa);
        
        $this->print_json_output($data);
    }

    //lakukan pendaftaran
    function daftar() {
		$peserta_didik_id = $this->session->get("peserta_didik_id");
		$sekolah_id = $this->request->getPostGet("sekolah_id");
		$penerapan_id = $this->request->getPostGet("penerapan_id");
		$jenis_pilihan = $this->request->getPostGet("jenis_pilihan");

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
            $this->print_json_error("Jalur penerapan ini tidak dibuka di putaran ini.");
        }

		$flag = 0;
        $data = null;
		do {
			$existing = $this->Msiswa->tcg_cek_pendaftaran_sekolah($peserta_didik_id, $penerapan_id, $sekolah_id);
			if ($existing > 0) {
                $this->print_json_error("Data tidak tersimpan. Sudah mendaftar di sekolah yang dipilih dengan jalur yang sama.");
			}

            //Jenis pilihan sudah terpakai
            $existing = $this->Msiswa->tcg_cek_pendaftaran_jenispilihan($peserta_didik_id, $jenis_pilihan);
			if ($existing > 0) {
                $this->print_json_error("Data tidak tersimpan. Jenis pilihan sudah terpakai.");
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
				$this->print_json_error("Data tidak tersimpan. Jumlah pilihan melebihi batas yang ditentukan.");	
			}
            
            $data = $this->Msiswa->tcg_daftar($peserta_didik_id, $penerapan_id, $sekolah_id, $jenis_pilihan);
            if ($data == null) {
                $this->print_json_error("Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.");	
            }

            //update with additional data
            $batasanperubahan = $this->Mconfig->tcg_batasanperubahan();
            $batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);
    
            $data = $this->_update_daftarpendaftaran($data, $batasanperubahan, $batasansiswa);

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
                $this->audit_pendaftaran($pendaftaran_id, "PENDAFTARAN", "Pendaftaran an " .$nama. " di " .$sekolah. " jalur " .$jalur. "(" .$pilihan. ")", 
                                            null, null, null);
            }
            else {
                $this->audit_pendaftaran($pendaftaran_id, "PENDAFTARAN", "Pendaftaran OTOMATIS an " .$nama. " di " .$sekolah. " jalur " .$jalur. "(" .$pilihan. ")", 
                                            null, null, null);
            }
        }

        $this->print_json_output($data);

    }

    //hapus pendaftaran
    function hapus() {
        $pendaftaran_id = $this->request->getPostGet("pendaftaran_id");
		$keterangan = $this->request->getPostGet("keterangan");
		
		$peserta_didik_id = $this->session->get("peserta_didik_id");

		$batasanperubahan = $this->Mconfig->tcg_batasanperubahan();
		$batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);

        if ($batasansiswa['hapus_pendaftaran'] >= $batasanperubahan['hapus_pendaftaran']) {
            $this->print_json_error("Data tidak tersimpan. Sudah melebihi batasan yang diperbolehkan.");
        }

        //pendaftaran lama
        $pendaftaran = $this->Msiswa->tcg_pendaftaran_detil($peserta_didik_id, $pendaftaran_id);

        //hapus
        $status = $this->Msiswa->tcg_hapus_pendaftaran($peserta_didik_id, $pendaftaran_id, $keterangan);
        if (!$status) {
            $this->print_json_error("Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.");
        }
       
        //audit trail
        $sekolah = $pendaftaran['sekolah'];
        $pilihan = $pendaftaran['label_jenis_pilihan'];
        $jalur = $pendaftaran['jalur'];
        $this->audit_pendaftaran($pendaftaran_id, "HAPUS PENDAFTARAN", "Hapus pendaftaran di " .$sekolah. " jalur " .$jalur. "(" .$pilihan. ")", 
                                    null, null, null);

        //get list of existing pendaftaran
        $data = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id);

        //update with additional data
        $batasanperubahan = $this->Mconfig->tcg_batasanperubahan();
        $batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);

        $data = $this->_update_daftarpendaftaran($data, $batasanperubahan, $batasansiswa);

        $this->print_json_output($data);
    }

    //daftar sebaran sekolah
    function sebaransekolah() {
		$peserta_didik_id = $this->session->get("peserta_didik_id");
        $limit = $this->request->getPostGet("limit");
        if (empty($limit)) {
            $limit = 25;
        }

        $data = $this->Msiswa->tcg_sebaransekolah($peserta_didik_id, $limit);
        $this->print_json_output($data);
    }

    protected function _update_daftarpendaftaran($pendaftaran, $batasan_perubahan, $batasan_siswa) {

        foreach($pendaftaran as $k => $p) {
            $pendaftaran_id = $p['pendaftaran_id'];
            $penerapan_id = $p['penerapan_id'];

            //data for view
            $pendaftaran[$k]['url_ubah_pilihan'] = base_url() ."siswa/pendaftaran/ubahjenispilihan?pendaftaran_id=". $pendaftaran_id;
            $pendaftaran[$k]['url_ubah_jalur'] = base_url() ."siswa/pendaftaran/ubahjalur?pendaftaran_id==". $pendaftaran_id;
            $pendaftaran[$k]['url_ubah_sekolah'] = base_url() ."siswa/pendaftaran/ubahsekolah?pendaftaran_id=". $pendaftaran_id ."&penerapan_id=". $penerapan_id;
            $pendaftaran[$k]['url_hapus'] = base_url() ."siswa/pendaftaran/hapus?pendaftaran_id=". $pendaftaran_id;

            $pendaftaran[$k]['ubah_pilihan'] = ($batasan_perubahan['ubah_pilihan'] > 0);
            $pendaftaran[$k]['ubah_jalur'] = ($batasan_perubahan['ubah_jalur'] > 0);
            $pendaftaran[$k]['ubah_sekolah'] = ($batasan_perubahan['ubah_sekolah'] > 0);
            $pendaftaran[$k]['hapus_pendaftaran'] = ($batasan_perubahan['hapus_pendaftaran'] > 0);

            $pendaftaran[$k]['allow_ubah_pilihan'] = !(($batasan_siswa['ubah_pilihan']>=$batasan_perubahan['ubah_pilihan']&&$p['jenis_pilihan']!=0)||$p['pendaftaran']!=1);
            $pendaftaran[$k]['allow_ubah_jalur'] = !(($batasan_siswa['ubah_jalur']>=$batasan_perubahan['ubah_jalur'])||$p['pendaftaran']!=1);
            $pendaftaran[$k]['allow_ubah_sekolah'] = !(($batasan_siswa['ubah_sekolah']>=$batasan_perubahan['ubah_sekolah'])||$p['pendaftaran']!=1);
            $pendaftaran[$k]['allow_hapus'] = !(($batasan_siswa['hapus_pendaftaran']>=$batasan_perubahan['hapus_pendaftaran'])||$p['pendaftaran']!=1);

            //perankingan
            if ($p['peringkat'] = 0) $pendaftaran[$k]['label_peringkat'] = "Belum Ada";
            else if ($p['peringkat'] == -1) $pendaftaran[$k]['label_peringkat'] = "Tidak Ada";
            else if ($p['status_penerimaan_final'] == 2 || $p['status_penerimaan_final'] == 4) $pendaftaran[$k]['label_peringkat'] = "Tidak Dihitung";
            else if ($p['status_penerimaan_final'] == 1 || $p['status_penerimaan_final'] == 3) $pendaftaran[$k]['label_peringkat'] = $p['peringkat_final'];
            else $pendaftaran[$k]['label_peringkat'] = "Belum Ada";

            $pendaftaran[$k]['url_perankingan'] = base_url() ."home/peringkat?sekolah_id=" .$p['sekolah_id']. "&bentuk=" .$p['bentuk'];

            //status penerimaan
            if ($p['status_penerimaan'] == 0) $pendaftaran[$k]['class_status_penerimaan'] = "status-tidak-dihitung";
            else if ($p['status_penerimaan'] == 1) $pendaftaran[$k]['class_status_penerimaan'] = "status-masuk-kuota";
            else if ($p['status_penerimaan'] == 2) $pendaftaran[$k]['class_status_penerimaan'] = "status-tidak-masuk-kuota";
            else if ($p['status_penerimaan'] == 3) $pendaftaran[$k]['class_status_penerimaan'] = "status-daftar-tunggu";
            else if ($p['status_penerimaan'] == 4) $pendaftaran[$k]['class_status_penerimaan'] = "status-tidak-dihitung";
            else $pendaftaran[$k]['class_status_penerimaan'] = "status-tidak-dihitung";

            if ($p['status_penerimaan'] == 0) $pendaftaran[$k]['icon_status_penerimaan'] = "glyphicon glyphicon-search";
            else if ($p['status_penerimaan'] == 1) $pendaftaran[$k]['icon_status_penerimaan'] = "glyphicon glyphicon-check";
            else if ($p['status_penerimaan'] == 2) $pendaftaran[$k]['icon_status_penerimaan'] = "glyphicon glyphicon-info-sign";
            else if ($p['status_penerimaan'] == 3) $pendaftaran[$k]['icon_status_penerimaan'] = "glyphicon glyphicon-check";
            else if ($p['status_penerimaan'] == 4) $pendaftaran[$k]['icon_status_penerimaan'] = "glyphicon glyphicon-info-sign";
            else $pendaftaran[$k]['icon_status_penerimaan'] = "glyphicon glyphicon-search";

            if ($p['status_penerimaan'] == 0) $pendaftaran[$k]['label_status_penerimaan'] = "Dalam Proses Verifikasi";
            else if ($p['status_penerimaan'] == 1) $pendaftaran[$k]['label_status_penerimaan'] = "Masuk Kuota";
            else if ($p['status_penerimaan'] == 2) $pendaftaran[$k]['label_status_penerimaan'] = "Tidak Masuk Kuota";
            else if ($p['status_penerimaan'] == 3) $pendaftaran[$k]['label_status_penerimaan'] = "Daftar Tunggu";
            else if ($p['status_penerimaan'] == 4) $pendaftaran[$k]['label_status_penerimaan'] = "Masuk Kuota " .$p['label_masuk_pilihan'];
            else $pendaftaran[$k]['label_status_penerimaan'] = "Dalam Proses Seleksi";

            //kelengkapan
            $kelengkapan = $this->Msiswa->tcg_kelengkapanpendaftaran($pendaftaran_id);
            foreach ($kelengkapan as $k2 => $i) {
                $kelengkapan[$k2]['status_ok'] = ($i['verifikasi']==1);
                $kelengkapan[$k2]['status_notok'] = ($i['verifikasi']==2);
                $kelengkapan[$k2]['status_tidakada'] = ($i['verifikasi']==3 || ($i['verifikasi']==0 && $i['wajib']==0));
                $kelengkapan[$k2]['status_dalamproses'] = (!$kelengkapan[$k2]['status_ok'] && !$kelengkapan[$k2]['status_notok'] && !$kelengkapan[$k2]['status_tidakada']);
                $kelengkapan[$k2]['kondisi_khusus'] = ($i['kondisi_khusus']!=0);
            }
            $pendaftaran[$k]['kelengkapan'] = $kelengkapan;
            
            // //berkas fisik
            // $berkas = $this->Msiswa->tcg_kelengkapanpendaftaran_berkasfisik($pendaftaran_id)->getResultArray();
            // foreach ($berkas as $k2 => $i) {
            //     $berkas[$k2]['status_ok'] = ($i['berkas_fisik']==1);
            //     $berkas[$k2]['status_notok'] = ($i['berkas_fisik']!=1);
            //     $berkas[$k2]['kondisi_khusus'] = ($i['kondisi_khusus']!=0);
            // }
            // $pendaftaran[$k]['berkasfisik'] = $berkas;

            //skoring
            $skoring = $this->Msiswa->tcg_nilaiskoring($pendaftaran_id);
            $pendaftaran[$k]['skoring'] = $skoring;

            //total skoring
            $totalskoring = 0;
            foreach($skoring as $k2 => $s) {
                $val = floatval($s['nilai']);
                $totalskoring += $val;
            }
            $pendaftaran[$k]['totalskoring'] = number_format($totalskoring,2,".","");
        }

        return $pendaftaran;
    }
}
?>
