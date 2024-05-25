<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">

<?php view('ppdb/home/head');?>

<body class="header_sticky page-loading">
    <?php
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

    ?>
    <section class="loading-overlay">
        <div class="preload-inner">
            <div class="wBall" id="wBall_1">
                <div class="wInnerBall"></div>
            </div>
            <div class="wBall" id="wBall_2">
                <div class="wInnerBall"></div>
            </div>
            <div class="wBall" id="wBall_3">
                <div class="wInnerBall"></div>
            </div>
            <div class="wBall" id="wBall_4">
                <div class="wInnerBall"></div>
            </div>
            <div class="wBall" id="wBall_5">
                <div class="wInnerBall"></div>
            </div>
        </div>
    </section>    
    <div id="beranda" class="boxed">
        <!-- <div class="site-header">
            <header id="header" class="header header-absolute header-style2 clearfix">
                <div class="container-fluid">    
                    <div class="header-wrap clearfix">
                        <div class="logo-wrap">
                            <div id="logo" class="logo"></div>
                        </div>
                        <div class="btn-menu">
                            <i class="ti-align-right"></i>
                        </div>
                        <div class="nav-flat-wrap">
                            <div class="nav-wrap">                            
                                <nav id="mainnav" class="mainnav color-white">
                                    <ul class="menu">
                                        <li class="home"><a href="#beranda" class="rotate arrow-bottom active smooth"><i class="glyphicon glyphicon-home"></i> Beranda</a></li>
                                        <li><a class="has-mega" href="javascript:void(0)"><i class="glyphicon glyphicon-time"></i> Pelaksanaan</a> 
                                            <ul class="submenu list-style">
                                                <li><a class="rotate arrow-bottom active smooth" href="#waktupelaksanaan">Waktu Pelaksanaan</a></li>
                                                <li><a class="rotate arrow-bottom active smooth" href="#pentunjukpelaksanaan">Petunjuk Pelaksanaan</a></li>
                                            </ul>
                                        </li>
                                        <li><a class="has-mega" href="javascript:void(0)"><i class="glyphicon glyphicon-dashboard"></i> Dashboard</a>
                                            <ul class="submenu list-style">
                                                <li><a class="rotate arrow-bottom active smooth" href="#dashboard">Dashboard</a></li>
                                                <li><a class="rotate arrow-bottom active smooth" href="#sekolahpenyelenggara">Rekapitulasi</a></li>
                                            </ul>
                                        </li>
                                        <li><a class="rotate arrow-bottom active smooth" href="#pencariansiswa"><i class="glyphicon glyphicon-search"></i> Pencarian</a></li>
                                        <li><a href="javascript:void(0)"><i class="glyphicon glyphicon-download"></i> Unduhan (Download)</a>
                                            <ul class="submenu list-style">
                                                <li><a href="javascript:void(0)" target="_blank"><i class="glyphicon glyphicon-download"></i> Petunjuk Teknis</a></li>
                                                <li><a href="javascript:void(0)" target="_blank"><i class="glyphicon glyphicon-download"></i> Panduan Aplikasi</a></li>
                                            </ul>
                                        </li>                     
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
        </div>   -->
   
        <div style="position: absolute; z-index: 10100; width: 100%; display: block;">
            <div class="container">
                <!-- <div class="alert alert-success" style="margin: 20px;">
                    <p class="text-center"><a href="<?php echo base_url();?>index.php/home/rekapitulasi" style="text-decoration:none;">Rekapitulasi Hasil PPDB Online 2020</a></p>
                </div>
                <div class="alert alert-info alert-dismissible" style="margin: 20px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p><i class="icon glyphicon glyphicon-info-sign"></i>Silahkan masuk dengan akun siswa dan gunakan menu Daftar Ulang untuk mendapatkan informasi lebih lanjut mengenai proses daftar ulang.</p>
                </div> -->

                <?php foreach($pengumuman->getResult() as $row): 
                    if(empty($row->text)) 
                        continue;
                ?>
                    <?php if ($row->tipe == 0) { ?><div class="alert alert-info <?php if($row->bisa_ditutup==1) {?>alert-dismissible<?php } ?>" style="margin: 20px;">
                    <?php } else if ($row->tipe == 1) { ?><div class="alert alert-success <?php if($row->bisa_ditutup==1) {?>alert-dismissible<?php } ?>" style="margin: 20px;">
                    <?php } else if ($row->tipe == 2) { ?><div class="alert alert-danger <?php if($row->bisa_ditutup==1) {?>alert-dismissible<?php } ?>" style="margin: 20px;">
                    <?php } else { ?><div class="alert alert-error <?php if($row->bisa_ditutup==1) {?>alert-dismissible<?php } ?>" style="margin: 20px;">
                    <?php } ?>
                    <?php if($row->bisa_ditutup==1) {?>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php } ?>
                        <p class="<?php echo $row->css; ?>"><?php echo $row->text; ?></p>
                    </div>
                <?php 
                endforeach; ?>
                
                <?php foreach($tahapan->getResult() as $row): 
                    if(empty($row->notifikasi_umum)) 
                        continue;
                ?>
                    <div class="alert alert-info alert-dismissible" style="margin: 20px;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <p><i class="icon glyphicon glyphicon-info-sign"></i><?php echo $row->tahapan; ?></p>
                        <p><?php echo $row->notifikasi_umum; ?></p>
                    </div>
                <?php 
                 endforeach; ?>
           </div>
        </div>

        <div id="rev_slider_1078_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container tparrows-none" data-alias="classic4export" data-source="gallery" style="margin:0px auto;background-color:transparent;padding:0px;margin-top:0px;margin-bottom:0px;">
            <?php view('ppdb/home/galeri');?>
        </div>
        <section id="waktupelaksanaan" class="flat-row element-countdown bg-black">
            <?php view('ppdb/home/waktupelaksanaan');?>
        </section>
        <section id="pentunjukpelaksanaan" class="flat-row element-tab">
            <?php view('ppdb/home/petunjukpelaksanaan');?>
        </section>
        <?php if (1==0) { ?>
        <section id="dashboard" class="flat-row element-countdown bg-black">
            <?php view('ppdb/home/dashboard');?>
        </section>
        <?php } ?>
       <!-- <section id="sekolahpenyelenggara" class="flat-row">
            <?php //view('home/rekapitulasi');?>
        </section> -->
         <section id="kontak" class=" blancejqurey footer-style8 bg-black">
            <?php view('ppdb/home/footer');?>       
        </section>
        <a class="go-top">
            <i class="ti-angle-up"></i>
        </a> 
    </div>
    <?php view('ppdb/home/js');?>
    <!--<script type="text/javascript">
        $(window).on('load',function(){
            $('#myModal').modal('show');
        });
    </script>!-->
</body>
</html>