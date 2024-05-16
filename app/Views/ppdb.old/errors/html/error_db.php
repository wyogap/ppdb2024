<!DOCTYPE html>
<html>
	<head>
		<?php 
		$CI =& get_instance();
			if( ! isset($CI))
			{
				$CI = new CI_Controller();
			}
			$CI->load->helper('url');
		?>
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
							<li><a href="<?php echo base_url();?>"><i class="glyphicon glyphicon-home"></i> BERANDA</a></li>
							<li class="active"><a href="#"><i class="glyphicon glyphicon-exclamation-sign"></i> ERROR PAGE</a></li>
						</ol>
					</section>
					<section class="content text-white">
						<h1>Oooooppppps....</h1>
						<p>Terjadi permasalahan muat halaman</p>
						<h1><?php echo $heading; ?></h1>
						<p><?php echo $message; ?></p>
						Klik Disini untuk kembali ke <a href="<?php echo base_url();?>" class="btn btn-primary"><i class="glyphicon glyphicon-step-backward"></i> Beranda</a>
					</section>
				</div>
			</div>
		</div>
	</body>
</html>