<script>
		//Dropdown Select
		$(function () {
			$(".select2").select2();
		});

</script>

<script>

	var editor, editor_siswa, dt_siswa;
	var nama, sekolah_id, peran_id, username;

	$(document).ready(function() {
		$.extend( $.fn.dataTable.defaults, { responsive: true } );

		$('a[data-bs-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		$.fn.dataTable.tables( { visible: true, api: true } ).columns.adjust().responsive.recalc();
		} );

		editor_siswa = new $.fn.dataTable.Editor( {
			ajax: "{$site_url}ppdb/dapodik/penerimaan/json",
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
			],
			"language": {
				"processing":   "Sedang proses...",
				"lengthMenu":   "Tampilan _MENU_ entri",
				"zeroRecords":  "Tidak ditemukan data yang sesuai",
				"info":         "Tampilan _START_ - _END_ dari _TOTAL_ entri",
				"infoEmpty":    "Tampilan 0 hingga 0 dari 0 entri",
				"infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
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
 			},	
			"ajax": {
                "type" : "POST",
                "url" : "{$site_url}ppdb/dapodik/penerimaan/json",
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

                        let str = "<a href='#' onclick='event.stopPropagation(); ubah_data(" +meta.row+ ", dt_siswa, \"" +row['peserta_didik_id']+ "\");' data-tag='" +meta.row+ "' class='btn btn-primary shadow btn-xs sharp me-1'><i class='fa fa-pencil'></i></a>";
						str += "<a href='#' onclick='event.stopPropagation(); hapus_penerimaan(" +meta.row+ ", dt_siswa, \"" +row['peserta_didik_id']+ "\");' data-tag='" +meta.row+ "' class='btn btn-danger shadow btn-xs sharp me-1'><i class='fa fa-trash'></i></a>";

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
			url: "{$site_url}/ppdb/dapodik/penerimaan/json",
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
							url: "{$site_url}ppdb/dapodik/penerimaan/impor",
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

<script>

	var editor, editor_pwd, dt_search;
	//var nama, sekolah_id, peran_id, username;
	var v_nama='', v_nisn='', v_nik='', v_sekolah_id='';

    $("#nama").keyup(function (e) {
        if (e.which == 13) {
            cari_peserta_didik();
        }
    });

    $("#nisn").keyup(function (e) {
        if (e.which == 13) {
            cari_peserta_didik();
        }
    });

    // $("#sekolah_id").on('change', function (e) {
    //     cari_peserta_didik();
    // });

	$(document).ready(function() {
		$.extend( $.fn.dataTable.defaults, { responsive: true } );

		$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		$.fn.dataTable.tables( { visible: true, api: true } ).columns.adjust().responsive.recalc();
		} );

		dt_search = $('#tsearch').DataTable({
			"responsive": true,
			"pageLength": 5,
			"lengthMenu": [ [5, 10, 20, -1], [5, 10, 20, "All"] ],
			"paging": true,
			"pagingType": "numbers",
			"dom": 'tpil',
			select: true,
			// buttons: [
			// 	{
			// 		extend: 'selectedSingle',
			// 		text: 'Ubah Data',
			// 		action: function ( e, dt, button, config ) {
			// 			var data = dt.row( { selected: true } ).data();
			// 			window.location = "{$base_url}index.php/dapodik/ubahdata?peserta_didik_id=" +data['peserta_didik_id']+ "&redirect=admin/pesertadidik" ;
			// 		}        
			// 	},
			// 	{ extend: "edit", editor: editor_pwd }
			// ],
			"language": {
				"processing":   "Sedang proses...",
				"lengthMenu":   "Tampilan _MENU_ entri",
				"zeroRecords":  "Tidak ditemukan data yang sesuai",
				"info":         "Tampilan _START_ - _END_ dari _TOTAL_ entri",
				"infoEmpty":    "Tampilan 0 hingga 0 dari 0 entri",
				"infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
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
					}
			},	
			columns: [
				{ data: "nama", className: 'dt-body-left'
				},
				{ data: "jenis_kelamin", className: 'dt-body-center text-nowrap' },
				{ data: "nisn", className: 'dt-body-center'
				},
				{ data: "nik", className: 'dt-body-center'
				},
				{ data: "tanggal_lahir", className: 'dt-body-center text-nowrap' },
				{ data: "sekolah", className: 'dt-body-left' },
				{ data: "diterima_sekolah", className: 'dt-body-left' },
				{
					data: null,
					className: 'text-end inline-flex text-nowrap inline-actions',
					"orderable": false,
					render: function(data, type, row, meta) {
						if(type != 'display') {
							return "";
						}

						if (row['diterima_sekolah'] !== null && row['diterima_sekolah'] != '') {
							return "";
						}

						return "<button href='#' onclick='event.stopPropagation(); tambah_penerimaan(" +meta.row+ ", dt_search, \"" +row['peserta_didik_id']+ "\");' data-tag='" +meta.row+ "' class='btn btn-sm btn-primary'>Tambahkan</button>";
					}
				}
			],
			order: [ 0, 'asc' ],
			"deferLoading": 0
		});

	});

	function cari_peserta_didik() {
		nama_baru = $("#nama").val();
		sekolah_id_baru = $("#sekolah_id").val();
		nisn_baru = $("#nisn").val();
		//nik_baru = $("#nik").val();
		nik_baru = '';

		if ('' == nama_baru && '' == nik_baru && '' == nisn_baru && '' == sekolah_id_baru) {
			return;
		}

		// v_nama = nama_baru;
		// v_sekolah_id = sekolah_id_baru;
		// v_nisn = nisn_baru;
		// v_nik = nik_baru;

		//show loader
		$("#loading").show();

		//reload
		dt_search.ajax.url("{$site_url}dapodik/penerimaan/search?nama=" + nama_baru + "&nisn=" + nisn_baru + "&nik=" + nik_baru + "&sekolah_id=" + sekolah_id_baru );
		dt_search.ajax.reload(function(json) {
			//hide loader
			$("#loading").hide();
		}, false);
  	}

	function tambah_penerimaan(row_id, dt, key) {
		// add assoc key values, this will be posts values
		var formData = new FormData();
		formData.append("peserta_didik_id", key);
		formData.append("action", "accept");

		$.ajax({
			type: "POST",
			url: "{$site_url}dapodik/penerimaan/json",
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

				dt_search.ajax.reload();
				dt_siswa.ajax.reload();

				// $("#loading2").show();

				// dt.ajax.url("<?php echo site_url('dapodik/penerimaan/json'); ?>");
				// dt.ajax.reload(function(json) {
				// 	//hide loader
				// 	$("#loading2").hide();
				// }, true);

			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Gagal menambahkan data');

				return;
			}
		});

	}

</script>
