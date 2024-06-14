<select id="kode_kabupaten" name="kode_kabupaten" class="form-control select2" data-validation="required">
	<option value="">-- Pilih Kabupaten/Kota --</option>
	<?php foreach($kabupaten as $row):?>
	<option value="<?php echo $row['kode_wilayah'];?>"><?php echo $row['kabupaten'];?></option>
	<?php endforeach;?>
</select>