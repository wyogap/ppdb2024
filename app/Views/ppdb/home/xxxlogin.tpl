<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>PPDB ONLINE {$nama_wilayah}</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link href="{$base_url}assets/image/tutwuri.png" rel="shortcut icon">
		<link rel="stylesheet" href="{$base_url}assets/adminlte/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="{$base_url}assets/adminlte/plugins/select2-4.0.13/css/select2.min.css">
		<link rel="stylesheet" href="{$base_url}assets/ionicons/css/ionicons.min.css">
		<link rel="stylesheet" href="{$base_url}assets/adminlte/plugins/iCheck/square/blue.css">
		<link rel="stylesheet" href="{$base_url}assets/adminlte/dist/css/AdminLTE.min.css">

		<script src='https://www.google.com/recaptcha/api.js' async defer></script>	
	</head>

	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<b>PPDB {$nama_tahun_ajaran}</b><br>{$nama_putaran}
			</div>
			<div class="text-center"><p><a href="{$base_url}" class="btn btn-default"><i class="glyphicon glyphicon-step-backward"></i> Kembali ke <b>Beranda</b></a></p></div>
			
            {*Pengumuman*}
            {foreach $pengumuman as $row}
            {if empty($row->text)}{continue}{/if}

            {*Get alert type*}
            {assign var='alert_type' value='alert-error'}
            {if $row->tipe==0}{$alert_type='alert-info'}
            {elseif $row->tipe==1}{$alert_type='alert-success'}
            {elseif $row->tipe==2}{$alert_type='alert-danger'}
            {/if}
            
            <div class="alert {$alert_type}{if $row->bisa_ditutup==1} alert-dismissable{/if}" style="margin: 20px;">
                {if $row->bisa_ditutup==1}<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{/if}
                <p class="{$row->css}">{$row->text}</p>
            </div>
            {/foreach}

            {*Pengumuman tahapan*}
            {foreach $tahapan_aktif as $row}
            {if empty($row->notifikasi_umum)}{continue}{/if}

            <div class="alert alert-info alert-dismissable" style="margin: 20px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p><i class="icon glyphicon glyphicon-info-sign"></i>{$row->tahapan}</p>
                <p>{$row->notifikasi_umum}</p>
            </div>
            {/foreach}

			<div class="login-box-body">
				<p class="login-box-msg text-info"><b>Silahkan log-in terlebih dahulu.</b></p>
                {if !empty($info)}<span>{$info}</span>{/if}

                {if !empty($info_message)}
                <div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {$error_message}                    
                </div>
                {/if}

                {if !empty($error_message)}
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {$error_message}                    
                </div>
                {/if}

                {if !empty($success_message)}
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {$success_message}                 
                </div>
                {/if}

				<form role="form" enctype="multipart/form-data" id="proses" action="{$base_url}home/dologin" method="post">
					<div class="form-group has-feedback">
						<input type="text" class="form-control" placeholder="NISN / NIK / Nama Pengguna" id="username" name="username" data-validation="required" minlength="8" maxlength="100">
						<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						<input type="password" class="form-control" placeholder="Password / PIN" id="password" name="password" data-validation="required">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					
					{if $cek_captcha}
                    <div class="form-group has-feedback">
                        <div class="g-recaptcha" data-sitekey="{$captcha_sitekey}"></div>
                    </div>
					{/if}

					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<button type="submit" class="btn btn-primary btn-block btn-flat"?>Log In</button>
						</div>
					</div>
				</form>
				<p>&nbsp;</p>
                {if $cek_registrasi}
				<p>Siswa yang berasal dari <b class="text-red">Luar Daerah</b>, silahkan daftar dibawah (<i class="text-blue glyphicon glyphicon-arrow-down"></i>) ini.</p>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<a href="{$site_url}home/registrasi" class="btn btn-block bg-green btn-flat">Daftar Manual</a>
					</div>
				</div>
                {else}
                <p>Pendaftaran untuk siswa dari <b class="text-red">Luar Daerah</b> belum dibuka.</p>
                {/if}
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
    // var $messages = $('#error-message-wrapper');
    // $.validate({
    //     modules: 'security',
    //     errorMessagePosition: $messages,
    //     scrollToTopOnError: false
    // });

    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%'
        });
        $(".select2").select2();
    });
</script>
