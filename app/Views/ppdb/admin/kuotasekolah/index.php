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

	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
			<?php view('header');?>
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<h1 class="text-white">
							<i class="glyphicon glyphicon-th-list"></i> Kuota Sekolah</small>
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
					</section>
					<section class="content">

					<span><?php if(isset($info)){echo $info;}?></span>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#pnegeri" data-toggle="tab">SMP Negeri</a></li>
				<li ><a href="#pswasta" data-toggle="tab">SMP Swasta</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="pnegeri">
					<table class="display" id="tnegeri" style="width:100%">
						<thead>
							<tr>
								<!-- <th class="text-center" data-priority="1"></th>
								<th class="text-center" data-priority="2">#</th> -->
								<th class="text-center" data-priority="1">NPSN</th>
								<th class="text-center" data-priority="3">Nama</th>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i><br>Kuota Total</th>
								<?php if ($negeri_zonasi==1) { ?>
								<th class="text-center">Zonasi</th>
								<?php } ?>
								<?php if ($negeri_prestasi==1) { ?>
								<!-- TODO: editable based on config -->
								<th class="text-center">Prestasi</th>
								<?php } ?>
								<?php if ($negeri_afirmasi==1) { ?>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i><br>Afirmasi</th>
								<?php } ?>
								<?php if ($negeri_perpindahan==1) { ?>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i><br>Perpindahan Orang Tua</th>
								<?php } ?>
								<?php if ($negeri_inklusi==1) { ?>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i><br>Inklusi</th>
								<?php } ?>
								<?php if ($negeri_susulan==1) { ?>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i><br>Susulan</th>
								<?php } ?>
							</tr>
						</thead>
					</table>
				</div>
				<div class="tab-pane" id="pswasta">
					<table class="display" id="tswasta" style="width:100%">
						<thead>
							<tr>
								<th class="text-center" data-priority="1"></th>
								<!-- <th class="text-center" data-priority="2">#</th> -->
								<th class="text-center" data-priority="1">NPSN</th>
								<th class="text-center" data-priority="3">Nama</th>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i><br>Ikut PPDB</th>
								<th class="text-center">Kuota Total</th>
								<?php if ($swasta_zonasi==1) { ?>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i><br>Zonasi</th>
								<?php } ?>
								<?php if ($swasta_prestasi==1) { ?>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i><br>Prestasi</th>
								<?php } ?>
								<?php if ($swasta_afirmasi==1) { ?>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i><br>Afirmasi</th>
								<?php } ?>
								<?php if ($swasta_perpindahan==1) { ?>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i><br>Perpindahan Orang Tua</th>
								<?php } ?>
								<?php if ($swasta_inklusi==1) { ?>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i><br>Inklusi</th>
								<?php } ?>
								<?php if ($swasta_swasta==1) { ?>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i><br>Kuota Swasta</th>
								<?php } ?>
							</tr>
						</thead>
					</table>
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
var editor; // use a global for the submit and return data rendering in the examples
var editor2; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

	$.extend( $.fn.dataTable.defaults, { responsive: true } );

	$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
	} );
 
	editor = new $.fn.dataTable.Editor( {
        ajax: "<?php echo site_url('admin/kuota/tablekuotanegeri/'); ?>?tahun_ajaran=<?php echo $tahun_ajaran_aktif; ?>",
        table: "#tnegeri",
		idSrc: "sekolah_id",
        fields: [ 
			{
                label: "Sekolah ID:",
                name: "sekolah_id",
                type: "text"
            },
			{
                label: "Kuota Total:",
                name: "kuota_total",
                type: "text",
				attr: { type: "number" }
			<?php if ($negeri_zonasi==1) { ?>
            }, {
                label: "Kuota Zonasi:",
                name: "kuota_zonasi",
                type: "text",
				attr: { type: "number" }
			<?php } ?>
			<?php if ($negeri_prestasi==1) { ?>
            }, {
                label: "Kuota Prestasi:",
                name: "kuota_prestasi",
                type: "text",
				attr: { type: "number" }
			<?php } ?>
			<?php if ($negeri_afirmasi==1) { ?>
            }, {
                label: "Kuota Afirmasi:",
                name: "kuota_afirmasi",
                type: "text",
				attr: { type: "number" }
			<?php } ?>
			<?php if ($negeri_perpindahan==1) { ?>
			}, {
                label: "Kuota Perpindahan Ortu:",
                name: "kuota_perpindahan_ortu",
                type: "text",
				attr: { type: "number" }
			<?php } ?>
			<?php if ($negeri_inklusi==1) { ?>
            }, {
                label: "Kuota Inklusi:",
                name: "kuota_inklusi",
                type: "text",
				attr: { type: "number" }
			<?php } ?>
			<?php if ($negeri_susulan==1) { ?>
            }, {
                label: "Kuota Susulan:",
                name: "kuota_susulan",
                type: "text",
				attr: { type: "number" }
			<?php } ?>
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

	$('#tnegeri').DataTable({
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
        ajax: "<?php echo site_url('admin/kuota/tablekuotanegeri/'); ?>?tahun_ajaran=<?php echo $tahun_ajaran_aktif; ?>",
        columns: [
            { data: "npsn", className: 'dt-body-center readonly-column' },
            { data: "nama", className: 'dt-body-left readonly-column' },
            { data: "kuota_total", className: 'editable dt-body-center',
				"render": function (val, type, row) {
					let total = parseInt(row.kuota_zonasi)+parseInt(row.kuota_prestasi)+parseInt(row.kuota_afirmasi)
									+parseInt(row.kuota_perpindahan_ortu)+parseInt(row.kuota_inklusi);
					if (val != total) {
						return val +" != "+ total;
					} else {
						return ""+total;
					}
				} 
			},
			<?php if ($negeri_zonasi==1) { ?>
            { data: "kuota_zonasi", className: 'dt-body-center' },
			<?php } ?>
			<?php if ($negeri_prestasi==1) { ?>
            { data: "kuota_prestasi", className: 'dt-body-center editable' },
			<?php } ?>
			<?php if ($negeri_afirmasi==1) { ?>
            { data: "kuota_afirmasi", className: 'dt-body-center editable' },
			<?php } ?>
			<?php if ($negeri_perpindahan==1) { ?>
            { data: "kuota_perpindahan_ortu", className: 'dt-body-center editable' },
			<?php } ?>
			<?php if ($negeri_inklusi==1) { ?>
            { data: "kuota_inklusi", className: 'dt-body-center editable' },
			<?php } ?>
			<?php if ($negeri_susulan==1) { ?>
            { data: "kuota_susulan", className: 'dt-body-center editable' },
			<?php } ?>
        ],
        // order: [ 0, 'asc' ],
	});

	editor2 = new $.fn.dataTable.Editor( {
        ajax: "<?php echo site_url('admin/kuota/tablekuotaswasta/'); ?>?tahun_ajaran=<?php echo $tahun_ajaran_aktif; ?>",
        table: "#tswasta",
		idSrc: "sekolah_id",
        fields: [ 
			{
                label: "Ikut PPDB?",
                name: "ikut_ppdb",
                type:  "select",
                options: [
                    { label: "Ya", 		value: "1" },
                    { label: "Tidak",   value: "0" },
                ]
			<?php if ($swasta_zonasi==1) { ?>
            }, {
                label: "Kuota Zonasi:",
                name: "kuota_zonasi",
                type: "text",
				attr: { type: "number" }
			<?php } ?>
			<?php if ($swasta_prestasi==1) { ?>
            }, {
                label: "Kuota Prestasi:",
                name: "kuota_prestasi",
                type: "text",
				attr: { type: "number" }
			<?php } ?>
			<?php if ($swasta_afirmasi==1) { ?>
            }, {
                label: "Kuota Afirmasi:",
                name: "kuota_afirmasi",
                type: "text",
				attr: { type: "number" }
			<?php } ?>
			<?php if ($swasta_perpindahan==1) { ?>
			}, {
                label: "Kuota Perpindahan Ortu:",
                name: "kuota_perpindahan_ortu",
                type: "text",
				attr: { type: "number" }
			<?php } ?>
			<?php if ($swasta_inklusi==1) { ?>
            }, {
                label: "Kuota Inklusi:",
                name: "kuota_inklusi",
                type: "text",
				attr: { type: "number" }
			<?php } ?>
			<?php if ($swasta_swasta==1) { ?>
            }, {
                label: "Kuota Swasta:",
                name: "kuota_swasta",
                type: "text",
				attr: { type: "number" }
			<?php } ?>
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
    $('#tswasta').on( 'click', 'tbody td.editable', function (e) {
        editor2.bubble( this );
    } );

    // Inline editing in responsive cell
    $('#tswasta').on( 'click', 'tbody ul.dtr-details li', function (e) {
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
        editor2.bubble( $('span.dtr-data', this) );
    } );

    // Edit record
    $('#tswasta').on('click', 'a.editor_edit', function (e) {
        e.preventDefault();
 
        editor2.edit( $(this).closest('tr'), {
            title: 'Ubah Kuota Sekolah',
            buttons: 'Simpan Perubahan'
        } );
    } );
 
 	$('#tswasta').DataTable({
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
        ajax: "<?php echo site_url('admin/kuota/tablekuotaswasta/'); ?>?tahun_ajaran=<?php echo $tahun_ajaran_aktif; ?>",
        columns: [
            {
                data: null,
                className: "center",
                defaultContent: '<a href="" class="editor_edit btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>'
            },
            { data: "npsn", className: 'dt-body-center readonly-column' },
            { data: "nama", className: 'dt-body-left readonly-column' },
            { data: "ikut_ppdb", className: 'editable dt-body-center', 
					"render": function (val, type, row) {
						return val == 0 ? "Tidak" : "Ya";
					} 
			},
            { data: "kuota_total", className: 'dt-body-center readonly-column' },
			<?php if ($swasta_zonasi==1) { ?>
            { data: "kuota_zonasi", className: 'editable dt-body-center' },
			<?php } ?>
			<?php if ($swasta_prestasi==1) { ?>
            { data: "kuota_prestasi", className: 'editable dt-body-center' },
			<?php } ?>
			<?php if ($swasta_afirmasi==1) { ?>
            { data: "kuota_afirmasi", className: 'editable dt-body-center' },
			<?php } ?>
			<?php if ($swasta_perpindahan==1) { ?>
            { data: "kuota_perpindahan_ortu", className: 'editable dt-body-center' },
			<?php } ?>
			<?php if ($swasta_inklusi==1) { ?>
            { data: "kuota_inklusi", className: 'editable dt-body-center' },
			<?php } ?>
			<?php if ($swasta_swasta==1) { ?>
            { data: "kuota_swasta", className: 'editable dt-body-center' },
			<?php } ?>
        ],
	});

	$('select[name="tahunajaran"]').on('change', function() {
		window.location.replace("<?php echo site_url('admin/kuota'); ?>?tahun_ajaran=" + $("#tahunajaran").val());
	});
});

</script>

</html>
