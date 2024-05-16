<!DOCTYPE html>

<?php 
	$bentuk = $this->session->userdata('bentuk');
	$pengguna_id = $this->session->userdata('pengguna_id');
	$tahun_ajaran_id = $this->session->userdata('tahun_ajaran_aktif');

	//for consistency
	if(!isset($page)) {
		$page = "";
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
						<h1 class="text-white" style="display: inline;">
							<i class="glyphicon glyphicon-th-list"></i> Peringkat<small>Pendaftaran</small>
						</h1>
						<h1 class="text-white pull-right">
							<a href="<?php echo base_url();?>" class="text-white"><b>PPDB</b><?php echo $nama_tahun_ajaran_aktif; ?></a>
						</h1>
					</section>
					<section class="content">
						<?php view('home/peringkatfinal/peringkat');?>
					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>
</html>
