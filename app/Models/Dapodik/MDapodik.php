<?php 

namespace App\Models\Dapodik;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Session\Session;
use App\Libraries\Uploader;
use App\Libraries\Setting;
use Exception;

defined('API_CLIENT_ID')        || define('API_CLIENT_ID', '80408b6378d03a49d7c0a6d5');
defined('API_CLIENT_SECRET')    || define('API_CLIENT_SECRET', '1b1322bf890e9b8c6b12ffb9523fc0a72421ba7b89e6b7120b2d6e76ca9037a5');
defined('API_KEY')              || define('API_KEY','7EAC850A-CDA0-4140-A308-06FA0024B625');

class MDapodik
{
    protected static $TIMEOUT_SEC = 120;

    protected Session $session;

    protected string $token = "";
    protected int $error_code = 0;
    protected $error_message = null;

    protected $logger = null;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->logger = new \App\Libraries\Monolog();
    }  
    
    public function tarikdatasiswa() {
        $this->reset_error();

        $this->logger->log('info', "Get token akses API");
        $this->token = $this->getToken();
        if (empty($this->token)) {
            $this->set_error("Gagal mendapatkan token akses.", 500, 1);
        }
        //$this->logger->log('info', "Akses token: " . $this->token);

        $this->logger->log('info', "Tarik data referensi wilayah");
        $wilayah = $this->getReferensiWilayah();
        if (empty($wilayah)) {
            $this->set_error("Gagal mendapatkan referensi wilayah.", 500, 1);
        }

        $msekolah = new MDapodikSekolah();
        $msiswa = new MDapodikSiswa(); 

        $msekolah->truncate();
        $msiswa->truncate();

        foreach ($wilayah as $w) {
            $this->logger->log('info', "Tarik data sekolah di wilayah: " . $w['nama']);

            $sekolah = $this->getSekolahByWilayah($w['kode_wilayah']);
            if (empty($sekolah)) {
                $this->logger->log('warning', "Tidak ada data sekolah ditemukan untuk wilayah: " . $w['nama']);
                continue;
            } else {
                $this->logger->log('info', "Jumlah sekolah ditemukan: " . count($sekolah));
            }

            //simpan data sekolah ke database
            $msekolah->addbatch($sekolah);

            foreach ($sekolah as $s) {
                //skip data siswa SMP
                if ($s['bentuk_pendidikan_id'] == 6) {
                    $this->logger->log('info', "Lewati tarik data siswa untuk sekolah: " . $s['nama']);
                    continue;
                }

                $this->logger->log('info', "Tarik data siswa di sekolah: " . $s['nama']);
                $siswa = $this->getSiswaBySekolah($s['npsn']);
                if (empty($siswa)) {
                    $this->logger->log('warning', "Gagal mendapatkan daftar siswa untuk sekolah: " . $s['nama']);
                    continue;
                } else {
                    $this->logger->log('info', "Jumlah siswa di sekolah: " . count($siswa));
                }

                //simpan data siswa ke database
                $msiswa->addbatch($siswa);
            }
        }

        return 1;
    }

    public function tarikulang_datasiswa() {
        $this->reset_error();

        $msekolah = new MDapodikSekolah();
        $msiswa = new MDapodikSiswa(); 

        $sekolah = $msekolah->sekolah_tidakadasiswa();

        if (empty($sekolah)) {
            $this->logger->log('info', "Semua sekolah sudah memiliki data siswa");
            return 1;
        } 
            
        $this->logger->log('info', "Jumlah sekolah yang belum memiliki data siswa: " . count($sekolah));
 
        foreach ($sekolah as $s) {
            $this->logger->log('info', "Tarik data siswa di sekolah: " . $s['nama']);
            $siswa = $this->getSiswaBySekolah($s['npsn']);
            if (empty($siswa)) {
                $this->logger->log('warning', "Gagal mendapatkan daftar siswa untuk sekolah: " . $s['nama']);
                continue;
            } else {
                $this->logger->log('info', "Jumlah siswa di sekolah: " . count($siswa));
            }

            //simpan data siswa ke database
            $msiswa->addbatch($siswa);
        }
 
        return 1;
    }

    public function tarikdatasiswa_tambahan() {
        $this->reset_error();

        $msekolah = new MDapodikSekolah();
        $msiswa = new MDapodikSiswa(); 

        $sekolah = $msekolah->missing_sekolah();

        if (empty($sekolah)) {
            $this->logger->log('info', "Tidak ada sekolah tambahan");
            return 1;
        } 
            
        $this->logger->log('info', "Jumlah sekolah tambahan: " . count($sekolah));
 
        foreach ($sekolah as $s) {
            $this->logger->log('info', "Tarik data siswa di sekolah: " . $s['nama']);
            $siswa = $this->getSiswaBySekolah($s['npsn']);
            if (empty($siswa)) {
                $this->logger->log('warning', "Gagal mendapatkan daftar siswa untuk sekolah: " . $s['nama']);
                continue;
            } else {
                $this->logger->log('info', "Jumlah siswa di sekolah: " . count($siswa));
            }

            //simpan data siswa ke database
            if (!empty($siswa)) {
                $msiswa->addbatch($siswa);
            }
        }
 
        return 1;
    }

    public function tarikdatatka() {
        $mnilaitka = new MDapodikTka();
        $mnilaitka->truncate();

        //get sekolah SD
        $this->tarikdatatka_sd();
        $this->tarikdatatka_mi();

        return 1;
    }

    public function tarikdatatka_sd() {
        //get sekolah SD
        $filter = array();
        $filter["bentuk_pendidikan_id"] = 5; //SD
        $msekolah = new MDapodikSekolah();
        $sekolah = $msekolah->list(0, null, $filter);

        $msiswa = new MDapodikSiswa();
        $mnilaitka = new MDapodikTka();

        foreach ($sekolah as $s) {
            $this->logger->log('info', "Tarik data TKA di sekolah: " . $s['nama']);

            //get daftar siswa
            $filter = array();
            $filter['sekolah_id'] = $s['sekolah_id'];
            $siswa = $msiswa->list(0, null, $filter);

            if (empty($siswa)) {
                $this->logger->log('warning', "Gagal mendapatkan daftar siswa untuk sekolah: " . $s['nama']);
                continue;
            } else {
                $this->logger->log('info', "Jumlah siswa di sekolah: " . count($siswa));
            }

            foreach ($siswa as $p) {
                //$this->logger->log('info', "Tarik data TKA untuk siswa: " . $p['nama']);

                $nilaitka = $this->getTkaSiswa($p['nisn'], $p['tanggal_lahir']);
                if (empty($nilaitka)) {
                    $this->logger->log('warning', "Tidak ada data nilai TKA untuk siswa: " . $p['nama']);
                    continue;
                }

                //simpan data sekolah ke database
                $mnilaitka->addbatch($nilaitka);
            }
        }

        return 1;
    }

    public function tarikdatatka_mi() {
        //get sekolah SD
        $filter = array();
        $filter["bentuk_pendidikan_id"] = 9; //MI
        $msekolah = new MDapodikSekolah();
        $sekolah = $msekolah->list(0, null, $filter);

        $msiswa = new MDapodikSiswa();
        $mnilaitka = new MDapodikTka();

        foreach ($sekolah as $s) {
            $this->logger->log('info', "Tarik data TKA di sekolah: " . $s['nama']);

            //get daftar siswa
            $filter = array();
            $filter['sekolah_id'] = $s['sekolah_id'];
            $siswa = $msiswa->list(0, null, $filter);

            if (empty($siswa)) {
                $this->logger->log('warning', "Gagal mendapatkan daftar siswa untuk sekolah: " . $s['nama']);
                continue;
            } else {
                $this->logger->log('info', "Jumlah siswa di sekolah: " . count($siswa));
            }

            foreach ($siswa as $p) {
                //$this->logger->log('info', "Tarik data TKA untuk siswa: " . $p['nama']);

                $nilaitka = $this->getTkaSiswa($p['nisn'], $p['tanggal_lahir']);
                if (empty($nilaitka)) {
                    $this->logger->log('warning', "Tidak ada data nilai TKA untuk siswa: " . $p['nama']);
                    continue;
                }

                //simpan data sekolah ke database
                $mnilaitka->addbatch($nilaitka);
            }
        }

        return 1;
    }

    protected function tarikdatatka_all() {
        //get sekolah SD

        $msekolah = new MDapodikSekolah();
        $msiswa = new MDapodikSiswa();
        $mnilaitka = new MDapodikTka();

        $sekolah = $msekolah->sekolah_tidakadatka();

        foreach ($sekolah as $s) {
            $this->logger->log('info', "Tarik data TKA di sekolah: " . $s['nama']);

            //get daftar siswa
            $filter = array();
            $filter['sekolah_id'] = $s['sekolah_id'];
            $siswa = $msiswa->list(0, null, $filter);

            if (empty($siswa)) {
                $this->logger->log('warning', "Gagal mendapatkan daftar siswa untuk sekolah: " . $s['nama']);
                continue;
            } else {
                $this->logger->log('info', "Jumlah siswa di sekolah: " . count($siswa));
            }

            foreach ($siswa as $p) {
                //$this->logger->log('info', "Tarik data TKA untuk siswa: " . $p['nama']);

                $nilaitka = $this->getTkaSiswa($p['nisn'], $p['tanggal_lahir']);
                if (empty($nilaitka)) {
                    $this->logger->log('warning', "Tidak ada data nilai TKA untuk siswa: " . $p['nama']);
                    continue;
                }

                //simpan data sekolah ke database
                $mnilaitka->addbatch($nilaitka);
            }
        }

        return 1;
    }

    public function tarikulang_datatka() {

        $msiswa = new MDapodikSiswa();
        $mnilaitka = new MDapodikTka();

        $siswa = $msiswa->siswa_tidakadatka();

        if (empty($siswa)) {
            $this->logger->log('info', "Semua siswa sudah memiliki data TKA");
            return 1;
        } 
            
        $this->logger->log('info', "Jumlah siswa yang belum memiliki data TKA: " . count($siswa));

        foreach ($siswa as $p) {
            $this->logger->log('info', "Tarik data TKA untuk siswa: " . $p['nama']);

            $nilaitka = $this->getTkaSiswa($p['nisn'], $p['tanggal_lahir']);
            if (empty($nilaitka)) {
                $this->logger->log('warning', "Tidak ada data nilai TKA untuk siswa: " . $p['nama']);
                continue;
            }

            //simpan data sekolah ke database
            $mnilaitka->addbatch($nilaitka);
        }

        return 1;
    }

    public function tarikdataprestasi() {
        $mprestasi = new MDapodikPrestasi();
        $mprestasi->truncate();

        //get sekolah SD
        $this->tarikdataprestasi_sd();
        $this->tarikdataprestasi_mi();

        return 1;
    }

    public function tarikdataprestasi_sd() {
        //get sekolah SD
        $filter = array();
        $filter["bentuk_pendidikan_id"] = 5; //SD
        // $filter['like::kode_wilayah'] = '030512%'; 
        $msekolah = new MDapodikSekolah();
        $sekolah = $msekolah->list(0, null, $filter);

        $msiswa = new MDapodikSiswa();
        $mprestasi = new MDapodikPrestasi();

        foreach ($sekolah as $s) {
            $this->logger->log('info', "Tarik data Prestasi di sekolah: " . $s['nama']);

            //get daftar siswa
            $filter = array();
            $filter['sekolah_id'] = $s['sekolah_id'];
            $siswa = $msiswa->list(0, null, $filter);

            if (empty($siswa)) {
                $this->logger->log('warning', "Gagal mendapatkan daftar siswa untuk sekolah: " . $s['nama']);
                continue;
            } else {
                $this->logger->log('info', "Jumlah siswa di sekolah: " . count($siswa));
            }

            foreach ($siswa as $p) {
                $this->logger->log('debug', "Tarik data Prestasi untuk siswa: " . $p['nama']);

                $prestasi = $this->getPrestasiSiswa($p['nisn'], $s['npsn']);
                if (empty($prestasi) || 
                        (count($prestasi) == 1 && isset($prestasi[0]['nama_ajang']) && $prestasi[0]['nama_ajang'] == "NA")) {
                    $this->logger->log('debug', "Tidak ada data prestasi untuk siswa: " . $p['nama']);
                }
                else {
                    $this->logger->log('info', "Data prestasi ditemukan untuk siswa: " . $p['nama']);
                }

                //simpan data sekolah ke database
                $mprestasi->addbatch($prestasi);
            }
        }

        return 1;
    }

    public function tarikdataprestasi_mi() {
        //get sekolah MI
        $filter = array();
        $filter["bentuk_pendidikan_id"] = 9; //MI
        $msekolah = new MDapodikSekolah();
        $sekolah = $msekolah->list(0, null, $filter);

        $msiswa = new MDapodikSiswa();
        $mprestasi = new MDapodikPrestasi();

        foreach ($sekolah as $s) {
            $this->logger->log('info', "Tarik data Prestasi di sekolah: " . $s['nama']);

            //get daftar siswa
            $filter = array();
            $filter['sekolah_id'] = $s['sekolah_id'];
            $siswa = $msiswa->list(0, null, $filter);

            if (empty($siswa)) {
                $this->logger->log('warning', "Gagal mendapatkan daftar siswa untuk sekolah: " . $s['nama']);
                continue;
            } else {
                $this->logger->log('info', "Jumlah siswa di sekolah: " . count($siswa));
            }

            foreach ($siswa as $p) {
                $this->logger->log('debug', "Tarik data Prestasi untuk siswa: " . $p['nama']);

                $prestasi = $this->getPrestasiSiswa($p['nisn'], $s['npsn']);
                if (empty($prestasi) || 
                        (count($prestasi) == 1 && isset($prestasi[0]['nama_ajang']) && $prestasi[0]['nama_ajang'] == "NA")) {
                    $this->logger->log('debug', "Tidak ada data prestasi untuk siswa: " . $p['nama']);
                }
                else {
                    $this->logger->log('info', "Data prestasi ditemukan untuk siswa: " . $p['nama']);
                }
 
                //simpan data sekolah ke database
                $mprestasi->addbatch($prestasi);
            }
        }

        return 1;
    }

    public function tarikulang_dataprestasi() {

        $msiswa = new MDapodikSiswa();
        $mprestasi = new MDapodikPrestasi();

        $siswa = $msiswa->siswa_belumtarikprestasi();

        if (empty($siswa)) {
            $this->logger->log('info', "Semua siswa sudah tarik data prestasi");
            return 1;
        } 
            
        $this->logger->log('info', "Jumlah siswa yang belum tarik data prestasi: " . count($siswa));

        foreach ($siswa as $p) {
            $this->logger->log('info', "Tarik data Prestasi untuk siswa: " . $p['nama']);

            $prestasi = $this->getPrestasiSiswa($p['nisn'], $p['npsn']);
            if (empty($prestasi)) {
                $this->logger->log('warning', "Tidak ada data Prestasi untuk siswa: " . $p['nama']);
                continue;
            }

            //simpan data sekolah ke database
            $mprestasi->addbatch($prestasi);
        }

        return 1;
    }

    public function update_registrasisiswa() {
        $msiswa = new MPesertaDidik();
        $msekolah = new MSekolah();

        //echo "Testing";
        $siswa = $msiswa->siswa_registrasi();

        if (empty($siswa)) {
            //$this->logger->log('info', "Tidak ada siswa registrasi");
            echo "Tidak ada siswa registrasi" . PHP_EOL;
            return 1;
        } 
            
        //$this->logger->log('info', "Jumlah siswa registrasi: " . count($siswa));
        echo "Jumlah siswa registrasi: " . count($siswa) . PHP_EOL;

        foreach ($siswa as $p) {
            //$this->logger->log('info', "Update siswa: " . $p['nama']);
            echo "Update siswa: " . $p['nama'] . PHP_EOL;

            $profil = $this->getSiswaByNisnDanNpsn($p['nisn'], $p['npsn_sekolah_asal']);
            if (empty($profil)) {
                $profil = $this->getSiswaByNisnDanTglLahir($p['nisn'], $p['tanggal_lahir']);
                if (empty($profil)) {
                    $this->logger->log('warning', "Tidak ada data siswa: " . $p['nama']);
                    continue;
                }
            }

            if (empty($profil)) continue;

            $profil = $profil[0];

            //update data sekolah
            $updated = array();
            $updated['npsn'] = $profil['npsn'];
            $updated['nama'] = $profil['nama_sekolah'];
            $updated['bentuk'] = $profil['bentuk_pendidikan'];
            $updated['status'] = $profil['status_sekolah'] == 1 ? 'N' : 'S';
            $updated['jenjang_id'] = 2;
            $updated['kode_wilayah'] = $profil['kode_wilayah']; 
            $updated['dapodik_id'] = $profil['sekolah_id']; 

            $sekolah_id = $msekolah->getSekolahIdByNpsn($profil['npsn']);
            if (empty($sekolah_id)) {
                //create new entry sekolah
                //$this->logger->log('info', "Create new sekolah: " . $updated['nama']);
                echo "Create new sekolah: " . $updated['nama'] . PHP_EOL;

                $result = $msekolah->add($updated);
                $sekolah_id = $result['sekolah_id'];
            }
            else {
                //update data sekolah
                $msekolah->update($sekolah_id, $updated);
            }

            //update data siswa ke database
            $updated = array();
            $updated['nama'] = $profil['nama'];
            $updated['jenis_kelamin'] = $profil['jenis_kelamin'];
            $updated['nama_ibu_kandung'] = $profil['nama_ibu_kandung'];
            $updated['tanggal_lahir'] = substr($profil['tanggal_lahir'], 0, 10);
            $updated['tempat_lahir'] = $profil['tempat_lahir'];
            $updated['sekolah_id'] = $sekolah_id;
            $updated['kebutuhan_khusus'] = $profil['kebutuhan_khusus'];
            $updated['npsn_sekolah_asal'] = $profil['npsn'];
            $updated['nama_sekolah_asal'] = $profil['nama_sekolah'];

            $msiswa->update($p['peserta_didik_id'], $updated);
        }

        return 1;

    }

    public function kirimdatabalikan() {
        $this->reset_error();

        $this->logger->log('info', "Get token akses API");
        $this->token = $this->getToken();
        if (empty($this->token)) {
            $this->set_error("Gagal mendapatkan token akses.", 500, 1);
        }
        //$this->logger->log('info', "Akses token: " . $this->token);

        $mbalikan = new MDataBalikan(); 
        $siswa = $mbalikan->siswa_belumsync();

        $cnt = count($siswa);
        $i = 1;

        $this->logger->log('info', "Jumlah data untuk dikirim: " .$cnt);

        foreach ($siswa as $s) {
            if (empty($s['nik']) || empty($s['kode_wilayah_siswa'])) {
                $i++;
                continue;
            }

            $id = $s['peserta_didik_id'];

            if (empty($s['agama_id'])) {
                $s['agama_id'] = 1; //default agama Islam
            }

            if (empty($s['rt'])) {
                $s['rt'] = "1"; 
            }
            $s['rt'] = str_replace("RT.", "", strtoupper($s['rt']));
            $s['rt'] = str_replace("RT", "", strtoupper($s['rt']));
            $s['rt'] = trim($s['rt']);

            if (empty($s['rw'])) {
                $s['rw'] = "1"; 
            }
            $s['rw'] = str_replace("RW.", "", strtoupper($s['rw']));
            $s['rw'] = str_replace("RW", "", strtoupper($s['rw']));
            $s['rw'] = trim($s['rw']);

            if (empty($s['alamat_jalan'])) {
                $s['alamat_jalan'] = "NA"; 
            }

            //this is internal id
            unset($s['peserta_didik_id']);

            //sync
            $this->logger->log('info', "(" .$i++. " of " .$cnt. "): " . $s['nama']);
            $return_id = $this->syncSiswa($s);
            if (empty($return_id)) {
                $this->logger->log('warning', "Status: FAILED!");
                continue;
            }
           
            $this->logger->log('warning', "Return ID: " .$return_id);

            //simpan data siswa ke database
            $mbalikan->update_syncstatus($id, 1, $return_id);
        }

        return 1;
    }

    public function tarik_agama() {
        $this->reset_error();

        $this->logger->log('info', "Get token akses API");
        $this->token = $this->getToken();
        if (empty($this->token)) {
            $this->set_error("Gagal mendapatkan token akses.", 500, 1);
        }
        //$this->logger->log('info', "Akses token: " . $this->token);

        $mbalikan = new MDataBalikan(); 
        $siswa = $mbalikan->siswa_agamainvalid();

        $cnt = count($siswa);
        $i = 1;

        $this->logger->log('info', "Jumlah data untuk ditarik: " .$cnt);

        foreach ($siswa as $s) {
            if (empty($s['nisn'])) {
                $i++;
                continue;
            }

            //get
            $this->logger->log('info', "(" .$i++. " of " .$cnt. "): " . $s['nama']);
            $val = $this->getSiswaByNisnDanTglLahir($s['nisn'], $s['tanggal_lahir']);
            if (empty($val)) {
                $this->logger->log('warning', "Status: FAILED!");
                continue;
            }
            if (count($val) > 0) {
                $val = $val[0];
            }
           
            $this->logger->log('warning', "Agama ID: " .$val['agama_id']);

            //simpan data siswa ke database
            $mbalikan->update_agama($s['peserta_didik_id'], $val['agama_id']);
        }

        return 1;
    }

    public function getToken() {
        $this->reset_error();
        
        $url = "https://api.spl.kemendikdasmen.go.id/spl/service/token";

        $data = [
            "client_id" => API_CLIENT_ID,
            "client_secret" => API_CLIENT_SECRET
        ];

        $this->logger->log('debug', "POST: \n" .$url);

        $prettystr = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->logger->log('debug', "DATA: \n" .$prettystr);

        $curl = curl_init($url);

        $fp = fopen(WRITEPATH .'logs/curl-errorlog.txt', 'w');
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_STDERR, $fp);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);

        // Return the transfer as a string instead of outputting it directly
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // *** Key option: Follow redirects ***
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        // Optional: Set a maximum number of redirects (default is 50, here limited to 5)
        curl_setopt($curl, CURLOPT_MAXREDIRS, 5);

        // Set a User-Agent string to mimic a browser
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');

        // Set the connection timeout
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, static::$TIMEOUT_SEC);

        // Set the total operation timeout
        curl_setopt($curl, CURLOPT_TIMEOUT, static::$TIMEOUT_SEC);

        $headers = [    
            "Content-Type: application/json",
            "Accept: application/json"
        ];          
                        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $json = json_encode($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        
        $resp = curl_exec($curl);
        curl_close($curl);
        
        $json = json_decode($resp, true);
        if (empty($json)) {
            $this->logger->log('debug', "RESPONSE: \n" .$resp);
            $this->set_error('API error: invalid response', -1, 0);
            return null;
        }

        $prettystr = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->logger->log('debug', "RESPONSE: \n" .$prettystr);

        //var_dump($json); exit;

        if (isset($json['status']) && $json['status'] == 200 && isset($json['data'])) {
            return $json['data']['access_token'];
        } else {
            return null;
        }

    }

    public function getReferensiWilayah(string $token = "") {
        $this->reset_error();
        
        if (empty($token)) {
            if (!empty($this->token)) {
                $token = $this->token;
            } else {
                $this->logger->log('info', "Get token akses API");
                $this->token = $this->getToken();
                if (empty($this->token)) {
                    $this->set_error("Gagal mendapatkan token akses.", 500, 1);
                    return null;
                }
                //$this->logger->log('info', "Akses token: " . $this->token);
                $token = $this->token;
            }
            $token = $this->token;
        }

        $url = "https://api.spl.kemendikdasmen.go.id/layanan/peserta-didik/spmb/referensi-wilayah";

        $queryParams = [
            'api_key' => API_KEY
        ];

        //build the full URL with the query string
        $url .= '?' . http_build_query($queryParams);

        $this->logger->log('info', "GET: \n" .$url);

        //send
        $curl = curl_init();

        $fp = fopen(WRITEPATH .'logs/curl-errorlog.txt', 'w');
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_STDERR, $fp);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 

        // Return the transfer as a string instead of outputting it directly
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // *** Key option: Follow redirects ***
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        // Optional: Set a maximum number of redirects (default is 50, here limited to 5)
        curl_setopt($curl, CURLOPT_MAXREDIRS, 5);

        // Set a User-Agent string to mimic a browser
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');

        // Set the connection timeout
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, static::$TIMEOUT_SEC);

        // Set the total operation timeout
        curl_setopt($curl, CURLOPT_TIMEOUT, static::$TIMEOUT_SEC);

        $headers = array(
            "Authorization: Bearer " .$this->token,
            "Content-Type: application/json",
            "X-Client-Id: " .API_CLIENT_ID
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        //Get the response  and response code
        $resp = curl_exec($curl); 
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
       
        //check the response code
        if ($http_code == 401) {
            //not authorized, token might be expired or invalid
            //try to re-call the function but force to re-authenticate and get new token
            $this->token = "";
            return $this->getReferensiWilayah();
        }
        else if ($http_code !== 200) {
            $this->set_error('API error: ' .curl_error($curl), curl_errno($curl), 0);
            return null;
        }

        // Check the return value of curl_exec(), too
        if ($resp === false) {
            $this->set_error('API error: ' .curl_error($curl), curl_errno($curl), 0);
            return null;
        }
        curl_close($curl);

        //$this->logger->log('debug', "RESPONSE: " .$resp, array("MIF"));
        
        $json = json_decode($resp, true);
        if (empty($json)) {
            $this->logger->log('debug', "RESPONSE: \n" .$resp, array("MIF"));
            $this->set_error('API error: invalid response', -1, 0);
            return null;
        }

        $prettystr = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->logger->log('debug', "RESPONSE: \n" .$prettystr, array("MIF"));

        if (isset($json['status']) && $json['status'] === 200 && isset($json['data'])) {
            return $json['data'];
        } else {
            return null;
        }
    }

    public function getSekolahByWilayah(string $kodewilayah, string $token = "") {
        $this->reset_error();

        if (empty($token)) {
            if (!empty($this->token)) {
                $token = $this->token;
            } else {
                $this->logger->log('info', "Get token akses API");
                $this->token = $this->getToken();
                if (empty($this->token)) {
                    $this->set_error("Gagal mendapatkan token akses.", 500, 1);
                    return null;
                }
                //$this->logger->log('info', "Akses token: " . $this->token);
                $token = $this->token;
            }
            $token = $this->token;
        }

        $url = "https://api.spl.kemendikdasmen.go.id/layanan/peserta-didik/spmb/sekolah-by-wilayah";

        $queryParams = [
            'api_key' => API_KEY,
            'kode_wilayah' => $kodewilayah
        ];

        //build the full URL with the query string
        $url .= '?' . http_build_query($queryParams);

        $this->logger->log('info', "GET: \n" .$url);

        //send
        $curl = curl_init();

        $fp = fopen(WRITEPATH .'logs/curl-errorlog.txt', 'w');
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_STDERR, $fp);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 

        // Return the transfer as a string instead of outputting it directly
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // *** Key option: Follow redirects ***
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        // Optional: Set a maximum number of redirects (default is 50, here limited to 5)
        curl_setopt($curl, CURLOPT_MAXREDIRS, 5);

        // Set a User-Agent string to mimic a browser
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');

        // Set the connection timeout
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, static::$TIMEOUT_SEC);

        // Set the total operation timeout
        curl_setopt($curl, CURLOPT_TIMEOUT, static::$TIMEOUT_SEC);

        $headers = array(
            "Authorization: Bearer " .$this->token,
            "Content-Type: application/json",
            "X-Client-Id: " .API_CLIENT_ID
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        //Get the response  and response code
        $resp = curl_exec($curl); 
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
       
        //check the response code
        if ($http_code == 401) {
            //not authorized, token might be expired or invalid
            //try to re-call the function but force to re-authenticate and get new token
            $this->token = "";
            return $this->getSekolahByWilayah($kodewilayah);
        }
        else if ($http_code !== 200) {
            $this->set_error('API error: ' .curl_error($curl), curl_errno($curl), 0);
            return null;
        }
        
        // Check the return value of curl_exec(), too
        if ($resp === false) {
            $this->set_error('API error: ' .curl_error($curl), curl_errno($curl), 0);
            return null;
        }
        curl_close($curl);

        //$this->logger->log('debug', "RESPONSE: " .$resp, array("MIF"));
        
        $json = json_decode($resp, true);
        if (empty($json)) {
            $this->logger->log('debug', "RESPONSE: \n" .$resp, array("MIF"));
            $this->set_error('API error: invalid response', -1, 0);
            return null;
        }

        $prettystr = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->logger->log('debug', "RESPONSE: \n" .$prettystr, array("MIF"));

        if (isset($json['status']) && $json['status'] === 200 && isset($json['data'])) {
            return $json['data'];
        } else {
            return null;
        }
    }

    public function getSiswaBySekolah(string $npsn, string $token = "") {
        $this->reset_error();
        
        if (empty($token)) {
            if (!empty($this->token)) {
                $token = $this->token;
            } else {
                $this->logger->log('info', "Get token akses API");
                $this->token = $this->getToken();
                if (empty($this->token)) {
                    $this->set_error("Gagal mendapatkan token akses.", 500, 1);
                    return null;
                }
                //$this->logger->log('info', "Akses token: " . $this->token);
                $token = $this->token;
            }
            $token = $this->token;
        }

        $url = "https://api.spl.kemendikdasmen.go.id/layanan/peserta-didik/spmb/peserta-didik-by-npsn";

        $queryParams = [
            'api_key' => API_KEY,
            'npsn' => $npsn
        ];

        //build the full URL with the query string
        $url .= '?' . http_build_query($queryParams);

        $this->logger->log('info', "GET: \n" .$url);

        //send
        $curl = curl_init();

        $fp = fopen(WRITEPATH .'logs/curl-errorlog.txt', 'w');
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_STDERR, $fp);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 

        // Return the transfer as a string instead of outputting it directly
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // *** Key option: Follow redirects ***
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        // Optional: Set a maximum number of redirects (default is 50, here limited to 5)
        curl_setopt($curl, CURLOPT_MAXREDIRS, 5);

        // Set a User-Agent string to mimic a browser
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');

        // Set the connection timeout
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, static::$TIMEOUT_SEC);

        // Set the total operation timeout
        curl_setopt($curl, CURLOPT_TIMEOUT, static::$TIMEOUT_SEC);

        $headers = array(
            "Authorization: Bearer " .$this->token,
            "Content-Type: application/json",
            "X-Client-Id: " .API_CLIENT_ID
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        //Get the response  and response code
        $resp = curl_exec($curl); 
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
       
        //check the response code
        if ($http_code == 401) {
            //not authorized, token might be expired or invalid
            //try to re-call the function but force to re-authenticate and get new token
            $this->token = "";
            return $this->getSiswaBySekolah($npsn);
        }
        else if ($http_code !== 200) {
            $this->set_error('API error: ' .curl_error($curl), curl_errno($curl), 0);
            return null;
        }
        
        // Check the return value of curl_exec(), too
        if ($resp === false) {
            $this->set_error('API error: ' .curl_error($curl), curl_errno($curl), 0);
            return null;
        }
        curl_close($curl);

        //$this->logger->log('debug', "RESPONSE: " .$resp, array("MIF"));
        
        $json = json_decode($resp, true);
        if (empty($json)) {
            $this->logger->log('debug', "RESPONSE: \n" .$resp, array("API"));
            $this->set_error('API error: invalid response', -1, 0);
            return null;
        }

        $prettystr = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->logger->log('debug', "RESPONSE: \n" .$prettystr, array("MIF"));

        if (isset($json['status']) && $json['status'] === 200 && isset($json['data'])) {
            $data = $json['data'];
            if (count($data) == 1 && count($data[0]) == 1 && isset($data[0]['keterangan'])) {
                $errmsg = $data[0]['keterangan'];
                $this->logger->log('debug', "ERROR: " .$errmsg, array("API"));
                $this->set_error('API error: ' .$errmsg, -1, 0);
                return null;
            }
            return $data;
        } else {
            return null;
        }
    }

    public function getSiswaByNisnDanTglLahir(string $nisn, string $tgllahir, string $token = "") {
        $this->reset_error();
        
        if (empty($token)) {
            if (!empty($this->token)) {
                $token = $this->token;
            } else {
                $this->logger->log('info', "Get token akses API");
                $this->token = $this->getToken();
                if (empty($this->token)) {
                    $this->set_error("Gagal mendapatkan token akses.", 500, 1);
                    return null;
                }
                //$this->logger->log('info', "Akses token: " . $this->token);
                $token = $this->token;
            }
            $token = $this->token;
        }

        $url = "https://api.spl.kemendikdasmen.go.id/layanan/peserta-didik/spmb/peserta-didik-by-nisn";

        $queryParams = [
            'api_key' => API_KEY,
            'nisn' => $nisn,
            'tanggal_lahir'=> $tgllahir
        ];

        //build the full URL with the query string
        $url .= '?' . http_build_query($queryParams);

        $this->logger->log('info', "GET: \n" .$url);

        //send
        $curl = curl_init();

        $fp = fopen(WRITEPATH .'logs/curl-errorlog.txt', 'w');
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_STDERR, $fp);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 

        // Return the transfer as a string instead of outputting it directly
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // *** Key option: Follow redirects ***
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        // Optional: Set a maximum number of redirects (default is 50, here limited to 5)
        curl_setopt($curl, CURLOPT_MAXREDIRS, 5);

        // Set a User-Agent string to mimic a browser
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');

        // Set the connection timeout
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, static::$TIMEOUT_SEC);

        // Set the total operation timeout
        curl_setopt($curl, CURLOPT_TIMEOUT, static::$TIMEOUT_SEC);

        $headers = array(
            "Authorization: Bearer " .$this->token,
            "Content-Type: application/json",
            "X-Client-Id: " .API_CLIENT_ID
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        //Get the response  and response code
        $resp = curl_exec($curl); 
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
       
        //check the response code
        if ($http_code == 401) {
            //not authorized, token might be expired or invalid
            //try to re-call the function but force to re-authenticate and get new token
            $this->token = "";
            return $this->getSiswaByNisnDanTglLahir($nisn, $tgllahir);
        }
        else if ($http_code !== 200) {
            $this->set_error('API error: ' .curl_error($curl), curl_errno($curl), 0);
            return null;
        }
        
        // Check the return value of curl_exec(), too
        if ($resp === false) {
            $this->set_error('API error: ' .curl_error($curl), curl_errno($curl), 0);
            return null;
        }
        curl_close($curl);

        //$this->logger->log('debug', "RESPONSE: " .$resp, array("MIF"));
        
        $json = json_decode($resp, true);
        if (empty($json)) {
            $this->logger->log('debug', "RESPONSE: \n" .$resp, array("MIF"));
            $this->set_error('API error: invalid response', -1, 0);
            return null;
        }

        $prettystr = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->logger->log('debug', "RESPONSE: \n" .$prettystr, array("MIF"));

        if (isset($json['status']) && $json['status'] === 200 && isset($json['data'])) {
            return $json['data'];
        } else {
            return null;
        }
    }

    public function getSiswaByNisnDanNpsn(string $nisn, string $npsn, string $token = "") {
        $this->reset_error();
        
        if (empty($token)) {
            if (!empty($this->token)) {
                $token = $this->token;
            } else {
                $this->logger->log('info', "Get token akses API");
                $this->token = $this->getToken();
                if (empty($this->token)) {
                    $this->set_error("Gagal mendapatkan token akses.", 500, 1);
                    return null;
                }
                //$this->logger->log('info', "Akses token: " . $this->token);
                $token = $this->token;
            }
            $token = $this->token;
        }

        $url = "https://api.spl.kemendikdasmen.go.id/layanan/peserta-didik/spmb/peserta-didik-by-nisn-npsn";

        $queryParams = [
            'api_key' => API_KEY,
            'nisn' => $nisn,
            'npsn'=> $npsn
        ];

        //build the full URL with the query string
        $url .= '?' . http_build_query($queryParams);

        $this->logger->log('info', "GET: \n" .$url);

        //send
        $curl = curl_init();

        $fp = fopen(WRITEPATH .'logs/curl-errorlog.txt', 'w');
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_STDERR, $fp);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 

        // Return the transfer as a string instead of outputting it directly
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // *** Key option: Follow redirects ***
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        // Optional: Set a maximum number of redirects (default is 50, here limited to 5)
        curl_setopt($curl, CURLOPT_MAXREDIRS, 5);

        // Set a User-Agent string to mimic a browser
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');

        // Set the connection timeout
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, static::$TIMEOUT_SEC);

        // Set the total operation timeout
        curl_setopt($curl, CURLOPT_TIMEOUT, static::$TIMEOUT_SEC);

        $headers = array(
            "Authorization: Bearer " .$this->token,
            "Content-Type: application/json",
            "X-Client-Id: " .API_CLIENT_ID
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        //Get the response  and response code
        $resp = curl_exec($curl); 
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
       
        //check the response code
        if ($http_code == 401) {
            //not authorized, token might be expired or invalid
            //try to re-call the function but force to re-authenticate and get new token
            $this->token = "";
            return $this->getSiswaByNisnDanNpsn($nisn, $npsn);
        }
        else if ($http_code !== 200) {
            $this->set_error('API error: ' .curl_error($curl), curl_errno($curl), 0);
            return null;
        }
        
        // Check the return value of curl_exec(), too
        if ($resp === false) {
            $this->set_error('API error: ' .curl_error($curl), curl_errno($curl), 0);
            return null;
        }
        curl_close($curl);

        //$this->logger->log('debug', "RESPONSE: " .$resp, array("MIF"));
        
        $json = json_decode($resp, true);
        if (empty($json)) {
            $this->logger->log('debug', "RESPONSE: \n" .$resp, array("MIF"));
            $this->set_error('API error: invalid response', -1, 0);
            return null;
        }

        $prettystr = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->logger->log('debug', "RESPONSE: \n" .$prettystr, array("MIF"));

        if (isset($json['status']) && $json['status'] === 200 && isset($json['data'])) {
            return $json['data'];
        } else {
            return null;
        }
    }

    public function getTkaSiswa(string $nisn, string $tgllahir, string $token = "") {
        $this->reset_error();
        
        if (empty($token)) {
            if (!empty($this->token)) {
                $token = $this->token;
            } else {
                $this->logger->log('info', "Get token akses API");
                $this->token = $this->getToken();
                if (empty($this->token)) {
                    $this->set_error("Gagal mendapatkan token akses.", 500, 1);
                    return null;
                }
                //$this->logger->log('info', "Akses token: " . $this->token);
                $token = $this->token;
            }
            $token = $this->token;
        }

        $url = "https://api.spl.kemendikdasmen.go.id/layanan/peserta-didik/spmb/peserta-didik-tka";

        $queryParams = [
            'api_key' => API_KEY,
            'nisn' => $nisn,
            'tanggal_lahir'=> $tgllahir
        ];

        //build the full URL with the query string
        $url .= '?' . http_build_query($queryParams);

        $this->logger->log('debug', "GET: \n" .$url);

        //send
        $curl = curl_init();

        $fp = fopen(WRITEPATH .'logs/curl-errorlog.txt', 'w');
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_STDERR, $fp);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 

        // Return the transfer as a string instead of outputting it directly
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // *** Key option: Follow redirects ***
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        // Optional: Set a maximum number of redirects (default is 50, here limited to 5)
        curl_setopt($curl, CURLOPT_MAXREDIRS, 5);

        // Set a User-Agent string to mimic a browser
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');

        // Set the connection timeout
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, static::$TIMEOUT_SEC);

        // Set the total operation timeout
        curl_setopt($curl, CURLOPT_TIMEOUT, static::$TIMEOUT_SEC);

        $headers = array(
            "Authorization: Bearer " .$this->token,
            "Content-Type: application/json",
            "X-Client-Id: " .API_CLIENT_ID
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        //Get the response  and response code
        $resp = curl_exec($curl); 
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
       
        //check the response code
        if ($http_code == 401) {
            //not authorized, token might be expired or invalid
            //try to re-call the function but force to re-authenticate and get new token
            $this->token = "";
            return $this->getTkaSiswa($nisn, $tgllahir);
        }
        else if ($http_code !== 200) {
            $this->set_error('API error: ' .curl_error($curl), curl_errno($curl), 0);
            return null;
        }
        
        // Check the return value of curl_exec(), too
        if ($resp === false) {
            $this->set_error('API error: ' .curl_error($curl), curl_errno($curl), 0);
            return null;
        }
        curl_close($curl);

        //$this->logger->log('debug', "RESPONSE: " .$resp, array("MIF"));
        
        $json = json_decode($resp, true);
        if (empty($json)) {
            $this->logger->log('debug', "RESPONSE: \n" .$resp, array($nisn));
            $this->set_error('API error: invalid response', -1, 0);
            return null;
        }

        $prettystr = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->logger->log('debug', "RESPONSE: \n" .$prettystr, array($nisn));

        if (isset($json['status']) && $json['status'] === 200 && isset($json['data'])) {
            return $json['data'];
        } else {
            return null;
        }
    }

    public function getPrestasiSiswa(string $nisn, string $npsn, string $token = "") {
        $this->reset_error();
        
        if (empty($token)) {
            if (!empty($this->token)) {
                $token = $this->token;
            } else {
                $this->logger->log('info', "Get token akses API");
                $this->token = $this->getToken();
                if (empty($this->token)) {
                    $this->set_error("Gagal mendapatkan token akses.", 500, 1);
                    return null;
                }
                //$this->logger->log('info', "Akses token: " . $this->token);
                $token = $this->token;
            }
            $token = $this->token;
        }

        $url = "https://api.spl.kemendikdasmen.go.id/layanan/peserta-didik/spmb/prestasi-siswa";

        $queryParams = [
            'nisn' => $nisn
        ];

        //build the full URL with the query string
        $url .= '?' . http_build_query($queryParams);

        $this->logger->log('debug', "GET: \n" .$url);

        //send
        $curl = curl_init();

        $fp = fopen(WRITEPATH .'logs/curl-errorlog.txt', 'w');
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_STDERR, $fp);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 

        // Return the transfer as a string instead of outputting it directly
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // *** Key option: Follow redirects ***
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        // Optional: Set a maximum number of redirects (default is 50, here limited to 5)
        curl_setopt($curl, CURLOPT_MAXREDIRS, 5);

        // Set a User-Agent string to mimic a browser
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');

        // Set the connection timeout
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, static::$TIMEOUT_SEC);

        // Set the total operation timeout
        curl_setopt($curl, CURLOPT_TIMEOUT, static::$TIMEOUT_SEC);

        $headers = array(
            "Authorization: Bearer " .$this->token,
            "Content-Type: application/json",
            "X-Client-Id: " .API_CLIENT_ID
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        //Get the response  and response code
        $resp = curl_exec($curl); 
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
       
        //check the response code
        if ($http_code == 401) {
            //not authorized, token might be expired or invalid
            //try to re-call the function but force to re-authenticate and get new token
            $this->token = "";
            return $this->getPrestasiSiswa($nisn, $npsn);
        }
        else if ($http_code !== 200) {
            $this->set_error('API error: ' .curl_error($curl), curl_errno($curl), 0);
            return null;
        }
        
        // Check the return value of curl_exec(), too
        if ($resp === false) {
            $this->set_error('API error: ' .curl_error($curl), curl_errno($curl), 0);
            return null;
        }
        curl_close($curl);

        //$this->logger->log('debug', "RESPONSE: " .$resp, array("MIF"));
        
        $json = json_decode($resp, true);
        if (empty($json)) {
            $this->logger->log('debug', "RESPONSE: \n" .$resp, array($nisn));
            $this->set_error('API error: invalid response', -1, 0);
            return null;
        }

        $prettystr = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->logger->log('debug', "RESPONSE: \n" .$prettystr, array($nisn));

        if (isset($json['status']) && $json['status'] === 200 && isset($json['data']) && count($json['data']) > 0) {
            return $json['data'];
        } else {
            $dummy = array(
                "nisn" => $nisn,
                "npsn" => $npsn,
                "nama_ajang" => "NA", 
                "achievement_description" => "NA", 
                "tingkat" => "NA"
            );
            $retval = array();
            $retval[] = $dummy;
            return $retval;
        }
    }

    public function syncSiswa($siswa, string $token = "") {
        $this->reset_error();
        
        if (empty($token)) {
            if (!empty($this->token)) {
                $token = $this->token;
            } else {
                $this->logger->log('info', "Get token akses API");
                $this->token = $this->getToken();
                if (empty($this->token)) {
                    $this->set_error("Gagal mendapatkan token akses.", 500, 1);
                    return null;
                }
                //$this->logger->log('info', "Akses token: " . $this->token);
                $token = $this->token;
            }
            $token = $this->token;
        }

        $url = "https://api.spl.kemendikdasmen.go.id/layanan/peserta-didik/spmb/peserta-didik-balikan";

        $data = $siswa;

        $this->logger->log('debug', "POST: \n" .$url);

        $prettystr = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->logger->log('debug', "DATA: \n" .$prettystr);

        $curl = curl_init($url);

        $fp = fopen(WRITEPATH .'logs/curl-errorlog.txt', 'w');
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_STDERR, $fp);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);

        // Return the transfer as a string instead of outputting it directly
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // *** Key option: Follow redirects ***
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        // Optional: Set a maximum number of redirects (default is 50, here limited to 5)
        curl_setopt($curl, CURLOPT_MAXREDIRS, 5);

        // Set a User-Agent string to mimic a browser
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');

        // Set the connection timeout
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, static::$TIMEOUT_SEC);

        // Set the total operation timeout
        curl_setopt($curl, CURLOPT_TIMEOUT, static::$TIMEOUT_SEC);

        $headers = array(
            "Authorization: Bearer " .$this->token,
            "Content-Type: application/json",
            "X-Client-Id: " .API_CLIENT_ID,
            "api_key: " .API_KEY
        );

        //var_dump($headers); exit;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $json = json_encode($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        
        $resp = curl_exec($curl);
        curl_close($curl);
        
        //var_dump($resp); exit;

        $json = json_decode($resp, true);
        if (empty($json)) {
            $this->logger->log('debug', "RESPONSE: \n" .$resp);
            $this->set_error('API error: invalid response', -1, 0);
            return null;
        }

        $prettystr = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->logger->log('debug', "RESPONSE: \n" .$prettystr);

        if (isset($json['status']) && $json['status'] == 200 && $json['message'] == "success") {
            return $json['data'][0]['id'];
        } else {
            return null;
        }

    }

    protected function set_error($message, $code, $throwexception = 0) {
        $this->error_code = $code;
        $this->error_message = $message;

        $this->logger->log('error', $message);

        // if ($throwexception) {
        //     throw new Exception($message, $code);
        // }
    }

    protected function reset_error() {
        $this->error_code = 0;
        $this->error_message = null;
    }
}

?>