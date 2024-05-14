<?php
	//$this->load->model(array('Mdropdown','Mhome'));
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
	$batasanbatalverifikasi = 0;
	foreach($referensibatasanperubahan->getResult() as $row):
		$batasanbatalverifikasi = $row->batal_verifikasi;
	endforeach;
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
		$kode_wilayah = "";
		$tanggal_lahir = "";
		$lintang = 0;
		$bujur = 0;
		foreach($profilsiswa->getResult() as $row):
			$peserta_didik_id = $row->peserta_didik_id;
			$kode_kabupaten = $row->kode_kabupaten;
			$kode_kecamatan = $row->kode_kecamatan;
			$kode_wilayah = $row->kode_wilayah;
			$tanggal_lahir = $row->tanggal_lahir;
			$lintang = $row->lintang;
			$bujur = $row->bujur;
			$batal_verifikasi = $row->batal_verifikasi;
	?>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header with-border">
				<i class="glyphicon glyphicon-user"></i>
				<h3 class="box-title"><b><?php if($row->nisn!=""){?>(<?php echo $row->nisn;?>) <?php }?><?php echo $row->nama;?></b></h3>
				<span class="pull-right"><a href="<?php echo base_url();?>index.php/Chome/detailpendaftaran?peserta_didik_id=<?php echo $peserta_didik_id;?>" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Profil Siswa</a></span>
			</div>
			<div class="box-body">
				<div class="row">
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
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<form role="form" enctype="multipart/form-data" id="prosesbatalverifikasi" action="<?php echo base_url();?>index.php/Cadmin/prosesbatalverifikasi/" method="post">
				<input type="hidden" id="peserta_didik_id" name="peserta_didik_id" value="<?php echo $peserta_didik_id;?>">
				<div class="box-header with-border">
					<i class="glyphicon glyphicon-remove text-danger"></i>
					<h3 class="box-title text-info"><b>Formulir Batal Verifikasi</b></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group has-feedback">
								<label for="keterangan">Alasan Batal Verifikasi</label>
								<textarea id="keterangan" name="keterangan" placeholder="Penjelasan terkait Batal Verifikasi ..." data-validation="required" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-danger btn-flat" <?php if($batal_verifikasi>=$batasanbatalverifikasi){?>disabled="true"<?php }?>>Batal Verifikasi</button>
				</div>
			</form>
		</div>
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header with-border">
				<i class="glyphicon glyphicon-th-list"></i>
				<h3 class="box-title text-info"><b>Daftar Pendaftaran</b></h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<p><b>"Daftar Pendaftaran"</b> akan muncul jika sudah melakukan pendaftaran</b>.</p>
					</div>
					<?php
						$jumlahpendaftaran=0;
						foreach($daftarpendaftaran->getResult() as $row):
						$jumlahpendaftaran++;
					?>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="box box-warning">
							<div class="box-header with-border">
								<h3 class="box-title">
									<p>(<b><?php echo $row->npsn;?></b>) <b><?php echo $row->sekolah;?></b></p>
								</h3>
							</div>
							<div class="box-body">
								<table class="table table-striped">
									<?php if($row->jenis_pilihan==0){?>
									<tr>
										<td colspan="3" class="text-danger">Jenis pilihan belum diperbaharui, silahkan lakukan perbaikan melalui menu <b><i class="glyphicon glyphicon-edit"></i> Ubah Pilihan</b> diatas (<i class="glyphicon glyphicon-arrow-up"></i>) ini</td>
									</tr>
									<?php }?>
									<tr <?php if($row->jenis_pilihan==0){?>class="bg-red"<?php }?>>
										<td><b>Jenis Pilihan</b></td>
										<td>:</td>
										<td>Pilihan <?php if($row->jenis_pilihan!=0){?><?php echo $row->jenis_pilihan;?><?php }else{?>Belum diperbaharui<?php }?></td>
									</tr>
									<tr>
										<td><b>Jalur</b></td>
										<td>:</td>
										<td><?php echo $row->jalur;?></td>
									</tr>
									<tr>
										<td><b>Waktu Pendaftaran</b></td>
										<td>:</td>
										<td><?php echo $row->create_date;?></td>
									</tr>
									<tr>
										<td><b>Nomor Pendaftaran</b></td>
										<td>:</td>
										<td><?php echo $row->nomor_pendaftaran;?></td>
									</tr>
									<tr>
										<td><b>Peringkat</b></td>
										<td>:</td>
										<td><?php if($row->peringkat>0){?><?php echo $row->peringkat;?><?php }else{?>Belum Ada Peringkat<?php }?><span class="pull-right"><a href="<?php echo base_url();?>index.php/Chome/peringkat?sekolah_id=<?php echo $row->sekolah_id;?>&bentuk=<?php echo $row->bentuk;?>" target="_blank"><i class="glyphicon glyphicon-search"></i> Lihat Peringkat</a></span></td>
									</tr>
									<tr>
										<td><b>Status Pendaftaran</b></td>
										<td>:</td>
										<td class="<?php if($row->status_penerimaan==0){?>bg-gray<?php }else if($row->status_penerimaan==1){?>bg-green<?php }else {?>bg-red<?php }?>"><?php if($row->status_penerimaan==0){?><i class="glyphicon glyphicon-search"></i> Dalam Proses Seleksi<?php }else if($row->status_penerimaan==1){?><i class="glyphicon glyphicon-check"></i> Masuk Dalam Kuota<?php }else {?><i class="glyphicon glyphicon-info-sign"></i> Tidak Masuk Kuota<?php }?></td>
									</tr>
								</table><br>
								<table class="table table-bordered">
									<tr class="bg-blue">
										<th>Daftar Kelengkapan Berkas</th>
										<th class="text-center">Verifikasi</th>
									</tr>
									<?php
										//$this->load->model(array('Mhome'));
										$kelengkapanpendaftaran = $this->Mhome->kelengkapanpendaftaran($row->pendaftaran_id);
										foreach($kelengkapanpendaftaran->getResult() as $row2):
									?>
									<tr <?php if($row2->kondisi_khusus>0){?>class="bg-warning"<?php }?>>
										<td><?php echo $row2->kelengkapan;?></td>
										<td class="text-center"><?php if($row2->verifikasi==1){?><i class="text-blue glyphicon glyphicon-ok"></i><?php }else if($row2->verifikasi==2){?><i class="text-red glyphicon glyphicon-remove"></i><?php }else{?>Dalam Proses<?php }?></td>
									</tr>
									<?php endforeach;?>
									<tr>
										<td colspan="2" class="text-warning"><b>Note</b> : Jika ada kelengkapan yang bertanda <i class="text-red glyphicon glyphicon-remove"></i> mohon untuk koordinasi dengan sekolah.</td>
									</tr>
								</table><br>
								<table class="table table-bordered">
									<tr class="bg-blue">
										<th>Daftar Skoring</th>
										<th class="text-center">Nilai</th>
									</tr>
									<?php
										$jumlah_nilai = 0;
										$nilaiskoring = $this->Mhome->nilaiskoring($row->pendaftaran_id);
										foreach($nilaiskoring->getResult() as $row3):
									?>
									<tr>
										<td><?php echo $row3->keterangan;?></td>
										<td class="text-right"><?php echo $row3->nilai;?></td>
									</tr>
									<?php $jumlah_nilai = $jumlah_nilai+$row3->nilai; endforeach;?>
									<tr class="bg-gray">
										<th>Total</th>
										<th class="text-right"><?php echo $jumlah_nilai;?></th>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<?php endforeach;?>
				</div>
				<?php endforeach;?>
			</div>
		</div>
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
	<?php foreach($profilsiswa->getResult() as $row):?>
	var map = L.map('peta',{zoomControl:false}).setView([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>],16);
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

	L.marker([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>]).addTo(layerGroup).bindPopup("<?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?>").openPopup();
	new L.control.fullscreen({position:'bottomleft'}).addTo(map);
	new L.Control.Zoom({position:'bottomright'}).addTo(map);
	<?php endforeach;?>
</script>