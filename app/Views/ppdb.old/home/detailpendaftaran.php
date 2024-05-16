<?php
	global $kelengkapan_data;
    global $bisa_perubahan;

	function tanggal_indo($tanggal)
	{
		$bulan = array (1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$split = explode('-', $tanggal);
		return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
	}

	$profil_konfirmasi = 0;
	$lokasi_konfirmasi = 0;
	$nilai_konfirmasi = 0;
	$prestasi_konfirmasi = 0;
	$afirmasi_konfirmasi = 0;
	$inklusi_konfirmasi = 0;
	$pernyataan_file = "";
	$nomor_handphone = "";

	//var_dump($statusprofil->row_array());

	foreach($statusprofil->getResult() as $row) {
		$profil_konfirmasi = $row->konfirmasi_profil;
		$lokasi_konfirmasi = $row->konfirmasi_lokasi;
		$nilai_konfirmasi = $row->konfirmasi_nilai;
		$prestasi_konfirmasi = $row->konfirmasi_prestasi;
		$afirmasi_konfirmasi = $row->konfirmasi_afirmasi;
		$inklusi_konfirmasi = $row->konfirmasi_inklusi;

		//if verification is performed, use verification value.
		//this is mostly for old data which are imported from ppdb2019 where the flow is different
		if ($row->verifikasi_profil != 0) $profil_konfirmasi = $row->verifikasi_profil;
		if ($row->verifikasi_lokasi != 0) $lokasi_konfirmasi = $row->verifikasi_lokasi;
		if ($row->verifikasi_nilai != 0) $nilai_konfirmasi = $row->verifikasi_nilai;
		if ($row->verifikasi_prestasi != 0) $prestasi_konfirmasi = $row->verifikasi_prestasi;
		if ($row->verifikasi_afirmasi != 0) $afirmasi_konfirmasi = $row->verifikasi_afirmasi;
		if ($row->verifikasi_inklusi != 0) $inklusi_konfirmasi = $row->verifikasi_inklusi;

		$nomor_handphone = $row->nomor_kontak;
		$pernyataan_file = $row->path_surat_pernyataan;

		$verifikasi_oleh = $row->nama_verifikator;
		$verifikasi_tanggal = $row->tanggal_verifikasi;
	}

	// $tahapan_nama = "";
	// $tahapan_id = 0;
	// $notifikasi_siswa = "";
	// foreach($tahapan->getResult() as $row) {
	// 	$tahapan_nama = $row->tahapan;
	// 	$tahapan_id = $row->tahapan_id;
	// 	$notifikasi_siswa = $row->notifikasi_siswa;
	// }

	$kelengkapan_data = 1;
	if ($profil_konfirmasi != 1 || $lokasi_konfirmasi != 1 || $nilai_konfirmasi != 1 || $prestasi_konfirmasi != 1 || $afirmasi_konfirmasi != 1 
			|| $inklusi_konfirmasi != 1 || empty($nomor_handphone) || empty($pernyataan_file)) {
		$kelengkapan_data = 0;
	}

	//echo "$kelengkapan_data,$profil_konfirmasi,$lokasi_konfirmasi,$nilai_konfirmasi,$prestasi_konfirmasi,$afirmasi_konfirmasi,$inklusi_konfirmasi,$nomor_handphone,$pernyataan_file";

	//jangan munculkan menu perubahan
	$bisa_perubahan = 0;
?>

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
							<?php foreach($profilsiswa->getResult() as $row):?>
							<i class="glyphicon glyphicon-user"></i> <?php echo $row->nama;?>
							<?php endforeach;?>
						</h1>
						<ol class="breadcrumb">
							<li class="active"><a href="#" onclick="window.history.back();"><i class="glyphicon glyphicon-remove"></i> Kembali </a></li>
						</ol>
					</section>
					<section class="content">

						<?php view('siswa/pendaftaran/daftarpendaftaran');?>

					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>
</html>