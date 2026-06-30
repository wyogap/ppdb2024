
<div class="row page-titles">
    <ol class="breadcrumb">
        {if $impersonasi_sekolah|default: FALSE}<li class="breadcrumb-item active">[{$profil['nama']}]</li>{/if}
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Sekolah</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Rekapitulasi Pendaftaran</a></li>
    </ol>
</div>

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
                            <th class="text-center">Status Penerimaan</th>
                            <th class="none">Nilai Kelulusan</th>
                            <th class="none">Nilai TKA Matematika</th>
                            <th class="none">Nilai TKA Bhs Indonesia</th>
                            <th class="none">Prestasi Kejuaraan</th>
                            <th class="none">Prestasi Akademik</th>
                            <th class="none">Prestasi Organisasi</th>
                            <th class="none">Nomor Kontak</th>
                            <!-- <th class="none">Jenis Pilihan</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $rekappendaftar as $row2}
                        <tr>
                            <td class="text-center">{$row2.peringkat}</td>
                            <td class="text-center">{$row2.nomor_pendaftaran}</td>
                            <td class="text-center">{$row2.nisn}</td>
                            <td><a href="{$site_url}home/detailpendaftaran?peserta_didik_id={$row2.peserta_didik_id}" target="_blank">{$row2.nama} <i class="fa fas fa-external-link-alt"></i></a></td>
                            <td class="text-center">{$row2.jenis_kelamin}</td>
                            <td class="text-left">{$row2.sekolah_asal}</td>
                            <td class="text-center">{$row2.created_on}</td>
                            <td class="text-center">{$row2.penerapan}</td>
                            <td class="text-center">{$row2.skor}</td>
                            <td class="text-center">{$row2.status_penerimaan_label}</td>
                            <td class="text-center">{$row2.nilai_kelulusan}</td>
                            <td class="text-center">{$row2.nilai_mat}</td>
                            <td class="text-center">{$row2.nilai_bin}</td>
                            <td class="text-center">{$row2.prestasi_kejuaraan}</td>
                            <td class="text-center">{$row2.prestasi_akademik}</td>
                            <td class="text-center">{$row2.prestasi_organisasi}</td>
                            <td class="text-center">{$row2.nomor_kontak}</td>
                            <!-- <td class="text-center">{$row2.jenis_pilihan}</td> -->
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
