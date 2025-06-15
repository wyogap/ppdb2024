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
        top: 12px;
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

    .wait-cursor {
        cursor: wait; 
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
                            {if $show_putaran}
							<p>Tahun {$nama_tahun_ajaran} {$nama_putaran}</p>
                            {else} 
                            <p>Tahun {$nama_tahun_ajaran}</p>
                            {/if}
						</div>

                        <div id='login-div'>
                            {if !empty($info_message)}
                            <div class="alert alert-info alert-dismissible" id="alert-info" role="alert" style="margin-top: -32px">
                                {$error_message}        
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>     
                            </div>
                            {/if}

                            {if !empty($error_message)}
                            <div class="alert alert-danger alert-dismissible" id="alert-danger" role="alert" style="margin-top: -32px">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>                    
                                {$error_message}
                            </div>
                            {/if}

                            {if !empty($success_message)}
                            <div class="alert alert-success alert-dismissible" id="alert-sucess" role="alert" style="margin-top: -32px">
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
                                {* TODO *}
                                <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                    <div class="mb-4" style="flex-grow: 1; text-align: right;">
                                        <a href="#" class="btn-link text-primary pwdreset" id="forgetpwd">Lupa PIN/Password?</a>
                                    </div>
                                </div>
                                {/if}
                                <div class="text-center mb-4">
                                    <button type="submit" class="btn btn-primary btn-block" id="login">Masuk</button>
                                </div>
                                <h6 class="login-title"><span>ATAU</span></h6>
                                {if $cek_registrasi|default: FALSE || $cek_sosialisasi|default: FALSE || $cek_pendaftaran|default: FALSE}
                                <div class="text-center mb-4">
                                    <a href="{$site_url}home/registrasi" class="btn btn-secondary btn-block">Registrasi Siswa Luar Daerah</a>
                                </div>
                                {else}
                                <p class="text-center">Registrasi untuk siswa dari <b class="text-red">Luar Daerah</b> belum dibuka.</p>
                                {/if}
                            </form>
                        </div>
                        <div id='forgetpwd-div' style="display:none;">
                            <p class="text-center" style="margin-top:12px; margin-bottom:24px;">Reset PIN/Password</p>
                            <div class="mb-4">
                                <!-- <label class="mb-1 text-dark">NISN / NIK / Nama Pengguna</label> -->
                                <input type="text" class="form-control form-control" placeholder="Ketik NISN / NIK / Nama Pengguna" 
                                id="username-reset" name="username-reset" data-validation="required" minlength="8" maxlength="100">
                            </div>
                            <p class="text-center" style="margin-bottom:24px;">Kode reset akan dikirim ke alamat email yang terdaftar dan/atau melalui Whatsapp ke nomor HP yang terdaftar.</p>
                            <p class="text-center" style="color: red; margin-top:12px; margin-bottom:24px; display:none;" id="username-error"></p>
                            <div class="text-center mb-4">
                                <button type="submit" class="btn btn-danger btn-block pwdreset" id="sendcode">Kirim Kode Reset</button><br>  
                                <button type="submit" class="btn btn-primary btn-block cancelreset" id="cancel">Batalkan</button>
                            </div>
                        </div>
                        <div id='resetpwd-div' style="display:none;">
                            <p class="text-center" style="margin-top:12px; margin-bottom:24px;">Masukkan kode reset yang dikirim ke alamat email/HP yang terdaftar:</p>
                            <div class="mb-4">
                                <!-- <label class="mb-1 text-dark">NISN / NIK / Nama Pengguna</label> -->
                                <input type="text" class="form-control form-control" placeholder="Kode Reset" 
                                id="kodereset" name="kodereset" data-validation="required" minlength="8" maxlength="100">
                            </div>
                            <div class="text-center mb-4" id="checkcode-div">
                                <button type="submit" class="btn btn-primary pwdreset" id="checkcode">Cek Kode</button>  
                                <button type="submit" class="btn btn-danger pwdreset" id="resendcode">Kirim Ulang</button>
                            </div>
                            <div id='newpwd-div' style="display:none;">
                                <p class="text-center" style="margin-top:12px; margin-bottom:24px;" id="kodereset-valid">Masukkan PIN/Password Baru:</p>
                                <div class="mb-4 position-relative">
                                    <!-- <label class="mb-1 text-dark">PIN / Password</label> -->
                                    <input type="password" id="reset-password1" class="form-control form-control"
                                        placeholder="Masukkan PIN / Password Baru" name="reset-password1" data-validation="required">
                                    <span class="tcg-show-pass eye" target='reset-password1'>								
                                        <i class="fa fa-eye-slash"></i>
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                                <div class="mb-4 position-relative">
                                    <!-- <label class="mb-1 text-dark">PIN / Password</label> -->
                                    <input type="password" id="reset-password2" class="form-control form-control"
                                        placeholder="Masukkan Ulang PIN / Password Baru" name="reset-password2" data-validation="required">
                                    <span class="tcg-show-pass eye" target='reset-password2'>								
                                        <i class="fa fa-eye-slash"></i>
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                                <div class="text-center mb-4">
                                        <button type="submit" class="btn btn-danger btn-block pwdreset" id="resetpwd">Reset PIN/Password</button>
                                </div>
                            </div>
                            <p class="text-center" style="color: red; margin-top:12px; margin-bottom:24px; display:none;" id="kodereset-error">Kode reset salah!</p>
                            <div class="text-center mb-4">
                                <button type="submit" class="btn btn-primary btn-block cancelreset" id="cancel2">Batalkan</button>
                            </div>
                        </div>
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
                                                        {assign var="jenjang" value=""}
                                                        {foreach $p.tahapan as $t}
                                                        {if $t.tahapan_id==0 || $t.tahapan_id==99} {continue} {/if} 
                                                        {if $t.jenjang && $t.jenjang!=$jenjang} 
                                                            {assign var="jenjang" value=$t.jenjang} 
                                                            <tr class="" style="padding-top: 10px; padding-bottom: 10px">
                                                                <td colspan="5" class="text-start">
                                                                    Jenjang {$jenjang}
                                                                </td>
                                                            </tr>
                                                        {/if}
                                                        <tr class="" style="padding-top: 10px; padding-bottom: 10px">
                                                            <td  class="">{$t.tahapan}</td><td></td>
                                                            {if ($t.tanggal_mulai == $t.tanggal_selesai)}<td class="local-datetime" colspan=3>{$t.tanggal_mulai}</td>
                                                            {else}<td class="local-datetime">{$t.tanggal_mulai}</td><td width="20px">s.d.</td><td class="local-datetime">{$t.tanggal_selesai}</td>
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

    var resetpwd_userid = null;
    //$counter = 0;
    var countdown = null;
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

        $(".pwdreset").on( "click", function(e) {
            e.preventDefault();
            dom = $(this);
            id = dom.attr("id");

            if (id=='forgetpwd') {
                $x = $("#login-div"); $x.hide();
                $x = $("#forgetpwd-div"); $x.show();
                $x = $("#resetpwd-div"); $x.hide();
            }
            else if (id=='sendcode') {
                //send code
                sendcode(dom);
            }
            else if (id=='checkcode') {
                //check code
                checkcode(dom);
            }
            else if (id=='resendcode') {
                //resend code
                resendcode(dom); 
            }
            else if (id=='resetpwd') {
                //reset password
                resetpwd(dom);
            }
        });

        $(".cancelreset").on( "click", function(e) {
            e.preventDefault();
            $("#forgetpwd-div").hide();
            $("#resetpwd-div").hide();
            $("#login-div").show();
        });

        //$dbg = $(".pwdreset");
    });

    var counter_resendcode=60;
    function countdown_resendcode() {
        counter_resendcode--;
        if (counter_resendcode<=0) {
            clearInterval(countdown);
            $('#resendcode').text("Kirim Ulang");
            $('#resendcode').prop('disabled', false);
        }
        else {
            $('#resendcode').text("Kirim Ulang (" +counter_resendcode+ ")");
        }

    };

    function sendcode(dom) {
        $('#username-error').hide();
        dom.prop('disabled', true);
        $('#login-form').addClass('wait-cursor'); 

        var key = $("#username-reset").val().trim();
        var data = { "key":key };
        $.ajax({
            type: "POST",
            url : "{$site_url}auth/sendresetcode",
            data: data,
            dataType: "json",
            success: function(json){
                $('#login-form').removeClass('wait-cursor'); 
                dom.prop('disabled', false);

                if (json.error!==undefined) {
                    //toastr.error(json.error);
                    $('#username-error').html(json.error);
                    $('#username-error').show();
                    return;
                }
                else if (json.success===undefined) {
                    //toastr.error("Tidak berhasil mengirim kode.");
                    $('#username-error').html("Tidak berhasil mengirim kode.");
                    $('#username-error').show();
                    return;
                }
                
                //get user id
                resetpwd_userid = json['userid'];

                //next step
                $("#login-div").hide();
                $("#forgetpwd-div").hide();
                $("#resetpwd-div").show();
                $("#checkcode-div").show();
                $('#kodereset-error').hide();
                $('#newpwd-div').hide();

                //start counter for resend
                $('#resendcode').prop('disabled', true);
                counter_resendcode=60;
                $('#resendcode').text("Kirim Ulang (" +counter_resendcode+ ")");

                countdown = setInterval(countdown_resendcode, 1000);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                //toastr.error("Tidak berhasil menyimpan nomor kontak. " + textStatus);
                $('#username-error').html("Tidak berhasil mengirim kode. " + textStatus);
                $('#username-error').show();

                $('#login-form').removeClass('wait-cursor'); 
                dom.prop('disabled', false);
                return;
            }
        });
    }

    function checkcode(dom) {
        $('#kodereset-error').hide();
        $('#newpwd-div').hide();

        dom.prop('disabled', true);
        $('#login-form').addClass('wait-cursor'); 

        var kode = $("#kodereset").val().trim();
        var data = { "code":kode, "userid": resetpwd_userid };
        $.ajax({
            type: "POST",
            url : "{$site_url}auth/checkresetcode",
            data: data,
            dataType: "json",
            success: function(json){
                $('#login-form').removeClass('wait-cursor'); 
                dom.prop('disabled', false);

                if (json.error!==undefined && json.errorno==-1198) {
                    $('#kodereset-error').html("Kode reset salah!");
                    $('#kodereset-error').show();
                    return;
                }
                else if (json.success===undefined) {
                    $('#kodereset-error').html('Tidak berhasil menvalidasi kode.');
                    $('#kodereset-error').show();
                    return;
                }
                
                $("#checkcode-div").hide();
                $('#kodereset-error').hide();
                $('#newpwd-div').show();
                $('#reset-password1').val('');
                $('#reset-password2').val('');

            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#kodereset-error').html("Tidak berhasil menvalidasi kode. " + textStatus);
                $('#kodereset-error').show();

                $('#login-form').removeClass('wait-cursor');
                dom.prop('disabled', false);
                return;
            }
        });

    }

    function resendcode(dom) {
        $('#kodereset-error').hide();
        dom.prop('disabled', true);
        $('#login-form').addClass('wait-cursor'); 

        var key = $("#username-reset").val().trim();
        var data = { "key": key, "userid": resetpwd_userid, "resend": 1 };
        $.ajax({
            type: "POST",
            url : "{$site_url}auth/sendresetcode",
            data: data,
            dataType: "json",
            success: function(json){
                $('#login-form').removeClass('wait-cursor'); 
                dom.prop('disabled', false);

                if (json.error!==undefined) {
                    //toastr.error(json.error);
                    $('#kodereset-error').html(json.error);
                    $('#kodereset-error').show();
                    return;
                }
                else if (json.success===undefined) {
                    //toastr.error("Tidak berhasil mengirim kode.");
                    $('#kodereset-error').html("Tidak berhasil mengirim kode.");
                    $('#kodereset-error').show();
                    return;
                }
                
                //start counter for resend
                $('#resendcode').prop('disabled', true);
                counter_resendcode=60;
                $('#resendcode').text("Kirim Ulang (" +counter_resendcode+ ")");

                countdown = setInterval(countdown_resendcode, 1000);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                //toastr.error("Tidak berhasil menyimpan nomor kontak. " + textStatus);
                $('#kodereset-error').html("Tidak berhasil mengirim kode. " + textStatus);
                $('#kodereset-error').show();

                $('#login-form').removeClass('wait-cursor'); 
                dom.prop('disabled', false);
                return;
            }
        });
    }

    function resetpwd(dom) {
        $('#kodereset-error').hide();
        dom.prop('disabled', true);
        $('#login-form').addClass('wait-cursor'); 

        var kode = $("#kodereset").val().trim();
        var data = { "code": kode, "pwd1":$("#reset-password1").val(), "pwd2":$("#reset-password2").val(), "userid": resetpwd_userid };
        $.ajax({
            type: "POST",
            url : "{$site_url}auth/resetpassword",
            data: data,
            dataType: "json",
            success: function(json){
                $('#login-form').removeClass('wait-cursor'); 
                dom.prop('disabled', false);

                if (json.error!==undefined) {
                    //toastr.error(json.error);
                    $('#kodereset-error').html(json.error);
                    $('#kodereset-error').show();
                    return;
                }
                else if (json.success===undefined) {
                    //toastr.error("Tidak berhasil mengirim kode.");
                    $('#kodereset-error').html("Tidak berhasil me-reset password.");
                    $('#kodereset-error').show();
                    return;
                }
                
                $("#forgetpwd-div").hide();
                $("#resetpwd-div").hide();
                $("#login-div").show();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //toastr.error("Tidak berhasil menyimpan nomor kontak. " + textStatus);
                $('#kodereset-error').html("Tidak berhasil me-reset password. " + textStatus);
                $('#kodereset-error').show();

                $('#login-form').removeClass('wait-cursor'); 
                dom.prop('disabled', false);
                return;
            }
        });
    }


</script>
