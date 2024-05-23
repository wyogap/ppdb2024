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
    
    <link rel="stylesheet" href="{$site_url}assets/leaflet/leaflet.css"/>
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet'/>

    {include file='../_css.tpl'}

    <style>
        .home .content-body {
            padding-top: 6rem !important;
        }

        .home .header {
            padding-left: 0px !important; 
            padding-right: 0px !important;
        }

        .home .nav-header {
            background-color: var(--headerbg) !important;
            margin-top: 8px !important;
            width: 6.25rem !important;
        }

        .home .header-left {
            padding-left: 120px;;
        }

        @media only screen and (min-width: 768px) {
            .home .content-body {
                margin-left: 0 !important;
            }

            .home .nav-header {
                top: 0 !important;
                height: 80px !important;
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

        @media only screen and (min-width: 1024px) {
            .home .header-left {
                padding-top: 0px;
            }
        }
    </style>

</head>

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
                <div class="app-name-long dashboard_bar">
                    <span class="app-name">{$app_name}</span>
                    <span class="app-desc">Tahun {$nama_tahun_ajaran} {$nama_putaran}</span>
                </div>
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
    
		<!-- Load Leaflet from CDN -->
		<!-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
		integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
		crossorigin=""/>
		<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
		integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
		crossorigin=""></script> -->


		<!-- Load Esri Leaflet from CDN -->
		<!-- <script src="https://unpkg.com/esri-leaflet@2.4.1/dist/esri-leaflet.js"
		integrity="sha512-xY2smLIHKirD03vHKDJ2u4pqeHA7OQZZ27EjtqmuhDguxiUvdsOuXMwkg16PQrm9cgTmXtoxA6kwr8KBy3cdcw=="
		crossorigin=""></script> -->


		<!-- Load Esri Leaflet Geocoder from CDN -->
		<!-- <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.css"
			integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
			crossorigin="">
		<script src="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.js"
			integrity="sha512-HrFUyCEtIpxZloTgEKKMq4RFYhxjJkCiF5sDxuAokklOeZ68U2NPfh4MFtyIVWlsKtVbK5GD2/JzFyAfvT5ejA=="
			crossorigin=""></script> -->

		<!-- <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
		<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>

		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
		<script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script> -->

        <script src="{$site_url}assets/leaflet/leaflet.js"></script>
        <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>

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

    {if $js_template|default: FALSE} 
        {include file="./$js_template"}
    {/if}

    <script>
        var userid = {$user_id};
        
        (function($) {           

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            //TODO

        })(jQuery);   
        
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
                url: "{$site_url}auth/resetpassword",
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

</body>
</html>