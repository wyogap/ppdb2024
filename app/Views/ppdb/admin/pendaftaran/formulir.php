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
							<label for="nisn">NISN</label>
							<input type="text" class="form-control" id="nisn" name="nisn" placeholder="NISN" minlength="3" maxlength="64">
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
						<label for="nik">NIK</label>
							<input type="text" class="form-control" id="nik" name="nik" placeholder="NIK" minlength="3" maxlength="64">
						</div>
					</div>
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
					<?php if (1==0) { ?>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="jenis_kelamin">Jenis Kelamin</label>
							<select id="jenis_kelamin" name="jenis_kelamin" class="form-control select2" style="width:100%;">
								<option value="">--</option>
								<option value="L">Laki-laki</option>
								<option value="P">Perempuan</option>
							</select>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="kode_kecamatan">Kecamatan</label>
							<select id="kode_kecamatan" name="kode_kecamatan" class="form-control select2" style="width:100%;">
								<option value="">--</option>
								<?php foreach($daftarkecamatan->getResult() as $row):?>									
								<option value="<?php echo $row->kode_wilayah;?>"><?php echo $row->nama;?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
						<label for="kode_desa">Desa/Kelurahan</label>
							<select id="kode_desa" name="kode_desa" class="form-control select2" style="width:100%;">
								<option value="">--</option>
							</select>
						</div>
					</div>
					<?php } ?>

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
							<label for="penerapan_id">Jalur Penerimaan</label>
							<select id="penerapan_id" name="penerapan_id" class="form-control select2" style="width:100%;">
								<option value="">--</option>
								<?php foreach($daftarpenerapan->getResult() as $row):?>									
								<option value="<?php echo $row->penerapan_id;?>"><?php echo $row->nama;?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="soft_delete">Dihapus?</label>
							<select id="soft_delete" name="soft_delete" class="form-control select2" style="width:100%;">
								<option value="0">Aktif</option>
								<option value="1">Dihapus</option>
							</select>
						</div>
					</div>
					<!-- <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="tempat_lahir">Tempat Lahir</label>
							<input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" minlength="3" maxlength="32">
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="tanggal_lahir">Tanggal Lahir</label>
							<input id="tanggal_lahir" name="tanggal_lahir" type="text" class="form-control" aria-describedby="basic-addon1">
						</div>
					</div> -->
				</div>
			</div>
			<div class="box-footer">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<a href="javascript:void(0)" onclick="reload(); return false;" class="btn btn-primary btn-flat">Cari Peserta Didik</a>
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
								<td class="text-center" data-priority="2">NISN</td>
								<td class="text-center">Tahun Ajaran</td>
								<td class="text-center">Sekolah Tujuan</td>
								<td class="text-center"><i class="glyphicon glyphicon-edit"></i> Jalur</td>
								<td class="text-center">Skor</td>
								<td class="text-center"><i class="glyphicon glyphicon-edit"></i> Pilihan</td>
								<td class="text-center"><i class="glyphicon glyphicon-edit"></i> Masuk Pilihan</td>
								<td class="text-center" data-priority="10001">Peringkat</td>
								<td class="text-center">Status</td>
								<td class="text-center"><i class="glyphicon glyphicon-edit"></i> Peringkat Final</td>
								<td class="text-center"><i class="glyphicon glyphicon-edit"></i> Status Final</td>
								<td class="none">Asal Sekolah</td>
								<td class="none">No. Pendaftaran</td>
								<td class="none"><i class="glyphicon glyphicon-edit"></i> Kelengkapan Berkas</td>
								<td class="none"><i class="glyphicon glyphicon-edit"></i> Dihapus?</td>
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
	//Date Picker
	$("#tanggal_lahir").datepicker({ 
		format: 'yyyy-mm-dd'
	});

	function carisiswa(){
		var data = {nama:$("#nama").val(),jenis_kelamin:$("#jenis_kelamin").val(),nisn:$("#nisn").val(),nik:$("#nik").val()};
		$.ajax({
			type: "POST",
			url : "<?php echo site_url('Cadmin/caripesertadidik')?>",
			data: data,
			success: function(msg){
				$('#daftarpencarian').html(msg);
			}
		});
	}

	//Event On Change Dropdown
	$(document).ready(function () {
		$('select[name="kode_kecamatan"]').on('change', function() {
			var data = {kode_wilayah:$(kode_kecamatan).val()};
			$.ajax({
				type: "POST",
				url : "<?php echo site_url('Cdropdown/desa')?>",
				data: data,
				success: function(msg){
					$('#kode_desa').html(msg);
				}
			});
		});
	});

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
			ajax: "<?php echo site_url('admin/pendaftaran/json'); ?>",
			table: "#tpengguna",
			idSrc: "pendaftaran_id",
			fields: [ 
			{
				label: "Plihan:",
				name: "jenis_pilihan",
				type: "select",
                options: [
                    { label: "Pilihan 1", value: "1" },
                    { label: "Pilihan 2", value: "2" },
                    { label: "Pilihan 3", value: "3" },
                    { label: "Pilihan 4", value: "4" },
                    { label: "Pilihan 5", value: "5" },
					{ label: "Belum Diperingkat", value: "0" },
               ]
			}, {
				label: "Jalur Pendaftaran:",
				name: "penerapan_id",
				type: "select",
                options: [
                    { label: "Zonasi", value: "101" },
                    { label: "Prestasi", value: "102" },
                    { label: "Afirmasi", value: "104" },
                    { label: "Perpindahan Orang Tua", value: "103" },
				]
			}, {
				label: "Masuk Pilihan:",
				name: "masuk_jenis_pilihan",
				type: "select",
                options: [
                    { label: "Pilihan 1", value: "1" },
                    { label: "Pilihan 2", value: "2" },
                    { label: "Pilihan 3", value: "3" },
                    { label: "Pilihan 4", value: "4" },
                    { label: "Pilihan 5", value: "5" },
					{ label: "Belum Diperingkat", value: "0" },
               ]
			}, {
				label: "Peringkat Final:",
				name: "peringkat_final",
				type: "text",
				attr: { type: "number" }
			}, {
				label: "Status Penerimaan Final:",
				name: "status_penerimaan_final",
				type: "select",
                options: [
                    { label: "Diterima", value: "1" },
                    { label: "Tidak Diterima", value: "2" },
                    { label: "Daftar Tunggu", value: "3" },
                    { label: "Pilihan 1/2", value: "4" },
					{ label: "Belum Diperingkat", value: "0" },
               ]
			}, {
				label: "Kelengkapan Berkas:",
				name: "kelengkapan_berkas",
				type: "select",
                options: [
                    { label: "Sudah Lengkap", value: "1" },
                    { label: "Belum Lengkap", value: "2" },
                    { label: "Belum Diverifikasi", value: "0" },
               ]
			}, {
				label: "Dihapus?:",
				name: "soft_delete",
				type: "select",
                options: [
                    { label: "Aktif", value: "0" },
                    { label: "Dihapus", value: "1" },
               ]
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
	
			editable_columns = [6, 7, 10, 11 , 14, 15];

			//ignore read-only column
			var colnum = parseInt($(this).attr( 'data-dt-column' ));
			if ( !editable_columns.includes(colnum) ) {
				return;
			}
		
			// Edit the value, but this method allows clicking on label as well
			editor.bubble( $('span.dtr-data', this) );
		});

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
				// { extend: "create", editor: editor },
				{ extend: "edit",   editor: editor },
				{ extend: "remove", editor: editor },
			],
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
              "url" : "<?php echo site_url('admin/pesertadidik/json'); ?>",
              "dataSrc": function ( json ) {
                  //hide loader
                  $("#loading").hide();

                  //actual data source
                  return json.data;
                }       
              },
			columns: [
				{ data: "nama", className: 'dt-body-left',
					"render": function ( data, type, row ) {
						if (type == 'display') {
                    		return "<a href='<?php echo base_url();?>index.php/Chome/detailpendaftaran?peserta_didik_id=" + row.peserta_didik_id + "' target='_blank'>" + data + "</a>";
						}
						else {
							return data;
						}
                	},
				},
				{ data: "nisn", className: 'dt-body-center'
				},
				{ data: "tahun_ajaran_id", className: 'dt-body-center' },
				{ data: "sekolah_tujuan", className: 'dt-body-center' },
				{ data: "penerapan", className: 'dt-body-center editable', editField: "penerapan_id" },
				{ data: "skor", className: 'dt-body-center' },
				{ data: "jenis_pilihan", className: 'dt-body-center editable' },
				{ data: "masuk_jenis_pilihan", className: 'dt-body-center editable' },
				{ data: "peringkat", className: 'dt-body-center' },
				{ data: "status_penerimaan", className: 'dt-body-center' },
				{ data: "peringkat_final", className: 'dt-body-center editable' },
				{ data: "status_penerimaan_final", className: 'dt-body-center editable',
					"render": function ( data, type, row ) {
						if (data == 0) {
							return "Dalam Proses";
						}
						else if (data == 1) {
							return "Diterima";
						}
						else if (data == 2) {
							return "Tdk Diterima";
						}
						else if (data == 3) {
							return "Daftar Tunggu";
						}
						else if (data == 4) {
							return "Pilihan " + row.masuk_jenis_pilihan;
						}
						else {
							return "Belum Lengkap";
						}
                 	},
				},
				{ data: "sekolah_asal", className: 'dt-body-center' },
				{ data: "nomor_pendaftaran", className: 'dt-body-center' },
				{ data: "kelengkapan_berkas", className: 'dt-body-center editable',
					"render": function ( data, type, row ) {
						if (data == 0) {
							return "Belum Verifikasi";
						}
						else if (data == 1) {
							return "Sudah Lengkap";
						}
						else {
							return "Belum Lengkap";
						}
                 	},
				},
				{ data: "soft_delete", className: 'dt-body-center editable',
					"render": function ( data, type, row ) {
						if (data == 1) {
							return "Dihapus";
						}
						else if (data == 0) {
							return "Aktif";
						}
						else {
							return "Tidak valid";
						}
                 	},
				},
			],
			order: [ 0, 'asc' ],
			"deferLoading": 0
		});

	});

	function reload() {
		nama_baru = $("#nama").val();
		sekolah_id_baru = $("#sekolah_id").val();
		nisn_baru = $("#nisn").val();
		nik_baru = $("#nik").val();
		sekolah_tujuan_id_baru = $("#sekolah_tujuan_id").val();
		penerapan_baru = $("#penerapan_id").val();
		soft_delete_baru = $("#soft_delete").val();

		// if (nama == nama_baru && sekolah_id == sekolah_id_baru && peran_id == peran_id_baru && username == username_baru) {
		// 	return;
		// }

		nama = nama_baru;
		sekolah_id = sekolah_id_baru;
		nisn = nisn_baru;
		nik = nik_baru;
		sekolah_tujuan_id = sekolah_tujuan_id_baru;
		penerapan = penerapan_baru;
		soft_delete = soft_delete_baru;

		//show loader
		$("#loading").show();

		// $("#loadMe").modal({
		// 	backdrop: "static", //remove ability to close modal with click
		// 	keyboard: false, //remove option to close with keyboard
		// 	show: true //Display loader!
		// });

		//reload
		dt.ajax.url("<?php echo site_url('admin/pendaftaran/json'); ?>?nama=" + nama + "&nisn=" + nisn + "&nik=" + nik + "&sekolah_id=" + sekolah_id + "&sekolah_tujuan_id=" + sekolah_tujuan_id+"&penerapan_id=" +penerapan+ "&soft_delete=" + soft_delete);
		dt.ajax.reload();
  }


</script>