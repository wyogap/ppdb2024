<?php

namespace App\Controllers\Ppdb\Dapodik;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Mdropdown;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
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
        $profil = $this->Msekolah->tcg_profilsekolah($sekolah_id, PUTARAN_SD);
        if (empty($profil) || !$profil['ikut_ppdb']) {
            return $this->notauthorized();
        }

        $mdropdown = new \App\Models\Ppdb\Mconfig();
		$data['daftarsekolah'] = $mdropdown->tcg_sekolah_tk_ra($this->kode_wilayah);

		//$sekolah_id = $this->session->get("sekolah_id");
		//$nama_sekolah = $this->Msekolah->tcg_nama_sekolah($sekolah_id);

        $data['waktupendaftaran_sd'] = $this->Mconfig->tcg_waktupendaftaran_sd();
        if (empty($data['waktupendaftaran_sd'])) {
            $data['cek_waktupendaftaran_sd'] = 0;
        }
        else {
            $data['cek_waktupendaftaran_sd'] = ($data['waktupendaftaran_sd']['aktif'] == 1) ? 1 : 0;
        }
        $data['waktusosialisasi'] = $this->Mconfig->tcg_waktusosialisasi();
        if (empty($data['waktusosialisasi'])) {
            $data['cek_waktusosialisasi'] = 0;
        }
        else {
            $data['cek_waktusosialisasi'] = ($data['waktusosialisasi']['aktif'] == 1) ? 1 : 0;
        }

        $data['kuota'] = $this->Msekolah->tcg_kuota_sd($sekolah_id);

        //debugging
        if (__DEBUGGING__) {

        }

        $data['use_select2'] = 1;
        $data['use_datatable'] = 1;
        $data['use_datatable_editor'] = 1;

        //content template
        $data['content_template'] = 'penerimaan.tpl';
        $data['js_template'] = '_penerimaan.tpl';

        $data['page'] = 'penerimaan';
		$data['page_title'] = "Penerimaan Siswa Baru";
        $this->smarty->render('ppdb/dapodik/ppdbdapodik.tpl', $data);

	}

	function json() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$action = $this->request->getPostGet("action");
		if (empty($action) || $action=='view') {
			$sekolah_id = $this->session->get("sekolah_id");

			$data = array();

			$result = $this->Msekolah->tcg_penerimaan_sd($sekolah_id);
            if ($result == null) {
                $error = $this->Msekolah->get_error_message();
                if (!empty($error)) {
                    print_json_error($error);
                }
            }

			print_json_output($result, 1);
		}
        else if ($action=='create'){
			$sekolah_id = $this->session->get("sekolah_id");
            $values = $this->request->getPost("data");

            if (empty($values)) {
                print_json_error("Data siswa baru tidak valid/kosong.");
            }

            $kuota = $this->Msekolah->tcg_kuota_sd($sekolah_id);
            $totalpendaftaran = $this->Msekolah->tcg_totalpendaftaran_sd($sekolah_id);
            if ($totalpendaftaran>=$kuota) {
                print_json_error("Kuota penerimaan sudah terpenuhi (" .$kuota. ")");
            }

            $siswa = $values[0];
            $detail = null;
			do {
				if (empty($siswa['nisn']) || $this->Msiswa->tcg_cek_nisn($siswa['nisn'])) {
					print_json_error("NISN siswa baru tidak valid/kosong/sudah terpakai.");
                    break;
				}

				if (empty($siswa['nik']) || $this->Msiswa->tcg_cek_nik($siswa['nik'])) {
					print_json_error("NIK siswa baru tidak valid/kosong/sudah terpakai.");
                    break;
				}

                //enforce
                $siswa['sekolah_id'] = $sekolah_id;
                
				$detail = $this->Msekolah->tcg_tambah_pesertadidik_sd($siswa);
				if ($detail == null) {
					$error = $this->Msekolah->get_error_message();
					if (!empty($error)) {
                        print_json_error($error);
					} else {
						print_json_error('Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.');
					}
				}

                //the expected json format of the dt editor
                $data = array();
                $data[] = $detail;

                //audit trail
                audit_siswa($detail, "SISWA BARU SD", "Siswa Baru Penerimaan SD an. " +$detail['nama']);
                audit_pendaftaran($detail, "PENDAFTARAN SD", "Pendaftaran di SD " .$detail['sekolah']. " an. " +$detail['nama']);

                print_json_output($data);
	
			} while(false);

            print_json_output($detail);
		}
        else if ($action=='edit'){
            $values = $this->request->getPost("data");

            if (empty($values)) {
                print_json_error("Data siswa baru tidak valid/kosong.");
            }

            $json = array();
            foreach($values as $k => $v) {
                $profil = $this->Msiswa->tcg_profilsiswa($k);
                $data = $this->Msiswa->tcg_update_siswa($k, $v);

                if ($data != null) {
                    //audit trail
                    audit_siswa($profil, "UBAH DATA", "Ubah data siswa an. " +$data['nama'], array_keys($v), $v, $profil);
                }

                $json[] = $this->Msekolah->tcg_penerimaan_sd_detil($k);
            }

            print_json_output($json);
        }
        else if ($action=='remove') {
			$sekolah_id = $this->session->get("sekolah_id");
            $peserta_didik_id = $this->request->getPostGet('peserta_didik_id');

            //pendaftaran lama
            $pendaftaran = $this->Msiswa->tcg_pendaftaran_diterima_sd($peserta_didik_id);
            if ($sekolah_id != $pendaftaran['sekolah_id']) {
                print_json_error('Tidak mendaftar di sekolah ini');
            }

            $status = $this->Msekolah->tcg_hapus_pesertadidik_sd($sekolah_id, $peserta_didik_id);
            if (!$status) {
                $error = $this->Msekolah->get_error_message();
                if (!empty($error)) {
                    print_json_error($error);
                } else {
                    print_json_error('Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.');
                }
            }

            //audit trail
            audit_pendaftaran($pendaftaran, "HAPUS PENDAFTARAN SD", "HAPUS Pendaftaran di SD " .$pendaftaran['sekolah']. " an. " .$pendaftaran['nama']);

			print_json_output(array());
        }
		else if ($action=='accept'){
			$sekolah_id = $this->session->get("sekolah_id");
			$peserta_didik_id= $this->request->getPostGet("peserta_didik_id"); 

            $kuota = $this->Msekolah->tcg_kuota_sd($sekolah_id);
            $totalpendaftaran = $this->Msekolah->tcg_totalpendaftaran_sd($sekolah_id);
            if ($totalpendaftaran>=$kuota) {
                print_json_error("Kuota penerimaan sudah terpenuhi (" .$kuota. ")");
            }

            //sudah diterima
            $pendaftaran = $this->Msiswa->tcg_pendaftaran_diterima_sd($peserta_didik_id);
            if ($pendaftaran != null) {
                print_json_error("Sudah diterima di " .$pendaftaran['sekolah']);
            }

            //batasan usia
            $batasan_usia = $this->Mconfig->tcg_batasanusia('SD');
            $siswa = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);

            if ($siswa['tanggal_lahir'] > $batasan_usia['minimal_tanggal_lahir']) {
                print_json_error('Minimal tanggal lahir: ' .$batasan_usia['minimal_tanggal_lahir']. '. Tanggal lahir siswa: ' .$siswa['tanggal_lahir']);
            }

            if ($siswa['tanggal_lahir'] < $batasan_usia['maksimal_tanggal_lahir']) {
                print_json_error('Maksimal tanggal lahir: ' .$batasan_usia['maksimal_tanggal_lahir']. '. Tanggal lahir siswa: ' .$siswa['tanggal_lahir']);
            }            

			$status = $this->Msekolah->tcg_terima_pesertadidik_sd($sekolah_id, $peserta_didik_id);
            if (!$status) {
                $error = $this->Msekolah->get_error_message();
                if (!empty($error)) {
                    print_json_error($error);
                } else {
                    print_json_error('Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.');
                }
            }

            $pendaftaran = $this->Msiswa->tcg_pendaftaran_diterima_sd($peserta_didik_id);

            //audit trail
            audit_pendaftaran($pendaftaran, "PENDAFTARAN SD", "Pendaftaran di SD " .$pendaftaran['sekolah']. " an. " +$pendaftaran['nama']);

			print_json_output($pendaftaran);	
        }
        else if ($action=='sekolah') {
            $mdropdown = new \App\Models\Ppdb\Mconfig();
            $sekolah = $mdropdown->tcg_sekolah_tk_ra();
            
            print_json_output($sekolah);
        }
        else if ($action=='search') {
			$nama = $this->request->getPostGet("nama"); 
			$nisn= $this->request->getPostGet("nisn"); 
			$sekolah_id= $this->request->getPostGet("sekolah_id"); 
            $limit = 1000;

			if (empty($nama) && empty($nisn) && empty($sekolah_id)) {
				//no search
				print_json_output(array(), 1);
                return;
			}

            $daftar = $this->Msekolah->tcg_calon_pesertadidik_sd($nama, $nisn, $sekolah_id, $limit);
            if ($daftar == null) {
                $daftar = array();
            }

            print_json_output($daftar, 1);
        }
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}
	}
       
}
?>