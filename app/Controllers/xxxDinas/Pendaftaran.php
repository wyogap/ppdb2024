<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pendaftaran extends MY_Controller {
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
		
		$this->load->model(array('Mdropdown','Msetting'));

		$data['daftarsekolah'] = $this->Mdropdown->tcg_sekolah_sd_mi($kode_wilayah);
		$data['daftarkecamatan'] = $this->Mdropdown->tcg_kecamatan(null);

		$data['daftarsekolahtujuan'] = $this->Mdropdown->tcg_sekolah_smp_ppdb($kode_wilayah);
		$data['daftarpenerapan'] = $this->Mdropdown->tcg_penerapan();

		$data['page'] = "pengelolaan-pesertadidik";
		view('admin/pendaftaran/index',$data);

	}

	function cari() {
		$nama = $_POST["data"] ?? null; (("nama");
		$nisn= $_POST["data"] ?? null; (("nisn");
		$nik= $_POST["data"] ?? null; (("nik");
		$sekolah_id= $_POST["data"] ?? null; (("sekolah_id");
		$jenis_kelamin= $_POST["data"] ?? null; (("jenis_kelamin");
		$kode_desa= $_POST["data"] ?? null; (("kode_desa");
		$kode_kecamatan= $_POST["data"] ?? null; (("kode_kecamatan");

		$sekolah_tujuan_id= $_POST["data"] ?? null; (("sekolah_tujuan_id");
		$penerapan_id= $_POST["data"] ?? null; (("penerapan_id");

		$this->load->model(array('Mdinas','Mdropdown'));

		$data['daftar'] = $this->Mdinas->tcg_cari_pendaftaran($nama, $nisn, $nik, $sekolah_id, $jenis_kelamin, $kode_desa, $kode_kecamatan, $sekolah_tujuan_id, $penerapan_id);
		view('admin/pendaftaran/daftarpencarian',$data);
	}

	function json() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$this->load->model(array('Mdinas','Mdropdown','Mlogin', 'Msiswa'));
		
		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$nama = $_GET["data"] ?? null; (("nama");
			$nisn= $_GET["data"] ?? null; (("nisn");
			$nik= $_GET["data"] ?? null; (("nik");
			$sekolah_id= $_GET["data"] ?? null; (("sekolah_id");
			$jenis_kelamin= $_GET["data"] ?? null; (("jenis_kelamin");
			$kode_desa= $_GET["data"] ?? null; (("kode_desa");
			$kode_kecamatan= $_GET["data"] ?? null; (("kode_kecamatan");
	
			$sekolah_tujuan_id= $_GET["data"] ?? null; (("sekolah_tujuan_id");
			$penerapan_id= $_GET["data"] ?? null; (("penerapan_id");
			$is_deleted= $_GET["data"] ?? null; (("is_deleted");
	
			if (empty($nama) && empty($nisn) && empty($nik) && empty($sekolah_id) && empty($jenis_kelamin) && empty($kode_desa) && empty($kode_kecamatan) && empty($sekolah_tujuan_id) && empty($penerapan_id) && empty($is_deleted)) {
				//no search
				$data['data'] = array();
				echo json_encode($data);
			}
			else {
				if (empty($is_deleted)) {
					$is_deleted=0;
				}

				//search
				$daftar = $this->Mdinas->tcg_cari_pendaftaran($nama, $nisn, $nik, $sekolah_id, $jenis_kelamin, $kode_desa, $kode_kecamatan, $sekolah_tujuan_id, $penerapan_id, $is_deleted);
				$sekolah = $this->Mdropdown->tcg_select_sd_mi($this->session->get("kode_wilayah_aktif"));
				$sekolah_tujuan= $this->Mdropdown->tcg_select_smp_ppdb($this->session->get("kode_wilayah_aktif"));;
				$penerapan = $this->Mdropdown->tcg_penerapan();

				//manual echo json file to avoid memory exhausted
				echo '{"data":[';
				$first = true;
				while ($row = $daftar->unbuffered_row())
				{
					if ($first) {
						echo json_encode($row);
						$first = false;
					} else {
						echo ",". json_encode($row);
					}
				}
				echo '],"options":{"sekolah_id":[';
				$first = true;
				while ($row = $sekolah->unbuffered_row()) 
				{
					if ($first) {
						echo json_encode($row);
						$first = false;
					} else {
						echo ",". json_encode($row);
					}
				}
				echo ']}}';
			}

			$data['options']['penerapan_id'] = $this->Mdinas->lookup_penerapan($tahun_ajaran_id)->getResultArray(); 
			$data['options']['jenis_pilihan'] = $this->Mdinas->lookup_jenis_pilihan($tahun_ajaran_id)->getResultArray(); 

		}
		else if ($action=='edit'){
			$values = $_POST["data"] ?? null; (("data");

			$data['data'] = array();
            $error_msg = "";
			foreach ($values as $key => $valuepair) {
				//$data['data'][$key] = $valuepair;
				$detil = $this->Mdinas->tcg_detil_pendaftaran($key)->getRowArray();
				if ($detil == null)	continue;

				//echo $valuepair['penerapan_id'] . "><". $detil['penerapan_id'];

				if (!empty($valuepair['penerapan_id']) && $valuepair['penerapan_id'] != $detil['penerapan_id']) {
					$this->Mdinas->tcg_ubah_jalur_pendaftaran($key, $valuepair['penerapan_id']);
					unset($valuepair['penerapan_id']);
				}

				if (count($valuepair) > 0) {
					$this->Mdinas->tcg_ubah_pendaftaran($key, $valuepair);
					// if ($this->Mdinas->tcg_ubah_pendaftaran($key, $valuepair)) {
					// 	// foreach($this->Mdinas->tcg_detil_pendaftaran($key)->getResult() as $row) {
					// 	// 	$data['data'][] = $row;
					// 	// }
					// }
				}

				$data['data'] = $this->Mdinas->tcg_detil_pendaftaran($key)->getResultArray();
			}

			echo json_encode($data);	
        }
		else if ($action=='remove'){
			$values = $_POST["data"] ?? null; (("data");

			$data['data'] = array();
            $error_msg = "";
			foreach ($values as $key => $valuepair) {

				$this->Mdinas->tcg_hapus_pendaftaran($key);

			}

			echo json_encode($data);	
		}
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}
	}

	function hitungskor() {
		$pendaftaran_id= $_GET["data"] ?? null; (("pendaftaran_id");

		$this->load->model(array('Mdinas'));
		$this->Mdinas->tcg_hitung_skor($pendaftaran_id);

		$data['status'] = "1"; 
		echo json_encode($data);
	}


	function kelengkapanberkas() {
		$pendaftaran_id= $_GET["data"] ?? null; (("pendaftaran_id");

		$this->load->model(array('Mdinas'));

		$this->Mdinas->tcg_hitung_skor($pendaftaran_id);

		$detil = $this->Mdinas->tcg_detil_pendaftaran($pendaftaran_id)->getRowArray();
		if ($detil == null) {
			$data['status'] = "0"; 
			echo json_encode($data);
		}

		$this->Mdinas->tcg_ubah_kelengkapan_berkas($detil['peserta_didik_id']);

		$data['status'] = "1"; 
		echo json_encode($data);
	}


}
?>