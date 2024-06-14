<div id="daftarsiswa-wrapper">

<div class="row page-titles">
    <ol class="breadcrumb">
        {if $impersonasi_sekolah|default: FALSE} 
        <li class="breadcrumb-item active">[{$profilsekolah['nama']}]</li>
        {/if}
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Sekolah</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Daftar Siswa Kelas 6</a></li>
    </ol>
</div>

<div class="card box-solid">
    <div class="card-body">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="display" id="tnegeri" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" data-priority="1">Nama</th>
                            <th class="text-center" data-priority="3">NISN</th>
                            <th class="text-center" data-priority="6">Lintang</th>
                            <th class="text-center" data-priority="7">Bujur</th>
                            <th class="text-center" data-priority="8">Last Update</th>
                            <th class="none">NIK</th>
                            <th class="none">Jenis Kelamin</th>
                            <th class="none">Tempat Lahir</th>
                            <th class="none">Tanggal Lahir</th>
                            <th class="none">Nama Ibu Kandung</th>
                            <th class="none">Nama Ayah</th>
                            <th class="none">Alamat</th>
                            <th class="none">RT</th>
                            <th class="none">RW</th>
                            <th class="none">Dusun</th>
                            <th class="none">Desa/Kelurahan</th>
                            <th class="text-center" data-priority="2">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>

</div>

{include file="./ubahdata.tpl"}