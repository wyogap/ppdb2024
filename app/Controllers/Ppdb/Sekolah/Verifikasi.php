<?php

namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Verifikasi extends PpdbController {

    protected static $ROLE_ID = ROLEID_SEKOLAH;      

    protected Mprofilsekolah $Msekolah;
    protected Mprofilsiswa $Msiswa;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load model
        $this->Msekolah = new Mprofilsekolah();
        $this->Msiswa = new Mprofilsiswa();

        //is-json
        if (!$this->is_json) {
            //other than the index, all are json call
            $this->is_json = ($this->method != '') && ($this->method != 'index');
        }
    }

	function index()
	{
        $sekolah_id = $this->session->get('sekolah_id');
        if (empty($sekolah_id)) {
			return $this->notauthorized();
		}

        $data['impersonasi_sekolah'] = $this->session->get("impersonasi_sekolah");
        if ($data['impersonasi_sekolah'] == 1) {
            $data['profil'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
        }

		do {   
			$data['sekolah_id'] = $sekolah_id;

            $data['waktuverifikasi'] = $this->Mconfig->tcg_waktuverifikasi();
            if (empty($data['waktuverifikasi'])) {
                $data['cek_waktuverifikasi'] = 0;
            }
            else {
                $data['cek_waktuverifikasi'] = ($data['waktuverifikasi']['aktif'] == 1) ? 1 : 0;
            }
            $data['waktusosialisasi'] = $this->Mconfig->tcg_waktusosialisasi();
            if (empty($data['waktusosialisasi'])) {
                $data['cek_waktusosialisasi'] = 0;
            }
            else {
                $data['cek_waktusosialisasi'] = ($data['waktusosialisasi']['aktif'] == 1) ? 1 : 0;
            }
            $data['flag_upload_dokumen'] = $this->setting->get('upload_dokumen');

            $data['daftarskoring'] = $this->Mconfig->tcg_lookup_daftarskoring_prestasi();
			// $data['info'] = $this->session->getFlashdata('info');
        }
        while (false);

        $data['provinsi'] = $this->Mconfig->tcg_provinsi();

        //debugging
        if (__DEBUGGING__) {
            $data['cek_waktuverifikasi'] = 1;
        }

        $data['use_datatable'] = 1;
        $data['use_leaflet'] = 1;

        //content template
        $data['content_template'] = 'verifikasi.tpl';

        $data['page'] = 'verifikasi';
        $data['page_title'] = 'Verifikasi';
        $this->smarty->render('ppdb/sekolah/ppdbsekolah.tpl', $data);
    }

    function profilsiswa() 
	{
		$pengguna_id = $this->session->get('user_id');

		$pendaftaran_id = $this->request->getPostGet("pendaftaran_id");
        if (empty($pendaftaran_id)) {
            print_json_error("Pendaftaran tidak ditemukan.");
            return;
        }

		$peserta_didik_id = $this->request->getPostGet("peserta_didik_id"); 
		if (empty($peserta_didik_id))
			$peserta_didik_id = $this->Msekolah->tcg_pesertadidikid_from_pendaftaranid($pendaftaran_id);

        $data = array();

		$profil = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id);
        if ($profil == null) {
            print_json_error("Pendaftaran tidak ditemukan.");
            return;
        }
        
        $data['profil'] = $profil;

		$dokumenpendukung = $this->Msiswa->tcg_dokumenpendukung($peserta_didik_id);
        foreach ($dokumenpendukung as $k => $v) {
            if ($v['verifikasi'] == 0) {
                $dokumenpendukung[ $k ]['status'] = "Belum Diverifikasi";
            }
            else if ($v['verifikasi'] == 1) {
                $dokumenpendukung[ $k ]['status'] = "Sudah BENAR";
            }
            else if ($v['verifikasi'] == 2) {
                $dokumenpendukung[ $k ]['status'] = "BELUM Benar";
            }
            else if ($v['verifikasi'] == 3) {
                $dokumenpendukung[ $k ]['status'] = "Tidak Ada";
            }
            //unfortunately moustache treat 0 as true, so we need to convert it to boolean
            $dokumenpendukung[ $k ]['is_tambahan'] = $v['tambahan'] ? true : false;
        }
        $data['dokumen'] = $dokumenpendukung;

        $suratpernyataan = $this->Msiswa->tcg_dokumenpendukung_from_kelengkapanid($peserta_didik_id,DOCID_SUKET_KEBENARAN_DOK);
        $data['surat_pernyataan'] = $suratpernyataan;

		// $data['prestasi'] = $this->Msiswa->tcg_daftarprestasi($peserta_didik_id);

		//tandai sedang verifikasi
		$this->Msekolah->tcg_touch_verifikasi($pengguna_id, $peserta_didik_id, 1); 
		
        print_json_output($data);
	}

	// function prosesverifikasiberkas()
	// {
	// 	$pengguna_id = $this->session->get('user_id');
	// 	$sekolah_id = $this->session->get('sekolah_id');
	// 	$peran_id = $this->session->get('peran_id');

	// 	$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

	// 	$peserta_didik_id = $_POST["peserta_didik_id"] ?? null; 

	// 	//TODO: only can verify within the specified timeframe
	// 	$data['settingverifikasiberkas'] = $this->Msetting->tcg_waktuverifikasi();

	// 	$jumlahberkas = 0;
	// 	$jumlahberkasfisik = 0;
	// 	$jumlahupdate = 0;

	// 	//daftar catatan kekurangan
	// 	$kelengkapan_profil = 1;
	// 	$kelengkapan_berkas = 1;
	// 	$catatan_kekurangan = "";

	// 	//daftar prestasi
	// 	$verifikasi_bukti_prestasi = 1;

	// 	//verifikasi profil siswa
	// 	$verifikasi_siswa = array();

	// 	// $kelengkapan_id = 21;
	// 	// echo ''. $_POST["data"] ?? null; (("system_".$kelengkapan_id). ';'. $_POST["data"] ?? null; (("orig_system_".$kelengkapan_id). ';'. $_POST["data"] ?? null; (("catatan_".$kelengkapan_id). ';'. $_POST["data"] ?? null; (("orig_catatan_".$kelengkapan_id). ';<br>' ;

	// 	//verifikasi dokumen pendukung satu-satu
	// 	foreach ($_POST as $key => $value) {
	// 		if(substr($key,0,6)=="radio_"){
	// 			if (empty($value)) {
	// 				//ignore
	// 				continue;
	// 			}

	// 			$dokumen_id = str_replace('radio_','',$key);

	// 			//tidak ada perubahan
    //             $val = $_POST["radio_".$dokumen_id] ?? null; 
    //             $orig_val = $_POST["orig_radio_".$dokumen_id] ?? null; 
    //             $cat = $_POST["catatan_".$dokumen_id] ?? null; 
    //             $orig_cat = $_POST["orig_catatan_".$dokumen_id] ?? null; 
	// 			if ($val == $orig_val && $cat == $orig_cat) {
	// 				//data lama masih menunjukkan belum lengkap -> dan tidak ada perubahan
	// 				if ($value != 1) {
	// 					$kelengkapan_berkas = 2;
	// 				}
	// 				continue;
	// 			}

	// 			$verifikasi = $value;
	// 			if ($value == 1) {
	// 				$catatan = "";
	// 			} else {
	// 				$catatan = nohtml($_POST["catatan_$dokumen_id"] ?? null);
	// 			}

	// 			//$catatan = str_replace(array("\r\n", "\n", "\r"),"<br>",$catatan);

	// 			//verifikasi dokumen pendukung
	// 			$this->Msiswa->tcg_verifikasi_dokumenpendukung($peserta_didik_id,$dokumen_id,$verifikasi,$catatan,$pengguna_id);

	// 			//verifikasi berkas untuk semua pendaftaran
	// 			$this->Msiswa->tcg_verifikasi_berkas($peserta_didik_id,$tahun_ajaran_id,$dokumen_id,$verifikasi,$pengguna_id);

	// 			//catatan kekurangan untuk riwayat verifikasi
	// 			if ($value != 1) {
	// 				$nama_dokumen = $this->Msetting->tcg_nama_dokumenpendukung($dokumen_id);
	// 				$catatan_kekurangan .= "$nama_dokumen:$catatan;";
	// 				$kelengkapan_berkas = 2;
	// 			} 

	// 			//mark data prestasi 
	// 			if ($dokumen_id == 8 && $verifikasi == 2) {
	// 				$verifikasi_bukti_prestasi = 2;
	// 			}

	// 			$jumlahberkas++;
	// 		}
	// 		else if (substr($key,0,11)=="verifikasi_") {
	// 			if (empty($value)) {
	// 				//ignore
	// 				continue;
	// 			}

	// 			$name = str_replace('verifikasi_','',$key);

	// 			//tidak ada perubahan
    //             $ver = $_POST["verifikasi_".$name] ?? null; 
    //             $orig_ver = $_POST["orig_verifikasi_".$name] ?? null; 
    //             $cat = $_POST["catatan_".$name] ?? null; 
    //             $orig_cat = $_POST["orig_catatan_".$name] ?? null; 
	// 			if ($ver == $orig_ver && $cat == $orig_cat) {
	// 				if ($value != 1) {
	// 					$kelengkapan_profil = 2;
	// 				}
	// 				continue;
	// 			}

	// 			$verifikasi = $value;
	// 			if ($verifikasi == 1) {
	// 				$catatan = "";
	// 			}
	// 			else {
	// 				$catatan = nohtml($_POST["catatan_".$name] ?? null);
	// 			}

	// 			//$catatan = str_replace(array("\r\n", "\n", "\r"),"<br>",$catatan);

	// 			$verifikasi_siswa["verifikasi_".$name] = $verifikasi;
	// 			$verifikasi_siswa["catatan_".$name] = $catatan;	
	// 			$verifikasi_siswa["konfirmasi_".$name] = $verifikasi;

	// 			if ($value == 3) {
	// 				//add entry verifikasi dinas
	// 				$this->Msiswa->tcg_verifikasidinas_baru($peserta_didik_id,$name,null,$catatan);
	// 			}
				
	// 			if ($value != 1) {
	// 				$title = "";
	// 				switch($name) {
	// 					case "profil": $title = "Identitas Siswa"; break;
	// 					case "lokasi": $title = "Lokasi Rumah"; break;
	// 					case "nilai": $title = "Nilai Kelulusan / Nilai Ujian Nasional"; break;
	// 					case "prestasi": $title = "Data Prestasi"; break;
	// 					case "afirmasi": $title = "Data Afirmasi"; break;
	// 					case "inklusi": $title = "Data Kebutuhan Khusus"; break;
	// 					default: "Tidak ada";
	// 				}
					
	// 				if ($value == 3) {
	// 					$catatan_kekurangan .= "Klarifikasi Dinas ($title):$catatan;";
	// 				}
	// 				else {
	// 					$catatan_kekurangan .= "$title:$catatan;";
	// 				}
	// 				$kelengkapan_profil = 2;
	// 			}

	// 			$jumlahupdate++;	

	// 		}
	// 		else if (substr($key,0,7)=="berkas_") {
	// 			if (!isset($value)) {
	// 				//ignore
	// 				continue;
	// 			}

	// 			$dokumen_id = str_replace('berkas_','',$key);

	// 			//tidak ada perubahan
    //             $berkas = $_POST["berkas_".$dokumen_id] ?? null; 
    //             $orig_berkas = $_POST["orig_berkas_".$dokumen_id] ?? null; 
	// 			if ($berkas == $orig_berkas) {
	// 				if ($value != 1) {
	// 					$kelengkapan_berkas = 2;
	// 				}
	// 				continue;
	// 			}

	// 			//verifikasi berkas untuk semua pendaftaran
	// 			$this->Msiswa->tcg_verifikasi_berkas_fisik($peserta_didik_id,$dokumen_id,$value,$pengguna_id);

	// 			//catatan kekurangan untuk riwayat verifikasi
	// 			if ($value == 1) {
	// 				$nama_dokumen = $this->Msetting->tcg_nama_dokumenpendukung($dokumen_id);
	// 				$catatan_kekurangan .= "$nama_dokumen (berkas fisik):diterima;";
	// 			} 
	// 			else {
	// 				$nama_dokumen = $this->Msetting->tcg_nama_dokumenpendukung($dokumen_id);
	// 				$catatan_kekurangan .= "$nama_dokumen (berkas fisik):belum-diterima;";
	// 				//mark belum lengkap
	// 				$kelengkapan_berkas = 2;
	// 			}

	// 			$jumlahberkasfisik++;	
	// 		}

	// 		if(substr($key,0,7)=="system_"){
	// 			if (empty($value)) {
	// 				//ignore
	// 				continue;
	// 			}

	// 			$kelengkapan_id = str_replace('system_','',$key);

	// 			$query = $this->Msiswa->tcg_dokumen_pendukung_kelengkapan_id($peserta_didik_id, $kelengkapan_id);
	// 			if ($query->getNumRows() > 0) {
	// 				return;
	// 			}
				
	// 			//tidak ada perubahan
    //             $sys = $_POST["system_".$kelengkapan_id] ?? null; 
    //             $orig_sys = $_POST["orig_system_".$kelengkapan_id] ?? null; 
    //             $cat = $_POST["catatan_".$kelengkapan_id] ?? null; 
    //             $orig_cat = $_POST["orig_catatan_".$kelengkapan_id] ?? null; 
	// 			if ($sys == $orig_sys && $cat == $orig_cat) {
	// 				if ($value != 1) {
	// 					$kelengkapan_berkas = 2;
	// 				}
	// 				continue;
	// 			}

	// 			$verifikasi = $value;
	// 			if ($value == 1) {
	// 				$catatan = "";
	// 			} else {
	// 				$catatan = nohtml($_POST["catatan_$kelengkapan_id"] ?? null);
	// 			}

	// 			//$catatan = str_replace(array("\r\n", "\n", "\r"),"<br>",$catatan);

	// 			//catatan kekurangan untuk riwayat verifikasi
	// 			if ($value != 1) {
	// 				$nama_dokumen = $this->Msetting->tcg_nama_daftarkelengkapan($kelengkapan_id);
	// 				$catatan_kekurangan .= "$nama_dokumen:$catatan;";
	// 				$kelengkapan_berkas = 2;
	// 			} 

	// 			//create dokumen dummy
	// 			$this->Msiswa->tcg_dokumen_pendukung_hilang($peserta_didik_id, $kelengkapan_id, $pengguna_id, $catatan);

	// 			$jumlahberkas++;
	// 		}
	// 	}

	// 	//override status of data prestasi if necessary
	// 	if ($verifikasi_bukti_prestasi == 2 && $verifikasi_siswa["verifikasi_prestasi"] != 2) {
	// 		$verifikasi_siswa["verifikasi_prestasi"] = 2;
	// 		$verifikasi_siswa["catatan_prestasi"] = "Verifikasi dokumen bukti prestasi belum benar.";	
	// 		$verifikasi_siswa["konfirmasi_prestasi"] = 2;
	// 		$catatan_kekurangan .= "Data Prestasi:". $verifikasi_siswa["catatan_prestasi"]. ";";
	// 		$kelengkapan_profil = 2;
	// 		$jumlahupdate++;
	// 	}

	// 	//update kelengkapan data
	// 	if ($jumlahupdate > 0) {
	// 		$this->Msiswa->tcg_verifikasi_siswa($peserta_didik_id,$verifikasi_siswa, $pengguna_id);
	// 	}
		
	// 	//if no update, just redirect
	// 	if ($jumlahberkas == 0 && $jumlahupdate == 0 && $jumlahberkasfisik == 0) {
	// 		$this->session->setFlashdata('success', "Tidak ada perubahan data.");
	// 		return redirect()->to("sekolah/verifikasi/siswa?peserta_didik_id=$peserta_didik_id");
	// 		return;
	// 	}

	// 	//var_dump($kelengkapan_berkas); exit;

	// 	//riwayat verifikasi. update it before tcg_ubah_kelengkapanberkas()
	// 	$status_verifikasi = 0;
	// 	if ($kelengkapan_profil == 1 && $kelengkapan_berkas == 1) {
	// 		$status_verifikasi = 1;
	// 	}
	// 	else {
	// 		$status_verifikasi = 2;
	// 	}
	// 	$this->Msiswa->tcg_tambah_riwayatverifikasi($peserta_didik_id, $pengguna_id, $status_verifikasi, $catatan_kekurangan);

	// 	//echo "$status_verifikasi; $kelengkapan_profil; $kelengkapan_berkas"; exit;

	// 	//update status kelengkapan berkas untuk semua pendaftaran
	// 	if ($kelengkapan_profil == 2 || $kelengkapan_berkas == 2) {
	// 		//force to status: belum lengkap
	// 		$this->Msiswa->tcg_ubah_kelengkapanberkas($peserta_didik_id,$tahun_ajaran_id, 2, $pengguna_id);
	// 	} else {
	// 		//sesuai daftar kelengkapan berkas
	// 		$this->Msiswa->tcg_ubah_kelengkapanberkas($peserta_didik_id,$tahun_ajaran_id, 0, $pengguna_id);
	// 	}

	// 	//update lokasi berkas
	// 	if ($jumlahberkasfisik > 0) {
	// 		$this->Msiswa->tcg_ubah_lokasiberkas($peserta_didik_id,$sekolah_id);
	// 	}

	// 	$this->session->setFlashdata('info', "<div class='alert alert-info alert-dismissable'>Data verifikasi telah berhasil disimpan. Status Verifikasi: " . ($status_verifikasi == 1 ? 'SUDAH Lengkap' : 'BELUM Lengkap') . "</div>");
	// 	if ($peran_id == 2) {
	// 		$this->Msekolah->tcg_touch_verifikasi($pengguna_id, $peserta_didik_id, 0); 
	// 		return redirect()->to("sekolah/verifikasi");
	// 	}
	// 	else {
	// 		//$this->Msekolah->tcg_sedangverifikasi($pengguna_id, $peserta_didik_id, 1); 
	// 		return redirect()->to("sekolah/verifikasi/siswa?peserta_didik_id=$peserta_didik_id");
	// 	}
	// }

    function simpan() {
        $data = $this->request->getPost("data");
        if (empty($data))   
            print_json_error("Invalid data");

        $peserta_didik_id = $this->request->getPost('peserta_didik_id');

		//only can verify within the specified timeframe
		$cek_waktuverifikasi = $this->Mconfig->tcg_cek_waktuverifikasi();

        //debugging
        if (__DEBUGGING__) {
            $cek_waktuverifikasi = 1;
        }

        if ($cek_waktuverifikasi != 1) {
            print_json_error("Tidak sedang tahapan verifikasi");
        }

        $updatedprofil = !empty($data['profil']) ? $data['profil'] : array();
        $updateddokumen = !empty($data['dokumen']) ? $data['dokumen'] : array();

        $message = array();
        $kelengkapan_profil = 1;
        $kelengkapan_dokumen = 1;
        $jml_verifikasi = 0;
        
        if (empty($updatedprofil) && empty($updateddokumen)) {
            print_json_error("Tidak ada perubahan data");
        }

        $pengguna_id = $this->session->get('user_id');
        $this->Msekolah->tcg_touch_verifikasi($pengguna_id, $peserta_didik_id, 1); 

        //oldvalues
        $siswa = $this->Msiswa->tcg_profilsiswa_detil($peserta_didik_id);
        if ($siswa == null) {
            print_json_error("Invalid ID siswa");
        }

        //VERIFIKASI DATA
        do {
            //only save changed data
            $updated = array();
            foreach($updatedprofil as $key => $val) {
                //echo $val ." - ". $siswa[$key];
                if ($val != $siswa[$key]) {
                    if (substr($key,0,8)=="catatan_") {
                        $updated[$key] = nohtml($updatedprofil[$key]);
                        // //only update catatan if verifikasi != 2 (belum verifikasi/belum benar/eskalasi dinas)
                        // $tag = substr($key,8, strlen($key)-8);
                        // $verifikasi = (isset($updatedprofil['verifikasi_' .$tag]) ? $updatedprofil['verifikasi_' .$tag] : $siswa['verifikasi_' .$tag]);
                        // if ($verifikasi != 1) {
                        //     $updated[$key] = nohtml($updatedprofil[$key]);
                        // }
                    } 
                    else if ($val == 1 && substr($key,0,11)=="verifikasi_") {
                        //if verifikasi is set = 1 (sudah benar), reset catatan
                        $updated[$key] = $updatedprofil[$key];
                        $tag = substr($key,11, strlen($key)-11);
                        $updated['catatan_' .$tag] = null;
                    }
                    else {
                        //internally, decimal point is '.' not ','
                        if ($key == 'nilai_semester' || $key == 'nilai_kelulusan' || $key == 'nilai_bin' || $key == 'nilai_mat' || $key == 'nilai_ipa') {
                            $val = str_replace(',', '.', $val);
                        }
                        $updated[$key] = $val;
                    }
                }
            }

            if (count($updated) == 0) {
                break;
            }

            $updated['terakhir_verifikasi_oleh'] = $pengguna_id;
            $updated['terakhir_verifikasi_timestamp'] = gmdate('Y/m/d H:i:s');
            
            $detail = $this->Msiswa->tcg_update_siswa($peserta_didik_id, $updated);

            unset($updated['terakhir_verifikasi_oleh']);
            unset($updated['terakhir_verifikasi_timestamp']);

            if ($detail == null)
                print_json_error("Tidak berhasil mengubah data siswa.");

            //audit trail
            audit_siswa($siswa, "VERIFIKASI PROFIL", "Verifikasi dan perbaikan data siswa", array_keys($updated), $updated, $siswa);

            //RIWAYAT VERIFIKASI
            //perbaikan data
            $perbaikan = array();
            foreach($updated as $key => $val) {
                if (substr($key,0,11)!="verifikasi_" && substr($key,0,8)!="catatan_") {
                    $perbaikan[$key] = $updatedprofil[$key];
                }
            }

            if (count($perbaikan) > 0) {
                $str = "Perbaikan data: ";
                foreach($perbaikan as $key => $value) {
                    $str .= "$key=$value, ";
                }
                $message[] = substr($str, 0, strlen($str)-2);
                $jml_verifikasi++;
            }

            //status verifikasi profil
            foreach($updated as $key => $val) {
                if (substr($key,0,11)!="verifikasi_") continue;

                $tag = substr($key,11,strlen($key)-11);
                $tagname = "";
                switch($tag) {
                    case "profil": $tagname = "Identitas Siswa"; break;
                    case "lokasi": $tagname = "Lokasi Rumah"; break;
                    case "nilai": $tagname = "Nilai Kelulusan / Nilai Ujian Nasional"; break;
                    case "prestasi": $tagname = "Data Prestasi"; break;
                    case "afirmasi": $tagname = "Data Afirmasi"; break;
                    case "inklusi": $tagname = "Data Kebutuhan Khusus"; break;
                    default: $tag;
                }

                if ($val == 0) {
                    //belum diverifikasi
                    $str = $tagname ." direset menjadi belum diverifikasi.";
                    if (!empty($updated['catatan_' .$tag])) {
                        $str .= " Catatan: " .$updated['catatan_' .$tag];
                    }
                    $message[] = $str;
                    $kelengkapan_profil = 2;
                }
                else if ($val == 1) {
                    $str = $tagname ." diverifikasi SUDAH BENAR.";
                    if (!empty($updated['catatan_' .$tag])) {
                        $str .= " Catatan Perbaikan: " .$updated['catatan_' .$tag];
                    }
                    $message[] = $str;
                }
                else if ($val == 2) {
                    $str = $tagname ." diverifikasi BELUM BENAR.";
                    if (!empty($updated['catatan_' .$tag])) {
                        $str .= " Catatan: " .$updated['catatan_' .$tag];
                    }
                    $message[] = $str;
                    $kelengkapan_profil = 2;
                }
                else if ($val == 3) {
                    $str = "Verifikasi " .$tagname ." dieskalasi ke DINAS.";
                    $catatan = "";
                    if (!empty($updated['catatan_' .$tag])) {
                        $catatan = $updated['catatan_' .$tag];
                        $str .= " Catatan: " .$catatan;
                    }
                    $message[] = $str;
                    $kelengkapan_profil = 2;
                    //buat eskalasi dinas
                    $this->Msekolah->tcg_buat_verifikasidinas($peserta_didik_id,$tag,null,$catatan);
                }

                $jml_verifikasi++;
            }

            //simpan catatan walaupun status verifikasi tidak berubah
            foreach($updated as $key => $val) {
                if (substr($key,0,8)!="catatan_") continue;

                $tag = substr($key,8,strlen($key)-8);
                if (!empty($updated['verifikasi_' .$tag])) continue;    //sudah ditambahkan bersama tag verifikasi

                $tagname = "";
                switch($tag) {
                    case "profil": $tagname = "Identitas Siswa"; break;
                    case "lokasi": $tagname = "Lokasi Rumah"; break;
                    case "nilai": $tagname = "Nilai Kelulusan / Nilai Ujian Nasional"; break;
                    case "prestasi": $tagname = "Data Prestasi"; break;
                    case "afirmasi": $tagname = "Data Afirmasi"; break;
                    case "inklusi": $tagname = "Data Kebutuhan Khusus"; break;
                    default: $tag;
                }

                $str = "Catatan " .$tagname. ": " .$updated['catatan_' .$tag];
                $message[] = $str;
            }

        } while (false);

        // var_dump($message);
        // var_dump($jml_verifikasi);

        //VERIFIKASI DOK
        do {
            //oldvalues
            $oldvalues = array();
            $result = $this->Msiswa->tcg_dokumenpendukung($peserta_didik_id);
            if ($result == null) {
                //tidak ada dokumen pendukung
                break;
            }

            //get old values
            foreach ($result as $key => $val) {
                $oldvalues[ $val['daftar_kelengkapan_id'] ] = $val['verifikasi'];
            }

            //only save changed data
            foreach($updateddokumen as $key => $val) {
                if ($val != $oldvalues[$key]) {

                    $nama_dok = $this->Msiswa->tcg_nama_dokumenpendukung($key);

                    if ($val == 0) {
                        $this->Msiswa->tcg_verifikasi_dokumenpendukung($peserta_didik_id,$key,$val,null);

                        //audit trail
                        audit_siswa($peserta_didik_id, "VERIFIKASI DOKUMEN", "Dokumen " .$nama_dok. " tidak ada / belum diterima");

                        //riwayat verifikasi
                        $str = "Dokumen " .$nama_dok. " tidak ada / belum diterima.";
                        $message[] = $str;
                    }
                    else if ($val == 1) {
                        $this->Msiswa->tcg_verifikasi_dokumenpendukung($peserta_didik_id,$key,$val,null);

                        //audit trail
                        audit_siswa($siswa, "VERIFIKASI DOKUMEN", "Verifikasi dokumen " .$nama_dok. " SUDAH BENAR");

                        //riwayat verifikasi
                        $str = "Dokumen " .$nama_dok. " diverifikasi SUDAH BENAR.";
                        $message[] = $str;
                    }
                    else if ($val == 2) {
                        $catatan = !empty( $updateddokumen["catatan_" .$key] ) ? nohtml($updateddokumen["catatan_" .$key]) : '';
                        $this->Msiswa->tcg_verifikasi_dokumenpendukung($peserta_didik_id,$key,$val,$catatan);

                        //audit trail
                        audit_siswa($siswa, "VERIFIKASI DOKUMEN", "Verifikasi dokumen " .$nama_dok. " BELUM BENAR");

                        //riwayat verifikasi
                        $str = "Dokumen " .$nama_dok. " diverifikasi SUDAH BENAR.";
                        if (!empty($catatan)) {
                            $str .= " Catatan: " .$catatan;
                        }
                        $message[] = $str;
                        $kelengkapan_dokumen = 2;
                    }

                    $jml_verifikasi++;
                }
            }
    
        } while (false);

		//update kelengkapan data
        $status_verifikasi = 0;
        $catatan_verifikasi = "";
		if ($jml_verifikasi > 0) {
            //recalc status kelengkapan berkas
            $status_verifikasi = $this->Msiswa->tcg_update_kelengkapanberkas($peserta_didik_id);

            //riwayat verifikasi
            foreach ($message as $val) {
                if ($catatan_verifikasi != "")  $catatan_verifikasi .= "<br>";
                $catatan_verifikasi .= $val;
            }

            $key = $this->Msiswa->tcg_tambah_riwayatverifikasi($peserta_didik_id, $status_verifikasi, $catatan_verifikasi);
            
            //set lokasi berkas ke sekolah ini (perlu konfirmasi secara explisit oleh user?)
            $sekolah_id = $this->session->get('sekolah_id');
            if ($siswa['lokasi_berkas'] != $sekolah_id) {
                $this->Msiswa->tcg_ubah_lokasiberkas($peserta_didik_id, $sekolah_id);

                //audit trail
                $nama_siswa = $siswa['nama'];
                $nama_sekolah = $this->Msekolah->tcg_nama_sekolah($sekolah_id);
                audit_siswa($siswa, "LOKASI BERKAS", "Ubah lokasi berkas an. " .$nama_siswa. " ke " .$nama_sekolah);
            }
        }

        //untouch 
        $this->Msekolah->tcg_touch_verifikasi($peserta_didik_id, 0); 
		
        $json = array();
        // $json['kelengkapan_profil'] = $kelengkapan_profil;
        // $json['kelengkapan_dokumen'] = $kelengkapan_dokumen;
        $json['kelengkapan_berkas'] = $status_verifikasi;
        print_json_output($json);

    }
	
	function riwayat() {
		$peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 

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

	function prestasi() {
		$peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data['data'] = $this->Msiswa->tcg_daftarprestasi($peserta_didik_id); 
			foreach($data['data'] as $key => $valuepair) {
				$data['data'][$key]['file_path'] = base_url(). $valuepair['path'];
				$data['data'][$key]['web_path'] = base_url(). $valuepair['web_path'];
				$data['data'][$key]['thumbnail_path'] = base_url(). $valuepair['thumbnail_path'];
 			}
             print_json_output($data);	
		}
		else {
			print_json_error("not-implemented");
		}
	}

	// function sudahdiverifikasi() {
	// 	$sekolah_id = $_GET["sekolah_id"] ?? null; 
	// 	if (empty($sekolah_id)) {
	// 		$sekolah_id = $this->session->get('sekolah_id');
	// 	}

	// 	$action = $_POST["action"] ?? null;
	// 	if (empty($action) || $action=='view') {
    //         //TODO: tcg_pendaftarsudahdiverifikasi() or tcg_pendaftarsudahlengkap()
	// 		$data['data'] = $this->Msekolah->tcg_pendaftarsudahdiverifikasi($sekolah_id)->getResultArray(); 
	// 		echo json_encode($data);	
	// 	}
	// 	else {
	// 		$data['error'] = "not-implemented";
	// 		echo json_encode($data);	
	// 	}
	// }

	function belumdiverifikasi() {
		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data = $this->Msekolah->tcg_pendaftarbelumdiverifikasi($sekolah_id); 
            if ($data == null) {
                $data = array();
            }
			print_json_output($data, 1);	
		}
		else {
			print_json_error("not-implemented");
		}
	}

	function belumlengkap() {
		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data = $this->Msekolah->tcg_pendaftarbelumlengkap($sekolah_id); 
            if ($data == null) {
                $data = array();
            }
			print_json_output($data, 1);	
		}
		else {
			print_json_error("not-implemented");
		}
	}

	function sudahlengkap() {
		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data = $this->Msekolah->tcg_pendaftarsudahlengkap($sekolah_id); 
            if ($data == null) {
                $data = array();
            }
			print_json_output($data, 1);	
		}
		else {
			print_json_error("not-implemented");
		}
	}

	function berkasdisekolah() {
		$sekolah_id = $_GET["sekolah_id"] ?? null; 
		if (empty($sekolah_id)) {
			$sekolah_id = $this->session->get('sekolah_id');
		}

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$data = $this->Msekolah->tcg_berkasdisekolah($sekolah_id); 
            if ($data == null) {
                $data = array();
            }
			print_json_output($data, 1);	
		}
		else {
			print_json_error("not-implemented");
		}
	}

	function touch() {
		$pengguna_id = $this->session->get('user_id');

		$peserta_didik_id = $_POST["peserta_didik_id"] ?? null; 
		$aktif = $_POST["aktif"] ?? null; 

		$data = array();

		$data['status'] = $this->Msekolah->tcg_touch_verifikasi($pengguna_id, $peserta_didik_id, $aktif); 
		echo json_encode($data);	
	}

    function cabutberkas() {
        $peserta_didik_id = $this->request->getGetPost("peserta_didik_id");
        $keterangan = $this->request->getGetPost("keterangan");
        $this->Msekolah->tcg_cabutberkas($peserta_didik_id, $keterangan);
        $data['status'] = 1;
        echo json_encode($data);        
    }
}

?>