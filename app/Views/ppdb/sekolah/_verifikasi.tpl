{literal}
<script id="cabut-berkas" type="text/template">

    <div class="alert alert-secondary alert-dismissible" role="alert">
    Benar-benar akan melakukan <b>Cabut Berkas</b>?</b>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table">
            <tr>
                <td><b>Nama</b></td>
                <td>:</td>
                <td>{{nama}}</td>
            </tr>
            <tr>
                <td><b>NISN</b></td>
                <td>:</td>
                <td>{{nisn}}</td>
            </tr>
        </table>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 8px;">
        <div class="form-group has-feedback">
            <label for="keterangan"><b>Alasan cabut berkas : </b></label>
            <textarea id="keterangan" class="form-control" name="keterangan" placeholder="Penjelasan terkait Cabut Berkas..." data-validation="required" 
            style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" autofocus></textarea>
        </div>
    </div>

</script>
{/literal}

<script>

    var dt_belum, dt_sedang, dt_sudah, dt_berkas;
    var verifikasi_siswa = 0;

    $(document).ready(function() {
        dt_belum = $('#tabelbelum').DataTable({
            "responsive": true,
            "processing": true,
            "pageLength": 25,
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
            "paging": true,
            "pagingType": "numbers",
            "dom": 'Bfrtpil',
            "buttons": [
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
            "ajax": "{$base_url}ppdb/sekolah/verifikasi/belumdiverifikasi?sekolah_id={$sekolah_id}",
            "columns": [
                {
                    data: "nama",
                    className: "text-left",
                    orderable: 'true',
                    render: function(data, type, row, meta) {
                        if(type != 'display') {
                            return data;
                        }

                        return row['nama']+ '<a target="_blank" href="{$base_url}home/detailpendaftaran?peserta_didik_id=' +row['peserta_didik_id']+ '"> <i class="fa fas fa-external-link"></i></a>';
                    }
                },
                {
                    data: "nomor_pendaftaran",
                    className: "text-center",
                    orderable: 'true',
                },
                {
                    data: "nisn",
                    className: "text-center",
                    orderable: 'true',
                },
                {
                    data: "sekolah_asal",
                    className: "text-left",
                    orderable: 'true',
                },
                {
                    data: "jalur",
                    className: "text-center",
                    orderable: 'true',
                },
                {
                    data: "label_jenis_pilihan",
                    className: "text-center",
                    orderable: 'true',
                },
                {
                    data: "created_on",
                    className: "text-center",
                    orderable: 'true',
                    render: function ( data, type, row ) {
                        if (typeof data === 'undefined' || data == null || data == "0000-00-00 00:00:00") {
                            data = "";
                        }

                        if (type == "display" && data != "") {
                            data = moment.utc(data).local().format('YYYY-MM-DD HH:mm:ss');
                        }

                        return data;
                    },
                },
                {
                    data: "sedang_verifikasi",
                    className: "text-center",
                    orderable: 'true',
                },
                {if $cek_waktuverifikasi}
                {
                    data: null,
                    className: "text-center",
                    orderable: 'false',
                    defaultContent: '',
                    render: function(data, type, row, meta) {
                        if (type != 'display') {
                            return data;
                        }

                        //return row['kelengkapan_berkas'];

                        {if ($cek_waktuverifikasi|default: FALSE)}
                        return '<a onclick=verifikasi_pendaftaran(' +row['pendaftaran_id']+ ') href="#" class="btn btn-xs btn-primary">Verifikasi</a>';
                        {/if}

                        return data;
                    }
                },
                {/if}
            ],
            order: [ [5, 'asc'] ],
            initComplete: function() {
                // //check for error
                // if (json.error != null && json.error != "") {
                //     if (json.error == 'not-login' || json.errorno == -1) {
                //         window.location.replace("{$site_url}auth");
                //         return;
                //     }
                //     else {
                //         toastr.error("Tidak berhasil mendapatkan data terbaru. " +json.error);
                //         return;
                //     }
                // }

                len = this.api().rows().count();
                if (len == 0) {
                    $('#label-belum').html("Belum Diverifikasi");
                }
                else {
                    $('#label-belum').html("Belum Diverifikasi (" +len+ ")");
                }
            },
        });

        dt_sedang = $('#tabelsedang').DataTable({
            "responsive": true,
            "processing": true,
            "pageLength": 25,
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
            "paging": true,
            "pagingType": "numbers",
            "dom": 'Bfrtpil',
            "buttons": [
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
            "ajax": "{$base_url}ppdb/sekolah/verifikasi/belumlengkap?sekolah_id={$sekolah_id}",
            "columns": [
                {
                    data: "nama",
                    className: "text-left",
                    orderable: 'true',
                    render: function(data, type, row, meta) {
                        if(type != 'display') {
                            return data;
                        }

                        return row['nama']+ '<a target="_blank" href="{$base_url}home/detailpendaftaran?peserta_didik_id=' +row['peserta_didik_id']+ '"> <i class="fa fas fa-external-link"></i></a>';
                    }
                },
                {
                    data: "nomor_pendaftaran",
                    className: "text-center",
                    orderable: 'true',
                },
                {
                    data: "nisn",
                    className: "text-center",
                    orderable: 'true',
                },
                {
                    data: "sekolah_asal",
                    className: "text-left",
                    orderable: 'true',
                },
                {
                    data: "jalur",
                    className: "text-center",
                    orderable: 'true',
                },
                {
                    data: "label_jenis_pilihan",
                    className: "text-center",
                    orderable: 'true',
                },
                {
                    data: "created_on",
                    className: "text-center",
                    orderable: 'true',
                    render: function ( data, type, row ) {
                        if (typeof data === 'undefined' || data == null || data == "0000-00-00 00:00:00") {
                            data = "";
                        }

                        if (type == "display" && data != "") {
                            data = moment.utc(data).local().format('YYYY-MM-DD HH:mm:ss');
                        }

                        return data;
                    },
                },
                {
                    data: "sedang_verifikasi",
                    className: "text-center",
                    orderable: 'true',
                },
                {if $cek_waktuverifikasi}
                {
                    data: null,
                    className: "text-center",
                    orderable: 'false',
                    defaultContent: '',
                    render: function(data, type, row, meta) {
                        if (type != 'display') {
                            return data;
                        }

                        //return row['kelengkapan_berkas'];

                        {if ($cek_waktuverifikasi|default: FALSE)}
                        return '<a onclick=verifikasi_pendaftaran(' +row['pendaftaran_id']+ ') href="#" class="btn btn-xs btn-primary">Verifikasi</a>';
                        {/if}

                        return data;
                    }
                },
                {/if}
            ],
            order: [ [5, 'asc'] ],
            initComplete: function() {
                // //check for error
                // if (json.error != null && json.error != "") {
                //     if (json.error == 'not-login' || json.errorno == -1) {
                //         window.location.replace("{$site_url}auth");
                //         return;
                //     }
                //     else {
                //         toastr.error("Tidak berhasil mendapatkan data terbaru. " +json.error);
                //         return;
                //     }
                // }

                len = this.api().rows().count();
                if (len == 0) {
                    $('#label-sedang').html("Belum Lengkap");
                }
                else {
                    $('#label-sedang').html("Belum Lengkap (" +len+ ")");
                }
            },
        });

        dt_sudah = $('#tabelsudah').DataTable({
            "responsive": true,
            "processing": true,
            "pageLength": 25,
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
            "paging": true,
            "pagingType": "numbers",
            "dom": 'Bfrtpil',
            "buttons": [
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
            "ajax": "{$base_url}ppdb/sekolah/verifikasi/sudahlengkap?sekolah_id={$sekolah_id}",
            "columns": [
                {
                    data: "nama",
                    className: "text-left",
                    orderable: 'true',
                    render: function(data, type, row, meta) {
                        if(type != 'display') {
                            return data;
                        }

                        return row['nama']+ '<a target="_blank" href="{$base_url}home/detailpendaftaran?peserta_didik_id=' +row['peserta_didik_id']+ '"> <i class="fa fas fa-external-link"></i></a>';
                    }
                },
                {
                    data: "nomor_pendaftaran",
                    className: "text-center",
                    orderable: 'true',
                },
                {
                    data: "nisn",
                    className: "text-center",
                    orderable: 'true',
                },
                {
                    data: "sekolah_asal",
                    className: "text-left",
                    orderable: 'true',
                },
                {
                    data: "jalur",
                    className: "text-center",
                    orderable: 'true',
                },
                {
                    data: "label_jenis_pilihan",
                    className: "text-center",
                    orderable: 'true',
                },
                {
                    data: "tanggal_verifikasi_berkas",
                    className: "text-center",
                    orderable: 'true',
                    render: function ( data, type, row ) {
                        if (typeof data === 'undefined' || data == null || data == "0000-00-00 00:00:00") {
                            data = "";
                        }

                        if (type == "display" && data != "") {
                            data = moment.utc(data).local().format('YYYY-MM-DD HH:mm:ss');
                        }

                        return data;
                    },
                },
                {
                    data: "lokasi_berkas",
                    className: "text-left",
                    orderable: 'true',
                },
                {if $cek_waktuverifikasi}
                {
                    data: null,
                    className: "text-center",
                    orderable: 'false',
                    defaultContent: '',
                    render: function(data, type, row, meta) {
                        if (type != 'display') {
                            return data;
                        }

                        //return row['kelengkapan_berkas'];

                        {if ($cek_waktuverifikasi|default: FALSE)}
                        return '<a onclick=verifikasi_pendaftaran(' +row['pendaftaran_id']+ ') href="#" class="btn btn-xs btn-primary">Verifikasi Ulang</a>';
                        {/if}

                        return data;
                    }
                },
                {/if}
            ],
            order: [ [0, 'asc'] ],
            initComplete: function() {
                // //check for error
                // if (json.error != null && json.error != "") {
                //     if (json.error == 'not-login' || json.errorno == -1) {
                //         window.location.replace("{$site_url}auth");
                //         return;
                //     }
                //     else {
                //         toastr.error("Tidak berhasil mendapatkan data terbaru. " +json.error);
                //         return;
                //     }
                // }

                len = this.api().rows().count();
                if (len == 0) {
                    $('#label-sudah').html("Sudah Lengkap");
                }
                else {
                    $('#label-sudah').html("Sudah Lengkap (" +len+ ")");
                }
            },
        });

        dt_berkas = $('#tabelberkas').DataTable({
            "responsive": true,
            "processing": true,
            "pageLength": 25,
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
            "paging": true,
            "pagingType": "numbers",
            "dom": 'Bfrtpil',
            "buttons": [
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
            "ajax": "{$base_url}ppdb/sekolah/verifikasi/berkasdisekolah?sekolah_id={$sekolah_id}",
            "columns": [
                {
                    data: "nama",
                    className: "text-left",
                    orderable: 'true',
                    render: function(data, type, row, meta) {
                        if(type != 'display') {
                            return data;
                        }

                        return row['nama']+ '<a target="_blank" href="{$base_url}home/detailpendaftaran?peserta_didik_id=' +row['peserta_didik_id']+ '"> <i class="fa fas fa-external-link"></i></a>';
                    }
                },
                {
                    data: "nisn",
                    className: "text-center",
                    orderable: 'true',
                },
                {
                    data: "sekolah_asal",
                    className: "text-left",
                    orderable: 'true',
                },
                {
                    data: "kelengkapan_berkas",
                    className: "text-center",
                    orderable: 'true',
                    render: function(data, type, row, meta) {
                        if(type != 'display') {
                            return data;
                        }

                        if (data != 1) {
                            return "Belum Lengkap";
                        }

                        return "Sudah Lengkap";
                    }
                },
                {
                    data: "sedang_verifikasi",
                    className: "text-center",
                    orderable: 'true',
                },
                {if $cek_waktuverifikasi}
                {
                    data: null,
                    className: "text-center",
                    orderable: 'false',
                    defaultContent: '',
                    render: function(data, type, row, meta) {
                        if (type != 'display') {
                            return data;
                        }

                        //return row['kelengkapan_berkas'];

                        {if ($cek_waktuverifikasi|default: FALSE)}
                        str = '<a onclick=cabut_berkas(' +meta['row']+ ') href="#" class="btn btn-xs btn-danger">Cabut Berkas</a>';
                        return str;
                        {/if}

                        return data;
                    }
                },
                {/if}
            ],
            order: [ [0, 'asc'] ],
            initComplete: function() {
                // //check for error
                // if (json.error != null && json.error != "") {
                //     if (json.error == 'not-login' || json.errorno == -1) {
                //         window.location.replace("{$site_url}auth");
                //         return;
                //     }
                //     else {
                //         toastr.error("Tidak berhasil mendapatkan data terbaru. " +json.error);
                //         return;
                //     }
                // }

                len = this.api().rows().count();
                if (len == 0) {
                    $('#label-berkas').html("Berkas Di Sekolah");
                }
                else {
                    $('#label-berkas').html("Berkas Di Sekolah (" +len+ ")");
                }
            },
        });

        //reload every 5 mins
        setInterval(verifikasi_refresh, 5*60000);

    });

    var verifikasi_refresh = debounce(function () {
        //sedang verifikasi siswa. no need to refresh
        if (verifikasi_siswa) {
            return;
        }

        dt_belum.ajax.reload( function ( json ) {
            //check for error
            if (json.error != null && json.error != "") {
                if (json.error == 'not-login' || json.errorno == -1) {
                    window.location.replace("{$site_url}auth");
                    return;
                }
                else {
                    toastr.error("Tidak berhasil mendapatkan data terbaru. " +json.error);
                    return;
                }
            }

            //update data
            var len = (typeof json.data == 'undefined') ? 0 : json.data.length;
            if (len == 0) {
                $('#label-belum').html("Belum Diverifikasi");
            }
            else {
                $('#label-belum').html("Belum Diverifikasi (" +len+ ")");
            }
        }, true );

        dt_sedang.ajax.reload( function ( json ) {
            //check for error
            if (json.error != null && json.error != "") {
                if (json.error == 'not-login' || json.errorno == -1) {
                    window.location.replace("{$site_url}auth");
                    return;
                }
                else {
                    toastr.error("Tidak berhasil mendapatkan data terbaru. " +json.error);
                    return;
                }
            }

            //update data
            var len = (typeof json.data == 'undefined') ? 0 : json.data.length;
            if (len == 0) {
                $('#label-sedang').html("Belum Lengkap");
            }
            else {
                $('#label-sedang').html("Belum Lengkap (" +len+ ")");
            }
        }, true );

        dt_sudah.ajax.reload( function ( json ) {
            //check for error
            if (json.error != null && json.error != "") {
                if (json.error == 'not-login' || json.errorno == -1) {
                    window.location.replace("{$site_url}auth");
                    return;
                }
                else {
                    toastr.error("Tidak berhasil mendapatkan data terbaru. " +json.error);
                    return;
                }
            }

            //update data
            var len = (typeof json.data == 'undefined') ? 0 : json.data.length;
            if (len == 0) {
                $('#label-sudah').html("Sudah Lengkap");
            }
            else {
                $('#label-sudah').html("Sudah Lengkap (" +len+ ")");
            }
        }, true );

        dt_berkas.ajax.reload( function ( json ) {
            //check for error
            if (json.error != null && json.error != "") {
                if (json.error == 'not-login' || json.errorno == -1) {
                    window.location.replace("{$site_url}auth");
                    return;
                }
                else {
                    toastr.error("Tidak berhasil mendapatkan data terbaru. " +json.error);
                    return;
                }
            }

            //update data
            var len = (typeof json.data == 'undefined') ? 0 : json.data.length;
            if (len == 0) {
                $('#label-berkas').html("Berkas Di Sekolah");
            }
            else {
                $('#label-berkas').html("Berkas Di Sekolah (" +len+ ")");
            }
        }, true );
    }, 1000);


    function cabut_berkas(rowid) {
        let data = dt_berkas.rows(rowid).data();
        let peserta_didik_id = data[0].peserta_didik_id;
        let nama = data[0].nama;
        let nisn = data[0].nisn;

        //peserta_didik_id, nama, nisn
        // render template
        let template = $('#cabut-berkas').html();
        Mustache.parse(template);

        let dom = Mustache.render(template, {
            'nama'   : nama,
            'nisn'   : nisn,
        });

        $.confirm({
                title: 'Cabut Berkas?',
                content: dom,
                closeIcon: true,
                columnClass: 'medium',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    // confirm: function () {
                    //     $.alert('Confirmed!');
                    // },
                    cancel: {
                        text: 'Batal',
                        keys: ['enter', 'shift'],
                        action: function(){
                            //do nothing
                        }
                    },
                    confirm: {
                        text: 'Cabut Berkas',
                        btnClass: 'btn-danger',
                        action: function(){
                            var keterangan = this.$content.find('#keterangan');
                            if(!keterangan.val().trim()){
                                keterangan.addClass("border-red");
                                return false;
                            }

                            $.confirm({
                                title: 'Seriously?',
                                content: '<p>SEKALI LAGI. Apakah anda benar-benar akan melakukan Cabut Berkas an. ' +nama+ '?</p><p><b>Setelah cabut berkas, akun yang bersangkutan akan diblok dan tidak akan bisa masuk ke sistem PPDB Online!</b></p>',
                                icon: 'fa fa-warning',
                                columnClass: 'medium',
                                animation: 'scale',
                                // closeAnimation: 'zoom',
                                buttons: {
                                    confirm: {
                                        text: 'Ya, Benar!',
                                        btnClass: 'btn-danger',
                                        action: function(){
                                            alert('TODO');
                                        }
                                    },
                                    cancel: {
                                        text: 'Batal',
                                        keys: ['enter', 'shift'],
                                        action: function(){
                                            //do nothing
                                        }
                                    },
                                }
                            });
                        }
                        },
                }
            });      

    }

</script>

{include file="./_verifikasisiswa.tpl"}
