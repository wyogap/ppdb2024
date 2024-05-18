<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="robots" content="" />

    <title>PPDB ONLINE {$nama_wilayah} - {$page_title}</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="{$base_url}assets/image/tutwuri.png" rel="shortcut icon">

    <!-- Datatable -->
    <!-- <link href="{$base_url}/themes/dompet/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet"> -->
 
    <link rel="stylesheet" href="{$base_url}assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{$base_url}assets/datatables/Select-1.3.1/css/select.dataTables.min.css">
    <link rel="stylesheet" href="{$base_url}assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">
	<link rel="stylesheet" href="{$base_url}assets/datatables/Buttons-1.6.1/css/buttons.dataTables.min.css">

    <!-- icons -->
    <!-- <link href="{$base_url}assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css" /> -->
    <!-- <link href="{$base_url}assets/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css" rel="stylesheet" type="text/css" /> -->
    <link href="{$base_url}assets/dripicons/icons.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{$base_url}assets/ionicons/css/ionicons.min.css">

    <!-- toastr toast popup -->
    <link href="{$base_url}assets/jquery-confirm/jquery-confirm.min.css" rel="stylesheet" type="text/css" />
    <link href="{$base_url}assets/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

	<!-- FAVICONS ICON -->
	<link href="{$base_url}/themes/dompet/vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
    <link href="{$base_url}/themes/dompet/css/style.css" rel="stylesheet">
    
    {include file='../_css.tpl'}

</head>

<body data-typography="cairo" data-theme-version="dark" data-sidebar-style="compact" data-layout="horizontal" data-nav-headerbg="color_1" 
    data-headerbg="color_1" data-sidebarbg="color_1" data-sidebar-position="fixed" data-header-position="fixed" data-container="boxed" direction="ltr" 
    data-primary="color_1">

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="index.html" class="brand-logo">
                <img src="{$site_url}assets/image/home-2.png">
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->
				
		
        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="app-name-short" style="display: none;">
                                {$app_short_name}{$tahun_ajaran_id}
                            </div>
                            <div class="app-name-long dashboard_bar">
                                <span class="app-name">{$app_name}</span>
                                <span class="app-desc">Tahun {$nama_tahun_ajaran} {$nama_putaran}</span>
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown header-profile">
                                    <a class="nav-link" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="{$base_url}assets/image/user.png" width="20" alt=""/>
                                        <div class="header-info ms-3">
                                            <span class="font-w600 ">Halo, <b>{$nama_pengguna}</b></span>
                                            <small class="font-w400">{$user_name}</small>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="./app-profile.html" class="dropdown-item ai-icon">
                                            <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                            <span class="ms-2">Profile </span>
                                        </a>
                                        <a href="{$site_url}auth/logout" class="dropdown-item ai-icon">
                                            <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                            <span class="ms-2">Logout </span>
                                        </a>
                                    </div>
                            </li>
                        </ul>
                    </div>
				</nav>
			</div>
		</div>
                    
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="dlabnav">
            <div class="dlabnav-scroll" style="text-align: center;">
				<ul class="metismenu" id="menu">
                    <li data-bs-toggle="tooltip" data-bs-placement="right" title="Beranda">
                        <a id="menu-kelengkapan" class="ai-icon" href="{$site_url}ppdb/sekolah/beranda" aria-expanded="false" >
							<i class="flaticon-025-dashboard"></i>
							<span class="nav-text">Beranda</span>
						</a>
                    </li>
                    <li data-bs-toggle="tooltip" data-bs-placement="right" title="Peringkat">
                        <a id="menu-pendaftaran" class="ai-icon" href="{$site_url}ppdb/sekolah/peringkat" aria-expanded="false" >
						<i class="flaticon-043-menu"></i>
						<span class="nav-text">Peringkat</span>
						</a>
                    </li>
                    <li data-bs-toggle="tooltip" data-bs-placement="right" title="Verifikasi">
                        <a id="menu-hasil" class="ai-icon nav-link" href="{$site_url}ppdb/sekolah/verifikasi" aria-expanded="false" >
							<i class="flaticon-012-checkmark"></i>
							<span class="nav-text">Verifikasi</span>
						</a>
                    </li>
                    <li data-bs-toggle="tooltip" data-bs-placement="right" title="Daftar Ulang">
                        <a id="menu-daftarulang" class="ai-icon" href="{$site_url}ppdb/sekolah/daftarulang" aria-expanded="false" >
							<i class="flaticon-086-star"></i>
							<span class="nav-text">Daftar Ulang</span>
						</a>
                    </li>
                    <li data-bs-toggle="tooltip" data-bs-placement="right" title="Pengelolaan">
                        <a id="menu-daftarulang" class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false" >
							<i class="flaticon-013-checkmark"></i>
							<span class="nav-text">Pengelolaan</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="{$site_url}ppdb/sekolah/ubahprofil">Ubah Profil Sekolah</a></li>
                            <li><a href="{$site_url}ppdb/sekolah/pengajuanakun">Pengajuan Akun</a></li>
                            <li><a href="{$site_url}ppdb/sekolah/pencarian">Pencarian Siswa</a></li>
                            <li><a href="{$site_url}ppdb/sekolah/kandidatswasta">Kandidat Siswa</a></li>
                            <li><a href="{$site_url}ppdb/sekolah/berkasdisekolah">Berkas Di Sekolah</a></li>
                        </ul>
                    </li>
                </ul>
				<div class="copyright">
                    <p><strong>Copyright &copy; 2019 <a class="text-white" href="javascript:void(0)">{$nama_wilayah}</a>.</strong> All rights reserved.</p>
				</div>
			</div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->


        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- container starts -->
            <div class="container-fluid">

                {*Pengumuman*}
                {if $pengumuman|default: FALSE}
                {foreach $pengumuman as $row}
                {if empty($row->text)}{continue}{/if}

                {*Get alert type*}
                {assign var='alert_type' value='alert-danger'}
                {if $row->tipe==0}{$alert_type='alert-info'}
                {elseif $row->tipe==1}{$alert_type='alert-success'}
                {elseif $row->tipe==2}{$alert_type='alert-danger'}
                {/if}
               
                <div class="alert {$alert_type}{if $row->bisa_ditutup==1} alert-dismissible{/if}" role="alert">
                    <p class="{$row->css}">{$row->text}</p>
                    {if $row->bisa_ditutup==1}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>{/if}
                </div>
                {/foreach}
                {/if}

                {*Pengumuman tahapan*}
                {if $tahapan_aktif|default: FALSE}
                {foreach $tahapan_aktif as $row}
                {if empty($row->notifikasi_sekolah)}{continue}{/if}

                <div class="alert alert-info alert-dismissible" role="alert">
                    <p><i class="icon glyphicon glyphicon-info-sign"></i>{$row->tahapan}</p>
                    <p>{$row->notifikasi_umum}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                {/foreach}
                {/if}

                {if !empty($info)}<span>{$info}</span>{/if}

                {if !empty($info_message)}
                <div class="alert alert-info alert-dismissible" role="alert">
                    {$error_message}                    
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                {/if}

                {if !empty($error_message)}
                <div class="alert alert-danger alert-dismissible" role="alert">
                    {$error_message}                    
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                {/if}

                {if !empty($success_message)}
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {$success_message}                 
                </div>
                {/if}

                {if $notif_ganti_password|default: FALSE}
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <p><i class="icon glyphicon glyphicon-warning-sign"></i>Anda belum mengganti PIN anda. <b>Segera ganti PIN awal anda!</b></p>
                                <p style="margin-top: 15px !important; margin-bottom: -4px;"><a href="{$base_url}index.php/akun/password" class="btn btn-primary">Ganti PIN</a></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                {/if}

                <div class="loading" id="loader">
                <div class="loading-circle"></div>
                </div>

                {if $content_template|default: FALSE} 
                    {include file="./$content_template"}
                {/if}
            </div>
            <!-- container ends -->
        </div>
        <!--**********************************
                Content body end
            ***********************************-->

        <!--**********************************
            Footer start
        ***********************************-->
        {include file='../footer.tpl'}

        <!--**********************************
            Footer end
        ***********************************-->

        <style>
            .DZ-theme-btn.DZ-bt-scroll-top {
                background: #1ebbf0;
                background: -moz-linear-gradient(45deg, #1ebbf0 8%, #39dfaa 100%);
                background: -webkit-linear-gradient(45deg, #1ebbf0 8%, #39dfaa 100%);
                background: linear-gradient(45deg, #1ebbf0 8%, #39dfaa 100%);
                bottom: 20px;
            }

            .DZ-theme-btn.DZ-bt-scroll-top img {
                margin-top: 4px;
            }
    
            .DZ-theme-btn {
                background-color: #fff;
                border-radius: 40px;
                bottom: 10px;
                /* color: #fff; */
                display: table;
                height: 50px;
                right: 10px;
                min-width: 50px;
                position: fixed;
                text-align: center;
                z-index: 99999;
                color: #6f6f6f;
                outline: 0 none;
                text-decoration: none;
            }
        </style>
        <a href="#top" class="DZ-bt-scroll-top DZ-theme-btn">
            <img src="http://localhost/ppdb2024/images/icons8-arrow-up-50.png" enable-background="new 0 0 512 512" height="40" viewBox="0 0 512 512" width="40">
        </a>  
    
        
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->

    <!-- Required vendors -->
    <script src="{$base_url}/themes/dompet/vendor/global/global.min.js"></script>
	<script src="{$base_url}/themes/dompet/vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>

    <!-- <link rel="stylesheet" href="{$base_url}assets/adminlte/plugins/select2/select2.min.css">
    <script src="{$base_url}assets/adminlte/plugins/select2/select2.full.min.js"></script> -->

    <!-- Datatable -->
    <!-- <script src="{$base_url}/themes/dompet/vendor/datatables/js/jquery.dataTables.min.js"></script> -->

    <script src="{$base_url}assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="{$base_url}assets/datatables/Select-1.3.1/js/dataTables.select.min.js"></script>
    <script src="{$base_url}assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>
	<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.flash.min.js"></script>
    <script src="{$base_url}assets/datatables/JSZip-2.5.0/jszip.min.js"></script>
    <script src="{$base_url}assets/datatables/pdfmake-0.1.36/pdfmake.min.js"></script>
    <script src="{$base_url}assets/datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
    <script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.html5.min.js"></script>
    <script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.print.min.js"></script>
    
    <!-- <link rel="stylesheet" href="{$base_url}assets/datatables/Editor-1.9.2/css/editor.dataTables.min.css">
    <script src="{$base_url}assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js"></script> -->

    <script src="{$base_url}/themes/dompet/js/plugins-init/datatables.init.js"></script>

    <!-- mustache templating -->
    <script src="{$base_url}assets/mustache/mustache.min.js"></script>

    <!-- toastr toast popup -->
    <script src="{$base_url}assets/jquery-confirm/jquery-confirm.min.js"></script>
    <script src="{$base_url}assets/toastr/toastr.min.js"></script>

    <!--- moment -->
    <script src="{$base_url}assets/moment/moment-with-locales.min.js" defer></script>

    <script>
    var dezSettingsOptions = {};

    (function($) {
        //update the theme setting. must be before dlabnav-init.js
        dezSettingsOptions = {
			typography: "cairo",
			version: "light",
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

    </script>

    <script src="{$base_url}/themes/dompet/js/custom.min.js"></script>
    <script src="{$base_url}/themes/dompet/js/dlabnav-init.js"></script>
	
    <!-- <script>
        "use strict"

        var dezSettingsOptions = {};

        function getUrlParams(dParam) 
        {
            var dPageURL = window.location.search.substring(1),
                dURLVariables = dPageURL.split('&'),
                dParameterName,
                i;

            for (i = 0; i < dURLVariables.length; i++) {
                dParameterName = dURLVariables[i].split('=');

                if (dParameterName[0] === dParam) {
                    return dParameterName[1] === undefined ? true : decodeURIComponent(dParameterName[1]);
                }
            }
        }

        (function($) {           
            "use strict"
            
            dezSettingsOptions = {
                    typography: $("body").attr('data-typography'),
                    version: $("body").attr('data-theme-version'),
                    layout: $("body").attr('data-layout'),
                    primary: $("body").attr('data-primary'),
                    //headerBg: $("body").attr('data-headerbg'),
                    navheaderBg: $("body").attr('data-nav-headerbg'),
                    sidebarBg: $("body").attr('data-sibebarbg'),
                    sidebarStyle: $("body").attr('data-sidebar-style'),
                    sidebarPosition: $("body").attr('data-sidebar-position'),
                    headerPosition: $("body").attr('data-header-position'),
                    containerLayout: $("body").attr('data-container'),
                    direction: $("body").attr('direction'),
                }; 
            //new dezSettings(dezSettingsOptions); 

            jQuery(window).on('resize',function(){
                // /*Check container layout on resize */
                // //alert(dezSettingsOptions.primary);
                dezSettingsOptions.containerLayout = $('#container_layout').val();
                // /*Check container layout on resize END */
                //alert(dezSettingsOptions.containerLayout);
                new dezSettings(dezSettingsOptions); 
            });
            
        })(jQuery);
    </script> -->

    <script>
        function throttle(func, wait, options) {
            var timeout, context, args, result;
            var previous = 0;
            if (!options) options = {};

            var later = function() {
                previous = options.leading === false ? 0 : now();
                timeout = null;
                result = func.apply(context, args);
                if (!timeout) context = args = null;
            };

            var throttled = function() {
                var _now = now();
                if (!previous && options.leading === false) previous = _now;
                var remaining = wait - (_now - previous);
                context = this;
                args = arguments;
                if (remaining <= 0 || remaining > wait) {
                if (timeout) {
                    clearTimeout(timeout);
                    timeout = null;
                }
                previous = _now;
                result = func.apply(context, args);
                if (!timeout) context = args = null;
                } else if (!timeout && options.trailing !== false) {
                timeout = setTimeout(later, remaining);
                }
                return result;
            };

            throttled.cancel = function() {
                clearTimeout(timeout);
                previous = 0;
                timeout = context = args = null;
            };

            return throttled;
        }

        function restArguments(func, startIndex) {
            startIndex = startIndex == null ? func.length - 1 : +startIndex;

            return function() {
                var length = Math.max(arguments.length - startIndex, 0),
                    rest = Array(length),
                    index = 0;
                for (; index < length; index++) {
                    rest[index] = arguments[index + startIndex];
                }
                switch (startIndex) {
                    case 0: return func.call(this, rest);
                    case 1: return func.call(this, arguments[0], rest);
                    case 2: return func.call(this, arguments[0], arguments[1], rest);
                }
                var args = Array(startIndex + 1);
                    for (index = 0; index < startIndex; index++) {
                    args[index] = arguments[index];
                }
                args[startIndex] = rest;
                return func.apply(this, args);
            };
        };

        function now() {
            return new Date().getTime();
        };

        function debounce(func, wait, immediate) {
            var timeout, previous, args, result, context;

            var later = function() {
                var passed = now() - previous;
                if (wait > passed) {
                    // new call while the existing call is executing -> schedule for latter
                    timeout = setTimeout(later, wait - passed);
                } else {
                    timeout = null;
                    if (!immediate) result = func.apply(context, args);
                    if (!timeout) args = context = null;
                }
            };

            var debounced = restArguments(function(_args) {
                context = this;
                args = _args;
                previous = now();
                if (!timeout) {
                    timeout = setTimeout(later, wait);
                    if (immediate) result = func.apply(context, args);
                }
                return result;
            });

            debounced.cancel = function() {
                clearTimeout(timeout);
                timeout = args = context = null;
            };

            return debounced;
        }        
    </script>

    {if $content_template|default: FALSE} 
        {include file="./_$content_template"}
    {/if}

    <script>

        (function($) {           

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            //TODO

        })(jQuery);        
    </script>

</body>
</html>