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
<!-- <script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/JSZip-2.5.0/jszip.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/pdfmake-0.1.36/pdfmake.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/buttons.print.min.js"></script> -->
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
							<i class="glyphicon glyphicon-list-alt"></i> Skoring <small>Prestasi</small>
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
								<th class="text-center" data-priority="2">#</th>
								<th class="text-center">Jalur</th>
								<th class="text-center" data-priority="3"><i class="glyphicon glyphicon-edit"></i> Nama </th>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i> Urutan</th>
								<th class="text-center" data-priority="4"><i class="glyphicon glyphicon-edit"></i> Skor </th>
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

<style>
#form-editor-baru {
    display: flex;
    flex-flow: row wrap;
}
 
#form-editor-baru fieldset {
    flex: 2 100%;
    border: 1px solid #aaa;
	margin: 0.5em;
	padding-bottom: 15px;
}
 
#form-editor-baru fieldset legend {
	padding: 5px 10px;
	margin-left: 10px;
	margin-bottom: 5px;
	width: auto !important;
	border: 1px solid #aaa;
	font-size: 14px;
    /* font-weight: bold; */
}
 
#form-editor-baru fieldset.nilai-baru {
    flex: 2 100%;
}
 
#form-editor-baru fieldset.name legend {
    background: #bfffbf;
}
 
#form-editor-baru fieldset.office legend {
    background: #ffffbf;
}
 
#form-editor-baru fieldset.hr legend {
    background: #ffbfbf;
}
 
/* #form-editor-baru div.DTE_Field {
    padding: 5px 15px;
} */
</style>

<div id="form-editor-baru">
	<fieldset class="nilai-baru">
		<legend>Pilih dari daftar skoring yang sudah ada</legend>
		<editor-field name="jalur_id"></editor-field>
		<editor-field name="skoring_id"></editor-field>
		<editor-field name="nilai"></editor-field>
		<editor-field name="urutan"></editor-field>
	</fieldset>
	<fieldset class="skoring-baru">
		<legend>ATAU buat skoring baru</legend>
		<editor-field name="jalur_id_baru"></editor-field>
		<editor-field name="tipe_skoring_id_baru"></editor-field>
		<editor-field name="nama_baru"></editor-field>
		<editor-field name="nilai_baru"></editor-field>
		<editor-field name="urutan_baru"></editor-field>
	</fieldset>
</div>

<script type="text/javascript">

// Tabel
var editor; // use a global for the submit and return data rendering in the examples
var editor_baru;
var jalur_id = 0;

$(document).ready(function() {
	$.extend( $.fn.dataTable.defaults, { responsive: true } );

	$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
	} );
 
   editor = new $.fn.dataTable.Editor( {
        ajax: "<?php echo site_url('admin/skoring/json'); ?>?tahun_ajaran=<?php echo $tahun_ajaran_aktif; ?>",
        table: "#tnegeri",
		idSrc: "daftar_nilai_skoring_id",
        fields: [ 
			{
                label: "Jalur:",
                name: "jalur_id",
                type: "select",
            }, {
                label: "Tipe Skoring:",
                name: "tipe_skoring_id",
                type: "select",
            }, {
                label: "Nama:",
                name: "nama",
                type: "textarea",
			}, {
                label: "Urutan:",
                name: "urutan",
                type: "text",
				attr: { type: "number" }
          }, {
                label: "Nilai:",
                name: "nilai",
                type: "text",
				attr: { type: "number" }
            }
        ],
		i18n: {
			create: {
				button: "Baru",
				title:  "Skoring baru",
				submit: "Simpan"
			},
			edit: {
				button: "Ubah",
				title:  "Ubah skoring",
				submit: "Simpan"
			},
			remove: {
				button: "Hapus",
				title:  "Hapus skoring",
				submit: "Hapus",
				confirm: {
					_: "Konfirmasi hapus %d skoring?",
					1: "Konfirmasi hapus 1 skoring?"
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
 
	editor_baru = new $.fn.dataTable.Editor( {
        ajax: "<?php echo site_url('admin/skoring/json'); ?>?tahun_ajaran=<?php echo $tahun_ajaran_aktif; ?>",
        table: "#tnegeri",
		idSrc: "daftar_nilai_skoring_id",
		template: '#form-editor-baru',
        fields: [ 
			{
                label: "Jalur:",
                name: "jalur_id",
                type: "select",
            }, {
                label: "Skoring:",
                name: "skoring_id",
                type: "select",
                options: [
                    { label: "-- Pilih Jalur --", value: "0" },
               ]
			}, {
                label: "Nilai:",
                name: "nilai",
                type: "text",
				attr: { type: "number" }
			}, {
                label: "Urutan:",
                name: "urutan",
                type: "text",
				attr: { type: "number" }
            }, {
                label: "Jalur:",
                name: "jalur_id_baru",
                type: "select",
            }, {
                label: "Tipe Skoring:",
                name: "tipe_skoring_id_baru",
                type: "select",
            }, {
                label: "Nama:",
                name: "nama_baru",
                type: "textarea",
            }, {
                label: "Nilai:",
                name: "nilai_baru",
                type: "text",
				attr: { type: "number" }
			}, {
                label: "Urutan:",
                name: "urutan_baru",
                type: "text",
				attr: { type: "number" }
            }
        ],
		i18n: {
			create: {
				button: "Baru",
				title:  "Skoring baru",
				submit: "Simpan"
			},
			edit: {
				button: "Ubah",
				title:  "Ubah skoring",
				submit: "Simpan"
			},
			remove: {
				button: "Hapus",
				title:  "Hapus skoring",
				submit: "Hapus",
				confirm: {
					_: "Konfirmasi hapus %d skoring?",
					1: "Konfirmasi hapus 1 skoring?"
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
		"pageLength": 50,
		"lengthMenu": [ [50, 100, 200, -1], [50, 100, 200, "All"] ],
		"paging": true,
		"pagingType": "numbers",
		"dom": 'Bfrtpil',
		select: true,
		buttons: [
			{ extend: "create", editor: editor_baru },
			{ extend: "edit",   editor: editor },
			{ extend: "remove", editor: editor },
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
        ajax: "<?php echo site_url('admin/skoring/json'); ?>?tahun_ajaran=<?php echo $tahun_ajaran_aktif; ?>",
        columns: [
            { data: "daftar_nilai_skoring_id", className: 'dt-body-right readonly-column' },
            { data: "jalur", className: 'dt-body-center readonly-column', editField: 'jalur_id' },
            { data: "nama", className: 'editable dt-body-left' },
            { data: "urutan", className: 'editable dt-body-center' },
            { data: "nilai", className: 'editable dt-body-center' }
        ],
        order: [ [1, 'asc'],[3, 'asc'] ],
	});

	$('select[name="tahunajaran"]').on('change', function() {
		window.location.replace("<?php echo site_url('admin/skoring'); ?>?tahun_ajaran=" + $("#tahunajaran").val());
	});

    editor_baru.dependent( 'jalur_id', function ( val ) {
		if (val == jalur_id) {
			return;
		}

		jalur_id = val;

		if (jalur_id == 0) {
			let arr = [{value:'0', label:'-- Tidak Ada --'}];
			editor_baru.field( 'skoring_id' ).update(arr);
			return;
		}

        //retrieve list from json
        $.ajax({
          url: "<?php echo site_url('admin/skoring/daftarskoring'); ?>?jalur_id=" + jalur_id + "&tahun_ajaran=" + $("#tahunajaran").val(),
          type: 'GET',
          dataType: 'json',
          beforeSend: function (request) {
              request.setRequestHeader("Content-Type", "application/json");
          },
          success: function(response) {
              if ( response.data === null) {
                editor_baru.field( 'skoring_id' ).error("Gagal mendapatkan daftar skoring.");
              }
              else if (typeof response.error !== 'undefined' && response.error !== null && response.error != "")  {
                editor_baru.field( 'skoring_id' ).error(response.error);
              }
              else {
                editor_baru.field( 'skoring_id' ).update( response.data );
              }
          },
          error: function(jqXhr, textStatus, errorMessage) {
            editor_baru.field( 'skoring_id' ).error(errorMessage);
          }
        });        
 
	});
});

</script>

</html>
