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

<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">
 
<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/iCheck/all.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css"/>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet'/>

<style>
    .nomer-kontak {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
        background-color: #666 !important;
        color: #fff;
    }
</style>

	<?php
		foreach($settingverifikasiberkas->getResult() as $row):
			$tanggal_mulai_aktif = $row->tanggal_mulai_aktif;
			$tanggal_selesai_aktif = $row->tanggal_selesai_aktif;
		endforeach;

		$pendaftaran_id = $this->input->get("pendaftaran_id");

		$nomor_kontak = "";
		$peserta_didik_id = ""; 
		$kelengkapan_berkas = 0;

		foreach($profilsiswa->getResult() as $row):
			$nomor_kontak = $row->nomor_kontak;
			$peserta_didik_id = $row->peserta_didik_id;
			$nisn = $row->nisn;
			$nama = $row->nama;
			//$kelengkapan_berkas = $row->kelengkapan_berkas;
	?>

<?php
    foreach($profilsiswa->getResult() as $row):
        $nomor_kontak = $row->nomor_kontak;
        $peserta_didik_id = $row->peserta_didik_id;
        $nisn = $row->nisn;
        $nama = $row->nama;
        //$kelengkapan_berkas = $row->kelengkapan_berkas;
?>

<span><?php if(isset($info)){echo $info;}?></span>

<?php
	$error = $this->session->flashdata('error');
	if($error)
	{
?>
	<div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<?php echo $error; ?>                    
	</div>
<?php 
	}

	$success = $this->session->flashdata('success');
	if($success)
	{
?>
	<div class="alert alert-success alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<?php echo $success; ?>                    
	</div>
<?php } ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header with-border">
				<i class="glyphicon glyphicon-user"></i>
				<h3 class="box-title"><b><?php if($row->nisn!=""){?>(<?php echo $row->nisn;?>) <?php }?><?php echo $row->nama;?></b></h3>
				<span class="pull-right"><a href="<?php echo base_url();?>index.php/Chome/detailpendaftaran?peserta_didik_id=<?php echo $peserta_didik_id;?>" target="_blank" class="btn btn-primary btn-sm">Detail Pendaftaran</a></span>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<table class="table table-striped"  style="margin-bottom:0px !important;">
							<tr>
								<td><b>Sekolah Asal</b></td>
								<td>:</td>
								<?php if($row->sekolah_id!=""){?>
									<td>(<b><?php echo $row->npsn;?></b>) <a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/<?php echo $row->sekolah_id;?>" target="_blank"><?php echo $row->sekolah;?></a></td>
								<?php }else{?>
									<td><p>Tidak bersekolah sebelumnya.</p></td>
								<?php }?>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-body">
				<div class="row">		
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<table class="table" style="margin-bottom:5px !important;">
						<tr>
							<td style="padding-top:0px !important; padding-bottom:0px !important;">
								<h4 style="margin-top:0px !important; margin-bottom:0px !important;"><b>Lokasi Berkas</b></h4></td>
							<td style="padding-top:0px !important; padding-bottom:0px !important;">
								<h4 style="margin-top:0px !important; margin-bottom:0px !important;">:</h4></td>
							<td class="px-0" style="width:50%; padding-top:0px !important; padding-bottom:0px !important;">
								<h4 style="margin-top:0px !important; margin-bottom:0px !important;"><?php echo $row->lokasi_berkas; ?></h4>
							</td>
						</tr>
					</table>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>

<form role="form" enctype="multipart/form-data" id="prosesverifikasiberkas" action="<?php echo base_url();?>index.php/sekolah/verifikasi/prosesverifikasiberkas" method="post">
<input type="hidden" id="peserta_didik_id" name="peserta_didik_id" value="<?php echo $peserta_didik_id;?>">
<input type="hidden" id="pendaftaran_id" name="pendaftaran_id" value="<?php echo $pendaftaran_id;?>">

<div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="glyphicon glyphicon-user"></i>
                        <h3 class="box-title text-info"><b>Identitas Siswa</b></h3>
                    </div>
                    <div class="box-body">
                    <table class="table table-striped">
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
                            <?php if(!empty($padukuhan)) { ?>
                            <?php if (!empty($row->padukuhan)) { ?>
                            <td>Dukuh <?php echo $row->padukuhan;?>, <br>Desa <?php echo $row->desa_kelurahan;?>, <br><?php echo $row->kecamatan;?>, <br><?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?></td>
                            <?php } else { ?>
                            <td>Desa <?php echo $row->desa_kelurahan;?>, <br><?php echo $row->kecamatan;?>, <br><?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?></td>
                            <?php } ?>
                            <?php } ?>
                            <td>Desa <?php echo $row->desa_kelurahan;?>, <br><?php echo $row->kecamatan;?>, <br><?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;"><b>Dokumen Pendukung</b></td>
                        </tr>
                        <tr>
                            <td style="width: 45%;"><b>Akte Kelahiran</b></td>
                            <td>:</td>
                            <td style="width: 50%;">
                                <img id="dokumen-5" class="img-view-thumbnail" 
                                        src="<?php echo (empty($dokumen[5])) ? '' : $dokumen[5]['thumbnail_path']; ?>" 
                                        img-path="<?php echo (empty($dokumen[5])) ? '' : $dokumen[5]['web_path']; ?>" 
                                        img-title="Akte Kelahiran"/> 
                                <?php if(!empty($dokumen[5])) { ?>
                                <div>
                                <a id='label-dokumen-5' href="<?php echo $dokumen[5]['web_path']; ?>" target="_blank">
                                    <?php echo $dokumen[5]['filename'] ?>
                                </a>
                                </div>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Kartu Keluarga</b></td>
                            <td>:</td>
                            <td>
                                <img id="dokumen-6" class="img-view-thumbnail" 
                                    src="<?php echo (empty($dokumen[6])) ? '' : $dokumen[6]['thumbnail_path']; ?>" 
                                    img-path="<?php echo (empty($dokumen[6])) ? '' : $dokumen[6]['web_path']; ?>" 
                                    img-title="Kartu Keluarga"/> 
                                <?php if(!empty($dokumen[6])) { ?>
                                <div>
                                <a id='label-dokumen-6' href="<?php echo $dokumen[6]['web_path']; ?>" target="_blank">
                                    <?php echo $dokumen[6]['filename'] ?>
                                </a>
                                </div>
                                <?php } ?> 
                            </td>
                        </tr>
                    </table>
                    </div>
                    <div class="box-footer">
                        <input type="hidden" id="orig_verifikasi_profil" name="orig_verifikasi_profil" value="<?php echo $row->verifikasi_profil; ?>">
                        <input type="hidden" id="orig_catatan_profil" name="orig_catatan_profil" value="<?php echo $row->catatan_profil; ?>">
                        <?php if ($row->asal_data == 0) { ?>
                            <input type="hidden" id="verifikasi_profil" name="verifikasi_profil" value="1">
                            <input type="hidden" id="catatan_profil" name="catatan_profil" value="">
                        <?php } else { ?>
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label style="margin-right: 10px;">
                                    <input type="radio" id="verifikasi_profil" name="verifikasi_profil" class="flat-green"<?php if($row->verifikasi_profil==1){?> checked <?php }?> value="1" data-validation="required" data-validation-error-msg="Verifikasi data identitas">
                                    SUDAH Benar
                                </label>
                                <label style="margin-right: 10px;">
                                    <input type="radio" id="verifikasi_profil" name="verifikasi_profil" class="flat-red"<?php if($row->verifikasi_profil==2){?> checked <?php }?> value="2" data-validation="required" data-validation-error-msg="Verifikasi data identitas">
                                    BELUM Benar
                                </label>
                                <?php if (!empty($klarifikasidinas) && $klarifikasidinas['klarifikasi_dinas_profil'] == 1 && !empty($klarifikasidinas['catatan_dinas_profil'])) { ?>
                                    <a href="#!" onclick="show_klarifikasi_dinas('<?php echo $klarifikasidinas['catatan_dinas_profil']; ?>')" class="btn btn-sm btn-primary" style="margin-left: 10px;">
                                        Catatan Dinas
                                    </a>
                                <?php } else { ?>
                                    <label>
                                        <input type="radio" id="verifikasi_profil" name="verifikasi_profil" class="flat-red"<?php if($row->verifikasi_profil==3){?> checked <?php }?> value="3" data-validation="required" data-validation-error-msg="Verifikasi data identitas">
                                        KLARIFIKASI Dinas
                                    </label>
                                <?php } ?>
                            </div>
                            <?php if($row->verifikasi_profil==2) { ?>
                                <textarea id="catatan_profil" name="catatan_profil" 
                                placeholder="Apabila belum benar, silahkan masukkan informasi yang perlu diperbaiki di sini." data-validation="required" 
                                data-validation-error-msg="Catatan verifikasi data identitas"
                                style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $row->catatan_profil; ?></textarea>
                            <?php } else if($row->verifikasi_profil==3) { ?>
                                <textarea id="catatan_profil" name="catatan_profil" 
                                placeholder="Beri catatan kenapa memerlukan bantuan dinas untuk melakukan klarifikasi." data-validation="required" 
                                data-validation-error-msg="Catatan verifikasi data identitas"
                                style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $row->catatan_profil; ?></textarea>
                            <?php } else { ?>
                                <textarea id="catatan_profil" name="catatan_profil" 
                                placeholder="" 
                                data-validation="" 
                                data-validation-error-msg="Catatan verifikasi data identitas"
                                style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" disabled></textarea>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="glyphicon glyphicon-map-marker"></i>
                        <h3 class="box-title text-info"><b>Lokasi Rumah</b></h3>
                    </div>
                    <div class="box-body">
                        <div id="peta" style="width: 100%; height: 400px;"></div>
                        <table class="table table-striped" style="margin-top: 20px;">
                            <tr>
                                <td colspan="3" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;"><b>Dokumen Pendukung</b></td>
                            </tr>
                            <tr>
                                <td><b>Surat Domisili (Apabila lokasi tempat tinggal berbeda dengan alamat pada Kartu Keluarga)</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <?php if (empty($dokumen[19])) { ?>
                                    <div>Tidak Ada</div>
                                    <?php } else { ?>
                                    <img id="dokumen-19" class="img-view-thumbnail" 
                                            src="<?php echo $dokumen[19]['thumbnail_path']; ?>" 
                                            img-path="<?php echo $dokumen[19]['web_path']; ?>" 
                                            img-title="Surat Keterangan Domisili"/> 
                                    <div>
                                    <a id='label-dokumen-19' href="<?php echo $dokumen[19]['web_path']; ?>" target="_blank">
                                        <?php echo $dokumen[19]['filename'] ?>
                                    </a>
                                    </div>
                                    <?php } ?> 
                                </td>
                            </tr>
                            <tr>
                                <td><b>Rapor Kelas 6</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <?php if (empty($dokumen[26])) { ?>
                                    <div>Tidak Ada</div>
                                    <?php } else { ?>
                                    <img id="dokumen-26" class="img-view-thumbnail" 
                                            src="<?php echo $dokumen[26]['thumbnail_path']; ?>" 
                                            img-path="<?php echo $dokumen[26]['web_path']; ?>" 
                                            img-title="Rapor Kelas 6"/> 
                                    <div>
                                    <a id='label-dokumen-26' href="<?php echo $dokumen[26]['web_path']; ?>" target="_blank">
                                        <?php echo $dokumen[26]['filename'] ?>
                                    </a>
                                    </div>
                                    <?php } ?> 
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-footer">
                        <input type="hidden" id="orig_verifikasi_lokasi" name="orig_verifikasi_lokasi" value="<?php echo $row->verifikasi_lokasi; ?>">
                        <input type="hidden" id="orig_catatan_lokasi" name="orig_catatan_lokasi" value="<?php echo $row->catatan_lokasi; ?>">
                        <?php if ($row->asal_data == 0) { ?>
                            <input type="hidden" id="verifikasi_lokasi" name="verifikasi_lokasi" value="1">
                            <input type="hidden" id="catatan_lokasi" name="catatan_lokasi" value="">
                        <?php } else { ?>
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label style="margin-right: 10px;">
                                    <input type="radio" id="verifikasi_lokasi" name="verifikasi_lokasi" class="flat-green"<?php if($row->verifikasi_lokasi==1){?> checked <?php }?> value="1" data-validation="required" data-validation-error-msg="Verifikasi data lokasi rumah">
                                    SUDAH Benar
                                </label>
                                <label style="margin-right: 10px;">
                                    <input type="radio" id="verifikasi_lokasi" name="verifikasi_lokasi" class="flat-red"<?php if($row->verifikasi_lokasi==2){?> checked <?php }?> value="2" data-validation="required" data-validation-error-msg="Verifikasi data lokasi rumah">
                                    BELUM Benar
                                </label>
                                <?php if (!empty($klarifikasidinas) && $klarifikasidinas['klarifikasi_dinas_lokasi'] == 1 && !empty($klarifikasidinas['catatan_dinas_lokasi'])) { ?>
                                    <a href="#!" onclick="show_klarifikasi_dinas('<?php echo $klarifikasidinas['catatan_dinas_lokasi']; ?>')" class="btn btn-sm btn-primary" style="margin-left: 10px;">
                                        Catatan Dinas
                                    </a>
                                <?php } else { ?>
                                <label>
                                    <input type="radio" id="verifikasi_lokasi" name="verifikasi_lokasi" class="flat-red"<?php if($row->verifikasi_lokasi==3){?> checked <?php }?> value="3" data-validation="required" data-validation-error-msg="Verifikasi data lokasi rumah">
                                    KLARIFIKASI Dinas
                                </label>
                                <?php } ?>
                            </div>
                            <?php if($row->verifikasi_lokasi==2) { ?>
                                <textarea id="catatan_lokasi" name="catatan_lokasi" 
                                placeholder="Apabila belum benar, silahkan masukkan informasi yang perlu diperbaiki di sini." data-validation="required" 
                                data-validation-error-msg="Catatan verifikasi data lokasi rumah"
                                style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $row->catatan_lokasi; ?></textarea>
                            <?php } else if($row->verifikasi_lokasi==3) { ?>
                                <textarea id="verifikasi_lokasi" name="catatan_lokasi" 
                                placeholder="Beri catatan kenapa memerlukan bantuan dinas untuk melakukan verifikasi." data-validation="required" 
                                data-validation-error-msg="Catatan verifikasi data lokasi rumah"
                                style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $row->catatan_lokasi; ?></textarea>
                            <?php } else { ?>
                                <textarea id="catatan_lokasi" name="catatan_lokasi" 
                                placeholder="" 
                                data-validation="" 
                                data-validation-error-msg="Catatan verifikasi data lokasi rumah"
                                style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" disabled></textarea>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
</div>

<div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="glyphicon glyphicon-book"></i>
                        <h3 class="box-title text-info"><b>Nilai Kelulusan / Nilai Ujian Nasional</b></h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped">
                            <tr>
                                <td style="width: 45%;"><b>Nilai Rata-rata Rapor</b></td>
                                <td>:</td>
                                <td id="nilai_semester" data-editor-field="nilai_semester" style="width: 50%;"><?php echo $row->nilai_semester;?></td>
                            </tr>
                            <tr>
                                <td style="width: 45%;"><b>Nilai Rata-rata Ujian Sekolah</b></td>
                                <td>:</td>
                                <td id="nilai_lulus" data-editor-field="nilai_lulus" style="width: 50%;"><?php echo $row->nilai_lulus;?></td>
                            </tr>

                            <?php if ($row->punya_nilai_un == 1) { ?>
                                <tr>
                                    <td colspan="3"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;">
                                        <b>Nilai Ujian Nasional</b>
                                    </td>
                                </tr>
                                <tr id="row-un-bin">
                                    <td style="width: 45%;"><b>Bahasa Indonesia</b></td>
                                    <td>:</td>
                                    <td id="nilai_bin" data-editor-field="nilai_bin" style="width:50%; text-align:left"><?php echo $row->nilai_bin;?></td>
                                </tr>
                                <tr id="row-un-mat">
                                    <td><span id="row-span-un-mat"><b>Matematika</b></td>
                                    <td>:</td>
                                    <td id="nilai_mat" data-editor-field="nilai_mat"><?php echo $row->nilai_mat;?></td>
                                </tr>
                                <tr id="row-un-ipa">
                                    <td><span id="row-span-un-ipa"><b>IPA</b></td>
                                    <td>:</td>
                                    <td id="nilai_ipa" data-editor-field="nilai_ipa"><?php echo $row->nilai_ipa;?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;"><b>Dokumen Pendukung</b></td>
                            </tr>
                            <tr>
                                <td id="row-dokumen-skl" style="width: 45%;"><b>Surat Keterangan Lulus</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <img id="dokumen-2" class="img-view-thumbnail" 
                                            src="<?php echo (empty($dokumen[2])) ? '' : $dokumen[2]['thumbnail_path']; ?>" 
                                            img-path="<?php echo (empty($dokumen[2])) ? '' : $dokumen[2]['web_path']; ?>" 
                                            img-title="Ijazah / Surat Keterangan Lulus"/>  
                                    <?php if(!empty($dokumen[2])) { ?>
                                    <div>
                                    <a id='label-dokumen-2' href="<?php echo $dokumen[2]['web_path']; ?>" target="_blank">
                                        <?php echo $dokumen[2]['filename'] ?>
                                    </a>
                                    </div>
                                    <?php } ?> 
                                </td>
                            </tr>
                            <?php if ($row->punya_nilai_un == 1) { ?>
                                <tr id="row-dokumen-un">
                                    <td><b>Hasil Ujian Nasional</b></td>
                                    <td>:</td>
                                    <td>
                                        <img id="dokumen-3" class="img-view-thumbnail" 
                                                src="<?php echo (empty($dokumen[3])) ? '' : $dokumen[3]['thumbnail_path']; ?>" 
                                                img-path="<?php echo (empty($dokumen[3])) ? '' : $dokumen[3]['web_path']; ?>" 
                                                img-title="Surat Keterangan Hasil Ujian Nasional"/>  
                                        <?php if(!empty($dokumen[3])) { ?>
                                        <div>
                                        <a id='label-dokumen-3' href="<?php echo $dokumen[3]['web_path']; ?>" target="_blank">
                                            <?php echo $dokumen[3]['filename'] ?>
                                        </a>
                                        </div>
                                        <?php } ?> 
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="box-footer">
                        <input type="hidden" id="orig_verifikasi_nilai" name="orig_verifikasi_nilai" value="<?php echo $row->verifikasi_nilai; ?>">
                        <input type="hidden" id="orig_catatan_nilai" name="orig_catatan_nilai" value="<?php echo $row->catatan_nilai; ?>">
                        <div class="form-group" style="margin-bottom: 10px;">
                            <label style="margin-right: 10px;">
                                <input type="radio" id="verifikasi_nilai" name="verifikasi_nilai" class="flat-green"<?php if($row->verifikasi_nilai==1){?> checked <?php }?> value="1" data-validation="required" data-validation-error-msg="Verifikasi data nilai kelulusan / nilai Ujian Nasional">
                                SUDAH Benar
                            </label>
                            <label>
                                <input type="radio" id="verifikasi_nilai" name="verifikasi_nilai" class="flat-red"<?php if($row->verifikasi_nilai==2){?> checked <?php }?> value="2" data-validation="required" data-validation-error-msg="Verifikasi data nilai kelulusan / nilai Ujian Nasional">
                                BELUM Benar
                            </label>
                        </div>
                        <?php if($row->verifikasi_nilai==2) { ?>
                            <textarea id="catatan_nilai" name="catatan_nilai" 
                            placeholder="Apabila belum benar, silahkan masukkan informasi yang perlu diperbaiki di sini." data-validation="required" 
                            data-validation-error-msg="Catatan verifikasi data nilai kelulusan / nilai Ujian Nasional"
                            style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $row->catatan_nilai; ?></textarea>
                        <?php } else { ?>
                            <textarea id="catatan_nilai" name="catatan_nilai" 
                            placeholder="" 
                            data-validation="" 
                            data-validation-error-msg="Catatan verifikasi data nilai kelulusan / nilai Ujian Nasional"
                            style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" disabled></textarea>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="glyphicon glyphicon-education"></i>
                        <h3 class="box-title text-info"><b>Prestasi</b></h3>
                    </div>
                    <div class="box-body">
                        <table id="tprestasi" class="display" style="width: 100%;">
                            <?php if ($row->punya_prestasi == 0) { ?>
                            <tr>
                                <td colspan=3><b>Tidak ada data prestasi</b></td>
                            </tr>
                            <?php } else { ?>
                            <thead>
                                <tr>
                                    <td><b>Jenjang Prestasi</b></td>
                                    <td>Skoring</td>
                                </tr>
                            </thead>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="box-footer">
                            <input type="hidden" id="orig_verifikasi_prestasi" name="orig_verifikasi_prestasi" value="<?php echo $row->verifikasi_prestasi; ?>">
                                <input type="hidden" id="orig_catatan_prestasi" name="orig_catatan_prestasi" value="<?php echo $row->catatan_prestasi; ?>">
                                <?php if ($row->punya_prestasi == 0) { ?>
                                    <input type="hidden" id="verifikasi_prestasi" name="verifikasi_prestasi" value="1">
                                    <input type="hidden" id="catatan_prestasi" name="catatan_prestasi" value="">
                                <?php } else { ?>
                                    <div class="form-group" style="margin-bottom: 10px;">
                                        <label style="margin-right: 10px;">
                                            <input type="radio" id="verifikasi_prestasi" name="verifikasi_prestasi" class="flat-green"<?php if($row->verifikasi_prestasi==1){?> checked <?php }?> value="1" data-validation="required" data-validation-error-msg="Verifikasi data prestasi">
                                            SUDAH Benar
                                        </label>
                                        <label>
                                            <input type="radio" id="verifikasi_prestasi" name="verifikasi_prestasi" class="flat-red"<?php if($row->verifikasi_prestasi==2){?> checked <?php }?> value="2" data-validation="required" data-validation-error-msg="Verifikasi data prestasi">
                                            BELUM Benar
                                        </label>
                                    </div>
                                    <?php if($row->verifikasi_prestasi==2) { ?>
                                        <textarea id="catatan_prestasi" name="catatan_prestasi" 
                                        placeholder="Apabila belum benar, silahkan masukkan informasi yang perlu diperbaiki di sini." data-validation="required" 
                                        data-validation-error-msg="Catatan verifikasi data prestasi"
                                        style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $row->catatan_prestasi; ?></textarea>
                                    <?php } else { ?>
                                        <textarea id="catatan_prestasi" name="catatan_prestasi" 
                                        placeholder="" 
                                        data-validation="" 
                                        data-validation-error-msg="Catatan verifikasi data prestasi"
                                        style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" disabled></textarea>
                                    <?php } ?>
                                <?php } ?>							
                        </table>
                    </div>
                </div>
            </div>
</div>

<div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="glyphicon glyphicon-flag"></i>
                        <h3 class="box-title text-info"><b>Program Afirmasi</b></h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped">
                            <?php if ($row->punya_kip == 0 && $row->masuk_bdt == 0) { ?>
                                <td colspan=3><b>Tidak masuk program afirmasi</b></td>
                            <?php } else { 
                                    if ($row->punya_kip == 1) { ?>								
                            <tr>
                                <td style="width: 45%;"><b>Nomor Kartu Indonesia Pintar</b></td>
                                <td>:</td>
                                <td id="nomor_kip" data-editor-field="nomor_kip" style="width: 50%;"><?php echo $row->nomor_kip;?></td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                            </tr>
                            <?php } ?>

                            <?php if ($row->masuk_bdt == 1) { ?>
                                <tr>
                                <td style="width: 45%;"><b>Nomor Basis Data Terpadu</b></td>
                                <td>:</td>
                                <td id="nomor_bdt" data-editor-field="nomor_bdt" style="width: 50%;"><?php echo $row->nomor_bdt;?></td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                            </tr>
                            <?php }} ?>
                            <?php if ($row->punya_kip == 1 || $row->masuk_bdt == 1) { ?>
                            <tr>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;"><b>Dokumen Pendukung</b></td>
                            </tr>
                            <?php if ($row->punya_kip == 1) { ?>
                            <tr>
                                <td id="row-dokumen-kip" style="width: 45%;"><b>Kartu Indonesia Pintar</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <img id="dokumen-16" class="img-view-thumbnail" 
                                            src="<?php echo (empty($dokumen[16])) ? '' : $dokumen[16]['thumbnail_path']; ?>" 
                                            img-path="<?php echo (empty($dokumen[16])) ? '' : $dokumen[16]['web_path']; ?>" 
                                            img-title="Kartu Indonesia Pintar"/>  
                                    <?php if(!empty($dokumen[16])) { ?>
                                    <div>
                                    <a id='label-dokumen-16' href="<?php echo $dokumen[16]['web_path']; ?>" target="_blank">
                                        <?php echo $dokumen[16]['filename'] ?>
                                    </a>
                                    </div>
                                    <?php } ?> 
                                </td>
                            </tr>
                            <?php } ?>
                            <?php if ($row->masuk_bdt == 1) { ?>
                            <tr>
                                <td id="row-dokumen-bdt" style="width: 45%;"><b>Kartu PKH / Kartu KJS / Surat Keterangan masuk BDT dari Desa/Kelurahan</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <img id="dokumen-20" class="img-view-thumbnail" 
                                            src="<?php echo (empty($dokumen[20])) ? '' : $dokumen[20]['thumbnail_path']; ?>" 
                                            img-path="<?php echo (empty($dokumen[20])) ? '' : $dokumen[20]['web_path']; ?>" 
                                            img-title="Surat Keterangan Basis Data Terpadu"/>  
                                    <?php if(!empty($dokumen[20])) { ?>
                                    <div>
                                    <a id='label-dokumen-20' href="<?php echo $dokumen[20]['web_path']; ?>" target="_blank">
                                        <?php echo $dokumen[20]['filename'] ?>
                                    </a>
                                    </div>
                                    <?php } ?> 
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="box-footer">
                        <input type="hidden" id="orig_verifikasi_afirmasi" name="orig_verifikasi_afirmasi" value="<?php echo $row->verifikasi_afirmasi; ?>">
                        <input type="hidden" id="orig_catatan_afirmasi" name="orig_catatan_afirmasi" value="<?php echo $row->catatan_afirmasi; ?>">
                        <?php if ($row->punya_kip == 0 && $row->masuk_bdt == 0) { ?>
                            <input type="hidden" id="verifikasi_afirmasi" name="verifikasi_afirmasi" value="1">
                            <input type="hidden" id="catatan_afirmasi" name="catatan_afirmasi" value="">
                        <?php } else { ?>
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label style="margin-right: 10px;">
                                    <input type="radio" id="verifikasi_afirmasi" name="verifikasi_afirmasi" class="flat-green"<?php if($row->verifikasi_afirmasi==1){?> checked <?php }?> value="1" data-validation="required" data-validation-error-msg="Verifikasi data afirmasi">
                                    SUDAH Benar
                                </label>
                                <label>
                                    <input type="radio" id="verifikasi_afirmasi" name="verifikasi_afirmasi" class="flat-red"<?php if($row->verifikasi_afirmasi==2){?> checked <?php }?> value="2" data-validation="required" data-validation-error-msg="Verifikasi data afirmasi">
                                    BELUM Benar
                                </label>
                            </div>
                            <?php if($row->verifikasi_afirmasi==2) { ?>
                                <textarea id="catatan_afirmasi" name="catatan_afirmasi" 
                                placeholder="Apabila belum benar, silahkan masukkan informasi yang perlu diperbaiki di sini." data-validation="required" 
                                data-validation-error-msg="Catatan verifikasi data afirmasi"
                                style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $row->catatan_afirmasi; ?></textarea>
                            <?php } else { ?>
                                <textarea id="catatan_afirmasi" name="catatan_afirmasi" 
                                placeholder="" 
                                data-validation="" 
                                data-validation-error-msg="Catatan verifikasi data afirmasi"
                                style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" disabled></textarea>
                            <?php } ?>
                        <?php } ?>								
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="glyphicon glyphicon-modal-window"></i>
                        <h3 class="box-title text-info"><b>Program Inklusi</b></h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped">
                            <tr>
                                <td style="width: 45%;"><b>Kebutuhan Khusus</b></td>
                                <td>:</td>
                                <td id="kebutuhan_khusus" data-editor-field="kebutuhan_khusus" style="width: 50%;"><?php echo $row->kebutuhan_khusus;?></td>
                            </tr>
                            <?php if($row->kebutuhan_khusus != 'Tidak ada') { ?>
                            <tr>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;"><b>Dokumen Pendukung</b></td>
                            </tr>
                            <tr>
                                <td id="row-dokumen-kebutuhan-khusus" style="width: 45%;"><b>Surat Keterangan Berkebutuhan Khusus</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <img id="dokumen-9" class="img-view-thumbnail" 
                                            src="<?php echo (empty($dokumen[9])) ? '' : $dokumen[9]['thumbnail_path']; ?>" 
                                            img-path="<?php echo (empty($dokumen[9])) ? '' : $dokumen[9]['web_path']; ?>" 
                                            img-title="Surat Keterangan Berkebutuhan Khusus"/>  
                                    <?php if(!empty($dokumen[9])) { ?>
                                    <div>
                                    <a id='label-dokumen-9' href="<?php echo $dokumen[9]['web_path']; ?>" target="_blank">
                                        <?php echo $dokumen[9]['filename'] ?>
                                    </a>
                                    </div>
                                    <?php } ?> 
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="box-footer">
                        <input type="hidden" id="orig_verifikasi_inklusi" name="orig_verifikasi_inklusi" value="<?php echo $row->verifikasi_inklusi; ?>">
                        <input type="hidden" id="orig_catatan_inklusi" name="orig_catatan_inklusi" value="<?php echo $row->catatan_inklusi; ?>">
                        <?php if ($row->asal_data == 0 || $row->kebutuhan_khusus == 'Tidak ada') { ?>
                            <input type="hidden" id="verifikasi_inklusi" name="verifikasi_inklusi" value="1">
                            <input type="hidden" id="catatan_inklusi" name="catatan_inklusi" value="">
                        <?php } else { ?>
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label style="margin-right: 10px;">
                                    <input type="radio" id="verifikasi_inklusi" name="verifikasi_inklusi" class="flat-green"<?php if($row->verifikasi_inklusi==1){?> checked <?php }?> value="1" data-validation="required" data-validation-error-msg="Verifikasi data kebutuhan khusus">
                                    SUDAH Benar
                                </label>
                                <label>
                                    <input type="radio" id="verifikasi_inklusi" name="verifikasi_inklusi" class="flat-red"<?php if($row->verifikasi_inklusi==2){?> checked <?php }?> value="2" data-validation="required" data-validation-error-msg="Verifikasi data kebutuhan khusus">
                                    BELUM Benar
                                </label>
                            </div>
                            <?php if($row->verifikasi_inklusi==2) { ?>
                                <textarea id="catatan_inklusi" name="catatan_inklusi" 
                                placeholder="Apabila belum benar, silahkan masukkan informasi yang perlu diperbaiki di sini." data-validation="required" 
                                data-validation-error-msg="Catatan verifikasi data kebutuhan khusus"
                                style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $row->catatan_inklusi; ?></textarea>
                            <?php } else { ?>
                                <textarea id="catatan_inklusi" name="catatan_inklusi" 
                                placeholder="" 
                                data-validation="" 
                                data-validation-error-msg="Catatan verifikasi data kebutuhan khusus"
                                style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" disabled></textarea>
                            <?php } ?>
                        <?php } ?>								
                    </div>
                </div>
            </div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	
			<div class="box box-solid">
				<div class="box-header with-border">
					<i class="glyphicon glyphicon-search text-info"></i>
					<h3 class="box-title text-info"><b>Verifikasi Dokumen Pendukung</b></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<table id="tdokumen" class="table table-striped" style="margin-bottom:0px !important;">
								<?php 
									foreach($dokumenpendukung->getResult() as $row2):
									//clean up di sini karena data kotor untuk ppdb2019
									if ($row->punya_prestasi==0 && $row2->daftar_kelengkapan_id==8) {
										continue;
									}

									if ($row->punya_nilai_un==0 && $row2->daftar_kelengkapan_id==3) {
										continue;
									}

									if ($row->punya_kip==0 && $row2->daftar_kelengkapan_id==16) {
										continue;
									}

									if ($row->masuk_bdt==0 && $row2->daftar_kelengkapan_id==20) {
										continue;
									}

									if ($row->kebutuhan_khusus=='Tidak ada' && $row2->daftar_kelengkapan_id==9) {
										continue;
									}
								?>
								<input type="hidden" id="orig_radio_<?php echo $row2->dokumen_id;?>" name="orig_radio_<?php echo $row2->dokumen_id;?>" value="<?php echo $row2->verifikasi; ?>">
								<input type="hidden" id="orig_catatan_<?php echo $row2->dokumen_id;?>" name="orig_catatan_<?php echo $row2->dokumen_id;?>" value="<?php echo $row2->catatan; ?>">
								<tr >
									<td>
										<div style="display: block; margin-bottom: 15px;"><b><?php echo $row2->nama; ?></b></div>
											<img id="dokumen-<?php echo $row2->dokumen_id; ?>" class="img-view-thumbnail" 
												src="<?php echo (empty($row2->thumbnail_path)) ? '' : $row2->thumbnail_path ?>"
												img-path="<?php echo $row2->web_path; ?>" 
												img-title="<?php echo $row2->nama; ?>"/> 
										<a href="<?php echo (empty($row2->web_path)) ? '' : $row2->web_path; ?>" target="_blank" class="btn btn-primary" style="margin-left: 10px;">
											Unduh
										</a>
									</td>
									<td style="width: 50%">
										<div class="form-group" style="margin-bottom: 10px;">
											<label style="margin-right: 10px;">
												<input type="radio" id="radio_<?php echo $row2->dokumen_id;?>" name="radio_<?php echo $row2->dokumen_id;?>" class="flat-green verifikasi-dokumen"<?php if($row2->verifikasi==1){?> checked <?php }?> value="1" data-validation="required" data-validation-error-msg="Verifikasi dokumen <?php echo $row2->nama; ?>">
												SUDAH Benar
											</label>
											<label>
												<input type="radio" id="radio_<?php echo $row2->dokumen_id;?>" name="radio_<?php echo $row2->dokumen_id;?>" class="flat-red verifikasi-dokumen"<?php if($row2->verifikasi==2){?> checked <?php }?> value="2" data-validation="required" data-validation-error-msg="Verifikasi dokumen <?php echo $row2->nama; ?>">
												BELUM Benar
											</label>
										</div>
										<?php if($row2->verifikasi==2) { ?>
											<textarea id="catatan_<?php echo $row2->dokumen_id;?>" name="catatan_<?php echo $row2->dokumen_id;?>" placeholder="Apabila belum benar, silahkan masukkan informasi yang perlu diperbaiki di sini." data-validation="required" 
											data-validation-error-msg="Catatan verifikasi dokumen <?php echo $row2->nama; ?>"
											style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $row2->catatan; ?></textarea>
										<?php } else { ?>
											<textarea id="catatan_<?php echo $row2->dokumen_id;?>" name="catatan_<?php echo $row2->dokumen_id;?>" placeholder="" data-validation="" 
											data-validation-error-msg="Catatan verifikasi dokumen <?php echo $row2->nama; ?>"
											style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" disabled></textarea>
										<?php } ?>
									</td>
								</tr>
								<?php endforeach; ?>
								<?php 
									if($suratpernyataan->num_rows() == 0) {
								?>
								<input type="hidden" id="orig_system_21" name="orig_system_21" value="">
								<input type="hidden" id="orig_catatan_21" name="orig_catatan_21" value="">
								<tr>
									<td>
										<div style="display: block; margin-bottom: 15px;"><b>Surat Pernyataan Kebenaran Dokumen</b></div>
									</td>
									<td style="width: 50%">
										<div class="form-group" style="margin-bottom: 10px;">
											<label style="margin-right: 10px;">
												<input type="radio" id="system_21" name="system_21" class="flat-green verifikasi-dokumen" value="1" data-validation="required" data-validation-error-msg="Verifikasi dokumen Surat Pernyataan" disabled>
												SUDAH Benar
											</label>
											<label>
												<input type="radio" id="system_21" name="system_21" class="flat-red verifikasi-dokumen" checked value="2" data-validation="required" data-validation-error-msg="Verifikasi dokumen Surat Pernyataan">
												BELUM Benar
											</label>
										</div>
										<textarea id="catatan_21" name="catatan_21" placeholder="" data-validation="" 
											data-validation-error-msg="Catatan verifikasi dokumen Surat Pernyataan"
											style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" readonly>BY SYSTEM: Surat Pernyataan Kebenaran Dokumen tidak ditemukan</textarea>
									</td>
								</tr>
								<?php } ?>

							</table>
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
					<i class="glyphicon glyphicon-search text-info"></i>
					<h3 class="box-title text-info"><b>Verifikasi Berkas Fisik</b></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
										<div class="form-group" style="margin-bottom: 10px;">
											<label style="margin-right: 10px;">
												<input type="radio" id="berkas_<?php echo $berkasfisik['dokumen_id'];?>" name="berkas_<?php echo $berkasfisik['dokumen_id'];?>" class="flat-green verifikasi-dokumen"<?php if($berkasfisik['berkas_fisik']==1){?> checked <?php }?> value="1" data-validation="required" data-validation-error-msg="Verifikasi berkas fisik <?php echo $berkasfisik['nama']; ?>">
												SUDAH Diterima
											</label>
											<label>
												<input type="radio" id="berkas_<?php echo $berkasfisik['dokumen_id'];?>" name="berkas_<?php echo $berkasfisik['dokumen_id'];?>" class="flat-red verifikasi-dokumen"<?php if($berkasfisik['berkas_fisik']==0){?> checked <?php }?> value="0" data-validation="required" data-validation-error-msg="Verifikasi berkas fisik <?php echo $berkasfisik['nama']; ?>">
												BELUM Diterima
											</label>
										</div>
										<?php if($berkasfisik['berkas_fisik']==1) { ?>
											<div id="catatan_berkas_<?php echo $berkasfisik['dokumen_id'];?>"><?php echo "Diterima oleh " .$berkasfisik['penerima_berkas']. " di " .$berkasfisik['sekolah']. " tanggal " .$berkasfisik['tanggal_berkas']; ?></div>
										<?php } ?>
									</td>
								</tr>
								<?php endforeach; ?>
							</table>
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
									<td colspan="1">Untuk komunikasi dan klarifikasi, hubungi nomor berikut: <span style="font-size: 25px;"><b><?php echo $row->nomor_kontak; ?></b></span>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		
			<div class="box box-solid">
				<div class="box-header with-border">
					<i class="glyphicon glyphicon-check"></i>
					<h3 class="box-title text-info"><b>Simpan Status Verifikasi</b></h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<table class="table" style="margin-bottom:0px !important;">
								<tr >
									<td>Note: Apabila anda melakukan perubahan status verifikasi berkas, maka sekolah anda akan ditandai sebagai lokasi berkas dari peserta didik tersebut.</td>
								</tr>
							</table>
						</div>
					</div>
				</div>

				<div class="box-footer">
					<!-- <button type="submit" class="btn btn-primary btn-flat" <?php if(date('Y-m-d H:i:s:u')<$tanggal_mulai_aktif||date('Y-m-d H:i:s:u')>$tanggal_selesai_aktif){?> disabled="true" <?php }?>>Simpan Verifikasi</button> -->
					<button type="submit" class="btn btn-primary btn-flat">Simpan Verifikasi</button>
					<a href="#" onclick="event.stopPropagation(); batal_perubahan();" class="btn btn-info btn-flat pull-right">Batalkan Perubahan</a>
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

</form>

<?php endforeach;?>

<div id="img-view-modal" class="modal fade" role="dialog">
	<div class="container">
	<div class="modal-dialog img-view-dialog">
		<div class="modal-content">
			<div class="modal-header bg-red">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><span id="img-view-title">Akte Kelahiran</span></h4>
			</div>
			<div class="modal-body">
				<div class="row" id="img-view-container">
					<img id="img-view-contain" class="img-view-contain" id="thumb" src="">
				</div>
				<div style="display: inline; position: absolute; left: 15px; top: 30px;">
					<button type="button" class="btn btn-sm" onclick="event.stopPropagation(); image_rotate(90); return false;"><i class="glyphicon glyphicon-repeat icon-flipped"></i></button>
					<button type="button" class="btn btn-sm" onclick="event.stopPropagation(); image_rotate(-90); return false;"><i class="glyphicon glyphicon-repeat"></i></button>
				</div>				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
	</div>
</div>

<?php if (!empty($nomor_kontak)) { ?>
<div class="bottom-right-menu" >
	<ul id='menu-list' class="menu-list">
		<li><a id='menu-item-batal' href="#" onclick="event.stopPropagation(); batal_perubahan(); return false;" class="btn btn-info" style="margin-bottom: 10px;">
				<h5 style="margin: 5px;"><b>Batalkan Perubahan</b></h5>
			</a>
		</li>
		<li><a id='menu-item-hapus' href="#" class="btn bg-blue pull-right" style="margin-bottom: 10px;" onclick="submit_form(); return false;"><h5 style="margin: 5px;"><b>Simpan Verifikasi</b></h4></a>
		</li>
    </ul>
      <div id="context-menu" class="nomer-kontak" aria-label="Quick context menu" style="cursor: auto;">
	  <h5 style="margin: 5px;"><b>Nomor Kontak : <?php echo $nomor_kontak; ?></b></h3>
      </div>
	<!-- <div class="box box-solid bg-blue">
		<div class="box-body">
			<h4 style="margin: 5px;"><b>Nomor Kontak : <?php echo $nomor_kontak; ?></b></h3>
		</div>
	</div> -->
</div>
<?php } ?>

<script src="<?php echo base_url();?>assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>
<script src="<?php echo base_url();?>assets/leaflet/leaflet.js"></script>
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>
<script>
	//Flat red color scheme for iCheck
	$('input[type="checkbox"].flat-green, input[type="radio"].flat-green').iCheck({
		radioClass: 'iradio_flat-green'
	});
	$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
		radioClass: 'iradio_flat-red'
	});

	$('input[type="checkbox"], input[type="radio"]').on('ifChecked', function (event){
		$(this).closest("input").attr('checked', true);          
	});

	$('input[type="checkbox"], input[type="radio"]').on('ifUnchecked', function (event) {
		$(this).closest("input").attr('checked', false);
	});

	//Validasi
	var myLanguage = {
        errorTitle: 'Gagal mengirim data. Belum mengisi semua data wajib:',
        requiredFields: 'Belum mengisi semua data wajib',
    };
	
	var $messages = $('#error-message-wrapper');
	$.validate({
		language : myLanguage,
		ignore: [],
		modules: 'security',
		errorMessagePosition: "top",
		scrollToTopOnError: true,
		validateHiddenInputs: true
	});

	//Peta
	<?php foreach($profilsiswa->getResult() as $row):?>
	var map = L.map('peta',{zoomControl:false}).setView([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>],16);
	L.tileLayer(
		'<?php echo $servis_peta_aktif;?>',{maxZoom: 18,attribution: 'PPDB <?php echo $wilayah_aktif;?>',id: 'mapbox.streets'}
	).addTo(map);

	var alamat = "";
	<?php if (!empty($row->padukuhan)) { ?>
		alamat = "Dukuh <?php echo $row->padukuhan;?>, Desa <?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?>"
	<?php } else { ?>
		alamat = "Desa <?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?>"
	<?php } ?>
	L.marker([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>]).addTo(map).bindPopup(alamat).openPopup();

	new L.control.fullscreen({position:'bottomleft'}).addTo(map);
	new L.Control.Zoom({position:'bottomright'}).addTo(map);
	var streetmap   = L.tileLayer('<?php echo $streetmap_aktif;?>', {id: 'mapbox.light', attribution: ''}),
		satelitemap  = L.tileLayer('<?php echo $satelitemap_aktif;?>', {id: 'mapbox.streets',   attribution: ''});
	var baseLayers = {
		"Satelite": satelitemap,
		"Streets": streetmap
	};
	var overlays = {};
	L.control.layers(baseLayers,overlays).addTo(map);
	<?php endforeach;?>
	
</script>

<script src="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">

    var img_rotation = 0;
    function show_img_view(title, src) {
        $("#img-view-title").html(title);
        $("#img-view-contain").attr("src", src);

        $('#img-view-modal').modal('show');
        img_rotate = 0;
    }

    function submit_form() {
        $("#prosesverifikasiberkas").submit();
    }

    $(document).ready(function() {

        $(".img-view-thumbnail").on('click', function(e) {
            let img_title = $(this).attr('img-title');
            let img_path = $(this).attr('img-path');
            if (typeof img_path === "undefined" || img_path == "") {
                return false;
            }

            show_img_view(img_title, img_path);
        });

        $('#tprestasi').on('click', 'tbody img.img-view-thumbnail', function (e) {
            let img_title = $(this).attr('img-title');
            let img_path = $(this).attr('img-path');
            if (typeof img_path === "undefined" || img_path == "") {
                return false;
            }

            //e.stopPropagation();

            show_img_view(img_title, img_path);
        });

        $.extend( $.fn.dataTable.defaults, { responsive: true } );

        $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
            $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
        } );
    
        $('#triwayat').DataTable({
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
            ajax: "<?php echo site_url('sekolah/verifikasi/riwayat'); ?>?peserta_didik_id=<?php echo $peserta_didik_id; ?>",
            columns: [
                { data: "created_on", className: 'dt-body-center readonly-column', orderable: false },
                { data: "nama", className: 'dt-body-left readonly-column', orderable: false },
                { data: "verifikasi", className: 'dt-body-center', orderable: false, 
                    "render": function (val, type, row) {
                            return val == 1 ? "SUDAH Lengkap" : "BELUM Lengkap";
                        } 
                },
                { data: "catatan_kekurangan", className: 'dt-body-left readonly-column', width: "50%", orderable: false,
                    "render": function (val, type, row) {
                            var decodedText = $("<p/>").html(val).text(); 
                            return decodedText.replace(/:/g, " : ").replace(/;/g, "<br>");
                        } 
                },
            ],
            order: [ 0, 'desc' ],
        });

        $('input').on('ifChecked', function(event){
            id = event.target.id;
            dok_id = "";
            if (id.substr(0, 6) == "radio_") {
                dok_id = id.replace("radio_", "");
            }
            else if (id.substr(0, 11) == "verifikasi_") {
                dok_id = id.replace("verifikasi_", "");
            }
            else {
                return;
            }

            catatan = $("#catatan_" + dok_id);
            orig_catatan = $("#orig_catatan_" + dok_id);

            val = event.target.value;
            if (val == 2) {
                catatan.attr("data-validation", "required");
                catatan.prop("disabled", false);
                catatan.attr("placeholder", "Apabila belum benar, silahkan masukkan informasi yang perlu diperbaiki di sini.");
                catatan.val(orig_catatan.val());
            } else if (val == 3) {

                $.confirm({
                    title: 'Klarifikasi Dinas',
                    content: ''
                        + 'Sebelum meminta bantuan dinas untuk melakukan klarifikasi, pastikan anda sudah menghubungi wali/siswa dan meminta klarifikasi yang diperlukan. ' 
                        + '<br /><br /><p class="text-danger"><b>Pastikan juga anda telah melakukan usaha maksimal untuk melakukan verifikasi!</b></p>'
                        + '<input type="text" placeholder="Ketik kata SUDAH di sini" class="name form-control" required />',
                    buttons: {
                        confirm: {
                            text: 'OK',
                            btnClass: 'btn-danger',
                            action: function(){
                                var name = this.$content.find('.name').val();
                                if(name !== 'SUDAH'){
                                    return false;
                                }
                                catatan.attr("data-validation", "required");
                                catatan.prop("disabled", false);
                                catatan.attr("placeholder", "Beri catatan kenapa memerlukan bantuan dinas untuk melakukan verifikasi.");
                                catatan.val(orig_catatan.val());
                            }
                        },
                        cancel: {
                            text: 'Batal',
                            btnClass: 'btn-info',
                            action: function(){
                                //cancel
                                $(':radio[name=' +id+ '][value=2]').iCheck('check');
                                catatan.attr("data-validation", "required");
                                catatan.prop("disabled", false);
                                catatan.attr("placeholder", "Apabila belum benar, silahkan masukkan informasi yang perlu diperbaiki di sini.");
                                catatan.val(orig_catatan.val());
                            }
                        },
                    }
                });
            } else {
                catatan.attr("data-validation", "");
                catatan.prop("disabled", true);
                catatan.attr("placeholder", "");
                catatan.val("");
            }
        });

        $('#tprestasi').DataTable({
            "responsive": true,
            "paging": false,
            "dom": "Brt",
            select: true,
            buttons: [
            ],
            ajax: "<?php echo site_url('sekolah/verifikasi/prestasi') . '?peserta_didik_id=' . $peserta_didik_id; ?>",
            "language": {
                "processing":   "Sedang proses...",
                "lengthMenu":   "Tampilan _MENU_ entri",
                "zeroRecords":  "Tidak ditemukan data yang sesuai",
                "loadingRecords": "Loading...",
                "emptyTable":   "Tidak ditemukan data yang sesuai",
                },
            columns: [
                // { "defaultContent": "" },
                { data: "prestasi", className: 'dt-body-left', "orderable": false },
                { data: "dokumen_pendukung", className: 'dt-body-left editable', "orderable": false, 'width': '50%',
                    render: function ( file_id, type, row ) {
                            if (type === 'display') {
                                let str = '<div style="margin-bottom: 5px;">' +row.uraian+ '</div>';
                                
                                str += '<img class="img-view-thumbnail" src="'+row.thumbnail_path+'" img-title="'+row.uraian+'" img-path="'+row.web_path+'"/>';

                                return str;
                            }
                            else {
                                return row.filename;
                            }
                        },
                    defaultContent: "Tidak ada dokumen",
                    title: "Dokumen Pendukung"
                },
            ],
            order: [ 0, 'asc' ],
        });

    });

    function show_klarifikasi_dinas(msg) {
        $.alert({
            title: 'Catatan Dinas',
            content: msg,
        });
    }

    function image_rotate(degree) {
		img_rotation += degree; 
		if ((img_rotation % 360) == 0) { 
			// 360 means rotate back to 0
			img_rotation = 0;
		}

		// var w=$("#img-view-contain").height();
		// var h=$("#img-view-contain").width();

		// $('#img-view-container').css("width",""+h+"px");
		// $('#img-view-container').css("height",""+w+"px");

		// $('#img-view-contain').css({"transform": 'rotate(${img_rotation}deg)'});

      	document.querySelector("#img-view-contain").style.transform = `rotate(${img_rotation}deg)`;
    }

</script>