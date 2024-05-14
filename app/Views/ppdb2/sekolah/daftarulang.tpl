<div class="custom-tab-1" id="tabs">
    <ul class="nav nav-tabs nav-justified" id="tabNames" style="margin-bottom: 16px;">
        {assign var="idx" value=0}
        {foreach $daftarpenerapan as $row}
        {if $row.jalur_id == $smarty.const.JALURID_INKLUSI && !$inklusi}{continue}{/if}
        <li class=" nav-item">
            <a class="nav-link {if $idx==0}active{/if}" href="#p{$row.penerapan_id}" data-bs-toggle="tab" style="min-height: 68px">
                <span class="text-nowrap" {if !empty($row.deskripsi)}data-bs-toggle="tooltip" title="{$row.deskripsi}" data-placement="top" data-bs-html="true"{/if}>{$row.jalur}</span><br>
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
                            <th class="text-center">No. Pendaftaran</th>
                            <th class="text-center">NISN</th>
                            <th class="text-center" data-priority="2">Nama</th>
                            <th class="text-center">Jenis Kelamin</th>
                            <th class="text-center">Tanggal Pendaftaran</th>
                            <th class="text-center">Skor</th>
                            <th class="text-center">Jenis Pilihan</th>
                            <th class="text-center" data-priority="4">Tanggal Daftar Ulang</th>
                            <th class="none">Nilai Kelulusan</th>
                            <th class="none">Nilai USBN</th>
                            <th class="text-center" data-priority="1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $pendaftarditerima[$row.penerapan_id] as $row2}
                        <tr>
                            <td class="text-center">{$row2.peringkat}</td>
                            <td class="text-center">{$row2.nomor_pendaftaran}</td>
                            <td class="text-center">{$row2.nisn}</td>
                            <td><a href="{$site_url}home/detailpendaftaran?peserta_didik_id={$row2.peserta_didik_id}" target="_blank">{$row2.nama}</a></td>
                            <td class="text-center">{$row2.jenis_kelamin}</td>
                            <td class="text-center">{$row2.create_date}</td>
                            <td class="text-center">{$row2.skor}</td>
                            <td class="text-center">{$row2.jenis_pilihan}</td>
                            <td class="text-center">{$row2.tanggal_daftar_ulang}</td>
                            <td class="text-center">{$row2.nilai_kelulusan}</td>
                            <td class="text-center">{$row2.nilai_usbn}</td>
                            <td class="text-left">
                                <span class="text-nowrap" style="display: flex;">
                                <a href="{$site_url}ppdb/sekolah/daftarulang/siswa?pendaftaran_id={$row2.pendaftaran_id}" class="btn btn-primary shadow btn-xs sharp me-1 mb-1"
                                data-bs-toggle="tooltip" title="Daftar Ulang" data-bs-placement="top"><i class='fa fa-check'></i></a><br>
                                {if ($row2.status_daftar_ulang==1)}
                                    <a href="{$site_url}ppdb/sekolah/daftarulang/buktipendaftaran?peserta_didik_id={$row2.peserta_didik_id}" 
                                        class="btn btn-secondary shadow btn-xs sharp me-1" data-bs-toggle="tooltip" title="Bukti Daftar Ulang" data-bs-placement="bottom"><i class='fa fa-print'></i></a>
                                {/if}
                                </span>
                            </td>
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