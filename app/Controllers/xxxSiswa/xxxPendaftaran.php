<?php

namespace App\Controllers\Siswa;

use App\Controllers\Ppdb\PpdbController;
use App\Libraries\QRCodeLibrary;
use App\Libraries\Uploader;
use App\Models\Ppdb\Admin\Msetting;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Psr\Log\LoggerInterface;

defined('BASEPATH') OR exit('No direct script access allowed');

class Pendaftaran extends PpdbController {

    protected $Msiswa;

    // public function __construct()
	// {
	// 	parent::__construct();
	// 	//return redirect()->to("Cinfo");
	// 	if($this->session->get('isLogged')==FALSE||$this->session->get('peran_id')!=1) {
	// 		return redirect()->to("akun/login");
	// 	}
	// }

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        //$this->smarty = new SmartyLibrary();

        //load model
        $this->Msiswa = new Mprofilsiswa();
        
        if($this->session->get('is_logged_in')==FALSE || $this->session->get('peran_id')!=ROLEID_SISWA) {
			redirect(site_url() .'auth');
		}
    }

	function index()
	{
		$peserta_didik_id = $this->session->get("user_id");
		$kode_wilayah = $this->session->get("kode_wilayah");
		$tanggal_lahir = $this->session->get("tanggal_lahir");
		$tahun_ajaran_id = $this->tahun_ajaran_id;

		// //for consistency
		// $kebutuhan_khusus = $this->session->get("kebutuhan_khusus");
		// if (empty($kebutuhan_khusus) || $kebutuhan_khusus=="0") {
		// 	$kebutuhan_khusus = "Tidak ada";
		// }

		$bentuk = $this->session->get("bentuk");
		$asal_data = $this->session->get("asal_data");

        $profil = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id)->getRowArray();
		if ($profil == null) {
			return redirect()->to("akun/login");
		}

		$kebutuhan_khusus = 1;
		if (empty($profil['kebutuhan_khusus']) || $profil['kebutuhan_khusus']=="0" || $profil['kebutuhan_khusus']=='Tidak ada') {
			$kebutuhan_khusus = 0;
		}

		$afirmasi = 1;
		if ((empty($profil['punya_kip']) || $profil['punya_kip']=="0") && (empty($profil['masuk_bdt']) || $profil['masuk_bdt']=="0")) {
			$afirmasi = 0;
		}

		$data['batasanperubahan'] = $this->Msetting->tcg_batasanperubahan();
		$data['batasansiswa'] = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);
		$data['batasanusia'] = $this->Msetting->tcg_batasanusia();
		$data['daftarjalur'] = $this->Msiswa->tcg_daftarjalur($peserta_didik_id, $kode_wilayah, $tanggal_lahir, $kebutuhan_khusus, $afirmasi);
		$data['daftarpendaftaran'] = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id);
		
		//var_dump($data['daftarjalur']->getResult_array()); exit;

		$data['maxpilihannegeri'] = $this->Msiswa->tcg_batasanpilihan_negeri();
		$data['jumlahpendaftarannegeri'] = $this->Msiswa->tcg_jumlahpendaftaran_negeri($peserta_didik_id);
		$data['maxpilihanswasta'] = $this->Msiswa->tcg_batasanpilihan_swasta();
		$data['jumlahpendaftaranswasta'] = $this->Msiswa->tcg_jumlahpendaftaran_swasta($peserta_didik_id);
		$data['maxpilihan'] = $this->Msiswa->tcg_batasanpilihan();
		$data['jumlahpendaftaran'] = $this->Msiswa->tcg_jumlahpendaftaran($peserta_didik_id);
		
		$data['cek_waktupendaftaran'] = $this->Msetting->tcg_cek_waktupendaftaran();
		$data['waktupendaftaran'] = $this->Msetting->tcg_waktupendaftaran();
		$data['cek_waktusosialisasi'] = $this->Msetting->tcg_cek_waktusosialisasi();
		$data['waktusosialiasi'] = $this->Msetting->tcg_waktusosialisasi();
		$data['cek_waktupendaftaransusulan'] = $this->Msetting->tcg_cek_waktupendaftaransusulan();
		$data['waktupendaftaransusulan'] = $this->Msetting->tcg_waktupendaftaransusulan();

		//echo "Waktu Sosialisasi:".$data['cek_waktusosialisasi']."<br>";

		$data['statusprofil'] = $this->Msiswa->tcg_profilsiswa_status($peserta_didik_id);
		$data['tahapan'] = $this->Msetting->tcg_tahapan_pelaksanaan_aktif();

		$data['satu_zonasi_satu_jalur'] = $this->Msetting->tcg_setting("general",'satu_zonasi_satu_jalur');
		if ($data['satu_zonasi_satu_jalur'] == 1) {
			$data['pendaftaran_dalam_zonasi'] = $this->Msiswa->tcg_jalur_pendaftaran_dalam_zonasi($peserta_didik_id, $tahun_ajaran_id);
		}

		$data['kebutuhan_khusus'] = $kebutuhan_khusus;
		$data['afirmasi'] = $afirmasi;

        $data['info'] = $this->session->getFlashdata('info');
        
        $data['page'] = "siswa-pendaftaran";
		view('siswa/pendaftaran/index',$data);
    }

	function debug()
	{
		$peserta_didik_id = $this->session->get("user_id");
		$kode_wilayah = $this->session->get("kode_wilayah");
		$tanggal_lahir = $this->session->get("tanggal_lahir");
		$tahun_ajaran_id = $this->tahun_ajaran_id;

		// //for consistency
		// $kebutuhan_khusus = $this->session->get("kebutuhan_khusus");
		// if (empty($kebutuhan_khusus) || $kebutuhan_khusus=="0") {
		// 	$kebutuhan_khusus = "Tidak ada";
		// }

		$bentuk = $this->session->get("bentuk");
		$asal_data = $this->session->get("asal_data");

        $profil = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id)->getRowArray();
		if ($profil == null) {
			return redirect()->to("akun/login");
		}

		$kebutuhan_khusus = 1;
		if (empty($profil['kebutuhan_khusus']) || $profil['kebutuhan_khusus']=="0" || $profil['kebutuhan_khusus']=='Tidak ada') {
			$kebutuhan_khusus = 0;
		}

		$afirmasi = 1;
		if ((empty($profil['punya_kip']) || $profil['punya_kip']=="0") && (empty($profil['masuk_bdt']) || $profil['masuk_bdt']=="0")) {
			$afirmasi = 0;
		}

		$data['batasanperubahan'] = $this->Msetting->tcg_batasanperubahan();
		$data['batasansiswa'] = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);
		$data['batasanusia'] = $this->Msetting->tcg_batasanusia();
		$data['daftarjalur'] = $this->Msiswa->tcg_daftarjalur($peserta_didik_id, $kode_wilayah, $tanggal_lahir, $kebutuhan_khusus, $afirmasi);
		$data['daftarpendaftaran'] = $this->Msiswa->tcg_daftarpendaftaran_debug($peserta_didik_id);
		
		//var_dump($data['daftarjalur']->getResult_array()); exit;

		$data['maxpilihannegeri'] = $this->Msiswa->tcg_batasanpilihan_negeri();
		$data['jumlahpendaftarannegeri'] = $this->Msiswa->tcg_jumlahpendaftaran_negeri($peserta_didik_id);
		$data['maxpilihanswasta'] = $this->Msiswa->tcg_batasanpilihan_swasta();
		$data['jumlahpendaftaranswasta'] = $this->Msiswa->tcg_jumlahpendaftaran_swasta($peserta_didik_id);
		$data['maxpilihan'] = $this->Msiswa->tcg_batasanpilihan();
		$data['jumlahpendaftaran'] = $this->Msiswa->tcg_jumlahpendaftaran($peserta_didik_id);
		
		$data['cek_waktupendaftaran'] = $this->Msetting->tcg_cek_waktupendaftaran();
		$data['waktupendaftaran'] = $this->Msetting->tcg_waktupendaftaran();
		$data['cek_waktusosialisasi'] = $this->Msetting->tcg_cek_waktusosialisasi();
		$data['waktusosialiasi'] = $this->Msetting->tcg_waktusosialisasi();
		$data['cek_waktupendaftaransusulan'] = $this->Msetting->tcg_cek_waktupendaftaransusulan();
		$data['waktupendaftaransusulan'] = $this->Msetting->tcg_waktupendaftaransusulan();

		//echo "Waktu Sosialisasi:".$data['cek_waktusosialisasi']."<br>";

		$data['statusprofil'] = $this->Msiswa->tcg_profilsiswa_status($peserta_didik_id);
		$data['tahapan'] = $this->Msetting->tcg_tahapan_pelaksanaan_aktif();

		$data['satu_zonasi_satu_jalur'] = $this->Msetting->tcg_setting("general",'satu_zonasi_satu_jalur');
		if ($data['satu_zonasi_satu_jalur'] == 1) {
			$data['pendaftaran_dalam_zonasi'] = $this->Msiswa->tcg_jalur_pendaftaran_dalam_zonasi($peserta_didik_id, $tahun_ajaran_id);
		}

		$data['kebutuhan_khusus'] = $kebutuhan_khusus;
		$data['afirmasi'] = $afirmasi;

        $data['info'] = $this->session->getFlashdata('info');
        
        $data['page'] = "siswa-pendaftaran";
		view('siswa/pendaftaran/index',$data);
    }

    function json() {
        $peserta_didik_id = $this->session->get("user_id");

        $action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
            $data["error"] = "not-implemented";
			echo json_encode($data);	
        }
		else if ($action=='edit'){
            $data["error"] = "not-implemented";
			echo json_encode($data);	
        }
        else if ($action=='remove') {
            $data["error"] = "not-implemented";
			echo json_encode($data);	
        }
        else if ($action=='create') {
            $data["error"] = "not-implemented";
			echo json_encode($data);	
        }
        else if ($action == "upload") {
            $data["error"] = "not-implemented";
			echo json_encode($data);	
        }   

    }

    function uploaddokumen() {
        $peserta_didik_id = $this->session->get("user_id");

		$action = $_POST["action"] ?? null; 
		if (empty($action) || $action=='view') {
            $data["error"] = "not-implemented";
			echo json_encode($data);	
        }
		else if ($action=='edit'){
            $data = $_POST["data"] ?? null; 
            if ($data == null) {
                $result = array();
                $result["error"] = "no-data";
                echo json_encode($result);
                return;
            }

			//dont save the data at the moment. save it only on submit of full form.
            $result = array();
            $result['data'] = $data;
            $result['dokumen'] = array();

            echo json_encode($result);	
        }
		else if ($action=='remove'){
            $data["error"] = "not-implemented";
			echo json_encode($data);	
        }
		else if ($action=='create'){
            $data["error"] = "not-implemented";
			echo json_encode($data);	
        }
        else if ($action == "upload") {

            $key = $_POST["uploadField"] ?? null; 
            if ($key == null) {
                $data = array();
                $data["error"] = "no-data";
                echo json_encode($data);
                return;
            }

            $arr = explode("_", $key);

            $kelengkapan_id=0;
            if (count($arr) > 1) {
                $kelengkapan_id = $arr[1];
            }

            $data = array();
            if ($kelengkapan_id == 0) {
                $data["error"] = "Kode dokumen tidak dikenal";
                echo json_encode($data);
                return;
            }

            $uploader = new Uploader();
            $fileObj = $uploader->upload($_FILES['upload']);

            if(!empty($fileObj['error'])) {
                $data['error'] = $fileObj['error'];
            } else {
                $data = array("data"=>array(),"files"=>array("files"=>array($fileObj['id']=>$fileObj)),"upload"=>array("id"=>$fileObj['id']));
            }

            echo json_encode($data);
            return;
         }   

    }

	function pilihsekolah()
	{
		$penerapan_id = $_GET["penerapan_id"] ?? 0; 
		
		$peserta_didik_id = $this->session->get("user_id");
		$kode_wilayah = $this->session->get("kode_wilayah");
		$tanggal_lahir = $this->session->get("tanggal_lahir");
		$tahun_ajaran_id = $this->tahun_ajaran_id;

		// //for consistency
		// $kebutuhan_khusus = $this->session->get("kebutuhan_khusus");
		// if (empty($kebutuhan_khusus) || $kebutuhan_khusus=="0") {
		// 	$kebutuhan_khusus = "Tidak ada";
		// }

        $profil = $this->Msiswa->tcg_profilsiswa($peserta_didik_id)->getRowArray();
		if ($profil == null) {
			return redirect()->to("akun/login");
		}

		$kebutuhan_khusus = 1;
		if (empty($profil['kebutuhan_khusus']) || $profil['kebutuhan_khusus']=="0" || $profil['kebutuhan_khusus']=='Tidak ada') {
			$kebutuhan_khusus = 0;
		}

		$afirmasi = 1;
		if ((empty($profil['punya_kip']) || $profil['punya_kip']=="0") && (empty($profil['masuk_bdt']) || $profil['masuk_bdt']=="0")) {
			$afirmasi = 0;
		}

		$jalurpenerapan = $this->Msiswa->tcg_jalurpenerapan($peserta_didik_id, $penerapan_id, $kode_wilayah, $tanggal_lahir, $kebutuhan_khusus, $afirmasi);

		$jalurid = 0;
		$namajalur = "";
		$negeri = 0;
		$swasta = 0;
		foreach($jalurpenerapan->getResult() as $row):
			$jalurid = $row->jalur_id;
			$namajalur = $row->jalur;
			$negeri = $row->sekolah_negeri;
			$swasta = ($row->jalur_id == 5) ? 1 : 0;
		endforeach;

		if($namajalur!=""){
			$data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);
			//$data['daftarsekolah'] = $this->Msiswa->tcg_pilihansekolah($peserta_didik_id, $penerapan_id);
			$data['dokumentambahan'] = $this->Msiswa->tcg_dokumen_pendukung_tambahan_penerapan($peserta_didik_id, $penerapan_id);
			$data['jenispilihan'] = $this->Msiswa->tcg_jenispilihan($peserta_didik_id, $penerapan_id);
			$data['jalurid'] = $jalurid;
			$data['namajalur'] = $namajalur;
			$data['jalurnegeri'] = $negeri;
			$data['jalurswasta'] = $swasta;

			//TODO : file dokumen tambahan
			$data['files'] = array();

			$data['satu_zonasi_satu_jalur'] = $this->Msetting->tcg_setting("general",'satu_zonasi_satu_jalur');
			if ($data['satu_zonasi_satu_jalur'] == 1) {
				$data['pendaftaran_dalam_zonasi'] = $this->Msiswa->tcg_jalur_pendaftaran_dalam_zonasi($peserta_didik_id, $tahun_ajaran_id);
				$data['pendaftaran_luar_zonasi'] = $this->Msiswa->tcg_jalur_pendaftaran_luar_zonasi($peserta_didik_id, $tahun_ajaran_id);
			}

			$data['daftarpendaftaran'] = $this->Msiswa->tcg_daftarpendaftaran_sekolah($peserta_didik_id);

			$data['page'] = 'siswa-pilihsekolah';
			view('siswa/pilihsekolah/index',$data);
		}else{
			$data['page'] = 'siswa-notauthorized';
			view('home/notauthorized',$data);
			return;
		}
	}
	
	function detailsekolah()
	{
		$peserta_didik_id = $this->session->get("user_id");
		$penerapan_id = $_POST["penerapan_id"] ?? 0; 
		$sekolah_id = $_POST["sekolah_id"] ?? null; 

		$data = array();
		$detailsekolah = $this->Msiswa->tcg_profilsekolah($sekolah_id);
		foreach($detailsekolah->getResult() as $row) {
			$data['data']['alamat'] =  $row->alamat_jalan;
			$data['data']['desa'] =  $row->desa_kelurahan;
			$data['data']['kecamatan'] =  $row->kecamatan;
		}

		echo json_encode($data);

		// $data['detailsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
		
		// // $kategori_prestasi = 0;
		// // $detailpenerapan = $this->Msiswa->detailpenerapan();
		// // foreach($detailpenerapan->getResult() as $row):
		// // 	$kategori_prestasi = $row->kategori_prestasi;
		// // endforeach;
		// // if($kategori_prestasi==1){
		// // 	$data['daftarnilaiskoringprestasi'] = $this->Mdropdown->tcg_skoringprestasi($penerapan_id);
		// // }
		// // $data['kategori_prestasi'] = $kategori_prestasi;

		// view('siswa/pilihsekolah/detailsekolah',$data);
	}
	
	function daftar()
	{
		$peserta_didik_id = $this->session->get("user_id");
		$sekolah_id = $_POST["sekolah_id"] ?? null; 
		$penerapan_id = $_POST["penerapan_id"] ?? 0; 
		$jenis_pilihan = $_POST["jenis_pilihan"] ?? null; 

		foreach($_POST as $key => $value)
		{
			if (substr($key, 0, 8) == "dokumen_")
			{
				$arr = explode("_", $key);
				$kelengkapan_id = $arr[1];

				if ($value == "") {
					$this->Msiswa->tcg_hapus_dokumen_pendukung($peserta_didik_id, $kelengkapan_id);
				}
				else {
					$this->Msiswa->tcg_simpan_dokumen_pendukung($value, $peserta_didik_id, $kelengkapan_id,1,0,1);
				}
			}
		}
		
		$flag = 0;
		do {
			$sekolahpendaftaran = $this->Msiswa->tcg_cekpendaftaran($peserta_didik_id, $penerapan_id, $sekolah_id);
			if ($sekolahpendaftaran > 0) {
				$this->session->setFlashdata('error', "Data tidak tersimpan. Sudah mendaftar di sekolah yang dipilih dengan jalur yang sama.");	
				break;
			}

			$satu_zonasi_satu_jalur = $this->Msetting->tcg_setting('general', 'satu_zonasi_satu_jalur');
			if($satu_zonasi_satu_jalur == "1") {
				$flag = 1;
				//TODO: fix usp
				// $query = $this->Msiswa->tcg_cek_satu_zonasi_satu_jalur($peserta_didik_id, $sekolah_id, $penerapan_id);
				// foreach($query->getResult() as $row) {
				// 	$flag = $row->value;
				// 	if ($flag == 0) {
				// 		$this->session->setFlashdata('error', "Data tidak tersimpan. Sudah mendaftar di zonasi Kec. ". $row->nama_zona. " menggunakan jalur ". $row->nama_penerapan. ". Anda hanya bisa mendaftar dengan satu jalur yang sama pada satu zonasi.");	
				// 		break;
				// 	}
				// }
	
				if ($flag == 0) {
					break;
				}
			}

				
			$pilihan_pertama_harus_zonasi = $this->Msetting->tcg_setting('general', 'pilihan_pertama_harus_zonasi');
			if($pilihan_pertama_harus_zonasi == "1") {
				if ($jenis_pilihan==2 && $this->Msiswa->tcg_cek_sekolah_dalam_zonasi($peserta_didik_id, $sekolah_id) == 0) {
					//pilihan ke 2 di luar zonasi. pilihan 1 harus zonasi
					$jalur_id = 1;
					$query = $this->Msiswa->tcg_pendaftaran_jenis_pilihan($peserta_didik_id, 1);
					foreach($query->getResult() as $row) {
						$jalur_id = $row->jalur_id;
					}

					if ($jalur_id != 1) {
						$this->session->setFlashdata('error', "Data tidak tersimpan. Untuk memilih sekolah di luar zonasi untuk pilihan 2, pilihan 1 harus dalam zonasi dengan jalur zonasi.");	
						break;
					}
				}
			}

			$maxpilihan = 0; $jumlahpendaftaran=0;
			$profilsekolah = $this->Msiswa->tcg_profilsekolah($sekolah_id);
			foreach($profilsekolah->getResult() as $row):
				if ($row->status == 'N') {
					$maxpilihan=$this->Msiswa->tcg_batasanpilihan_negeri();
					$jumlahpendaftaran = $this->Msiswa->tcg_jumlahpendaftaran_negeri($peserta_didik_id);
				} else {
					$maxpilihan=$this->Msiswa->tcg_batasanpilihan_swasta();
					$jumlahpendaftaran = $this->Msiswa->tcg_jumlahpendaftaran_swasta($peserta_didik_id);
				}
			endforeach;
	
			//pilihan untuk either negeri atau swasta
			$maxpilihan_all=$this->Msiswa->tcg_batasanpilihan();
			$jumlahpendaftaran_all = $this->Msiswa->tcg_jumlahpendaftaran($peserta_didik_id);
	
			if(($jumlahpendaftaran>=$maxpilihan && $jumlahpendaftaran_all>=$maxpilihan_all)) {
				$this->session->setFlashdata('error', "Data tidak tersimpan. Jumlah pilihan melebihi batas yang ditentukan.");	
			}
			else if($this->Msiswa->tcg_daftar($peserta_didik_id, $penerapan_id, $sekolah_id, $jenis_pilihan)){
				$this->session->setFlashdata('success', "Data telah tersimpan. Silahkan cek kembali di daftar pendaftaran.");	
			}
			else{
				$this->session->setFlashdata('error', "Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.");	
			}

			return redirect()->to("siswa/pendaftaran");
			return;

		} while(false);

		return redirect()->to("siswa/pendaftaran/pilihsekolah?penerapan_id=$penerapan_id");
	}

	function ubahjenispilihan()
	{
		$pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 
		
		//only siswa can do this
		$peserta_didik_id = $this->session->get("user_id");

		$batasanperubahan = $this->Msetting->tcg_batasanperubahan();
		$batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);

		$maxubahjenispilihan=0;
		$ubahjenispilihansiswa=0;
		foreach($batasanperubahan->getResult() as $row):
			$maxubahjenispilihan = $row->ubah_pilihan;
		endforeach;
		foreach($batasansiswa->getResult() as $row):
			$ubahjenispilihansiswa = $row->ubah_pilihan;
		endforeach;

		$data['maxubahjenispilihan'] = $maxubahjenispilihan;
		$data['ubahjenispilihansiswa'] = $ubahjenispilihansiswa;

		$data['detailpilihan'] = $this->Msiswa->tcg_detailpendaftaran($peserta_didik_id, $pendaftaran_id);
		$data['jenispilihan'] = $this->Msiswa->tcg_jenispilihan_perubahan($peserta_didik_id, $pendaftaran_id);

        $data['page'] = 'siswa-ubahpilihan';
		view('siswa/ubahjenispilihan/index',$data);
	}

	function prosesubahjenispilihan()
	{
		$pendaftaran_id = $_POST["pendaftaran_id"] ?? null; 
		$jenis_pilihan_awal = $_POST["jenis_pilihan_awal"] ?? null; 
		$jenis_pilihan_baru = $_POST["jenis_pilihan_baru"] ?? null; 

		//only siswa can do this
		$peserta_didik_id = $this->session->get("user_id");

		$msg = '';
		if($jenis_pilihan_awal!=$jenis_pilihan_baru){
			if($this->Msiswa->tcg_ubahjenispilihan($peserta_didik_id, $pendaftaran_id, $jenis_pilihan_baru)){
				$this->session->setFlashdata('success', "Data telah tersimpan silahkan cek kembali di daftar pendaftaran.");	
			}else{
				$this->session->setFlashdata('error', "Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.");	
			}
		}
		
		return redirect()->to("siswa/pendaftaran");
	}

	function ubahjalur()
	{
		$tahun_ajaran_id = $this->tahun_ajaran_id;	
		$pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 
		
		//only siswa can do this
		$peserta_didik_id = $this->session->get("user_id");
		$kode_wilayah = $this->session->get("kode_wilayah");

		$batasanperubahan = $this->Msetting->tcg_batasanperubahan();
		$batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);

		$maxubahjalur=0;
		$ubahjalursiswa=0;
		foreach($batasanperubahan->getResult() as $row):
			$maxubahjalur = $row->ubah_jalur;
		endforeach;
		foreach($batasansiswa->getResult() as $row):
			$ubahjalursiswa = $row->ubah_jalur;
		endforeach;

		$data['maxubahjalur'] = $maxubahjalur;
		$data['ubahjalursiswa'] = $ubahjalursiswa;

		$detailpilihan = $this->Msiswa->tcg_detailpendaftaran($peserta_didik_id, $pendaftaran_id)->getRowArray();
		if ($detailpilihan == null) {
			//not authorized
			$data['page'] = 'notauthorized';
			view('home/notauthorized',$data);
			return;
		}

		$data['jalurswasta'] = ($detailpilihan['jalur_id']==5)?1:0;
		$data['satu_zonasi_satu_jalur'] = $this->Msetting->tcg_setting("general",'satu_zonasi_satu_jalur');

		// $profilsekolah = $this->Msekolah->tcg_profilsekolah($detailpilihan['sekolah_id'])->getRowArray();
		// if ($profilsekolah == null) {
		// 	//not authorized
		// 	$data['page'] = 'notauthorized';
		// 	view('home/notauthorized',$data);
		// 	return;
		// }

		$jalurid_dalam_zonasi = 0;
		$namajalur_dalam_zonasi = "";
		$dalam_zonasi = 0;

		if ($data['satu_zonasi_satu_jalur'] == 1) {
			$dalam_zonasi = $this->Msiswa->tcg_cek_sekolah_dalam_zonasi($peserta_didik_id, $detailpilihan['sekolah_id']);

			if ($dalam_zonasi == 1) {
				$penerimaan_dalam_zonasi = $this->Msiswa->tcg_jalur_pendaftaran_dalam_zonasi($peserta_didik_id, $tahun_ajaran_id, $pendaftaran_id);
				foreach($penerimaan_dalam_zonasi->getResult() as $row) {
					$jalurid_dalam_zonasi = $row->jalur_id;
					$namajalur_dalam_zonasi = $row->jalur;
				}
			}
		}

		$data['detailpilihan'] = $detailpilihan;
		$data['dalam_zonasi'] = $dalam_zonasi;
		$data['jalurid_dalam_zonasi'] = $jalurid_dalam_zonasi;
		$data['namajalur_dalam_zonasi'] = $namajalur_dalam_zonasi;

		if ($jalurid_dalam_zonasi == 0) {
			$data['daftarpenerapan'] = $this->Msiswa->tcg_daftarpenerapan_perubahan($peserta_didik_id, $detailpilihan['penerapan_id'])->getResult_array();
		}
		else {
			$data['daftarpenerapan'] = array();
		}

        $data['page'] = 'siswa-ubahjalur';
		view('siswa/ubahjalur/index',$data);
	}

	function prosesubahjalur()
	{
		//TODO: implement
		$pendaftaran_id = $_POST["pendaftaran_id"] ?? null; 
		$jalur_penerapan_awal = $_POST["jalur_penerapan_awal"] ?? null; 
		$jalur_penerapan_baru = $_POST["jalur_penerapan_baru"] ?? null; 

		//only siswa can do this
		$peserta_didik_id = $this->session->get("user_id");

		$msg = '';
		if($jalur_penerapan_awal!=$jalur_penerapan_baru){
			if($this->Msiswa->tcg_ubah_jalur($peserta_didik_id, $pendaftaran_id, $jalur_penerapan_baru)){
				$this->session->setFlashdata('success', "Data telah tersimpan silahkan cek kembali di daftar pendaftaran.");	
			}else{
				$this->session->setFlashdata('error', "Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.");	
			}
		}
		
		return redirect()->to("siswa/pendaftaran");
	}

	function ubahsekolah()
	{
		$penerapan_id = $_GET["penerapan_id"] ?? null; 
		$pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 

		//only siswa can do this
		$peserta_didik_id = $this->session->get("user_id");
		$kode_wilayah = $this->session->get("kode_wilayah");
		$tanggal_lahir = $this->session->get("tanggal_lahir");
		$tahun_ajaran_id = $this->tahun_ajaran_id;

		// $kebutuhan_khusus = $this->session->get("kebutuhan_khusus");

        $profil = $this->Msiswa->tcg_profilsiswa($peserta_didik_id)->getRowArray();
		if ($profil == null) {
			return redirect()->to("akun/login");
		}

		$kebutuhan_khusus = 1;
		if (empty($profil['kebutuhan_khusus']) || $profil['kebutuhan_khusus']=="0" || $profil['kebutuhan_khusus']=='Tidak ada') {
			$kebutuhan_khusus = 0;
		}

		$afirmasi = 1;
		if ((empty($profil['punya_kip']) || $profil['punya_kip']=="0") && (empty($profil['masuk_bdt']) || $profil['masuk_bdt']=="0")) {
			$afirmasi = 0;
		}

		$jalurpenerapan = $this->Msiswa->tcg_jalurpenerapan($peserta_didik_id, $penerapan_id, $kode_wilayah, $tanggal_lahir, $kebutuhan_khusus, $afirmasi);
		
		$jalurid = 0;
		$namajalur = "";
		$negeri = 0;
		$swasta = 0;
		foreach($jalurpenerapan->getResult() as $row):
			$jalurid = $row->jalur_id;
			$namajalur = $row->jalur;
			$negeri = $row->sekolah_negeri;
			$swasta = ($row->jalur_id == 5) ? 1 : 0;
		endforeach;

		$batasanperubahan = $this->Msetting->tcg_batasanperubahan();
		$batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);

		$maxubahsekolah=0;
		$ubahsekolahsiswa=0;
		foreach($batasanperubahan->getResult() as $row):
			$maxubahsekolah = $row->ubah_sekolah;
		endforeach;
		foreach($batasansiswa->getResult() as $row):
			$ubahsekolahsiswa = $row->ubah_sekolah;
		endforeach;

		$data['maxubahsekolah'] = $maxubahsekolah;
		$data['ubahsekolahsiswa'] = $ubahsekolahsiswa;

		if($namajalur!=""){
			$data['detailpilihan'] = $this->Msiswa->tcg_detailpendaftaran($peserta_didik_id, $pendaftaran_id);
			$data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);

			$jenis_pilihan = 0;
			foreach($data['detailpilihan']->getResult() as $row) {
				$jenis_pilihan = $row->jenis_pilihan;
			}
            $data['daftarsekolah'] = $this->Msiswa->tcg_pilihansekolah($peserta_didik_id, $penerapan_id, $jenis_pilihan);
            
			$data['jalurid'] = $jalurid;
			$data['namajalur'] = $namajalur;
			$data['jalurnegeri'] = $negeri;
			$data['jalurswasta'] = $swasta;

			$data['satu_zonasi_satu_jalur'] = $this->Msetting->tcg_setting("general",'satu_zonasi_satu_jalur');
			if ($data['satu_zonasi_satu_jalur'] == 1) {
				$data['pendaftaran_dalam_zonasi'] = $this->Msiswa->tcg_jalur_pendaftaran_dalam_zonasi($peserta_didik_id, $tahun_ajaran_id);
				$data['pendaftaran_luar_zonasi'] = $this->Msiswa->tcg_jalur_pendaftaran_luar_zonasi($peserta_didik_id, $tahun_ajaran_id);
			}

			$data['daftarpendaftaran'] = $this->Msiswa->tcg_daftarpendaftaran_sekolah($peserta_didik_id);

			$data['page'] = 'siswa-ubahsekolah';
			view('siswa/ubahsekolah/index',$data);
		}else{
			$data['page'] = 'siswa-notauthorized';
			view('home/notauthorized',$data);
			return;
		}
	}
	
	function detailubahsekolah()
	{
		$sekolah_id = $_POST["sekolah_id"] ?? null; 
		
		//only siswa can do this
		$peserta_didik_id = $this->session->get("user_id");

		$batasanperubahan = $this->Msetting->tcg_batasanperubahan();
		$batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);

		$maxubahsekolah = 0;
		$batasanperubahan = $this->Msetting->tcg_batasanperubahan();
		foreach($batasanperubahan->getResult() as $row):
			$maxubahsekolah = $row->ubah_sekolah;
		endforeach;
		
		$ubahsekolahsiswa = 0;
		$batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);
		foreach($batasansiswa->getResult() as $row):
			$ubahsekolahsiswa = $row->ubah_sekolah;
		endforeach;
		
		$data['maxubahsekolah'] = $maxubahsekolah;
		$data['ubahsekolahsiswa'] = $ubahsekolahsiswa;

		$data['detailsekolah'] = $this->Msiswa->tcg_profilsekolah($sekolah_id);
		view('siswa/ubahsekolah/detailsekolah',$data);
	}
	
	function prosesubahsekolah()
	{
		$pendaftaran_id = $_POST["pendaftaran_id"] ?? null; 
		$sekolah_id_baru = $_POST["sekolah_id"] ?? null; 

		//only siswa can do this
		$peserta_didik_id = $this->session->get("user_id");

        $this->Msetting = new Msetting();

		$batasanperubahan = $this->Msetting->tcg_batasanperubahan();
		$batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);

		$maxubahsekolah = 0;
		$batasanperubahan = $this->Msetting->tcg_batasanperubahan();
		foreach($batasanperubahan->getResult() as $row):
			$maxubahsekolah = $row->ubah_sekolah;
		endforeach;
		
		$ubahsekolahsiswa = 0;
		$batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);
		foreach($batasansiswa->getResult() as $row):
			$ubahsekolahsiswa = $row->ubah_sekolah;
		endforeach;
		
		$jenispilihan = 0;
		$detailpendaftaran = $this->Msiswa->tcg_detailpendaftaran($peserta_didik_id, $pendaftaran_id);
		foreach($detailpendaftaran->getResult() as $row):
			$jenispilihan = $row->jenis_pilihan;
		endforeach;
		
		$msg = '';
		if($maxubahsekolah>$ubahsekolahsiswa || $jenispilihan==0){
			if($this->Msiswa->tcg_ubahpilihansekolah($peserta_didik_id, $pendaftaran_id, $sekolah_id_baru)){
				$this->session->setFlashdata('success', "Data telah tersimpan silahkan cek kembali di daftar pendaftaran.");	
			}else{
				$this->session->setFlashdata('error', "Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.");	
			}
		}else{
			$this->session->setFlashdata('error', "Data tidak tersimpan. Jumlah pilihan melebihi batas yang ditentukan atau sekolah dipilih lebih dari satu kali.");	
		}
		
		if (!empty($redirect)) {
			redirect($redirect);
		} else {
			return redirect()->to("siswa/pendaftaran");
		}
	}
	
	function hapus()
	{
		$pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 
		$peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 
		if (empty($peserta_didik_id))
			$peserta_didik_id = $this->session->get("user_id");

		$pengguna_id = $this->session->get("user_id");
		$peran_id = $this->session->get("peran_id");

		if($peran_id == 1 && ($peserta_didik_id != $pengguna_id)) {
			//siswa can only update his/her own
			$data['page'] = 'siswa-pendaftaran';
			view("home/notauthorized?$pendaftaran_id&$peserta_didik_id",$data);
			return;
		}

        $this->Msetting = new Msetting();

        $maxhapuspendaftaran = 0;
		$batasanperubahan = $this->Msetting->tcg_batasanperubahan();
		foreach($batasanperubahan->getResult() as $row):
			$maxhapuspendaftaran = $row->hapus_pendaftaran;
		endforeach;
		
		$hapuspendaftaransiswa = 0;
		$batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);
		foreach($batasansiswa->getResult() as $row):
			$hapuspendaftaransiswa = $row->hapus_pendaftaran;
		endforeach;

		$data['maxhapuspendaftaran'] = $maxhapuspendaftaran;
		$data['hapuspendaftaransiswa'] = $hapuspendaftaransiswa;

		$data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);
		$data['detailpendaftaran'] = $this->Msiswa->tcg_detailpendaftaran($peserta_didik_id, $pendaftaran_id);
		
		if($data['detailpendaftaran']->num_rows() == 0) {
			//not authorized
			$data['page'] = 'siswa-notauthorized';
			view('home/notauthorized',$data);
		} else {
			$data['page'] = 'siswa-hapuspendaftaran';
			view('siswa/hapuspendaftaran/index',$data);
		}
	}
	
	function proseshapus()
	{
		$pendaftaran_id = $_POST["pendaftaran_id"] ?? null; 
		$keterangan = $_POST["keterangan"] ?? null; 
		$redirect = $_POST["redirect"] ?? null; 
		
		$peserta_didik_id = $_POST["peserta_didik_id"] ?? null; 
		if (empty($peserta_didik_id))
			$peserta_didik_id = $this->session->get("user_id");

		$pengguna_id = $this->session->get("user_id");
		$peran_id = $this->session->get("peran_id");

		if($peran_id == 1 && $peserta_didik_id != $pengguna_id) {
			//siswa can only update his/her own
			$data['page'] = 'siswa-pendaftaran';
			view('home/notauthorized',$data);
			return;
		}
		
        $this->Msetting = new Msetting();

		$maxhapuspendaftaran = 0;
		$batasanperubahan = $this->Msetting->tcg_batasanperubahan();
		foreach($batasanperubahan->getResult() as $row):
			$maxhapuspendaftaran = $row->hapus_pendaftaran;
		endforeach;

		$hapuspendaftaransiswa = 0;
		$batasansiswa = $this->Msiswa->tcg_batasansiswa($peserta_didik_id);
		foreach($batasansiswa->getResult() as $row):
			$hapuspendaftaransiswa = $row->hapus_pendaftaran;
		endforeach;

		$msg = '';
		if($maxhapuspendaftaran>$hapuspendaftaransiswa){
			if($this->Msiswa->tcg_hapuspendaftaran($peserta_didik_id, $pendaftaran_id, $keterangan, $pengguna_id)){
				$this->session->setFlashdata('success', "Pendaftaran telah berhasil dihapus.");	
			}else{
				$this->session->setFlashdata('error', "Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.");	
			}
		}else{
			$this->session->setFlashdata('error', "Data tidak tersimpan. Sudah melampaui batasan yang ditentukan.");	
		}

		if (!empty($redirect)) {
			redirect($redirect);
		} else {
			return redirect()->to("siswa/pendaftaran");
		}
	}

	function buktipendaftaran() {
		$peserta_didik_id = $this->session->get("user_id");
		$username = $this->session->get("username");
		$peran_id = $this->session->get("peran_id");
		$nisn = $this->session->get("nisn");
		
        $qrcode = new QRCodeLibrary();
		$config['cacheable'] = true; //boolean, the default is true
        // $config['cachedir'] = './qrcode/'; //string, the default is application/cache/
        // $config['errorlog'] = './qrcode/'; //string, the default is application/logs/
        // $config['imagedir'] = './qrcode/'; //direktori penyimpanan qr code
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
		$data['daftarpendaftaran'] = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id);
		
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
		$html = $view->render('siswa/pendaftaran/buktipendaftaran',$data);
		
		// $dompdf = new Dompdf(array('enable_remote' => true));
		$dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("BuktiPendaftaran.pdf", array("Attachment"=>0));
	}

	function kesanggupankelengkapanakte() {
		$peserta_didik_id = $this->session->get("user_id");
		$username = $this->session->get("username");
		$peran_id = $this->session->get("peran_id");
		$nisn = $this->session->get("nisn");
		
        $qrcode = new QRCodeLibrary();
		$config['cacheable'] = true; //boolean, the default is true
        // $config['cachedir'] = './qrcode/'; //string, the default is application/cache/
        // $config['errorlog'] = './qrcode/'; //string, the default is application/logs/
        // $config['imagedir'] = './qrcode/'; //direktori penyimpanan qr code
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
        $html = $view->render('siswa/pendaftaran/kesanggupankelengkapanakte',$data);

		// $dompdf = new Dompdf(array('enable_remote' => true));
		$dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("KesanggupanKelengkapanAkte.pdf", array("Attachment"=>0));
	}

	function rekapitulasizonasi() {
		$peserta_didik_id = $this->session->get("user_id");

		$data['data'] = $this->Msiswa->tcg_rekapitulasi_sekolah_dalam_zonasi($peserta_didik_id)->getResult_array();

		echo json_encode($data);
	}

	function dokumentpendukungjalur() {
		$penerapan_id = $_POST["penerapan_id"] ?? null; 

		$peserta_didik_id = $this->session->get("user_id");

		$data['dokumentambahan'] = $this->Msiswa->tcg_dokumen_pendukung_tambahan_penerapan($peserta_didik_id, $penerapan_id);
		$data['files'] = array();

		view('siswa/ubahjalur/dokumenpendukung',$data);
	}

	function daftarsekolah() {
		$penerapan_id = $_POST["penerapan_id"] ?? null; 
		$jenis_pilihan = $_POST["jenis_pilihan"] ?? null; 
		$peserta_didik_id = $this->session->get("user_id");

		$data = $this->Msiswa->tcg_pilihansekolah($peserta_didik_id, $penerapan_id, $jenis_pilihan);

		if ($data == null) {
			$data['error'] = "Gagal mengambil daftar sekolah";
		}
		else {
			$data = $data->getResult_array();
		}
		echo json_encode($data);
        return;
	}

}
?>