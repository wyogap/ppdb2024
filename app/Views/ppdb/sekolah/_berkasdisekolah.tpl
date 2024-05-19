<script type="text/javascript" defer>
    var dt;

    $(document).ready(function() {
        $.extend($.fn.dataTable.defaults, {
            responsive: true
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            $.fn.dataTable.tables({
                visible: true,
                api: true
            }).columns.adjust().responsive.recalc();
        });

        dt = $('#tnegeri').DataTable({
            "responsive": true,
            "processing": true,
            "pageLength": 50,
            "lengthMenu": [
                [50, 100, 200, -1],
                [50, 100, 200, "All"]
            ],
            "paging": true,
            "pagingType": "numbers",
            "dom": 'Bfrtpil',
            select: false,
            buttons: [
				{
                    text: 'Refresh',
                    action: function ( e, dt, node, conf ) {
                        dt.ajax.reload();
                    },
                    className: 'btn-sm btn-custom-action btn-primary'
				},
            ],
            "language": {
                "sProcessing": "Sedang proses...",
                "sLengthMenu": "Tampilan _MENU_ entri",
                "sZeroRecords": "Tidak ditemukan data yang sesuai",
                "sInfo": "Tampilan _START_ - _END_ dari _TOTAL_ entri",
                "sInfoEmpty": "Tampilan 0 hingga 0 dari 0 entri",
                "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "sInfoPostFix": "",
                "sSearch": "Cari:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Awal",
                    "sPrevious": "Balik",
                    "sNext": "Lanjut",
                    "sLast": "Akhir"
                }
            },
            ajax: "{$site_url}ppdb/sekolah/verifikasi/berkasdisekolah?tahun_ajaran={$tahun_ajaran_id}",
            columns: [
                {
                    data: "nama",
                    className: 'dt-body-left readonly-column',
                },
                {
                    data: "nisn",
                    className: 'dt-body-center readonly-column'
                },
                {
                    data: "jenis_kelamin",
                    className: 'dt-body-center'
                },
                {
                    data: "tanggal_lahir",
                    className: 'dt-body-left'
                },
                {
                    data: "sekolah_asal",
                    className: 'dt-body-left'
                },
                {
                    data: "kelengkapan_berkas",
                    className: 'dt-body-center',
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
                    data: "tanggal_verifikasi",
                    className: 'dt-body-left'
                },
                {
                    data: "verifikasi_oleh",
                    className: 'dt-body-left'
                },
            ],
            order: [
                [0, 'asc']
            ],
        });

    });

    function setujui_akun(userid, rowIdx) {
 
        $.ajax({
                "url": "{$site_url}ppdb/sekolah/pengajuanakun/approve",
                "dataType": "json",
                "type": "POST",
                "data": {
                    userid: userid,
                },
                beforeSend: function(request) {
                    request.setRequestHeader("Content-Type",
                        "application/x-www-form-urlencoded; charset=UTF-8");
                },
                success: function(response) {
                    //delete the row
                    dt.ajax.reload();
                },
                error: function(jqXhr, textStatus, errorMessage) {
                    if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                            (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                            && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                        ) {
                        //login ulang
                        window.location.href = "{$site_url}" +'auth';
                    }
                    //send toastr message
                    toastr.error("Gagal mengambil data via ajax");
                    resolve({
                        data: [],
                    });
                }
            });
       
    }
</script>