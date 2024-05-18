<?php
namespace App\Controllers\Ppdb\Sekolah;

use App\Controllers\Ppdb\PpdbController;
use App\Models\Ppdb\Sekolah\Mprofilsekolah;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Beranda extends PpdbController {
    protected $Msekolah;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //load library
        //$this->smarty = new SmartyLibrary();

        //load model
        $this->Msekolah = new Mprofilsekolah();
        
        //TODO: disable first for testing
        // if($this->session->get('is_logged_in')==FALSE || $this->session->get('peran_id')!=ROLEID_SISWA) {
		// 	redirect(site_url() .'auth');
		// }

    }

	function index()
	{
		$sekolah_id = $this->session->get("sekolah_id");
        
        //notifikasi tahapan
        $data['tahapan_aktif'] = $this->Msetting->tcg_tahapan_pelaksanaan_aktif()->getResultArray();
        $data['pengumuman'] = $this->Msetting->tcg_pengumuman()->getResult();

        $data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
		// $data['daftarkuota'] = $this->Msekolah->tcg_daftarkuota();

        //content template
        $data['content_template'] = 'beranda.tpl';

		$data['page_title'] = 'Beranda';
        $this->smarty->render('ppdb/sekolah/ppdbsekolah.tpl', $data);
	}

	function dashboard()
	{
		$sekolah_id = $this->session->get("sekolah_id");

		$data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
		$data['daftarpenerapan'] = $this->Msekolah->tcg_daftar_penerapan($sekolah_id);

		$data['daftarkuota'] = $this->Msekolah->daftarkuota();
		$data['dashboardsekolah'] = $this->Msekolah->dashboardsekolah();
		$data['dashboardpenerapan'] = $this->Msekolah->dashboardpenerapan();
		$data['dashboardline'] = $this->Msekolah->dashboardline();
		$data['daftarpendaftar'] = $this->Msekolah->dashboardpendaftar();
		
		return view('ppdb/sekolah/dashboard/index',$data);
	}

	// function peringkat()
	// {
	// 	$this->load->model('Msekolah');
	// 	$data['dashboardpenerapan'] = $this->Msekolah->dashboardpenerapan();
	// 	$data['daftarpendaftar'] = $this->Msekolah->dashboardpendaftar();
	// 	$this->load->view('ppdb/sekolah/peringkat/index',$data);
	// }

	// function verifikasiberkas()
	// {
	// 	$sekolah_id = $this->session->userdata("sekolah_id");

	// 	//return redirect()->to("Cinfo");
	// 	$this->load->model(array('Msekolah','Msetting'));
	// 	$data['waktuverifikasi'] = $this->Msetting->tcg_cek_waktuverifikasi();
	// 	$data['belum'] = $this->Msekolah->tcg_pendaftarbelumdiverifikasi($sekolah_id);
	// 	$data['sudah'] = $this->Msekolah->tcg_pendaftarsudahdiverifikasi($sekolah_id);
	// 	$this->load->view('ppdb/sekolah/verifikasiberkas/index',$data);
	// }
	
	// function perubahandatasiswa()
	// {
	// 	$this->load->model('Msekolah');
	// 	$data['daftarpendaftar'] = $this->Msekolah->daftarpendaftar();
	// 	$this->load->view('ppdb/sekolah/perubahandatasiswa/index',$data);
	// }

	// function detailperubahandatasiswa()
	// {
	// 	$peserta_didik_id = $this->input->get("peserta_didik_id");
	// 	$pendaftaran_id = $this->input->get("pendaftaran_id");
	// 	$sekolah_id = $this->session->userdata("sekolah_id");

	// 	$this->load->model(array('Msekolah','Msiswa','Mdropdown'));
	// 	$data['kabupaten'] = $this->Mdropdown->tcg_kabupaten();
	// 	$data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);

		
	// 	$this->load->view('ppdb/sekolah/perubahandatasiswa/detail/index',$data);
	// }

	// function prosesperubahandatasiswa()
	// {
	// 	$this->load->model('Msekolah');

	// 	if ($this->input->post("kode_kabupaten") == $this->input->post("kode_kabupaten_lama")
	// 		&& $this->input->post("kode_kecamatan") == $this->input->post("kode_kecamatan_lama")
	// 		&& $this->input->post("kode_desa") == $this->input->post("kode_desa_lama")
	// 		&& $this->input->post("tanggal_lahir") == $this->input->post("tanggal_lahir_lama")
	// 		&& $this->input->post("lintang") == $this->input->post("lintang_lama")
	// 		&& $this->input->post("bujur") == $this->input->post("bujur_lama")
	// 	) {
	// 		$data['info'] = "<div class='alert alert-info alert-dismissable'>Tidak ada perubahan data.</div>";
	// 	}
	// 	else {
	// 		if($this->Msekolah->perubahandatasiswa()){
	// 			// $data['info'] = "<div class='alert alert-info alert-dismissable'>Data perubahan telah berhasil disimpan. Silahkan tunggu persetujuan dari Dinas.</div>";
	// 			$data['info'] = "<div class='alert alert-info alert-dismissable'>Data perubahan telah berhasil disimpan.</div>";
	// 		}else{
	// 			$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.</div>";
	// 		}
	// 	}

	// 	$data['daftarpendaftar'] = $this->Msekolah->daftarpendaftar();
	// 	$this->load->view('ppdb/sekolah/perubahandatasiswa/index',$data);
	// }
		
	// function cabutberkas()
	// {
	// 	//$this->load->model('Msekolah');
	// 	$this->load->model(array('Msekolah','Msetting'));
	// 	$data['settingpendaftaran'] = $this->Msetting->tcg_waktupendaftaran();
	// 	$data['daftarpendaftar'] = $this->Msekolah->daftarpendaftar();
	// 	$data['daftarpendaftarcabutberkas'] = $this->Msekolah->daftarpendaftarcabutberkas();
	// 	$this->load->view('ppdb/sekolah/cabutberkas/index',$data);
	// }

	// function detailcabutberkas()
	// {
	// 	$peserta_didik_id = $this->input->get("peserta_didik_id");
	// 	$pendaftaran_id = $this->input->get("pendaftaran_id");
	// 	$sekolah_id = $this->session->userdata("sekolah_id");

	// 	$this->load->model(array('Msekolah','Mdropdown','Msetting'));
	// 	$data['settingpendaftaran'] = $this->Msetting->tcg_waktupendaftaran();
	// 	$data['referensibatasanperubahan'] = $this->Msetting->tcg_batasanperubahan();
	// 	$data['profilsiswa'] = $this->Msekolah->profilpendaftaran($sekolah_id, $pendaftaran_id);
	// 	$data['detailpendaftarpilihansatu'] = $this->Msekolah->detailpendaftarpilihansatu();
		
	// 	$this->load->view('ppdb/sekolah/cabutberkas/detail/index',$data);
	// }

	// function prosescabutberkas()
	// {
	// 	$this->load->model(array('Msekolah','Msetting'));
	// 	if($this->Msekolah->cabutberkas()){
	// 		$data['info'] = "<div class='alert alert-info alert-dismissable'>Pendaftaran telah berhasil dilakukan cabut berkas.</div>";
	// 	}else{
	// 		$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.</div>";
	// 	}
	// 	$data['referensibatasanperubahan'] = $this->Msetting->tcg_batasanperubahan();
	// 	$data['daftarpendaftar'] = $this->Msekolah->daftarpendaftar();
	// 	$data['daftarpendaftarcabutberkas'] = $this->Msekolah->daftarpendaftarcabutberkas();
	// 	$this->load->view('ppdb/sekolah/cabutberkas/index',$data);
	// }

	// function tandabuktiverifikasi() {
	// 	$peserta_didik_id = $this->input->get("peserta_didik_id");
	// 	$pendaftaran_id = $this->input->get("pendaftaran_id");

	// 	$pengguna_id = $this->session->userdata("pengguna_id");
	// 	$username = $this->session->userdata("username");
	// 	$peran_id = $this->session->userdata("peran_id");
	// 	$sekolah_id = $this->session->userdata("sekolah_id");

	// 	$this->load->model(array('Msiswa','Msekolah'));

	// 	$this->load->library('Ciqrcode');
	// 	$config['cacheable'] = true; //boolean, the default is true
    //     $config['cachedir'] = './qrcode/'; //string, the default is application/cache/
    //     $config['errorlog'] = './qrcode/'; //string, the default is application/logs/
    //     $config['imagedir'] = './qrcode/images/'; //direktori penyimpanan qr code
    //     $config['quality'] = true; //boolean, the default is true
    //     $config['size'] = '1024'; //interger, the default is 1024
    //     $config['black'] = array(224,255,255); // array, default is array(255,255,255)
    //     $config['white'] = array(70,130,180); // array, default is array(0,0,0)
    //     $this->ciqrcode->initialize($config);
    //     $image_name = $pengguna_id.'.png'; //buat name dari qr code sesuai dengan nim
 
    //     $params['data'] = $pengguna_id.",".$username.",".$peran_id; //data yang akan di jadikan QR CODE
    //     $params['level'] = 'M'; //H=High
    //     $params['size'] = 10;
    //     $params['savename'] = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/
    //     $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
		

	// 	$data['daftarberkasverifikasi'] = $this->Msekolah->daftarberkasverifikasi();
	// 	$data['waktuberkasverifikasi'] = $this->Msekolah->waktuberkasverifikasi();
	// 	$data['profilsiswa'] = $this->Msekolah->profilpendaftaran($sekolah_id, $pendaftaran_id);
		
	// 	$data['daftarpendaftaran'] = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id);
		
	// 	$this->load->view('ppdb/sekolah/verifikasiberkas/detail/tandabuktiverifikasi',$data);

    //     $html = $this->output->get_output();
    //     $this->load->library('Pdf');
	// 	$dompdf = new Pdf(array('enable_remote' => true));
    //     $this->dompdf->loadHtml($html);
    //     $this->dompdf->setPaper('A4', 'portrait');
    //     $this->dompdf->render();
    //     $this->dompdf->stream("TandaBuktiVerifikasi.pdf", array("Attachment"=>0));
	// }

	// function gantiprestasi()
	// {
	// 	$this->load->model('Msekolah');
	// 	$data['daftar'] = $this->Msekolah->daftarsiswagantiprestasi();
	// 	$this->load->view('ppdb/sekolah/gantiprestasi/index',$data);
	// }

	// function detailgantiprestasi()
	// {
	// 	$penerapan_id = $this->input->post("penerapan_id", TRUE);
	// 	if($penerapan_id==""){
	// 		$penerapan_id = $this->input->get("penerapan_id", TRUE);
	// 	}

	// 	$this->load->model(array('Msekolah','Mdropdown'));
	// 	$data['daftarnilaiskoringprestasi'] = $this->Mdropdown->tcg_skoringprestasi($penerapan_id);
	// 	$data['detailnilaiprestasi'] = $this->Msekolah->detailnilaiprestasi();
	// 	$data['profilsiswa'] = $this->Msekolah->detailgantiprestasi();
	// 	$this->load->view('ppdb/sekolah/gantiprestasi/detailpencarian/index',$data);
	// }

	// function prosesubahprestasi()
	// {
	// 	$this->load->model('Msekolah');
	// 	if($this->Msekolah->ubahprestasi()){
	// 		$data['info'] = "<div class='alert alert-info alert-dismissable'>Prestasi telah berhasil diubah.</div>";
	// 	}else{
	// 		$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.</div>";
	// 	}
	// 	$data['daftar'] = $this->Msekolah->daftarsiswagantiprestasi();
	// 	$this->load->view('ppdb/sekolah/gantiprestasi/index',$data);
	// }

	// function pengajuanakun()
	// {
	// 	$this->load->view('ppdb/sekolah/pengajuanakun/index');
	// }

	// function caripengajuanakun()
	// {
	// 	$this->load->model(array('Msekolah'));
	// 	$data['daftar'] = $this->Msekolah->caripengajuanakun()->result_array();
	// 	if ($data['daftar'] == null) {
	// 		$data['daftar'] = array();
	// 	}
		
	// 	$this->load->view('ppdb/sekolah/pengajuanakun/daftarpencarian',$data);
	// }

	// function tarikdata()
	// {
	// 	$this->load->view('ppdb/sekolah/tarikdata/index');
	// }

	// function carisiswatarikdata()
	// {
	// 	$this->load->model(array('Msekolah'));
	// 	$data['daftar'] = $this->Msekolah->caritarikdata();
	// 	$this->load->view('ppdb/sekolah/tarikdata/daftarpencarian',$data);
	// }

	// function detailtarikdata()
	// {
	// 	$this->load->model(array('Msekolah','Mdropdown'));
	// 	$data['kabupaten'] = $this->Mdropdown->tcg_kabupaten();
	// 	$data['profilsiswa'] = $this->Msekolah->detailtarikdata();
	// 	$this->load->view('ppdb/sekolah/tarikdata/detailpencarian/index',$data);
	// }

	// function prosestarikdata()
	// {
	// 	$this->load->model('Msekolah');
	// 	if($this->Msekolah->tarikdata()){
	// 		if($this->Msekolah->buatakuntarikdata()){

	// 			$username = "";
	// 			$detailusername = $this->Msekolah->detailusername();
	// 			foreach($detailusername->result() as $row):
	// 				$username = $row->username;
	// 			endforeach;

	// 			$data['info'] = "<div class='alert alert-info alert-dismissable'>Persetujuan akun telah berhasil disimpan dengan rincian :<br><b>Username</b> : ".$username."<br><b>Password</b> : ".$username."</div>";
	// 		}else{
	// 			$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.</div>";
	// 		}
	// 	}else{
	// 		$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.</div>";
	// 	}
	// 	$this->load->view('ppdb/sekolah/tarikdata/index',$data);
	// }
	
	// function pencarian()
	// {
	// 	$this->load->view('ppdb/sekolah/pencarian/index');
	// }

	// function caripendaftar()
	// {
	// 	$this->load->model(array('Msekolah'));
	// 	$data['daftar'] = $this->Msekolah->carisiswa();
	// 	$this->load->view('ppdb/sekolah/pencarian/daftarpencarian',$data);
	// }

	// function detailpencarian()
	// {
	// 	$peserta_didik_id = $this->input->get("peserta_didik_id");

	// 	$this->load->model(array('Msekolah','Msiswa','Mdropdown'));
	// 	$data['kabupaten'] = $this->Mdropdown->tcg_kabupaten();
	// 	$data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);
	// 	$this->load->view('ppdb/sekolah/pencarian/detailpencarian/index',$data);
	// }

	// function prosesperubahandata()
	// {
	// 	$this->load->model('Msekolah');
	// 	if($this->Msekolah->perubahandatasiswa()){
	// 		//Cek Ulang Perubahan
	// 		$this->Msekolah->perubahandatasiswa();
	// 		$data['info'] = "<div class='alert alert-info alert-dismissable'>Perubahan data telah berhasil disimpan.</div>";
	// 	}else{
	// 		$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.</div>";
	// 	}
	// 	$this->load->view('ppdb/sekolah/pencarian/index',$data);
	// }

	// function undurdiri()
	// {
	// 	$this->load->model('Msekolah');
	// 	$data['daftarpendaftar'] = $this->Msekolah->daftarpendaftar();
	// 	$this->load->view('ppdb/sekolah/undurdiri/index',$data);
	// }

	// function detailundurdiri()
	// {
	// 	$peserta_didik_id = $this->input->get("peserta_didik_id");
	// 	$pendaftaran_id = $this->input->get("pendaftaran_id");

	// 	$this->load->model(array('Msekolah','Mdropdown'));
	// 	$data['profilsiswa'] = $this->Msekolah->profilpendaftaran($sekolah_id, $pendaftaran_id);
	// 	$data['detailpendaftar'] = $this->Msekolah->detailpendaftar();
		
	// 	$this->load->view('ppdb/sekolah/undurdiri/detail/index',$data);
	// }

	// function prosesundurdiri()
	// {
	// 	$this->load->model('Msekolah');
	// 	if($this->Msekolah->undurdiri()){
	// 		$data['info'] = "<div class='alert alert-info alert-dismissable'>Pendaftaran telah berhasil dihapus.</div>";
	// 	}else{
	// 		$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.</div>";
	// 	}
	// 	$data['daftarpendaftar'] = $this->Msekolah->daftarpendaftar();
	// 	$this->load->view('ppdb/sekolah/undurdiri/index',$data);
	// }

	// function daftarulang()
	// {
	// 	return redirect()->to("sekolah/daftarulang");
	// }

	// function prosesdaftarulang()
	// {
	// 	$sekolah_id = $this->session->userdata("sekolah_id");

	// 	$this->load->model(array('Msekolah', 'Msetting'));

	// 	$this->Msekolah->resetdaftarulang();
	// 	$this->Msekolah->resetdaftarulangrpt();
	// 	foreach ($_POST as $key => $value)
	// 	{
	// 		if(substr($key,0,4)=="pdid"){
	// 			$pendaftaran_id = str_replace("pdid_","",$key);
	// 			if($this->Msekolah->daftarulangcheck($pendaftaran_id,$value))
	// 			{
	// 				$this->Msekolah->daftarulangcheckrpt($pendaftaran_id,$value);
	// 			}
	// 		}
	// 	}
	// 	$data['info'] = "<div class='alert alert-info alert-dismissable'>Daftar ulang telah berhasil disimpan.</div>";

	// 	$data['daftarpenerapan'] = $this->Msekolah->tcg_daftarpenerapan($sekolah_id);
	// 	$data['waktudaftarulang'] = $this->Msetting->tcg_cek_waktudaftarulang($sekolah_id);
	// 	$this->load->view('ppdb/sekolah/daftarulang/index',$data);
	// }

	// function pendaftarsusulan()
	// {
	// 	$this->load->view('ppdb/sekolah/pendaftarsusulan/index');
	// }

	// function caripendaftarsusulan()
	// {
	// 	$this->load->model(array('Msekolah'));
	// 	$data['daftar'] = $this->Msekolah->carisiswa();
	// 	$this->load->view('ppdb/sekolah/pendaftarsusulan/daftarpencarian',$data);
	// }
	
	// function detailpendaftarsusulan()
	// {
	// 	$peserta_didik_id = $this->input->get("peserta_didik_id");

	// 	$this->load->model(array('Msekolah','Msiswa','Msetting'));
	// 	$data['referensibatasanperubahan'] = $this->Msetting->tcg_batasanperubahan();
		
	// 	$data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa($peserta_didik_id);
	// 	$data['daftarpendaftaran'] = $this->Msiswa->tcg_daftarpendaftaran($peserta_didik_id);
	// 	$this->load->view('ppdb/sekolah/pendaftarsusulan/detailpencarian/index',$data);
	// }
	
	// function prosespendaftarsusulan()
	// {
	// 	$this->load->model('Msekolah');
	// 	if($this->Msekolah->pendaftarsusulan()){
	// 		$data['info'] = "<div class='alert alert-info alert-dismissable'>Data berhasil disimpan.</div>";
	// 	}else{
	// 		$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.</div>";
	// 	}
	// 	$this->load->view('ppdb/sekolah/pendaftarsusulan/index',$data);
	// }

	// function detailpengajuanakun()
	// {
	// 	$this->tcg_detailakun(null);
	// }
	
	// function prosespengajuanakun()
	// {
	// 	$this->load->model('Msekolah');
	// 	if($this->Msekolah->pengajuanakun()){
	// 		$username = $this->input->post("username");
	// 		$data['info'] = "<div class='alert alert-info alert-dismissable'>Persetujuan akun telah berhasil disimpan dengan rincian :<br><b>Username</b> : ".$username."<br><b>Password</b> : ".$username."</div>";
	// 	}else{
	// 		$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.</div>";
	// 	}
	// 	$this->load->view('ppdb/sekolah/pengajuanakun/index',$data);
	// }

	// function ubahprofilsiswa() {
	// 	$this->form_validation->set_rules('nik','NIK','trim|required|min_length[16]|max_length[16]');
	// 	$this->form_validation->set_rules('nisn','NISN','trim|min_length[10]|max_length[10]');
	// 	$this->form_validation->set_rules('nama','Nama','trim|required|min_length[3]|max_length[100]');
	// 	$this->form_validation->set_rules('jenis_kelamin','Jenis Kelamin','trim|required|min_length[1]|max_length[1]');
	// 	$this->form_validation->set_rules('tempat_lahir','Tempat Lahir','trim|required|min_length[3]|max_length[32]');
	// 	$this->form_validation->set_rules('tanggal_lahir','Tanggal Lahir','trim|required');
	// 	$this->form_validation->set_rules('nama_ibu_kandung','Nama Ibu Kandung','trim|required|min_length[3]|max_length[100]');
	// 	$this->form_validation->set_rules('kebutuhan_khusus','Kebutuhan Khusus','trim|required|min_length[3]|max_length[50]');

	// 	$nik = $this->input->post("nik",TRUE);
	// 	$nisn = $this->input->post("nisn",TRUE);
	// 	$nomor_ujian = $this->input->post("nomor_ujian",TRUE);
	// 	$nama = $this->input->post("nama",TRUE);
	// 	$jenis_kelamin = $this->input->post("jenis_kelamin",TRUE);
	// 	$tempat_lahir = $this->input->post("tempat_lahir",TRUE);
	// 	$tanggal_lahir = $this->input->post("tanggal_lahir",TRUE);
	// 	$nama_ibu_kandung = $this->input->post("nama_ibu_kandung",TRUE);
	// 	$kebutuhan_khusus = $this->input->post("kebutuhan_khusus",TRUE);

	// 	$this->load->model(array('Msekolah','Msiswa'));
	// 	if($this->form_validation->run() != false){
	// 		if($this->Msiswa->tcg_ubahprofilsiswa($peserta_didik_id, $nik, $nisn, $nomor_ujian, $nama, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $nama_ibu_kandung, $kebutuhan_khusus))
	// 		{
	// 			$data['info'] = "<div class='alert alert-info alert-dismissable'>Profil siswa berhasil diperbaharui.</div>";
	// 		}else{
	// 			$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan silahkan ulangi kembali.</div>";
	// 		}
	// 	}else{
	// 		$data['info'] = "<div class='alert alert-danger alert-dismissable'>Gagal memperbaharui profil siswa.</div>";
	// 	}

	// }

	// function ubahalamatsiswa() {
	// 	$this->form_validation->set_rules('alamat', 'Alamat','trim|required|min_length[3]|max_length[80]');
	// 	$this->form_validation->set_rules('kode_desa','Desa/Kelurahan','trim|required|min_length[8]|max_length[8]');

	// 	$alamat = $this->input->post("alamat",TRUE);
	// 	$kode_kabupaten = $this->input->post("kode_kabupaten",TRUE);
	// 	$kode_kecamatan = $this->input->post("kode_kecamatan",TRUE);
	// 	$kode_desa = $this->input->post("kode_desa",TRUE);
	// 	$kode_wilayah = $this->input->post("kode_wilayah",TRUE);
	// 	if($kode_wilayah==""){
	// 		$kode_wilayah = $kode_desa;
	// 	}

	// 	$this->load->model(array('Msekolah','Msiswa'));
	// 	if($this->form_validation->run() != false){
	// 		if($this->Mlogin->tcg_ubahalamatsiswa($peserta_didik_id, $alamat, $kode_wilayah))
	// 		{
	// 			$data['info'] = "<div class='alert alert-info alert-dismissable'>Alamat siswa berhasil diperbaharui.</div>";
	// 		}else{
	// 			$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan silahkan ulangi kembali.</div>";
	// 		}
	// 	}else{
	// 		$data['info'] = "<div class='alert alert-danger alert-dismissable'>Gagal memperbaharui alamat siswa.</div>";
	// 	}

	// }

	// function ubahnilaiusbn() {
	// 	$bin = $this->input->post("bin",TRUE);
	// 	$mat = $this->input->post("mat",TRUE);
	// 	$ipa = $this->input->post("ipa",TRUE);

	// 	$this->load->model(array('Msekolah','Msiswa'));
	// 	if($this->form_validation->run() != false){
	// 		if($this->Mlogin->tcg_ubahnilaiusbn($peserta_didik_id, $bin, $mat, $ipa))
	// 		{
	// 			$data['info'] = "<div class='alert alert-info alert-dismissable'>Nilai USBN berhasil diperbaharui.</div>";
	// 		}else{
	// 			$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan silahkan ulangi kembali.</div>";
	// 		}
	// 	}else{
	// 		$data['info'] = "<div class='alert alert-danger alert-dismissable'>Gagal memperbaharui nilai USBN.</div>";
	// 	}

	// }

	// function ubahnilaisemester() {
	// 	$kelas4_sem1 = $this->input->post("kelas4sem1",TRUE);
	// 	$kelas4_sem2 = $this->input->post("kelas4sem2",TRUE);
	// 	$kelas5_sem1 = $this->input->post("kelas5sem1",TRUE);
	// 	$kelas5_sem2 = $this->input->post("kelas5sem2",TRUE);
	// 	$kelas6_sem1 = $this->input->post("kelas6sem1",TRUE);
	// 	$kelas6_sem2 = $this->input->post("kelas6sem2",0);
	// 	if (empty($kelas6_sem2))
	// 		$kelas6_sem2 = 0;

	// 	$this->load->model(array('Msekolah','Msiswa'));
	// 	if($this->form_validation->run() != false){
	// 		if($this->Msiswa->tcg_ubahnilaisemester($peserta_didik_id, $kelas4_sem1, $kelas4_sem2, $kelas5_sem1, $kelas5_sem2, $kelas6_sem1, $kelas6_sem2))
	// 		{
	// 			$data['info'] = "<div class='alert alert-info alert-dismissable'>Nilai semester berhasil diperbaharui.</div>";
	// 		}else{
	// 			$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan silahkan ulangi kembali.</div>";
	// 		}
	// 	}else{
	// 		$data['info'] = "<div class='alert alert-danger alert-dismissable'>Gagal memperbaharui nilai semester.</div>";
	// 	}
	
	// }

	// function ubahlokasirumah() {
	// 	$this->form_validation->set_rules('lintang','Lintang','trim|required|decimal');
	// 	$this->form_validation->set_rules('bujur','Bujur','trim|required|decimal');

	// 	$lintang = $this->input->post("lintang",TRUE);
	// 	$bujur = $this->input->post("bujur",TRUE);

	// 	$this->load->model(array('Msekolah','Msiswa'));
	// 	if($this->form_validation->run() != false){
	// 		if($this->Msiswa->tcg_ubahlokasirumah($peserta_didik_id, $lintang, $bujur))
	// 		{
	// 			$data['info'] = "<div class='alert alert-info alert-dismissable'>Lokasi rumah berhasil diperbaharui.</div>";
	// 		}else{
	// 			$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan silahkan ulangi kembali.</div>";
	// 		}
	// 	}else{
	// 		$data['info'] = "<div class='alert alert-danger alert-dismissable'>Gagal memperbaharui lokasi rumah.</div>";
	// 	}

	// }

	// function tcg_ubahakun($msg) {
	// 	$pengguna_id = $this->input->get("pengguna_id");

	// 	$this->load->model(array('Msekolah','Msiswa','Msetting','Mdropdown'));
												
	// 	$maxtgllahir="";
	// 	$mintgllahir="";

	// 	$batasanusia = $this->Msetting->tcg_batasanusia();
	// 	foreach($batasanusia->result() as $row):
	// 		$maxtgllahir = $row->maksimal_tanggal_lahir;
	// 		$mintgllahir = $row->minimal_tanggal_lahir;
	// 	endforeach;

	// 	$data['profilsiswa'] = $this->Msiswa->tcg_profilsiswa($pengguna_id);
	// 	$data['nilaiusbn'] = $this->Msiswa->tcg_nilaiusbn($pengguna_id);
	// 	$data['nilaisemester'] = $this->Msiswa->tcg_nilaisemester($pengguna_id);
	// 	$data['maxtgllahir'] = $maxtgllahir;
	// 	$data['mintgllahir'] = $mintgllahir;
	// 	$data['kabupaten'] = $this->Mdropdown->tcg_kabupaten();
	// 	$data['pengguna_id'] = $pengguna_id;

	// 	$approved = 0;
	// 	$akunsiswa = $this->Msiswa->tcg_akunsiswa($pengguna_id);
	// 	foreach($akunsiswa->result() as $row):
	// 		$approved = $row->approved;
	// 	endforeach;

	// 	$data['approved'] = $approved;
	// 	$data['msg'] = $msg;

	// 	$this->load->view('ppdb/sekolah/pengajuanakun/detail/index',$data);
	// }
}
