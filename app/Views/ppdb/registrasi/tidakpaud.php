<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>PPDB ONLINE</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/select2-4.0.13/css/select2.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/ionicons/css/ionicons.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/dist/css/AdminLTE.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/datepicker/datepicker3.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css"/>
		<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet'/>
		<link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.css" integrity="sha512-v5YmWLm8KqAAmg5808pETiccEohtt8rPVMGQ1jA6jqkWVydV5Cuz3nJ9fQ7ittSxvuqsvI9RSGfVoKPaAJZ/AQ==" crossorigin="">
	</head>
	<body class="hold-transition">
		<?php
			foreach($settingregistrasitidakpaud->getResult() as $row):
				$tanggal_mulai_aktif_tidak_paud = $row->tanggal_mulai_aktif;
				$tanggal_selesai_aktif_tidak_paud = $row->tanggal_selesai_aktif;
			endforeach;
		?>
		<div class="wrapper">
			<div class="container">
				<div class="box box-primary">
					<div class="login-logo">
						<a href="javascript:void(0)"><b>Registrasi</b> Siswa <b class="text-orange">Tidak PAUD</b></a><br>
						<a class="text-white btn btn-primary" href="<?php echo base_url();?>index.php/Clogin/"><b><i class="glyphicon glyphicon-chevron-left"></i></b> Kembali ke halaman <b>Log In</b></a>
					</div>
					<div class="box-body">
						<span class="text-red"><?php echo validation_errors();?></span>
						<span><?php if(isset($info)){echo $info;}?></span>
						<form role="form" enctype="multipart/form-data" id="prosesregistrasitidakpaud" action="<?php echo base_url();?>index.php/Clogin/prosesregistrasitidakpaud/" method="post">
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
										<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
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
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="tanggal_lahir">Tanggal Lahir</label>
												<input id="tanggal_lahir" name="tanggal_lahir" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required">
											</div>
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
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="kode_kabupaten">Kabupaten/Kota</label>
												<select id="kode_kabupaten" name="kode_kabupaten" class="form-control select2" data-validation="required">
													<option value="">-- Pilih Kabupaten/Kota --</option>
													<?php foreach($kabupaten->getResult() as $row):?>
														<option value="<?php echo $row->kode_wilayah;?>"><?php echo $row->kabupaten;?> (<?php echo $row->provinsi;?>)</option>
													<?php endforeach;?>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="kode_kecamatan">Kecamatan</label>
												<select id="kode_kecamatan" name="kode_kecamatan" class="form-control select2" data-validation="required">
													<option value="">-- Pilih Kecamatan --</option>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="kode_desa">Desa/Kelurahan</label>
												<select id="kode_desa" name="kode_desa" class="form-control select2" data-validation="required">
													<option value="">-- Pilih Desa/Kelurahan --</option>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="kode_wilayah">Padukuhan</label>
												<select id="kode_wilayah" name="kode_wilayah" class="form-control select2">
													<option value="">-- Pilih Padukuhan --</option>
												</select>
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="alamat">Alamat</label>
												<input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" minlength="3" maxlength="80" data-validation="required">
											</div>
										</div>
									</div>
								</div>
							</div>
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
														<input type="number" readonly="true" class="form-control" id="lintang" name="lintang" placeholder="Lintang" data-validation="required">
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="form-group has-feedback">
														<label for="bujur">Bujur</label>
														<input type="number" readonly="true" class="form-control" id="bujur" name="bujur" placeholder="Bujur" data-validation="required">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="box-footer">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<button type="submit" class="btn btn-primary btn-flat" <?php if(date('Y-m-d H:i:s:u')<$tanggal_mulai_aktif_tidak_paud||date('Y-m-d H:i:s:u')>$tanggal_selesai_aktif_tidak_paud){?> disabled="true" <?php }?>>Registrasi Siswa</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="box-footer">
						<strong>Copyright &copy; 2019 <a href="javascript:void(0)">Dinas Pendidikan <?php echo $wilayah_aktif;?></a>.</strong> All rights reserved.
					</div>
				</div>
			</div>
		</div>
		<script src="<?php echo base_url();?>assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
		<script src="<?php echo base_url();?>assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>assets/adminlte/plugins/select2/select2.full.min.js"></script>
		<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>
		<script src="<?php echo base_url();?>assets/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>
		<script src="<?php echo base_url();?>assets/leaflet/leaflet.js"></script>
		<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>
		<script src="https://unpkg.com/esri-leaflet@2.2.4/dist/esri-leaflet.js" integrity="sha512-tyPum7h2h36X52O2gz+Pe8z/3l+Y9S1yEUscbVs5r5aEY5dFmP1WWRY/WLLElnFHa+k1JBQZSCDGwEAnm2IxAQ==" crossorigin=""></script>
		<script src="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.js" integrity="sha512-zdT4Pc2tIrc6uoYly2Wp8jh6EPEWaveqqD3sT0lf5yei19BC1WulGuh5CesB0ldBKZieKGD7Qyf/G0jdSe016A==" crossorigin=""></script>
		<script>
			//Dropdown Select
			$(function () {
				$(".select2").select2();
			});
			//Date Picker
			$("#tanggal_lahir").datepicker({ 
				format: 'yyyy-mm-dd',
				startDate: new Date('2009-07-01'),
				endDate: new Date('2014-01-01')
			});
			//Event On Change Dropdown
			$(document).ready(function () {
				$('select[name="kode_kabupaten"]').on('change', function() {
					var data = {kode_wilayah:$("#kode_kabupaten").val()};
					$.ajax({
						type: "POST",
						url : "<?php echo site_url('Cdropdown/kecamatan')?>",
						data: data,
						success: function(msg){
							$('#kode_kecamatan').html(msg);
						}
					});
				});
				$('select[name="kode_kecamatan"]').on('change', function() {
					var data = {kode_wilayah:$(kode_kecamatan).val()};
					$.ajax({
						type: "POST",
						url : "<?php echo site_url('Cdropdown/desa')?>",
						data: data,
						success: function(msg){
							$('#kode_desa').html(msg);
						}
					});
				});
				$('select[name="kode_desa"]').on('change', function() {
					var data = {kode_wilayah:$(kode_desa).val()};
					$.ajax({
						type: "POST",
						url : "<?php echo site_url('Cdropdown/padukuhan')?>",
						data: data,
						success: function(msg){
							$('#kode_wilayah').html(msg);
						}
					});
				});
			});
			//Validasi
			var $messages = $('#error-message-wrapper');
			$.validate({
				modules: 'security',
				errorMessagePosition: $messages,
				scrollToTopOnError: false
			});
			//Peta
			var map = L.map('peta',{zoomControl:false}).setView([<?php echo $lintang_aktif;?>,<?php echo $bujur_aktif;?>],10);
			L.tileLayer(
				'<?php echo $servis_peta_aktif;?>',{maxZoom: 18,attribution: 'PPDB <?php echo $wilayah_aktif;?>',id: 'mapbox.streets'}
			).addTo(map);

		    var streetmap   = L.tileLayer('<?php echo $streetmap_aktif;?>', {id: 'mapbox.light', attribution: ''}),
		        satelitemap  = L.tileLayer('<?php echo $satelitemap_aktif;?>', {id: 'mapbox.streets',   attribution: ''});
		    var baseLayers = {
		        "Satelite": satelitemap,
		        "Streets": streetmap
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
			new L.control.fullscreen({position:'bottomleft'}).addTo(map);
			new L.Control.Zoom({position:'bottomright'}).addTo(map);
		</script>
	</body>
</html>
