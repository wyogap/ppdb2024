{include file="../header.tpl"}

<style>
    .select2-container--default .select2-selection--single {
        border-color: var(--primary-hover);
    }

    .select2-container {
        margin-bottom: 0px !important; 
    }

    .select2-container--default .select2-selection--single {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis; 
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-right: 12px !important;
        white-space: normal;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #6e6e6e;
    }

    .select2-container--default .select2-dropdown.select2-dropdown--below {
        padding-bottom: 12px;
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
    }

    .select2-container--default .select2-dropdown.select2-dropdown--above {
        padding-top: 12px;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    textarea.form-control {
        padding: 8px 16px;
        color: #6e6e6e;
    }

    [data-theme-version="dark"] .select2-container--default .select2-selection--single {
        border-color: var(--primary-hover);
    }

    [data-theme-version="dark"] textarea.form-control {
        color: #fff;
    }

    [data-theme-version="dark"] textarea.form-control:hover, textarea.form-control:focus, textarea.form-control.active {
        color: var(--bs-dark);
    }

    [data-theme-version="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #fff;
    }

    [data-theme-version="dark"] .jconfirm .select2-container--default .select2-selection--single {
        background-color: var(--bs-light);
        color: var(--bs-dark);
    }

    [data-theme-version="dark"] .jconfirm .select2-container--default.select2-container--open .select2-selection--single {
        background-color: white;
        color: var(--bs-dark);
    }

    [data-theme-version="dark"] .jconfirm .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--bs-dark);
    }

    [data-theme-version="dark"] .jconfirm .select2-container--default .select2-dropdown {
        background: white;
        border-color: var(--bs-dark);
    }

    [data-theme-version="dark"] .jconfirm .select2-search--dropdown .select2-search__field
    {
        background: white;
        border-color: var(--bs-dark);
        color: var(--bs-dark);
    }

    .jconfirm .form-control {
        margin-bottom: 8px !important;
    }

    .jconfirm .table td, .jconfirm .table td {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
    }

    [data-theme-version="dark"] .btn-danger {
        color: #fff !important;
    }

    @media only screen and (max-width: 768px) {
        .x-label {
            margin-bottom: 8px;
        }
    }

</style>

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
                    <div class="alert alert-danger alert-dismissible" role="alert" id="ganti-password-notif">
                        <p><i class="icon glyphicon glyphicon-warning-sign"></i>Anda belum mengganti PIN anda. <b>Segera ganti PIN awal anda!</b></p>
                        <p style="margin-top: 15px !important; margin-bottom: -4px;"><a onclick=ganti_password() class="btn btn-primary">Ganti PIN</a></p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
 
        <!--**********************************
            Footer end
        ***********************************-->
        
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

</body>

{include file="../footer.tpl"}

{include file="./_profil.tpl"}
{include file="./_pendaftaran.tpl"}
{include file="./_daftarpendaftaran.tpl"}

<script type="text/javascript">
    //profil
    var tags = ['profil', 'lokasi', 'nilai', 'prestasi', 'afirmasi', 'inklusi', 'surat-pernyataan', 'nomer-hp'];
    var flags = ['punya_nilai_un', 'punya_prestasi', 'punya_organisasi', 'punya_akademik', 'punya_kip', 'masuk_bdt', 'kebutuhan_khusus'];

    var profil = {$profilsiswa|json_encode};
    var konfirmasi = {};
    var verifikasi = {};
    var profilflag = {};
 
    var kelengkapan_data = 0;
    var profildikunci = 0;
    var pendaftarandikunci = 0;
    var kebutuhankhusus = 0;
    var siswa_tutup_akses = 0;

    //pendaftaran
    var diterima = {$diterima|default: 0};
    var diverifikasi = {$profilsiswa.diverifikasi|default: 0};
    var tutup_akses = {$tutup_akses|default: 0};
    var cek_waktusosialisasi = {$cek_waktusosialisasi};
    var cek_waktupendaftaran = {$cek_waktupendaftaran};
    var cek_batasanusia = {$cek_batasanusia};
    var maxpilihan = {$maxpilihan};
    var maxpilihanumum = {$maxpilihanumum};
    var maxpilihannegeri = {$maxpilihannegeri};
    var maxpilihanswasta = {$maxpilihanswasta};
    var jumlahpendaftarannegeri = {$jumlahpendaftarannegeri};
    var jumlahpendaftaranswasta = {$jumlahpendaftaranswasta};
    
    var daftarpenerapan = {$daftarpenerapan|json_encode};

    //hasil pendaftaran

    var daftarpendaftaran = {$daftarpendaftaran|json_encode};

    //profil
    // var editprestasi;
    // var dtprestasi = null;
    var dtriwayat = null;

    (function($) {           

        //profil dikunci kalau ada pendaftaran
        if (daftarpendaftaran != null && daftarpendaftaran.length > 0) {
            profildikunci = 1;
        }

        //profil dikunci kalau sudah diverifikasi
        if (diverifikasi) {
            profildikunci = 1;
        }

        //update flag pendaftaran dikunci
        if (cek_batasanusia==0 || (!cek_waktupendaftaran && !cek_waktusosialisasi) || tutup_akses==1 || diterima==1) {
            pendaftarandikunci = 1;
        }

        reload_profil();
         
        //menu
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

            //raise event
            target.trigger("shown.bs.tab");

            //close side-bar
            $('#main-wrapper').removeClass('menu-toggle');
            $('.hamburger').removeClass('is-active');
        } );

        
        // toggle 'scroll to top' based on scroll position
        const btnScrollToTop = document.querySelector(".DZ-bt-scroll-top");
        window.addEventListener('scroll', e => {
            btnScrollToTop.style.display = window.scrollY > 40 ? 'block' : 'none';
        });

        //scroll to top
        $("a[href='#top']").click(function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });;
            return false;
        });

        init_profil();
        init_pendaftaran();
        update_hasil_layout();

        //pilih tab yang aktif
        $('#menu-{$aktif}').trigger('click');

    })(jQuery);       

</script>



