<table class="table table-striped" style="margin-bottom: 0px !important;">
	<?php foreach($detailsekolah->getResult() as $row):?>
	<tr>
		<td><b>NPSN</b></td>
		<td>:</td>
		<td><?php echo $row->npsn;?></td>
	</tr>
	<tr>
		<td><b>Nama</b></td>
		<td>:</td>
		<td><?php echo $row->nama;?></td>
	</tr>
	<tr>
		<td><b>Jenjang</b></td>
		<td>:</td>
		<td><?php echo $row->bentuk;?></td>
	</tr>
	<tr>
		<td><b>Status</b></td>
		<td>:</td>
		<td><?php echo $row->status;?></td>
	</tr>
	<tr>
		<td><b>Alamat</b></td>
		<td>:</td>
		<td><?php echo $row->alamat_jalan;?></td>
	</tr>
	<tr>
		<td><b>Desa/Kelurahan</b></td>
		<td>:</td>
		<td><?php echo $row->desa_kelurahan;?></td>
	</tr>
	<tr>
		<td><b>Kecamatan</b></td>
		<td>:</td>
		<td><?php echo $row->kecamatan;?></td>
	</tr>
	<tr>
		<td><b>Penyelenggara Inklusi</b></td>
		<td>:</td>
		<td><?php if($row->inklusi==1){?>Ya<?php }else{?>Tidak<?php }?></td>
	</tr>
	<?php endforeach;?>
	<tr>
		<td colspan="3">
			<form role="form" enctype="multipart/form-data" action="<?php echo base_url();?>index.php/siswa/pendaftaran/prosesubahsekolah" method="post">
				<input type="hidden" id="pendaftaran_id" name="pendaftaran_id" value="<?php echo $this->input->post("pendaftaran_id");?>" data-validation="required">
				<input type="hidden" id="sekolah_id" name="sekolah_id" value="<?php echo $this->input->post("sekolah_id");?>" data-validation="required">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<button type="submit" class="btn btn-primary btn-flat" <?php if($this->session->userdata("tutup_akses")==1||($maxubahsekolah<=$ubahsekolahsiswa)){?>disabled="true"<?php }?>>Pilih Sekolah</button>
					</div>
				</div>
			</form>
		</td>
	</tr>
</table>
<!-- <script>
	//Dropdown Select
	$(function () {
		$(".select2").select2();
	});
	//Validasi
	var $messages = $('#error-message-wrapper');
	$.validate({
		modules: 'security',
		errorMessagePosition: $messages,
		scrollToTopOnError: false
	});
</script> -->