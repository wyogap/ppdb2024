<?php

namespace App\Controllers;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Mhome;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use App\Models\Ppdb\Siswa\Mprofilsiswa;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

require_once ROOTPATH .'vendor/autoload.php';

class Home extends PpdbController
{
    protected static $AUTHENTICATED = false;

    protected $Mhome;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        if ($this->method == 'peringkat' || $this->method == 'detailpendaftaran' || $this->method == 'raportmutu') {
            $this->is_json = false;
        }

        //load model
        $this->Mhome = new Mhome();
    }

	function index()
	{	
        return redirect()->to(site_url());
	}

	function detailpendaftaran()
	{
		$peserta_didik_id = $_GET["peserta_didik_id"] ?? null; 

        $upload_dokumen = $this->setting->get('upload_dokumen');

        $msiswa = new Mprofilsiswa();
		$profil = $msiswa->tcg_profilsiswa($peserta_didik_id);
        
		$pendaftaran = $msiswa->tcg_daftarpendaftaran($peserta_didik_id);
        $pendaftaran = update_daftarpendaftaran($pendaftaran);

        //var_dump($pendaftaran); exit;

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

        $data['profilsiswa'] = $profil; 
        $data['daftarpendaftaran'] = $pendaftaran;
        $data['kelengkapan_data'] = $kelengkapan_data;

        //allow edit?
        $data['is_public'] = 1;
        $data['use_datatable'] = 1;

        // //content template
        $data['content_template'] = '../siswa/daftarpendaftaran.tpl';
        $data['js_template'] = '../siswa/_daftarpendaftaran.tpl';

		$data['page'] = 'detailpendaftaran';
 		$data['page_title'] = 'Detail Pendaftaran';
 
        $this->smarty->render('ppdb/home/ppdbhome.tpl', $data);	
	}

	function peringkat() {
		$sekolah_id = $this->request->getPostGet("sekolah_id");
        if (empty($sekolah_id)) {
            return redirect()->to(site_url() ."home/rekapitulasi");
        }

        $msekolah = new \App\Models\Ppdb\Sekolah\Mprofilsekolah();
        $data['profilsekolah'] = $msekolah->tcg_profilsekolah($sekolah_id);
        if(empty($data['profilsekolah'])) {
            return redirect()->to(site_url() ."home/rekapitulasi");
        }

		$data['daftarpenerapan'] = $msekolah->tcg_daftarpenerapan($sekolah_id);
        
        //pendaftar per penerapan
        $pendaftar = array();
        foreach($data['daftarpenerapan'] as $p) {
            $penerapan_id = $p['penerapan_id'];
            $penerapan = $msekolah->tcg_pendaftaran_penerapanid($sekolah_id, $penerapan_id);
            for($i=0; $i<count($penerapan); $i++) {
                $penerapan[$i]['idx'] = $i+1;
                //mask nisn
                $penerapan[$i]['nisn'] = substr($penerapan[$i]['nisn'],0,6) .str_repeat("*", 4);
                $penerapan[$i]['skor'] = round($penerapan[$i]['skor'],2);
            }
            //var_dump($penerapan);
            $pendaftar[$penerapan_id] = $penerapan;
        }
        $data['pendaftar'] = $pendaftar;
        //exit;

        //semua pendaftar
        $data['show_all_pendaftar'] = 1;
		$semuapendaftar = $msekolah->tcg_daftarpendaftaran($sekolah_id);
        for($i=0; $i<count($semuapendaftar); $i++) {
            $semuapendaftar[$i]['idx'] = $i+1;
            //mask nisn
            $semuapendaftar[$i]['nisn'] = substr($semuapendaftar[$i]['nisn'],0,6) .str_repeat("*", 4);
        }
        $data['semuapendaftar'] = $semuapendaftar;

		$data['action'] = 'peringkat';
		$data['sekolah_id'] = $sekolah_id;
		
		//terakhir kali peringkat diproses
		$data['last_execution_date'] = '';
		$data['next_execution_date'] = '';
        $mhome = new Mhome();
		$job = $mhome->tcg_job_peringkatpendaftaran();
		if ($job != null) {
			$data['last_execution_date'] = $job['last_execution_end'];
			$data['next_execution_date'] = $job['next_execution'];
        }
		
		//nggak perlu tampilin profil sekolah untuk admin sekolah
		if ($this->is_sekolah && $sekolah_id == $this->session->get('sekolah_id'))
			$data['show_profile_sekolah'] = 0;
		else
			$data['show_profile_sekolah'] = 1;

		$data['cek_waktupendaftaran'] = $this->Mconfig->tcg_cek_waktupendaftaran();
        if ($data['cek_waktupendaftaran'] == 2) {
            $data['final_ranking'] = 1;
        }
        else {
            $data['final_ranking'] = 0;
        }

		$data['inklusi']=0;
        if ($data['profilsekolah'] != null) {
            $data['inklusi']=$data['profilsekolah']['inklusi'];
        }

        $data['profilsekolah'] = $msekolah->tcg_profilsekolah($sekolah_id);

        $data['use_datatable'] = 1;
        $data['is_public'] = 1;

        //content template
        $data['content_template'] = '../sekolah/peringkat.tpl';
        $data['js_template'] = '../sekolah/_peringkat.tpl';

		$data['page'] = 'peringkat';
		$data['page_title'] = 'Peringkat';
 
        $this->smarty->render('ppdb/home/ppdbhome.tpl', $data);	
    }

	function rapormutu() {
        $json = $this->request->getPostGet('json'); 
        if (!empty($json)) {
            $this->is_json=1;
            return $this->rapormutu_json();
        }

        $data['use_datatable'] = 1;

        //content template
        $data['content_template'] = './rapormutu.tpl';
        $data['js_template'] = './_rapormutu.tpl';

		$data['page'] = 'peringkat';
		$data['page_title'] = 'Peringkat';
 
        $this->smarty->render('ppdb/home/ppdbhome.tpl', $data);	

        // $data['use_datatable'] = 1;

		// $data['page'] = "rapormutu";
		// $data['page_title'] = "Rapor Mutu";
		// $this->smarty->render('ppdb/home/rapormutu.tpl',$data);

	}

	protected function rapormutu_json() {
		$action = $_POST["action"] ?? null; 
		if (empty($action) || $action=='view') {
			$data = $this->Mhome->tcg_rapor_mutu(); 
			print_json_output($data);	
		}
	}    

    function cekdapodik() {
        $nisn = $this->request->getPostGet('nisn');
        $npsn = $this->request->getPostGet('npsn');

        $jumlah = $this->Mhome->tcg_cek_nisn($nisn);
        if($jumlah>0){
            print_json_error("Data akun siswa dengan nisn tersebut sudah ada.", -90);
        }

        helper("dom");
      
        $url = 'https://pelayanan.data.kemdikbud.go.id/vci/index.php/CPelayananData/getSiswa?kode_wilayah=030500&token=16F236D8-1153-4B69-B9EF-CC99FEDE2D65&nisn=' .$nisn. '&npsn=' .$npsn;

        $client = new \GuzzleHttp\Client(['verify' => false ]);
        $req = $client->request('GET', $url);
        $resp = $req->getBody();

        if ($resp == null) {
            print_json_error("Tidak berhasil mendapatkan data dapodik.");
        }

        $profil = json_decode($resp);
        $profil = (array) $profil[0];

        $sekolah = $this->Mhome->tcg_profilsekolah_from_npsn($npsn);

        if (empty($sekolah)) {
            $url = "https://referensi.data.kemdikbud.go.id/tabs.php?npsn=" .$npsn;

            $retry = 0;
            $arr = array();
            do {
                $client = new \GuzzleHttp\Client(['verify' => false ]);
                $req = $client->request('GET', $url, ['http_errors' => false]);
                $status_code = $req->getStatusCode();
                $retry++;
    
                if ($status_code != 200) continue;
    
                $resp = (string) $req->getBody();
                $html = str_get_html($resp);
    
                $tab = $html->find('.tabby-tab')[0];
                $tr = $tab->find("tr");
                foreach($tr as $row) {
                    $td = $row->find("td");
                    $field = $value = '';
                    foreach($td as $k => $col) {
                        if ($k == 1) $field = trim($col->innertext);
                        if ($k == 3) $value = trim($col->innertext);
                    }
                    if (empty($field))  continue;
                    $arr[$field] = $value;
                }
            } 
            while ($status_code != 200 && $retry < 3);
    
            $sekolah = array();
            $sekolah['npsn'] = $npsn;
            $sekolah['dapodik_id'] = $profil['sekolah_id'];
            $sekolah['nama'] = $arr['Nama'];
            $sekolah['alamat'] = $arr['Alamat'];
            $sekolah['nama_desa'] = $arr['Desa/Kelurahan'];
            $sekolah['nama_kec'] = $arr['Kecamatan/Kota (LN)'];
            $sekolah['nama_kab'] = $arr['Kab.-Kota/Negara (LN)'];
            $sekolah['nama_prov'] = trim(str_replace("PROV.", "", $arr['Propinsi/Luar Negeri (LN)']));
            $sekolah['bentuk'] = $arr['Bentuk Pendidikan'];
            $sekolah['status'] = substr($arr['Status Sekolah'],0,1);
            $sekolah['kode_wilayah'] = $this->Mhome->tcg_kode_wilayah($sekolah['nama_prov'], $sekolah['nama_kab'], $sekolah['nama_kec'], $sekolah['nama_desa']);
   
            //buat sekolah baru
            $sekolah['sekolah_id'] = $this->Mhome->tcg_sekolah_baru($sekolah['nama'],$sekolah['kode_wilayah'],$sekolah['bentuk'],$npsn,$sekolah['status']);
        }

        $profil['nama_sekolah'] = $sekolah['nama'];
        $profil['sekolah_dapodik_id'] = $sekolah['dapodik_id'];
        $profil['sekolah_id'] = $sekolah['sekolah_id'];
        $profil['npsn_sekolah'] = $sekolah['npsn'];
    
        print_json_output($profil);
    }

    function registrasi() {
		$mdropdown = new \App\Models\Ppdb\Mconfig();
		$data['cek_registrasi'] = $this->Mconfig->tcg_cek_wakturegistrasi();
		$data['cek_sosialisasi'] = $this->Mconfig->tcg_cek_waktusosialisasi();
        $data['kabupaten'] = $mdropdown->tcg_kabupaten();

        $batasanusia = $this->Mconfig->tcg_batasanusia("SMP");
        if (!empty($batasanusia)) {
            $data['maxtgllahir'] = $batasanusia['maksimal_tanggal_lahir'];
            $data['mintgllahir'] = $batasanusia['minimal_tanggal_lahir'];
        }

        $data['use_leaflet'] = 1;
        $data['use_select2'] = 1;

        //content template
        $data['content_template'] = './registrasi.tpl';
        $data['js_template'] = './_registrasi.tpl';

		$data['page'] = 'registrasi';
		$data['page_title'] = 'Registrasi Siswa Luar Daerah';
 
        $this->smarty->render('ppdb/home/ppdbhome.tpl', $data);	

        // $data["page_title"] = "Registrasi";
		// $this->smarty->render('ppdb/home/registrasi.tpl',$data);
    }

	function doregistrasi() {   
        $data = array();
		$data['kode_kabupaten_sekolah'] = $_POST["kode_kabupaten_sekolah"] ?? "";
		$data['sekolah_id'] = $_POST["sekolah_id"] ?? "";
		$data['bentuk_sekolah'] = $_POST["bentuk"] ?? "";
		$data['nama_sekolah'] = $_POST["nama_sekolah"] ?? "";
		$data['npsn_sekolah'] = $_POST["npsn_sekolah"] ?? "";

		$data['nik'] = $_POST["nik"] ?? "";
		$data['nisn'] = $_POST["nisn"] ?? "";
		$data['nomor_ujian'] = $_POST["nomor_ujian"] ?? "";
		$data['nama'] = $_POST["nama"] ?? "";
		$data['jenis_kelamin'] = $_POST["jenis_kelamin"] ?? "";
		$data['tempat_lahir'] = $_POST["tempat_lahir"] ?? "";
		$data['tanggal_lahir'] = $_POST["tanggal_lahir"] ?? "";
		$data['nama_ibu_kandung'] = $_POST["nama_ibu_kandung"] ?? "";
		$data['kebutuhan_khusus'] = $_POST["kebutuhan_khusus"] ?? "";
		$data['alamat'] = $_POST["alamat"] ?? "";
		$data['kode_kabupaten'] = $_POST["kode_kabupaten"] ?? "";
		$data['kode_kecamatan'] = $_POST["kode_kecamatan"] ?? "";
		$data['kode_desa'] = $_POST["kode_desa"] ?? "";
		$data['kode_wilayah'] = $_POST["kode_wilayah"] ?? "";
		if(empty($data['kode_wilayah'])){
			$data['kode_wilayah'] = $data['kode_desa'];
		}
		$data['lintang'] = $_POST["lintang"] ?? "";
        $data['bujur'] = $_POST["bujur"] ?? "";

		$data['nomor_kontak'] = $_POST["nomor_kontak"] ?? "";

		//load the entered data again
        do {
            $jumlah = $this->Mhome->tcg_cek_registrasi($data['nama'], $data['jenis_kelamin'], $data['tempat_lahir'], $data['tanggal_lahir'], $data['nama_ibu_kandung']);
			if($jumlah>0){
                $this->session->setFlashdata('error', "Data siswa tersebut sudah ada sebelumnya, silahkan koordinasi dengan Sekolah Tujuan atau Sekolah Tempat Registrasi Akun untuk pengelolaan akun siswa dengan membawa berkas pendukung.");
                break;
			}
		   
            $jumlah = $this->Mhome->tcg_cek_nik($data['nik']);
			if($jumlah>0){
                $this->session->setFlashdata('error', "Nomor NIK invalid / sama dengan siswa lain.");
                break;
			}

            $jumlah = $this->Mhome->tcg_cek_nisn($data['nisn']);
			if($jumlah>0){
                $this->session->setFlashdata('error', "Nomor NISN invalid / sama dengan siswa lain.");
                break;
			}

            //TODO: check for existing sekolah with the same npsn
            $sekolah = $this->Mhome->tcg_profilsekolah_from_npsn($data['npsn_sekolah']);

			if (empty($sekolah)) {
				$npsn_sekolah = empty($data['npsn_sekolah']) ? "00000000" : $data['npsn_sekolah'];
				$status_sekolah = 'S';
				//create sekolah id first
				$data['sekolah_id'] = $this->Mhome->tcg_sekolah_baru($data['nama_sekolah'],$data['kode_kabupaten_sekolah'],$data['bentuk_sekolah'],$npsn_sekolah,$status_sekolah);
				if ($data['sekolah_id'] == "") {
                    $this->session->setFlashdata('error', "Tidak berhasil menambahkan data sekolah.");
					break;
				}
			}
            else {
                $data['sekolah_id'] = $sekolah['sekolah_id'];
            }

            $user = $this->Mhome->tcg_registrasiuser($data['sekolah_id'], $data['nik'], $data['nisn'], $data['nomor_ujian'], $data['nama'], $data['jenis_kelamin'], 
                                                                    $data['tempat_lahir'], $data['tanggal_lahir'], $data['nama_ibu_kandung'], $data['kebutuhan_khusus'], 
                                                                    $data['alamat'], $data['kode_wilayah'], $data['lintang'], $data['bujur'], $data['nomor_kontak']);
            if ($user == null) {						
                $this->session->setFlashdata('error', "Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.");
                break;
            }

            //audit trail
            $data['peserta_didik_id'] = $user['peserta_didik_id'];
            $data['user_id'] = $user['user_id'];
            audit_siswa($user, "REGISTRASI", "Registrasi siswa an. " .$user['nama_pengguna'], array_keys($data), $data);

            //pesan
            $data['info'] = "<div class='alert alert-secondary' role='alert'>Registrasi berhasil. Silahkan tunggu pemberitahuan persetujuan akun melalui nomor kontak yang anda berikan. Apabila setelah 1x24 jam anda belum menerima pemberitahuan persetujuan, silahkan menghubungi nomor bantuan yang ada di halaman utama.</div>
            <div class='alert alert-secondary' role='alert'>Anda bisa melakukan masuk ke sistem PPDB Online menggunakan nomor NISN anda. </div>
            <div class='alert alert-secondary' role='alert'>Segera masuk dan ganti PIN anda sekarang juga. <br>Gunakan akun berikut untuk masuk ke sistem: <br><ul><li>Nama Pengguna: " .$data['nisn']. "</li><li>PIN: " .$data['nisn']. "</li></ol></div>";

            $data["sukses"] = 1;

        } while (false);

		$mdropdown = new \App\Models\Ppdb\Mconfig();
		$data['cek_registrasi'] = $this->Mconfig->tcg_cek_wakturegistrasi();
		$data['cek_sosialisasi'] = $this->Mconfig->tcg_cek_waktusosialisasi();
        $data['kabupaten'] = $mdropdown->tcg_kabupaten();

        $batasanusia = $this->Mconfig->tcg_batasanusia("SMP");
        if (!empty($batasanusia)) {
            $data['maxtgllahir'] = $batasanusia['maksimal_tanggal_lahir'];
            $data['mintgllahir'] = $batasanusia['minimal_tanggal_lahir'];
        }

        $data['use_leaflet'] = 1;
        $data['use_select2'] = 1;

        //content template
        $data['content_template'] = './registrasi.tpl';
        $data['js_template'] = './_registrasi.tpl';

		$data['page'] = 'registrasi';
		$data['page_title'] = 'Registrasi Siswa Luar Daerah';
 
        $this->smarty->render('ppdb/home/ppdbhome.tpl', $data);	

        // $data["page_title"] = "Registrasi";
		// $this->smarty->render('ppdb/home/registrasi.tpl',$data);
	}

    function rekapitulasi(){
		$data['daftarsekolah'] = $this->Mhome->tcg_rekapitulasi_sekolah();
			
        $data['use_datatable'] = 1;

        //content template
        $data['content_template'] = './rekapitulasi.tpl';
        $data['js_template'] = './_rekapitulasi.tpl';

		$data['page'] = 'peringkat';
		$data['page_title'] = 'Peringkat';
 
        $this->smarty->render('ppdb/home/ppdbhome.tpl', $data);	
	}

	function ddsekolah()
	{
        $kode_wilayah = $this->request->getPostGet("kode_wilayah"); 
        $bentuk = $this->request->getPostGet("bentuk"); 
		$mdropdown = new \App\Models\Ppdb\Mconfig();
		$data['sekolah'] = $mdropdown->tcg_sekolah($kode_wilayah, $bentuk);
        //var_dump($data['sekolah']); exit;
		return view('ppdb/home/_dropdownsekolah',$data);
	}

	function ddkecamatan()
	{
        $kode_wilayah = $this->request->getPostGet("kode_wilayah"); 
		$mdropdown = new \App\Models\Ppdb\Mconfig();
		$data['kecamatan'] = $mdropdown->tcg_kecamatan($kode_wilayah);
		return view('ppdb/home/_dropdownkecamatan',$data);
	}

	function dddesa()
	{
        $kode_wilayah = $this->request->getPostGet("kode_wilayah"); 
		$mdropdown = new \App\Models\Ppdb\Mconfig();
		$data['desa'] = $mdropdown->tcg_desa($kode_wilayah);
		return view('ppdb/home/_dropdowndesa',$data);
	}

	function ddpadukuhan()
	{
        $kode_wilayah = $this->request->getPostGet("kode_wilayah"); 
		$mdropdown = new \App\Models\Ppdb\Mconfig();
		$data['padukuhan'] = $mdropdown->tcg_padukuhan($kode_wilayah);
		return view('ppdb/home/_dropdownpadukuhan',$data);
	}

}
