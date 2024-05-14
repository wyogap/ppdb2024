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
<div class="nav-tabs-custom" id="tabs">
	<ul class="nav nav-pills nav-justified" id="tabNames">
		<li class="active"><a href="#belum" data-toggle="tab" id='label-belum'>Belum Diverifikasi</a></li>
		<li><a href="#sedang" data-toggle="tab" id='label-sedang'>Belum Lengkap</a></li>
		<li><a href="#sudah" data-toggle="tab" id="label-sudah">Sudah Lengkap</a></li>
		<li><a href="#berkas" data-toggle="tab" id="label-berkas">Berkas Di Sekolah</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="belum">
			<!-- <div class="table-responsive"> -->
				<table class="display" id="tabelbelum" style="width: 100%;">
					<thead>
						<tr>
							<th class="text-center" data-priority="1">&nbsp;</th>
							<th class="text-center">Nomor Pendaftaran</th>
							<th class="text-center" data-priority="2">Nama</th>
							<th class="text-center">NISN</th>
							<th class="text-center">Sekolah Asal</th>
							<th class="text-center" data-priority="3">Jalur</th>
							<th class="text-center">Jenis Pilihan</th>
							<th class="text-center">Tanggal Pendaftaran</th>
							<th class="text-center">Sedang Verifikasi</th>
						</tr>
					</thead>
				</table>
			<!-- </div> -->
		</div>
		<div class="tab-pane" id="sedang">
			<!-- <div class="table-responsive"> -->
				<table class="display" id="tabelsedang" style="width: 100%;">
					<thead>
						<tr>
							<th class="text-center" data-priority="1">&nbsp;</th>
							<th class="text-center">Nomor Pendaftaran</th>
							<th class="text-center" data-priority="2">Nama</th>
							<th class="text-center">NISN</th>
							<th class="text-center">Sekolah Asal</th>
							<th class="text-center" data-priority="3">Jalur</th>
							<th class="text-center">Jenis Pilihan</th>
							<th class="text-center">Tanggal Pendaftaran</th>
							<th class="text-center">Sedang Verifikasi</th>
						</tr>
					</thead>
				</table>
			<!-- </div> -->
		</div>
		<div class="tab-pane" id="sudah">
			<!-- <div class="table-responsive"> -->
				<table class="display" id="tabelsudah" style="width: 100%;">
					<thead>
						<tr>
							<th class="text-center" data-priority="1">&nbsp;</th>
							<th class="text-center">Nomor Pendaftaran</th>
							<th class="text-center" data-priority="2">Nama</th>
							<th class="text-center">NISN</th>
							<th class="text-center">Sekolah Asal</th>
							<th class="text-center">Jalur</th>
							<th class="text-center">Jenis Pilihan</th>
							<!-- <th class="text-center">Tanggal Pendaftaran</th> -->
							<th class="text-center" data-priority="3">Tanggal Verifikasi</th>
							<th class="text-center" data-priority="4">Lokasi Berkas</th>
						</tr>
					</thead>
				</table>
			<!-- </div> -->
		</div>
		<div class="tab-pane" id="berkas">
			<!-- <div class="table-responsive"> -->
				<table class="display" id="tabelberkas" style="width: 100%;">
					<thead>
						<tr>
							<th class="text-center" data-priority="1">#</th>
							<th class="text-center" data-priority="2">Nama</th>
							<th class="text-center">NISN</th>
							<th class="text-center" data-priority="4">Asal Sekolah</th>
							<th class="text-center">Kelengkapan Berkas</th>
							<th class="text-center">Sedang Verifikasi</th>
						</tr>
					</thead>
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

	var dt_belum, dt_sedang, dt_sudah, dt_berkas;

	$(document).ready(function() {
		dt_belum = $('#tabelbelum').DataTable({
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
			},
			"ajax": "<?php echo base_url();?>index.php/sekolah/verifikasi/belumdiverifikasi?sekolah_id=<?php echo $sekolah_id; ?>",
			"columns": [
				{
					data: null,
					className: "text-right",
					orderable: 'false',
					defaultContent: '',
					render: function(data, type, row, meta) {
						if (type != 'display') {
							return data;
						}

						//return row['kelengkapan_berkas'];

						<?php if($waktuverifikasi==1){?>
						if (row['kelengkapan_berkas'] != 1) {
							return '<a href="<?php echo base_url();?>index.php/sekolah/verifikasi/siswa?pendaftaran_id=' +row['pendaftaran_id']+ '" class="btn btn-xs btn-primary">Verifikasi</a>';
						}
						<?php } ?>

						return data;
					}
				},
				{
					data: "nomor_pendaftaran",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "nama",
					className: "text-left",
					orderable: 'true',
					render: function(data, type, row, meta) {
						if(type != 'display') {
							return data;
						}

						return '<a href="<?php echo base_url();?>index.php/Chome/detailpendaftaran?peserta_didik_id=' +row['peserta_didik_id']+ '">' +row['nama']+ '</a>';
					}
				},
				{
					data: "nisn",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "sekolah_asal",
					className: "text-left",
					orderable: 'true',
				},
				{
					data: "jalur",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "label_jenis_pilihan",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "create_date",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "sedang_verifikasi",
					className: "text-center",
					orderable: 'true',
				},
			],
			order: [ [7, 'asc'] ],
		});

		dt_sedang = $('#tabelsedang').DataTable({
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
			},	
			"ajax": "<?php echo base_url();?>index.php/sekolah/verifikasi/belumlengkap?sekolah_id=<?php echo $sekolah_id; ?>",
			"columns": [
				{
					data: null,
					className: "text-right",
					orderable: 'false',
					defaultContent: '',
					render: function(data, type, row, meta) {
						if (type != 'display') {
							return data;
						}

						//return row['kelengkapan_berkas'];

						<?php if($waktuverifikasi==1){?>
						if (row['kelengkapan_berkas'] != 1) {
							return '<a href="<?php echo base_url();?>index.php/sekolah/verifikasi/siswa?pendaftaran_id=' +row['pendaftaran_id']+ '" class="btn btn-xs btn-primary">Verifikasi</a>';
						}
						<?php } ?>

						return data;
					}
				},
				{
					data: "nomor_pendaftaran",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "nama",
					className: "text-left",
					orderable: 'true',
					render: function(data, type, row, meta) {
						if(type != 'display') {
							return data;
						}

						return '<a href="<?php echo base_url();?>index.php/Chome/detailpendaftaran?peserta_didik_id=' +row['peserta_didik_id']+ '">' +row['nama']+ '</a>';
					}
				},
				{
					data: "nisn",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "sekolah_asal",
					className: "text-left",
					orderable: 'true',
				},
				{
					data: "jalur",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "label_jenis_pilihan",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "create_date",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "sedang_verifikasi",
					className: "text-center",
					orderable: 'true',
				},
			],
			order: [ [7, 'asc'] ],
		});

		dt_sudah = $('#tabelsudah').DataTable({
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
			},
			"ajax": "<?php echo base_url();?>index.php/sekolah/verifikasi/sudahlengkap?sekolah_id=<?php echo $sekolah_id; ?>",
			"columns": [
				{
					data: null,
					className: "text-right",
					orderable: 'false',
					defaultContent: '',
					render: function(data, type, row, meta) {
						if (type != 'display') {
							return data;
						}

						//return row['kelengkapan_berkas'];

						<?php if($waktuverifikasi==1){?>
						if (row['kelengkapan_berkas'] != 1) {
							return '<a href="<?php echo base_url();?>index.php/sekolah/verifikasi/siswa?pendaftaran_id=' +row['pendaftaran_id']+ '" class="btn btn-xs btn-primary">Verifikasi Ulang</a>';
						}
						<?php } ?>

						return data;
					}
				},
				{
					data: "nomor_pendaftaran",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "nama",
					className: "text-left",
					orderable: 'true',
					render: function(data, type, row, meta) {
						if(type != 'display') {
							return data;
						}

						return '<a href="<?php echo base_url();?>index.php/Chome/detailpendaftaran?peserta_didik_id=' +row['peserta_didik_id']+ '">' +row['nama']+ '</a>';
					}
				},
				{
					data: "nisn",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "sekolah_asal",
					className: "text-left",
					orderable: 'true',
				},
				{
					data: "jalur",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "label_jenis_pilihan",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "tanggal_verifikasi_berkas",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "lokasi_berkas",
					className: "text-left",
					orderable: 'true',
				},
			],
			order: [ [2, 'asc'] ],
		});

		dt_berkas = $('#tabelberkas').DataTable({
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
			},		
			"ajax": "<?php echo base_url();?>index.php/sekolah/verifikasi/berkasdisekolah?sekolah_id=<?php echo $sekolah_id; ?>",
			"columns": [
				{
					data: null,
					className: "text-right",
					orderable: 'false',
					defaultContent: '',
					render: function(data, type, row, meta) {
						if (type != 'display') {
							return data;
						}

						//return row['kelengkapan_berkas'];

						<?php if($waktuverifikasi==1){?>
						if (row['kelengkapan_berkas'] != 1) {
							return '<a href="<?php echo base_url();?>index.php/sekolah/verifikasi/siswa?pendaftaran_id=' +row['pendaftaran_id']+ '" class="btn btn-xs btn-primary">Verifikasi</a>';
						}
						<?php } ?>

						return data;
					}
				},
				{
					data: "nama",
					className: "text-left",
					orderable: 'true',
					render: function(data, type, row, meta) {
						if(type != 'display') {
							return data;
						}

						return '<a href="<?php echo base_url();?>index.php/Chome/detailpendaftaran?peserta_didik_id=' +row['peserta_didik_id']+ '">' +row['nama']+ '</a>';
					}
				},
				{
					data: "nisn",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "sekolah_asal",
					className: "text-left",
					orderable: 'true',
				},
				{
					data: "kelengkapan_berkas",
					className: "text-center",
					orderable: 'true',
					render: function(data, type, row, meta) {
						if(type != 'display') {
							return data;
						}

						if (data != 1) {
							return "Belum Lengkap";
						}

						return "Sudah Lengkap";
					}
				},
				// {
				// 	data: "label_jenis_pilihan",
				// 	className: "text-center",
				// 	orderable: 'true',
				// },
				// {
				// 	data: "create_date",
				// 	className: "text-center",
				// 	orderable: 'true',
				// },
				{
					data: "sedang_verifikasi",
					className: "text-center",
					orderable: 'true',
					render: function(data, type, row, meta) {
						if (row['kelengkapan_berkas'] != 1) {
							return data;
						}

						return 'Sudah lengkap';
					}
				},
			],
			order: [ [1, 'asc'] ],

		});

		//reload every 5 mins
		setInterval(function(){
			dt_belum.ajax.reload( function ( json ) {
				var len = (typeof json.data == 'undefined') ? 0 : json.data.length;
				if (len == 0) {
					$('#label-belum').html("Belum Diverifikasi");
				}
				else {
					$('#label-belum').html("Belum Diverifikasi (" +len+ ")");
				}
			}, true );

			dt_sedang.ajax.reload( function ( json ) {
				var len = (typeof json.data == 'undefined') ? 0 : json.data.length;
				if (len == 0) {
					$('#label-sedang').html("Belum Lengkap");
				}
				else {
					$('#label-sedang').html("Belum Lengkap (" +len+ ")");
				}
			}, true );

			dt_sudah.ajax.reload( function ( json ) {
				var len = (typeof json.data == 'undefined') ? 0 : json.data.length;
				if (len == 0) {
					$('#label-sudah').html("Sudah Lengkap");
				}
				else {
					$('#label-sudah').html("Sudah Lengkap (" +len+ ")");
				}
			}, true );

			dt_berkas.ajax.reload( function ( json ) {
				var len = (typeof json.data == 'undefined') ? 0 : json.data.length;
				if (len == 0) {
					$('#label-berkas').html("Berkas Di Sekolah");
				}
				else {
					$('#label-berkas').html("Berkas Di Sekolah (" +len+ ")");
				}
			}, true );

			//console.log("reload completed!");
		}, 5*60000);

		var len = dt_belum.rows().count();
		if (len == 0) {
			$('#label-belum').html("Belum Diverifikasi");
		}
		else {
			$('#label-belum').html("Belum Diverifikasi (" +len+ ")");
		}

		len = dt_sedang.rows().count();
		if (len == 0) {
			$('#label-sedang').html("Belum Lengkap");
		}
		else {
			$('#label-sedang').html("Belum Lengkap (" +len+ ")");
		}

		len = dt_sudah.rows().count();
		if (len == 0) {
			$('#label-sudah').html("Sudah Lengkap");
		}
		else {
			$('#label-sudah').html("Sudah Lengkap (" +len+ ")");
		}

		len = dt_berkas.rows().count();
		if (len == 0) {
			$('#label-berkas').html("Berkas Di Sekolah");
		}
		else {
			$('#label-berkas').html("Berkas Di Sekolah (" +len+ ")");
		}
	});
</script>