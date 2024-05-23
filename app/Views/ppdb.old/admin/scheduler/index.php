<!DOCTYPE html>
<html>
	<?php view('head');?>

<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ppdb.css">
<script src="<?php echo base_url();?>assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>

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
							<i class="glyphicon glyphicon-list-alt"></i> Scheduler
						</h1>
						<!-- <ol class="breadcrumb">
							<li class="active"><a href="#"><i class="glyphicon glyphicon-th-list"></i> Peringkat Pendaftaran</a></li>
						</ol> -->
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
								<th class="text-center" data-priority="3">#</th>
								<th class="text-center" data-priority="2">Nama</th>
								<th class="text-center">Selanjutnya</th>
								<th class="text-center">Terakhir Mulai</th>
								<th class="text-center">Terakhir Selesai</th>
								<th class="text-center">Status</th>
								<th class="text-center">Oleh</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$i=1;
								foreach($jobs->getResult() as $row2):
							?>
							<tr>
								<td class="text-center">
								<a href="#" class="btn btn-xs btn-primary" onClick="execute_job(<?php echo $row2->source; ?>, <?php echo $row2->job_id; ?>); return false;" title="Klik untuk mengeksekusi job">Jalankan</a>
								<!-- <a href="<?php echo base_url().'index.php/admin/scheduler/executejob?id='.$row2->job_id.'&source='.$row2->source; ?>" class="btn btn-xs btn-primary" title="Klik untuk mengeksekusi job">Jalankan</a> -->
								</td>
								<td class="text-end"><?php echo $row2->job_id; ?></td>
								<td class="text-left"><?php echo $row2->title;?></td>
								<td class="text-center"><?php echo $row2->next_execution;?></td>
								<td class="text-center"><?php echo $row2->last_execution_start;?></td>
								<td class="text-center"><?php echo $row2->last_execution_end;?></td>
								<td class="text-center"><?php echo $row2->execution_status;?></td>
								<td class="text-center"><?php echo $row2->execution_by;?></td>
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

function execute_job(source, jobid) {
	xhttp=new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			let json = this.response;
			if (json == null) {
				alert("Error - tidak berhasil menjalankan job");
			}
			else if (json.result > 0) {
				//reload
				window.location = window.location;
			} 
			else {
				alert(this.responseText);
			}
		}
	};
	xhttp.responseType = 'json';
	xhttp.open("POST", "<?php echo site_url('admin/scheduler/executejob?json=1'); ?>", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("id=" + jobid + "&source=" + source);
}

</script>

</html>
