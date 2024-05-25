<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Editor-1.9.2/css/editor.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ppdb.css">
<script src="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/JSZip-2.5.0/jszip.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/pdfmake-0.1.36/pdfmake.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/buttons.print.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js"></script>

<?php
		function tanggal_indo($tanggal)
		{
			//echo "tanggal: $tanggal <br>";
			if (empty($tanggal))
				return $tanggal;

			$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
			$part = explode(' ', $tanggal, 2);
			$split = explode('-', $part[0]);
			return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0] . ' ' . $part[1];
		}
?>

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
							<?php $i=1; foreach($daftarsekolah->getResult() as $row):
								if ($row->kuota == 0) {
									continue;
								}
							?>
							<tr>
								<td class="text-left"><?php echo $row->nama;?></td>
								<td class="text-center"><?php echo $row->npsn;?></td>
								<td class="text-center"><?php echo $row->kuota;?></td>
								<td class="text-center"><?php echo $row->total_pendaftar;?></td>
								<td class="text-center"><?php echo $row->diterima;?></td>
								<td class="text-center"><?php echo ($row->kuota - $row->diterima);?></td>
								<td class="text-center"><?php echo $row->daftar_ulang;?></td>
								<td class="text-center"><?php echo $row->kekurangan_daftar_ulang;?></td>
								<td class="text-center"><a href="<?php echo base_url();?>index.php/home/peringkatfinal?sekolah_id=<?php echo $row->sekolah_id;?>" class="btn btn-xs btn-primary">Peringkat</a></td>
							</tr>
							<?php $i++; endforeach;?>
						</tbody>
					</table>
				
				</div>	
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$.extend( $.fn.dataTable.defaults, { responsive: true } );

    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
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