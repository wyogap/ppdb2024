<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>PPDB ONLINE</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link href="<?php echo base_url();?>assets/image/tutwuri.png" rel="shortcut icon">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/select2-4.0.13/css/select2.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/ionicons/css/ionicons.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/dist/css/AdminLTE.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/datepicker/datepicker3.css">
		<script src="<?php echo base_url();?>assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
		<script src="<?php echo base_url();?>assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>assets/adminlte/plugins/select2/select2.full.min.js"></script>
		<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>
		<script src="<?php echo base_url();?>assets/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>
		<!-- 
		<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css"/>
		<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet'/>
		<link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.css" integrity="sha512-v5YmWLm8KqAAmg5808pETiccEohtt8rPVMGQ1jA6jqkWVydV5Cuz3nJ9fQ7ittSxvuqsvI9RSGfVoKPaAJZ/AQ==" crossorigin="">
		<script src="<?php echo base_url();?>assets/leaflet/leaflet.js"></script>
		<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>
		<script src="https://unpkg.com/esri-leaflet@2.2.4/dist/esri-leaflet.js" integrity="sha512-tyPum7h2h36X52O2gz+Pe8z/3l+Y9S1yEUscbVs5r5aEY5dFmP1WWRY/WLLElnFHa+k1JBQZSCDGwEAnm2IxAQ==" crossorigin=""></script>
		<script src="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.js" integrity="sha512-zdT4Pc2tIrc6uoYly2Wp8jh6EPEWaveqqD3sT0lf5yei19BC1WulGuh5CesB0ldBKZieKGD7Qyf/G0jdSe016A==" crossorigin=""></script>
		-->

		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ppdb.css">

		<!-- Load Leaflet from CDN -->
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
		integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
		crossorigin=""/>
		<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
		integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
		crossorigin=""></script>


		<!-- Load Esri Leaflet from CDN -->
		<script src="https://unpkg.com/esri-leaflet@2.4.1/dist/esri-leaflet.js"
		integrity="sha512-xY2smLIHKirD03vHKDJ2u4pqeHA7OQZZ27EjtqmuhDguxiUvdsOuXMwkg16PQrm9cgTmXtoxA6kwr8KBy3cdcw=="
		crossorigin=""></script>


		<!-- Load Esri Leaflet Geocoder from CDN -->
		<link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.css"
			integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
			crossorigin="">
		<script src="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.js"
			integrity="sha512-HrFUyCEtIpxZloTgEKKMq4RFYhxjJkCiF5sDxuAokklOeZ68U2NPfh4MFtyIVWlsKtVbK5GD2/JzFyAfvT5ejA=="
			crossorigin=""></script>

		<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
		<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>

		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
		<script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>

	</head>
	<body class="hold-transition">
		<?php
			foreach($settingregistrasimadrasah->getResult() as $row):
				$tanggal_mulai_aktif_madrasah = $row->tanggal_mulai_aktif;
				$tanggal_selesai_aktif_madrasah = $row->tanggal_selesai_aktif;
			endforeach;

			$this->load->model('Msetting');
		
			$maxtgllahir="";
			$mintgllahir="";
	
			$batasanusia = $this->Msetting->tcg_batasanusia();
			foreach($batasanusia->getResult() as $row):
				$maxtgllahir = $row->maksimal_tanggal_lahir;
				$mintgllahir = $row->minimal_tanggal_lahir;
			endforeach;
	
		?>

			<div class="wrapper">
			<div class="container">
				<div class="box box-primary">
					<div class="login-logo">
						<a href="javascript:void(0)"><b>Registrasi</b> Siswa <b class="text-green">Luar Daerah</b></a><br>
						<a class="text-white btn btn-primary" href="<?php echo base_url();?>index.php/Clogin/"><b><i class="glyphicon glyphicon-chevron-left"></i></b> Kembali ke halaman <b>Log In</b></a>
					</div>
					<div class="box-body">
						<span><?php if(isset($info)){echo $info;}?></span>

						<?php if ($sukses == 0) { ?>
						<form role="form" enctype="multipart/form-data" id="prosesregistrasimadrasah" action="<?php echo base_url();?>index.php/akun/registrasi/prosesregistrasi/" method="post">
							<div class="box box-solid">
								<div class="box-header with-border">
									<i class="glyphicon glyphicon-book"></i>
									<h3 class="box-title text-info"><b>Pengisian Asal Sekolah</b></h3>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="kode_kabupaten_sekolah">Kabupaten/Kota</label>
												<select id="kode_kabupaten_sekolah" name="kode_kabupaten_sekolah" class="form-control select2" data-validation="required">
													<option value="">-- Pilih Kabupaten/Kota --</option>
													<?php foreach($kabupaten->getResult() as $row):?>
														<option value="<?php echo $row->kode_wilayah;?>"><?php echo $row->kabupaten;?> (<?php echo $row->provinsi;?>)</option>
													<?php endforeach;?>
												</select>
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="bentuk">Jenjang</label>
												<select id="bentuk" name="bentuk" class="form-control select2" data-validation="required">
													<option value="">-- Pilih Jenjang --</option>
													<!--<option value="RA">RA</option>!-->
													<option value="MI">MI</option>
													<option value="SD">SD</option>
												</select>

											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="sekolah_id">Nama Sekolah</label>
												<select id="sekolah_id" name="sekolah_id" class="form-control select2" style="width:100%;" data-validation="required">
													<option value="">-- Pilih Sekolah --</option>
												</select>
												<input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" placeholder="Nama Sekolah (Apabila Tidak Ada Di Daftar)" style="margin-top: 15px; display: none;">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="box box-solid">
								<div class="box-header with-border">
									<i class="glyphicon glyphicon-user"></i>
									<h3 class="box-title text-info"><b>Pengisian Identitas Siswa</b></h3>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="nik">NIK</label>
												<input type="number" class="form-control" id="nik" name="nik" placeholder="NIK" min="1000000000000000" max="9999999999999999" data-validation="required">
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="nisn">NISN</label>
												<input type="text" class="form-control" id="nisn" name="nisn" placeholder="NISN" minlength="10" maxlength="10" data-validation="required">
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="nomor_ujian">Nomor Ujian (Apabila Ada)</label>
												<input type="text" class="form-control" id="nomor_ujian" name="nomor_ujian" placeholder="Nomor Ujian" maxlength="20">
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="nama">Nama</label>
												<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" minlength="3" maxlength="100" data-validation="required">
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="jenis_kelamin">Jenis Kelamin</label>
												<select id="jenis_kelamin" name="jenis_kelamin" class="form-control select2" style="width:100%;" data-validation="required">
													<option value="">-- Pilih Jenis Kelamin --</option>
													<option value="L">Laki-laki</option>
													<option value="P">Perempuan</option>
												</select>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="kebutuhan_khusus">Kebutuhan Khusus</label>
												<select id="kebutuhan_khusus" name="kebutuhan_khusus" class="form-control select2" style="width:100%;" data-validation="required">
													<option value="Tidak ada">Tidak ada</option>
													<option value="A - Tuna netra">A - Tuna netra</option>
													<option value="B - Tuna rungu">B - Tuna rungu</option>
													<option value="C - Tuna grahita ringan">C - Tuna grahita ringan</option>
													<option value="C1 - Tuna grahita sedang">C1 - Tuna grahita sedang</option>
													<option value="D - Tuna daksa ringan">D - Tuna daksa ringan</option>
													<option value="D1 - Tuna daksa sedang">D1 - Tuna daksa sedang</option>
													<option value="E - Tuna laras">E - Tuna laras</option>
													<option value="F - Tuna wicara">F - Tuna wicara</option>
													<option value="K - Kesulitan Belajar">K - Kesulitan Belajar</option>
													<option value="P - Down Syndrome">P - Down Syndrome</option>
													<option value="Q - Autis">Q - Autis</option>
												</select>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="tempat_lahir">Tempat Lahir</label>
												<input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" minlength="3" maxlength="32" data-validation="required">
											</div>
										</div>
										<div id="tanggal_lahir_siswa" class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="tanggal_lahir">Tanggal Lahir</label>
												<input id="tanggal_lahir" name="tanggal_lahir" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required">
											</div>

											<script>
												//Date Picker
												$("#tanggal_lahir").datepicker({ 
													format: 'yyyy-mm-dd',
													startDate: new Date("<?php echo $maxtgllahir; ?>"),
													endDate: new Date("<?php echo $mintgllahir; ?>")
												});
											</script>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="nama_ibu_kandung">Nama Ibu Kandung</label>
												<input type="text" class="form-control" id="nama_ibu_kandung" name="nama_ibu_kandung" placeholder="Nama Ibu Kandung" minlength="3" maxlength="100" data-validation="required">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="box box-solid">
								<div class="box-header with-border">
									<i class="glyphicon glyphicon-road"></i>
									<h3 class="box-title text-info"><b>Pengisian Alamat Siswa</b></h3>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="kode_kabupaten">Kabupaten/Kota</label>
												<select id="kode_kabupaten" name="kode_kabupaten" class="form-control select2" data-validation="required" value="<?php echo $kode_kabupaten??''; ?>">
													<option value="">-- Pilih Kabupaten/Kota --</option>
													<?php foreach($kabupaten->getResult() as $row):?>
														<option value="<?php echo $row->kode_wilayah;?>"><?php echo $row->kabupaten;?> (<?php echo $row->provinsi;?>)</option>
													<?php endforeach;?>
												</select>
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="kode_kecamatan">Kecamatan</label>
												<select id="kode_kecamatan" name="kode_kecamatan" class="form-control select2" data-validation="required" value="<?php echo $kode_kecamatan??''; ?>">
													<option value="">-- Pilih Kecamatan --</option>
												</select>
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="kode_wilayah">Desa/Kelurahan</label>
												<select id="kode_wilayah" name="kode_wilayah" class="form-control select2" data-validation="required" value="<?php echo $kode_wilayah??''; ?>">
													<option value="">-- Pilih Desa/Kelurahan --</option>
												</select>
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="alamat">Alamat</label>
												<input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" minlength="3" maxlength="80" data-validation="required" value="<?php echo $alamat??''; ?>">
											</div>
										</div>
									</div>
								</div>
							</div>

							<?php if (1 == 0) { ?>
							<div id="nilaiusbn">
								<?php view('registrasi/nilaiusbn');?>
							</div>

							<div id="nilaisemester">
								<?php view('registrasi/nilaisemester');?>
							</div>
							<?php } ?>

							<div class="box box-solid">
								<div class="box-header with-border">
									<i class="glyphicon glyphicon-map-marker"></i>
									<h3 class="box-title text-info"><b>Pengisian Lokasi Rumah</b></h3>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
											<div id="peta" style="width: 100%; height: 400px;"></div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<p class="text-info">NB : Silahkan pilih lokasi rumah di peta dengan menggunakan pencarian pada tombol <b>cari</b> setelah itu <b>klik</b> pada lokasi peta.</p>
													<p class="text-danger">Mohon untuk <b>memastikan lokasi rumah</b> mendekati aslinya.</p>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="form-group has-feedback">
														<label for="lintang">Lintang</label>
														<input type="number" readonly="false" class="form-control" id="lintang" name="lintang" placeholder="Lintang" data-validation="required" value="<?php echo $lintang??''; ?>">
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="form-group has-feedback">
														<label for="bujur">Bujur</label>
														<input type="number" readonly="false" class="form-control" id="bujur" name="bujur" placeholder="Bujur" data-validation="required" value="<?php echo ($bujur??''); ?>">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="box box-solid">
								<div class="box-header with-border">
									<i class="glyphicon glyphicon-phone"></i>
									<h3 class="box-title text-info"><b>Nomor Handphone Aktif</b></h3>
								</div>
								<div class="box-body">
									<div class="row">
										<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
											<button class="btn">Baru</button>
											<button class="btn">Hapus</button>
										</div> -->
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<table class="table table-striped" style="margin-bottom: 0px !important;">
												<tr>
													<td colspan="1">Sebagai bagian dari proses verifikasi dokumen secara dalam jaringan (daring), kami membutuhkan nomor handphone aktif yang bisa dihubungi sebagai media komunikasi apabila ada dokumen yang perlu diperbaiki ataupun persyaratan tambahan yang perlu dilengkapi.  
													</td>
												</tr>
												<tr id="kontak-ubah-row">
													<td colspan="1"><div class="form-group has-feedback"><label for="nomor_kontak">Nomor handphone aktif: </label><input  id="nomor_kontak" name="nomor_kontak" type="text" data-validation="required" value=""></input></div>
													</td>
												</tr>
											</table>
										</div>
									</div>
								</div>
							</div>

							<div class="box box-solid">
								<div class="box-header with-border">
									<i class="glyphicon glyphicon-check"></i>
									<h3 class="box-title text-info"><b>Registrasi</b></h3>
								</div>
								<div class="box-body">
									<div class="row">
										<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
											<button class="btn">Baru</button>
											<button class="btn">Hapus</button>
										</div> -->
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<p>Pastikan semua data yang anda masukkan benar sebelum menekan tombol <b>Registrasi</b> di bawah.</p>
										</div>
									</div>
								</div>
								<div class="box-footer">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<button type="submit" class="btn btn-primary btn-flat" <?php if(date('Y-m-d H:i:s:u')<$tanggal_mulai_aktif_madrasah||date('Y-m-d H:i:s:u')>$tanggal_selesai_aktif_madrasah){?> disabled="true" <?php }?>>Registrasi Siswa</button>
										</div>
									</div>
								</div>
							</div>
						</form>
						<?php } ?>
					</div>
					<div class="box-footer">
						<strong>Copyright &copy; 2019 <a href="javascript:void(0)">Dinas Pendidikan <?php echo $wilayah_aktif;?></a>.</strong> All rights reserved.
					</div>
				</div>
			</div>
		</div>
		<script>
			//Dropdown Select
			$(function () {
				$(".select2").select2();
			});
			
			var kode_kabupaten_sekolah = <?php if (isset($kode_kabupaten_sekolah)) { echo "'$kode_kabupaten_sekolah'"; } else { echo "''"; } ?>;
			var sekolah_id = <?php if (isset($sekolah_id)) { echo "'$sekolah_id'"; } else { echo "''"; } ?>;
			var bentuk_sekolah = <?php if (isset($bentuk_sekolah)) { echo "'$bentuk_sekolah'"; } else { echo "''"; } ?>;
			var nik = <?php if (isset($nik)) { echo "'$nik'"; } else { echo "''"; } ?>;
			var nisn = <?php if (isset($nisn)) { echo "'$nisn'"; } else { echo "''"; } ?>;
			var nomor_ujian = <?php if (isset($nomor_ujian)) { echo "'$nomor_ujian'"; } else { echo "''"; } ?>;
			var nama = <?php if (isset($nama)) { echo "'$nama'"; } else { echo "''"; } ?>;
			var jenis_kelamin = <?php if (isset($jenis_kelamin)) { echo "'$jenis_kelamin'"; } else { echo "''"; } ?>;
			var tempat_lahir = <?php if (isset($tempat_lahir)) { echo "'$tempat_lahir'"; } else { echo "''"; } ?>;
			var tanggal_lahir = <?php if (isset($tanggal_lahir)) { echo "'$tanggal_lahir'"; } else { echo "''"; } ?>;
			var nama_ibu_kandung = <?php if (isset($nama_ibu_kandung)) { echo "'$nama_ibu_kandung'"; } else { echo "''"; } ?>;
			var kebutuhan_khusus = <?php if (isset($kebutuhan_khusus)) { echo "'$kebutuhan_khusus'"; } else { echo "''"; } ?>;
			var alamat = <?php if (isset($alamat)) { echo "'$alamat'"; } else { echo "''"; } ?>;
			var kode_kabupaten = <?php if (isset($kode_kabupaten)) { echo "'$kode_kabupaten'"; } else { echo "''"; } ?>;
			var kode_kecamatan = <?php if (isset($kode_kecamatan)) { echo "'$kode_kecamatan'"; } else { echo "''"; } ?>;
			var kode_desa = <?php if (isset($kode_desa)) { echo "'$kode_desa'"; } else { echo "''"; } ?>;
			var kode_wilayah = <?php if (isset($kode_wilayah)) { echo "'$kode_wilayah'"; } else { echo "''"; } ?>;
			var lintang = <?php if (isset($lintang)) { echo "'$lintang'"; } else { echo "''"; } ?>;
			var bujur = <?php if (isset($bujur)) { echo "'$bujur'"; } else { echo "''"; } ?>;
			var nama_sekolah = <?php if (isset($nama_sekolah)) { echo "'$nama_sekolah'"; } else { echo "''"; } ?>;
			var nomor_kontak = <?php if (isset($nomor_kontak)) { echo "'$nomor_kontak'"; } else { echo "''"; } ?>;

			//Event On Change Dropdown
			$(document).ready(function () {

				$('#kode_kabupaten_sekolah').on('change', function() {
					let val1 = $("#kode_kabupaten_sekolah").val();
					if (val1 == kode_kabupaten_sekolah && bentuk_sekolah != "") {
						$("#bentuk").val(bentuk_sekolah).change();
					}

					kode_kabupaten_sekolah = val1;
				});

				$('select[name="bentuk"]').on('change', function() {
					let val1 = $("#kode_kabupaten_sekolah").val();
					let val2 = $("#bentuk").val();
					var data = {kode_wilayah:val1,bentuk:val2};
					$.ajax({
						type: "POST",
						url : "<?php echo site_url('Cdropdown/sekolah')?>",
						data: data,
						success: function(msg){
							$('#sekolah_id').html(msg);
							if (val1 == kode_kabupaten_sekolah && val2 == bentuk_sekolah && sekolah_id != "") {
								$('#sekolah_id').val(sekolah_id).change();
							}
							else {
								$('#sekolah_id').val("").change();
							}

							kode_kabupaten_sekolah = val1;
							bentuk_sekolah = val2;
						}
					});
				});

				$('select[name="sekolah_id"]').on('change', function() {
					var val = $("#sekolah_id").val();
					var txt = $( "#sekolah_id option:selected" ).text();
					if (val == "") {
						$('#nama_sekolah').prop("disabled", false);
						$('#nama_sekolah').prop("placeholder", "Masukkan Nama Sekolah Di Sini");
						$('#nama_sekolah').attr("data-validation", "required");
						$('#nama_sekolah').show();
						$('#sekolah_id').attr("data-validation", "");
						if (nama_sekolah != "") {
							$('#nama_sekolah').val(nama_sekolah);
						}
					}
					else {
						$('#nama_sekolah').prop("disabled", true);
						$('#nama_sekolah').prop("placeholder", "");
						$('#nama_sekolah').attr("data-validation", "");
						$('#nama_sekolah').val("");
						$('#nama_sekolah').hide();
						$('#sekolah_id').attr("data-validation", "required");
					}

					sekolah_id = val;
				});

				$('#nama_sekolah').on('change', function() {
					nama_sekolah = $('#nama_sekolah').val();
				});

				$('select[name="kode_kabupaten"]').on('change', function() {
					let val = $("#kode_kabupaten").val();
					var data = {kode_wilayah:val};
					$.ajax({
						type: "POST",
						url : "<?php echo site_url('Cdropdown/kecamatan')?>",
						data: data,
						success: function(msg){
							$('#kode_kecamatan').html(msg);
							if (val == kode_kabupaten && kode_kecamatan != "") {
								$('#kode_kecamatan').val(kode_kecamatan).change();
							}
							kode_kabupaten = val;
						}
					});
				});

				$('select[name="kode_kecamatan"]').on('change', function() {
					let val = $("#kode_kecamatan").val();
					var data = {kode_wilayah:val};
					$.ajax({
						type: "POST",
						url : "<?php echo site_url('Cdropdown/desa')?>",
						data: data,
						success: function(msg){
							$('#kode_wilayah').html(msg);
							if (val == kode_kecamatan && kode_wilayah != "") {
								$('#kode_wilayah').val(kode_wilayah).change();
							}
							kode_kecamatan = val;
						}
					});
				});

				$('select[name="kode_wilayah"]').on('change', function() {
					kode_wilayah = $("#kode_wilayah").val();
				});

				if (kode_kabupaten_sekolah != "") {
					$("#kode_kabupaten_sekolah").val(kode_kabupaten_sekolah).change();
				}

				// if (bentuk_sekolah != "") {
				// 	$("#bentuk_sekolah").val(bentuk_sekolah).change();
				// }

				// if (sekolah_id != "") {
				// 	$("#sekolah_id").val(sekolah_id).change();
				// }

				// if (nama_sekolah != "") {
				// 	$("#nama_sekolah").val(nama_sekolah);
				// }

				if (nik != "") {
					$("#nik").val(nik);
				}
				if (nisn != "") {
					$("#nisn").val(nisn);
				}
				if (nomor_ujian != "") {
					$("#nomor_ujian").val(nomor_ujian);
				}
				if (nama != "") {
					$("#nama").val(nama);
				}

				if (jenis_kelamin != "") {
					$("#jenis_kelamin").val(jenis_kelamin).change();
				}

				if (tempat_lahir != "") {
					$("#tempat_lahir").val(tempat_lahir);
				}
				if (tanggal_lahir != "") {
					$("#tanggal_lahir").val(tanggal_lahir);
				}
				if (nama_ibu_kandung != "") {
					$("#nama_ibu_kandung").val(nama_ibu_kandung);
				}

				if (kebutuhan_khusus != "") {
					$("#kebutuhan_khusus").val(kebutuhan_khusus).change();
				}

				if (alamat != "") {
					$("#alamat").val(alamat);
				}

				if (kode_kabupaten != "") {
					$("#kode_kabupaten").val(kode_kabupaten).change();
				}

				// if (kode_kecamatan != "") {
				// 	$("#kode_kecamatan").val(kode_kecamatan).change();
				// }

				// if (kode_desa != "") {
				// 	$("#kode_wilayah").val(kode_desa).change();
				// }

				if (lintang != "") {
					$("#lintang").val(lintang);
				}

				if (bujur != "") {
					$("#bujur").val(bujur);
				}

				if (lintang != "" && bujur != "") {
					layerGroup.clearLayers();
					new L.marker([lintang,bujur]).addTo(layerGroup).bindPopup("Lokasi :<br>"+lintang+" , "+bujur).openPopup();
				}

				if (nomor_kontak != "") {
					$("#nomor_kontak").val(nomor_kontak);
				}

			});

			//Validasi
			var myLanguage = {
				errorTitle: 'Gagal mengirim data. Belum mengisi semua data wajib:',
				requiredFields: 'Belum mengisi semua data wajib',
			};
			
			var $messages = $('#error-message-wrapper');
			$.validate({
				language : myLanguage,
				ignore: [],
				modules: 'security',
				errorMessagePosition: "top",
				scrollToTopOnError: true,
				validateHiddenInputs: true
			});

			//Peta
			var map = L.map('peta',{zoomControl:false}).setView([<?php echo $lintang_aktif;?>,<?php echo $bujur_aktif;?>],10);
			//var map = L.map('peta',{zoomControl:false}).setView([<?php echo $lintang_aktif;?>,<?php echo $bujur_aktif;?>],10);
			var tile = L.tileLayer(
				'<?php echo $servis_peta_aktif;?>',{maxZoom: 18,attribution: 'PPDB <?php echo $wilayah_aktif;?>',id: 'mapbox.streets'}
			).addTo(map);

		    var streetmap   = L.tileLayer('<?php echo $streetmap_aktif;?>', {id: 'mapbox.light', attribution: ''}),
		        satelitemap  = L.tileLayer('<?php echo $satelitemap_aktif;?>', {id: 'mapbox.streets',   attribution: ''});

			var baseLayers = {
		        "Streets": streetmap,
		        "Satelite": satelitemap
		    };

		    var overlays = {};
			L.control.layers(baseLayers,overlays).addTo(map);

			var layerGroup = L.layerGroup().addTo(map);
			function onMapClick(e) {
				layerGroup.clearLayers();
				var lintang = e.latlng.lat;
                var bujur = e.latlng.lng;
				new L.marker(e.latlng).addTo(layerGroup).bindPopup("Lokasi :<br>"+lintang+" , "+bujur).openPopup();
				document.getElementById("lintang").value=lintang;
				document.getElementById("bujur").value=bujur;
			}
			map.on('click', onMapClick);

			var searchControl = L.esri.Geocoding.geosearch().addTo(map);
			searchControl.on('layerGroup', function(data){
				layerGroup.clearLayers();
			});

			new L.Control.Fullscreen({position:'bottomleft'}).addTo(map);
			new L.Control.Zoom({position:'bottomright'}).addTo(map);

			new L.Control.EasyButton( '<span class="map-button">&curren;</span>', function(){
				map.setView([<?php echo $lintang_aktif;?>,<?php echo $bujur_aktif;?>],10);;
			}, {position: 'topleft'}).addTo(map);

			streetmap.addTo(map);
   
		</script>
	</body>
</html>
