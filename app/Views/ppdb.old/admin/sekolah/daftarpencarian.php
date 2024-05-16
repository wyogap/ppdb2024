<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header">
				<b>Hasil Pencarian: <?php echo $daftar->num_rows(); ?> Pendaftar</b>
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
				<p>Tempat Lahir : <?php echo $row->tempat_lahir;?><br></p>
				<p>Tanggal Lahir : <?php echo $row->tanggal_lahir;?><br></p>
				<p>Nama Ibu Kandung : <?php echo $row->nama_ibu_kandung;?><br></p>
				<p>Jenis Kelamin : <?php echo $row->jenis_kelamin;?><br></p>
				<p>NIK : <?php echo $row->nik;?><br></p>
			</div>
			<div class="box-footer with-border">
				<a href="<?php echo base_url();?>index.php/Chome/detailpendaftaran?peserta_didik_id=<?php echo $row->peserta_didik_id;?>" target="_blank" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-list"></i> Detail</a>
				<a href="<?php echo base_url();?>index.php/Csekolah/detailperubahandatasiswa?pendaftaran_id=<?php echo $row->pendaftaran_id;?>" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-edit"></i></a>			<a href="<?php echo base_url();?>index.php/Csekolah/detailverifikasiberkas?pendaftaran_id=<?php echo $row->pendaftaran_id;?>" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-ok"></i></a>				
				<a href="<?php echo base_url();?>index.php/Csekolah/detailcabutberkas?pendaftaran_id=<?php echo $row->pendaftaran_id;?>" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove"></i></a>
				<a href="<?php echo base_url();?>index.php/Csekolah/detailgantiprestasi?pendaftaran_id=<?php echo $row->pendaftaran_id;?>&penerapan_id=<?php echo $row->penerapan_id;?>" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-education"></i></a>
				<!-- <br>
				<form role="form" name="prosesdaftarulang" id="prosesdaftarulang" action="<?php echo base_url();?>index.php/Csekolah/prosesdaftarulang/" method="post">
					<a href="<?php echo base_url();?>index.php/Csekolah/detailgantiprestasi?pendaftaran_id=<?php echo $row->pendaftaran_id;?>&penerapan_id=<?php echo $row->penerapan_id;?>" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-save"></i> Daftar Ulang</a>
				</form> -->
			</div>
		</div>
	</div>
	<?php endforeach;?>
</div>