<script type="text/javascript" defer>
    var dt;

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
            "dom": 'Bfrtpil',
            select: false,
            buttons: [
				{
                    text: 'Refresh',
                    action: function ( e, dt, node, conf ) {
                        dt.ajax.reload();
                    },
                    className: 'btn-sm btn-custom-action btn-primary'
				},
            ],
            ajax: "{$site_url}ppdb/sekolah/pengajuanakun/json?tahun_ajaran={$tahun_ajaran_id}",
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
                    data: "alamat",
                    className: 'dt-body-left'
                },
                {
                    data: null,
                    className: "text-center",
                    orderable: 'false',
                    defaultContent: '',
                    render: function(data, type, row, meta) {
                        if (type != 'display') {
                            return data;
                        }

                        return '<a onclick=setujui_akun("' +row['user_id']+ '",' +meta.row+ ') href="#" class="btn btn-xs btn-primary">Setujui</a>';
                    }
                },
            ],
            order: [
                [0, 'asc']
            ],
        });

    });

    function setujui_akun(userid, rowIdx) {
 
        $.ajax({
                "url": "{$site_url}ppdb/sekolah/pengajuanakun/approve",
                "dataType": "json",
                "type": "POST",
                "data": {
                    userid: userid,
                },
                beforeSend: function(request) {
                    request.setRequestHeader("Content-Type",
                        "application/x-www-form-urlencoded; charset=UTF-8");
                },
                success: function(response) {
                    //delete the row
                    dt.ajax.reload();
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
       
    }
</script>