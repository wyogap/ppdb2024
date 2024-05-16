<!DOCTYPE html>
<html lang="en">
	<?php view('head');?>
	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
			<?php view('header');?>
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<h1 class="text-white">
							<i class="glyphicon glyphicon-user"></i> Profil<small>Siswa</small>
						</h1>
						<!-- <ol class="breadcrumb">
							<li class="active"><a href="#"><i class="glyphicon glyphicon-user"></i> Profil Siswa</a></li>
						</ol> -->
					</section>
					<section id="content" class="content">
						<?php view('siswa/beranda/profil');?>
					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>
</html>
