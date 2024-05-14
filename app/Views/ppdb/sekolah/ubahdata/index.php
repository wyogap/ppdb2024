<!DOCTYPE html>
<?php 
		$nama = "";
		$nisn = "";
		foreach($profilsiswa->getResult() as $row):
			$nama = $row->nama;
			$nisn = $row->nisn;
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
							<i class="glyphicon glyphicon-user"></i> <?php if($nisn!=""){?>(<?php echo $nisn;?>) <?php }?><?php echo $nama;?></small>
						</h1>
						<ol class="breadcrumb">
							<li class="active"><a href="#" onclick="window.history.back();"><i class="glyphicon glyphicon-remove"></i> Batal </a></li>
						</ol>
					</section>
					<section class="content">
						<?php view('sekolah/ubahdata/formulir');?>
					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>
</html>
