<select id="sekolah_id" name="sekolah_id" class="form-control select2" data-validation="required">
	<option value="" selected="true">-- Pilih Sekolah --</option>
	<?php foreach($sekolah as $row):?>
	<option value="<?php echo $row['sekolah_id'];?>"><?php echo "(".$row['npsn'].") ".$row['nama'];?></option>
	<?php endforeach;?>
</select>