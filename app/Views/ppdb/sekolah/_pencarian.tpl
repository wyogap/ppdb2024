<script type="text/javascript" defer>
    var dt = null;

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
                            data = data+ ' <a href="{$site_url}home/detailpendaftaran?peserta_didik_id=' + row['peserta_didik_id'] + '" target="_blank"><i class="fa fas fa-external-link"></i></a>';
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
                    jenjang: $("#f_jenjang").val(),
                    asaldata: $("#f_asaldata").val(),
                    inklusi: $("#f_inklusi").val(),
                    afirmasi: $("#f_afirmasi").val()
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
                        toastr.error(response.error);
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
                        window.location.href = "{$site_url}" +'auth';
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