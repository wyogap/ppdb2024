<?php
	setlocale(LC_ALL,'IND');
	date_default_timezone_set('Asia/Jakarta');

	$pendaftaran_mulai = "";
	$pendaftaran_selesai = "";
	$daftarulang_mulai = "";
	$daftarulang_selesai = "";

	foreach($waktupendaftaran->getResult() as $row) {
		$pendaftaran_mulai = $row->tanggal_mulai_aktif;
		$pendaftaran_selesai = $row->tanggal_selesai_aktif;
	}

	foreach($waktudaftarulang->getResult() as $row) {
		$daftarulang_mulai = $row->tanggal_mulai_aktif;
		$daftarulang_selesai = $row->tanggal_selesai_aktif;
	}

?>

<div class="alert alert-danger">
	<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
	<p><i class="icon glyphicon glyphicon-exclamation-sign"></i>Daftar ulang belum dibuka/sudah ditutup.</p>
</div>

<div class="alert alert-info">
	<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
	<p><i class="icon glyphicon glyphicon-info-sign"></i>Periode Pendaftaran</p>
	<p>Periode pendaftaran adalah dari tanggal <b><?php echo strftime("%d %B %Y %H:%M", strtotime($pendaftaran_mulai)); ?></b> sampai dengan tanggal <b><?php echo strftime("%d %B %Y %H:%M", strtotime($pendaftaran_selesai)); ?></b>.</p>
</div>

<div class="alert alert-info">
	<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
	<p><i class="icon glyphicon glyphicon-info-sign"></i>Periode Daftar Ulang</p>
	<p>Periode daftar ulang adalah dari tanggal <b><?php echo strftime("%d %B %Y %H:%M", strtotime($daftarulang_mulai)); ?></b> sampai dengan tanggal <b><?php echo strftime("%d %B %Y %H:%M", strtotime($daftarulang_selesai)); ?></b>.</p>
</div>

