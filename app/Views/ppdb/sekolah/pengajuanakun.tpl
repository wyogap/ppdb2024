<div class="row page-titles">
    <ol class="breadcrumb">
        {if $impersonasi_sekolah|default: FALSE} 
        <li class="breadcrumb-item active">[{$profil['nama']}]</li>
        {/if}
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Sekolah</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Pengajuan Akun</a></li>
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
                            <th class="text-center" data-priority="2">NISN</th>
                            <th class="text-center" data-priority="3">Jenis Kelamin</th>
                            <th class="text-center">Tempat Lahir</th>
                            <th class="text-center" data-priority="4">Tanggal Lahir</th>
                            <th class="text-center">Alamat</th>
                            <th class="text-center">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>
