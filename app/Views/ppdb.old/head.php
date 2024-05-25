<?php
	if (!empty($page_title)) {
		$page_title = ' - '. $page_title;
	}
	else {
		$page_title = "";
	}
?>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>PPDB ONLINE <?php echo $wilayah_aktif;?><?php echo $page_title;?></title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link href="<?php echo base_url();?>assets/image/tutwuri.png" rel="shortcut icon">

	<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/select2/select2.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/ionicons/css/ionicons.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/dist/css/AdminLTE.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/dist/css/skins/_all-skins.css">

	<link rel="stylesheet" href="<?php echo base_url();?>assets/jquery-confirm/jquery-confirm.min.css">

	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ppdb.css">

	<!-- <script src="<?php echo base_url();?>assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script> -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/podes/javascript/jquery.min.js"></script>

	<script src="<?php echo base_url();?>assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>assets/adminlte/plugins/select2/select2.full.min.js"></script>
	<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>
	<script src="<?php echo base_url();?>assets/adminlte/plugins/fastclick/fastclick.js"></script>
	<script src="<?php echo base_url();?>assets/adminlte/dist/js/app.min.js"></script>
	<!-- <script src="<?php echo base_url();?>assets/highcharts/code/highcharts.js"></script>
	<script src="<?php echo base_url();?>assets/highcharts/code/highcharts-more.js"></script>
	<script src="<?php echo base_url();?>assets/highcharts/code/themes/grid-light.js"></script> -->
	<script src="<?php echo base_url();?>assets/adminlte/plugins/slimScroll/jquery.slimscroll.min.js"></script>
	<script src="<?php echo base_url();?>assets/adminlte/dist/js/demo.js"></script>

	<script src="<?php echo base_url();?>assets/jquery-confirm/jquery-confirm.min.js"></script>

</head> 