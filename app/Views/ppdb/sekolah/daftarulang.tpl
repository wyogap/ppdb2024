{if $cek_waktudaftarulang!=1}
<div class="alert alert-secondary" role='alert'>
    Periode Daftar Ulang adalah dari tanggal <b><span class='tgl-indo'>{$waktudaftarulang.tanggal_mulai_aktif}</span></b> sampai dengan tanggal <b>
        <span class='tgl-indo'>{$waktudaftarulang.tanggal_selesai_aktif}</span></b>.      
</div>
{/if}

{if $impersonasi_sekolah|default: FALSE} 
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">[{$profil['nama']}]</li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Sekolah</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Daftar Ulang</a></li>
    </ol>
</div>
{/if}

<div class="custom-tab-1" id="tabs">
    <ul class="nav nav-tabs nav-justified" id="tabNames" style="margin-bottom: 16px;">
        {assign var="idx" value=0}
        {foreach $daftarpenerapan as $row}
        {if $row.jalur_id == $smarty.const.JALURID_INKLUSI && !$inklusi}{continue}{/if}
        <li class=" nav-item">
            <a class="nav-link {if $idx==0}active{/if}" href="#p{$row.penerapan_id}" data-bs-toggle="tab" style="min-height: 68px">
                <span class="text-nowrap" {if !empty($row.tooltip)}data-bs-toggle="tooltip" title="{$row.tooltip}" data-placement="top" data-bs-html="true"{/if}>{$row.jalur}</span><br>
                <span class="text-nowrap" style="color: #fff; ">
                <span class="badge badge-secondary" data-bs-toggle="tooltip" title="Jumlah Kuota" data-placement="bottom">{$row.kuota}</span>
                <span class="badge badge-primary" data-bs-toggle="tooltip" title="Jumlah Diterima" data-placement="bottom">{$row.diterima}</span>
                </span>
            </a>
        </li>
        {$idx = $idx+1}
        {/foreach}
    </ul>
    <div class="tab-content">
        {assign var="idx" value=0}
        {foreach $daftarpenerapan as $row}
        {if $row.jalur_id == $smarty.const.JALURID_INKLUSI && !$inklusi}{continue}{/if}
        <div class="tab-pane {if $idx==0}active{/if}" id="p{$row.penerapan_id}">
            <!-- <div class="table-responsive"> -->
                <table class="display" id="t{$row.penerapan_id}" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" data-priority="1">#</th>
                            <th class="text-center none" data-priorty="-1">No. Pendaftaran</th>
                            <th class="text-center">NISN</th>
                            <th class="text-center" data-priority="2">Nama</th>
                            <th class="text-center">Jenis Kelamin</th>
                            <th class="text-center">Tanggal Pendaftaran</th>
                            <th class="text-center">Skor</th>
                            <th class="text-center">Jenis Pilihan</th>
                            <th class="text-center" data-priority="4">Tanggal Daftar Ulang</th>
                            <th class="none">Nilai Kelulusan</th>
                            <th class="none">Nilai USBN</th>
                            {if $cek_waktudaftarulang}
                            <th class="text-center" data-priority="1"></th>
                            {/if}
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $pendaftarditerima[$row.penerapan_id] as $row2}
                        <tr>
                            <td class="text-center">{$row2.peringkat}</td>
                            <td class="text-center">{$row2.nomor_pendaftaran}</td>
                            <td class="text-center">{$row2.nisn}</td>
                            <td><a href="{$site_url}home/detailpendaftaran?peserta_didik_id={$row2.peserta_didik_id}" target="_blank">{$row2.nama} <i class="fa fas fa-external-link-alt"></i></a></td>
                            <td class="text-center">{$row2.jenis_kelamin}</td>
                            <td class="text-center">{$row2.created_on}</td>
                            <td class="text-center">{$row2.skor}</td>
                            <td class="text-center">{$row2.jenis_pilihan}</td>
                            <td class="text-center">{$row2.tanggal_daftar_ulang}</td>
                            <td class="text-center">{$row2.nilai_kelulusan}</td>
                            <td class="text-center">{$row2.nilai_usbn}</td>
                            {if $cek_waktudaftarulang}
                            <td class="text-left">
                                <span class="text-nowrap" style="display: flex;">   
                                {if ($row2.status_daftar_ulang==1)}
                                    <a href="{$site_url}ppdb/sekolah/daftarulang/buktipendaftaran?peserta_didik_id={$row2.peserta_didik_id}" target="_blank" 
                                        class="btn btn-secondary btn-xs" data-bs-toggle="tooltip" title="Bukti Daftar Ulang" data-bs-placement="bottom">Bukti DU</a>
                                {else}        
                                    <a href="{$site_url}ppdb/sekolah/daftarulang/siswa?pendaftaran_id={$row2.pendaftaran_id}" target="_blank" 
                                        class="btn btn-primary btn-xs" data-bs-toggle="tooltip" title="Daftar Ulang" data-bs-placement="top">Daftar Ulang</a><br>
                                {/if}
                                </span>
                            </td>
                            {/if}
                        </tr>
                        {/foreach}

                    </tbody>
                </table>
            <!-- </div> -->
        </div>
        {$idx=$idx+1}
        {/foreach}
    </div>
</div>