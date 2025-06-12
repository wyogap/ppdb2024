{include file="../header.tpl"}

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
        color: #000 !important;
        background-color: #d2d6de !important;
    }

    table.dataTable tbody td.bg-red {
        color: #fff !important;
        background-color: red !important;
    }

    table.dataTable tbody td.bg-orange {
        color: #fff !important;
        background-color: orange !important;
    }

    table.dataTable tbody td.bg-green {
        color: #fff !important;
        background-color: #00a65a !important;
    }

    table.dataTable tbody td.bg-yellow {
        color: #000 !important;
        background-color: yellow !important;
    }

    table.dataTable.display tbody>tr.odd.selected>.sorting_1, table.dataTable.order-column.stripe tbody>tr.odd.selected>.sorting_1,
    table.dataTable.display tbody>tr.odd.selected>.sorting_2, table.dataTable.order-column.stripe tbody>tr.odd.selected>.sorting_2,
    table.dataTable.display tbody>tr.odd.selected>.sorting_3, table.dataTable.order-column.stripe tbody>tr.odd.selected>.sorting_3 {
        background-color: inherit;
    }

    table.dataTable.display tbody>tr.even.selected>.sorting_1, table.dataTable.order-column.stripe tbody>tr.even.selected>.sorting_1,
    table.dataTable.display tbody>tr.even.selected>.sorting_2, table.dataTable.order-column.stripe tbody>tr.even.selected>.sorting_2,
    table.dataTable.display tbody>tr.even.selected>.sorting_3, table.dataTable.order-column.stripe tbody>tr.even.selected>.sorting_3 {
        background-color: inherit;
    }

    [data-theme-version="dark"] .select2-container--default .select2-results>.select2-results__options
    {
        background-color: #ffffff;
        border: 2px solid black;
    }

    [data-theme-version="dark"] .select2-search--dropdown  {
        background: var(--bs-white);
        border-left: 2px solid black;
        border-right: 2px solid black;
    }

    [data-theme-version="dark"] .select2-search--dropdown.select2-dropdown--above {
        border-top: 2px solid black;
    }

    [data-theme-version="dark"] .select2-search--dropdown.select2-dropdown--below {
        border-bottom: 2px solid black;
    }

    [data-theme-version="dark"] .select2-search--dropdown .select2-search__field {
        background: var(--bs-white);
        border-color: var(--bs-dark);
        color: #999;;
    }
</style>

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
            <a href="{$site_url}" class="brand-logo"> 
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
                                {if $show_putaran}
                                <span class="app-desc">Tahun {$nama_tahun_ajaran} {$nama_putaran}</span>
                                {else}
                                <span class="app-desc">Tahun {$nama_tahun_ajaran}</span>
                                {/if}
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
                                        <button onclick=toggle_dark_mode() class="dropdown-item ai-icon" id="toggle-dark">
                                            <i class="fas fa-moon"></i> <span class="ms-2">Mode Gelap</span>
                                        </button>
                                        <button onclick=toggle_dark_mode() class="dropdown-item ai-icon" id="toggle-light">
                                            <i class="fas fa-sun"></i> <span class="ms-2">Mode Terang </span>
                                        </button>
                                        <button onclick=ganti_password() class="dropdown-item ai-icon">
                                            <i class="fas fa-user"></i> <span class="ms-2">Ganti PIN/Password </span>
                                        </button>
                                        <a href="{$site_url}auth/logout" class="dropdown-item ai-icon">
                                            <i class="fas fa-sign-out-alt"></i> <span class="ms-2">Logout </span>
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
                    <li data-bs-toggle="tooltip" data-bs-placement="right" title="Daftar Siswa">
                        <a id="menu-pendaftaran" class="ai-icon" href="{$site_url}ppdb/dapodik/daftarsiswa" aria-expanded="false" >
						<i class="flaticon-043-menu"></i>
						<span class="nav-text">Daftar Siswa</span>
						</a>
                    </li>
                    <li data-bs-toggle="tooltip" data-bs-placement="right" title="Penerimaan">
                        <a id="menu-hasil" class="ai-icon" href="{$site_url}ppdb/dapodik/penerimaan" aria-expanded="false" >
							<i class="flaticon-012-checkmark"></i>
							<span class="nav-text">Penerimaan</span>
						</a>
                    </li>
                </ul>
				<div class="copyright">
                    <p><strong>Copyright &copy; 2020 <a href="javascript:void(0)">{$nama_wilayah}</a>.</strong> All rights reserved.</p>
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

                <!-- <div class="loading" id="loader">
                    <div class="loading-circle"></div>
                </div> -->

                {if $content_template|default: FALSE} 
                    {include file="./$content_template"}
                {/if}
            </div>
            <!-- container ends -->
                 
            {if $show_footer|default: TRUE}
            <footer class="main-footer footer mb-2">
                <div class="container text-center">
                        <strong>Copyright &copy; 2020 <a href="javascript:void(0)">{$nama_wilayah}</a>.</strong> All rights reserved.
                </div>
            </footer>
            {/if}

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