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
<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css"/>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet'/>

<!-- <div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_1" data-toggle="tab">Detail Pendaftaran</a></li>
	</ul>
	<div class="tab-content bg-gray">
		<div class="tab-pane active" id="tab_1"> -->

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="box box-solid">
					<div class="box-header with-border">
						<i class="glyphicon glyphicon-user"></i>
						<h3 class="box-title"><b>Pendaftaran</b></h3>
					</div>
					<div class="box-body">
						<table class="table table-striped">
							<?php foreach($detailpendaftaran->getResult() as $row):?>
							<tr <?php if($row->jenis_pilihan==0){?>class="bg-red"<?php }else{?>class="bg-warning"<?php }?>>
								<td><b>Jenis Piihan</b></td>
								<td>:</td>
								<td><?php echo $row->jenis_pilihan;?></td>
							</tr>
							<tr>
								<td><b>Sekolah Pilihan</b></td>
								<td>:</td>
								<td>(<b><?php echo $row->npsn;?></b>) <a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/<?php echo $row->sekolah_id;?>" target="_blank"><?php echo $row->sekolah;?></a></td>
							</tr>
							<tr>
													<td><b>Jalur</b></td>
													<td>:</td>
													<td><?php echo $row->jalur;?></td>
												</tr>
												<tr>
													<td><b>Waktu Pendaftaran</b></td>
													<td>:</td>
													<td><?php echo $row->created_on;?></td>
												</tr>
												<tr>
													<td><b>Nomor Pendaftaran</b></td>
													<td>:</td>
													<td><?php echo $row->nomor_pendaftaran;?></td>
												</tr>
												<tr>
													<td><b>Peringkat</b></td>
													<td>:</td>
													<td>
													<?php if($row->peringkat==0 || $row->peringkat==9999){?>Belum Ada Peringkat
													<?php } else if($row->peringkat==-1){?>Tidak Ada Peringkat
													<?php } else{?><?php echo $row->peringkat;?>
													<?php }?>
													<span class="pull-right"><a href="<?php echo base_url();?>index.php/Chome/peringkat?sekolah_id=<?php echo $row->sekolah_id;?>" target="_blank"><i class="glyphicon glyphicon-search"></i> Lihat Peringkat</a></span></td>
												</tr>
												<tr>
													<td><b>Status Pendaftaran</b></td>
													<td>:</td>
													<td class="
													<?php if($row->status_penerimaan==0){?>bg-gray
													<?php }else if($row->status_penerimaan==1){?>bg-green
													<?php }else if($row->status_penerimaan==2){?>bg-red
													<?php }else if($row->status_penerimaan==3){?>bg-yellow
													<?php }else if($row->status_penerimaan==4){?>bg-gray
													<?php }else {?>bg-gray
													<?php }?>">
													<?php if($row->status_penerimaan==0){?><i class="glyphicon glyphicon-search"></i> Dalam Proses Seleksi
													<?php }else if($row->status_penerimaan==1){?><i class="glyphicon glyphicon-check"></i> Masuk Kuota
													<?php }else if($row->status_penerimaan==2){?><i class="glyphicon glyphicon-info-sign"></i> Tidak Masuk Kuota
													<?php }else if($row->status_penerimaan==3){?><i class="glyphicon glyphicon-check"></i> Daftar Tunggu
													<?php }else if($row->status_penerimaan==4){?><i class="glyphicon glyphicon-info-sign"></i> Diterima Pilihan 1
													<?php }else {?><i class="glyphicon glyphicon-search"></i> Dalam Proses Seleksi
													<?php }?></td>
												</tr>
							<?php endforeach;?>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="box box-solid">
					<?php foreach($detailpendaftaran->getResult() as $row):?>
					<form role="form" enctype="multipart/form-data" id="proseshapuspendaftaran" action="<?php echo base_url();?>index.php/siswa/pendaftaran/proseshapus" method="post">
						<input type="hidden" id="peserta_didik_id" name="peserta_didik_id" value="<?php echo $row->peserta_didik_id;?>">
						<input type="hidden" id="pendaftaran_id" name="pendaftaran_id" value="<?php echo $row->pendaftaran_id;?>">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-remove text-danger"></i>
							<h3 class="box-title text-info"><b>Formulir Hapus Pendaftaran</b></h3>
						</div>
						<div class="box-body">
							<!-- <div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> -->
									<div class="form-group has-feedback">
										<label for="keterangan">Alasan Hapus Pendaftaran</label>
										<textarea id="keterangan" name="keterangan" placeholder="Penjelasan terkait Hapus Pendaftaran ..." data-validation="required" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
									</div>
								<!-- </div>
							</div> -->
						</div>
						<div class="box-footer">
							<button type="submit" class="btn btn-danger btn-flat" <?php if($maxhapuspendaftaran<=$hapuspendaftaransiswa||$this->session->userdata("tutup_akses")==1){?>disabled="true"<?php }?>>Hapus Pendaftaran</button>
						</div>
					</form>
					<?php endforeach;?>
				</div>
			</div>
		</div>
	</div>
</div>

		<!-- </div>
	</div>
</div> -->

<!-- <script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script> -->
<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>
<script>
	//Validasi
	var $messages = $('#error-message-wrapper');
	$.validate({
		modules: 'security',
		errorMessagePosition: $messages,
		scrollToTopOnError: false
	});
</script>