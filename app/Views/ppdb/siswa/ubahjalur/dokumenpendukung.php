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

<script src="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js"></script>

<script>
	var editor;
	var files = <?php echo json_encode($files); ?>

	// function show_img_view(title, src) {
	// 	$("#img-view-title").html(title);
	// 	$("#img-view-contain").attr("src", src);

	// 	$('#img-view-modal').modal('show');
	// }

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
<?php }?>
