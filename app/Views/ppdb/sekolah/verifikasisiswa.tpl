<style>
    textarea.catatan {
        width: 100% !important;
        height: 100px;
    }
</style>

<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Verifikasi</a></li>
        <li class="breadcrumb-item" id="nama-siswa">Dummy</li>
    </ol>
</div>

<div class="accordion accordion-primary-solid" id="profil-siswa">
    <div class="accordion-item" id="asal-sekolah" data-editor-id="">
        <div class="accordion-header rounded-lg collapsed" id="asal-sekolah-header" data-bs-toggle="collapse" data-bs-target="#asal-sekolah-content" aria-controls="asal-sekolah-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Asal Sekolah</span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="asal-sekolah-content" class="collapse accordion__body" aria-labelledby="asal-sekolah-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row">
                    <span tcg-field='sekolah' tcg-field-type='toggle' tcg-field-false='hide' tcg-field-true='show'>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <p>(<b><span tcg-field='npsn' tcg-field-type='label'></span></b>) <span tcg-field='sekolah' tcg-field-type='label'></span></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <a href="" target="_blank" class="btn btn-primary" tcg-field='url_sekolah' tcg-field-type='href'>Profil Sekolah Asal</a>
                        </div>
                    </span>
                    <span tcg-field='sekolah' tcg-field-type='toggle' tcg-field-false='show' tcg-field-true='hide'>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <p>Tidak bersekolah sebelumnya.</p>
                    </div>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item" id="profil" data-editor-id="">
        <div class="accordion-header rounded-lg collapsed" id="identitas-header" data-bs-toggle="collapse" data-bs-target="#identitas-content" aria-controls="identitas-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Identitas Siswa <span class='status'></span></span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="identitas-content" class="collapse accordion__body" aria-labelledby="identitas-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <table class="table table-striped" style="margin-bottom: 20px !important;">
                            <tr>
                                <td style="width: 45%;"><b>NIK</b></td>
                                <td>:</td>
                                <td style="width: 50%;"><span tcg-field='nik' tcg-field-type='label'></span></td>
                            </tr>
                            <tr>
                                <td><b>NISN</b></td>
                                <td>:</td>
                                <td><span tcg-field='nisn' tcg-field-type='label'></span></td>
                            </tr>
                            <tr>
                                <td><b>Nomor Ujian</b></td>
                                <td>:</td>
                                <td><span tcg-field='nomor_ujian' tcg-field-type='label'></span></td>
                            </tr>
                            <tr>
                                <td><b>Nama</b></td>
                                <td>:</td>
                                <td><span tcg-field='nama' tcg-field-type='label'></span></td>
                            </tr>
                            <tr>
                                <td><b>Jenis Kelamin</b></td>
                                <td>:</td>
                                <td><span tcg-field='jenis_kelamin_label' tcg-field-type='label'></span></td>
                            </tr>
                            <tr>
                                <td><b>Tempat Lahir</b></td>
                                <td>:</td>
                                <td><span tcg-field='tempat_lahir' tcg-field-type='label'></span></td>
                            </tr>
                            <tr>
                                <td><b>Tanggal Lahir</b></td>
                                <td>:</td>
                                <td><span tcg-field='tanggal_lahir' tcg-field-type='label'></span></td>
                            </tr>
                            <tr>
                                <td style="width: 45%;"><b>Nama Ibu Kandung</b></td>
                                <td>:</td>
                                <td style="width: 50%;"><span tcg-field='nama_ibu_kandung' tcg-field-type='label'></span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <table class="table table-striped" style="margin-bottom: 0px !important;">
                            <tr>
                                <td colspan="3"><b>Alamat (Sesuai Kartu Keluarga)</b></td>
                            </tr>
                            <tr>
                                <td style="width:45%;"><b>Provinsi</b></td>
                                <td>:</td>
                                <!-- <td style="width:50%;"><span tcg-field='provinsi' tcg-field-type='label'></span></td> -->
                                <td style="width: 50%;">
                                    <select class="form-control" id="kode_provinsi"
                                        tcg-tag='profil' tcg-field='kode_provinsi' tcg-field-type='input' tcg-field-submit=0 style="display: none; width: 100%;">
                                        <option value="">-- Pilih Provinsi --</option>
                                        {foreach $provinsi as $prov}
                                            <option value="{$prov.kode_wilayah}">{$prov.provinsi}</option>
                                        {/foreach}
                                    </select>
                                    <span tcg-tag='profil' tcg-field='provinsi' tcg-field-type='label'></span>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Kabupaten/Kota</b></td>
                                <td>:</td>
                                <!-- <td><span tcg-field='kabupaten' tcg-field-type='label'></span></td> -->
                                <td style="width: 50%;">
                                    <select class="form-control" id="kode_kabupaten" 
                                        tcg-tag='profil' tcg-field='kode_kabupaten' tcg-field-type='input' tcg-field-submit=0 style="display: none; width: 100%;">
                                        <option value="">-- Pilih Kabupaten/Kota --</option>
                                    </select>
                                    <span tcg-tag='profil' tcg-field='kabupaten' tcg-field-type='label'></span>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Kecamatan</b></td>
                                <td>:</td>
                                <!-- <td><span tcg-field='kecamatan' tcg-field-type='label'></span></td> -->
                                <td style="width: 50%;">
                                    <select class="form-control" id="kode_kecamatan"  
                                        tcg-tag='profil' tcg-field='kode_kecamatan' tcg-field-type='input' tcg-field-submit=0 style="display: none; width: 100%;">
                                        <option value="">-- Pilih Kecamatan --</option>
                                    </select>
                                    <span tcg-tag='profil' tcg-field='kecamatan' tcg-field-type='label'></span>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Desa/Kelurahan</b></td>
                                <td>:</td>
                                <!-- <td><span tcg-field='desa_kelurahan_label' tcg-field-type='label'></span></td> -->
                                <td style="width: 50%;">
                                    <select class="form-control" id="kode_wilayah"  
                                        tcg-tag='profil' tcg-field='kode_wilayah' tcg-field-type='input' tcg-field-submit=1 style="display: none; width: 100%;">
                                        <option value="">-- Pilih Desa/Kelurahan --</option>
                                    </select>
                                    <span tcg-tag='profil' tcg-field='desa_kelurahan_label' tcg-field-type='label'></span>
                                </td>
                            </tr>
                            <tr tcg-field='padukuhan' tcg-field-type='toggle' tcg-field-false='hide' tcg-field-true='show'>
                                <td><b>Padukuhan</b></td>
                                <td>:</td>
                                <!-- <td><span tcg-field='padukuhan' tcg-field-type='label'></span></td> -->
                                <td style="width: 50%;">
                                    <input class="form-control" 
                                        tcg-tag='profil' tcg-field='padukuhan' tcg-field-type='input' tcg-field-submit=1 style="display: none; width: 100%;"></input>
                                    <span tcg-tag='profil' tcg-field='padukuhan' tcg-field-type='label'></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-striped dokumen-pendukung" style="margin-bottom: 0px !important; margin-top: 20px !important;">
                            <tr>
                                <td colspan="3"><b>Dokumen Pendukung</b></td>
                            </tr>
                            <tr>
                                <td style="width: 45%;"><b>Akte Kelahiran</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    {if !($flag_upload_dokumen)}
                                    Dicocokkan di sekolah tujuan
                                    {else}
                                        <img id="dokumen-5" class="img-view-thumbnail"  tcg-doc-id="5"
                                                src="" 
                                                img-path="" 
                                                img-id="" 
                                                img-title="Akte Kelahiran"
                                                style="display:none; "/> 
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="5" id="unggah-profil-5" hidden/>
                                        <label for="unggah-profil-5" class="btn btn-primary" tcg-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                        </span>
                                        <div id="msg-dokumen-5" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                    {/if}
                                </td>
                            </tr>
                            <tr>
                                <td style="width:45%;"><b>Kartu Keluarga</b></td>
                                <td>:</td>
                                <td>
                                    {if !($flag_upload_dokumen)}
                                    Dicocokkan di sekolah tujuan
                                    {else}
                                        <img id="dokumen-6" class="img-view-thumbnail" tcg-doc-id="6" 
                                            src="" 
                                            img-path="" 
                                            img-id="" 
                                            img-title="Kartu Keluarga"
                                                style="display:none; "/> 
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="{$smarty.const.DOCID_KK}" id="unggah-profil-6" hidden/>
                                        <label for="unggah-profil-6" class="btn btn-primary" tcg-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                        </span>
                                        <div id="msg-dokumen-6" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="box-footer" tcg-visible-tag="dikunci">
                    <table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
                        <tr id="profil-konfirmasi-row">
                            <td colspan="1">
                                <b>Apakah data identitas di atas sudah benar? </b>
                                <select class="form-control input-default status-verifikasi" 
                                    tcg-tag='profil'
                                    tcg-field='verifikasi_profil' tcg-field-type='status' tcg-field-submit=1>
                                <option value="0">Belum Diverifikasi</option>
                                <option value="1">SUDAH Benar</option>
                                <option value="2">BELUM Benar</option>
                                </select>
                                <button class="btn btn-secondary btn-perbaikan" tcg-tag='profil'>Perbaiki Data</button>
                                <button class="btn btn-primary btn-kembalikan" tcg-tag='profil'>Kembalikan Data Awal</button>
                                <button class="btn btn-danger btn-simpan" style="display: none;" tcg-tag='profil'>Simpan Data</button>
                                <button class="btn btn-primary btn-batal" style="display: none;" tcg-tag='profil'>Batalkan</button>
                            </td>
                        </tr>
                        <tr id="profil-catatan-row" class="catatan" tcg-tag='profil'>
                            <td colspan="1">
                                <span id="profil-error-msg">
                                    <textarea class="form-control catatan" placeholder="Catatan verifikasi" tcg-tag='profil'
                                    tcg-field='catatan_profil' tcg-field-type='note' tcg-field-submit=1></textarea>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item" id="lokasi" data-editor-id="">
        <div class="accordion-header rounded-lg collapsed" id="lokasi-header" data-bs-toggle="collapse" data-bs-target="#lokasi-content" aria-controls="lokasi-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Lokasi Rumah <span class='status'></span></span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="lokasi-content" class="collapse accordion__body" aria-labelledby="lokasi-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div id="peta" style="width: 100%; height: 400px; z-index: 1;"></div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
                            <tr>
                                <td><span id="row-span-lintang"><i class="glyphicon glyphicon-edit"></i> </span><b>Lintang</b></td>
                                <td>:</td>
                                <td id="row-lintang-value" style="width: 50%;">
                                    <input class="form-control" id="lintang-input" 
                                        tcg-tag='lokasi'  
                                        tcg-field='lintang' tcg-field-type='input' tcg-field-submit=1
                                        style="display: none;"></input>
                                    <span id="lintang" tcg-tag='lokasi' tcg-field='lintang' tcg-field-type='label'></span>
                                </td>
                            </tr>
                            <tr>
                                <td><span id="row-span-bujur"><i class="glyphicon glyphicon-edit"></i> </span><b>Bujur</b></td>
                                <td>:</td>
                                <td id="row-bujur-value">
                                    <input class="form-control" id="bujur-input" 
                                        tcg-tag='lokasi' 
                                        tcg-field='bujur' tcg-field-type='input' tcg-field-submit=1
                                        style="display: none;"></input>
                                    <span id="bujur" tcg-tag='lokasi' tcg-field='bujur' tcg-field-type='label'></span>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-striped dokumen-pendukung" style="margin-bottom: 0px !important; width: 100%">
                            <tr>
                                <td colspan="3"><b>Dokumen Pendukung</b></td>
                            </tr>
                            <tr>
                                <td style="width:45%;"><b>Surat Domisili disertai Rapor Kelas 6 (Apabila lokasi rumah berbeda dengan alamat pada Kartu Keluarga)</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    {if !($flag_upload_dokumen)}
                                    Dicocokkan di sekolah tujuan
                                    {else}
                                        <img id="dokumen-19" class="img-view-thumbnail" tcg-doc-id="{$smarty.const.DOCID_SUKET_DOMISILI}" 
                                                src="" 
                                                img-path="" 
                                                img-id="" 
                                                img-title="Surat Keterangan Domisili"
                                                style="display:none; "/>  
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="{$smarty.const.DOCID_SUKET_DOMISILI}" id="unggah-profil-19" hidden/>
                                        <label for="unggah-profil-19" class="btn btn-primary" tcg-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                        </span>
                                        <div id="msg-dokumen-19" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                    {/if}
                                </td>
                            </tr>
                            <tr>
                                <td id="row-dokumen-kelas6" style="width: 45%;"><b>Rapor Kelas 6</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    {if !($flag_upload_dokumen)}
                                    Dicocokkan di sekolah tujuan
                                    {else}
                                    <img id="dokumen-26" class="img-view-thumbnail" tcg-doc-id="{$smarty.const.DOCID_RAPOR_KELAS6}" 
                                            src="" 
                                            img-path="" 
                                            img-id="" 
                                            img-title="Rapor Kelas 6"
                                            style="display:none; "/>  
                                    <span>
                                    <input type="file" class="upload-file" tcg-doc-id="{$smarty.const.DOCID_RAPOR_KELAS6}" id="unggah-profil-26" hidden/>
                                    <label for="unggah-profil-26" class="btn btn-primary" tcg-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                    </span>
                                    <div id="msg-dokumen-26" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="box-footer" tcg-visible-tag="dikunci">
                    <table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
                            <tr id="lokasi-konfirmasi-row">
                                <td colspan="1">
                                    <b>Apakah data lokasi rumah di atas sudah benar? </b>
                                    <select class="form-control input-default status-verifikasi" 
                                        tcg-tag='lokasi'
                                        tcg-field='verifikasi_lokasi' tcg-field-type='status' tcg-field-submit=1>
                                    <option value="0">Belum Diverifikasi</option>
                                    <option value="1">SUDAH Benar</option>
                                    <option value="2">BELUM Benar</option>
                                    </select>
                                    <button class="btn btn-secondary btn-perbaikan" tcg-tag='lokasi'>Perbaiki Data</button>
                                    <button class="btn btn-primary btn-kembalikan" tcg-tag='lokasi'>Kembalikan Data Awal</button>
                                    <button class="btn btn-danger btn-simpan" style="display: none;" tcg-tag='lokasi'>Simpan Data</button>
                                    <button class="btn btn-primary btn-batal" style="display: none;" tcg-tag='lokasi'>Batalkan</button>
                                </td>
                            </tr>
                            <tr id="lokasi-catatan-row" class="catatan" tcg-tag='lokasi'>
                                <td colspan="1">
                                    <span id="lokasi-error-msg">
                                        <textarea class="form-control catatan" placeholder="Catatan verifikasi" tcg-tag='lokasi'
                                        tcg-field='catatan_lokasi' tcg-field-type='note' tcg-field-submit=1></textarea>
                                    </span>
                                </td>
                            </tr>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="accordion-item" id="nilai" data-editor-id="">
        <div class="accordion-header rounded-lg collapsed" id="nilai-header" data-bs-toggle="collapse" data-bs-target="#nilai-content" aria-controls="nilai-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Nilai Kelulusan / Nilai Ujian Nasional <span class='status'></span></span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="nilai-content" class="collapse accordion__body" aria-labelledby="nilai-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <table class="table table-striped" style="margin-bottom: 20px !important;">
                            <tr>
                                <td colspan="3"><b>Nilai Kelulusan</b></td>
                            </tr>
                            <tr>
                                <td style="width: 45%;"><b>Nilai Rata-rata Rapor</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <input class="form-control" id="nilai-rapor-input" type="number" tcg-min=0 tcg-max=100 onkeyup=impose_min_max(this)
                                        tcg-tag='nilai'
                                        tcg-field='nilai_semester' tcg-field-type='input' tcg-field-submit=1
                                        style="display: none;"></input>
                                    <span id="nilai-rapor" tcg-tag='nilai' tcg-field='nilai_semester' tcg-field-type='label'></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 45%;"><b>Nilai Rata-rata Ujian Sekolah</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <input class="form-control" id="nilai-lulus-input" type="number" tcg-min=0 tcg-max=100 onkeyup=impose_min_max(this)
                                        tcg-tag='nilai'
                                        tcg-field='nilai_kelulusan' tcg-field-type='input' tcg-field-submit=1 
                                        style="display: none;"></input>
                                    <span id="nilai-lulus" tcg-tag='nilai' tcg-field='nilai_kelulusan' tcg-field-type='label'></span>
                               </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <table class="table table-striped" style="margin-bottom: 0px !important;">
                            <tr>
                                <td colspan="3">
                                    <b>Nilai Ujian Nasional? </b>
                                    <select class="form-control input-default " id="nilai-un" name="nilai-un" 
                                        tcg-tag='nilai'
                                        tcg-field='punya_nilai_un' tcg-field-type='toggle' tcg-field-submit=1
                                        tcg-edit-action='toggle' tcg-toggle-tag='nilai-un'>
                                    <option value="0">Tidak</option>
                                    <option value="1">YA</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="row-un-bin" tcg-visible-tag='nilai-un' tcg-field='nilai_bin'>
                                <td style="width: 45%;"><b>Bahasa Indonesia</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <input class="form-control" id="nilai-bin-input" type="number" tcg-min=0 tcg-max=100 onkeyup=impose_min_max(this) 
                                        tcg-tag='nilai'
                                        tcg-field='nilai_bin' tcg-field-type='input' tcg-field-submit=1
                                        style="display: none;"></input>
                                    <span id="nilai-bin" tcg-tag='nilai' tcg-field='nilai_bin' tcg-field-type='label'></span>
                                </td>
                            </tr>
                            <tr id="row-un-mat" tcg-visible-tag='nilai-un' tcg-field='nilai_mat'>
                                <td><b>Matematika</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <input class="form-control" id="nilai-mat-input" type="number" tcg-min=0 tcg-max=100 onkeyup=impose_min_max(this) 
                                        tcg-tag='nilai'
                                        tcg-field='nilai_mat' tcg-field-type='input' tcg-field-submit=1
                                        style="display: none;"></input>
                                    <span id="nilai-mat" tcg-tag='nilai' tcg-field='nilai_mat' tcg-field-type='label'></span>
                                </td>
                            </tr>
                            <tr id="row-un-ipa" tcg-visible-tag='nilai-un' tcg-field='nilai_ipa'>
                                <td><b>IPA</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <span><input class="form-control" id="nilai-ipa-input" type="number" tcg-min=0 tcg-max=100 onkeyup=impose_min_max(this) 
                                        tcg-tag='nilai'
                                        tcg-field='nilai_ipa' tcg-field-type='input' tcg-field-submit=1
                                        style="display: none;"></input></span>
                                    <span id="nilai-ipa" tcg-tag='nilai' tcg-field='nilai_ipa' tcg-field-type='label'></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-striped dokumen-pendukung" style="margin-bottom: 0px !important; margin-top: 20px !important">
                            <tr>
                                <td colspan="3"><b>Dokumen Pendukung</b></td>
                            </tr>
                            <tr id="row-dokumen-skl">
                                <td style="width: 45%;"><b>Surat Keterangan Lulus</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    {if !($flag_upload_dokumen)}
                                    Dicocokkan di sekolah tujuan
                                    {else}
                                        <img id="dokumen-2" class="img-view-thumbnail" tcg-doc-id='{$smarty.const.DOCID_IJAZAH_SKL}'
                                                src="" 
                                                img-path="" 
                                                img-id="" 
                                                img-title="Ijazah / Surat Keterangan Lulus"
                                                style="display:none; "/>  
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="{$smarty.const.DOCID_IJAZAH_SKL}"" id="unggah-profil-2" hidden/>
                                        <label for="unggah-profil-2" class="btn btn-primary" tcg-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                        </span>
                                        <div id="msg-dokumen-2" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                    {/if}
                                </td>
                            </tr>
                            <tr id="row-dokumen-un" tcg-visible-tag='nilai-un'>
                                <td><span id="row-span-dokumen-skhun"><b>Hasil Ujian Nasional</b></td>
                                <td>:</td>
                                <td>
                                    {if !($flag_upload_dokumen)}
                                    Dicocokkan di sekolah tujuan
                                    {else}
                                        <img id="dokumen-3" class="img-view-thumbnail" tcg-doc-id="{$smarty.const.DOCID_HASIL_UN}" 
                                                src="" 
                                                img-path="" 
                                                img-id="" 
                                                img-title="Surat Keterangan Hasil Ujian Nasional"
                                                style="display:none; "/>  
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="{$smarty.const.DOCID_HASIL_UN}" id="unggah-profil-3" hidden/>
                                        <label for="unggah-profil-3" class="btn btn-primary" tcg-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                        </span>
                                        <div id="msg-dokumen-3" class="box-red" style="padding-left: 5px; padding-right: 5px; display: none;"></div>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    </div>
                </div>
                <div class="box-footer" tcg-visible-tag="dikunci">
                        <table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
                            <tr id="nilai-konfirmasi-row">
                                <td colspan="1">
                                    <b>Apakah data nilai UN dan nilai kelulusan di atas sudah benar? </b>
                                    <select class="form-control input-default status-verifikasi" 
                                        tcg-tag='nilai'
                                        tcg-field='verifikasi_nilai' tcg-field-type='status' tcg-field-submit=1>
                                        <option value="0">Belum Diverifikasi</option>
                                        <option value="1">SUDAH Benar</option>
                                        <option value="2">BELUM Benar</option>
                                    </select>
                                    <button class="btn btn-secondary btn-perbaikan" tcg-tag='nilai'>Perbaiki Data</button>
                                    <button class="btn btn-primary btn-kembalikan" tcg-tag='nilai'>Kembalikan Data Awal</button>
                                    <button class="btn btn-danger btn-simpan" style="display: none;" tcg-tag='nilai'>Simpan Data</button>
                                    <button class="btn btn-primary btn-batal" style="display: none;" tcg-tag='nilai'>Batalkan</button>
                                </td>
                            </tr>
                            <tr id="nilai-catatan-row" class="catatan" tcg-tag='nilai'>
                                <td colspan="1">
                                    <span  id="nilai-error-msg">
                                        <textarea class="form-control catatan" placeholder="Catatan verifikasi" tcg-tag='nilai'
                                        tcg-field='catatan_nilai' tcg-field-type='note' tcg-field-submit=1></textarea>
                                    </span>
                                </td>
                            </tr>
                        </table>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item" id="prestasi" data-editor-id="">
        <div class="accordion-header rounded-lg collapsed" id="prestasi-header" data-bs-toggle="collapse" data-bs-target="#prestasi-content" aria-controls="prestasi-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Prestasi Siswa <span class='status'></span></span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="prestasi-content" class="collapse accordion__body" aria-labelledby="prestasi-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
                            <tr>
                                <td colspan="3">
                                    <b>Punya prestasi akademis atau non-akademis (olahraga/seni/dll)? </b>
                                    <select class="form-control input-default " id="prestasi-akademis" name="prestasi-akademis" 
                                        tcg-tag='prestasi' 
                                        tcg-field='punya_prestasi' tcg-field-type='toggle' tcg-field-submit=1
                                        tcg-edit-action='toggle' tcg-toggle-tag='prestasi'>
                                    <option value="0">Tidak</option>
                                    <option value="1">YA</option>
                                    </select>
                                </td>
                            </tr>
                            <tr tcg-visible-tag='prestasi'>
                                <td style="width: 45%;"><b>Jenjang Prestasi</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <select class="form-control" 
                                        tcg-tag='prestasi' tcg-field='prestasi_skoring_id' tcg-field-type='input' tcg-field-submit=1 style="display: none;">
                                    <option value="0">Tidak ada</option>
                                    {foreach $daftarskoring as $skor}
                                    <option value="{$skor.value}">{$skor.label}</option>
                                    {/foreach}
                                    </select>
                                    <span tcg-tag='prestasi' tcg-field='prestasi_skoring_id' tcg-field-type='label'></span>
                                </td>
                            </tr>
                            <tr tcg-visible-tag='prestasi'>
                                <td style="width: 45%;"><b>Beri Uraian Tentang Prestasi Tersebut</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <textarea class="form-control" 
                                        tcg-tag='prestasi' tcg-field='uraian_prestasi' tcg-field-type='input' tcg-field-submit=1
                                        style="display: none; width: 100%; height: 100px;"></textarea>
                                    <span tcg-tag='prestasi' tcg-field='uraian_prestasi' tcg-field-type='label'></span>
                               </td>
                            </tr>
                        </table>
                        <table class="table table-striped dokumen-pendukung" style="margin-bottom: 0px !important;" tcg-visible-tag='prestasi'>
                            <tr id="row-dokumen-header">
                                <td colspan="3"><b>Dokumen Pendukung</b></td>
                            </tr>
                            {include file="./_verifikasisiswa_dokumen.tpl" tag='prestasi' visible_tag='' docid=$smarty.const.DOCID_PRESTASI 
                                label='Bukti Pendukung Prestasi yang Dilegalisir' dok=null}
                        </table>
                    </div>
                </div>
                <div class="box-footer" tcg-visible-tag="dikunci">
                    {include file="./_verifikasisiswa_status.tpl" tag="prestasi" tag_label="Prestasi"}
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item" id="afirmasi" data-editor-id="">
        <div class="accordion-header rounded-lg collapsed" id="afirmasi-header" data-bs-toggle="collapse" data-bs-target="#afirmasi-content" aria-controls="afirmasi-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Program Afirmasi <span class='status'></span></span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="afirmasi-content" class="collapse accordion__body" aria-labelledby="afirmasi-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-striped" style="margin-bottom: 0px !important;">
                            <tr>
                                <td colspan="3">
                                    <b>Punya Kartu Indonesia Pintar? </b>
                                    <select class="form-control input-default " id="kip" name="kip" 
                                        tcg-tag='afirmasi'
                                        tcg-field='punya_kip' tcg-field-type='toggle' tcg-field-submit=1
                                        tcg-edit-action='toggle' tcg-toggle-tag='kip'>
                                    <option value="0">Tidak</option>
                                    <option value="1">YA</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="row-kip" tcg-visible-tag='kip'>
                                <td style="width: 45%;"><b>Nomor Kartu Indonesia Pintar</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <input class="form-control" type="text"
                                        tcg-tag='afirmasi' 
                                        tcg-field='no_kip' tcg-field-type='input' tcg-field-submit=1
                                        style="display: none;"></input>
                                    <span tcg-tag='afirmasi' tcg-field='no_kip' tcg-field-type='label'></span>
                                </td>
                            </tr>
                            <tr tcg-visible-tag='kip'>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <b>Masuk di dalam Basis Data Terpadu dalam kategori Keluarga Miskin? </b>
                                    <select class="form-control input-default " id="bdt" name="bdt" 
                                        tcg-tag='afirmasi' 
                                        tcg-field='masuk_bdt' tcg-field-type='toggle' tcg-field-submit=1
                                        tcg-edit-action='toggle' tcg-toggle-tag='bdt'>
                                    <option value="0">Tidak</option>
                                    <option value="1">YA</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="row-bdt" tcg-visible-tag='bdt'>
                                <td style="width: 45%;"><b>Nomor PKH/KJS/BDT</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <span><input class="form-control" id="nomor-bdt-input" type="text"
                                        tcg-tag='afirmasi'
                                        tcg-field='no_bdt' tcg-field-type='input' tcg-field-submit=1
                                        style="display: none;"></input></span>
                                    <span id="nomor-bdt" tcg-tag='afirmasi' tcg-field='no_bdt' tcg-field-type='label'></span>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-striped dokumen-pendukung" style="margin-bottom: 0px !important;" tcg-visible-tag='afirmasi'>
                            <tr id="row-dokumen-afirmasi">
                                <td colspan="3"><b>Dokumen Pendukung</b></td>
                            </tr>
                            <tr id="row-dokumen-kip" tcg-visible-tag='kip'>
                                <td style="width: 45%;"><b>Kartu Indonesia Pintar</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    {if !($flag_upload_dokumen)}
                                    Dicocokkan di sekolah tujuan
                                    {else}
                                        <img id="dokumen-16" class="img-view-thumbnail" tcg-doc-id="{$smarty.const.DOCID_KIP}" 
                                                src="" 
                                                img-path="" 
                                                img-id="" 
                                                img-title="Kartu Indonesia Pintar"
                                                style="display:none; "/>  
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="{$smarty.const.DOCID_KIP}" id="unggah-profil-16" hidden/>
                                        <label for="unggah-profil-16" class="btn btn-primary" tcg-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                        </span>
                                        <div id="msg-dokumen-16" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                    {/if}
                                </td>
                            </tr>
                            <tr id="row-dokumen-bdt" tcg-visible-tag='bdt'>
                                <td style="width: 45%;"><b>Kartu PKH / Kartu KJS / Surat Keterangan masuk BDT dari Desa/Kelurahan</b></td>
                                <td>:</td>
                                <td>
                                    {if !($flag_upload_dokumen)}
                                    Dicocokkan di sekolah tujuan
                                    {else}
                                        <img id="dokumen-20" class="img-view-thumbnail" tcg-doc-id="{$smarty.const.DOCID_SUKET_BDT}" 
                                                src="" 
                                                img-path="" 
                                                img-id="" 
                                                img-title="Surat Keterangan Basis Data Terpadu"
                                                style="display:none; "/>  
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="{$smarty.const.DOCID_SUKET_BDT}" id="unggah-profil-20" hidden/>
                                        <label for="unggah-profil-20" class="btn btn-primary" tcg-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                        </span>
                                        <div id="msg-dokumen-20" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="box-footer" tcg-visible-tag="dikunci">
                        <table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
                            <tr id="afirmasi-konfirmasi-row">
                                <td colspan="1">
                                    <b>Apakah data afirmasi di atas sudah benar? </b>
                                    <select class="form-control input-default status-verifikasi" 
                                        tcg-tag='afirmasi'
                                        tcg-field='verifikasi_afirmasi' tcg-field-type='status' tcg-field-submit=1>
                                        <option value="0">Belum Diverifikasi</option>
                                    <option value="1">SUDAH Benar</option>
                                    <option value="2">BELUM Benar</option>
                                    </select>
                                    <button class="btn btn-secondary btn-perbaikan" tcg-tag='afirmasi'>Perbaiki Data</button>
                                    <button class="btn btn-primary btn-kembalikan" tcg-tag='nilai'>Kembalikan Data Awal</button>
                                    <button class="btn btn-danger btn-simpan" style="display: none;" tcg-tag='afirmasi'>Simpan Data</button>
                                    <button class="btn btn-primary btn-batal" style="display: none;" tcg-tag='afirmasi'>Batalkan</button>
                                </td>
                            </tr>
                            <tr id="afirmasi-catatan-row" class="catatan" tcg-tag='afirmasi'>
                                <td colspan="1">
                                    <span  id="afirmasi-error-msg">
                                        <textarea class="form-control catatan" placeholder="Catatan verifikasi" tcg-tag='afirmasi'
                                        tcg-field='catatan_afirmasi' tcg-field-type='note' tcg-field-submit=1></textarea>
                                    </span>
                                </td>
                            </tr>
                        </table>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item" id="inklusi" data-editor-id="">
        <div class="accordion-header rounded-lg collapsed" id="inklusi-header" data-bs-toggle="collapse" data-bs-target="#inklusi-content" aria-controls="inklusi-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Program Inklusi <span class='status'></span></span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="inklusi-content" class="collapse accordion__body" aria-labelledby="inklusi-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-striped" style="margin-bottom: 0px !important;">
                            <tr>
                                <td colspan="3">
                                    <b>Punya Kebutuhan Khusus? </b>
                                    <select class="form-control input-default " id="inklusi" name="inklusi" 
                                        tcg-tag='inklusi' 
                                        tcg-field='punya_kebutuhan_khusus' tcg-field-type='toggle'
                                        tcg-edit-action='toggle' tcg-toggle-tag='inklusi'>
                                    <option value="0">Tidak</option>
                                    <option value="1">YA</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="row-kebutuhan-khusus" tcg-visible-tag='inklusi'>
                                <td style="width:45%;"><b>Kebutuhan Khusus</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <select class="form-control" id="kebutuhan-khusus-input" name="inklusi" 
                                            tcg-tag='inklusi'
                                            tcg-field='kebutuhan_khusus' tcg-field-type='input' tcg-field-submit='1'
                                            style="display: none;">
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
                                    <span id="kebutuhan-khusus" tcg-tag='inklusi' tcg-field='kebutuhan_khusus' tcg-field-type='label'></span>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-striped dokumen-pendukung" style="margin-bottom: 0px !important;" tcg-visible-tag='inklusi'>
                            <tr id="row-dokumen-header">
                                <td colspan="3"><b>Dokumen Pendukung</b></td>
                            </tr>
                            <tr id="row-dokumen-kebutuhan-khusus">
                                <td style="width:45%;"><b>Surat Keterangan Berkebutuhan Khusus</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    {if !($flag_upload_dokumen)}
                                    Dicocokkan di sekolah tujuan
                                    {else}
                                        <img id="dokumen-9" class="img-view-thumbnail" tcg-doc-id="{$smarty.const.DOCID_SUKET_INKLUSI}" 
                                                src="" 
                                                img-path="" 
                                                img-id="" 
                                                img-title="Surat Keterangan Berkebutuhan Khusus"
                                                style="display:none; "/>  
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="{$smarty.const.DOCID_SUKET_INKLUSI}" id="unggah-profil-9" hidden/>
                                        <label for="unggah-profil-9" class="btn btn-primary" tcg-tag='inklusi' tcg-input-false='show' tcg-input-true='hide' >Unggah</label>
                                        </span>
                                        <div id="msg-dokumen-9" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="box-footer" tcg-visible-tag="dikunci">
                        <table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
                            <tr id="inklusi-konfirmasi-row">
                                <td colspan="1">
                                    <b>Apakah data kebutuhan khusus di atas sudah benar? </b>
                                    <select class="form-control input-default status-verifikasi"
                                        tcg-tag='inklusi' 
                                        tcg-field='verifikasi_inklusi' tcg-field-type='status' tcg-field-submit=1>
                                        <option value="0">Belum Diverifikasi</option>
                                        <option value="1">SUDAH Benar</option>
                                        <option value="2">BELUM Benar</option>
                                    </select>
                                    <button class="btn btn-secondary btn-perbaikan" tcg-tag='inklusi'>Perbaiki Data</button>
                                    <button class="btn btn-primary btn-kembalikan" tcg-tag='nilai'>Kembalikan Data Awal</button>
                                    <button class="btn btn-danger btn-simpan" style="display: none;" tcg-tag='inklusi'>Simpan Data</button>
                                    <button class="btn btn-primary btn-batal" style="display: none;" tcg-tag='inklusi'>Batalkan</button>
                                </td>
                            </tr>
                            <tr id="inklusi-catatan-row" class="catatan" tcg-tag='inklusi'>
                                <td colspan="1">
                                    <span  id="inklusi-error-msg">
                                        <textarea class="form-control catatan" placeholder="Catatan verifikasi" tcg-tag='inklusi'
                                        tcg-field='catatan_inklusi' tcg-field-type='note' tcg-field-submit=1></textarea>
                                    </span>
                                </td>
                            </tr>
                        </table>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item" id="dokumen" data-editor-id="" 
            tcg-field='punya_dokumen_pendukung' tcg-field-type='toggle' tcg-field-false='hide' tcg-field-true='show'>
        <div class="accordion-header rounded-lg collapsed" id="dokumen-header" data-bs-toggle="collapse" data-bs-target="#dokumen-content" aria-controls="dokumen-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Dokumen Pendukung</span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="dokumen-content" class="collapse accordion__body" aria-labelledby="dokumen-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="daftar-dokumen-wrapper">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item" id="riwayat" data-editor-id="">
        <div class="accordion-header rounded-lg collapsed" id="riwayat-header" data-bs-toggle="collapse" data-bs-target="#riwayat-content" aria-controls="riwayat-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Riwayat Verifikasi</span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="riwayat-content" class="collapse accordion__body" aria-labelledby="riwayat-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table id="triwayat" class="display" style="width: 100%; margin-bottom: 20px;">
                            <thead>
                                <tr>
                                    <td class="text-center" data-priority="1">Tanggal</td>
                                    <td class="text-center" data-priority="2">Oleh</td>
                                    <td class="text-center" data-priority="3">Status</td>
                                    <td class="text-center" data-priority="4">Catatan Kekurangan / Perubahan</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .ctx .ctx-selesai-verifikasi {
        /* background: #1ebbf0;
        background: -moz-linear-gradient(45deg, #1ebbf0 8%, #39dfaa 100%);
        background: -webkit-linear-gradient(45deg, #1ebbf0 8%, #39dfaa 100%);
        background: linear-gradient(45deg, #1ebbf0 8%, #39dfaa 100%); */
    }

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



