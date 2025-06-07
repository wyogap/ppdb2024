

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

{if $diterima} 
<div class="alert alert-secondary" role='alert'>
    Kamu sudah diterima dan tidak diijinkan melakukan pendaftaran lagi.
</div>
{else if $tutup_akses}
<div class="alert alert-secondary" role='alert'>
    Akses kamu ditutup sementara. Hubungi Administrator untuk membuka akses.
</div>
{/if}

{if !$tutup_akses}
<div class="alert alert-secondary alert-dismissible" role="alert" id='kebutuhan-khusus-notif'>
    Hanya jalur pendaftaran dan sekolah yang mendukung <b>Kebutuhan Khusus</b> yang ditampilkan.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
{/if}

{if !$tutup_akses && ($satu_zonasi_satu_jalur == 1)}
<div class="alert alert-secondary alert-dismissible" role="alert">
     Kamu hanya bisa mendaftar menggunakan <b>Satu Jalur pada Satu Zonasi</b>. Mohon berhati-hati dalam menentukan jalur pendaftaran.           
     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
{/if}

{if !$tutup_akses && !$cek_batasanusia}
<div class="alert alert-danger" role="alert">
    Usiamu tidak memenuhi syarat untuk melakukan pendaftaran. Batasan <b>Usia Pendaftaran</b> jenjang <b>{$batasanusia.nama_jenjang}</b> : 
    tanggal lahir dari <b><span class='tgl-indo'>{$batasanusia.maksimal_tanggal_lahir}</span></b> 
    sampai dengan <b><span class='tgl-indo'>{$batasanusia.minimal_tanggal_lahir}</span></b>
</div>
{/if}

<div class="alert alert-danger" role='alert' id='kelengkapan-data-notif'>
    Data profil belum lengkap. Silahkan lengkapi data profil sebelum kamu bisa melakukan pendaftaran.
</div>

{if !$tutup_akses && $cek_waktusosialisasi == 1}
<div class="alert alert-secondary" role="alert">
    <b>PERIODE SOSIALISASI. SETELAH PERIODE SOSIALISASI, SEMUA DATA PENDAFTARAN AKAN DIHAPUS. </b>        
</div>
{/if}

{if !$tutup_akses && !$cek_waktupendaftaran && !$cek_waktusosialisasi}
<div class="alert alert-secondary" role="alert">
    <b>Pendaftaran belum dibuka.</b> Periode pendaftaran adalah dari tanggal <b><span class='tgl-indo'>{$waktupendaftaran.tanggal_mulai_aktif}</span></b> sampai dengan tanggal <b><span class='tgl-indo'>{$waktupendaftaran.tanggal_selesai_aktif}</span></b>.      
</div>
{/if}

{if !$tutup_akses && ($cek_waktupendaftaran || $cek_waktusosialisasi)} 
<div class="alert alert-secondary" role="alert" id="pendaftaran-notif">
    {if $maxpilihan==$maxpilihanumum}
    Kamu memiliki <b><span id="slot-umum">0</span> slot</b> pendaftaran.
    {else}
    Kamu memiliki <b><span id="slot-negeri">0</span> slot</b> pendaftaran sekolah negeri dan/atau <b><span id="slot-swasta">0</span> slot</b> pendaftaran sekolah swasta.
    {/if}
</div>
{/if}

<div class="row" id='jalur-pendaftaran'>
</div>

<div class="row" id="peta-sebaran-wrapper">
    <div class="col-12"> 
        <div class="card box-default box-solid">
            <div class="card-header bg-purple with-border">
                <h3 class="box-title">Peta Sebaran Sekolah</h3>
            </div>
            <div class="card-body">
                <div id="peta-sebaran" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
    </div>
</div>


