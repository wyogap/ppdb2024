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
                    <h1 class="text-white">
                        <i class="glyphicon glyphicon-list-alt"></i> Daftar Siswa
                    </h1>
                </section>
                <section class="content">

                    <span><?php if (isset($info)) {
                                echo $info;
                            } ?></span>

                    <?php
                    $error = $this->session->flashdata('error');
                    if ($error) {
                    ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $error; ?>
                        </div>
                    <?php
                    }

                    $success = $this->session->flashdata('success');
                    if ($success) {
                    ?>
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $success; ?>
                        </div>
                    <?php } ?>

                    <div class="box box-solid">
                        <div class="box-body">

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center justify-content-center" id="loading" style="position: absolute; margin-top: 24px; margin-left: -12px;">
                                    <div class="loader" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table class="display" id="tnegeri" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center" data-priority="1">Nama</th>
                                                <th class="text-center" data-priority="3">NISN</th>
                                                <th class="text-center" data-priority="6">Lintang</th>
                                                <th class="text-center" data-priority="7">Bujur</th>
                                                <th class="text-center" data-priority="8">Last Update</th>
                                                <th class="none">NIK</th>
                                                <th class="none">Jenis Kelamin</th>
                                                <th class="none">Tempat Lahir</th>
                                                <th class="none">Tanggal Lahir</th>
                                                <th class="none">Nama Ibu Kandung</th>
                                                <th class="none">Nama Ayah</th>
                                                <th class="none">Alamat</th>
                                                <th class="none">RT</th>
                                                <th class="none">RW</th>
                                                <th class="none">Dusun</th>
                                                <th class="none">Desa/Kelurahan</th>
                                                <th class="text-center" data-priority="2">#</th>
                                            </tr>
                                        </thead>
                                    </table>
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
    // Tabel
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
            "buttons": [
                'copyHtml5',
                'excelHtml5',
                'pdfHtml5',
                'print'
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
            "ajax": {
                "type": "GET",
                "url": "<?php echo site_url('dapodik/beranda/daftarsiswa'); ?>",
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
                    orderable: false
                },
                {
                    data: "bujur",
                    className: 'dt-body-center',
                    orderable: false
                },
                {
                    data: "last_update",
                    className: 'dt-body-center'
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
                    "render": function(data, type, row) {
                        return "<a href='<?php echo base_url(); ?>index.php/dapodik/ubahdata?peserta_didik_id=" + row.peserta_didik_id + "' class='btn-sm btn-primary dt-btn-inline'>Ubah</a>";
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

</html>