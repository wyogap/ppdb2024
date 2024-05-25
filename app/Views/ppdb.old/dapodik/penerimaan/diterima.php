
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center justify-content-center" id="loading2" style="position: absolute; margin-top: 24px; margin-left: -12px;">
			<div class="loader" role="status">
				<span class="sr-only">Loading...</span>
			</div>
		</div>
		<div class="box box-solid">
			<!-- <div class="box-header with-border">
				<i class="glyphicon glyphicon-search"></i>
				<h3 class="box-title"><b>Pencarian Siswa</b></h3>
			</div> -->
			<div class="box-body">
					<table class="display" id="tditerima" style="width:100%;">
						<thead>
							<tr>
								<!-- <td class="text-center" data-priority="1">#</td> -->
								<td class="text-center" data-priority="1">Nama</td>
								<td class="text-center">Jenis Kelamin</td>
								<td class="text-center" data-priority="3">NISN</td>
								<td class="text-center">NIK</td>
								<td class="text-center">Tempat Lahir</td>
								<td class="text-center">Tanggal Lahir</td>
								<td class="text-center">Nama Ibu Kandung</td>
								<td class="text-center">NPSN Sekolah Asal</td>
								<td class="text-center" data-priority="4">Sekolah Asal</td>
								<td class="text-center" data-priority="2"></td>
							</tr>
						</thead>
					</table>
			</div>
		</div>
	</div>
</div>



<script>

	var editor, editor_siswa, dt_siswa;
	var nama, sekolah_id, peran_id, username;

	$(document).ready(function() {
		$.extend( $.fn.dataTable.defaults, { responsive: true } );

		$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
		} );

		editor_siswa = new $.fn.dataTable.Editor( {
			ajax: "<?php echo site_url('dapodik/penerimaan/json'); ?>",
			table: "#tditerima",
			idSrc: "peserta_didik_id",
			fields: [ 
			{
				label: "Nama:",
				name: "nama",
				type: "text",
            }, {
                label: "Jenis Kelamin:",
                name:  "jenis_kelamin",
                type:  "radio",
                options: [
                    { label: "Laki-laki", 	value: "L" },
                    { label: "Perempuan",   value: "P" },
                ]
			}, {
				label: "NISN:",
				name: "nisn",
				type: "text",
			}, {
				label: "NIK:",
				name: "nik",
				type: "text",
			}, {
				label: "Tempat Lahir:",
				name: "tempat_lahir",
				type: "text",
			}, {
				label: "Tanggal Lahir:",
				name: "tanggal_lahir",
                type:  'datetime',
                def:   function () { return new Date(); }
			}, {
				label: "Nama Ibu Kandung:",
				name: "nama_ibu_kandung",
				type: "text",
			}, {
				label: "NPSN Sekolah Asal:",
				name: "npsn_sekolah_asal",
				type: "text",
			}, {
				label: "Nama Sekolah Asal:",
				name: "asal_sekolah",
				type: "text",
			}
			],
            formOptions: {
                main: {
                    submit: 'changed',
					onBackground: 'none'
                }
            },
			i18n: {
				create: {
					button: "Data Baru",
					title:  "Data Peserta Didik Baru",
					submit: "Simpan"
				},
				edit: {
					button: "Ubah Data",
					title:  "Ubah Data Peserta Didik",
					submit: "Simpan"
				},
				error: {
					system: "Ada permasalahan dalam menyimpan data. Silahkan hubungi admin sistem."
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

        editor_siswa.on('preSubmit', function(e, o, action) {
            if (action === 'create' || action === 'edit') {
                let field = null;
                let hasError = false;

				field = this.field('nama');
				if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == '') {
                        hasError = true;
                        field.error('Nama harus diisi.');
                    }
 
                    if (!hasError) {
                        //TODO: validasi lebih lanjut
                    }
                }
 
				field = this.field('jenis_kelamin');
				if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == '') {
                        hasError = true;
                        field.error('Jenis kelamin harus diisi.');
                    }
 
                    if (!hasError) {
                        //TODO: validasi lebih lanjut
                    }
                }

				field = this.field('nisn');
				if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == '') {
                        hasError = true;
                        field.error('NISN harus diisi.');
                    }
 
                    if (!hasError) {
                        //TODO: validasi lebih lanjut
						if (field.val().length != 10) {
							hasError = true;
                        	field.error('NISN harus 10 digit.');
						}
                    }
                }

				field = this.field('nik');
				if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == '') {
                        hasError = true;
                        field.error('NIK harus diisi.');
                    }
 
                    if (!hasError) {
                        //TODO: validasi lebih lanjut
						if (field.val().length != 16) {
							hasError = true;
                        	field.error('NPSN harus 16 digit.');
						}
                    }
                }

				field = this.field('tempat_lahir');
				if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == '') {
                        hasError = true;
                        field.error('Tempat lahir harus diisi.');
                    }
 
                    if (!hasError) {
                        //TODO: validasi lebih lanjut
                    }
                }

				field = this.field('tanggal_lahir');
				if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == '') {
                        hasError = true;
                        field.error('Tanggal lahir harus diisi.');
                    }
 
                    if (!hasError) {
                        //TODO: validasi lebih lanjut
                    }
                }

				field = this.field('nama_ibu_kandung');
				if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == '') {
                        hasError = true;
                        field.error('Nama ibu kandung harus diisi.');
                    }
 
                    if (!hasError) {
                        //TODO: validasi lebih lanjut
                    }
                }

				field = this.field('npsn_sekolah_asal');
				if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == '') {
                        hasError = true;
                        field.error('NPSN sekolah asal harus diisi.');
                    }
 
                    if (!hasError) {
                        //TODO: validasi lebih lanjut
						if (field.val().length != 8) {
							hasError = true;
                        	field.error('NPSN harus 8 digit.');
						}
                    }
                }

				field = this.field('asal_sekolah');
				if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == '') {
                        hasError = true;
                        field.error('Nama sekolah asal harus diisi.');
                    }
 
                    if (!hasError) {
                        //TODO: validasi lebih lanjut
                    }
                }

                /* If any error was reported, cancel the submission so it can be corrected */
				if (this.inError()) {
                    this.error('Data wajib belum diisi atau tidak berhasil divalidasi');
                    return false;
                }

            }

        });        

		dt_siswa = $('#tditerima').DataTable({
			"responsive": true,
			"paging": false,
			"dom": 'Bfrtpil',
			select: true,
			buttons: [
				{ 
					extend: "create", editor: editor_siswa,
					formButtons: [
						{ text: 'Simpan', className: 'btn-primary', action: function () { this.submit(); } },
						{ text: 'Batal', className: 'btn-secondary', action: function () { this.close(); } }
					]
				},
				// { extend: "edit", editor: editor_siswa },
				{
					extend: 'excelHtml5',
					text: 'Ekspor',
					className: 'btn-sm btn-primary',
					exportOptions: {
						orthogonal: "export",
						modifier: {
							//selected: true
						},
					},
				},
				// {
				// 	text: 'Impor',
				// 	className: 'btn-sm btn-danger',
				// 	action: function ( e, dt, node, conf ) {
				// 		impor_data(e, dt, node, conf);
				// 	},
				// },
			],
			"language": {
				"processing":   "Sedang proses...",
				"lengthMenu":   "Tampilan _MENU_ baris",
				"zeroRecords":  "Tidak ditemukan data yang sesuai",
				"info":         "Tampilan _START_ - _END_ dari _TOTAL_ baris",
				"infoEmpty":    "Tampilan 0 hingga 0 dari 0 baris",
				"infoFiltered": "(disaring dari _MAX_ baris keseluruhan)",
				"infoPostFix":  "",
				"loadingRecords": "Loading...",
				"emptyTable":   "Tidak ditemukan data yang sesuai",
				"search":       "Cari:",
				"url":          "",
				"paginate": {
				"first":    "Awal",
				"previous": "Balik",
				"next":     "Lanjut",
				"last":     "Akhir"
				},
				aria: {
						sortAscending:  ": klik untuk mengurutkan dari bawah ke atas",
						sortDescending: ": klik untuk mengurutkan dari atas ke bawah"
					},
                // edit: {
                //     button: "Ubah",
                //     title: "Ubah",
                //     submit: "Simpan"
                // },
                // remove: {
                //     button: "Hapus",
                //     title: "Hapus",
                //     submit: "Hapus",
                //     // confirm: {
                //     //     _: "{__('Konfirmasi menghapus')} %d {$tbl.title}?",
                //     //     1: "{__('Konfirmasi menghapus')} 1 {$tbl.title}?"
                //     // }
                // },
 			},	
			// "ajax": "<?php echo site_url('dapodik/penerimaan/json'); ?>",
			"ajax": {
              "type" : "POST",
              "url" : "<?php echo site_url('dapodik/penerimaan/json'); ?>",
              "dataSrc": function ( json ) {
                  //hide loader
                  $("#loading2").hide();

                  //actual data source
				  if (typeof json.error!='undefined' && json.error!=null && json.error!='') {
					  alert(json.error);
					  return [];
				  }

                  return json.data;
                }       
            },
			rowId: 'peserta_didik_id',
			columns: [
				{ data: "nama", className: 'dt-body-left'
				},
				{ data: "jenis_kelamin", className: 'dt-body-center  text-nowrap' },
				{ data: "nisn", className: 'dt-body-center'
				},
				{ data: "nik", className: 'dt-body-center'
				},
				{ data: "tempat_lahir", className: 'dt-body-center' },
				{ data: "tanggal_lahir", className: 'dt-body-center  text-nowrap' },
				{ data: "nama_ibu_kandung", className: 'dt-body-center' },
				{ data: "npsn_sekolah_asal", className: 'dt-body-center' },
				{ data: "asal_sekolah", className: 'dt-body-left' },
				{
					data: null,
					className: 'text-end inline-flex text-nowrap inline-actions',
					"orderable": false,
					render: function(data, type, row, meta) {
						if(type != 'display') {
							return "";
						}

						let str = "<button href='#' onclick='event.stopPropagation(); ubah_data(" +meta.row+ ", dt_siswa, \"" +row['peserta_didik_id']+ "\");' data-tag='" +meta.row+ "' class='btn btn-sm btn-primary' style='margin-right: 6px;'>Ubah</button>";
						str += "<button href='#' onclick='event.stopPropagation(); hapus_penerimaan(" +meta.row+ ", dt_siswa, \"" +row['peserta_didik_id']+ "\");' data-tag='" +meta.row+ "' class='btn btn-sm btn-danger'>Hapus</button>";

						return str;
						// return "<button href='#' onclick='event.stopPropagation(); hapus_penerimaan(" +meta.row+ ", dt_siswa, \"" +row['peserta_didik_id']+ "\");' data-tag='" +meta.row+ "' class='btn btn-sm btn-danger'>Hapus</button>";
					}
				}
			],
			order: [ 0, 'asc' ],
			"deferLoading": 0
		});

	});

	function ubah_data(row_id, dt, key) {
		let row = dt.row('#' +key);

		editor_siswa
				.title("Ubah Data Peserta Didik")
				.buttons([
					{ label: "Simpan", className: "btn-primary", fn: function () { this.submit(); } },
					{ label: "Batal", className: "btn-secondary", fn: function () { this.close(); } },
				])
				.edit(row.index(), {
					submit: 'changed'
				});

		return;
	}

	function hapus_penerimaan(row_id, dt, key) {
		// add assoc key values, this will be posts values
		var formData = new FormData();
		formData.append("peserta_didik_id", key);
		formData.append("action", "remove");

		$.ajax({
			type: "POST",
			url: "<?php echo site_url('dapodik/penerimaan/json'); ?>",
			async: true,
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			timeout: 60000,
			dataType: 'json',
			success: function(json) {
				if (typeof json.error !== 'undefined' && json.error != "" && json.error != null) {
					alert(json.error);
					return;
				}

				//hide loader
				//$("#loading2").show();
				dt_siswa.ajax.reload();
				dt_search.ajax.reload();

				// dt.ajax.url("<?php echo site_url('dapodik/penerimaan/json'); ?>");
				// dt.ajax.reload(function(json) {
				// 	//hide loader
				// 	$("#loading2").hide();
				// }, true);
				// dt.draw();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Gagal menghapus data');

				return;
			}
		});

	}

	function impor_data(e, dt, node, conf){
		$.confirm({
			columnClass: 'medium',
			title: 'Impor Daftar Peserta Didik Baru',
			content: '' +
			'<form action="" class="formName">' +
			'<div class="form-group">' +
			'<input id="upload" type="file" name="import" accept=".xlsx, .xls, .csv" style="width: 100%;" />' +
			'<div id="error" class="d-none text-danger mt-2"></div>' +
			'<div class="d-none text-center justify-content-center" id="spinner" style="position: absolute; width: 100%; top: 0px;">' +
			'  <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>' +
			'</div>' +
			'</div>' +
			'</form>',
			buttons: {
				cancel: function () {
					//close
				},
				formSubmit: {
					text: 'Impor',
					btnClass: 'btn-primary',
					action: function () {
						let that = this;

						//upload the file
						let upload = that.$content.find('#upload');
						if (upload[0].files.length == 0) {
							let message = that.$content.find('#error');
							message.html("Belum memilih file");
							message.removeClass("d-none");
							return false;
						}
						let file = upload[0].files[0];

						let spinner = that.$content.find('#spinner');

						// add assoc key values, this will be posts values
						var formData = new FormData();
						formData.append("upload", file, file.name);
						formData.append("action", "import");

						spinner.removeClass('d-none');

						upload.attr('disabled', 'disabled');
						this.buttons.cancel.disable();
						this.buttons.formSubmit.disable();

						$.ajax({
							type: "POST",
							url: "<?php echo site_url('dapodik/penerimaan/impor'); ?>",
							async: true,
							data: formData,
							cache: false,
							contentType: false,
							processData: false,
							timeout: 60000,
							dataType: 'json',
							success: function(json) {
								if (typeof json.error !== 'undefined' && json.error != "" && json.error != null) {
									let message = that.$content.find('#error');
									message.html(json.error);
									message.removeClass("d-none");
									//hide spinner
									spinner.addClass('d-none');
									upload.removeAttr('disabled');
									that.buttons.cancel.enable();
									that.buttons.formSubmit.enable();
									return;
								}

								//hide spinner
								spinner.addClass('d-none');

								dt.ajax.reload();
								that.close();
							},
							error: function(jqXHR, textStatus, errorThrown) {
								let message = that.$content.find('#error');
								message.html("Gagal mengimpor file: " + textStatus);
								message.removeClass("d-none");
								//hide spinner
								spinner.addClass('d-none');

								upload.removeAttr('disabled');
								that.buttons.cancel.enable();
								that.buttons.formSubmit.enable();

								return;
							}
						});

						//wait for completion of ajax
						return false;
					}
				},
			},
			onContentReady: function () {
				// bind to events
				var that = this;
				this.$content.find('form').on('submit', function (e) {
					// if the user submits the form by pressing enter in the field.
					e.preventDefault();
					that.$$formSubmit.trigger('click'); // reference the button and click it
				});
				this.$content.find('#upload').on('change', function (e) {
					let message = that.$content.find('#error');
					message.html("");
					message.addClass("d-none");
				});
			}
		});
	}
</script>