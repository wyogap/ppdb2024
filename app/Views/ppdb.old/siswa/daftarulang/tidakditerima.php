<?php
	setlocale(LC_ALL,'IND');
	date_default_timezone_set('Asia/Jakarta');

	$pendaftaran_mulai = "";
	$pendaftaran_selesai = "";
	$pendaftaransusulan_mulai = "";
	$pendaftaransusulan_selesai = "";
	$notifikasi_siswa = "";

	foreach($waktupendaftaransusulan->getResult() as $row) {
		$pendaftaransusulan_mulai = $row->tanggal_mulai_aktif;
		$pendaftaransusulan_selesai = $row->tanggal_selesai_aktif;
		$notifikasi_siswa = $row->notifikasi_siswa;
	}

	foreach($waktupendaftaran->getResult() as $row) {
		$pendaftaran_mulai = $row->tanggal_mulai_aktif;
		$pendaftaran_selesai = $row->tanggal_selesai_aktif;
	}

?>

<?php if ($pendaftaran == 0) { ?>
	<div class="alert alert-warning">
		<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
		<p><i class="icon glyphicon glyphicon-info-sign"></i>Anda belum melakukan pendaftaran</p>
	</div>

	<?php if ($pendaftaran_mulai != "") { ?>
		<div class="alert alert-info">
			<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
			<p><i class="icon glyphicon glyphicon-info-sign"></i>Periode Pendaftaran</p>
			<p>Periode pendaftaran adalah dari tanggal <b><?php echo strftime("%d %B %Y %H:%M", strtotime($pendaftaran_mulai)); ?></b> sampai dengan tanggal <b><?php echo strftime("%d %B %Y %H:%M", strtotime($pendaftaran_selesai)); ?></b>.</p>
		</div>
	<?php } ?>
<?php } else { ?>
	<div class="alert alert-info">
		<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
		<p><i class="icon glyphicon glyphicon-info-sign"></i>Tidak Diterima</p>
		<p>Mohon maaf. Anda tidak diterima di sekolah pilihan tempat anda mendaftar.
	</div>

	<?php if ($pendaftaransusulan_mulai != "") { ?>
	<div class="alert alert-info">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<p><i class="icon glyphicon glyphicon-info-sign"></i>Periode Pendaftaran Susulan</p>
		<p>Periode pendaftaran susulan adalah dari tanggal <b><?php echo strftime("%d %B %Y %H:%M", strtotime($pendaftaransusulan_mulai)); ?></b> sampai dengan tanggal <b><?php echo strftime("%d %B %Y %H:%M", strtotime($pendaftaransusulan_selesai)); ?></b>.</p>
		<?php if ($notifikasi_siswa != "") { ?>
		<p><?php echo $notifikasi_siswa; ?></p>
		<?php } ?>
	</div>
	<?php } ?>
<?php } ?>

