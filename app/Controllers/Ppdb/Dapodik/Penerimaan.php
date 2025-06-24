<?php

namespace App\Controllers\Ppdb\Dapodik;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Mdropdown;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use App\Models\Ppdb\Mconfig;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Penerimaan extends PpdbController {

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
        
        //role-based permission
        //static::$ROLE_ID = ROLEID_DAPODIK;
    }
    
	function index()
	{
        $sekolah_id = $this->session->get("sekolah_id");
        if (empty($sekolah_id)) {
			return $this->notauthorized();
		}

        $data['profilsekolah'] = $this->session->get("profilsekolah");
        $data['impersonasi_sekolah'] = $this->session->get("impersonasi_sekolah");
        if ($data['impersonasi_sekolah'] == 1) {
            $data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
        }

        $jenjang_id=JENJANGID_SMP;
        $nama_jenjang='SMP';
        if ($data['profilsekolah']['bentuk'] == 'SD' || $data['profilsekolah']['bentuk'] == 'MI') {
            $jenjang_id=JENJANGID_SD;
            $nama_jenjang='SD';
        }
        else if ($data['profilsekolah']['bentuk'] == 'TK') {
            $jenjang_id=JENJANGID_TK;
            $nama_jenjang='TK';
        }

        //enforce
        $this->session->set("jenjang_aktif", $jenjang_id);
        $this->session->get("nama_jenjang_aktif", $nama_jenjang);

        $data['nama_jenjang'] = $nama_jenjang;
        $data['inklusi'] = $data['profilsekolah']['inklusi'];

        $mdropdown = new \App\Models\Ppdb\Mconfig();
		$data['daftarsekolah'] = $mdropdown->tcg_sekolah_tk_ra($this->kode_wilayah);

		//$sekolah_id = $this->session->get("sekolah_id");
		//$nama_sekolah = $this->Msekolah->tcg_nama_sekolah($sekolah_id);

        $data['waktupendaftaran'] = $this->Mconfig->tcg_waktupendaftaran($jenjang_id);
        if (empty($data['waktupendaftaran'])) {
            $data['cek_waktupendaftaran'] = 0;
        }
        else {
            $data['cek_waktupendaftaran'] = ($data['waktupendaftaran']['aktif'] == 1) ? 1 : 0;
        }

        $data['waktusosialisasi'] = $this->Mconfig->tcg_waktusosialisasi();
        if (empty($data['waktusosialisasi'])) {
            $data['cek_waktusosialisasi'] = 0;
        }
        else {
            $data['cek_waktusosialisasi'] = ($data['waktusosialisasi']['aktif'] == 1) ? 1 : 0;
        }
        
        $data['waktudaftarulang'] = $this->Mconfig->tcg_waktudaftarulang();
        if (empty($data['waktudaftarulang'])) {
            $data['cek_waktudaftarulang'] = 0;
        }
        else {
            $data['cek_waktudaftarulang'] = ($data['waktudaftarulang']['aktif'] == 1) ? 1 : 0;
        }

        //$data['kuota'] = $this->Msekolah->tcg_kuota_sd($sekolah_id);

        //debugging
        if (__DEBUGGING__) {
            $data['cek_waktusosialisasi']=1;
            //$data['cek_waktudaftarulang']=1;
        }

        // var_dump($jenjang_id);
        // var_dump($data['waktupendaftaran_sd']);
        // exit;

        $mconfig = new Mconfig();
        $data['daftarkab'] = $mconfig->tcg_kabupaten();

        $data['use_select2'] = 1;
        $data['use_datatable'] = 1;
        $data['use_datatable_editor'] = 1;

        $data['show_all_pendaftar'] = 1;

		$data['cek_waktuverifikasi'] = $this->Mconfig->tcg_cek_waktuverifikasi();
        if ($data['cek_waktupendaftaran'] != 1 && $data['cek_waktuverifikasi'] != 1 && $data['cek_waktusosialisasi'] != 1) {
            $data['final_ranking'] = 1;
        }
        else {
            $data['final_ranking'] = 0;
        }

		$data['daftarpenerapan'] = $this->Msekolah->tcg_daftarpenerapan($sekolah_id);
        $this->session->set("daftarpenerapan", $data['daftarpenerapan']);

        //content template
        $data['content_template'] = 'penerimaan.tpl';
        $data['js_template'] = '_penerimaan.tpl';

        $data['page'] = 'penerimaan';
		$data['page_title'] = "Penerimaan Siswa Baru";
        $this->smarty->render('ppdb/dapodik/ppdbdapodik.tpl', $data);

	}

	function json() {
		//$tahun_ajaran_id = $this->tahun_ajaran_id;

        $penerapan_id = $_GET["penerapan_id"] ?? null;
		if (empty($penerapan_id)) {
            $penerapan_id = 0;
        };

        // $jenjang_id = $this->session->get("jenjang_aktif");
        // $nama_jenjang = $this->session->get("nama_jenjang_aktif");

        $action = $this->request->getPostGet("action");
		if (empty($action) || $action=='view') {
			$sekolah_id = $this->session->get("sekolah_id");

			$data = array();

			$result = $this->Msekolah->tcg_penerimaan_sd($sekolah_id, $penerapan_id);
            if ($result == null) {
                $error = $this->Msekolah->get_error_message();
                if (!empty($error)) {
                    print_json_error($error);
                }
            }

			$info = $this->Msekolah->tcg_infopenerapan($sekolah_id, $penerapan_id);
            if ($info == null) {
                $error = $this->Msekolah->get_error_message();
                if (!empty($error)) {
                    print_json_error($error);
                }
            }

			print_json_output($result, 1, $info);
		}
        else if ($action=='remove') {
			$sekolah_id = $this->session->get("sekolah_id");
            $peserta_didik_id = $this->request->getPostGet('peserta_didik_id');

            //pendaftaran lama
            $pendaftaran = $this->Msekolah->tcg_penerimaan_sd_siswa($peserta_didik_id);
            if ($sekolah_id != $pendaftaran['sekolah_id']) {
                print_json_error('Tidak mendaftar di sekolah ini');
            }

            $status = $this->Msekolah->tcg_hapus_pendaftar_sd($sekolah_id, $peserta_didik_id);
            if (!$status) {
                $error = $this->Msekolah->get_error_message();
                if (!empty($error)) {
                    print_json_error($error);
                } else {
                    print_json_error('Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.');
                }
            }

            //audit trail
            $pendaftaran['peserta_didik_id'] = $peserta_didik_id;
            audit_pendaftaran($pendaftaran, "HAPUS PENDAFTARAN SD/TK", "HAPUS Pendaftaran di " .$pendaftaran['sekolah']. " an. " .$pendaftaran['nama']);

			print_json_output(array());
        }
        else if ($action=='sekolah') {
            $mdropdown = new \App\Models\Ppdb\Mconfig();
            $sekolah = $mdropdown->tcg_sekolah_tk_ra();
            
            print_json_output($sekolah);
        }
        else if ($action=='search') {
			$nama = $this->request->getPostGet("nama"); 
			$nisn= $this->request->getPostGet("nisn"); 
			$nik= $this->request->getPostGet("nik");
			$sekolah_id= $this->request->getPostGet("sekolah_id"); 

			if (empty($nama) && empty($nisn) && empty($sekolah_id) && empty($nik)) {
				//no search
				print_json_output(array(), 1);
                return;
			}

            $daftar = $this->Msekolah->tcg_calon_pesertadidik_sd($nama, $nisn, $sekolah_id, $nik);
            if ($daftar == null) {
                $daftar = array();
            }

            print_json_output($daftar, 1);
        }
        else if ($action=='daftarulang') {
			$sekolah_id = $this->session->get("sekolah_id");
            $pendaftaran_id = $this->request->getPostGet('pendaftaran_id');

            $status = $this->Msekolah->tcg_ubah_daftarulang($pendaftaran_id,1);
            if (!$status) {
                $error = $this->Msekolah->get_error_message();
                if (!empty($error)) {
                    print_json_error($error);
                } else {
                    print_json_error('Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.');
                }
            }

            //audit trail -> already done in tcg_ubah_daftarulang()

			print_json_output(array());
        }
        else if ($action=='bataldu') {
			$sekolah_id = $this->session->get("sekolah_id");
            $pendaftaran_id = $this->request->getPostGet('pendaftaran_id');

            $status = $this->Msekolah->tcg_ubah_daftarulang($pendaftaran_id,0);
            if (!$status) {
                $error = $this->Msekolah->get_error_message();
                if (!empty($error)) {
                    print_json_error($error);
                } else {
                    print_json_error('Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.');
                }
            }

            //audit trail -> already done in tcg_ubah_daftarulang()

			print_json_output(array());
        }
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}
	}

    function ubahdata() {
		//$tahun_ajaran_id = $this->tahun_ajaran_id;

        $sekolah_id = $this->session->get("sekolah_id");
        $daftarpenerapan = $this->session->get("daftarpenerapan");
        $jenjang_id = $this->session->get("jenjang_aktif");

        $action = $this->request->getPostGet("action");
        if ($action=='create'){
            $values = $this->request->getPost("data");

            if (empty($values)) {
                print_json_error("Data siswa tidak valid/kosong.");
            }

            $json = array();
            foreach($values as $peserta_didik_id => $siswa) {
                //remove helper fields
                unset($siswa['kode_wilayah_kab']);
                unset($siswa['kode_wilayah_kec']);

                $siswa['nisn'] = trim($siswa['nisn'] ?? '');
				if (empty($siswa['nisn']) || $this->Msiswa->tcg_cek_nisn($siswa['nisn'])) {
					print_json_error("NISN siswa baru tidak valid/kosong/sudah terpakai.");
                    break;
				}

                $siswa['nik'] = trim($siswa['nik'] ?? '');
				if (empty($siswa['nik']) || $this->Msiswa->tcg_cek_nik($siswa['nik'])) {
					print_json_error("NIK siswa baru tidak valid/kosong/sudah terpakai.");
                    break;
				}

                //asal sekolah
                if (intval($siswa['npsn_sekolah_asal']) == 0) {
                    $siswa['npsn_sekolah_asal'] = '';
                }
                $npsn_sekolah_asal = trim($siswa['npsn_sekolah_asal']);
                //dont use '0000'
                if (!empty($npsn_sekolah_asal)) {
                    $asal_sekolah = get_profilsekolah_from_npsn($npsn_sekolah_asal);
                    $siswa['sekolah_id'] = $asal_sekolah['sekolah_id'];
                }
                else {
                    //default
                    $siswa['sekolah_id'] = $sekolah_id;
                }

                $penerapan_id = 0;
                if (isset($siswa['penerapan_id'])) {
                    $penerapan_id = $siswa['penerapan_id'];
                    unset($siswa['penerapan_id']);
                }

                //batasan usia
                //var_dump($jenjang_id); exit;
                $batasan_usia = $this->Mconfig->tcg_batasanusia($jenjang_id);

                if ($siswa['tanggal_lahir'] > $batasan_usia['minimal_tanggal_lahir']) {
                    print_json_error('Minimal tanggal lahir: ' .$batasan_usia['minimal_tanggal_lahir']. '. Tanggal lahir siswa: ' .$siswa['tanggal_lahir']);
                }

                if ($siswa['tanggal_lahir'] < $batasan_usia['maksimal_tanggal_lahir']) {
                    print_json_error('Maksimal tanggal lahir: ' .$batasan_usia['maksimal_tanggal_lahir']. '. Tanggal lahir siswa: ' .$siswa['tanggal_lahir']);
                }            

                //make sure it is valid penerapan
                $penerapan = false;
                foreach($daftarpenerapan as $p) {
                    if ($p['penerapan_id'] == $penerapan_id) {
                        $penerapan = $p;
                        break;
                    }
                }

                if (!$penerapan) {
                    print_json_error("Jalur pendaftaran tidak valid.");
                    break;
                }
            
                //kalau pakai jalur afirmasi, cek masuk data afirmasi
                if ($penerapan['jalur_id'] == JALURID_AFIRMASI) {
                    $nik = $siswa['nik'];
                    if (!$this->Msekolah->tcg_cek_dataafirmasi($nik)) {
                        print_json_error("NIK siswa tidak terdaftar di data afirmasi.");
                    }
                }
                
                //buat peserta-didik
				$peserta_didik_id = $this->Msekolah->tcg_tambah_pendaftar_sd($siswa);
				if (empty($peserta_didik_id)) {
					$error = $this->Msekolah->get_error_message();
					if (!empty($error)) {
                        print_json_error($error);
					} else {
						print_json_error('Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.');
					}
				}

                //$profil = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);

                //audit trail
                $siswa['peserta_didik_id'] = $peserta_didik_id;
                audit_siswa($siswa, "SISWA BARU SD/TK", "Siswa Baru Penerimaan SD/TK an. " .$siswa['nama']);

                $pendaftaran = $this->Msekolah->tcg_terima_pendaftar_sd($sekolah_id, $peserta_didik_id, $penerapan_id);
                if (!$pendaftaran) {
                    $error = $this->Msekolah->get_error_message();
                    if (!empty($error)) {
                        print_json_error($error);
                    } else {
                        print_json_error('Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.');
                    }
                }
    
                if (empty($pendaftaran) || $pendaftaran['sekolah_id']!=$sekolah_id || $pendaftaran['penerapan_id']!=$penerapan_id) {
                    print_json_error('Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.');
                }
    
                //audit trail
                audit_pendaftaran($pendaftaran, "PENDAFTARAN SD/TK", "Pendaftaran di " .$pendaftaran['sekolah']. " an. " .$pendaftaran['nama']);
    
                $json[] = $pendaftaran;    
            }

            print_json_output($json);
        }
        else if ($action=='edit') {
            $values = $this->request->getPost("data");

            if (empty($values)) {
                print_json_error("Data siswa tidak valid/kosong.");
            }

            $json = array();
            foreach($values as $k => $v) {
                $pendaftaran_id = $k;

                // if (!isset($v['pendaftaran_id'])) {
                //     print_json_error("Pendaftaran tidak valid.");
                //     break;
                // }
                // $pendaftaran_id = $v['pendaftaran_id'];
                // unset($v['pendaftaran_id']);

                $pendaftaran = $this->Msekolah->tcg_penerimaan_sd_detil($sekolah_id, $pendaftaran_id);
                if ($pendaftaran == null) {
                    print_json_error("Pendaftaran tidak valid.");
                    break;
                }

                $penerapan_id = 0;
                if (isset($v['penerapan_id'])) {
                    $penerapan_id = $v['penerapan_id'];
                    unset($v['penerapan_id']);
                }

                //remove helper fields
                unset($v['kode_wilayah_kab']);
                unset($v['kode_wilayah_kec']);

                //update profil
                $peserta_didik_id = $pendaftaran['peserta_didik_id'];
                if (isset($v['nisn'])) {
                    $v['nisn'] = trim($v['nisn'] ?? '');
                    if (empty($v['nisn']) || $this->Msiswa->tcg_cek_nisn($v['nisn'], $peserta_didik_id)) {
                        print_json_error("NISN siswa tidak valid/kosong/sudah terpakai.");
                        break;
                    }
                }

                if (isset($v['nik'])) {
                    $v['nik'] = trim($v['nik'] ?? '');
                    if (empty($v['nik']) || $this->Msiswa->tcg_cek_nik($v['nik'], $peserta_didik_id)) {
                        print_json_error("NIK siswa tidak valid/kosong/sudah terpakai.");
                        break;
                    }
                }

                //$profil = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);
                $data = $this->Msekolah->tcg_update_pendaftar_sd($pendaftaran, $v);

                if ($data != null) {
                    //audit trail
                    audit_siswa($pendaftaran, "UBAH DATA", "Ubah data siswa an. " .$data['nama'], array_keys($v), $v, $pendaftaran);
                }

                if ($penerapan_id == 0 || $penerapan_id == $pendaftaran['penerapan_id']) {
                    $json[] = $this->Msekolah->tcg_penerimaan_sd_detil($sekolah_id, $pendaftaran_id); 
                    continue;               
                }

                //update penerapan
                //make sure it is valid penerapan
                $penerapan = false;
                foreach($daftarpenerapan as $p) {
                    if ($p['penerapan_id'] == $penerapan_id) {
                        $penerapan = $p;
                        break;
                    }
                }

                if (!$penerapan) {
                    print_json_error("Jalur pendaftaran tidak valid.");
                    break;
                }
            
                //kalau pakai jalur afirmasi, cek masuk data afirmasi
                if ($penerapan['jalur_id'] == JALURID_AFIRMASI) {
                    $nik = $pendaftaran['nik'];
                    if (!$this->Msekolah->tcg_cek_dataafirmasi($nik)) {
                        print_json_error("NIK siswa tidak terdaftar di data afirmasi.");
                    }
                }

                $data = $this->Msekolah->tcg_ubahjalur_sd($pendaftaran_id, $penerapan_id);

                if ($data != null) {
                    //audit trail
                    audit_pendaftaran($pendaftaran, "UBAH PENDAFTARAN SD/TK", "Ubah jalur pendaftaran siswa an. " .$data['nama']. " ke jalur " .$data['jalur'], 
                        array("penerapan_id"), array($penerapan_id), array($pendaftaran['penerapan_id']));
                }

                $json[] = $data;
            }

            print_json_output($json);
        }
    }

	function ubahjalur() {
		//$tahun_ajaran_id = $this->tahun_ajaran_id;

        // $penerapan_id = $_GET["penerapan_id"] ?? null;
		// if (empty($penerapan_id)) {
        //     $penerapan_id = 0;
        // };

        // $jenjang_id = $this->session->get("jenjang_aktif");
        // $nama_jenjang = $this->session->get("nama_jenjang_aktif");
        $sekolah_id = $this->session->get("sekolah_id");
        $daftarpenerapan = $this->session->get("daftarpenerapan");

        $values = $this->request->getPost("data");

        if (empty($values)) {
            print_json_error("Data pendaftaran tidak valid/kosong.");
        }

        $json = array();
        foreach($values as $k => $v) {
            $pendaftaran_id = $k;
            $pendaftaran = $this->Msekolah->tcg_penerimaan_sd_detil($sekolah_id, $pendaftaran_id);
            if (empty($pendaftaran)) {
                print_json_error("Pendaftaran tidak ditemukan di sekolah.");
                break;
            }

            if (!isset($v['penerapan_id'])) {
                print_json_error("Jalur pendaftaran tidak valid.");
                break;
            }

            $penerapan_id = $v['penerapan_id'];
            if ($penerapan_id == $pendaftaran['penerapan_id'])  continue;

            //make sure it is valid penerapan
            $penerapan = false;
            foreach($daftarpenerapan as $p) {
                if ($p['penerapan_id'] == $penerapan_id) {
                    $penerapan = $p;
                    break;
                }
            }

            if (!$penerapan) {
                print_json_error("Jalur pendaftaran tidak valid.");
                break;
            }
           
            //kalau pakai jalur afirmasi, cek masuk data afirmasi
            if ($penerapan['jalur_id'] == JALURID_AFIRMASI) {
                $nik = $pendaftaran['nik'];
                if (!$this->Msekolah->tcg_cek_dataafirmasi($nik)) {
                    print_json_error("NIK siswa tidak terdaftar di data afirmasi.");
                }
            }

            $data = $this->Msekolah->tcg_ubahjalur_sd($pendaftaran_id, $penerapan_id);

            if ($data != null) {
                //audit trail
                audit_pendaftaran($pendaftaran, "UBAH PENDAFTARAN SD/TK", "Ubah jalur pendaftaran siswa an. " .$data['nama']. " ke jalur " .$data['jalur'], 
                    array("penerapan_id"), array($penerapan_id), array($pendaftaran['penerapan_id']));
            }

            $json[] = $data;
        }

        print_json_output($json);
    }

    function daftar() {
		//$tahun_ajaran_id = $this->tahun_ajaran_id;

        // $penerapan_id = $_GET["penerapan_id"] ?? null;
		// if (empty($penerapan_id)) {
        //     $penerapan_id = 0;
        // };

        $sekolah_id = $this->session->get("sekolah_id");
        if (empty($sekolah_id)) {
            print_json_error("not-authorized");
		}

        $jenjang_id = $this->session->get("jenjang_aktif");
        // $nama_jenjang = $this->session->get("nama_jenjang_aktif");

        $daftarpenerapan = $this->session->get("daftarpenerapan");

        $values = $this->request->getPost("data");

        if (empty($values)) {
            print_json_error("Data pendaftaran tidak valid/kosong.");
        }

        $json = array();
        foreach($values as $k => $v) {
            $peserta_didik_id = $k;

            if (!isset($v['penerapan_id'])) {
                print_json_error("Jalur pendaftaran tidak valid.");
                break;
            }
            $penerapan_id = $v['penerapan_id'];

            //make sure it is valid penerapan
            $penerapan = null;
            foreach($daftarpenerapan as $p) {
                if ($p['penerapan_id'] == $penerapan_id) {
                    $penerapan = $p;
                    break;
                }
            }

            if (!$penerapan) {
                print_json_error("Jalur pendaftaran tidak valid.");
                break;
            }

            // if (__DEBUGGING__) {
            //     $penerapan_id=301;
            // }

            // $kuota = $this->Msekolah->tcg_kuota_sd($sekolah_id);
            // $totalpendaftaran = $this->Msekolah->tcg_totalpendaftaran_sd($sekolah_id);
            // if ($totalpendaftaran>=$kuota) {
            //     print_json_error("Kuota penerimaan sudah terpenuhi (" .$kuota. ")");
            // }

            //sudah diterima
            $pendaftaran = $this->Msekolah->tcg_penerimaan_sd_siswa($peserta_didik_id);
            if ($pendaftaran != null) {
                print_json_error("Sudah didaftarkan di " .$pendaftaran['sekolah']);
            }

            //batasan usia
            $batasan_usia = $this->Mconfig->tcg_batasanusia($jenjang_id);
            $siswa = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);

            // var_dump($jenjang_id); 
            // var_dump($batasan_usia); exit;

            if ($siswa['tanggal_lahir'] > $batasan_usia['minimal_tanggal_lahir']) {
                print_json_error('Minimal tanggal lahir: ' .$batasan_usia['minimal_tanggal_lahir']. '. Tanggal lahir siswa: ' .$siswa['tanggal_lahir']);
            }

            if ($siswa['tanggal_lahir'] < $batasan_usia['maksimal_tanggal_lahir']) {
                print_json_error('Maksimal tanggal lahir: ' .$batasan_usia['maksimal_tanggal_lahir']. '. Tanggal lahir siswa: ' .$siswa['tanggal_lahir']);
            }            

            //kalau pakai jalur afirmasi, cek masuk data afirmasi
            //important: data afirmasi yand di sistem tidak mengcover semua data afirmasi
            // if ($penerapan['jalur_id'] == JALURID_AFIRMASI) {
            //     $nik = $siswa['nik'];
            //     if (!$this->Msekolah->tcg_cek_dataafirmasi($nik)) {
            //         print_json_error("NIK siswa tidak terdaftar di data afirmasi.");
            //     }
            // }

            $pendaftaran = $this->Msekolah->tcg_terima_pendaftar_sd($sekolah_id, $peserta_didik_id, $penerapan_id);
            if (!$pendaftaran) {
                $error = $this->Msekolah->get_error_message();
                if (!empty($error)) {
                    print_json_error($error);
                } else {
                    print_json_error('Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.');
                }
            }

            if (empty($pendaftaran) || $pendaftaran['sekolah_id']!=$sekolah_id || $pendaftaran['penerapan_id']!=$penerapan_id) {
                print_json_error('Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.');
            }

            //audit trail
            audit_pendaftaran($pendaftaran, "PENDAFTARAN SD/TK", "Pendaftaran di " .$pendaftaran['sekolah']. " an. " .$pendaftaran['nama']);

            $json[] = $pendaftaran;
        }

        print_json_output($pendaftaran);	
    }
}
?>