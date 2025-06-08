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
            ajax: "{$site_url}ppdb/sekolah/verifikasi/berkasdisekolah?tahun_ajaran={$tahun_ajaran_id}",
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
                    data: "tanggal_lahir",
                    className: 'dt-body-left text-nowrap'
                },
                {
                    data: "sekolah_asal",
                    className: 'dt-body-left'
                },
                {
                    data: "kelengkapan_berkas",
                    className: 'dt-body-center',
                    render: function(data, type, row, meta) {
                        if(type != 'display') {
                            return data;
                        }

                        if (data != 1) {
                            return "Belum Lengkap";
                        }

                        return "Sudah Lengkap";
                    }
                },
                {
                    data: "tanggal_verifikasi",
                    className: 'dt-body-left'
                },
                {
                    data: "verifikasi_oleh",
                    className: 'dt-body-left'
                },
                {
                    data: null,
                    className: 'dt-body-center',
                    orderable: false,
                    "render": function(data, type, row, meta) {
                        return "<button onclick=reset_password(" +row['peserta_didik_id']+ "," +meta['row']+ ") class='btn btn-danger btn-xs text-nowrap'>Reset PIN</button>";
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

{* Popup to reset password *}
<script type="text/javascript">
    function reset_password(peserta_didik_id, rowid) {
        let data = dt.rows(rowid).data();
        if (data.length == 0) return;

        data = data[0];
        nama = data['nama'];

        $.confirm({
            title: 'Reset PIN/Password an. ' +nama,
            content: "<div style='overflow: hidden;'>"
                        +"<input type='password' class='form-control' placeholder='PIN / Password Baru' id='password' name='password' data-validation='required'>"
                        +"<input type='password' class='form-control' placeholder='Masukkan Lagi' id='password2' name='password2' data-validation='required'>"
                        +"<span id='error-msg'>&nbsp</span></div>",
            closeIcon: true,
            columnClass: 'medium',
            //type: 'purple',
            typeAnimated: true,
            buttons: {
                cancel: {
                    text: 'Batal',
                    action: function(){
                        //do nothing
                    }
                },
                confirm: {
                    text: 'Ganti',
                    btnClass: 'btn-primary',
                    action: function(){
                        let el1 = this.$content.find('#password');
                        let el2 = this.$content.find('#password2');
                        if (el1.val().length < 6) {
                            let msg = this.$content.find('#error-msg');
                            msg.html("PIN/Password harus minimal 6 huruf.");
                            el1.addClass('border-red');
                            return false;
                        }
                        else if (el1.val() != el2.val()) {
                            let msg = this.$content.find('#error-msg');
                            msg.html("PIN/Password baru tidak sama.");
                            el2.addClass('border-red');
                            return false;
                        }

                        send_reset_password(peserta_didik_id, nama, el1.val());
                    }
                },
            },

        });      
    }

    function send_reset_password(peserta_didik_id, nama, pwd1) {
        json = {};
        data = {};
        data['pwd1'] = pwd1;
        data['pwd2'] = pwd1;
        
        json['data'] = {};
        json['data'][peserta_didik_id] = data;

        $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/sekolah/Berkasdisekolah/resetpassword",
            dataType: 'json',
            data: json,
            async: true,
            cache: false,
            //if we use formData, set processData = false. if we use json, set processData = true!
            //contentType: true,
            //processData: true,      
            timeout: 60000,
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error('Tidak berhasil mengubah PIN/Password an. ' +nama+ ": " +json.error);
                    return;
                }

                //tambahkan ke daftar pendaftaran
                toastr.success("PIN/Password an. " +nama+ " berhasil diubah.");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error('Tidak berhasil mengubah PIN/Password an. ' +nama+ ": " +textStatus);
                return;
            }
        });

    }
</script>