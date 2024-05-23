<script type="text/javascript">

	$.extend( $.fn.dataTable.defaults, { responsive: true } );

    $('a[data-bs-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $.fn.dataTable.tables( { visible: true, api: true } ).columns.adjust().responsive.recalc();
    } );
     
    var on_dt_refresh = debounce(function () {
        //recreate tooltip. it is lost after redraw()
        //TODO: still not working it. FIX ME!
        $('[data-bs-toggle="tooltip"]').tooltip();
    }, 1000);

	// Tabel
	$(document).ready(function() {
		{foreach $daftarpenerapan as $row}
		$('#t{$row.penerapan_id}').dataTable({
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
			},		
			"columnDefs": [
				{
					// The `data` parameter refers to the data for the cell (defined by the
					// `data` option, which defaults to the column being worked with, in
					// this case `data: 0`.
                    className: "text-end",
					"render": function ( data, type, row ) {
						if (type=='display') {
							return $.fn.dataTable.render.number(',', '.', 2, '').display(data);
						}
						return data;
					},
					"targets": [9,10]
				},
			],
			order: [ [9, 'asc'] ],
            "drawCallback": function( settings ) {
                on_dt_refresh();
            },
            "footerCallback": function ( row, data, start, end, display ) {
                on_dt_refresh();
            },
 		});
		{/foreach}

        //create tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
	});

</script>