<script>
		//Dropdown Select
		$(function () {
			$(".select2").select2();
		});

</script>

<script>

	var editor, editor_siswa, dt_siswa;
	var nama, sekolah_id, peran_id, username;

    var kode_wilayah_kab=kode_wilayah_kec=kode_wilayah=null;
    var onchange_flag=onchange_flag1=onchange_flag2=false;

    var cek_waktupendaftaran = {$cek_waktupendaftaran|default: 0};
    var cek_waktuverifikasi = {$cek_waktuverifikasi|default: 0};
    var cek_waktusosialisasi = {$cek_waktusosialisasi|default: 0};
    var cek_waktudaftarulang = {$cek_waktudaftarulang|default: 0};
    var impersonasi_sekolah = {$impersonasi_sekolah|default: 0};

	$(document).ready(function() {
		$.extend( $.fn.dataTable.defaults, { 
            responsive: true, 
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
				}
			},	
        } );

        //change error message from html pop-up to toastr.
        $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) { 
            if (message.search("not-login") >= 0) {
                toastr.error("Sesi login sudah kadaluarsa. Silahkan login kembali.");
            }
            else {
                toastr.error(message);
            }
        };

		$('a[data-bs-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		    $.fn.dataTable.tables( { visible: true, api: true } ).columns.adjust().responsive.recalc();
		} );

        {if $cek_waktupendaftaran==1 || $cek_waktusosialisasi==1}
		editor_siswa = new $.fn.dataTable.Editor( {
			ajax: "{$site_url}ppdb/dapodik/penerimaan/ubahdata",
			table: "#tdaftarpendaftar",
			idSrc: "pendaftaran_id",
			fields: [ 
            {
                label: "Jalur Pendaftaran:",
                name:  "penerapan_id",
                type:  "radio",
                compulsory: true,
                options: [
                    {foreach $daftarpenerapan as $p}
                    { label: "{$p.jalur}", 	value: "{$p.penerapan_id}" },
                    {/foreach}
                ]
            }, {
				label: "Nama:",
				name: "nama",
				type: "text",
                compulsory: true,
            }, {
                label: "Jenis Kelamin:",
                name:  "jenis_kelamin",
                type:  "radio",
                compulsory: true,
                options: [
                    { label: "Laki-laki", 	value: "L" },
                    { label: "Perempuan",   value: "P" },
                ]
			}, {
				label: "NISN:",
				name: "nisn",
				type: "text",
                compulsory: true,
                def: "NA",
                fieldInfo: "Apabila belum mempunya NISN, diisi dengan: NA"
			}, {
				label: "NIK:",
				name: "nik",
				type: "text",
                compulsory: true,
                fieldInfo: "Apabila belum mempunya NIK/KK, diisi dengan: NA"
			}, {
				label: "Tempat Lahir:",
				name: "tempat_lahir",
				type: "text",
                compulsory: true,
			}, {
				label: "Tanggal Lahir:",
				name: "tanggal_lahir",
                type:  'datetime',
                compulsory: true,
                def:   function () { return new Date(); }
			}, {
				label: "Nama Ibu Kandung:",
				name: "nama_ibu_kandung",
				type: "text",
                compulsory: true,
			}, {
				label: "Alamat:",
				name: "kode_wilayah_kab",
				type: "tcg_select2",
                options: [
                    {foreach $daftarkab as $kab}
                    { label: "{$kab.kabupaten}", value: "{$kab.kode_wilayah}" },
                    {/foreach}
                ],
                attr: {
                    compulsory: true,
                    placeholder: "Kabupaten/Kota",
                    editorId: "editor_siswa",
                },
			}, {
				label: "",
				name: "kode_wilayah_kec",
				type: "tcg_select2",
                options: [],
                attr: {
                    compulsory: true,
                    placeholder: "Kecamatan",
                    editorId: "editor_siswa",
                },
			}, {
				label: "",
				name: "kode_wilayah",
				type: "tcg_select2",
                options: [],
                attr: {
                    compulsory: true,
                    placeholder: "Desa/Kelurahan",
                    editorId: "editor_siswa",
                },
			}, {
				label: "NPSN Sekolah Asal:",
				name: "npsn_sekolah_asal",
				type: "text",
                fieldInfo: "Apabila belum pernah sekolah, dikosongkan"
			}, {
				label: "Nama Sekolah Asal:",
				name: "nama_sekolah_asal",
				type: "text",
                fieldInfo: "Apabila belum pernah sekolah, dikosongkan"
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

                field = this.field('penerapan_id');
                if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == '') {
                        hasError = true;
                        field.error('Jalur pendaftaran harus diisi.');
                    }

                    if (!hasError) {
                        //TODO: validasi lebih lanjut
                    }
                }

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
						if (field.val() != 'NA' && field.val().length != 10) {
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
						if (field.val() != 'NA' && field.val().length != 16) {
							hasError = true;
                        	field.error('NIK harus 16 digit.');
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

				field = this.field('kode_wilayah');
				if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == '') {
                        hasError = true;
                        field.error('Alamat desa/kelurahan harus diisi.');
                    }
 
                    if (!hasError) {
                        //TODO: validasi lebih lanjut
                    }
                }

				// field = this.field('npsn_sekolah_asal');
				// if (!field.isMultiValue()) {
                //     hasError = false;
                //     if (!field.val() || field.val() == '') {
                //         hasError = true;
                //         field.error('NPSN sekolah asal harus diisi.');
                //     }
 
                //     if (!hasError) {
                //         //TODO: validasi lebih lanjut
				// 		if (field.val().length != 8) {
				// 			hasError = true;
                //         	field.error('NPSN harus 8 digit.');
				// 		}
                //     }
                // }

				// field = this.field('asal_sekolah');
				// if (!field.isMultiValue()) {
                //     hasError = false;
                //     if (!field.val() || field.val() == '') {
                //         hasError = true;
                //         field.error('Nama sekolah asal harus diisi.');
                //     }
 
                //     if (!hasError) {
                //         //TODO: validasi lebih lanjut
                //     }
                // }

                /* If any error was reported, cancel the submission so it can be corrected */
				if (this.inError()) {
                    this.error('Data wajib belum diisi atau tidak berhasil divalidasi');
                    return false;
                }

                //remove helper field
                $.each(o.data, function (key, val) {
                    delete o.data[key].kode_wilayah_kab;
                    delete o.data[key].kode_wilayah_kec;
                });

            }

        });  
        
        editor_siswa.on('postSubmit', function(e, json, data, action, xhr) {
            if (action=="edit") {
                if(json !== undefined && json !== null && json.data !== undefined && json.data !== null) {
                    json.data.forEach(function(data) {
                        let nama = data['nama'];
                        toastr.success("Berhasil mengubah data siswa siswa an. " +nama);
                    });

                    dt_siswa.ajax.reload();

                    {foreach $daftarpenerapan as $row}
                    dt_{$row.penerapan_id}.ajax.reload();
                    {/foreach}
                }
            }
            else if(action=="create") {
                if(json !== undefined && json !== null && json.data !== undefined && json.data !== null) {
                    json.data.forEach(function(data) {
                        let nama = data['nama'];
                        toastr.success("Berhasil menambahkan data siswa an. " +nama);
                    });
 
                    dt_siswa.ajax.reload();

                    {foreach $daftarpenerapan as $row}
                    dt_{$row.penerapan_id}.ajax.reload();
                    {/foreach}
                }
            }
        });

        /* Called before editor open event when edit is called. Value is not set. */
        editor_siswa.on( 'initEdit', function (e, node, data, items, type) {
            onchange_flag = true;

            //get list of kecamatan from json
            let newval1=data['kode_wilayah_kab'];

            //default kab to kode_wilayah_aktif
            if (newval1===null || newval1=="") {
                newval1 = "{$profilsekolah.kode_wilayah_kab}";
            }

            if (kode_wilayah_kab!=newval1) {
                onchange_flag1 = true;

                $.ajax({
                    type: "POST",
                    url: "{$site_url}/home/lkkecamatan?kode_wilayah=" +newval1,
                    async: true,
                    data: null,
                    cache: false,
                    contentType: false,
                    processData: false,
                    timeout: 60000,
                    dataType: 'json',
                    success: function(json) {
                        if (typeof json.error !== 'undefined' && json.error != "" && json.error != null) {
                            toastr.error("Tidak berhasil mendapat daftar kecamatan. " +json.error);
                        }
                        else {
                            //update list
                            editor_siswa.field('kode_wilayah_kec').update(json.data); 
                            //set value in case open() already finished
                            let val = data['kode_wilayah_kec'];     
                            editor_siswa.field('kode_wilayah_kec').set(val);

                            kode_wilayah_kab = newval1;
                        }
                        
                        onchange_flag1 = false;              
                        if (!onchange_flag1 && !onchange_flag2) {
                            onchange_flag = false;
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error("Tidak berhasil mendapat daftar kecamatan. " +textStatus);
                        
                        onchange_flag1 = false;              
                        if (!onchange_flag1 && !onchange_flag2) {
                            onchange_flag = false;
                        }

                        return;
                    }
                })
            }

            //get list of kecamatan from json
            let newval2=data['kode_wilayah_kec'];

            //default kab to kode_wilayah_aktif
            if (newval2===null || newval2=="") {
                newval2 = "{$profilsekolah.kode_wilayah_kec}";
            }

            if (kode_wilayah_kec!=newval2) {
                onchange_flag2 = true;

                $.ajax({
                    type: "POST",
                    url: "{$site_url}/home/lkdesa?kode_wilayah=" + newval2,
                    async: true,
                    data: null,
                    cache: false,
                    contentType: false,
                    processData: false,
                    timeout: 60000,
                    dataType: 'json',
                    success: function(json) {
                        if (typeof json.error !== 'undefined' && json.error != "" && json.error != null) {
                            toastr.error("Tidak berhasil mendapat daftar desa/kelurahan. " +json.error);
                        }
                        else {
                            //update list
                            editor_siswa.field('kode_wilayah').update(json.data);  
                            //set value in case open() already finished
                            let val = data['kode_wilayah'];     
                            editor_siswa.field('kode_wilayah').set(val);
                            
                            kode_wilayah_kec = newval2;
                        }

                        onchange_flag2 = false;                        
                        if (!onchange_flag1 && !onchange_flag2) {
                            onchange_flag = false;
                        }                        
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error("Tidak berhasil mendapat daftar desa/kelurahan. " +textStatus);
                        
                        onchange_flag2 = false;
                        if (!onchange_flag1 && !onchange_flag2) {
                            onchange_flag = false;
                        }

                        return;
                    }
                })
            }

            if (!onchange_flag1 && !onchange_flag2) {
                onchange_flag = false;
            }

        });

        /* Value is set */
        editor_siswa.on( 'open' , function ( e, type ) {
            //let data = this.s.editData;

            //hide empty label
            $(".DTE_Label").each(function(i, el) {
                dom = $(el);

                if (dom.text() == "") {
                    dom.hide();
                };
            });

        });

        /* onchange */
        $(editor_siswa.field('kode_wilayah_kab').node()).on('change', function() {
            // let data = this.s.editData;
            // if (typeof(data) === undefined)     return;

            let newval = editor_siswa.field('kode_wilayah_kab').val();
            if (newval == null || kode_wilayah_kab === newval) {
                return;
            }

            //in the middle of onchange processing. dont let it recursive
            if (onchange_flag)  return;

            onchange_flag = true;

            //get list from json
            let lookup=null;

            $.ajax({
                type: "POST",
                url: "{$site_url}/home/lkkecamatan?kode_wilayah=" +newval,
                async: true,
                data: null,
                cache: false,
                contentType: false,
                processData: false,
                timeout: 60000,
                dataType: 'json',
                success: function(json) {
                    if (typeof json.error !== 'undefined' && json.error != "" && json.error != null) {
                        toastr.error("Tidak berhasil mendapat daftar kecamatan. " +json.error);
                        return;
                    }

                    //update list
                    editor_siswa.field('kode_wilayah_kec').update(json.data);      
                    
                    kode_wilayah_kab = editor_siswa.field('kode_wilayah_kab').val();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error("Tidak berhasil mendapat daftar kecamatan. " +textStatus);

                    onchange_flag = false;
                    return;
                }
            })
            .then(function(resp){
                onchange_flag = false;
            });

        });

        $(editor_siswa.field('kode_wilayah_kec').node()).on('change', function() {
            // let data = this.s.editData;
            // if (typeof(data) === undefined)     return;

            let newval = editor_siswa.field('kode_wilayah_kec').val();
            if (newval == null || kode_wilayah_kec === newval) {
                return;
            }

            //in the middle of onchange processing. dont let it recursive
            if (onchange_flag)  return;

            onchange_flag = true;

            //get list from json
            let lookup=null;

            $.ajax({
                type: "POST",
                url: "{$site_url}/home/lkdesa?kode_wilayah=" + newval,
                async: true,
                data: null,
                cache: false,
                contentType: false,
                processData: false,
                timeout: 60000,
                dataType: 'json',
                success: function(json) {
                    if (typeof json.error !== 'undefined' && json.error != "" && json.error != null) {
                        toastr.error("Tidak berhasil mendapat daftar desa/kelurahan. " +json.error);
                        return;
                    }

                    //update list
                    editor_siswa.field('kode_wilayah').update(json.data);      
                    
                    kode_wilayah_kec = editor_siswa.field('kode_wilayah_kec').val();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error("Tidak berhasil mendapat daftar desa/kelurahan. " +textStatus);
                    onchange_flag = false;

                    return;
                }
            })
            .then(function(resp){
                onchange_flag = false;
            });

        });
        {/if}

		dt_siswa = $('#tdaftarpendaftar').DataTable({
			"responsive": true,
			"pageLength": 10,
			"lengthMenu": [ [5, 10, 20, -1], [5, 10, 20, "All"] ],
			"paging": true,
			"pagingType": "numbers",
			"dom": 'Bfrtpil',
			select: true,
			buttons: [
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
                {if $cek_waktupendaftaran==1 || $cek_waktusosialisasi==1 || $impersonasi_sekolah==1}
				{ 
					extend: "create", 
                    text: "Siswa Baru (Luar Daerah/Belum Sekolah)",
                    editor: editor_siswa,
					formButtons: [
						{ text: 'Simpan', className: 'btn-primary', action: function () { this.submit(); } },
						{ text: 'Batal', className: 'btn-secondary', action: function () { this.close(); } }
					]
				},
                {/if}
			],
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
			rowId: 'pendaftaran_id',
			columns: [
				{ data: "rn", className: 'dt-body-left'},
				{ 
                    data: "nama", className: 'dt-body-left',
                    render: function(data, type, row, meta) {
						if(type != 'display') {
							return data;
						}

                        return "<a href='{$base_url}home/detailpendaftaran?peserta_didik_id=" +row['peserta_didik_id']+ "' target='_blank'>" +row['nama']+ " <i class='fa fas fa-external-link-alt'></i></a>";
                    }
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
				{ data: "nama_sekolah_asal", className: 'dt-body-left' },
				{ data: "jalur", className: 'dt-body-center' },
				{ data: "status_penerimaan_final", className: 'dt-body-center', 
					render: function(data, type, row, meta) {
						if(type != 'display') {
							return data;
						}

                        {if $final_ranking}
                        if (data==1 || data==3) {
                            return "Diterima";
                        }
                        else {
                            return "Tidak Diterima"
                        }
                        {else}
                        if (data==1 || data==3) {
                            return "Masuk Kuota";
                        }
                        else if (data==0) {
                            return "Belum Diperingkat";
                        }
                        else {
                            return "Tidak Masuk Kuota"
                        }
                        {/if}
					}

                },
                {if $cek_waktupendaftaran==1 || $cek_waktusosialisasi==1 || $cek_waktuverifikasi==1 || $cek_waktudaftarulang==1 || $impersonasi_sekolah==1}
				{
					data: null,
					className: 'text-end inline-flex text-nowrap inline-actions',
					"orderable": false,
					render: function(data, type, row, meta) {
						if(type != 'display') {
							return "";
						}

                        let str = "";

                        if (cek_waktupendaftaran==1 || cek_waktusosialisasi==1 || cek_waktuverifikasi==1 || impersonasi_sekolah==1) {
                            str += "<button onclick='event.stopPropagation(); ubah_data(" +meta.row+ ", dt_siswa, \"" +row['pendaftaran_id']+ "\");' data-tag='" +meta.row+ "' class='btn btn-primary shadow btn-xs sharp me-1'><i class='fa fa-pen'></i></button>";
                            str += "<button onclick='event.stopPropagation(); hapus_penerimaan(" +meta.row+ ", dt_siswa, \"" +row['pendaftaran_id']+ "\");' data-tag='" +meta.row+ "' class='btn btn-danger shadow btn-xs sharp me-1'><i class='fa fa-trash'></i></button>";
                        }

                        if (cek_waktudaftarulang==1 || impersonasi_sekolah==1) {
                            //row['status_penerimaan_final']=3;
                            //row['status_daftar_ulang']=1;
                            if (row['status_penerimaan_final']==1 || row['status_penerimaan_final']==3) {
                                if (row['status_daftar_ulang']==1) {
                                    str += "<button onclick='event.stopPropagation(); batal_du(" +meta.row+ ", dt_siswa, \"" +row['pendaftaran_id']+ "\");' data-tag='" +meta.row+ "' class='btn btn-danger shadow btn-xs me-1'>Batal DU</button>";
                                }
                                else {
                                    str += "<button onclick='event.stopPropagation(); daftar_ulang(" +meta.row+ ", dt_siswa, \"" +row['pendaftaran_id']+ "\");' data-tag='" +meta.row+ "' class='btn btn-primary shadow btn-xs me-1'>Daftar Ulang</button>";
                                }
                            }
                        }

						return str;
						// return "<button href='#' onclick='event.stopPropagation(); hapus_penerimaan(" +meta.row+ ", dt_siswa, \"" +row['peserta_didik_id']+ "\");' data-tag='" +meta.row+ "' class='btn btn-sm btn-danger'>Hapus</button>";
					}
				}
                {/if}
			],
			order: [ 1, 'asc' ],
			deferLoading: 0,
            columnDefs: [{
                targets: 11, 
                // status penerimaan
                createdCell: function(td, cellData, rowData, row, col) {                   
                    if (rowData['status_penerimaan_final']==1 || rowData['status_penerimaan_final']==3) {
                        $(td).removeClass('bg-red');
                        $(td).addClass('bg-green');
                        $(td).removeClass('bg-gray');
                    }
                    else if (rowData['status_penerimaan_final']==0) {
                        $(td).removeClass('bg-red');
                        $(td).removeClass('bg-green');
                        $(td).addClass('bg-gray');
                    }
                    else {
                        $(td).addClass('bg-red');
                        $(td).removeClass('bg-green');
                        $(td).removeClass('bg-gray');
                    }
                },
            }],
		});

        dt_siswa.on('order.dt search.dt', function () {
            let i = 1;
    
            dt_siswa
                .cells(null, 0, { search: 'applied', order: 'applied' })
                .every(function (cell) {
                    let data = this.data();
                    this.data(i++);
                    let data2 = this.data();
                });
        })
        .draw();

        {foreach $daftarpenerapan as $row}
            dt_{$row.penerapan_id} = $('#t{$row.penerapan_id}').DataTable({
                "responsive": true,
                "processing": true,
                "pageLength": 10,
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                "paging": true,
                "pagingType": "numbers",
                "dom": 'Bfrtpil',
                select: true,
                buttons: [
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
                "ajax": {
                    "type" : "POST",
                    "url" : "{$site_url}ppdb/dapodik/penerimaan/json?penerapan_id={$row.penerapan_id}",
                    "dataSrc": function ( json ) {
                        //hide loader
                        $("#loading2").hide();

                        //actual data source
                        if (typeof json.error!='undefined' && json.error!=null && json.error!='') {
                            alert(json.error);
                            return [];
                        }

                        //update the count in tab header
                        if (json.ext!==undefined) {
                            let el = $('[data-penerapan-id="{$row.penerapan_id}"][data-tag="kuota"]');
                            el.text(json.ext.kuota);
                            el = $('[data-penerapan-id="{$row.penerapan_id}"][data-tag="tambahan_kuota"]');
                            if (json.ext.tambahan_kuota > 0) {
                                el.text(json.ext.tambahan_kuota);
                                el.show();
                            }
                            else {
                                el.text(json.ext.tambahan_kuota);
                                el.hide();
                            }
                            el = $('[data-penerapan-id="{$row.penerapan_id}"][data-tag="diterima"]');
                            el.text(json.ext.diterima);
                            el = $('[data-penerapan-id="{$row.penerapan_id}"][data-tag="total_pendaftar"]');
                            el.text(json.ext.total_pendaftar);
                        }                        

                        return json.data;
                    }       
                },
                rowId: 'pendaftaran_id',
                columns: [
                    { data: "rn", className: 'dt-body-left'},
                    { 
                        data: "peringkat_final", className: 'dt-body-center',
                    },
                    { 
                        data: "nama", className: 'dt-body-left',
                        render: function(data, type, row, meta) {
                            if(type != 'display') {
                                return data;
                            }

                            return "<a href='{$base_url}home/detailpendaftaran?peserta_didik_id=" +row['peserta_didik_id']+ "' target='_blank'>" +row['nama']+ " <i class='fa fas fa-external-link-alt'></i></a>";
                        }
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
                    { data: "nama_sekolah_asal", className: 'dt-body-left' },
                    { data: "skor", className: 'dt-body-center' },
                    {if $cek_waktupendaftaran==1 || $cek_waktusosialisasi==1 || $cek_waktuverifikasi==1 || $impersonasi_sekolah==1}
                    {
                        data: null,
                        className: 'text-end inline-flex text-nowrap inline-actions',
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            if(type != 'display') {
                                return "";
                            }

                            let str = "";

                            str += "<button onclick='event.stopPropagation(); ubah_jalur(" +meta.row+ ", dt_{$row.penerapan_id}, editor_{$row.penerapan_id}, \"" +row['pendaftaran_id']+ "\");' data-tag='" +meta.row+ "' class='btn btn-primary shadow btn-xs sharp me-1'><i class='fa fa-share'></i></button>";
                            str += "<button onclick='event.stopPropagation(); hapus_penerimaan(" +meta.row+ ", dt_{$row.penerapan_id}, \"" +row['pendaftaran_id']+ "\");' data-tag='" +meta.row+ "' class='btn btn-danger shadow btn-xs sharp me-1'><i class='fa fa-trash'></i></button>";

                            return str;
                            // return "<button href='#' onclick='event.stopPropagation(); hapus_penerimaan(" +meta.row+ ", dt_siswa, \"" +row['peserta_didik_id']+ "\");' data-tag='" +meta.row+ "' class='btn btn-sm btn-danger'>Hapus</button>";
                        }
                    }
                    {/if}
                ],
                order: [ 11, 'desc' ],
                deferLoading: 0,
                columnDefs: [{
                    targets: 1, 
                    // status penerimaan
                    createdCell: function(td, cellData, rowData, row, col) {                   
                        if (rowData['status_penerimaan_final']==1 || rowData['status_penerimaan_final']==3) {
                            $(td).removeClass('bg-red');
                            $(td).addClass('bg-green');
                            $(td).removeClass('bg-gray');
                        }
                        else if (rowData['status_penerimaan_final']==0) {
                            $(td).removeClass('bg-red');
                            $(td).removeClass('bg-green');
                            $(td).addClass('bg-gray');
                        }
                        else {
                            $(td).addClass('bg-red');
                            $(td).removeClass('bg-green');
                            $(td).removeClass('bg-gray');
                        }
                    },
                }],
            });

            editor_{$row.penerapan_id} = new $.fn.dataTable.Editor( {
                ajax: "{$site_url}ppdb/dapodik/penerimaan/ubahjalur",
                table: "#t{$row.penerapan_id}",
                idSrc: "pendaftaran_id",
                fields: [ 
                    {
                        label: "Jalur Pendaftaran:",
                        name:  "penerapan_id",
                        type:  "radio",
                        options: [
                            {foreach $daftarpenerapan as $p}
                            { label: "{$p.jalur}", 	value: "{$p.penerapan_id}" },
                            {/foreach}
                        ]
                    }
                ],
                formOptions: {
                    main: {
                        submit: 'changed',
                        onBackground: 'none'
                    }
                },
                i18n: {
                    edit: {
                        button: "Ubah Jalur",
                        title:  "Ubah Jalur Pendaftaran",
                        submit: "Simpan"
                    },
                    error: {
                        system: "Ada permasalahan dalam menyimpan data. Silahkan hubungi admin sistem."
                    },
                }
            });

            editor_{$row.penerapan_id}.on('preSubmit', function(e, o, action) {
                if (action === 'create' || action === 'edit') {
                    let field = null;
                    let hasError = false;

                    field = this.field('penerapan_id');
                    if (!field.isMultiValue()) {
                        hasError = false;
                        if (!field.val() || field.val() == '') {
                            hasError = true;
                            field.error('Jalur pendaftaran harus diisi.');
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

            editor_{$row.penerapan_id}.on('postSubmit', function(e, json, data, action, xhr) {
            if (action=="edit") {
                if(json !== undefined && json !== null && json.data !== undefined && json.data !== null) {
                    json.data.forEach(function(data) {
                        let nama = data['nama'];
                        toastr.success("Berhasil mengubah jalur pendaftaran siswa an. " +nama);
                    });

                    dt_siswa.ajax.reload();

                    {foreach $daftarpenerapan as $row}
                    dt_{$row.penerapan_id}.ajax.reload();
                    {/foreach}
                }
            }
        });            
        {/foreach}

        //populate daftar sekolah untuk pencarian
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

    function ubah_jalur(row_id, dt, editor, key) {
		let row = dt.row('#' +key);
        // let data = row.data();
        // var penerapan_id = data['penerapan_id'];

		editor
				.title("Ubah Jalur Pendaftaran")
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
        let data = dt.rows(row_id).data();
        var nama = data[0]['nama'];
        var pendaftaran_id = data[0]['pendaftaran_id'];
        var peserta_didik_id = data[0]['peserta_didik_id'];

        // add assoc key values, this will be posts values
		var formData = new FormData();
		formData.append("pendaftaran_id", pendaftaran_id);
		formData.append("peserta_didik_id", peserta_didik_id);
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
					toastr.error("Tidak berhasil menghapus pendaftaran. " +json.error);
					return;
				}

				//hide loader
				//$("#loading2").show();
				dt_siswa.ajax.reload();

                //update penerapan table if necessary
                if (dt.settings()[0].sTableId == dt_siswa.settings()[0].sTableId) {
                    {foreach $daftarpenerapan as $row}
                    dt_{$row.penerapan_id}.ajax.reload();
                    {/foreach}
                }
                else {
                    dt.ajax.reload();
                }
				
                //reload the search if necessary
                cari_peserta_didik();

                toastr.success("Data pendaftaran an. " +nama+ " berhasil dihapus");
			},
			error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil menghapus pendaftaran. " +textStatus);

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

	function daftar_ulang(row_id, dt, key) {
		// add assoc key values, this will be posts values
        let data = dt.rows(row_id).data();
        var nama = data[0]['nama'];
        var pendaftaran_id = data[0]['pendaftaran_id'];
        var peserta_didik_id = data[0]['peserta_didik_id'];

        // add assoc key values, this will be posts values
		var formData = new FormData();
		formData.append("pendaftaran_id", pendaftaran_id);
		formData.append("peserta_didik_id", peserta_didik_id);
		formData.append("action", "daftarulang");

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
					toastr.error("Tidak berhasil melakukan daftar ulang. " +json.error);
					return;
				}

				//hide loader
				//$("#loading2").show();
				dt_siswa.ajax.reload();
				
                toastr.success("Daftar ulang an. " +nama+ " berhasil.");
			},
			error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil melakukan daftar ulang. " +textStatus);

				return;
			}
		});
	}

	function batal_du(row_id, dt, key) {
		// add assoc key values, this will be posts values
        let data = dt.rows(row_id).data();
        var nama = data[0]['nama'];
        var pendaftaran_id = data[0]['pendaftaran_id'];
        var peserta_didik_id = data[0]['peserta_didik_id'];

        // add assoc key values, this will be posts values
		var formData = new FormData();
		formData.append("pendaftaran_id", pendaftaran_id);
		formData.append("peserta_didik_id", peserta_didik_id);
		formData.append("action", "bataldu");

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
					toastr.error("Tidak berhasil menghapus daftar ulang. " +json.error);
					return;
				}

				//hide loader
				//$("#loading2").show();
				dt_siswa.ajax.reload();
				
                toastr.success("Daftar ulang an. " +nama+ " berhasil dihapus.");
			},
			error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil menghapus daftar ulang. " +textStatus);

				return;
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

    $("#nik").keyup(function (e) {
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
            rowId: "peserta_didik_id",
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
				{ 
                    data: "masuk_bdt", className: 'dt-body-center',
					render: function(data, type, row, meta) {
						if(type != 'display') {
							return data;
						}

						return (data == 1) ? "YA" : "TIDAK";
					}

                },
				{ data: "sumber_bdt", className: 'dt-body-center' },
				{ data: "diterima_sekolah", className: 'dt-body-left' },
				//{ data: "penerapan_id", className: 'dt-body-left' },
                {if $cek_waktupendaftaran==1 || $cek_waktusosialisasi==1 || $impersonasi_sekolah==1}
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
                {/if}
			],
			order: [ 0, 'asc' ],
			deferLoading: 0
		});

        editor_search = new $.fn.dataTable.Editor( {
            ajax: "{$site_url}ppdb/dapodik/penerimaan/daftar",
            table: "#tsearch",
            idSrc: "peserta_didik_id",
            fields: [ 
                {
                    name: "peserta_didik_id",
                    type: "hidden"
                },
                {
                    label: "Jalur Pendaftaran:",
                    name:  "penerapan_id",
                    type:  "radio",
                    options: [
                        {foreach $daftarpenerapan as $p}
                        { label: "{$p.jalur}", 	value: "{$p.penerapan_id}" },
                        {/foreach}
                    ]
                }
            ],
            formOptions: {
                main: {
                    submit: 'changed',
                    onBackground: 'none'
                }
            },
            i18n: {
                edit: {
                    button: "Ubah Jalur",
                    title:  "Ubah Jalur Pendaftaran",
                    submit: "Simpan"
                },
                error: {
                    system: "Ada permasalahan dalam menyimpan data. Silahkan hubungi admin sistem."
                },
            }
        });

        editor_search.on('preSubmit', function(e, o, action) {
            if (action === 'create' || action === 'edit') {
                let field = null;
                let hasError = false;

                field = this.field('penerapan_id');
                if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == '') {
                        hasError = true;
                        field.error('Jalur pendaftaran harus diisi.');
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

        editor_search.on('postSubmit', function(e, json, data, action, xhr) {
            if (action=="edit") {
                if(json !== undefined && json !== null && json.data !== undefined && json.data !== null) {
                    let nama = json.data['nama'];

                    dt_search.ajax.reload();
                    dt_siswa.ajax.reload();

                    {foreach $daftarpenerapan as $row}
                    dt_{$row.penerapan_id}.ajax.reload();
                    {/foreach}

                    toastr.success("Berhasil menambahkan pendaftaran siswa an. " +nama);
                }
            }
        });


        populate_daftar_sekolah();
	});

    function populate_daftar_sekolah() {

        //get daftar sekolah from server
		$.ajax({
			type: "POST",
			url: "{$site_url}ppdb/dapodik/penerimaan/json?action=sekolah",
			async: true,
			cache: false,
			contentType: false,
			processData: false,
			timeout: 60000,
			dataType: 'json',
			success: function(json) {
				if (typeof json.error !== 'undefined' && json.error != "" && json.error != null) {
					toastr.error("Tidak berhasil mendapatkan daftar sekolah. " +json.error);
					return;
				}

                if (json.data == null || json.length == 0) {
                    return;
                }

                //repopulate the list
                let select = $("#sekolah_id");

                select.empty();

                let opt = $("<option>").val('').text("-- Asal Sekolah --");
                select.append(opt);

                json.data.forEach(function(item, index, arr) {
                    opt = $("<option>").val(item.sekolah_id).text("(" +item.npsn+ ") " +item.nama);
                    select.append(opt);
                });

                //rebuild the select2?

			},
			error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil menambahkan penerimaan siswa. " +textStatus);

				return;
			}
		});

    }

	function cari_peserta_didik() {
		nama_baru = $("#nama").val();
		nisn_baru = $("#nisn").val();
		nik_baru = $("#nik").val();
		sekolah_baru = $("#sekolah_id").val();

		if ('' == nama_baru && '' == nisn_baru && '' == nik_baru && '' == sekolah_baru) {
			return;
		}

		// v_nama = nama_baru;
		// v_sekolah_id = sekolah_id_baru;
		// v_nisn = nisn_baru;
		// v_nik = nik_baru;

		//show loader
		$("#loader").show();

		//reload
		dt_search.ajax.url("{$site_url}ppdb/dapodik/penerimaan/json?action=search&nama=" +nama_baru+ "&nisn=" +nisn_baru+ "&nik=" +nik_baru+ "&sekolah_id=" +sekolah_baru);
		dt_search.ajax.reload(function(json) {
			//hide loader
			$("#loader").hide();
		}, false);
  	}

	function tambah_penerimaan(row_id, dt, key) {
		let row = dt.row('#' +key);

		editor_search
				.title("Pilih Jalur Pendaftaran")
				.buttons([
					{ label: "Simpan", className: "btn-primary", fn: function () { this.submit(); } },
					{ label: "Batal", className: "btn-secondary", fn: function () { this.close(); } },
				])
				.edit(row.index(), {
					submit: 'changed'
				});

		return;

        // // add assoc key values, this will be posts values
        // let data = dt.rows(row_id).data();
        // var nama = data[0]['nama'];

		// var formData = new FormData();
		// formData.append("peserta_didik_id", key);
		// formData.append("action", "accept");

		// $.ajax({
		// 	type: "POST",
		// 	url: "{$site_url}ppdb/dapodik/penerimaan/json",
		// 	async: true,
		// 	data: formData,
		// 	cache: false,
		// 	contentType: false,
		// 	processData: false,
		// 	timeout: 60000,
		// 	dataType: 'json',
		// 	success: function(json) {
		// 		if (typeof json.error !== 'undefined' && json.error != "" && json.error != null) {
		// 			toastr.error("Tidak berhasil menambahkan penerimaan siswa. " +json.error);
		// 			return;
		// 		}

		// 		dt_search.ajax.reload();
		// 		dt_siswa.ajax.reload();

        //         toastr.success("Berhasil menambahkan penerimaan siswa an. " +nama);

		// 	},
		// 	error: function(jqXHR, textStatus, errorThrown) {
        //         toastr.error("Tidak berhasil menambahkan penerimaan siswa. " +textStatus);

		// 		return;
		// 	}
		// });

	}

</script>
