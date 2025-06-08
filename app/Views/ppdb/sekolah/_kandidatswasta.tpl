<script type="text/javascript" defer>
    $(document).ready(function() {
        $.extend($.fn.dataTable.defaults, {
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
        });

        //change error message from html pop-up to toastr.
        $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) { 
            if (message.search("not-login") >= 0) {
                toastr.error("Sesi login sudah kadaluarsa. Silahkan login kembali.");
            }
            else {
                toastr.error(message);
            }
        };

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            $.fn.dataTable.tables({
                visible: true,
                api: true
            }).columns.adjust().responsive.recalc();
        });


        $('#tnegeri').dataTable({
            "responsive": true,
            "processing": true,
            "pageLength": 25,
            "lengthMenu": [
                [25, 50, 100, -1],
                [25, 50, 100, "All"]
            ],
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
            ajax: "{$site_url}ppdb/sekolah/kandidatswasta/json?tahun_ajaran={$tahun_ajaran_id}",
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