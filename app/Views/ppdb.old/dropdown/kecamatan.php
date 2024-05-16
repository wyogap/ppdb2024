<select id="kode_kecamatan" name="kode_kecamatan" class="form-control select2" data-validation="required">
	<option value="">-- Pilih Kecamatan --</option>
	<?php foreach($kecamatan->getResult() as $row):?>
	<option value="<?php echo $row->kode_wilayah;?>"><?php echo $row->nama;?></option>
	<?php endforeach;?>
</select>