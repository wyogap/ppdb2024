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
							<i class="glyphicon glyphicon-remove"></i> Hapus Pendaftaran<small>Siswa</small>
						</h1>
						<ol class="breadcrumb">
							<li class="active"><a href="<?php echo site_url('siswa/pendaftaran');?>"><i class="glyphicon glyphicon-remove"></i> Batal </a></li>
						</ol>

					</section>
					<section class="content">
					<?php if ($maxhapuspendaftaran > $hapuspendaftaransiswa) { ?>
						<div class="alert alert-danger alert-dismissable">
							<i class="icon glyphicon glyphicon-info-sign"></i>
							Anda hanya bisa <b>menghapus</b> pendaftaran sebanyak <b><?php echo $maxhapuspendaftaran-$hapuspendaftaransiswa;?> kali</b>. Jika salah satu pilihan sekolah diperbaharui/dihapus, sistem mungkin akan menyesuikan jenis pilihan lain secara otomatis.
						</div>
						<?php } else { ?>
							<div class="alert alert-danger alert-dismissable">
							<i class="icon glyphicon glyphicon-info-warning"></i>
							Anda sudah tidak bisa <b>menghapus</b> pendaftaran karena sudah melebihi batasan.</b>.
						</div>
						<?php } ?>
						<?php view('siswa/hapuspendaftaran/formulir');?>
					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>
</html>
