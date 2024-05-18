<?php 

namespace App\Models\Ppdb;

Class Mdropdown 
{
    protected $db;
    protected $session;
    protected $tahun_ajaran_id;

    function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");
    }

	// function peran(){
	// 	$builder->select('peran_id,nama');
	// 	$builder = $this->db->table('ref_peran');
	// 	$builder->where('expired_date',NULL);
	// 	$builder->orderBy('urutan');
	// 	return $builder->get();
	// }
	// function kabupaten(){
	// 	$builder->select('CONVERT(a.kode_wilayah,CHAR(6)) AS kode_wilayah,a.nama AS kabupaten,b.nama AS provinsi');
	// 	$builder = $this->db->table('ref_wilayah a');
	// 	$builder->join('ref_wilayah b','a.mst_kode_wilayah = b.kode_wilayah AND b.is_deleted=0 AND b.id_level_wilayah = 1');
	// 	$builder->where(array('a.id_level_wilayah'=>2,'a.expired_date'=>NULL));
	// 	$builder->orderBy('b.nama','a.nama');
	// 	return $builder->get();
	// }
	// function sekolahmadrasah(){
	// 	$kode_wilayah = $this->input->post("kode_wilayah", TRUE);
	// 	$bentuk = $this->input->post("bentuk", TRUE);
	// 	$builder->select('sekolah_id,npsn,nama');
	// 	$builder = $this->db->table('ref_sekolah');
	// 	$builder->where('bentuk',$bentuk);
	// 	$builder->where('LEFT(kode_wilayah,4)',substr($kode_wilayah,0,4),true);
	// 	$builder->orderBy('nama');
	// 	return $builder->get();
	// }
	// function kecamatan(){
	// 	$kode_wilayah = $this->input->post("kode_wilayah", TRUE);
	// 	$builder->select('CONVERT(kode_wilayah,CHAR(6)) AS kode_wilayah,nama');
	// 	$builder = $this->db->table('ref_wilayah');
	// 	$builder->where(array('id_level_wilayah'=>3,'expired_date'=>NULL,'mst_kode_wilayah'=>$kode_wilayah));
	// 	$builder->orderBy('nama');
	// 	return $builder->get();
	// }
	// function desa(){
	// 	$kode_wilayah = $this->input->post("kode_wilayah", TRUE);
	// 	$builder->select('CONVERT(kode_wilayah,CHAR(8)) AS kode_wilayah,nama');
	// 	$builder = $this->db->table('ref_wilayah');
	// 	$builder->where(array('id_level_wilayah'=>4,'expired_date'=>NULL,'mst_kode_wilayah'=>$kode_wilayah));
	// 	$builder->orderBy('nama');
	// 	return $builder->get();
	// }
	// function padukuhan(){
	// 	$kode_wilayah = $this->input->post("kode_wilayah", TRUE);
	// 	$builder->select('CONVERT(kode_wilayah,CHAR(10)) AS kode_wilayah,nama');
	// 	$builder = $this->db->table('ref_wilayah');
	// 	$builder->where(array('id_level_wilayah'=>5,'expired_date'=>NULL,'mst_kode_wilayah'=>$kode_wilayah));
	// 	$builder->orderBy('nama');
	// 	return $builder->get();
	// }	

	// // function daftarnilaiskoringprestasi(){
	// // 	$penerapan_id = $this->input->post("penerapan_id", TRUE);
	// // 	if($penerapan_id==""){
	// // 		$penerapan_id = $this->input->get("penerapan_id", TRUE);
	// // 	}
	// // 	$builder->select('b.daftar_nilai_skoring_id,a.nama AS jenis,b.nama AS keterangan,b.nilai');
	// // 	$builder = $this->db->table('ref_daftar_skoring a');
	// // 	$builder->join('ref_daftar_nilai_skoring b','a.daftar_skoring_id = b.daftar_skoring_id AND b.is_deleted=0');
	// // 	$builder->join('ref_penerapan c','b.penerapan_id = c.penerapan_id AND c.aktif = 1 AND c.is_deleted=0');
	// // 	$builder->where(array('a.expired_date'=>NULL,'a.manual'=>1,'c.kategori_prestasi'=>1,'b.penerapan_id'=>$penerapan_id));
	// // 	$builder->orderBy('b.nilai ASC','a.nama ASC');
	// // 	return $builder->get();
	// // }

	/* --
	**/

	function tcg_kabupaten(){
		$builder = $this->db->table('ref_wilayah a');
		$builder->select('CONVERT(a.kode_wilayah,CHAR(6)) AS kode_wilayah,a.nama AS kabupaten,b.nama AS provinsi');
		$builder->join('ref_wilayah b','a.mst_kode_wilayah = b.kode_wilayah AND b.is_deleted=0 AND b.id_level_wilayah = 1');
		$builder->where(array('a.id_level_wilayah'=>2,'a.expired_date'=>NULL));
		$builder->orderBy('b.nama','a.nama');
		return $builder->get();
	}

	function tcg_kecamatan($kode_wilayah){
		if (empty($kode_wilayah))
			$kode_wilayah = $this->session->get("kode_wilayah_aktif");
 
        $builder = $this->db->table('ref_wilayah');
        $builder->select('CONVERT(kode_wilayah,CHAR(6)) AS kode_wilayah,nama');
		$builder->where(array('id_level_wilayah'=>3,'expired_date'=>NULL,'mst_kode_wilayah'=>$kode_wilayah));
		$builder->orderBy('nama');
		return $builder->get();
	}

	function tcg_desa($kode_wilayah_kec){
		$builder = $this->db->table('ref_wilayah');
		$builder->select('CONVERT(kode_wilayah,CHAR(8)) AS kode_wilayah,nama');
		$builder->where(array('id_level_wilayah'=>4,'expired_date'=>NULL,'mst_kode_wilayah'=>$kode_wilayah_kec));
		$builder->orderBy('nama');
		return $builder->get();
	}

	function tcg_padukuhan($kode_wilayah_desa){
		$builder = $this->db->table('ref_wilayah');
		$builder->select('CONVERT(kode_wilayah,CHAR(10)) AS kode_wilayah,nama');
		$builder->where(array('id_level_wilayah'=>5,'expired_date'=>NULL,'mst_kode_wilayah'=>$kode_wilayah_desa));
		$builder->orderBy('nama');
		return $builder->get();
	}

	function tcg_sekolah($kode_wilayah, $bentuk = null) {
		if (empty($kode_wilayah))
			$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		$builder = $this->db->table('ref_sekolah');
		$builder->select('sekolah_id,nama,npsn');
        if ($bentuk != null) {
            $builder->where('bentuk',$bentuk);
        }
        else {
            $builder->where("bentuk in ('SD','MI','SMP')");
        }
        $builder->where('LEFT(kode_wilayah,4)',substr($kode_wilayah,0,4));
        $builder->where('expired_date is NULL');
		$builder->orderBy('kode_wilayah, nama');
		return $builder->get();
	}

	function tcg_sekolah_smp($kode_wilayah) {
		if (empty($kode_wilayah))
			$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		$builder = $this->db->table('ref_sekolah');
		$builder->select('sekolah_id,nama,npsn');
		$builder->where(array('bentuk'=>'SMP','expired_date'=>NULL,'kode_wilayah_kab'=>$kode_wilayah));
		$builder->orderBy('kode_wilayah, nama');
		return $builder->get();
	
	}

	function tcg_sekolah_sd_mi($kode_wilayah) {
		if (empty($kode_wilayah))
			$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		$sql = "select sekolah_id,nama,npsn 
		from ref_sekolah 
		where expired_date is null and kode_wilayah_kab=? and (bentuk='SD' or bentuk='MI')
		order by kode_wilayah, nama";

		return $this->db->query($sql, array($kode_wilayah));
	
	}

	function tcg_sekolah_tk_ra($kode_wilayah) {
		if (empty($kode_wilayah))
			$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		$sql = "select sekolah_id,nama,npsn 
		from ref_sekolah 
		where expired_date is null and kode_wilayah_kab=? and (bentuk='TK' or bentuk='RA')
		order by kode_wilayah, nama";

		return $this->db->query($sql, array($kode_wilayah));
	
	}

	// function tcg_sekolah($kode_wilayah) {
	// 	if (empty($kode_wilayah))
	// 		$kode_wilayah = $this->session->get("kode_wilayah_aktif");

	// 	$sql = "select sekolah_id,nama,npsn 
	// 	from ref_sekolah 
	// 	where expired_date is null and kode_wilayah_kab=? and (bentuk='SD' or bentuk='MI' or bentuk='SMP')
	// 	order by nama";

	// 	return $this->db->query($sql, array($kode_wilayah));
	
	// }

	function tcg_sekolah_smp_ppdb($kode_wilayah) {
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");
		if (empty($kode_wilayah))
			$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		$sql = "select a.sekolah_id, a.nama, a.npsn 
				from ref_sekolah  a
				join dbo_kuota_sekolah b on a.sekolah_id=b.sekolah_id and b.is_deleted=0
				where a.is_deleted=0 and a.kode_wilayah_kab=? and (a.bentuk='SMP') and b.ikut_ppdb=1
				group by a.sekolah_id, a.nama
				order by a.kode_wilayah, a.nama";

		return $this->db->query($sql, array($kode_wilayah));	
	
	}

	function tcg_penerapan() {
		$tahun_ajaran_id = $this->session->get("tahun_ajaran_aktif");

		$sql = "select penerapan_id, nama
		from ref_penerapan 
		where expired_date is null and tahun_ajaran_id=?
		order by urutan";

		return $this->db->query($sql, array($tahun_ajaran_id));
	
	}

	function tcg_select_sekolah($kode_wilayah) {
		if (empty($kode_wilayah))
			$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		$sql = "
		with data as (
			select 0 as value, '-- Pilih Sekolah / Tidak Ada --' as label
		
			union all
			
			(
				select sekolah_id as value, concat('(',npsn,') ', nama) as label 
				from ref_sekolah 
				where expired_date is null and kode_wilayah_kab=? and (bentuk='SD' or bentuk='MI' or bentuk='SMP')
				order by kode_wilayah, nama
			)
		)
		select * from data";

		return $this->db->query($sql, array($kode_wilayah));	
	}

	function tcg_select_smp($kode_wilayah) {
		if (empty($kode_wilayah))
			$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		$sql = "
		with data as (
			select 0 as value, '-- Pilih Sekolah / Tidak Ada --' as label
		
			union all
			
			(
				select sekolah_id as value, concat('(',npsn,') ', nama) as label 
				from ref_sekolah 
				where expired_date is null and kode_wilayah_kab=? and (bentuk='SMP')
				order by kode_wilayah, nama
			)
		)
		select * from data";

		return $this->db->query($sql, array($kode_wilayah));	
	}

	function tcg_select_smp_ppdb($kode_wilayah) {
		if (empty($kode_wilayah))
			$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		$sql = "
		with data as (
			select 0 as value, '-- Pilih Sekolah / Tidak Ada --' as label
		
			union all
			
			(
				select a.sekolah_id as value, concat('(',a.npsn,') ', a.nama) as label 
				from ref_sekolah  a
				join dbo_kuota_sekolah b on a.sekolah_id=b.sekolah_id and b.is_deleted=0
				where a.is_deleted=0 and a.kode_wilayah_kab=? and (a.bentuk='SMP') and b.ikut_ppdb=1
				group by a.sekolah_id, a.nama
				order by a.kode_wilayah, a.nama
			)
		)
		select * from data";

		return $this->db->query($sql, array($kode_wilayah));	
	}

	function tcg_select_sd_mi($kode_wilayah) {
		if (empty($kode_wilayah))
			$kode_wilayah = $this->session->get("kode_wilayah_aktif");

		$sql = "
		with data as (
			select 0 as value, '-- Pilih Sekolah / Tidak Ada --' as label
		
			union all
			
			(
				select sekolah_id as value, concat('(',npsn,') ', nama) as label 
				from ref_sekolah 
				where expired_date is null and kode_wilayah_kab=? and (bentuk='SD' or bentuk='MI')
				order by kode_wilayah, nama
			)
		)
		select * from data";

		return $this->db->query($sql, array($kode_wilayah));	
	}

	function tcg_peran() {
		$sql = "select *
				from ref_peran
				where expired_date is null 
				order by urutan";

		return $this->db->query($sql);	
	}

	function tcg_select_peran() {
		$sql = "
		with data as (
			select 0 as value, '-- Pilih Peran --' as label
		
			union all
			
			(
				select peran_id as value, nama as label 
				from ref_peran
				where expired_date is null 
				order by urutan
			)
		)
		select * from data";

		return $this->db->query($sql);	
	}

	function tcg_skoringprestasi($penerapan_id){
		$query = "
		select b.skoring_id, d.nama as jenis, b.nama as keterangan, c.nilai
		from ref_penerapan a
		join ref_daftar_skoring b on a.jalur_id=b.jalur_id and b.is_deleted=0
		join ref_daftar_nilai_skoring c on b.skoring_id=c.skoring_id and c.tahun_ajaran_id=a.tahun_ajaran_id and c.is_deleted=0
		join ref_tipe_skoring d on b.tipe_skoring_id=d.tipe_skoring_id and d.is_deleted=0
		where a.penerapan_id=$penerapan_id and a.kategori_prestasi=1 and a.is_deleted=0
		order by b.tipe_skoring_id desc, c.nilai asc, b.nama asc";

		return $this->db->query($query, array($penerapan_id));

		// $builder->select('a.daftar_nilai_skoring_id,b.nama AS jenis,a.nama AS keterangan,a.nilai');
		// $builder = $this->db->table('ref_daftar_nilai_skoring a');
		// $builder->join('ref_daftar_skoring b','a.daftar_skoring_id = b.daftar_skoring_id AND b.is_deleted=0');
		// //$builder->join('ref_jalur c','a.jalur_id = c.jalur_id AND c.is_deleted=0');
		// $builder->join('ref_penerapan d','d.jalur_id = a.jalur_id AND d.aktif = 1 AND d.is_deleted=0');
		// $builder->where(array('a.expired_date'=>NULL,'b.manual'=>1,'d.kategori_prestasi'=>1,'d.penerapan_id'=>$penerapan_id));
		// $builder->orderBy('a.daftar_skoring_id desc', 'a.nilai ASC','a.nama ASC');
		
		// return $builder->get();
	}

	function tcg_daftarprestasi() {
		$query = "
		select a.skoring_id as value, a.nama as label
		from ref_daftar_skoring a
		where a.tipe_skoring_id in (3,6)
		order by a.urutan, a.tipe_skoring_id desc, a.skoring_id asc";

		return $this->db->query($query);
	}

	function tcg_select_prestasi($tahun_ajaran_id) {
		$sql = "
		with data as (
			select 0 as value, '-- Pilih Prestasi --' as label, 0 as urutan1, 0 as urutan2, 0 as tipe_skoring_id, 0 as nilai
		
			union all
			
			(
				select a.skoring_id as value, b.nama as label, a.urutan as urutan1, b.urutan as urutan2, b.tipe_skoring_id, a.nilai
				  from ref_daftar_nilai_skoring a
				  join ref_daftar_skoring b on a.skoring_id=b.skoring_id and b.is_deleted=0
				  where a.tahun_ajaran_id='$tahun_ajaran_id' and a.is_deleted=0 and b.kunci=0 
			)
		)
		select value, label from data order by urutan1, urutan2, tipe_skoring_id, nilai desc";

		return $this->db->query($sql, array($tahun_ajaran_id));
	}

	function tcg_select_jalur() {
		$sql = "
		with data as (
			select 0 as value, '-- Pilih Jalur --' as label
		
			union all
			
			(
				select jalur_id as value, nama as label from ref_jalur where expired_date is null
			)
		)
		select * from data";

		return $this->db->query($sql);
	}

	function tcg_select_tipeskoring() {
		$sql = "
		with data as (
			select 0 as value, '-- Pilih Tipe Skoring --' as label
		
			union all
			
			(
				select tipe_skoring_id as value, nama as label from ref_tipe_skoring where expired_date is null and manual=1
			)
		)
		select * from data";

		return $this->db->query($sql);
	}

	function tcg_select_daftarskoring($jalur_id) {
		$sql = "
		with data as (
			select 0 as value, '-- Pilih Skoring --' as label, 0 as urutan
		
			union all
			
			(
				select skoring_id as value, nama as label, urutan from ref_daftar_skoring where expired_date is null and kunci=0 and jalur_id=$jalur_id
			)
		)
		select value, label from data order by urutan";

		return $this->db->query($sql, array($jalur_id));
	}

	function tcg_select_sekolah_filter($sekolah_id) {
		$sql = "select sekolah_id as value, concat('(',npsn,') ', nama) as label 
				from ref_sekolah 
				where expired_date is null and sekolah_id='$sekolah_id'";

		return $this->db->query($sql, array($sekolah_id));	
	}

	function tcg_lookup_jenjang() {
		$query = "
		select a.jenjang_id as value, a.nama as label
		from ref_jenjang a
		order by a.jenjang_id asc";

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

}