<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>PPDB ONLINE</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link href="<?php echo base_url();?>assets/image/tutwuri.png" rel="shortcut icon">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/select2-4.0.13/css/select2.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/ionicons/css/ionicons.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/dist/css/AdminLTE.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/datepicker/datepicker3.css">
		<script src="<?php echo base_url();?>assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
		<script src="<?php echo base_url();?>assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>assets/adminlte/plugins/select2/select2.full.min.js"></script>
		<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>
		<script src="<?php echo base_url();?>assets/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>
		<!-- 
		<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css"/>
		<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet'/>
		<link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.css" integrity="sha512-v5YmWLm8KqAAmg5808pETiccEohtt8rPVMGQ1jA6jqkWVydV5Cuz3nJ9fQ7ittSxvuqsvI9RSGfVoKPaAJZ/AQ==" crossorigin="">
		<script src="<?php echo base_url();?>assets/leaflet/leaflet.js"></script>
		<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>
		<script src="https://unpkg.com/esri-leaflet@2.2.4/dist/esri-leaflet.js" integrity="sha512-tyPum7h2h36X52O2gz+Pe8z/3l+Y9S1yEUscbVs5r5aEY5dFmP1WWRY/WLLElnFHa+k1JBQZSCDGwEAnm2IxAQ==" crossorigin=""></script>
		<script src="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.js" integrity="sha512-zdT4Pc2tIrc6uoYly2Wp8jh6EPEWaveqqD3sT0lf5yei19BC1WulGuh5CesB0ldBKZieKGD7Qyf/G0jdSe016A==" crossorigin=""></script>
		-->

		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ppdb.css">

		<!-- Load Leaflet from CDN -->
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
		integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
		crossorigin=""/>
		<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
		integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
		crossorigin=""></script>


		<!-- Load Esri Leaflet from CDN -->
		<script src="https://unpkg.com/esri-leaflet@2.4.1/dist/esri-leaflet.js"
		integrity="sha512-xY2smLIHKirD03vHKDJ2u4pqeHA7OQZZ27EjtqmuhDguxiUvdsOuXMwkg16PQrm9cgTmXtoxA6kwr8KBy3cdcw=="
		crossorigin=""></script>


		<!-- Load Esri Leaflet Geocoder from CDN -->
		<link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.css"
			integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
			crossorigin="">
		<script src="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.js"
			integrity="sha512-HrFUyCEtIpxZloTgEKKMq4RFYhxjJkCiF5sDxuAokklOeZ68U2NPfh4MFtyIVWlsKtVbK5GD2/JzFyAfvT5ejA=="
			crossorigin=""></script>

		<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
		<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>

		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
		<script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>

	</head>
	<body class="hold-transition">
			<div class="wrapper">
			<div class="container">
				<div class="box box-primary">
					<div class="login-logo">
						<a href="javascript:void(0)"><b>Pencarian Siswa</b></a><br>
						<a class="text-white btn btn-primary" href="<?php echo base_url();?>index.php/Clogin/"><b><i class="glyphicon glyphicon-chevron-left"></i></b> Kembali ke halaman <b>Log In</b></a>
					</div>
					<div class="box-body">
						<h4>Silahkan cari data pendaftaran berdasarkan NISN.</h4>
						<br>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<input type="text" class="form-control" id="nisn" name="nisn" placeholder="NISN" minlength="10" maxlength="10" data-validation="required">
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" minlength="3" maxlength="100">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<a href="javascript:void(0)" onclick="carisiswa()" class="btn btn-warning btn-lg btn-flat">Cari Siswa</a>
							</div>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="daftarpencarian"></div>
					</div>
					<div class="box-footer">
						<strong>Copyright &copy; 2019 <a href="javascript:void(0)">Dinas Pendidikan <?php echo $wilayah_aktif;?></a>.</strong> All rights reserved.
					</div>
				</div>
			</div>
		</div>
	</body>

	<script>
        $(function () {
            $(".select2").select2();
        });

        function carisiswa(){
            var nama = $("#nama").val();
            if(nama.length<=5){
                alert('Variabel nama minimal 5 karakter');
            }else{
                var data = {nisn:$("#nisn").val(),nama:nama};
                $.ajax({
                    type: "POST",
                    url : "<?php echo site_url('akun/pencarian/carisiswa')?>",
                    data: data,
                    success: function(msg){
                        $('#daftarpencarian').html(msg);
                    }
                });
            }
        }

	</script>	

</html>
