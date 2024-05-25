<!DOCTYPE html>
<html>
	<?php view('head');?>
	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
			<?php view('header');?>
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<h1 class="text-white">
							<i class="glyphicon glyphicon-edit"></i> Ubah Pilihan Sekolah
						</h1>
						<ol class="breadcrumb">
							<li class="active"><a href="<?php echo site_url('siswa/pendaftaran');?>"><i class="glyphicon glyphicon-remove"></i> Batal </a></li>
						</ol>

					</section>
					<section class="content">
					<?php if ($maxubahsekolah > $ubahsekolahsiswa) { ?>
						<div class="alert alert-danger alert-dismissible">
							<i class="icon glyphicon glyphicon-info-sign"></i>
							Anda hanya bisa melakukan perubahan <b>"Sekolah"</b> sebanyak <b><?php echo $maxubahsekolah-$ubahsekolahsiswa;?> kali</b>.
						</div>
						<?php } else { ?>
							<div class="alert alert-danger alert-dismissible">
							<i class="icon glyphicon glyphicon-info-warning"></i>
							Anda sudah tidak bisa melakukan perubahan <b>"Sekolah"</b> karena sudah melebihi batasan.</b>.
						</div>
						<?php } ?>
						<?php view('siswa/ubahsekolah/formulir');?>
					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>
</html>
