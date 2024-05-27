<script type="text/javascript">
    // Tabel
    var dt_siswa_kls6 = null;

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

        dt_siswa_kls6 = $('#tnegeri').DataTable({
            "responsive": true,
            "pageLength": 25,
            "lengthMenu": [
                [25, 50, 100, -1],
                [25, 50, 100, "All"]
            ],
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
                "sProcessing": "Sedang proses...",
                "sLengthMenu": "Tampilan _MENU_ baris",
                "sZeroRecords": "Tidak ditemukan data yang sesuai",
                "sInfo": "Tampilan _START_ - _END_ dari _TOTAL_ baris",
                "sInfoEmpty": "Tampilan 0 hingga 0 dari 0 baris",
                "sInfoFiltered": "(disaring dari _MAX_ baris keseluruhan)",
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
            "ajax": {
                "type": "GET",
                "url": "{$site_url}ppdb/dapodik/daftarsiswa/json",
                "dataSrc": function(json) {
                    //hide loader
                    $("#loading").hide();

                    //actual data source
                    return json.data;
                }
            },
            columns: [{
                    data: "nama",
                    className: 'dt-body-left',
                },
                {
                    data: "nisn",
                    className: 'dt-body-center'
                },
                {
                    data: "lintang",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "bujur",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "updated_on",
                    className: 'dt-body-center text-nowrap'
                },
                {
                    data: "nik",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "jenis_kelamin",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "tempat_lahir",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "tanggal_lahir",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "nama_ibu_kandung",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "nama_ayah",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "alamat",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "rt",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "rw",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "nama_dusun",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "desa_kelurahan",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: null,
                    className: 'dt-body-center',
                    orderable: false,
                    "render": function(data, type, row, meta) {
                        return "<button onclick=ubah_data(" +row['peserta_didik_id']+ ") class='btn btn-primary btn-xs'>Ubah Data</button>";
                    }
                },
            ],
            order: [0, 'asc'],
        });

        // Edit record
        $('#tnegeri').on('click', 'a.dt-btn-inline', function(e) {
            e.stopPropagation();
        });

    });

</script>

{include file="./_ubahdata.tpl"}