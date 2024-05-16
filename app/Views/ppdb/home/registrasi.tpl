<!DOCTYPE html>
<html>
    {include file='../header.tpl'}
	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
            <div class="content-wrapper">
                <div class="container">
                    <div class="box box-primary">
                        <div class="login-logo">
                            <a href="javascript:void(0)"><b>Registrasi</b> Siswa <b class="text-green">Luar Daerah</b></a><br>
                            <a class="text-white btn btn-primary" href="{$base_url}home/login"><b><i class="glyphicon glyphicon-chevron-left"></i></b> Kembali ke halaman <b>Log In</b></a>
                        </div>
                        <div class="box-body">
                            {if !empty($info)}<span>{$info}</span>{/if}

                            {if !empty($info_message)}
                            <div class="alert alert-info alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                {$error_message}                    
                            </div>
                            {/if}

                            {if !empty($error_message)}
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                {$error_message}                    
                            </div>
                            {/if}

                            {if !empty($success_message)}
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                {$success_message}                 
                            </div>
                            {/if}

                            {if empty($sukses)}
                            <form role="form" enctype="multipart/form-data" id="prosesregistrasimadrasah" action="{$base_url}home/doregistrasi" method="post">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-book"></i>
                                        <h3 class="box-title text-info"><b>Pengisian Asal Sekolah</b></h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <div class="form-group has-feedback">
                                                    <label for="kode_kabupaten_sekolah">Kabupaten/Kota</label>
                                                    <select id="kode_kabupaten_sekolah" name="kode_kabupaten_sekolah" class="form-control select2" data-validation="required">
                                                        <option value="">-- Pilih Kabupaten/Kota --</option>
                                                        {foreach $kabupaten as $row}
                                                        <option value="{$row['kode_wilayah']}">{$row['kabupaten']} ({$row['provinsi']})</option>
                                                        {/foreach}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <div class="form-group has-feedback">
                                                    <label for="bentuk">Jenjang</label>
                                                    <select id="bentuk" name="bentuk" class="form-control select2" data-validation="required">
                                                        <option value="">-- Pilih Jenjang --</option>
                                                        <!--<option value="RA">RA</option>!-->
                                                        <option value="MI">MI</option>
                                                        <option value="SD">SD</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <div class="form-group has-feedback">
                                                    <label for="sekolah_id">Nama Sekolah</label>
                                                    <select id="sekolah_id" name="sekolah_id" class="form-control select2" style="width:100%;" data-validation="required">
                                                        <option value="">-- Pilih Sekolah --</option>
                                                    </select>
                                                    <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" placeholder="Nama Sekolah (Apabila Tidak Ada Di Daftar)" style="margin-top: 15px; display: none;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-user"></i>
                                        <h3 class="box-title text-info"><b>Pengisian Identitas Siswa</b></h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <div class="form-group has-feedback">
                                                    <label for="nik">NIK</label>
                                                    <input type="number" class="form-control" id="nik" name="nik" placeholder="NIK" min="1000000000000000" max="9999999999999999" data-validation="required">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <div class="form-group has-feedback">
                                                    <label for="nisn">NISN</label>
                                                    <input type="text" class="form-control" id="nisn" name="nisn" placeholder="NISN" minlength="10" maxlength="10" data-validation="required">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <div class="form-group has-feedback">
                                                    <label for="nomor_ujian">Nomor Ujian (Apabila Ada)</label>
                                                    <input type="text" class="form-control" id="nomor_ujian" name="nomor_ujian" placeholder="Nomor Ujian" minlength="3" maxlength="20">
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group has-feedback">
                                                    <label for="nama">Nama</label>
                                                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" minlength="3" maxlength="100" data-validation="required">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group has-feedback">
                                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control select2" style="width:100%;" data-validation="required">
                                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                                        <option value="L">Laki-laki</option>
                                                        <option value="P">Perempuan</option>
                                                    </select>
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
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group has-feedback">
                                                    <label for="tempat_lahir">Tempat Lahir</label>
                                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" minlength="3" maxlength="32" data-validation="required">
                                                </div>
                                            </div>
                                            <div id="tanggal_lahir_siswa" class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group has-feedback">
                                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                                    <input id="tanggal_lahir" name="tanggal_lahir" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required">
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group has-feedback">
                                                    <label for="nama_ibu_kandung">Nama Ibu Kandung</label>
                                                    <input type="text" class="form-control" id="nama_ibu_kandung" name="nama_ibu_kandung" placeholder="Nama Ibu Kandung" minlength="3" maxlength="100" data-validation="required">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-road"></i>
                                        <h3 class="box-title text-info"><b>Pengisian Alamat Siswa</b></h3>
                                    </div>
                                    <div class="box-body">
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
                                                    <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" minlength="3" maxlength="80" data-validation="required" value="{$alamat|default:''}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-map-marker"></i>
                                        <h3 class="box-title text-info"><b>Pengisian Lokasi Rumah</b></h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                                <div id="peta" style="width: 100%; height: 400px;"></div>
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
                                                            <input type="number" readonly="false" class="form-control" id="lintang" name="lintang" placeholder="Lintang" data-validation="required" value="<?php echo $lintang??''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="form-group has-feedback">
                                                            <label for="bujur">Bujur</label>
                                                            <input type="number" readonly="false" class="form-control" id="bujur" name="bujur" placeholder="Bujur" data-validation="required" value="<?php echo ($bujur??''); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-phone"></i>
                                        <h3 class="box-title text-info"><b>Nomor Handphone Aktif</b></h3>
                                    </div>
                                    <div class="box-body">
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
                                                        <td colspan="1"><div class="form-group has-feedback"><label for="nomor_kontak">Nomor handphone aktif: </label>&nbsp;&nbsp;<input  id="nomor_kontak" name="nomor_kontak" type="text" data-validation="required" value=""></input></div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-check"></i>
                                        <h3 class="box-title text-info"><b>Registrasi</b></h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <p>Pastikan semua data yang anda masukkan benar sebelum menekan tombol <b>Registrasi</b> di bawah.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <button type="submit" class="btn btn-primary btn-flat"{if $cek_registrasi} disabled="true"{/if}>Registrasi Siswa</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
            {include file='../footer.tpl'}
		</div>

	</body>
</html>


<script>
    //Date Picker
    $("#tanggal_lahir").datepicker({ 
        format: 'yyyy-mm-dd',
        {if !empty($maxtgllahir)}startDate: new Date("{$maxtgllahir}"),{/if}
        {if !empty($mintgllahir)}endDate: new Date("{$mintgllahir}"){/if}
    });
</script>

<script>
    //Dropdown Select
    $(function () {
        $(".select2").select2();
    });
    
    var kode_kabupaten_sekolah = "{$kode_kabupaten_sekolah|default:''}";
    var sekolah_id = "{$sekolah_id|default:''}";
    var bentuk_sekolah = "{$bentuk_sekolah|default:''}";
    var nik = "{$nik|default:''}";
    var nisn = "{$nisn|default:''}";
    var nomor_ujian = "{$nomor_ujian|default:''}";
    var nama = "{$nama|default:''}";
    var jenis_kelamin = "{$jenis_kelamin|default:''}";
    var tempat_lahir = "{$tempat_lahir|default:''}";
    var tanggal_lahir = "{$tanggal_lahir|default:''}";
    var nama_ibu_kandung = "{$nama_ibu_kandung|default:''}";
    var kebutuhan_khusus = "{$kebutuhan_khusus|default:''}";
    var alamat = "{$alamat|default:''}";
    var kode_kabupaten = "{$kode_kabupaten|default:''}";
    var kode_kecamatan = "{$kode_kecamatan|default:''}";
    var kode_desa = "{$kode_desa|default:''}";
    var kode_wilayah = "{$kode_wilayah|default:''}";
    var lintang = "{$lintang|default:''}";
    var bujur = "{$bujur|default:''}";
    var nama_sekolah = "{$nama_sekolah|default:''}";
    var nomor_kontak = "{$nomor_kontak|default:''}";

    //Event On Change Dropdown
    $(document).ready(function () {

        $('#kode_kabupaten_sekolah').on('change', function() {
            let val1 = $("#kode_kabupaten_sekolah").val();
            if (val1 == kode_kabupaten_sekolah && bentuk_sekolah != "") {
                $("#bentuk").val(bentuk_sekolah).change();
            }

            kode_kabupaten_sekolah = val1;
        });

        $('select[name="bentuk"]').on('change', function() {
            let val1 = $("#kode_kabupaten_sekolah").val();
            let val2 = $("#bentuk").val();
            var data = { kode_wilayah:val1, bentuk:val2 };
            $.ajax({
                type: "POST",
                url : "{$site_url}home/dropdownsekolah",
                data: data,
                success: function(msg){
                    $('#sekolah_id').html(msg);
                    if (val1 == kode_kabupaten_sekolah && val2 == bentuk_sekolah && sekolah_id != "") {
                        $('#sekolah_id').val(sekolah_id).change();
                    }
                    else {
                        $('#sekolah_id').val("").change();
                    }

                    kode_kabupaten_sekolah = val1;
                    bentuk_sekolah = val2;
                }
            });
        });

        $('select[name="sekolah_id"]').on('change', function() {
            var val = $("#sekolah_id").val();
            var txt = $( "#sekolah_id option:selected" ).text();
            if (val == "") {
                $('#nama_sekolah').prop("disabled", false);
                $('#nama_sekolah').prop("placeholder", "Masukkan Nama Sekolah Di Sini");
                $('#nama_sekolah').attr("data-validation", "required");
                $('#nama_sekolah').show();
                $('#sekolah_id').attr("data-validation", "");
                if (nama_sekolah != "") {
                    $('#nama_sekolah').val(nama_sekolah);
                }
            }
            else {
                $('#nama_sekolah').prop("disabled", true);
                $('#nama_sekolah').prop("placeholder", "");
                $('#nama_sekolah').attr("data-validation", "");
                $('#nama_sekolah').val("");
                $('#nama_sekolah').hide();
                $('#sekolah_id').attr("data-validation", "required");
            }

            sekolah_id = val;
        });

        $('#nama_sekolah').on('change', function() {
            nama_sekolah = $('#nama_sekolah').val();
        });

        $('select[name="kode_kabupaten"]').on('change', function() {
            let val = $("#kode_kabupaten").val();
            var data = { kode_wilayah:val };
            $.ajax({
                type: "POST",
                url : "{$site_url}home/dropdownkecamatan",
                data: data,
                success: function(msg){
                    $('#kode_kecamatan').html(msg);
                    if (val == kode_kabupaten && kode_kecamatan != "") {
                        $('#kode_kecamatan').val(kode_kecamatan).change();
                    }
                    kode_kabupaten = val;
                }
            });
        });

        $('select[name="kode_kecamatan"]').on('change', function() {
            let val = $("#kode_kecamatan").val();
            var data = { kode_wilayah:val };
            $.ajax({
                type: "POST",
                url : "{$site_url}home/dropdowndesa",
                data: data,
                success: function(msg){
                    $('#kode_wilayah').html(msg);
                    if (val == kode_kecamatan && kode_wilayah != "") {
                        $('#kode_wilayah').val(kode_wilayah).change();
                    }
                    kode_kecamatan = val;
                }
            });
        });

        $('select[name="kode_wilayah"]').on('change', function() {
            kode_wilayah = $("#kode_wilayah").val();
        });

        if (kode_kabupaten_sekolah != "") {
            $("#kode_kabupaten_sekolah").val(kode_kabupaten_sekolah).change();
        }

        // if (bentuk_sekolah != "") {
        // 	$("#bentuk_sekolah").val(bentuk_sekolah).change();
        // }

        // if (sekolah_id != "") {
        // 	$("#sekolah_id").val(sekolah_id).change();
        // }

        // if (nama_sekolah != "") {
        // 	$("#nama_sekolah").val(nama_sekolah);
        // }

        if (nik != "") {
            $("#nik").val(nik);
        }
        if (nisn != "") {
            $("#nisn").val(nisn);
        }
        if (nomor_ujian != "") {
            $("#nomor_ujian").val(nomor_ujian);
        }
        if (nama != "") {
            $("#nama").val(nama);
        }

        if (jenis_kelamin != "") {
            $("#jenis_kelamin").val(jenis_kelamin).change();
        }

        if (tempat_lahir != "") {
            $("#tempat_lahir").val(tempat_lahir);
        }
        if (tanggal_lahir != "") {
            $("#tanggal_lahir").val(tanggal_lahir);
        }
        if (nama_ibu_kandung != "") {
            $("#nama_ibu_kandung").val(nama_ibu_kandung);
        }

        if (kebutuhan_khusus != "") {
            $("#kebutuhan_khusus").val(kebutuhan_khusus).change();
        }

        if (alamat != "") {
            $("#alamat").val(alamat);
        }

        if (kode_kabupaten != "") {
            $("#kode_kabupaten").val(kode_kabupaten).change();
        }

        if (lintang != "") {
            $("#lintang").val(lintang);
        }

        if (bujur != "") {
            $("#bujur").val(bujur);
        }

        if (lintang != "" && bujur != "") {
            layerGroup.clearLayers();
            new L.marker([lintang,bujur]).addTo(layerGroup).bindPopup("Lokasi :<br>"+lintang+" , "+bujur).openPopup();
        }

        if (nomor_kontak != "") {
            $("#nomor_kontak").val(nomor_kontak);
        }

    });

    //Validasi
    var myLanguage = {
        errorTitle: 'Gagal mengirim data. Belum mengisi semua data wajib:',
        requiredFields: 'Belum mengisi semua data wajib',
    };
    
    // var $messages = $('#error-message-wrapper');
    // $.validate({
    //     language : myLanguage,
    //     ignore: [],
    //     modules: 'security',
    //     errorMessagePosition: "top",
    //     scrollToTopOnError: true,
    //     validateHiddenInputs: true
    // });

    //Peta
    var map_lintang = "{$map_lintang}";
    var map_bujur = "{$map_bujur}";
    var map_namawilayah = "{$nama_wilayah}";
    var map_streetmap = '{$map_streetmap}';
    var map_satelitemap = '{$map_satelitemap}';

    {literal}
    var map = L.map('peta',{ zoomControl:false }).setView([map_lintang,map_bujur],10);
    var tile = L.tileLayer(
        map_streetmap,{ maxZoom: 18,attribution: 'PPDB ' +map_namawilayah,id: 'mapbox.streets' }
    ).addTo(map);

    var streetmap   = L.tileLayer(map_streetmap, { id: 'mapbox.light', attribution: '' }),
        satelitemap  = L.tileLayer(map_satelitemap, { id: 'mapbox.streets',   attribution: '' });

    var baseLayers = {
        "Streets": streetmap,
        "Satelite": satelitemap
    };

    var overlays = {};
    L.control.layers(baseLayers,overlays).addTo(map);

    var layerGroup = L.layerGroup().addTo(map);
    function onMapClick(e) {
        layerGroup.clearLayers();
        let lintang = e.latlng.lat;
        let bujur = e.latlng.lng;
        new L.marker(e.latlng).addTo(layerGroup).bindPopup("Lokasi :<br>"+lintang+" , "+bujur).openPopup();
        document.getElementById("lintang").value=lintang;
        document.getElementById("bujur").value=bujur;
    }
    map.on('click', onMapClick);

    var searchControl = L.esri.Geocoding.geosearch().addTo(map);
    searchControl.on('layerGroup', function(data){
        layerGroup.clearLayers();
    });

    new L.Control.Fullscreen({ position:'bottomleft' }).addTo(map);
    new L.Control.Zoom({ position:'bottomright' }).addTo(map);

    new L.Control.EasyButton( '<span class="map-button">&curren;</span>', function(){
        map.setView([map_lintang,map_bujur],10);;
    }, { position: 'topleft' }).addTo(map);

    streetmap.addTo(map);
    {/literal}

</script>