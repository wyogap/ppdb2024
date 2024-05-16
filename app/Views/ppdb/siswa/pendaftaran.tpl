

<!--
    //kelengkapan data
    //batasan-usia
    //inklusi
    //afirmasi
    //satu zona satu jalur
    //slot pendaftaran
    //waktu sosialisasi
    //cek-waktu-pendaftaran
-->

{if $profilsiswa.tutup_akses} 
<div class="alert alert-secondary" role='alert'>
    Akses anda ditutup sementara. Hubungi Administrator untuk membuka akses.
</div>
{/if}

{if !$profilsiswa.tutup_akses && $kebutuhan_khusus}
<div class="alert alert-secondary alert-dismissible" role="alert">
    Hanya jalur pendaftaran dan sekolah yang mendukung <b>Kebutuhan Khusus</b> yang ditampilkan.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
{/if}

{if !$profilsiswa.tutup_akses && ($satu_zonasi_satu_jalur == 1)}
<div class="alert alert-secondary alert-dismissible" role="alert">
     Anda hanya bisa mendaftar menggunakan <b>Satu Jalur pada Satu Zonasi</b>. Mohon berhati-hati dalam menentukan jalur pendaftaran.           
     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
{/if}

{if !$profilsiswa.tutup_akses && !$cek_batasanusia}
<div class="alert alert-danger" role="alert">
    Usia anda tidak memenuhi syarat untuk melakukan pendaftaran. Batasan <b>Usia Pendaftaran</b> jenjang <b>{$batasanusia.bentuk_tujuan_sekolah}</b> : 
    tanggal lahir dari <b><span class='tgl-indo'>{$batasanusia.maksimal_tanggal_lahir}</span></b> 
    sampai dengan <b><span class='tgl-indo'>{$batasanusia.minimal_tanggal_lahir}</span></b>
</div>
{/if}

<div class="alert alert-danger" role='alert' id='kelengkapan-data-notif'>
    Data profil belum lengkap. Silahkan lengkapi data profil sebelum anda bisa melakukan pendaftaran.
</div>

{if !$profilsiswa.tutup_akses && $cek_waktusosialisasi == 1}
<div class="alert alert-secondary" role="alert">
    <b>PERIODE SOSIALISASI. SETELAH PERIODE SOSIALISASI, SEMUA DATA PENDAFTARAN AKAN DIHAPUS. </b>        
</div>
{/if}

{if !$cek_waktupendaftaran && !$cek_waktusosialisasi}
<div class="alert alert-secondary" role="alert">
    <b>Pendaftaran belum dibuka.</b> Periode pendaftaran adalah dari tanggal <b><span class='tgl-indo'>{$waktupendaftaran.tanggal_mulai_aktif}</span></b> sampai dengan tanggal <b><span class='tgl-indo'>{$waktupendaftaran.tanggal_selesai_aktif}</span></b>.      
</div>
{/if}

{if !$profilsiswa.tutup_akses && ($cek_waktupendaftaran || $cek_waktusosialisasi)} 
<div class="alert alert-secondary" role="alert" id="pendaftaran-notif">
    Anda memiliki <b><span id="slot-negeri">0</span> slot</b> pendaftaran sekolah negeri dan <b><span id="slot-swasta">0</span> slot</b> pendaftaran sekolah swasta
</div>
{/if}

<div class="row" id='pendaftaran' {if $cek_batasanusia && ($cek_waktupendaftaran || $cek_waktusosialisasi)}style="display: none;"{/if}>
</div>

<div class="row" id="peta-sebaran">
    <div class="col-12"> 
        <div class="card box-default box-solid">
            <div class="card-header bg-purple with-border">
                <h3 class="box-title">Peta Sebaran Sekolah</h3>
            </div>
            <div class="card-body">
                <div id="peta" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
    </div>
</div>

{literal}
<script id="jalur-pendaftaran" type="text/template">

    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"> 
        <div class="card box-default box-solid">
            <div class="card-header bg-purple with-border">
                <h3 class="box-title">{{jalur.nama}}</h3>
                <div class="box-tools pull-right">
                    <i class="glyphicon glyphicon-ok"></i>
                </div>
            </div>
            <div class="card-body">
                {{jalur.keterangan}}
            </div>
            <div class="card-footer">
                <a href="#" onclick=pilih_sekolah({{idx}}) 
                class="btn btn-primary {{#tutup_akses}}disabled{{/tutup_akses}}>">Klik disini untuk mendaftar</a>
            </div>
        </div>
    </div>

</script>

<script id="pilih-sekolah" type="text/template">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table">
            <tr class="alert-secondary">
                <td><b>Jalur</b></td>
                <td>:</td>
                <td>{{jalur.nama}}</td>
            </tr>
        </table>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 8px;">
        <div class="form-group has-feedback">
            <label for="jenis_pilihan"><b>Urutan Pilihan</b></label>
            <select id="jenis_pilihan" name="jenis_pilihan" class="form-control select2" data-validation="required" autofocus>
                <option value="">-- Silahkan Pilih Urutan --</option>
            </select>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 8px;">
        <div class="form-group has-feedback">
            <label for="sekolah_id"><b>Daftar Sekolah</b></label>
            <select id="sekolah_id" name="sekolah_id" class="form-control select2" data-validation="required" data-validation-error-msg="Belum memilih sekolah">
                <option value="">-- Silahkan Pilih Sekolah --</option>
            </select>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div id="detailsekolah"></div>
    </div>
    <div id="dokumen_pendukung" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    </div>

</script>
{/literal}

<script>
    var cek_waktusosialisasi = {$cek_waktusosialisasi};
    var cek_waktupendaftaran = {$cek_waktupendaftaran};
    var cek_batasanusia = {$cek_batasanusia};
    var pendaftarandikunci = {$pendaftarandikunci};
    var maxpilihannegeri = {$maxpilihannegeri};
    var maxpilihanswasta = {$maxpilihanswasta};
    var jumlahpendaftarannegeri = {$jumlahpendaftarannegeri};
    var jumlahpendaftaranswasta = {$jumlahpendaftaranswasta};
    
    var daftarjalur = {$daftarjalur|json_encode};

    function update_pendaftaran_layout() {
        //update kelengkapan data
        tags.forEach(function(key) {
            if(!konfirmasi[key]) {
                kelengkapan_data = 0;
            }
        });

        if (!konfirmasi['nomer-hp']) {
            kelengkapan_data = 0;
        }

        //update flag pendaftaran dikunci
        if (!cek_batasanusia || (!cek_waktupendaftaran && !cek_waktusosialisasi) || !kelengkapan_data || tutup_akses) {
            pendaftarandikunci = 1;
        }

        //sisa slot pendaftaran
        let slotnegeri = maxpilihannegeri - jumlahpendaftarannegeri;
        let slotswasta = maxpilihanswasta - jumlahpendaftaranswasta;
        $("#slot-negeri").html(slotnegeri);
        $("#slot-swasta").html(slotswasta);

        //update layout
        el = $("#kelengkapan-data-notif");
        if (kelengkapan_data) {
            el.hide();
        } else {
            el.show();
        }

        el = $("#pendaftaran");
        if (pendaftarandikunci) {
            el.hide();
        } else {
            el.show();
        }
        
        daftarjalur.forEach(function(jalur, idx) {
            // render template
            let template = $('#jalur-pendaftaran').html();
            Mustache.parse(template);

            let dom = Mustache.render(template, {
                'jalur'      : jalur,
                'idx'        : idx,
                'tutup_akses': false
            });

            let parent = $("#pendaftaran");
            parent.append(dom);
        });
    }

    function pilih_sekolah(idx) {
        let p = daftarjalur[idx];
        let jalur_id = p['jalur_id'];
        let nama_jalur = p['nama'];

        //TODO: get opsi pilihan

        // render template
        let template = $('#pilih-sekolah').html();
        Mustache.parse(template);

        let dom = Mustache.render(template, {
            'jalur'     : p,
            'has_dokumen_tambahan' : false
        });

        $.confirm({
                title: 'Pilih Sekolah',
                content: dom,
                closeIcon: true,
                columnClass: 'medium',
                // type: 'info',
                // typeAnimated: true,
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
                        text: 'DAFTAR',
                        btnClass: 'btn-orange',
                        action: function(){
                            window.location.href = "#";
                        }
                    },
                }
            });      
    }
</script>