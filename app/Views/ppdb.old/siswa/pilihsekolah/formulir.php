<?php 
	$kode_zona = "";
	$lintang_rumah = 0;
	$bujur_rumah = 0;
	foreach($profilsiswa->getResult() as $row) {
		$kode_zona = $row->kode_kecamatan;
		$lintang_rumah = $row->lintang;
		$bujur_rumah = $row->bujur;
	}

	$jalurid_dalam_zonasi = 0;
	$namajalur_dalam_zonasi = "";
	$zona_eksklusi = array();

	if ($satu_zonasi_satu_jalur == 1) {
		foreach($pendaftaran_dalam_zonasi->getResult() as $row) {
			$jalurid_dalam_zonasi = $row->jalur_id;
			$namajalur_dalam_zonasi = $row->jalur;
		}
	
		foreach($pendaftaran_dalam_zonasi->getResult() as $row) {
			if ($row->jalur_id != $jalurid) {
				array_push($zona_eksklusi,$row->kode_zona);
			}
		}
	
		foreach($pendaftaran_luar_zonasi->getResult() as $row) {
			if ($row->jalur_id != $jalurid) {
				array_push($zona_eksklusi,$row->kode_zona);
			}
		}
	}

?>


<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css"/>

<?php
	$error = $this->session->flashdata('error');
	if($error)
	{
?>
	<div class="alert alert-danger alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<?php echo $error; ?>                    
	</div>
<?php 
	}

	$success = $this->session->flashdata('success');
	if($success)
	{
?>
	<div class="alert alert-success alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<?php echo $success; ?>                    
	</div>
<?php } ?>

<?php if ($jalurswasta != 1 && $satu_zonasi_satu_jalur == 1) { ?>
	<div class="alert alert-danger alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<p><i class="icon glyphicon glyphicon-exclamation-sign"></i>Anda hanya bisa mendaftar menggunakan satu jalur pada satu zonasi. Mohon berhati-hati dalam menentukan jalur pendaftaran.</p>             
	</div>

	<?php foreach($pendaftaran_dalam_zonasi->getResult() as $row) { ?>
		<?php if ($jalurid != $row->jalur_id) { ?>
		<div class="alert alert-warning alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<p><i class="icon glyphicon glyphicon-info-sign"></i>Anda sudah mendaftar menggunakan jalur <?php echo $row->jalur; ?> di dalam zonasi anda. Anda hanya bisa mendaftar dengan jalur <?php echo $namajalur; ?> ke sekolah di luar jalur zonasi anda.</p>             
		</div>
		<?php } ?>
	<?php } ?>

	<?php foreach($pendaftaran_luar_zonasi->getResult() as $row) { ?>
		<?php if ($jalurid != $row->jalur_id) { ?>
		<div class="alert alert-info alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<p><i class="icon glyphicon glyphicon-info-sign"></i>Anda sudah mendaftar menggunakan jalur <?php echo $row->jalur; ?> di zonasi Kec. <?php echo $row->nama; ?>. Anda tidak bisa mendaftar dengan jalur <?php echo $namajalur; ?> ke sekolah di zonasi ini.</p>             
		</div>
		<?php } ?>
	<?php } ?>
<?php } ?>

<?php if ($daftarpendaftaran->num_rows()>0) { ?>
	<div class="alert alert-info alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<p>Anda sudah melakukan pendaftaran di sekolah berikut:
		<ul>
			<?php foreach($daftarpendaftaran->getResult() as $row): ?>
				<li><?php echo "($row->npsn) $row->sekolah"; ?> </li>
			<?php endforeach ?>
		</ul>
		</p>
		<p>Anda tidak bisa melakukan pendaftaran di sekolah yang sama.
	</div>

<?php } ?>


<!-- <div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_1" data-toggle="tab">Daftar Sekolah</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_1"> -->
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-map-marker"></i>
							<h3 class="box-title text-info"><b>Peta Sebaran Sekolah</b></h3>
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
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<form role="form" enctype="multipart/form-data" action="<?php echo base_url();?>index.php/siswa/pendaftaran/daftar" method="post">
					<input type="hidden" id="penerapan_id" name="penerapan_id" value="<?php echo $this->input->get("penerapan_id");?>" data-validation="required">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-book"></i>
							<h3 class="box-title text-info"><b>Pemilihan Sekolah</b></h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group has-feedback" style="margin-bottom: 0px;">
										<label for="jenis_pilihan">Urutan Pilihan</label>
										<select id="jenis_pilihan" name="jenis_pilihan" class="form-control select2" data-validation="required" data-validation-error-msg="Belum memilih urutan pilihan">
											<option value="">-- Silahkan Pilih Urutan --</option>
											<?php foreach($jenispilihan->getResult() as $row): ?>
											<option value="<?php echo $row->jenis_pilihan;?>"><?php echo $row->keterangan;?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
							<div class="row" style="margin-top: 8px;">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group has-feedback">
										<label for="sekolah_id">Daftar Sekolah</label>
										<select id="sekolah_id" name="sekolah_id" class="form-control select2" data-validation="required" data-validation-error-msg="Belum memilih sekolah">
											<option value="">-- Silahkan Pilih Sekolah --</option>
										</select>
									</div>
								</div>
							</div>
							<div id="detailsekolah" class="row" style="display: none;">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<table class="table table-striped">
										<tbody>
											<!-- <div id="detailsekolah"></div> -->
											<tr>
												<td style="width: 45%;">Alamat</td>
												<td>:</td>
												<td style="width: 50%;"><span id="alamat"></span></td>
											</tr>
											<tr>
												<td>Desa/Kelurahan</td>
												<td>:</td>
												<td style="width: 50%;"><span id="desa"></span></td>
											</tr>
											<tr>
												<td>Kecamatan</td>
												<td>:</td>
												<td style="width: 50%;"><span id="kecamatan"></span></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<?php if($dokumentambahan->num_rows() > 0) {?>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<b>Dokumen Pendukung Tambahan</b>
									<table class="table table-striped">
										<tbody>
											<?php foreach($dokumentambahan->getResult() as $row): ?>
												<tr class="form-group has-feedback">
													<td style="width: 45%;">
													<label class="control-label" for="dokumen_<?php echo $row->daftar_kelengkapan_id; ?>"><?php echo $row->nama; ?> <?php if($row->wajib==0) { echo "(Jika Ada)"; } ?></label></td>
													<td>:</td>
													<td style="width: 50%;">
														<img id="dokumen-<?php echo $row->daftar_kelengkapan_id; ?>" class="img-view-thumbnail" 
															src="" 
															img-path="" 
															img-title="<?php echo $row->nama; ?>"
															style="display:none; "/>  
														<button type="button" id="unggah-dokumen-<?php echo $row->daftar_kelengkapan_id; ?>" class="img-view-button editable" data-editor-field="dokumen_<?php echo $row->daftar_kelengkapan_id; ?>" data-editor-value="" >Unggah</button>
														<input 
															class="form-control" type="hidden" 
															id="dokumen_<?php echo $row->daftar_kelengkapan_id; ?>" 
															name="dokumen_<?php echo $row->daftar_kelengkapan_id; ?>" 
															<?php if($row->wajib==1) { echo "data-validation='required' data-validation-error-msg='Belum mengunggah $row->nama'"; } ?>
														>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
							<?php }?>
						</div>
						<div class="box-footer">
								<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> -->
									<button type="submit" class="btn btn-primary btn-flat" <?php if($this->session->userdata("tutup_akses")==1){?>disabled="true"<?php }?>>Pilih Sekolah</button>
								<!-- </div> -->
								<span id="error-message-wrapper"></span>
						</div>
					</div>
					</form>
				</div>
			</div>
		<!-- </div>
	</div>
</div> -->
<script src="<?php echo base_url();?>assets/leaflet/leaflet.js"></script>
<script>
	//Dropdown Select
	$(function () {
		$(".select2").select2();
	});

	//Event On Change Dropdown
	$(document).ready(function () {
		$('select[name="jenis_pilihan"]').on('change', function() {
			let jenis_pilihan = $("#jenis_pilihan").val();
			let penerapan_id = <?php echo $this->input->get("penerapan_id");?>;

			if (jenis_pilihan=='') {
					//update daftar sekolah
					update_daftar_sekolah($("#sekolah_id"), null, '-- Silahkan Pilih Sekolah --');
					$("#sekolah_id").val('');
					$('#sekolah_id option[value=""]').attr('selected','selected');
					//update daftar marker di map
					update_marker_sekolah(map, markers, null);
			}
			else {
				var data = {jenis_pilihan:jenis_pilihan,penerapan_id:penerapan_id};
				$.ajax({
					type: "POST",
					url : "<?php echo site_url('siswa/pendaftaran/daftarsekolah')?>",
					data: data,
					dataType: 'json',
					success: function(json, textStatus, jQxhr) {
						//update daftar sekolah
						update_daftar_sekolah($("#sekolah_id"), json, '-- Silahkan Pilih Sekolah --');
						//update daftar marker di map
						update_marker_sekolah(map, markers, json);
					},
					error: function( jqXhr, textStatus, errorThrown ){
						//update daftar sekolah
						update_daftar_sekolah($("#sekolah_id"), null, '-- Silahkan Pilih Sekolah --');
						//update daftar marker di map
						update_marker_sekolah(map, markers, null);
					}
				});
			}
		});

		$('select[name="sekolah_id"]').on('change', function() {
			let val = $("#sekolah_id").val();

			if ($("#sekolah_id").val() == "") {
				$('#detailsekolah').hide();
			}
			else {
				var data = {sekolah_id:$("#sekolah_id").val(),penerapan_id:<?php echo $this->input->get("penerapan_id");?>};
				$.ajax({
					type: "POST",
					url : "<?php echo site_url('siswa/pendaftaran/detailsekolah')?>",
					data: data,
					dataType: 'json',
					success: function(json, textStatus, jQxhr) {

						if (typeof json.data === 'undefined') {
							$('#detailsekolah').hide();
						}
						else {
							$('#alamat').html(json.data.alamat);
							$('#desa').html(json.data.desa);
							$('#kecamatan').html(json.data.kecamatan);
							$('#detailsekolah').show();
						}

						// if (typedef data !== "undefined" && data != null && typedef data.error === "undefined" && typedef data.data !== "undefined" && data.data != null) {
						// 	$('#alamat').html(json.data.alamat);
						// 	$('#desa').html(json.data.desa);
						// 	$('#kecamatan').html(json.data.kecamatan);
						// 	$('#detailsekolah').show();
						// } else {
						// 	$('#detailsekolah').hide();
						// }
					},
					error: function( jqXhr, textStatus, errorThrown ){
						$('#detailsekolah').hide();
					}
				});
			}
		});
	});

	//Peta
	var map;

	var greenMarker = new L.Icon({
		iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
		shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
		iconSize: [25, 41],
		iconAnchor: [12, 41],
		popupAnchor: [1, -34],
		shadowSize: [41, 41]
	});

	var lintang_rumah = <?php echo $lintang_rumah;?>;
	var bujur_rumah = <?php echo $bujur_rumah;?>;

	var lintang_aktif = <?php echo ($lintang_rumah!=0) ? $lintang_rumah : $lintang_aktif ;?>;
	var bujur_aktif = <?php echo ($bujur_rumah!=0) ? $bujur_rumah : $bujur_aktif ;?>;

	map = L.map('peta',{zoomControl:false}).setView([lintang_aktif,bujur_aktif],16);

	var streetview = L.tileLayer(
		'<?php echo $servis_peta_aktif;?>',{maxZoom: 18,attribution: 'PPDB <?php echo $wilayah_aktif;?>',id: 'mapbox.streets'}
	);
	map.addLayer(streetview);

	var rumahsiswa = null;
	if (lintang_rumah!=0 && bujur_rumah!=0) {
		rumahsiswa = L.marker([lintang_rumah,bujur_rumah], {icon: greenMarker}).bindPopup("Lokasi Rumah").openPopup();
		map.addLayer(rumahsiswa);
	}

	new L.Control.Zoom({position:'bottomright'}).addTo(map);
	
	var markers = L.layerGroup();
	map.addLayer(markers);
	
	// markers.clearLayers();
	// map.eachLayer(function (layer) {
    //     map.removeLayer(layer);
    // });

	function update_daftar_sekolah (select, daftarsekolah, label) {
		//store current value
		let _value = select.val();

		//rebuild the option list
		select.empty();

		let _option = $("<option>").val('').text(label);
		//selected
		if (typeof _value === 'undefined' || _value == null || _value == '') {
			_option.attr("selected", true);
		}
		select.append(_option);

		if (daftarsekolah != null && Array.isArray(daftarsekolah)) {
			//add options one by one
			for (item of daftarsekolah) {
				if (typeof item === "undefined" || item == null ||
					typeof item.sekolah_id === "undefined" || item.sekolah_id == null ||
					typeof item.nama === "undefined" || item.nama == null) {
					return;
				}

				let label = '' + item.npsn + ' ' + item.nama;
				let jarak = parseFloat(item.jarak);
				if (isNaN(jarak)) {
					jarak = 100000;
				}

				if (item.jarak != '') {
					label += ' (' + (jarak/1000).toFixed(2) + 'Km)';
				}
				let value = item.sekolah_id;

				//let _option = $("<option>").val(sekolah_id).text(label);
				let _option = new Option(label, value, false, false);

				select.append(_option);
			};
		}

		//re-set the value
		select.val(_value);

		if (select.val() != _value) {
			select.val('').trigger('change');
		}

		return select;
	};

	function update_marker_sekolah(map, markers, daftarsekolah) {
		markers.clearLayers();


		if (daftarsekolah != null && Array.isArray(daftarsekolah)) {
			var bounds = [];

			bounds.push([lintang_rumah, bujur_rumah]);

			//add options one by one
			for (item of daftarsekolah) {
				if (typeof item.bujur === "undefined" || item.bujur == 0 ||
					typeof item.lintang === "undefined" || item.lintang == 0) {
					return;
				}

				var marker = L.marker([item.lintang,item.bujur]).bindPopup("(" + item.npsn + ") " + item.nama);
				markers.addLayer(marker);

				bounds.push([item.lintang,item.bujur]);
			};

			// daftarsekolah.forEach(function (item, index, arr) {
			// });

			map.fitBounds(bounds);
		}

	}

</script>

<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>
<script>
	//Validasi
	var myLanguage = {
        errorTitle: 'Gagal mengirim data!',
        requiredFields: 'Belum mengisi semua data wajib',
    };
	
	var $messages = $('#error-message-wrapper');
	$.validate({
		language : myLanguage,
		ignore: [],
		modules: 'security',
		errorMessagePosition: "top",
		scrollToTopOnError: false,
		validateHiddenInputs: true
	});
</script>

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

<script src="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js"></script>

<script>
	var editor;
	var files = <?php echo json_encode($files); ?>

	function show_img_view(title, src) {
		$("#img-view-title").html(title);
		$("#img-view-contain").attr("src", src);

		$('#img-view-modal').modal('show');
	}

	$(document).ready(function() {

		$(".img-view-thumbnail").on('click', function() {
			let img_title = $(this).attr('img-title');
			let img_path = $(this).attr('img-path');
			if (typeof img_path === "undefined" || img_path == "") {
				return false;
			}

			show_img_view(img_title, img_path);
		});

		//file list
		$.fn.dataTable.Editor.files[ 'files' ] = files;

		//editor
		editor = new $.fn.dataTable.Editor( {
			ajax: "<?php echo site_url('siswa/pendaftaran/uploaddokumen'); ?>",
			fields: [ 
				<?php foreach($dokumentambahan->getResult() as $row): ?>
				{
					label: "<?php echo $row->nama; ?>:",
					name: "dokumen_<?php echo $row->daftar_kelengkapan_id; ?>",
					type: "upload",
					display: function ( file_id ) {
						if (file_id == "" || typeof files[file_id] === undefined) {
							return "";
						}
						return '<img src="'+editor.file( 'files', file_id ).web_path+'"/>';
					},
					clearText: "Hapus",
					processingText: 'Sedang mengunggah',
					noImageText: 'No image'
				}, 
				<?php endforeach; ?>
			],
			i18n: {
				edit: {
					button: "Ubah",
					title:  "Ubah data siswa",
					submit: "Simpan"
				},
				error: {
					system: "Ada permasalahan dalam menyimpan data. Silahkan hubungi nomor bantuan."
				},
			}
		});

		// Activate the bubble editor on click of a table cell
		$('[data-editor-field]').on( 'click', function (e) {
			if (!$(this).hasClass("editable")) return;
			editor.bubble( this );
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
					if (field.substr(0,8) == "dokumen_") {
						var kelengkapan_id = field.substr(8, field.length);
						
						var file_id = entry[field]; 
						var dokumen = $.fn.dataTable.Editor.files[ 'files' ][file_id];
						if (typeof dokumen === "undefined" || dokumen == null) {
							continue;
						}

						if (dokumen.thumbnail_path == "") {
							var img = $("#dokumen-" + kelengkapan_id);
							img.hide();

							var btn = $("#unggah-dokumen-" + kelengkapan_id);
							btn.attr("data-editor-value", '');
							btn.html("Unggah");

							var input = $("#dokumen_" + kelengkapan_id);
							input.val("");
						}
						else {
							//update img
							var img = $("#dokumen-" + kelengkapan_id);
							img.attr("src", dokumen.thumbnail_path);
							img.attr("img-path", dokumen.web_path);
							img.show();

							//update button
							var btn = $("#unggah-dokumen-" + kelengkapan_id);
							btn.attr("data-editor-value", file_id);
							btn.html("Ubah");

							var input = $("#dokumen_" + kelengkapan_id);
							input.val(file_id);
						}
					}
				}
			}
		});

	});
</script>