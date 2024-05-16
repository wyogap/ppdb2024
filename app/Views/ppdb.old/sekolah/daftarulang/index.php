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
							<i class="glyphicon glyphicon-th-list"></i> Daftar Ulang <small>Pendaftaran</small>
						</h1>
						<!-- <ol class="breadcrumb">
							<li class="active"><a href="<?php echo site_url('sekolah/daftarulang');?>"><i class="glyphicon glyphicon-th-list"></i> Kembali</a></li>
						</ol> -->
						<div class="tahun-selection">
							<div class="tahun-selection-label">
							Periode: 
							</div>
								<?php if (1==0) { ?>
								<select id="putaran" name="putaran" class="tahun-selection-control" data-validation="required">
								<?php foreach($daftarputaran->getResult() as $row2): ?>
									<option value="<?php echo $row2->putaran; ?>" <?php if($row2->putaran==$putaran_aktif){?>selected="true"<?php }?>><?php echo $row2->nama; ?></option>
								<?php endforeach;?>
								</select>
								<?php } ?>
								<select id="tahun_ajaran" name="tahun_ajaran" class="tahun-selection-control" data-validation="required">
								<?php foreach($daftartahunajaran->getResult() as $row2): ?>
									<option value="<?php echo $row2->tahun_ajaran_id; ?>" <?php if($row2->tahun_ajaran_id==$tahun_ajaran_aktif){?>selected="true"<?php }?>><?php echo $row2->nama; ?></option>
								<?php endforeach;?>
								</select>

						</div>
					</section>
					<section class="content">
						<?php view('sekolah/daftarulang/pendaftar');?>
					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>
</html>

<script type="text/javascript">

$(document).ready(function() {

	$('select[name="putaran"]').on('change', function() {
		window.location.replace("<?php echo site_url('sekolah/daftarulang'); ?>?tahun_ajaran_id=<?php echo $tahun_ajaran_aktif; ?>&putaran=" + $("#putaran").val());
	});

	$('select[name="tahun_ajaran"]').on('change', function() {
		window.location.replace("<?php echo site_url('sekolah/daftarulang'); ?>?tahun_ajaran_id=" + $("#tahun_ajaran").val());
	});

});

</script>