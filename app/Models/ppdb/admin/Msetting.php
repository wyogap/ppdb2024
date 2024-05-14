<?php 

namespace App\Models\Ppdb\Admin;


Class Msetting
{

    protected $db;
    protected $session;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

	function list() {
		$query = "select a.* from dbo_settings a where a.is_deleted=0 order by a.group desc, a.name";
		return $this->db->query($query);
	}

	function detail($key) {
		$query = "select a.* from dbo_settings a where a.setting_id=$key and a.is_deleted=0";
		return $this->db->query($query);
	}

	function value($group, $name) {
		$builder = $this->db->table('dbo_settings a');
		$builder->select('a.value');
		$builder->where(array('a.group'=>$group,'a.name'=>$name,'a.is_deleted'=>0));

		$value = "";
		foreach ($builder->get()->getResult() as $row) {
			$value = $row->value;
		}

		return $value;
	}

	function update($key, $valuepair) {
		//inject last update
		$valuepair['updated_on'] = date("Y/m/d H:i:s");

        $builder = $this->db->table('dbo_settings');
        $builder->where('setting_id', $key);
        $builder->update($valuepair);

        return 1;
    }

	// function tcg_waktupelaksanaan(){
	// 	$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
	// 	$putaran = $this->session->get('putaran_aktif');

	// 	$builder = $this->db->table('tcg_waktu_pelaksanaan a');
	// 	$builder->select('a.tanggal_mulai,a.tanggal_selesai, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah');
	// 	$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.tahapan_id'=>3,'a.soft_delete'=>0));
	// 	return $builder->get();
	// }

	function tcg_waktusosialisasi(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('tcg_waktu_pelaksanaan a');
		$builder->select('a.tanggal_mulai as tanggal_mulai_aktif,a.tanggal_selesai as tanggal_selesai_aktif, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah');
		$builder->select('case when a.tanggal_mulai < now() then 0 when a.tanggal_selesai > now() then 2 else 1 end as aktif');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.tahapan_id'=>TAHAPANID_SOSIALISASI,'a.soft_delete'=>0));
		return $builder->get();
	}

	function tcg_cek_waktusosialisasi(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "select count(*) as jumlah from tcg_waktu_pelaksanaan a 
				  where a.tahapan_id=" .TAHAPANID_SOSIALISASI. " and a.soft_delete=0 and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran' 
						and a.tanggal_mulai <= now() and a.tanggal_selesai >= now()";
		
		$dalamperiode=0;
		foreach($this->db->query($query)->getResult() as $row):
			$dalamperiode = $row->jumlah;
		endforeach;

		return $dalamperiode;
	}

	function tcg_wakturegistrasi(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('tcg_waktu_pelaksanaan a');
		$builder->select('a.tanggal_mulai as tanggal_mulai_aktif,a.tanggal_selesai as tanggal_selesai_aktif, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah');
		$builder->select('case when a.tanggal_mulai < now() then 0 when a.tanggal_selesai > now() then 2 else 1 end as aktif');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.tahapan_id'=>TAHAPANID_REGISTRASI,'a.soft_delete'=>0));
		return $builder->get();
	}

	function tcg_cek_wakturegistrasi(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "select count(*) as jumlah from tcg_waktu_pelaksanaan a 
				  where a.tahapan_id=" .TAHAPANID_REGISTRASI. " and a.soft_delete=0 and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran' 
						and a.tanggal_mulai <= now() and a.tanggal_selesai >= now()";
		
		$dalamperiode=0;
		foreach($this->db->query($query)->getResult() as $row):
			$dalamperiode = $row->jumlah;
		endforeach;

		return $dalamperiode;
	}

	function tcg_waktupendaftaran(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('tcg_waktu_pelaksanaan a');
		$builder->select('a.tanggal_mulai as tanggal_mulai_aktif,a.tanggal_selesai as tanggal_selesai_aktif, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah');
		$builder->select('case when a.tanggal_mulai < now() then 0 when a.tanggal_selesai > now() then 2 else 1 end as aktif');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.tahapan_id'=>TAHAPANID_PENDAFTARAN,'a.soft_delete'=>0));
		return $builder->get();
	}

	function tcg_cek_waktupendaftaran(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "select count(*) as jumlah from tcg_waktu_pelaksanaan a 
				  where a.tahapan_id=" .TAHAPANID_PENDAFTARAN. " and a.soft_delete=0 and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran' 
						and a.tanggal_mulai <= now() and a.tanggal_selesai >= now()";
		
		$dalamperiode=0;
		foreach($this->db->query($query)->getResult() as $row):
			$dalamperiode = $row->jumlah;
		endforeach;

		return $dalamperiode;
	}

	function tcg_waktuverifikasi(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('tcg_waktu_pelaksanaan a');
		$builder->select('a.tanggal_mulai as tanggal_mulai_aktif,a.tanggal_selesai as tanggal_selesai_aktif, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah');
		$builder->select('case when a.tanggal_mulai < now() then 0 when a.tanggal_selesai > now() then 2 else 1 end as aktif');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.tahapan_id'=>TAHAPANID_VERIFIKASI,'a.soft_delete'=>0));
		return $builder->get();
	}

	function tcg_cek_waktuverifikasi(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "select count(*) as jumlah from tcg_waktu_pelaksanaan a 
				  where a.tahapan_id=" .TAHAPANID_VERIFIKASI. " and a.soft_delete=0 and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran' 
						and a.tanggal_mulai <= now() and a.tanggal_selesai >= now()";
		
		$dalamperiode=0;
		foreach($this->db->query($query)->getResult() as $row):
			$dalamperiode = $row->jumlah;
		endforeach;

		return $dalamperiode;
	}

	function tcg_waktudaftarulang(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('tcg_waktu_pelaksanaan a');
		$builder->select('a.tanggal_mulai as tanggal_mulai_aktif,a.tanggal_selesai as tanggal_selesai_aktif, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah');
		$builder->select('case when a.tanggal_mulai < now() then 0 when a.tanggal_selesai > now() then 2 else 1 end as aktif');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.tahapan_id'=>TAHAPANID_DAFTARULANG,'a.soft_delete'=>0));
		return $builder->get();
	}

	function tcg_cek_waktudaftarulang(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "select count(*) as jumlah from tcg_waktu_pelaksanaan a 
				  where a.tahapan_id=" .TAHAPANID_DAFTARULANG. " and a.soft_delete=0 and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran' 
						and a.tanggal_mulai <= now() and a.tanggal_selesai >= now()";
		
		//echo $query;
		$dalamperiode=0;
		foreach($this->db->query($query)->getResult() as $row):
			$dalamperiode = $row->jumlah;
		endforeach;

		return $dalamperiode;
	}

	function tcg_waktupendaftaransusulan(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('tcg_waktu_pelaksanaan a');
		$builder->select('a.tanggal_mulai as tanggal_mulai_aktif,a.tanggal_selesai as tanggal_selesai_aktif, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah');
		$builder->select('case when a.tanggal_mulai < now() then 0 when a.tanggal_selesai > now() then 2 else 1 end as aktif');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.tahapan_id'=>TAHAPANID_SUSULAN,'a.soft_delete'=>0));
		return $builder->get();
	}

	function tcg_cek_waktupendaftaransusulan(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "select count(*) as jumlah from tcg_waktu_pelaksanaan a 
				  where a.tahapan_id=" .TAHAPANID_SUSULAN. " and a.soft_delete=0 and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran'
						and a.tanggal_mulai <= now() and a.tanggal_selesai >= now()";
		
		$dalamperiode=0;
		foreach($this->db->query($query)->getResult() as $row):
			$dalamperiode = $row->jumlah;
		endforeach;

		return $dalamperiode;
	}

	function tcg_batasanusia($bentuk_tujuan_sekolah){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
        if (empty($bentuk_tujuan_sekolah)) {
            $bentuk_tujuan_sekolah = $this->session->get('bentuk_sekolah_aktif');
        }

		$query = "select bentuk_tujuan_sekolah,minimal_tanggal_lahir,maksimal_tanggal_lahir 
				  from ref_batasan_usia
				  where expired_date is null and tahun_ajaran_id='$tahun_ajaran_id'";
		
		if (!empty($bentuk_tujuan_sekolah)) {
			$query .= " and bentuk_tujuan_sekolah='$bentuk_tujuan_sekolah'";
		}

		return $this->db->query($query);
	}

	function tcg_tahunajaran(){
		$builder = $this->db->table('ref_tahun_ajaran a');
		$builder->select('a.tahun_ajaran_id, a.nama');
		$builder->where(array('a.expired_date'=>NULL));

		return $builder->get();
	}

	function tcg_tahunajaran_aktif(){
		$builder = $this->db->table('dbo_settings a');
		$builder->select('a.value');
		$builder->where(array('a.is_deleted'=>0,'a.group'=>'ppdb','a.name'=>'tahun_ajaran'));

		$tahun_ajaran=2020;
		foreach($builder->get()->getResult() as $row):
			$tahun_ajaran = $row->value;
		endforeach;

		return $tahun_ajaran;
	}

	function tcg_kodewilayah_aktif() {
		$builder = $this->db->table('dbo_settings a');
		$builder->select('a.value');
		$builder->where(array('a.is_deleted'=>0,'a.group'=>'ppdb','a.name'=>'kode_wilayah'));

		$value="030500";
		foreach($builder->get()->getResult() as $row):
			$value = $row->value;
		endforeach;

		return $value;
	}

	function tcg_cek_captcha() {
		$builder = $this->db->table('dbo_settings a');
		$builder->select('a.value');
		$builder->where(array('a.is_deleted'=>0,'a.group'=>'ppdb','a.name'=>'use_captcha'));

		$value=1;
		foreach($builder->get()->getResult() as $row):
			$value = $row->value;
		endforeach;

		return $value;
	}

	function tcg_nama_dokumenpendukung($dokumen_id) {
		$builder = $this->db->table('ref_daftar_kelengkapan a');
		$builder->select('a.nama');
		$builder->join('tcg_dokumen_pendukung b','a.daftar_kelengkapan_id = b.daftar_kelengkapan_id and b.soft_delete = 0');
		$builder->where(array('a.expired_date'=>NULL,'b.dokumen_id'=>$dokumen_id));

		$nama = "";
		foreach ($builder->get()->getResult() as $row) {
			$nama = $row->nama;
		}

		return $nama;
	}

	function tcg_nama_daftarkelengkapan($kelengkapan_id) {
		$builder = $this->db->table('ref_daftar_kelengkapan a');
		$builder->select('a.nama');
		$builder->where(array('a.expired_date'=>NULL,'a.daftar_kelengkapan_id'=>$kelengkapan_id));

		$nama = "";
		foreach ($builder->get()->getResult() as $row) {
			$nama = $row->nama;
		}

		return $nama;
	}


	function tcg_nama_wilayah($kode_wilayah) {
		$builder = $this->db->table('ref_wilayah a');
		$builder->select('a.nama');
		$builder->where(array('a.kode_wilayah'=>$kode_wilayah,'a.expired_date'=>NULL));

		$value = "";
		foreach ($builder->get()->getResult() as $row) {
			$value = $row->nama;
		}

		return $value;
	}

	function tcg_nama_tahunajaran($tahun_ajaran_id) {
		$builder = $this->db->table('ref_tahun_ajaran a');
		$builder->select('a.nama');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.expired_date'=>NULL));

		$value = "";
		foreach ($builder->get()->getResult() as $row) {
			$value = $row->nama;
		}

		return $value;
	}

	function tcg_putaran_aktif(){
		$builder = $this->db->table('dbo_settings a');
		$builder->select('a.value');
		$builder->where(array('a.is_deleted'=>0,'a.group'=>'ppdb','a.name'=>'putaran'));

		$tahun_ajaran=2020;
		foreach($builder->get()->getResult() as $row):
			$tahun_ajaran = $row->value;
		endforeach;

		return $tahun_ajaran;
	}

	function tcg_nama_putaran($putaran) {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$builder = $this->db->table('ref_putaran a');
		$builder->select('a.nama');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.expired_date'=>NULL));

		$value = "";
		foreach ($builder->get()->getResult() as $row) {
			$value = $row->nama;
		}

		return $value;
	}

	function tcg_putaran() {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$builder = $this->db->table('ref_putaran a');
		$builder->select('a.putaran_id, a.putaran, coalesce(a.nama, a.tahun_ajaran_id) as nama');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.expired_date'=>NULL));

		return $builder->get();
	}

    //--
    
	function tcg_batasanperubahan(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

        //Tidak per putaran!!!
		$builder = $this->db->table('ref_batasan_perubahan');
		$builder->select('cabut_berkas,hapus_pendaftaran,ubah_pilihan,ubah_sekolah,ubah_jalur,batal_verifikasi');
		$builder->where(array('expired_date'=>NULL, 'tahun_ajaran_id'=>$tahun_ajaran_id));
		return $builder->get();
	}

	function tcg_petunjuk_pelaksanaan() {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

        //Tidak per putaran!!!
		$builder = $this->db->table('tcg_petunjuk_pelaksanaan a');
		$builder->select('a.jadwal_pelaksanaan,a.persyaratan,a.tata_cara_pendaftaran,a.jalur_pendaftaran,a.proses_seleksi,a.konversi_nilai,a.embedded_script');
		$builder->where(array('a.soft_delete'=>0,'a.tahun_ajaran_id'=>$tahun_ajaran_id));

		return $builder->get();
	}

	function tcg_tahapan_pelaksanaan(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

        //Semua tahapan (semua putaran)!!!
		$query = "select a.putaran, c.nama as nama_putaran, a.tahapan_id, b.nama as tahapan, a.tanggal_mulai, a.tanggal_selesai 
				  from tcg_waktu_pelaksanaan a
				  join ref_tahapan b on a.tahapan_id=b.tahapan_id and b.expired_date is null
                  join ref_putaran c on c.putaran_id=a.putaran and c.expired_date is null
				  where a.tahun_ajaran_id=? and a.soft_delete=0
				  order by a.putaran, a.tahapan_id";

		return $this->db->query($query, array($tahun_ajaran_id));
	}

	function tcg_tahapan_pelaksanaan_aktif(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "select a.tahapan_id, b.nama as tahapan, a.tanggal_mulai, a.tanggal_selesai, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah 
				  from tcg_waktu_pelaksanaan a
				  join ref_tahapan b on a.tahapan_id=b.tahapan_id and b.expired_date is null
				  where a.tahun_ajaran_id=? and a.putaran=? and a.soft_delete=0
						and (a.tanggal_mulai <= now() or a.tanggal_mulai is null)
						and (a.tanggal_selesai >= now() or a.tanggal_selesai is null)
				  order by a.tahapan_id asc";

		return $this->db->query($query, array($tahun_ajaran_id, $putaran));
	}

	function tcg_setting($group, $name) {
		$builder = $this->db->table('dbo_settings a');
		$builder->select('a.value');
		$builder->where(array('a.group'=>$group,'a.name'=>$name,'a.is_deleted'=>0));

		$value = "";
		foreach ($builder->get()->getResult() as $row) {
			$value = $row->value;
		}

		return $value;
	}

	function tcg_pengumuman(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$query = "select a.tipe, a.css, a.text, a.bisa_ditutup 
				  from tcg_pengumuman a
				  where a.tahun_ajaran_id=? and a.soft_delete=0
						and (a.tanggal_mulai <= now() or a.tanggal_mulai is null)
						and (a.tanggal_selesai >= now() or a.tanggal_selesai is null)
				  order by a.tanggal_mulai asc";

		return $this->db->query($query, array($tahun_ajaran_id));
	}

	function tcg_upload_dokumen() {
		$builder = $this->db->table('dbo_settings a');
		$builder->select('a.value');
		$builder->where(array('a.is_deleted'=>0,'a.group'=>'ppdb','a.name'=>'upload_dokumen'));

		$value="1";
		foreach($builder->get()->getResult() as $row):
			$value = $row->value;
		endforeach;

		return $value;
	}

    function tcg_daftarpilihan(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

        $builder = $this->db->table('ref_jenis_pilihan');
		$builder->select('*');
		$builder->where(array('pendaftaran'=>1,'expired_date'=>NULL,'tahun_ajaran_id'=>$tahun_ajaran_id,'putaran'=>$putaran));
        return $builder->get();
	}
}