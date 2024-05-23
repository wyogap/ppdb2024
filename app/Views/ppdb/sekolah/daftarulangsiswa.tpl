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
	<?php
		foreach($settingdaftarulang->getResult() as $row):
			$tanggal_mulai_aktif = $row->tanggal_mulai_aktif;
			$tanggal_selesai_aktif = $row->tanggal_selesai_aktif;
		endforeach;

		$pendaftaran_id = $this->input->get("pendaftaran_id");
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

<?php 

	$nomor_kontak = "";
	$peserta_didik_id = ""; 
	$kelengkapan_berkas = 0;

	foreach($profilsiswa->getResult() as $row):
		$nomor_kontak = $row->nomor_kontak;
		$peserta_didik_id = $row->peserta_didik_id;
		//$kelengkapan_berkas = $row->kelengkapan_berkas;

?>

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
								<td style="width:45%;"><b>Sekolah Asal</b></td>
								<td>:</td>
								<?php if($row->sekolah_id!=""){?>
									<td style="width:50%;">(<b><?php echo $row->npsn;?></b>) <a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/<?php echo $row->sekolah_id;?>" target="_blank"><?php echo $row->sekolah;?></a></td>
								<?php }else{?>
									<td style="width:50%;"><p>Tidak bersekolah sebelumnya.</p></td>
								<?php }?>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php endforeach ?>

<div class="row">
	<?php foreach($profilsiswa->getResult() as $row): ?>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="box box-solid">
					<div class="box-header with-border">
						<i class="glyphicon glyphicon-user"></i>
						<h3 class="box-title text-info"><b>Identitas Siswa</b></h3>
					</div>
					<div class="box-body">
					<table class="table table-striped">
						<tr>
							<td><b>NISN</b></td>
							<td>:</td>
							<td style="width: 50%;"><?php echo $row->nisn;?></td>
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
							<td><b>Tempat Lahir / Tanggal Lahir</b></td>
							<td>:</td>
							<td><?php echo $row->tempat_lahir;?>, <?php echo tanggal_indo($row->tanggal_lahir);?></td>
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
	<?php endforeach; ?>

	<?php foreach($pendaftaran->getResult() as $row): 
	?>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="box box-solid">
					<div class="box-header with-border">
						<i class="glyphicon glyphicon-registration-mark"></i>
						<h3 class="box-title text-info"><b>Pendaftaran</b></h3>
					</div>
					<div class="box-body">
					<table class="table table-striped">
						<tr>
							<td><b>Jenis Pilihan</b></td>
							<td>:</td>
							<td style="width: 50%;">Pilihan <?php echo $row->jenis_pilihan;?></td>
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
							<?php echo $row->peringkat_final;?>
							<span class="pull-right"><a href="<?php echo base_url();?>index.php/Chome/peringkat?sekolah_id=<?php echo $row->sekolah_id;?>" target="_blank"><i class="glyphicon glyphicon-search"></i> Lihat Peringkat</a></span></td>
						</tr>
						<tr>
							<td><b>Status Pendaftaran</b></td>
							<td>:</td>
							<td>
							<?php if($row->status_penerimaan_final==0){?>Dalam Proses Seleksi
							<?php }else if($row->status_penerimaan_final==1){?>Diterima
							<?php }else if($row->status_penerimaan_final==2){?>Tidak Diterima
							<!-- Pada saat daftar ulang Daftar Tunggu == Masuk Kuota !-->
							<?php }else if($row->status_penerimaan_final==3){?>Diterima
							<?php }else if($row->status_penerimaan_final==4){?>Diterima Pilihan <?php echo $row->masuk_jenis_pilihan ?>
							<?php }else {?>Tidak Diterima
							<?php }?>
							</td>
						</tr>
					</table>
					</div>
				</div>
			</div>
	<?php endforeach; ?>
</div>

<?php if ($status_daftar_ulang == 1) { ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="box box-solid">
				<div class="box-header with-border">
					<i class="glyphicon glyphicon-print"></i>
					<h3 class="box-title text-info"><b>Bukti Pendaftaran</b></h3>
				</div>
				<div class="box-body">
					<p>Sudah melakukan daftar ulang pada tanggal <?php echo $tanggal_daftar_ulang; ?>.</p>
				</div>
				<div class="box-footer">
					<a href="<?php echo base_url();?>index.php/sekolah/daftarulang/buktipendaftaran?peserta_didik_id=<?php echo $peserta_didik_id; ?>" class="btn bg-orange" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak Bukti Pendaftaran</a>
				</div>
			</div>
	</div>
</div>
<?php } else { ?>
	<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="box box-solid">
				<div class="box-header with-border">
					<i class="glyphicon glyphicon-print"></i>
					<h3 class="box-title text-info"><b>Bukti Pendaftaran</b></h3>
				</div>
				<div class="box-body">
					<p>Dokumen daftar ulang belum lengkap. Anda hanya bisa mencetak Bukti Pendaftaran apabila dokumen daftar ulang sudah lengkap.</p>
				</div>
				<div class="box-footer">
					<span class="btn bg-orange" disabled><i class="glyphicon glyphicon-print"></i> Cetak Bukti Pendaftaran</span>
				</div>
			</div>
	</div>
</div>
<?php } ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-body">
				<div class="row">		
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<table class="table" style="margin-bottom:5px !important;">
						<tr>
							<td style="width:45%; padding-top:0px !important; padding-bottom:0px !important;">
								<h4 style="margin-top:0px !important; margin-bottom:0px !important;"><b>Lokasi Berkas</b></h4></td>
							<td style="padding-top:0px !important; padding-bottom:0px !important;">
								<h4 style="margin-top:0px !important; margin-bottom:0px !important;">:</h4></td>
							<td class="px-0" style="width:50%; padding-top:0px !important; padding-bottom:0px !important;">
								<h4 style="margin-top:0px !important; margin-bottom:0px !important;"><?php echo $lokasi_berkas; ?></h4>
							</td>
						</tr>
					</table>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>

<form role="form" enctype="multipart/form-data" id="prosesverifikasiberkas" action="<?php echo base_url();?>index.php/sekolah/daftarulang/prosesdaftarulang" method="post">
    <input type="hidden" id="peserta_didik_id" name="peserta_didik_id" value="<?php echo $peserta_didik_id;?>">
    <input type="hidden" id="pendaftaran_id" name="pendaftaran_id" value="<?php echo $pendaftaran_id;?>">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="glyphicon glyphicon-file text-info"></i>
                        <h3 class="box-title text-info"><b>Dokumen Pendukung Sebagai Berkas</b></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table id="tdokumen" class="table table-striped" style="margin-bottom:0px !important;">
                                    <?php foreach($dokumenpendukung->getResult() as $row2):
                                        if($row2->verifikasi==3) {
                                            continue;
                                        }

                                        //clean up di sini karena data kotor untuk ppdb2019
                                        if ($punya_prestasi==0 && $row2->daftar_kelengkapan_id==8) {
                                            continue;
                                        }

                                        if ($punya_nilai_un==0 && $row2->daftar_kelengkapan_id==3) {
                                            continue;
                                        }

                                        if ($punya_kip==0 && $row2->daftar_kelengkapan_id==16) {
                                            continue;
                                        }

                                        if ($masuk_bdt==0 && $row2->daftar_kelengkapan_id==20) {
                                            continue;
                                        }

                                        if ($kebutuhan_khusus=='Tidak ada' && $row2->daftar_kelengkapan_id==9) {
                                            continue;
                                        }
                                    ?>
                                    <input type="hidden" id="orig_radio_<?php echo $row2->dokumen_id;?>" name="orig_radio_<?php echo $row2->dokumen_id;?>" value="<?php echo $row2->berkas_fisik; ?>">
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
                                            <div style="display: block; margin-bottom: 15px;">
                                                <?php if($row2->dokumen_fisik==1){?>Asli
                                                <?php }else if($row2->dokumen_fisik==2){?>Fotokopi Dilegalisir (Dokumen asli dibawa untuk dicocokkan)</i>
                                                <?php }else{?>Fotokopi (Dokumen asli dibawa untuk dicocokkan)<?php }?>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 10px;">
                                                <label style="margin-right: 10px;">
                                                    <input type="radio" id="radio_<?php echo $row2->dokumen_id;?>" name="radio_<?php echo $row2->dokumen_id;?>" class="flat-green verifikasi-dokumen"<?php if($row2->berkas_fisik==1){?> checked <?php }?> value="1" data-validation="required" data-validation-error-msg="Berkas fisik dokumen <?php echo $row2->nama; ?>">
                                                    Ada
                                                </label>
                                                <label>
                                                    <input type="radio" id="radio_<?php echo $row2->dokumen_id;?>" name="radio_<?php echo $row2->dokumen_id;?>" class="flat-red verifikasi-dokumen"<?php if($row2->berkas_fisik==2){?> checked <?php }?> value="2" data-validation="required" data-validation-error-msg="Berkas fisik dokumen <?php echo $row2->nama; ?>">
                                                    Tidak Ada
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-flat">Simpan Daftar Ulang</button>
                    </div>
                </div>
        </div>
    </div>
</form>

<div id="img-view-modal" class="modal fade" role="dialog">
	<div class="container">
	<div class="modal-dialog img-view-dialog">
		<div class="modal-content">
			<div class="modal-header bg-red">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><span id="img-view-title">Akte Kelahiran</span></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<img id="img-view-contain" class="img-view-contain" id="thumb" src="">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
	</div>
</div>

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
	
</script>

<script src="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">

    function show_img_view(title, src) {
            $("#img-view-title").html(title);
            $("#img-view-contain").attr("src", src);

            $('#img-view-modal').modal('show');
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

    });

</script>