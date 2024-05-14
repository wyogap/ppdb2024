<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Petunjukpelaksanaan extends MY_Controller {
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

		
		$this->load->model(array('Mdinas','Msetting'));

		// $data['jalur_penerimaan'] = $this->Mdinas->tcg_jalurpenerimaan($tahun_ajaran_id);
		$data['tahun_ajaran'] = $this->Msetting->tcg_tahunajaran();
		$data['tahun_ajaran_aktif'] = $tahun_ajaran_id;
		$data['petunjuk_pelaksanaan'] = $this->Msetting->tcg_petunjuk_pelaksanaan();

		$data['page'] = "konfigurasi-petunjuk";
		view('admin/petunjukpelaksanaan/index',$data);

	}

	function json() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='edit') {
			$jadwal = $_POST["data"] ?? null; (("jadwal");
			$persyaratan = $_POST["data"] ?? null; (("persyaratan");
			$tatacara = $_POST["data"] ?? null; (("tatacara");
			$jalur = $_POST["data"] ?? null; (("jalur");
			$seleksi = $_POST["data"] ?? null; (("seleksi");
			$nilai = $_POST["data"] ?? null; (("nilai");

			//decode
			$valuepair["jadwal_pelaksanaan"] = base64_decode($jadwal);
			$valuepair["persyaratan"] = base64_decode($persyaratan);
			$valuepair["tata_cara_pendaftaran"] = base64_decode($tatacara);
			$valuepair["jalur_pendaftaran"] = base64_decode($jalur);
			$valuepair["proses_seleksi"] = base64_decode($seleksi);
			$valuepair["konversi_nilai"] = base64_decode($nilai);

			//replace
			$valuepair["jadwal_pelaksanaan"] = str_ireplace("<ul>", '<ul class="list-style list-show style-caret style-font12 color-silver">', $valuepair["jadwal_pelaksanaan"]);
			$valuepair["jadwal_pelaksanaan"] = str_ireplace("<li>", '<li><a>', $valuepair["jadwal_pelaksanaan"]);
			$valuepair["jadwal_pelaksanaan"] = str_ireplace("</li>", '</a></li>', $valuepair["jadwal_pelaksanaan"]);

			$valuepair["persyaratan"] = str_ireplace("<ul>", '<ul class="list-style list-show style-caret style-font12 color-silver">', $valuepair["persyaratan"]);
			$valuepair["persyaratan"] = str_ireplace("<li>", '<li><a>', $valuepair["persyaratan"]);
			$valuepair["persyaratan"] = str_ireplace("</li>", '</a></li>', $valuepair["persyaratan"]);

			$valuepair["tata_cara_pendaftaran"] = str_ireplace("<ul>", '<ul class="list-style list-show style-caret style-font12 color-silver">', $valuepair["tata_cara_pendaftaran"]);
			$valuepair["tata_cara_pendaftaran"] = str_ireplace("<li>", '<li><a>', $valuepair["tata_cara_pendaftaran"]);
			$valuepair["tata_cara_pendaftaran"] = str_ireplace("</li>", '</a></li>', $valuepair["tata_cara_pendaftaran"]);

			$valuepair["jalur_pendaftaran"] = str_ireplace("<ul>", '<ul class="list-style list-show style-caret style-font12 color-silver">', $valuepair["jalur_pendaftaran"]);
			$valuepair["jalur_pendaftaran"] = str_ireplace("<li>", '<li><a>', $valuepair["jalur_pendaftaran"]);
			$valuepair["jalur_pendaftaran"] = str_ireplace("</li>", '</a></li>', $valuepair["jalur_pendaftaran"]);

			$valuepair["proses_seleksi"] = str_ireplace("<ul>", '<ul class="list-style list-show style-caret style-font12 color-silver">', $valuepair["proses_seleksi"]);
			$valuepair["proses_seleksi"] = str_ireplace("<li>", '<li><a>', $valuepair["proses_seleksi"]);
			$valuepair["proses_seleksi"] = str_ireplace("</li>", '</a></li>', $valuepair["proses_seleksi"]);

			$valuepair["konversi_nilai"] = str_ireplace("<ul>", '<ul class="list-style list-show style-caret style-font12 color-silver">', $valuepair["konversi_nilai"]);
			$valuepair["konversi_nilai"] = str_ireplace("<li>", '<li><a>', $valuepair["konversi_nilai"]);
			$valuepair["konversi_nilai"] = str_ireplace("</li>", '</a></li>', $valuepair["konversi_nilai"]);


			$this->load->model(array('Mdinas','Msetting'));
			$data['status'] = $this->Mdinas->tcg_ubah_petunjuk_pelaksanaan($tahun_ajaran_id, $valuepair);

			echo json_encode($data);	
		}
		else {
			$data["error"] = "not-implemented";
			echo json_encode($data);	
		}

	}
	
}
?>