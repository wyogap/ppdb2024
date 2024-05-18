
<div class="custom-tab-1" id="tabs">
    <ul class="nav nav-tabs nav-justified" id="tabNames" style="margin-bottom: 16px;">
        <li class=" nav-item"><a href="#belum" class="nav-link active" data-bs-toggle="tab" id='label-belum'>Belum Diverifikasi</a></li>
        <li class=" nav-item"><a href="#sedang" class="nav-link" data-bs-toggle="tab" id='label-sedang'>Belum Lengkap</a></li>
        <li class=" nav-item"><a href="#sudah" class="nav-link" data-bs-toggle="tab" id="label-sudah">Sudah Lengkap</a></li>
        <li class=" nav-item"><a href="#berkas" class="nav-link" data-bs-toggle="tab" id="label-berkas">Berkas Di Sekolah</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="belum">

                <table class="display" id="tabelbelum" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="text-center" data-priority="1">Nama</th>
                            <th class="text-center">Nomor Pendaftaran</th>
                            <th class="text-center">NISN</th>
                            <th class="text-center">Sekolah Asal</th>
                            <th class="text-center" data-priority="3">Jalur</th>
                            <th class="text-center">Jenis Pilihan</th>
                            <th class="text-center">Tanggal Pendaftaran</th>
                            <th class="text-center">Sedang Verifikasi</th>
                            <th class="text-center" data-priority="1">#</th>
                        </tr>
                    </thead>
                </table>
        </div>
        <div class="tab-pane" id="sedang">
            <!-- <div class="table-responsive"> -->
                <table class="display" id="tabelsedang" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="text-center" data-priority="1">Nama</th>
                            <th class="text-center">Nomor Pendaftaran</th>
                            <th class="text-center">NISN</th>
                            <th class="text-center">Sekolah Asal</th>
                            <th class="text-center" data-priority="3">Jalur</th>
                            <th class="text-center">Jenis Pilihan</th>
                            <th class="text-center">Tanggal Pendaftaran</th>
                            <th class="text-center">Sedang Verifikasi</th>
                            <th class="text-center" data-priority="1">#</th>
                        </tr>
                    </thead>
                </table>
            <!-- </div> -->
        </div>
        <div class="tab-pane" id="sudah">
            <!-- <div class="table-responsive"> -->
                <table class="display" id="tabelsudah" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="text-center" data-priority="1">Nama</th>
                            <th class="text-center">Nomor Pendaftaran</th>
                            <th class="text-center">NISN</th>
                            <th class="text-center">Sekolah Asal</th>
                            <th class="text-center">Jalur</th>
                            <th class="text-center">Jenis Pilihan</th>
                            <!-- <th class="text-center">Tanggal Pendaftaran</th> -->
                            <th class="text-center" data-priority="3">Tanggal Verifikasi</th>
                            <th class="text-center" data-priority="4">Lokasi Berkas</th>
                            <th class="text-center" data-priority="1">#</th>
                        </tr>
                    </thead>
                </table>
            <!-- </div> -->
        </div>
        <div class="tab-pane" id="berkas">
            <!-- <div class="table-responsive"> -->
                <table class="display" id="tabelberkas" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="text-center" data-priority="1">Nama</th>
                            <th class="text-center">NISN</th>
                            <th class="text-center" data-priority="4">Asal Sekolah</th>
                            <th class="text-center">Kelengkapan Berkas</th>
                            <th class="text-center">Sedang Verifikasi</th>
                            <th class="text-center" data-priority="1">#</th>
                       </tr>
                    </thead>
                </table>
            <!-- </div> -->
        </div>
    </div>

            <!-- </div>
        </div>
        </div>
    <<div> -->

</div>

<span id="verifikasi" style="display: none;">
    {include file="./verifikasisiswa.tpl"}
</span>