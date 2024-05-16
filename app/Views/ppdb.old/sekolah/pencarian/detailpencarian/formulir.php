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
<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css"/>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet'/>
<link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.css" integrity="sha512-v5YmWLm8KqAAmg5808pETiccEohtt8rPVMGQ1jA6jqkWVydV5Cuz3nJ9fQ7ittSxvuqsvI9RSGfVoKPaAJZ/AQ==" crossorigin="">
<div class="row">
	<?php 
		$peserta_didik_id = "";
		$kode_kabupaten = "";
		$kode_kecamatan = "";
		$kode_desa = "";
		$kode_padukuhan = "";
		$tanggal_lahir = "";
		$lintang = 0;
		$bujur = 0;
		$asal_data = 0;
		foreach($profilsiswa->getResult() as $row):
			$peserta_didik_id = $row->peserta_didik_id;
			$kode_kabupaten = $row->kode_kabupaten;
			$kode_kecamatan = $row->kode_kecamatan;
			$kode_desa = $row->kode_desa;
			$kode_padukuhan = $row->kode_padukuhan;
			$tanggal_lahir = $row->tanggal_lahir;
			$lintang = $row->lintang;
			$bujur = $row->bujur;
			$asal_data = $row->asal_data;
	?>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header with-border">
				<i class="glyphicon glyphicon-user"></i>
				<h3 class="box-title"><b><?php if($row->nisn!=""){?>(<?php echo $row->nisn;?>) <?php }?><?php echo $row->nama;?></b></h3>
				<span class="pull-right"><a href="<?php echo base_url();?>index.php/Chome/detailpendaftaran?peserta_didik_id=<?php echo $peserta_didik_id;?>" target="_blank" class="btn btn-primary btn-sm">Detail Pendaftaran</a></span>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="peta" style="width: 100%; height: 250px;"></div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="table table-striped">
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
								<td><b>Nama</b></td>
								<td>:</td>
								<td><?php echo $row->nama;?></td>
							</tr>
							<tr>
								<td><b>Jenis Kelamin</b></td>
								<td>:</td>
								<td><?php if($row->jenis_kelamin=="L"){echo "Laki-laki";}else{echo "Perempuan";}?></td>
							</tr>
							<tr>
								<td><b>Kebutuhan Khusus</b></td>
								<td>:</td>
								<td><?php echo $row->kebutuhan_khusus;?></td>
							</tr>
							<tr>
								<td><b>Tempat Lahir</b></td>
								<td>:</td>
								<td><?php echo $row->tempat_lahir;?></td>
							</tr>
							<tr>
								<td><b>Tanggal Lahir</b></td>
								<td>:</td>
								<td><?php echo tanggal_indo($row->tanggal_lahir);?></td>
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
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach;?>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<form role="form" enctype="multipart/form-data" id="prosesperubahandatasiswa" action="<?php echo base_url();?>index.php/Csekolah/prosesperubahandata/" method="post">
			<input type="hidden" id="peserta_didik_id" name="peserta_didik_id" value="<?php echo $peserta_didik_id;?>">
			<input type="hidden" id="approval" name="approval" value="1">
			<div class="box box-solid">
				<div class="box-header with-border">
					<i class="glyphicon glyphicon-edit text-info"></i>
					<h3 class="box-title text-info"><b>Formulir Perubahan Data</b></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="kode_kabupaten">Kabupaten/Kota</label>
									<select id="kode_kabupaten" name="kode_kabupaten" class="form-control select2" data-validation="required">
										<option value="">-- Pilih Kabupaten/Kota --</option>
										<?php foreach($kabupaten->getResult() as $row):?>
											<option value="<?php echo $row->kode_wilayah;?>" <?php if($row->kode_wilayah==$kode_kabupaten){?>selected="true"<?php }?>><?php echo $row->kabupaten;?> (<?php echo $row->provinsi;?>)</option>
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
											$kecamatanubahdatasiswa = $this->Mdropdown->tcg_kecamatan($kode_kabupaten);
											foreach($kecamatanubahdatasiswa->getResult() as $row):
										?>
										<option value="<?php echo $row->kode_wilayah;?>" <?php if($row->kode_wilayah==$kode_kecamatan){?>selected="true"<?php }?>><?php echo $row->nama;?></option>
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
											$desaubahdatasiswa = $this->Mdropdown->tcg_desa($kode_kecamatan);
											foreach($desaubahdatasiswa->getResult() as $row):
										?>
										<option value="<?php echo $row->kode_wilayah;?>" <?php if($row->kode_wilayah==$kode_desa){?>selected="true"<?php }?>><?php echo $row->nama;?></option>
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
											$padukuhanubahdatasiswa = $this->Mdropdown->tcg_padukuhan($kode_desa);
											foreach($padukuhanubahdatasiswa->getResult() as $row):
										?>
										<option value="<?php echo $row->kode_wilayah;?>" <?php if($row->kode_wilayah==$kode_padukuhan){?>selected="true"<?php }?>><?php echo $row->nama;?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="tanggal_lahir">Tanggal Lahir</label>
									<input id="tanggal_lahir" name="tanggal_lahir" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required" value="<?php echo $tanggal_lahir;?>">
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="lintang">Lintang</label>
									<input type="text" class="form-control" id="lintang" name="lintang" placeholder="Lintang" data-validation="required" value="<?php echo $lintang;?>">
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="bujur">Bujur</label>
									<input type="text" class="form-control" id="bujur" name="bujur" placeholder="Bujur" data-validation="required" value="<?php echo $bujur;?>">
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="asal_data">Asal Data</label>
									<select id="asal_data" name="asal_data" class="form-control select2" data-validation="required">
										<option value="">-- Asal Data --</option>
										<option value="0" <?php if($asal_data==0){?>selected="true"<?php }?>>DAPODIK</option>
										<option value="1" <?php if($asal_data==1){?>selected="true"<?php }?>>Registrasi Tidak Paud atau Madrasah</option>
										<option value="2" <?php if($asal_data==2){?>selected="true"<?php }?>>Siswa Luar Kabupaten</option>
									</select>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-info">NB : Silahkan klik di peta <b>(<i class="glyphicon glyphicon-map-marker"></i>)</b> untuk perubahan data koordinat siswa.</div>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary btn-flat">Simpan Perubahan</button>
				</div>
			</div>
		</form>
	</div>
</div>
<script src="<?php echo base_url();?>assets/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>
<script src="<?php echo base_url();?>assets/leaflet/leaflet.js"></script>
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>
<script src="https://unpkg.com/esri-leaflet@2.2.4/dist/esri-leaflet.js" integrity="sha512-tyPum7h2h36X52O2gz+Pe8z/3l+Y9S1yEUscbVs5r5aEY5dFmP1WWRY/WLLElnFHa+k1JBQZSCDGwEAnm2IxAQ==" crossorigin=""></script>
<script src="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.js" integrity="sha512-zdT4Pc2tIrc6uoYly2Wp8jh6EPEWaveqqD3sT0lf5yei19BC1WulGuh5CesB0ldBKZieKGD7Qyf/G0jdSe016A==" crossorigin=""></script>
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

	L.marker([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>]).addTo(layerGroup).bindPopup("<?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?>").openPopup();
	new L.control.fullscreen({position:'bottomleft'}).addTo(map);
	new L.Control.Zoom({position:'bottomright'}).addTo(map);
	<?php endforeach;?>
</script>