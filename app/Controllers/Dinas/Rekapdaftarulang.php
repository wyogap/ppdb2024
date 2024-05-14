<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rekapdaftarulang extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		if($this->session->get('isLogged')==FALSE||$this->session->get('peran_id')!=4) {
			return redirect()->to("akun/login");
		}
	}

	function index()
	{
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$kode_wilayah = $this->session->get("kode_wilayah_aktif");
		
		$this->load->model(array('Mdinas','Mdropdown'));

		$data['daftarsekolah'] = $this->Mdropdown->tcg_sekolah_sd_mi($kode_wilayah);
		$data['daftarsekolahtujuan'] = $this->Mdropdown->tcg_sekolah_smp_ppdb($kode_wilayah);

		$data['page'] = "rekap-daftar-ulang";
		view('admin/rekapdaftarulang/index',$data);

	}

	function json() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$sekolah_id = $this->session->get("sekolah_id");

		$sekolah_asal_id = $_GET["data"] ?? null; (("sekolah_asal_id");
		$sekolah_tujuan_id = $_GET["data"] ?? null; (("sekolah_tujuan_id");
		$status_sekolah_tujuan = $_GET["data"] ?? null; (("status_sekolah_tujuan");

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$this->load->model(array('Mdinas'));

			$data['data'] = $this->Mdinas->tcg_rekap_daftarulang($sekolah_asal_id, $sekolah_tujuan_id, $status_sekolah_tujuan)->getResultArray(); 
			echo json_encode($data);	
		}
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}

	}

	
}
?>