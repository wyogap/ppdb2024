<script type="text/javascript" defer>
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


        $('#tnegeri').dataTable({
            "responsive": true,
            "pageLength": 50,
            "lengthMenu": [
                [50, 100, 200, -1],
                [50, 100, 200, "All"]
            ],
            "paging": true,
            "pagingType": "numbers",
            "dom": 'Bfrtpil',
            select: true,
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'pdfHtml5',
                'print',
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
            ajax: "{$site_url}sekolah/kandidatswasta/json?tahun_ajaran={$tahun_ajaran_id}",
            columns: [{
                    "data": "id",
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: "nama",
                    className: 'dt-body-left readonly-column',
                    render: function(data, type, row, meta) {
                        return '{$site_url}home/detailpendaftaran?peserta_didik_id=' + row['peserta_didik_id'] + '" target="_blank">' + data + '</a>';
                    }
                },
                {
                    data: "nisn",
                    className: 'dt-body-center readonly-column'
                },
                {
                    data: "sekolah",
                    className: 'dt-body-left'
                },
                {
                    data: "jarak",
                    className: 'dt-body-center'
                },
                {
                    data: "alamat",
                    className: 'dt-body-left'
                }
            ],
            order: [
                [4, 'asc']
            ],
        });

    });
</script>