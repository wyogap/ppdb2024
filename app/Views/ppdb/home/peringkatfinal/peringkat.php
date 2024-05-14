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
	<?php if ($show_profile_sekolah == 1) { ?>
	<?php foreach($profilsekolah->getResult() as $row):?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<div class="box-header with-border">
				<i class="glyphicon glyphicon-education"></i>
				<h3 class="box-title text-info"><b>(<?php echo $row->npsn;?>) <?php echo $row->nama;?></b></h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="table table-striped">
							<tr>
								<td><b>Jenjang</b></td>
								<td>:</td>
								<td><?php echo $row->bentuk_pendidikan;?></td>
							</tr>
							<tr>
								<td><b>Status Sekolah</b></td>
								<td>:</td>
								<td><?php if($row->status=='N'){?>NEGERI<?php }else{?>SWASTA<?php }?></td>
							</tr>
							<tr>
								<td><b>Alamat</b></td>
								<td>:</td>
								<td><?php echo $row->alamat_jalan;?>, <?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach;?>
	<?php } ?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="nav-tabs-custom" id="tabs">
			<ul class="nav nav-pills nav-justified" id="tabNames">
				<li class="active"><a href="#daftarpendaftar" data-toggle="tab">Pendaftar</a></li>
				<?php 
					$i = 0;
					foreach($daftarpenerapan->getResult() as $row):
						//skip jalur inklusi kalau sekolah bukan sekolah inklusi
						if ($row->jalur_id==7 && !$inklusi) {
							continue;
						}
						$i++;
					?>
				<li><a href="#p<?php echo $row->penerapan_id;?>" data-toggle="tab"><?php echo $row->jalur;?><br>

					<small class="label bg-blue"><?php echo ($row->kuota);?></small>
					<small class="label bg-green"><?php echo $row->diterima;?></small>
					<small class="label bg-gray"><?php echo $row->total_pendaftar;?></small>

				</a></li>
				<?php endforeach;?>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="daftarpendaftar">
					<!-- <div class="table-responsive"> -->
						<table class="display" id="tdaftarpendaftar" style="width:100%">
							<thead>
								<tr>
									<th class="text-center" data-priority="1">No</th>
									<th class="text-center" data-priority="4">Nomor Pendaftaran</th>
									<th class="text-center">NISN</th>
									<th class="text-center" data-priority="2">Nama</th>
									<th class="text-center">Sekolah Asal</th>
									<th class="text-center" data-priority="3">Jalur</th>
									<th class="text-center">Jenis Pilihan</th>
									<th class="text-center">Skor</th>
									<th class="text-center">Tanggal Pembukuan</th>
									<th class="text-center">Status</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; foreach($daftarpendaftar->getResult() as $row):?>
								<tr>
									<td class="text-center"><?php echo $i;?></td>
									<td class="text-center"><?php echo $row->nomor_pendaftaran;?></td>
									<td class="text-center"><?php echo substr($row->nisn,0,6). str_repeat("*", 4);?></td>
									<td><a href="<?php echo base_url();?>index.php/Chome/detailpendaftaran?peserta_didik_id=<?php echo $row->peserta_didik_id;?>" target="_blank"><?php echo $row->nama;?></a></td>
									<td><?php echo $row->sekolah_asal;?></td>
									<td class="text-center"><?php echo $row->jalur;?></td>
									<td class="text-center"><?php echo $row->jenis_pilihan;?></td>
									<td class="text-right"><?php echo round($row->skor,2);?></td>
									<td class="text-center"><?php echo $row->create_date;?></td>
									<td class="text-center 
										<?php if($row->status_penerimaan_final==1 || $row->status_penerimaan_final==3){?>bg-green
										<?php }else if($row->status_penerimaan_final==2 && $row->status_penerimaan!=2 && $row->masuk_jenis_pilihan != 0){ ?>bg-gray
										<?php }else if($row->status_penerimaan_final==2){ ?>bg-red
										<?php }else if($row->status_penerimaan_final==0 || $row->status_penerimaan_final==4){ ?>bg-gray
										<?php }else{ ?>bg-red<?php }?>">

										<?php 
										if($row->status_penerimaan_final==1 || $row->status_penerimaan_final==3) {
											echo 'Diterima';
										} else if ($row->status_penerimaan_final==2 && $row->status_penerimaan!=2 && $row->masuk_jenis_pilihan!=0 && $row->masuk_jenis_pilihan!=$row->jenis_pilihan) {
											echo 'Pilihan '.$row->masuk_jenis_pilihan;
										} else if ($row->status_penerimaan_final==2) {
											echo 'Tidak Diterima';
										} else if ($row->status_penerimaan_final==4) {
											echo 'Pilihan '.$row->masuk_jenis_pilihan;
										} else if ($row->status_penerimaan_final==0) {
											echo 'Berkas Tidak Lengkap';
										}										
										?>

									</td>
								</tr>
								<?php $i++; endforeach;?>
							</tbody>
						</table>
					<!-- </div> -->
				</div>
				<?php 
					$kuota=0; 
					$i=0;
					foreach($daftarpenerapan->getResult() as $row):
						//skip jalur inklusi kalau sekolah bukan sekolah inklusi
						if ($row->jalur_id==7 && !$inklusi) {
							continue;
						}
						$i++;
				?>
				<div class="tab-pane" id="p<?php echo $row->penerapan_id;?>">
					<!-- <div class="table-responsive"> -->
						<table class="display" id="t<?php echo $row->penerapan_id;?>" style="width:100%">
							<thead>
								<tr>
									<th class="text-center" data-priority="1">#</th>
									<th class="text-center" data-priority="1">Peringkat</th>
									<th class="text-center" data-priority="10001">Nomor Pendaftaran</th>
									<th class="text-center">NISN</th>
									<th class="text-center" data-priority="2">Nama</th>
									<th class="text-center">Jenis Pilihan</th>
									<th class="text-center">Tanggal Pembukuan</th>
									<th class="text-center">Skor</th>
								</tr>
							</thead>
							<tbody>
								<?php if(1==1) { ?>
								<?php 
									$kuota=$row->kuota;
									$i=1;
									//$this->load->model('Mhome');
									$pendaftar = $this->Msekolah->tcg_pendaftaran_penerapan_id($sekolah_id, $row->penerapan_id);
									foreach($pendaftar->getResult() as $row2):
								?>
								<?php if ($row2->status_penerimaan==4 || ($row2->masuk_jenis_pilihan != $row2->jenis_pilihan && $row2->masuk_jenis_pilihan != 0 && $row2->status_penerimaan != 2)){ ?>
								<tr class='bg-gray'>
									<td class="text-center"><?php echo $i;?></td>
									<td class="text-center"><?php echo $row2->label_masuk_jenis_pilihan;?></td>
								<?php } else if ($row2->status_penerimaan==0) { ?>
								<tr class='bg-gray'>
									<td class="text-center"><?php echo $i;?></td>
									<td class="text-center">Belum Diperingkat</td>
								<?php } else { ?>
								<tr>
									<td class="text-center"><?php echo $i;?></td>
									<td class="text-center 
										<?php if($row2->status_penerimaan==1){?>bg-green
										<?php }else if($row2->status_penerimaan==3){ ?>bg-green
										<?php }else{ ?>bg-red<?php }?>">
									<?php 
										if($row2->status_penerimaan==2) {
											echo 'Tidak Diterima';
										} else {
											echo $row2->peringkat_final;
										}
									?>
									</td>
								<?php } ?>
									<td class="text-center"><?php echo $row2->nomor_pendaftaran;?></td>
									<td class="text-center"><?php echo substr($row2->nisn,0,6). str_repeat("*", 4);?></td>
									<td>
									<a href="<?php echo base_url();?>index.php/Chome/detailpendaftaran?peserta_didik_id=<?php echo $row2->peserta_didik_id;?>" target="_blank">
										<?php echo $row2->nama;?>
									</a>
									</td>
									<td class="text-center"><?php echo $row2->label_jenis_pilihan;?></td>
									<td class="text-center"><?php echo $row2->create_date;?></td>
									<td class="text-right"><?php echo round($row2->skor,2);?></td>
								</tr>
								<?php $i++; endforeach;?>
								<?php } ?>


							</tbody>
						</table>
					<!-- </div> -->
				</div>
				<?php endforeach;?>
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
		<?php foreach($daftarpenerapan->getResult() as $row):?>
		var dt_<?php echo $row->penerapan_id;?> = $('#t<?php echo $row->penerapan_id;?>').DataTable({
			"responsive": true,
			"pageLength": 50,
			"lengthMenu": [ [50, 100, 200, -1], [50, 100, 200, "All"] ],
			"paging": true,
			"pagingType": "numbers",
			"dom": 'Bfrtpil',
			"buttons": [
				'copyHtml5',
				'excelHtml5',
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'A3'
				},
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
			},		
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"order": [[ 7, 'desc' ]]
		});

		// dt_<?php echo $row->penerapan_id;?>.on( 'order.dt search.dt', function () {
		// 	dt_<?php echo $row->penerapan_id;?>.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		// 		cell.innerHTML = i+1;
		// 	} );
		// } ).draw();

		<?php endforeach;?>

		var dt = $('#tdaftarpendaftar').DataTable({
			"responsive": true,
			"pageLength": 50,
			"lengthMenu": [ [50, 100, 200, -1], [50, 100, 200, "All"] ],
			"paging": true,
			"pagingType": "numbers",
			"dom": 'Bfrtpil',
			"buttons": [
				'copyHtml5',
				'excelHtml5',
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'A3'
				},
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
			},		
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"order": [[ 8, 'asc' ]]
		});

		// dt.on( 'order.dt search.dt', function () {
		// 	dt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		// 		cell.innerHTML = i+1;
		// 	} );
		// } ).draw();

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