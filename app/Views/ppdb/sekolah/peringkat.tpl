{if $is_public|default: FALSE} 
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="{$site_url}home/rekapitulasi">Rekapitulasi Pendaftaran</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">{$profilsekolah.nama}</a></li>
    </ol>
</div>
{/if}

{if !$final_ranking|default: FALSE}
<div class="alert alert-info alert-dismissible">
    <i class="icon glyphicon glyphicon-info-sign"></i>
    Perhitungan peringkat dilakukan oleh system secara otomotis pada: <b><span id="last_execution_date" class='local-datetime'>{$last_execution_date}</span></b>. 
    {if $cek_waktupendaftaran==1 && !empty($next_execution_date)}
        Perhitungan selanjutnya pada: <b><span id="next_execution_date" class='local-datetime'>{$next_execution_date}</span></b>
    {/if}
</div>	
{/if}

<div class="custom-tab-1" id="tabs">
    <ul class="nav nav-tabs nav-justified" id="tabNames" style="margin-bottom: 16px;">
        {if $show_all_pendaftar|default: FALSE}
        <li class=" nav-item"><a class="nav-link active" href="#daftarpendaftar" data-bs-toggle="tab" style="min-height: 68px">Semua<br>Pendaftar<br></a></li>
        {/if}

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
        {if $show_all_pendaftar|default: FALSE}
        <div class="tab-pane active" id="daftarpendaftar">
            <!-- <div class="table-responsive"> -->
            <table class="display" id="tdaftarpendaftar" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center" data-priority="1">No</th>
                        <th class="text-center">Nomor Pendaftaran</th>
                        <th class="text-center" data-priority="4">NISN</th>
                        <th class="text-center" data-priority="2">Nama</th>
                        <th class="text-center">Sekolah Asal</th>
                        <th class="text-center" data-priority="5">Jalur</th>
                        <th class="text-center">Jenis Pilihan</th>
                        <th class="text-center">Skor</th>
                        <th class="text-center">Tanggal Pembukuan</th>
                        <th class="text-center" data-priority="3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $semuapendaftar as $row}
                    <tr>
                        <td class="text-center">{$row.idx}</td>
                        <td class="text-center">{$row.nomor_pendaftaran}</td>
                        <td class="text-center">{$row.nisn}</td>
                        <td>{$row.nama}<a href="{$base_url}home/detailpendaftaran?peserta_didik_id={$row.peserta_didik_id}" target="_blank"> <i class="fa fas fa-external-link"></i></a></td>
                        <td>{$row.sekolah_asal}</td>
                        <td class="text-center">{$row.jalur}</td>
                        <td class="text-center">{$row.label_jenis_pilihan}</td>
                        <td class="text-end">{$row.skor}</td>
                        <td class="text-center">{$row.created_on}</td>
                        {*
                            status_penerimaan == 0  => belum diperingkat
                            status_penerimaan == 1  => masuk kuota
                            status_penerimaan == 2  => tidak masuk kuota
                            status_penerimaan == 3  => daftar tunggu
                            status_penerimaan == 4  => diterima pilihan1
                        *}
                        {if $final_ranking|default: FALSE}
                            <td class="text-center 
                                {if $row.status_penerimaan_final==1 || $row.status_penerimaan_final==3}bg-green
                                {elseif $row.status_penerimaan_final==4}bg-gray
                                {elseif $row.status_penerimaan_final==2 && $row.status_penerimaan!=2 && $row.masuk_jenis_pilihan != 0}bg-gray
                                {elseif $row.status_penerimaan_final==2}bg-red
                                {elseif $row.status_penerimaan_final==0}bg-gray
                                {else}bg-gray{/if}
                            ">
                                {if $row.status_penerimaan_final==1 || $row.status_penerimaan_final==3}Diterima
                                {elseif $row.status_penerimaan_final==4}{$row.label_masuk_pilihan}
                                {elseif $row.status_penerimaan_final==2 && $row.status_penerimaan!=2 && $row.masuk_jenis_pilihan!=0 && $row.masuk_jenis_pilihan!=$row.jenis_pilihan}{$row.label_masuk_pilihan}
                                {elseif $row.status_penerimaan_final==2}Tidak Diterima
                                {elseif $row.kelengkapan_berkas==2}Berkas Tidak Lengkap
                                {else}Berkas Tidak Lengkap
                                {/if}
                            </td>
                        {else}
                            <td class="text-center 
                                {if $row.status_penerimaan_final==1 || $row.status_penerimaan_final==3}bg-green
                                {elseif $row.status_penerimaan_final==4}bg-gray
                                {elseif $row.status_penerimaan_final==2 && $row.status_penerimaan!=2 && $row.masuk_jenis_pilihan != 0}bg-gray
                                {elseif $row.status_penerimaan_final==2}bg-red
                                {elseif $row.status_penerimaan_final==0}bg-gray
                                {else}bg-gray{/if}
                            ">
                                {if $row.status_penerimaan_final==1 || $row.status_penerimaan_final==3}Diterima
                                {elseif $row.status_penerimaan_final==4}{$row.label_masuk_pilihan}
                                {elseif $row.status_penerimaan_final==2 && $row.status_penerimaan!=2 && $row.masuk_jenis_pilihan!=0 && $row.masuk_jenis_pilihan!=$row.jenis_pilihan}{$row.label_masuk_pilihan}
                                {elseif $row.status_penerimaan_final==2}Tidak Diterima
                                {elseif $row.kelengkapan_berkas==2}Berkas Belum Lengkap
                                {else}Belum Diperingkat
                                {/if}
                            </td>
                        {/if}
                    </tr>
                    {/foreach}
                </tbody>
            </table>
            <!-- </div> -->
        </div>
        {/if}
        {assign var="idx" value=0}
        {foreach $daftarpenerapan as $row}
        {if $row.jalur_id == $smarty.const.JALURID_INKLUSI && !$inklusi}{continue}{/if}
        <div class="tab-pane {if $idx==0 && empty($show_all_pendaftar)}active{/if}" id="p{$row.penerapan_id}">
            <!-- <div class="table-responsive"> -->
                <table class="display" id="t{$row.penerapan_id}" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" data-priority="1">#</th>
                            <th class="text-center" data-priority="1">Peringkat</th>
                            <th class="text-center" data-priority="10001">Nomor Pendaftaran</th>
                            <th class="text-center">NISN</th>
                            <th class="text-center" data-priority="2">Nama</th>
                            <th class="text-center">Jenis Pilihan</th>
                            <th class="text-center">Tanggal Pembukuan</th>
                            <th class="text-center">Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $pendaftar[$row.penerapan_id] as $row2}
                        <tr>
                            <td class="text-center">{$row2.idx}</td>
                            {if $final_ranking|default: FALSE}
                                {if ($row2.status_penerimaan==4)}
                                <td class="text-center bg-gray">{$row2.label_masuk_pilihan}</td>
                                {elseif ($row2.masuk_jenis_pilihan != $row2.jenis_pilihan && $row2.masuk_jenis_pilihan != 0 && $row2.status_penerimaan != 2)}
                                <td class="text-center bg-gray">{$row2.label_masuk_pilihan}</td>
                                {elseif ($row2.kelengkapan_berkas==2)}
                                <td class="text-center bg-gray">Berkas Tidak Lengkap</td>
                                {elseif ($row2.status_penerimaan==0)}
                                <td class="text-center bg-gray">Berkas Tidak Lengkap</td>
                                {elseif ($row2.status_penerimaan==2)}
                                <td class="text-center bg-red">Tidak Diterima</td>
                                {elseif ($row2.status_penerimaan==3)}
                                <td class="text-center bg-green">{$row2.peringkat_final}</td>
                                {else}
                                <td class="text-center bg-green">{$row2.peringkat_final}</td>
                                {/if}
                            {else}
                                {if ($row2.status_penerimaan==4)}
                                <td class="text-center bg-gray">{$row2.label_masuk_pilihan}</td>
                                {elseif ($row2.masuk_jenis_pilihan != $row2.jenis_pilihan && $row2.masuk_jenis_pilihan != 0 && $row2.status_penerimaan != 2)}
                                <td class="text-center bg-gray">{$row2.label_masuk_pilihan}</td>
                                {elseif ($row2.kelengkapan_berkas==2)}
                                <td class="text-center bg-gray">Berkas Belum Lengkap</td>
                                {elseif ($row2.status_penerimaan==0)}
                                <td class="text-center bg-gray">Belum Diperingkat</td>
                                {elseif ($row2.status_penerimaan==2)}
                                <td class="text-center bg-red">Tidak Masuk Kuota</td>
                                {elseif ($row2.status_penerimaan==3)}
                                <td class="text-center bg-yellow">{$row2.peringkat_final}</td>
                                {else}
                                <td class="text-center bg-green">{$row2.peringkat_final}</td>
                                {/if}
                            {/if}
                            <td class="text-center">{$row2.nomor_pendaftaran}</td>
                            <td class="text-center">{$row2.nisn}</td>
                            <td>    
                            {$row2.nama}<a href="{$base_url}home/detailpendaftaran?peserta_didik_id={$row2.peserta_didik_id}" target="_blank"> <i class="fa fas fa-external-link"></i></a>
                            </td>
                            <td class="text-center hala">{$row2.label_jenis_pilihan}</td>
                            <td class="text-center">{$row2.created_on}</td>
                            <td class="text-end">{$row2.skor}</td>
                        </tr>
                        {/foreach}

                    </tbody>
                </table>
            <!-- </div> -->
        </div>
        {$idx = $idx+1}
        {/foreach}
    </div>
</div>



