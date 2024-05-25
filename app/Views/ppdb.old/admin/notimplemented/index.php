<!DOCTYPE html>

<?php
	if (empty($home)) {
		$home = "Belum Diimplementasikan";
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
							<li class="active"><a href="#"><i class="glyphicon glyphicon-bookmark"></i> Belum diimplementasikan</a></li>
						</ol>
					</section>
					<section class="content">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="alert alert-danger alert-dismissible">
								<i class="icon glyphicon glyphicon-info-sign"></i>
								Fitur ini belum diimplementasikan. Perubahan konfigurasi masih harus dilakukan secara manual langsung di database.
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
