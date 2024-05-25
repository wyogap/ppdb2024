<style> 
    .btn-ubah-pilihan {
        background-color: var(--bs-warning);
        border-color: var(--bs-warning);
        color: #fff;
    }

    .btn-ubah-jalur {
        /* display: inherit; */
        background-color: var(--bs-secondary);
        border-color: var(--bs-secondary);
        color: #fff;    
    }

    .btn-ubah-sekolah {
        /* display: inherit; */
        background-color: var(--bs-info);
        border-color: var(--bs-info);
        color: #fff;
    }

    .btn-hapus {
        /* display: inherit; */
        background-color: var(--bs-danger);
        border-color: var(--bs-danger);
        color: #fff;
    }

    .status-masuk-kuota {
        /* green */
        background-color: green !important;
        color: #fff;
    }

    .status-tidak-masuk-kuota {
        /* red */
        background-color: red !important;
        color: #fff;
    }

    .status-daftar-tunggu {
        /* yellow */
        background-color: yellow !important;
        color: #000;
    }

    .status-tidak-dihitung {
        /* gray */
        background-color: gray !important;
        color: #fff;
    }

    .header-row {
        background-color: var(--primary);
        color: #fff;
    }

    .table-pendaftaran td {
        border-top: solid 1px var(--primary) !important;
        border-bottom: solid 1px var(--primary) !important;
    }

    .table-kelengkapan td {
        border-left: 0px;
        border-right: 0px;
        border-color: var(--bs-light) !important;
        padding: 10px 10px;
    }

    .table-kelengkapan td .bi {
        font-size: 20px;
    }

    .table-kelengkapan tbody tr:last-child td {
        border-bottom: 0px;
    }

    .table-kelengkapan tbody tr:last-child th {
        border-top: 2px;
        border-bottom: 0px;
    }

</style>

{literal}
<script id="detail-pendaftaran" type="text/template">

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h3 class="box-title">
                    (<b>{{item.npsn}}</b>) <b>{{item.sekolah}}</b>
                    
                    {{#allow_edit}}
                    <div style="min-height: 38px; margin-top: 8px;">
                        {{#item.ubah_pilihan}}
                        <button onclick=ubah_pilihan({{idx}}) class="btn btn-sm btn-ubah-pilihan {{^item.allow_ubah_pilihan}}disabled{{/item.allow_ubah_pilihan}}" style="margin-top: 4px;">
                            <i class="glyphicon glyphicon-edit"></i> Ubah Pilihan
                        </button>
                        {{/item.ubah_pilihan}}

                        {{#item.ubah_jalur}}
                        <button onclick=ubah_jalur({{idx}}) class="btn btn-sm btn-ubah-jalur {{^item.allow_ubah_jalur}}disabled{{/item.allow_ubah_jalur}}" style="margin-top: 4px;" class="btn-ubah-jalur">
                            <i class="glyphicon glyphicon-sort"></i> Ubah Jalur
                        </button>
                        {{/item.ubah_jalur}}

                        {{#item.ubah_sekolah}}
                        <button onclick=ubah_sekolah({{idx}}) class="btn btn-sm btn-ubah-sekolah {{^item.allow_ubah_sekolah}}disabled{{/item.allow_ubah_sekolah}}" style="margin-top: 4px;">
                            <i class="glyphicon glyphicon-home"></i> Ubah Sekolah
                        </button>
                        {{/item.ubah_sekolah}}

                        {{#item.hapus_pendaftaran}}
                        <button onclick=hapus_pendaftaran({{idx}}) class="btn btn-sm btn-hapus {{^item.allow_hapus}}disabled{{/item.allow_hapus}}" style="margin-top: 4px;">
                            <i class="glyphicon glyphicon-remove"></i> Hapus Pendaftaran
                        </button>
                        {{/item.hapus_pendaftaran}}
                    </div>
                    {{/allow_edit}}
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-pendaftaran">
                    {{^item.jenis_pilihan}}
                    <tr>
                        <td colspan="3" class="text-danger">Jenis pilihan belum diperbaharui, silahkan lakukan perbaikan melalui menu <b><i class="glyphicon glyphicon-edit"></i> Ubah Pilihan</b> diatas (<i class="glyphicon glyphicon-arrow-up"></i>) ini</td>
                    </tr>
                    <tr class="bg-red">
                        <td><b>Jenis Pilihan</b></td>
                        <td>:</td>
                        <td>Belum diperbaharui</td>
                    </tr>
                    {{/item.jenis_pilihan}}
                    {{#item.jenis_pilihan}}
                    <tr>
                        <td><b>Jenis Pilihan</b></td>
                        <td>:</td>
                        <td>{{item.label_jenis_pilihan}}</td>
                    </tr>
                    {{/item.jenis_pilihan}}
                    <tr>
                        <td><b>Jalur</b></td>
                        <td>:</td>
                        <td>{{item.jalur}}</td>
                    </tr>
                    <tr>
                        <td><b>Waktu Pendaftaran</b></td>
                        <td>:</td>
                        <td>{{item.created_on}}</td>
                    </tr>
                    <tr>
                        <td><b>Nomor Pendaftaran</b></td>
                        <td>:</td>
                        <td>{{item.nomor_pendaftaran}}</td>
                    </tr>
                    <tr>
                        <td><b>Peringkat</b></td>
                        <td>:</td>
                        <td>
                            <span>{{item.label_peringkat}}</span>
                            <span class="pull-right"><a href="{{item.url_perankingan}}" target="_blank" 
                                data-bs-toggle="tooltip" title="Lihat Peringkat Sekolah" data-placement="top" >
                                <i class="fa fas fa-external-link" style="font-size: 18px;"></i></a>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Status Pendaftaran</b></td>
                        <td>:</td>
                        <td class="{{item.class_status_penerimaan}}"><i class="{{item.icon_status_penerimaan}}"></i>  {{item.label_status_penerimaan}}
                        </td>
                    </tr>
                </table><br>
                <div class="accordion accordion-primary-solid" id="pendaftaran-{{item.pendaftaran_id}}">
                    <div class="accordion-item" id="kelengkapan-{{item.pendaftaran_id}}">
                        <div class="accordion-header rounded-lg collapsed" id="kelengkapan-header-{{item.pendaftaran_id}}" data-bs-toggle="collapse" 
                            data-bs-target="#kelengkapan-content-{{item.pendaftaran_id}}" aria-controls="kelengkapan-content-{{item.pendaftaran_id}}" aria-expanded="true" role="button">
                            <span class="accordion-header-icon"></span>
                        <span class="accordion-header-text">Daftar Kelengkapan Berkas</span>
                        <span class="accordion-header-indicator"></span>
                        </div>
                        <div id="kelengkapan-content-{{item.pendaftaran_id}}" class="collapse accordion__body" aria-labelledby="kelengkapan-header-{{item.pendaftaran_id}}" 
                            data-bs-parent="#pendaftaran-{{item.pendaftaran_id}}" style="">
                            <div class="accordion-body-text">
                                <div class="row">
                                <table class="table table-kelengkapan">
                                    <tr>
                                        <td>Data Profil</td>
                                        <td class="text-center">
                                            {{#kelengkapan_data}}<i class="text-blue bi bi-check"></i>{{/kelengkapan_data}}
                                            {{^kelengkapan_data}}<i class="text-red bi bi-exclamation"></i>{{/kelengkapan_data}}
                                        </td>
                                    </tr>
                                    {{#item.kelengkapan}}
                                    <tr {{#kondisi_khusus}}class="bg-warning"{{/kondisi_khusus}}>
                                        <td>{{kelengkapan}}</td>
                                        <td class="text-center">
                                            {{#status_ok}}<i class="text-blue bi bi-check"></i>{{/status_ok}}
                                            {{#status_notok}}<i class="text-red bi bi-exclamation"></i>{{/status_notok}}
                                            {{#status_tidakada}}Tidak Ada{{/status_tidakada}}
                                            {{#status_dalamproses}}Dalam Proses{{/status_dalamproses}}
                                        </td>
                                    </tr>
                                    {{/item.kelengkapan}}

                                    <tr>
                                        <td colspan="2" class="text-warning"><b>Note</b> : Jika ada kelengkapan yang bertanda <i class="text-red glyphicon glyphicon-remove"></i> mohon untuk memperbaiki di halaman <a href="{{item.url_profil}}">Profil Siswa</a>.</td>
                                    </tr>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item" id="skoring-{{item.pendaftaran_id}}">
                        <div class="accordion-header rounded-lg collapsed" id="skoring-header-{{item.pendaftaran_id}}" data-bs-toggle="collapse" 
                            data-bs-target="#skoring-content-{{item.pendaftaran_id}}" aria-controls="skoring-content-{{item.pendaftaran_id}}" aria-expanded="true" role="button">
                            <span class="accordion-header-icon"></span>
                        <span class="accordion-header-text">Daftar Skoring</span>
                        <span class="accordion-header-indicator"></span>
                        </div>
                        <div id="skoring-content-{{item.pendaftaran_id}}" class="collapse accordion__body" aria-labelledby="skoring-header-{{item.pendaftaran_id}}" 
                            data-bs-parent="#skoring-{{item.pendaftaran_id}}" style="">
                            <div class="accordion-body-text">
                                <div class="row">
                                <table class="table table-kelengkapan">
                                    {{#item.skoring}}
                                    <tr>
                                        <td>{{{keterangan}}}</td>
                                        <td class="text-end">{{nilai}}</td>
                                    </tr>
                                    {{/item.skoring}}
                                    <tr class="bg-gray">
                                        <th>Total</th>
                                        <th class="text-end">{{item.totalskoring}}</th>
                                    </tr>
                                </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

</script>
{/literal}

{if !$is_public|default: FALSE}
{literal}
<script id="ubah-pilihan" type="text/template">

    <div class="alert alert-secondary alert-dismissible" role="alert">
        Anda hanya bisa melakukan perubahan <b>"Jenis Pilihan"</b> sebanyak <b>{{batasan}} kali</b>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table">
            <tr>
                <td><b>Jenis Pilihan</b></td>
                <td>:</td>
                <td>{{item.label_jenis_pilihan}}</td>
            </tr>
            <tr>
                <td><b>Sekolah</b></td>
                <td>:</td>
                <td>({{item.npsn}}) {{item.sekolah}}</td>
            </tr>
            <tr>
                <td><b>Jalur</b></td>
                <td>:</td>
                <td>{{item.jalur}}</td>
            </tr>
            <tr>
                <td><b>Status Penerimaan</b></td>
                <td>:</td>
                <td>{{item.label_status_penerimaan}}</td>
            </tr>
        </table>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 8px;">
        <div class="form-group has-feedback">
            <label for="jenis_pilihan"><b>Ubah jenis pilihan menjadi :</b></label>
            <select id="jenis_pilihan_baru" name="jenis_pilihan_baru" class="form-control select2" data-validation="required" autofocus>
                <option value="">-- Jenis Pilihan --</option>
                {{#pilihan}}
                <option value="{{jenis_pilihan}}">{{keterangan}}</option>
                {{/pilihan}}
            </select>
        </div>
    </div>

</script>

<script id="ubah-sekolah" type="text/template">

    <div class="alert alert-secondary alert-dismissible" role="alert">
        Anda hanya bisa melakukan perubahan <b>"Pilihan Sekolah"</b> sebanyak <b>{{batasan}} kali</b>
    </div>
    <!-- <div class="alert alert-secondary alert-dismissible" role="alert">
        Anda hanya bisa mendaftar menggunakan satu jalur pada satu zonasi. Mohon berhati-hati dalam menentukan jalur pendaftaran.            
    </div> -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table">
            <tr>
                <td><b>Jenis Pilihan</b></td>
                <td>:</td>
                <td>{{item.label_jenis_pilihan}}</td>
            </tr>
            <tr>
                <td><b>Sekolah</b></td>
                <td>:</td>
                <td>({{item.npsn}}) {{item.sekolah}}</td>
            </tr>
            <tr>
                <td><b>Jalur</b></td>
                <td>:</td>
                <td>{{item.jalur}}</td>
            </tr>
            <tr>
                <td><b>Status Penerimaan</b></td>
                <td>:</td>
                <td>{{item.label_status_penerimaan}}</td>
            </tr>
        </table>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 8px;">
        <div class="form-group has-feedback">
            <label for="sekolah_id"><b>Ubah sekolah menjadi :</b></label>
            <select id="sekolah_id" name="sekolah_id" class="form-control select2" data-validation="required" autofocus>
                <option value="">-- Silahkan Pilih Sekolah --</option>
                {{#pilihan}}
                <option value="{{sekolah_id}}">{{nama}}</option>
                {{/pilihan}}
            </select>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div id="detailsekolah"></div>
    </div>

</script>

<script id="ubah-jalur" type="text/template">

    <div class="alert alert-secondary alert-dismissible" role="alert">
        Anda hanya bisa melakukan perubahan <b>"Jalur Pendaftaran"</b> sebanyak <b>{{batasan}} kali</b>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table">
            <tr>
                <td><b>Jenis Pilihan</b></td>
                <td>:</td>
                <td>{{item.label_jenis_pilihan}}</td>
            </tr>
            <tr>
                <td><b>Sekolah</b></td>
                <td>:</td>
                <td>({{item.npsn}}) {{item.sekolah}}</td>
            </tr>
            <tr>
                <td><b>Jalur</b></td>
                <td>:</td>
                <td>{{item.jalur}}</td>
            </tr>
            <tr>
                <td><b>Status Penerimaan</b></td>
                <td>:</td>
                <td>{{item.label_status_penerimaan}}</td>
            </tr>
        </table>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 8px;">
        <div class="form-group has-feedback">
            <label for="jalur_penerapan"><b>Ubah jalur pendaftaran menjadi :</b></label>
            <select id="jalur_penerapan_baru" name="jalur_penerapan_baru" class="form-control select2" data-validation="required" autofocus>
                <option value="">-- Jalur Pendaftaran --</option>
                {{#pilihan}}
                <option value="{{penerapan_id}}">{{nama}}</option>
                {{/pilihan}}
            </select>
        </div>
    </div>
    <div id="dokumen_pendukung" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    </div>

</script>

<script id="hapus-pendaftaran" type="text/template">

    <div class="alert alert-secondary alert-dismissible" role="alert">
    Anda hanya bisa <b>menghapus</b> pendaftaran sebanyak <b>{{batasan}} kali</b>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table">
            <tr>
                <td><b>Jenis Pilihan</b></td>
                <td>:</td>
                <td>{{item.label_jenis_pilihan}}</td>
            </tr>
            <tr>
                <td><b>Sekolah</b></td>
                <td>:</td>
                <td>({{item.npsn}}) {{item.sekolah}}</td>
            </tr>
            <tr>
                <td><b>Jalur</b></td>
                <td>:</td>
                <td>{{item.jalur}}</td>
            </tr>
            <tr>
                <td><b>Status Penerimaan</b></td>
                <td>:</td>
                <td>{{item.label_status_penerimaan}}</td>
            </tr>
        </table>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 8px;">
        <div class="form-group has-feedback">
            <label for="keterangan"><b>Alasan Hapus Pendaftaran : </b></label>
            <textarea id="keterangan" name="keterangan" placeholder="Penjelasan terkait Hapus Pendaftaran ..." data-validation="required" 
            style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" autofocus></textarea>
        </div>
    </div>

</script>
{/literal}
{/if}

{if $is_public|default: FALSE}
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Pendaftaran</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">{$profilsiswa.nama}</a></li>
    </ol>
</div>
{/if}

<div class="row" id="daftar-pendaftaran-notif">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="alert alert-info alert-dismissible" role="alert">
            <i class="icon glyphicon glyphicon-info-sign"></i>Kamu belum melakukan pendaftaran.
        </div>
    </div>
</div>

<div class="row" id="daftar-pendaftaran"></div>


<script>
    function update_hasil_layout() {
        if (daftarpendaftaran == null || daftarpendaftaran.length == 0) {
            $('#daftar-pendaftaran-notif').show();
            return;
        }

        $('#daftar-pendaftaran-notif').hide();
        
        //clear first
        let parent = $("#daftar-pendaftaran");
        parent.html('');

        //add pendaftaran one-by-one
        daftarpendaftaran.forEach(function(pendaftaran, idx) {

            // render template
            let template = $('#detail-pendaftaran').html();
            Mustache.parse(template);

            let dom = Mustache.render(template, {
                'allow_edit': !pendaftarandikunci,
                'kelengkapan_data' : kelengkapan_data,
                'item'      : pendaftaran,
                //'batasan'   : batasanperubahan,
                //'idx'       : idx
            });

            parent.append(dom);
        });
    }

    {if !$is_public|default: FALSE}
    function hapus_pendaftaran(idx) {
        let p = daftarpendaftaran[idx];
        let pendaftaran_id = p['pendaftaran_id'];

        // render template
        let template = $('#hapus-pendaftaran').html();
        Mustache.parse(template);

        let dom = Mustache.render(template, {
            'batasan'   : 1,
            'item'      : p,
        });

        $.confirm({
                title: 'Hapus Pendaftaran?',
                content: dom,
                closeIcon: true,
                columnClass: 'medium',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    // confirm: function () {
                    //     $.alert('Confirmed!');
                    // },
                    cancel: {
                        text: 'Batal',
                        keys: ['enter', 'shift'],
                        action: function(){
                            //do nothing
                        }
                    },
                    confirm: {
                        text: 'Hapus',
                        btnClass: 'btn-danger',
                        action: function(){
                            let el = this.$content.find('#keterangan');
                            let catatan = el.val().trim();
                            if(!catatan){
                                el.addClass("border-red");
                                toastr.error("Alasan penghapusan pendaftaran harus diisi.");
                                return false;
                            }   
                                               
                            send_hapus_pendaftaran(pendaftaran_id, catatan);
                        }
                    },
                }
            });      
    }

    function ubah_pilihan(idx) {
        let p = daftarpendaftaran[idx];
        let pendaftaran_id = p['pendaftaran_id'];
        let peserta_didik_id = profil['peserta_didik_id'];

        //get opsi pilihan
        $.ajax({
            type: 'GET',
            url: "{$site_url}ppdb/siswa/pilihan?tipe=ubahpilihan&peserta_didik_id=" +peserta_didik_id+ "&pendaftaran_id=" +pendaftaran_id,
            dataType: 'json',
            async: true,
            cache: false,
            timeout: 60000,
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil mendapatkan daftar pilihan. " + textStatus)
                return;
            }
        })
        .then(function(json) {
            if (json.error !== undefined && json.error != "" && json.error != null) {
                toastr.error("Tidak berhasil mendapatkan daftar pilihan. " + json.error)
                return;
            }

            // render template
            let template = $('#ubah-pilihan').html();
            Mustache.parse(template);

            let dom = Mustache.render(template, {
                'batasan'   : 1,
                'item'      : p,
                'pilihan'   : json.data
            });

            $.confirm({
                    title: 'Ubah Jenis Pilihan?',
                    content: dom,
                    closeIcon: true,
                    columnClass: 'medium',
                    type: 'orange',
                    typeAnimated: true,
                    buttons: {
                        // confirm: function () {
                        //     $.alert('Confirmed!');
                        // },
                        cancel: {
                            text: 'Batal',
                            keys: ['enter', 'shift'],
                            action: function(){
                                //do nothing
                            }
                        },
                        confirm: {
                            text: 'Ubah Pilihan',
                            btnClass: 'btn-orange',
                            action: function(){
                                let el = this.$content.find('#jenis_pilihan_baru');
                                let pilihan_baru = el.val();
                                if(!pilihan_baru){
                                    el.addClass("border-red");
                                    this.$content.find('.select2-selection').addClass("border-red");
                                    toastr.error("Urutan pilihan baru harus diisi.");
                                    return false;
                                }   

                                send_ubah_pilihan(pendaftaran_id, pilihan_baru);
                            }
                        },
                    }
                });      
        });
    }

    function ubah_jalur(idx) {
        let p = daftarpendaftaran[idx];
        let pendaftaran_id = p['pendaftaran_id'];
        let peserta_didik_id = profil['peserta_didik_id'];

        //get opsi pilihan
        $.ajax({
            type: 'GET',
            url: "{$site_url}ppdb/siswa/pilihan?tipe=ubahjalur&peserta_didik_id=" +peserta_didik_id+ "&pendaftaran_id=" +pendaftaran_id,
            dataType: 'json',
            async: true,
            cache: false,
            timeout: 60000,
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil mendapatkan daftar pilihan. " + textStatus)
                return;
            }
        })
        .then(function(json) {
            if (json.error !== undefined && json.error != "" && json.error != null) {
                toastr.error("Tidak berhasil mendapatkan daftar pilihan. " + json.error)
                return;
            }

            // render template
            let template = $('#ubah-jalur').html();
            Mustache.parse(template);

            let dom = Mustache.render(template, {
                'batasan'   : 1,
                'item'      : p,
                'pilihan'   : json.data
            });

            $.confirm({
                title: 'Ubah Jalur?',
                content: dom,
                closeIcon: true,
                columnClass: 'medium',
                type: 'blue',
                typeAnimated: true,
                buttons: {
                    // confirm: function () {
                    //     $.alert('Confirmed!');
                    // },
                    cancel: {
                        text: 'Batal',
                        keys: ['enter', 'shift'],
                        action: function(){
                            //do nothing
                        }
                    },
                    confirm: {
                        text: 'Ubah Jalur',
                        btnClass: 'btn-blue',
                        action: function(){
                            let el = this.$content.find('#jalur_penerapan_baru');
                            let pilihan_baru = el.val();
                            if(!pilihan_baru){
                                el.addClass("border-red");
                                this.$content.find('.select2-selection').addClass("border-red");
                                toastr.error("Pilihan jalur penerimaan baru harus diisi.");
                                return false;
                            }   

                            send_ubah_jalur(pendaftaran_id, pilihan_baru);
                        }
                    },
                }
            });      
        });
    }

    function ubah_sekolah(idx) {
        let p = daftarpendaftaran[idx];
        let pendaftaran_id = p['pendaftaran_id'];
        let peserta_didik_id = profil['peserta_didik_id'];

        //get opsi pilihan
        $.ajax({
            type: 'GET',
            url: "{$site_url}ppdb/siswa/pilihan?tipe=ubahsekolah&peserta_didik_id=" +peserta_didik_id+ "&pendaftaran_id=" +pendaftaran_id,
            dataType: 'json',
            async: true,
            cache: false,
            timeout: 60000,
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil mendapatkan daftar pilihan. " + textStatus)
                return;
            }
        })
        .then(function(json) {
            if (json.error !== undefined && json.error != "" && json.error != null) {
                toastr.error("Tidak berhasil mendapatkan daftar pilihan. " + json.error)
                return;
            }

            //update the label display
            options = json.data;
            options.forEach(function(item, idx) {
                if (typeof item === "undefined" || item == null ||
                    typeof item.sekolah_id === "undefined" || item.sekolah_id == null ||
                    typeof item.nama === "undefined" || item.nama == null) {
                    return;
                }

                let label = item.nama;
                let jarak = parseFloat(item.jarak);
                if (isNaN(jarak)) {
                    jarak = 100000;
                }

                if (item.jarak != '') {
                    label += ' (' + (jarak/1000).toFixed(2) + 'Km)';
                }
                
                //update label
                options[idx]['nama'] = label;
            })
             
            // render template
            let template = $('#ubah-sekolah').html();
            Mustache.parse(template);

            let dom = Mustache.render(template, {
                'batasan'   : 1,
                'item'      : p,
                'pilihan'   : options
            });

            $.confirm({
                title: 'Ubah Pilihan Sekolah?',
                content: dom,
                closeIcon: true,
                columnClass: 'medium',
                type: 'purple',
                typeAnimated: true,
                buttons: {
                    // confirm: function () {
                    //     $.alert('Confirmed!');
                    // },
                    cancel: {
                        text: 'Batal',
                        keys: ['enter', 'shift'],
                        action: function(){
                            //do nothing
                        }
                    },
                    confirm: {
                        text: 'Ubah Sekolah',
                        btnClass: 'btn-purple',
                        action: function(){
                            let el = this.$content.find('#sekolah_id');
                            let pilihan_baru = el.val();
                            if(!pilihan_baru){
                                el.addClass("border-red");
                                this.$content.find('.select2-selection').addClass("border-red");
                                toastr.error("Pilihan sekolah baru harus diisi.");
                                return false;
                            }   

                            send_ubah_sekolah(pendaftaran_id, pilihan_baru);
                        }
                    },
                },
                onContentReady: function () {
                    var jc = this;

                    parent = $(document.body).find(".jconfirm");
                    select = this.$content.find('#sekolah_id');
                    select.select2({
                        minimumResultsForSearch: 5,
                        //dropdownParent: $(_input).parent().parent().parent().parent(),
                        dropdownParent: parent,
                    });     
                }
            });      
        });
    }

    function send_hapus_pendaftaran(pendaftaran_id, catatan) {
        let item;

        //get item to delete
        for (i of daftarpendaftaran) {
            if (i['pendaftaran_id'] == pendaftaran_id) {
                item = i;
                break;
            }
        }

        //build json
        let json = {};
        json["pendaftaran_id"] = pendaftaran_id;
        json['keterangan'] = catatan;

        $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/siswa/hapus",
            dataType: 'json',
            data: json,
            async: true,
            cache: false,
            timeout: 60000,
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error("Tidak berhasil menghapus pendaftaran. " + json.error)
                    return;
                }

                //for logging
                sekolah = item['sekolah'];
                jalur = item['jalur'];

                //get the return value and re-set the field
                daftarpendaftaran = json.data;
                update_hasil_layout();
                update_pendaftaran_layout();

                toastr.success("Berhasil menghapus pendaftaran di " +sekolah+ " (" +jalur+ ")");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil menghapus pendaftaran. " + textStatus)
                return;
            }
        });

    }

    function send_ubah_pilihan(pendaftaran_id, jenis_pilihan) {
        let item;

        //get item to delete
        for (i of daftarpendaftaran) {
            if (i['pendaftaran_id'] == pendaftaran_id) {
                item = i;
                break;
            }
        }

        //build json
        let json = {};
        json["tipe"] = 'ubahpilihan';
        json["pendaftaran_id"] = pendaftaran_id;
        json['jenis_pilihan_baru'] = jenis_pilihan;

        $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/siswa/ubah",
            dataType: 'json',
            data: json,
            async: true,
            cache: false,
            timeout: 60000,
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error("Tidak berhasil mengubah urutan pilihan. " + json.error)
                    return;
                }

                //for logging
                sekolah = item['sekolah'];
                pilihan = item['label_jenis_pilihan'];

                //get the return value and re-set the field
                daftarpendaftaran = json.data;
                update_hasil_layout();
                update_pendaftaran_layout();

                pilihan_baru = '';
                for (i of daftarpendaftaran) {
                    if (i['pendaftaran_id'] == pendaftaran_id) {
                        pilihan_baru = i['label_jenis_pilihan'];
                        break;
                    }
                }

                toastr.success("Berhasil mengubah urutan pilihan di " +sekolah+ " (" +pilihan+ ") menjadi " +pilihan_baru);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil mengubah urutan pilihan. " + textStatus)
                return;
            }
        });
    }

    function send_ubah_jalur(pendaftaran_id, penerapan_id) {
        let item;

        //get item to delete
        for (i of daftarpendaftaran) {
            if (i['pendaftaran_id'] == pendaftaran_id) {
                item = i;
                break;
            }
        }

        //build json
        let json = {};
        json["tipe"] = 'ubahjalur';
        json["pendaftaran_id"] = pendaftaran_id;
        json['penerapan_id_baru'] = penerapan_id;

        $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/siswa/ubah",
            dataType: 'json',
            data: json,
            async: true,
            cache: false,
            timeout: 60000,
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error("Tidak berhasil mengubah jalur pendaftaran. " + json.error)
                    return;
                }

                //for logging
                sekolah = item['sekolah'];
                jalur = item['penerapan'];

                //get the return value and re-set the field
                daftarpendaftaran = json.data;
                update_hasil_layout();
                update_pendaftaran_layout();

                jalur_baru = '';
                for (item of daftarpendaftaran) {
                    if (item['tag'] == pendaftaran_id && item['pendaftaran'] == 1) {
                        jalur_baru = item['penerapan'];
                        break;
                    }
                }

                toastr.success("Berhasil mengubah jalur pendaftaran di " +sekolah+ " (" +jalur+ ") menjadi " +jalur_baru);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil mengubah jalur pendaftaran. " + textStatus)
                return;
            }
        });
    }

    function send_ubah_sekolah(pendaftaran_id, sekolah_id) {
        let item;

        //get item to delete
        for (i of daftarpendaftaran) {
            if (i['pendaftaran_id'] == pendaftaran_id) {
                item = i;
                break;
            }
        }

        //build json
        let json = {};
        json["tipe"] = 'ubahsekolah';
        json["pendaftaran_id"] = pendaftaran_id;
        json['sekolah_id_baru'] = sekolah_id;

        $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/siswa/ubah",
            dataType: 'json',
            data: json,
            async: true,
            cache: false,
            timeout: 60000,
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error("Tidak berhasil mengubah pilihan sekolah. " + json.error)
                    return;
                }

                //for logging
                sekolah = item['sekolah'];
                jalur = item['jalur'];

                //get the return value and re-set the field
                daftarpendaftaran = json.data;
                update_hasil_layout();
                update_pendaftaran_layout();

                jalur_baru = '';
                for (i of daftarpendaftaran) {
                    if (i['pendaftaran_id'] == pendaftaran_id) {
                        jalur_baru = i['jalur'];
                        break;
                    }
                }

                toastr.success("Berhasil mengubah pilihan sekolah dari " +sekolah+ " (" +jalur+ ") menjadi " +sekolah+ " (" +jalur_baru+ ")");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil mengubah pilihan sekolah. " + textStatus)
                return;
            }
        });
    }
    {/if}
    
</script>

