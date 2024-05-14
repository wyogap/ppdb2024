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
<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/iCheck/all.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css"/>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet'/>
<div class="row">
	<?php foreach($detailperubahandatasiswa->getResult() as $row):?>
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
								<td><?php echo $row->desa_lama;?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header with-border">
				<i class="glyphicon glyphicon-saved text-warning"></i>
				<h3 class="box-title text-warning"><b>Daftar Pengajuan Perubahan Data</b></h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="table table-striped">
							<tr class="text-danger">
								<td><b>Desa Lama</b></td>
								<td>:</td>
								<td><?php echo $row->desa_lama;?></td>
							</tr>
							<tr>
								<td><b>Desa Baru</b></td>
								<td>:</td>
								<td><?php echo $row->desa_baru;?></td>
							</tr>
							<tr class="text-danger">
								<td><b>Tanggal Lahir Lama</b></td>
								<td>:</td>
								<td><?php echo tanggal_indo($row->tanggal_lahir_lama);?></td>
							</tr>
							<tr>
								<td><b>Tanggal Lahir Baru</b></td>
								<td>:</td>
								<td><?php echo tanggal_indo($row->tanggal_lahir_baru);?></td>
							</tr>
							<tr class="text-danger">
								<td><b>Lintang Lama</b></td>
								<td>:</td>
								<td><?php echo $row->lintang_lama;?></td>
							</tr>
							<tr>
								<td><b>Lintang Baru</b></td>
								<td>:</td>
								<td><?php echo $row->lintang_baru;?></td>
							</tr>
							<tr class="text-danger">
								<td><b>Bujur Lama</b></td>
								<td>:</td>
								<td><?php echo $row->bujur_lama;?></td>
							</tr>
							<tr>
								<td><b>Bujur Baru</b></td>
								<td>:</td>
								<td><?php echo $row->bujur_baru;?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<form role="form" enctype="multipart/form-data" id="prosespengajuanakun" action="<?php echo base_url();?>index.php/admin/prosesperubahandata/" method="post">
					<input type="hidden" id="peserta_didik_id" name="peserta_didik_id" value="<?php echo $row->peserta_didik_id;?>">
					<input type="hidden" id="kode_wilayah" name="kode_wilayah" value="<?php echo $row->kode_wilayah;?>">
					<input type="hidden" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo $row->tanggal_lahir;?>">
					<input type="hidden" id="lintang" name="lintang" value="<?php echo $row->lintang;?>">
					<input type="hidden" id="bujur" name="bujur" value="<?php echo $row->bujur;?>">
					<input type="hidden" id="asal_data" name="asal_data" value="<?php echo $row->asal_data;?>">
					<div class="form-group">
						<label>
							<input type="radio" id="approval" name="approval" class="flat-red" value="2" data-validation="required">
							Tidak Disetujui
						</label>
						<label>
							<input type="radio" id="approval" name="approval" class="flat-green" value="1" data-validation="required">
							Disetujui
						</label>
					</div>
					<div class="form-group has-feedback">
						<textarea id="keterangan_approval" name="keterangan_approval" placeholder="Penjelasan terkait Persetujuan ..." data-validation="required" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
					</div>
					<button type="submit" class="btn btn-primary btn-flat">Proses Persetujuan</button>
				</form>
			</div>
		</div>
	</div>
	<?php endforeach;?>
</div>
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
	<?php foreach($detailperubahandatasiswa->getResult() as $row):?>
	var LeafIcon = L.Icon.extend({
		options: {
			shadowUrl: '<?php echo base_url();?>assets/leaflet/images/marker-icon.png'
		}
	});
	var blue = new LeafIcon({iconUrl: '<?php echo base_url();?>assets/leaflet/images/marker-icon.png'}),
	red = new LeafIcon({iconUrl: '<?php echo base_url();?>assets/leaflet/images/marker-icon-red.png'}),
	green = new LeafIcon({iconUrl: '<?php echo base_url();?>assets/leaflet/images/marker-icon-green.png'});
	var map = L.map('peta',{zoomControl:false}).setView([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>],16);
	L.tileLayer(
		'<?php echo $servis_peta_aktif;?>',{maxZoom: 18,attribution: 'PPDB <?php echo $wilayah_aktif;?>',id: 'mapbox.streets'}
	).addTo(map);

	L.marker([<?php echo $row->lintang_lama;?>,<?php echo $row->bujur_lama;?>], {icon: red}).addTo(map).bindPopup("Koordinat Lama");
	L.marker([<?php echo $row->lintang_baru;?>,<?php echo $row->bujur_baru;?>], {icon: green}).addTo(map).bindPopup("Koordinat Baru");

	map.fitBounds([
		[<?php echo $row->lintang_lama;?>,<?php echo $row->bujur_lama;?>],
		[<?php echo $row->lintang_baru;?>,<?php echo $row->bujur_baru;?>]
	]);
	new L.control.fullscreen({position:'bottomleft'}).addTo(map);
	new L.Control.Zoom({position:'bottomright'}).addTo(map);
	<?php endforeach;?>
</script>