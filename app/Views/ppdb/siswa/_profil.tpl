
<script type="text/javascript">

    function init_profil() {
       
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
            else if (width < 1020) {
                header_offset = 120;
            }

            $('html,body').animate({
                scrollTop: card.offset().top - additionalOffset - header_offset
            }, 100);

            //resize table
            if (card.prop('id') == 'riwayat') {
                dtriwayat.columns.adjust().responsive.recalc();
            }
        });

        $("[tcg-edit-action='submit']").on("change", function(evt) {
            simpan_profil(this);
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
            if (toggletag == 'punya_kip' || toggletag == 'masuk_bdt') {
                elements = $("[tcg-visible-tag='afirmasi']");
                if (!profilflag['punya_kip'] && !profilflag['masuk_bdt']) {
                    elements.hide();
                }
                else {
                    elements.show();
                }
            }

            //special case: prestasi
            if (toggletag == 'punya_prestasi' || toggletag == 'punya_organisasi') {
                elements = $("[tcg-visible-tag='prestasi']");
                if (!profilflag['punya_prestasi'] && !profilflag['punya_organisasi']) {
                    elements.hide();
                }
                else {
                    elements.show();
                }
            }

            //special case: kebutuhan khusus
            if (toggletag == 'kebutuhan_khusus') {
                if (value == 0) {
                    $('select[tcg-field="kebutuhan_khusus"]').val("Tidak ada");
                    $('span[tcg-field="kebutuhan_khusus"]').text("Tidak ada");
                }
            }
        });

        $("#btn_nomor_kontak").on("click", function(evt) {
            simpan_nomer_hp()
        });

        //all datatable must be responsive
        dtriwayat = $('#triwayat').DataTable({
            "responsive": true,
            "paging": false,
            "dom": 't',
            "buttons": [
            ],
            ajax: "{$site_url}ppdb/siswa/riwayat",
            columns: [
                { data: "created_on", className: 'dt-body-center readonly-column', orderable: true, 
                    render: function ( data, type, row ) {
                        if (typeof data === 'undefined' || data == null || data == "0000-00-00 00:00:00") {
                            data = "";
                        }

                        if (type == "display" && data != "") {
                            return moment.utc(data).local().format('YYYY-MM-DD HH:mm:ss');
                        }
                        return data;
                    },
                },
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
    };

    function update_profile_layout() {
        //dikunci: editability
        elements = $("[tcg-visible-tag='dikunci']");
        if (profildikunci) {
            elements.hide();
            $("#profil-dikunci-notif").show();
        }
        else {
            elements.show();
            $("#profil-dikunci-notif").hide();
        }

        //show/hide section berdasarkan profilflag
        flags.forEach(function(key) {
            value = profilflag[key];
            elements = $("[tcg-visible-tag='" +key+ "']");
            if (value) { elements.show(); }
            else { elements.hide(); }
        });

        //special case: afirmasi
        elements = $("[tcg-visible-tag='afirmasi']");
        if (!profilflag['punya_kip'] && !profilflag['masuk_bdt']) {
            elements.hide();
        }
        else {
            elements.show();
        }

        //konfirmasi: editability
        kelengkapan_profil = 1;
        tags.forEach(function(key) {
            if (verifikasi[key] == 2) {
                konfirmasi[key] = 0;
            }

            //dikonfirmasi oleh system -> hide dok pendukung
            if (konfirmasi[key] == 4) {
                elements = $("[tcg-visible-tag='" +key+ "']");
                elements.each(function(idx) {
                    el = $(this);
                    if (el.hasClass('dokumen-pendukung')) {
                        el.hide();
                    }
                });
            };

            //show/hide input berdasarkan status konfirmasi
            elements = $("[tcg-input-tag='" +key+ "']");
            elements.each(function(idx) {
                el = $(this);
                if (konfirmasi[key] == 4) {
                    //konfirmasi oleh system
                    action = el.attr('tcg-input-true');
                }
                else if (konfirmasi[key]) {
                    //sudah dikonfirmasi
                    action = el.attr('tcg-input-true');
                }
                else {
                    //belum dikonfirmasi
                    action = el.attr('tcg-input-false');
                }
                //default view
                if (action == 'show') el.show();
                else if (action == 'hide') el.hide();
                else if (action == 'enable') el.attr("disabled",false);
                else if (action == 'disable') el.attr("disabled",true);
            });

            //show card text berdasarkan status konfirmasi dan verifikasi
            let card = $("#" +key);
            if (verifikasi[key] == 2) {
                //status verifikasi
                card.addClass("status-danger");
                catatan = profil["catatan_" +key];
                if (catatan != null && catatan.trim() != '') {
                    card.find(".accordion-header-text .status").html('*Sedang Proses Verifikasi* : ' +catatan);
                }
                else {
                    card.find(".accordion-header-text .status").html('*Sedang Proses Verifikasi*');
                }
            }
            else if (konfirmasi[key] == 4) {
                //dikonfirmasi oleh system
                card.removeClass("status-danger");
                card.find(".accordion-header-text .status").html('Data Sesuai Sistem');
            }
            else if (konfirmasi[key]) {
                //sudah dikonfirmasi
                card.removeClass("status-danger");
                card.find(".accordion-header-text .status").html('');
            }
            else {
                card.addClass("status-danger");
                card.find(".accordion-header-text .status").html('*Belum Benar*');
            }

            //if need update for verification, show edit button
            if (verifikasi[key] == 2) {
                item = $("#" +key);
                item.find("[tcg-visible-tag='dikunci']").show();
                item.find("#" +key+ "-konfirmasi").val(0);
            }

            //if konfirmasi == 0 or 2 -> profil belum lengkap
            if (konfirmasi[key]!=1 && konfirmasi[key]!=4) {
                kelengkapan_profil = 0;
            }
        });
        
        //special case: nomor-hp
        let card = $("#nomer-hp");
        if (konfirmasi['nomer-hp']) {
            card.removeClass("status-danger");
            card.find(".accordion-header-text .status").html('');
        }
        else {
            card.addClass("status-danger");
            card.find(".accordion-header-text .status").html('*Belum Diisi*');
            //kalau nomer hp belum diisi -> profil belum lengkap
            kelengkapan_profil=0;
        }

        //special case: surat pernyataan
        card = $("#surat-pernyataan");
        {if !($flag_upload_dokumen)}
            {* no upload -> always OK *}
            card.removeClass("status-danger");
            card.find(".accordion-header-text .status").html('');
        {else}
            if (konfirmasi['surat-pernyataan']) {
                card.removeClass("status-danger");
                card.find(".accordion-header-text .status").html('');
            }
            else {
                card.addClass("status-danger");
                card.find(".accordion-header-text .status").html('*Belum Unggah Dokumen*');
                //kalau upload_dok=1 dan belum upload surat pernyataan -> profil belum lengkap
                kelengkapan_profil=0;
            }
        {/if}

        // //profil lengkap -> enable pendaftaran -> munculkan daftar jalur (buka kunci)
        // if (kelengkapan_profil && (!kelengkapan_data)) {
        //     kelengkapan_data=1;
        //     update_pendaftaran_layout();
        // }
        // else if (!kelengkapan_profil && kelengkapan_data) {
        //     kelengkapan_data=0;
        //     update_pendaftaran_layout();
        // }
    }       

    function impose_min_max(el){
        if(el.value != ""){
            val = parseFloat(el.value);
            if (isNaN(val)) val = 0;

            min = parseFloat(el.getAttribute('tcg-min'));
            max = parseFloat(el.getAttribute('tcg-max'));
            if(!isNaN(min) && val < min){
                el.value = min;
            }
            else if(!isNaN(max) && val > max){
                el.value = max;
            }
        }
    }

    function reload_profil(redraw=0) {
        //kebutuhan khusus
        if (profil['kebutuhan_khusus'] != 'Tidak ada') {
            kebutuhankhusus = 1;
        }

        //konfirmasi dan verifikasi
        tags.forEach(function(key) {
            if (key == 'surat-pernyataan' || key == 'nomer-hp')  return;
            konfirmasi[key] = parseInt(profil['konfirmasi_' +key]);
            verifikasi[key] = parseInt(profil['verifikasi_' +key]);
        });

        {if ($flag_upload_dokumen)}
        if (parseInt(profil['surat_pernyataan_kebenaran_dokumen']) == 0) {
            konfirmasi['surat-pernyataan'] = verifikasi['surat-pernyataan'] = 0;
        }
        else {
            konfirmasi['surat-pernyataan'] = 1;
            verifikasi['surat-pernyataan'] = profil['surat_pernyataan_kebenaran_dokumen'];
        }
        {else}
        konfirmasi['surat-pernyataan'] = verifikasi['surat-pernyataan'] = 1;
        {/if}

        if (profil['nomor_kontak'] == null || $.trim(profil['nomor_kontak']) == '') {
            konfirmasi['nomer-hp'] = verifikasi['nomer-hp'] = 0;
        }
        else {
            konfirmasi['nomer-hp'] = verifikasi['nomer-hp'] = 1;
        }

        //update profil flag
        flags.forEach(function(key) {
            if (key == 'kebutuhan_khusus')  return;
            profilflag[key] = parseInt(profil[key]);
        });

        //special profil flag
        profilflag['kebutuhan_khusus'] = kebutuhankhusus;
        profilflag['afirmasi'] = (profilflag['punya_kip'] || profilflag['masuk_bdt']) ? 1 : 0;

        //reset kelengkapan data
        kelengkapan_data = 1;
        tags.forEach(function(key) {
            //kalau sedang proses verifikasi (dan ada yang perlu verifikasi ulang) => tetap dianggap data sudah lengkap => bisa melakukakn pendaftaran
            if(konfirmasi[key] != 1 && konfirmasi[key] != 4) { 
                kelengkapan_data = 0;
            }
        });

        if (!konfirmasi['nomer-hp']) {
            kelengkapan_data = 0;
        }

        siswa_tutup_akses = parseInt(profil['tutup_akses']);

        //update flag pendaftaran dikunci
        flag = 0
        if (!kelengkapan_data || siswa_tutup_akses || tutup_akses==1 || diterima==1
            || (!cek_waktupendaftaran && !cek_waktusosialisasi)
        ) {
            flag = 1;
        }

        if (flag != pendaftarandikunci) {
            pendaftarandikunci=flag;
            update_pendaftaran_layout();
        }

        //redraw is necessary
        if (redraw) {
            update_profile_layout();
        }
    }

    function simpan_profil(el) {
        let select = $(el);
        let flagval = parseInt(select.val());
        let submittag = select.attr('tcg-submit-tag');

        let json = {};
        json['user_id'] = userid;
        json['data'] = {};

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
            let txtval = '';

            if (!flagval) {
                //start-editing -> store old value just in case
                this.defaultValue = this.value;
            }
            else {
                //save editing -> only submit if change
                if ((el.is('input') || el.is('select') || el.is('textarea')) && this.defaultValue != this.value) {
                    if (el.attr('type') == 'number') {
                        val = parseFloat(el.val());
                        if (isNaN(val)) val = 0;
                        txtval = val.toLocaleString("id-ID", { maximumFractionDigits:"2" });

                        val = val.toFixed(2);
                        el.val(val);
                    }
                    else {
                        val = el.val();
                        txtval = val;
                    }

                    //form data
                    json['data'][field] = val;

                    //store the label
                    if (el.is('select')) {
                        txtval = el.children(':selected').text();
                    }

                    //copy value if necessary
                    //lbl = elements.find("span[tcg-field='" +field+ "']");
                    lbl = $("span[tcg-field='" +field+ "']");
                    lbl.html(txtval);
                }
            }
        });

        //submit form
        json['data']["konfirmasi_" +submittag] = flagval;

        $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/siswa/updateprofil",
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
                    toastr.error('Tidak berhasil menyimpan data profil. ' +json.error);
                    return;
                }
                //get the return value and re-set the field
                profil = json.data;
                reload_profil();
                update_pendaftaran_layout();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error('Tidak berhasil menyimpan data profil. ' +textStatus);
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
    }

    function simpan_nomer_hp() {
        let el = $("#nomor_kontak");
        let nomor_hp = $.trim(el.val());

        //validate nomor hp
        {literal}
        let konfirmasi = 1; 
        matches = nomor_hp.match(/^[+]?[-\s./0-9]{7,15}$/g);
        if (matches == null) {
            konfirmasi = 0;
        }
        {/literal}

        if (konfirmasi == 0) {
            //TODO: alert and cancel the submit
            el.addClass("border-red");
            toastr.error("Nomor HP belum diisi atau tidak benar.");
            return;
        }

        //send update
        let json = {};
        json['user_id'] = userid;
        json['data'] = {};
        json['data']['nomor_kontak'] = nomor_hp;

        $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/siswa/updateprofil",
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
                    toastr.error("Tidak berhasil menyimpan nomor kontak. " + json.error);
                    return;
                }
                toastr.success("Nomor kontak berhasil disimpan.");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil menyimpan nomor kontak. " + textStatus);
                return;
            }
        });

        konfirmasi['nomer-hp'] = verifikasi['nomer-hp'] = konfirmasi;

        //update status
        var card = el.closest('.accordion-item');
        if (konfirmasi) {
            card.removeClass("status-danger");
            card.find(".accordion-header-text .status").html('');
        }
        else {
            card.addClass("status-danger");
            card.find(".accordion-header-text .status").html('*Belum Benar*');
        }

        el.removeClass("border-red");        
    }
</script>

<script>
		//Dropdown Select
		$(function () {
			$(".select2").select2();
		});

</script>

<script type="text/javascript" defer>
    var map;

    $(document).ready(function() {
        //Peta
        map = L.map('profil-peta',{ zoomControl:false }).setView([profil['lintang'],profil['bujur']],16);
        L.tileLayer(
        	'{$map_streetmap}',{ maxZoom: 18,attribution: '{$app_short_name} {$nama_wilayah}',id: 'mapbox.streets' }
        ).addTo(map);

        L.marker([profil['lintang'],profil['bujur']]).addTo(map)
            .bindPopup(profil['desa_kelurahan'] +", " +profil['kecamatan']+ ", " +profil['kabupaten']+ ", " +profil['provinsi']);

        var streetmap   = L.tileLayer('{$map_streetmap}', { id: 'mapbox.light', attribution: '' }),
            satelitemap  = L.tileLayer('{$map_satelitemap}', { id: 'mapbox.streets', attribution: '' });

        var baseLayers = {
            "Streets": streetmap,
            "Satelite": satelitemap
        };

        var overlays = {};
        L.control.layers(baseLayers,overlays).addTo(map);

        new L.control.fullscreen({ position:'bottomleft' }).addTo(map);
        new L.Control.Zoom({ position:'bottomright' }).addTo(map);

        //refresh the size of the map
        $("body").on("shown.bs.collapse", "#lokasi-content", function() {
            map.invalidateSize(false);
        });
    });

</script>
