
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box box-solid">
			<!-- <div class="box-header with-border">
				<i class="glyphicon glyphicon-search"></i>
				<h3 class="box-title"><b>Pencarian Siswa</b></h3>
			</div> -->
			<div class="box-body">
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
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
							<label for="sekolah_id">Asal Sekolah</label>
							<select id="sekolah_id" name="sekolah_id" class="form-control select2" style="width:100%;">
								<option value="">--</option>
								<?php foreach($daftarsekolah->getResult() as $row):?>									
								<option value="<?php echo $row->sekolah_id;?>">(<?php echo $row->npsn;?>) <?php echo $row->nama;?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<a id='btn_search' href="javascript:void(0)" onclick="cari_peserta_didik(); return false;" class="btn btn-primary btn-flat">Cari Peserta Didik</a>
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
					<table class="display" id="tsearch" style="width:100%;">
						<thead>
							<tr>
								<!-- <td class="text-center" data-priority="1">#</td> -->
								<td class="text-center" data-priority="1">Nama</td>
								<td class="text-center">Jenis Kelamin</td>
								<td class="text-center" data-priority="3">NISN</td>
								<td class="text-center">NIK</td>
								<td class="text-center" data-priority="4">Tanggal Lahir</td>
								<td class="text-center">Asal Sekolah</td>
								<td class="text-center">Diterima Di</td>
								<td class="text-center" data-priority="2"></td>
							</tr>
						</thead>
					</table>
			</div>
		</div>
	</div>
</div>



<script>

	var editor, editor_pwd, dt_search;
	//var nama, sekolah_id, peran_id, username;
	var v_nama='', v_nisn='', v_nik='', v_sekolah_id='';

    $("#nama").keyup(function (e) {
        if (e.which == 13) {
            cari_peserta_didik();
        }
    });

    $("#nisn").keyup(function (e) {
        if (e.which == 13) {
            cari_peserta_didik();
        }
    });

    // $("#sekolah_id").on('change', function (e) {
    //     cari_peserta_didik();
    // });

	$(document).ready(function() {
		$.extend( $.fn.dataTable.defaults, { responsive: true } );

		$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
		$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
		} );

		dt_search = $('#tsearch').DataTable({
			"responsive": true,
			"pageLength": 5,
			"lengthMenu": [ [5, 10, 20, -1], [5, 10, 20, "All"] ],
			"paging": true,
			"pagingType": "numbers",
			"dom": 'frtpil',
			select: true,
			// buttons: [
			// 	{
			// 		extend: 'selectedSingle',
			// 		text: 'Ubah Data',
			// 		action: function ( e, dt, button, config ) {
			// 			var data = dt.row( { selected: true } ).data();
			// 			window.location = "<?php echo base_url();?>index.php/dapodik/ubahdata?peserta_didik_id=" +data['peserta_didik_id']+ "&redirect=admin/pesertadidik" ;
			// 		}        
			// 	},
			// 	{ extend: "edit", editor: editor_pwd }
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
			// "ajax": {
            //   "type" : "GET",
            //   "url" : "<?php echo site_url('dapodik/penerimaan/search'); ?>",
            //   "dataSrc": function ( json ) {
            //       //hide loader
            //       $("#loading").hide();

            //       //actual data source
            //       return json.data;
            //     }       
            //   },
			columns: [
				{ data: "nama", className: 'dt-body-left'
				},
				{ data: "jenis_kelamin", className: 'dt-body-center text-nowrap' },
				{ data: "nisn", className: 'dt-body-center'
				},
				{ data: "nik", className: 'dt-body-center'
				},
				{ data: "tanggal_lahir", className: 'dt-body-center text-nowrap' },
				{ data: "sekolah", className: 'dt-body-left' },
				{ data: "diterima_sekolah", className: 'dt-body-left' },
				{
					data: null,
					className: 'text-right inline-flex text-nowrap inline-actions',
					"orderable": false,
					render: function(data, type, row, meta) {
						if(type != 'display') {
							return "";
						}

						if (row['diterima_sekolah'] !== null && row['diterima_sekolah'] != '') {
							return "";
						}

						return "<button href='#' onclick='event.stopPropagation(); tambah_penerimaan(" +meta.row+ ", dt_search, \"" +row['peserta_didik_id']+ "\");' data-tag='" +meta.row+ "' class='btn btn-sm btn-primary'>Tambahkan</button>";
					}
				}
			],
			order: [ 0, 'asc' ],
			"deferLoading": 0
		});

	});

	function cari_peserta_didik() {
		nama_baru = $("#nama").val();
		sekolah_id_baru = $("#sekolah_id").val();
		nisn_baru = $("#nisn").val();
		//nik_baru = $("#nik").val();
		nik_baru = '';

		if ('' == nama_baru && '' == nik_baru && '' == nisn_baru && '' == sekolah_id_baru) {
			return;
		}

		// v_nama = nama_baru;
		// v_sekolah_id = sekolah_id_baru;
		// v_nisn = nisn_baru;
		// v_nik = nik_baru;

		//show loader
		$("#loading").show();

		//reload
		dt_search.ajax.url("<?php echo site_url('dapodik/penerimaan/search'); ?>?nama=" + nama_baru + "&nisn=" + nisn_baru + "&nik=" + nik_baru + "&sekolah_id=" + sekolah_id_baru );
		dt_search.ajax.reload(function(json) {
			//hide loader
			$("#loading").hide();
		}, false);
  	}

	function tambah_penerimaan(row_id, dt, key) {
		// add assoc key values, this will be posts values
		var formData = new FormData();
		formData.append("peserta_didik_id", key);
		formData.append("action", "accept");

		$.ajax({
			type: "POST",
			url: "<?php echo site_url('dapodik/penerimaan/json'); ?>",
			async: true,
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			timeout: 60000,
			dataType: 'json',
			success: function(json) {
				if (typeof json.error !== 'undefined' && json.error != "" && json.error != null) {
					alert(json.error);
					return;
				}

				dt_search.ajax.reload();
				dt_siswa.ajax.reload();

				// $("#loading2").show();

				// dt.ajax.url("<?php echo site_url('dapodik/penerimaan/json'); ?>");
				// dt.ajax.reload(function(json) {
				// 	//hide loader
				// 	$("#loading2").hide();
				// }, true);

			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Gagal menambahkan data');

				return;
			}
		});

	}

</script>