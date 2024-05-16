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
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/dist/css/AdminLTE.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/dist/css/skins/_all-skins.css">
		<script src="<?php echo base_url();?>assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
	    <script src="<?php echo base_url();?>assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>assets/adminlte/plugins/select2/select2.full.min.js"></script>
		<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>
	    <script src="<?php echo base_url();?>assets/adminlte/plugins/slimScroll/jquery.slimscroll.min.js"></script>
	    <script src="<?php echo base_url();?>assets/adminlte/plugins/fastclick/fastclick.min.js"></script>
	    <script src="<?php echo base_url();?>assets/adminlte/dist/js/app.min.js"></script>
	    <script src="<?php echo base_url();?>assets/adminlte/dist/js/demo.js"></script>
		<script src="<?php echo base_url();?>assets/highcharts/code/highcharts.js"></script>
		<script src="<?php echo base_url();?>assets/highcharts/code/highcharts-more.js"></script>
		<script src="<?php echo base_url();?>assets/highcharts/code/themes/grid-light.js"></script>
	</head>
	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<h1 class="text-white">
							<i class="glyphicon glyphicon-exclamation-sign"></i> ERROR PAGE
						</h1>
						<ol class="breadcrumb">
							<li><a href="https://ppdb.disdik.kebumenkab.go.id/"><i class="glyphicon glyphicon-home"></i> BERANDA</a></li>
							<li class="active"><a href="#"><i class="glyphicon glyphicon-exclamation-sign"></i> ERROR PAGE</a></li>
						</ol>
					</section>
					<section class="content text-white">
						<h1>Oooooppppps....</h1>
						<p>Terjadi permasalahan muat halaman</p>
						<h4>An uncaught Exception was encountered</h4>
							<p>Type: <?php echo get_class($exception); ?></p>
							<p>Message: <?php echo $message; ?></p>
							<p>Filename: <?php echo $exception->getFile(); ?></p>
							<p>Line Number: <?php echo $exception->getLine(); ?></p>

						<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>
								<p>Backtrace:</p>
								<?php foreach ($exception->getTrace() as $error): ?>
									<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>
										<p style="margin-left:10px">
										File: <?php echo $error['file']; ?><br />
										Line: <?php echo $error['line']; ?><br />
										Function: <?php echo $error['function']; ?>
										</p>
									<?php endif ?>
								<?php endforeach ?>
						<?php endif ?>
						Klik Disini untuk kembali ke <a href="https://ppdb.disdik.kebumenkab.go.id/" class="btn btn-primary"><i class="glyphicon glyphicon-step-backward"></i> Beranda</a>
					</section>
				</div>
			</div>
		</div>
	</body>
</html>