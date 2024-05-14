<!DOCTYPE html>
<?php 
		$nama = "";
		$npsn = "";
		foreach($profilsekolah->getResult() as $row):
			$nama = $row->nama;
			$npsn = $row->npsn;
		endforeach;
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
							<i class="glyphicon glyphicon-home"></i> <?php if($npsn!=""){?>(<?php echo $npsn;?>) <?php }?><?php echo $nama;?></small>
						</h1>
						<ol class="breadcrumb">
							<li class="active"><a href="#" onclick="window.history.back();"><i class="glyphicon glyphicon-remove"></i> Batal </a></li>
						</ol>
					</section>
					<section class="content">
						<?php view('sekolah/ubahprofil/formulir');?>
					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>
</html>
