<script type="text/javascript" defer>
    var dt = null;

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

        dt = $('#tnegeri').DataTable({
            "responsive": true,
            "processing": true,
            "pageLength": 25,
            "lengthMenu": [
                [25, 50, 100, -1],
                [25, 50, 100, "All"]
            ],
            "paging": true,
            "pagingType": "numbers",
            "dom": 'tpil',
            //deferLoading: 0,
            // select: true,
            // buttons: [
			// 	{
			// 		extend: 'excelHtml5',
			// 		text: 'Ekspor',
			// 		className: 'btn-sm btn-primary',
			// 		exportOptions: {
			// 			orthogonal: "export",
			// 			modifier: {
			// 				//selected: true
			// 			},
			// 		},
			// 	},
            // ],
            //ajax: "{$site_url}ppdb/sekolah/pencarian/json?tahun_ajaran={$tahun_ajaran_id}",
            ajax: function(data, callback, settings) {
                dt_ajax_search()
                .then(function(json) { callback(json); });
            },
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
                    data: "tempat_lahir",
                    className: 'dt-body-center'
                },
                {
                    data: "tanggal_lahir",
                    className: 'dt-body-left'
                },
                {
                    data: "asal_sekolah",
                    className: 'dt-body-left'
                },
                {
                    data: "status_pendaftaran",
                    className: 'dt-body-left',
                    render: function(data, type, row, meta) {
                        if (data == null) return data;
                        if (type == 'display') {
                            data = '<a href="{$site_url}home/detailpendaftaran?peserta_didik_id=' + row['peserta_didik_id'] + '" target="_blank">' +data+ ' <i class="fa fas fa-external-link-alt"></i></a>';
                        }
                        return data;
                    }
                },
                {
                    data: "kelengkapan_berkas",
                    className: 'dt-body-center',
                    render: function(data, type, row, meta) {
                        if (type == 'display') {
                            if (data == null) {
                                data = '';
                            }
                            else if (data == '1') {
                                data = 'LENGKAP';
                            }
                            else {
                                data = 'TIDAK LENGKAP';
                            }
                        }
                        return data;
                    }
                },
                {
                    data: "lokasi_berkas",
                    className: 'dt-body-left'
                },
            ],
            order: [
                [0, 'asc']
            ],
        });

        $('.adv-search-btn').click(function(e) {
            $('.adv-search-box').toggle();
        });

        $('.btn-search').click(function(e) {
            e.stopPropagation();
            //reload, reset paging
            dosearch();
        });

        $("#search").keyup(function (e) {
            if (e.which == 13) {
                $('.btn-search').trigger('click');
            }
        });        
    });

    function dosearch() {
        el = $("#search");
        val = $.trim(el.val());

        if (val == "") {
            toastr.info("Tidak ada kata pencarian.");
        }
        else {
            dt.ajax.reload();
        }
    }

    function dt_ajax_search() {
        return new Promise(function(resolve, reject) {
            el = $("#search");
            val = $.trim(el.val());
           
            if (val == "") {
                resolve({
                    data: [],
                });
                return;
            }

            $.ajax({
                "url": "{$site_url}ppdb/sekolah/pencarian/cari",
                "dataType": "json",
                "type": "POST",
                "data": {
                    search: val,
                    f_jenjang: $("#f_jenjang").val(),
                    f_asaldata: $("#f_asaldata").val(),
                    f_inklusi: $("#f_inklusi").val(),
                    f_afirmasi: $("#f_afirmasi").val()
                },
                beforeSend: function(request) {
                    request.setRequestHeader("Content-Type",
                        "application/x-www-form-urlencoded; charset=UTF-8");
                },
                success: function(response) {
                    let data = [];
                    if (response.data === null) {
                        toastr.error("Gagal mengambil data via ajax");
                        data = [];
                    } else if (typeof response.error !== 'undefined' && response.error !== null && response.error != "") {
                        toastr.error("Gagal mengambil data via ajax: " .response.error);
                        data = [];
                    } else {
                        data = response.data;
                    }

                    resolve({
                        data: data,
                    });
                },
                error: function(jqXhr, textStatus, errorMessage) {
                    if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                            (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                            && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                        ) {
                        //login ulang
                        window.location.href = "{$site_url}auth";
                    }
                    //send toastr message
                    toastr.error("Gagal mengambil data via ajax");
                    resolve({
                        data: [],
                    });
                }
            });
        });
    }    
</script>