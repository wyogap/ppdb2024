<table class="table table-striped">
	<?php foreach($detailsekolah->getResult() as $row):?>
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
	<?php endforeach;?>
</table>
<script>
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
</script>