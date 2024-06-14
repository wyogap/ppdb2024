<script type="text/javascript">
    // Tabel
    var dt_siswa_kls6 = null;

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

        dt_siswa_kls6 = $('#tnegeri').DataTable({
            "responsive": true,
            "pageLength": 25,
            "lengthMenu": [
                [25, 50, 100, -1],
                [25, 50, 100, "All"]
            ],
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
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
 
                        sheet.querySelectorAll('row c[r^="C"]').forEach((el) => {
                            el.setAttribute('s', '50');
                        });
                    }				
                },
            ],
            "language": {
                "sProcessing": "Sedang proses...",
                "sLengthMenu": "Tampilan _MENU_ baris",
                "sZeroRecords": "Tidak ditemukan data yang sesuai",
                "sInfo": "Tampilan _START_ - _END_ dari _TOTAL_ baris",
                "sInfoEmpty": "Tampilan 0 hingga 0 dari 0 baris",
                "sInfoFiltered": "(disaring dari _MAX_ baris keseluruhan)",
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
            "ajax": {
                "type": "GET",
                "url": "{$site_url}ppdb/dapodik/daftarsiswa/json",
                "dataSrc": function(json) {
                    //hide loader
                    $("#loading").hide();

                    //actual data source
                    return json.data;
                }
            },
            columns: [{
                    data: "nama",
                    className: 'dt-body-left',
                },
                {
                    data: "nisn",
                    className: 'dt-body-center'
                },
                {
                    data: "lintang",
                    className: 'dt-body-center',
                    orderable: false,
                },
                {
                    data: "bujur",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "updated_on",
                    className: 'dt-body-center text-nowrap'
                },
                {
                    data: "nik",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "jenis_kelamin",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "tempat_lahir",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "tanggal_lahir",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "nama_ibu_kandung",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "nama_ayah",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "alamat",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "rt",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "rw",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "nama_dusun",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "desa_kelurahan",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: null,
                    className: 'dt-body-center',
                    orderable: false,
                    "render": function(data, type, row, meta) {
                        let str = '';
                        if ({if $cek_waktusosialisasi == 1 || $impersonasi_sekolah == 1}true || {/if}row['akses_ubah_data'] == 1) {
                            str = "<button onclick=ubah_data(" +row['peserta_didik_id']+ ") class='btn btn-primary btn-xs text-nowrap mb-1'>Ubah Data</button><br>";
                        }
                        str += "<button onclick=reset_password(" +row['peserta_didik_id']+ "," +meta['row']+ ") class='btn btn-danger btn-xs text-nowrap'>Reset PIN</button>";
                        return str;
                    }
                },
            ],
            order: [0, 'asc'],
        });

        // Edit record
        $('#tnegeri').on('click', 'a.dt-btn-inline', function(e) {
            e.stopPropagation();
        });

    });

</script>

{* Popup to reset password *}
<script type="text/javascript">
    function reset_password(peserta_didik_id, rowid) {
        let data = dt_siswa_kls6.rows(rowid).data();
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
            url: "{$site_url}ppdb/dapodik/daftarsiswa/resetpassword",
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

{include file="./_ubahdata.tpl"}