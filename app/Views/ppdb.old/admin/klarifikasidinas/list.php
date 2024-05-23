<!DOCTYPE html>
<html>
	<?php view('head');?>

<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Select-1.3.1/css/select.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Editor-1.9.2/css/editor.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ppdb.css">
<script src="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Select-1.3.1/js/dataTables.select.min.js"></script>
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

	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
			<?php view('header');?>
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<!-- <div class="row"> -->
						<h1 class="text-white">
							<i class="glyphicon glyphicon-list-alt"></i> Klarifikasi Dinas</small>
						</h1>
						<!-- </div> -->
					</section>
					<section class="content">

<span><?php if(isset($info)){echo $info;}?></span>

<?php
	$error = $this->session->flashdata('error');
	if($error)
	{
?>
	<div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<?php echo $error; ?>                    
	</div>
<?php 
	}

	$success = $this->session->flashdata('success');
	if($success)
	{
?>
	<div class="alert alert-success alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<?php echo $success; ?>                    
	</div>
<?php 
	}

	$info = $this->session->flashdata('info');
	if(!empty($info))
	{
?>
	<div class="alert alert-info alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<?php echo $info; ?>                    
	</div>
<?php } ?>

<!-- <div class="row"> -->
	<div class="nav-tabs-custom" id="tabs">
		<ul class="nav nav-pills nav-justified" id="tabNames">
			<li class="active"><a href="#profil" data-toggle="tab">Perlu Klarifikasi</a></li>
			<li><a href="#lokasi" data-toggle="tab">Sudah Diklarifikasi</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="profil">
					<table class="display" id="tprofil" style="width:100%">
						<thead>
							<tr>
								<th class="text-center" data-priority="1">#</th>
								<th class="text-center" data-priority="2">Nama</th>
								<th class="text-center">NISN</th>
								<th class="text-center" data-priority="4">NIK</th>
								<th class="text-center">Tipe Data</th>
								<th class="text-center">Tanggal Eskalasi</th>
								<th class="text-center">Operator Sekolah</th>
								<th class="text-center">Sekolah</th>
								<th class="text-center">Catatan Sekolah</th>
							</tr>
						</thead>
					</table>
			</div>
			<div class="tab-pane" id="lokasi">
					<table class="display" id="tlokasi" style="width:100%">
						<thead>
							<tr>
							<th class="text-center" data-priority="1">#</th>
								<th class="text-center" data-priority="2">Nama</th>
								<th class="text-center">NISN</th>
								<th class="text-center" data-priority="4">NIK</th>
								<th class="text-center">Tipe Data</th>
								<th class="text-center">Tanggal Eskalasi</th>
								<th class="text-center">Catatan Sekolah</th>
								<th class="text-center">Tanggal Klarifikasi</th>
								<th class="text-center">Admin Dinas</th>
								<th class="text-center">Catatan Dinas</th>
							</tr>
						</thead>
					</table>
			</div>
		</div> 
	</div>
<!-- </div> -->

	<!-- </div>
</div> -->

					</section>
				</div>
			</div>
			<?php view('footer');?>
		</div>
	</body>

<script type="text/javascript">

var dt_profil = null;
var dt_lokasi = null;

$(document).ready(function() {
	$.extend( $.fn.dataTable.defaults, { responsive: true } );

	$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
	} );
 
 
	dt_profil = $('#tprofil').DataTable({
		"responsive": true,
		"pageLength": 50,
		"lengthMenu": [ [50, 100, 200, -1], [50, 100, 200, "All"] ],
		"paging": true,
		"pagingType": "numbers",
		"dom": 'Bfrtpil',
		select: true,
		buttons: [
			'copyHtml5',
			'excelHtml5',
			'pdfHtml5',
			'print',
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
        ajax: "<?php echo base_url();?>index.php/admin/klarifikasidinas/json?klarifikasi=0",
		"columns": [
				{
					data: null,
					className: "text-end",
					orderable: 'false',
					defaultContent: '',
					render: function(data, type, row, meta) {
						if (type != 'display') {
							return data;
						}

						//return row['kelengkapan_berkas'];

						<?php if($waktuverifikasi==1){?>
						if (row['kelengkapan_berkas'] != 1) {
							return '<a href="<?php echo base_url();?>index.php//admin/klarifikasidinas/detail?klarifikasi_id=' +row['klarifikasi_id']+ '" class="btn btn-xs btn-primary">Klarifikasi</a>';
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
					data: "nik",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "tipe_data",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "created_on",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "nama_pengguna_sekolah",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "sekolah_tujuan",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "catatan_sekolah",
					className: "text-center",
					orderable: 'true',
				},
			],
        order: [ [1, 'asc'] ],
	});

	dt_lokasi = $('#tlokasi').DataTable({
		"responsive": true,
		"pageLength": 50,
		"lengthMenu": [ [50, 100, 200, -1], [50, 100, 200, "All"] ],
		"paging": true,
		"pagingType": "numbers",
		"dom": 'Bfrtpil',
		select: true,
		buttons: [
			'copyHtml5',
			'excelHtml5',
			'pdfHtml5',
			'print',
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
        ajax: "<?php echo base_url();?>index.php/admin/klarifikasidinas/json?klarifikasi=1",
		"columns": [
				{
					data: null,
					className: "text-end",
					orderable: 'false',
					defaultContent: '',
					render: function(data, type, row, meta) {
						if (type != 'display') {
							return data;
						}

						//return row['kelengkapan_berkas'];

						<?php if($waktuverifikasi==1){?>
						if (row['kelengkapan_berkas'] != 1) {
							return '<a href="<?php echo base_url();?>index.php//admin/klarifikasidinas/detail?klarifikasi_id=' +row['klarifikasi_id']+ '" class="btn btn-xs btn-primary">Detail</a>';
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
					data: "nik",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "tipe_data",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "tanggal_eskalasi",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "catatan_sekolah",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "tanggal_klarifikasi",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "nama_pengguna_dinas",
					className: "text-center",
					orderable: 'true',
				},
				{
					data: "catatan_dinas",
					className: "text-center",
					orderable: 'true',
				},
			],
         order: [ [1, 'asc'] ],
	});

	//reload every 5 mins
	var refresh_datatable = window.setInterval(function(){
		dt_profil.ajax.reload(null, true);
		dt_lokasi.ajax.reload(null, true);

		//console.log("reload completed!");
	}, 5*60000);

});

</script>

</html>
