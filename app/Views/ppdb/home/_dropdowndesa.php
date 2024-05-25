<select id="kode_wilayah" name="kode_wilayah" class="form-control select2" data-validation="required">
	<option value="">-- Pilih Desa/Kelurahan --</option>
	<?php foreach($desa as $row):?>
	<option value="<?php echo $row['kode_wilayah'];?>"><?php echo $row['nama'];?></option>
	<?php endforeach;?>
</select>