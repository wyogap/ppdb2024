<?php
	function tanggal_indo($tanggal)
	{
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
		$split = explode('-', $tanggal);
		return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
	}
?>

<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">
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

<span><?php if(isset($info)){echo $info;}?></span>
<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#pendaftar" data-toggle="tab">Pendaftar</a></li>
		<li><a href="#daftarcabutberkas" data-toggle="tab">Daftar Cabut Berkas</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="pendaftar">
			<!-- <div class="table-responsive"> -->
				<table class="display" id="tabeldaftar" style="width: 100%;">
					<thead>
						<tr>
							<th class="text-center" data-priority="1">&nbsp;</th>
							<th class="text-center">Nomor Pendaftaran</th>
							<th class="text-center">NISN</th>
							<th class="text-center" data-priority="2">Nama</th>
							<th class="text-center">Sekolah Asal</th>
							<th class="text-center" data-priority="3">Jalur</th>
							<th class="text-center">Tanggal Pendaftaran</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; foreach($daftarpendaftar->getResult() as $row):?>
						<tr>
							<td class="text-center"><a href="<?php echo base_url();?>index.php/Csekolah/detailcabutberkas?pendaftaran_id=<?php echo $row->pendaftaran_id;?>" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-remove"></i></a></td>
							<td class="text-center"><?php echo $row->nomor_pendaftaran;?></td>
							<td class="text-center"><?php echo $row->nisn;?></td>
							<td><?php echo $row->nama;?></td>
							<td><?php echo $row->sekolah_asal;?></td>
							<td class="text-center"><?php echo $row->jalur;?></td>
							<td class="text-center"><?php echo $row->created_on;?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			<!-- </div> -->
		</div>
		<div class="tab-pane" id="daftarcabutberkas">
			<!-- <div class="table-responsive"> -->
				<table class="display" id="tabeldaftarcabutberkas" style="width: 100%;">
					<thead>
						<tr>
							<th class="text-center" data-priority="1">Nomor Pendaftaran</th>
							<th class="text-center">NISN</th>
							<th class="text-center" data-priority="2">Nama</th>
							<th class="text-center">Sekolah Asal</th>
							<th class="text-center" data-priority="3">Jalur</th>
							<th class="text-center">Tanggal Pendaftaran</th>
							<th class="text-center" data-priority="4">Tanggal Cabut Berkas</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; foreach($daftarpendaftarcabutberkas->getResult() as $row):?>
						<tr>
							<td class="text-center"><?php echo $row->nomor_pendaftaran;?></td>
							<td class="text-center"><?php echo $row->nisn;?></td>
							<td><?php echo $row->nama;?></td>
							<td><?php echo $row->sekolah_asal;?></td>
							<td class="text-center"><?php echo $row->jalur;?></td>
							<td class="text-center"><?php echo $row->created_on;?></td>
							<td class="text-center"><?php echo $row->tanggal_cabut_berkas;?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			<!-- </div> -->
		</div>
	</div>
</div>
<script>

$.extend( $.fn.dataTable.defaults, { responsive: true } );

$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
	$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
} );

	$(document).ready(function() {
		$('#tabeldaftar').dataTable({
			"responsive": true,
			"pageLength": 25,
			"lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
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
			}		
		});

		$('#tabeldaftarcabutberkas').dataTable({
			"responsive": true,
			"pageLength": 25,
			"lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
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
			}		
		});
    });
</script>