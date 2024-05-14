	<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
 	<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Editor-2.0.6/css/editor.dataTables.min.css">
  	<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Select-1.3.1/css/select.dataTables.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/css/buttons.dataTables.min.css">

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
	$bentuk = $this->session->userdata('bentuk');

	$profil_konfirmasi = 0;
	$lokasi_konfirmasi = 0;
	$nilai_konfirmasi = 0;
	$prestasi_konfirmasi = 0;
	$afirmasi_konfirmasi = 0;
	$inklusi_konfirmasi = 0;

	$profil_verifikasi = 0;
	$lokasi_verifikasi = 0;
	$nilai_verifikasi = 0;
	$prestasi_verifikasi = 0;
	$afirmasi_verifikasi = 0;
	$inklusi_verifikasi = 0;

	$kebutuhan_khusus = "";

	$pernyataan_file = "";
	$pernyataan_tanggal = "18 Mei 2020";

	$nomor_handphone = "";

	$verifikasi_oleh = "";
	$verifikasi_tanggal = "";

	$punya_nilai_un = 0;
	$punya_prestasi = 0;
	$punya_kip = 0;
	$masuk_bdt = 0;
	$nomor_kip = "";
	$nomor_bdt = "";

	foreach($profilsiswa->getResult() as $row) {
		$profil_verifikasi = $row->verifikasi_profil;
		$lokasi_verifikasi = $row->verifikasi_lokasi;
		$nilai_verifikasi = $row->verifikasi_nilai;
		$prestasi_verifikasi = $row->verifikasi_prestasi;
		$afirmasi_verifikasi = $row->verifikasi_afirmasi;
		$inklusi_verifikasi = $row->verifikasi_inklusi;

		$profil_konfirmasi = $row->konfirmasi_profil;
		$lokasi_konfirmasi = $row->konfirmasi_lokasi;
		$nilai_konfirmasi = $row->konfirmasi_nilai;
		$prestasi_konfirmasi = $row->konfirmasi_prestasi;
		$afirmasi_konfirmasi = $row->konfirmasi_afirmasi;
		$inklusi_konfirmasi = $row->konfirmasi_inklusi;

		$punya_nilai_un = $row->punya_nilai_un;
		$punya_prestasi = $row->punya_prestasi;
		$punya_kip = $row->punya_kip;
		$masuk_bdt = $row->masuk_bdt;
	
		$nomor_kip = $row->nomor_kip;
		$nomor_bdt = $row->nomor_bdt;
		$kebutuhan_khusus = $row->kebutuhan_khusus;
		$nomor_handphone = $row->nomor_kontak;
		$pernyataan_file = $row->path_surat_pernyataan;
		$pernyataan_tanggal = $row->tanggal_surat_pernyataan;

		$verifikasi_oleh = $row->nama_verifikator;
		$verifikasi_tanggal = $row->tanggal_verifikasi;
	}

	//echo $kebutuhan_khusus;

	//for consistency
	if (empty($kebutuhan_khusus) || $kebutuhan_khusus == "0") {
		$kebutuhan_khusus == "Tidak ada";
	}

	$tahapan_nama = "";
	$tahapan_id = 0;
	$notifikasi_siswa = "";
	foreach($tahapan->getResult() as $row) {
		$tahapan_nama = $row->tahapan;
		$tahapan_id = $row->tahapan_id;
		$notifikasi_siswa = $row->notifikasi_siswa;
	}

	//selama ada pendaftaran aktif, dikunci dan tidak bisa diedit
	if ($dikunci) {
		if ($profil_verifikasi != 2) $profil_konfirmasi = 1;
		if ($lokasi_verifikasi != 2) $lokasi_konfirmasi = 1;
		if ($nilai_verifikasi != 2) $nilai_konfirmasi = 1;
		if ($prestasi_verifikasi != 2) $prestasi_konfirmasi = 1;
		if ($afirmasi_verifikasi != 2) $afirmasi_konfirmasi = 1;
		if ($inklusi_verifikasi != 2) $inklusi_konfirmasi = 1;
	}

	$pernyataan_verifikasi = 0;
	if (!empty($dokumen[21])) {
		$pernyataan_verifikasi = $dokumen[21]['verifikasi'];
	}

	//echo "afirmasi_verifikasi:$afirmasi_verifikasi; afirmasi_konfirmasi:$afirmasi_konfirmasi";
?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css"/>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet'/>
<!-- <div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_1" data-toggle="tab">Identitas</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_1"> -->

			<?php foreach($tahapan->getResult() as $row): 
				if(!empty($row->notifikasi_siswa)) {
			?>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="alert alert-success alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p><i class="icon glyphicon glyphicon-info-sign"></i>Tahapan <?php echo $row->tahapan; ?></p>
							<p><?php echo $row->notifikasi_siswa; ?></p>
						</div>
					</div>
				</div>
			<?php 
				}
			endforeach; ?>

			<?php if ($dikunci > 0) { ?>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="alert alert-info alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p><i class="icon glyphicon glyphicon-info-sign"></i>Data profil terkunci karena anda sudah melakukan pendaftaran.</p>
						</div>
					</div>
				</div>
			<?php } ?>

			<?php foreach($profilsiswa->getResult() as $row):?>
			<?php if ($row->ganti_password == 0) { ?>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p><i class="icon glyphicon glyphicon-warning-sign"></i>Anda belum mengganti PIN anda. <b>Segera ganti PIN awal anda!</b></p>
						<p style="margin-top: 15px !important;"><a href="<?php echo base_url();?>index.php/akun/password" class="btn btn-primary">Ganti PIN</a></p>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="row" data-editor-id="<?php echo $row->peserta_didik_id; ?>">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-book"></i>
							<h3 class="box-title text-info"><b>Asal Sekolah</b></h3>
						</div>
						<div class="box-body">
							<div class="row">
								<?php if($row->sekolah_id!=""){?>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<p>(<b><?php echo $row->npsn;?></b>) <?php echo $row->sekolah;?></p>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/<?php echo $row->sekolah_id;?>" target="_blank" class="btn btn-default">Profil Sekolah Asal</a>
								</div>
								<?php }else{?>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<p>Tidak bersekolah sebelumnya.</p>
								</div>
								<?php }?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row" data-editor-id="<?php echo $row->peserta_didik_id; ?>">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-phone"></i>
							<h3 class="box-title text-info"><b>Nomor Handphone Aktif</b></h3>
						</div>
						<div class="box-body">
							<div class="row">
								<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
									<button class="btn">Baru</button>
									<button class="btn">Hapus</button>
								</div> -->
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<table class="table table-striped" style="margin-bottom: 0px !important;">
										<tr>
											<td colspan="1">Sebagai bagian dari proses verifikasi dokumen secara dalam jaringan (daring), kami membutuhkan nomor handphone aktif yang bisa dihubungi sebagai media komunikasi apabila ada dokumen yang perlu diperbaiki ataupun persyaratan tambahan yang perlu dilengkapi.  
											</td>
										</tr>
										<tr id="kontak-ubah-row">
											<td colspan="1">Nomor handphone aktif: <input id="nomor_kontak" type="text" value="<?php echo $nomor_handphone; ?>"></input> <button id="btn_nomor_kontak">Simpan</button>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row" data-editor-id="<?php echo $row->peserta_didik_id; ?>">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-user"></i>
							<h3 class="box-title text-info"><b>Identitas Siswa</b></h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<table class="table table-striped" style="margin-bottom: 20px !important;">
										<tr>
											<td style="width: 45%;"><b>NIK</b></td>
											<td>:</td>
											<td style="width: 50%;"><?php echo $row->nik;?></td>
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
											<td style="width: 45%;"><b>Nama Ibu Kandung</b></td>
											<td>:</td>
											<td style="width: 50%;"><?php echo $row->nama_ibu_kandung;?></td>
										</tr>
									</table>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<table class="table table-striped" style="margin-bottom: 0px !important;">
										<tr>
											<td colspan="3"><b>Alamat (Sesuai Kartu Keluarga)</b></td>
										</tr>
										<?php if (!empty($padukuhan)) { ?>
										<tr>
											<td><b>Padukuhan</b></td>
											<td>:</td>
											<td><?php echo $row->padukuhan;?></td>
										</tr>
										<?php } ?>
										<tr>
											<td><b>Desa/Kelurahan</b></td>
											<td>:</td>
											<td><?php if($row->desa_kelurahan!=""){?><?php echo $row->desa_kelurahan;?><?php }else{?><b>Desa/Kelurahan masih kosong</b><?php }?></td>
										</tr>
										<tr>
											<td><b>Kecamatan</b></td>
											<td>:</td>
											<td><?php echo $row->kecamatan;?></td>
										</tr>
										<tr>
											<td><b>Kabupaten/Kota</b></td>
											<td>:</td>
											<td><?php echo $row->kabupaten;?></td>
										</tr>
										<tr>
											<td><b>Provinsi</b></td>
											<td>:</td>
											<td><?php echo $row->provinsi;?></td>
										</tr>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<table class="table table-striped" style="margin-bottom: 0px !important; margin-top: 20px !important;">
										<tr>
											<td colspan="3" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;"><b>Dokumen Pendukung</b></td>
										</tr>
										<tr>
											<td style="width: 45%;"><b>Akte Kelahiran</b></td>
											<td>:</td>
											<td style="width: 50%;">
												<?php if($upload_dokumen) { ?>
													<img id="dokumen-5" class="img-view-thumbnail" 
															src="<?php echo (empty($dokumen[5])) ? '' : $dokumen[5]['thumbnail_path']; ?>" 
															img-path="<?php echo (empty($dokumen[5])) ? '' : $dokumen[5]['web_path']; ?>" 
															img-id="<?php echo (empty($dokumen[5])) ? '' : $dokumen[5]['dokumen_id']; ?>" 
															img-title="Akte Kelahiran"
															style="display:none; "/> 
													<button id="unggah-dokumen-5" class="img-view-button" data-editor-field="dokumen_5" data-editor-value="<?php echo (empty($dokumen[5])) ? '' : $dokumen[5]['dokumen_id']; ?>" >Unggah</button>
													<?php if(!empty($dokumen[5])) { ?>
													<div>
													<a id='label-dokumen-5' href="<?php echo $dokumen[5]['web_path']; ?>" target="_blank">
														<?php echo $dokumen[5]['filename'] ?>
													</a>
													</div>
													<?php } ?>
													<div id="msg-dokumen-5" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
												<?php } ?>
											</td>
										</tr>
										<tr>
											<td style="width:45%;"><b>Kartu Keluarga</b></td>
											<td>:</td>
											<td>
												<?php if($upload_dokumen) { ?>
													<img id="dokumen-6" class="img-view-thumbnail" 
														src="<?php echo (empty($dokumen[6])) ? '' : $dokumen[6]['thumbnail_path']; ?>" 
														img-path="<?php echo (empty($dokumen[6])) ? '' : $dokumen[6]['web_path']; ?>" 
														img-id="<?php echo (empty($dokumen[6])) ? '' : $dokumen[6]['dokumen_id']; ?>" 
														img-title="Kartu Keluarga"
															style="display:none; "/> 
													<button id="unggah-dokumen-6" 
													class="img-view-button" data-editor-field="dokumen_6" data-editor-value="<?php echo (empty($dokumen[6])) ? '' : $dokumen[6]['dokumen_id']; ?>" >Unggah</button>
													<?php if(!empty($dokumen[6])) { ?>
													<div>
													<a id='label-dokumen-6' href="<?php echo $dokumen[6]['web_path']; ?>" target="_blank">
														<?php echo $dokumen[6]['filename'] ?>
													</a>
													</div>
													<?php } ?>
													<div id="msg-dokumen-6" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
												<?php } ?>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<?php if ($dikunci == 0 || $profil_verifikasi == 2) { ?>
						<div class="box-footer">
								<table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
									<tr id="profil-konfirmasi-row">
										<td colspan="1">
											<b>Apakah data identitas di atas sudah benar? </b>
											<select id="profil-konfirmasi" name="profil-konfirmasi">
											<option value="0" <?php if($profil_konfirmasi==0 || $profil_konfirmasi == 2) { echo "selected"; } ?>>BELUM Benar</option>
											<option value="1" <?php if($profil_konfirmasi==1) { echo "selected"; } ?>>SUDAH Benar</option>
											</select>
										</td>
									</tr>
									<tr id="profil-error-row" class="box-red" style="display: none;">
										<td colspan="1">
											<span style="color: red; " id="profil-error-msg"></span>
										</td>
									</tr>
							</table>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-map-marker"></i>
							<h3 class="box-title text-info"><b>Lokasi Rumah</b></h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div id="peta" style="width: 100%; height: 400px;"></div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
										<tr>
											<td><span id="row-span-lintang"><i class="glyphicon glyphicon-edit"></i> </span><b>Lintang</b></td>
											<td>:</td>
											<td id="row-lintang-value" style="width: 50%;"><?php echo $row->lintang;?></td>
										</tr>
										<tr>
											<td><span id="row-span-bujur"><i class="glyphicon glyphicon-edit"></i> </span><b>Bujur</b></td>
											<td>:</td>
											<td id="row-bujur-value"><?php echo $row->bujur;?></td>
										</tr>
										<tr>
											<td colspan="3"></td>
										</tr>
										<tr>
											<td colspan="3" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;"><b>Dokumen Pendukung</b></td>
										</tr>
										<tr>
											<td style="width:45%;"><b>Surat Domisili disertai Rapor Kelas 6 (Apabila lokasi rumah berbeda dengan alamat pada Kartu Keluarga)</b></td>
											<td>:</td>
											<td style="width: 50%;">
												<?php if($upload_dokumen) { ?>
													<img id="dokumen-19" class="img-view-thumbnail" 
															src="<?php echo (empty($dokumen[19])) ? '' : $dokumen[19]['thumbnail_path']; ?>" 
															img-path="<?php echo (empty($dokumen[19])) ? '' : $dokumen[19]['web_path']; ?>" 
															img-id="<?php echo (empty($dokumen[19])) ? '' : $dokumen[19]['dokumen_id']; ?>" 
															img-title="Surat Keterangan Domisili"
															style="display:none; "/>  
													<button id="unggah-dokumen-19" class="img-view-button" data-editor-field="dokumen_19" data-editor-value="<?php echo (empty($dokumen[19])) ? '' : $dokumen[19]['dokumen_id']; ?>" >Unggah</button>
													<?php if(!empty($dokumen[19])) { ?>
													<div>
													<a id='label-dokumen-19' href="<?php echo $dokumen[19]['web_path']; ?>" target="_blank">
														<?php echo $dokumen[19]['filename'] ?>
													</a>
													</div>
													<?php } ?>
													<div id="msg-dokumen-19" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
												<?php } ?>
											</td>
										</tr>
										<tr>
											<td id="row-dokumen-kelas6" style="width: 45%;"><b>Rapor Kelas 6</b></td>
											<td>:</td>
											<td style="width: 50%;">
												<?php if($upload_dokumen) { ?>
													<img id="dokumen-26" class="img-view-thumbnail" 
															src="<?php echo (empty($dokumen[26])) ? '' : $dokumen[26]['thumbnail_path']; ?>" 
															img-path="<?php echo (empty($dokumen[26])) ? '' : $dokumen[26]['web_path']; ?>" 
															img-id="<?php echo (empty($dokumen[26])) ? '' : $dokumen[26]['dokumen_id']; ?>" 
															img-title="Rapor Kelas 6"
															style="display:none; "/>  
													<button id="unggah-dokumen-26" class="img-view-button" data-editor-field="dokumen_26" data-editor-value="<?php echo (empty($dokumen[26])) ? '' : $dokumen[26]['dokumen_id']; ?>" >Unggah</button>
													<?php if(!empty($dokumen[26])) { ?>
													<div>
													<a id='label-dokumen-26' href="<?php echo $dokumen[26]['web_path']; ?>" target="_blank">
														<?php echo $dokumen[26]['filename'] ?>
													</a>
													</div>
													<?php } ?>
													<div id="msg-dokumen-26" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
												<?php } ?>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<?php if ($dikunci == 0 || $lokasi_verifikasi == 2) { ?>
						<div class="box-footer">
							<table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
									<tr id="lokasi-konfirmasi-row">
										<td colspan="1">
											<b>Apakah data lokasi rumah di atas sudah benar? </b>
											<select id="lokasi-konfirmasi" name="lokasi-konfirmasi">
											<option value="0" <?php if($lokasi_konfirmasi==0 || $lokasi_konfirmasi == 2) { echo "selected"; } ?>>BELUM Benar</option>
											<option value="1" <?php if($lokasi_konfirmasi==1) { echo "selected"; } ?>>SUDAH Benar</option>
											</select>
										</td>
									</tr>
									<tr id="lokasi-error-row" class="box-red" style="display: none;">
										<td colspan="1">
											<span style="color: red; " id="lokasi-error-msg"></span>
										</td>
									</tr>
							</table>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="row" data-editor-id="<?php echo $row->peserta_didik_id; ?>">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-book"></i>
							<h3 class="box-title text-info"><b>Nilai Kelulusan / Nilai Ujian Nasional</b></h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<table class="table table-striped" style="margin-bottom: 20px !important;">
										<tr>
											<td colspan="3"><b>Nilai Kelulusan</b></td>
										</tr>
										<tr>
											<td style="width: 45%;"><b>Nilai Rata-rata Rapor</b></td>
											<td>:</td>
											<td style="width: 50%;"><div style="display: flex;"><span id="row-span-rapor" class="editable-icon"><i class="glyphicon glyphicon-edit"></i> </span>
											<div id="nilai_semester" data-editor-field="nilai_semester" style="margin-left: 3px; flex-grow: 1;"><?php echo $row->nilai_semester;?></div>
											</td>
										</tr>
										<tr>
											<td style="width: 45%;"><b>Nilai Rata-rata Ujian Sekolah</b></td>
											<td>:</td>
											<td style="width: 50%;"><div style="display: flex;"><span id="row-span-skl" class="editable-icon"><i class="glyphicon glyphicon-edit"></i> </span>
											<div id="nilai_lulus" data-editor-field="nilai_lulus" style="margin-left: 3px; flex-grow: 1;"><?php echo $row->nilai_lulus;?></div>
											</td>
										</tr>
									</table>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<table class="table table-striped" style="margin-bottom: 0px !important;">
										<tr>
											<td colspan="3">
												<b>Nilai Ujian Nasional? </b>
												<select id="nilai-un" name="nilai-un">
												<option value="0" <?php if($punya_nilai_un==0||$punya_nilai_un=="") { echo "selected"; } ?>>Tidak</option>
												<option value="1" <?php if($punya_nilai_un==1) { echo "selected"; } ?>>YA</option>
												</select>
											</td>
										</tr>
										<tr id="row-un-bin">
											<td style="width: 45%;"><b>Bahasa Indonesia</b></td>
											<td>:</td>
											<td style="width: 50%;"><div style="display: flex;"><span id="row-span-un-bin" class="editable-icon"><i class="glyphicon glyphicon-edit"></i> </span>
											<div id="nilai_bin" data-editor-field="nilai_bin" style="margin-left: 3px; flex-grow: 1;"><?php echo $row->nilai_bin;?></div>
											</td>
										</tr>
										<tr id="row-un-mat">
											<td><b>Matematika</b></td>
											<td>:</td>
											<td style="width: 50%;"><div style="display: flex;"><span id="row-span-un-mat" class="editable-icon"><i class="glyphicon glyphicon-edit"></i> </span>
											<div id="nilai_mat" data-editor-field="nilai_mat" style="margin-left: 3px; flex-grow: 1;"><?php echo $row->nilai_mat;?></div>
											</td>
										</tr>
										<tr id="row-un-ipa">
											<td><b>IPA</b></td>
											<td>:</td>
											<td style="width: 50%;"><div style="display: flex;"><span id="row-span-un-ipa" class="editable-icon"><i class="glyphicon glyphicon-edit"></i> </span>
											<div id="nilai_ipa" data-editor-field="nilai_ipa" style="margin-left: 3px; flex-grow: 1;"><?php echo $row->nilai_ipa;?></div>
											</td>
										</tr>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<table class="table table-striped" style="margin-bottom: 0px !important; margin-top: 20px !important">
										<tr>
											<td colspan="3" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;"><b>Dokumen Pendukung</b></td>
										</tr>
										<tr>
											<td id="row-dokumen-skl" style="width: 45%;"><b>Surat Keterangan Lulus</b></td>
											<td>:</td>
											<td style="width: 50%;">
												<?php if($upload_dokumen) { ?>
													<img id="dokumen-2" class="img-view-thumbnail" 
															src="<?php echo (empty($dokumen[2])) ? '' : $dokumen[2]['thumbnail_path']; ?>" 
															img-path="<?php echo (empty($dokumen[2])) ? '' : $dokumen[2]['web_path']; ?>" 
															img-id="<?php echo (empty($dokumen[2])) ? '' : $dokumen[2]['dokumen_id']; ?>" 
															img-title="Ijazah / Surat Keterangan Lulus"
															style="display:none; "/>  
													<button id="unggah-dokumen-2" class="img-view-button" data-editor-field="dokumen_2" data-editor-value="<?php echo (empty($dokumen[2])) ? '' : $dokumen[2]['dokumen_id']; ?>" >Unggah</button>
													<?php if(!empty($dokumen[2])) { ?>
													<div>
													<a id='label-dokumen-2' href="<?php echo $dokumen[2]['web_path']; ?>" target="_blank">
														<?php echo $dokumen[2]['filename'] ?>
													</a>
													</div>
													<?php } ?>
													<div id="msg-dokumen-2" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
												<?php } ?>
											</td>
										</tr>
										<tr id="row-dokumen-un">
											<td><span id="row-span-dokumen-skhun"><b>Hasil Ujian Nasional</b></td>
											<td>:</td>
											<td>
												<?php if($upload_dokumen) { ?>
													<img id="dokumen-3" class="img-view-thumbnail" 
															src="<?php echo (empty($dokumen[3])) ? '' : $dokumen[3]['thumbnail_path']; ?>" 
															img-path="<?php echo (empty($dokumen[3])) ? '' : $dokumen[3]['web_path']; ?>" 
															img-id="<?php echo (empty($dokumen[3])) ? '' : $dokumen[3]['dokumen_id']; ?>" 
															img-title="Surat Keterangan Hasil Ujian Nasional"
															style="display:none; "/>  
													<button id="unggah-dokumen-3" class="img-view-button" data-editor-field="dokumen_3" data-editor-value="<?php echo (empty($dokumen[3])) ? '' : $dokumen[3]['dokumen_id']; ?>" >Unggah</button>
													<?php if(!empty($dokumen[3])) { ?>
													<div>
													<a id='label-dokumen-3' href="<?php echo $dokumen[3]['web_path']; ?>" target="_blank">
														<?php echo $dokumen[3]['filename'] ?>
													</a>
													</div>
													<?php } ?>
													<div id="msg-dokumen-3" class="box-red" style="padding-left: 5px; padding-right: 5px; display: none;"></div>
												<?php } ?>
											</td>
										</tr>
									</table>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								</div>
							</div>
						</div>
						<?php if ($dikunci == 0 || $nilai_verifikasi == 2) { ?>
						<div class="box-footer">
								<table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
									<tr id="nilai-konfirmasi-row">
										<td colspan="1">
											<b>Apakah data nilai UN dan nilai kelulusan di atas sudah benar? </b>
											<select id="nilai-konfirmasi" name="nilai-konfirmasi">
											<option value="0" <?php if($nilai_konfirmasi==0 || $nilai_konfirmasi == 2) { echo "selected"; } ?>>BELUM Benar</option>
											<option value="1" <?php if($nilai_konfirmasi==1) { echo "selected"; } ?>>SUDAH Benar</option>
											</select>
										</td>
									</tr>
									<tr id="nilai-error-row" class="box-red" style="display: none;">
										<td colspan="1">
											<span style="color: red; " id="nilai-error-msg"></span>
										</td>
									</tr>
								</table>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-education"></i>
							<h3 class="box-title text-info"><b>Prestasi</b></h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
										<tr>
											<td colspan="3">
												<b>Punya prestasi akademis atau non-akademis (olahraga/seni/dll)? </b>
												<select id="prestasi-akademis" name="prestasi-akademis">
												<option value="0" <?php if($punya_prestasi==0||$punya_prestasi=="") { echo "selected"; } ?>>Tidak</option>
												<option value="1" <?php if($punya_prestasi==1) { echo "selected"; } ?>>YA</option>
												</select>
											</td>
										</tr>
									</table>
								</div>
								<div id="tbl-prestasi-kontainer" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px; display:none;">
									<table id="tbl-prestasi" class="display" style="width: 100%;">
										<thead>
											<tr>
												<th class="text-center" data-priority="1">#</th>
												<th class="text-center" data-priority="2">Prestasi</th>
												<th class="text-center" style="width: 30%;" data-priority="4">Uraian</th>
												<?php if($upload_dokumen) { ?>
												<th class="text-center" data-priority="3">Dokumen Pendukung</th>
												<?php } ?>
												<th class="text-center" data-priority="5">Catatan</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
						<?php if ($dikunci == 0 || $prestasi_verifikasi == 2) { ?>
						<div class="box-footer">
								<table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
									<tr id="prestasi-konfirmasi-row">
										<td colspan="1">
											<b>Apakah data prestasi di atas sudah benar? </b>
											<select id="prestasi-konfirmasi" name="prestasi-konfirmasi">
											<option value="0" <?php if($prestasi_konfirmasi==0 || $prestasi_konfirmasi == 2) { echo "selected"; } ?>>BELUM Benar</option>
											<option value="1" <?php if($prestasi_konfirmasi==1) { echo "selected"; } ?>>SUDAH Benar</option>
											</select>
										</td>
									</tr>
									<tr id="prestasi-error-row" class="box-red" style="display: none;">
										<td colspan="1">
											<span style="color: red; " id="prestasi-error-msg"></span>
										</td>
									</tr>
								</table>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="row" data-editor-id="<?php echo $row->peserta_didik_id; ?>">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-flag"></i>
							<h3 class="box-title text-info"><b>Program Afirmasi</b></h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<table class="table table-striped" style="margin-bottom: 0px !important;">
										<tr>
											<td colspan="3">
												<b>Punya Kartu Indonesia Pintar? </b>
												<select id="kip" name="kip">
												<option value="0" <?php if($punya_kip==0||$punya_kip=="") { echo "selected"; } ?>>Tidak</option>
												<option value="1" <?php if($punya_kip==1) { echo "selected"; } ?>>YA</option>
												</select>
											</td>
										</tr>
										<tr id="row-kip">
											<td style="width: 45%;"><b>Nomor Kartu Indonesia Pintar</b></td>
											<td>:</td>
											<td style="width: 50%;"><div style="display: flex;"><span id="row-span-kip" class="editable-icon"><i class="glyphicon glyphicon-edit"></i> </span>
											<div id="nomor_kip" data-editor-field="nomor_kip" style="margin-left: 3px; flex-grow: 1;"><?php echo $row->nomor_kip;?></div>
											</td>
										</tr>
										<tr>
											<td colspan="3"></td>
										</tr>
										<tr>
											<td colspan="3">
												<b>Masuk di dalam Basis Data Terpadu dalam kategori Keluarga Miskin? </b>
												<select id="bdt" name="bdt">
												<option value="0" <?php if($masuk_bdt==0||$masuk_bdt=="") { echo "selected"; } ?>>Tidak</option>
												<option value="1" <?php if($masuk_bdt==1) { echo "selected"; } ?>>YA</option>
												</select>
											</td>
										</tr>
										<tr id="row-bdt" style="display: none;">
											<td style="width: 45%;"><b>Nomor PKH/KJS/BDT</b></td>
											<td>:</td>
											<td style="width: 50%;"><div style="display: flex;"><span id="row-span-bdt" class="editable-icon"><i class="glyphicon glyphicon-edit"></i> </span>
											<div id="nomor_bdt" data-editor-field="nomor_bdt" style="margin-left: 3px; flex-grow: 1;"><?php echo $row->nomor_bdt;?></div>
											</td>
										</tr>
										<tr>
											<td colspan="3"></td>
										</tr>
										<tr id="row-dokumen-afirmasi">
											<td colspan="3" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;"><b>Dokumen Pendukung</b></td>
										</tr>
										<tr id="row-dokumen-kip">
											<td style="width: 45%;"><b>Kartu Indonesia Pintar</b></td>
											<td>:</td>
											<td style="width: 50%;">
												<?php if($upload_dokumen) { ?>
													<img id="dokumen-16" class="img-view-thumbnail" 
															src="<?php echo (empty($dokumen[16])) ? '' : $dokumen[16]['thumbnail_path']; ?>" 
															img-path="<?php echo (empty($dokumen[16])) ? '' : $dokumen[16]['web_path']; ?>" 
															img-id="<?php echo (empty($dokumen[16])) ? '' : $dokumen[16]['dokumen_id']; ?>" 
															img-title="Kartu Indonesia Pintar"
															style="display:none; "/>  
													<button id="unggah-dokumen-16" class="img-view-button" data-editor-field="dokumen_16" data-editor-value="<?php echo (empty($dokumen[16])) ? '' : $dokumen[16]['dokumen_id']; ?>" >Unggah</button>
													<?php if(!empty($dokumen[16])) { ?>
													<div>
													<a id='label-dokumen-16' href="<?php echo $dokumen[16]['web_path']; ?>" target="_blank">
														<?php echo $dokumen[16]['filename'] ?>
													</a>
													</div>
													<?php } ?>
													<div id="msg-dokumen-16" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
												<?php } ?>
											</td>
										</tr>
										<tr id="row-dokumen-bdt" style="display: none;">
											<td style="width: 45%;"><b>Kartu PKH / Kartu KJS / Surat Keterangan masuk BDT dari Desa/Kelurahan</b></td>
											<td>:</td>
											<td>
												<?php if($upload_dokumen) { ?>
													<img id="dokumen-20" class="img-view-thumbnail" 
															src="<?php echo (empty($dokumen[20])) ? '' : $dokumen[20]['thumbnail_path']; ?>" 
															img-path="<?php echo (empty($dokumen[20])) ? '' : $dokumen[20]['web_path']; ?>" 
															img-id="<?php echo (empty($dokumen[20])) ? '' : $dokumen[20]['dokumen_id']; ?>" 
															img-title="Surat Keterangan Basis Data Terpadu"
															style="display:none; "/>  
													<button id="unggah-dokumen-20" class="img-view-button" data-editor-field="dokumen_20" data-editor-value="<?php echo (empty($dokumen[20])) ? '' : $dokumen[20]['dokumen_id']; ?>" >Unggah</button>
													<?php if(!empty($dokumen[20])) { ?>
													<div>
													<a id='label-dokumen-20' href="<?php echo $dokumen[20]['web_path']; ?>" target="_blank">
														<?php echo $dokumen[20]['filename'] ?>
													</a>
													</div>
													<?php } ?>
													<div id="msg-dokumen-20" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
												<?php } ?>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<?php if ($dikunci == 0 || $afirmasi_verifikasi == 2) { ?>
						<div class="box-footer">
								<table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
									<tr id="afirmasi-konfirmasi-row">
										<td colspan="1">
											<b>Apakah data afirmasi di atas sudah benar? </b>
											<select id="afirmasi-konfirmasi" name="afirmasi-konfirmasi">
											<option value="0" <?php if($afirmasi_konfirmasi==0 || $afirmasi_konfirmasi == 2) { echo "selected"; } ?>>BELUM Benar</option>
											<option value="1" <?php if($afirmasi_konfirmasi==1) { echo "selected"; } ?>>SUDAH Benar</option>
											</select>
										</td>
									</tr>
									<tr id="afirmasi-error-row" class="box-red" style="display: none;">
										<td colspan="1">
											<span style="color: red; " id="afirmasi-error-msg"></span>
										</td>
									</tr>
								</table>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-modal-window"></i>
							<h3 class="box-title text-info"><b>Program Inklusi</b></h3>
						</div>
						<div class="box-body">
							<div class="row">
								<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
									<button class="btn">Baru</button>
									<button class="btn">Hapus</button>
								</div> -->
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<table class="table table-striped" style="margin-bottom: 0px !important;">
										<tr>
											<td colspan="3">
												<b>Punya Kebutuhan Khusus? </b>
												<select id="inklusi" name="inklusi">
												<option value="0" <?php if($kebutuhan_khusus == 'Tidak ada') { echo "selected"; }?>>Tidak</option>
												<option value="1" <?php if($kebutuhan_khusus != 'Tidak ada') { echo "selected"; }?>>YA</option>
												</select>
											</td>
										</tr>
										<tr id="row-kebutuhan-khusus" <?php if($kebutuhan_khusus == 'Tidak ada') { echo "style='display: none;'"; }?>>
											<td style="width:45%;"><b>Kebutuhan Khusus</b></td>
											<td>:</td>
											<td style="width: 50%;"><div style="display: flex;"><span id="row-span-inklusi" class="editable-icon"><i class="glyphicon glyphicon-edit"></i> </span>
											<div id="kebutuhan_khusus" data-editor-field="kebutuhan_khusus" style="margin-left: 3px; flex-grow: 1;"><?php echo $row->kebutuhan_khusus;?></div>
											</td>
										</tr>
										<tr id="row-spacer">
											<td colspan="3"></td>
										</tr>
										<tr id="row-dokumen-header">
											<td colspan="3" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;"><b>Dokumen Pendukung</b></td>
										</tr>
										<tr id="row-dokumen-kebutuhan-khusus">
											<td style="width:45%;"><b>Surat Keterangan Berkebutuhan Khusus</b></td>
											<td>:</td>
											<td style="width: 50%;">
												<?php if($upload_dokumen) { ?>
													<img id="dokumen-9" class="img-view-thumbnail" 
															src="<?php echo (empty($dokumen[9])) ? '' : $dokumen[9]['thumbnail_path']; ?>" 
															img-path="<?php echo (empty($dokumen[9])) ? '' : $dokumen[9]['web_path']; ?>" 
															img-id="<?php echo (empty($dokumen[9])) ? '' : $dokumen[9]['dokumen_id']; ?>" 
															img-title="Surat Keterangan Berkebutuhan Khusus"
															style="display:none; "/>  
													<button id="unggah-dokumen-9" class="img-view-button" data-editor-field="dokumen_9" data-editor-value="<?php echo (empty($dokumen[9])) ? '' : $dokumen[9]['dokumen_id']; ?>" >Unggah</button>
													<?php if(!empty($dokumen[9])) { ?>
													<div>
													<a id='label-dokumen-9' href="<?php echo $dokumen[9]['web_path']; ?>" target="_blank">
														<?php echo $dokumen[9]['filename'] ?>
													</a>
													</div>
													<?php } ?>
													<div id="msg-dokumen-9" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
												<?php } ?>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<?php if ($dikunci == 0 || $inklusi_verifikasi == 2) { ?>
						<div class="box-footer">
								<table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
									<tr id="inklusi-konfirmasi-row">
										<td colspan="1">
											<b>Apakah data kebutuhan khusus di atas sudah benar? </b>
											<select id="inklusi-konfirmasi" name="inklusi-konfirmasi">
											<option value="0" <?php if($inklusi_konfirmasi==0 || $inklusi_konfirmasi == 2) { echo "selected"; } ?>>BELUM Benar</option>
											<option value="1" <?php if($inklusi_konfirmasi==1) { echo "selected"; } ?>>SUDAH Benar</option>
											</select>
										</td>
									</tr>
									<tr id="inklusi-error-row" class="box-red" style="display: none;">
										<td colspan="1">
											<span style="color: red; " id="inklusi-error-msg"></span>
										</td>
									</tr>
								</table>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="row" data-editor-id="<?php echo $row->peserta_didik_id; ?>">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-alert"></i>
							<h3 class="box-title text-info"><b>Surat Pernyataan Kebenaran Dokumen</b></h3>
						</div>
						<?php if ($dikunci == 0 || empty($pernyataan_file) || $pernyataan_file == 0 || $pernyataan_verifikasi == 2 || $pernyataan_verifikasi == 0) { ?>
						<div class="box-body">
							<div class="row" id="row-dokumen-21-input">
								<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
									<button class="btn">Baru</button>
									<button class="btn">Hapus</button>
								</div> -->
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<table class="table table-striped" style="margin-bottom: 0px !important;">
										<tr>
											<td colspan="1">
												Silahkan unduh Surat Pernyataan Kebenaran Dokumen di sini: 
												<a href="<?php echo site_url('siswa/profil/suratpernyataan');?>" class="btn btn-primary" target="_blank">Unduh</a>
											</td>
										</tr>
										<tr id="spkd-unggah-row">
											<td colspan="1">
												Setelah dicetak dan ditandatangan, silahkan unggah kembali Surat Pernyataan tersebut di sini: 
												<button class="editable" data-editor-field="dokumen_21" data-editor-value="">Unggah</button>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<?php } ?>
						<div id="row-dokumen-21" class="box-footer" <?php if(empty($pernyataan_file)) { ?>style="display: none;"<?php } ?>>
								<table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
									<tr>
										<td><a id="dokumen-21" href="<?php echo $pernyataan_file; ?>" target="_blank"><b>Surat Pernyataan Kebenaran Dokumen</b></a> diunggah pada <b><span id="tanggal-dokumen-21"><?php echo $pernyataan_tanggal; ?></span></b></td>
									</tr>
									<tr id="dokumen-21-error-row" class="box-red" style="display: none;">
										<td colspan="1">
											<span style="color: red; " id="dokumen-21-error-msg"></span>
										</td>
									</tr>
								</table>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach;?>

			<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="box box-solid">
					<div class="box-header with-border">
						<i class="glyphicon glyphicon-print"></i>
						<h3 class="box-title text-info"><b>Cetak Surat Pernyataan Kesanggupan</b></h3>
					</div>
					<div class="box-body">
						<p>Calon peserta didik yang pada saat pendaftaran belum memiliki akte kelahiran, dapat diganti dengan surat pernyataan kesanggupan dari orang tua/wali untuk melengkapi paling lambat pada semester 2 (dua) di tahun ajaran yang sama.</p>
						<p>Setelah dicetak dan ditandatangani, silahkan unggah surat pernyataan tersebut sebagai ganti Akte Kelahiran di atas.</p>
					</div>
					<div class="box-footer">
						<a href="<?php echo base_url();?>index.php/siswa/pendaftaran/kesanggupankelengkapanakte" class="btn bg-orange" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak Surat Pernyataan</a>
					</div>
				</div>
			</div>
			</div>

			<?php if (count($dokumen_tambahan) > 0) { ?>
			<div class="row" data-editor-id="<?php echo $row->peserta_didik_id; ?>">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-file"></i>
							<h3 class="box-title text-info"><b>Dokumen Pendukung Tambahan</b></h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
										<?php foreach($dokumen_tambahan as $key => $fields) { 
											//nilai SKHUN
											if ($key == 3) continue;
										?>
										<tr>
											<td><span id="row-span-dokumen-<?php echo $key; ?>"><i class="glyphicon glyphicon-edit"></i> </span><b><?php echo $fields['nama']; ?></b></td>
											<td>:</td>
											<td style="width: 50%;">
												<?php if($upload_dokumen) { ?>
													<img id="dokumen-<?php echo $key; ?>" class="img-view-thumbnail" 
															src="<?php echo $fields['thumbnail_path']; ?>" 
															img-path="<?php echo $fields['web_path']; ?>" 
															img-title="<?php echo $fields['nama']; ?>"
															img-id="<?php echo $fields['dokumen_id']; ?>" 
															style="display:none; "/>  
													<button id="unggah-dokumen-<?php echo $key; ?>" class="img-view-button" data-editor-field="dokumen_<?php echo $key; ?>" data-editor-value="<?php echo $fields['dokumen_id']; ?>" >Unggah</button>
													<div id="msg-dokumen-<?php echo $key; ?>" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
												<?php } ?>
											</td>
										</tr>
										<?php } ?>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <i class="glyphicon glyphicon-search text-info"></i>
                                <h3 class="box-title text-info"><b>Berkas Fisik</b></h3>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?php if ($berkas_fisik != null) { ?>
                                        <table id="tberkas" class="table table-striped" style="margin-bottom:0px !important;">
                                            <?php 
                                                foreach($berkas_fisik as $berkasfisik):
                                                //clean up di sini karena data kotor untuk ppdb2019
                                                if ($row->punya_prestasi==0 && $berkasfisik['daftar_kelengkapan_id']==8) {
                                                    continue;
                                                }

                                                if ($row->punya_nilai_un==0 && $berkasfisik['daftar_kelengkapan_id']==3) {
                                                    continue;
                                                }

                                                if ($row->punya_kip==0 && $berkasfisik['daftar_kelengkapan_id']==16) {
                                                    continue;
                                                }

                                                if ($row->masuk_bdt==0 && $berkasfisik['daftar_kelengkapan_id']==20) {
                                                    continue;
                                                }

                                                if ($row->kebutuhan_khusus=='Tidak ada' && $berkasfisik['daftar_kelengkapan_id']==9) {
                                                    continue;
                                                }
                                            ?>
                                            <input type="hidden" id="orig_berkas_<?php echo $berkasfisik['dokumen_id'];?>" name="orig_berkas_<?php echo $berkasfisik['dokumen_id'];?>" value="<?php echo $berkasfisik['berkas_fisik']; ?>">
                                            <tr >
                                                <td>
                                                    <div style="display: block; margin-bottom: 15px;"><b><?php echo  $berkasfisik['nama']; ?></b></div>
                                                </td>
                                                <td style="width: 50%">
                                                    <?php if($berkasfisik['berkas_fisik']==1) { ?>
                                                        <div id="catatan_berkas_<?php echo $berkasfisik['dokumen_id'];?>"><?php echo "Diterima oleh " .$berkasfisik['penerima_berkas']. " di " .$berkasfisik['sekolah']. " tanggal " .$berkasfisik['tanggal_berkas']; ?></div>
                                                    <?php } else { ?>
                                                        <div id="catatan_berkas_<?php echo $berkasfisik['dokumen_id'];?>">Belum diserahkan ke sekolah tujuan</div>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </table>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer">
                            </div>
                        </div>
                </div>
            </div>

			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					
						<div class="box box-solid">
							<div class="box-header with-border">
								<i class="glyphicon glyphicon-th-list"></i>
								<h3 class="box-title text-info"><b>Riwayat Verifikasi</b></h3>
							</div>
							<div class="box-body">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<table id="triwayat" class="display" style="width: 100%; margin-bottom: 20px;">
											<thead>
												<tr>
													<td class="text-center" data-priority="1">Tanggal</td>
													<td class="text-center" data-priority="2">Oleh</td>
													<td class="text-center" data-priority="3">Status</td>
													<td class="text-center" data-priority="4">Catatan Kekurangan</td>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
						</div>
				</div>
			</div>

		<!-- </div>
	</div>
</div> -->

<?php 
	if ($profil_konfirmasi != 1 || $lokasi_konfirmasi != 1 || $nilai_konfirmasi != 1 || $prestasi_konfirmasi != 1 || $afirmasi_konfirmasi != 1 || $inklusi_konfirmasi != 1 || empty($nomor_handphone) || empty($pernyataan_file) 
	|| (!empty($dokumen[5]) && $dokumen[5]['verifikasi'] == 2) 
	|| (!empty($dokumen[6]) && $dokumen[6]['verifikasi'] == 2) 
	|| (!empty($dokumen[19]) && $dokumen[19]['verifikasi'] == 2)
	|| (!empty($dokumen[2]) && $dokumen[2]['verifikasi'] == 2) 
	|| (!empty($dokumen[3]) && $dokumen[3]['verifikasi'] == 2) 
	|| (!empty($dokumen[16]) && $dokumen[16]['verifikasi'] == 2) 
	|| (!empty($dokumen[20]) && $dokumen[20]['verifikasi'] == 2) 
	|| (!empty($dokumen[9]) && $dokumen[9]['verifikasi'] == 2) 
	|| (!empty($dokumen[8]) && $dokumen[8]['verifikasi'] == 2)
	|| ($verifikasi_dokumen_tambahan == 2)) {
?>
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-red">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><i class="glyphicon glyphicon-info-sign"></i> Perhatian!</h4>
			</div>
			<div class="modal-body">
				<p>Pastikan data profil <b>Calon Siswa</b> telah sesuai sebelum melakukan pendaftaran PPDB.</p>
				<p>Mohon konfirmasi data berikut </p>
				<ol>
					<?php if ($profil_konfirmasi != 1 || $profil_verifikasi == 2) { echo "<li><b>Profil Siswa</b></li>"; } ?>
					<?php if ($lokasi_konfirmasi != 1 || $lokasi_verifikasi == 2) { echo "<li><b>Lokasi Tempat Tinggal</b></li>"; } ?>
					<?php if ($nilai_konfirmasi != 1 || $nilai_verifikasi == 2) { echo "<li><b>Nilai Ujian Nasional / Nilai Kelulusan</b></li>"; } ?>
					<?php if ($prestasi_konfirmasi != 1 || $prestasi_verifikasi == 2) { echo "<li><b>Prestasi</b></li>"; } ?>
					<?php if ($afirmasi_konfirmasi != 1 || $afirmasi_verifikasi == 2) { echo "<li><b>Data Afirmasi</b></li>"; } ?>
					<?php if ($inklusi_konfirmasi != 1 || $inklusi_verifikasi == 2) { echo "<li><b>Data Inklusi / Kebutuhan Khusus</b></li>"; } ?>
					<?php if (empty($nomor_handphone)) { echo "<li><b>Nomor Handphone Aktif</b></li>"; } ?>
					<!-- selalu muncul terkait surat pernyataan dokument selama masih ada data yang belum benar dan surat pernyataan belum diverifikasi oleh panitia SMP -->
					<?php if (empty($pernyataan_file) || $pernyataan_verifikasi != 1) { echo "<li><b>Surat Pernyataan Kebenaran Dokumen</b></li>"; } ?>
					<?php if (!empty($dokumen[5]) && $dokumen[5]['verifikasi'] == 2) { echo "<li><b>Dokumen Akte Kelahiran</b></li>"; } ?>
					<?php if (!empty($dokumen[6]) && $dokumen[6]['verifikasi'] == 2) { echo "<li><b>Dokumen Kartu Keluarga</b></li>"; } ?>
					<?php if (!empty($dokumen[19]) && $dokumen[19]['verifikasi'] == 2) { echo "<li><b>Dokumen Surat Keterangan Domisili</b></li>"; } ?>
					<?php if (!empty($dokumen[2]) && $dokumen[2]['verifikasi'] == 2) { echo "<li><b>Dokumen Ijazah / Surat Keterangan Lulus</b></li>"; } ?>
					<?php if (!empty($dokumen[3]) && $dokumen[3]['verifikasi'] == 2) { echo "<li><b>Dokumen SKHUN</b></li>"; } ?>
					<?php if (!empty($dokumen[8]) && $dokumen[8]['verifikasi'] == 2) { echo "<li><b>Surat Bukti Prestasi yang Dilegalisir</b></li>"; } ?>
					<?php if (!empty($dokumen[16]) && $dokumen[16]['verifikasi'] == 2) { echo "<li><b>Dokumen Kartu Indonesia Pintar</b></li>"; } ?>
					<?php if (!empty($dokumen[20]) && $dokumen[20]['verifikasi'] == 2) { echo "<li><b>Surat Keterangan Masuk BDT</b></li>"; } ?>
					<?php if (!empty($dokumen[9]) && $dokumen[9]['verifikasi'] == 2) { echo "<li><b>Surat Keterangan Berkebutuhan Khusus</b></li>"; } ?>
					<?php if ($verifikasi_dokumen_tambahan == 2) { echo "<li><b>Dokumen Pendukung Tambahan</b></li>"; } ?>
				</ol>
				<p>sudah benar dan sesuai dengan dokumen pendukung yang diunggah.</b></p><br>
				<p>Terima Kasih.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(window).on('load',function(){
		$('#myModal').modal('show');
	});
</script>

<?php } ?>

<style>
	.icon-flipped {
		transform: scaleX(-1);
		-moz-transform: scaleX(-1);
		-webkit-transform: scaleX(-1);
		-ms-transform: scaleX(-1);
	}
</style>

<div id="img-view-modal" class="modal fade" role="dialog">
	<div class="container">
	<div class="modal-dialog img-view-dialog">
		<div class="modal-content">
			<div class="modal-header bg-red">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><span id="img-view-title">Akte Kelahiran</span></h4>
			</div>
			<div class="modal-body">
				<div id='img-rotate-buttons' style="display: inline; position: absolute; left: 15px; top: 30px;">
					<button type="button" class="btn btn-sm" onclick="image_rotate(90); return false;"><i class="glyphicon glyphicon-repeat icon-flipped"></i></button>
					<button type="button" class="btn btn-sm" onclick="image_rotate(-90); return false;"><i class="glyphicon glyphicon-repeat"></i></button>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center justify-content-center" id="loading" style="position: absolute; margin-top: 24px; margin-left: -12px; display: none;">
					<div class="loader" role="status">
						<span class="sr-only">Loading...</span>
					</div>
				</div>
				<div class="row">
					<img id="img-view-contain" class="img-view-contain" id="thumb" src="" style="display: none;">
					<div id='pdf-view-contain' style="display: none; font-size: large; margin-left: 16px;">
						<a id='pdf-view-link' href="" target="_blank">
						</a>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
	</div>
</div>

	<script src="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url();?>assets/datatables/Select-1.3.1/js/dataTables.select.min.js"></script>
	<script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js"></script>
	<!-- <script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/buttons.flash.min.js"></script>
	<script src="<?php echo base_url();?>assets/datatables/JSZip-2.5.0/jszip.min.js"></script>
	<script src="<?php echo base_url();?>assets/datatables/pdfmake-0.1.36/pdfmake.min.js"></script>
	<script src="<?php echo base_url();?>assets/datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
	<script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/buttons.print.min.js"></script>
	<script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/buttons.html5.min.js"></script> -->
	<script src="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>
	<script src="<?php echo base_url();?>assets/datatables/Editor-2.0.6/js/dataTables.editor.min.js"></script>

<script src="<?php echo base_url();?>assets/leaflet/leaflet.js"></script>
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>
<script>
	<?php foreach($profilsiswa->getResult() as $row):
	?>

	//Peta
	var map;
	<?php if (!empty($row->lintang) && !empty($row->bujur)) { ?>
		map = L.map('peta',{zoomControl:false}).setView([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>],16);
		L.marker([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>]).addTo(map).bindPopup("<?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?>").openPopup();
	<?php } else { ?>
		map = L.map('peta',{zoomControl:false}).setView([<?php echo $lintang_aktif;?>,<?php echo $bujur_aktif;?>],10);
	<?php } ?>

	L.tileLayer(
		'<?php echo $servis_peta_aktif;?>',{maxZoom: 18,attribution: 'PPDB <?php echo $wilayah_aktif;?>',id: 'mapbox.streets'}
	).addTo(map);

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

<script text="javascript">
	
	function send_profil_konfirmasi() {
		let val = $("#profil-konfirmasi").val();
		if (val == 1) {
			//akte kelahiran
			let img_src = $('#dokumen-5').attr("src");
			if (typeof img_src === "undefined" || img_src == null || img_src == "") {
				$('#profil-error-msg').html("Anda belum mengunggah Akte Kelahirann");
				$('#profil-error-row').show();
				//karena upload dok sering bermasalah, upload dok pendukung dibuat optional
				//return 0;
			}

			//akte kelahiran
			img_src = $('#dokumen-6').attr("src");
			if (typeof img_src === "undefined" || img_src == null || img_src == "") {
				$('#profil-error-msg').html("Anda belum mengunggah Kartu Keluarga");
				$('#profil-error-row').show();
				//karena upload dok sering bermasalah, upload dok pendukung dibuat optional
				//return 0;
			}

			let flag = confirm("Konfirmasi data identitas sudah benar?");
			if (!flag) {
				return;
			}
		}

		//Send to server
		let data = {'action':'edit','data[null][konfirmasi_profil]':val};
		$.ajax({
			type: "POST",
			url : "<?php echo site_url('siswa/profil/json')?>",
			data: data,
			success: function(msg){
				$('#profil-error-row').hide();
				$("#profil-konfirmasi-row").removeClass("box-red");
			}
		});


		return 1;
	}

	function send_lokasi_konfirmasi() {
		let bujur = $("#row-bujur-value").html().trim();
		let lintang = $("#row-lintang-value").html().trim();

		let val = $("#lokasi-konfirmasi").val();
		if (val == 1) {
			if (bujur == "-" || bujur == "") {
				$('#lokasi-error-msg').html("Anda belum mengisi lokasi rumah (bujur)");
				$('#lokasi-error-row').show();
				return 0;
			}

			if (lintang == "-" || lintang == "") {
				$('#lokasi-error-msg').html("Anda belum mengisi lokasi rumah (lintang)");
				$('#lokasi-error-row').show();
				return 0;
			}

			let flag = confirm("Konfirmasi data lokasi tempat tinggal sudah benar?");
			if (!flag) {
				return 0;
			}
		}

		//Send to server
		var data = {'action':'edit','data[null][konfirmasi_lokasi]':val};
		$.ajax({
			type: "POST",
			url : "<?php echo site_url('siswa/profil/json')?>",
			data: data,
			success: function(msg){
				$('#lokasi-error-row').hide();
				$("#lokasi-konfirmasi-row").removeClass("box-red");
			}
		});

		
		return 1;
	}

	function send_nilai_konfirmasi() {
		let nilai_un = $("#nilai-un").val();

		let nilai_semester = $("#nilai_semester").html().trim();
		let nilai_lulus = $("#nilai_lulus").html().trim();

		<?php if(!empty($nilai_per_semester)) { ?>
		let kelas4_sem1 = $("#kelas4_sem1").html().trim();
		let kelas4_sem2 = $("#kelas4_sem2").html().trim();
		let kelas5_sem1 = $("#kelas5_sem1").html().trim();
		let kelas5_sem2 = $("#kelas5_sem2").html().trim();
		let kelas6_sem1 = $("#kelas6_sem1").html().trim();
		<?php } ?>

		let val = $("#nilai-konfirmasi").val();
		if (val == 1) {
			<?php if(!empty($nilai_per_semester)) { ?>
			if (kelas4_sem1 == "-" || kelas4_sem1 == "") {
				$('#nilai-error-msg').html("Anda belum mengisi nilai rapor kelas 4 semester 1");
				$('#nilai-error-row').show();
				return 0;
			}

			if (kelas4_sem2 == "-" || kelas4_sem2 == "") {
				$('#nilai-error-msg').html("Anda belum mengisi nilai rapor kelas 4 semester 2");
				$('#nilai-error-row').show();
				return 0;
			}

			if (kelas5_sem1 == "-" || kelas5_sem1 == "") {
				$('#nilai-error-msg').html("Anda belum mengisi nilai rapor kelas 5 semester 1");
				$('#nilai-error-row').show();
				return 0;
			}

			if (kelas5_sem2 == "-" || kelas5_sem2 == "") {
				$('#nilai-error-msg').html("Anda belum mengisi nilai rapor kelas 5 semester 2");
				$('#nilai-error-row').show();
				return 0;
			}

			if (kelas6_sem1 == "-" || kelas6_sem1 == "") {
				$('#nilai-error-msg').html("Anda belum mengisi nilai rapor kelas 6 semester 1");
				$('#nilai-error-row').show();
				return 0;
			}
			<?php } ?>
			
			if (nilai_semester == "-" || nilai_semester == "") {
				$('#nilai-error-msg').html("Anda belum mengisi nilai rata-rata rapor");
				$('#nilai-error-row').show();
				return 0;
			}

			if (nilai_lulus == "-" || nilai_lulus == "") {
				$('#nilai-error-msg').html("Anda belum mengisi nilai rata-rata ujian sekolah");
				$('#nilai-error-row').show();
				return 0;
			}

			//SKL
			img_src = $('#dokumen-2').attr("src");
			if (typeof img_src === "undefined" || img_src == null || img_src == "") {
				$('#nilai-error-msg').html("Anda belum mengunggah Ijazah / Surat Keterangan Lulus");
				$('#nilai-error-row').show();
				//karena upload dok sering bermasalah, upload dok pendukung dibuat optional
				//return 0;
			}

			//SKHUN
			if (nilai_un == 1) {
				img_src = $('#dokumen-3').attr("src");
				if (typeof img_src === "undefined" || img_src == null || img_src == "") {
					$('#nilai-error-msg').html("Anda belum mengunggah Surat Keterangan Hasil Ujian Nasional");
					$('#nilai-error-row').show();
					//karena upload dok sering bermasalah, upload dok pendukung dibuat optional
					//return 0;
				}
			}

			let flag = confirm("Konfirmasi data nilai kelulusan dan nilai UN sudah benar?");
			if (!flag) {
				return 0;
			}
		}


		//Send to server
		var data = {'action':'edit', 'data[null][konfirmasi_nilai]':val, 'data[null][punya_nilai_un]':nilai_un};
		$.ajax({
			type: "POST",
			url : "<?php echo site_url('siswa/profil/json')?>",
			data: data,
			success: function(msg){
				$('#nilai-error-row').hide();
				$("#nilai-konfirmasi-row").removeClass("box-red");		
			}
		});

		return 1;
	}

	function send_prestasi_konfirmasi() {
		let prestasi = $("#prestasi-akademis").val();

		let val = $("#prestasi-konfirmasi").val();
		if (val == 1) {
			let flag = confirm("Konfirmasi data prestasi sudah benar?");
			if (!flag) {
				return;
			}
		}

		//Send to server
		var data = {'action':'edit', 'data[null][konfirmasi_prestasi]':val, 'data[null][punya_prestasi]':prestasi};
		$.ajax({
			type: "POST",
			url : "<?php echo site_url('siswa/profil/json')?>",
			data: data,
			success: function(msg){
				dtprestasi.ajax.reload();
				// if (val == 0) {
				// 	//clear table prestasi
				// }
				//$('#prestasi-error-row').hide();
				$("#prestasi-konfirmasi-row").removeClass("box-red");		
			}
		});


		return 1;
	}

	function send_afirmasi_konfirmasi() {
		let punya_kip = $("#kip").val();
		let masuk_bdt = $("#bdt").val();

		let val = $("#afirmasi-konfirmasi").val();
		if (val == 1) {
			//KIP
			if (punya_kip == 1) {
				let img_src = $('#dokumen-16').attr("src");
				if (typeof img_src === "undefined" || img_src == null || img_src == "") {
					$('#afirmasi-error-msg').html("Anda belum mengunggah foto Kartu Indonesia Pintar");
					$('#afirmasi-error-row').show();
					//karena upload dok sering bermasalah, upload dok pendukung dibuat optional
					//return 0;
				}
			}

			//BDT
			if (masuk_bdt == 1) {
				img_src = $('#dokumen-20').attr("src");
				if (typeof img_src === "undefined" || img_src == null || img_src == "") {
					$('#afirmasi-error-msg').html("Anda belum mengunggah Surat Keterangan Masuk Basis Data Terpadu");
					$('#afirmasi-error-row').show();
					//karena upload dok sering bermasalah, upload dok pendukung dibuat optional
					//return 0;
				}
			}

			let flag = confirm("Konfirmasi data afirmasi sudah benar?");
			if (!flag) {
				return;
			}
		}

		//Send to server
		var data = {'action':'edit', 'data[null][konfirmasi_afirmasi]':val, 'data[null][punya_kip]':punya_kip, 'data[null][masuk_bdt]':masuk_bdt};
		$.ajax({
			type: "POST",
			url : "<?php echo site_url('siswa/profil/json')?>",
			data: data,
			success: function(msg){
				$('#afirmasi-error-row').hide();
				$("#afirmasi-konfirmasi-row").removeClass("box-red");		
			}
		});

		return 1;
	}

	function send_inklusi_konfirmasi() {
		let inklusi = $("#inklusi").val();
		let kebutuhan_khusus = $("#kebutuhan_khusus").html();

		let val = $("#inklusi-konfirmasi").val();
		if (val == 1) {
			if (inklusi == 1 && kebutuhan_khusus != 'Tidak ada') {
				let img_src = $('#dokumen-9').attr("src");
				if (typeof img_src === "undefined" || img_src == null || img_src == "") {
					$('#inklusi-error-msg').html("Anda belum mengunggah Surat Keterangan Berkebutuhan Khusus");
					$('#inklusi-error-row').show();
					//karena upload dok sering bermasalah, upload dok pendukung dibuat optional
					//return 0;
				}
			}

			let flag = confirm("Konfirmasi data inklusi kebutuhan khusus sudah benar?");
			if (!flag) {
				return;
			}
		}

		//Send to server
		var data = {'action':'edit', 'data[null][konfirmasi_inklusi]':val, 'data[null][kebutuhan_khusus]':kebutuhan_khusus};
		$.ajax({
			type: "POST",
			url : "<?php echo site_url('siswa/profil/json')?>",
			data: data,
			success: function(msg) {
				$('#inklusi-error-row').hide();
				$("#inklusi-konfirmasi-row").removeClass("box-red");		
			}
		});

		return 1;
	}

	function send_nomor_kontak() {
		let nomor_kontak = $("#nomor_kontak").val().trim();

		if (nomor_kontak == "") {
			return 0;
		}

		//Send to server
		var data = {'action':'edit','data[null][nomor_kontak]':nomor_kontak};
		$.ajax({
			type: "POST",
			url : "<?php echo site_url('siswa/profil/json')?>",
			data: data,
			success: function(msg){
				$("#kontak-ubah-row").removeClass("box-red");		
				alert("Nomor kontak berhasil diubah")
			}
		});

		return 1;
	}

	function refresh_view_profil_konfirmasi(val) {
		if (val == 1) {
			//lock for editing
			$("#row-span-dokumen-akte").hide();
			$("#row-span-dokumen-kk").hide();
			$("#unggah-dokumen-5").hide();
			$("#unggah-dokumen-6").hide();
			$("#unggah-dokumen-5").removeClass("editable");
			$("#unggah-dokumen-6").removeClass("editable");
		}
		else {
			//open for editing
			$("#row-span-dokumen-akte").show();
			$("#row-span-dokumen-kk").show();
			$("#unggah-dokumen-5").show();
			$("#unggah-dokumen-6").show();
			$("#unggah-dokumen-5").addClass("editable");
			$("#unggah-dokumen-6").addClass("editable");
		}
	}

	function refresh_view_lokasi_konfirmasi(val) {
		if (val == 1) {
			//lock for editing
			$("#row-span-lintang").hide();
			$("#row-span-bujur").hide();
			$("#row-span-dokumen-domisili").hide();
			$("#unggah-dokumen-19").hide();
			$("#unggah-dokumen-26").hide();
			$("#row-lintang-value").removeClass("editable");
			$("#row-bujur-value").removeClass("editable");
			$("#unggah-dokumen-19").removeClass("editable");
			$("#unggah-dokumen-26").removeClass("editable");
		}
		else {
			//edit for editing
			$("#row-span-lintang").show();
			$("#row-span-bujur").show();
			$("#row-span-dokumen-domisili").show();
			$("#unggah-dokumen-19").show();
			$("#unggah-dokumen-26").show();
			$("#row-lintang-value").addClass("editable");
			$("#row-bujur-value").addClass("editable");
			$("#unggah-dokumen-19").addClass("editable");
			$("#unggah-dokumen-26").addClass("editable");
		}
	}

	function refresh_view_nilai_konfirmasi(val) {
		if (val == 1) {
			//lock for editing
			$("#row-span-rapor").hide();
			$("#row-span-skl").hide();
			$("#row-span-un-bin").hide();
			$("#row-span-un-mat").hide();
			$("#row-span-un-ipa").hide();
			$("#row-span-dokumen-skl").hide();
			$("#row-span-dokumen-skhun").hide();
			$("#unggah-dokumen-2").hide();
			$("#unggah-dokumen-3").hide();
			$("#nilai_semester").removeClass("editable");
			$("#nilai_lulus").removeClass("editable");
			$("#nilai_bin").removeClass("editable");
			$("#nilai_mat").removeClass("editable");
			$("#nilai_ipa").removeClass("editable");
			$("#unggah-dokumen-2").removeClass("editable");
			$("#unggah-dokumen-3").removeClass("editable");
			$("#nilai-un").prop("disabled", true);	
		}
		else {
			//edit for editing
			$("#row-span-rapor").show();
			$("#row-span-skl").show();
			$("#row-span-un-bin").show();
			$("#row-span-un-mat").show();
			$("#row-span-un-ipa").show();
			$("#row-span-dokumen-skl").show();
			$("#row-span-dokumen-skhun").show();
			$("#unggah-dokumen-2").show();
			$("#unggah-dokumen-3").show();
			$("#nilai_semester").addClass("editable");
			$("#nilai_lulus").addClass("editable");
			$("#nilai_bin").addClass("editable");
			$("#nilai_mat").addClass("editable");
			$("#nilai_ipa").addClass("editable");
			$("#unggah-dokumen-2").addClass("editable");
			$("#unggah-dokumen-3").addClass("editable");
			$("#nilai-un").prop("disabled", false);	
		}
	}

	function refresh_view_nilai_un() {
		val = $("#nilai-un").val();
		if (val == 1) {
			$("#row-un-bin").show();
			$("#row-un-mat").show();
			$("#row-un-ipa").show();
			$("#row-dokumen-un").show();
		}
		else {
			$("#row-un-bin").hide();
			$("#row-un-mat").hide();
			$("#row-un-ipa").hide();
			$("#row-dokumen-un").hide();			
		}
	}

	function refresh_view_prestasi_konfirmasi(val) {
		let buttons = dtprestasi.buttons('.dt-button');
		let nodes = dtprestasi.buttons('.dt-button').nodes();
		if (val == 1) {
			//lock for editing
			$("#prestasi-akademis").prop("disabled", true);	
			dtprestasi.buttons('.dt-button').nodes().addClass('d-none');
		}
		else {
			//edit for editing
			$("#prestasi-akademis").prop("disabled", false);	
			dtprestasi.buttons('.dt-button').nodes().removeClass('d-none');
		}
	}

	function refresh_view_prestasi() {
		val = $("#prestasi-akademis").val();
		if (val == 1) {
			$("#tbl-prestasi-kontainer").show();
		}
		else {
			$("#tbl-prestasi-kontainer").hide();
		}
	}

	function refresh_view_afirmasi_konfirmasi(val) {
		if (val == 1) {
			//lock for editing
			$("#row-span-kip").hide();
			$("#nomor_kip").removeClass("editable");
			$("#row-span-bdt").hide();
			$("#nomor_bdt").removeClass("editable");
			$("#row-span-dokumen-kip").hide();
			$("#row-span-dokumen-bdt").hide();
			$("#unggah-dokumen-16").hide();
			$("#unggah-dokumen-20").hide();
			$("#unggah-dokumen-16").removeClass("editable");
			$("#unggah-dokumen-20").removeClass("editable");
			$("#kip").prop("disabled", true);	
			$("#bdt").prop("disabled", true);	
		}
		else {
			//open for editing
			$("#row-span-kip").show();
			$("#nomor_kip").addClass("editable");
			$("#row-span-bdt").show();
			$("#nomor_bdt").addClass("editable");
			$("#row-span-dokumen-kip").show();
			$("#row-span-dokumen-bdt").show();
			$("#unggah-dokumen-16").show();
			$("#unggah-dokumen-20").show();
			$("#unggah-dokumen-16").addClass("editable");
			$("#unggah-dokumen-20").addClass("editable");
			$("#kip").prop("disabled", false);	
			$("#bdt").prop("disabled", false);	
		}
	}

	function refresh_view_kip() {
		val = $("#kip").val();
		if (val == 1) {
			$("#row-kip").show();
			$("#row-dokumen-kip").show();
			if ($("#row-dokumen-kip").is(':visible') || $("#row-dokumen-bdt").is(':visible')) {
				$("#row-dokumen-afirmasi").show();
			} else {
				$("#row-dokumen-afirmasi").hide();
			}
		}
		else {
			$("#row-kip").hide();
			$("#row-dokumen-kip").hide();
			if (!$("#row-dokumen-kip").is(':visible') && !$("#row-dokumen-bdt").is(':visible')) {
				$("#row-dokumen-afirmasi").hide();
			} else {
				$("#row-dokumen-afirmasi").show();
			}
		}
	}

	function refresh_view_bdt() {
		val = $("#bdt").val();
		if (val == 1) {
			$("#row-bdt").show();
			$("#row-dokumen-bdt").show();
			if ($("#row-dokumen-kip").is(':visible') || $("#row-dokumen-bdt").is(':visible')) {
				$("#row-dokumen-afirmasi").show();
			} else {
				$("#row-dokumen-afirmasi").hide();
			}
		}
		else {
			$("#row-bdt").hide();
			$("#row-dokumen-bdt").hide();
			if (!$("#row-dokumen-kip").is(':visible') && !$("#row-dokumen-bdt").is(':visible')) {
				$("#row-dokumen-afirmasi").hide();
			} else {
				$("#row-dokumen-afirmasi").show();
			}
		}
	}

	function refresh_view_inklusi_konfirmasi(val) {
		if (val == 1) {
			//lock for editing
			$("#row-span-inklusi").hide();
			$("#kebutuhan_khusus").removeClass("editable");
			$("#row-span-dokumen-kebutuhan-khusus").hide();
			$("#unggah-dokumen-9").hide();
			$("#unggah-dokumen-9").removeClass("editable");
			$("#inklusi").prop("disabled", true);	
		}
		else {
			//lock for editing
			$("#row-span-inklusi").show();
			$("#kebutuhan_khusus").addClass("editable");
			$("#row-span-dokumen-kebutuhan-khusus").show();
			$("#unggah-dokumen-9").show();
			$("#unggah-dokumen-9").addClass("editable");
			$("#inklusi").prop("disabled", false);	
		}
	}

	function refresh_view_inklusi() {
		val = $("#inklusi").val();
		if (val == 1) {
			$("#row-kebutuhan-khusus").show();
			//$("#row-spacer").show();
			$("#row-dokumen-header").show();
			$("#row-dokumen-kebutuhan-khusus").show();
		}
		else {
			$("#row-kebutuhan-khusus").hide();
			//$("#row-spacer").hide();
			$("#row-dokumen-header").hide();
			$("#row-dokumen-kebutuhan-khusus").hide();
		}
	}

	function show_img_view(title, src, img_id) {
		$("#img-view-title").html(title);
		$("#img-view-contain").attr("src", src);
		$("#img-view-contain").attr("img-id", img_id);
		$("#img-view-contain").show();	
		$("#pdf-view-contain").hide();	

		$("#img-rotate-buttons").show();
		$('#img-view-modal').modal('show');
	}

	function show_pdf_view(title, src, filename) {
		$("#img-view-title").html(title);

		$("#pdf-view-link").attr("href", src);

		if (title != filename) {
			$("#pdf-view-link").html(filename);
		} else {
			$("#pdf-view-link").html('Unduh Di Sini');
		}
		$("#pdf-view-contain").show();	
		$("#img-view-contain").hide();	

		$("#img-rotate-buttons").hide();

		$('#img-view-modal').modal('show');
	}

	function image_rotate(degree) {
		let dokumen_id = $("#img-view-contain").attr("img-id");
		if (dokumen_id == null || dokumen_id == "") {
			return;
		}

		$("#loading").show();

		$.post("<?php echo site_url('siswa/profil/json'); ?>",
			{
				action: "rotate",
				dokumen_id: dokumen_id,
				degree: degree
			},
			function(data) {
				do {
					if (typeof data.error !== "undefined" && data.error != null && data.error != "") {
						//TODO: error
						alert(data.error);
						break;
					}

					if (typeof data.data === "undefined" || data.data == null || data.data == "") {
						break;
					}

					for (var key of Object.keys(data.data)) {
						let dokumen = data.data[key];

						var kelengkapan_id = dokumen.daftar_kelengkapan_id;

						$("#img-view-contain").attr("src", dokumen.thumbnail_path);

						if (kelengkapan_id == 8) {
							//update img dokumen prestasi
							//TODO
						}
						else {
							//update img dokumen pendukung
							var img = $("#dokumen-" + kelengkapan_id);
							img.attr("src", dokumen.thumbnail_path);
							img.attr("img-path", dokumen.web_path);
							// img.show();

							// //update button
							// var btn = $("#unggah-dokumen-" + kelengkapan_id);
							// btn.attr("data-editor-value", dokumen.dokumen_id);
							// btn.html("Ubah");
						}

					}
				}
				while(false);

				$("#loading").hide();
			},
			"json")
		.done(function(msg) {
			//not necessary. already handled.
		})
		.fail(function(xhr, textStatus, error) {
			//console.log(xhr.statusText);
			//console.log(xhr.responseText);
			//console.log(textStatus);
			//console.log(error);

			if (typeof xhr.responseText === "undefined" ) {
				alert("Tidak ada koneksi ke server.");
			}
			else if (error != null && error != ""){
				alert("Tidak berhasil menyimpan data: " + error);
			}
			else {
				alert(xhr.responseText);
			}
		})
		.always(function() {
			$("#loading").hide();
		});
	}

</script>

<script text="javascript">

	var editor;
	var editprestasi;
	var dtprestasi;
	var dtriwayat;
	var peserta_didik_id = "<?php echo $peserta_didik_id; ?>";
	var files = <?php echo json_encode($files); ?>

	var flag = 0;
	var preview_dokumen_id = 0;
	$(document).ready(function() {

		$(".img-view-thumbnail").on('click', function(e) {
			let img_title = $(this).attr('img-title');
			let img_path = $(this).attr('img-path');
			let img_id = $(this).attr('img-id');
			if (typeof img_path === "undefined" || img_path == "") {
				return false;
			}

			const lastDot = img_path.lastIndexOf('.');
			const ext = img_path.substring(lastDot + 1);

			if (ext=='pdf') {
				show_pdf_view(img_title, img_path, img_title);
			}
			else {
				show_img_view(img_title, img_path, img_id);
			}
			
		});

		$('#tbl-prestasi').on('click', 'tbody img.img-view-thumbnail', function (e) {
			let img_title = $(this).attr('img-title');
			let img_path = $(this).attr('img-path');
			let img_id = $(this).attr('img-id');
			if (typeof img_path === "undefined" || img_path == "") {
				return false;
			}

			e.stopPropagation();
			
			const lastDot = img_path.lastIndexOf('.');
			const ext = img_path.substring(lastDot + 1);

			if (ext=='pdf') {
				show_pdf_view(img_title, img_path, img_title);
			}
			else {
				show_img_view(img_title, img_path, img_id);
			}
		});

		//only show img thumbnail when src is set
        $('.img-view-thumbnail[src=""]').hide();
        $('.img-view-thumbnail:not([src=""])').show();

        $('.img-view-button[data-editor-value=""]').html("Unggah");
        $('.img-view-button:not([data-editor-value=""])').html("Ubah");

		//event view
		$('#profil-konfirmasi').on('change', function() {
			if (flag == 1) return;

			flag = 1;

			val = $("#profil-konfirmasi").val();
			if (send_profil_konfirmasi()) {
				refresh_view_profil_konfirmasi(val);
			} else {
				if (val == 1) {
					$("#profil-konfirmasi").val(0);
				} else {
					$("#profil-konfirmasi").val(1);
				}
			}

			flag = 0;
		});

		$('#lokasi-konfirmasi').on('change', function() {
			if (flag == 1) return;

			flag = 1;

			val = $("#lokasi-konfirmasi").val();
			if (send_lokasi_konfirmasi()) {
				refresh_view_lokasi_konfirmasi(val);
			} else {
				if (val == 1) {
					$("#lokasi-konfirmasi").val(0);
				} else {
					$("#lokasi-konfirmasi").val(1);
				}
			}

			flag = 0;
		});

		$('#nilai-konfirmasi').on('change', function() {
			if (flag == 1) return;

			flag = 1;

			val = $("#nilai-konfirmasi").val();
			if (send_nilai_konfirmasi()) {
				refresh_view_nilai_konfirmasi(val);
			} else {
				if (val == 1) {
					$("#nilai-konfirmasi").val(0);
				} else {
					$("#nilai-konfirmasi").val(1);
				}
			}

			flag = 0;
		});

		$('#nilai-un').on('change', refresh_view_nilai_un);

		$('#prestasi-konfirmasi').on('change', function() {
			if (flag == 1) return;

			flag = 1;

			val = $("#prestasi-konfirmasi").val();
			if (send_prestasi_konfirmasi()) {
				refresh_view_prestasi_konfirmasi(val);
			} else {
				if (val == 1) {
					$("#prestasi-konfirmasi").val(0);
				} else {
					$("#prestasi-konfirmasi").val(1);
				}
			}

			flag = 0;
		});

		$('#prestasi-akademis').on('change', refresh_view_prestasi);

		$('#afirmasi-konfirmasi').on('change', function() {
			if (flag == 1) return;

			flag = 1;

			val = $("#afirmasi-konfirmasi").val();
			if (send_afirmasi_konfirmasi()) {
				refresh_view_afirmasi_konfirmasi(val);
			} else {
				if (val == 1) {
					$("#afirmasi-konfirmasi").val(0);
				} else {
					$("#afirmasi-konfirmasi").val(1);
				}
			}

			flag = 0;
		});

		$('#kip').on('change', refresh_view_kip);
		$('#bdt').on('change', refresh_view_bdt);

		$('#inklusi-konfirmasi').on('change', function() {
			if (flag == 1) return;

			flag = 1;

			val = $("#inklusi-konfirmasi").val();
			if (send_inklusi_konfirmasi()) {
				refresh_view_inklusi_konfirmasi(val);
			} else {
				if (val == 1) {
					$("#inklusi-konfirmasi").val(0);
				} else {
					$("#inklusi-konfirmasi").val(1);
				}
			}

			flag = 0;
		});

		$('#inklusi').on('change', refresh_view_inklusi);

		$('#btn_nomor_kontak').on('click', send_nomor_kontak);

		//all datatable must be responsive
		$.extend( $.fn.dataTable.defaults, { responsive: true } );

		//file list
		$.fn.dataTable.Editor.files[ 'files' ] = files;

		//editor
		editor = new $.fn.dataTable.Editor( {
			ajax: "<?php echo site_url('siswa/profil/json'); ?>",
			fields: [ 
				{
					label: "Nilai Rata-rata Rapor (0-100):",
					name: "nilai_semester",
					type: "text",
					attr: { type: "number", max:'100', min:'0' }
				}, {
					label: "Nilai Rata-rata Ujian Sekolah (0-100):",
					name: "nilai_lulus",
					type: "text",
					attr: { type: "number" }
				}, {
					label: "Nilai UN Bahasa Indonesia (0-100):",
					name: "nilai_bin",
					type: "text",
					attr: { type: "number" }
				}, {
					label: "Nilai UN Matematika (0-100):",
					name: "nilai_mat",
					type: "text",
					attr: { type: "number" }
				}, {
					label: "Nilai UN Ilmu Pengetahuan Alam (0-100):",
					name: "nilai_ipa",
					type: "text",
					attr: { type: "number" }
				}, {
					label: "Nomor Kartu Indonesia Pintar:",
					name: "nomor_kip",
					type: "text",
				}, {
					label: "Nomor Basis Data Terpadu:",
					name: "nomor_bdt",
					type: "text",
				}, {
					label: "Kebutuhan Khusus:",
					name: "kebutuhan_khusus",
					type: "select",
					options: [
								{ label: "Tidak ada", value: "Tidak ada" },
								{ label: "A - Tuna netra", value: "A - Tuna netra" },
								{ label: "B - Tuna rungu", value: "B - Tuna rungu" },
								{ label: "C - Tuna grahita ringan", value: "C - Tuna grahita ringan" },
								{ label: "C1 - Tuna grahita sedang", value: "C1 - Tuna grahita sedang" },
								{ label: "D - Tuna daksa ringan", value: "D - Tuna daksa ringan" },
								{ label: "D1 - Tuna daksa sedang", value: "D1 - Tuna daksa sedang" },
								{ label: "E - Tuna laras", value: "E - Tuna laras" },
								{ label: "F - Tuna wicara", value: "F - Tuna wicara" },
								{ label: "K - Kesulitan Belajar", value: "K - Kesulitan Belajar" },
								{ label: "P - Down Syndrome", value: "P - Down Syndrome" },
								{ label: "Q - Autis", value: "Q - Autis" },
							],
				<?php if($upload_dokumen) { ?>
					}, {
						label: "Akte Kelahiran:",
						name: "dokumen_5",
						type: "upload",
						display: function ( file_id ) {
							if (file_id == "" || typeof files[file_id] === undefined) {
								return "";
							}
							return '<img src="'+editor.file( 'files', file_id ).thumbnail_path+'"/>';
						},
						clearText: "Hapus",
						noImageText: 'No image',
						uploadText: "Pilih dokumen...",
						noFileText: 'Tidak ada dokumen',
						processingText: 'Sedang mengunggah',
						fileReadText: 'Membaca dokumen',
						dragDropText: 'Seret dan letakkan dokumen di sini untuk mengunggah'
					}, {
						label: "Kartu Keluarga:",
						name: "dokumen_6",
						type: "upload",
						display: function ( file_id ) {
							if (file_id == "" || typeof files[file_id] === undefined) {
								return "";
							}
							return '<img src="'+editor.file( 'files', file_id ).thumbnail_path+'"/>';
						},
						clearText: "Hapus",
						noImageText: 'No image',
						uploadText: "Pilih dokumen...",
						noFileText: 'Tidak ada dokumen',
						processingText: 'Sedang mengunggah',
						fileReadText: 'Membaca dokumen',
						dragDropText: 'Seret dan letakkan dokumen di sini untuk mengunggah'
					}, {
						label: "Surat Keterangan Domisili:",
						name: "dokumen_19",
						type: "upload",
						display: function ( file_id ) {
							if (file_id == "" || typeof files[file_id] === undefined) {
								return "";
							}
							return '<img src="'+editor.file( 'files', file_id ).thumbnail_path+'"/>';
						},
						clearText: "Hapus",
						noImageText: 'No image',
						uploadText: "Pilih dokumen...",
						noFileText: 'Tidak ada dokumen',
						processingText: 'Sedang mengunggah',
						fileReadText: 'Membaca dokumen',
						dragDropText: 'Seret dan letakkan dokumen di sini untuk mengunggah'
					}, {
						label: "Rapor Kelas 6:",
						name: "dokumen_26",
						type: "upload",
						display: function ( file_id ) {
							if (file_id == "" || typeof files[file_id] === undefined) {
								return "";
							}
							return '<img src="'+editor.file( 'files', file_id ).thumbnail_path+'"/>';
						},
						clearText: "Hapus",
						noImageText: 'No image',
						uploadText: "Pilih dokumen...",
						noFileText: 'Tidak ada dokumen',
						processingText: 'Sedang mengunggah',
						fileReadText: 'Membaca dokumen',
						dragDropText: 'Seret dan letakkan dokumen di sini untuk mengunggah'
					}, {
						label: "Ijazah / Surat Keterangan Lulus (SKL):",
						name: "dokumen_2",
						type: "upload",
						display: function ( file_id ) {
							if (file_id == "" || typeof files[file_id] === undefined) {
								return "";
							}
							return '<img src="'+editor.file( 'files', file_id ).thumbnail_path+'"/>';
						},
						clearText: "Hapus",
						noImageText: 'No image',
						uploadText: "Pilih dokumen...",
						noFileText: 'Tidak ada dokumen',
						processingText: 'Sedang mengunggah',
						fileReadText: 'Membaca dokumen',
						dragDropText: 'Seret dan letakkan dokumen di sini untuk mengunggah'
					}, {
						label: "Surat Keterangan Hasil Ujian Nasional (SKHUN):",
						name: "dokumen_3",
						type: "upload",
						display: function ( file_id ) {
							if (file_id == "" || typeof files[file_id] === undefined) {
								return "";
							}
							return '<img src="'+editor.file( 'files', file_id ).thumbnail_path+'"/>';
						},
						clearText: "Hapus",
						noImageText: 'No image',
						uploadText: "Pilih dokumen...",
						noFileText: 'Tidak ada dokumen',
						processingText: 'Sedang mengunggah',
						fileReadText: 'Membaca dokumen',
						dragDropText: 'Seret dan letakkan dokumen di sini untuk mengunggah'
					}, {
						label: "Kartu Indonesia Pintar:",
						name: "dokumen_16",
						type: "upload",
						display: function ( file_id ) {
							if (file_id == "" || typeof files[file_id] === undefined) {
								return "";
							}
							return '<img src="'+editor.file( 'files', file_id ).thumbnail_path+'"/>';
						},
						clearText: "Hapus",
						noImageText: 'No image',
						uploadText: "Pilih dokumen...",
						noFileText: 'Tidak ada dokumen',
						processingText: 'Sedang mengunggah',
						fileReadText: 'Membaca dokumen',
						dragDropText: 'Seret dan letakkan dokumen di sini untuk mengunggah'
					}, {
						label: "Surat Keterangan Masuk BDT:",
						name: "dokumen_20",
						type: "upload",
						display: function ( file_id ) {
							if (file_id == "" || typeof files[file_id] === undefined) {
								return "";
							}
							return '<img src="'+editor.file( 'files', file_id ).thumbnail_path+'"/>';
						},
						clearText: "Hapus",
						noImageText: 'No image',
						uploadText: "Pilih dokumen...",
						noFileText: 'Tidak ada dokumen',
						processingText: 'Sedang mengunggah',
						fileReadText: 'Membaca dokumen',
						dragDropText: 'Seret dan letakkan dokumen di sini untuk mengunggah'
					}, {
						label: "Surat Keterangan Berkebutuhan Khusus:",
						name: "dokumen_9",
						type: "upload",
						display: function ( file_id ) {
							if (file_id == "" || typeof files[file_id] === undefined) {
								return "";
							}
							return '<img src="'+editor.file( 'files', file_id ).thumbnail_path+'"/>';
						},
						clearText: "Hapus",
						noImageText: 'No image',
						uploadText: "Pilih dokumen...",
						noFileText: 'Tidak ada dokumen',
						processingText: 'Sedang mengunggah',
						fileReadText: 'Membaca dokumen',
						dragDropText: 'Seret dan letakkan dokumen di sini untuk mengunggah'
					}, {
						label: "Surat Pernyataan Kebenaran Dokumen:",
						name: "dokumen_21",
						type: "upload",
						display: function ( file_id ) {
							if (file_id == "" || typeof files[file_id] === undefined) {
								return "";
							}
							return '<img src="'+editor.file( 'files', file_id ).thumbnail_path+'"/>';
						},
						clearText: "Hapus",
						noImageText: 'No image',
						uploadText: "Pilih dokumen...",
						noFileText: 'Tidak ada dokumen',
						processingText: 'Sedang mengunggah',
						fileReadText: 'Membaca dokumen',
						dragDropText: 'Seret dan letakkan dokumen di sini untuk mengunggah'
					},
					<?php foreach($dokumen_tambahan as $key => $fields) { 
						//SKHUN
						if ($key == 3 || $key == 9) continue;
					?>
					{
						label: "<?php echo $fields['nama']; ?>:",
						name: "dokumen_<?php echo $key; ?>",
						type: "upload",
						display: function ( file_id ) {
							if (file_id == "" || typeof files[file_id] === undefined) {
								return "";
							}
							return '<img src="'+editor.file( 'files', file_id ).thumbnail_path+'"/>';
						},
						clearText: "Hapus",
						noImageText: 'No image',
						uploadText: "Pilih dokumen...",
						noFileText: 'Tidak ada dokumen',
						processingText: 'Sedang mengunggah',
						fileReadText: 'Membaca dokumen',
						dragDropText: 'Seret dan letakkan dokumen di sini untuk mengunggah'
					},
					<?php } ?>
				<?php } ?>
			],
			i18n: {
			create: {
				button: "Baru",
				title:  "Data siswa baru",
				submit: "Simpan"
			},
			edit: {
				button: "Ubah",
				title:  "Ubah data siswa",
				submit: "Simpan"
			},
			remove: {
				button: "Hapus",
				title:  "Hapus data siswa",
				submit: "Hapus",
				confirm: {
					_: "Konfirmasi hapus %d data siswa?",
					1: "Konfirmasi hapus 1 data siswa?"
				}
			},        
			error: {
				system: "Ada permasalahan dalam menyimpan data. Silahkan hubungi nomor bantuan."
			},
			datetime: {
				previous: 'Sebelum',
				next:     'Selanjutnya',
				months:   [ 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember' ],
				weekdays: [ 'Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab' ],
				hour: 'Jam',
				minute: 'Menit'
			}
			}
		});

		// // get the editor form before it submits to weed out data
		// editor.on('preSubmit', function (e, o, action) {
		// 	// if it's a bubble edit (a single field)
		// 	if(action == 'edit' && e.target.s.mode=='bubble') {
		// 		// loop through our data array and remove all properties except dtype
		// 		for(var key in o) {
		// 			var obj = o[key];
		// 			for(var prop in obj) {
		// 				if(prop != o.dtype) {
		// 					// remove the unwanted data property
		// 					delete o[key][prop];
		// 				}
		// 			}
		// 		}
		// 	}
		// });

		// Activate the bubble editor on click of a table cell
		$('[data-editor-field]').on( 'click', function (e) {
			if (!$(this).hasClass("editable")) return;

			if ($(this).hasClass("editable-prestasi")) {
				editprestasi.bubble(this, { submit: 'changed' });
			}
			else {
				editor.bubble( this, { submit: 'changed' });
			}
		});
		

		$('.editable-icon').on( 'click', function (e) {
			editor.bubble($(this).next(), { submit: 'changed' });
			e.stopPropagation();
		});

		editprestasi = new $.fn.dataTable.Editor( {
			ajax: "<?php echo site_url('siswa/prestasi/json'); ?>",
			table: '#tbl-prestasi',
			idSrc: "prestasi_id",
			fields: [ 
				{
					label: "Prestasi:",
					name: "skoring_id",
					type: "select",
				}, {
					label: "Uraian:",
					name: "uraian",
					type: "textarea",
				<?php if($upload_dokumen) { ?>
				}, {
					label: "Dokumen Pendukung:",
					name: "dokumen_pendukung",
					type: "upload",
					display: function ( file_id ) {
						if (file_id == "" || typeof files[file_id] === undefined) {
							return "";
						}
						return '<img src="'+editprestasi.file( 'files', file_id ).thumbnail_path+'"/>';
					},
					clearText: "Hapus",
					noImageText: 'No image'
				<?php } ?>
				}
			],
			i18n: {
			create: {
				button: "Baru",
				title:  "Prestasi baru",
				submit: "Simpan"
			},
			edit: {
				button: "Ubah",
				title:  "Ubah prestasi",
				submit: "Simpan"
			},
			remove: {
				button: "Hapus",
				title:  "Hapus prestasi",
				submit: "Hapus",
				confirm: {
					_: "Konfirmasi hapus %d prestasi?",
					1: "Konfirmasi hapus 1 prestasi?"
				}
			},        
			error: {
				system: "Ada permasalahan dalam menyimpan data. Silahkan hubungi nomor bantuan."
			},
			datetime: {
				previous: 'Sebelum',
				next:     'Selanjutnya',
				months:   [ 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember' ],
				weekdays: [ 'Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab' ],
				hour: 'Jam',
				minute: 'Menit'
			}
			}
		});

        editprestasi.on('preSubmit', function(e, o, action) {
            if (action === 'create' || action === 'edit') {
                let field = null;

				field = this.field('skoring_id');
				if (!field.isMultiValue()) {
					if (!field.val() || field.val() == 0) {
						field.error('Prestasi harus diisi');
					}
				}

				field = this.field('uraian');
				if (!field.isMultiValue()) {
					if (!field.val() || field.val() == 0) {
						field.error('Uraian harus diisi');
					}
				}

				field = this.field('dokumen_pendukung');
				if (!field.isMultiValue()) {
					if (!field.val() || field.val() == 0) {
						field.error('Dokumen pendukung harus diisi');
					}
				}

                /* If any error was reported, cancel the submission so it can be corrected */
                if (this.inError()) {
                    this.error('Data wajib belum diisi');
                    return false;
                }
            }            
        });        

		dtprestasi = $('#tbl-prestasi').DataTable({
			"responsive": true,
			"paging": false,
			"dom": "Brt",
			select: true,
			buttons: [
				{ extend: "create", editor: editprestasi, className: "dt-button margin-left-10 d-none" },
				// { extend: "edit", editor: editprestasi, className: "dt-button d-none" },
				{ extend: "remove", editor: editprestasi, className: "dt-button d-none" },
			],
			ajax: "<?php echo site_url('siswa/prestasi/json'); ?>",
			"language": {
				"processing":   "Sedang proses...",
				"lengthMenu":   "Tampilan _MENU_ entri",
				"zeroRecords":  "Tidak ditemukan data yang sesuai",
				"loadingRecords": "Loading...",
				"emptyTable":   "Tidak ditemukan data yang sesuai",
				},
			columns: [
				// { "defaultContent": "" },
				{ data: "prestasi_id", className: 'dt-body-center', "orderable": false },
				{ data: "prestasi", className: 'dt-body-left', width: '25%', "orderable": false },
				{ data: "uraian", className: 'dt-body-left', width: '25%', "orderable": false },
				<?php if($upload_dokumen) { ?>
				{ data: "dokumen_pendukung", className: 'dt-body-left editable', "orderable": false,
					render: function ( file_id, type, row ) {
						if (type === 'display') {
							if (typeof editprestasi.file( 'files', file_id ) === "undefined") {
								return "";
							}
							
							let str= '<img class="img-view-thumbnail" src="'+editprestasi.file( 'files', file_id ).thumbnail_path+'" img-title="'+row.uraian+'" img-path="'+editprestasi.file( 'files', file_id ).web_path+'"/>';

							let verifikasi = row.verifikasi;
							if (verifikasi == 2) {
								str += ' <button class="img-view-button editable-prestasi" data-editor-field="dokumen_pendukung" data-editor-value="' +file_id+ '" data-editor-id="' +row.prestasi_id+ '" >Unggah</button>';
								str += '<div class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px;">' +row.catatan+ '</div>'
							}

							return str;
						}
						else {
							row.filename;
						}
					},
					defaultContent: "Tidak ada dokumen",
					title: "Dokumen Pendukung"
				},
				<?php } ?>
				{ data: "catatan", className: 'dt-body-left', width: '20%', "orderable": false },
			],
			"columnDefs": [ {
				"targets": 3,
				"createdCell": function (td, cellData, rowData, row, col) {
					if ( rowData.verifikasi == 1 ) {
						$(td).removeClass('editable');
					}
				}
			} ],
			order: [ 0, 'asc' ],
		});

		//TODO: when confirmed, still can edit dokumen-pendukung because this function is called. 
		$('#tbl-prestasi').on( 'click', '.editable', function (e) {
			editprestasi.bubble(this);
			e.stopPropagation();
		});

		editor.on( 'postSubmit', function ( e, json, data, action, xhr ) {
			//always reload after updating anggaran
			if (action != "edit") {
				return;
			}

			if (typeof json === "undefined" || json == null || (typeof json.error !== "undefined" && json.error != null)) {
				//there is error. ignore it
				return;
			}

			for (var key of Object.keys(data.data)) {
				let entry = data.data[key];
				for (var field of Object.keys(entry)) {
					let value = entry[field];

					//Important: a bug in dt editor!!
					if (value == "" && field == "nilai_lulus") {
						continue;
					}

					if (value == "" && field == "nilai_semester") {
						continue;
					}

					if (field == "dokumen_21") {
						var kelengkapan_id = 21;

						if (value == "") {
							//reset value
							var link = $("#dokumen-" + kelengkapan_id);
							link.attr("href","");

							var tgl = $("#tanggal-dokumen-" + kelengkapan_id);
							tgl.html("");

							var footer = $("#row-dokumen-" + kelengkapan_id);
							footer.hide();
						}
						else {
							//new value
							var dokumen = json.dokumen[kelengkapan_id];
							if (typeof dokumen === "undefined" || dokumen == null || dokumen.thumbnail_path == "") {
								continue;
							}

							var link = $("#dokumen-" + kelengkapan_id);
							link.attr("href",dokumen.web_path);

							var tgl = $("#tanggal-dokumen-" + kelengkapan_id);
							tgl.html(dokumen.create_date);

							var footer = $("#row-dokumen-" + kelengkapan_id);
							footer.show();

							$("#spkd-unggah-row").removeClass("box-red");		
						}

					}
					else if (field.substr(0,8) == "dokumen_") {
						var kelengkapan_id = field.substr(8, field.length);

						if (value == "") {
							//reset value
							var img = $("#dokumen-" + kelengkapan_id);
							img.attr("src", "");
							img.attr("img-path", "");
							img.hide();

							var btn = $("#unggah-dokumen-" + kelengkapan_id);
							btn.attr("data-editor-value", '');
							btn.html("Unggah");

							var lbl = $("#label-dokumen-" + kelengkapan_id);
							lbl.hide();
						}
						else {
							//new value
							var dokumen = json.dokumen[kelengkapan_id];
							if (typeof dokumen === "undefined" || dokumen == null) {
								continue;
							}

							if (dokumen.thumbnail_path == "") {
								var img = $("#dokumen-" + kelengkapan_id);
								img.attr("src", "");
								img.attr("img-path", "");
								img.hide();

								var btn = $("#unggah-dokumen-" + kelengkapan_id);
								btn.attr("data-editor-value", '');
								btn.html("Unggah");

								var lbl = $("#label-dokumen-" + kelengkapan_id);
								lbl.hide();
							}
							else {
								//update img
								var img = $("#dokumen-" + kelengkapan_id);
								img.attr("src", dokumen.thumbnail_path);
								img.attr("img-path", dokumen.web_path);
								img.show();

								//update button
								var btn = $("#unggah-dokumen-" + kelengkapan_id);
								btn.attr("data-editor-value", dokumen.dokumen_id);
								btn.html("Ubah");

								var lbl = $("#label-dokumen-" + kelengkapan_id);
								lbl.attr("href", dokumen.web_path);
								lbl.html(dokumen.filename);
								lbl.show();
							}
						}
					}
					else {
						//kolom/td
						var kol = $("#" + field);
						kol.html(entry[field]);
					}

				}
			}
		});

		//setup the view
		<?php if ($dikunci == 1 && $profil_verifikasi != 2) { ?>
		val = 1;
		<?php } else { ?>
		val = $("#profil-konfirmasi").val();
		<?php } ?>
		refresh_view_profil_konfirmasi(val);

		<?php if ($dikunci == 1 && $lokasi_verifikasi != 2) { ?>
		val = 1;
		<?php } else { ?>
		val = $("#lokasi-konfirmasi").val();
		<?php } ?>
		refresh_view_lokasi_konfirmasi(val);

		<?php if ($dikunci == 1 && $nilai_verifikasi != 2) { ?>
		val = 1;
		<?php } else { ?>
		val = $("#nilai-konfirmasi").val();
		<?php } ?>
		refresh_view_nilai_konfirmasi(val);
		refresh_view_nilai_un();

		<?php if ($dikunci == 1 && $prestasi_verifikasi != 2) { ?>
		val = 1;
		<?php } else { ?>
		val = $("#prestasi-konfirmasi").val();
		<?php } ?>
		refresh_view_prestasi_konfirmasi(val);
		refresh_view_prestasi();

		<?php if ($dikunci == 1 && $afirmasi_verifikasi != 2) { ?>
		val = 1;
		<?php } else { ?>
		val = $("#afirmasi-konfirmasi").val();
		<?php } ?>
		refresh_view_afirmasi_konfirmasi(val);
		refresh_view_kip();
		refresh_view_bdt();

		<?php if ($dikunci == 1 && $inklusi_verifikasi != 2) { ?>
		val = 1;
		<?php } else { ?>
		val = $("#inklusi-konfirmasi").val();
		<?php } ?>
		refresh_view_inklusi_konfirmasi(val);
		refresh_view_inklusi();

		<?php if ($profil_konfirmasi == 0 && $profil_verifikasi != 2) { ?>$("#profil-konfirmasi-row").addClass("box-red");<?php } ?>
		<?php if ($lokasi_konfirmasi == 0 && $lokasi_verifikasi != 2) { ?>$("#lokasi-konfirmasi-row").addClass("box-red");<?php } ?>
		<?php if ($nilai_konfirmasi == 0 && $nilai_verifikasi != 2) { ?>$("#nilai-konfirmasi-row").addClass("box-red");<?php } ?>
		<?php if ($prestasi_konfirmasi == 0 && $prestasi_verifikasi != 2) { ?>$("#prestasi-konfirmasi-row").addClass("box-red");<?php } ?>
		<?php if ($afirmasi_konfirmasi == 0 && $afirmasi_verifikasi != 2) { ?>$("#afirmasi-konfirmasi-row").addClass("box-red");<?php } ?>
		<?php if ($inklusi_konfirmasi == 0 && $inklusi_verifikasi != 2) { ?>$("#inklusi-konfirmasi-row").addClass("box-red");<?php } ?>
		<?php if (empty($nomor_handphone)) { ?>$("#kontak-ubah-row").addClass("box-red");<?php } ?>
		<?php if (empty($pernyataan_file)) { ?>$("#spkd-unggah-row").addClass("box-red");<?php } ?>

		<?php if ($profil_verifikasi == 2) { 
				$msg = "";
				if ($profil_konfirmasi == 1) {
					$msg = "Menunggu verifikasi ulang"; 
				} else {
					$msg = empty($row->catatan_profil) ? 'Data belum benar' : nonewline($row->catatan_profil); 
				}
		?>
			$("#profil-error-msg").html("<?php echo $msg; ?>");
			$("#profil-error-row").show();
		<?php } ?>

		<?php if ($lokasi_verifikasi == 2) { 
				$msg = "";
				if ($lokasi_konfirmasi == 1) {
					$msg = "Menunggu verifikasi ulang"; 
				} else {
					$msg = empty($row->catatan_lokasi) ? 'Data belum benar' : nonewline($row->catatan_lokasi); 
				}
		?>
			$("#lokasi-error-msg").html("<?php echo $msg; ?>");
			$("#lokasi-error-row").show();
		<?php } ?>

		<?php if ($nilai_verifikasi == 2) { 
				$msg = "";
				if ($nilai_konfirmasi == 1) {
					$msg = "Menunggu verifikasi ulang"; 
				} else {
					$msg = empty($row->catatan_nilai) ? 'Data belum benar' : nonewline($row->catatan_nilai); 
				}
		?>
			$("#nilai-error-msg").html("<?php echo $msg; ?>");
			$("#nilai-error-row").show();
		<?php } ?>

		<?php if ($prestasi_verifikasi == 2) { 
				$msg = "";
				if ($prestasi_konfirmasi == 1) {
					$msg = "Menunggu verifikasi ulang"; 
				} else {
					$msg = empty($row->catatan_prestasi) ? 'Data belum benar' : nonewline($row->catatan_prestasi); 
				}
		?>
			$("#prestasi-error-msg").html("<?php echo $msg; ?>");
			$("#prestasi-error-row").show();
		<?php } ?>

		<?php if ($afirmasi_verifikasi == 2) { 
				$msg = "";
				if ($afirmasi_konfirmasi == 1) {
					$msg = "Menunggu verifikasi ulang"; 
				} else {
					$msg = empty($row->catatan_afirmasi) ? 'Data belum benar' : nonewline($row->catatan_afirmasi); 
				}
		?>
			$("#afirmasi-error-msg").html("<?php echo $msg; ?>");
			$("#afirmasi-error-row").show();
		<?php } ?>

		<?php if ($inklusi_verifikasi == 2) { 
				$msg = "";
				if ($inklusi_konfirmasi == 1) {
					$msg = "Menunggu verifikasi ulang"; 
				} else {
					$msg = empty($row->catatan_inklusi) ? 'Data belum benar' : nonewline($row->catatan_inklusi); 
				}
		?>
			$("#inklusi-error-msg").html("<?php echo $msg; ?>");
			$("#inklusi-error-row").show();
		<?php } ?>

		// akte kelahiran
		<?php if (!empty($dokumen[5]) && $dokumen[5]['verifikasi'] == 2) { ?>
			$("#msg-dokumen-5").html("<?php echo empty($dokumen[5]['catatan']) ? "Mohon dicek ulang" : nonewline($dokumen[5]['catatan']); ?>");
			$("#msg-dokumen-5").show();
			$("#row-span-dokumen-kk").show();
			$("#unggah-dokumen-5").show();
			$("#unggah-dokumen-5").addClass("editable");
		<?php } ?>

		// kartu keluarga
		<?php if (!empty($dokumen[6]) && $dokumen[6]['verifikasi'] == 2) { ?>
			$("#msg-dokumen-6").html("<?php echo empty($dokumen[6]['catatan']) ? "Mohon dicek ulang" : nonewline($dokumen[6]['catatan']); ?>");
			$("#msg-dokumen-6").show();
			$("#row-span-dokumen-akte").show();
			$("#unggah-dokumen-6").show();
			$("#unggah-dokumen-6").addClass("editable");
		<?php } ?>

		// surat domisili (19)
		<?php if (!empty($dokumen[19]) && $dokumen[19]['verifikasi'] == 2) { ?>
			$("#msg-dokumen-19").html("<?php echo empty($dokumen[19]['catatan']) ? "Mohon dicek ulang" : nonewline($dokumen[19]['catatan']); ?>");
			$("#msg-dokumen-19").show();
			$("#row-span-dokumen-domisili").show();
			$("#unggah-dokumen-19").show();
			$("#unggah-dokumen-19").addClass("editable");
		<?php } ?>

		// rapor kelas 6 (26)
		<?php if (!empty($dokumen[26]) && $dokumen[26]['verifikasi'] == 2) { ?>
			$("#msg-dokumen-26").html("<?php echo empty($dokumen[26]['catatan']) ? "Mohon dicek ulang" : nonewline($dokumen[26]['catatan']); ?>");
			$("#msg-dokumen-26").show();
			$("#unggah-dokumen-26").show();
			$("#unggah-dokumen-26").addClass("editable");
		<?php } ?>

		// ijazah (2)
		<?php if (!empty($dokumen[2]) && $dokumen[2]['verifikasi'] == 2) { ?>
			$("#msg-dokumen-2").html("<?php echo empty($dokumen[2]['catatan']) ? "Mohon dicek ulang" : nonewline($dokumen[2]['catatan']); ?>");
			$("#msg-dokumen-2").show();
			$("#row-span-dokumen-skl").show();
			$("#unggah-dokumen-2").show();
			$("#unggah-dokumen-2").addClass("editable");
		<?php } ?>

		// SKHUN (3)
		<?php if (!empty($dokumen[3]) && $dokumen[3]['verifikasi'] == 2) { ?>
			$("#msg-dokumen-3").html("<?php echo empty($dokumen[3]['catatan']) ? "Mohon dicek ulang" : nonewline($dokumen[3]['catatan']); ?>");
			$("#msg-dokumen-3").show();
			$("#row-span-dokumen-skhun").show();
			$("#unggah-dokumen-3").show();
			$("#unggah-dokumen-3").addClass("editable");
		<?php } ?>

		// Surat Bukti Prestasi (8)
		<?php if (!empty($dokumen[8]) && $dokumen[8]['verifikasi'] == 2) { ?>
			$("#tbl-prestasi-kontainer").show();
		<?php } ?>

		// KIP (16)
		<?php if (!empty($dokumen[16]) && $dokumen[16]['verifikasi'] == 2) { ?>
			$("#msg-dokumen-16").html("<?php echo empty($dokumen[16]['catatan']) ? "Mohon dicek ulang" : nonewline($dokumen[16]['catatan']); ?>");
			$("#msg-dokumen-16").show();
			$("#row-span-dokumen-kip").show();
			$("#unggah-dokumen-16").show();
			$("#unggah-dokumen-16").addClass("editable");
		<?php } ?>

		// BDT (20)
		<?php if (!empty($dokumen[20]) && $dokumen[20]['verifikasi'] == 2) { ?>
			$("#msg-dokumen-20").html("<?php echo empty($dokumen[20]['catatan']) ? "Mohon dicek ulang" : nonewline($dokumen[20]['catatan']); ?>");
			$("#msg-dokumen-20").show();
			$("#row-span-dokumen-bdt").show();
			$("#unggah-dokumen-20").show();
			$("#unggah-dokumen-20").addClass("editable");
		<?php } ?>

		// SK Kebutuhan Khusus (9)
		<?php if (!empty($dokumen[9]) && $dokumen[9]['verifikasi'] == 2) { ?>
			$("#msg-dokumen-9").html("<?php echo empty($dokumen[9]['catatan']) ? "Mohon dicek ulang" : nonewline($dokumen[9]['catatan']); ?>");
			$("#msg-dokumen-9").show();
			$("#row-span-dokumen-kebutuhan-khusus").show();
			$("#unggah-dokumen-9").show();
			$("#unggah-dokumen-9").addClass("editable");
		<?php } ?>

		// Surat Pernyataan
		<?php if (!empty($dokumen[21]) && $dokumen[21]['verifikasi'] == 2) { ?>
			$("#dokumen-21-error-msg").html("<?php echo empty($dokumen[21]['catatan']) ? "Mohon dicek ulang" : nonewline($dokumen[21]['catatan']); ?>");
			$("#dokumen-21-error-row").show();
			$("#row-dokumen-21-input").show();
		<?php } ?>

		// dokumen pendukung tambahan
		<?php foreach($dokumen_tambahan as $key => $fields) { ?>
			<?php if ($fields['verifikasi'] == 1) { ?>
				$("#row-span-dokumen-<?php echo $key; ?>").hide();
				$("#unggah-dokumen-<?php echo $key; ?>").hide();
				$("#unggah-dokumen-<?php echo $key; ?>").removeClass("editable");
				$("#msg-dokumen-<?php echo $key; ?>").html("");
				$("#msg-dokumen-<?php echo $key; ?>").hide();
			<?php } else if ($fields['verifikasi'] == 2) { ?>
				$("#row-span-dokumen-<?php echo $key; ?>").show();
				$("#unggah-dokumen-<?php echo $key; ?>").show();
				$("#unggah-dokumen-<?php echo $key; ?>").addClass("editable");
				$("#msg-dokumen-<?php echo $key; ?>").html("<?php echo empty($dokumen[9]['catatan']) ? "Mohon dicek ulang" : nonewline($dokumen[9]['catatan']); ?>");
				$("#msg-dokumen-<?php echo $key; ?>").show();
			<?php } else { ?>
				<?php if ($dikunci == 1) { ?>
					$("#row-span-dokumen-<?php echo $key; ?>").hide();
					$("#unggah-dokumen-<?php echo $key; ?>").hide();
					$("#unggah-dokumen-<?php echo $key; ?>").removeClass("editable");
				<?php } else { ?>
					$("#row-span-dokumen-<?php echo $key; ?>").show();
					$("#unggah-dokumen-<?php echo $key; ?>").show();
					$("#unggah-dokumen-<?php echo $key; ?>").addClass("editable");
				<?php } ?>
				$("#msg-dokumen-<?php echo $key; ?>").html("");
				$("#msg-dokumen-<?php echo $key; ?>").hide();
			<?php } ?>
		<?php } ?>

		dtriwayat = $('#triwayat').DataTable({
			"responsive": true,
			"paging": false,
			"dom": 't',
			"buttons": [
			],
			"language": {
				"sProcessing":   "Sedang proses...",
				"sLengthMenu":   "Tampilan _MENU_ entri",
				"sZeroRecords":  "Tidak ditemukan data yang sesuai",
				"sInfo":         "Tampilan _START_ - _END_ dari _TOTAL_ entri",
				"sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 entri",
				"sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
				"sInfoPostFix":  "",
				"sSearch":       "Cari:",
				"sUrl":          "",
				"oPaginate": {
					"sFirst":    "Awal",
					"sPrevious": "Balik",
					"sNext":     "Lanjut",
					"sLast":     "Akhir"
				}
			},	
			ajax: "<?php echo site_url('siswa/profil/riwayat'); ?>",
			columns: [
				{ data: "create_date", className: 'dt-body-center readonly-column', orderable: false },
				{ data: "nama", className: 'dt-body-left readonly-column', orderable: false },
				{ data: "verifikasi", className: 'dt-body-center', orderable: false, 
					"render": function (val, type, row) {
							return val == 1 ? "SUDAH Lengkap" : "BELUM Lengkap";
						} 
				},
				{ data: "catatan_kekurangan", className: 'dt-body-left readonly-column', width: "50%", orderable: false,
					"render": function (val, type, row) {
							return val.replace(/:/g, " : ").replace(/;/g, "<br>");
						} 
				},
			],
			order: [ 0, 'desc' ],
		});

	});

</script>