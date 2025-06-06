<div class="row page-titles">
    <ol class="breadcrumb">
        {if $impersonasi_sekolah|default: FALSE} 
        <li class="breadcrumb-item active">[{$profilsekolah['nama']}]</li>
        {/if}
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Sekolah</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Penerimaan Siswa Baru</a></li>
    </ol>
</div>

{if $cek_waktupendaftaran_sd!=1 && !empty($waktupendaftaran_sd)}
<div class="alert alert-secondary" role='alert'>
    Periode pendaftaran SD adalah dari tanggal <b><span class='tgl-indo'>{$waktupendaftaran_sd.tanggal_mulai_aktif}</span></b> sampai dengan tanggal <b>
        <span class='tgl-indo'>{$waktupendaftaran_sd.tanggal_selesai_aktif}</span></b>.      
</div>
{/if}

{if $cek_waktusosialisasi == 1}
<div class="alert alert-secondary" role="alert">
    <b>PERIODE SOSIALISASI. SETELAH PERIODE SOSIALISASI, SEMUA DATA PENDAFTARAN AKAN DIHAPUS. </b>        
</div>
{/if}

{if $cek_waktupendaftaran_sd==1 || $cek_waktusosialisasi==1}
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center justify-content-center" id="loading2" style="position: absolute; margin-top: 24px; margin-left: -12px;">
    <div class="loader" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<div class="custom-tab-1" id="tabs">
    <ul class="nav nav-tabs nav-justified" id="tabNames" style="margin-bottom: 16px;">
        <li class=" nav-item"><a class="nav-link active" href="#daftarpendaftar" data-bs-toggle="tab" style="min-height: 68px">Semua<br>Pendaftar<br></a></li>

        {assign var="idx" value=0}
        {foreach $daftarpenerapan as $row}
        {if $row.jalur_id == $smarty.const.JALURID_INKLUSI && !$inklusi}{continue}{/if}
        <li class=" nav-item">
            <a class="nav-link {if $idx==0 && empty($show_all_pendaftar)}active{/if}" href="#p{$row.penerapan_id}" data-bs-toggle="tab" style="min-height: 68px">
                <span class="text-nowrap" {if !empty($row.tooltip)}data-bs-toggle="tooltip" title="{$row.tooltip}" data-placement="top" data-bs-html="true"{/if}>{$row.jalur}</span><br>
                <span class="text-nowrap" style="color: #fff; ">
                <span class="badge badge-secondary" data-bs-toggle="tooltip" title="Jumlah Kuota" data-placement="bottom" style="color: #000; ">{$row.kuota}</span>
                {if $row.tambahan_kuota>0}<span class='badge badge-warning' data-bs-toggle="tooltip" title="Tambahan Kuota" data-placement="bottom" style="color: #000; ">{$row.tambahan_kuota}</span>{/if}
                <span class="badge badge-primary" data-bs-toggle="tooltip" title="Jumlah Diterima" data-placement="bottom" style="color: #000; ">{$row.diterima}</span>
                <span class="badge badge-light" data-bs-toggle="tooltip" title="Total Pendaftar" data-placement="bottom" style="color: #000; ">{$row.total_pendaftar}</span>
                </span>
            </a>
        </li>
        {$idx = $idx+1}
        {/foreach}
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="daftarpendaftar">
            <!-- <div class="table-responsive"> -->
            <table class="display" id="tdaftarpendaftar" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <td class="text-center" data-priority="1">Nama</td>
                        <td class="text-center">Jenis Kelamin</td>
                        <td class="text-center" data-priority="4">NISN</td>
                        <td class="text-center">NIK</td>
                        <td class="text-center">Tempat Lahir</td>
                        <td class="text-center">Tanggal Lahir</td>
                        <td class="text-center">Nama Ibu Kandung</td>
                        <td class="text-center">NPSN Sekolah Asal</td>
                        <td class="text-center" data-priority="6">Sekolah Asal</td>
                        <td class="text-center" data-priority="5">Jalur</td>
                        <td class="text-center" data-priority="3">Status</td>
                        {if $cek_waktupendaftaran_sd==1 || $cek_waktusosialisasi==1}
                        <td class="text-center" data-priority="2"></td>
                        {/if}
                    </tr>
                </thead>
            </table>
            <!-- </div> -->
        </div>
        {assign var="idx" value=0}
        {foreach $daftarpenerapan as $row}
        {if $row.jalur_id == $smarty.const.JALURID_INKLUSI && !$inklusi}{continue}{/if}
        <div class="tab-pane {if $idx==0 && empty($show_all_pendaftar)}active{/if}" id="p{$row.penerapan_id}">
            <!-- <div class="table-responsive"> -->
                <table class="display" id="t{$row.penerapan_id}" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <td class="text-center" data-priority="2">Peringkat</td>
                            <td class="text-center" data-priority="1">Nama</td>
                            <td class="text-center">Jenis Kelamin</td>
                            <td class="text-center" data-priority="4">NISN</td>
                            <td class="text-center">NIK</td>
                            <td class="text-center">Tempat Lahir</td>
                            <td class="text-center" data-priority="6">Tanggal Lahir</td>
                            <td class="text-center">Nama Ibu Kandung</td>
                            <td class="text-center">NPSN Sekolah Asal</td>
                            <td class="text-center">Sekolah Asal</td>
                            <td class="text-center" data-priority="5">Skor</td>
                            {if $cek_waktupendaftaran_sd==1 || $cek_waktusosialisasi==1}
                            <td class="text-center" data-priority="3"></td>
                            {/if}
                        </tr>
                    </thead>
                </table>
            <!-- </div> -->
        </div>
        {$idx = $idx+1}
        {/foreach}
    </div>
</div>

<div class="card box-solid">
    <div class="card-header">
    <h3 class="box-title">Pencarian</h3>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-12 col-md-3">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" minlength="3" maxlength="100">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="nisn" name="nisn" placeholder="NISN" minlength="3" maxlength="10">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="nik" name="nik" placeholder="NIK" minlength="3" maxlength="16">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="form-group has-feedback">
                    <select id="sekolah_id" name="sekolah_id" class="form-control select2" style="width:100%;">
                        <option value="">-- Asal Sekolah --</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-12">
                <a id='btn_search' href="javascript:void(0)" onclick="cari_peserta_didik(); return false;" class="btn btn-sm btn-primary btn-flat" style=" float: right;">Cari Peserta Didik</a>
            </div>
        </div>
        <div class="row" style="margin-top: 16px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center justify-content-center" id="loading" style="position: absolute; margin-top: 24px; margin-left: -12px; display: none;">
                <div class="loader" role="status">
                    <span class="sr-only">Loading...</span>
                </div> 
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="display" id="tsearch" style="width:100%;">
                    <thead>
                        <tr>
                            <!-- <td class="text-center" data-priority="1">#</td> -->
                            <td class="text-center" data-priority="1">Nama</td>
                            <td class="text-center">Jenis Kelamin</td>
                            <td class="text-center" data-priority="3">NISN</td>
                            <td class="text-center">NIK</td>
                            <td class="text-center" data-priority="5">Tanggal Lahir</td>
                            <td class="text-center">Asal Sekolah</td>
                            <td class="text-center" data-priority="4">Mendaftar Di</td>
                            {if $cek_waktupendaftaran_sd==1 || $cek_waktusosialisasi==1}
                            <td class="text-center" data-priority="2"></td>
                            {/if}
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
    <!-- <div class="card-body" style="border-top: solid 1px #f5f5f5;">
    </div> -->
    <!-- <div class="card-footer">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <a id='btn_search' href="javascript:void(0)" onclick="cari_peserta_didik(); return false;" class="btn btn-sm btn-primary btn-flat">Cari Peserta Didik</a>
            </div>
        </div>
    </div> -->
</div>
{/if}
