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

	$batasancabutberkas = 0;
	foreach($referensibatasanperubahan->getResult() as $row):
		$batasancabutberkas = $row->cabut_berkas;
	endforeach;

	foreach($settingpendaftaran->getResult() as $row):
		$tanggal_mulai_aktif = $row->tanggal_mulai_aktif;
		$tanggal_selesai_aktif = $row->tanggal_selesai_aktif;
	endforeach;

?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css"/>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet'/>
<div class="alert alert-danger alert-dismissable">
	<i class="icon glyphicon glyphicon-warning-sign"></i>
	Untuk cabut berkas dimasing-masing siswa hanya dibatasi <b><?php echo $batasancabutberkas;?> kali</b>. Mohon berhati-hati dan berkonfirmasi lebih lanjut dengan Siswa/Orang Tua yang bersangkutan.
</div>
<div class="row">
	<?php 
		$peserta_didik_id = "";
		$kode_kabupaten = "";
		$kode_kecamatan = "";
		$kode_wilayah = "";
		$tanggal_lahir = "";
		$lintang = 0;
		$bujur = 0;
		$cabut_berkas = 0;

		$pendaftaran_id = $this->input->get("pendaftaran_id");

		foreach($profilsiswa->getResult() as $row):
			$peserta_didik_id = $row->peserta_didik_id;
			$kode_kabupaten = $row->kode_kabupaten;
			$kode_kecamatan = $row->kode_kecamatan;
			$kode_wilayah = $row->kode_wilayah;
			$tanggal_lahir = $row->tanggal_lahir;
			$lintang = $row->lintang;
			$bujur = $row->bujur;
			$cabut_berkas = $row->cabut_berkas;

	?>
	<?php if ($cabut_berkas >= $batasancabutberkas) { ?>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="alert alert-info alert-dismissable">
			<i class="icon glyphicon glyphicon-info-sign"></i></h4>
			Sudah tidak bisa melakukan cabut berkas. Batasan maksimal sudah tercapai.
		</div>
		</div>
	<?php } ?>
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
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" >
		<div class="box box-solid"> 
			<form role="form" enctype="multipart/form-data" id="prosesperubahandatasiswa" action="<?php echo base_url();?>index.php/Csekolah/prosescabutberkas/" method="post">
				<input type="hidden" id="peserta_didik_id" name="peserta_didik_id" value="<?php echo $peserta_didik_id;?>">
				<input type="hidden" id="pendaftaran_id" name="pendaftaran_id" value="<?php echo $pendaftaran_id;?>">
				<div class="box-header with-border">
					<i class="glyphicon glyphicon-remove text-danger"></i>
					<h3 class="box-title text-info"><b>Formulir Cabut Berkas</b></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group has-feedback">
								<label for="keterangan">Alasan Cabut Berkas</label>
								<textarea id="keterangan" name="keterangan" placeholder="Penjelasan terkait Cabut Berkas ..." data-validation="required" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-danger btn-flat" <?php if($cabut_berkas>=$batasancabutberkas||(date("Y-m-d H:i:s:u")<$tanggal_mulai_aktif||date("Y-m-d H:i:s:u")>$tanggal_selesai_aktif)){?>disabled="true"<?php }?>>Cabut Berkas</button>
				</div>
			</form> 
		</div>
	</div>
	<?php endforeach;?>
</div>
<script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>
<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>
<script src="<?php echo base_url();?>assets/leaflet/leaflet.js"></script>
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>
<script>
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

	L.marker([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>]).addTo(map).bindPopup("<?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?>").openPopup();
	new L.control.fullscreen({position:'bottomleft'}).addTo(map);
	new L.Control.Zoom({position:'bottomright'}).addTo(map);
	<?php endforeach;?>
</script>