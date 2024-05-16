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
		<li class="active"><a href="#daftarpengajuan" data-toggle="tab">Daftar Pengajuan</a></li>
		<li><a href="#daftarpersetujuan" data-toggle="tab">Daftar Persetujuan</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="daftarpengajuan">
				<table class="display" id="tabelpengajuan" style="width: 100%;">
					<thead>
						<tr>
							<th class="text-center" rowspan="2">&nbsp;</th>
							<th class="text-center" rowspan="2">Status</th>
							<th class="text-center" rowspan="2">NISN</th>
							<th class="text-center" rowspan="2">Nama</th>
							<th class="text-center" colspan="2">Tanggal Lahir</th>
							<th class="text-center" colspan="2">Desa/Kelurahan</th>
							<th class="text-center" colspan="2">Lintang</th>
							<th class="text-center" colspan="2">Bujur</th>
							<th class="text-center" rowspan="2">Tanggal Pengajuan</th>
							<th class="text-center" rowspan="2">Pengguna</th>
						</tr>
						<tr>
							<th class="text-center">Lama</th>
							<th class="text-center">Baru</th>
							<th class="text-center">Lama</th>
							<th class="text-center">Baru</th>
							<th class="text-center">Lama</th>
							<th class="text-center">Baru</th>
							<th class="text-center">Lama</th>
							<th class="text-center">Baru</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; foreach($daftarpengajuan->getResult() as $row):?>
						<tr>
							<td class="text-center"><a href="<?php echo base_url();?>index.php/admin/pengajuanperubahandata?peserta_didik_id=<?php echo $row->peserta_didik_id;?>" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-search"></i></a></td>
							<td class="text-center"><?php if($row->approval==1){?><i class="text-blue glyphicon glyphicon-ok"></i><?php }else if($row->approval==2){?><i class="text-red glyphicon glyphicon-remove"></i><?php }else{?>Dalam Proses<?php }?></td>
							<td class="text-center"><?php echo $row->nisn;?></td>
							<td><?php echo $row->nama;?></td>
							<td class="text-center"><?php echo tanggal_indo($row->tanggal_lahir_lama);?></td>
							<td class="text-center <?php if($row->tanggal_lahir_lama!=$row->tanggal_lahir_baru) { echo 'bg-green'; } ?>">
							<?php echo tanggal_indo($row->tanggal_lahir_baru);?></td>
							<td class="text-left"><?php echo $row->desa_lama;?></td>
							<td class="text-left <?php if($row->kode_wilayah_lama!=$row->kode_wilayah_baru) { echo 'bg-green'; } ?>">
							<?php echo $row->desa_baru;?></td>
							<td class="text-center"><?php echo $row->lintang_lama;?></td>
							<td class="text-center <?php if($row->lintang_lama!=$row->lintang_baru) { echo 'bg-green'; } ?>">
							<?php echo $row->lintang_baru;?></td>
							<td class="text-center"><?php echo $row->bujur_lama;?></td>
							<td class="text-center <?php if($row->bujur_lama!=$row->bujur_baru) { echo 'bg-green'; } ?>">
							<?php echo $row->bujur_baru;?></td>
							<td class="text-center"><?php echo $row->created_on;?></td>
							<td class="text-left"><?php echo $row->pengguna;?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
		</div>
		<div class="tab-pane" id="daftarpersetujuan">
				<table class="display" id="tabelpersetujuan" style="width: 100%;">
					<thead>
						<tr>
							<th class="text-center" rowspan="2">Status</th>
							<th class="text-center" rowspan="2">NISN</th>
							<th class="text-center" rowspan="2">Nama</th>
							<th class="text-center" colspan="2">Tanggal Lahir</th>
							<th class="text-center" colspan="2">Desa/Kelurahan</th>
							<th class="text-center" colspan="2">Lintang</th>
							<th class="text-center" colspan="2">Bujur</th>
							<th class="text-center" rowspan="2">Tanggal Pengajuan</th>
							<th class="text-center" rowspan="2">Pengguna</th>
							<th class="text-center" rowspan="2">Keterangan</th>
						</tr>
						<tr>
							<th class="text-center">Lama</th>
							<th class="text-center">Baru</th>
							<th class="text-center">Lama</th>
							<th class="text-center">Baru</th>
							<th class="text-center">Lama</th>
							<th class="text-center">Baru</th>
							<th class="text-center">Lama</th>
							<th class="text-center">Baru</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; foreach($daftarpersetujuan->getResult() as $row):?>
						<tr>
							<td class="text-center"><?php if($row->approval==1){?><i class="text-blue glyphicon glyphicon-ok"></i><?php }else if($row->approval==2){?><i class="text-red glyphicon glyphicon-remove"></i><?php }else{?>Dalam Proses<?php }?></td>
							<td class="text-center"><?php echo $row->nisn;?></td>
							<td><?php echo $row->nama;?></td>
							<td class="text-center"><?php echo tanggal_indo($row->tanggal_lahir_lama);?></td>
							<td class="text-center <?php if($row->tanggal_lahir_lama!=$row->tanggal_lahir_baru) { echo 'bg-green'; } ?>">
							<?php echo tanggal_indo($row->tanggal_lahir_baru);?></td>
							<td class="text-left"><?php echo $row->desa_lama;?></td>
							<td class="text-left <?php if($row->kode_wilayah_lama!=$row->kode_wilayah_baru) { echo 'bg-green'; } ?>">
							<?php echo $row->desa_baru;?></td>
							<td class="text-center"><?php echo $row->lintang_lama;?></td>
							<td class="text-center <?php if($row->lintang_lama!=$row->lintang_baru) { echo 'bg-green'; } ?>">
							<?php echo $row->lintang_baru;?></td>
							<td class="text-center"><?php echo $row->bujur_lama;?></td>
							<td class="text-center <?php if($row->bujur_lama!=$row->bujur_baru) { echo 'bg-green'; } ?>">
							<?php echo $row->bujur_baru;?></td>
							<td class="text-center"><?php echo $row->created_on;?></td>
							<td class="text-left"><?php echo $row->pengguna;?></td>
							<td class="text-left"><?php echo $row->keterangan_approval;?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$.extend( $.fn.dataTable.defaults, { responsive: true } );

		$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
			$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
		} );

		$('#tabelpengajuan').dataTable({
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
		$('#tabelpersetujuan').dataTable({
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