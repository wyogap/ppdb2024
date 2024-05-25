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
