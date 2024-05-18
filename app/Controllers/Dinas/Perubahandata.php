<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Perubahandata extends MY_Controller {
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
		$data['daftarpengajuan'] = $this->Mdinas->daftarpengajuanperubahandata();
		$data['daftarpersetujuan'] = $this->Mdinas->daftarpersetujuanperubahandata();

		$data['page'] = "pengelolaan-perubahandata";
		view('admin/pengajuanperubahandata/index',$data);
	}

	function pengajuanperubahandata()
	{
		$this->load->model(array('Mdinas'));
		$data['detailperubahandatasiswa'] = $this->Mdinas->detailperubahandatasiswa();
		view('admin/pengajuanperubahandata/detail/index',$data);
	}

	function prosesperubahandata()
	{
		$pengguna_id = $this->session->get("user_id");

		$peserta_didik_id = $_POST["data"] ?? null; (("peserta_didik_id");
		$approval = $_POST["data"] ?? null; (("approval");
		$kode_wilayah = $_POST["data"] ?? null; (("kode_wilayah");
		if($kode_wilayah==''){
			$kode_wilayah = $_POST["data"] ?? null; (("kode_desa");
		}
		$tanggal_lahir = $_POST["data"] ?? null; (("tanggal_lahir");
		$lintang = $_POST["data"] ?? null; (("lintang");
		$bujur = $_POST["data"] ?? null; (("bujur");
		$keterangan_approval = $_POST["data"] ?? null; (("keterangan_approval");
		
		$this->load->model(array('Mdinas','Msiswa'));
		if($this->Msiswa->tcg_ubahdata($peserta_didik_id, $approval, $keterangan_approval, $kode_wilayah, $tanggal_lahir, $lintang, $bujur, $pengguna_id)){
			$data['info'] = "<div class='alert alert-info alert-dismissable'>Persetujuan perubahan data telah berhasil disimpan.</div>";
		}else{
			$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.</div>";
		}
		$data['daftarpengajuan'] = $this->Mdinas->daftarpengajuanperubahandata();
		$data['daftarpersetujuan'] = $this->Mdinas->daftarpersetujuanperubahandata();
		view('admin/pengajuanperubahandata/index',$data);
	}
	


	
}
?>