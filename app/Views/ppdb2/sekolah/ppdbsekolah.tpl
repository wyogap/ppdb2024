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
    
    <style>

        .header {
            padding: 0px;
            width: 100% !important;
            max-width: 1199px;
        }

        .header .header-content {
            padding-left: 0px !important;
            padding-right: 0px !important;
        }
        
        .header-profile {
            /* padding: 0 15px; */
            -webkit-transition: all 0.5s;
            -ms-transition: all 0.5s;
            transition: all 0.5s;
            margin-top: 5.5px;
        }

        .header-profile > a.nav-link {
            border: 1px solid #f5f5f5;
            border-radius: 1rem;
            padding: 10px 15px !important;
            display: flex;
            align-items: center;
            background-color: var(--sidebar-bg);
            transition: all .2s ease;
            box-shadow: 0px 15px 30px 0px rgba(0, 0, 0, 0.02);
        }

        .header-profile > a {
            font-weight: 400;
            display: inline-block;
            font-size: 18px;
            color: #9fa4a6;
            position: relative;
            padding: 0.625rem 1.875rem;
            outline-width: 0;
            text-decoration: none;
        }

        .header-profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .header-profile > a.nav-link .header-info {
            /* padding-left: 10px; */
            text-align: left;
        }

        .header-profile > a.nav-link .header-info span {
            font-size: 16px;
            color: #000;
            display: block;
            margin-bottom: 5px;
            margin-top: -5px;
            font-weight: 600;
        }

        .header-profile > a.nav-link .header-info small {
            display: block;
            font-size: 12px;
            color: #89879f;
            font-weight: 400;
            line-height: 1.2;
        }

        /* .header-profile > .dropdown-menu {
            position: static !important;
        } */

        .content-body > .container-fluid {
            padding-top: 16px !important;
        }

        /* .dlabnav {
            left: 0px !important;
            padding-left: 40px !important;
            padding-right: 40px !important;
            height: 100% !important;
            width: 100% !important;
            max-width: 1199px !important;
        } */

        .header {
            right: 0px !important;
            padding-left: 40px !important;
            padding-right: 40px !important;
            /* height: 100% !important; */
            width: 100% !important;
            max-width: 1199px !important;
        }

        .nav-header {
            left: 0px !important;
            padding-left: 40px !important;
            padding-right: 40px !important;
            height: 104px !important;
            width: 75% !important;
            max-width: 800px !important;
        }

        .accordion-header {
            border-radius: 1rem;
            
        }

        .accordion-header-bg .accordion-header {
            background-color: white;
        }

        .accordion-header.collapsed {
            border-radius: 1rem;
        }

        .accordion-primary-solid .accordion-header.collapsed, 
        .accordion-bordered .accordion-header.collapsed {
            border-radius: 1rem;
        }        

        .accordion-bordered .accordion__body {
            border: 1px solid #f5f5f5;
            border-top: none;
            border-bottom-left-radius: 1rem;
            border-bottom-right-radius: 1rem;
        }        

        .accordion__body {
            background-color: white;
        }

        .accordion-header-text {
            font-size: 20px;
            font-weight: 500;
        }

        .ai-icon {
            color: rgb(150, 155, 160);
            background-color: #fff;
            border-radius: 16px;
        }

        .ai-icon.active {
            color: #fff !important;
            background-color: #5BCFC5 !important;
            border-radius: 16px;
        }

        .ai-icon.active i {
            color: #fff !important;
            background-color: #5BCFC5 !important;
        }

        [data-sidebar-position="fixed"][data-layout="vertical"] .dlabnav .dlabnav-scroll {
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .nav-header .brand-logo img {
            width: 64px;
            height: 64px;
        }

        .header-profile .dropdown-menu {
            /* width: 250px; 
            z-index: 1035;   */
            left: inherit;
            right: 0px;
        }

        .accordion__body .box-footer {
            border-top: solid var(--primary-hover);
            padding-top: 16px;
            margin-top: 16px;
        }

        table.dokumen-pendukung {
            margin-top: 24px;
        }

        table.dokumen-pendukung tbody tr:first-child {
            background-color: var(--primary) !important;
        }

        table.dokumen-pendukung tbody tr:first-child td {
            color: #fff;
            border-top: 1px solid var(--primary-hover); 
            border-bottom: 1px solid var(--primary-hover);
        }

        .accordion__body .form-control {
            display: inline-block;
            width: auto;
            border-color: var(--primary-hover);
        }

        .accordion-header .status {
            display: block;
            font-size: 12px;
        }

        .accordion-primary-solid .accordion-item.status-danger .accordion-header {
            background: #f72b50 !important;
            border-color: #f72b50 !important;
            color: #fff;
        }

        .accordion-primary-solid .accordion-item.status-danger .accordion-header.collapsed {
            background: #fee6ea !important;
            border-color: #fee6ea !important;
            color: rgb(33, 28, 55);
        }

        .accordion-primary-solid .accordion-item.status-danger .accordion__body {
            border-color: #f72b50 !important;
        }

        @media only screen and (max-width: 784px) {
            /* .header .header-content {
                padding-left: 25px !important;
                padding-right: 25px !important;
            } */

            .header-profile .dropdown-menu {
                margin-top: -8px;
            }

            .page-greeting {
                display: block;
                margin-left: 0;
                margin-right: 0;
                margin-bottom: 30px;
                padding: 15px 20px;
                margin-top: -15px;
                border-radius: 16px;
                background-color: #fff;
            }

            .nav-header .brand-logo {
                display: none;
            }

            .header-profile img {
                width: 40px;
                height: 40px;
                border-radius: 50%;
            }

            .header-profile > a.nav-link .header-info {
                display: none;
            }

            .navbar-expand .navbar-nav .nav-link {
                width: 46px;
                height: 46px;
                border-radius: 50%;
                border: 1px solid #000000;
                padding: 2px !important;
                /* background-color: var(--primary); */
            }

            /* .dlabnav {
                padding-left: 15px !important;
                padding-right: 15px !important;
            } */

            .header {
                padding-left: 70px !important;
                padding-right: 20px !important;
            }

            .nav-header {
                padding-left: 15px !important;
                padding-right: 15px !important;
                top: 0px;
                background-color: transparent;
                height: 5rem !important;
            }

            .nav-control {
                position: absolute;
                right: unset !important;
                left: 20px !important;
                top: 48px !important;
            }

            /* .header-left {
                margin-top: 10px;
            } */

            .header-left .app-name-short {
                display: block !important;
                color: #000000;
                font-size: 34px;
                margin-top: 10px;
            }

            .header-left .app-name-long {
                display: none;
            }

        }

        @media only screen and (min-width: 785px) {
            .page-greeting {
                display: none;
            }

            .nav-header {
                width: 6.25rem !important;
                left: 1.25rem !important;
                top: 0.75rem;
                /* top: 0px;
                background-color: transparent; */
            }

            .header {
                padding-left: 150px !important;
                padding-right: 30px !important;
            }

            .header-left .app-name-short {
                display: block !important;
                color: #000000;
                font-size: 38px;
                font-weight: 600;
                margin-top: 10px;
            }

            .header-left .app-name-long {
                display: none !important;
            }
        }            

        @media only screen and (min-width: 768px) {

            /* .header {
                padding-left: 150px !important;
                padding-right: 30px !important;            
            } */

            .nav-header {
                padding-left: 30px !important;
                padding-right: 30px !important;
            }

            .nav-control {
                left: 35px !important;
            }
        }

        @media (min-width: 1041px) {
            .nav-header {
                width: 75% !important;
                max-width: 800px !important;
                left: 0px !important;
                padding-left: 40px !important;
            }

            .nav-header .brand-logo {
                padding-left: 0px !important;
                padding-right: 0px !important;
                margin-top: 4px; 
            }

            .header {
                padding-left: 40px !important;
                padding-right: 40px !important;
            }

            .header .header-content {
                padding-left: 80px !important;
            }

            .header-left .app-name-short {
                display: none !important;
            }

            .header-left .app-name-long {
                display: block !important;
                color: #000000;
                font-size: 38px;
                font-weight: 600;
                margin-top: 10px;
            }
        }

        @media (min-width: 1200px) {
            .dlabnav {
                width: 1199px !important;
                left: calc((100% - 1199px) / 2) !important;
            }

            .nav-header {
                width: auto !important;
                left: calc((100% - 1199px) / 2) !important;
                padding-left: 40px !important;
            }

            .header {
                width: 1199px !important;
                right: calc((100% - 1199px) / 2) !important;
                padding-right: 40px !important;
            }
        }
    </style>

    <style>
        .custom-tab-1 {
            background-color: #fff;
            border-radius: 16px;
            padding-top: 16px;
            padding-bottom: 16px;
        }

        .custom-tab-1 .tab-pane {
            padding-left: 30px;
            padding-right: 30px;
            /* padding-top: 16px; */
            /* padding-bottom: 16px; */
        }

        .badge {
            border-radius: 8px !important;
        }

        table.dataTable tbody td.bg-gray {
            color: #000;
            background-color: #d2d6de !important;
        }

        table.dataTable tbody td.bg-red {
            color: #fff;
            background-color: red !important;
        }

        table.dataTable tbody td.bg-orange {
            color: #fff;
            background-color: orange !important;
        }

        table.dataTable tbody td.bg-green {
            color: #fff;
            background-color: #00a65a !important;
        }

        table.dataTable tbody td.bg-yellow {
            color: #000;
            background-color: yellow !important;
        }
    </style>
</head>

<body data-typography="cairo" data-theme-version="light" data-sidebar-style="compact" data-layout="horizontal" data-nav-headerbg="color_1" 
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
                                PPDB{$tahun_ajaran_id}
                            </div>
                            <div class="app-name-long dashboard_bar">
                                PPDB{$nama_tahun_ajaran} {$nama_putaran}
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown header-profile">
                                    <a class="nav-link" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="{$base_url}assets/image/user.png" width="20" alt=""/>
                                        <div class="header-info ms-3">
                                            <span class="font-w600 ">Halo,<b>{$nama_pengguna}</b></span>
                                            <small class="font-w400">{$username}</small>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="./app-profile.html" class="dropdown-item ai-icon">
                                            <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                            <span class="ms-2">Profile </span>
                                        </a>
                                        <a href="./email-inbox.html" class="dropdown-item ai-icon">
                                            <svg id="icon-inbox" xmlns="http://www.w3.org/2000/svg" class="text-success" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                            <span class="ms-2">Inbox </span>
                                        </a>
                                        <a href="./login.html" class="dropdown-item ai-icon">
                                            <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                            <span class="ms-2">Logout </span>
                                        </a>
                                    </div>
                            </li>

                            <!-- <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> 
                                    <img src="https://framework.jejakku.id/assets/image/user.png" class="user-image img-circle elevation-2" alt="User Image" style="width: ">
                                    <div class="header-info ms-3">
                                        <span class="font-w600 ">Halo,<b>Wahyu Yoga Pratama</b></span>
                                        <small class="font-w400">william@gmail.com</small>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" style="width: 250px; z-index: 1035; left: inherit; right: 0px;">
                                    <a href="./app-profile.html" class="dropdown-item ai-icon">
                                        <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        <span class="ms-2">Profile </span>
                                    </a>
                                    <a href="./email-inbox.html" class="dropdown-item ai-icon">
                                        <svg id="icon-inbox" xmlns="http://www.w3.org/2000/svg" class="text-success" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                        <span class="ms-2">Inbox </span>
                                    </a>
                                    <a href="./login.html" class="dropdown-item ai-icon">
                                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                        <span class="ms-2">Logout </span>
                                    </a>
                                </div>
                            </li> -->
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
                        <a id="menu-kelengkapan" class="ai-icon" href="{$site_url}sekolah/beranda" aria-expanded="false" >
							<i class="flaticon-025-dashboard"></i>
							<span class="nav-text">Beranda</span>
						</a>
                    </li>
                    <li data-bs-toggle="tooltip" data-bs-placement="right" title="Peringkat">
                        <a id="menu-pendaftaran" class="ai-icon" href="{$site_url}sekolah/peringkat" aria-expanded="false" >
						<i class="flaticon-043-menu"></i>
						<span class="nav-text">Peringkat</span>
						</a>
                    </li>
                    <li data-bs-toggle="tooltip" data-bs-placement="right" title="Verifikasi">
                        <a id="menu-hasil" class="ai-icon nav-link" href="{$site_url}sekolah/verifikasi" aria-expanded="false" >
							<i class="flaticon-012-checkmark"></i>
							<span class="nav-text">Verifikasi</span>
						</a>
                    </li>
                    <li data-bs-toggle="tooltip" data-bs-placement="right" title="Daftar Ulang">
                        <a id="menu-daftarulang" class="ai-icon" href="{$site_url}sekolah/daftarulang" aria-expanded="false" >
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
                            <li><a href="{$site_url}sekolah/ubahprofil">Ubah Profil Sekolah</a></li>
                            <li><a href="{$site_url}sekolah/pengajuanakun">Pengajuan Akun</a></li>
                            <li><a href="{$site_url}sekolah/pencarian">Pencarian Siswa</a></li>
                            <li><a href="{$site_url}sekolah/kandidatswasta">Kandidat Siswa</a></li>
                            <li><a href="{$site_url}sekolah/berkasdisekolah">Berkas Di Sekolah</a></li>
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