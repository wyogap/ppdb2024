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
<!-- <div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_1" data-toggle="tab">Identitas</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_1"> -->
			<?php foreach($detailpengajuanakun->getResult() as $row):?>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<div id="peta" style="width: 100%; height: 400px;"></div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-user"></i>
							<h3 class="box-title text-info"><b>Identitas Siswa</b></h3>
						</div>
						<div class="box-body">
							<div class="row">
								<!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<div id="peta" style="width: 100%; height: 400px;"></div>
								</div> -->
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<table class="table table-striped">
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
										<tr>
											<td><b>NIK</b></td>
											<td>:</td>
											<td><?php echo $row->nik;?></td>
										</tr>
										<tr>
											<td><b>NISN</b></td>
											<td>:</td>
											<td><?php echo $row->nisn;?></td>
										</tr>
										<tr>
											<td><b>Nomor Ujian</b></td>
											<td>:</td>
											<td><?php echo $row->nomor_ujian;?></td>
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
										<tr class="bg-blue">
											<td><b>Username</b></td>
											<td>:</td>
											<td><b><?php echo $row->username;?></b></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<form role="form" enctype="multipart/form-data" id="prosespengajuanakun" action="<?php echo base_url();?>index.php/Csekolah/prosespengajuanakun/" method="post">
								<input type="hidden" id="pengguna_id" name="pengguna_id" value="<?php echo $row->pengguna_id;?>">
								<input type="hidden" id="username" name="username" value="<?php echo $row->username;?>">
								<div class="form-group">
									<label>
										<input type="radio" id="approval" name="approval" class="flat-red" value="2" data-validation="required" <?php if($row->approval==1){?>disabled<?php }?> <?php if($row->approval==2){?>checked="checked"<?php }?>>
										Tidak Disetujui
									</label>
									<label>
										<input type="radio" id="approval" name="approval" class="flat-green" value="1" data-validation="required" <?php if($row->approval==1){?>disabled<?php }?> <?php if($row->approval==1){?>checked="checked"<?php }?>>
										Disetujui
									</label>
								</div>
								<button type="submit" class="btn btn-primary btn-flat" <?php if($row->approval==1){?>disabled="true"<?php }?>>Proses Persetujuan</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach;?>
		<!-- </div>
	</div>
</div> -->
<script src="<?php echo base_url();?>assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>
<script src="<?php echo base_url();?>assets/leaflet/leaflet.js"></script>
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>
<script>
	//Flat red color scheme for iCheck
	$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
		radioClass: 'iradio_flat-red'
	});
	$('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
		radioClass: 'iradio_flat-green'
	});
	//Validasi
	var $messages = $('#error-message-wrapper');
	$.validate({
		modules: 'security',
		errorMessagePosition: $messages,
		scrollToTopOnError: false
	});

	//Peta
	<?php foreach($detailpengajuanakun->getResult() as $row):?>
	var map = L.map('peta',{zoomControl:false}).setView([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>],16);
	L.tileLayer(
		'<?php echo $servis_peta_aktif;?>',{maxZoom: 18,attribution: 'PPDB <?php echo $wilayah_aktif;?>',id: 'mapbox.streets'}
	).addTo(map);
	L.marker([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>]).addTo(map).bindPopup("<?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?>").openPopup();
	new L.control.fullscreen({position:'bottomleft'}).addTo(map);
	new L.Control.Zoom({position:'bottomright'}).addTo(map);
	<?php endforeach;?>
</script>