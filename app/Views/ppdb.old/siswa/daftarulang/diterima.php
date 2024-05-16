<?php
	setlocale(LC_ALL,'IND');
	date_default_timezone_set('Asia/Jakarta');

	$pendaftaran_mulai = "";
	$pendaftaran_selesai = "";
	$notifikasi_siswa = "";

	foreach($waktudaftarulang->getResult() as $row) {
		$daftarulang_mulai = $row->tanggal_mulai_aktif;
		$daftarulang_selesai = $row->tanggal_selesai_aktif;
		$notifikasi_siswa = $row->notifikasi_siswa;
	}

?>

<div class="alert alert-success">
	<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
	<p><i class="icon glyphicon glyphicon-info-sign"></i>Selamat!</p>
	<p>Anda diterima di <b>pilihan <?php echo $pilihan; ?></b> di <b><?php echo $sekolah; ?></b> melalui jalur <b><?php echo $jalur; ?></b>. Silahkan melakukan daftar ulang di sekolah tujuan dengan membawa dokumen-dokumen pendukung di bawah.</p>
</div>

<div class="alert alert-info">
	<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
	<p><i class="icon glyphicon glyphicon-info-sign"></i>Periode Daftar Ulang</p>
	<p>Periode daftar ulang adalah dari tanggal <b><?php echo strftime("%d %B %Y %H:%M", strtotime($daftarulang_mulai)); ?></b> sampai dengan tanggal <b><?php echo strftime("%d %B %Y %H:%M", strtotime($daftarulang_selesai)); ?></b>.</p>
</div>

<?php if ($notifikasi_siswa != "") { ?>
<div class="alert alert-info">
	<p><i class="icon glyphicon glyphicon-info-sign"></i><?php echo $notifikasi_siswa; ?></p>
</div>
<?php } ?>

<div class="alert alert-danger">
	<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
	<p><i class="icon glyphicon glyphicon-exclamation-sign"></i>Untuk dokumen pendukung fotokopi, diwajibkan membawa dokumen asli untuk dicocokkan.</p>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header with-border">
				<i class="glyphicon glyphicon-file"></i>
				<h3 class="box-title text-info"><b>Dokumen Pendukung Yang Diserahkan</b></h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
							<!-- <thead>
								<tr>
									<th class="text-center">Nama Dokumen</th>
									<th class="text-center">Dokumen Fisik</th>
								</tr>
							</thead> -->
							<tbody>
							<?php foreach($dokumenpendukung->getResult() as $row) { 
								if($row->verifikasi==3) {
									continue;
								}

								//filter di sini karena data ppdb2019 yang kotor
								if ($punya_prestasi==0 && $row->daftar_kelengkapan_id==8) {
									continue;
								}

								if ($punya_nilai_un==0 && $row->daftar_kelengkapan_id==3) {
									continue;
								}

								if ($punya_kip==0 && $row->daftar_kelengkapan_id==16) {
									continue;
								}

								if ($masuk_bdt==0 && $row->daftar_kelengkapan_id==20) {
									continue;
								}

								if ($kebutuhan_khusus=='Tidak ada' && $row->daftar_kelengkapan_id==9) {
									continue;
								}
						?>
							<tr>
								<td>
									<div style="display: block; margin-bottom: 15px;"><b><?php echo $row->nama; ?></b></div>
										<img id="dokumen-<?php echo $row->dokumen_id; ?>" class="img-view-thumbnail" 
											src="<?php echo (empty($row->thumbnail_path)) ? '' : $row->thumbnail_path ?>"
											img-path="<?php echo $row->web_path; ?>" 
											img-title="<?php echo $row->nama; ?>"/> 
									<a href="<?php echo (empty($row->web_path)) ? '' : $row->web_path; ?>" target="_blank" class="btn btn-primary" style="margin-left: 10px;">
										Unduh
									</a>
								</td>
								<td style="width: 50%;">
									<?php if($row->verifikasi==3){?>Tidak Ada
									<?php }else if($row->dokumen_fisik==1){?>Asli
									<?php }else if($row->dokumen_fisik==2){?>Fotokopi Dilegalisir<br>(Dokumen asli dibawa untuk dicocokkan)</i>
									<?php }else{?>Fotokopi<br>(Dokumen asli dibawa untuk dicocokkan)<?php }?>
								</td>
							</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="img-view-modal" class="modal fade" role="dialog">
	<div class="container">
	<div class="modal-dialog img-view-dialog">
		<div class="modal-content">
			<div class="modal-header bg-red">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><span id="img-view-title">Akte Kelahiran</span></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<img id="img-view-contain" class="img-view-contain" id="thumb" src="">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
	</div>
</div>

<script>
	function show_img_view(title, src, img_id) {
		$("#img-view-title").html(title);
		$("#img-view-contain").attr("src", src);
		$("#img-view-contain").attr("img-id", img_id);
			

		$('#img-view-modal').modal('show');
	}

	$(document).ready(function() {

		$(".img-view-thumbnail").on('click', function(e) {
			let img_title = $(this).attr('img-title');
			let img_path = $(this).attr('img-path');
			let img_id = $(this).attr('img-id');
			if (typeof img_path === "undefined" || img_path == "") {
				return false;
			}

			show_img_view(img_title, img_path, img_id);
		});
	});

</script>