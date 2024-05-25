<?php
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
?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/iCheck/all.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css"/>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet'/>
<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.css" integrity="sha512-v5YmWLm8KqAAmg5808pETiccEohtt8rPVMGQ1jA6jqkWVydV5Cuz3nJ9fQ7ittSxvuqsvI9RSGfVoKPaAJZ/AQ==" crossorigin="">


<span><?php if(isset($info)){echo $info;}?></span>
<?php if(isset($msg)){?>
			<div class="alert alert-info alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<i class="icon glyphicon glyphicon-info-sign"></i><?php echo $msg; ?>
			</div>
<?php } ?>

<!-- <div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_1" data-toggle="tab">Identitas</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_1"> -->
			<?php foreach($profilsiswa->getResult() as $row):?>
			<!-- <div class="row"> -->
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="row">
					<div class="box box-solid">
						<!-- <div class="box-header with-border">
							<i class="glyphicon glyphicon-user"></i>
							<h3 class="box-title text-info"><b>Identitas Siswa</b></h3>
						</div> -->
						<div class="box-body">
							<div class="box box-solid">
								<div class="box-header with-border">
									<i class="glyphicon glyphicon-book"></i>
									<h3 class="box-title text-info"><b>Asal Sekolah</b></h3>
								</div>
								<div class="box-body">
									<table class="table">
										<tr>
											<td><b>Asal Sekolah</b></td>
											<td>:</td>
											<td>
											<?php if($row->sekolah_id!=""){?>
													<p>(<b><?php echo $row->npsn;?></b>) <?php echo $row->sekolah;?></p>
													<a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/<?php echo $row->sekolah_id;?>" target="_blank" class="btn btn-default">Profil Sekolah Asal</a>
												<?php }else{?>
													<p>Tidak bersekolah sebelumnya.</p>
												<?php }?>
											</td>
										</tr>
									</table>
								</div>
							</div>

							<form role="form" enctype="multipart/form-data" id="ubahprofilsiswa" action="<?php echo base_url();?>index.php/Cakun/ubahprofilsiswa" method="post">
									<input type="hidden" id="pengguna_id" name="pengguna_id" value="<?php echo $row->peserta_didik_id;?>">
							<div class="box box-solid">
								<div class="box-header with-border">
									<i class="glyphicon glyphicon-user"></i>
									<h3 class="box-title text-info"><b>Profil Siswa</b></h3>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="nik">NIK</label>
												<input type="number" class="form-control" id="nik" name="nik" placeholder="NIK" min="1000000000000000" max="9999999999999999" data-validation="required" value="<?php echo $row->nik; ?>">
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="nisn">NISN</label>
												<input type="text" class="form-control" id="nisn" name="nisn" placeholder="NISN" minlength="10" maxlength="10" data-validation="required" value="<?php echo $row->nisn; ?>">
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="nomor_ujian">Nomor Ujian</label>
												<input type="text" class="form-control" id="nomor_ujian" name="nomor_ujian" placeholder="Nomor Ujian" maxlength="20" value="<?php echo $row->nomor_ujian; ?>">
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="nama">Nama</label>
												<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" minlength="3" maxlength="100" data-validation="required" value="<?php echo $row->nama; ?>">
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="jenis_kelamin">Jenis Kelamin</label>
												<select id="jenis_kelamin" name="jenis_kelamin" class="form-control select2" style="width:100%;" data-validation="required">
													<option value="">-- Pilih Jenis Kelamin --</option>
													<option value="L" <?php if($row->jenis_kelamin=='L'){?>selected="true"<?php }?>>Laki-laki</option>
													<option value="P" <?php if($row->jenis_kelamin=='P'){?>selected="true"<?php }?>>Perempuan</option>
												</select>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="kebutuhan_khusus">Kebutuhan Khusus</label>
												<select id="kebutuhan_khusus" name="kebutuhan_khusus" class="form-control select2" style="width:100%;" data-validation="required" value="<?php echo $row->kebutuhan_khusus; ?>">
													<option value="Tidak ada" <?php if($row->kebutuhan_khusus=='Tidak ada'){?>selected="true"<?php }?>>Tidak ada</option>
													<option value="A - Tuna netra" <?php if($row->kebutuhan_khusus=='A - Tuna netra'){?>selected="true"<?php }?>>A - Tuna netra</option>
													<option value="B - Tuna rungu" <?php if($row->kebutuhan_khusus=='B - Tuna rungu'){?>selected="true"<?php }?>>B - Tuna rungu</option>
													<option value="C - Tuna grahita ringan" <?php if($row->kebutuhan_khusus=='C - Tuna grahita ringan'){?>selected="true"<?php }?>>C - Tuna grahita ringan</option>
													<option value="C1 - Tuna grahita sedang" <?php if($row->kebutuhan_khusus=='C1 - Tuna grahita sedang'){?>selected="true"<?php }?>>C1 - Tuna grahita sedang</option>
													<option value="D - Tuna daksa ringan" <?php if($row->kebutuhan_khusus=='D - Tuna daksa ringan'){?>selected="true"<?php }?>>D - Tuna daksa ringan</option>
													<option value="D1 - Tuna daksa sedang" <?php if($row->kebutuhan_khusus=='D1 - Tuna daksa sedang'){?>selected="true"<?php }?>>D1 - Tuna daksa sedang</option>
													<option value="E - Tuna laras" <?php if($row->kebutuhan_khusus=='E - Tuna laras'){?>selected="true"<?php }?>>E - Tuna laras</option>
													<option value="F - Tuna wicara" <?php if($row->kebutuhan_khusus=='F - Tuna wicara'){?>selected="true"<?php }?>>F - Tuna wicara</option>
													<option value="K - Kesulitan Belajar" <?php if($row->kebutuhan_khusus=='K - Kesulitan Belajar'){?>selected="true"<?php }?>>K - Kesulitan Belajar</option>
													<option value="P - Down Syndrome" <?php if($row->kebutuhan_khusus=='P - Down Syndrome'){?>selected="true"<?php }?>>P - Down Syndrome</option>
													<option value="Q - Autis" <?php if($row->kebutuhan_khusus=='Q - Autis'){?>selected="true"<?php }?>>Q - Autis</option>
												</select>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="tempat_lahir">Tempat Lahir</label>
												<input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" minlength="3" maxlength="32" data-validation="required" value="<?php echo $row->tempat_lahir; ?>">
											</div>
										</div>
										<div id="tanggal_lahir_siswa" class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="tanggal_lahir">Tanggal Lahir</label>
												<input id="tanggal_lahir" name="tanggal_lahir" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required" value="<?php echo $row->tanggal_lahir; ?>">
											</div>

										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="nama_ibu_kandung">Nama Ibu Kandung</label>
												<input type="text" class="form-control" id="nama_ibu_kandung" name="nama_ibu_kandung" placeholder="Nama Ibu Kandung" minlength="3" maxlength="100" data-validation="required" value="<?php echo $row->nama_ibu_kandung; ?>">
											</div>
										</div>
									</div>
								</div>
								<div class="box-footer">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<button type="submit" class="btn btn-primary btn-flat">Ubah Data</button>
										</div>
									</div>
								</div>
							</div>
							</form>

							<form role="form" enctype="multipart/form-data" id="ubahalamatsiswa" action="<?php echo base_url();?>index.php/Cakun/ubahalamatsiswa" method="post">
									<input type="hidden" id="pengguna_id" name="pengguna_id" value="<?php echo $row->peserta_didik_id;?>">
							<div class="box box-solid">
								<div class="box-header with-border">
									<i class="glyphicon glyphicon-road"></i>
									<h3 class="box-title text-info"><b>Alamat Siswa</b></h3>
								</div>
								<?php
									$kode_kabupaten = $row->kode_kabupaten;
									$kode_kecamatan = $row->kode_kecamatan;
									$kode_desa = $row->kode_desa;
								?>
								<div class="box-body">
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="kode_kabupaten">Kabupaten/Kota</label>
												<select id="kode_kabupaten" name="kode_kabupaten" class="form-control select2" data-validation="required">
													<option value="">-- Pilih Kabupaten/Kota --</option>
													<?php foreach($kabupaten->getResult() as $row2):?>
														<option value="<?php echo $row2->kode_wilayah;?>" <?php if($row2->kode_wilayah==$kode_kabupaten){?>selected="true"<?php }?>>
															<?php echo $row2->kabupaten;?> (<?php echo $row2->provinsi;?>)
														</option>
													<?php endforeach;?>
												</select>
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="kode_kecamatan">Kecamatan</label>
												<select id="kode_kecamatan" name="kode_kecamatan" class="form-control select2" data-validation="required">
													<option value="">-- Pilih Kecamatan --</option>
													<?php
														$kecamatandatasiswa = $this->Mdropdown->tcg_kecamatan($kode_kabupaten);
														foreach($kecamatandatasiswa->getResult() as $row2):
													?>
													<option value="<?php echo $row2->kode_wilayah;?>" <?php if($row2->kode_wilayah==$kode_kecamatan){?>selected="true"<?php }?>><?php echo $row2->nama;?></option>
													<?php endforeach;?>
												</select>
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="kode_desa">Desa/Kelurahan</label>
												<select id="kode_desa" name="kode_desa" class="form-control select2" data-validation="required">
													<option value="">-- Pilih Desa/Kelurahan --</option>
													<?php
														$desaubahdatasiswa = $this->Mdropdown->tcg_desa($kode_kecamatan);
														foreach($desaubahdatasiswa->getResult() as $row2):
													?>
													<option value="<?php echo $row2->kode_wilayah;?>" <?php if($row2->kode_wilayah==$kode_desa){?>selected="true"<?php }?>><?php echo $row2->nama;?></option>
													<?php endforeach;?>
												</select>
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group has-feedback">
												<label for="alamat">Alamat</label>
												<input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" minlength="3" maxlength="80" data-validation="required" value="<?php echo $row->alamat; ?>">
											</div>
										</div>
									</div>
								</div>
								<div class="box-footer">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<button type="submit" class="btn btn-primary btn-flat">Ubah Alamat</button>
										</div>
									</div>
								</div>
							</div>
							</form>

							<form role="form" enctype="multipart/form-data" id="ubahlokasirumah" action="<?php echo base_url();?>index.php/Cakun/ubahlokasirumah" method="post">
									<input type="hidden" id="pengguna_id" name="pengguna_id" value="<?php echo $row->peserta_didik_id;?>">
							<div class="box box-solid">
								<div class="box-header with-border">
									<i class="glyphicon glyphicon-map-marker"></i>
									<h3 class="box-title text-info"><b>Lokasi Rumah</b></h3>
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
														<input type="number" readonly="false" class="form-control" id="lintang" name="lintang" placeholder="Lintang" data-validation="required" value="<?php echo $row->lintang; ?>">
													</div>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="form-group has-feedback">
														<label for="bujur">Bujur</label>
														<input type="number" readonly="false" class="form-control" id="bujur" name="bujur" placeholder="Bujur" data-validation="required" value="<?php echo $row->bujur; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<button type="submit" class="btn btn-primary btn-flat">Ubah Lokasi</button>
												</div>
											</row>
										</div>
									</div>
								</div>
							</div>
							</form>

						</div>
						<!-- <div class="box-footer">
						</div> -->
					</div>

				</div>

				<?php if($approved == 0) { ?>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box box-solid">
							<div class="box-header with-border">
								<i class="glyphicon glyphicon-check"></i>
								<h3 class="box-title text-info"><b>Approval</b></h3>
							</div>
							<div class="box-body">
								<table class="table bg-gray">
									<tr>
										<td><b>Username</b></td>
										<td>:</td>
										<td>
										<?php echo $row->username; ?>
										</td>
									</tr>
								</table>
							</div>
							<div class="box-footer">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<form role="form" enctype="multipart/form-data" id="approveakun" action="<?php echo base_url();?>index.php/Cakun/approveakun/" method="post">
											<input type="hidden" id="pengguna_id" name="pengguna_id" value="<?php echo $row->peserta_didik_id;?>">
											<input type="hidden" id="username" name="username" value="<?php echo $row->username;?>">
											<div class="form-group">
												<label>
													<input type="radio" id="approval" name="approval" class="flat-red" value="2" data-validation="required" >
													Tidak Disetujui <nbsp/><nbsp/><nbsp/>
												</label>
												<label>
													<input type="radio" id="approval" name="approval" class="flat-green" value="1" data-validation="required" >
													Disetujui
												</label>
											</div>
											<button type="submit" class="btn btn-primary btn-flat">Proses Persetujuan</button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

				</div>
			<!-- </div> -->


			<?php endforeach;?>
		<!-- </div>
	</div>
</div> -->
<script src="<?php echo base_url();?>assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>
<script src="<?php echo base_url();?>assets/leaflet/leaflet.js"></script>
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>
<script src="<?php echo base_url();?>assets/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="https://unpkg.com/esri-leaflet@2.2.4/dist/esri-leaflet.js" integrity="sha512-tyPum7h2h36X52O2gz+Pe8z/3l+Y9S1yEUscbVs5r5aEY5dFmP1WWRY/WLLElnFHa+k1JBQZSCDGwEAnm2IxAQ==" crossorigin=""></script>
<script src="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.js" integrity="sha512-zdT4Pc2tIrc6uoYly2Wp8jh6EPEWaveqqD3sT0lf5yei19BC1WulGuh5CesB0ldBKZieKGD7Qyf/G0jdSe016A==" crossorigin=""></script>

<script>
	//Date Picker
	$("#tanggal_lahir").datepicker({ 
		format: 'yyyy-mm-dd',
		startDate: new Date("<?php echo $maxtgllahir; ?>"),
		endDate: new Date("<?php echo $mintgllahir; ?>")
	});

	//Flat red color scheme for iCheck
	$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
		radioClass: 'iradio_flat-red'
	});
	$('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
		radioClass: 'iradio_flat-green'
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
	<?php foreach($profilsiswa->getResult() as $row):?>
	var map = L.map('peta',{zoomControl:false}).setView([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>],16);
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

	L.marker([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>]).addTo(layerGroup).bindPopup("<?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?>").openPopup();
	new L.control.fullscreen({position:'bottomleft'}).addTo(map);
	new L.Control.Zoom({position:'bottomright'}).addTo(map);
	<?php endforeach;?>

</script>