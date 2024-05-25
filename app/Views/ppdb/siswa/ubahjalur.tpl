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
							<i class="glyphicon glyphicon-edit"></i> Ubah Jalur Pendaftaran Sekolah
						</h1>
						<ol class="breadcrumb">
							<li class="active"><a href="<?php echo site_url('siswa/pendaftaran');?>"><i class="glyphicon glyphicon-remove"></i> Batal </a></li>
						</ol>

					</section>
					<section class="content">
						<?php if ($maxubahjalur > $ubahjalursiswa) { ?>
						<div class="alert alert-danger alert-dismissible">
							<i class="icon glyphicon glyphicon-info-sign"></i>
							Anda hanya bisa melakukan perubahan <b>"Jalur Pendaftaran"</b> sebanyak <b><?php echo $maxubahjalur-$ubahjalursiswa;?> kali</b>.
						</div>
						<?php } else { ?>
							<div class="alert alert-danger alert-dismissible">
							<i class="icon glyphicon glyphicon-info-warning"></i>
							Anda sudah tidak bisa melakukan perubahan <b>"Jalur Pendaftaran"</b> karena sudah melebihi batasan.</b>.
						</div>
						<?php } ?>

                        <?php if ($jalurswasta == 1) { ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <p><i class="icon glyphicon glyphicon-exclamation-sign"></i>Anda tidak bisa mengubah jalur pendaftaran dari jalur Swasta. Jalur pendaftaran Swasta dikhususkan untuk pendaftaran ke sekolah swasta.</p>             
                            </div>
                        <?php } ?>

                        <?php if ($dalam_zonasi == 1 && $jalurswasta != 1 && $satu_zonasi_satu_jalur == 1) { ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <p><i class="icon glyphicon glyphicon-exclamation-sign"></i>Anda hanya bisa mendaftar menggunakan satu jalur pada satu zonasi. Mohon berhati-hati dalam menentukan jalur pendaftaran.</p>             
                            </div>

                            <?php if ($jalurid_dalam_zonasi) { ?>
                                <div class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <p><i class="icon glyphicon glyphicon-info-sign"></i>Anda mempunyai pendaftaran menggunakan jalur <?php echo $namajalur_dalam_zonasi; ?> di dalam zonasi anda. Silahkan hapus terlebih dahulu pendaftaran tersebut.</p>             
                                </div>
                            <?php } ?>

                        <?php } ?>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-map-marker"></i>
                                        <h3 class="box-title text-info"><b>Peta Sebaran Sekolah</b></h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div id="peta" style="width: 100%; height: 400px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-search"></i>
                                        <h3 class="box-title text-info"><b>Detail Pendaftaran</b></h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <?php view('siswa/ubahjalur/detailjalur');?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

					</section>
				</div>
			</div>
			{include file='../footer.tpl'}
		</div>
	</body>

    <script>
        //Dropdown Select
        $(function () {
            $(".select2").select2();
        });
        //Peta
        var map = L.map('peta',{zoomControl:false}).setView([0,0],16);
        L.tileLayer(
            '<?php echo $servis_peta_aktif;?>',{maxZoom: 18,attribution: 'PPDB <?php echo $wilayah_aktif;?>',id: 'mapbox.streets'}
        ).addTo(map);
        
        <?php if($detailpilihan!=null) {?>
            L.marker([<?php echo $detailpilihan['lintang_siswa'];?>,<?php echo $detailpilihan['bujur_siswa'];?>]).addTo(map).bindPopup("Lokasi Rumah").openPopup();
            L.marker([<?php echo $detailpilihan['lintang_sekolah'];?>,<?php echo $detailpilihan['bujur_sekolah'];?>]).addTo(map).bindPopup("(<?php echo $detailpilihan['npsn'];?>) <?php echo $detailpilihan['sekolah'];?>");
        <?php }?>
        
        map.fitBounds([
            <?php if($detailpilihan!=null) {?>
            [<?php echo  $detailpilihan['lintang_sekolah'];?>,<?php echo  $detailpilihan['bujur_sekolah'];?>],[<?php echo  $detailpilihan['lintang_siswa'];?>,<?php echo  $detailpilihan['bujur_siswa'];?>]
            <?php }?>
        ]);
        
        new L.Control.Zoom({position:'bottomright'}).addTo(map);

    </script>

</html>
