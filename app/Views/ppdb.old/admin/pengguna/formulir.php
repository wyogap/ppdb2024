<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url();?>assets/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>

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

<!-- <script src="<?php echo base_url();?>assets/datatables/JSZip-2.5.0/jszip.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/pdfmake-0.1.36/pdfmake.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Buttons-1.6.1/js/buttons.print.min.js"></script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/4.6.3/papaparse.min.js"> </script>

<span><?php if(isset($info)){echo $info;}?></span>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<!-- <div class="box-header with-border">
				<i class="glyphicon glyphicon-search"></i>
				<h3 class="box-title"><b>Pencarian Siswa</b></h3>
			</div> -->
			<div class="box-body">
				<div class="row">

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="nama">Nama</label>
							<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" minlength="3" maxlength="100"/>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="username">Username</label>
							<input type="text" class="form-control" id="username" name="username" placeholder="Pengguna" minlength="3" maxlength="100"/>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="sekolah_id">Sekolah</label>
							<select id="sekolah_id" name="sekolah_id" class="form-control select2" style="width:100%;">
								<option value="">--</option>
								<?php foreach($daftarsekolah->getResult() as $row2): ?>									
								<option value="<?php echo $row2->sekolah_id;?>">(<?php echo $row2->npsn;?>) <?php echo $row2->nama;?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="peran_id">Peran</label>
							<select id="peran_id" name="peran_id" class="form-control select2" style="width:100%;">
								<option value="">--</option>
								<?php foreach($daftarperan->getResult() as $peran): 
									if ($peran->peran_id == 1) continue;
								?>									
								<option value="<?php echo $peran->peran_id;?>"><?php echo $peran->nama;?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

				</div>
			</div>
			<div class="box-footer">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<a href="javascript:void(0)" onclick="reload()" class="btn btn-primary btn-flat">Cari Pengguna</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center justify-content-center" id="loading" style="position: absolute; margin-top: 24px; margin-left: -12px; display: none;">
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
					<table class="display" id="tpengguna" style="width:100%;">
						<thead>
							<tr>
								<!-- <td class="text-center" data-priority="1">#</td> -->
								<td class="text-center" data-priority="2">Nama</td>
								<td class="text-center" data-priority="3">Username</td>
								<td class="text-center">Sekolah</td>
								<td class="text-center">Peran</td>
							</tr>
						</thead>
					</table>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div id="daftarpencarian"></div>
	</div>
</div>
<script>
	//Dropdown Select
	$(function () {
		$(".select2").select2();
	});
	//Date Picker
	$("#tanggal_lahir").datepicker({ 
		format: 'yyyy-mm-dd'
	});

	function carisiswa(){
		var data = {nama:$("#nama").val(),sekolah_id:$("#sekolah_id").val(),peran_id:$("#peran_id").val(),username:$("#username").val()};
		$.ajax({
			type: "POST",
			url : "<?php echo site_url('admin/pengguna/cari')?>",
			data: data,
			success: function(msg){
				$('#daftarpencarian').html(msg);
			}
		});
	}
</script>

<script>

	var editor, editor_pwd, dt;
	var nama, sekolah_id, peran_id, username;

	$(document).ready(function() {
		$.extend( $.fn.dataTable.defaults, { responsive: true } );

		$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
		} );
	
		editor = new $.fn.dataTable.Editor( {
			ajax: "<?php echo site_url('admin/pengguna/json'); ?>",
			table: "#tpengguna",
			idSrc: "pengguna_id",
			fields: [ 
			{
				label: "Nama:",
				name: "nama",
				type: "text",
			}, {
				label: "Username:",
				name: "username",
				type: "text",
			}, {
				label: "Sekolah:",
				name: "sekolah_id",
				type: "tcg_select2",
			}, {
				label: "Peran:",
				name: "peran_id",
				type: "select",
			}
			],
			i18n: {
			create: {
				button: "Baru",
				title:  "Pengguna baru",
				submit: "Simpan"
			},
			edit: {
				button: "Ubah",
				title:  "Ubah profil pengguna",
				submit: "Simpan"
			},
			remove: {
				button: "Hapus",
				title:  "Hapus pengguna",
				submit: "Hapus",
				confirm: {
					_: "Konfirmasi hapus %d pengguna?",
					1: "Konfirmasi hapus 1 pengguna?"
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
	
		editor_pwd = new $.fn.dataTable.Editor( {
			ajax: "<?php echo site_url('admin/pengguna/json'); ?>",
			table: "#tpengguna",
			idSrc: "pengguna_id",
			fields: [ 
			{
				label: "Nama:",
				name: "nama",
				type: "readonly",
			}, {
				label: "Username:",
				name: "username",
				type: "readonly",
			}, {
				label: "PIN Baru:",
				name: "pwd1",
				type: "password",
			}, {
				label: "PIN Baru (Lagi):",
				name: "pwd2",
				type: "password",
			}
			],
			i18n: {
			create: {
				button: "Baru",
				title:  "Pengguna baru",
				submit: "Simpan"
			},
			edit: {
				button: "Reset PIN",
				title:  "Ubah PIN pengguna",
				submit: "Simpan"
			},
			remove: {
				button: "Hapus",
				title:  "Hapus pengguna",
				submit: "Hapus",
				confirm: {
					_: "Konfirmasi hapus %d pengguna?",
					1: "Konfirmasi hapus 1 pengguna?"
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

		// // Activate the bubble editor on click of a table cell
		// $('#tpengguna').on( 'click', 'tbody td.editable', function (e) {
		// 	editor.bubble( this );
		// } );

		// // Inline editing in responsive cell
		// $('#tpengguna').on( 'click', 'tbody ul.dtr-details li', function (e) {
		// 	// Ignore the Responsive control and checkbox columns
		// 	if ( $(this).hasClass( 'control' ) || $(this).hasClass('select-checkbox') ) {
		// 		return;
		// 	}
	
		// 	//ignore read-only column
		// 	var colnum = $(this).attr( 'data-dt-column' );
		// 	if ( colnum == 1 ) {
		// 		return;
		// 	}
		
		// 	// Edit the value, but this method allows clicking on label as well
		// 	editor.bubble( $('span.dtr-data', this) );
		// });

		// editor.on( 'preSubmit', function ( e, o, action ) {
		// 	if ( action !== 'remove' && action !== 'view' ) {
		// 		var uraian = this.field( 'uraian' );
	
		// 		// Only validate user input values - different values indicate that
		// 		// the end user has not entered a value
		// 		if ( ! uraian.isMultiValue() ) {
		// 			if ( ! uraian.val() ) {
		// 				uraian.error( 'Uraian transaksi harus diisi' );
		// 			}
		// 		}
	
		// 		var nilai = this.field( 'nilai' );
	
		// 		// Only validate user input values - different values indicate that
		// 		// the end user has not entered a value
		// 		if ( ! nilai.isMultiValue() ) {
		// 			if ( ! nilai.val() ) {
		// 				nilai.error( 'Nilai transaksi harus diisi' );
		// 			}
		// 			else if ( nilai.val() <= 1000 ) {
		// 				nilai.error( 'Nilai transaksi harus lebih dari 1000' );
		// 			}
		// 		}

		// 		// If any error was reported, cancel the submission so it can be corrected
		// 		if ( this.inError() ) {
		// 			return false;
		// 		}
		// 	}
		// } );

		dt = $('#tpengguna').DataTable({
			"responsive": true,
			"pageLength": 50,
			"lengthMenu": [ [50, 100, 200, -1], [50, 100, 200, "All"] ],
			"paging": true,
			"pagingType": "numbers",
			//dom: "Bfrtip",
			"dom": 'Bfrtpil',
			select: true,
			buttons: [
				{ extend: "create", editor: editor },
				{ extend: "edit",   editor: editor },
				{ extend: "remove", editor: editor },
				{ extend: "edit", editor: editor_pwd },
			// 	{
			// 		extend: 'csv',
			// 		text: 'Export CSV',
			// 		className: 'btn-space',
			// 		exportOptions: {
			// 			orthogonal: null
			// 		}
			// 	},
			// 	{
			// 		text: 'Import CSV',
			// 		action: function () {
			// 			uploadEditor.create( {
			// 				title: 'CSV file import'
			// 			} );
			// 		}
			// 	},
			],
			// "buttons": [
			// 	'copyHtml5',
			// 	'excelHtml5',
			// 	'pdfHtml5',
			// 	'print'
			// ],
			"language": {
				"processing":   "Sedang proses...",
				"lengthMenu":   "Tampilan _MENU_ entri",
				"zeroRecords":  "Tidak ditemukan data yang sesuai",
				"info":         "Tampilan _START_ - _END_ dari _TOTAL_ entri",
				"infoEmpty":    "Tampilan 0 hingga 0 dari 0 entri",
				"infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
				"infoPostFix":  "",
				"loadingRecords": "Loading...",
				"emptyTable":   "Tidak ditemukan data yang sesuai",
				"search":       "Cari:",
				"url":          "",
				"paginate": {
				"first":    "Awal",
				"previous": "Balik",
				"next":     "Lanjut",
				"last":     "Akhir"
				},
				aria: {
						sortAscending:  ": klik untuk mengurutkan dari bawah ke atas",
						sortDescending: ": klik untuk mengurutkan dari atas ke bawah"
					}
			},	
			"ajax": {
              "type" : "GET",
              "url" : "<?php echo site_url('admin/pengguna/json'); ?>",
              "dataSrc": function ( json ) {
                  //hide loader
                  $("#loading").hide();

                  //actual data source
                  return json.data;
                }       
              },
			columns: [
				{ data: "nama", className: 'dt-body-left editable' },
				{ data: "username", className: 'dt-body-left editable' },
				{ data: "sekolah", className: 'dt-body-left editable', editField: 'sekolah_id' },
				{ data: "peran", className: 'dt-body-center editable', editField: 'peran_id' },
			],
			order: [ 0, 'asc' ],
			"deferLoading": 0
		});

	});

	function reload() {
		nama_baru = $("#nama").val();
		sekolah_id_baru = $("#sekolah_id").val();
		peran_id_baru = $("#peran_id").val();
		username_baru = $("#username").val();

		// if (nama == nama_baru && sekolah_id == sekolah_id_baru && peran_id == peran_id_baru && username == username_baru) {
		// 	return;
		// }

		nama = nama_baru;
		sekolah_id = sekolah_id_baru;
		peran_id = peran_id_baru;
		username = username_baru;

		//show loader
		$("#loading").show();

		//reload
		dt.ajax.url("<?php echo site_url('admin/pengguna/json'); ?>?nama=" + nama + "&username=" + username + "&peran_id=" + peran_id + "&sekolah_id=" + sekolah_id );
		dt.ajax.reload();
  }


</script>