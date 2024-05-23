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
                    <p>(<b>{{item.npsn}}</b>) <b>{{item.sekolah}}</b>)</p>
                    
                    {{#allow_edit}}
                    <div style="min-height: 38px;">
                        {{#item.ubah_pilihan}}
                        <a href="{{item.url_ubah_pilihan}}" class="btn btn-sm btn-ubah-pilihan {{^item.allow_ubah_pilihan}}disabled{{/item.allow_ubah_pilihan}}" style="margin-top: 4px;">
                            <i class="glyphicon glyphicon-edit"></i> Ubah Pilihan
                        </a>
                        {{/item.ubah_pilihan}}

                        {{#item.ubah_jalur}}
                        <a href="{{item.url_ubah_jalur}}" class="btn btn-sm btn-ubah-jalur {{^item.allow_ubah_jalur}}disabled{{/item.allow_ubah_jalur}}" style="margin-top: 4px;" class="btn-ubah-jalur">
                            <i class="glyphicon glyphicon-sort"></i> Ubah Jalur
                        </a>
                        {{/item.ubah_jalur}}

                        {{#item.ubah_sekolah}}
                        <a href="{{item.url_ubah_sekolah}}" class="btn btn-sm btn-ubah-sekolah {{^item.allow_ubah_sekolah}}disabled{{/item.allow_ubah_sekolah}}" style="margin-top: 4px;">
                            <i class="glyphicon glyphicon-home"></i> Ubah Sekolah
                        </a>
                        {{/item.ubah_sekolah}}

                        {{#item.hapus_pendaftaran}}
                        <a href="{{item.url_hapus}}" class="btn btn-sm btn-hapus {{^item.allow_hapus}}disabled{{/item.allow_hapus}}" style="margin-top: 4px;">
                            <i class="glyphicon glyphicon-remove"></i> Hapus Pendaftaran
                        </a>
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
                            {{item.label_peringkat}}
                            <span class="pull-right"><a href="{{item.url_perankingan}}" target="_blank"><i class="glyphicon glyphicon-search"></i> Lihat Peringkat</a></span>
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
                                            {{#kelengkapan_data}}<i class="text-blue glyphicon glyphicon-ok"></i>{{/kelengkapan_data}}
                                            {{^kelengkapan_data}}<i class="text-red glyphicon glyphicon-remove"></i>{{/kelengkapan_data}}
                                        </td>
                                    </tr>
                                    {{#item.kelengkapan}}
                                    <tr {{#kondisi_khusus}}class="bg-warning"{{/kondisi_khusus}}>
                                        <td>{{kelengkapan}}</td>
                                        <td class="text-center">
                                            {{#status_ok}}<i class="text-blue glyphicon glyphicon-ok"></i>{{/status_ok}}
                                            {{#status_notok}}<i class="text-red glyphicon glyphicon-remove"></i>{{/status_notok}}
                                            {{#status_tidakada}}Tidak Ada{{/status_tidakada}}
                                            {{#status_dalamproses}}Dalam Proses{{/status_dalamproses}}
                                        </td>
                                    </tr>
                                    {{/item.kelengkapan}}

                                    {{#item.berkasfisik}}
                                    {foreach $berkasfisik[$row.pendaftaran_id] as $row2}
                                    <tr {{#kondisi_khusus}}class="bg-warning"{{/kondisi_khusus}}>
                                        <td>{{kelengkapan}} (Berkas Fisik)</td>
                                        <td class="text-center">
                                            {{#status_ok}}<i class="text-blue glyphicon glyphicon-ok"></i>{{/status_ok}}
                                            {{#status_notok}}<i class="text-red glyphicon glyphicon-remove"></i>{{/status_notok}}
                                        </td>
                                    </tr>
                                    {{/item.berkasfisik}}

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
                                        <td>{{keterangan}}</td>
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

<script>
    var batasanperubahan = {$batasanperubahan|json_encode};
    var daftarpendaftaran = {$daftarpendaftaran|json_encode};
    var pendaftarandikunci = {$pendaftarandikunci};
    var kelengkapan_data = {$kelengkapan_data};

    function update_hasil_layout() {
        if (daftarpendaftaran == null || daftarpendaftaran.length == 0) return;

        daftarpendaftaran.forEach(function(pendaftaran) {

            // render template
            let template = $('#detail-pendaftaran').html();
            Mustache.parse(template);

            let dom = Mustache.render(template, {
                'allow_edit': !pendaftarandikunci,
                'kelengkapan_data' : kelengkapan_data,
                'item'      : pendaftaran,
                'batasan'   : batasanperubahan,
            });

            let parent = $("#daftar-pendaftaran");
            parent.append(dom);
        });
    }

</script>

<div class="row" id="daftar-pendaftaran"></div>