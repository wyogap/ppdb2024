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

<span><?php if(isset($info)){echo $info;}?></span>

<div class="box box-solid">
<div class="box-body">

<div class="row">
<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 15px !important;">

				<table class="display" id="tabeldaftar" style="width: 100%;">
					<thead>
						<tr>
							<th class="text-center" data-priority="1">&nbsp;</th>
							<th class="text-center">Nomor Pendaftaran</th>
							<th class="text-center">NISN</th>
							<th class="text-center" data-priority="2">Nama</th>
							<th class="text-center">Sekolah Asal</th>
							<th class="text-center" data-priority="3">Jenis Pilihan</th>
							<th class="text-center">Tanggal Pendaftaran</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; foreach($daftar->getResult() as $row):?>
						<tr>
							<td class="text-center"><a href="<?php echo base_url();?>index.php/Csekolah/detailgantiprestasi?pendaftaran_id=<?php echo $row->pendaftaran_id;?>&penerapan_id=<?php echo $row->penerapan_id;?>" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a></td>
							<td class="text-center"><?php echo $row->nomor_pendaftaran;?></td>
							<td class="text-center"><?php echo $row->nisn;?></td>
							<td><?php echo $row->nama;?></td>
							<td><?php echo $row->sekolah_asal;?></td>
							<td class="text-center"><?php echo $row->jenis_pilihan;?></td>
							<td class="text-center"><?php echo $row->created_on;?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>

</div>
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
			"pageLength": 50,
			"lengthMenu": [ [50, 100, 200, -1], [50, 100, 200, "All"] ],
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
				"sLengthMenu":   "Tampilan _MENU_ entri",
				"sZeroRecords":  "Tidak ditemukan data yang sesuai",
				"sInfo":         "Tampilan _START_ - _END_ dari _TOTAL_ entri",
				"sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 entri",
				"sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
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