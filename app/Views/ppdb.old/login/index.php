<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>PPDB ONLINE</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link href="<?php echo base_url();?>assets/image/tutwuri.png" rel="shortcut icon">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/select2-4.0.13/css/select2.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/ionicons/css/ionicons.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/iCheck/square/blue.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/dist/css/AdminLTE.min.css">

		<script src='https://www.google.com/recaptcha/api.js' async defer></script>	
	</head>

	<?php 
		$tahapan_id = 0;
		$notifikasi_umum = "";
		foreach($tahapan->getResult() as $row) {
			$tahapan_id = $row->tahapan_id;
			$notifikasi_umum = $row->notifikasi_umum;
		}
	?>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<a href="javascript:void(0)"><b>PPDB</b><?php echo $nama_tahun_ajaran_aktif; ?></a>
			</div>
			<div class="text-center"><p><a href="<?php echo base_url();?>" class="btn btn-default"><i class="glyphicon glyphicon-step-backward"></i> Kembali ke <b>Beranda</b></a></p></div>
			<?php if (($tahapan_id == 0 || $tahapan_id == 99) && (!empty($notifikasi_umum))) { ?>
				<p class="alert alert-info alert-dismissible"><b><?php echo $notifikasi_umum; ?></b></p>
			<?php } ?>

			<!-- <div class="alert alert-success">
				<p class="text-center"><a href="<?php echo base_url();?>index.php/home/rekapitulasi" style="text-decoration:none;">Rekapitulasi Hasil PPDB Online 2020</a></p>
			</div>
			<div class="alert alert-info alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<p><i class="icon glyphicon glyphicon-info-sign"></i>Silahkan masuk dengan akun siswa dan gunakan menu Daftar Ulang untuk mendapatkan informasi lebih lanjut mengenai proses daftar ulang.</p>
			</div> -->

			<?php foreach($pengumuman->getResult() as $row): 
				if(empty($row->text)) 
					continue;
			?>
				<?php if ($row->tipe == 0) { ?><div class="alert alert-info <?php if($row->bisa_ditutup==1) {?>alert-dismissible<?php } ?>" style="margin-bottom: 10px;">
				<?php } else if ($row->tipe == 1) { ?><div class="alert alert-success <?php if($row->bisa_ditutup==1) {?>alert-dismissible<?php } ?>" style="margin-bottom: 10px;">
				<?php } else if ($row->tipe == 2) { ?><div class="alert alert-danger <?php if($row->bisa_ditutup==1) {?>alert-dismissible<?php } ?>" style="margin-bottom: 10px;">
				<?php } else { ?><div class="alert alert-error <?php if($row->bisa_ditutup==1) {?>alert-dismissible<?php } ?>" style="margin-bottom: 10px;">
				<?php } ?>
				<?php if($row->bisa_ditutup==1) {?>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<?php } ?>
					<p class="<?php echo $row->css; ?>"><?php echo $row->text; ?></p>
				</div>
			<?php endforeach; ?>

			<?php foreach($tahapan->getResult() as $row): 
				if(empty($row->notifikasi_umum)) 
					continue;
			?>
				<div class="alert alert-info alert-dismissible" style="margin-bottom: 10px;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p><i class="icon glyphicon glyphicon-info-sign"></i><?php echo $row->tahapan; ?></p>
					<p><?php echo $row->notifikasi_umum; ?></p>
				</div>
			<?php endforeach; ?>

			<div class="login-box-body">
				<p class="login-box-msg text-info"><b>Silahkan log-in terlebih dahulu.</b></p>
				<?php
					$error = $this->session->flashdata('error');
					if($error)
					{
				?>
					<div class="alert alert-danger">
						<?php echo $error; ?>                    
					</div>
				<?php 
					}
				?>
				<span class="text-red"><?php echo validation_errors();?></span>
				<form role="form" enctype="multipart/form-data" id="proses" action="<?php echo base_url();?>index.php/akun/login/login/" method="post">
					<?php if(!empty($tahun_ajaran)) { ?>
						<input type="hidden" id="tahun_ajaran" name="tahun_ajaran" value="<?php echo $tahun_ajaran; ?>"/>
					<?php } ?>
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="NISN / NIK / Nama Pengguna" id="username" name="username" data-validation="required" minlength="8" maxlength="100">
						<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						<input type="password" class="form-control" placeholder="Password / PIN" id="password" name="password" data-validation="required">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					
					<?php if($cek_captcha == 1) { ?>
					<?php if(strpos(base_url(), 'localhost')) { ?>
						<!-- localhost -->
						<div class="form-group has-feedback">
							<div class="g-recaptcha" data-sitekey="6LdDOOoUAAAAABvtPcoIZ4RHTm545Wb9lgD8j2Ab"></div>
						</div>
					<?php } else { ?>
						<!-- ppdb.disdik.kebumenkab.go.id -->
						<div class="form-group has-feedback">
							<div class="g-recaptcha" data-sitekey="6LfUN-oUAAAAAAEiaEPyE-S-d3NRbzXZVoNo51-x"></div>
						</div>
					<?php } ?>
					<?php } ?>

					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<button type="submit" class="btn btn-primary btn-block btn-flat"?>Log In</button>
						</div>
					</div>
				</form>
				<p>&nbsp;</p>
				<p>
				Siswa yang berasal dari <b class="text-red">Luar Daerah</b>, silahkan daftar dibawah (<i class="text-blue glyphicon glyphicon-arrow-down"></i>) ini.
				</p>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<a href="<?php echo site_url('akun/registrasi')?>" class="btn btn-block bg-green btn-flat <?php if($cek_registrasi==0 || $tahapan_id == 0 || $tahapan_id == 99) { ?>disabled<?php }?>"><i class="glyphicon glyphicon-registration-mark"></i> Daftar Manual </p></a>
					</div>
				</div>
			</div>
		</div>
		<script src="<?php echo base_url();?>assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
		<script src="<?php echo base_url();?>assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>assets/adminlte/plugins/iCheck/icheck.min.js"></script>
		<script src="<?php echo base_url();?>assets/adminlte/plugins/select2/select2.full.min.js"></script>
		<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>
		<script>
			var $messages = $('#error-message-wrapper');
				$.validate({
					modules: 'security',
					errorMessagePosition: $messages,
					scrollToTopOnError: false
				});
			$(function () {
				$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
					radioClass: 'iradio_square-blue',
					increaseArea: '20%'
				});
				$(".select2").select2();
			});
		</script>
	</body>
</html>
