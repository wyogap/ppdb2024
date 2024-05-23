<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css"/>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet'/>

<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header with-border">
				<i class="glyphicon glyphicon-info-sign"></i>
				<h3 class="box-title text-info"><b>Tabulasi</b></h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="table table-striped" style="margin-bottom: 0px;">
							<?php foreach($profilsekolah->getResult() as $row):?>
							<tr>
								<td><b>NPSN</b></td>
								<td>:</td>
								<td><?php echo $row->npsn;?></td>
							</tr>
							<tr>
								<td><b>Nama</b></td>
								<td>:</td>
								<td><?php echo $row->nama;?><span class="pull-right"><a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/<?php echo $this->session->userdata("sekolah_id");?>" target="_blank" class="btn btn-xs btn-primary">Profil Sekolah Kita</a></span></td>
							</tr>
							<tr>
								<td><b>Jenjang</b></td>
								<td>:</td>
								<td><?php echo $row->bentuk_pendidikan;?></td>
							</tr>
							<tr>
								<td><b>Status Sekolah</b></td>
								<td>:</td>
								<td><?php if($row->status=='N'){?>NEGERI<?php }else{?>SWASTA<?php }?></td>
							</tr>
							<tr>
								<td><b>Inklusi</b></td>
								<td>:</td>
								<td><?php if($row->inklusi==0){?>TIDAK<?php }else{?>YA<?php }?></td>
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
							<?php endforeach;?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header with-border">
				<i class="glyphicon glyphicon-info-sign"></i>
				<h3 class="box-title text-info"><b>Daftar Kuota</b></h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="table table-striped table-bordered">
							<tr class="bg-blue">
								<th class="text-center">Jalur</th>
								<th class="text-center">Kuota</th>
							</tr>
							<?php
								$totalkuota = 0;
								foreach($daftarkuota->getResult() as $row):
									$totalkuota += $row->kuota;
								?>
							<tr>
								<td><b><?php echo $row->jalur;?></b></td>
								<td class="text-end"><?php echo $row->kuota;?></td>
							</tr>
							<?php endforeach;?>
							<tr class="bg-gray">
								<td class="text-end"><b>Total Kuota</b></td>
								<td class="text-end"><?php echo $totalkuota;?></td>
							</tr>

						</table>
					</div>
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
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url();?>assets/leaflet/leaflet.js"></script>
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>
<script>
	<?php foreach($profilsekolah->getResult() as $row):?>
		//Peta
		var map = L.map('peta',{zoomControl:false}).setView([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>],16);
		L.tileLayer(
			'<?php echo $servis_peta_aktif;?>',{maxZoom: 18,attribution: 'PPDB <?php echo $wilayah_aktif;?>',id: 'mapbox.streets'}
		).addTo(map);

		L.marker([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>]).addTo(map).bindPopup("<?php echo $row->alamat_jalan;?>, <?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>");

		// <?php foreach($daftarkuota->getResult() as $kuota):?>
		// 	//Layer Group
		// 	var j<?php echo $kuota->jalur_id;?> = new L.LayerGroup();
		// 	//Adding Layer Group
		// 	j<?php echo $kuota->jalur_id;?>.addTo(map);
		// <?php endforeach;?>

		// map.fitBounds([
		// 	[<?php echo $row->lintang;?>,<?php echo $row->bujur;?>]
		// ]);

		var streetmap   = L.tileLayer('<?php echo $streetmap_aktif;?>', {id: 'mapbox.light', attribution: ''}),
			satelitemap  = L.tileLayer('<?php echo $satelitemap_aktif;?>', {id: 'mapbox.streets',   attribution: ''});

		var baseLayers = {
			"Streets": streetmap,
			"Satelite": satelitemap
		};

		var overlays = {};
		L.control.layers(baseLayers,overlays).addTo(map);

		new L.control.fullscreen({position:'bottomleft'}).addTo(map);
		new L.Control.Zoom({position:'bottomright'}).addTo(map);
	<?php endforeach;?>
</script>