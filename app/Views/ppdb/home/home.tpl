<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="robots" content="" />
	<meta name="description" content="PPDB ONLINE {$nama_wilayah}" />
	<meta property="og:title" content="PPDB ONLINE {$nama_wilayah}" />
	<meta property="og:description" content="PPDB ONLINE {$nama_wilayah}" />
	<meta property="og:image" content="{$base_url}assets/image/tutwuri.png" />
	<meta name="format-detection" content="telephone=no">
	
	<!-- PAGE TITLE HERE -->
    <title>PPDB ONLINE {$nama_wilayah}</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="{$base_url}assets/image/tutwuri.png" rel="shortcut icon">

    <!-- <link rel="stylesheet" href="{$base_url}assets/bootstrap/css/bootstrap.min.css"> -->

    <link rel="stylesheet" type="text/css" href="{$base_url}assets/podes/stylesheets/bootstrap.css" >
    <link rel="stylesheet" type="text/css" href="{$base_url}assets/podes/stylesheets/style.css">
    <link rel="stylesheet" type="text/css" href="{$base_url}assets/podes/stylesheets/responsive.css">
    <link rel="stylesheet" type="text/css" href="{$base_url}assets/podes/stylesheets/colors/color1.css" id="colors">
    <link rel="stylesheet" type="text/css" href="{$base_url}assets/podes/stylesheets/animate.css">
    <link rel="stylesheet" type="text/css" href="{$base_url}assets/podes/revolution/css/layers.css">
    <link rel="stylesheet" type="text/css" href="{$base_url}assets/podes/revolution/css/settings.css">
    <link href="{$base_url}assets/podes/icon/apple-touch-icon-48-precomposed.png" rel="apple-touch-icon-precomposed" sizes="48x48">
    <link href="{$base_url}assets/podes/icon/apple-touch-icon-32-precomposed.png" rel="apple-touch-icon-precomposed">

	<!-- FAVICONS ICON -->
    <link href="{$base_url}/themes/dompet/css/style.css" rel="stylesheet">

    <style>
        .tp-caption.botton-slider.bg2e2f {
            background-color: black;
            color: #fff;
            padding: 11px 28px 10px 32px !important;
        }

        .ti-mouse {
            color: var(--dark) !important;
        }
        
        .accordion-header {
            border: 1px solid #969ba0;
            border-radius: 1rem;
            font-size: 18px;
        }

        .accordion-body-text .table tbody tr td {
            border-bottom: 1px solid #969ba0;;
            border-top: 1px solid #969ba0;;
        }

    </style>
</head>

<body class="vh-100">

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
        <div style="position: absolute; z-index: 10100; width: 100%; display: block;">
            <div class="container">
                {*Pengumuman*}
                {foreach $pengumuman as $row}
                {if empty($row.text)}{continue}{/if}

                {*Get alert type*}
                {assign var='alert_type' value='alert-error'}
                {if $row.tipe==0}{$alert_type='alert-info'}
                {elseif $row.tipe==1}{$alert_type='alert-success'}
                {elseif $row.tipe==2}{$alert_type='alert-danger'}
                {/if}
               
                <div class="alert {$alert_type}{if $row.bisa_ditutup==1} alert-dismissible{/if}" style="margin: 20px;">
                    {if $row.bisa_ditutup==1}<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{/if}
                    <p class="{$row.css}">{$row.text}</p>
                </div>
                {/foreach}

                {*Pengumuman tahapan*}
                {foreach $tahapan_aktif as $row}
                {if empty($row.notifikasi_umum)}{continue}{/if}

                <div class="alert alert-info alert-dismissible" style="margin: 20px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p><i class="icon glyphicon glyphicon-info-sign"></i>{$row.tahapan}</p>
                    <p>{$row.notifikasi_umum}</p>
                </div>
                {/foreach}
           </div>
        </div>

        <div id="rev_slider_1078_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container tparrows-none" data-alias="classic4export" data-source="gallery" style="margin:0px auto;background-color:transparent;padding:0px;margin-top:0px;margin-bottom:0px;">
            <div id="rev_slider_1078_1" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.3.0.2">
                <div class="slotholder"></div>
                    <ul>
                        <li data-index="rs-3049" data-transition="fade" data-slotamount="7" data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="Power4.easeInOut" data-easeout="Power4.easeInOut" data-masterspeed="2000"    data-rotate="0"  data-saveperformance="off"  data-title="Ken Burns" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                            <img src="assets/image/landingpage.jpg"  alt=""  data-bgposition="center center" data-kenburns="off" data-duration="30000" data-ease="Linear.easeNone" data-scalestart="100" data-scaleend="120" data-rotatestart="0" data-rotateend="0" data-offsetstart="0 0" data-offsetend="0 0" data-bgparallax="10" class="rev-slidebg" data-no-retina>
                            <div class="tp-caption title-slide color-white letter-spacing-1 text-orange" 
                                id="slide-3049-layer-1" 
                                data-x="['right','right','right','right']" data-hoffset="['-73','30','30','30']" 
                                data-y="['middle','middle','middle','middle']" data-voffset="['-90','-90','-90','-90']" 
                                data-fontsize="['70','70','65','33']"
                                data-lineheight="['100','100','80','45']"
                                data-fontweight="['600','600','600','600']"
                                data-width="none"
                                data-height="none"
                                data-whitespace="nowrap"
                                data-type="text" 
                                data-responsive_offset="on"                             
                                data-frames='[{ "delay":100,"speed":3000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut" },{ "delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut" }]'
                                data-textAlign="['right','right','right','right']"
                                data-paddingtop="[10,10,10,10]"
                                data-paddingright="[0,0,0,0]"
                                data-paddingbottom="[0,0,0,0"
                                data-paddingleft="[0,0,0,0]"
                                style="z-index: 16; white-space: nowrap;">{$nama_wilayah}
                            </div>
                            <div class="tp-caption sub-title position color-white letter-spacing-0" 
                                id="slide-3049-layer-3" 
                                data-x="['right','right','right','right']" data-hoffset="['-73','30','30','30']" 
                                data-y="['middle','middle','middle','middle']" data-voffset="['-163','-163','-140','-140']"
                                data-fontsize="['48',48','30','18']" 
                                data-lineheight="['60','60','35','16']"
                                data-fontweight="['00','00','00','00']"
                                data-width="['800','800','800','300']"
                                data-height="none"
                                data-whitespace="nowrap"
                                data-type="text" 
                                data-responsive_offset="on" 
                                data-frames='[{ "delay":1100,"speed":3000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut" },{ "delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut" }]'
                                data-textAlign="['right','right','right','right']"
                                data-paddingtop="[0,0,0,0]"
                                data-paddingright="[0,0,0,0]"
                                data-paddingbottom="[0,0,0,0]"
                                data-paddingleft="[0,0,0,0]"
                                style="z-index: 17; white-space: nowrap;text-transform:left;">Penerimaan <b>Peserta Didik</b> Baru
                            </div>
                            <div class="tp-caption sub-title color7779 letter-spacing-0" 
                                id="slide-3049-layer-4" 
                                data-x="['right','right','right','right']" data-hoffset="['-73','30','30','30']" 
                                data-y="['middle','middle','middle','middle']" data-voffset="['32','32','10','-10']"
                                data-fontsize="['18',18','16','14']" 
                                data-lineheight="['30','30','22','16']"
                                data-fontweight="['300','300','300','300']"
                                data-width="['800',800','700','450']"
                                data-height="none"
                                data-whitespace="['nowrap',normal','normal','normal']" 
                                data-type="text" 
                                data-responsive_offset="on" 
                                data-frames='[{ "delay":1100,"speed":3000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut" },{ "delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut" }]'
                                data-textAlign="['right','right','right','right']"
                                data-paddingtop="[0,0,0,0]"
                                data-paddingright="[0,0,0,0]"
                                data-paddingbottom="[0,0,0,0]"
                                data-paddingleft="[0,0,0,0]"
                                style="z-index: 17; white-space: normal;color:white">Sistem <b>PPDB</b> Online Tahun {$nama_tahun_ajaran}
                            </div>
                            <a href="{site_url()}auth" class="tp-caption flat-button botton-slider bg2e2f fontsize13 smooth bg-blue"
                                data-frames='[{ "from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","mask":"x:0px;y:[100%];s:inherit;e:inherit;","speed":2000,"to":"o:1;","delay":2000,"ease":"Power4.easeInOut" },{ "delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut" }]'
                                data-x="['right','right','right','right']" data-hoffset="['-73','30','30','30']" 
                                data-y="['middle','middle','middle','middle']" data-voffset="['158','158','140','100']" 
                                data-width="['auto']"
                                data-height="['auto']"
                                style="z-index: 3;">SILAHKAN MASUK DISINI
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="img-arrow arr-top style2 color-2e2f clearfix">
                <a class="icon-right-arrow rotate arrow-bottom active smooth" href="#waktupelaksanaan"><i class="ti-mouse"></i></a>
            </div>
        </div>
    </div>
    <section id="waktupelaksanaan" class="flat-row element-countdown">
        <div class="container">
            <div class="title-call-action another text-center">
                Waktu Pelaksanaan PPDB
            </div>
            <div class="row text-center">
                <div class="col-12">
                <div class="accordion accordion-active-header" id="accordion-nine">
                    {foreach $putaran as $p}
                    {if empty($p.tahapan)}{continue}{/if}
                    <div class="accordion-item">
                        <div class="accordion-header rounded-lg collapsed" id="accord-{$p.putaran}" data-bs-toggle="collapse" data-bs-target="#collapse-{$p.putaran}" aria-controls="collapse-{$p.putaran}" aria-expanded="true" role="button">
                            <span class="accordion-header-icon"></span>
                            <span class="accordion-header-text">{$p.nama}</span>
                            <span class="accordion-header-indicator"></span>
                        </div>
                        <div id="collapse-{$p.putaran}" class="accordion__body collapse" aria-labelledby="accord-{$p.putaran}" data-bs-parent="#accordion-nine" style="">
                            <div class="accordion-body-text">
                                <table class="table">
                                    <tbody>
                                        {foreach $p.tahapan as $t}
                                        {if $t.tahapan_id==0 || $t.tahapan_id==99} {continue} {/if} 
                                        <tr class="" style="padding-top: 10px; padding-bottom: 10px">
                                            <td  class="">{$t.tahapan}</td><td></td>
                                            {if ($t.tanggal_mulai == $t.tanggal_selesai)}<td class="" colspan=3>{$t.tanggal_mulai}</td>
                                            {else}<td class="">{$t.tanggal_mulai}</td><td width="20px">s.d.</td><td class="">{$t.tanggal_selesai}</td>
                                            {/if}
                                        </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {/foreach}
                </div>
                </div>
            </div>
        </div>
    </section>
    {if !empty($petunjuk_pelaksanaan)}
    <section id="pentunjukpelaksanaan" class="flat-row element-tab">
        <div class="container">
            <div class="title-call-action another text-center">
                Petunjuk Pelaksanaan
            </div>
            <div class="row">
                <div class="col-12">
                <div class="accordion accordion-active-header" id="accordion-2">
                    {foreach $petunjuk_pelaksanaan as $p}
                    <div class="accordion-item">
                        <div class="accordion-header rounded-lg collapsed text-center" id="accord2-{$p.id}" data-bs-toggle="collapse" data-bs-target="#collapse2-{$p.id}" aria-controls="collapse2-{$p.id}" aria-expanded="true" role="button">
                            <span class="accordion-header-icon"></span>
                            <span class="accordion-header-text">{$p.title}</span>
                            <span class="accordion-header-indicator"></span>
                        </div>
                        <div id="collapse2-{$p.id}" class="accordion__body collapse" aria-labelledby="accord2-{$p.id}" data-bs-parent="#accordion-2" style="">
                            <div class="accordion-body-text">
                                {$p.text}
                            </div>
                        </div>
                    </div>
                    {/foreach}
                </div>
            </div>
        </div>
    </section>
    {/if}
    <a class="go-top">
            <i class="ti-angle-up"></i>
        </a> 

    <section id="kontak" class=" blancejqurey bg-black" style="padding-bottom: 36px;">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <div class="title-footer8">
                        <img src="{$base_url}assets/image/logodinas.png" style="width:150px">
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="title-footer8">{$nama_wilayah}</div><br>
                    <p>{$alamat_dinas}</p><br>
                    <div class="info-contact" style="font-size: 36px;"><div class="phone">{$nomor_telp_dinas}</div></div>
                </div>
            </div>
        </div>        
    </section>

	
</body>

<!--**********************************
    Scripts
***********************************-->
<!-- Required vendors -->
<script src="{$base_url}/themes/dompet/vendor/global/global.min.js"></script>

<script type="text/javascript" src="{$base_url}assets/podes/javascript/jquery.min.js"></script>
<script type="text/javascript" src="{$base_url}assets/bootstrap/js/bootstrap.min.js"></script>

<!-- <script type="text/javascript" src="{$base_url}assets/podes/javascript/jquery.easing.js"></script>      
<script type="text/javascript" src="{$base_url}assets/podes/javascript/jquery-validate.js"></script>
<script type="text/javascript" src="{$base_url}assets/podes/javascript/imagesloaded.min.js"></script>
<script type="text/javascript" src="{$base_url}assets/podes/javascript/jquery.isotope.min.js"></script>
<script type="text/javascript" src="{$base_url}assets/podes/javascript/owl.carousel.js"></script> -->
<!-- <script type="text/javascript" src="{$base_url}assets/podes/javascript/jquery-countTo.js"></script>
<script type="text/javascript" src="{$base_url}assets/podes/javascript/jquery.cookie.js"></script>
<script type="text/javascript" src="{$base_url}assets/podes/javascript/jquery.tweet.min.js"></script> -->
<!-- <script type="text/javascript" src="{$base_url}assets/podes/javascript/parallax.js"></script>
<script type="text/javascript" src="{$base_url}assets/podes/javascript/main.js"></script> -->
<!-- <script type="text/javascript" src="{$base_url}assets/podes/javascript/TimeCircles.js"></script> -->
<!-- <script type="text/javascript" src="{$base_url}assets/podes/javascript/jquery-waypoints.js"></script> 
<script type="text/javascript" src="{$base_url}assets/podes/javascript/jquery.magnific-popup.min.js"></script>  
<script type="text/javascript" src="{$base_url}assets/podes/javascript/countdown.js"></script> -->

<script type="text/javascript" src="{$base_url}assets/podes/revolution/js/jquery.themepunch.tools.min.js"></script>
<script type="text/javascript" src="{$base_url}assets/podes/revolution/js/jquery.themepunch.revolution.min.js"></script>
<script type="text/javascript" src="{$base_url}assets/podes/revolution/js/slider2.js"></script>

<script type="text/javascript" src="{$base_url}assets/podes/revolution/js/extensions/revolution.extension.actions.min.js"></script>
<script type="text/javascript" src="{$base_url}assets/podes/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
<script type="text/javascript" src="{$base_url}assets/podes/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
<script type="text/javascript" src="{$base_url}assets/podes/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
<script type="text/javascript" src="{$base_url}assets/podes/revolution/js/extensions/revolution.extension.migration.min.js"></script>
<script type="text/javascript" src="{$base_url}assets/podes/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
<script type="text/javascript" src="{$base_url}assets/podes/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
<script type="text/javascript" src="{$base_url}assets/podes/revolution/js/extensions/revolution.extension.slideanims.min.js"></script> 

<script>
    var dezSettingsOptions = {};

    (function($) {
        let dark_theme = getCookie('dark_theme');
        if (dark_theme === undefined) {
            dark_theme = 0;
        } 

        //update the theme setting. must be before dlabnav-init.js
        dezSettingsOptions = {
            typography: "cairo",
            version: ((dark_theme==1) ? "dark" : "light"),
            layout: "horizontal",
            primary: "color_1",
            navheaderBg: "color_1",
            sidebarBg: "color_1",
            sidebarStyle: "compact",
            sidebarPosition: "fixed",
            headerPosition: "fixed",
            containerLayout: "boxed",
        };
        
    })(jQuery);

    function toggle_dark_mode() {
        let dark_theme = getCookie('dark_theme');
        if (dark_theme === undefined) {
            dark_theme = 0;
        } 

        if (dark_theme == 1)    dark_theme = 0;
        else                    dark_theme = 1;

        setCookie("dark_theme", dark_theme, 30);

        dezSettingsOptions = {
            typography: "cairo",
            version: ((dark_theme==1) ? "dark" : "light"),
            layout: "horizontal",
            primary: "color_1",
            navheaderBg: "color_1",
            sidebarBg: "color_1",
            sidebarStyle: "compact",
            sidebarPosition: "fixed",
            headerPosition: "fixed",
            containerLayout: "boxed",
        };
        
        new dezSettings(dezSettingsOptions); 
    }

    function setCookie(c_name, value, exdays) {
        var exdate = new Date();
        exdate.setDate(exdate.getDate() + exdays);
        var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
        document.cookie = c_name + "=" + c_value;
    }

    function getCookie(c_name) {
        var i, x, y, ARRcookies = document.cookie.split(";");
        for (i = 0; i < ARRcookies.length; i++) {
            x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
            y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
            x = x.replace(/^\s+|\s+$/g, "");
            if (x == c_name) {
                return unescape(y);
            }
        }
    }
            
</script>

<script src="{$base_url}/themes/dompet/js/custom.min.js"></script>
<script src="{$base_url}/themes/dompet/js/dlabnav-init.js"></script>

</html>