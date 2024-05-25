{include file="../header.tpl"}

<style>
    .home .content-body {
        padding-top: 6rem !important;
    }

    .home .header {
        padding-left: 0px !important; 
        padding-right: 0px !important;
    }

    .home .nav-header {
        /* background-color: var(--headerbg) !important; */
        margin-top: 8px !important;
        width: 6.25rem !important;
    }

    .home .header-left {
        padding-left: 120px;;
    }

    .home .header-right {
        position: fixed;
        /* display: inline-grid; */
        top: 12px;
        height: auto;
        right: 30px;
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

        .home .header-right .nav-item {
            height: auto;
        }

        .home .nav-header {
            /* background-color: transparent !important; */
            display: none;
        }

        .home .header-right {
            right: 0px
        }

        /* .home .nav-header {
            margin-top: 0px !important;
            width: 5rem !important;
        } */

        .home .header-left {
            padding-left: 24px;
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
            top: 8px !important;
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

    @media only screen and (min-width: 992px) {
        .home .header-left {
            padding-top: 0px;
        }

        .home .nav-header {
            top: 0px !important;
        }
    }

    @media only screen and (min-width: 1200px) {
        .home .header-right {
            right: calc((100% - 1199px) / 2) !important;
            padding-right: 40px
        }
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
        <!--**********************************
            Nav header end
        ***********************************-->
				
		
        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
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
                <!-- <li class="nav-item dropdown header-profile">
                    <a class="nav-link" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{$base_url}assets/image/user.png" width="20" alt=""/>
                        <div class="header-info ms-3">
                            <span class="font-w600 ">Halo, <b>{$nama_pengguna}</b></span>
                            <small class="font-w400">{$user_name}</small>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <button onclick=toggle_dark_mode() class="dropdown-item ai-icon">
                            <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <span class="ms-2">Mode Gelap / Mode Terang </span>
                        </button>
                        <button onclick=ganti_password() class="dropdown-item ai-icon">
                            <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <span class="ms-2">Ganti PIN/Password </span>
                        </button>
                        <a href="{$site_url}auth/logout" class="dropdown-item ai-icon">
                            <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                            <span class="ms-2">Logout </span>
                        </a>
                    </div>
                </li>
 -->
                <li class="nav-item dropdown header-profile">
                    <a class="nav-link" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="hamburger">
                        <span class="line"></span><span class="line"></span><span class="line"></span>
                    </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <button onclick=toggle_dark_mode() class="dropdown-item ai-icon">
                            <i class="fa fa-moon"></i> <span class="ms-2">Mode Gelap / Mode Terang </span>
                        </button>
                        <a href="{$site_url}home/rekapitulasi" class="dropdown-item ai-icon">
                            <i class="fa fa-table"></i> <span class="ms-2">Rekapitulasi Sekolah </span>
                        </a>
                        <a href="{$site_url}auth/login" class="dropdown-item ai-icon">
                            <i class="fas fa-sign-in-alt"></i> <span class="ms-2">Login </span>
                        </a>
                    </div>
                </li>
            </ul>
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

<script>
    var userid = {$user_id|default: 0};  
    
    function ganti_password() {
        $.confirm({
            title: 'Ganti PIN/Password',
            content: "<div style='overflow: hidden;'><input type='password' class='form-control' placeholder='PIN / Password Baru' id='password' name='password' data-validation='required'>"
                        +"<input type='password' class='form-control' placeholder='Masukkan Lagi' id='password2' name='password2' data-validation='required'>"
                        +"<span id='error-msg'>&nbsp</span></div>",
            closeIcon: true,
            columnClass: 'medium',
            //type: 'purple',
            typeAnimated: true,
            buttons: {
                cancel: {
                    text: 'Batal',
                    action: function(){
                        //do nothing
                    }
                },
                confirm: {
                    text: 'Ganti',
                    btnClass: 'btn-primary',
                    action: function(){
                        let el1 = this.$content.find('#password');
                        let el2 = this.$content.find('#password2');
                        if (el1.val().length < 6) {
                            let msg = this.$content.find('#error-msg');
                            msg.html("PIN/Password harus minimal 6 huruf.");
                            el1.addClass('border-red');
                            return false;
                        }
                        else if (el1.val() != el2.val()) {
                            let msg = this.$content.find('#error-msg');
                            msg.html("PIN/Password baru tidak sama.");
                            el2.addClass('border-red');
                            return false;
                        }

                        send_ganti_password(el1.val());
                    }
                },
            },

        });      
    }

    function send_ganti_password(pwd1) {
        json = {};
        data = {};
        data['pwd1'] = pwd1;
        data['pwd2'] = pwd1;
        
        json['data'] = {};
        json['data'][userid] = data;

        $.ajax({
            type: 'POST',
            url: "{$site_url}auth/changepassword",
            dataType: 'json',
            data: json,
            async: true,
            cache: false,
            //if we use formData, set processData = false. if we use json, set processData = true!
            //contentType: true,
            //processData: true,      
            timeout: 60000,
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error('Tidak berhasil mengubah PIN/Password. ' +json.error);
                    return;
                }

                $("#ganti-password-notif").hide();
                
                //tambahkan ke daftar pendaftaran
                toastr.success("PIN/Password berhasil diubah.");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error('Tidak berhasil mengubah PIN/Password. ' +textStatus);
                return;
            }
        });

    }

</script>

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