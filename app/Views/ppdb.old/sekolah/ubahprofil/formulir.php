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
		foreach($profilsekolah->getResult() as $row):
	?>
	<form role="form" enctype="multipart/form-data" id="prosesperubahanprofil" action="<?php echo base_url();?>index.php/sekolah/ubahprofil/simpan" method="post">
	<input type="hidden" id="npsn_lama" name="npsn_lama" value="<?php echo $row->npsn?>">
	<input type="hidden" id="inklusi_lama" name="inklusi_lama" value="<?php echo $row->inklusi?>">
	<input type="hidden" id="alamat_jalan_lama" name="alamat_jalan_lama" value="<?php echo $row->alamat_jalan?>">
	<input type="hidden" id="bujur_lama" name="bujur_lama" value="<?php echo $row->bujur?>">
	<input type="hidden" id="lintang_lama" name="lintang_lama" value="<?php echo $row->lintang?>">
	<input type="hidden" id="sekolah_id" name="sekolah_id" value="<?php echo $row->sekolah_id;?>">

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="box box-solid">
				<div class="box-header with-border">
					<i class="glyphicon glyphicon-edit text-info"></i>
					<h3 class="box-title text-info"><b>Perubahan Data</b></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> -->
						<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="nisn">NPSN</label>
									<input id="nisn" name="nisn" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required" placeholder="NPSN" minlength="10" maxlength="10" value="<?php echo $row->npsn;?>" disabled>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="inklusi">Inklusi</label>
									<select id="inklusi" name="inklusi" class="form-control select2" data-validation="required">
										<option value="0" <?php if(0==$row->inklusi){?>selected="true"<?php }?>>Tidak</option>
										<option value="1" <?php if(1==$row->inklusi){?>selected="true"<?php }?>>Ya</option>
									</select>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="alamat">Alamat</label>
									<input id="alamat_jalan" name="alamat_jalan" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required" value="<?php echo $row->alamat_jalan;?>">
								</div>
							</div>
						<!-- </div> -->
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="box box-solid">
				<div class="box-header with-border">
					<i class="glyphicon glyphicon-map-marker"></i>
					<h3 class="box-title text-info"><b>Lokasi Sekolah</b></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div id="peta" style="width: 100%; height: 400px;"></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-info" style="margin-top: 10px; margin-bottom: 10px;">NB : Silahkan klik di peta <b>(<i class="glyphicon glyphicon-map-marker"></i>)</b> untuk perubahan data koordinat lokasi sekolah.</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group has-feedback">
								<label for="lintang">Lintang</label>
								<input type="text" class="form-control" id="lintang" name="lintang" placeholder="Lintang" data-validation="required" value="<?php echo $row->lintang;?>">
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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
	</form>
	<?php endforeach;?>
</div>

<script src="<?php echo base_url();?>assets/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>

<script>
	//Dropdown Select
	$(function () {
		$(".select2").select2();
	});
	//Event On Change Dropdown
	$(document).ready(function () {
		$('#lintang').on('change', onChangeCoordinate);
		$('#bujur').on('change', onChangeCoordinate);

	});
	//Validasi
	var $messages = $('#error-message-wrapper');
	$.validate({
		modules: 'security',
		errorMessagePosition: $messages,
		scrollToTopOnError: false
	});
	
	//Peta
	<?php foreach($profilsekolah->getResult() as $row):?>

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
		L.marker([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>]).addTo(layerGroup).bindPopup("<?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>").openPopup();
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