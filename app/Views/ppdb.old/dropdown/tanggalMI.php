<?php 
		$this->load->model('Msetting');
		
		$maxtgllahir="";
		$mintgllahir="";

		$batasanusia = $this->Msetting->tcg_batasanusia();
		foreach($batasanusia->getResult() as $row):
			$maxtgllahir = $row->maksimal_tanggal_lahir;
			$mintgllahir = $row->minimal_tanggal_lahir;
		endforeach;
	?>
	
<div class="form-group has-feedback">
	<label for="tanggal_lahir">Tanggal Lahir</label>
	<input id="tanggal_lahir" name="tanggal_lahir" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required">
</div>

<script>
	//Date Picker
	$("#tanggal_lahir").datepicker({ 
		format: 'yyyy-mm-dd',
		startDate: new Date("<?php echo $maxtgllahir; ?>"),
		endDate: new Date("<?php echo $mintgllahir; ?>")
	});
</script>