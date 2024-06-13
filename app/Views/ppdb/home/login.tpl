{include file="../header.tpl"}

<style>

    .login-form {
        padding: 0 50px;
        max-width: 600px;
        margin: 0 auto;
        margin-top: calc((100vh - 550px) / 2);
        margin-left: calc(((100vw / 2) - 560px) / 2);
        position: fixed;
        width: 500px;
    }

    .login-form .login-title {
        text-align: center;
        position: relative;
        margin-bottom: 24px;
        z-index: 1;
        display: flex;
        align-items: center;
    }
            
    .login-form .login-title:before, 
    .login-form .login-title:after {
        content: "";
        height: 1px;
        flex: 1 1;
        left: 0;
        background-color: #E1E1F0;
        top: 50%;
        z-index: -1;
        margin: 0;
        padding: 0;
    }

    .eye {
        position: absolute;
        /* top: 45px; */
        top: 20px;
        right: 20px;
    }
    input.form-control {
        position: relative;
    }

    .form-control {
        background: #fff;
        border: 0.0625rem solid #e6e6e6;
        padding: 0.3125rem 1.25rem;
        color: #6e6e6e;
        height: 3.5rem;
        border-radius: 1rem;
    }

    .bg-image {
        /* background-image: url(assets/image/landingpage.jpg); */
        background-repeat: no-repeat;
        background-position: center;
        background-attachment: local;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }

    .accordion-primary-solid .accordion-header {
        font-size: 16px;
    }

    .accordion-primary-solid .accordion-header.collapsed {
        background: var(--primary);
        color: white;
    }

    .accordion-primary-solid .accordion-body-text {
        /* background-color: var(--rgba-primary-3);
        border-bottom-left-radius: 24px;
        border-bottom-right-radius: 24px;
        font-size: 16px;
        color: white; */
        background-color: var(--bs-gray-dark);
        border-bottom-left-radius: 28px;
        border-bottom-right-radius: 28px;
        font-size: 16px;
        color: white;
    }

    .accordion-primary-solid .accordion-body-text .table {
        color: white;
    }

    .tab-content .container {
        padding-top: 24px;
        padding-bottom: 24px;
    }

    .app-title {
        margin-bottom: 3rem;
    }

    .app-title p {
        color: black;
        font-size: 16px;
    }

    [data-theme-version="dark"] .app-title p {
        color: white;
        font-size: 16px;
    }

    @media only screen and (max-width: 992px) {
        .authincation {
            background: var(--headerbg);
        }

        .login-form {
            padding: 0 15px;
            padding-top: 150px;
            max-width: 600px;
            margin: auto auto;
            /* align-content: center; */
            position: relative;
            width: 100%;
            height: calc(100vh - 100px);
        }

        .app-title {
            position: fixed;
            top: 16px;
            color: white;
            background-color: var(--title);
            margin: -16px 0px;
            width: calc(100vw);
            z-index: 1000;
            padding-top: 16px;
            left: 0px;
        }

        .app-title .title {
            color: white;
        }

        .app-title p {
            color: white;
        }

        .right-side {
            min-height: calc(100vh - 100px);
        }

        .right-side .container {
            padding: 16px 0px;
        }
    }

    @media only screen and (max-width: 768px) {
        .login-form {
            height: calc(100vh - 150px);
        }    
    }

</style>

<body class="vh-100">
<span id="warning-container"><i data-reactroot=""></i></span>
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row h-100">
				<div class="left-side col-lg-6 col-md-12 col-sm-12 mx-auto">
					<div class="login-form">
						<div class="app-title text-center"">
							<h3 class="title">{$app_name}</h3>
							<p>Tahun {$nama_tahun_ajaran} {$nama_putaran}</p>
						</div>

                        {if !empty($info_message)}
                        <div class="alert alert-info alert-dismissible" id="alert-info" role="alert">
                            {$error_message}        
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>     
                        </div>
                        {/if}

                        {if !empty($error_message)}
                        <div class="alert alert-danger alert-dismissible" id="alert-danger" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>                    
                            {$error_message}
                        </div>
                        {/if}

                        {if !empty($success_message)}
                        <div class="alert alert-success alert-dismissible" id="alert-sucess" role="alert">
                            {$success_message}                 
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        {/if}

                        {if $rekapitulasi|DEFAULT: FALSE}
                        <div class="alert alert-secondary mb-4" role="alert">
                            <span>Rekapitulasi Sekolah / Perangkingan: <a href="{$site_url}home/rekapitulasi" class="btn btn-xs btn-primary">Klik Di Sini</a></span>
                        </div>
                        {/if} 

                        <form role="form" enctype="multipart/form-data" id="proses" action="{$base_url}auth/login" method="post">
                            {if $user_id}
                            <div class="header-profile mb-5">
                                <a class="nav-link" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{$base_url}assets/image/user.png" width="20" alt=""/>
                                    <div class="header-info ms-3" style="display: block;">
                                        <span class="font-w600 ">Halo, <b>{$nama_pengguna}</b></span>
                                        <small class="font-w400">{$user_name}</small>
                                    </div>
                               </a>
                            </div>
                            {else}
							<div class="mb-4">
								<!-- <label class="mb-1 text-dark">NISN / NIK / Nama Pengguna</label> -->
								<input type="text" class="form-control form-control" placeholder="Ketik NISN / NIK / Nama Pengguna" 
                                id="username" name="username" data-validation="required" minlength="8" maxlength="100">
							</div>
							<div class="mb-4 position-relative">
								<!-- <label class="mb-1 text-dark">PIN / Password</label> -->
								<input type="password" id="dlab-password" class="form-control form-control"
                                placeholder="Masukkan PIN / Password" id="password" name="password" data-validation="required">
								<span class="show-pass eye">								
									<i class="fa fa-eye-slash"></i>
									<i class="fa fa-eye"></i>
								</span>
							</div>
							<!-- <div class="form-row d-flex justify-content-between mt-4 mb-2">
								<div class="mb-4" style="flex-grow: 1; text-align: right;">
									<a href="page-forgot-password.html" class="btn-link text-primary">Lupa PIN/Password?</a>
								</div>
							</div> -->
                            {/if}
							<div class="text-center mb-4">
								<button type="submit" class="btn btn-primary btn-block">Masuk</button>
							</div>
							<h6 class="login-title"><span>ATAU</span></h6>
							{if $cek_registrasi|default: FALSE || $cek_sosialisasi|default: FALSE}
							<div class="text-center mb-4">
								<a href="{$site_url}home/registrasi" class="btn btn-secondary btn-block">Registrasi Siswa Luar Daerah</a>
							</div>
                            {else}
                            <p class="text-center">Registrasi untuk siswa dari <b class="text-red">Luar Daerah</b> belum dibuka.</p>
                            {/if}
						</form>
					</div>
				</div>
                <div class="right-side col-xl-6 col-lg-6 bg-image" style="background-color: var(--bs-gray-dark); background-image: url('{$base_url}assets/image/landingpage.jpg')">
					<div class="pages-left" style="display: flex; margin-top: 24px; margin-bottom: 24px;">
						<!-- <div class="login-content">							
							<p>Your true value is determined by how much more you give in value than you take in payment. ...</p>
						</div> -->
						<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin: auto;">
                            <button type="button" class="btn btn-outline-primary nav-link active"
                            aria-expanded="false" data-bs-toggle="menu" data-bs-target="#pengumuman" type="button" role="tab">Pengumuman</button>
                            <button type="button" class="btn btn-outline-primary nav-link"
                            aria-expanded="false" data-bs-toggle="menu" data-bs-target="#waktupelaksanaan" type="button" role="tab">Waktu Pelaksanaan</button>
                            <button type="button" class="btn btn-outline-primary nav-link"
                            aria-expanded="false" data-bs-toggle="menu" data-bs-target="#petunjukpelaksanaan" type="button" role="tab">Petunjuk Pelaksanaan</button>
                        </div>
					</div>
                    <div class="tab-content" id="menu-contents">
                        <div class="container tab-pane fade show active" id="pengumuman" role="tabpanel" aria-labelledby="v-pengumuman-tab">
                            {*Pengumuman*}
                            {foreach $pengumuman as $row}
                            {if empty($row.text)}{continue}{/if}

                            {*Get alert type*}
                            {if $row.tipe==0}             
                                {*info*}
                                <div class="alert alert-primary{if $row.bisa_ditutup==1} alert-dismissible{/if}" role="alert">
                                    <span class="{$row.css}">{$row.text}</span>
                                    {if $row.bisa_ditutup==1}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>{/if}
                                </div>
                            {elseif $row.tipe==1}    
                                {*success*}
                                <div class="alert alert-secondary{if $row.bisa_ditutup==1} alert-dismissible{/if}" role="alert">
                                    <span class="{$row.css}">{$row.text}</span>
                                    {if $row.bisa_ditutup==1}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>{/if}
                                </div>
                            {elseif $row.tipe==2}           
                                {*error/danger*}
                                <div class="alert alert-danger{if $row.bisa_ditutup==1} alert-dismissible{/if}" role="alert">
                                    <span class="{$row.css}">{$row.text}</span>
                                    {if $row.bisa_ditutup==1}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>{/if}
                                </div>
                            {else}
                                {*default*}
                                <div class="alert alert-primary{if $row.bisa_ditutup==1} alert-dismissible{/if}" role="alert">
                                    <span class="{$row.css}">{$row.text}</span>
                                    {if $row.bisa_ditutup==1}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>{/if}
                                </div>
                            {/if}
                            
                            {/foreach}

                            {*Pengumuman tahapan*}
                            {foreach $tahapan_aktif as $row}
                            {if empty($row.notifikasi_umum)}{continue}{/if}

                            <div class="alert alert-info alert-dismissible" role="alert">
                                <span>{$row.notifikasi_umum}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            {/foreach}

                        </div>
                        <div class="container tab-pane fade" id="waktupelaksanaan" role="tabpanel" aria-labelledby="v-pengumuman-tab">
                            <div class="row text-center">
                                <div class="col-12">
                                <div class="accordion accordion-primary-solid" id="accordion-nine">
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
                                                            {if ($t.tanggal_mulai == $t.tanggal_selesai)}<td class="local-datetime" colspan=3>{$t.tanggal_mulai}</td>
                                                            {else}<td class="local-datetime">{$t.tanggal_mulai}</td><td width="20px">s.d.</td><td class="">{$t.tanggal_selesai}</td>
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
                        <div class="container tab-pane fade" id="petunjukpelaksanaan" role="tabpanel" aria-labelledby="v-pengumuman-tab">
                            <!-- <div class="row text-center">
                                <div class="col-12"> -->
                                <div class="accordion accordion-primary-solid" id="accordion-2">
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
                                <!-- </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

{include file="../footer.tpl"}

<script type="text/javascript">

    $(document).ready(function() {
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
                var els = target.find(".accordion-header");
                // target.find(".accordion-header").addClass("collapsed");
                // target.find(".accordion__body").removeClass("show");
            } 
            else {
                //close all
                contents.find(".tab-pane").removeClass('show').removeClass("active");
                dom.parent().parent().find(".nav-link").removeClass('active');
                //show tab
                target.addClass('active').addClass('show');
                dom.addClass('active');
                //collapse all  
                target.find(".accordion-header").addClass("collapsed");
                target.find(".accordion__body").removeClass("show");
            }

            //raise event
            target.trigger("shown.bs.tab");

            let width = $(window).width();
            if (width < 992) {
                var additionalOffset = 0;
                let header_offset = 190;
                if (width < 785) {
                    header_offset = 205;
                }

                $('html,body').animate({
                    scrollTop: target.offset().top - additionalOffset - header_offset
                }, 100);
            }

        } );

        els = $('.local-datetime');
        els.each(function(idx, dom) {
            el = $(dom);
            el.html( moment.utc( el.html() ).local().format('YYYY-MM-DD HH:mm:ss') );
        });

    });

</script>
