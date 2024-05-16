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
		foreach($profilsiswa->getResult() as $row):
			$peserta_didik_id = $row->peserta_didik_id;
			$kode_kabupaten = $row->kode_kabupaten;
			$kode_kecamatan = $row->kode_kecamatan;
			$kode_desa = $row->kode_desa;
			$kode_padukuhan = $row->kode_padukuhan;
			$tanggal_lahir = $row->tanggal_lahir;
			$lintang = $row->lintang;
			$bujur = $row->bujur;
	?>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header with-border">
				<i class="glyphicon glyphicon-user"></i>
				<h3 class="box-title"><b><?php if($row->nisn!=""){?>(<?php echo $row->nisn;?>) <?php }?><?php echo $row->nama;?></b></h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="peta" style="width: 100%; height: 250px;"></div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="table table-striped">
							<tr class="bg-gray">
								<td><b>Jenis Piihan</b></td>
								<td>:</td>
								<td><?php echo $row->jenis_pilihan;?></td>
							</tr>
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
								<td><?php if($row->jenis_kelamin=="L"){echo "Laki-laki";}else{"Perempuan";}?></td>
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
		<form role="form" enctype="multipart/form-data" id="prosesperubahandatasiswa" action="<?php echo base_url();?>index.php/Csekolah/proseshapusperubahandatasiswa/" method="post">
			<input type="hidden" id="peserta_didik_id" name="peserta_didik_id" value="<?php echo $peserta_didik_id;?>">
			<div class="box box-solid">
				<div class="box-header with-border">
					<i class="glyphicon glyphicon-saved text-warning"></i>
					<h3 class="box-title text-warning"><b>Daftar Pengajuan Perubahan Data</b></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<table class="table table-striped">
								<?php foreach($detailperubahandatasiswa->getResult() as $row):?>
								<input type="hidden" id="perubahan_data_siswa_id" name="perubahan_data_siswa_id" value="<?php echo $row->perubahan_data_siswa_id;?>">
								<tr>
									<td><b>Padukuhan</b></td>
									<td>:</td>
									<td><?php echo $row->padukuhan_lama;?> <i class="glyphicon glyphicon-arrow-right"></i> <?php echo $row->padukuhan_baru;?></td>
								</tr>
								<tr>
									<td><b>Desa</b></td>
									<td>:</td>
									<td><?php echo $row->desa_lama;?> <i class="glyphicon glyphicon-arrow-right"></i> <?php echo $row->desa_baru;?></td>
								</tr>
								<tr>
									<td><b>Tanggal Lahir</b></td>
									<td>:</td>
									<td><?php echo tanggal_indo($row->tanggal_lahir_lama);?> <i class="glyphicon glyphicon-arrow-right"></i> <?php echo tanggal_indo($row->tanggal_lahir_baru);?></td>
								</tr>
								<tr>
									<td><b>Lintang</b></td>
									<td>:</td>
									<td><?php echo $row->lintang_lama;?> <i class="glyphicon glyphicon-arrow-right"></i> <?php echo $row->lintang_baru;?></td>
								</tr>
								<tr>
									<td><b>Bujur</b></td>
									<td>:</td>
									<td><?php echo $row->bujur_lama;?> <i class="glyphicon glyphicon-arrow-right"></i> <?php echo $row->bujur_baru;?></td>
								</tr>
								<?php endforeach;?>
							</table>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-danger btn-flat">Hapus Pengajuan</button>
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
		format: 'yyyy-mm-dd',
		startDate: new Date('2010-07-01'),
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
	<?php foreach($detailperubahandatasiswa->getResult() as $row):?>
	var LeafIcon = L.Icon.extend({
		options: {
			shadowUrl: '<?php echo base_url();?>assets/leaflet/images/marker-icon.png'
		}
	});
	var blue = new LeafIcon({iconUrl: '<?php echo base_url();?>assets/leaflet/images/marker-icon.png'}),
	red = new LeafIcon({iconUrl: '<?php echo base_url();?>assets/leaflet/images/marker-icon-red.png'}),
	green = new LeafIcon({iconUrl: '<?php echo base_url();?>assets/leaflet/images/marker-icon-green.png'});
	var map = L.map('peta',{zoomControl:false}).setView([<?php echo $row->lintang_baru;?>,<?php echo $row->bujur_baru;?>],16);
	L.tileLayer(
		'<?php echo $servis_peta_aktif;?>',{maxZoom: 18,attribution: 'PPDB <?php echo $wilayah_aktif;?>',id: 'mapbox.streets'}
	).addTo(map);

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

	L.marker([<?php echo $row->lintang_lama;?>,<?php echo $row->bujur_lama;?>], {icon: red}).addTo(layerGroup).bindPopup("Data Lama");
	L.marker([<?php echo $row->lintang_baru;?>,<?php echo $row->bujur_baru;?>], {icon: green}).addTo(layerGroup).bindPopup("Data Baru");

	map.fitBounds([
		[<?php echo $row->lintang_lama;?>,<?php echo $row->bujur_lama;?>],
		[<?php echo $row->lintang_baru;?>,<?php echo $row->bujur_baru;?>]
	]);

	new L.control.fullscreen({position:'bottomleft'}).addTo(map);
	new L.Control.Zoom({position:'bottomright'}).addTo(map);
	<?php endforeach;?>
</script>