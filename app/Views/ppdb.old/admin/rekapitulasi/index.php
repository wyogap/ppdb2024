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
							<i class="glyphicon glyphicon-th-list"></i> Rekapitulasi<small>Pendaftaran</small>
						</h1>
						<!-- <ol class="breadcrumb">
							<li class="active"><a href="#"><i class="glyphicon glyphicon-th-list"></i> Rekapitulasi Pendaftaran</a></li>
						</ol> -->
					</section>
					<section class="content">
						<?php view('admin/rekapitulasi/sekolah');?>
					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>
</html>
