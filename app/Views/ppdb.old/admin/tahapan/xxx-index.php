<!DOCTYPE html>
<html>
	<?php view('head');?>

<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Editor-1.9.2/css/editor.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ppdb.css">
<script src="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
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
							<i class="glyphicon glyphicon-list-alt"></i> Tahapan Penerimaan</small>
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
								<th class="text-center" data-priority="1"></th>
								<th class="text-center" data-priority="2">#</th>
								<th class="text-center" data-priority="3">Tahapan</th>
								<th class="text-center">Mulai</th>
								<th class="text-center">Selesai</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$i=1;
								foreach($tahapan_penerimaan->getResult() as $row2):
							?>
							<tr>
								<td class="text-center"><a href="<?php echo base_url();?>index.php/Cadmin/ubahkuotanegeri?waktu_pelaksanaan_id=<?php echo $row2->waktu_pelaksanaan_id;?>" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a></td>
								<td class="text-end"><?php echo $row2->tahapan_id; ?></td>
								<td class="text-left"><?php echo $row2->tahapan;?></td>
								<td class="text-center"><?php echo $row2->tanggal_mulai;?></td>
								<td class="text-center"><?php echo $row2->tanggal_selesai;?></td>
							</tr>
							<?php $i++; endforeach;?>
						</tbody>
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

$.extend( $.fn.dataTable.defaults, { responsive: true } );

$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
	$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
} );
 
// Tabel
$(document).ready(function() {
	$('#tnegeri').dataTable({
		"responsive": true,
		"paging": false,
		"dom": 'rt',
		"language": {
			"sProcessing":   "Sedang proses...",
			"sZeroRecords":  "Tidak ditemukan data yang sesuai",
			"sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 entri",
			"sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
			"sInfoPostFix":  "",
			"sSearch":       "Cari:",
			"sUrl":          ""
		}		
	});
});

	//Event On Change Dropdown
	$(document).ready(function () {
		$('select[name="tahunajaran"]').on('change', function() {
			window.location.replace("<?php echo site_url('Cadmin/tahapanpenerimaan'); ?>?tahun_ajaran=" + $("#tahunajaran").val());
		});
	});

</script>

</html>
