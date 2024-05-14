<!DOCTYPE html>
<html>
{include file='../header.tpl'}

<?php
$this->load->helper('url');
?>

<body class="hold-transition skin-black layout-top-nav">
    <div class="wrapper">
        {include file='../navigation.tpl'}
        <div class="content-wrapper">
            <div class="container">
                <section class="content-header">
                    <!-- <div class="row"> -->
                    <h1 class="text-white">
                        <i class="glyphicon glyphicon-list-alt"></i> Daftar Berkas Di Sekolah</small>
                    </h1>
                    <!-- </div> -->
                </section>
                <section class="content">

                    <span><?php if (isset($info)) {
                                echo $info;
                            } ?></span>

                    <div class="box box-solid">
                        <!-- <div class="box-header with-border">
                            <i class="glyphicon glyphicon-edit text-info"></i>
                            <h3 class="box-title text-info"><b>Kuota Sekolah</b></h3>
                        </div> -->
                        <div class="box-body">

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table class="display" id="tnegeri" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center" data-priority="1">#</th>
                                                <th class="text-center" data-priority="2">Nama</th>
                                                <th class="text-center">NISN</th>
                                                <th class="text-center" data-priority="4">Asal Sekolah</th>
                                                <th class="text-center">Kelengkapan Berkas</th>
                                                <th class="text-center">Sedang Verifikasi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <!-- </div>
                                </div>
                            </div> -->
                                </div>
                            </div>

                        </div>
                    </div>

                </section>
            </div>
        </div>
        {include file='../footer.tpl'}
    </div>
</body>

<script type="text/javascript">
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


        $('#tnegeri').dataTable({
            "responsive": true,
            "pageLength": 50,
            "lengthMenu": [
                [50, 100, 200, -1],
                [50, 100, 200, "All"]
            ],
            "paging": true,
            "pagingType": "numbers",
            "dom": 'Bfrtpil',
            select: true,
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'pdfHtml5',
                'print',
            ],
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
            ajax: "<?php echo base_url(); ?>index.php/sekolah/berkasdisekolah/json?sekolah_id=<?php echo $sekolah_id; ?>",
            "columns": [{
                    data: null,
                    className: "text-right",
                    orderable: 'false',
                    defaultContent: '',
                    render: function(data, type, row, meta) {
                        if (type != 'display') {
                            return data;
                        }

                        //return row['kelengkapan_berkas'];

                        <?php if ($waktuverifikasi == 1) { ?>
                            if (row['kelengkapan_berkas'] != 1) {
                                return '<a href="<?php echo base_url(); ?>index.php/sekolah/verifikasi/siswa?pendaftaran_id=' + row['pendaftaran_id'] + '" class="btn btn-xs btn-primary">Verifikasi</a>';
                            }
                        <?php } ?>

                        return data;
                    }
                },
                {
                    data: "nama",
                    className: "text-left",
                    orderable: 'true',
                    render: function(data, type, row, meta) {
                        if (type != 'display') {
                            return data;
                        }

                        return '<a href="<?php echo base_url(); ?>index.php/Chome/detailpendaftaran?peserta_didik_id=' + row['peserta_didik_id'] + '">' + row['nama'] + '</a>';
                    }
                },
                {
                    data: "nisn",
                    className: "text-center",
                    orderable: 'true',
                },
                {
                    data: "sekolah_asal",
                    className: "text-left",
                    orderable: 'true',
                },
                {
                    data: "kelengkapan_berkas",
                    className: "text-center",
                    orderable: 'true',
                    render: function(data, type, row, meta) {
                        if (type != 'display') {
                            return data;
                        }

                        if (data != 1) {
                            return "Belum Lengkap";
                        }

                        return "Sudah Lengkap";
                    }
                },
                // {
                // 	data: "jenis_pilihan",
                // 	className: "text-center",
                // 	orderable: 'true',
                // },
                // {
                // 	data: "create_date",
                // 	className: "text-center",
                // 	orderable: 'true',
                // },
                {
                    data: "sedang_verifikasi",
                    className: "text-center",
                    orderable: 'true',
                    render: function(data, type, row, meta) {
                        if (row['kelengkapan_berkas'] != 1) {
                            return data;
                        }

                        return 'Sudah lengkap';
                    }
                },
            ],
            order: [
                [1, 'asc']
            ],
        });

    });
</script>

</html>