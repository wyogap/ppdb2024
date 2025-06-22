<div class="page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Rekapitulasi Sekolah</a></li>
        {if $show_jenjang}
        <li class="breadcrumb-item active" id="nama-jenjang">{$nama_jenjang_aktif}</li>
        {/if}
    </ol>
    {if $show_jenjang}
    <div class="page-tabs">
        {foreach $daftarjenjang as $row}
        <span class="btn btn-xs btn-secondary btn-jenjang" tcg-jenjang-id="{$row.jenjang_id}" {if $row.jenjang_id==$jenjang_aktif}style="display:none;"{/if}>{$row.nama}</span>
        {/foreach}
    </div>
    {/if}
</div>
 
<div class="page-titles" style="margin-top: -16px;">
    <div>Untuk melihat perangkingan sekolah, silahkan cari sekolah yang dituju lalu klik di tombol <span class="btn btn-xs btn-primary">Peringkat</span> di sebelah kanan.</div>
    <div>Untuk pencarian yang lebih presisi, gunakan tanda kutip pada nama yang dicari. Sebagai contoh: "SMP NEGERI 1 KEBUMEN" (dengan tanda kutip "").</div>
</div>

<div class="card box-solid">
    <!-- <div class="box-header with-border">
        <i class="glyphicon glyphicon-search"></i>
        <h3 class="box-title"><b>Pencarian Siswa</b></h3>
    </div> -->
    <div class="card-body">
        <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table class="display" id="tdaftarpendaftar" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center" data-priority="1">Sekolah</th>
                        <th class="text-center">Kecamatan</th>
                        <th class="text-center">NPSN</th>
                        <th class="text-center">Total<br/>Kuota</th>
                        <th class="text-center" data-priority="3">Total<br/>Pendaftar</th>
                        <th class="text-center" data-priority="4">Diterima</th>
                        <th class="text-center">Selisih</th>
                        <th class="text-center" data-priority="5">Daftar Ulang</th>
                        <th class="text-center">Selisih<br/>(Daftar Ulang)</th>
                        <th class="text-center" data-priority="1">#</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $daftarsekolah as $row}
                    <tr>
                        <td class="text-left">{$row.nama}</td>
                        <td class="text-center">{$row.kecamatan}</td>
                        <td class="text-center">{$row.npsn}</td>
                        <td class="text-center">{$row.kuota}</td>
                        <td class="text-center">{$row.total_pendaftar}</td>
                        <td class="text-center">{$row.diterima}</td>
                        <td class="text-center">{$row.kuota - $row.diterima}</td>
                        <td class="text-center">{$row.daftar_ulang}</td>
                        <td class="text-center">{$row.kekurangan_daftar_ulang}</td>
                        <td class="text-center"><a href="{$base_url}home/peringkat?sekolah_id={$row.sekolah_id}" class="btn btn-xs btn-primary">Peringkat</a></td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        
        </div>	
        </div>
    </div>
</div>

