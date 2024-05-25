<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>PPDB ONLINE</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/select2-4.0.13/css/select2.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/ionicons/css/ionicons.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/iCheck/square/blue.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/dist/css/AdminLTE.min.css">
	</head>
	<body class="hold-transition lockscreen">
		<div class="lockscreen-wrapper">
			<span><?php if(isset($info)){echo $info;}?></span>

			<?php
				$error = $this->session->flashdata('error');
				if($error)
				{
			?>
				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<?php echo $error; ?>                    
				</div>
			<?php 
				}

				$success = $this->session->flashdata('success');
				if($success)
				{
			?>
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<?php echo $success; ?>                    
				</div>
			<?php } ?>

			<div class="lockscreen-logo">
				<a href="<?php echo base_url();?>"><b>UBAH</b>PASSWORD</a>
			</div>
			<div class="lockscreen-name"><?php echo $this->session->userdata("username");?></div>
				<div class="lockscreen-item">
					<div class="lockscreen-image">
						<img src="<?php echo base_url();?>assets/image/user.png" alt="User Image">
					</div>
					<form role="form" enctype="multipart/form-data" id="proses" action="<?php echo base_url();?>index.php/akun/password/ubah" method="post" class="lockscreen-credentials">
						<div class="input-group">
							<input type="password" class="form-control" placeholder="PIN / Password Baru" id="password" name="password" data-validation="required">
							<div class="input-group-btn">
								<button type="submit" class="btn"><i class="glyphicon glyphicon-arrow-right text-muted"></i></button>
							</div>
						</div>
					</form>
				</div>
				<div class="help-block text-center">
					Silahkan masukkan password baru Anda.
				</div>
				<div class="text-center">
					<a href="<?php echo base_url();?>index.php/akun/login">Kembali ke beranda</a>
				</div>
				<div class="lockscreen-footer text-center">
					Copyright &copy; 2019 <b><a href="javascript:void(0)" class="text-black"><?php echo $nama_wilayah_aktif;?></a></b><br>All rights reserved
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
