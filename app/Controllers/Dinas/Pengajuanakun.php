<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pengajuanakun extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		if($this->session->get('isLogged')==FALSE||$this->session->get('peran_id')!=4) {
			return redirect()->to("akun/login");
		}
	}

	function index()
	{
		$this->load->model('Mdinas');
		$data['daftarpengajuanakun'] = $this->Mdinas->daftarpengajuanakun();
		$data['daftarpersetujuanakun'] = $this->Mdinas->daftarpersetujuanakun();
		$data['daftartidaksetujuakun'] = $this->Mdinas->daftartidaksetujuakun();

		$data['page'] = "pengelolaan-pengajuanakun";
		view('admin/pengajuanakun/index',$data);
	}

	function detailpengajuanakun()
	{
		$this->load->model('Mdinas');
		$data['detailpengajuanakun'] = $this->Mdinas->detailpengajuanakun();
		view('admin/pengajuanakun/detail/index',$data);
	}

	function prosespengajuanakun()
	{
		$this->load->model('Mdinas');
		if($this->Mdinas->pengajuanakun()){
			$username = $_POST["data"] ?? null; (("username");
			$data['info'] = "<div class='alert alert-info alert-dismissable'>Persetujuan akun telah berhasil disimpan dengan rincian :<br><b>Username</b> : ".$username."<br><b>Password</b> : ".$username."</div>";
		}else{
			$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.</div>";
		}
		$data['daftarpengajuanakun'] = $this->Mdinas->daftarpengajuanakun();
		$data['daftarpersetujuanakun'] = $this->Mdinas->daftarpersetujuanakun();
		$data['daftartidaksetujuakun'] = $this->Mdinas->daftartidaksetujuakun();

		view('admin/pengajuanakun/index',$data);
	}



	
}
?>