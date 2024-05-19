
{literal}
<script id="daftar-dokumen" type="text/template">
    <table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
        {{#dokumen}}
        <tr>
            <td><span id="row-span-dokumen-{$fields.daftar_kelengkapan_id}}"><i class="glyphicon glyphicon-edit"></i> </span><b>{{nama}</span></b></td>
            <td>:</td>
            <td style="width: 50%;">
                {if !($flag_upload_dokumen)}
                Dicocokkan di sekolah tujuan
                {else}
                    <img id="dokumen-{{daftar_kelengkapan_id}}" class="img-view-thumbnail" tcg-doc-id="{{daftar_kelengkapan_id}}"
                            src="{$fields['thumbnail_path']}}" 
                            img-path="{$fields['web_path']}}" 
                            img-title="{$fields['nama']}}"
                            img-id="{$fields['dokumen_id']}}" 
                            style="display:none; "/>  
                    <button id="unggah-dokumen-{{daftar_kelengkapan_id}}" tcg-doc-id="{{daftar_kelengkapan_id}}" class="img-view-button" 
                        data-editor-field="dokumen_{{daftar_kelengkapan_id}}" data-editor-value="{{dokumen_id}}" >Unggah</button>
                    <div id="msg-dokumen-{{daftar_kelengkapan_id}}" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                {/if}
            </td>
        </tr>
        {{/dokumen}}
    </table>
</script>
{/literal}

<script>

    var tags = ['profil', 'lokasi', 'nilai', 'prestasi', 'afirmasi', 'inklusi'];
    var flags = ['nilai-un', 'prestasi', 'kip', 'bdt', 'inklusi'];

    var verifikasi = {};
    var profilflag = {};
    var perbaikan = {};
    var tutup_akses = 1;

    var profil = {};
    var dokumen = {};

    var dtprestasi;
    var dtriwayat;

    $(document).ready(function() {    

        //all datatable must be responsive
        $.extend( $.fn.dataTable.defaults, { responsive: true } );

        dtprestasi = $('#tbl-prestasi').DataTable({
            responsive: true,
            "paging": false,
            "dom": "Brt",
            select: true,
            buttons: [
                {
                    text: 'Prestasi Baru',
                    className: 'btn-sm btn-primary btn-add',
                    action: function ( e, dt, node, conf ) {
                        dtprestasi_add(e, dt, node, conf);
                    },
                },
                {
                    text: 'Hapus Prestasi',
                    className: 'btn-sm btn-danger btn-delete',
                    action: function ( e, dt, node, conf ) {
                        dtprestasi_delete(e, dt, node, conf);
                    },
                },
            ],
            ajax: "",
            "language": {
                "processing":   "Sedang proses...",
                "lengthMenu":   "Tampilan _MENU_ entri",
                "zeroRecords":  "Tidak ditemukan data yang sesuai",
                "loadingRecords": "Loading...",
                "emptyTable":   "Tidak ditemukan data yang sesuai",
                },
            columns: [
                // { "defaultContent": "" },
                { data: "prestasi_id", className: 'dt-body-center', "orderable": false },
                { data: "prestasi", className: 'dt-body-left', "orderable": false },
                { data: "uraian", className: 'dt-body-left', "orderable": false },
                { data: "dokumen_pendukung", className: 'dt-body-left editable', "orderable": false,
                    render: function ( file_id, type, row ) {
                        {if !($flag_upload_dokumen)}
                        return "Dicocokkan di sekolah tujuan";
                        {else}
                        if (type === 'display') {
                            if (typeof editprestasi.file( 'files', file_id ) === "undefined") {
                                return "";
                            }
                            
                            let str= '<img class="img-view-thumbnail" src="'+editprestasi.file( 'files', file_id ).thumbnail_path+'" img-title="'+row.uraian+'" img-path="'+editprestasi.file( 'files', file_id ).web_path+'"/>';

                            let verifikasi = row.verifikasi;
                            if (verifikasi == 2) {
                                str += ' <button class="img-view-button editable-prestasi" data-editor-field="dokumen_pendukung" data-editor-value="' +file_id+ '" data-editor-id="' +row.prestasi_id+ '" >Unggah</button>';
                                str += '<div class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px;">' +row.catatan+ '</div>'
                            }

                            return str;
                        }
                        else {
                            row.filename;
                        }
                        {/if}
                    },
                    defaultContent: "Tidak ada dokumen",
                    title: "Dokumen Pendukung"
                },
                { data: "catatan", className: 'dt-body-left', width: '20%', "orderable": false },
            ],
            columnDefs: [ {
                "targets": 3,
                "createdCell": function (td, cellData, rowData, row, col) {
                    if ( rowData.verifikasi == 1 ) {
                        $(td).removeClass('editable');
                    }
                }
            } ],
            order: [ 0, 'asc' ],
        });

        dtprestasi.buttons( 0, null ).container().find(".dt-button").removeClass("dt-button").addClass('btn');

        //default: hide button
        dtprestasi.buttons( 0, null ).container().hide();

        dtriwayat = $('#triwayat').DataTable({
            "responsive": true,
            "paging": false,
            "dom": 't',
            "buttons": [
            ],
            "language": {
                "sProcessing":   "Sedang proses...",
                "sLengthMenu":   "Tampilan _MENU_ entri",
                "sZeroRecords":  "Tidak ditemukan data yang sesuai",
                "sInfo":         "Tampilan _START_ - _END_ dari _TOTAL_ entri",
                "sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 entri",
                "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "sInfoPostFix":  "",
                "sSearch":       "Cari:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "Awal",
                    "sPrevious": "Balik",
                    "sNext":     "Lanjut",
                    "sLast":     "Akhir"
                }
            },	
            ajax: "",
            columns: [
                { data: "created_on", className: 'dt-body-center readonly-column', orderable: true },
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
            if (card.prop('id') == 'prestasi') {
                dtprestasi.columns.adjust().responsive.recalc();
            }

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

            btn = $(".btn-perbaikan[tcg-tag='" +submittag+ "']");
            if (flagval != 2) {
                btn.hide();
            } else {
                btn.show();
            }

            el = $("tr.catatan[tcg-tag='" +submittag+ "']");
            if (flagval == 2 || perbaikan[submittag]) {
                el.show();
            } else {
                el.hide();
            }

            // if (flagval == 0) {
            //     // //reset the notes   
            //     // el.val("");
            //     // // //form data
            //     // // formdata.append("verifikasi_" +submittag, flagval)
            //     //hide tombol perbaikan
            //     $(".btn-perbaikan[tcg-tag='" +submittag+ "']").hide();
            // }
            // else if (flagval == 2) {
            //     // //note harus diisi
            //     // val = $.trim(el.val());
            //     // if (val == "") {
            //     //     //alert("Catatan harus diisi!");
            //     //     //toastr.options.positionClass = 'toast-bottom-left';
            //     //     toastr.error('Catatan verifikasi harus diisi!');
            //     //     //cancel event
            //     //     this.value = this.defaultValue;
            //     //     //set border to red to notify
            //     //     el.addClass("border-red");
            //     //     return;
            //     // }
            //     // // //form data
            //     // // formdata.append("verifikasi_" +submittag, flagval)
            //     // // formdata.append("catatan_" +submittag, val)
            //     //munculkan tombol perbaikan
            //     $(".btn-perbaikan[tcg-tag='" +submittag+ "']").show();
            // }
            // else {
            //     //reset the notes
            //     el.val("");
            //     // //form data
            //     // formdata.append("verifikasi_" +submittag, flagval)
            //     //fields
            //     let elements = $("[tcg-tag='" +submittag+ "']");
            //     elements.each(function(idx) {
            //         el = $(this);

            //         //data to submit
            //         let field = el.attr('tcg-field');
            //         if (field == null || field == '') return;

            //         let tosubmit = el.attr("tcg-field-submit");
            //         if (!tosubmit) return;

            //         val = el.val();
            //         formdata.append(field, val);
            //     });
            //     //hide tombol perbaikan
            //     $(".btn-perbaikan[tcg-tag='" +submittag+ "']").hide();
            // }

            // $.ajax({
            //     type: "POST",
            //     url: "{$site_url}ppdb/sekolah/verifikasi/updateprofil",
            //     async: true,
            //     data: formdata,
            //     cache: false,
            //     contentType: false,
            //     processData: false,
            //     timeout: 60000,
            //     dataType: 'json',
            //     success: function(json) {
            //         if (json.error !== undefined && json.error != "" && json.error != null) {
            //             return;
            //         }
            //         //TODO: get the return value and re-set the field
            //     },
            //     error: function(jqXHR, textStatus, errorThrown) {
            //         //TODO
            //         return;
            //     }
            // });

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
            else {
                card.removeClass("status-warning");
                card.removeClass("status-danger");
                card.find(".accordion-header-text .status").html('');
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
            if (toggletag == 'kip' || toggletag == 'bdt') {
                elements = $("[tcg-visible-tag='afirmasi']");
                if (!profilflag['kip'] && !profilflag['bdt']) {
                    elements.hide();
                }
                else {
                    elements.show();
                }
            }

            //special case: prestasi
            if (toggletag == 'prestasi') {
                if (profilflag['prestasi']) {
                    dtprestasi.columns.adjust().responsive.recalc();
                }
            }
        });

        $(".btn-perbaikan").on("click", function(e) {
            btn = $(this);
            tag = btn.attr("tcg-tag");

            //val: set to false to edit
            flagval = false;

            //show input element
            let elements = $("[tcg-tag='" +tag+ "']");
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
            btn.hide();

            //special case
            if (tag == 'lokasi') {
                map_enable_edit = true;
            }

            //disable the status flag
            elements.filter(".status-verifikasi").attr("disabled", true);

        });

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
                            });

                            //hide tombol revert
                            elements.filter(".btn-kembalikan").hide();   
                            
                            elements.filter(".catatan").removeClass("border-red");
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
                type = el.attr('tcg-field-type');

                if (type == 'input') el.hide();
                else if (type == 'label') el.show();
                else if (type == 'toggle')  el.attr('disabled', true);

                //copy value
                if (type == 'input') {
                    let field = el.attr('tcg-field');
                    if (field == null || field == '') return;

                    let val = 0;
                    if (el.attr('type') == 'number') {
                        val = parseFloat(el.val());
                        if (isNaN(val)) val = 0;
                        val = val.toFixed(2);
                        el.val(val);
                    }
                    else {
                        val = el.val();
                    }

                    //copy value if necessary
                    lbl = elements.filter("span[tcg-field='" +field+ "']");
                    lbl.html(val); 

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
            });

            //show perbaikan save
            elements.filter(".btn-perbaikan").show();

            //hide btn simpan
            elements.filter(".btn-simpan").hide();
            elements.filter(".btn-batal").hide();

            //tombol revert
            if (perbaikan[tag]) {
                elements.filter(".btn-kembalikan").show();
            }

            //special case
            if (tag == 'lokasi') {
                map_enable_edit = false;
            }

            //enable the status flag
            elements.filter(".status-verifikasi").attr("disabled", false);

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
                type = el.attr('tcg-field-type');

                if (type == 'input') el.hide();
                else if (type == 'label') el.show();
                else if (type == 'toggle')  el.attr('disabled', true);

                //revert to default value
                if (el.is('input') || el.is('select')) {
                    el.val(this.defaultValue).trigger("change");
                }            
            });

            //show perbaikan save
            elements.filter(".btn-perbaikan").show();

            //hide btn simpan
            elements.filter(".btn-simpan").hide();
            elements.filter(".btn-batal").hide();

            //special case
            if (tag == 'lokasi') {
                map_enable_edit = false;
            }

            //enable the status flag
            elements.filter(".status-verifikasi").attr("disabled", false);

        });

        $(".ctx-batal").on("click", function(e) {
            close_verifikasi();
        });

        $(".ctx-simpan").on("click", function(e) {
            if (simpan_verifikasi())    close_verifikasi();
        });
    });

    function show_profile(profil) {
 
        //additional fields
        profil['jenis_kelamin_label'] = (profil['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan';
        profil['desa_kelurahan_label'] = (profil['desa_kelurahan'] != '') ? profil['desa_kelurahan'] : 'Desa/Kelurahan masih kosong';
        profil['punya_kebutuhan_khusus'] = (profil['kebutuhan_khusus'] == 'Tidak ada') ? '0' : '1';
        profil['punya_dokumen_pendukung'] = (profil['dokumen'] != null && profil['dokumen'].length>0);

        keys = Object.keys(profil);
        
        //update lokal value
        tutup_akses = profil.tutup_akses;

        verifikasi = {};
        tags.forEach(function(key) {
            verifikasi[key] = profil["verifikasi_" +key];
            perbaikan[key] = 0;
        });

        profilflag = {};
        profilflag['nilai-un'] = profil['punya_nilai_un'];
        profilflag['prestasi'] = profil['punya_prestasi'];
        profilflag['kip'] = profil['punya_kip'];
        profilflag['bdt'] = profil['masuk_bdt'];
        profilflag['inklusi'] = profil['kebutuhan_khusus'] == 'Tidak ada' ? 0 : 1;

        //set dom field value
        keys.forEach(function(key) {
            value = profil[key];
            elements = $("[tcg-field='" +key+ "']");
            elements.each(function(idx) {
                el = $(this);
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
                else if (type == 'note') {
                    el.val(value);
                }
                else if (type == 'status') {
                    el.val(value);
                }
            });
        });

        //dokumen pendukung
        let template = $('#daftar-dokumen').html();
        Mustache.parse(template);

        let dom = Mustache.render(template, {
            'dokumen': dokumen,
        });

        let parent = $("#daftar-dokumen-wrapper");
        parent.html(dom);

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
        if (!profilflag['kip'] && !profilflag['bdt']) {
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

            el = elements.filter("tr.catatan");
            btn = elements.filter(".btn-perbaikan");
            if (value != 2) {
                el.hide();
                btn.hide();
            }
            else {
                el.show();
                btn.show();
            }

            btn = elements.filter(".btn-kembalikan");
            if (perbaikan[key]) {
                btn.show();
            }
            else {
                btn.hide();
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
            else {
                card.removeClass("status-warning");
                card.removeClass("status-danger");
                card.find(".accordion-header-text .status").html('');
            }

        });
        
        //special case
        dtprestasi.buttons( 0, null ).container().hide();

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
                show_profile(json.data.profil);
            
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

        json = {};
        updated = {};
        tosubmit = true;

        tags.forEach(function(key) {
            let elements = $("[tcg-tag='" +key+ "']");
            elements.each(function(idx) {
                el = $(this);

                //data to submit
                let field = el.attr('tcg-field');
                if (field == null || field == '') return;

                let tosubmit = el.attr("tcg-field-submit");
                if (!tosubmit) return;

                val = el.val();
                oldval = profil[field];
                if (el.attr('type') == 'number') {
                    val = parseFloat(val);
                    if (isNaN(val)) val = 0;
                    oldval = parseFloat(oldval);
                    if (isNaN(oldval)) oldval = 0;
                }

                //only get updated value
                if (val != oldval) {
                    updated[field] = val;
                    console.log("field: " +field+ "; oldval: " +oldval+ "; newval: " +val);
                }
            });

            let card = $("#" +key);

            //check for catatan verifikasi
            status = elements.filter(".status-verifikasi").val();
            if (perbaikan[key] && updated["catatan_" +key] === undefined) {
                msg = "Catatan verifikasi " +key.toUpperCase()+ " harus diisi!";
                toastr.info(msg);
                card.find(".accordion-header-text .status").html('*' +msg+ '*');
                elements.filter(".catatan").addClass("border-red");
                //dont submit
                tosubmit = false;
            }
            else if (status == 2 && updated["catatan_" +key] === undefined && profil["catatan_" +key] == "") {
                msg = "Catatan verifikasi " +key.toUpperCase()+ " harus diisi!";
                toastr.info(msg);
                card.find(".accordion-header-text .status").html('*' +msg+ '*');
                elements.filter(".catatan").addClass("border-red");
                //dont submit
                tosubmit = false;
            }

            //collapse the card
            card.find(".accordion__body").removeClass("show");
            card.find(".accordion-header").addClass("collapsed");
        });

        if (!tosubmit)  return false;

        json[ profil['peserta_didik_id'] ] = updated;
        $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/sekolah/simpanverifikasi",
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
                    return;
                }
                //TODO: get the return value and re-set the field
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //TODO
                return;
            }
        });

        return true;
    }
</script>

<script>

    //Peta
    var map = null;
    var map_enable_edit = false;

    $(document).ready(function() {
        map = L.map('peta',{ zoomControl:false }).setView([{$map_lintang},{$map_bujur}],10);
        var tile = L.tileLayer(
            '{$map_streetmap}',{ maxZoom: 18,attribution: 'PPDB {$nama_wilayah}',id: 'mapbox.streets' }
        ).addTo(map);

        var streetmap   = L.tileLayer('{$map_streetmap}', { id: 'mapbox.light', attribution: '' }),
            satelitemap  = L.tileLayer('{$map_satelitemap}', { id: 'mapbox.streets',   attribution: '' });

        var baseLayers = {
            "Streets": streetmap,
            "Satelite": satelitemap
        };

        var overlays = {};
        L.control.layers(baseLayers,overlays).addTo(map);

        var layerGroup = L.layerGroup().addTo(map);
        function onMapClick(e) {
            if (!map_enable_edit)   return;

            layerGroup.clearLayers();
            var lintang = e.latlng.lat;
            var bujur = e.latlng.lng;
            new L.marker(e.latlng).addTo(layerGroup).bindPopup("Lokasi :<br>"+lintang+" , "+bujur).openPopup();
            document.getElementById("lintang-input").value=lintang;
            document.getElementById("bujur-input").value=bujur;
        }
        map.on('click', onMapClick);

        var searchControl = L.esri.Geocoding.geosearch().addTo(map);
        searchControl.on('layerGroup', function(data){
            layerGroup.clearLayers();
        });

        new L.Control.Fullscreen({ position:'bottomleft' }).addTo(map);
        new L.Control.Zoom({ position:'bottomright' }).addTo(map);

        new L.Control.EasyButton( '<span class="map-button">&curren;</span>', function(){
            map.setView([{$map_lintang},{$map_bujur}],10);;
        }, { position: 'topleft' }).addTo(map);

        streetmap.addTo(map);
    });

</script>