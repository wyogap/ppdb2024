<?php 
Class Mklarifikasidinas 
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

	// function tcg_daftarpenerapan($tahun_ajaran_id){
	// 	$builder->select('a.penerapan_id,a.jalur_id,c.nama AS jalur');
	// 	$builder = $this->db->table('ref_penerapan a');
	// 	$builder->join('ref_jalur c','a.jalur_id = c.jalur_id AND c.is_deleted=0');
	// 	$builder->where(array('a.aktif'=>1,'a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.expired_date'=>NULL));
	// 	return $builder->get();
	// }
	
	// function lookup($tahun_ajaran_id) {
	// 	$tahun_ajaran_id = secure($tahun_ajaran_id);
	// 	$query = "select a.penerapan_id as value, a.nama as label
	// 			  from ref_penerapan a
	// 			  where a.tahun_ajaran_id=$tahun_ajaran_id and a.is_deleted=0 order by a.aktif desc, a.urutan";
	// 	return $this->db->query($query);
	// }

	function list($tahun_ajaran_id, $tipe_data='', $klarifikasi=0) {
		$builder = $this->db->table("tcg_verifikasi_dinas a");
		$builder->select("b.*, 
							a.verifikasi_id as klarifikasi_id,
							a.tipe_data,
							a.pengguna_sekolah,
							a.sekolah_id,
							a.catatan_sekolah,
							a.verifikasi,
							a.pengguna_dinas,
							a.catatan_dinas,
							a.tanggal_verifikasi as tanggal_klarifikasi,
							a.created_on as tanggal_eskalasi,
							x1.nama as nama_pengguna_sekolah,
							x2.nama as nama_pengguna_dinas,
							x3.nama as sekolah_tujuan
										");
		$builder->join("tcg_peserta_didik b", "b.peserta_didik_id=a.peserta_didik_id AND b.is_deleted=0 AND b.tahun_ajaran_id=a.tahun_ajaran_id");
		$builder->join('dbo_users x1','x1.peserta_didik_id = a.pengguna_sekolah AND x1.is_deleted=0','LEFT OUTER');
		$builder->join('dbo_users x2','x2.peserta_didik_id = a.pengguna_dinas AND x2.is_deleted=0','LEFT OUTER');
		$builder->join('ref_sekolah x3','x3.sekolah_id = a.sekolah_id AND x3.is_deleted=0','LEFT OUTER');
		$builder->where("a.is_deleted", 0);
		$builder->where("a.tahun_ajaran_id", $tahun_ajaran_id);
		if (!empty($tipe_data)) {
			$builder->where("a.tipe_data", $tipe_data);
		}

		if ($klarifikasi == 1) {
			$builder->where("a.verifikasi", 1);
		}
		else {
			$builder->where("a.verifikasi !=", 1);
		}

		return $builder->get();
	}

	function detail($klarifikasi_id) {
		$builder = $this->db->table("tcg_verifikasi_dinas a");
		$builder->select("
			b.peserta_didik_id,b.nik,b.nisn,
			b.nama,b.jenis_kelamin,b.tempat_lahir,b.tanggal_lahir,
			b.nama_ibu_kandung,b.nama_ayah,b.nama_wali,
			b.rt,b.rw,b.alamat,b.nomor_kontak,b.kode_wilayah, 
			a.verifikasi_id as klarifikasi_id,
			a.tipe_data,
			a.pengguna_sekolah,
			a.sekolah_id,
			a.catatan_sekolah,
			a.verifikasi as klarifikasi,
			a.pengguna_dinas,
			a.catatan_dinas,
			a.tanggal_verifikasi as tanggal_klarifikasi,
			a.created_on as tanggal_eskalasi,
			c.npsn,
			c.nama as sekolah,
			d.kode_wilayah_desa as kode_desa, coalesce(d.nama_desa,b.desa_kelurahan) AS desa_kelurahan,
			d.kode_wilayah_kec as kode_kecamatan,d.nama_kec AS kecamatan,
			d.kode_wilayah_kab as kode_kabupaten,d.nama_kab AS kabupaten,
			d.kode_wilayah_prov,d.nama_prov AS provinsi,
			b.verifikasi_profil,b.verifikasi_lokasi,b.catatan_profil,b.catatan_lokasi,
			b.asal_data,
			b.lintang, b.bujur,
			x1.nama as nama_pengguna_sekolah,
			x2.nama as nama_pengguna_dinas,
			x3.nama as sekolah_tujuan
						");
		$builder->join("tcg_peserta_didik b", "b.peserta_didik_id=a.peserta_didik_id AND b.is_deleted=0 AND b.tahun_ajaran_id=a.tahun_ajaran_id");
		$builder->join('ref_sekolah c','c.sekolah_id = b.sekolah_id AND c.is_deleted=0','LEFT OUTER');
		$builder->join('ref_wilayah d','d.kode_wilayah = b.kode_wilayah AND d.is_deleted=0','LEFT OUTER');
		$builder->join('dbo_users x1','x1.peserta_didik_id = a.pengguna_sekolah AND x1.is_deleted=0','LEFT OUTER');
		$builder->join('dbo_users x2','x2.peserta_didik_id = a.pengguna_dinas AND x2.is_deleted=0','LEFT OUTER');
		$builder->join('ref_sekolah x3','x3.sekolah_id = a.sekolah_id AND x3.is_deleted=0','LEFT OUTER');
		$builder->where("a.is_deleted", 0);
		$builder->where("a.verifikasi_id", $klarifikasi_id);

		return $builder->get();
	}

	function update($penerapan_id, $key, $value) {
		$query = "update ref_penerapan set $key='$value' where penerapan_id=$penerapan_id";
		return $this->db->query($query);
	}

	function ubah($klarifikasi_id, $verifikasi, $catatan) {
		$user_id = $this->session->get('user_id');
		
		$filter = array(
			'verifikasi_id' 	=> $klarifikasi_id,
			'is_deleted'		=> 0
		);

		$valuepair = array(
			'verifikasi'		=> $verifikasi,
			'catatan_dinas'		=> $catatan,
			'pengguna_dinas'	=> $user_id,
			'tanggal_verifikasi'	=> date("Y/m/d H:i:s"),
			'updated_on'		=> date("Y/m/d H:i:s")
		);

		$builder = $this->db->table('tcg_verifikasi_dinas');
        $builder->where($filter);
		$query = $builder->update($valuepair);
		if ($query) {
			if ($verifikasi == 1) {
				$detail = $this->detail($klarifikasi_id)->getRowArray();

				$peserta_didik_id = $detail['peserta_didik_id'];
				$tahun_ajaran_id = $detail['tahun_ajaran_id'];
				$tipe_data = $detail['tipe_data'];

				//update kelengkapan info
				$filter = array(
					'peserta_didik_id' 	=> $peserta_didik_id,
					'tahun_ajaran_id'	=> $tahun_ajaran_id,
					'is_deleted'		=> 0
				);

				$valuepair = array(
					'verifikasi_'.$tipe_data		=> 2,
					'updated_on'					=> date("Y/m/d H:i:s")
				);

                $builder = $this->db->table('tcg_peserta_didik');
                $builder->where($filter);
                $builder->update($valuepair);
			}

			return 1;
		}

		return 0;
	}

	function add($tahun_ajaran_id, $valuepair) {
		//inject tahun ajaran
		$valuepair['tahun_ajaran_id'] = $tahun_ajaran_id;

        $builder = $this->db->table('ref_penerapan');
		if ($builder->insert($valuepair)) {
			return $this->db->insertID();
		}

		return 0;
	}

	function baru($peserta_didik_id, $tipe_data, $kelengkapan_id, $catatan) {
		$user_id = $this->session->get('user_id');
		$sekolah_id = $this->session->get('sekolah_id');
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$valuepair = array(
			'peserta_didik_id' 	=> $peserta_didik_id,
			'tahun_ajaran_id'	=> $tahun_ajaran_id,
			'pengguna_sekolah'	=> $user_id,
			'sekolah_id'		=> $sekolah_id,
			'tipe_data'			=> $tipe_data,
			'daftar_kelengkapan_id'	=> $kelengkapan_id,
			'catatan_sekolah'	=> $catatan
		);

        $builder = $this->db->table('tcg_verifikasi_dinas');
		$query = $builder->insert($valuepair);
		if ($query) {
			$key = $this->db->insertID();
			return $key;
		}

		return 0;
	}

	function hapus($verifikasi_id) {
		//$user_id = $this->session->get('user_id');
		
		$filter = array(
			'verifikasi_id' 	=> $verifikasi_id,
			'is_deleted'		=> 0
		);

		$valuepair = array(
			'is_deleted'		=> 1,
			'updated_on'	=> date("Y/m/d H:i:s")
		);

        $builder = $this->db->table('tcg_verifikasi_dinas');
        $builder->where($filter);
		$query = $builder->update($valuepair);
		return 1;
	}

}