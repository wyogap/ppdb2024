<!DOCTYPE html>

<?php
	if (empty($home)) {
		$home = "Tidak ditemukan";
	}
?>

<html>
	<?php view('head');?>
	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
			<?php view('header');?>
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<h1 class="text-white">
							<i class="glyphicon glyphicon-bookmark"></i> <?php echo $home; ?> <small>TODO</small>
						</h1>
						<ol class="breadcrumb">
							<li class="active"><a href="#"><i class="glyphicon glyphicon-bookmark"></i> Tidak ditemukan</a></li>
						</ol>
					</section>
					<section class="content">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="alert alert-danger alert-dismissable">
								<i class="icon glyphicon glyphicon-info-sign"></i>
								Halaman yang anda cari tidak ditemukan atau anda tidak mempunyai hak untuk mengakses halaman tersebut.
							</div>

							</div>
						</div>
					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>
</html>
