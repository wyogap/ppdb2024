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

<?php
		// foreach($settingverifikasiberkas->getResult() as $row):
		// 	$tanggal_mulai_aktif = $klarifikasi['tanggal_mulai_aktif;
		// 	$tanggal_selesai_aktif = $klarifikasi['tanggal_selesai_aktif;
		// endforeach;

		$klarifikasi_id = $this->input->get("klarifikasi_id");

		$peserta_didik_id = $klarifikasi['peserta_didik_id'];
		$nomor_kontak = $klarifikasi['nomor_kontak'];
		$nisn = $klarifikasi['nisn'];
		$nama = $klarifikasi['nama'];
		//$kelengkapan_berkas = $klarifikasi['kelengkapan_berkas;

	?>

<!DOCTYPE html>
<html>
<?php view('head');?>
<link rel="stylesheet"
    href="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet"
    href="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">

<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/iCheck/all.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css" />
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css'
    rel='stylesheet' />

<body class="hold-transition skin-black layout-top-nav">
    <div class="wrapper">
        <?php view('header');?>
        <div class="content-wrapper">
            <div class="container">
                <section class="content-header">
                    <h1 class="text-white">
                        <i class="glyphicon glyphicon-ok"></i> Klarifikasi<small> Dinas</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li class="active"><a href="<?php echo site_url('admin/klarifikasidinas');?>"><i
                                    class="glyphicon glyphicon-remove"></i> Batal </a></li>
                    </ol>

                    <!-- <ol class="breadcrumb">
							<li class="active"><a href="#" onclick="window.history.back();"><i class="glyphicon glyphicon-remove"></i> Batal </a></li>
						</ol> -->
                </section>
                <section class="content">

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
                                    <h3 class="box-title">
                                        <b><?php if($klarifikasi['nisn']!=""){?>(<?php echo $klarifikasi['nisn'];?>)
                                            <?php }?><?php echo $klarifikasi['nama'];?></b></h3>
                                    <span class="pull-right"><a
                                            href="<?php echo base_url();?>index.php/Chome/detailpendaftaran?peserta_didik_id=<?php echo $peserta_didik_id;?>"
                                            target="_blank" class="btn btn-primary btn-sm">Detail Pendaftaran</a></span>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <table class="table table-striped" style="margin-bottom:0px !important;">
                                                <tr>
                                                    <td><b>Sekolah Asal</b></td>
                                                    <td>:</td>
                                                    <?php if($klarifikasi['sekolah_id']!=""){?>
                                                    <td>(<b><?php echo $klarifikasi['npsn'];?></b>) <a
                                                            href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/<?php echo $klarifikasi['sekolah_id'];?>"
                                                            target="_blank"><?php echo $klarifikasi['sekolah'];?></a>
                                                    </td>
                                                    <?php }else{?>
                                                    <td>
                                                        <p>Tidak bersekolah sebelumnya.</p>
                                                    </td>
                                                    <?php }?>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form role="form" enctype="multipart/form-data" id="prosesklarifikasidinas"
                        action="<?php echo base_url();?>index.php/admin/klarifikasidinas/prosesklarifikasidinas"
                        method="post">
                        <input type="hidden" id="peserta_didik_id" name="peserta_didik_id"
                            value="<?php echo $peserta_didik_id;?>">
						<input type="hidden" id="klarifikasi_id" name="klarifikasi_id"
                            value="<?php echo $klarifikasi_id;?>">
						<input type="hidden" id="orig_catatan_dinas" name="orig_catatan_dinas"
                            value="<?php echo $klarifikasi['catatan_dinas'];?>">

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-user"></i>
                                        <h3 class="box-title text-info"><b>Identitas Siswa</b></h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <table class="table table-striped">
                                                    <tr>
                                                        <td><b>NIK</b></td>
                                                        <td>:</td>
                                                        <td><?php echo $klarifikasi['nik'];?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Nama</b></td>
                                                        <td>:</td>
                                                        <td><?php echo $klarifikasi['nama'];?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Jenis Kelamin</b></td>
                                                        <td>:</td>
                                                        <td><?php if($klarifikasi['jenis_kelamin']=="L"){echo "Laki-laki";}else{echo "Perempuan";}?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Tempat Lahir</b></td>
                                                        <td>:</td>
                                                        <td><?php echo $klarifikasi['tempat_lahir'];?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Tanggal Lahir</b></td>
                                                        <td>:</td>
                                                        <td><?php echo tanggal_indo($klarifikasi['tanggal_lahir']);?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Nama Ibu Kandung</b></td>
                                                        <td>:</td>
                                                        <td><?php echo $klarifikasi['nama_ibu_kandung'];?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Alamat</b></td>
                                                        <td>:</td>
                                                        <?php if (!empty($klarifikasi['padukuhan'])) { ?>
                                                        <td>Dukuh <?php echo $klarifikasi['padukuhan'];?>, <br>Desa
                                                            <?php echo $klarifikasi['desa_kelurahan'];?>,
                                                            <br><?php echo $klarifikasi['kecamatan'];?>,
                                                            <br><?php echo $klarifikasi['kabupaten'];?>,
                                                            <?php echo $klarifikasi['provinsi'];?></td>
                                                        <?php } else { ?>
                                                        <td>Desa <?php echo $klarifikasi['desa_kelurahan'];?>,
                                                            <br><?php echo $klarifikasi['kecamatan'];?>,
                                                            <br><?php echo $klarifikasi['kabupaten'];?>,
                                                            <?php echo $klarifikasi['provinsi'];?></td>
                                                        <?php } ?>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <table class="table table-striped">
                                                    <tr>
                                                        <td colspan="3"
                                                            style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;">
                                                            <b>Dokumen Pendukung</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 45%;"><b>Akte Kelahiran</b></td>
                                                        <td>:</td>
                                                        <td style="width: 50%;">
                                                            <img id="dokumen-5" class="img-view-thumbnail"
                                                                src="<?php echo (empty($dokumen[5])) ? '' : $dokumen[5]['thumbnail_path']; ?>"
                                                                img-path="<?php echo (empty($dokumen[5])) ? '' : $dokumen[5]['web_path']; ?>"
                                                                img-title="Akte Kelahiran" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Kartu Keluarga</b></td>
                                                        <td>:</td>
                                                        <td>
                                                            <img id="dokumen-6" class="img-view-thumbnail"
                                                                src="<?php echo (empty($dokumen[6])) ? '' : $dokumen[6]['thumbnail_path']; ?>"
                                                                img-path="<?php echo (empty($dokumen[6])) ? '' : $dokumen[6]['web_path']; ?>"
                                                                img-title="Kartu Keluarga" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($klarifikasi['tipe_data'] == 'lokasi') { ?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-map-marker"></i>
                                        <h3 class="box-title text-info"><b>Lokasi Rumah</b></h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div id="peta" style="width: 100%; height: 300px;"></div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <table class="table table-striped">
                                                    <tr>
                                                        <td colspan="3"
                                                            style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;">
                                                            <b>Dokumen Pendukung</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Surat Domisili (Apabila lokasi tempat tinggal berbeda
                                                                dengan alamat pada Kartu Keluarga)</b></td>
                                                        <td>:</td>
                                                        <td style="width: 50%;">
                                                            <?php if (empty($dokumen[19])) { ?>
                                                            <div>Tidak Ada</div>
                                                            <?php } else { ?>
                                                            <img id="dokumen-19" class="img-view-thumbnail"
                                                                src="<?php echo $dokumen[19]['thumbnail_path']; ?>"
                                                                img-path="<?php echo $dokumen[19]['web_path']; ?>"
                                                                img-title="Surat Keterangan Domisili" />
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
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
                                            <i class="glyphicon glyphicon-question-sign"></i>
                                            <h3 class="box-title text-info"><b>Catatan Sekolah</b></h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <textarea id="catatan_sekolah" name="catatan_sekolah"
                                                        style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                                                        disabled><?php echo $klarifikasi['catatan_sekolah']; ?></textarea>
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
                                            <h3 class="box-title text-info"><b>Klarifikasi Dinas</b></h3>
                                        </div>
                                        <div class="box-body">
                                            <textarea id="catatan_dinas" name="catatan_dinas"
                                                style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                                                placeholder="Tulis catatan/klarifikasi dari dinas di sini."><?php echo $klarifikasi['catatan_dinas']; ?></textarea>
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
                                                    <table class="table table-striped"
                                                        style="margin-bottom: 0px !important;">
                                                        <tr>
                                                            <td colspan="1">Untuk komunikasi dan klarifikasi, hubungi
                                                                nomor berikut: <span
                                                                    style="font-size: 25px;"><b><?php echo $klarifikasi['nomor_kontak']; ?></b></span>
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
                                            <i class="glyphicon glyphicon-floppy-saved"></i>
                                            <h3 class="box-title text-info"><b>Simpan Klarifikasi</b></h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <table class="table" style="margin-bottom:0px !important;">
                                                        <tr>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-primary btn-flat">Simpan
                                                Klarifikasi</button>
                                            <a href="<?php echo site_url('admin/klarifikasidinas');?>"
                                                class="btn btn-info btn-flat pull-right">Batalkan Perubahan</a>
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
                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($nomor_kontak)) { ?>
                    <div class="bottom-right-menu">
                       	<ul id='menu-list' class="menu-list">
					   		<li><a id='menu-item-batal' href="<?php echo site_url('admin/klarifikasidinas');?>" class="btn btn-info" style="margin-bottom: 10px;">
                                    <h5 style="margin: 5px;"><b>Batalkan Perubahan</b></h5>
                                </a>
							</li>
                            <li><a id='menu-item-hapus' href="#" class="btn bg-blue pull-right"
                                    style="margin-bottom: 10px;" onclick="submit_form(); return false;">
                                    <h5 style="margin: 5px;"><b>Simpan Klarifikasi</b></h5>
                                </a>
							</li>
                        </ul>
                        <div id="context-menu" href="#" class="btn bg-blue" aria-label="Quick context menu"
                            style="cursor: auto;">
                            <h5 style="margin: 5px;"><b>Nomor Kontak : <?php echo $nomor_kontak; ?></b></h3>
                        </div>
                    </div>
                    <?php } ?>

                    <script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js">
                    </script>

                    <script src="<?php echo base_url();?>assets/leaflet/leaflet.js"></script>
                    <script
                        src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'>
                    </script>

                <script>
                    //Validasi
                    var myLanguage = {
                        errorTitle: 'Gagal mengirim data. Belum mengisi semua data wajib:',
                        requiredFields: 'Belum mengisi semua data wajib',
                    };

                    var $messages = $('#error-message-wrapper');
                    $.validate({
                        language: myLanguage,
                        ignore: [],
                        modules: 'security',
                        errorMessagePosition: "top",
                        scrollToTopOnError: true,
                        validateHiddenInputs: true
                    });
				</script>

				<?php if ($klarifikasi['tipe_data'] == 'lokasi') { ?>
				<script>
                    //Peta
                    var map = L.map('peta', {
                        zoomControl: false
                    }).setView([<?php echo $klarifikasi['lintang'];?>, <?php echo $klarifikasi['bujur'];?>], 16);
                    L.tileLayer(
                        '<?php echo $servis_peta_aktif;?>', {
                            maxZoom: 18,
                            attribution: 'PPDB <?php echo $wilayah_aktif;?>',
                            id: 'mapbox.streets'
                        }
                    ).addTo(map);

                    var alamat = "";
                    <?php if (!empty($klarifikasi['padukuhan'])) { ?>
                    alamat =
                        "Dukuh <?php echo $klarifikasi['padukuhan'];?>, Desa <?php echo $klarifikasi['desa_kelurahan'];?>, <?php echo $klarifikasi['kecamatan'];?>, <?php echo $klarifikasi['kabupaten'];?>, <?php echo $klarifikasi['provinsi'];?>"
                    <?php } else { ?>
                    alamat =
                        "Desa <?php echo $klarifikasi['desa_kelurahan'];?>, <?php echo $klarifikasi['kecamatan'];?>, <?php echo $klarifikasi['kabupaten'];?>, <?php echo $klarifikasi['provinsi'];?>"
                    <?php } ?>

                    L.marker([<?php echo $klarifikasi['lintang'];?>, <?php echo $klarifikasi['bujur'];?>]).addTo(map)
                        .bindPopup(alamat).openPopup();
                    new L.control.fullscreen({
                        position: 'bottomleft'
                    }).addTo(map);
                    new L.Control.Zoom({
                        position: 'bottomright'
                    }).addTo(map);
                    var streetmap = L.tileLayer('<?php echo $streetmap_aktif;?>', {
                            id: 'mapbox.light',
                            attribution: ''
                        }),
                        satelitemap = L.tileLayer('<?php echo $satelitemap_aktif;?>', {
                            id: 'mapbox.streets',
                            attribution: ''
                        });
                    var baseLayers = {
                        "Satelite": satelitemap,
                        "Streets": streetmap
                    };
                    var overlays = {};
                    L.control.layers(baseLayers, overlays).addTo(map);
                </script>
				<?php } ?>

                    <script
                        src="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js">
                    </script>
                    <script
                        src="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js">
                    </script>

                    <script type="text/javascript">
                    function show_img_view(title, src) {
                        $("#img-view-title").html(title);
                        $("#img-view-contain").attr("src", src);

                        $('#img-view-modal').modal('show');
                    }

                    function submit_form() {
                        $("#prosesklarifikasidinas").submit();
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

                        $('#tprestasi').on('click', 'tbody img.img-view-thumbnail', function(e) {
                            let img_title = $(this).attr('img-title');
                            let img_path = $(this).attr('img-path');
                            if (typeof img_path === "undefined" || img_path == "") {
                                return false;
                            }

                            //e.stopPropagation();

                            show_img_view(img_title, img_path);
                        });

                        $.extend($.fn.dataTable.defaults, {
                            responsive: true
                        });

                        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                            $.fn.dataTable.tables({
                                visible: true,
                                api: true
                            }).columns.adjust().responsive.recalc();
                        });

                     });
                    </script>
                </section>
            </div>
        </div>
        <?php view('footer');?>
    </div>
</body>

</html>