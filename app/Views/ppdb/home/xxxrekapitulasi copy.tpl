<!DOCTYPE html>
<html>
    {include file='../header.tpl'}
	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
            {include file='../navigation.tpl'}
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<h1 class="text-white" style="display: inline;">
							<i class="glyphicon glyphicon-th-list"></i> Rekapitulasi<small>Pendaftaran</small>
						</h1>
					</section>
					<section class="content">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="box box-solid">
                                    <!-- <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-search"></i>
                                        <h3 class="box-title"><b>Pencarian Siswa</b></h3>
                                    </div> -->
                                    <div class="box-body">
                                        <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="display" id="tdaftarpendaftar" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" data-priority="1">Sekolah</th>
                                                        <th class="text-center">NPSN</th>
                                                        <th class="text-center">Total Kuota</th>
                                                        <th class="text-center" data-priority="3">Total Pendaftar</th>
                                                        <th class="text-center" data-priority="4">Diterima</th>
                                                        <th class="text-center">Selisih</th>
                                                        <th class="text-center" data-priority="5">Daftar Ulang</th>
                                                        <th class="text-center">Selisih<br/>(Daftar Ulang)</th>
                                                        <th class="text-center" data-priority="1">#</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {foreach $daftarsekolah as $row}
                                                    <tr>
                                                        <td class="text-left">{$row.nama}</td>
                                                        <td class="text-center">{$row.npsn}</td>
                                                        <td class="text-center">{$row.kuota}</td>
                                                        <td class="text-center">{$row.total_pendaftar}</td>
                                                        <td class="text-center">{$row.diterima}</td>
                                                        <td class="text-center">{$row.kuota - $row.diterima}</td>
                                                        <td class="text-center">{$row.daftar_ulang}</td>
                                                        <td class="text-center">{$row.kekurangan_daftar_ulang}</td>
                                                        <td class="text-center"><a href="{$base_url}home/peringkat?sekolah_id={$row.sekolah_id}" class="btn btn-xs btn-primary">Peringkat</a></td>
                                                    </tr>
                                                    {/foreach}
                                                </tbody>
                                            </table>
                                        
                                        </div>	
                                        </div>
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
        $.extend( $.fn.dataTable.defaults, { responsive: true } );

        $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
            $.fn.dataTable.tables( { visible: true, api: true } ).columns.adjust().responsive.recalc();
        } );
        
        // Tabel
        $(document).ready(function() {
            $('#tdaftarpendaftar').dataTable({
                "responsive": true,
                "pageLength": 25,
                "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
                "paging": true,
                "pagingType": "numbers",
                "dom": 'Bfrtpil',
                "buttons": [
                ],
                "language": {
                    "sProcessing":   "Sedang proses...",
                    "sLengthMenu":   "Tampilan _MENU_ baris",
                    "sZeroRecords":  "Tidak ditemukan data yang sesuai",
                    "sInfo":         "Tampilan _START_ - _END_ dari _TOTAL_ baris",
                    "sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 baris",
                    "sInfoFiltered": "(disaring dari _MAX_ baris keseluruhan)",
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
                order: [ 1, 'asc' ],
            });
        });

        /* Recalculates the size of the resposive DataTable */
        function recalculateDataTableResponsiveSize() {
            $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust()
            .responsive.recalc(); 
            //$($.fn.dataTable.tables(true)).DataTable().responsive.recalc();
        }
        
        // $('#tabs').tabs({
        // 	activate: recalculateDataTableResponsiveSize,
        // 	create: recalculateDataTableResponsiveSize
        // });

    </script>
</html>
