<?php 

namespace App\Models\Ppdb;

Class Mconfig 
{
    protected $db;
    protected $session;
    protected $tahun_ajaran_id;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");
    }

	function tcg_lookup_jenjang() {
		$query = "
		select a.jenjang_id as value, a.nama as label
		from ref_jenjang a
		order by a.urutan asc";

		return $this->db->query($query)->getResultArray();
	}

	function tcg_lookup_asaldata() {
		$query = "
		select a.value, a.label
		from dbo_lookups a
        where a.group='asaldata'
		order by a.order_no asc";

		return $this->db->query($query)->getResultArray();
	}

	function tcg_waktusosialisasi(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$builder = $this->db->table('cfg_waktu_pelaksanaan a');
		$builder->select('a.tanggal_mulai as tanggal_mulai_aktif,a.tanggal_selesai as tanggal_selesai_aktif, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah');
		$builder->select('case when a.tanggal_mulai < now() and a.tanggal_selesai < now() then 0 
                                when a.tanggal_mulai > now() and a.tanggal_selesai > now() then 2 
                                else 1 end as aktif');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.tahapan_id'=>TAHAPANID_SOSIALISASI,'a.is_deleted'=>0));
		return $builder->get()->getRowArray();
	}

	function tcg_cek_waktusosialisasi(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "select count(*) as jumlah from cfg_waktu_pelaksanaan a 
				  where a.tahapan_id=" .TAHAPANID_SOSIALISASI. " and a.is_deleted=0 and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran' 
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

		$builder = $this->db->table('cfg_waktu_pelaksanaan a');
		$builder->select('a.tanggal_mulai as tanggal_mulai_aktif,a.tanggal_selesai as tanggal_selesai_aktif, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah');
		$builder->select('case when a.tanggal_mulai < now() and a.tanggal_selesai < now() then 0 
                                when a.tanggal_mulai > now() and a.tanggal_selesai > now() then 2 
                                else 1 end as aktif');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.tahapan_id'=>TAHAPANID_REGISTRASI,'a.is_deleted'=>0));
		return $builder->get()->getRowArray();
	}

	function tcg_cek_wakturegistrasi(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "select count(*) as jumlah from cfg_waktu_pelaksanaan a 
				  where a.tahapan_id=" .TAHAPANID_REGISTRASI. " and a.is_deleted=0 and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran' 
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

		$builder = $this->db->table('cfg_waktu_pelaksanaan a');
		$builder->select('a.tanggal_mulai as tanggal_mulai_aktif,a.tanggal_selesai as tanggal_selesai_aktif, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah');
		$builder->select('case when a.tanggal_mulai < now() and a.tanggal_selesai < now() then 0 
                                when a.tanggal_mulai > now() and a.tanggal_selesai > now() then 2 
                                else 1 end as aktif');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.tahapan_id'=>TAHAPANID_PENDAFTARAN,'a.is_deleted'=>0));
		return $builder->get()->getRowArray();
	}

	function tcg_cek_waktupendaftaran(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "select count(*) as jumlah from cfg_waktu_pelaksanaan a 
				  where a.tahapan_id=" .TAHAPANID_PENDAFTARAN. " and a.is_deleted=0 and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran' 
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

		$builder = $this->db->table('cfg_waktu_pelaksanaan a');
		$builder->select('a.tanggal_mulai as tanggal_mulai_aktif,a.tanggal_selesai as tanggal_selesai_aktif, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah');
		$builder->select('case when a.tanggal_mulai < now() and a.tanggal_selesai < now() then 0 
                                when a.tanggal_mulai > now() and a.tanggal_selesai > now() then 2 
                                else 1 end as aktif');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.tahapan_id'=>TAHAPANID_VERIFIKASI,'a.is_deleted'=>0));
		return $builder->get()->getRowArray();
	}

	function tcg_cek_waktuverifikasi(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "select count(*) as jumlah from cfg_waktu_pelaksanaan a 
				  where a.tahapan_id=" .TAHAPANID_VERIFIKASI. " and a.is_deleted=0 and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran' 
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

		$builder = $this->db->table('cfg_waktu_pelaksanaan a');
		$builder->select('a.tanggal_mulai as tanggal_mulai_aktif,a.tanggal_selesai as tanggal_selesai_aktif, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah');
		$builder->select('case when a.tanggal_mulai < now() and a.tanggal_selesai < now() then 0 
                                when a.tanggal_mulai > now() and a.tanggal_selesai > now() then 2 
                                else 1 end as aktif');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.tahapan_id'=>TAHAPANID_DAFTARULANG,'a.is_deleted'=>0));
		return $builder->get()->getRowArray();
	}

	function tcg_cek_waktudaftarulang(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "select count(*) as jumlah from cfg_waktu_pelaksanaan a 
				  where a.tahapan_id=" .TAHAPANID_DAFTARULANG. " and a.is_deleted=0 and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran' 
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

		$builder = $this->db->table('cfg_waktu_pelaksanaan a');
		$builder->select('a.tanggal_mulai as tanggal_mulai_aktif,a.tanggal_selesai as tanggal_selesai_aktif, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah');
		$builder->select('case when a.tanggal_mulai < now() and a.tanggal_selesai < now() then 0 
                                when a.tanggal_mulai > now() and a.tanggal_selesai > now() then 2 
                                else 1 end as aktif');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.tahapan_id'=>TAHAPANID_SUSULAN,'a.is_deleted'=>0));
		return $builder->get()->getRowArray();
	}

	function tcg_cek_waktupendaftaransusulan(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "select count(*) as jumlah from cfg_waktu_pelaksanaan a 
				  where a.tahapan_id=" .TAHAPANID_SUSULAN. " and a.is_deleted=0 and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran'
						and a.tanggal_mulai <= now() and a.tanggal_selesai >= now()";
		
		$dalamperiode=0;
		foreach($this->db->query($query)->getResult() as $row):
			$dalamperiode = $row->jumlah;
		endforeach;

		return $dalamperiode;
	}


	function tcg_waktupendaftaran_sd(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$builder = $this->db->table('cfg_waktu_pelaksanaan a');
		$builder->select('a.tanggal_mulai as tanggal_mulai_aktif,a.tanggal_selesai as tanggal_selesai_aktif, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah');
		$builder->select('case when a.tanggal_mulai < now() and a.tanggal_selesai < now() then 0 
                                when a.tanggal_mulai > now() and a.tanggal_selesai > now() then 2 
                                else 1 end as aktif');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>PUTARAN_SD,'a.tahapan_id'=>TAHAPANID_PENDAFTARAN,'a.is_deleted'=>0));
		return $builder->get()->getRowArray();
	}

	function tcg_cek_waktupendaftaran_sd(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = PUTARAN_SD;

		$query = "select count(*) as jumlah from cfg_waktu_pelaksanaan a 
				  where a.tahapan_id=" .TAHAPANID_PENDAFTARAN. " and a.is_deleted=0 and a.tahun_ajaran_id='$tahun_ajaran_id' and a.putaran='$putaran'
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
				  from cfg_batasan_usia
				  where expired_date is null and tahun_ajaran_id='$tahun_ajaran_id'";
		
		if (!empty($bentuk_tujuan_sekolah)) {
			$query .= " and bentuk_tujuan_sekolah='$bentuk_tujuan_sekolah'";
		}

		return $this->db->query($query)->getRowArray();
	}

    
	function tcg_batasanperubahan(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

        //Tidak per putaran!!!
		$builder = $this->db->table('cfg_batasan_perubahan');
		$builder->select('cabut_berkas,hapus_pendaftaran,ubah_pilihan,ubah_sekolah,ubah_jalur,batal_verifikasi');
		$builder->where(array('expired_date'=>NULL, 'tahun_ajaran_id'=>$tahun_ajaran_id));
		return $builder->get()->getRowArray();
	}

	function tcg_petunjuk_pelaksanaan() {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

        //Tidak per putaran!!!
		$builder = $this->db->table('cfg_petunjuk_pelaksanaan a');
		$builder->select('a.jadwal_pelaksanaan,a.persyaratan,a.tata_cara_pendaftaran,a.jalur_pendaftaran,a.proses_seleksi,a.konversi_nilai,a.embedded_script');
		$builder->where(array('a.is_deleted'=>0,'a.tahun_ajaran_id'=>$tahun_ajaran_id));

		return $builder->get()->getRowArray();
	}

	function tcg_putaran() {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

        //Tidak per putaran!!!
		$builder = $this->db->table('cfg_putaran a');
		$builder->select('a.*');
		$builder->where(array('a.is_deleted'=>0,'a.tahun_ajaran_id'=>$tahun_ajaran_id));

		return $builder->get()->getResultArray();
	}

	function tcg_tahapan_pelaksanaan($putaran=null){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
        if ($putaran == null) {
            $putaran = $this->session->get("putaran_aktif");
        }

        //Semua tahapan (semua putaran)!!!
		$query = "select a.putaran, c.nama as nama_putaran, a.tahapan_id, b.nama as tahapan, a.tanggal_mulai, a.tanggal_selesai 
				  from cfg_waktu_pelaksanaan a
				  join ref_tahapan b on a.tahapan_id=b.tahapan_id and b.is_deleted=0
                  join cfg_putaran c on c.putaran_id=a.putaran and c.is_deleted=0
				  where a.tahun_ajaran_id=? and a.putaran=? and a.is_deleted=0
				  order by a.putaran, a.tahapan_id";

		return $this->db->query($query, array($tahun_ajaran_id, $putaran))->getResultArray();
	}

	function tcg_pengumuman(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$query = "select a.tipe, a.css, a.text, a.bisa_ditutup 
				  from cfg_pengumuman a
				  where a.tahun_ajaran_id=? and a.is_deleted=0
						and (a.tanggal_mulai = 0 or a.tanggal_mulai <= now() or a.tanggal_mulai is null)
						and (a.tanggal_selesai = 0 or a.tanggal_selesai >= now() or a.tanggal_selesai is null)
				  order by a.tanggal_mulai asc";

		return $this->db->query($query, array($tahun_ajaran_id))->getResultArray();
	}

    function tcg_daftarpilihan(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

        $builder = $this->db->table('cfg_jenis_pilihan');
		$builder->select('*');
		$builder->where(array('pendaftaran'=>1,'expired_date'=>NULL,'tahun_ajaran_id'=>$tahun_ajaran_id,'putaran'=>$putaran));
        return $builder->get()->getResultArray();
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

	function tcg_nama_putaran($putaran) {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

		$builder = $this->db->table('cfg_putaran a');
		$builder->select('a.nama');
		$builder->where(array('a.tahun_ajaran_id'=>$tahun_ajaran_id,'a.putaran'=>$putaran,'a.expired_date'=>NULL));

		$value = "";
		foreach ($builder->get()->getResult() as $row) {
			$value = $row->nama;
		}

		return $value;
	}

	function tcg_tahapan_pelaksanaan_aktif(){
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');
		$putaran = $this->session->get('putaran_aktif');

		$query = "select a.tahapan_id, b.nama as tahapan, a.tanggal_mulai, a.tanggal_selesai, a.notifikasi_umum, a.notifikasi_siswa, a.notifikasi_sekolah 
				  from cfg_waktu_pelaksanaan a
				  join ref_tahapan b on a.tahapan_id=b.tahapan_id and b.is_deleted=0
				  where a.tahun_ajaran_id=? and a.putaran=? and a.is_deleted=0
						and (a.tanggal_mulai <= now() or a.tanggal_mulai is null)
						and (a.tanggal_selesai >= now() or a.tanggal_selesai is null)
				  order by a.tahapan_id asc";

		return $this->db->query($query, array($tahun_ajaran_id, $putaran))->getResultArray();
	}

    function tcg_lookup_daftarskoring_prestasi() {
		$tahun_ajaran_id = $this->session->get('tahun_ajaran_aktif');

        $query = "select a.skoring_id as value, b.nama as label 
                  from cfg_daftar_nilai_skoring a 
                  join ref_daftar_skoring b on b.skoring_id=a.skoring_id and b.is_deleted=0
                  where a.tahun_ajaran_id=? and b.kunci=0 and b.jalur_id=" .JALURID_PRESTASI. "
                  order by b.urutan";

        return $this->db->query($query, array($tahun_ajaran_id))->getResultArray();
    }

    function tcg_provinsi(){
		$builder = $this->db->table('ref_wilayah a');
		$builder->select('CONVERT(a.kode_wilayah,CHAR(6)) AS kode_wilayah,a.nama AS provinsi');
		$builder->where(array('a.id_level_wilayah'=>1,'a.expired_date'=>NULL));
		$builder->orderBy('a.nama');
		return $builder->get()->getResultArray();
	}

    function tcg_kabupaten($kode_wilayah = null){
		$builder = $this->db->table('ref_wilayah a');
		$builder->select('CONVERT(a.kode_wilayah,CHAR(6)) AS kode_wilayah,a.nama AS kabupaten,b.nama AS provinsi');
		$builder->join('ref_wilayah b','a.mst_kode_wilayah = b.kode_wilayah AND b.is_deleted=0 AND b.id_level_wilayah = 1');
		$builder->where(array('a.id_level_wilayah'=>2,'a.expired_date'=>NULL));
        if (!empty($kode_wilayah)) {
            $builder->where('a.mst_kode_wilayah', $kode_wilayah);
        }
		$builder->orderBy('b.nama','a.nama');
		return $builder->get()->getResultArray();
	}

	function tcg_kecamatan($kode_wilayah){
		if (empty($kode_wilayah))
			$kode_wilayah = $this->session->get("kode_wilayah_aktif");
 
        $builder = $this->db->table('ref_wilayah');
        $builder->select('CONVERT(kode_wilayah,CHAR(6)) AS kode_wilayah,nama');
		$builder->where(array('id_level_wilayah'=>3,'expired_date'=>NULL,'mst_kode_wilayah'=>$kode_wilayah));
		$builder->orderBy('nama');
		return $builder->get()->getResultArray();
	}

	function tcg_desa($kode_wilayah_kec){
		$builder = $this->db->table('ref_wilayah');
		$builder->select('CONVERT(kode_wilayah,CHAR(8)) AS kode_wilayah,nama');
		$builder->where(array('id_level_wilayah'=>4,'expired_date'=>NULL,'mst_kode_wilayah'=>$kode_wilayah_kec));
		$builder->orderBy('nama');
		return $builder->get()->getResultArray();
	}

	function tcg_padukuhan($kode_wilayah_desa){
		$builder = $this->db->table('ref_wilayah');
		$builder->select('CONVERT(kode_wilayah,CHAR(10)) AS kode_wilayah,nama');
		$builder->where(array('id_level_wilayah'=>5,'expired_date'=>NULL,'mst_kode_wilayah'=>$kode_wilayah_desa));
		$builder->orderBy('nama');
		return $builder->get()->getResultArray();
	}

	function tcg_sekolah($kode_wilayah, $bentuk){
		$builder = $this->db->table('ref_sekolah');
		$builder->select('sekolah_id,npsn,nama');
		$builder->where('bentuk',$bentuk);
		$builder->where('LEFT(kode_wilayah,4)',substr($kode_wilayah,0,4),true);
		$builder->orderBy('nama');

        //$sql = $builder->getCompiledSelect(); echo $sql; exit;

		return $builder->get()->getResultArray();
	}

	function tcg_sekolah_smp($kode_wilayah = null) {
		if (empty($kode_wilayah))
			$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		$builder = $this->db->table('ref_sekolah');
		$builder->select('sekolah_id,nama,npsn');
		$builder->where(array('bentuk'=>'SMP','expired_date'=>NULL,'kode_wilayah_kab'=>$kode_wilayah));
		$builder->orderBy('kode_wilayah, nama');
		return $builder->get()->getResultArray();
	
	}

	function tcg_sekolah_sd_mi($kode_wilayah = null) {
		if (empty($kode_wilayah))
			$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		$sql = "select sekolah_id,nama,npsn 
		from ref_sekolah 
		where expired_date is null and kode_wilayah_kab=? and (bentuk='SD' or bentuk='MI')
		order by kode_wilayah, nama";

		return $this->db->query($sql, array($kode_wilayah))->getResultArray();
	}

	function tcg_sekolah_tk_ra($kode_wilayah = null) {
		if (empty($kode_wilayah))
			$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		$sql = "select sekolah_id,nama,npsn 
		from ref_sekolah 
		where expired_date is null and kode_wilayah_kab=? and bentuk in ('TK', 'RA', 'PAUD', 'KB', 'SPS', 'SKB')
		order by kode_wilayah, nama";

		return $this->db->query($sql, array($kode_wilayah))->getResultArray();
	}

}