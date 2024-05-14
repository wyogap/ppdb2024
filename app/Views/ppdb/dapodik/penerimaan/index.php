<!DOCTYPE html>
<html>
	<?php view('head');?>

	<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/datepicker/datepicker3.css">
	<script src="<?php echo base_url();?>assets/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>

	<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Select-1.3.1/css/select.dataTables.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/css/buttons.dataTables.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Editor-1.9.2/css/editor.dataTables.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/jquery-confirm/jquery-confirm.min.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ppdb.css">

	<script src="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url();?>assets/datatables/Select-1.3.1/js/dataTables.select.min.js"></script>
	<script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/buttons.html5.min.js" defer></script>
	<script src="<?php echo base_url();?>assets/datatables/JSZip-2.5.0/jszip.min.js" defer></script>
	<script src="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>
	<script src="<?php echo base_url();?>assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js"></script>
	<script src="<?php echo base_url();?>assets/jquery-confirm/jquery-confirm.min.js"></script>

	<style>
		content-wrapper-compact {
			min-height: 0px !important;
		}
	</style>

	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
			<?php view('header');?>
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<h1 class="text-white">
							<i class="glyphicon glyphicon-list"></i> Daftar Peserta Didik Baru
						</h1>
						<!-- <ol class="breadcrumb">
							<li class="active"><a href="#"><i class="glyphicon glyphicon-search"></i> Pencarian Siswa Pendaftar</a></li>
						</ol> -->
					</section>
					<section class="content">
						<?php view('dapodik/penerimaan/diterima');?>
					</section>
				</div>
				<div class="container">
					<section class="content-header">
						<h1 class="text-white">
							<i class="glyphicon glyphicon-search"></i> Pencarian
						</h1>
						<!-- <ol class="breadcrumb">
							<li class="active"><a href="#"><i class="glyphicon glyphicon-search"></i> Pencarian Siswa Pendaftar</a></li>
						</ol> -->
					</section>
					<section class="content">
						<?php view('dapodik/penerimaan/formulir');?>
					</section>
				</div>
			</div>
			<div class="content-wrapper content-wrapper-compact">
			</div>
			<?php view('footer');?>
		</div>
	</body>

	<script>
		//Dropdown Select
		$(function () {
			$(".select2").select2();
		});

		$(document).ready(function() {
			$.extend( $.fn.dataTable.defaults, { responsive: true } );

			$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
			$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
			} );
		});


	</script>

</html>