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

<div class="card box-solid">
    <div class="card-body">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="display" id="tnegeri" style="width:100%">
                <thead>
                        <tr>
                            <th class="text-center" data-priority="1">#</th>
                            <th class="text-center none" data-priorty="-1">No. Pendaftaran</th>
                            <th class="text-center">NISN</th>
                            <th class="text-center" data-priority="2">Nama</th>
                            <th class="text-center" data-priority="4">Jenis Kelamin</th>
                            <th class="text-center" data-priority="5">Asal Sekolah</th>
                            <th class="none">Tanggal Pendaftaran</th>
                            <th class="text-center" data-priority="3">Jalur</th>
                            <th class="text-center">Skor</th>
                            <th class="text-center">Jenis Pilihan</th>
                            <th class="text-center">Tanggal Daftar Ulang</th>
                            <th class="none">Nilai Kelulusan</th>
                            <th class="none">Nilai USBN</th>
                            <th class="none">Nomor Kontak</th>
                            {if $cek_waktudaftarulang}
                            <th class="text-center" data-priority="1"></th>
                            {/if}
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $pendaftarditerima as $row2}
                        <tr>
                            <td class="text-center">{$row2.peringkat}</td>
                            <td class="text-center">{$row2.nomor_pendaftaran}</td>
                            <td class="text-center">{$row2.nisn}</td>
                            <td><a href="{$site_url}home/detailpendaftaran?peserta_didik_id={$row2.peserta_didik_id}" target="_blank">{$row2.nama} <i class="fa fas fa-external-link-alt"></i></a></td>
                            <td class="text-center">{$row2.jenis_kelamin}</td>
                            <td class="text-left">{$row2.sekolah_asal}</td>
                            <td class="text-center">{$row2.created_on}</td>
                            <td class="text-center">{$row2.jalur}</td>
                            <td class="text-center">{$row2.skor}</td>
                            <td class="text-center">{$row2.jenis_pilihan}</td>
                            <td class="text-center">{$row2.tanggal_daftar_ulang}</td>
                            <td class="text-center">{$row2.nilai_kelulusan}</td>
                            <td class="text-center">{$row2.nilai_usbn}</td>
                            <td class="text-center">{$row2.nomor_kontak}</td>
                            {if $cek_waktudaftarulang}
                            <td class="text-left">
                                <span class="text-nowrap" style="display: flex;" dt-pendaftaran-id="{$row2.pendaftaran_id}">   
                                {if ($row2.status_daftar_ulang==1)}
                                    <a href="{$site_url}ppdb/sekolah/daftarulang/buktipendaftaran?peserta_didik_id={$row2.peserta_didik_id}" target="_blank" 
                                        class="btn btn-secondary btn-xs" data-bs-toggle="tooltip" title="Bukti Daftar Ulang" data-bs-placement="bottom">Bukti DU</a>
                                {else}        
                                    <button onclick="daftar_ulang('{$row2.pendaftaran_id}');"
                                        class="btn btn-primary btn-xs" data-bs-toggle="tooltip" title="Daftar Ulang" data-bs-placement="top">Daftar Ulang</button><br>
                                {/if}
                                </span>
                            </td>
                            {/if}
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
