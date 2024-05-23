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

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#smp" data-toggle="tab">SMP NEGERI</a></li>
				<li><a href="#swasta" data-toggle="tab">SMP SWASTA</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="smp">
						<table class="display" id="tsmp" style="width: 100%;">
							<thead>
								<tr>
									<th class="text-center" data-priority="1">&nbsp;</th>
									<th class="text-center" data-priority="1">NPSN</th>
									<th class="text-center" data-priority="2">Nama</th>
									<th class="text-center" data-priority="3">Kuota Total</th>
									<th class="text-center" data-priority="4">Total Diterima</th>
									<th class="text-center" data-priority="5">Selisih</th>
									<th class="text-center" data-priority="6">Total Pendaftar</th>
									<th class="none" data-priority="8">Kuota Zonasi</th>
									<th class="text-center" data-priority="7">Masuk Kuota Zonasi</th>
									<th class="none">Tidak Masuk Kuota Zonasi</th>
									<th class="none">Jumlah Pendaftar Zonasi</th>
									<th class="none" data-priority="8">Kuota Prestasi</th>
									<th class="text-center" data-priority="7">Masuk Kuota Prestasi</th>
									<th class="none">Tidak Masuk Kuota Prestasi</th>
									<th class="none">Jumlah Pendaftar Prestasi</th>
									<th class="none" data-priority="8">Kuota Afirmasi</th>
									<th class="text-center" data-priority="7">Masuk Kuota Afirmasi</th>
									<th class="none">Tidak Masuk Kuota Afirmasi</th>
									<th class="none">Jumlah Pendaftar Afirmasi</th>
									<th class="none" data-priority="8">Kuota Perpindahan</th>
									<th class="text-center" data-priority="7">Masuk Kuota Perpindahan</th>
									<th class="none">Tidak Masuk Kuota Perpindahan</th>
									<th class="none">Jumlah Pendaftar Perpindahan</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; foreach($rekapitulasismp->getResult() as $row):
									if ($row->kuota_total == 0) {
										continue;
									}
								?>
								<tr>
									<td class="text-center"><a href="<?php echo base_url();?>index.php/home/peringkat?sekolah_id=<?php echo $row->sekolah_id;?>"><i class="glyphicon glyphicon-list-alt"></i></a></td>
									<td class="text-center"><?php echo $row->npsn;?></td>
									<td><?php echo $row->nama;?></td>
									<td class="text-center"><?php echo $row->kuota_total;?></td>
									<td class="text-center"><?php echo $row->diterima;?></td>
									<td class="text-center"><?php echo $row->kuota_total - $row->diterima;?></td>
									<td class="text-center"><?php echo $row->total_pendaftar;?></td>
									<td class="text-center"><?php echo $row->kuota_zonasi;?></td>
									<td class="text-center"><?php echo $row->zonasi_diterima;?></td>
									<td class="text-center"><?php echo $row->zonasi_tidak_diterima;?></td>
									<td class="text-center"><?php echo $row->pendaftar_zonasi;?></td>
									<td class="text-center"><?php echo $row->kuota_prestasi;?></td>
									<td class="text-center"><?php echo $row->prestasi_diterima;?></td>
									<td class="text-center"><?php echo $row->prestasi_tidak_diterima;?></td>
									<td class="text-center"><?php echo $row->pendaftar_prestasi;?></td>
									<td class="text-center"><?php echo $row->kuota_afirmasi;?></td>
									<td class="text-center"><?php echo $row->afirmasi_diterima;?></td>
									<td class="text-center"><?php echo $row->afirmasi_tidak_diterima;?></td>
									<td class="text-center"><?php echo $row->pendaftar_afirmasi;?></td>
									<td class="text-center"><?php echo $row->kuota_perpindahan_orang_tua;?></td>
									<td class="text-center"><?php echo $row->perpindahan_orang_tua_diterima;?></td>
									<td class="text-center"><?php echo $row->perpindahan_orang_tua_tidak_diterima;?></td>
									<td class="text-center"><?php echo $row->pendaftar_perpindahan_orang_tua;?></td>
								</tr>
								<?php $i++; endforeach;?>
							</tbody>
						</table>
				</div>
				<div class="tab-pane" id="swasta">
						<table class="display" id="tswasta" style="width: 100%;">
							<thead>
								<tr>
									<th rowspan="2" class="text-center">&nbsp;</th>
									<th rowspan="2" class="text-center">NPSN</th>
									<th rowspan="2" class="text-center">Nama</th>
									<th rowspan="2" class="text-center">Kuota Total</th>
									<th colspan="3" class="text-center">Swasta</th>
								</tr>
								<tr>
									<th class="text-center">Masuk Kuota</th>
									<th class="text-center">Tidak Masuk Kuota</th>
									<th class="text-center">Jumlah</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; foreach($rekapitulasismpswasta->getResult() as $row):
									if ($row->kuota_total == 0) {
										continue;
									}
								?>
								<tr>
									<td class="text-center"><a href="<?php echo base_url();?>index.php/home/peringkat?sekolah_id=<?php echo $row->sekolah_id;?>"><i class="glyphicon glyphicon-list-alt"></i></a></td>
									<td class="text-center"><?php echo $row->npsn;?></td>
									<td><?php echo $row->nama;?></td>
									<td class="text-center"><?php echo $row->kuota_total;?></td>
                                    <td class="text-end"><?php echo $row->swasta_diterima;?></td>
                                    <td class="text-end"><?php echo $row->swasta_tidak_diterima;?></td>
                                    <td class="text-end"><?php echo $row->pendaftar_swasta;?></td>
								</tr>
								<?php $i++; endforeach;?>
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {

		$.extend( $.fn.dataTable.defaults, { responsive: true } );

		$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
			$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
		} );

		$('#tsmp').dataTable({
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
		$('#tswasta').dataTable({
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