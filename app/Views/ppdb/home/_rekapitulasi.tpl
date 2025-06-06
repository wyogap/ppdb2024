<script type="text/javascript">
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

    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $.fn.dataTable.tables( { visible: true, api: true } ).columns.adjust().responsive.recalc();
    } );
    
    // Tabel
    $(document).ready(function() {

        $('#tdaftarpendaftar').dataTable({
            "responsive": true,
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
            order: [ 1, 'asc' ],
        });
    });

    /* Recalculates the size of the resposive DataTable */
    function recalculateDataTableResponsiveSize() {
        $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust()
        .responsive.recalc(); 
        //$($.fn.dataTable.tables(true)).DataTable().responsive.recalc();
    }

    function show_rekapitulasi($jenjang_id, $nama) {
        //alert($nama);

        $("#nama-jenjang").text($nama);
        $('.page-tabs .btn').each(function(i, obj) {
            if ($(this).attr('tcg-jenjang-id') == $jenjang_id) {
                $(this).hide();
            }
            else {
                $(this).show();
            }
        });
    }
</script>