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
							<i class="glyphicon glyphicon-edit"></i> Ubah Jenis Pilihan Sekolah
						</h1>
						<ol class="breadcrumb">
							<li class="active"><a href="<?php echo site_url('siswa/pendaftaran');?>"><i class="glyphicon glyphicon-remove"></i> Batal </a></li>
						</ol>

					</section>
					<section class="content">
						<?php if ($maxubahjenispilihan > $ubahjenispilihansiswa) { ?>
						<div class="alert alert-danger alert-dismissable">
							<i class="icon glyphicon glyphicon-info-sign"></i>
							Anda hanya bisa melakukan perubahan <b>"Jenis Pilihan"</b> sebanyak <b><?php echo $maxubahjenispilihan-$ubahjenispilihansiswa;?> kali</b>. Jika salah satu jenis pilihan sekolah diperbaharui, sistem mungkin akan menyesuikan jenis pilihan lain secara otomatis.</b>.
						</div>
						<?php } else { ?>
							<div class="alert alert-danger alert-dismissable">
							<i class="icon glyphicon glyphicon-info-warning"></i>
							Anda sudah tidak bisa melakukan perubahan <b>"Jenis Pilihan"</b> karena sudah melebihi batasan.</b>.
						</div>
						<?php } ?>
						<?php view('siswa/ubahjenispilihan/formulir');?>
					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>
</html>
