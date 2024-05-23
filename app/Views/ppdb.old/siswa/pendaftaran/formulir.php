  <link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Editor-1.9.2/css/editor.dataTables.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">

  <?php
	global $kelengkapan_data;
    global $bisa_perubahan;
    global $maxcabutberkas, $maxhapuspendaftaran, $maxubahjenispilihan, $maxubahsekolah, $maxubahjalur, $cabutberkassiswa, $hapuspendaftaransiswa, $ubahjenispilihansiswa, $ubahsekolahsiswa, $ubahjalursiswa;

	function tanggal_indo($tanggal)
	{
		$bulan = array (1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$split = explode('-', $tanggal);
		return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
	}

	// setlocale(LC_ALL,'id_ID');
	// setlocale(LC_ALL,'INDONESIA');
	setlocale(LC_ALL,'IND');

	date_default_timezone_set('Asia/Jakarta');

	foreach($waktupendaftaran->getResult() as $row):
		$tanggal_mulai_aktif = $row->tanggal_mulai_aktif;
		$tanggal_selesai_aktif = $row->tanggal_selesai_aktif;
	endforeach;

	$bentuk = $this->session->userdata("bentuk");

	$profil_konfirmasi = 0;
	$lokasi_konfirmasi = 0;
	$nilai_konfirmasi = 0;
	$prestasi_konfirmasi = 0;
	$afirmasi_konfirmasi = 0;
	$inklusi_konfirmasi = 0;
	$pernyataan_file = "";
	$nomor_handphone = "";
	$punya_kip = 0;
	$masuk_bdt = 0;

	foreach($statusprofil->getResult() as $row) {
		$profil_konfirmasi = $row->konfirmasi_profil;
		$lokasi_konfirmasi = $row->konfirmasi_lokasi;
		$nilai_konfirmasi = $row->konfirmasi_nilai;
		$prestasi_konfirmasi = $row->konfirmasi_prestasi;
		$afirmasi_konfirmasi = $row->konfirmasi_afirmasi;
		$inklusi_konfirmasi = $row->konfirmasi_inklusi;

		//if verification is performed, use verification value.
		//this is mostly for old data which are imported from ppdb2019 where the flow is different
		if ($row->verifikasi_profil != 0) $profil_konfirmasi = $row->verifikasi_profil;
		if ($row->verifikasi_lokasi != 0) $lokasi_konfirmasi = $row->verifikasi_lokasi;
		if ($row->verifikasi_nilai != 0) $nilai_konfirmasi = $row->verifikasi_nilai;
		if ($row->verifikasi_prestasi != 0) $prestasi_konfirmasi = $row->verifikasi_prestasi;
		if ($row->verifikasi_afirmasi != 0) $afirmasi_konfirmasi = $row->verifikasi_afirmasi;
		if ($row->verifikasi_inklusi != 0) $inklusi_konfirmasi = $row->verifikasi_inklusi;

		$nomor_handphone = $row->nomor_kontak;
		$pernyataan_file = $row->path_surat_pernyataan;

		$verifikasi_oleh = $row->nama_verifikator;
		$verifikasi_tanggal = $row->tanggal_verifikasi;

		$punya_kip = $row->punya_kip;
		$masuk_bdt = $row->masuk_bdt;
	}

	// $tahapan_nama = "";
	// $tahapan_id = 0;
	// $notifikasi_siswa = "";
	// foreach($tahapan->getResult() as $row) {
	// 	$tahapan_nama = $row->tahapan;
	// 	$tahapan_id = $row->tahapan_id;
	// 	$notifikasi_siswa = $row->notifikasi_siswa;
	// }

	$kelengkapan_data = 1;
	if ($profil_konfirmasi != 1 || $lokasi_konfirmasi != 1 || $nilai_konfirmasi != 1 || $prestasi_konfirmasi != 1 || $afirmasi_konfirmasi != 1 
			|| $inklusi_konfirmasi != 1 || empty($nomor_handphone) || empty($pernyataan_file)) {
		$kelengkapan_data = 0;
	}

	$jalurid_dalam_zonasi = 0;
	$namajalur_dalam_zonasi = "";

	if ($satu_zonasi_satu_jalur == 1) {
		foreach($pendaftaran_dalam_zonasi->getResult() as $row) {
			$jalurid_dalam_zonasi = $row->jalur_id;
			$namajalur_dalam_zonasi = $row->jalur;
		}
	}

	//munculkan menu perubahan
	$bisa_perubahan = 1;

	$maxcabutberkas=0;
	$maxhapuspendaftaran=0;
	$maxubahjenispilihan=0;
	$maxubahsekolah=0;
	$maxubahjalur=0;
	$cabutberkassiswa=0;
	$hapuspendaftaransiswa=0;
	$ubahjenispilihansiswa=0;
	$ubahsekolahsiswa=0;
	$ubahjalursiswa=0;

	$this->load->model('Msiswa');

	foreach($batasanperubahan->getResult() as $row):
		$maxcabutberkas = $row->cabut_berkas;
		$maxhapuspendaftaran = $row->hapus_pendaftaran;
		$maxubahjenispilihan = $row->ubah_pilihan;
		$maxubahsekolah = $row->ubah_sekolah;
		$maxubahjalur = $row->ubah_jalur;
	endforeach;
	foreach($batasansiswa->getResult() as $row):
		$cabutberkassiswa = $row->cabut_berkas;
		$hapuspendaftaransiswa = $row->hapus_pendaftaran;
		$ubahjenispilihansiswa = $row->ubah_pilihan;
		$ubahsekolahsiswa = $row->ubah_sekolah;
		$ubahjalursiswa = $row->ubah_jalur;
	endforeach;

?>
<!-- <div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_1" data-toggle="tab">Detail Pendaftaran</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_1"> -->
			<span><?php if(isset($info)){echo $info;}?></span>

			<?php
				$error = $this->session->flashdata('error');
				if($error)
				{
			?>
				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<?php echo $error; ?>                    
				</div>
			<?php 
				}

				$success = $this->session->flashdata('success');
				if($success)
				{
			?>
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<?php echo $success; ?>                    
				</div>
			<?php } ?>

			<?php if ($kelengkapan_data == 0) { ?>
			<div class="alert alert-error alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<p><i class="icon glyphicon glyphicon-exclamation-sign"></i>Data profil belum lengkap</p>
				<p>Silahkan lengkapi data profil berikut sebelum anda bisa melakukan pendaftaran: </p>
				<ol>
					<?php if ($profil_konfirmasi != 1) { echo "<li><b>Profil Siswa</b></li>"; } ?>
					<?php if ($lokasi_konfirmasi != 1) { echo "<li><b>Lokasi Tempat Tinggal</b></li>"; } ?>
					<?php if ($nilai_konfirmasi != 1) { echo "<li><b>Nilai Ujian Nasional / Nilai Kelulusan</b></li>"; } ?>
					<?php if ($prestasi_konfirmasi != 1) { echo "<li><b>Prestasi</b></li>"; } ?>
					<?php if ($afirmasi_konfirmasi != 1) { echo "<li><b>Data Afirmasi</b></li>"; } ?>
					<?php if ($inklusi_konfirmasi != 1) { echo "<li><b>Data Inklusi / Kebutuhan Khusus</b></li>"; } ?>
					<?php if (empty($nomor_handphone)) { echo "<li><b>Nomor Handphone Aktif</b></li>"; } ?>
					<?php if (empty($pernyataan_file)) { echo "<li><b>Surat Pernyataan Kebenaran Dokumen</b></li>"; } ?>
				</ol>
			</div>
			<?php } ?>

			<?php foreach($tahapan->getResult() as $row): 
				if(!empty($row->notifikasi_siswa)) {
			?>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p><i class="icon glyphicon glyphicon-info-sign"></i>Tahapan <?php echo $row->tahapan; ?></p>
							<p><?php echo $row->notifikasi_siswa; ?></p>
						</div>
					</div>
				</div>
			<?php 
				}
			endforeach; ?>

			<?php if ($cek_waktupendaftaran == 0) { ?>
			<div class="alert alert-info alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<p><i class="icon glyphicon glyphicon-info-sign"></i>Pendaftaran belum dibuka</p>
				<p>Periode pendaftaran adalah dari tanggal <b><?php echo strftime("%d %B %Y %H:%M", strtotime($tanggal_mulai_aktif)); ?></b> sampai dengan tanggal <b><?php echo strftime("%d %B %Y %H:%M", strtotime($tanggal_selesai_aktif)); ?></b>.</p>
			</div>
			<?php } ?>

			<?php if (1 == 1) { ?>
			<div class="alert alert-info alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<i class="icon glyphicon glyphicon-info-sign"></i>Batasan <b>"Usia Pendaftaran"</b> adalah sebagai berikut :
				<ul>
				<?php foreach($batasanusia->getResult() as $row):?>
					<li>Pendaftaran Jenjang <b><?php echo $row->bentuk_tujuan_sekolah;?></b>, tanggal lahir dari <b><?php echo tanggal_indo($row->maksimal_tanggal_lahir);?></b> sampai dengan <b><?php echo tanggal_indo($row->minimal_tanggal_lahir);?></b></li>
				<?php endforeach;?>
				</ul>
			</div>
			<?php } ?>

			<div class="alert alert-info alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<p><i class="icon glyphicon glyphicon-info-sign"></i>Anda memiliki <b><?php echo ($maxpilihannegeri-$jumlahpendaftarannegeri); ?> slot</b> pendaftaran sekolah negeri dan <b><?php echo ($maxpilihanswasta-$jumlahpendaftaranswasta); ?> slot</b> pendaftaran sekolah swasta.</p>
			</div>

			<?php if ($cek_waktusosialisasi == 1) { ?>
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<p><i class="icon glyphicon glyphicon-info-sign"></i>Periode Sosialisasi</p>
					<p>Setelah periode sosialisasi, semua data pendaftaran akan dihapus.</p>             
				</div>
			<?php } ?>

			<?php if ($satu_zonasi_satu_jalur == 1) { ?>
				<?php if ($jalurid_dalam_zonasi==0) { ?>
					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<p><i class="icon glyphicon glyphicon-exclamation-sign"></i>Anda hanya bisa mendaftar menggunakan satu jalur pada satu zonasi. Mohon berhati-hati dalam menentukan jalur pendaftaran.</p>             
					</div>
				<?php } else if ($jalurid_dalam_zonasi!=1) { ?>
					<div class="alert alert-warning alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<p><i class="icon glyphicon glyphicon-info-sign"></i>Anda sudah mendaftar menggunakan jalur <?php echo $namajalur_dalam_zonasi; ?> di dalam zonasi anda. Anda tidak bisa mendaftar menggunakan jalur Zonasi.</p>             
					</div>
				<?php } else { ?>
					<div class="alert alert-warning alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<p><i class="icon glyphicon glyphicon-info-sign"></i>Anda sudah mendaftar menggunakan jalur Zonasi di dalam zonasi anda. Anda tidak bisa mendaftar menggunakan jalur selain jalur Zonasi.</p>             
					</div>
				<?php } ?>
			<?php } ?>

			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-th-list"></i>
							<h3 class="box-title text-info"><b>Rekapitulasi Sekolah Dalam Zonasi</b></h3>
						</div>
						<div class="box-body">
							<!-- <div class="row"> -->
								<table id="trekapitulasi" class="display" style="width: 100%;">
								<thead>
									<tr>
										<th class="text-center">Sekolah</th>
										<th class="text-center">Jarak</th>
										<th class="text-center">Total Kuota</th>
										<th class="text-center">Total Pendaftar</th>
										<th class="text-center">Sisa Kuota Zonasi</th>
										<th class="text-center">Sisa Kuota Prestasi</th>
										<th class="text-center">Sisa Kuota Afirmasi</th>
										<th class="text-center">Sisa Kuota Perpindahan Ortu</th>
										<th class="none">Status</th>
									</tr>
								</thead>
								</table>
							<!-- </div> -->
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-registration-mark"></i>
							<h3 class="box-title text-info"><b>Pendaftaran Jalur PPDB</b></h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<p>Silahkan pilih <b>Jalur PPDB</b> yang dikehendaki dibawah (<i class="glyphicon glyphicon-arrow-down"></i>) ini</b>.</p>
								</div>
								<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<?php
									//echo $cek_waktusosialisasi;
									$cnt = 0; $harus_tampil = 0;
									$tutup_akses = 0;
									foreach($daftarpenerapan->getResult() as $row):
										// if ($cek_waktupendaftaransusulan != $row->kategori_susulan) {
										// 	continue;
										// }
										$harus_tampil=0;
										if ($kebutuhan_khusus==1 && $row->kategori_inklusi==1) {
											$harus_tampil = 1;
										}

										if ($afirmasi==1 && $row->jalur_id==9) {
											$harus_tampil = 1;
										}

										if ($harus_tampil == 0 && $row->jalur_id==9 && $punya_kip==0 && $masuk_bdt==0) {
											continue;
										}

										//cek kondisi tutup akses
										if ($cek_waktupendaftaran==0 && $cek_waktusosialisasi==0) {
											$tutup_akses = 1;
										}

										if ($kelengkapan_data==0) {
											$tutup_akses = 1;
										}

										if (($satu_zonasi_satu_jalur==1 && $row->jalur_id==1 && $jalurid_dalam_zonasi!=0 && $jalurid_dalam_zonasi!=1)) {
											$utup_akses = 1;
										}

										if ($this->session->userdata("tutup_akses")==1) {
											$tutup_akses = 1;
										}

										if ($maxpilihan<=$jumlahpendaftaran) {
											$tutup_akses = 1;
										}

										$tutup_akses_negeri = ($row->sekolah_negeri==0 || $maxpilihannegeri<=$jumlahpendaftarannegeri);
										$tutup_akses_swasta = ($row->sekolah_swasta==0 || $maxpilihanswasta<=$jumlahpendaftaranswasta);

										if ($tutup_akses_negeri && $tutup_akses_swasta) {
											$tutup_akses = 1;
										}

										$cnt++;										
								?>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div class="box box-default box-solid">
											<div class="box-header bg-purple with-border">
												<h3 class="box-title"><?php echo $row->nama;?></h3>
												<div class="box-tools pull-right">
													<i class="glyphicon glyphicon-ok"></i>
												</div>
											</div>
											<div class="box-body">
												<?php echo $row->keterangan;?>
											</div>
											<div class="box-footer">
												<a href="<?php echo base_url();?>index.php/siswa/pendaftaran/pilihsekolah?penerapan_id=<?php echo $row->penerapan_id;?>" 
												class="btn btn-primary <?php if($tutup_akses==1) { ?>disabled<?php }?>">Klik disini untuk mendaftar</a>
											</div>
										</div>
									</div>
								<?php if ($cnt % 3 == 0 && $daftarpenerapan->num_rows() > $cnt) { ?>
								</div>
								</div>
								<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<?php } ?>
								<?php endforeach;?>
								</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php view('siswa/pendaftaran/daftarpendaftaran');?>
		<!-- </div>
	</div>
</div> -->

  <script src="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url();?>assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js"></script>
  <script src="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>


<script text="javascript">

	dt = $('#trekapitulasi').DataTable({
      "responsive": true,
      "pageLength": 5,
      "lengthMenu": [ [5, 10, 20, -1], [5, 10, 20, "All"] ],
      "paging": true,
      "pagingType": "numbers",
      dom: "tip",
      // "dom": 'Bfrtpil',
      select: true,
      buttons: [
        //   { extend: "create", editor: editor, className: "dt-button d-none" },
        //   { extend: "edit",   editor: editor, className: "dt-button d-none" },
        //   { extend: "remove", editor: editor, className: "dt-button d-none" },
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
      ajax: "<?php echo site_url('siswa/pendaftaran/rekapitulasizonasi'); ?>",
      columns: [
          { data: "nama", className: 'dt-body-left readonly-column',
			render: function ( data, type, row ) {
				if (type == 'display') {
					return "<a href='<?php echo base_url();?>index.php/Chome/peringkat?sekolah_id=" + row.sekolah_id + "' target='_blank'>" + data + "</a>";
				}
				else {
					return data;
				}
			}
		  },
		  { data: "jarak", className: 'dt-body-center readonly-column', width: '10%', 
			render: function ( data, type, row ) {
				if (type === 'display')
					return (row.jarak/1000).toFixed(2) + ' Km';
				else
					return row.jarak;

				//type: 
				//- filter : for filtering
				//- display : for display
				//- type : for type matching
				//- sort : for sorting
			}
		  },
          { data: "kuota_total", className: 'dt-body-center readonly-column', width: '10%' },
          { data: "total_pendaftar", className: 'dt-body-center', width: '10%' },
          { data: "sisa_kuota_zonasi", className: 'dt-body-center', width: '10%' },
          { data: "sisa_kuota_prestasi", className: 'dt-body-center', width: '10%' },
          { data: "sisa_kuota_afirmasi", className: 'dt-body-center', width: '10%' },
          { data: "sisa_kuota_perpindahan", className: 'dt-body-center', width: '10%' },
          { data: "status", className: 'dt-body-center' },
      ],
      order: [[8, 'asc'], [ 1, 'asc' ]],
	  "columnDefs": [
			{
                targets: [8],
                visible: false
            }
	  ]	  
    });


</script>
