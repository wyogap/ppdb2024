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
		<li><a href="#daftarpersetujuan" data-toggle="tab">Daftar Disetujui</a></li>
		<li><a href="#daftartidaksetuju" data-toggle="tab">Daftar Tidak Disetujui</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="daftarpengajuan">
				<table class="display" id="tabeldaftarpengajuan" style="width: 100%;">
					<thead>
						<tr>
							<th class="text-center" rowspan="2">&nbsp;</th>
							<th class="text-center" colspan="2">Sekolah Asal</th>
							<th class="text-center" colspan="3">Siswa</th>
						</tr>
						<tr>
							<th class="text-center">NPSN</th>
							<th class="text-center">Nama</th>
							<th class="text-center">NISN</th>
							<th class="text-center">Nama</th>
							<th class="text-center">Kabupaten/Kota</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; foreach($daftarpengajuanakun->getResult() as $row):?>
						<tr>
							<td class="text-center"><a href="<?php echo base_url();?>index.php/Cadmin/detailpengajuanakun?pengguna_id=<?php echo $row->pengguna_id;?>" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-search"></i></a></td>
							<td class="text-center"><?php echo $row->npsn;?></td>
							<td><a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/<?php echo $row->sekolah_id;?>" target="_blank"><?php echo $row->sekolah;?></a></td>
							<td class="text-center"><?php echo $row->nisn;?></td>
							<td><?php echo $row->nama;?></td>
							<td><?php echo $row->kabupaten;?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
		</div>
		<div class="tab-pane" id="daftarpersetujuan">
				<table class="display" id="tabeldaftarpersetujuan" style="width: 100%;">
					<thead>
						<tr>
							<th class="text-center" rowspan="2">Status</th>
							<th class="text-center" colspan="2">Sekolah Asal</th>
							<th class="text-center" colspan="3">Siswa</th>
							<th class="text-center" rowspan="2">Username</th>
						</tr>
						<tr>
							<th class="text-center">NPSN</th>
							<th class="text-center">Nama</th>
							<th class="text-center">NISN</th>
							<th class="text-center">Nama</th>
							<th class="text-center">Kabupaten/Kota</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; foreach($daftarpersetujuanakun->getResult() as $row):?>
						<tr>
							<td class="text-center"><?php if($row->approval==1){ ?><i class="text-info glyphicon glyphicon-ok"></i><?php }else{?><i class="text-danger glyphicon glyphicon-remove"><?php }?></td>
							<td class="text-center"><?php echo $row->npsn;?></td>
							<td><a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/<?php echo $row->sekolah_id;?>" target="_blank"><?php echo $row->sekolah;?></a></td>
							<td class="text-center"><?php echo $row->nisn;?></td>
							<td><?php echo $row->nama;?></td>
							<td><?php echo $row->kabupaten;?></td>
							<td class="text-center"><?php echo $row->username;?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
		</div>
		<div class="tab-pane" id="daftartidaksetuju">
				<table class="display" id="tabeldaftartidaksetuju" style="width: 100%;">
					<thead>
						<tr>
							<th class="text-center" rowspan="2">Status</th>
							<th class="text-center" colspan="2">Sekolah Asal</th>
							<th class="text-center" colspan="3">Siswa</th>
							<th class="text-center" rowspan="2">Username</th>
						</tr>
						<tr>
							<th class="text-center">NPSN</th>
							<th class="text-center">Nama</th>
							<th class="text-center">NISN</th>
							<th class="text-center">Nama</th>
							<th class="text-center">Kabupaten/Kota</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; foreach($daftartidaksetujuakun->getResult() as $row):?>
						<tr>
							<td class="text-center"><a href="<?php echo base_url();?>index.php/Cadmin/detailpengajuanakun?pengguna_id=<?php echo $row->pengguna_id;?>" class="btn btn-xs btn-primary">
								<i class="text-danger glyphicon glyphicon-remove"></i>
								</a>
								</td>
							<td class="text-center"><?php echo $row->npsn;?></td>
							<td><a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/<?php echo $row->sekolah_id;?>" target="_blank"><?php echo $row->sekolah;?></a></td>
							<td class="text-center"><?php echo $row->nisn;?></td>
							<td><?php echo $row->nama;?></td>
							<td><?php echo $row->kabupaten;?></td>
							<td class="text-center"><?php echo $row->username;?></td>
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

		$('#tabeldaftarpengajuan').DataTable({
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
		$('#tabeldaftarpersetujuan').DataTable({
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
		$('#tabeldaftartidaksetuju').DataTable({
			"iDisplayLength": 5,
			"aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
			"bPaginate": false,
			"dom": 'Bfrtip',
			"buttons": [
			]
		});
    });
</script>