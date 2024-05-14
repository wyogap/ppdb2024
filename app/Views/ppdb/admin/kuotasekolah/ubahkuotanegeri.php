<!DOCTYPE html>
<html>
	<?php view('head');?>

	<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/datepicker/datepicker3.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css"/>
	<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet'/>
	<link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.css" integrity="sha512-v5YmWLm8KqAAmg5808pETiccEohtt8rPVMGQ1jA6jqkWVydV5Cuz3nJ9fQ7ittSxvuqsvI9RSGfVoKPaAJZ/AQ==" crossorigin="">

	<script src="<?php echo base_url();?>assets/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>
	<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>
	<script src="<?php echo base_url();?>assets/leaflet/leaflet.js"></script>
	<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>
	<script src="https://unpkg.com/esri-leaflet@2.2.4/dist/esri-leaflet.js" integrity="sha512-tyPum7h2h36X52O2gz+Pe8z/3l+Y9S1yEUscbVs5r5aEY5dFmP1WWRY/WLLElnFHa+k1JBQZSCDGwEAnm2IxAQ==" crossorigin=""></script>
	<script src="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.js" integrity="sha512-zdT4Pc2tIrc6uoYly2Wp8jh6EPEWaveqqD3sT0lf5yei19BC1WulGuh5CesB0ldBKZieKGD7Qyf/G0jdSe016A==" crossorigin=""></script>

	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
			<?php view('header');?>
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<h1 class="text-white">
							<i class="glyphicon glyphicon-edit"></i> Ubah Kuota Sekolah <small>Negeri</small>
						</h1>
						<!-- <ol class="breadcrumb">
							<li class="active"><a href="#"><i class="glyphicon glyphicon-edit"></i> Detail Perubahan Data Siswa</a></li>
						</ol> -->
					</section>
					<section class="content">

<div class="row">
	<?php 
		$sekolah_id = "";
		foreach($profilsekolah->getResult() as $row):
			$sekolah_id = $row->sekolah_id;
	?>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header with-border">
				<i class="glyphicon glyphicon-user"></i>
				<h3 class="box-title"><b><?php if($row->npsn!=""){?>(<?php echo $row->npsn;?>) <?php }?><?php echo $row->nama;?></b></h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="peta" style="width: 100%; height: 250px;"></div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="table table-striped">
							<tr>
								<td><b>Nama</b></td>
								<td>:</td>
								<td>(<b><?php echo $row->npsn;?></b>) <a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/<?php echo $row->sekolah_id;?>" target="_blank"><?php echo $row->nama;?></a></td>
							</tr>
							<tr>
								<td><b>NPSN</b></td>
								<td>:</td>
								<td><?php echo $row->npsn;?></td>
							</tr>
							<tr>
								<td><b>Jenjang</b></td>
								<td>:</td>
								<td><?php echo $row->bentuk;?></td>
							</tr>
							<tr>
								<td><b>Status Sekolah</b></td>
								<td>:</td>
								<td><?php if($row->status=='N'){?>NEGERI<?php }else{?>SWASTA<?php }?></td>
							</tr>
							<tr>
								<td><b>Alamat</b></td>
								<td>:</td>
								<td><?php echo $row->alamat_jalan;?></td>
							</tr>
							<tr>
								<td><b>Desa/Kelurahan</b></td>
								<td>:</td>
								<td><?php echo $row->desa_kelurahan;?></td>
							</tr>
							<tr>
								<td><b>Kecamatan</b></td>
								<td>:</td>
								<td><?php echo $row->kecamatan;?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach;?>

	<?php foreach($kuotasekolah->getResult() as $row): ?>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<form role="form" enctype="multipart/form-data" id="prosesubahkuotanegeri" action="<?php echo base_url();?>index.php/admin/kuota/prosesubahkuotanegeri/" method="post">
			<input type="hidden" id="sekolah_id" name="sekolah_id" value="<?php echo $row->sekolah_id;?>">
			<input type="hidden" id="approval" name="approval" value="1">
			<div class="box box-solid">
				<div class="box-header with-border">
					<i class="glyphicon glyphicon-edit text-info"></i>
					<h3 class="box-title text-info"><b>Kuota Sekolah</b></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<table class="table table-striped">
								<thead>
									<tr class="bg-blue">
										<th class="text-center">Jalur</th>
										<th class="text-center">Kuota</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Zonasi</td>
										<td><?php echo $row->kuota_zonasi;?></td>
									</tr>
									<tr>
										<td>Prestasi</td>
										<td><?php echo $row->kuota_prestasi;?></td>
									</tr>
									<tr>
										<td>Afirmasi</td>
										<td><?php echo $row->kuota_afirmasi;?></td>
									</tr>
									<tr>
										<td>Perpindahan Orang Tua</td>
										<td><?php echo $row->kuota_perpindahan_ortu;?></td>
									</tr>
									<tr>
										<td>Inklusi</td>
										<td><?php echo $row->kuota_inklusi;?></td>
									</tr>
									<tr class="bg-gray">
										<td><b>Kuota Total</b></td>
										<td>
											<input type="text" class="form-control" id="kuota_total" name="kuota_total" placeholder="kuota" data-validation="required" value="<?php echo $row->kuota_total;?>">
										</td>
									</tr>

								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary btn-flat">Simpan Perubahan</button>
				</div>
			</div>
		</form>
	</div>
	<?php endforeach;?>
</div>

					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>

<script>
	//Peta
	<?php foreach($profilsekolah->getResult() as $row):?>
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

	L.marker([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>]).addTo(layerGroup).bindPopup("<?php echo $row->alamat_jalan;?>, <?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>").openPopup();
	new L.control.fullscreen({position:'bottomleft'}).addTo(map);
	new L.Control.Zoom({position:'bottomright'}).addTo(map);
	<?php endforeach;?>
</script>

</html>
