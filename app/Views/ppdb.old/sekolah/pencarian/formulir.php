<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url();?>assets/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>
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
							<label for="tempat_lahir">Tempat Lahir</label>
							<input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" minlength="3" maxlength="32">
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group has-feedback">
							<label for="tanggal_lahir">Tanggal Lahir</label>
							<input id="tanggal_lahir" name="tanggal_lahir" type="text" class="form-control" aria-describedby="basic-addon1">
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<a href="javascript:void(0)" onclick="carisiswa()" class="btn btn-primary btn-flat">Cari Pendaftar</a>
					</div>
				</div>
			</div>
		</div>
	</div>
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
		var data = {nama:$("#nama").val(),jenis_kelamin:$("#jenis_kelamin").val(),tempat_lahir:$("#tempat_lahir").val(),tanggal_lahir:$("#tanggal_lahir").val()};
		$.ajax({
			type: "POST",
			url : "<?php echo site_url('sekolah/pendaftaran/cari')?>",
			data: data,
			success: function(msg){
				$('#daftarpencarian').html(msg);
			}
		});
	}
</script>