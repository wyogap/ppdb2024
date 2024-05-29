
<div class="loading" id="loader" style="display: none;">
    <div class="loading-circle"></div>
</div>

<!-- {if $show_footer|default: TRUE}
<footer class="main-footer footer mb-2">
	<div class="container text-center">
            <strong>Copyright &copy; 2020 <a href="javascript:void(0)">{$nama_wilayah}</a>.</strong> All rights reserved.
	</div>
</footer>
{/if} -->

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

<a href="#top" class="DZ-bt-scroll-top DZ-theme-btn" style='display: none;'>
    <img src="{$base_url}images/icons8-arrow-up-50.png" enable-background="new 0 0 512 512" height="40" viewBox="0 0 512 512" width="40">
</a>  

<!-- Required vendors -->
<script src="{$base_url}/themes/dompet/js/global.min.js"></script>
<script src="{$base_url}/themes/dompet/vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>

{if $use_select2|default: FALSE}
<script src="{$base_url}assets/select2/js/select2.min.js"></script>
{/if}

{if $use_datatable|default: FALSE}
<script src="{$base_url}assets/datatables/DataTables-1.11.4/js/jquery.dataTables.min.js"></script>
<script src="{$base_url}assets/datatables/Select-1.3.4/js/dataTables.select.min.js"></script>
<script src="{$base_url}assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>
<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js"></script>
<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.html5.min.js"></script>
<script src="{$base_url}assets/datatables/JSZip-2.5.0/jszip.min.js" defer></script>

{if $use_datatable_editor|default: FALSE}
<script src="{$base_url}assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js"></script>
{/if}

<!-- <script src="{$base_url}/themes/dompet/js/plugins-init/datatables.init.js"></script> -->
{/if}

<!-- mustache templating -->
<script src="{$base_url}assets/mustache/mustache.min.js"></script>

<!-- toastr toast popup -->
<script src="{$base_url}assets/jquery-confirm/jquery-confirm.min.js"></script>
<script src="{$base_url}assets/toastr/toastr.min.js"></script>

<!--- moment -->
<script src="{$base_url}assets/moment/moment-with-locales.min.js" defer></script>

{if $use_leaflet|default: FALSE}
<script src="{$site_url}assets/leaflet/leaflet.js"></script>
<script src="{$site_url}assets/leafletfullscreen/leaflet.fullscreen.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script> 
{/if}

<script src="{$base_url}assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<!-- Set up theme -->
<script type="text/javascript">
    var dezSettingsOptions = {};

    (function($) {
        let dark_theme = getCookie('tcg-dark-theme');
        //alert(document.cookie);
        if (dark_theme === undefined) {
            dark_theme = 0;
            setCookie("tcg-dark-theme", dark_theme, 30);
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
        
        if (dark_theme == 0) {
            $("#toggle-light").hide();
            $("#toggle-dark").show();
        }
        else {
            $("#toggle-light").show();
            $("#toggle-dark").hide();
        }

    })(jQuery);

    function toggle_dark_mode() {
        let dark_theme = getCookie('tcg-dark-theme');
        if (dark_theme === undefined) {
            dark_theme = 0;
        } 

        if (dark_theme == 1)    dark_theme = 0;
        else                    dark_theme = 1;

        setCookie("tcg-dark-theme", dark_theme, 30);

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
        
        if (dark_theme == 0) {
            $("#toggle-light").hide();
            $("#toggle-dark").show();
        }
        else {
            $("#toggle-light").show();
            $("#toggle-dark").hide();
        }
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

<script type="text/javascript">

    $(document).ready(function() {

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

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

        {if $use_datatable|default: FALSE}
        $.extend( $.fn.dataTable.defaults, { 
            responsive: true, 
            "language": {
                    "processing":   "Sedang proses...",
                    "lengthMenu":   "Tampilan _MENU_ baris",
                    "zeroRecords":  "Tidak ditemukan data yang sesuai",
                    "info":         "Tampilan _START_ - _END_ dari _TOTAL_ baris",
                    "infoEmpty":    "Tampilan 0 hingga 0 dari 0 baris",
                    "infoFiltered": "(disaring dari _MAX_ baris keseluruhan)",
                    "infoPostFix":  "",
                    "loadingRecords": "Loading...",
                    "emptyTable":   "Tidak ditemukan data yang sesuai",
                    "search":       "Cari:",
                    "url":          "",
                    "paginate": {
                        "first":    "Awal",
                        "previous": "Balik",
                        "next":     "Lanjut",
                        "last":     "Akhir"
                    },
                    aria: {
                        sortAscending:  ": klik untuk mengurutkan dari bawah ke atas",
                        sortDescending: ": klik untuk mengurutkan dari atas ke bawah"
                    }
                },	
        });

        $('a[data-bs-toggle="tab"]').on( 'shown.bs.tab', function (e) {
            $.fn.dataTable.tables( { visible: true, api: true } ).columns.adjust().responsive.recalc();
        });
        {/if}

        //default toastr options
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

    });

</script>

{* Some usefule js functions*}
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
    
    function select_build(select, deflabel, defvalue, value, options, attr) {
        //store current value
        let _prevvalue = select.val();

        //rebuild the option list
        select.empty();

        //default option
        if (typeof deflabel !== "undefined" && deflabel != null && typeof defvalue !== "undefined" && defvalue != null) {
            let _def = $("<option>").val(defvalue).text(deflabel);
            _def.addClass("select-option-level-1");
            select.append(_def);
        }

        //list of options
        if (options != null && Array.isArray(options)) {
            //add options one by one
            options.forEach(function(item, index, arr) {
                if (typeof item === "undefined" || item == null ||
                    typeof item.value === "undefined" || item.value == null ||
                    typeof item.label === "undefined" || item.label == null) {
                    return;
                }

                if (item.value == defvalue) {
                    return;
                }

                let _option = $("<option>").val(item.value).text(item.label);

                if (typeof item.level === "undefined" || item.level == null) {
                    _option.addClass("select-option-level-1");
                } else if (item.level == 2) {
                    _option.addClass("select-option-level-2");
                } else if (item.level == 3) {
                    _option.addClass("select-option-level-3");
                } else if (item.level == 4) {
                    _option.addClass("select-option-level-4");
                } else if (item.level == 5) {
                    _option.addClass("select-option-level-5");
                } else {
                    _option.addClass("select-option-level-1");
                }

                if (typeof item.optgroup !== "undefined" && item.optgroup != null && item.optgroup == 1) {
                    _option.addClass("select-option-group");
                    _option.prop("disabled", true);
                }

                select.append(_option);

            });
        }

        //re-set the value
        if (typeof value !== 'undefined' && value != null) {
            select.val(value);
        } else {
            select.val(_prevvalue);
        }

        // if (typeof value === 'undefined' || value == null) {
        //     if (typeof defvalue === 'undefined' || defvalue == null || defvalue == '') {
        //         select.val('0').trigger('change');
        //     } else {
        //         select.val(defvalue).trigger('change');
        //     }
        // } else {
        //     select.val(value);
        // }

        //multiple select?
        if (typeof attr.multiple !== 'undefined' && attr.multiple) {
            select.attr('multiple', 'multiple');
        }

        //read-only?
        if (typeof attr.readonly !== 'undefined' && attr.readonly == true) {
            select.attr("readonly", true);
        }

        return select;
    }

    {if !empty($use_select2)}
    function select2_build(select, deflabel, defvalue, value, options, attr, parent = null) {

        //build the select
        select_build(select, deflabel, defvalue, value, options, attr);

        //convert to select2
        select.select2({
            minimumResultsForSearch: attr.minimumResultsForSearch,
            //dropdownCssClass: attr.cssClass,
            dropdownParent: parent,
            templateResult: function(data) {
                // We only really care if there is an element to pull classes from
                if (!data.element) {
                    return data.text;
                }

                var $element = $(data.element);

                var $wrapper = $('<div></div>');
                $wrapper.addClass($element[0].className);

                $wrapper.text(data.text);

                return $wrapper;
            }
        });

        //read-only?
        if (typeof attr.readonly !== 'undefined' && attr.readonly == true) {
            select.select2("readonly", true);
        }

        return select;
    }

    function select2_rebuild(select, attr, parent = null) {

        //convert to select2
        select.select2({
            minimumResultsForSearch: attr.minimumResultsForSearch,
            //dropdownCssClass: attr.cssClass,
            dropdownParent: parent,
            templateResult: function(data) {
                // We only really care if there is an element to pull classes from
                if (!data.element) {
                    return data.text;
                }

                var $element = $(data.element);

                var $wrapper = $('<div></div>');
                $wrapper.addClass($element[0].className);

                $wrapper.text(data.text);

                return $wrapper;
            }
        });

        //read-only?
        if (typeof attr.readonly !== 'undefined' && attr.readonly == true) {
            select.select2("readonly", true);
        }

        return select;
    }
    {/if}
</script>

{* Popup to change password *}
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

</html>