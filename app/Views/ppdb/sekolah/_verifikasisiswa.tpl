
{literal}
<script id="daftar-dokumen" type="text/template">
    <table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
        {{#dokumen}}
        <tr tcg-doc-id="{{daftar_kelengkapan_id}}">
            <td><span id="row-span-dokumen-{{daftar_kelengkapan_id}}"><i class="glyphicon glyphicon-edit"></i> </span><b>{{nama}}</span></b></td>
            <td>:</td>
            <td style="width: 50%;">
                {{^flag_upload_dokumen}}
                Dicocokkan di sekolah tujuan
                {{/flag_upload_dokumen}}
                {{#flag_upload_dokumen}}
                <span tcg-doc-id="{{daftar_kelengkapan_id}}" class="dok-value" style="display: none;">{{verifikasi}}</span>
                <div class="form-check form-check-inline" style="align-items: center; display: inline-flex;">
                    <input tcg-doc-id="{{daftar_kelengkapan_id}}" class="form-check-input primary dok-pendukung" type="radio" tcg-doc-value="{{verifikasi}}"
                        name="dok-{{daftar_kelengkapan_id}}" id="dok-{{daftar_kelengkapan_id}}-1" value="1" {{#verifikasi1}}checked{{/verifikasi1}}>
                    <label class="form-check-label" for="dok-{{daftar_kelengkapan_id}}-1" style="margin-bottom: 0px;">SUDAH Benar</label>
                </div>
                <div class="form-check form-check-inline" style="align-items: center; display: inline-flex;">
                    <input tcg-doc-id="{{daftar_kelengkapan_id}}" class="form-check-input danger dok-pendukung" type="radio" tcg-doc-value="{{verifikasi}}"
                        name="dok-{{daftar_kelengkapan_id}}" id="dok-{{daftar_kelengkapan_id}}-2" value="2" {{#verifikasi2}}checked{{/verifikasi2}}>
                    <label class="form-check-label" for="dok-{{daftar_kelengkapan_id}}-2" style="margin-bottom: 0px;">BELUM Benar</label>
                </div>
                <div class="form-check form-check-inline" style="align-items: center; display: inline-flex;">
                    <input tcg-doc-id="{{daftar_kelengkapan_id}}" class="form-check-input gray dok-pendukung" type="radio" tcg-doc-value="{{verifikasi}}" 
                        name="dok-{{daftar_kelengkapan_id}}" id="dok-{{daftar_kelengkapan_id}}-3" value="3" {{#verifikasi3}}checked{{/verifikasi3}}>
                    <label class="form-check-label" for="dok-{{daftar_kelengkapan_id}}-3" style="margin-bottom: 0px;">Tidak Ada</label>
                </div>
                <img id="dokumen-{{daftar_kelengkapan_id}}" class="img-view-thumbnail" tcg-doc-id="{{daftar_kelengkapan_id}}"
                        src="{{thumbnail_path}}" 
                        img-path="{{web_path}}" 
                        img-title="{{nama}}"
                        img-id="{{dokumen_id}}" 
                        style="display:none; "/>  
                <button id="unggah-dokumen-{{daftar_kelengkapan_id}}" tcg-doc-id="{{daftar_kelengkapan_id}}" class="img-view-button" 
                    data-editor-field="dokumen_{{daftar_kelengkapan_id}}" data-editor-value="{{dokumen_id}}" >Unggah</button>
                <div id="msg-dokumen-{{daftar_kelengkapan_id}}" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                {{/flag_upload_dokumen}}
             </td>
        </tr>
        {{/dokumen}}
    </table>
</script>
{/literal}

<script>
		//Dropdown Select
		$(function () {
			$(".select2").select2();
		});

</script>

<script>

    var tags = ['profil', 'lokasi', 'nilai', 'prestasi', 'afirmasi', 'inklusi'];
    var flags = ['nilai-un', 'punya_akademik', 'punya_organisasi', 'punya_prestasi', 'kip', 'masuk_bdt', 'inklusi'];
    //var flags = ['punya_nilai_un', 'punya_prestasi', 'punya_kip', 'masuk_bdt', 'kebutuhan_khusus'];

    var verifikasi = {};
    var profilflag = {};
    var perbaikan = {};
    var tutup_akses = 1;

    var profil = {};
    var dokumen = {};

    // var dtprestasi;
    var dtriwayat;

    $(document).ready(function() {    

        dtriwayat = $('#triwayat').DataTable({
            "responsive": true,
            "paging": false,
            "dom": 't',
            "buttons": [
            ],
            ajax: "",
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
            // if (card.prop('id') == 'prestasi') {
            //     dtprestasi.columns.adjust().responsive.recalc();
            // }

            //resize table
            if (card.prop('id') == 'riwayat') {
                dtriwayat.columns.adjust().responsive.recalc();
            }
        });

        $("[tcg-field-type='status']").on("change", function(evt) {
            let select = $(this);
            let flagval = parseInt(select.val());
            let submittag = select.attr('tcg-tag');

            let formdata = new FormData();

            // btn = $(".btn-perbaikan[tcg-tag='" +submittag+ "']");
            // if (flagval != 2) {
            //     btn.hide();
            // } else {
            //     btn.show();
            // }

            el = $("tr.catatan[tcg-tag='" +submittag+ "']");
            if (flagval == 2 || flagval == 3 || perbaikan[submittag]) {
                el.show();
            } else {
                el.hide();
            }

            //dont send data partially -> send as a whole when use click button "Simpan"

            //set border to red to notify
            el.removeClass("border-red");

            //store value
            verifikasi[submittag] = flagval;
            this.defaultValue = this.value;

            //update status
            let card = $("#" +submittag);
            if (flagval == 0) {
                card.addClass("status-warning");
                card.removeClass("status-danger");
                card.find(".accordion-header-text .status").html('*Belum Diverifikasi*');
            }
            else if (flagval == 2){
                card.removeClass("status-warning");
                card.addClass("status-danger");
                card.find(".accordion-header-text .status").html('*Belum Benar*');
            }
            else if (flagval == 3){
                card.removeClass("status-warning");
                card.addClass("status-danger");
                card.find(".accordion-header-text .status").html('*Perbaikan Data*');
            }
            else {
                card.removeClass("status-warning");
                card.removeClass("status-danger");
                card.find(".accordion-header-text .status").html('');
            }

            //perbaiki data
            if (flagval == 3) {
                //show input element
                let elements = $("[tcg-tag='" +submittag+ "']");
                elements.each(function(idx) {
                    //action
                    el = $(this);
                    type = el.attr('tcg-field-type');

                    if (type == 'input') el.show();
                    else if (type == 'label') el.hide();
                    else if (type == 'toggle')  el.attr('disabled', false);
    
                    //save default value in case we need to revert
                    if (el.is('input') || el.is('select')) {
                        this.defaultValue = this.value;
                    }
                });

                elements.filter(".btn-kembalikan").hide();

                //show btn save
                elements.filter(".btn-simpan").show();
                elements.filter(".btn-batal").show();

                //hide btn perbaikan
                //btn.hide();

                //special case
                if (submittag == 'lokasi') {
                    map_enable_edit = true;
                }

                //disable the status flag
                elements.filter(".status-verifikasi").attr("disabled", true);

                //catatan
                elements.filter(".catatan").show();
            }
        });

        $("[tcg-field-type='toggle']").on("change", function(evt) {
            select = $(this);
            value = parseInt(select.val());
            toggletag = select.attr('tcg-toggle-tag');

            elements = $("[tcg-visible-tag='" +toggletag+ "']");
            if (value) { elements.show(); }
            else { elements.hide(); }

            //update flag
            profilflag[toggletag] = value;
            
            //special case: afirmasi
            if (toggletag == 'kip' || toggletag == 'masuk_bdt') {
                elements = $("[tcg-visible-tag='afirmasi']");
                if (!profilflag['kip'] && !profilflag['masuk_bdt']) {
                    elements.hide();
                }
                else {
                    elements.show();
                }
            }

            // //special case: prestasi
            // if (toggletag == 'prestasi') {
            //     if (profilflag['prestasi']) {
            //         dtprestasi.columns.adjust().responsive.recalc();
            //     }
            // }
        });

        // $(".btn-perbaikan").on("click", function(e) {
        //     btn = $(this);
        //     tag = btn.attr("tcg-tag");

        //     //val: set to false to edit
        //     flagval = false;

        //     //show input element
        //     let elements = $("[tcg-tag='" +tag+ "']");
        //     elements.each(function(idx) {
        //         //action
        //         el = $(this);
        //         type = el.attr('tcg-field-type');

        //         if (type == 'input') el.show();
        //         else if (type == 'label') el.hide();
        //         else if (type == 'toggle')  el.attr('disabled', false);
 
        //         //save default value in case we need to revert
        //         if (el.is('input') || el.is('select')) {
        //             this.defaultValue = this.value;
        //         }
        //     });

        //     elements.filter(".btn-kembalikan").hide();

        //     //show btn save
        //     elements.filter(".btn-simpan").show();
        //     elements.filter(".btn-batal").show();

        //     //hide btn perbaikan
        //     btn.hide();

        //     //special case
        //     if (tag == 'lokasi') {
        //         map_enable_edit = true;
        //     }

        //     //disable the status flag
        //     elements.filter(".status-verifikasi").attr("disabled", true);

        // });

        $(".btn-kembalikan").on("click", function(e) {
            btn = $(this);
            tag = btn.attr("tcg-tag");

            $.confirm({
                title: 'Batalkan Perubahan',
                content: '<p>Batalkan perubahan dan kembalikan ke data awal?</p>',
                icon: 'fa fa-question-circle',
                animation: 'scale',
                closeAnimation: 'scale',
                opacity: 0.5,
                buttons: {
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-secondary',
                    },
                    'confirm': {
                        text: 'Kembalikan',
                        btnClass: 'btn-primary',
                        action: function(){
                            let elements = $("[tcg-tag='" +tag+ "']");
                            elements.each(function(idx) {
                                el = $(this);
                                if (el.hasClass("status-verifikasi")) return;

                                //get the value
                                key = el.attr('tcg-field');
                                value = profil[key];

                                //set the value
                                type = el.attr('tcg-field-type');
                                if (type == 'input') {
                                    //if number, try to convert it
                                    if (el.attr('type') == 'number') {
                                        value = parseFloat(value);
                                        if (isNaN(value)) value = 0;
                                        //save back for easier comparison
                                        profil[key] = value;
                                    }
                                    el.val(value);
                                }
                                else if (type == 'label') {
                                    el.html(value);
                                }
                                else if (type == 'href') {
                                    el.attr('href', value);
                                }
                                else if (type == 'toggle') {
                                    el.val(value);
                                }

                                //trigger related change
                                el.trigger("change");
                            });

                            //hide tombol revert
                            elements.filter(".btn-kembalikan").hide();   
                        
                            //enable the status flag
                            elements.filter(".status-verifikasi").attr("disabled", false);
                            elements.filter(".status-verifikasi").val(0);   //belum verifikasi

                            //reset tampilan
                            let card = $("#" +tag);
                            card.addClass("status-warning");
                            card.removeClass("status-danger");
                            card.find(".accordion-header-text .status").html('*Belum Diverifikasi*');

                            //catatan
                            elements.filter(".catatan").removeClass("border-red");
                            el = $("tr.catatan[tcg-tag='" +tag+ "']");
                            el.hide();

                            //map
                            layerGroup.clearLayers();

                            //flag
                            perbaikan[tag] = 0;
                        }
                    },
                }
            });


        });

        $(".btn-simpan").on("click", function(e) {
            btn = $(this);
            tag = btn.attr("tcg-tag");

            //val: set to true to make read-only
            flagval = true;

            let elements = $("[tcg-tag='" +tag+ "']");
            elements.each(function(idx) {
                //action
                el = $(this);
                if (el.hasClass("status-verifikasi")) return;

                type = el.attr('tcg-field-type');
                if (type == 'input') el.hide();
                else if (type == 'label') el.show();
                else if (type == 'toggle')  el.attr('disabled', true);

                //copy value
                if (type == 'input') {
                    if (el.is('select')) {
                        //select
                        let field = el.attr('tcg-field');
                        if (field == null || field == '') return;

                        //get value and label
                        let val = 0, str = '';
                        val = el.val();
                        str = el.children(':selected').text();

                        //copy label not the value
                        lbl = elements.filter("span[tcg-field='" +field+ "']");
                        lbl.html(str); 

                        //save to default value
                        this.defaultValue = this.value;
                        
                        //check for updated value
                        oldval = profil[field];
                        if (el.attr('type') == 'number') {
                            oldval = parseFloat(oldval);
                            if (isNaN(oldval)) oldval = 0;
                        }

                        if (val != oldval) {
                            perbaikan[tag] = 1;
                        }
                    }
                    else {
                        //normal input
                        let field = el.attr('tcg-field');
                        if (field == null || field == '') return;

                        let val = 0;
                        let txtval = '';
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

                        //copy value if necessary
                        lbl = elements.filter("span[tcg-field='" +field+ "']");
                        lbl.html(txtval); 

                        //save to default value
                        this.defaultValue = this.value;
                        
                        //check for updated value
                        oldval = profil[field];
                        if (el.attr('type') == 'number') {
                            oldval = parseFloat(oldval);
                            if (isNaN(oldval)) oldval = 0;
                        }

                        if (val != oldval) {
                            perbaikan[tag] = 1;
                        }
                    }
                }
                else if (type == 'toggle') {
                    let field = el.attr('tcg-field');
                    if (field == null || field == '') return;

                    //check for updated value
                    val = el.val();
                    oldval = profil[field];
                    if (val != oldval) {
                        perbaikan[tag] = 1;
                    }
                }
                else if (type == 'select') {
                }
            });

            // //show perbaikan save
            // elements.filter(".btn-perbaikan").show();

            //hide btn simpan
            elements.filter(".btn-simpan").hide();
            elements.filter(".btn-batal").hide();

            //ada perbaikan data?
            if (perbaikan[tag]) {
                //tombol revert
                elements.filter(".btn-kembalikan").show();
                //harus ada catatan perbaikan
                elements.filter(".catatan").removeClass("border-red");
                el = $("tr.catatan[tcg-tag='" +tag+ "']");
                el.show();
            }
            else {
                //tombol revert
                elements.filter(".btn-kembalikan").hide();
                //harus ada catatan perbaikan
                elements.filter(".catatan").removeClass("border-red");
                el = $("tr.catatan[tcg-tag='" +tag+ "']");
                el.hide();
            }

            //special case
            if (tag == 'lokasi') {
                map_enable_edit = false;
            }

            //enable the status flag
            elements.filter(".status-verifikasi").attr("disabled", false);
            elements.filter(".status-verifikasi").val(1);   //sudah benar

            //update card status
            let card = $("#" +tag);
            card.removeClass("status-warning");
            card.removeClass("status-danger");
            card.find(".accordion-header-text .status").html('');

        });

        $(".btn-batal").on("click", function(e) {
            btn = $(this);
            tag = btn.attr("tcg-tag");

            //val: set to true to make read-only
            flagval = true;

            let elements = $("[tcg-tag='" +tag+ "']");
            elements.each(function(idx) {
                //action
                el = $(this);
                if (el.hasClass("status-verifikasi")) return;

                //revert to default value
                if (el.is('input') || el.is('select')) {
                    el.val(this.defaultValue).trigger("change");
                }            

                //visibility
                type = el.attr('tcg-field-type');
                if (type == 'input') el.hide();
                else if (type == 'label') el.show();
                else if (type == 'toggle')  el.attr('disabled', true);
            });

            // //show perbaikan save
            // elements.filter(".btn-perbaikan").show();

            //hide btn simpan
            elements.filter(".btn-simpan").hide();
            elements.filter(".btn-batal").hide();

            //special case
            if (tag == 'lokasi') {
                map_enable_edit = false;
            }

            //catatan
            elements.filter(".catatan").removeClass("border-red");
            el = $("tr.catatan[tcg-tag='" +tag+ "']");
            el.hide();

            //enable the status flag
            elements.filter(".status-verifikasi").attr("disabled", false);
            elements.filter(".status-verifikasi").val(0);   //belum diverifikasi

            //update card status
            let card = $("#" +tag);
            card.addClass("status-warning");
            card.removeClass("status-danger");
            card.find(".accordion-header-text .status").html('*Belum Diverifikasi*');

        });

        $(".ctx-batal").on("click", function(e) {
            close_verifikasi();
        });

        $(".ctx-simpan").on("click", function(e) {
            simpan_verifikasi();
        });

        $('#kode_provinsi').on('change', function() {
			var data = { "kode_wilayah":$("#kode_provinsi").val() };
			$.ajax({
				type: "POST",
				url : "{$site_url}home/ddkabupaten",
				data: data,
				success: function(msg){
					$('#kode_kabupaten').html(msg);
                    $('#kode_kabupaten').val(profil['kode_kabupaten']).trigger("change");
				}
			});
		});

        $('#kode_kabupaten').on('change', function() {
			var data = { "kode_wilayah":$("#kode_kabupaten").val() };
			$.ajax({
				type: "POST",
				url : "{$site_url}home/ddkecamatan",
				data: data,
				success: function(msg){
					$('#kode_kecamatan').html(msg);
                    $('#kode_kecamatan').val(profil['kode_kecamatan']).trigger("change");
				}
			});
		});

		$('#kode_kecamatan').on('change', function() {
			var data = { "kode_wilayah":$("#kode_kecamatan").val() };
			$.ajax({
				type: "POST",
				url : "{$site_url}home/dddesa",
				data: data,
				success: function(msg){
					$('#kode_wilayah').html(msg);
                    $('#kode_wilayah').val(profil['kode_wilayah']).trigger("change");
				}
			});
		});



    });

    function dokpendukung_onchange(e) {
        if (!this.checked)  return;

        //check status all dok-pendukung and update the card colour accordingly
        el = $(this);
        val = el.val();

        let card = $("#dokumen");
        if (val == 2 || val == 3) {
            card.removeClass("status-warning");
            card.addClass("status-danger");
            card.find(".accordion-header-text .status").html('*Belum Lengkap*');
            return;
        }

        sudahlengkap = 1;
        belumverifikasi = 1;

        values = $(".dok-value");
        elements = $(".dok-pendukung");
        values.each(function(dom) {
            el = $(this);
            let docid = el.attr('tcg-doc-id');

            //el = $(".dok-pendukung[tcg-doc-id='" +docid+ "']:checked"); //
            //Important: jquery.find() only return first match, and somehow it doesnt work well with attribute search
            el = elements.filter("[tcg-doc-id='" +docid+ "']:checked");
            if (el.length == 0) {
                sudahlengkap = 0;
                return;
            }

            val = el.val();
            if (val!=1 && val!=4) sudahlengkap = 0;
            if (val!=0) belumverifikasi = 0;
        });

        if (belumverifikasi) {
            card.addClass("status-warning");
            card.removeClass("status-danger");
            card.find(".accordion-header-text .status").html('*Belum Diverifikasi*');
        }
        else if (sudahlengkap){
            card.removeClass("status-warning");
            card.removeClass("status-danger");
            card.find(".accordion-header-text .status").html('');
        }
        else {
            card.removeClass("status-warning");
            card.addClass("status-danger");
            card.find(".accordion-header-text .status").html('*Belum Lengkap*');
        }
    }
    
    function show_profile(profil, dokumen) {
 
        //additional fields
        profil['jenis_kelamin_label'] = (profil['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan';
        profil['desa_kelurahan_label'] = (profil['desa_kelurahan'] != '') ? profil['desa_kelurahan'] : 'Desa/Kelurahan masih kosong';
        profil['punya_kebutuhan_khusus'] = (profil['kebutuhan_khusus'] == 'Tidak ada') ? '0' : '1';
        profil['punya_dokumen_pendukung'] = (dokumen != null && dokumen.length>0);

        keys = Object.keys(profil);
        
        //update lokal value
        tutup_akses = profil.tutup_akses;

        verifikasi = {};
        tags.forEach(function(key) {
            verifikasi[key] = profil["verifikasi_" +key];
            perbaikan[key] = 0;
        });

        profilflag = {};
        profilflag['nilai-un'] = parseInt(profil['punya_nilai_un']);
        profilflag['prestasi'] = parseInt(profil['punya_prestasi']);
        profilflag['kip'] = parseInt(profil['punya_kip']);
        profilflag['masuk_bdt'] = parseInt(profil['masuk_bdt']);
        profilflag['inklusi'] = profil['kebutuhan_khusus'] == 'Tidak ada' ? 0 : 1;

        flag_provinsi = flag_kabupaten = flag_kecamatan = flag_desa = 0;

        //set dom field value
        keys.forEach(function(key) {
            value = profil[key];
            txtvalue = '';
            elements = $("[tcg-field='" +key+ "']");
            elements.each(function(idx) {
                el = $(this);
                type = el.attr('tcg-field-type');
                if (type == 'input') {
                    curval = el.val();
                    //if number, try to convert
                    if (el.attr('type') == 'number') {
                        value = parseFloat(value);
                        if (isNaN(value)) value = 0;
                        //save back for easier comparison
                        profil[key] = value;
                    }
                    //only set if it is a new value
                    if (value != curval) {
                        el.val(value);
                        el.attr("defaultValue", value);
                        if (key=='kode_provinsi') {
                            //cascading code wilayah. trigger change later
                            flag_provinsi = 1;
                        }
                        else if (key=='kode_kabupaten') {
                            //cascading code wilayah. trigger change later
                            flag_kabupaten = 1;
                        } 
                        else if (key=='kode_kecamatan') {
                            //cascading code wilayah. trigger change later
                            flag_kecamatan = 1;
                        }
                        else if (key=='kode_wilayah') {    
                            //cascading code wilayah. trigger change later
                            flag_desa = 1;
                        }
                        else {
                            el.trigger('change');
                        }
                    }
                }
                else if (type == 'label') {
                    //use init-field for label
                    lblfield = el.attr('tcg-init-field');
                    if (lblfield !== undefined && profil[lblfield] !== undefined) {
                        value = profil[lblfield];
                    }
                    //if number, convert to locale
                    format = el.attr('type');
                    if (format == "number") {
                        value = parseFloat(value);
                        if (isNaN(value)) value = 0;
                        //txtval
                        value = value.toLocaleString("id-ID", { maximumFractionDigits:"2" });                  
                    }
                    el.html(value);
                }
                else if (type == 'href') {
                    el.attr('href', value);
                }
                else if (type == 'toggle') {
                    el.val(value);
                }
                else if (type == 'note') {
                    el.val(value);
                }
                else if (type == 'status') {
                    el.val(value);
                }
            });
        });

        //special case for cascading kode_wilayah -> trigger from the top
        if (flag_provinsi) {
            el = $("#kode_provinsi");
            el.trigger('change');
        }
        else if (flag_kabupaten) {
            el = $("#kode_kabupaten");
            el.trigger('change');
        }
        else if (flag_kecamatan) {
            el = $("#kode_kecamatan");
            el.trigger('change');
        }
        else if (flag_desa) {
            el = $("#kode_wilayah");
            el.trigger('change');
        }

        //dokumen pendukung
        if (dokumen != null && dokumen.length > 0) {
            let template = $('#daftar-dokumen').html();
            Mustache.parse(template);

            let dom = Mustache.render(template, {
                'dokumen': dokumen,
                'flag_upload_dokumen': {$flag_upload_dokumen|default: 0}
            });
 
            let parent = $("#daftar-dokumen-wrapper");
            parent.html(dom);
            
            //event handler
            parent.on('change', '.dok-pendukung', dokpendukung_onchange);

            $("#dokumen").show();
        }
        else {
            $("#dokumen").hide();
        }

        //current value
        elements = $("[tcg-edit-action='submit']");
        elements.each(function(idx) {
            el = $(this);
            tag = el.attr('tcg-tag');
            val = parseInt(el.val());
            //store current value
            verifikasi[tag] = val;
            this.defaultValue = val;
        })

        $('#nama-siswa').html(profil['nama']);

        //update layout (hide/show relevant dom)
        update_profile_layout();

        //update riwayat
        dtriwayat.ajax.url("{$site_url}ppdb/sekolah/verifikasi/riwayat?peserta_didik_id=" +profil['peserta_didik_id']);
        dtriwayat.ajax.reload();

        //show nomor kontak
        $(".ctx-handphone").html( profil['nomor_kontak'] );
        
        //update marker
        lintang = parseFloat(profil['lintang']);
        bujur = parseFloat(profil['bujur']);
        if (!isNaN(lintang) && lintang != 0 && !isNaN(bujur) && bujur != 0) {
            map.setView([lintang,bujur],16);
 
            layerGroup.clearLayers();
            L.marker([lintang, bujur]).addTo(layerGroup)
            .bindPopup(profil['desa_kelurahan']+ ", " +profil['kecamatan']+ ", " +profil['kabupaten']+ ", " +profil['provinsi']).openPopup();
        }

    }

    function update_profile_layout() {

        //flag: visibility
        flags.forEach(function(key) {
            value = profilflag[key];
            if (value) { $("[tcg-visible-tag='" +key+ "']").show(); }
            else { $("[tcg-visible-tag='" +key+ "']").hide(); }
        });

        //special case: afirmasi
        elements = $("[tcg-visible-tag='afirmasi']");
        if (!profilflag['kip'] && !profilflag['masuk_bdt']) {
            elements.hide();
        }
        else {
            elements.show();
        }

        //verifikasi: editability
        tags.forEach(function(key) {
            value = verifikasi[key];
            elements = $("[tcg-tag='" +key+ "']");
            elements.each(function(idx) {
                el = $(this);
                //always in read-only unless button perbaikan is clicked
                type = el.attr('tcg-field-type');
                if (type == "label")        el.show();
                else if (type == "input")   el.hide();
                else if (type == "toggle")  el.attr("disabled",true);
                //except for status select
                action = el.attr('tcg-edit-action');
                if (action == 'submit')     el.show();
            });

            //hide/show button
            buttons = elements.filter(".btn");
            buttons.hide();

            //only show catatan ketika "belum benar"
            el = elements.filter("tr.catatan");
            if (value != 2) {
                el.hide();
            }
            else {
                el.show();
            }

            // btn = elements.filter(".btn-perbaikan");
            // if (value != 2) {
            //     btn.hide();
            // }
            // else {
            //     btn.show();
            // }

            btn = elements.filter(".btn-kembalikan");
            if (perbaikan[key]) {
                btn.show();
            }
            else {
                btn.hide();
            }

            //dikonfirmasi oleh system -> hide dok pendukung
            if (value == 4) {
                //hide dok pendukung
                elements = $("[tcg-visible-tag='" +key+ "']");
                elements.each(function(idx) {
                    el = $(this);
                    if (el.hasClass('dokumen-pendukung')) {
                        el.hide();
                    }
                });
            };

            //hide/show verifikasi button
            let ver_sistem = $("#" +key+ "-verifikasi-row .verifikasi-sistem");
            let ver_manual = $("#" +key+ "-verifikasi-row .verifikasi-manual");
            if (value == 4) {
                ver_sistem.show();
                ver_manual.hide();
            }
            else {
                ver_sistem.hide();
                ver_manual.show();
            }

            let card = $("#" +key);
            if (value == 0) {
                card.addClass("status-warning");
                card.removeClass("status-danger");
                card.find(".accordion-header-text .status").html('*Belum Diverifikasi*');
            }
            else if (value == 2){
                card.removeClass("status-warning");
                card.addClass("status-danger");
                card.find(".accordion-header-text .status").html('*Belum Benar*');
            }
            else if (value == 4){
                card.removeClass("status-warning");
                card.removeClass("status-danger");
                card.find(".accordion-header-text .status").html('Data Sesuai Sistem');
            }
            else {
                card.removeClass("status-warning");
                card.removeClass("status-danger");
                card.find(".accordion-header-text .status").html('');
            }

        });
        
        //dokumen pendukung
        {if $flag_upload_dokumen}
        //show status verifikasi
        sudahlengkap = 1;
        belumverifikasi = 1;

        values = $(".dok-value");
        elements = $(".dok-pendukung");
        values.each(function(dom) {
            el = $(this);
            let docid = el.attr('tcg-doc-id');

            //Important: jquery.find() only return first match, and somehow it doesnt work well with attribute search
            el = elements.filter("[tcg-doc-id='" +docid+ "']:checked");
            if (el.length == 0) {
                sudahbenar = 0;
                return;
            }

            val = el.val();
            if (val!=1 && val!=4) sudahlengkap = 0;
            if (val!=0) belumverifikasi = 0;
        });

        let card = $("#dokumen");
        if (belumverifikasi) {
            card.addClass("status-warning");
            card.removeClass("status-danger");
            card.find(".accordion-header-text .status").html('*Belum Diverifikasi*');
        }
        else if (sudahlengkap){
            card.removeClass("status-warning");
            card.removeClass("status-danger");
            card.find(".accordion-header-text .status").html('');
        }
        else {
            card.removeClass("status-warning");
            card.addClass("status-danger");
            card.find(".accordion-header-text .status").html('*Belum Lengkap*');
        }
        {else}
        let card = $("#dokumen");
        card.removeClass("status-warning");
        card.removeClass("status-danger");
        card.find(".accordion-header-text .status").html('');
        {/if}

        //kunci data?
        if (cek_waktuverifikasi==1 || cek_waktusosialisasi==1 || impersonasi_sekolah==1) {
            $(".status-verifikasi").attr('disabled', false);
            $(".btn-kembalikan").attr('disabled', false);
            $(".btn-batal").attr('disabled', false);
            $(".btn-simpan").attr('disabled', false);
            $(".ctx-simpan").attr('disabled', false);
            $(".ctx-simpan").show();
            $(".ctx-batal").text(' Batal ');
        }
        else {
            $(".status-verifikasi").attr('disabled', true);
            $(".btn-kembalikan").attr('disabled', true);
            $(".btn-batal").attr('disabled', true);
            $(".btn-simpan").attr('disabled', true);
            $(".ctx-simpan").attr('disabled', true);
            $(".ctx-simpan").hide();
            $(".ctx-batal").text(' Tutup ');
        }


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

    function verifikasi_pendaftaran(pendaftaran_id) {
        //show loading
        loader = $("#loader");
        loader.show();

        //get profil data
        let formdata = new FormData();
        formdata.append("pendaftaran_id", pendaftaran_id)

        $.ajax({
            type: "POST",
            url: "{$site_url}ppdb/sekolah/verifikasi/profilsiswa",
            async: true,
            data: formdata,
            cache: false,
            contentType: false,
            processData: false,
            timeout: 60000,
            dataType: 'json',
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error(json.error);
                    return;
                }
                //simpan for later
                profil = json.data.profil;
                dokumen = json.data.dokumen;

                //set null to ''. simplify everything
                keys = Object.keys(profil);
                keys.forEach(function(key) {
                    if (profil[key] == null) {
                        profil[key] = '';
                    }
                });

                //reset the content
                //json.data.dokumen
                //json.data.prestasi
                show_profile(json.data.profil, json.data.dokumen);
            
                loader.hide();

                //layout
                $("#tabs").hide();

                verifikasi = $("#verifikasi");
                verifikasi.show();

                let width = $(window).width();
                let header_offset = 230;
                if (width < 785) {
                    header_offset = 100;
                }
                else if (width < 1040) {
                    header_offset = 100;
                }

                $('html,body').animate({
                    scrollTop: verifikasi.offset().top - header_offset
                }, 100);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error(textStatus);
                loader.hide();
                return;
            }
        });
       
    }

    function close_verifikasi() {
        $("#verifikasi").hide();

        //enable status -> in case disabled by perbaikan data
        $(".status-verifikasi").prop('disabled', false);

        tabs = $("#tabs");
        tabs.show();

        let width = $(window).width();
        let header_offset = 230;
        if (width < 785) {
            header_offset = 100;
        }
        else if (width < 1040) {
            header_offset = 100;
        }
        
        $('html,body').animate({
            scrollTop: tabs.offset().top - header_offset
        }, 100);
    }

    function simpan_verifikasi() {
        //check active perbaikan
        perbaikan = 0;
        elements = $(".status-verifikasi");
        elements.each(function(idx) {
            el = $(this);
            tag = el.attr('tcg-tag');

            if (el.val()==3) {
                let card = $("#" +tag);

                msg = "Perbaikan data " +tag.toUpperCase()+ " belum dikonfirmasi!";
                toastr.info(msg);

                card.removeClass("status-warning");
                card.addClass("status-danger");
                card.find(".accordion-header-text .status").html('*' +msg+ '*');
                elements.filter(".catatan").addClass("border-red");

                perbaikan = 1;
            }
        });

        //kalau ada perbaikan, cancel
        if (perbaikan)  return;

        updated = {};
        tosubmit = true;

        tags.forEach(function(key) {
            elements = $("[tcg-tag='" +key+ "']");
            elements.each(function(idx) {
                el = $(this);

                //data to submit
                let field = el.attr('tcg-field');
                if (field == null || field == '') return;

                let submit = el.attr("tcg-field-submit");
                if (!submit || submit == 0) return;

                val = el.val();
                oldval = profil[field];
                if (oldval === undefined)   oldval = '';
                if (el.attr('type') == 'number') {
                    val = parseFloat(val);
                    if (isNaN(val)) val = 0;
                    oldval = parseFloat(oldval);
                    if (isNaN(oldval)) oldval = 0;
                }

                //console.log("tosubmit:" +tosubmit+ "; field: " +field+ "; oldval: " +oldval+ "; newval: " +val);

                //only get updated value
                if (val != oldval) {
                    updated[field] = val;
                }
            });

            let card = $("#" +key);

            //check for catatan verifikasi
            status = elements.filter(".status-verifikasi").val();
            if (perbaikan[key] && updated["catatan_" +key] === undefined) {
                msg = "Catatan perbaikan " +key.toUpperCase()+ " harus diisi!";
                toastr.info(msg);

                card.removeClass("status-warning");
                card.addClass("status-danger");
                card.find(".accordion-header-text .status").html('*' +msg+ '*');
                elements.filter(".catatan").addClass("border-red");

                //dont submit
                tosubmit = false;
                
            }
            else if (status == 2 && updated["catatan_" +key] === undefined && profil["catatan_" +key] == "") {
                msg = "Catatan verifikasi " +key.toUpperCase()+ " harus diisi!";
                toastr.info(msg);

                card.removeClass("status-warning");
                card.addClass("status-danger");
                card.find(".accordion-header-text .status").html('*' +msg+ '*');
                elements.filter(".catatan").addClass("border-red");

                //dont submit
                tosubmit = false;
            }

            //collapse the card
            card.find(".accordion__body").removeClass("show");
            card.find(".accordion-header").addClass("collapsed");
        });

        if (!tosubmit)  {
            toastr.error("Catatan verifikasi/perbaikan harus diisi.");
            return false;
        }

        //check verifikasi dokumen pendukung
        dokumen = {};

        {if $flag_upload_dokumen}
        values = $(".dok-value");
        elements = $(".dok-pendukung");
        values.each(function(dom) {
            el = $(this);
            let docid = el.attr('tcg-doc-id');

            //get old/original value
            oldval = el.text();
            if (oldval === undefined || isNaN(oldval))   oldval = 0
            else oldval = parseInt(oldval);

            //get current value
            val = 0;
            el = elements.filter("[tcg-doc-id='" +docid+ "']:checked");
            if (el.length > 0) {
                val = el.val();
            }

            //only send updated value
            if (val != oldval) {
                dokumen[docid] = val;
            }
        });
        {/if}

        //Important: must use Object.keys(updated).length because updated.length == undefined
        if (Object.keys(updated).length == 0 && Object.keys(dokumen).length == 0) {
            toastr.info("Tidak ada perubahan data verifikasi an. " +profil['nama']);
            close_verifikasi();
            return true;
        }

        json = {};
        // json[ profil['peserta_didik_id'] ]['test'] = 1;
        json['peserta_didik_id'] = profil['peserta_didik_id'];
        json['data'] = {};
        json['data']['profil'] = updated;
        json['data']['dokumen'] = dokumen;

        //console.log(json);

        status = $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/sekolah/verifikasi/simpan",
            dataType: 'json',
            data: json,
            async: false,
            cache: false,
            //if we use formData, set processData = false. if we use json, set processData = true!
            //contentType: true,
            //processData: true,      
            timeout: 60000,
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error("Tidak berhasil menyimpan data verifikasi siswa: " +json.error);
                    return false;
                }
                toastr.success("Data verifikasi siswa an. " +profil['nama']+ " berhasil disimpan.");

                //refresh the list
                verifikasi_siswa = 0;
                verifikasi_refresh();

                //close the window
                close_verifikasi();
                return true;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //TODO
                toastr.error("Tidak berhasil menyimpan data verifikasi siswa: " +textStatus);
                verifikasi_siswa = 0;
                return false;
            }
        });

        return false;
    }
</script>

<script>

    //Peta
    var map = null;
    var map_enable_edit = false;
    var layerGroup = null;

    $(document).ready(function() {
        map = L.map('peta',{ zoomControl:false }).setView([{$map_lintang},{$map_bujur}],16);
		L.tileLayer(
			'{$map_streetmap}',{ maxZoom: 18,attribution: '{$app_short_name} {$nama_wilayah}',id: 'mapbox.streets' }
		).addTo(map);

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

        new L.Control.EasyButton( '<span class="map-button" style="font-size: 30px;">&curren;</span>', function(){
            map.setView([{$map_lintang},{$map_bujur}],10);
        }, { position: 'topleft' }).addTo(map);

        var greenMarker = new L.Icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        layerGroup = L.layerGroup().addTo(map);
        function onMapClick(e) {
            if (!map_enable_edit)   return;

            layerGroup.clearLayers();
            var lintang = e.latlng.lat;
            var bujur = e.latlng.lng;
            new L.marker(e.latlng, { icon: greenMarker }).addTo(layerGroup).bindPopup("Koordinat Baru :<br>"+lintang+" , "+bujur).openPopup();
            document.getElementById("lintang-input").value=lintang;
            document.getElementById("bujur-input").value=bujur;
        }

        map.on('click', onMapClick);

		// L.marker([{$map_lintang},{$map_bujur}]).addTo(layerGroup)
        //     .bindPopup("");


        // var searchControl = L.esri.Geocoding.geosearch().addTo(map);
        // searchControl.on('layerGroup', function(data){
        //     layerGroup.clearLayers();
        // });

        //streetmap.addTo(map);

        //refresh the size of the map
        $("body").on("shown.bs.collapse", "#lokasi-content", function() {
            map.invalidateSize(false);
        });
    });

</script>