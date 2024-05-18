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
 
    <!-- icons -->
    <link href="{$base_url}assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="{$base_url}assets/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css" rel="stylesheet" type="text/css" /> -->
    <link href="{$base_url}assets/dripicons/icons.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{$base_url}assets/ionicons/css/ionicons.min.css">

    <!-- toastr toast popup -->
    <link href="{$base_url}assets/jquery-confirm/jquery-confirm.min.css" rel="stylesheet" type="text/css" />
    <link href="{$base_url}assets/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

	<!-- FAVICONS ICON -->
	<link href="{$base_url}/themes/dompet/vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
    <link href="{$base_url}/themes/dompet/css/style.css" rel="stylesheet">

    <!-- <style>

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

            .page-titles {
                display: block;
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
            .page-titles {
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
    </style> -->

    {include file='../_css.tpl'} 

    <!-- <style>
        .table tbody tr td {
            border-color: var(--bs-light);
        }

    </style> -->

</head>

<body data-typography="cairo" data-theme-version="light" data-sidebar-style="compact" data-layout="horizontal" data-nav-headerbg="color_1" 
    data-headerbg="color_1" data-sidebarbg="color_1" data-sidebar-position="fixed" data-header-position="fixed" data-container="boxed" direction="ltr" 
    data-primary="color_1">

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="waviy">
		   <span style="--i:1">L</span>
		   <span style="--i:2">o</span>
		   <span style="--i:3">a</span>
		   <span style="--i:4">d</span>
		   <span style="--i:5">i</span>
		   <span style="--i:6">n</span>
		   <span style="--i:7">g</span>
		   <span style="--i:8">.</span>
		   <span style="--i:9">.</span>
		   <span style="--i:10">.</span>
		</div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


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
                                            <span class="ms-2">Ganti PIN/Password </span>
                                        </a>
                                        <a href="{$site_url}auth/logout" class="dropdown-item ai-icon">
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
                    <li data-bs-toggle="tooltip" data-bs-placement="right" title="Lengkapi Data">
                        <a id="menu-kelengkapan" class="ai-icon nav-link active" href="javascript:void()" aria-expanded="false" 
                                data-bs-toggle="menu" data-bs-target="#content-kelengkapan" type="button" role="tab">
							<i class="flaticon-025-dashboard"></i>
							<span class="nav-text">Lengkapi Data</span>
						</a>
                    </li>
                    <li data-bs-toggle="tooltip" data-bs-placement="right" title="Lakukan Pendaftaran">
                        <a id="menu-pendaftaran" class="ai-icon nav-link" href="javascript:void()" aria-expanded="false" data-bs-toggle="menu" data-bs-target="#content-pendaftaran" type="button" role="tab">
						<i class="flaticon-012-checkmark"></i>
						<span class="nav-text">Lakukan Pendaftaran</span>
						</a>
                    </li>
                    <li data-bs-toggle="tooltip" data-bs-placement="right" title="Cek Pendaftaran">
                        <a id="menu-hasil" class="ai-icon nav-link" href="javascript:void()" aria-expanded="false" data-bs-toggle="menu" data-bs-target="#content-hasil" type="button" role="tab">
							<i class="flaticon-041-graph"></i>
							<span class="nav-text">Cek Pendaftaran</span>
						</a>
                    </li>
                    <li data-bs-toggle="tooltip" data-bs-placement="right" title="Daftar Ulang">
                        <a id="menu-daftarulang" class="ai-icon nav-link" href="javascript:void()" aria-expanded="false" data-bs-toggle="menu" data-bs-target="#content-daftarulang" type="button" role="tab">
							<i class="flaticon-086-star"></i>
							<span class="nav-text">Daftar Ulang</span>
						</a>
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

				<div class="row page-greeting">
					<ol class="breadcrumb">
                        Halo, <b>{$nama_pengguna}</b>
					</ol>
                </div>

                {*Pengumuman*}
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

                {*Pengumuman tahapan*}
                {foreach $tahapan_aktif as $row}
                {if empty($row->notifikasi_siswa)}{continue}{/if}

                <div class="alert alert-info alert-dismissible" role="alert">
                    <p><i class="icon glyphicon glyphicon-info-sign"></i>{$row->tahapan}</p>
                    <p>{$row->notifikasi_umum}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                {/foreach}

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

                {if $profilsiswa['ganti_password'] == 0}
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

                <div class="tab-content" id="menu-contents">
                    <div class="tab-pane fade show active" id="content-kelengkapan" role="tabpanel" aria-labelledby="v-pills-home-tab">

                        {include file='./profil.tpl'}

                    </div>
                    <div class="tab-pane fade" id="content-pendaftaran" role="tabpanel" aria-labelledby="v-pills-profile-tab">

                        {include file='./pendaftaran.tpl'}
                        
                    </div>
                    <div class="tab-pane fade" id="content-hasil" role="tabpanel" aria-labelledby="v-pills-messages-tab">

                        {include file='./daftarpendaftaran.tpl'}                    

                    </div>
                    <div class="tab-pane fade" id="content-daftarulang" role="tabpanel" aria-labelledby="v-pills-settings-tab">

                        {include file='./daftarulang.tpl'}

                    </div>
                </div>

            </div>
            <!-- container ends -->
        </div>
        <!--**********************************
                Content body end
            ***********************************-->

        {if 1==0}
        {include file='pilihsekolah.tpl'}
        {include file='hapuspendaftaran.tpl'}
        {include file='ubahjenispilihan.tpl'}
        {include file='ubahsekolah.tpl'}
        {include file='ubahjalur.tpl'} 
        {/if}

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

    <link rel="stylesheet" href="{$base_url}assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{$base_url}assets/datatables/Select-1.3.1/css/select.dataTables.min.css">
    <link rel="stylesheet" href="{$base_url}assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">
	<link rel="stylesheet" href="{$base_url}assets/datatables/Buttons-1.6.1/css/buttons.dataTables.min.css">

    <script src="{$base_url}assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="{$base_url}assets/datatables/Select-1.3.1/js/dataTables.select.min.js"></script>
    <script src="{$base_url}assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>
	<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js"></script>

    <link rel="stylesheet" href="{$base_url}assets/datatables/Editor-1.9.2/css/editor.dataTables.min.css">
    <script src="{$base_url}assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js"></script>

    <script src="{$base_url}/themes/dompet/js/plugins-init/datatables.init.js"></script>

    <!-- mustache templating -->
    <script src="{$base_url}assets/mustache/mustache.min.js"></script>

    <!-- toastr toast popup -->
    <script src="{$base_url}assets/jquery-confirm/jquery-confirm.min.js"></script>
    <script src="{$base_url}assets/toastr/toastr.min.js"></script>

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


</body>

</html>

<script>

var editor;
var editprestasi;
var dtprestasi;
var dtriwayat;

var files = {$files|json_encode};

(function($) {           

    $('[data-bs-toggle="menu"]').on( "click", function(e) {
        e.preventDefault();
        let dom = $(this);
        
        let contents = $('#menu-contents');
        let target = contents.find(dom.attr("data-bs-target"));
        if (target.length == 0) return;

        let active = dom.hasClass('active');
        if (active) {
            dom.parent().parent().find(".nav-link").each(function(){
                let peer = $(this);
                if (peer.attr('id') != dom.attr('id')) {
                    peer.removeClass('active');
                }
            });
            target.addClass('active').addClass('show');
            contents.find(".tab-pane").each(function(){
                let peer = $(this);
                if (peer.attr('id') != target.attr('id')) {
                    peer.removeClass('active').removeClass('show');
                }
            });
        } 
        else {
            //close all
            contents.find(".tab-pane").removeClass('show').removeClass("active");
            dom.parent().parent().find(".nav-link").removeClass('active');
            //show tab
            target.addClass('active').addClass('show');
            dom.addClass('active');
        }

        //close side-bar
        $('#main-wrapper').removeClass('menu-toggle');
        $('.hamburger').removeClass('is-active');
    } );
    
    //scroll to top
    $("a[href='#top']").click(function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });;
        return false;
    });

    // let width = $(window).width();
    // if (width >= 785 && width < 1041) {
    //     var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    //     var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    //         return new bootstrap.Tooltip(tooltipTriggerEl)
    //     })
    // }

    $('.collapse').on('shown.bs.collapse', function(e) {
        let el = $(this);
        var card = el.closest('.accordion-item');
        var open = $(el.data('parent')).find('.collapse.show');
        let width = $(window).width();

        var additionalOffset = 0;
        if(card.prevAll().filter(open.closest('.accordion-item')).length !== 0)
        {
                additionalOffset = open.height();
        }

        let header_offset = 230;
        if (width < 785) {
            header_offset = 100;
        }
        else if (width < 1040) {
            header_offset = 100;
        }

        $('html,body').animate({
            scrollTop: card.offset().top - additionalOffset - header_offset
        }, 100);

        //resize table
        if (card.prop('id') == 'prestasi') {
            dtprestasi.columns.adjust().responsive.recalc();
        }

        //resize table
        if (card.prop('id') == 'riwayat') {
            dtriwayat.columns.adjust().responsive.recalc();
        }
    });

    $("[tcg-edit-action='submit']").on("change", function(evt) {
        let select = $(this);
        let flagval = parseInt(select.val());
        let submittag = select.attr('tcg-submit-tag');

        let formdata = new FormData();

        let elements = $("[tcg-input-tag='" +submittag+ "']");
        elements.each(function(idx) {
            //action
            el = $(this);
            if (flagval) {
                action = el.attr('tcg-input-true');
            }
            else {
                action = el.attr('tcg-input-false');
            }
            if (action == 'show') el.show();
            else if (action == 'hide') el.hide();
            else if (action == 'enable') el.attr("disabled",false);
            else if (action == 'disable') el.attr("disabled",true);

            //data to submit
            let field = el.attr('tcg-field');
            if (field == null || field == '') return;

            let val = 0;
            if (flagval && field!=null && (el.is('input') || el.is('select'))) {
                if (el.attr('type') == 'number') {
                    val = parseFloat(el.val());
                    if (isNaN(val)) val = 0;
                    val = val.toFixed(2);
                    el.val(val);
                }
                else {
                    val = el.val();
                }

                //form data
                formdata.append(field, val);

                //copy value if necessary
                //lbl = elements.find("span[tcg-field='" +field+ "']");
                lbl = $("span[tcg-field='" +field+ "']");
                lbl.html(val);
            }
        });

        //TODO submit form
        formdata.append("konfirmasi_" +submittag, flagval)

        $.ajax({
            type: "POST",
            url: "{$site_url}ppdb/siswa/updateprofil",
            async: true,
            data: formdata,
            cache: false,
            contentType: false,
            processData: false,
            timeout: 60000,
            dataType: 'json',
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    return;
                }
                //TODO: get the return value and re-set the field
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //TODO
                return;
            }
        });

        konfirmasi[submittag] = flagval;
        if (flagval) {
            verifikasi[submittag] = 1;
        }

        //update status
        var card = select.closest('.accordion-item');
        if (flagval) {
            card.removeClass("status-danger");
            card.find(".accordion-header-text .status").html('');
        }
        else {
            card.addClass("status-danger");
            card.find(".accordion-header-text .status").html('*Belum Benar*');
        }

        //special case
        if (submittag == 'prestasi') {
            if (flagval) {
                dtprestasi.buttons( 0, null ).container().hide();
            }
            else {
                dtprestasi.buttons( 0, null ).container().show();
            }
        }

    })

    $("[tcg-edit-action='toggle']").on("change", function(evt) {
        select = $(this);
        value = parseInt(select.val());
        toggletag = select.attr('tcg-toggle-tag');

        elements = $("[tcg-visible-tag='" +toggletag+ "']");
        if (value) { elements.show(); }
        else { elements.hide(); }

        //update flag
        profilflag[toggletag] = value;
        
        //special case: afirmasi
        if (toggletag == 'kip' || toggletag == 'bdt') {
            elements = $("[tcg-visible-tag='afirmasi']");
            if (!profilflag['kip'] && !profilflag['bdt']) {
                elements.hide();
            }
            else {
                elements.show();
            }
        }

        //special case: prestasi
        if (toggletag == 'prestasi') {
            if (profilflag['prestasi']) {
                dtprestasi.columns.adjust().responsive.recalc();
            }
        }
    });

    //all datatable must be responsive
    $.extend( $.fn.dataTable.defaults, { responsive: true } );

    // //file list
    // $.fn.dataTable.Editor.files[ 'files' ] = files;

    // //editor
    // editor = new $.fn.dataTable.Editor( {
    //     ajax: "<?php echo site_url('siswa/profil/json'); ?>",
    //     fields: [ 
    //         {
    //             label: "Surat Pernyataan Kebenaran Dokumen:",
    //             name: "dokumen_21",
    //             type: "upload",
    //             display: function ( file_id ) {
    //                 if (file_id == "" || typeof files[file_id] === undefined) {
    //                     return "";
    //                 }
    //                 return '<img src="'+editor.file( 'files', file_id ).thumbnail_path+'"/>';
    //             },
    //             clearText: "Hapus",
    //             noImageText: 'No image',
    //             uploadText: "Pilih dokumen...",
    //             noFileText: 'Tidak ada dokumen',
    //             processingText: 'Sedang mengunggah',
    //             fileReadText: 'Membaca dokumen',
    //             dragDropText: 'Seret dan letakkan dokumen di sini untuk mengunggah'
    //         },
    //     ],
    //     i18n: {
    //     create: {
    //         button: "Baru",
    //         title:  "Data siswa baru",
    //         submit: "Simpan"
    //     },
    //     edit: {
    //         button: "Ubah",
    //         title:  "Ubah data siswa",
    //         submit: "Simpan"
    //     },
    //     remove: {
    //         button: "Hapus",
    //         title:  "Hapus data siswa",
    //         submit: "Hapus",
    //         confirm: {
    //             _: "Konfirmasi hapus %d data siswa?",
    //             1: "Konfirmasi hapus 1 data siswa?"
    //         }
    //     },        
    //     error: {
    //         system: "Ada permasalahan dalam menyimpan data. Silahkan hubungi nomor bantuan."
    //     },
    //     datetime: {
    //         previous: 'Sebelum',
    //         next:     'Selanjutnya',
    //         months:   [ 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember' ],
    //         weekdays: [ 'Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab' ],
    //         hour: 'Jam',
    //         minute: 'Menit'
    //     }
    //     }
    // });

    editprestasi = new $.fn.dataTable.Editor( {
        ajax: "{$site_url}ppdb/siswa/prestasi",
        table: '#tbl-prestasi',
        idSrc: "prestasi_id",
        fields: [ 
            {
                label: "Prestasi:",
                name: "skoring_id",
                type: "select",
            }, {
                label: "Uraian:",
                name: "uraian",
                type: "textarea",
            {if ($flag_upload_dokumen)}
            }, {
                label: "Dokumen Pendukung:",
                name: "dokumen_pendukung",
                type: "upload",
                display: function ( file_id ) {
                    if (file_id == "" || typeof files[file_id] === undefined) {
                        return "";
                    }
                    return '<img src="'+editprestasi.file( 'files', file_id ).thumbnail_path+'"/>';
                },
                clearText: "Hapus",
                noImageText: 'No image'
            {/if}
            }
        ],
        i18n: {
        create: {
            button: "Baru",
            title:  "Tambah data prestasi",
            submit: "Simpan"
        },
        edit: {
            button: "Ubah",
            title:  "Ubah prestasi",
            submit: "Simpan"
        },
        remove: {
            button: "Hapus",
            title:  "Hapus prestasi",
            submit: "Hapus",
            confirm: {
                _: "Konfirmasi hapus %d prestasi?",
                1: "Konfirmasi hapus 1 prestasi?"
            }
        },        
        error: {
            system: "Ada permasalahan dalam menyimpan data. Silahkan hubungi nomor bantuan."
        },
        datetime: {
            previous: 'Sebelum',
            next:     'Selanjutnya',
            months:   [ 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember' ],
            weekdays: [ 'Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab' ],
            hour: 'Jam',
            minute: 'Menit'
        }
        }
    });

    editprestasi.on('preSubmit', function(e, o, action) {
        if (action === 'create' || action === 'edit') {
            let field = null;

            field = this.field('skoring_id');
            if (!field.isMultiValue()) {
                if (!field.val() || field.val() == 0) {
                    field.error('Prestasi harus diisi');
                }
            }

            field = this.field('uraian');
            if (!field.isMultiValue()) {
                if (!field.val() || field.val() == 0) {
                    field.error('Uraian harus diisi');
                }
            }

            {if ($flag_upload_dokumen)}
            field = this.field('dokumen_pendukung');
            if (!field.isMultiValue()) {
                if (!field.val() || field.val() == 0) {
                    field.error('Dokumen pendukung harus diisi');
                }
            }
            {/if}

            /* If any error was reported, cancel the submission so it can be corrected */
            if (this.inError()) {
                this.error('Data wajib belum diisi');
                return false;
            }
        }            
    });        

    dtprestasi = $('#tbl-prestasi').DataTable({
        "responsive": true,
        "paging": false,
        "dom": "Brt",
        select: true,
        buttons: [
            { extend: "create", editor: editprestasi, className: "btn btn-primary" },
            // { extend: "edit", editor: editprestasi, className: "dt-button d-none" },
            { extend: "remove", editor: editprestasi, className: "btn btn-danger" },
        ],
        ajax: "{$site_url}ppdb/siswa/prestasi",
        "language": {
            "processing":   "Sedang proses...",
            "lengthMenu":   "Tampilan _MENU_ entri",
            "zeroRecords":  "Tidak ditemukan data yang sesuai",
            "loadingRecords": "Loading...",
            "emptyTable":   "Tidak ditemukan data yang sesuai",
            },
        columns: [
            // { "defaultContent": "" },
            { data: "prestasi_id", className: 'dt-body-center', "orderable": false },
            { data: "prestasi", className: 'dt-body-left', "orderable": false },
            { data: "uraian", className: 'dt-body-left', "orderable": false },
            { data: "dokumen_pendukung", className: 'dt-body-left editable', "orderable": false,
                render: function ( file_id, type, row ) {
                    {if !($flag_upload_dokumen)}
                    return "Dicocokkan di sekolah tujuan";
                    {else}
                    if (type === 'display') {
                        if (typeof editprestasi.file( 'files', file_id ) === "undefined") {
                            return "";
                        }
                        
                        let str= '<img class="img-view-thumbnail" src="'+editprestasi.file( 'files', file_id ).thumbnail_path+'" img-title="'+row.uraian+'" img-path="'+editprestasi.file( 'files', file_id ).web_path+'"/>';

                        let verifikasi = row.verifikasi;
                        if (verifikasi == 2) {
                            str += ' <button class="img-view-button editable-prestasi" data-editor-field="dokumen_pendukung" data-editor-value="' +file_id+ '" data-editor-id="' +row.prestasi_id+ '" >Unggah</button>';
                            str += '<div class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px;">' +row.catatan+ '</div>'
                        }

                        return str;
                    }
                    else {
                        row.filename;
                    }
                    {/if}
                },
                defaultContent: "Tidak ada dokumen",
                title: "Dokumen Pendukung"
            },
            { data: "catatan", className: 'dt-body-left', width: '20%', "orderable": false },
        ],
        "columnDefs": [ {
            "targets": 3,
            "createdCell": function (td, cellData, rowData, row, col) {
                if ( rowData.verifikasi == 1 ) {
                    $(td).removeClass('editable');
                }
            }
        } ],
        order: [ 0, 'asc' ],
    });

    dtprestasi.buttons( 0, null ).container().find(".dt-button").removeClass("dt-button").addClass('btn');

    //default: hide button
    dtprestasi.buttons( 0, null ).container().hide();

    dtriwayat = $('#triwayat').DataTable({
        "responsive": true,
        "paging": false,
        "dom": 't',
        "buttons": [
        ],
        "language": {
            "sProcessing":   "Sedang proses...",
            "sLengthMenu":   "Tampilan _MENU_ entri",
            "sZeroRecords":  "Tidak ditemukan data yang sesuai",
            "sInfo":         "Tampilan _START_ - _END_ dari _TOTAL_ entri",
            "sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 entri",
            "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
            "sInfoPostFix":  "",
            "sSearch":       "Cari:",
            "sUrl":          "",
            "oPaginate": {
                "sFirst":    "Awal",
                "sPrevious": "Balik",
                "sNext":     "Lanjut",
                "sLast":     "Akhir"
            }
        },	
        ajax: "{$site_url}ppdb/siswa/riwayat",
        columns: [
            { data: "created_on", className: 'dt-body-center readonly-column', orderable: true },
            { data: "nama", className: 'dt-body-left readonly-column', orderable: false },
            { data: "verifikasi", className: 'dt-body-center', orderable: false, 
                "render": function (val, type, row) {
                        return val == 1 ? "SUDAH Lengkap" : "BELUM Lengkap";
                    } 
            },
            { data: "catatan_kekurangan", className: 'dt-body-left readonly-column', width: "50%", orderable: false,
                "render": function (val, type, row) {
                        return val.replace(/:/g, " : ").replace(/;/g, "<br>");
                    } 
            },
        ],
        order: [ 0, 'desc' ],
    });

    $('.upload-file').change(function() {
        let el = $(this);
        let docid = el.attr('tcg-doc-id');
        let files = el.prop('files');

        if (files.length == 0) {
            alert ("Belum memilih file")
            return;
        }

        let file = files[0];
        alert (docid + ":" + file.name);

        // add assoc key values, this will be posts values
        var formData = new FormData();
        formData.append("upload", file, file.name);
        formData.append("action", "upload");

        //TODO
        // $.ajax({
        //     type: "POST",
        //     url: "",
        //     async: true,
        //     data: formData,
        //     cache: false,
        //     contentType: false,
        //     processData: false,
        //     timeout: 60000,
        //     dataType: 'json',
        //     success: function(json) {
        //         if (json.error !== undefined && json.error != "" && json.error != null) {
        //             return;
        //         }

        //         //TODO
        //     },
        //     error: function(jqXHR, textStatus, errorThrown) {

        //         return;
        //     }
        // });

        
    });

    update_profile_layout();
    update_pendaftaran_layout();
    update_hasil_layout();

})(jQuery);        
</script>

