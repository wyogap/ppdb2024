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
        $peserta_didik_id = $this->pengguna_id;
        if ($this->is_dinas) {
            $peserta_didik_id = $_GET["peserta_didik_id"] ?? null;
        }

        $tahun_ajaran_id = $this->tahun_ajaran_id;
        $upload_dokumen = $this->setting->get('upload_dokumen');

        //DEBUG
        $peserta_didik_id = '000EB5F3-46F3-4C5D-A95E-CF56D743B306';
        $this->session->set("pengguna_id", $peserta_didik_id);
        $this->putaran = 2;
        $this->session->set('putaran_aktif', $this->putaran);
        $upload_dokumen = 0;
        //END DEBUG

        //waktu pelaksanaan
        $waktudaftarulang = $this->Msetting->tcg_waktudaftarulang()->getRowArray();
        $waktupendaftaran = $this->Msetting->tcg_waktupendaftaran()->getRowArray();
        $waktusosialisasi = $this->Msetting->tcg_waktusosialisasi()->getRowArray();
        $cek_waktudaftarulang = ($waktudaftarulang['aktif'] == 1);
        $cek_waktupendaftaran = ($waktupendaftaran['aktif'] == 1);
        $cek_waktusosialisasi = ($waktusosialisasi['aktif'] == 1);

        //daftar pendaftaran
        $pendaftaran = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id)->getResultArray();

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
        $profil = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id)->getRowArray();

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
        $files = array();

        // //a hack for consistent logic
        // $dokumen[DOCID_PRESTASI] = array();
        // $dokumen[DOCID_PRESTASI]['verifikasi'] = 1;

        $query = $this->Msiswa->tcg_dokumen_pendukung($peserta_didik_id);
        foreach($query->getResult() as $row) {
            $row->catatan = nl2br(trim($row->catatan));

            if($row->daftar_kelengkapan_id == DOCID_PRESTASI) {
                //dokumen prestasi
                if(!isset($dokumen[DOCID_PRESTASI])) {
                    $dokumen[DOCID_PRESTASI] = array();
                }
                $dokumen[DOCID_PRESTASI][$row->dokumen_id] = array(
                    "dokumen_id"=>$row->dokumen_id, 
                    "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
                    "nama"=>$row->nama,
                    "verifikasi"=>$row->verifikasi,
                    "berkas_fisik"=>$row->berkas_fisik,
                    "catatan"=>$row->catatan,
                    "filename"=>$row->filename, 
                    "file_path"=>base_url().$row->path, 
                    "web_path"=>base_url().$row->web_path, 
                    "thumbnail_path"=>base_url().$row->thumbnail_path
                );
                //a hack for consistent logic
                if ($row->verifikasi == 2) {
                    //ada dok yang gagal verifikasi
                    $dokumen[DOCID_PRESTASI]['verifikasi'] = 2;
                }
                else if ($row->verifikasi == 0 && $dokumen[DOCID_PRESTASI]['verifikasi'] == 1) {
                    //ada dok yang belum diverifikasi
                    $dokumen[DOCID_PRESTASI]['verifikasi'] = 0;
                }
            }
            else {
                //dokumen lain
                $dokumen[$row->daftar_kelengkapan_id] = array(
                    "dokumen_id"=>$row->dokumen_id, 
                    "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
                    "nama"=>$row->nama,
                    "verifikasi"=>$row->verifikasi,
                    "berkas_fisik"=>$row->berkas_fisik,
                    "catatan"=>$row->catatan,
                    "filename"=>$row->filename, 
                    "file_path"=>base_url().$row->path, 
                    "web_path"=>base_url().$row->web_path, 
                    "thumbnail_path"=>base_url().$row->thumbnail_path);
            }   

            $files[$row->dokumen_id] = array(
                "dokumen_id"=>$row->dokumen_id, 
                "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
                "nama"=>$row->nama,
                "verifikasi"=>$row->verifikasi,
                "catatan"=>$row->catatan,
                "filename"=>$row->filename, 
                "file_path"=>base_url().$row->path, 
                "web_path"=>base_url().$row->web_path, 
                "thumbnail_path"=>base_url().$row->thumbnail_path);
        }

        //dokumen tambahan
        $dokumen_tambahan = array();
        $verifikasi_dokumen_tambahan = 1;

        $query = $this->Msiswa->tcg_dokumen_pendukung_tambahan($peserta_didik_id);
        foreach($query->getResult() as $row) {

            $row->catatan = nl2br(trim($row->catatan));

            $dokumen_tambahan[$row->daftar_kelengkapan_id] = array(
                "dokumen_id"=>$row->dokumen_id, 
                "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
                "nama"=>$row->nama,
                "verifikasi"=>$row->verifikasi,
                "catatan"=>$row->catatan,
                "filename"=>$row->filename, 
                "file_path"=>base_url().$row->path, 
                "web_path"=>base_url().$row->web_path, 
                "thumbnail_path"=>base_url().$row->thumbnail_path);

            if ($row->verifikasi == 2) {
                $data['verifikasi_dokumen_tambahan'] = 2;
            }
            else if ($row->verifikasi == 0) {
                $data['verifikasi_dokumen_tambahan'] = 0;
            }
        }

        $pernyataan_verifikasi = 0;
        $pernyataan_file = null;
        $pernyataan_tanggal = null;
        if (!$upload_dokumen) {
            //no dok upload
            $pernyataan_verifikasi = 1;
            $pernyataan_file = "no-upload";
            $pernyataan_tanggal = "no-upload";
        }
        else if (!empty($dokumen[DOCID_SUKET_KEBENARAN_DOK])) {
            $pernyataan_verifikasi = $dokumen[DOCID_SUKET_KEBENARAN_DOK]['verifikasi'];
            $pernyataan_file = $dokumen[DOCID_SUKET_KEBENARAN_DOK]['path_surat_pernyataan'];
            $pernyataan_tanggal = $dokumen[DOCID_SUKET_KEBENARAN_DOK]['tanggal_surat_pernyataan'];
        }
    
        //verifikasi dokumen
        $verifikasi_dok = 1;
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
        $data['tahapan_aktif'] = $this->Msetting->tcg_tahapan_pelaksanaan_aktif()->getResultArray();
        $data['pengumuman'] = $this->Msetting->tcg_pengumuman()->getResult();

        //data for view
        $data['peserta_didik_id'] = $peserta_didik_id;
        $data['tahun_ajaran'] = $tahun_ajaran_id;
        $data['profilsiswa'] = $profil;
        $data['kelengkapan_data'] = $kelengkapan_data;
        $data['verifikasi_dok'] = $verifikasi_dok;

        //var_dump($dokumen); exit;

        //dokumen pendukung
        $data['dokumen'] = $dokumen;   
        $data['files'] = $files;
        $data['verifikasi_dokumen'] = $verifikasi_dok;
        $data['dokumen_tambahan'] = $dokumen_tambahan;
        $data['verifikasi_dokumen_tambahan'] = $verifikasi_dokumen_tambahan;

        $data['pernyataan_verifikasi'] = $pernyataan_verifikasi;
        $data['pernyataan_file'] = $pernyataan_file;
        $data['pernyataan_tanggal'] = $pernyataan_tanggal;
                  
		$kebutuhan_khusus = 1;
		if (empty($profil['kebutuhan_khusus']) || $profil['kebutuhan_khusus']=="0" || $profil['kebutuhan_khusus']=='Tidak ada') {
			$kebutuhan_khusus = 0;
		}

		$afirmasi = 1;
		if ((empty($profil['punya_kip']) || $profil['punya_kip']=="0") && (empty($profil['masuk_bdt']) || $profil['masuk_bdt']=="0")) {
			$afirmasi = 0;
		}

		$data['kebutuhan_khusus'] = $kebutuhan_khusus;
		$data['afirmasi'] = $afirmasi;

		$data['waktupendaftaran'] = $waktupendaftaran;
		$data['cek_waktupendaftaran'] = $cek_waktupendaftaran;
		$data['waktudaftarulang'] = $waktudaftarulang;
		$data['cek_waktudaftarulang'] = $cek_waktudaftarulang;

        $data['waktusosialisasi'] = $waktusosialisasi;
        $data['cek_waktusosialisasi'] = $cek_waktusosialisasi;
        $data['waktupendaftaransusulan'] = $this->Msetting->tcg_waktupendaftaransusulan()->getRowArray();
        // $data['cek_waktupendaftaransusulan'] = 0;
        // if (!empty($data['cek_waktupendaftaransusulan'])) {
        //     $data['cek_waktupendaftaransusulan'] = ($data['waktupendaftaransusulan']['aktif'] == 1);
        // }

        $data['satu_zonasi_satu_jalur'] = $this->setting->get('satu_zonasi_satu_jalur');

		$data['jumlahpendaftaran'] = count($pendaftaran);
		$data['jumlahpendaftarannegeri'] = $jumlahpendaftarannegeri;
		$data['jumlahpendaftaranswasta'] = $jumlahpendaftaranswasta;

		$data['pendaftaranditerima'] = $pendaftaran_diterima;

        //data tambahan
        # PROFIL
        $data['konfirmasiprofil'] = array (
            "nomer-hp" => (empty($profil['nomor_kontak']) || strlen($profil['nomor_kontak']) < 7 || strlen($profil['nomor_kontak']) > 14) ? 0 : 1,
            'profil' => (empty($profil['konfirmasi_profil'])) ? 0 : 1,
            'lokasi' => (empty($profil['konfirmasi_lokasi'])) ? 0 : 1,
            'nilai' => (empty($profil['konfirmasi_nilai'])) ? 0 : 1,
            'prestasi' => (empty($profil['konfirmasi_prestasi'])) ? 0 : 1,
            'afirmasi' => (empty($profil['konfirmasi_afirmasi'])) ? 0 : 1,
            'inklusi' => (empty($profil['konfirmasi_inklusi'])) ? 0 : 1,
            'surat-pernyataan' => (empty($pernyataan_file)) ? 0 : 1
        );

        $data['verifikasiprofil'] = array (
            'nomer-hp' => 1,
            'profil' => (empty($profil['verifikasi_profil'])) ? 0 : $profil['verifikasi_profil'],
            'lokasi' => (empty($profil['verifikasi_lokasi'])) ? 0 : $profil['verifikasi_lokasi'],
            'nilai' => (empty($profil['verifikasi_nilai'])) ? 0 : $profil['verifikasi_nilai'],
            'prestasi' => (empty($profil['verifikasi_prestasi'])) ? 0 : $profil['verifikasi_prestasi'],
            'afirmasi' => (empty($profil['verifikasi_afirmasi'])) ? 0 : $profil['verifikasi_afirmasi'],
            'inklusi' => (empty($profil['verifikasi_inklusi'])) ? 0 : $profil['verifikasi_inklusi'],
            'surat-pernyataan' => $pernyataan_verifikasi
        );
    
        $data['profilflag'] = array (
            'nilai-un' => (empty($profil['punya_nilai_un'])) ? 0 : 1,
            'prestasi' => (empty($profil['punya_prestasi'])) ? 0 : 1,
            'kip' => (empty($profil['punya_kip'])) ? 0 : 1,
            'bdt' => (empty($profil['masuk_bdt'])) ? 0 : 1,
            'inklusi' => ($profil['kebutuhan_khusus'] == 'Tidak ada') ? 0 : 1
        );

        //selama ada pendaftaran aktif, kunci tidak bisa edit
        $data['profildikunci'] = !$cek_pendaftaran_aktif;

        // if ($data['aktif'] == 'profil') {
            $data['berkas_fisik'] = $this->Msiswa->tcg_berkas_fisik($peserta_didik_id)->getResultArray();
        // } 

        # PENDAFTARAN
        $global_tutup_akses = $this->session->get("tutup_akses");
        $data['pendaftarandikunci'] = (!$cek_waktupendaftaran && !$cek_waktusosialisasi) || $global_tutup_akses;

        $data['batasanperubahan'] = $this->Msetting->tcg_batasanperubahan()->getRowArray();
        $data['batasansiswa'] = $this->Msiswa->tcg_batasansiswa($peserta_didik_id)->getRowArray();
        //$data['tutup_akses'] = $this->session->get("tutup_akses");

        // if ($data['aktif'] == 'pendaftaran') {
            $data['batasanusia'] = $this->Msetting->tcg_batasanusia("SMP")->getRowArray();
            $data['cek_batasanusia'] = ($data['batasanusia']['maksimal_tanggal_lahir'] < $profil['tanggal_lahir'] || $data['batasanusia']['minimal_tanggal_lahir'] > $profil['tanggal_lahir']);

            $data['daftarjalur'] = $this->Msiswa->tcg_daftarjalur($profil['kode_wilayah'], !($profil['kebutuhan_khusus'] == 'Tidak ada'))->getResultArray();
            
            $daftarpilihan = $this->Msetting->tcg_daftarpilihan()->getResultArray();
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
        // }

        ## DAFTAR PENDAFTARAN
        //berkas dok
        
        foreach($pendaftaran as $k => $p) {
            $pendaftaran_id = $p['pendaftaran_id'];
            $penerapan_id = $p['penerapan_id'];

            //data for view
            $pendaftaran[$k]['url_ubah_pilihan'] = base_url() ."siswa/pendaftaran/ubahjenispilihan?pendaftaran_id=". $pendaftaran_id;
            $pendaftaran[$k]['url_ubah_jalur'] = base_url() ."siswa/pendaftaran/ubahjalur?pendaftaran_id==". $pendaftaran_id;
            $pendaftaran[$k]['url_ubah_sekolah'] = base_url() ."siswa/pendaftaran/ubahsekolah?pendaftaran_id=". $pendaftaran_id ."&penerapan_id=". $penerapan_id;
            $pendaftaran[$k]['url_hapus'] = base_url() ."siswa/pendaftaran/hapus?pendaftaran_id=". $pendaftaran_id;

            $pendaftaran[$k]['ubah_pilihan'] = ($data['batasanperubahan']['ubah_pilihan'] > 0);
            $pendaftaran[$k]['ubah_jalur'] = ($data['batasanperubahan']['ubah_jalur'] > 0);
            $pendaftaran[$k]['ubah_sekolah'] = ($data['batasanperubahan']['ubah_sekolah'] > 0);
            $pendaftaran[$k]['hapus_pendaftaran'] = ($data['batasanperubahan']['hapus_pendaftaran'] > 0);

            $pendaftaran[$k]['allow_ubah_pilihan'] = !(($data['batasansiswa']['ubah_pilihan']>=$data['batasanperubahan']['ubah_pilihan']&&$p['jenis_pilihan']!=0)||$p['pendaftaran']!=1);
            $pendaftaran[$k]['allow_ubah_jalur'] = !(($data['batasansiswa']['ubah_jalur']>=$data['batasanperubahan']['ubah_jalur'])||$p['pendaftaran']!=1);
            $pendaftaran[$k]['allow_ubah_sekolah'] = !(($data['batasansiswa']['ubah_sekolah']>=$data['batasanperubahan']['ubah_sekolah'])||$p['pendaftaran']!=1);
            $pendaftaran[$k]['allow_hapus'] = !(($data['batasansiswa']['hapus_pendaftaran']>=$data['batasanperubahan']['hapus_pendaftaran'])||$p['pendaftaran']!=1);

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

            if ($p['status_penerimaan'] == 0) $pendaftaran[$k]['label_status_penerimaan'] = "Dalam Proses Seleksi";
            else if ($p['status_penerimaan'] == 1) $pendaftaran[$k]['label_status_penerimaan'] = "Masuk Kuota";
            else if ($p['status_penerimaan'] == 2) $pendaftaran[$k]['label_status_penerimaan'] = "Tidak Masuk Kuota";
            else if ($p['status_penerimaan'] == 3) $pendaftaran[$k]['label_status_penerimaan'] = "Daftar Tunggu";
            else if ($p['status_penerimaan'] == 4) $pendaftaran[$k]['label_status_penerimaan'] = "Masuk Kuota " .$p['label_masuk_pilihan'];
            else $pendaftaran[$k]['label_status_penerimaan'] = "Dalam Proses Seleksi";

            //kelengkapan
            $kelengkapan = $this->Msiswa->tcg_kelengkapanpendaftaran($pendaftaran_id)->getResultArray();
            foreach ($kelengkapan as $k2 => $i) {
                $kelengkapan[$k2]['status_ok'] = ($i['verifikasi']==1);
                $kelengkapan[$k2]['status_notok'] = ($i['verifikasi']==2);
                $kelengkapan[$k2]['status_tidakada'] = ($i['verifikasi']==3 || ($i['verifikasi']==0 && $i['wajib']==0));
                $kelengkapan[$k2]['status_dalamproses'] = (!$kelengkapan[$k2]['status_ok'] && !$kelengkapan[$k2]['status_notok'] && !$kelengkapan[$k2]['status_tidakada']);
                $kelengkapan[$k2]['kondisi_khusus'] = ($i['kondisi_khusus']!=0);
            }
            $pendaftaran[$k]['kelengkapan'] = $kelengkapan;
            
            //berkas fisik
            $berkas = $this->Msiswa->tcg_kelengkapanpendaftaran_berkasfisik($pendaftaran_id)->getResultArray();
            foreach ($berkas as $k2 => $i) {
                $berkas[$k2]['status_ok'] = ($i['berkas_fisik']==1);
                $berkas[$k2]['status_notok'] = ($i['berkas_fisik']!=1);
                $berkas[$k2]['kondisi_khusus'] = ($i['kondisi_khusus']!=0);
            }
            $pendaftaran[$k]['berkasfisik'] = $berkas;

            //skoring
            $skoring = $this->Msiswa->tcg_nilaiskoring($pendaftaran_id)->getResultArray();
            $pendaftaran[$k]['skoring'] = $skoring;

            //total skoring
            $totalskoring = 0;
            foreach($skoring as $k2 => $s) {
                $val = floatval($s['nilai']);
                $totalskoring += $val;
            }
            $pendaftaran[$k]['totalskoring'] = number_format($totalskoring,2,".","");

            //var_dump($pendaftaran[$k]);

        }

        //var_dump($pendaftaran); exit; 
        //exit;

        $data['daftarpendaftaran'] = $pendaftaran;

        ## END DAFTAR PENDAFTARAN

        ## DAFTAR ULANG

            // $data['sekolah'] = null;
            // if ($pendaftaran_diterima != null) {
            //     $data['sekolah'] = $this->Msiswa->tcg_profilsekolah($pendaftaran_diterima['sekolah_id']);
            // }


        ## END DAFTAR ULANG

        //debugging
        $data['nama_pengguna'] = $profil['nama'];
        $data['username'] = $profil['nisn'];
        $data['profildikunci'] = 0;
        $data['cek_waktupendaftaran'] = 0;
        $data['cek_waktusosialisasi'] = 0;
        $data['pendaftarandikunci'] = 0;
        $data['kebutuhan_khusus'] = 1;
        $data['satu_zonasi_satu_jalur'] = 1;
        $data['profilsiswa']['tutup_akses'] = 0;

        foreach($data['profilflag'] as $key => $value) {
            $data['profilflag'][$key] = 1;
        }
        //end debugging

        $data['page_title'] = 'Profil Siswa';
        $this->smarty->render('ppdb/siswa/ppdbsiswa.tpl', $data);
    }

    // function json() {
    //     $peserta_didik_id = $this->session->get("pengguna_id");
    //     if ($this->session->get('peran_id') == 4) {
    //         $peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 
    //     }

	// 	$action = $_POST["action"] ?? null; 
	// 	if (empty($action) || $action=='view') {
    //         $data["error"] = "not-implemented";
	// 		echo json_encode($data);	
    //     }
	// 	else if ($action=='edit'){
    //         $data = $_POST["data"] ?? null;  

    //         $result = array();
    //         $result['data'] = array();
    //         $result['dokumen'] = array();

    //         foreach ($data as $key => $valuepair) {
    //             if (!empty($key) && $key!='null') $peserta_didik_id = $key;

    //             if (!empty($valuepair['nilai_semester'])) {
    //                 //udah ada nilai rata-rata
    //                 unset($valuepair['kelas4_sem1']);
    //                 unset($valuepair['kelas4_sem2']);
    //                 unset($valuepair['kelas5_sem1']);
    //                 unset($valuepair['kelas5_sem2']);
    //                 unset($valuepair['kelas6_sem1']);
    //                 unset($valuepair['kelas6_sem2']);
    //             }

    //             foreach($valuepair as $field => $value) {
 	// 				//Important: a bug in dt editor!!
    //                 if ($value=="" && $field=="nilai_lulus") {
    //                     unset($valuepair[$field]);
    //                     continue;
    //                 }
    //                 else if ($value=="" && 
    //                         ($field=="nilai_semester" || $field=="kelas4_sem1" || $field=="kelas4_sem2" 
    //                         || $field=="kelas5_sem1" || $field=="kelas5_sem2" || $field=="kelas6_sem1" || $field=="kelas6_sem2")
    //                 ) {
    //                     unset($valuepair[$field]);
    //                     continue;
    //                 }
                    
    //                 if ($field == 'dokumen_21') {
    //                     //surat pernyataan kebenaran dokumen
    //                     $kelengkapan_id = 21;

    //                     if ($value == "") {
    //                         $this->Msiswa->tcg_hapus_dokumen_pendukung($peserta_didik_id, $kelengkapan_id);

    //                         $result["data"][$field] = $value;
    //                         $valuepair['surat_pernyataan_kebenaran_dokumen'] = "";
    //                     }
    //                     else {
    //                         $query = $this->Msiswa->tcg_simpan_dokumen_pendukung($value, $peserta_didik_id, $kelengkapan_id,1,0,0);

    //                         $result["data"][$field] = $value;
    //                         foreach($query->getResult() as $row) {
    //                             $result['dokumen'][$row->daftar_kelengkapan_id] = array(
    //                                 "dokumen_id"=>$row->dokumen_id, 
    //                                 "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
    //                                 "verifikasi"=>'0',
    //                                 "catatan"=>'',
    //                                 "filename"=>$row->filename, 
    //                                 "file_path"=>base_url().$row->path, 
    //                                 "web_path"=>base_url().$row->web_path, 
    //                                 "thumbnail_path"=>base_url().$row->thumbnail_path, 
    //                                 "create_date"=>$row->create_date);

    //                             $valuepair['surat_pernyataan_kebenaran_dokumen'] = $row->dokumen_id;
    //                         }
    //                     }

    //                     unset($valuepair[$field]);
    //                 }
    //                 else if (substr($field, 0, 8) == 'dokumen_') {
    //                     $arr = explode("_", $field);
    //                     $kelengkapan_id = $arr[1];

    //                     if ($value == "") {
    //                         $this->Msiswa->tcg_hapus_dokumen_pendukung($peserta_didik_id, $kelengkapan_id);

    //                         $result["data"][$field] = $value;
    //                     }
    //                     else {
    //                         $query = $this->Msiswa->tcg_simpan_dokumen_pendukung($value, $peserta_didik_id, $kelengkapan_id,1,0,0);

    //                         $result["data"][$field] = $value;
    //                         foreach($query->getResult() as $row) {
    //                             if($row->daftar_kelengkapan_id == 8) {
    //                                 //ignore
    //                                 continue;

    //                                 // //dokumen prestasi
    //                                 // if(!isset($data['dokumen'][8])) {
    //                                 //     $data['dokumen'][8] = array();
    //                                 // }
    //                                 // $result['dokumen'][8][$row->dokumen_id] = array("dokumen_id"=>$row->dokumen_id, "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, "filename"=>$row->filename, "web_path"=>base_url().$row->web_path, "thumbnail_path"=>base_url().$row->thumbnail_path, "create_date"=>$row->create_date);
    //                             }
    //                             else {
    //                                 $result['dokumen'][$row->daftar_kelengkapan_id] = array(
    //                                     "dokumen_id"=>$row->dokumen_id, 
    //                                     "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
    //                                     "verifikasi"=>'0',
    //                                     "catatan"=>'',
    //                                     "filename"=>$row->filename, 
    //                                     "file_path"=>base_url().$row->path, 
    //                                     "web_path"=>base_url().$row->web_path, 
    //                                     "thumbnail_path"=>base_url().$row->thumbnail_path, 
    //                                     "create_date"=>$row->create_date);
    //                             }   
    //                         }
    //                     }

    //                     unset($valuepair[$field]);
    //                 }
    //                 else if ($field == "nilai_lulus") {
    //                     $value = floatval($value);
    //                     if ($value > 100) { $value=100; }
    //                     if ($value < 0) { $value=0; }

    //                     $retval = $this->Msiswa->tcg_ubahnilaikelulusan($peserta_didik_id, $value);

    //                     if ($retval['status'] == 0) {
    //                         $result["error"] = $retval['message'];
    //                         unset($valuepair[$field]);
    //                         break;
    //                     }
    //                     $result["data"][$field] = $value;
    //                     unset($valuepair[$field]);
    //                 }
    //                 else if ($field == "nilai_semester") {
    //                     $value = floatval($value);
    //                     if ($value > 100) { $value=100; }
    //                     if ($value < 0) { $value=0; }

    //                     $retval = $this->Msiswa->tcg_ubahnilaisemester($peserta_didik_id, $value, $value, $value, $value, $value, $value);

    //                     if ($retval['status'] == 0) {
    //                         $result["error"] = $retval['message'];
    //                         unset($valuepair[$field]);
    //                         break;
    //                     }
    //                     $result["data"][$field] = $value;
    //                     unset($valuepair[$field]);
    //                 }
    //                 else if ($field == "kelas4_sem1") {
    //                     $value = floatval($value);
    //                     if ($value > 100) { $value=100; }
    //                     if ($value < 0) { $value=0; }

    //                     $retval = $this->Msiswa->tcg_ubahnilaisemester($peserta_didik_id, $value, -1, -1, -1, -1, -1);

    //                     if ($retval['status'] == 0) {
    //                         $result["error"] = $retval['message'];
    //                         unset($valuepair[$field]);
    //                         break;
    //                     }
    //                     $result["data"][$field] = $value;
    //                     unset($valuepair[$field]);
    //                 }
    //                 else if ($field == "kelas4_sem2") {
    //                     $value = floatval($value);
    //                     if ($value > 100) { $value=100; }
    //                     if ($value < 0) { $value=0; }

    //                     $retval = $this->Msiswa->tcg_ubahnilaisemester($peserta_didik_id, -1, $value, -1, -1, -1, -1);

    //                     if ($retval['status'] == 0) {
    //                         $result["error"] = $retval['message'];
    //                         unset($valuepair[$field]);
    //                         break;
    //                     }
    //                     $result["data"][$field] = $value;
    //                     unset($valuepair[$field]);
    //                 }
    //                 else if ($field == "kelas5_sem1") {
    //                     $value = floatval($value);
    //                     if ($value > 100) { $value=100; }
    //                     if ($value < 0) { $value=0; }

    //                     $retval = $this->Msiswa->tcg_ubahnilaisemester($peserta_didik_id, -1, -1, $value, -1, -1, -1);

    //                     if ($retval['status'] == 0) {
    //                         $result["error"] = $retval['message'];
    //                         unset($valuepair[$field]);
    //                         break;
    //                     }
    //                     $result["data"][$field] = $value;
    //                     unset($valuepair[$field]);
    //                 }
    //                 else if ($field == "kelas5_sem2") {
    //                     $value = floatval($value);
    //                     if ($value > 100) { $value=100; }
    //                     if ($value < 0) { $value=0; }

    //                     $retval = $this->Msiswa->tcg_ubahnilaisemester($peserta_didik_id, -1, -1, -1, $value, -1, -1);

    //                     if ($retval['status'] == 0) {
    //                         $result["error"] = $retval['message'];
    //                         unset($valuepair[$field]);
    //                         break;
    //                     }
    //                     $result["data"][$field] = $value;
    //                     unset($valuepair[$field]);
    //                 }
    //                 else if ($field == "kelas6_sem1") {
    //                     $value = floatval($value);
    //                     if ($value > 100) { $value=100; }
    //                     if ($value < 0) { $value=0; }

    //                     $retval = $this->Msiswa->tcg_ubahnilaisemester($peserta_didik_id, -1, -1, -1, -1, $value, -1);

    //                     if ($retval['status'] == 0) {
    //                         $result["error"] = $retval['message'];
    //                         unset($valuepair[$field]);
    //                         break;
    //                     }
    //                     $result["data"][$field] = $value;
    //                     unset($valuepair[$field]);
    //                 }
    //                 else if ($field == "kelas6_sem2") {
    //                     $value = floatval($value);
    //                     if ($value > 100) { $value=100; }
    //                     if ($value < 0) { $value=0; }

    //                     $retval = $this->Msiswa->tcg_ubahnilaisemester($peserta_didik_id, -1, -1, -1, -1, -1, $value);

    //                     if ($retval['status'] == 0) {
    //                         $result["error"] = $retval['message'];
    //                         unset($valuepair[$field]);
    //                         break;
    //                     }
    //                     $result["data"][$field] = $value;
    //                     unset($valuepair[$field]);
    //                 }
    //                 else if ($field == "nilai_bin") {
    //                     $value = floatval($value);
    //                     if ($value > 100) { $value=100; }
    //                     if ($value < 0) { $value=0; }

    //                     $retval = $this->Msiswa->tcg_ubahnilaiusbn($peserta_didik_id, $value, -1, -1);

    //                     if ($retval['status'] == 0) {
    //                         $result["error"] = $retval['message'];
    //                         unset($valuepair[$field]);
    //                         break;
    //                     }
    //                     $result["data"][$field] = $value;
    //                     unset($valuepair[$field]);
    //                 }
    //                 else if ($field == "nilai_mat") {
    //                     $value = floatval($value);
    //                     if ($value > 100) { $value=100; }
    //                     if ($value < 0) { $value=0; }

    //                     $retval = $this->Msiswa->tcg_ubahnilaiusbn($peserta_didik_id, -1, $value, -1);

    //                     if ($retval['status'] == 0) {
    //                         $result["error"] = $retval['message'];
    //                         unset($valuepair[$field]);
    //                         break;
    //                     }
    //                     $result["data"][$field] = $value;
    //                     unset($valuepair[$field]);
    //                 }
    //                 else if ($field == "nilai_ipa") {
    //                     $value = floatval($value);
    //                     if ($value > 100) { $value=100; }
    //                     if ($value < 0) { $value=0; }

    //                     $retval = $this->Msiswa->tcg_ubahnilaiusbn($peserta_didik_id, -1, -1, $value);

    //                     if ($retval['status'] == 0) {
    //                         $result["error"] = $retval['message'];
    //                         unset($valuepair[$field]);
    //                         break;
    //                     }
    //                     $result["data"][$field] = $value;
    //                     unset($valuepair[$field]);
    //                 }
    //                 else if ($field == "punya_nilai_un") {
    //                     if ($value == 0) {
    //                         $this->Msiswa->tcg_ubahnilaiusbn($peserta_didik_id, 0, 0, 0);
    //                     }
    //                 }
    //                 else if ($field == "punya_prestasi") {
    //                     //handled separately
    //                     if ($value == 0) {

    //                     }
    //                 }
    //                 else if ($field == "punya_kip") {
    //                     if ($value == 0) {
    //                         $valuepair["no_kip"] = null;
    //                     }
    //                 }
    //                 else if ($field == "masuk_bdt") {
    //                     if ($value == 0) {
    //                         $valuepair["no_bdt"] = null;
    //                     }
    //                 }
    //                 else if ($field == "nomor_bdt") {
    //                     if ($value == "-" || $value == "") {
    //                         $valuepair["punya_bdt"] = 0;
    //                         $valuepair["no_bdt"] = null;
    //                         unset($valuepair["nomor_bdt"]);
    //                     } 
    //                     else {
    //                         $valuepair["no_bdt"] = $value;
    //                         unset($valuepair["nomor_bdt"]);
    //                     }
    //                 }
    //                 else if ($field == "nomor_kip") {
    //                     if ($value == "-" || $value == "") {
    //                         $valuepair["punya_kip"] = 0;
    //                         $valuepair["no_kip"] = null;
    //                         unset($valuepair["nomor_kip"]);
    //                     } 
    //                     else {
    //                         $valuepair["no_kip"] = $value;
    //                         unset($valuepair["nomor_kip"]);
    //                     }
    //                 }
    //             }

    //             //update the profil
    //             if (count($valuepair) > 0) {
    //                 $this->Msiswa->tcg_ubah_profil_siswa($peserta_didik_id,$valuepair);

    //                 foreach($valuepair as $field => $value) {
    //                     $result["data"][$field] = $value;
    //                 }
    //             }
    //         }

    //         echo json_encode($result);	
    //     }
	// 	else if ($action=='remove'){
    //         $data["error"] = "not-implemented";
	// 		echo json_encode($data);	
    //     }
	// 	else if ($action=='create'){
    //         $data["error"] = "not-implemented";
	// 		echo json_encode($data);	
    //     }
    //     else if ($action == "upload") {

    //         $key = $_POST["uploadField"] ?? null; 
    //         $arr = explode("_", $key);

    //         $kelengkapan_id=0;
    //         if (count($arr) > 1) {
    //             $kelengkapan_id = $arr[1];
    //         }

    //         $data = array();
    //         if ($kelengkapan_id == 0) {
    //             $data["error"] = "Kode dokumen tidak dikenal";
    //             echo json_encode($data);
    //             return;
    //         }

    //         $uploader = new Uploader();
    //         $fileObj = $uploader->upload($_FILES['upload']);

    //         if(!empty($fileObj['error'])) {
    //             $data['error'] = $fileObj['error'];
    //         } else {
    //             $data = array("data"=>array(),"files"=>array("files"=>array($fileObj['id']=>$fileObj)),"upload"=>array("id"=>$fileObj['id']));
    //         }

    //         echo json_encode($data);
    //         return;
    //      }   
    //      else if ($action == "rotate") {
    //         $dokumen_id = $_POST["dokumen_id"] ?? null;
    //         $degree = $_POST["degree"] ?? null;

    //         $result = array();
    //         $query = $this->Msiswa->tcg_detil_dokumen_pendukung($dokumen_id);
            
    //         //$result['dokumen_id'] = $dokumen_id;
    //         //$result['data'] = $query->getResultArray();

    //         $uploader = new Uploader();
    //         foreach($query->getResult() as $row) {
    //             $webpath = $row->web_path;
    //             $thumbpath = $row->thumbnail_path;

    //             $ext = pathinfo($webpath, PATHINFO_EXTENSION);
    //             $dirname = pathinfo($webpath, PATHINFO_DIRNAME);

    //             $filename_baru = rand(). time();
    //             $webpath_baru = $dirname. "/". $filename_baru. ".". $ext;
    //             $thumbpath_baru = $dirname. "/". $filename_baru. "_thumb.". $ext;

    //             $msg = $uploader->imagerotate(FCPATH. $webpath, FCPATH. $webpath_baru, $degree);
    //             if ($msg != "") {
    //                 $result['error'] = $msg;
    //                 break;
    //             }
   
    //             $msg = $uploader->imagerotate(FCPATH. $thumbpath, FCPATH. $thumbpath_baru, $degree);
    //             if ($msg != "") {
    //                 $result['error'] = $msg;
    //                 break;
    //             }
   
    //             //update the file
    //             $valuepair = array(
    //                 'path' => $webpath_baru,
    //                 'web_path' => $webpath_baru,
    //                 'thumbnail_path' => $thumbpath_baru
    //             );
    //             $this->Msiswa->tcg_ubah_dokumen_pendukung($dokumen_id, $valuepair);

    //             //return the new data
    //             $result['data'][$dokumen_id] = array(
    //                 "dokumen_id"=>$row->dokumen_id, 
    //                 "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
    //                 "verifikasi"=>'0',
    //                 "catatan"=>'',
    //                 "filename"=>$row->filename, 
    //                 "file_path"=>base_url().$webpath_baru, 
    //                 "web_path"=>base_url().$webpath_baru, 
    //                 "thumbnail_path"=>base_url().$thumbpath_baru, 
    //                 "create_date"=>$row->create_date);

    //             // //remove old files
    //             // if (!empty($webpath) && ile_exists(FCPATH. $webpath))
    //             //     unlink(FCPATH. $webpath);

    //             // if (!empty($webpath) && ile_exists(FCPATH. $thumbpath))
    //             //     unlink(FCPATH. $thumbpath);

    //         }

    //         echo json_encode($result);
    //         return;
    //      }
    // }

	function suratpernyataan() {
		$peserta_didik_id = $this->session->get("pengguna_id");
        if ($this->session->get('peran_id') == 4) {
            $peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 
        }

        $username = $this->session->get("username");
		$peran_id = $this->session->get("peran_id");
		$nisn = $this->session->get("nisn");
		
        $qrcode = new QRCodeLibrary();
		$config['cacheable'] = true; //boolean, the default is true
        // $config['cachedir'] = './qrcode/'; //string, the default is application/cache/
        // $config['errorlog'] = './qrcode/'; //string, the default is application/logs/
        // $config['imagedir'] = './qrcode/images/'; //direktori penyimpanan qr code
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
		
		$data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);
        //$data['daftarpendaftaran'] = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id);
        
        $tgl_mulai_pendaftaran = date('Y-m-d');
        $query = $this->Msetting->tcg_waktupendaftaran();
        foreach($query->getResult() as $row) {
            $tgl = date( 'Y-m-d', strtotime($row->tanggal_mulai_aktif));
            if ($tgl > $tgl_mulai_pendaftaran) {
                $tgl_mulai_pendaftaran = $tgl;
            }
        }

        $data['tanggal_pernyataan'] = $tgl_mulai_pendaftaran;
        $data['tahun_ajaran_aktif'] = $this->session->get('tahun_ajaran_aktif');

        $view = \Config\Services::renderer();
		$html = $view->render('siswa/beranda/suratpernyataan',$data);
		
        //$this->response->removeHeader('Content-Type'); 

		$dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("SuratPernyataan.pdf", array("Attachment"=>0));
        exit(); 
	}

    //riwayat verifikasi (view only)
	function riwayat() {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

        $peserta_didik_id = $this->session->get("pengguna_id");
        if ($this->session->get('peran_id') == 4) {
            $peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 
        }

		$action = $_POST["action"] ?? null; 
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Msiswa->tcg_riwayat_verifikasi($peserta_didik_id)->getResultArray(); 
			echo json_encode($data);	
		}
		else {
			$data['error'] = "not-implemented";
			echo json_encode($data);	
		}
	}
    
    //daftar prestasi (view/edit/add/delete)
    function prestasi() {
        $peserta_didik_id = $this->session->get("pengguna_id");
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

    //upload dokumen
    function upload() {

    }
    
    //pilihan untuk perubahan
    function pilihan() {
        $pendaftaran_id = 0;
        $action = '';   //ubahpilihan | ubahsekolah | ubahjalur | ubahpilihan

    }

    //ubah pendaftaran
    function ubah() {
        $pendaftaran_id = 0;
        $action = '';
    }
    
    //pilihan sekolah
    function sekolah() {
        $penerapan_id = 0;
    }

    //lakukan pendaftaran
    function daftar() {
        $penerapan_id = 0;
        $sekolah_id = 0;
        $jenis_pilihan = 0;

    }
}
?>
