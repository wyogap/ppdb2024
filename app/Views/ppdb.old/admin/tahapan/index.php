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
<script src="<?php echo base_url();?>assets/adminlte/plugins/moment/moment-with-locales.min.js"></script>

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
							<i class="glyphicon glyphicon-list-alt"></i> Tahapan Penerimaan</small>
						</h1>
						<!-- <ol class="breadcrumb">
							<li class="active"><a href="#"><i class="glyphicon glyphicon-th-list"></i> Peringkat Pendaftaran</a></li>
						</ol> -->
						<div class="tahun-selection">
							<div class="tahun-selection-label">
							Tahun Ajaran: 
							</div>
								<select id="tahunajaran" name="tahunajaran" class="tahun-selection-control" data-validation="required">
								<?php foreach($tahun_ajaran->getResult() as $row2): ?>
									<option value="<?php echo $row2->tahun_ajaran_id; ?>" <?php if($row2->tahun_ajaran_id==$tahun_ajaran_aktif){?>selected="true"<?php }?>><?php echo $row2->tahun_ajaran_id; ?></option>
								<?php endforeach;?>
								</select>

						</div>
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
		<!-- <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#pnegeri" data-toggle="tab">SMP Negeri</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="pnegeri"> -->
					<table class="display" id="tnegeri" style="width:100%">
						<thead>
							<tr>
								<!-- <th class="text-center" data-priority="1"></th> -->
								<th class="text-center" data-priority="2">#</th>
								<th class="text-center" data-priority="3">Tahapan</th>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i> Mulai</th>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i> Selesai</th>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i> Notifikasi Umum</th>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i> Notifikasi Siswa</th>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i> Notifikasi Sekolah</th>
							</tr>
						</thead>
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

// Tabel
var editor; // use a global for the submit and return data rendering in the examples
 
$(document).ready(function() {
	$.extend( $.fn.dataTable.defaults, { responsive: true } );

	$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
	} );
 
   editor = new $.fn.dataTable.Editor( {
        ajax: "<?php echo site_url('admin/tahapan/json'); ?>?tahun_ajaran=<?php echo $tahun_ajaran_aktif; ?>",
        table: "#tnegeri",
		idSrc: "waktu_pelaksanaan_id",
        fields: [ 
			{
                label: "Tanggal Mulai:",
                name: "tanggal_mulai",
                type: "datetime",
                def:       function () { return new Date(); },
                format:    'YYYY-MM-DD HH:mm:00',
                opts: {
                    minutesIncrement: 5
                }
           }, {
                label: "Tanggal Selesai:",
                name: "tanggal_selesai",
                type: "datetime",
                def:       function () { return new Date(); },
                format:    'YYYY-MM-DD HH:00:00',
                opts: {
                    minutesIncrement: 5
                }
			}, {
                label: "Notifikasi Untuk Umum:",
                name: "notifikasi_umum",
                type: "textarea"
			}, {
                label: "Notifikasi Untuk Siswa:",
                name: "notifikasi_siswa",
                type: "textarea"
			}, {
                label: "Notifikasi Untuk Sekolah:",
                name: "notifikasi_sekolah",
                type: "textarea"
            }
        ],
        i18n: {
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
    } );
 
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
        if ( colnum == 1 ) {
            return;
        }
 		
        // Edit the value, but this method allows clicking on label as well
        editor.bubble( $('span.dtr-data', this) );
    } );

	$('#tnegeri').dataTable({
		"responsive": true,
		"paging": false,
		"dom": 'rt',
		"language": {
			"sProcessing":   "Sedang proses...",
			"sZeroRecords":  "Tidak ditemukan data yang sesuai",
			"sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 baris",
			"sInfoFiltered": "(disaring dari _MAX_ baris keseluruhan)",
			"sInfoPostFix":  "",
			"sSearch":       "Cari:",
			"sUrl":          ""
		},
        ajax: "<?php echo site_url('admin/tahapan/json'); ?>?tahun_ajaran=<?php echo $tahun_ajaran_aktif; ?>",
        columns: [
            { data: "tahapan_id", className: 'dt-body-right' },
            { data: "tahapan" },
            { data: "tanggal_mulai", className: 'editable dt-body-center dt-nowrap' },
            { data: "tanggal_selesai", className: 'editable dt-body-center dt-nowrap' },
            { data: "notifikasi_umum", className: 'editable dt-body-left', width: "20%" },
            { data: "notifikasi_siswa", className: 'editable dt-body-left', width: "20%" },
            { data: "notifikasi_sekolah", className: 'editable dt-body-left', width: "20%" },
        ],
        order: [ 0, 'asc' ],
	});

	$('select[name="tahunajaran"]').on('change', function() {
		window.location.replace("<?php echo site_url('admin/tahapan'); ?>?tahun_ajaran=" + $("#tahunajaran").val());
	});
});

	// //Event On Change Dropdown
	// $(document).ready(function () {
	// 	$('select[name="tahunajaran"]').on('change', function() {
	// 		window.location.replace("<?php echo site_url('Cadmin/tahapanpenerimaan'); ?>?tahun_ajaran=" + $("#tahunajaran").val());
	// 	});
	// });

</script>

</html>
