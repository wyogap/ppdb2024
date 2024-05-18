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
							<i class="glyphicon glyphicon-edit"></i> Perubahan<small>Data Siswa</small>
						</h1>
						<ol class="breadcrumb">
							<li class="active"><a href="<?php echo site_url('Csekolah/perubahandatasiswa');?>"><i class="glyphicon glyphicon-remove"></i> Batal </a></li>
						</ol>
						<!-- <ol class="breadcrumb">
							<li class="active"><a href="#" onclick="window.history.back();"><i class="glyphicon glyphicon-remove"></i> Batal </a></li>
						</ol> -->
					</section>
					<section class="content">
						<?php view('sekolah/perubahandatasiswa/detail/formulir');?>
					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>
</html>