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
							<i class="glyphicon glyphicon-ok"></i> Verifikasi<small>Berkas</small>
						</h1>
						<ol class="breadcrumb">
							<li class="active"><a href="#" onclick="event.stopPropagation(); batal_perubahan(); return false;"><i class="glyphicon glyphicon-remove"></i> Batal </a></li>
						</ol>

						<!-- <ol class="breadcrumb">
							<li class="active"><a href="#" onclick="window.history.back();"><i class="glyphicon glyphicon-remove"></i> Batal </a></li>
						</ol> -->
					</section>
					<section class="content">
						<?php view('sekolah/verifikasisiswa/verifikasi');?>
					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>
</html>

<script>
function batal_perubahan() {
	//release flag
	$.ajax({
		"url": "<?php echo site_url('sekolah/verifikasi/sedangverifikasi'); ?>",
		"dataType": "json",
		"type": "POST",
		"data": {
			peserta_didik_id: '<?php echo $peserta_didik_id?>',
			aktif: '0'
		},
		beforeSend: function(request) {
			request.setRequestHeader("Content-Type",
				"application/x-www-form-urlencoded; charset=UTF-8");
		},
		success: function(response) {
			window.location.href = "<?php echo base_url();?>index.php/sekolah/verifikasi";
		},
		error: function(jqXhr, textStatus, errorMessage) {
			//window.location.href = "<?php echo base_url();?>index.php/sekolah/verifikasi";
		}
	});

}
</script>
