<script type="text/javascript">
    var pendaftaranditerima = {$pendaftarditerima|json_encode};

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
		dt = $('#tnegeri').DataTable({
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
					"targets": [8,11,12]
				},
			],
			order: [ [3, 'asc'] ],
            "drawCallback": function( settings ) {
                on_dt_refresh();
            },
            "footerCallback": function ( row, data, start, end, display ) {
                on_dt_refresh();
            },
 		});

        //create tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        dt.on('order.dt search.dt', function () {
                let i = 1;
        
                dt.cells(null, 0, { search: 'applied', order: 'applied' })
                    .every(function (cell) {
                        this.data(i++);
                    });
            })
            .draw();
        });

    function daftar_ulang(pendaftaran_id) {

    }

    function daftar_ulang(pendaftaran_id) {
        let pendaftaran = null;
        
        for(i=0; i<pendaftaranditerima.length; i++) {
            if (pendaftaranditerima[i]['pendaftaran_id'] == pendaftaran_id) {
                pendaftaran = pendaftaranditerima[i];
                break;
            }
        }

        if (pendaftaran == null) {
            toastr.error("Pendaftaran tidak ditemukan");
            return;
        }

        let nama = pendaftaran['nama'];
        let peserta_didik_id = pendaftaran['peserta_didik_id'];

        $.confirm({
            title: 'Daftar Ulang',
            content: 'Konfirmasi Daftar Ulang an. ' +nama+ "?",
            icon: 'fa fa-warning',
            columnClass: 'medium',
            animation: 'scale',
            // closeAnimation: 'zoom',
            buttons: {
                confirm: {
                    text: 'Ya, Benar!',
                    btnClass: 'btn-danger',
                    action: function(){
                        
                        json = {};
                        json['pendaftaran_id'] = pendaftaran_id;

                        //alert("TODO"); return true;
                        status = $.ajax({
                            type: 'POST',
                            url: "{$site_url}ppdb/sekolah/daftarulang/dodaftarulang",
                            dataType: 'json',
                            data: json,
                            async: false,
                            cache: false,
                            //if we use formData, set processData = false. if we use json, set processData = true!
                            //contentType: true,
                            //processData: true,      
                            timeout: 60000,
                            success: function(json) {
                                if (json.status == 0) {
                                    if (json.error != null) {
                                        toastr.error("Tidak berhasil daftar ulang an." +nama+ ": " + json.error);
                                    }
                                    else {
                                        toastr.error("Tidak berhasil daftar ulang an." +nama);
                                    }
                                    return true;
                                }

                                toastr.success("Berhasil daftar ulang an." +nama);

                                //reload table
                                str = '<a href="{$site_url}ppdb/sekolah/daftarulang/buktipendaftaran?peserta_didik_id=' +peserta_didik_id+ '" target="_blank" '
                                        +'class="btn btn-secondary btn-xs" data-bs-toggle="tooltip" title="Bukti Daftar Ulang" data-bs-placement="bottom">Bukti DU</a>';

                                $('.row-action[dt-pendaftaran-id="' +pendaftaran_id+ '"]').html(str);

                                str = moment().format('YYYY-MM-DD HH:mm:ss');

                                $('.tgl-daftar-ulang[dt-pendaftaran-id="' +pendaftaran_id+ '"]').html(str);

                                return true;
                            },
                            error: function(jqXhr, textStatus, errorThrown) {
                                if (jqXhr.status == 403 || textStatus == 'Forbidden' || 
                                        (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                                    ) {
                                    //login ulang
                                    window.location.href = "{$site_url}" +'auth';
                                }
                                //send toastr message
                                toastr.error("Gagal mengambil data via ajax");
                                return false;
                            }
                        });
                    }
                },
                cancel: {
                    text: 'Batal',
                    action: function(){
                        //do nothing
                    }
                },
            }
        });    

    }
</script>