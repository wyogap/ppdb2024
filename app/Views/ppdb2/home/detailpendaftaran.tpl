<!DOCTYPE html>
<html>
    {include file='../header.tpl'}
	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
            {include file='../navigation.tpl'}
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<h1 class="text-white">
							<i class="glyphicon glyphicon-user"></i> {$profilsiswa.nama}
						</h1>
						<ol class="breadcrumb">
							<li class="active"><a href="#" onclick="window.history.back();"><i class="glyphicon glyphicon-remove"></i> Kembali </a></li>
						</ol>
					</section>
					<section class="content">

                        {include file='../common/daftarpendaftaran.tpl'}

					</section>
				</div>
			</div>
			{include file='../footer.tpl'}
		</div>
	</body>
</html>