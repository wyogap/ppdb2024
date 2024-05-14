<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Klarifikasidinas extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		if($this->session->get('isLogged')==FALSE||$this->session->get('peran_id')!=4) {
			return redirect()->to("akun/login");
		}
	}

	function index()
	{
		// $sekolah_id = $_GET["sekolah_id"] ?? null; 
		// if (empty($sekolah_id)) {
		// 	$sekolah_id = $this->session->get('sekolah_id');
		// }

		// $data['sekolah_id'] = $sekolah_id;
		
		$this->load->model(array('Mdinas','Msetting'));
		$data['waktuverifikasi'] = $this->Msetting->tcg_cek_waktuverifikasi();

		$data['page'] = "klarifikasidinas";
		view('admin/klarifikasidinas/list',$data);

	}

	function json() {
		$tahun_ajaran_id = $_GET["tahun_ajaran"] ?? null;
		if (empty($tahun_ajaran_id))
			$tahun_ajaran_id = $this->tahun_ajaran_id;

		$tipe_data = $_GET["data"] ?? null; (("tipe_data");
		if (empty($tipe_data)) {
			$tipe_data = '';
		}

		$klarifikasi = $_GET["data"] ?? null; (("klarifikasi");
		if (empty($klarifikasi)) {
			$klarifikasi = 0;
		}

		$action = $_POST["action"] ?? null;
		if (empty($action) || $action=='view') {
			$this->load->model(array('admin/Mklarifikasidinas'));

			$data['data'] = $this->Mklarifikasidinas->list($tahun_ajaran_id, $tipe_data, $klarifikasi)->getResultArray(); 
			echo json_encode($data);	
		}
		else {
			$data['error'] = "not-implemented"; 
			echo json_encode($data);	
		}

	}

	function detail()
	{
		$klarifikasi_id = $_GET["data"] ?? null; (("klarifikasi_id");;
		if (empty($klarifikasi_id)) {
			return redirect()->to("admin/klarifikasidinas");
		}
		
		$this->load->model(array('admin/Mklarifikasidinas', 'Msiswa'));
		$data['waktuverifikasi'] = $this->Msetting->tcg_cek_waktuverifikasi();

		$data['klarifikasi_id'] = $klarifikasi_id;
		$data['klarifikasi'] = $this->Mklarifikasidinas->detail($klarifikasi_id)->getRowArray(); 
		//echo json_encode($data);	

		$peserta_didik_id = $data['klarifikasi']['peserta_didik_id'];
		
		$data['files'] = array();
		$data['dokumen'] = array();
		$data['dokumen_tambahan'] = array();

		$data['dokumenpendukung'] = $this->Msiswa->tcg_dokumen_pendukung($peserta_didik_id);
		foreach($data['dokumenpendukung']->getResult() as $row) {
			$row->path= base_url(). $row->path;
			$row->web_path= base_url(). $row->web_path;
			$row->thumbnail_path = base_url(). $row->thumbnail_path;
 
            $data['files'][$row->dokumen_id] = array(
                "dokumen_id"=>$row->dokumen_id, 
                "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
                "verifikasi"=>$row->verifikasi,
                "catatan"=>$row->catatan,
                "filename"=>$row->filename, 
                "file_path"=>$row->path, 
                "web_path"=>$row->web_path, 
                "thumbnail_path"=>$row->thumbnail_path);

			if($row->daftar_kelengkapan_id == 8) {
				//dokumen prestasi
				continue;
            }
            else {
                $data['dokumen'][$row->daftar_kelengkapan_id] = array(
                    "dokumen_id"=>$row->dokumen_id, 
                    "daftar_kelengkapan_id"=>$row->daftar_kelengkapan_id, 
                    "verifikasi"=>$row->verifikasi,
                    "catatan"=>$row->catatan,
                    "filename"=>$row->filename, 
					"file_path"=>$row->path, 
                    "web_path"=>$row->web_path, 
                    "thumbnail_path"=>$row->thumbnail_path);
            }   
		}

		//var_dump($data['klarifikasi']); exit;

		$data['page'] = "klarifikasidinas";
		view('admin/klarifikasidinas/detil',$data);

	}

	function prosesklarifikasidinas() {
		$peserta_didik_id = $_POST["data"] ?? null; (("peserta_didik_id");
		$klarifikasi_id = $_POST["data"] ?? null; (("klarifikasi_id");
		$catatan_dinas = $_POST["data"] ?? null; (("catatan_dinas");
		$orig_catatan_dinas = $_POST["data"] ?? null; (("orig_catatan_dinas");

		if ($catatan_dinas == $orig_catatan_dinas) {
			$this->session->setFlashdata('info', "Tidak ada perubahan data.");
			//no changes
			return redirect()->to('admin/klarifikasidinas');
		}

		$this->load->model(array('admin/Mklarifikasidinas'));

		if (empty($catatan_dinas)) {
			$this->session->setFlashdata('success', "Perubahan data berhasil disimpan. Status klarifikasi: BELUM DIKLARIFIKASI");
			$this->Mklarifikasidinas->ubah($klarifikasi_id, 2, null);
		}
		else {
			$this->session->setFlashdata('success', "Perubahan data berhasil disimpan. Status klarifikasi: SUDAH DIKLARIFIKASI");
			$this->Mklarifikasidinas->ubah($klarifikasi_id, 1, $catatan_dinas);
		}

		return redirect()->to('admin/klarifikasidinas');
	}
}
?>