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
<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#pendaftar" data-toggle="tab">Pendaftar</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="pendaftar">
			<div class="table-responsive">
				<table class="table table-hover table-bordered" id="tabeldaftar">
					<thead>
						<tr>
							<th class="text-center">&nbsp;</th>
							<th class="text-center">Nomor Pendaftaran</th>
							<th class="text-center">NISN</th>
							<th class="text-center">Nama</th>
							<th class="text-center">Sekolah Asal</th>
							<th class="text-center">Jalur</th>
							<th class="text-center">Tanggal Pendaftaran</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; foreach($daftarpendaftar->getResult() as $row):?>
						<tr>
							<td class="text-center"><a href="<?php echo base_url();?>index.php/Csekolah/detailundurdiri?pendaftaran_id=<?php echo $row->pendaftaran_id;?>" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-remove"></i></a></td>
							<td class="text-center"><?php echo $row->nomor_pendaftaran;?></td>
							<td class="text-center"><?php echo $row->nisn;?></td>
							<td><?php echo $row->nama;?></td>
							<td><?php echo $row->sekolah_asal;?></td>
							<td class="text-center"><?php echo $row->jalur;?></td>
							<td class="text-center"><?php echo $row->create_date;?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('#tabeldaftar').dataTable({
			"iDisplayLength": 5,
			"aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
			"bPaginate": false,
			"dom": 'Bfrtip',
			"buttons": [
				{ extend: 'copy', footer: true, messageTop: "Penerimaan Peserta Didik Baru"},
				{ extend: 'excel', footer: true, messageTop: "Penerimaan Peserta Didik Baru"},
				{ extend: 'pdf', footer: true, messageTop: "Penerimaan Peserta Didik Baru"},
				{ extend: 'print', footer: true, messageTop: "Penerimaan Peserta Didik Baru"}
			]
		});
		$('#tabeldaftarcabutberkas').dataTable({
			"iDisplayLength": 5,
			"aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
			"bPaginate": false,
			"dom": 'Bfrtip',
			"buttons": [
				{ extend: 'copy', footer: true, messageTop: "Penerimaan Peserta Didik Baru"},
				{ extend: 'excel', footer: true, messageTop: "Penerimaan Peserta Didik Baru"},
				{ extend: 'pdf', footer: true, messageTop: "Penerimaan Peserta Didik Baru"},
				{ extend: 'print', footer: true, messageTop: "Penerimaan Peserta Didik Baru"}
			]
		});
    });
</script>