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
							<i class="glyphicon glyphicon-list-alt"></i> Jalur Penerimaan</small>
						</h1>
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
					<table class="display" id="tnegeri" style="width:100%;">
						<thead>
							<tr>
								<th class="text-center" data-priority="1">#</th>
								<th class="text-center" data-priority="2">Nama</th>
								<th class="text-center" data-priority="43"><i class="glyphicon glyphicon-edit"></i> Urutan</th>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i> Aktif</th>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i>&nbsp;Kuota (%)</th>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i> Negeri</th>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i> Swasta</th>
								<th class="text-center" data-priority="3"><i class="glyphicon glyphicon-edit"></i> Keterangan</th>
								<th class="text-center none"><i class="glyphicon glyphicon-edit"></i>&nbsp;Dalam Wilayah</th>
								<th class="text-center none"><i class="glyphicon glyphicon-edit"></i>&nbsp;Luar Wilayah</th>
								<th class="text-center none">Jalur</th>
								<th class="text-center none"><i class="glyphicon glyphicon-edit"></i> Kategori Zona</th>
								<th class="text-center none"><i class="glyphicon glyphicon-edit"></i> Kategori Inklusi</th>
								<th class="text-center none"><i class="glyphicon glyphicon-edit"></i> Kategori Susulan</th>
								<th class="text-center none"><i class="glyphicon glyphicon-edit"></i> Perhitungan Jarak</th>
								<th class="text-center none"><i class="glyphicon glyphicon-edit"></i> Perhitungan Prestasi</th>
								<th class="text-center none"><i class="glyphicon glyphicon-edit"></i> Perhitungan Usia</th>
								<th class="text-center none"><i class="glyphicon glyphicon-edit"></i>&nbsp;Pinalti Luar Wilayah</th>
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
        ajax: "<?php echo site_url('admin/penerimaan/json'); ?>?tahun_ajaran=<?php echo $tahun_ajaran_aktif; ?>",
        table: "#tnegeri",
		idSrc: "penerapan_id",
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
				label: "Jalur:",
                name: "jalur_id",
                type: "select",
            }, {
               label: "Aktif?",
                name: "aktif",
                type:  "select",
                options: [
                    { label: "Ya", 		value: "1" },
                    { label: "Tidak",   value: "0" },
                ]
            }, {
				label: "Kuota (%):",
                name: "persen_kuota",
                type: "text",
				attr: { type: "number" }
            }, {
                label: "Siswa Dalam Wilayah?",
                name: "dalam_wilayah_administrasi",
                type:  "select",
                options: [
                    { label: "Ya", 		value: "1" },
                    { label: "Tidak",   value: "0" },
                ]
            }, {
                label: "Siswa Luar Wilayah?",
                name: "luar_wilayah_administrasi",
                type:  "select",
                options: [
                    { label: "Ya", 		value: "1" },
                    { label: "Tidak",   value: "0" },
                ]
            }, {
                label: "Sekolah Negeri?",
                name: "sekolah_negeri",
                type:  "select",
                options: [
                    { label: "Ya", 		value: "1" },
                    { label: "Tidak",   value: "0" },
                ]
            }, {
                label: "Sekolah Swasta?",
                name: "sekolah_swasta",
                type:  "select",
                options: [
                    { label: "Ya", 		value: "1" },
                    { label: "Tidak",   value: "0" },
                ]
            }, {
				label: "Keterangan:",
                name: "keterangan",
                type: "textarea",
			}, {
                label: "Kategori Zona?",
                name: "kategori_zona",
                type:  "select",
                options: [
                    { label: "Ya", 		value: "1" },
                    { label: "Tidak",   value: "0" },
                ]
			}, {
                label: "Kategori Inklusi?",
                name: "kategori_inklusi",
                type:  "select",
                options: [
                    { label: "Ya", 		value: "1" },
                    { label: "Tidak",   value: "0" },
                ]
			}, {
                label: "Kategori Susulan?",
                name: "kategori_susulan",
                type:  "select",
                options: [
                    { label: "Ya", 		value: "1" },
                    { label: "Tidak",   value: "0" },
                ]
			}, {
                label: "Perhitungan Jarak?",
                name: "kategori_jarak",
                type:  "select",
                options: [
                    { label: "Ya", 		value: "1" },
                    { label: "Tidak",   value: "0" },
                ]
			}, {
                label: "Perhitungan Prestasi?",
                name: "kategori_prestasi",
                type:  "select",
                options: [
                    { label: "Ya", 		value: "1" },
                    { label: "Tidak",   value: "0" },
                ]
			}, {
                label: "Perhitungan Usia?",
                name: "kategori_usia",
                type:  "select",
                options: [
                    { label: "Ya", 		value: "1" },
                    { label: "Tidak",   value: "0" },
                ]
            }, {
				label: "Pinalti Siswa Luar Wilayah:",
                name: "pinalti_luar_wilayah",
                type: "text",
				attr: { type: "number" }
            }
        ],
		i18n: {
			create: {
				button: "Baru",
				title:  "Penerapan jalur baru",
				submit: "Simpan"
			},
			edit: {
				button: "Ubah",
				title:  "Ubah penerapan jalur",
				submit: "Simpan"
			},
			remove: {
				button: "Hapus",
				title:  "Hapus penerapan jalur",
				submit: "Hapus",
				confirm: {
					_: "Konfirmasi hapus %d penerapan jalur?",
					1: "Konfirmasi hapus 1 penerapan jalur?"
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
        ajax: "<?php echo site_url('admin/penerimaan/json'); ?>?tahun_ajaran=<?php echo $tahun_ajaran_aktif; ?>",
		columnDefs: [
				{ "width": "100px", "targets": 1 },	
				{ "width": "300px", "targets": 7 }
			],
        columns: [
            {
                data: "penerapan_id", className: 'dt-body-right',
                orderable: false
            },
            { data: "nama", className: 'dt-body-left', "width": "100px" },
            { data: "urutan", className: 'editable dt-body-center' },
            { data: "aktif", className: 'editable dt-body-center', 
					"render": function (val, type, row) {
						return val == 0 ? "Tidak" : "Ya";
					} 
			},
            { data: "persen_kuota", className: 'editable dt-body-center' },
            { data: "sekolah_negeri", className: 'editable dt-body-center', 
					"render": function (val, type, row) {
						return val == 0 ? "Tidak" : "Ya";
					} 
			},
            { data: "sekolah_swasta", className: 'editable dt-body-center', 
					"render": function (val, type, row) {
						return val == 0 ? "Tidak" : "Ya";
					} 
			},
            { data: "keterangan", className: 'editable dt-body-left',
				width: "300px"
			},
            { data: "dalam_wilayah_administrasi", className: 'editable dt-body-center', 
					"render": function (val, type, row) {
						return val == 0 ? "Tidak" : "Ya";
					} 
			},
            { data: "luar_wilayah_administrasi", className: 'editable dt-body-center', 
					"render": function (val, type, row) {
						return val == 0 ? "Tidak" : "Ya";
					} 
			},
			{ data: "jalur", className: 'dt-body-center' },
            { data: "kategori_zona", className: 'editable dt-body-center', 
				"render": function (val, type, row) {
						return val == 0 ? "Tidak" : "Ya";
					} 
			},
            { data: "kategori_inklusi", className: 'editable dt-body-center', 
				"render": function (val, type, row) {
						return val == 0 ? "Tidak" : "Ya";
					} 
			},
            { data: "kategori_susulan", className: 'editable dt-body-center', 
				"render": function (val, type, row) {
						return val == 0 ? "Tidak" : "Ya";
					} 
			},
            { data: "kategori_jarak", className: 'editable dt-body-center', 
				"render": function (val, type, row) {
						return val == 0 ? "Tidak" : "Ya";
					} 
			},
            { data: "kategori_prestasi", className: 'editable dt-body-center', 
				"render": function (val, type, row) {
						return val == 0 ? "Tidak" : "Ya";
					} 
			},
           { data: "kategori_usia", className: 'editable dt-body-center', 
				"render": function (val, type, row) {
						return val == 0 ? "Tidak" : "Ya";
					} 
			},
            { data: "pinalti_luar_wilayah", className: 'editable dt-body-center' },
        ],
        order: [ 2, 'asc' ]
	});

	//Event On Change Dropdown
	$('select[name="tahunajaran"]').on('change', function() {
		window.location.replace("<?php echo site_url('admin/penerimaan'); ?>?tahun_ajaran=" + $("#tahunajaran").val());
	});
});

</script>

</html>
