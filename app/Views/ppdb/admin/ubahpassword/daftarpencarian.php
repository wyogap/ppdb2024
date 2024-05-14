<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header">
				<?php if ($daftar->num_rows() > 100) { ?>
				<b>Hasil Pencarian: <?php echo $daftar->num_rows(); ?> Siswa. Hanya menampilkan 100 hasil pertama.</b>
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
			if ($i >=100) break;
			$i++;
	?>
	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
		<div class="box box-primary box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="glyphicon glyphicon-user"></i> (<?php echo $row->nisn;?>) <?php echo $row->nama;?></h3>
			</div>
			<div class="box-body">
				<p>Tempat Lahir : <?php echo $row->tempat_lahir;?><br></p>
				<p>Tanggal Lahir : <?php echo $row->tanggal_lahir;?><br></p>
				<p>Nama Ibu Kandung : <?php echo $row->nama_ibu_kandung;?><br></p>
				<p>Jenis Kelamin : <?php echo $row->jenis_kelamin;?><br></p>
				<p>NIK : <?php echo $row->nik;?><br></p>
			</div>
			<div class="box-footer with-border">
				<a href="<?php echo base_url();?>index.php/Cadmin/detailubahpassword?peserta_didik_id=<?php echo $row->peserta_didik_id;?>" class="btn btn-warning"><i class="glyphicon glyphicon-search"></i> Detail Siswa</a>
			</div>
		</div>
	</div>
	<?php endforeach;?>
</div>