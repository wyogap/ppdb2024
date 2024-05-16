<!DOCTYPE html>
<html>
	<?php view('head');?>

<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Select-1.3.1/css/select.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Editor-1.9.2/css/editor.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/tcg/dt-editor-select2.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ppdb.css">

<script src="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Select-1.3.1/js/dataTables.select.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/tcg/dt-editor-select2.js"></script>

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
							<i class="glyphicon glyphicon-list-alt"></i> Daftar Kelengkapan</small>
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
		<!-- <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#pnegeri" data-toggle="tab">SMP Negeri</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="pnegeri"> -->
					<table class="display" id="tnegeri" style="width:100%;">
						<thead>
							<tr>
								<th class="text-center" data-priority="1">#</th>
								<th class="text-center" data-priority="2">Nama</th>
								<th class="text-center" data-priority="43"><i class="glyphicon glyphicon-edit"></i> Urutan</th>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i> Master Kelengkapan</th>
								<th class="text-center text-nowrap"><i class="glyphicon glyphicon-edit"></i> Dokumen Fisik</th>
								<th class="text-center text-nowrap"><i class="glyphicon glyphicon-edit"></i> Daftar Ulang</th>
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

var editor; // use a global for the submit and return data rendering in the examples
 
$(document).ready(function() {
	$.extend( $.fn.dataTable.defaults, { responsive: true } );

	$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
	} );
	
	editor = new $.fn.dataTable.Editor( {
        ajax: "<?php echo site_url('admin/daftarkelengkapan/json'); ?>?tahun_ajaran=<?php echo $tahun_ajaran_aktif; ?>",
        table: "#tnegeri",
		idSrc: "daftar_kelengkapan_id",
        fields: [ 
			{
                label: "Urutan:",
                name: "urutan",
                type: "text",
				attr: { type: "number" }
            }, {
				label: "Nama:",
                name: "nama",
                type: "text",
            }, {
				label: "Master Kelengkapan:",
                name: "master_kelengkapan_id",
                type: "tcg_select2",
            }, {
                label: "Dokumen Fisik?",
                name: "dokumen_fisik",
                type:  "select",
                options: [
                    { label: "Ya", 		value: "1" },
                    { label: "Tidak",   value: "0" },
                ]
			}, {
                label: "Dokumen Daftar Ulang",
                name: "daftar_ulang",
                type:  "select",
                options: [
                    { value: "-1", label: "Tidak perlu" },
                    { value: "0", label: "Fotokopi" },
                    { value: "1", label: "Dokumen asli" },
                    { value: "2", label: "Fotokopi yang dilegalisir" },
                ]
            }
        ],
		i18n: {
			create: {
				button: "Baru",
				title:  "Kelengkapan baru",
				submit: "Simpan"
			},
			edit: {
				button: "Ubah",
				title:  "Ubah kelengkapan",
				submit: "Simpan"
			},
			remove: {
				button: "Hapus",
				title:  "Hapus kelengkapan",
				submit: "Hapus",
				confirm: {
					_: "Konfirmasi hapus %d kelengkapan?",
					1: "Konfirmasi hapus 1 kelengkapan?"
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
		"dom": 'Bfrtpil',
		select: true,
		buttons: [
			{ extend: "create", editor: editor },
			{ extend: "edit",   editor: editor },
			{ extend: "remove", editor: editor },
		],
		"language": {
			"sProcessing":   "Sedang proses...",
			"sZeroRecords":  "Tidak ditemukan data yang sesuai",
			"sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 entri",
			"sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
			"sInfoPostFix":  "",
			"sSearch":       "Cari:",
			"sUrl":          ""
		},
        ajax: "<?php echo site_url('admin/daftarkelengkapan/json'); ?>",
		// columnDefs: [
		// 		{ "width": "400px", "targets": 1 },	
		// 		{ "width": "200px", "targets": 3 },
		// 		{ "width": "200px", "targets": 4 }
		// 	],
        columns: [
            {
                data: "daftar_kelengkapan_id", className: 'dt-body-right',
                orderable: false
            },
            { data: "nama", className: 'dt-body-left' },
            { data: "urutan", className: 'editable dt-body-center' },
            { data: "master_kelengkapan", className: 'editable dt-body-left', editField: "master_kelengkapan_id" },
            { data: "dokumen_fisik", className: 'editable dt-body-center', 
					"render": function (val, type, row) {
						return val == 1 ? "Ya" : "Tidak";
					} 
			},
            { data: "daftar_ulang", className: 'editable dt-body-center', 
					"render": function (val, type, row) {
						if (type == 'display') {
							switch(val) {
								case '-1':	return 'Tidak perlu';
								case '0':	return 'Fotokopi';
								case '1': return 'Dokumen asli';
								case '2': return 'Fotokopi dilegalisir';
							}

							return val;
						}
						return val;
					} 
			},
        ],
        order: [ 2, 'asc' ]
	});

});

</script>

</html>
