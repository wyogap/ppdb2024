<?php
	$this->load->model('Mdropdown');
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
	<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/datepicker/datepicker3.css">

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

<div class="row">
	<?php 
		$peserta_didik_id = "";
		foreach($profilsiswa->getResult() as $row):
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header with-border">
				<i class="glyphicon glyphicon-user"></i>
				<h3 class="box-title"><b>Data Identitas</b></h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="table table-striped" style="margin-bottom: 0px;">
							<tr>
								<td><b>Sekolah Asal</b></td>
								<td>:</td>
								<?php if($row->sekolah_id!=""){?>
									<td>(<b><?php echo $row->npsn;?></b>) <a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/<?php echo $row->sekolah_id;?>" target="_blank"><?php echo $row->sekolah;?></a></td>
								<?php }else{?>
									<td><p>Tidak bersekolah sebelumnya.</p></td>
								<?php }?>
							</tr>
							<tr>
								<td><b>NIK</b></td>
								<td>:</td>
								<td><?php echo $row->nik;?></td>
							</tr>
							<tr>
								<td><b>Jenis Kelamin</b></td>
								<td>:</td>
								<td><?php if($row->jenis_kelamin=="L"){echo "Laki-laki";}else{echo "Perempuan";}?></td>
							</tr>
							<tr>
								<td><b>Nama Ibu Kandung</b></td>
								<td>:</td>
								<td><?php echo $row->nama_ibu_kandung;?></td>
							</tr>
							<tr>
								<td><b>Alamat</b></td>
								<td>:</td>
								<td><?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?></td>
							</tr>
							<tr>
								<td><b>Sumber Data</b></td>
								<td>:</td>
								<td><?php if ($row->asal_data == 0) { echo 'DAPODIK'; } else if ($row->asal_data == 1) { echo "REGISTRASI"; } else if ($row->asal_data == 5) { echo "API Tarik Data"; } else { echo "Lain"; }?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<form role="form" enctype="multipart/form-data" id="prosesperubahandatasiswa" action="<?php echo base_url();?>index.php/sekolah/ubahdata/simpan?redirect=<?php echo $redirect; ?>" method="post">
		<input type="hidden" id="peserta_didik_id" name="peserta_didik_id" value="<?php echo $row->peserta_didik_id;?>">
		<input type="hidden" id="approval" name="approval" value="1">

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="box box-solid">
				<div class="box-header with-border">
					<i class="glyphicon glyphicon-edit text-info"></i>
					<h3 class="box-title text-info"><b>Perubahan Data</b></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="tempat_lahir">Tempat Lahir</label>
									<input id="tempat_lahir" name="tempat_lahir" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required" value="<?php echo $row->tempat_lahir;?>" disabled>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="tanggal_lahir">Tanggal Lahir</label>
									<input id="tanggal_lahir" name="tanggal_lahir" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required" value="<?php echo $row->tanggal_lahir;?>">
								</div>
							</div>
						</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="kode_kabupaten">Kabupaten/Kota</label>
									<select id="kode_kabupaten" name="kode_kabupaten" class="form-control select2" data-validation="required">
										<option value="">-- Pilih Kabupaten/Kota --</option>
										<?php foreach($kabupaten->getResult() as $kab):?>
											<option value="<?php echo $kab->kode_wilayah;?>" <?php if($kab->kode_wilayah==$row->kode_kabupaten){?>selected="true"<?php }?>><?php echo $kab->kabupaten;?> (<?php echo $kab->provinsi;?>)</option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="kode_kecamatan">Kecamatan</label>
									<select id="kode_kecamatan" name="kode_kecamatan" class="form-control select2" data-validation="required">
										<option value="">-- Pilih Kecamatan --</option>
										<?php
											$kecamatanubahdatasiswa = $this->Mdropdown->tcg_kecamatan($row->kode_kabupaten);
											foreach($kecamatanubahdatasiswa->getResult() as $kec):
										?>
										<option value="<?php echo $kec->kode_wilayah;?>" <?php if($kec->kode_wilayah==$row->kode_kecamatan){?>selected="true"<?php }?>><?php echo $kec->nama;?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="kode_desa">Desa/Kelurahan</label>
									<select id="kode_desa" name="kode_desa" class="form-control select2" data-validation="required">
										<option value="">-- Pilih Desa/Kelurahan --</option>
										<?php
											$desaubahdatasiswa = $this->Mdropdown->tcg_desa($row->kode_kecamatan);
											foreach($desaubahdatasiswa->getResult() as $desa):
										?>
										<option value="<?php echo $desa->kode_wilayah;?>" <?php if($desa->kode_wilayah==$row->kode_desa){?>selected="true"<?php }?>><?php echo $desa->nama;?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="kode_wilayah">Padukuhan</label>
									<select id="kode_wilayah" name="kode_wilayah" class="form-control select2">
										<option value="">-- Pilih Padukuhan --</option>
										<?php
											$padukuhanubahdatasiswa = $this->Mdropdown->tcg_padukuhan($row->kode_desa);
											foreach($padukuhanubahdatasiswa->getResult() as $dukuh):
										?>
										<option value="<?php echo $dukuh->kode_wilayah;?>" <?php if($dukuh->kode_wilayah==$row->kode_padukuhan){?>selected="true"<?php }?>><?php echo $dukuh->nama;?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="box box-solid">
				<div class="box-header with-border">
					<i class="glyphicon glyphicon-map-marker"></i>
					<h3 class="box-title text-info"><b>Lokasi Rumah</b></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div id="peta" style="width: 100%; height: 400px;"></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-info" style="margin-top: 10px; margin-bottom: 10px;">NB : Silahkan klik di peta <b>(<i class="glyphicon glyphicon-map-marker"></i>)</b> untuk perubahan data koordinat lokasi rumah siswa.</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="form-group has-feedback">
								<label for="lintang">Lintang</label>
								<input type="text" class="form-control" id="lintang" name="lintang" placeholder="Lintang" data-validation="required" value="<?php echo $row->lintang;?>">
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="form-group has-feedback">
								<label for="bujur">Bujur</label>
								<input type="text" class="form-control" id="bujur" name="bujur" placeholder="Bujur" data-validation="required" value="<?php echo $row->bujur;?>">
							</div>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary btn-flat">Simpan Perubahan</button>
				</div>
			</div>
		</div>
	</div>
	</form>
	</div>
	<?php endforeach;?>
</div>

<script src="<?php echo base_url();?>assets/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>

<script>
	//Dropdown Select
	$(function () {
		$(".select2").select2();
	});
	//Datepicker
	$("#tanggal_lahir").datepicker({ 
		format: 'yyyy-mm-dd'
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
				url : "<?php echo site_url('Cdropdown/Padukuhan')?>",
				data: data,
				success: function(msg){
					$('#kode_wilayah').html(msg);
				}
			});
		});

		$('#lintang').on('change', onChangeCoordinate);
		$('#bujur').on('change', onChangeCoordinate);

	});
	//Validasi
	//Validasi
	var myLanguage = {
        errorTitle: 'Gagal mengirim data!',
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
	<?php foreach($profilsiswa->getResult() as $row):?>

	var map;
	<?php if (!empty($row->lintang) && !empty($row->bujur)) { ?>
		map = L.map('peta',{zoomControl:false}).setView([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>],15);
	<?php } else { ?>
		map = L.map('peta',{zoomControl:false}).setView([<?php echo $lintang_aktif;?>,<?php echo $bujur_aktif;?>],10);
	<?php } ?>

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
		if (confirm("Ubah lokasi rumah siswa?")) {
			layerGroup.clearLayers();
			var lintang = e.latlng.lat;
			var bujur = e.latlng.lng;
			new L.marker(e.latlng).addTo(layerGroup).bindPopup("Lokasi :<br>"+lintang+" , "+bujur).openPopup();
			document.getElementById("lintang").value=lintang;
			document.getElementById("bujur").value=bujur;
		}
	}
	map.on('click', onMapClick);
	var searchControl = L.esri.Geocoding.geosearch().addTo(map);
	searchControl.on('layerGroup', function(data){
		layerGroup.clearLayers();
	});

	<?php if (!empty($row->lintang) && !empty($row->bujur)) { ?>
		L.marker([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>]).addTo(layerGroup).bindPopup("<?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?>").openPopup();
	<?php } ?>

	new L.control.fullscreen({position:'bottomleft'}).addTo(map);
	new L.Control.Zoom({position:'bottomright'}).addTo(map);

	new L.Control.EasyButton( '<span class="map-button">&curren;</span>', function(){
				map.setView([<?php echo $lintang_aktif;?>,<?php echo $bujur_aktif;?>],10);;
			}, {position: 'topleft'}).addTo(map);

	function onChangeCoordinate() {
		layerGroup.clearLayers();
		var lintang = document.getElementById("lintang").value;
		var bujur = document.getElementById("bujur").value;
		new L.marker([lintang,bujur]).addTo(layerGroup).bindPopup("Lokasi :<br>"+lintang+" , "+bujur).openPopup();
	}

	<?php endforeach;?>
</script>