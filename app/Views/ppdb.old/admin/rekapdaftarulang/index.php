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
							<i class="glyphicon glyphicon-list-alt"></i> Rekapitulasi Daftar Ulang</small>
						</h1>
						<!-- </div> -->
					</section>
					<section class="content">

					<span><?php if(isset($info)){echo $info;}?></span>

<div class="box box-solid">
	<!-- <div class="box-header with-border">
		<i class="glyphicon glyphicon-edit text-info"></i>
		<h3 class="box-title text-info"><b>Kuota Sekolah</b></h3>
	</div> -->
	<div class="box-body">

	<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<!-- <div class="box-header with-border">
				<i class="glyphicon glyphicon-search"></i>
				<h3 class="box-title"><b>Pencarian Siswa</b></h3>
			</div> -->
			<div class="box-body">
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="sekolah_id">Asal Sekolah</label>
							<select id="sekolah_id" name="sekolah_id" class="form-control select2" style="width:100%;">
								<option value="">--</option>
								<?php foreach($daftarsekolah->getResult() as $row):?>									
								<option value="<?php echo $row->sekolah_id;?>">(<?php echo $row->npsn;?>) <?php echo $row->nama;?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="sekolah_tujuan_id">Sekolah Tujuan</label>
							<select id="sekolah_tujuan_id" name="sekolah_tujuan_id" class="form-control select2" style="width:100%;">
								<option value="">--</option>
								<?php foreach($daftarsekolahtujuan->getResult() as $row):?>									
								<option value="<?php echo $row->sekolah_id;?>">(<?php echo $row->npsn;?>) <?php echo $row->nama;?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="status_sekolah_tujuan">Status Sekolah Tujuan</label>
							<select id="status_sekolah_tujuan" name="status_sekolah_tujuan" class="form-control select2" style="width:100%;">
								<option value="">--</option>
								<option value="N" selected>Negeri</option>
								<option value="S">Swasta</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<a href="javascript:void(0)" onclick="reload(); return false;" class="btn btn-primary btn-flat">Filter</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center justify-content-center" id="loading" style="position: absolute; margin-top: 24px; margin-left: -12px;">
			<div class="loader" role="status">
				<span class="sr-only">Loading...</span>
			</div>
		</div>
		<div class="box box-solid">
			<!-- <div class="box-header with-border">
				<i class="glyphicon glyphicon-search"></i>
				<h3 class="box-title"><b>Pencarian Siswa</b></h3>
			</div> -->
			<div class="box-body">
					<table class="display" id="tnegeri" style="width:100%">
						<thead>
							<tr>
								<th class="text-center" data-priority="1">#</th>
								<th class="text-center">Peserta Didik Id</th>
								<th class="text-center" data-priority="2">NPSN Sekolah Asal</th>
								<th class="text-center" data-priority="4">Nama Sekolah Asal</th>
								<th class="text-center">NIK</th>
								<th class="text-center">NISN</th>
								<th class="text-center" data-priority="3">Nama</th>
								<th class="text-center">Tempat Lahir</th>
								<th class="text-center">Tanggal Lahir</th>
								<th class="text-center">Jenis Kelamin</th>
								<th class="text-center">Nama Ibu Kandung</th>
								<th class="text-center">Sekolah Id Tujuan</th>
								<th class="text-center" data-priority="2">NPSN Sekolah Tujuan</th>
								<th class="text-center" data-priority="5">Nama Sekolah Tujuan</th>
							</tr>
						</thead>
					</table>
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

var sekolah_id = '';
var sekolah_tujuan_id = '';
var status_sekolah_tujuan = 'N';

//Dropdown Select
$(function () {
	$(".select2").select2();
});

var dt = null;

$(document).ready(function() {
	$.extend( $.fn.dataTable.defaults, { responsive: true } );

	$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
	} );
 
 
	dt = $('#tnegeri').DataTable({
		"responsive": true,
		"pageLength": 25,
		"lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
		"paging": true,
		"pagingType": "numbers",
		"dom": 'Bfrtpil',
		select: true,
		buttons: [
			'copyHtml5',
            {
                extend: 'excelHtml5',
                exportOptions: {
                    orthogonal: "export",
                    modifier: {
                        //selected: true
                    },
                },
            },
			'pdfHtml5',
			'print',
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
		},	
		"ajax": {
			"type" : "GET",
			"url" : "<?php echo site_url('admin/rekapdaftarulang/json'); ?>?status_sekolah_tujuan=" +status_sekolah_tujuan,
			"dataSrc": function ( json ) {
				//hide loader
				$("#loading").hide();

				//actual data source
				return json.data;
			}       
        },
        columns: [
			{ "data": "id", orderable: false,
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
            { data: "peserta_didik_id", className: 'dt-body-left' },
            { data: "npsn_sekolah_asal", className: 'dt-body-left' },
            { data: "nama_sekolah_asal", className: 'dt-body-center' },
            { data: "nik", className: 'dt-body-left',
				render: function (data, type, row, meta) {
					if (type == 'export') {
						return '\u200C' + data;
					}

					return data;
				}
			},
            { data: "nisn", className: 'dt-body-left' },
            { data: "nama", className: 'dt-body-left' },
            { data: "tempat_lahir", className: 'dt-body-left' },
            { data: "tanggal_lahir", className: 'dt-body-left' },
            { data: "jenis_kelamin", className: 'dt-body-left' },
            { data: "nama_ibu_kandung", className: 'dt-body-left' },
            { data: "sekolah_id_tujuan", className: 'dt-body-left' },
            { data: "npsn_sekolah_tujuan", className: 'dt-body-left' },
            { data: "nama_sekolah_tujuan", className: 'dt-body-left' },
        ],
        order: [ [12, 'asc'], [2, 'asc'] ],
	});

    dt.on( 'order.dt search.dt', function () {
        dt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

});

function reload() {
		sekolah_id_baru = $("#sekolah_id").val();
		sekolah_tujuan_id_baru = $("#sekolah_tujuan_id").val();
		status_sekolah_tujuan_baru = $("#status_sekolah_tujuan").val();

		if (sekolah_id == sekolah_id_baru && sekolah_tujuan_id == sekolah_tujuan_id_baru && status_sekolah_tujuan == status_sekolah_tujuan_baru) {
			return;
		}

		sekolah_id = sekolah_id_baru;
		sekolah_tujuan_id = sekolah_tujuan_id_baru;
		status_sekolah_tujuan = status_sekolah_tujuan_baru;

		//show loader
		$("#loading").show();

		//reload
		dt.ajax.url("<?php echo site_url('admin/rekapdaftarulang/json'); ?>?sekolah_asal_id=" + sekolah_id + "&sekolah_tujuan_id=" + sekolah_tujuan_id + "&status_sekolah_tujuan=" + status_sekolah_tujuan_baru);
		dt.ajax.reload();
  }

</script>

</html>
