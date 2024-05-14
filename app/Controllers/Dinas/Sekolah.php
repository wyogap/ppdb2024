<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sekolah extends MY_Controller {
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
		$data = array();

		$data['page'] = "pengelolaan-sekolah";
		view('admin/sekolah/index',$data);

	}

	function cari() {
		$nama = $_POST["data"] ?? null; (("nama");
		$npsn= $_POST["data"] ?? null; (("nisn");
		$bentuk_pendidikan= $_POST["data"] ?? null; (("bentuk_pendidikan");
		$status= $_POST["data"] ?? null; (("status");

		$this->load->model(array('Mdinas','Mdropdown'));

		$data['daftar'] = $this->Mdinas->tcg_cari_sekolah($nama, $npsn, $bentuk_pendidikan, $status);
		view('admin/sekolah/daftarpencarian',$data);
	}

	function json() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$this->load->model(array('Mdinas','Mdropdown','Mlogin', 'Msiswa'));
		
		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$nama = $_GET["data"] ?? null; (("nama");
			$npsn= $_GET["data"] ?? null; (("npsn");
			$bentuk_pendidikan= $_GET["data"] ?? null; (("bentuk_pendidikan");
			$status= $_GET["data"] ?? null; (("status");
		
			if (empty($nama) && empty($npsn) && empty($bentuk_pendidikan) && empty($status)) {
				//no search
				$data['data'] = array();
				echo json_encode($data);
			}
			else {
				//search
				$data['data'] = $this->Mdinas->tcg_cari_sekolah($nama, $npsn, $bentuk_pendidikan, $status)->getResultArray();
				echo json_encode($data);
			}
		}
		else if ($action=='edit'){
			$values = $_POST["data"] ?? null; (("data");

			$data['data'] = array();
            $error_msg = "";
			foreach ($values as $key => $valuepair) {
				$retval = $this->Mdinas->tcg_ubah_sekolah($key, $valuepair);
				if($retval == 0) {
					$error = $builder->error();
					if (empty($error['message'])) {
						$data['error'] = 'Terjadi permasalahan sehingga data gagal tersimpan. Silahkan ulangi kembali.';
					} else {
						$data['error'] = $error['message'];
					}
				} else {
					foreach($this->Mdinas->tcg_detil_sekolah($key)->getResult() as $row) {
						$data['data'][] = $row;
					}
				}
			}

			echo json_encode($data);	
        }
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}
	}

}
?>