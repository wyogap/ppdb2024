
<script>
    $.extend( $.fn.dataTable.defaults, { responsive: true } );

    $('a[data-bs-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $.fn.dataTable.tables( { visible: true, api: true } ).columns.adjust().responsive.recalc();
    } );

    var dt_belum, dt_sedang, dt_sudah, dt_berkas;

    $(document).ready(function() {
        dt_belum = $('#tabelbelum').DataTable({
            "responsive": true,
            "processing": true,
            "pageLength": 50,
            "lengthMenu": [ [50, 100, 200, -1], [50, 100, 200, "All"] ],
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
            ],
            order: [ [5, 'asc'] ],
            initComplete: function() {
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
            "pageLength": 50,
            "lengthMenu": [ [50, 100, 200, -1], [50, 100, 200, "All"] ],
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
            ],
            order: [ [5, 'asc'] ],
            initComplete: function() {
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
            "pageLength": 50,
            "lengthMenu": [ [50, 100, 200, -1], [50, 100, 200, "All"] ],
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
            ],
            order: [ [0, 'asc'] ],
            initComplete: function() {
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
            "pageLength": 50,
            "lengthMenu": [ [50, 100, 200, -1], [50, 100, 200, "All"] ],
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
                    render: function(data, type, row, meta) {
                        if (row['kelengkapan_berkas'] != 1) {
                            return data;
                        }

                        return 'Sudah lengkap';
                    }
                },
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
            ],
            order: [ [0, 'asc'] ],
            initComplete: function() {
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
        setInterval(function(){
            dt_belum.ajax.reload( function ( json ) {
                var len = (typeof json.data == 'undefined') ? 0 : json.data.length;
                if (len == 0) {
                    $('#label-belum').html("Belum Diverifikasi");
                }
                else {
                    $('#label-belum').html("Belum Diverifikasi (" +len+ ")");
                }
            }, true );

            dt_sedang.ajax.reload( function ( json ) {
                var len = (typeof json.data == 'undefined') ? 0 : json.data.length;
                if (len == 0) {
                    $('#label-sedang').html("Belum Lengkap");
                }
                else {
                    $('#label-sedang').html("Belum Lengkap (" +len+ ")");
                }
            }, true );

            dt_sudah.ajax.reload( function ( json ) {
                var len = (typeof json.data == 'undefined') ? 0 : json.data.length;
                if (len == 0) {
                    $('#label-sudah').html("Sudah Lengkap");
                }
                else {
                    $('#label-sudah').html("Sudah Lengkap (" +len+ ")");
                }
            }, true );

            dt_berkas.ajax.reload( function ( json ) {
                var len = (typeof json.data == 'undefined') ? 0 : json.data.length;
                if (len == 0) {
                    $('#label-berkas').html("Berkas Di Sekolah");
                }
                else {
                    $('#label-berkas').html("Berkas Di Sekolah (" +len+ ")");
                }
            }, true );

            //console.log("reload completed!");
        }, 5*60000);

        var len = dt_belum.rows().count();
        if (len == 0) {
            $('#label-belum').html("Belum Diverifikasi");
        }
        else {
            $('#label-belum').html("Belum Diverifikasi (" +len+ ")");
        }

        // len = dt_sedang.rows().count();
        // if (len == 0) {
        //     $('#label-sedang').html("Belum Lengkap");
        // }
        // else {
        //     $('#label-sedang').html("Belum Lengkap (" +len+ ")");
        // }

        // len = dt_sudah.rows().count();
        // if (len == 0) {
        //     $('#label-sudah').html("Sudah Lengkap");
        // }
        // else {
        //     $('#label-sudah').html("Sudah Lengkap (" +len+ ")");
        // }

        // len = dt_berkas.rows().count();
        // if (len == 0) {
        //     $('#label-berkas').html("Berkas Di Sekolah");
        // }
        // else {
        //     $('#label-berkas').html("Berkas Di Sekolah (" +len+ ")");
        // }
    });

</script>

{include file="./_verifikasisiswa.tpl"}
