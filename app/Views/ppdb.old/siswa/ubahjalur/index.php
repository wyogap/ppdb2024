<!DOCTYPE html>
<html>
	<?php view('head');?>
	<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
 	<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Editor-1.9.2/css/editor.dataTables.min.css">

	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
			<?php view('header');?>
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<h1 class="text-white">
							<i class="glyphicon glyphicon-edit"></i> Ubah Jalur Pendaftaran Sekolah
						</h1>
						<ol class="breadcrumb">
							<li class="active"><a href="<?php echo site_url('siswa/pendaftaran');?>"><i class="glyphicon glyphicon-remove"></i> Batal </a></li>
						</ol>

					</section>
					<section class="content">
						<?php if ($maxubahjalur > $ubahjalursiswa) { ?>
						<div class="alert alert-danger alert-dismissible">
							<i class="icon glyphicon glyphicon-info-sign"></i>
							Anda hanya bisa melakukan perubahan <b>"Jalur Pendaftaran"</b> sebanyak <b><?php echo $maxubahjalur-$ubahjalursiswa;?> kali</b>.
						</div>
						<?php } else { ?>
							<div class="alert alert-danger alert-dismissible">
							<i class="icon glyphicon glyphicon-info-warning"></i>
							Anda sudah tidak bisa melakukan perubahan <b>"Jalur Pendaftaran"</b> karena sudah melebihi batasan.</b>.
						</div>
						<?php } ?>
						<?php view('siswa/ubahjalur/formulir');?>
					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>
</html>
