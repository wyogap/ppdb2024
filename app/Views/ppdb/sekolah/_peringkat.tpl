<script type="text/javascript">
        $.extend( $.fn.dataTable.defaults, { responsive: true } );

        $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
            $.fn.dataTable.tables( { visible: true, api: true } ).columns.adjust().responsive.recalc();
        } );
        
        // Tabel
        $(document).ready(function() {
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })

            {foreach $daftarpenerapan as $row}
            $('#t{$row.penerapan_id}').dataTable({
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
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                } ],
                "order": [[ 7, 'desc' ]]
            });
            {/foreach}

            $('#tdaftarpendaftar').dataTable({
                "responsive": true,
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
                }		
            });
        });

        /* Recalculates the size of the resposive DataTable */
        function recalculateDataTableResponsiveSize() {
            $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust()
            .responsive.recalc(); 
        }
        
        // $('#tabs').tabs({
        // 	activate: recalculateDataTableResponsiveSize,
        // 	create: recalculateDataTableResponsiveSize
        // });

    </script>