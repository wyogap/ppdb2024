<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header">
				<b>Hasil Pencarian: <?php echo $daftar->num_rows(); ?> Siswa</b>
			</div>
			<!-- <div class="box-footer">
			</div> -->
		</div>
	</div>
</div>

<div class="row">
	<?php foreach($daftar->getResult() as $row):?>
	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
		<div class="box box-primary box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="glyphicon glyphicon-user"></i> (<?php echo $row->nisn;?>) <?php echo $row->nama;?></h3>
			</div>
			<div class="box-body">
				<p><b>SEKOLAH TUJUAN : <?php echo $row->sekolah;?></b><br></p>
				<p>Tempat Lahir : <?php echo $row->tempat_lahir;?><br></p>
				<p>Tanggal Lahir : <?php echo $row->tanggal_lahir;?><br></p>
				<p>Nama Ibu Kandung : <?php echo $row->nama_ibu_kandung;?><br></p>
				<p>Jenis Kelamin : <?php echo $row->jenis_kelamin;?><br></p>
				<p>NIK : <?php echo $row->nik;?><br></p>
				<p>Keterangan : <?php echo $row->keterangan;?><br></p>
				<p>Jenis Pilihan : <?php echo $row->jenis_pilihan;?><br></p>
			</div>
			<div class="box-footer with-border">
				<a href="<?php echo base_url();?>index.php/Cadmin/detailgantiprestasi?pendaftaran_id=<?php echo $row->pendaftaran_id;?>&penerapan_id=<?php echo $row->penerapan_id;?>" class="btn btn-warning"><i class="glyphicon glyphicon-search"></i> Detail Siswa</a>
			</div>
		</div>
	</div>
	<?php endforeach;?>
</div>