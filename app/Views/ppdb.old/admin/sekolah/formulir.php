<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url();?>assets/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>

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

<style>
</style>

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
							<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" minlength="3" maxlength="100">
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="npsn">NPSN</label>
							<input type="text" class="form-control" id="npsn" name="npsn" placeholder="NPSN" minlength="3" maxlength="64">
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="bentuk_pendidikan">Jenjang</label>
							<select id="bentuk_pendidikan" name="bentuk_pendidikan" class="form-control select2" style="width:100%;">
								<option value="">--</option>
								<option value="MI">MI</option>
								<option value="SD">SD</option>
								<option value="SMP">SMP</option>
							</select>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="status">Status</label>
							<select id="status" name="status" class="form-control select2" style="width:100%;">
								<option value="">--</option>
								<option value="N">Negeri</option>
								<option value="S">Swasta</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<a href="javascript:void(0)" onclick="reload(); return false;" class="btn btn-primary btn-flat">Cari Sekolah</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
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
					<table class="display" id="tpengguna" style="width:100%;">
						<thead>
							<tr>
								<!-- <td class="text-center" data-priority="1">#</td> -->
								<td class="text-center" data-priority="1">Nama</td>
								<td class="text-center" data-priority="2">NPSN</td>
								<td class="text-center">Jenjang</td>
								<td class="text-center">Status</td>
								<td class="text-center">Inklusi</td>
								<td class="text-center" data-priority="3">Lintang</td>
								<td class="text-center" data-priority="4">Bujur</td>
								<td class="none">Alamat</td>
							</tr>
						</thead>
					</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<!-- <div class="modal fade" id="loadMe" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <div class="loader"></div>
      </div>
    </div>
  </div>
</div> -->

<script>
	//Dropdown Select
	$(function () {
		$(".select2").select2();
	});

</script>

<script>

	var editor, dt;
	var nama, sekolah_id, peran_id, username;

	$(document).ready(function() {
		$.extend( $.fn.dataTable.defaults, { responsive: true } );

		$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
		} );
	
		editor = new $.fn.dataTable.Editor( {
			ajax: "<?php echo site_url('admin/sekolah/json'); ?>",
			table: "#tpengguna",
			idSrc: "sekolah_id",
			fields: [ 
			{
				label: "Nama:",
				name: "nama",
				type: "text",
			}, {
				label: "NPSN:",
				name: "npsn",
				type: "text",
			}, {
				label: "Lintang:",
				name: "lintang",
				type: "text",
				attr: { type: "number" }
			}, {
				label: "Bujur:",
				name: "bujur",
				type: "text",
				attr: { type: "number" }
			}, {
				label: "Inklusi:",
				name: "inklusi",
				type: "select",
                options: [
                    { label: "Ya", value: "1" },
                    { label: "Tidak", value: "0" },
               ]
			}
			],
			i18n: {
			create: {
				button: "Baru",
				title:  "Sekolah baru",
				submit: "Simpan"
			},
			edit: {
				button: "Ubah",
				title:  "Ubah profil sekolah",
				submit: "Simpan"
			},
			remove: {
				button: "Hapus",
				title:  "Hapus sekolah",
				submit: "Hapus",
				confirm: {
					_: "Konfirmasi hapus %d sekolah?",
					1: "Konfirmasi hapus 1 sekolah?"
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
		$('#tpengguna').on( 'click', 'tbody td.editable', function (e) {
			editor.bubble( this );
		} );

		// Inline editing in responsive cell
		$('#tpengguna').on( 'click', 'tbody ul.dtr-details li', function (e) {
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
		});

		dt = $('#tpengguna').DataTable({
			"responsive": true,
			"pageLength": 25,
			"lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
			"paging": true,
			"pagingType": "numbers",
			//dom: "Bfrtip",
			"dom": 'Bfrtpil',
			select: true,
			buttons: [
				// { extend: "create", editor: editor },
				{ extend: "edit",   editor: editor },
				// { extend: "remove", editor: editor },
			],
			// "buttons": [
			// 	'copyHtml5',
			// 	'excelHtml5',
			// 	'pdfHtml5',
			// 	'print'
			// ],
			"language": {
				"processing":   "Sedang proses...",
				"lengthMenu":   "Tampilan _MENU_ baris",
				"zeroRecords":  "Tidak ditemukan data yang sesuai",
				"info":         "Tampilan _START_ - _END_ dari _TOTAL_ baris",
				"infoEmpty":    "Tampilan 0 hingga 0 dari 0 baris",
				"infoFiltered": "(disaring dari _MAX_ baris keseluruhan)",
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
              "url" : "<?php echo site_url('admin/sekolah/json'); ?>",
              "dataSrc": function ( json ) {
                  //hide loader
                  $("#loading").hide();

                  //actual data source
                  return json.data;
                }       
              },
			columns: [
				{ data: "nama", className: 'dt-body-left', width: '25%',
					"render": function ( data, type, row ) {
						if (type == 'display') {
                    		return "<a href='<?php echo base_url();?>index.php/Chome/peringkat?sekolah_id=" + row.sekolah_id + "' target='_blank'>" + data + "</a>";
						}
						else {
							return data;
						}
                	},
				},
				{ data: "npsn", className: 'dt-body-center' },
				{ data: "bentuk_pendidikan", className: 'dt-body-center' },
				{ data: "status", className: 'dt-body-center',
					"render": function ( data, type, row ) {
                    	return data == 'N' ? 'Negeri' : 'Swasta';
                	},
				},
				{ data: "inklusi", className: 'dt-body-center editable',
					"render": function ( data, type, row ) {
                    	return data == 0 ? 'Tidak' : 'Ya';
                	},
				},
				{ data: "lintang", className: 'dt-body-center editable',
					editField: ['lintang', 'bujur']
				},
				{ data: "bujur", className: 'dt-body-center editable', 
					editField: ['lintang', 'bujur']
				},
				{ data: "alamat", className: 'dt-body-left', 
					"render": function ( data, type, row ) {
                    	return row.desa_kelurahan + ", " + row.kecamatan + ", " + row.kabupaten;
                	},
				},
			],
			order: [ 0, 'asc' ],
			"deferLoading": 0
		});

	});

	function reload() {
		nama_baru = $("#nama").val();
		npsn_baru = $("#npsn").val();
		bentuk_baru = $("#bentuk_pendidikan").val();
		status_baru = $("#status").val();

		// if (nama == nama_baru && sekolah_id == sekolah_id_baru && peran_id == peran_id_baru && username == username_baru) {
		// 	return;
		// }

		nama = nama_baru;
		npsn = npsn_baru;
		bentuk = bentuk_baru;
		status = status_baru;

		//show loader
		$("#loading").show();

		// $("#loadMe").modal({
		// 	backdrop: "static", //remove ability to close modal with click
		// 	keyboard: false, //remove option to close with keyboard
		// 	show: true //Display loader!
		// });

		//reload
		dt.ajax.url("<?php echo site_url('admin/sekolah/json'); ?>?nama=" + nama + "&npsn=" + npsn + "&bentuk_pendidikan=" + bentuk + "&status=" + status );
		dt.ajax.reload();
  }


</script>