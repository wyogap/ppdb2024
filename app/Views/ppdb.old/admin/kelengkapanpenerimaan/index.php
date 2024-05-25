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
							<i class="glyphicon glyphicon-list-alt"></i> Kelengkapan Jalur Penerimaan</small>
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
								<th class="text-center" data-priority="2">Jalur Penerimaan</th>
								<th class="text-center" data-priority="2"><i class="glyphicon glyphicon-edit"></i> Kelengkapan</th>
								<th class="text-center" data-priority="43">Urutan</th>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i> Verifikasi?</th>
								<th class="text-center"><i class="glyphicon glyphicon-edit"></i> Wajib?</th>
								<th class="text-center">Dokumen Fisik?</th>
								<th class="text-center">Daftar Ulang</th>
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
        ajax: "<?php echo site_url('admin/kelengkapanpenerimaan/json'); ?>?tahun_ajaran=<?php echo $tahun_ajaran_aktif; ?>",
        table: "#tnegeri",
		idSrc: "kelengkapan_dokumen_id",
        fields: [ 
			{
				label: "Jalur Penerimaan:",
                name: "penerapan_id",
                type: "select",
            }, {
                label: "Kelengkapan",
                name: "daftar_kelengkapan_id",
                type:  "select",
            }, {
                label: "Perlu Verifikasi?",
                name: "perlu_verifikasi",
                type:  "select",
                options: [
                    { label: "Ya", 		value: "1" },
                    { label: "Tidak",   value: "0" },
                ]
            }, {
                label: "Wajib Diisi?",
                name: "wajib",
                type:  "select",
                options: [
                    { label: "Ya", 		value: "1" },
                    { label: "Tidak",   value: "0" },
                ]
            // }, {
            //     label: "Dokumen Fisik?",
            //     name: "dokumen_fisik",
            //     type:  "select",
            //     options: [
            //         { label: "Ya", 		value: "1" },
            //         { label: "Tidak",   value: "0" },
            //     ]
            }
        ],
		i18n: {
			create: {
				button: "Baru",
				title:  "Kelengkapan penerimaan baru",
				submit: "Simpan"
			},
			edit: {
				button: "Ubah",
				title:  "Ubah kelengkapan penerimaan",
				submit: "Simpan"
			},
			remove: {
				button: "Hapus",
				title:  "Hapus kelengkapan penerimaan",
				submit: "Hapus",
				confirm: {
					_: "Konfirmasi hapus %d kelengkapan penerimaan?",
					1: "Konfirmasi hapus 1 kelengkapan penerimaan?"
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
			"sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 baris",
			"sInfoFiltered": "(disaring dari _MAX_ baris keseluruhan)",
			"sInfoPostFix":  "",
			"sSearch":       "Cari:",
			"sUrl":          ""
		},
        ajax: "<?php echo site_url('admin/kelengkapanpenerimaan/json'); ?>?tahun_ajaran=<?php echo $tahun_ajaran_aktif; ?>",
        columns: [
            {
                data: "kelengkapan_dokumen_id", className: 'dt-body-right',
                orderable: false
            },
            { data: "penerapan", className: 'dt-body-left', editField: 'penerapan_id' },
            { data: "daftar_kelengkapan", className: 'editable dt-body-left', editField: 'daftar_kelengkapan_id' },
            { data: "urutan", className: 'dt-body-center' },
            { data: "perlu_verifikasi", className: 'editable dt-body-center', 
					"render": function (val, type, row) {
						return val == 0 ? "Tidak" : "Ya";
					} 
			},
            { data: "wajib", className: 'editable dt-body-center', 
					"render": function (val, type, row) {
						return val == 0 ? "Tidak" : "Ya";
					} 
			},
            { data: "dokumen_fisik", className: 'dt-body-center', 
					"render": function (val, type, row) {
						return val == 1 ? "Ya" : "Tidak";
					} 
			},
            { data: "daftar_ulang", className: 'dt-body-center', 
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
        order: [ [ 1, 'asc' ], [ 3, 'asc' ] ]
	});

	//Event On Change Dropdown
	$('select[name="tahunajaran"]').on('change', function() {
		window.location.replace("<?php echo site_url('admin/kelengkapanpenerimaan'); ?>?tahun_ajaran=" + $("#tahunajaran").val());
	});
});

</script>

</html>
