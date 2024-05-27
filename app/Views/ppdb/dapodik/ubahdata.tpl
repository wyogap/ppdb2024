<div id="ubahdata-wrapper" style="display: none;">

    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Ubah Data</a></li>
            <li class="breadcrumb-item" id="nama-siswa">Dummy</li>
        </ol>
    </div>

    <form role="form" enctype="multipart/form-data" id="ubahdata" action="{$site_url}ppdb/dapodik/daftarsiswa/simpan" method="post">
        <input type="hidden" id="peserta_didik_id" name="peserta_didik_id" tcg-field-type="input">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card box-solid">
                    <div class="card-header with-border">
                        <h3 class="card-title"><b>Perubahan Data</b></h3>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group has-feedback">
                                        <label for="nama">Nama</label>
                                        <input id="nama" tcg-field-type="input" name="nama" type="text" class="form-control" 
                                            aria-describedby="basic-addon1" data-validation="required" placeholder="Nama">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group has-feedback">
                                        <label for="nisn">NISN</label>
                                        <input id="nisn" tcg-field-type="input" name="nisn" type="text" class="form-control" 
                                            aria-describedby="basic-addon1" data-validation="required" placeholder="NISN" minlength="10" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group has-feedback">
                                        <label for="nik">NIK</label>
                                        <input id="nik" tcg-field-type="input" name="nik" type="text" class="form-control" 
                                            aria-describedby="basic-addon1" data-validation="required" placeholder="NIK" minlength="16" maxlength="16">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group has-feedback">
                                        <label for="jenis_kelamin">Jenis Kelamin</label>
                                        <select id="jenis_kelamin" tcg-field-type="input" name="jenis_kelamin" class="form-control select2" style="width:100%;" data-validation="required">
                                            <option value="">--</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group has-feedback">
                                        <label for="kebutuhan_khusus">Kebutuhan Khusus</label>
                                        <select id="kebutuhan_khusus" tcg-field-type="input" name="kebutuhan_khusus" class="form-control select2" style="width:100%;" data-validation="required">
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
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group has-feedback">
                                        <label for="tempat_lahir">Tempat Lahir</label>
                                        <input id="tempat_lahir" tcg-field-type="input" name="tempat_lahir" type="text" class="form-control" 
                                            aria-describedby="basic-addon1" data-validation="required">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group has-feedback">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input id="tanggal_lahir" tcg-field-type="input" name="tanggal_lahir" type="text" class="form-control" 
                                            aria-describedby="basic-addon1" data-validation="required">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group has-feedback">
                                        <label for="nama_ibu">Nama Ibu Kandung</label>
                                        <input id="nama_ibu" tcg-field-type="input" name="nama_ibu" type="text" class="form-control" 
                                            aria-describedby="basic-addon1" data-validation="required">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group has-feedback">
                                        <label for="nama_ayah">Nama Ayah</label>
                                        <input id="nama_ayah" tcg-field-type="input" name="nama_ayah" type="text" class="form-control" 
                                            aria-describedby="basic-addon1" data-validation="required">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group has-feedback">
                                        <label for="kode_kabupaten">Kabupaten/Kota</label>
                                        <select id="kode_kabupaten" tcg-field-type="input" name="kode_kabupaten" class="form-control select2" data-validation="required">
                                            <option value="">-- Pilih Kabupaten/Kota --</option>
                                            {foreach $kabupaten as $kab}
                                                <option value="{$kab.kode_wilayah}">{$kab.kabupaten} ({$kab.provinsi})</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group has-feedback">
                                        <label for="kode_kecamatan">Kecamatan</label>
                                        <select id="kode_kecamatan" tcg-field-type="input" name="kode_kecamatan" class="form-control select2" data-validation="required">
                                            <option value="">-- Pilih Kecamatan --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group has-feedback">
                                        <label for="kode_desa">Desa/Kelurahan</label>
                                        <select id="kode_desa" tcg-field-type="input" name="kode_desa" class="form-control select2" data-validation="required">
                                            <option value="">-- Pilih Desa/Kelurahan --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group has-feedback">
                                        <label for="kode_wilayah">Padukuhan</label>
                                        <select id="kode_wilayah" tcg-field-type="input" name="kode_wilayah" class="form-control select2">
                                            <option value="">-- Pilih Padukuhan --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="form-group has-feedback">
                                        <label for="rt">RT</label>
                                        <input id="rt" tcg-field-type="input" name="rt" type="text" class="form-control" aria-describedby="basic-addon1" 
                                            placeholder="RT">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="form-group has-feedback">
                                        <label for="rw">RW</label>
                                        <input id="rw" tcg-field-type="input" name="rw" type="text" class="form-control" aria-describedby="basic-addon1" 
                                            placeholder="RW">
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card box-solid">
                    <div class="card-header with-border">
                        <h3 class="card-title"><b>Lokasi Rumah</b></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div id="peta" style="width: 100%; height: 400px; z-index: 1;"></div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-info" style="margin-top: 10px; margin-bottom: 10px;">NB : Silahkan klik di peta <b>(<i class="fa fa-map-marker"></i>)</b> untuk perubahan data koordinat lokasi rumah siswa.</div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group has-feedback">
                                    <label for="lintang">Lintang</label>
                                    <input type="text" tcg-field-type="input" class="form-control" id="lintang" name="lintang" placeholder="Lintang" 
                                        data-validation="required">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group has-feedback">
                                    <label for="bujur">Bujur</label>
                                    <input type="text" tcg-field-type="input" class="form-control" id="bujur" name="bujur" placeholder="Bujur" 
                                        data-validation="required">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-flat">Simpan Perubahan</button>
                    </div> -->
                </div>
            </div>
        </div>
    </form>

    <style>
        .ctx .btn {
            /* background-color: #fff; */
            /* color: #6f6f6f; */
            /* bottom: 10px; */
            /* color: #fff; */
            /* display: table; */
            border-radius: 40px;
            height: 50px;
            right: 10px;
            min-width: 50px;
            text-align: center;
            z-index: 99999;
            outline: 0 none;
            text-decoration: none;
            float: right;
            font-size: 18px;
        }

        .ctx {
            position: fixed;
            bottom: 20px;
            right: 70px;
            z-index: 1000;
        }
    </style>
    <div class="ctx">
        <a href="#top" class="btn btn-danger ctx-simpan">
            Simpan
        </a>  
        <a href="#top" class="btn btn-primary ctx-batal">
            Batal
        </a>
    </div>

</div>
