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
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })

            {foreach $daftarpenerapan as $row}
            $('#t{$row.penerapan_id}').dataTable({
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
                "columnDefs": [ 
                {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                },
                {
                    "targets": 7,
                    className: "text-end",
                    render: function (data, type, row, meta) {
						if (type=='display') {
							return $.fn.dataTable.render.number('.', ',', 2, '').display(data);
						}
						return data;
                    }
                },
                ],
                "order": [[ 7, 'desc' ]]
            });
            {/foreach}

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
                "columnDefs": [ 
                {
                    "targets": 7,
                    className: "text-end",
                    render: function (data, type, row, meta) {
						if (type=='display') {
							return $.fn.dataTable.render.number('.', ',', 2, '').display(data);
						}
						return data;
                    }
                },
                ],
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