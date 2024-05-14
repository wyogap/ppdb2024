<?php

namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Verifikasi extends PpdbController {

    protected Mprofilsekolah $Msekolah;
    protected Mprofilsiswa $Msiswa;

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
        $this->Msekolah = new Mprofilsekolah();
        $this->Msiswa = new Mprofilsiswa();

        // if($this->session->get('is_logged_in')==FALSE || $this->session->get('peran_id')!=ROLEID_SEKOLAH) {
		// 	redirect(site_url() .'auth');
		// }
    }

	function index()
	{
		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		do {
            $pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 
            $peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 
            if (!empty($pendaftaran_id) || !empty($peserta_didik_id)) {
                return $this->siswa();
            }
    
			$data['sekolah_id'] = $sekolah_id;

            $data['waktuverifikasi'] = $this->Msetting->tcg_cek_waktuverifikasi();
            $data['belum'] = $this->Msekolah->tcg_pendaftarbelumdiverifikasi($sekolah_id);
			$data['sudah'] = $this->Msekolah->tcg_pendaftarsudahlengkap($sekolah_id);
			$data['sedang'] = $this->Msekolah->tcg_pendaftarbelumlengkap($sekolah_id);
			
			$data['info'] = $this->session->getFlashdata('info');
        }
        while (false);

        //Debug
        $data['nama_pengguna'] = "Wahyu Yoga Pratama";
        $data['username'] = "wyogap@gmail.com";
        //END DEBUG

        //content template
        $data['content_template'] = 'verifikasi.tpl';

        $data['page_title'] = 'Verifikasi';
        $this->smarty->render('ppdb2/sekolah/ppdbsekolah.tpl', $data);
    }

	function json() {
		$riwayat = $_GET["riwayat"] ?? null; 
		if ($riwayat == 1) {
			$this->riwayat();
			return;
		}

		$lengkap = $_GET["lengkap"] ?? null; 
		if ($lengkap == 1) {
			$this->sudahdiverifikasi();
			return;
		}
		else if ($lengkap == 0) {
			$this->belumdiverifikasi();
			return;
		}
	}

    function siswa() 
	{
		$pengguna_id = $this->session->get('pengguna_id');

		$pendaftaran_id = $_GET["pendaftaran_id"] ?? null; 
		$peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 
		if (empty($peserta_didik_id))
			$peserta_didik_id = $this->Msekolah->tcg_pesertadidikid_pendaftaran($pendaftaran_id);

		$sekolah_id = $this->session->get("sekolah_id");

		//return redirect()->to("Cinfo");
		$data['settingverifikasiberkas'] = $this->Msetting->tcg_waktuverifikasi();
		$data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id);

		//TODO: to fix.
		$data['klarifikasidinas'] = $this->Msiswa->tcg_profilsiswa_klarifikasidinas($peserta_didik_id)->getRowArray();
		//$data['klarifikasidinas'] = array();

		//var_dump($data['klarifikasidinas']);
		
		$data['files'] = array();
		$data['dokumen'] = array();
		$data['dokumen_tambahan'] = array();
				
		$data['dokumenpendukung'] = $this->Msiswa->tcg_dokumen_pendukung($peserta_didik_id);
		foreach($data['dokumenpendukung']->getResult() as $row) {
			$row->path= base_url(). $row->path;
			$row->web_path= base_url(). $row->web_path;
			$row->thumbnail_path = base_url(). $row->thumbnail_path;
 
            $data['files'][$row->dokumen_id] = array(
                "dokumen_id"=>$row->dokumen_id, 
                "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
                "verifikasi"=>$row->verifikasi,
                "catatan"=>$row->catatan,
                "filename"=>$row->filename, 
                "file_path"=>$row->path, 
                "web_path"=>$row->web_path, 
                "thumbnail_path"=>$row->thumbnail_path);

			if($row->daftar_kelengkapan_id == 8) {
				//dokumen prestasi
				continue;
            }
            else {
                $data['dokumen'][$row->daftar_kelengkapan_id] = array(
                    "dokumen_id"=>$row->dokumen_id, 
                    "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
                    "verifikasi"=>$row->verifikasi,
                    "catatan"=>$row->catatan,
                    "filename"=>$row->filename, 
					"file_path"=>$row->path, 
                    "web_path"=>$row->web_path, 
                    "thumbnail_path"=>$row->thumbnail_path);
            }   
		}

        $query = $this->Msiswa->tcg_dokumen_pendukung_tambahan($peserta_didik_id);
        foreach($query->getResult() as $row) {
            $data['dokumen_tambahan'][$row->daftar_kelengkapan_id] = array(
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

		$query = $this->Msiswa->tcg_berkas_fisik($peserta_didik_id);
        foreach($query->getResult() as $row) {
            $data['berkas_fisik'][$row->daftar_kelengkapan_id] = array(
                "dokumen_id"=>$row->dokumen_id, 
                "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
                "nama"=>$row->nama,
                "berkas_fisik"=>$row->berkas_fisik,
                "penerima_berkas_id"=>$row->penerima_berkas_id,
				"penerima_berkas"=>$row->penerima_berkas,
				"sekolah"=>$row->sekolah,
                "tanggal_berkas"=>$row->tanggal_berkas);
        }

		$data['suratpernyataan'] = $this->Msiswa->tcg_dokumen_pendukung_kelengkapan_id($peserta_didik_id,21);

		//TODO: verify how it work!
		//only need to verify the achievement used for pendaftaran (normally is the highest scoring)
		$data['prestasi'] = $this->Msiswa->tcg_prestasi($peserta_didik_id);

		//tandai sedang verifikasi
		$data['peserta_didik_id'] = $peserta_didik_id;
		$data['pendaftaran_id'] = $pendaftaran_id;
		$this->Msekolah->tcg_sedangverifikasi($pengguna_id, $peserta_didik_id, 1); 

		$data['info'] = $this->session->getFlashdata('info');
		view('ppdb/sekolah/verifikasisiswa/index',$data);
	}

	function prosesverifikasiberkas()
	{
		$pengguna_id = $this->session->get('pengguna_id');
		$sekolah_id = $this->session->get('sekolah_id');
		$peran_id = $this->session->get('peran_id');

		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$peserta_didik_id = $_POST["peserta_didik_id"] ?? null; 

		//TODO: only can verify within the specified timeframe
		$data['settingverifikasiberkas'] = $this->Msetting->tcg_waktuverifikasi();

		$jumlahberkas = 0;
		$jumlahberkasfisik = 0;
		$jumlahupdate = 0;

		//daftar catatan kekurangan
		$kelengkapan_profil = 1;
		$kelengkapan_berkas = 1;
		$catatan_kekurangan = "";

		//daftar prestasi
		$verifikasi_bukti_prestasi = 1;

		//verifikasi profil siswa
		$verifikasi_siswa = array();

		// $kelengkapan_id = 21;
		// echo ''. $_POST["data"] ?? null; (("system_".$kelengkapan_id). ';'. $_POST["data"] ?? null; (("orig_system_".$kelengkapan_id). ';'. $_POST["data"] ?? null; (("catatan_".$kelengkapan_id). ';'. $_POST["data"] ?? null; (("orig_catatan_".$kelengkapan_id). ';<br>' ;

		//verifikasi dokumen pendukung satu-satu
		foreach ($_POST as $key => $value) {
			if(substr($key,0,6)=="radio_"){
				if (empty($value)) {
					//ignore
					continue;
				}

				$dokumen_id = str_replace('radio_','',$key);

				//tidak ada perubahan
                $val = $_POST["radio_".$dokumen_id] ?? null; 
                $orig_val = $_POST["orig_radio_".$dokumen_id] ?? null; 
                $cat = $_POST["catatan_".$dokumen_id] ?? null; 
                $orig_cat = $_POST["orig_catatan_".$dokumen_id] ?? null; 
				if ($val == $orig_val && $cat == $orig_cat) {
					//data lama masih menunjukkan belum lengkap -> dan tidak ada perubahan
					if ($value != 1) {
						$kelengkapan_berkas = 2;
					}
					continue;
				}

				$verifikasi = $value;
				if ($value == 1) {
					$catatan = "";
				} else {
					$catatan = nohtml($_POST["catatan_$dokumen_id"] ?? null);
				}

				//$catatan = str_replace(array("\r\n", "\n", "\r"),"<br>",$catatan);

				//verifikasi dokumen pendukung
				$this->Msiswa->tcg_verifikasi_dokumenpendukung($peserta_didik_id,$dokumen_id,$verifikasi,$catatan,$pengguna_id);

				//verifikasi berkas untuk semua pendaftaran
				$this->Msiswa->tcg_verifikasi_berkas($peserta_didik_id,$tahun_ajaran_id,$dokumen_id,$verifikasi,$pengguna_id);

				//catatan kekurangan untuk riwayat verifikasi
				if ($value != 1) {
					$nama_dokumen = $this->Msetting->tcg_nama_dokumenpendukung($dokumen_id);
					$catatan_kekurangan .= "$nama_dokumen:$catatan;";
					$kelengkapan_berkas = 2;
				} 

				//mark data prestasi 
				if ($dokumen_id == 8 && $verifikasi == 2) {
					$verifikasi_bukti_prestasi = 2;
				}

				$jumlahberkas++;
			}
			else if (substr($key,0,11)=="verifikasi_") {
				if (empty($value)) {
					//ignore
					continue;
				}

				$name = str_replace('verifikasi_','',$key);

				//tidak ada perubahan
                $ver = $_POST["verifikasi_".$name] ?? null; 
                $orig_ver = $_POST["orig_verifikasi_".$name] ?? null; 
                $cat = $_POST["catatan_".$name] ?? null; 
                $orig_cat = $_POST["orig_catatan_".$name] ?? null; 
				if ($ver == $orig_ver && $cat == $orig_cat) {
					if ($value != 1) {
						$kelengkapan_profil = 2;
					}
					continue;
				}

				$verifikasi = $value;
				if ($verifikasi == 1) {
					$catatan = "";
				}
				else {
					$catatan = nohtml($_POST["catatan_".$name] ?? null);
				}

				//$catatan = str_replace(array("\r\n", "\n", "\r"),"<br>",$catatan);

				$verifikasi_siswa["verifikasi_".$name] = $verifikasi;
				$verifikasi_siswa["catatan_".$name] = $catatan;	
				$verifikasi_siswa["konfirmasi_".$name] = $verifikasi;

				if ($value == 3) {
					//add entry verifikasi dinas
					$this->Msiswa->tcg_verifikasidinas_baru($peserta_didik_id,$name,null,$catatan);
				}
				
				if ($value != 1) {
					$title = "";
					switch($name) {
						case "profil": $title = "Identitas Siswa"; break;
						case "lokasi": $title = "Lokasi Rumah"; break;
						case "nilai": $title = "Nilai Kelulusan / Nilai Ujian Nasional"; break;
						case "prestasi": $title = "Data Prestasi"; break;
						case "afirmasi": $title = "Data Afirmasi"; break;
						case "inklusi": $title = "Data Kebutuhan Khusus"; break;
						default: "Tidak ada";
					}
					
					if ($value == 3) {
						$catatan_kekurangan .= "Klarifikasi Dinas ($title):$catatan;";
					}
					else {
						$catatan_kekurangan .= "$title:$catatan;";
					}
					$kelengkapan_profil = 2;
				}

				$jumlahupdate++;	

			}
			else if (substr($key,0,7)=="berkas_") {
				if (!isset($value)) {
					//ignore
					continue;
				}

				$dokumen_id = str_replace('berkas_','',$key);

				//tidak ada perubahan
                $berkas = $_POST["berkas_".$dokumen_id] ?? null; 
                $orig_berkas = $_POST["orig_berkas_".$dokumen_id] ?? null; 
				if ($berkas == $orig_berkas) {
					if ($value != 1) {
						$kelengkapan_berkas = 2;
					}
					continue;
				}

				//verifikasi berkas untuk semua pendaftaran
				$this->Msiswa->tcg_verifikasi_berkas_fisik($peserta_didik_id,$dokumen_id,$value,$pengguna_id);

				//catatan kekurangan untuk riwayat verifikasi
				if ($value == 1) {
					$nama_dokumen = $this->Msetting->tcg_nama_dokumenpendukung($dokumen_id);
					$catatan_kekurangan .= "$nama_dokumen (berkas fisik):diterima;";
				} 
				else {
					$nama_dokumen = $this->Msetting->tcg_nama_dokumenpendukung($dokumen_id);
					$catatan_kekurangan .= "$nama_dokumen (berkas fisik):belum-diterima;";
					//mark belum lengkap
					$kelengkapan_berkas = 2;
				}

				$jumlahberkasfisik++;	
			}

			if(substr($key,0,7)=="system_"){
				if (empty($value)) {
					//ignore
					continue;
				}

				$kelengkapan_id = str_replace('system_','',$key);

				$query = $this->Msiswa->tcg_dokumen_pendukung_kelengkapan_id($peserta_didik_id, $kelengkapan_id);
				if ($query->getNumRows() > 0) {
					return;
				}
				
				//tidak ada perubahan
                $sys = $_POST["system_".$kelengkapan_id] ?? null; 
                $orig_sys = $_POST["orig_system_".$kelengkapan_id] ?? null; 
                $cat = $_POST["catatan_".$kelengkapan_id] ?? null; 
                $orig_cat = $_POST["orig_catatan_".$kelengkapan_id] ?? null; 
				if ($sys == $orig_sys && $cat == $orig_cat) {
					if ($value != 1) {
						$kelengkapan_berkas = 2;
					}
					continue;
				}

				$verifikasi = $value;
				if ($value == 1) {
					$catatan = "";
				} else {
					$catatan = nohtml($_POST["catatan_$kelengkapan_id"] ?? null);
				}

				//$catatan = str_replace(array("\r\n", "\n", "\r"),"<br>",$catatan);

				//catatan kekurangan untuk riwayat verifikasi
				if ($value != 1) {
					$nama_dokumen = $this->Msetting->tcg_nama_daftarkelengkapan($kelengkapan_id);
					$catatan_kekurangan .= "$nama_dokumen:$catatan;";
					$kelengkapan_berkas = 2;
				} 

				//create dokumen dummy
				$this->Msiswa->tcg_dokumen_pendukung_hilang($peserta_didik_id, $kelengkapan_id, $pengguna_id, $catatan);

				$jumlahberkas++;
			}
		}

		//override status of data prestasi if necessary
		if ($verifikasi_bukti_prestasi == 2 && $verifikasi_siswa["verifikasi_prestasi"] != 2) {
			$verifikasi_siswa["verifikasi_prestasi"] = 2;
			$verifikasi_siswa["catatan_prestasi"] = "Verifikasi dokumen bukti prestasi belum benar.";	
			$verifikasi_siswa["konfirmasi_prestasi"] = 2;
			$catatan_kekurangan .= "Data Prestasi:". $verifikasi_siswa["catatan_prestasi"]. ";";
			$kelengkapan_profil = 2;
			$jumlahupdate++;
		}

		//update kelengkapan data
		if ($jumlahupdate > 0) {
			$this->Msiswa->tcg_verifikasi_siswa($peserta_didik_id,$verifikasi_siswa, $pengguna_id);
		}
		
		//if no update, just redirect
		if ($jumlahberkas == 0 && $jumlahupdate == 0 && $jumlahberkasfisik == 0) {
			$this->session->setFlashdata('success', "Tidak ada perubahan data.");
			return redirect()->to("sekolah/verifikasi/siswa?peserta_didik_id=$peserta_didik_id");
			return;
		}

		//var_dump($kelengkapan_berkas); exit;

		//riwayat verifikasi. update it before tcg_ubah_kelengkapanberkas()
		$status_verifikasi = 0;
		if ($kelengkapan_profil == 1 && $kelengkapan_berkas == 1) {
			$status_verifikasi = 1;
		}
		else {
			$status_verifikasi = 2;
		}
		$this->Msiswa->tcg_tambah_riwayatverifikasi($peserta_didik_id, $pengguna_id, $status_verifikasi, $catatan_kekurangan);

		//echo "$status_verifikasi; $kelengkapan_profil; $kelengkapan_berkas"; exit;

		//update status kelengkapan berkas untuk semua pendaftaran
		if ($kelengkapan_profil == 2 || $kelengkapan_berkas == 2) {
			//force to status: belum lengkap
			$this->Msiswa->tcg_ubah_kelengkapanberkas($peserta_didik_id,$tahun_ajaran_id, 2, $pengguna_id);
		} else {
			//sesuai daftar kelengkapan berkas
			$this->Msiswa->tcg_ubah_kelengkapanberkas($peserta_didik_id,$tahun_ajaran_id, 0, $pengguna_id);
		}

		//update lokasi berkas
		if ($jumlahberkasfisik > 0) {
			$this->Msiswa->tcg_ubah_lokasiberkas($peserta_didik_id,$sekolah_id);
		}

		$this->session->setFlashdata('info', "<div class='alert alert-info alert-dismissable'>Data verifikasi telah berhasil disimpan. Status Verifikasi: " . ($status_verifikasi == 1 ? 'SUDAH Lengkap' : 'BELUM Lengkap') . "</div>");
		if ($peran_id == 2) {
			$this->Msekolah->tcg_sedangverifikasi($pengguna_id, $peserta_didik_id, 0); 
			return redirect()->to("sekolah/verifikasi");
		}
		else {
			//$this->Msekolah->tcg_sedangverifikasi($pengguna_id, $peserta_didik_id, 1); 
			return redirect()->to("sekolah/verifikasi/siswa?peserta_didik_id=$peserta_didik_id");
		}
	}

    function magnifier() {
		view('ppdb/sekolah/verifikasi/magnifier');
	}
	
	function riwayat() {
		$peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 

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

	function prestasi() {
		$peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Msiswa->tcg_daftar_prestasi($peserta_didik_id)->getResultArray(); 
			foreach($data['data'] as $key => $valuepair) {
				$data['data'][$key]['file_path'] = base_url(). $valuepair['path'];
				$data['data'][$key]['web_path'] = base_url(). $valuepair['web_path'];
				$data['data'][$key]['thumbnail_path'] = base_url(). $valuepair['thumbnail_path'];
 			}
			echo json_encode($data);	
		}
		else {
			$data['error'] = "not-implemented";
			echo json_encode($data);	
		}
	}

	function sudahdiverifikasi() {
		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
            //TODO: tcg_pendaftarsudahdiverifikasi() or tcg_pendaftarsudahlengkap()
			$data['data'] = $this->Msekolah->tcg_pendaftarsudahdiverifikasi($sekolah_id)->getResultArray(); 
			echo json_encode($data);	
		}
		else {
			$data['error'] = "not-implemented";
			echo json_encode($data);	
		}
	}

	function belumdiverifikasi() {
		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Msekolah->tcg_pendaftarbelumdiverifikasi($sekolah_id)->getResultArray(); 
			echo json_encode($data);	
		}
		else {
			$data['error'] = "not-implemented";
			echo json_encode($data);	
		}
	}

	function belumlengkap() {
		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Msekolah->tcg_pendaftarbelumlengkap($sekolah_id)->getResultArray(); 
			echo json_encode($data);	
		}
		else {
			$data['error'] = "not-implemented";
			echo json_encode($data);	
		}
	}

	function sudahlengkap() {
		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Msekolah->tcg_pendaftarsudahlengkap($sekolah_id)->getResultArray(); 
			echo json_encode($data);	
		}
		else {
			$data['error'] = "not-implemented";
			echo json_encode($data);	
		}
	}

	function berkasdisekolah() {
		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Msekolah->tcg_berkasdisekolah($sekolah_id)->getResultArray(); 
			echo json_encode($data);	
		}
		else {
			$data['error'] = "not-implemented";
			echo json_encode($data);	
		}
	}

	function sedangverifikasi() {
		$pengguna_id = $this->session->get('pengguna_id');

		$peserta_didik_id = $_POST["peserta_didik_id"] ?? null; 
		$aktif = $_POST["aktif"] ?? null; 

		$data = array();

		$data['status'] = $this->Msekolah->tcg_sedangverifikasi($pengguna_id, $peserta_didik_id, $aktif); 
		echo json_encode($data);	
	}
}

?>