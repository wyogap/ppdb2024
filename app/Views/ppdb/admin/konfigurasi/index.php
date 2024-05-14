<!DOCTYPE html>
<html>
	<?php view('head');?>

<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Editor-1.9.2/css/editor.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ppdb.css">
<script src="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
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
							<i class="glyphicon glyphicon-list-alt"></i> Konfigurasi Sistem
						</h1>
						<!-- <ol class="breadcrumb">
							<li class="active"><a href="#"><i class="glyphicon glyphicon-th-list"></i> Peringkat Pendaftaran</a></li>
						</ol> -->
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
		<!-- <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#pnegeri" data-toggle="tab">SMP Negeri</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="pnegeri"> -->
					<table class="display" id="tnegeri" style="width:100%">
						<thead>
							<tr>
								<th class="text-center" data-priority="1">#</th>
								<th class="text-center">Group</th>
								<th class="text-center" data-priority="2">Name</th>
								<th class="text-center" data-priority="3">Value</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$i=1;
								foreach($settings->getResult() as $row2):
							?>
							<tr>
								<td class="text-center"><?php echo $row2->setting_id; ?></td>
								<td class="text-left"><?php echo strtoupper($row2->group);?></td>
								<td class="text-left"><?php echo $row2->description;?></td>
								<td class="text-left"><?php echo $row2->value;?></td>
							</tr>
							<?php $i++; endforeach;?>
						</tbody>
					</table>
				<!-- </div>
			</div>
		</div> -->
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


var editor;

// Tabel
$(document).ready(function() {
	$.extend( $.fn.dataTable.defaults, { responsive: true } );

	$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
	} );

	editor = new $.fn.dataTable.Editor( {
			ajax: "<?php echo site_url('admin/konfigurasi/json'); ?>",
			table: "#tnegeri",
			idSrc: "setting_id",
			fields: [ 
			{
				label: "Value:",
				name: "value",
				type: "textarea",
			}
			],
			i18n: {
			create: {
				button: "Baru",
				title:  "Konfigurasi baru",
				submit: "Simpan"
			},
			edit: {
				button: "Ubah",
				title:  "Ubah konfigurasi",
				submit: "Simpan"
			},
			remove: {
				button: "Hapus",
				title:  "Hapus konfigurasi",
				submit: "Hapus",
				confirm: {
					_: "Konfirmasi hapus %d konfigurasi?",
					1: "Konfirmasi hapus 1 konfigurasi?"
				}
			},        
			error: {
				system: "Ada permasalahan dalam menyimpan data. Silahkan hubungi admin sistem."
			},
			datetime: {
				previous: 'Sebelum',
				next:     'Selanjutnya',
				months:   [ 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember' ],
				weekdays: [ 'Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab' ],
				hour: 'Jam',
				minute: 'Menit'
			}
			}
		});
	
		// Activate the bubble editor on click of a table cell
		$('#tnegeri').on( 'click', 'tbody td.editable', function (e) {
			editor.bubble( this );
		} );

		// Inline editing in responsive cell
		$('#tnegeri').on( 'click', 'tbody ul.dtr-details li', function (e) {
			// Ignore the Responsive control and checkbox columns
			if ( $(this).hasClass( 'control' ) || $(this).hasClass('select-checkbox') ) {
				return;
			}
	
			//ignore read-only column
			var colnum = $(this).attr( 'data-dt-column' );
			if ( colnum != 3 ) {
				return;
			}
		
			// Edit the value, but this method allows clicking on label as well
			editor.bubble( $('span.dtr-data', this) );
		});


	$('#tnegeri').dataTable({
		"responsive": true,
		"paging": false,
		"dom": 'rt',
		"language": {
			"sProcessing":   "Sedang proses...",
			"sZeroRecords":  "Tidak ditemukan data yang sesuai",
			"sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 entri",
			"sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
			"sInfoPostFix":  "",
			"sSearch":       "Cari:",
			"sUrl":          ""
		},		
		columns: [
			{ data: "setting_id", className: 'dt-body-center' },
			{ data: "group", className: 'dt-body-left' },
			{ data: "description", className: 'dt-body-left' },
			{ data: "value", className: 'dt-body-left editable'},
		],
		order: [ 0, 'asc' ],
	});

});

</script>

</html>
