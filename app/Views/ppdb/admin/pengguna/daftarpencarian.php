<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header">
			<?php if ($daftar->num_rows() > 100) { ?>
				<b>Hasil Pencarian: <?php echo $daftar->num_rows(); ?> Pengguna. Hanya menampilkan 100 hasil pertama.</b>
				<?php } else { ?>
				<b>Hasil Pencarian: <?php echo $daftar->num_rows(); ?> Pengguna</b>
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
				<h3 class="box-title"><i class="glyphicon glyphicon-user"></i> <?php echo $row->nama;?></h3>
			</div>
			<div class="box-body">
				<p>Username : <?php echo $row->username;?><br></p>
				<p>Peran : <?php echo $row->peran;?><br></p>
				<p>Sekolah : <?php echo $row->sekolah;?><br></p>
			</div>
			<div class="box-footer with-border">
			</div>
		</div>
	</div>
	<?php endforeach;?>
</div>