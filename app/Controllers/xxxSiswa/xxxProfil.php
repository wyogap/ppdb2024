<?php

namespace App\Controllers\Siswa;

use App\Controllers\Ppdb\PpdbController;
use App\Libraries\QRCodeLibrary;
use App\Libraries\Uploader;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Psr\Log\LoggerInterface;

class Profil extends PpdbController {

    protected $Msiswa;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        //$this->smarty = new SmartyLibrary();

        //load model
        $this->Msiswa = new Mprofilsiswa();
       
        //TODO: disable first for testing
        // if($this->session->get('is_logged_in')==FALSE || $this->session->get('peran_id')!=ROLEID_SISWA) {
		// 	redirect(site_url() .'auth');
		// }
    }

	function index()
	{
        $peserta_didik_id = $this->session->get("user_id");
        if ($this->session->get('peran_id') == 4) {
            $peserta_didik_id = $_GET["peserta_didik_id"] ?? null;
        }

        $tahun_ajaran_id = $this->tahun_ajaran_id;

        $data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id);
        foreach($data['profilsiswa']->getResult() as $row) {
            if (!empty($row->path_surat_pernyataan)) {
                $row->path_surat_pernyataan= base_url(). $row->path_surat_pernyataan;
                $row->thumbnail_surat_pernyataan= base_url(). $row->thumbnail_surat_pernyataan;
            }
            $row->catatan_profil=nl2br(trim($row->catatan_profil));
            $row->catatan_lokasi=nl2br(trim($row->catatan_lokasi));
            $row->catatan_nilai=nl2br(trim($row->catatan_nilai));
            $row->catatan_prestasi=nl2br(trim($row->catatan_prestasi));
            $row->catatan_afirmasi=nl2br(trim($row->catatan_afirmasi));
            $row->catatan_inklusi=nl2br(trim($row->catatan_inklusi));
        }

        //selama ada pendaftaran aktif, kunci tidak bisa edit
        $data['dikunci'] = ($this->Msiswa->tcg_cek_pendaftaran_aktif($peserta_didik_id, $tahun_ajaran_id) > 0 ? 1 : 0);

		$data['peserta_didik_id'] = $peserta_didik_id;
		$data['tahun_ajaran'] = $tahun_ajaran_id;
		$data['tahapan'] = $this->Msetting->tcg_tahapan_pelaksanaan_aktif();
		$data['upload_dokumen'] = $this->Msetting->tcg_upload_dokumen();
        
        $data['dokumen'] = array();
        $data['files'] = array();

        //a hack for consistent logic
        $data['dokumen'][8] = array();
        $data['dokumen'][8]['verifikasi'] = 1;

        $query = $this->Msiswa->tcg_dokumen_pendukung($peserta_didik_id);
        foreach($query->getResult() as $row) {
            $row->catatan = nl2br(trim($row->catatan));

            if($row->daftar_kelengkapan_id == 8) {
                //dokumen prestasi
                if(!isset($data['dokumen'][8])) {
                    $data['dokumen'][8] = array();
                }
                $data['dokumen'][8][$row->dokumen_id] = array(
                    "dokumen_id"=>$row->dokumen_id, 
                    "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
                    "verifikasi"=>$row->verifikasi,
                    "catatan"=>$row->catatan,
                    "filename"=>$row->filename, 
                    "file_path"=>base_url().$row->path, 
                    "web_path"=>base_url().$row->web_path, 
                    "thumbnail_path"=>base_url().$row->thumbnail_path);
                //a hack for consistent logic
                if ($row->verifikasi == 2) {
                    $data['dokumen'][8]['verifikasi'] = 2;
                }
                else if ($row->verifikasi == 0 && $data['dokumen'][8]['verifikasi'] == 1) {
                    $data['dokumen'][8]['verifikasi'] = 0;
                }
            }
            else {
                $data['dokumen'][$row->daftar_kelengkapan_id] = array(
                    "dokumen_id"=>$row->dokumen_id, 
                    "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
                    "verifikasi"=>$row->verifikasi,
                    "catatan"=>$row->catatan,
                    "filename"=>$row->filename, 
                    "file_path"=>base_url().$row->path, 
                    "web_path"=>base_url().$row->web_path, 
                    "thumbnail_path"=>base_url().$row->thumbnail_path);
            }   

            $data['files'][$row->dokumen_id] = array(
                "dokumen_id"=>$row->dokumen_id, 
                "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
                "verifikasi"=>$row->verifikasi,
                "catatan"=>$row->catatan,
                "filename"=>$row->filename, 
                "file_path"=>base_url().$row->path, 
                "web_path"=>base_url().$row->web_path, 
                "thumbnail_path"=>base_url().$row->thumbnail_path);
        }

        $query = $this->Msiswa->tcg_dokumen_pendukung_tambahan($peserta_didik_id);
        $data['dokumen_tambahan'] = array();
        $data['verifikasi_dokumen_tambahan'] = 1;
        foreach($query->getResult() as $row) {

            $row->catatan = nl2br(trim($row->catatan));

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

            if ($row->verifikasi == 2) {
                $data['verifikasi_dokumen_tambahan'] = 2;
            }
            else if ($row->verifikasi == 0 && $data['verifikasi_dokumen_tambahan'] == 1) {
                $data['verifikasi_dokumen_tambahan'] = 0;
            }
        }

        $data['berkas_fisik'] = array();
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

        $data['page'] = "siswa-profil";
		view('siswa/beranda/index',$data);
    }

    function json() {
        $peserta_didik_id = $this->session->get("user_id");
        if ($this->session->get('peran_id') == 4) {
            $peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 
        }

		$action = $_POST["action"] ?? null; 
		if (empty($action) || $action=='view') {
            $data["error"] = "not-implemented";
			echo json_encode($data);	
        }
		else if ($action=='edit'){
            $data = $_POST["data"] ?? null;  

            $result = array();
            $result['data'] = array();
            $result['dokumen'] = array();

            foreach ($data as $key => $valuepair) {
                if (!empty($key) && $key!='null') $peserta_didik_id = $key;

                if (!empty($valuepair['nilai_semester'])) {
                    //udah ada nilai rata-rata
                    unset($valuepair['kelas4_sem1']);
                    unset($valuepair['kelas4_sem2']);
                    unset($valuepair['kelas5_sem1']);
                    unset($valuepair['kelas5_sem2']);
                    unset($valuepair['kelas6_sem1']);
                    unset($valuepair['kelas6_sem2']);
                }

                foreach($valuepair as $field => $value) {
 					//Important: a bug in dt editor!!
                    if ($value=="" && $field=="nilai_lulus") {
                        unset($valuepair[$field]);
                        continue;
                    }
                    else if ($value=="" && 
                            ($field=="nilai_semester" || $field=="kelas4_sem1" || $field=="kelas4_sem2" 
                            || $field=="kelas5_sem1" || $field=="kelas5_sem2" || $field=="kelas6_sem1" || $field=="kelas6_sem2")
                    ) {
                        unset($valuepair[$field]);
                        continue;
                    }
                    
                    if ($field == 'dokumen_21') {
                        //surat pernyataan kebenaran dokumen
                        $kelengkapan_id = 21;

                        if ($value == "") {
                            $this->Msiswa->tcg_hapus_dokumen_pendukung($peserta_didik_id, $kelengkapan_id);

                            $result["data"][$field] = $value;
                            $valuepair['surat_pernyataan_kebenaran_dokumen'] = "";
                        }
                        else {
                            $query = $this->Msiswa->tcg_simpan_dokumen_pendukung($value, $peserta_didik_id, $kelengkapan_id,1,0,0);

                            $result["data"][$field] = $value;
                            foreach($query->getResult() as $row) {
                                $result['dokumen'][$row->daftar_kelengkapan_id] = array(
                                    "dokumen_id"=>$row->dokumen_id, 
                                    "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
                                    "verifikasi"=>'0',
                                    "catatan"=>'',
                                    "filename"=>$row->filename, 
                                    "file_path"=>base_url().$row->path, 
                                    "web_path"=>base_url().$row->web_path, 
                                    "thumbnail_path"=>base_url().$row->thumbnail_path, 
                                    "created_on"=>$row->created_on);

                                $valuepair['surat_pernyataan_kebenaran_dokumen'] = $row->dokumen_id;
                            }
                        }

                        unset($valuepair[$field]);
                    }
                    else if (substr($field, 0, 8) == 'dokumen_') {
                        $arr = explode("_", $field);
                        $kelengkapan_id = $arr[1];

                        if ($value == "") {
                            $this->Msiswa->tcg_hapus_dokumen_pendukung($peserta_didik_id, $kelengkapan_id);

                            $result["data"][$field] = $value;
                        }
                        else {
                            $query = $this->Msiswa->tcg_simpan_dokumen_pendukung($value, $peserta_didik_id, $kelengkapan_id,1,0,0);

                            $result["data"][$field] = $value;
                            foreach($query->getResult() as $row) {
                                if($row->daftar_kelengkapan_id == 8) {
                                    //ignore
                                    continue;

                                    // //dokumen prestasi
                                    // if(!isset($data['dokumen'][8])) {
                                    //     $data['dokumen'][8] = array();
                                    // }
                                    // $result['dokumen'][8][$row->dokumen_id] = array("dokumen_id"=>$row->dokumen_id, "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, "filename"=>$row->filename, "web_path"=>base_url().$row->web_path, "thumbnail_path"=>base_url().$row->thumbnail_path, "created_on"=>$row->created_on);
                                }
                                else {
                                    $result['dokumen'][$row->daftar_kelengkapan_id] = array(
                                        "dokumen_id"=>$row->dokumen_id, 
                                        "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
                                        "verifikasi"=>'0',
                                        "catatan"=>'',
                                        "filename"=>$row->filename, 
                                        "file_path"=>base_url().$row->path, 
                                        "web_path"=>base_url().$row->web_path, 
                                        "thumbnail_path"=>base_url().$row->thumbnail_path, 
                                        "created_on"=>$row->created_on);
                                }   
                            }
                        }

                        unset($valuepair[$field]);
                    }
                    else if ($field == "nilai_lulus") {
                        $value = floatval($value);
                        if ($value > 100) { $value=100; }
                        if ($value < 0) { $value=0; }

                        $retval = $this->Msiswa->tcg_ubahnilaikelulusan($peserta_didik_id, $value);

                        if ($retval['status'] == 0) {
                            $result["error"] = $retval['message'];
                            unset($valuepair[$field]);
                            break;
                        }
                        $result["data"][$field] = $value;
                        unset($valuepair[$field]);
                    }
                    else if ($field == "nilai_semester") {
                        $value = floatval($value);
                        if ($value > 100) { $value=100; }
                        if ($value < 0) { $value=0; }

                        $retval = $this->Msiswa->tcg_ubahnilaisemester($peserta_didik_id, $value, $value, $value, $value, $value, $value);

                        if ($retval['status'] == 0) {
                            $result["error"] = $retval['message'];
                            unset($valuepair[$field]);
                            break;
                        }
                        $result["data"][$field] = $value;
                        unset($valuepair[$field]);
                    }
                    else if ($field == "kelas4_sem1") {
                        $value = floatval($value);
                        if ($value > 100) { $value=100; }
                        if ($value < 0) { $value=0; }

                        $retval = $this->Msiswa->tcg_ubahnilaisemester($peserta_didik_id, $value, -1, -1, -1, -1, -1);

                        if ($retval['status'] == 0) {
                            $result["error"] = $retval['message'];
                            unset($valuepair[$field]);
                            break;
                        }
                        $result["data"][$field] = $value;
                        unset($valuepair[$field]);
                    }
                    else if ($field == "kelas4_sem2") {
                        $value = floatval($value);
                        if ($value > 100) { $value=100; }
                        if ($value < 0) { $value=0; }

                        $retval = $this->Msiswa->tcg_ubahnilaisemester($peserta_didik_id, -1, $value, -1, -1, -1, -1);

                        if ($retval['status'] == 0) {
                            $result["error"] = $retval['message'];
                            unset($valuepair[$field]);
                            break;
                        }
                        $result["data"][$field] = $value;
                        unset($valuepair[$field]);
                    }
                    else if ($field == "kelas5_sem1") {
                        $value = floatval($value);
                        if ($value > 100) { $value=100; }
                        if ($value < 0) { $value=0; }

                        $retval = $this->Msiswa->tcg_ubahnilaisemester($peserta_didik_id, -1, -1, $value, -1, -1, -1);

                        if ($retval['status'] == 0) {
                            $result["error"] = $retval['message'];
                            unset($valuepair[$field]);
                            break;
                        }
                        $result["data"][$field] = $value;
                        unset($valuepair[$field]);
                    }
                    else if ($field == "kelas5_sem2") {
                        $value = floatval($value);
                        if ($value > 100) { $value=100; }
                        if ($value < 0) { $value=0; }

                        $retval = $this->Msiswa->tcg_ubahnilaisemester($peserta_didik_id, -1, -1, -1, $value, -1, -1);

                        if ($retval['status'] == 0) {
                            $result["error"] = $retval['message'];
                            unset($valuepair[$field]);
                            break;
                        }
                        $result["data"][$field] = $value;
                        unset($valuepair[$field]);
                    }
                    else if ($field == "kelas6_sem1") {
                        $value = floatval($value);
                        if ($value > 100) { $value=100; }
                        if ($value < 0) { $value=0; }

                        $retval = $this->Msiswa->tcg_ubahnilaisemester($peserta_didik_id, -1, -1, -1, -1, $value, -1);

                        if ($retval['status'] == 0) {
                            $result["error"] = $retval['message'];
                            unset($valuepair[$field]);
                            break;
                        }
                        $result["data"][$field] = $value;
                        unset($valuepair[$field]);
                    }
                    else if ($field == "kelas6_sem2") {
                        $value = floatval($value);
                        if ($value > 100) { $value=100; }
                        if ($value < 0) { $value=0; }

                        $retval = $this->Msiswa->tcg_ubahnilaisemester($peserta_didik_id, -1, -1, -1, -1, -1, $value);

                        if ($retval['status'] == 0) {
                            $result["error"] = $retval['message'];
                            unset($valuepair[$field]);
                            break;
                        }
                        $result["data"][$field] = $value;
                        unset($valuepair[$field]);
                    }
                    else if ($field == "nilai_bin") {
                        $value = floatval($value);
                        if ($value > 100) { $value=100; }
                        if ($value < 0) { $value=0; }

                        $retval = $this->Msiswa->tcg_ubahnilaiusbn($peserta_didik_id, $value, -1, -1);

                        if ($retval['status'] == 0) {
                            $result["error"] = $retval['message'];
                            unset($valuepair[$field]);
                            break;
                        }
                        $result["data"][$field] = $value;
                        unset($valuepair[$field]);
                    }
                    else if ($field == "nilai_mat") {
                        $value = floatval($value);
                        if ($value > 100) { $value=100; }
                        if ($value < 0) { $value=0; }

                        $retval = $this->Msiswa->tcg_ubahnilaiusbn($peserta_didik_id, -1, $value, -1);

                        if ($retval['status'] == 0) {
                            $result["error"] = $retval['message'];
                            unset($valuepair[$field]);
                            break;
                        }
                        $result["data"][$field] = $value;
                        unset($valuepair[$field]);
                    }
                    else if ($field == "nilai_ipa") {
                        $value = floatval($value);
                        if ($value > 100) { $value=100; }
                        if ($value < 0) { $value=0; }

                        $retval = $this->Msiswa->tcg_ubahnilaiusbn($peserta_didik_id, -1, -1, $value);

                        if ($retval['status'] == 0) {
                            $result["error"] = $retval['message'];
                            unset($valuepair[$field]);
                            break;
                        }
                        $result["data"][$field] = $value;
                        unset($valuepair[$field]);
                    }
                    else if ($field == "punya_nilai_un") {
                        if ($value == 0) {
                            $this->Msiswa->tcg_ubahnilaiusbn($peserta_didik_id, 0, 0, 0);
                        }
                    }
                    else if ($field == "punya_prestasi") {
                        //handled separately
                        if ($value == 0) {

                        }
                    }
                    else if ($field == "punya_kip") {
                        if ($value == 0) {
                            $valuepair["no_kip"] = null;
                        }
                    }
                    else if ($field == "masuk_bdt") {
                        if ($value == 0) {
                            $valuepair["no_bdt"] = null;
                        }
                    }
                    else if ($field == "nomor_bdt") {
                        if ($value == "-" || $value == "") {
                            $valuepair["punya_bdt"] = 0;
                            $valuepair["no_bdt"] = null;
                            unset($valuepair["nomor_bdt"]);
                        } 
                        else {
                            $valuepair["no_bdt"] = $value;
                            unset($valuepair["nomor_bdt"]);
                        }
                    }
                    else if ($field == "nomor_kip") {
                        if ($value == "-" || $value == "") {
                            $valuepair["punya_kip"] = 0;
                            $valuepair["no_kip"] = null;
                            unset($valuepair["nomor_kip"]);
                        } 
                        else {
                            $valuepair["no_kip"] = $value;
                            unset($valuepair["nomor_kip"]);
                        }
                    }
                }

                //update the profil
                if (count($valuepair) > 0) {
                    $this->Msiswa->tcg_ubah_profil_siswa($peserta_didik_id,$valuepair);

                    foreach($valuepair as $field => $value) {
                        $result["data"][$field] = $value;
                    }
                }
            }

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
         else if ($action == "rotate") {
            $dokumen_id = $_POST["dokumen_id"] ?? null;
            $degree = $_POST["degree"] ?? null;

            $result = array();
            $query = $this->Msiswa->tcg_detil_dokumen_pendukung($dokumen_id);
            
            //$result['dokumen_id'] = $dokumen_id;
            //$result['data'] = $query->getResultArray();

            $uploader = new Uploader();
            foreach($query->getResult() as $row) {
                $webpath = $row->web_path;
                $thumbpath = $row->thumbnail_path;

                $ext = pathinfo($webpath, PATHINFO_EXTENSION);
                $dirname = pathinfo($webpath, PATHINFO_DIRNAME);

                $filename_baru = rand(). time();
                $webpath_baru = $dirname. "/". $filename_baru. ".". $ext;
                $thumbpath_baru = $dirname. "/". $filename_baru. "_thumb.". $ext;

                $msg = $uploader->imagerotate(FCPATH. $webpath, FCPATH. $webpath_baru, $degree);
                if ($msg != "") {
                    $result['error'] = $msg;
                    break;
                }
   
                $msg = $uploader->imagerotate(FCPATH. $thumbpath, FCPATH. $thumbpath_baru, $degree);
                if ($msg != "") {
                    $result['error'] = $msg;
                    break;
                }
   
                //update the file
                $valuepair = array(
                    'path' => $webpath_baru,
                    'web_path' => $webpath_baru,
                    'thumbnail_path' => $thumbpath_baru
                );
                $this->Msiswa->tcg_ubah_dokumen_pendukung($dokumen_id, $valuepair);

                //return the new data
                $result['data'][$dokumen_id] = array(
                    "dokumen_id"=>$row->dokumen_id, 
                    "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
                    "verifikasi"=>'0',
                    "catatan"=>'',
                    "filename"=>$row->filename, 
                    "file_path"=>base_url().$webpath_baru, 
                    "web_path"=>base_url().$webpath_baru, 
                    "thumbnail_path"=>base_url().$thumbpath_baru, 
                    "created_on"=>$row->created_on);

                // //remove old files
                // if (!empty($webpath) && ile_exists(FCPATH. $webpath))
                //     unlink(FCPATH. $webpath);

                // if (!empty($webpath) && ile_exists(FCPATH. $thumbpath))
                //     unlink(FCPATH. $thumbpath);

            }

            echo json_encode($result);
            return;
         }
    }

	function suratpernyataan() {
		$peserta_didik_id = $this->session->get("user_id");
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

	function riwayat() {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

        $peserta_didik_id = $this->session->get("user_id");
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

}
?>
