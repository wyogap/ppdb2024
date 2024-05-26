{include file="../header.tpl"}

<style>
    .home .content-body {
        padding-top: 6rem !important;
    }

    /* .home .header {
        padding-left: 0px !important; 
        padding-right: 0px !important;
    } */

    .home .nav-header {
        /* background-color: var(--headerbg) !important; */
        margin-top: 0px !important;
        width: 6.25rem !important;
    }

    .home .nav-header .brand-logo {
        margin-top: 16px;
    }

    .home .header-left {
        padding-left: 80px;;
    }

    .home .header-right .nav-item {
        height: 80px;
    }

    .home .header-right .nav-item .nav-link {
        font-size: 24px;
    }

    [data-theme-version="dark"] .hamburger .line {
        background: var(--bs-white);
    }

    .home .hamburger {
        top: 0px; 
    }


    @media only screen and (max-width: 768px) {
        [data-theme-version="dark"] .hamburger .line {
            background: var(--bs-white) !important;
        }

        .home .header {
            padding-left: 20px !important; 
            padding-right: 20px !important;
        }

        .home .header-right .nav-item {
            height: auto;
        }

        .home .nav-header {
            /* background-color: transparent !important; */
            display: none;
        }

        .home .header-profile > a.nav-link {
            border: 1px solid #f5f5f5;
            border-radius: 1rem;
            padding: 10px 10px !important;
            display: flex;
            align-items: center;
            background-color: var(--sidebar-bg);
            transition: all .2s ease;
            box-shadow: 0px 15px 30px 0px rgba(0, 0, 0, 0.02);
        }

        .home .header-left {
            padding-left: 16px;
        }

        .home .content-body {
            padding-top: 5rem !important;
        }
    }        

    @media only screen and (min-width: 768px) {
        .home .content-body {
            margin-left: 0 !important;
        }

        .home .nav-header {
            top: 0px !important;
            height: 80px !important;
        }

        .home .nav-header {
            background-color: transparent !important;
            display: block;
        }

        .home .header-left {
            padding-top: 20px;
        }

        .home .app-name-short {
            display: none !important;
        } 

        .home .app-name-long {
            display: block !important;
        } 
    }

    /* @media only screen and (min-width: 992px) { */
    @media only screen and (min-width: 1023px) {
        .home .header-left {
            padding-top: 0px;
        }
        
        /* .home .nav-header {
            top: 0px !important;
        } */
    }

    @media only screen and (min-width: 1200px) {
        /* .home .header-right {
            right: calc((100% - 1199px) / 2) !important;
            padding-right: 40px
        } */
    }
</style>

<body class="home" data-typography="cairo" data-theme-version="dark" data-sidebar-style="compact" data-layout="horizontal" data-nav-headerbg="color_1" 
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
                                <div class="hamburger">
                                    <span class="line"></span><span class="line"></span><span class="line"></span>
                                </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <button onclick=toggle_dark_mode() class="dropdown-item ai-icon" id="toggle-dark">
                                        <i class="fas fa-moon"></i> <span class="ms-2">Mode Gelap</span>
                                    </button>
                                    <button onclick=toggle_dark_mode() class="dropdown-item ai-icon" id="toggle-light">
                                        <i class="fas fa-sun"></i> <span class="ms-2">Mode Terang </span>
                                    </button>
                                    <a href="{$site_url}home/rekapitulasi" class="dropdown-item ai-icon">
                                        <i class="fas fa-table"></i> <span class="ms-2">Rekapitulasi Sekolah </span>
                                    </a>
                                    <a href="{$site_url}auth/login" class="dropdown-item ai-icon">
                                        <i class="fas fa-sign-in-alt"></i> <span class="ms-2">Login </span>
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

        <!--**********************************
            Sidebar end
        ***********************************-->



        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- container starts -->
            <div class="container-fluid">

                <!-- <div class="loading" id="loader">
                <div class="loading-circle"></div>
                </div> -->

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

        <!--**********************************
            Footer end
        ***********************************-->

        
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->
</body>

{include file="../footer.tpl"}


{if $js_template|default: FALSE} 
    {include file="./$js_template"}
{/if}

{if $page=='detailpendaftaran'} 
<script>
    var daftarpendaftaran = {$daftarpendaftaran|json_encode};
    var kelengkapan_data = {$kelengkapan_data};
    var profildikunci = 1;
    var pendaftarandikunci = 1;

    $(document).ready(function() {
        update_hasil_layout();
    })

</script>
{/if}