<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kuota extends MY_Controller {
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

		$this->load->model(array('Msetting','admin/Mpenerapan'));

		$data['tahun_ajaran'] = $this->Msetting->tcg_tahunajaran();
		$data['tahun_ajaran_aktif'] = $tahun_ajaran_id;

		$data['negeri_zonasi'] = 0;
		$data['negeri_afirmasi'] = 0;
		$data['negeri_prestasi'] = 0;
		$data['negeri_inklusi'] = 0;
		$data['negeri_perpindahan'] = 0;
		$data['negeri_susulan'] = 0;
		$data['swasta_zonasi'] = 0;
		$data['swasta_afirmasi'] = 0;
		$data['swasta_prestasi'] = 0;
		$data['swasta_inklusi'] = 0;
		$data['swasta_perpindahan'] = 0;
		$data['swasta_swasta'] = 0;

		$daftarjalur = $this->Mpenerapan->tcg_jalur_pendaftaran($tahun_ajaran_id)->getResultArray(); 
		foreach($daftarjalur as $jalur) {
			switch ($jalur['jalur_id']) {
				case 1:		//zonasi
					if ($jalur['sekolah_negeri'])	$data['negeri_zonasi']=1;
					if ($jalur['sekolah_swasta'])	$data['swasta_zonasi']=1;
					break;
				case 2:		//prestasi
					if ($jalur['sekolah_negeri'])	$data['negeri_prestasi']=1;
					if ($jalur['sekolah_swasta'])	$data['swasta_prestasi']=1;
					break;
				case 3:		//perpindahan
					if ($jalur['sekolah_negeri'])	$data['negeri_perpindahan']=1;
					if ($jalur['sekolah_swasta'])	$data['swasta_perpindahan']=1;
					break;
				case 9: 	//afirmasi
					if ($jalur['sekolah_negeri'])	$data['negeri_afirmasi']=1;
					if ($jalur['sekolah_swasta'])	$data['swasta_afirmasi']=1;
					break;
				case 7:		//inklusi
					if ($jalur['sekolah_negeri'])	$data['negeri_inklusi']=1;
					if ($jalur['sekolah_swasta'])	$data['swasta_inklusi']=1;
					break;
				case 12:	//susulan
					if ($jalur['sekolah_negeri'])	$data['negeri_susulan']=1;
					if ($jalur['sekolah_swasta'])	$data['swasta_susulan']=1;
					break;
				case 5:		//swasta
					if ($jalur['sekolah_swasta'])	$data['swasta_swasta']=1;
					break;
			}
		}

		//enforce
		$data['negeri_prestasi'] = 1;
		$data['swasta_prestasi'] = 1;

		$data['page'] = "konfigurasi-kuota";
		view('admin/kuotasekolah/index',$data);
	}

	// function json() {
	// 	$negeri = $_GET["data"] ?? null; (("negeri");
	// 	if (!empty($negeri)) {
	// 		$this->tablekuotanegeri();
	// 		return;
	// 	}

	// 	$swasta = $_GET["data"] ?? null; (("swasta");
	// 	if (!empty($swasta)) {
	// 		$this->tablekuotaswasta();
	// 		return;
	// 	}
	// }

	function tablekuotanegeri() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$this->load->model(array('admin/Msekolah'));

			$data['data'] = $this->Msekolah->tcg_kuotasekolahnegeri($tahun_ajaran_id)->getResultArray(); 
			echo json_encode($data);	
		}
		else if ($action=='edit'){
			$data = $_POST["data"] ?? null; (("data");

			$this->load->model(array('admin/Msekolah'));

			foreach ($data as $sekolah_id => $valuepair) {
				$kuota_total = 0;
				$kuota_zonasi = 0;
				$kuota_prestasi = 0;
				$kuota_afirmasi = 0;
				$kuota_perpindahan_ortu = 0;
				$kuota_inklusi = 0;
				$kuota_susulan = 0;
				$kuota_swasta = 0;
				$ikut_ppdb = 0;
		
				foreach($this->Msekolah->tcg_kuotasekolah($tahun_ajaran_id, $sekolah_id)->getResult() as $row):
					$kuota_zonasi = $row->kuota_zonasi;
					$kuota_prestasi = $row->kuota_prestasi;
					$kuota_afirmasi = $row->kuota_afirmasi;
					$kuota_perpindahan_ortu = $row->kuota_perpindahan_ortu;
					$kuota_inklusi = $row->kuota_inklusi;
					$kuota_susulan = $row->kuota_susulan;
					$kuota_total = $row->kuota_total;
				endforeach;	

				$flag = false;
				foreach ($valuepair as $key => $value) {
					if ($key == "kuota_total") {
						$kuota_total = $value;
						$flag = true;
					} else if ($key == "kuota_zonasi" && $value != $kuota_zonasi) {
						$kuota_zonasi = $value;
						//overide individual jalur
						$kuota_total = 0;
						$flag = true;
					} else if ($key == "kuota_prestasi" && $value != $kuota_prestasi) {
						$kuota_prestasi = $value;
						//overide individual jalur
						$kuota_total = 0;
						$flag = true;
					} else if ($key == "kuota_afirmasi" && $value != $kuota_afirmasi) {
						$kuota_afirmasi = $value;
						//overide individual jalur
						$kuota_total = 0;
						$flag = true;
					} else if ($key == "kuota_perpindahan_ortu" && $value != $kuota_perpindahan_ortu) {
						$kuota_perpindahan_ortu = $value;
						//overide individual jalur
						$kuota_total = 0;
						$flag = true;
					} else if ($key == "kuota_inklusi" && $value != $kuota_inklusi) {
						$kuota_inklusi = $value;
						//overide individual jalur
						$kuota_total = 0;
						$flag = true;
					} else if ($key == "kuota_susulan" && $value != $kuota_susulan) {
						$kuota_susulan = $value;
						//overide individual jalur
						$kuota_total = 0;
						$flag = true;
					} else {
						continue;
					}
				}

				if ($flag) {
					$this->Msekolah->tcg_ubahkuotanegeri($tahun_ajaran_id,$sekolah_id, $kuota_total, $kuota_zonasi, $kuota_prestasi, $kuota_afirmasi, $kuota_perpindahan_ortu, $kuota_inklusi, $kuota_susulan);
				}
			}

			// foreach ($data as $i => $valuepair) {
			// 	foreach ($valuepair as $j => $value) {
			// 		$this->Mdinas->tcg_update_kuotanegeri($tahun_ajaran_id, $i, $j, $value);
			// 	}
			// }

			$data['data'] = $this->Msekolah->tcg_kuotasekolah($tahun_ajaran_id, $sekolah_id)->getResultArray(); 
			echo json_encode($data);	
		}

	}

	function tablekuotaswasta() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$this->load->model(array('admin/Msekolah'));

			$data['data'] = $this->Msekolah->tcg_kuotasekolahswasta($tahun_ajaran_id)->getResultArray(); 
			echo json_encode($data);	
		}
		else if ($action=='edit'){
			$data = $_POST["data"] ?? null; (("data");
		
			$this->load->model(array('admin/Msekolah'));

			foreach ($data as $sekolah_id => $valuepair) {
				$kuota_zonasi = 0;
				$kuota_prestasi = 0;
				$kuota_afirmasi = 0;
				$kuota_perpindahan_ortu = 0;
				$kuota_inklusi = 0;
				$kuota_swasta = 0;
				$ikut_ppdb = 0;
		
				foreach($this->Msekolah->tcg_kuotasekolah($tahun_ajaran_id, $sekolah_id)->getResult() as $row):
					$kuota_zonasi = $row->kuota_zonasi;
					$kuota_prestasi = $row->kuota_prestasi;
					$kuota_afirmasi = $row->kuota_afirmasi;
					$kuota_perpindahan_ortu = $row->kuota_perpindahan_ortu;
					$kuota_inklusi = $row->kuota_inklusi;
					$kuota_swasta = $row->kuota_swasta;
					$ikut_ppdb = $row->ikut_ppdb;
				endforeach;	
				
				$flag = false;
				foreach ($valuepair as $key => $value) {
					if ($key == "kuota_zonasi" && $value != $kuota_zonasi) {
						$kuota_zonasi = $value;
						$flag = true;
					} else if ($key == "kuota_prestasi" && $value != $kuota_prestasi) {
						$kuota_prestasi = $value;
						$flag = true;
					} else if ($key == "kuota_afirmasi" && $value != $kuota_afirmasi) {
						$kuota_afirmasi = $value;
						$flag = true;
					} else if ($key == "kuota_perpindahan_ortu" && $value != $kuota_perpindahan_ortu) {
						$kuota_perpindahan_ortu = $value;
						$flag = true;
					} else if ($key == "kuota_inklusi" && $value != $kuota_inklusi) {
						$kuota_inklusi = $value;
						$flag = true;
					} else if ($key == "kuota_swasta" && $value != $kuota_swasta) {
						$kuota_swasta = $value;
						$flag = true;
					} else if ($key == "ikut_ppdb" && $value != $ikut_ppdb) {
						$ikut_ppdb = $value;
						$flag = true;
					} else {
						continue;
					}
				}

				if ($flag) {
					$this->Msekolah->tcg_ubahkuotaswasta($tahun_ajaran_id,$sekolah_id, $ikut_ppdb, $kuota_zonasi, $kuota_prestasi, $kuota_afirmasi, $kuota_perpindahan_ortu, $kuota_inklusi, $kuota_swasta);
				}
			}

			// //make sure we process ikut_ppdb first
			// $ikut_ppdb = 0;
			// foreach ($data as $i => $valuepair) {
			// 	foreach ($valuepair as $j => $value) {
			// 		if ($j == 'ikut_ppdb') {
			// 			$this->Mdinas->tcg_update_kuotaswasta($tahun_ajaran_id, $i, $j, $value);
			// 			$ikut_ppdb = $value;
			// 		}
			// 	}
			// }

			// //then we process individual kuota, only if ikut-ppdb
			// if ($ikut_ppdb == 1) {
			// 	foreach ($data as $i => $valuepair) {
			// 		foreach ($valuepair as $j => $value) {
			// 			if ($j != 'ikut_ppdb') {
			// 				$this->Mdinas->tcg_update_kuotaswasta($tahun_ajaran_id, $i, $j, $value);
			// 				$ikut_ppdb = $value;
			// 			}
			// 		}
			// 	}
			// }

			$data['data'] = $this->Msekolah->tcg_kuotasekolah($tahun_ajaran_id, $sekolah_id)->getResultArray(); 
			echo json_encode($data);	
		}

	}

	function ubah() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$sekolah_id = $_GET["sekolah_id"] ?? null; 

		$this->load->model(array('admin/Msekolah'));

		$data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);
		$status = "N";
		foreach($data['profilsekolah']->getResult() as $row):
			$status = $row->status;
		endforeach;

		$data['kuotasekolah'] = $this->Msekolah->tcg_kuotasekolah($tahun_ajaran_id,$sekolah_id);
		if ($status == "S") {
			view('admin/kuotasekolah/ubahkuotaswasta',$data);
		} else {
			view('admin/kuotasekolah/ubahkuotanegeri',$data);
		}
	}

	function ubahkuotanegeri() {
		$tahun_ajaran_id = $this->session->get("tahun_ajaran");
		$sekolah_id = $_GET["sekolah_id"] ?? null; 

		$this->load->model(array('admin/Msekolah'));

		$data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);

		$status = "N";
		foreach($data['profilsekolah']->getResult() as $row):
			$status = $row->status;
		endforeach;

		if ($status == 'S') {
			return redirect()->to('Cadmin/kuotasekolah/ubahsekolahswasta?sekolah_id='.$sekolah_id);
		}

		$data['kuotasekolah'] = $this->Msekolah->tcg_kuotasekolah($tahun_ajaran_id, $sekolah_id);

		view('admin/kuotasekolah/ubahkuotanegeri',$data);

	}

	function ubahkuotaswasta() {
		$tahun_ajaran_id = $this->session->get("tahun_ajaran");
		$sekolah_id = $_GET["sekolah_id"] ?? null; 

		$this->load->model(array('admin/Msekolah'));

		$data['profilsekolah'] = $this->Msekolah->tcg_profilsekolah($sekolah_id);

		$status = "N";
		foreach($data['profilsekolah']->getResult() as $row):
			$status = $row->status;
		endforeach;

		if ($status != 'S') {
			return redirect()->to('Cadmin/kuotasekolah/ubahsekolahnegeri?sekolah_id='.$sekolah_id);
		}

		$data['kuotasekolah'] = $this->Msekolah->tcg_kuotasekolah($tahun_ajaran_id,$sekolah_id);

		view('admin/kuotasekolah/ubahkuotaswasta',$data);

	}

	function prosesubahkuotanegeri() {
		$sekolah_id = $_POST["sekolah_id"] ?? null;
		$kuota_total = $_POST["data"] ?? null; (("kuota_total");
		$tahun_ajaran_id = $this->session->get("tahun_ajaran");

		$this->load->model(array('Mdinas'));
		$status = $this->Mdinas->tcg_ubahkuotanegeri($tahun_ajaran_id,$sekolah_id, $kuota_total);
		if ($status > 0) {
			$data['info'] = "<div class='alert alert-info alert-dismissable'>Perubahan kuota berhasil disimpan.</div>";
		}else{
			$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.</div>";			
		}

		$data['sekolah_negeri'] = $this->Mdinas->tcg_kuotasekolahnegeri($tahun_ajaran_id);
		$data['sekolah_swasta'] = $this->Mdinas->tcg_kuotasekolahswasta($tahun_ajaran_id);

		view('admin/kuotasekolah/index',$data);
	}

	function prosesubahkuotaswasta() {
		$sekolah_id = $_POST["sekolah_id"] ?? null;
		$status = $_POST["data"] ?? null; (("ikut_ppdb");
		$kuota_zonasi = $_POST["data"] ?? null; (("kuota_zonasi");
		$kuota_prestasi = $_POST["data"] ?? null; (("kuota_prestasi");
		$kuota_afirmasi = $_POST["data"] ?? null; (("kuota_afirmasi");
		$kuota_perpindahan = $_POST["data"] ?? null; (("kuota_perpindahan_ortu");
		$kuota_inklusi = $_POST["data"] ?? null; (("kuota_inklusi");
		$tahun_ajaran_id = $this->session->get("tahun_ajaran");

		$this->load->model(array('Mdinas'));
		$status = $this->Mdinas->tcg_ubahkuotaswasta($tahun_ajaran_id,$sekolah_id, $status, $kuota_zonasi, $kuota_prestasi, $kuota_afirmasi, $kuota_perpindahan, $kuota_inklusi);
		if ($status > 0) {
			$data['info'] = "<div class='alert alert-info alert-dismissable'>Perubahan kuota berhasil disimpan.</div>";
		}else{
			$data['info'] = "<div class='alert alert-danger alert-dismissable'>Terjadi permasalahan sehingga data gagal tersimpan, silahkan ulangi kembali.</div>";			
		}

		$data['sekolah_negeri'] = $this->Mdinas->tcg_kuotasekolahnegeri($tahun_ajaran_id);
		$data['sekolah_swasta'] = $this->Mdinas->tcg_kuotasekolahswasta($tahun_ajaran_id);

		view('admin/kuotasekolah/index',$data);
	}

	
}
?>