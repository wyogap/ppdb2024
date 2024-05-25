<select id="kode_wilayah" name="kode_wilayah" class="form-control select2">
	<option value="">-- Pilih Padukuhan --</option>
	<?php foreach($padukuhan as $row):?>
	<option value="<?php echo $row['kode_wilayah'];?>"><?php echo $row['nama'];?></option>
	<?php endforeach;?>
</select>