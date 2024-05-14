<div class="form-group has-feedback">
	<label for="tanggal_lahir">Tanggal Lahir</label>
	<input id="tanggal_lahir" name="tanggal_lahir" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required">
</div>
<script>
	//Date Picker
	$("#tanggal_lahir").datepicker({ 
		format: 'yyyy-mm-dd',
		startDate: new Date('2009-07-01'),
		endDate: new Date('2014-01-01')
	});
</script>