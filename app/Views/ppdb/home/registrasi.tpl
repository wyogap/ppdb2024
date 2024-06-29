

    <style>
        .form-control-sm {
            font-size: 14px;
            height: 40px;
        }

        .line-title {
            text-align: center;
            position: relative;
            margin-top: 12px;
            margin-bottom: 24px;
            z-index: 1;
            display: flex;
            align-items: center;
        }
                
        .line-title:before, 
        .line-title:after {
            content: "";
            height: 1px;
            flex: 1 1;
            left: 0;
            background-color: #E1E1F0;
            top: 50%;
            z-index: -1;
            margin: 0;
            padding: 0;
        }

        .btn-tarik-data {
            height: 40px;
            padding: 0.5rem 1.5rem;
        }
    </style>

    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Registrasi Siswa Luar Daerah</a></li>
        </ol>
    </div>

    {if $cek_sosialisasi}
    <div class="alert alert-secondary" role="alert">
        <b>PERIODE SOSIALISASI. SETELAH PERIODE SOSIALISASI, SEMUA DATA PENDAFTARAN AKAN DIHAPUS. </b>        
    </div>
    {/if}

    {if !empty($info)}{$info}{/if}

    {if !empty($info_message)}
    <div class="alert alert-info alert-dismissable">
        {$error_message}                    
    </div>
    {/if}

    {if !empty($error_message)}
    <div class="alert alert-danger alert-dismissable">
        {$error_message}                    
    </div>
    {/if}

    {if !empty($success_message)}
    <div class="alert alert-success alert-dismissable">
        {$success_message}                 
    </div>
    {/if}

    {if $sukses|default: 0}
    <a href="{$site_url}" class="btn btn-primary">Kembali ke Halaman Utama</a>
    {else}
        <div class="card box-solid">
            <div class="card-header with-border">
                <h3 class="card-title text-primary"><i class="glyphicon glyphicon-book"></i><b>Tarik Data DAPODIK</b></h3>
            </div>
            <div class="card-body">
                <p>Untuk mempermudah registrasi, ayo kita coba tarik data kamu dari sistem DAPODIK. Silahkan masukkan NISN kamu dan NPSN sekolah asal kamu di bawah. Setelah itu tekan tombol "Tarik Data DAPODIK".</p>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="nisn">NISN</label>
                            <input type="text" class="form-control form-control-sm" id="cari_nisn" name="cari_nisn" placeholder="NISN" minlength="10" maxlength="10" data-validation="required">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="nisn">NPSN Sekolah Asal</label>
                            <input type="text" class="form-control form-control-sm" id="cari_npsn" name="cari_npsn" placeholder="NPSN" minlength="8" maxlength="8" data-validation="required">

                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="align-content: end;">
                        <div class="form-group has-feedback">
                            <button class="btn btn-primary btn-flat btn-tarik-data" style="margin-bottom: 16px;">Tarik Data DAPODIK</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-dismissable" id="status-tarik-data" style="display: none;">
        </div>

        
        <form role="form" enctype="multipart/form-data" id="registrasi" action="{$base_url}home/doregistrasi" method="post">
        <div id="formulir" style="display: none">
        <div class="card box-solid" id="asal-sekolah-2">
            <div class="card-header with-border">
                <h3 class="card-title text-primary"><i class="glyphicon glyphicon-book"></i><b>Pengisian Asal Sekolah</b></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="bentuk">No NPSN</label>
                            <input type="text" class="form-control form-control-sm" id="npsn_sekolah" name="npsn_sekolah" placeholder="Masukkan NPSN Sekolah Di Sini" data-validation="required">
                            <span>NPSN adalah nomor induk sekolah yang terdaftar di Data Pokok Pendidikan (DAPODIK) Kementerian Pendidikan</span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="bentuk">Nama Sekolah</label>
                            <input type="text" class="form-control form-control-sm" id="nama_sekolah" name="nama_sekolah" placeholder="Masukkan Nama Sekolah Di Sini" data-validation="required">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card box-solid">
            <div class="card-header with-border">
                <h3 class="card-title text-primary"><i class="glyphicon glyphicon-user"></i><b>Pengisian Identitas Siswa</b></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="nik">NIK</label>
                            <input type="number" class="form-control form-control-sm" id="nik" name="nik" placeholder="NIK" min="1000000000000000" max="9999999999999999" data-validation="required">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="nisn">NISN</label>
                            <input type="text" class="form-control form-control-sm" id="nisn" name="nisn" placeholder="NISN" minlength="10" maxlength="10" data-validation="required">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="nomor_ujian">Nomor Ujian (Apabila Ada)</label>
                            <input type="text" class="form-control form-control-sm" id="nomor_ujian" name="nomor_ujian" placeholder="Nomor Ujian" minlength="3" maxlength="20">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control form-control-sm" id="nama" name="nama" placeholder="Nama" minlength="3" maxlength="100" data-validation="required">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="form-control select2" style="width:100%;" data-validation="required">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <input type="text" class="form-control form-control-sm" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" minlength="3" maxlength="32" data-validation="required">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" id="tanggal_lahir_siswa">
                        <div class="form-group has-feedback">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input id="tanggal_lahir" name="tanggal_lahir" type="text" class="form-control form-control-sm" aria-describedby="basic-addon1" data-validation="required">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="nama_ibu_kandung">Nama Ibu Kandung</label>
                            <input type="text" class="form-control form-control-sm" id="nama_ibu_kandung" name="nama_ibu_kandung" placeholder="Nama Ibu Kandung" minlength="3" maxlength="100" data-validation="required">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="kebutuhan_khusus">Kebutuhan Khusus</label>
                            <select id="kebutuhan_khusus" name="kebutuhan_khusus" class="form-control select2" style="width:100%;" data-validation="required">
                                <option value="Tidak ada">Tidak ada</option>
                                <option value="A - Tuna netra">A - Tuna netra</option>
                                <option value="B - Tuna rungu">B - Tuna rungu</option>
                                <option value="C - Tuna grahita ringan">C - Tuna grahita ringan</option>
                                <option value="C1 - Tuna grahita sedang">C1 - Tuna grahita sedang</option>
                                <option value="D - Tuna daksa ringan">D - Tuna daksa ringan</option>
                                <option value="D1 - Tuna daksa sedang">D1 - Tuna daksa sedang</option>
                                <option value="E - Tuna laras">E - Tuna laras</option>
                                <option value="F - Tuna wicara">F - Tuna wicara</option>
                                <option value="K - Kesulitan Belajar">K - Kesulitan Belajar</option>
                                <option value="P - Down Syndrome">P - Down Syndrome</option>
                                <option value="Q - Autis">Q - Autis</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card box-solid">
            <div class="card-header with-border">
                <h3 class="card-title text-primary"><i class="glyphicon glyphicon-road"></i><b>Pengisian Alamat Siswa</b></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="kode_kabupaten">Kabupaten/Kota</label>
                            <select id="kode_kabupaten" name="kode_kabupaten" class="form-control select2" data-validation="required" value="{$kode_kabupaten|default:''}">
                                <option value="">-- Pilih Kabupaten/Kota --</option>
                                {foreach $kabupaten as $row}
                                <option value="{$row['kode_wilayah']}">{$row['kabupaten']} ({$row['provinsi']})</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="kode_kecamatan">Kecamatan</label>
                            <select id="kode_kecamatan" name="kode_kecamatan" class="form-control select2" data-validation="required" value="{$kode_kecamatan|default:''}">
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="kode_wilayah">Desa/Kelurahan</label>
                            <select id="kode_wilayah" name="kode_wilayah" class="form-control select2" data-validation="required" value="{$kode_wilayah|default:''}">
                                <option value="">-- Pilih Desa/Kelurahan --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control form-control-sm" id="alamat" name="alamat" placeholder="Alamat" minlength="3" maxlength="80" data-validation="required" value="{$alamat|default:''}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card box-solid">
            <div class="card-header with-border">
                <h3 class="card-title text-primary"><i class="glyphicon glyphicon-map-marker"></i><b>Pengisian Lokasi Rumah</b></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div id="peta" style="width: 100%; height: 400px; z-index: 1;"></div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p class="text-info">NB : Silahkan pilih lokasi rumah di peta dengan menggunakan pencarian pada tombol <b>cari</b> setelah itu <b>klik</b> pada lokasi peta.</p>
                                <p class="text-danger">Mohon untuk <b>memastikan lokasi rumah</b> mendekati aslinya.</p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group has-feedback">
                                    <label for="lintang">Lintang</label>
                                    <input type="number" readonly="false" class="form-control form-control-sm" id="lintang" name="lintang" placeholder="Lintang" data-validation="required">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group has-feedback">
                                    <label for="bujur">Bujur</label>
                                    <input type="number" readonly="false" class="form-control form-control-sm" id="bujur" name="bujur" placeholder="Bujur" data-validation="required">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card box-solid">
            <div class="card-header with-border">
                <h3 class="card-title text-primary"><i class="glyphicon glyphicon-phone"></i><b>Nomor Handphone Aktif</b></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
                        <button class="btn">Baru</button>
                        <button class="btn">Hapus</button>
                    </div> -->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-striped" style="margin-bottom: 0px !important;">
                            <tr>
                                <td colspan="1">Sebagai bagian dari proses verifikasi dokumen secara dalam jaringan (daring), kami membutuhkan nomor handphone aktif yang bisa dihubungi sebagai media komunikasi apabila ada dokumen yang perlu diperbaiki ataupun persyaratan tambahan yang perlu dilengkapi.  
                                </td>
                            </tr>
                            <tr id="kontak-ubah-row">
                                <td colspan="1"><div class="form-group has-feedback"><label for="nomor_kontak">Nomor handphone aktif: </label>&nbsp;&nbsp;
                                <input class="form-control form-control-sm" style="width: auto; display: inline-block;" id="nomor_kontak" name="nomor_kontak" type="text" data-validation="required" value=""></input></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card box-solid">
            <div class="card-header with-border">
                <h3 class="card-title text-primary"><i class="glyphicon glyphicon-check"></i><b>Registrasi</b></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        Pastikan semua data yang anda masukkan benar sebelum menekan tombol <b>Registrasi</b> di bawah.
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <button type="submit" class="btn btn-primary btn-flat btn-registrasi"{if !$cek_registrasi && !$cek_sosialisasi && !$cek_pendaftaran} disabled="true"{/if}>Registrasi Siswa</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </form>
    {/if}



