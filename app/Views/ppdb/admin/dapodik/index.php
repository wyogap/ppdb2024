<!DOCTYPE html>
<html>
	<?php view('head');?>

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
	$this->load->helper('url');
?>

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

	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
			<?php view('header');?>
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<!-- <div class="row"> -->
						<h1 class="text-white">
							<i class="glyphicon glyphicon-list-alt"></i> Data DAPODIK <small>Lokasi Rumah Invalid</small>
						</h1>
						<!-- <ol class="breadcrumb">
							<li class="active"><a href="#"><i class="glyphicon glyphicon-th-list"></i> Peringkat Pendaftaran</a></li>
						</ol> -->
					</section>
					<section class="content">

					<span><?php if(isset($info)){echo $info;}?></span>

		<div class="alert alert-info">
			<i class="icon glyphicon glyphicon-info-sign"></i>
			Penarikan terakhir dilakukan pada: <b><?php echo tanggal_indo($last_execution_date); ?></b>. 
		</div>	

<div class="box box-solid">
	<!-- <div class="box-header with-border">
		<i class="glyphicon glyphicon-edit text-info"></i>
		<h3 class="box-title text-info"><b>Kuota Sekolah</b></h3>
	</div> -->
	<div class="box-body">

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#psummary" data-toggle="tab">Overview</a></li>
				<li><a href="#pnegeri" data-toggle="tab">Daftar Peserta Didik</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="psummary">
					<table class="display" id="tsummary" style="width:100%">
						<thead>
							<tr>
								<th class="text-center" data-priority="1">Kecamatan</th>
								<th class="text-center" data-priority="3">NPSN</th>
								<th class="text-center" data-priority="2">Sekolah</th>
								<th class="text-center">Jumlah Tidak Diisi</th>
							</tr>
						</thead>
					</table>
				</div>
				<div class="tab-pane" id="pnegeri">
					<table class="display" id="tnegeri" style="width:100%">
						<thead>
							<tr>
								<th class="text-center" data-priority="1">Sekolah</th>
								<th class="text-center" data-priority="2">Nama</th>
								<th class="text-center">Jenis Kelamin</th>
								<th class="text-center" data-priority="6">Lintang</th>
								<th class="text-center" data-priority="7">Bujur</th>
								<th class="text-center" data-priority="4">Last Update</th>
								<th class="text-center" data-priority="5">Last Sync</th>
								<th class="none">NISN</th>
								<th class="none">NIK</th>
								<th class="none">Tanggal Lahir</th>
								<th class="none">Kode Wilayah</th>
								<th class="none">Alamat</th>
								<th class="none">RT</th>
								<th class="none">RW</th>
								<th class="none">Dusun</th>
								<th class="none">Desa/Kelurahan</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

	</div>
</div>

					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>

<script type="text/javascript">

// Tabel
$(document).ready(function() {
	$.extend( $.fn.dataTable.defaults, { responsive: true } );

	$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
	} );
 
	$('#tsummary').dataTable({
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
        ajax: "<?php echo site_url('admin/dapodik/tabledapodiknolocationsummary'); ?>",
        columns: [
            { data: "nama_kec", className: 'dt-body-left' },
            { data: "npsn", className: 'dt-body-center' },
            { data: "sekolah", className: 'dt-body-left' },
            { data: "jumlah", className: 'dt-body-center' },
        ],
        order: [ 0, 'asc' ],
	});

	$('#tnegeri').dataTable({
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
        ajax: "<?php echo site_url('admin/dapodik/tabledapodiknolocation'); ?>",
        columns: [
            { data: "sekolah", className: 'dt-body-left' },
            { data: "nama", className: 'dt-body-left' },
            { data: "jenis_kelamin", className: 'dt-body-center' },
            { data: "lintang", className: 'dt-body-center' },
            { data: "bujur", className: 'dt-body-center' },
            { data: "last_update", className: 'dt-body-center' },
            { data: "last_sync", className: 'dt-body-center' },
            { data: "nisn", className: 'dt-body-center' },
            { data: "nik", className: 'dt-body-center' },
            { data: "tanggal_lahir", className: 'dt-body-center' },
            { data: "kode_wilayah", className: 'dt-body-center' },
            { data: "alamat_jalan", className: 'dt-body-center' },
            { data: "rt", className: 'dt-body-center' },
            { data: "rw", className: 'dt-body-center' },
            { data: "nama_dusun", className: 'dt-body-center' },
            { data: "desa_kelurahan", className: 'dt-body-center' }
        ],
        order: [ 0, 'asc' ],
	});

});

</script>

</html>
