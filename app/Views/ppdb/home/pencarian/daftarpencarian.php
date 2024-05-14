
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header">
				<?php if ($daftar->num_rows() > 10) { ?>
					<b>Hasil Pencarian: <?php echo $daftar->num_rows(); ?> Siswa. Hanya menampilkan 10 hasil pertama.</b>
					<?php } else { ?>
					<b>Hasil Pencarian: <?php echo $daftar->num_rows(); ?> Siswa</b>
				<?php } ?>
			</div>
			<!-- <div class="box-footer">
			</div> -->
		</div>
	</div>
</div>
<div class="row">
	<?php 
		$i = 0;
		foreach($daftar->getResult() as $row):
			if ($i >=10) break;
			$i++;
	?>
	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
		<div class="box box-primary box-solid">
			<div class="box-header with-border">
				<h3 class="box-title text-white"><i class="glyphicon glyphicon-user"></i> <?php echo $row->nama;?></h3>
			</div>
			<div class="box-body">
				<p><b>Jenis Kelamin</b> : <?php if($row->jenis_kelamin=="L"){echo "Laki-laki";}else{echo "Perempuan";}?></p>
				<p><b>Asal Sekolah</b> : <?php echo $row->sekolah;?></p>
			</div>
			<div class="box-footer with-border">
				<a href="<?php echo base_url();?>index.php/Chome/detailpendaftaran?peserta_didik_id=<?php echo $row->peserta_didik_id;?>" target="_blank" class="btn btn-lg btn-default"><i class="glyphicon glyphicon-search"></i> Detail Pendaftaran</a>
			</div>
		</div>
	</div>
	<?php if ($cnt%3 == 0) {?>
</div>
<div class="row">
	<?php } ?>
	<?php endforeach;?>
</div>