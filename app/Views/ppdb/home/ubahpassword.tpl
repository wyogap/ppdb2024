<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>PPDB ONLINE</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link href="{$base_url}assets/image/tutwuri.png" rel="shortcut icon">
        
		<link rel="stylesheet" href="{$base_url}assets/adminlte/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="{$base_url}assets/adminlte/plugins/select2-4.0.13/css/select2.min.css">
		<link rel="stylesheet" href="{$base_url}assets/ionicons/css/ionicons.min.css">
		<link rel="stylesheet" href="{$base_url}assets/adminlte/plugins/iCheck/square/blue.css">
		<link rel="stylesheet" href="{$base_url}assets/adminlte/dist/css/AdminLTE.min.css">
	</head>
	<body class="hold-transition lockscreen">
		<div class="lockscreen-wrapper">
            {if $info_message}<span>{$info_message}</span>{/if}

			{if $error_message}
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {$error_message}                    
            </div>
            {/if}

			{if $success_message}
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {$success_message}                 
            </div>
			{/if}

			<div class="lockscreen-logo">
				<a href="{$base_url}"><b>UBAH</b>PASSWORD</a>
			</div>
            
			<div class="lockscreen-name">{$user_name}</div>
				<div class="lockscreen-item">
					<div class="lockscreen-image">
						<img src="{$base_url}assets/image/user.png" alt="User Image">
					</div>
					<form role="form" enctype="multipart/form-data" id="proses" action="{$base_url}index.php/akun/password/ubah" method="post" class="lockscreen-credentials">
						<div class="input-group">
							<input type="password" class="form-control" placeholder="PIN / Password Baru" id="password" name="password" data-validation="required">
							<div class="input-group-btn">
								<button type="submit" class="btn"><i class="glyphicon glyphicon-arrow-right text-muted"></i></button>
							</div>
						</div>
					</form>
				</div>
				<div class="help-block text-center">
					Silahkan masukkan password baru Anda.
				</div>
				<div class="text-center">
					<a href="{$base_url}index.php/akun/login">Kembali ke beranda</a>
				</div>
				<div class="lockscreen-footer text-center">
					Copyright &copy; 2019 <b><a href="javascript:void(0)" class="text-black">{$nama_wilayah}</a></b><br>All rights reserved
			    </div>
		    </div>
        </div>
	</body>
</html>

<script src="{$base_url}assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="{$base_url}assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="{$base_url}assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="{$base_url}assets/adminlte/plugins/select2/select2.full.min.js"></script>
<script src="{$base_url}assets/formvalidation/form-validator/jquery.form-validator.js"></script>
<script>
    var $messages = $('#error-message-wrapper');
        $.validate({
            modules: 'security',
            errorMessagePosition: $messages,
            scrollToTopOnError: false
        });
    $(function () {
        $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%'
        });
        $(".select2").select2();
    });
</script>