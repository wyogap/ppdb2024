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
    
    // public function __construct()
	// {
	// 	parent::__construct();
	// 	if($this->session->get('isLogged')==FALSE||$this->session->get('peran_id')!=5) {
	// 		return redirect()->to("akun/login");
	// 	}
	// }

	function index()
	{
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

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

        //debugging
        if (__DEBUGGING__) {

        }

        $data['use_select2'] = 1;
        $data['use_datatable'] = 1;
        $data['use_datatable_editor'] = 1;

        //content template
        $data['content_template'] = 'penerimaan.tpl';

		$data['page_title'] = "Penerimaan Siswa Baru";
        $this->smarty->render('ppdb/dapodik/ppdbdapodik.tpl', $data);

	}

	// function cari() {
	// 	$nama = $_POST["data"] ?? null; (("nama");
	// 	$nisn= $_POST["data"] ?? null; (("nisn");
	// 	$nik= $_POST["data"] ?? null; (("nik");
	// 	$sekolah_id= $_POST["data"] ?? null; (("sekolah_id");
	// 	$jenis_kelamin= $_POST["data"] ?? null; (("jenis_kelamin");
	// 	$kode_desa= $_POST["data"] ?? null; (("kode_desa");
	// 	$kode_kecamatan= $_POST["data"] ?? null; (("kode_kecamatan");

	// 	$this->load->model(array('Mdinas','Mdropdown'));

	// 	$data['daftar'] = $this->Mdinas->tcg_cari_pesertadidik($nama, $nisn, $nik, $sekolah_id, $jenis_kelamin, $kode_desa, $kode_kecamatan);
	// 	view('admin/pesertadidik/daftarpencarian',$data);
	// }

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
                } else {
                    print_json_error('Tidak berhasil mendapatkan daftar penerimaan.');
                }
            }

			print_json_output($result);
		}
        else if ($action=='create'){
			$sekolah_id = $this->session->get("sekolah_id");
            $values = $this->request->getPost("data");

            if (empty($values)) {
                print_json_error("Data siswa baru tidak valid/kosong.");
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
                $data = $this->Msiswa->tcg_update_siswa($k, $v);

                if ($data != null) {
                    //TODO: audit trail
                }

                $json[] = $this->Msekolah->tcg_penerimaan_sd_detil($k);
            }

            print_json_output($json);
        }
        else if ($action=='remove') {
			$sekolah_id = $this->session->get("sekolah_id");
            $peserta_didik_id = $this->request->getPostGet('peserta_didik_id');

            $status = $this->Msekolah->tcg_hapus_pesertadidik_sd($sekolah_id, $peserta_didik_id);
            if (!$status) {
                $error = $this->Msekolah->get_error_message();
                if (!empty($error)) {
                    print_json_error($error);
                } else {
                    print_json_error('Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.');
                }
            }

			print_json_output(array());
        }
		else if ($action=='accept'){
			$sekolah_id = $this->session->get("sekolah_id");
			$peserta_didik_id= $this->request->getPostGet("peserta_didik_id"); 

            //sudah diterima
            $pendaftaran = $this->Msiswa->tcg_pendaftaran_diterima_sd($peserta_didik_id);
            if ($pendaftaran != null) {
                print_json_error("Sudah diterima di " .$pendaftaran['sekolah']);
            }

            //batasan usia
            $batasan_usia = $this->Mconfig->tcg_batasanusia('SD');
            $siswa = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);

            if ($siswa['tanggal_lahir'] > $batasan_usia['minimal_tanggal_lahir']) {
                print_json_error('Minimal tanggal lahir: ', $batasan_usia['minimal_tanggal_lahir'], '. Tanggal lahir siswa: ', $siswa['tanggal_lahir']);
            }

            if ($siswa['tanggal_lahir'] < $batasan_usia['maksimal_tanggal_lahir']) {
                print_json_error('Maksimal tanggal lahir: ', $batasan_usia['maksimal_tanggal_lahir'], '. Tanggal lahir siswa: ', $siswa['tanggal_lahir']);
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

			print_json_output(null);	
        }
        else if ($action=='sekolah') {
            $mdropdown = new \App\Models\Ppdb\Mconfig();
            $sekolah = $mdropdown->tcg_sekolah_tk_ra();

            print_json_output($sekolah);
        }
        else if ($action=='search') {
			$nama = $this->request->getPostGet("nama"); 
			$nisn= $this->request->getPostGet("nisn"); 
            $limit = 1000;

			if (empty($nama) && empty($nisn)) {
				//no search
				print_json_error("Tidak ada yang perlu dicari.");
			}

            $daftar = $this->Msekolah->tcg_calon_pesertadidik_sd($nama, $nisn, null, null, null, null, null, $limit);
            print_json_output($daftar);
        }
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}
	}

}
?>