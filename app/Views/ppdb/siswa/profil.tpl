						

<div class="row" id="profil-dikunci-notif" style="display: none;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="alert alert-info alert-dismissible" role="alert">
            <i class="icon glyphicon glyphicon-info-sign"></i>Data profil terkunci karena anda sudah melakukan pendaftaran.
        </div>
    </div>
</div>

<div class="accordion accordion-primary-solid" id="profil-siswa">
    <div class="accordion-item" id="asal-sekolah" data-editor-id="{$peserta_didik_id}">
        <div class="accordion-header rounded-lg collapsed" id="asal-sekolah-header" data-bs-toggle="collapse" data-bs-target="#asal-sekolah-content" aria-controls="asal-sekolah-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Asal Sekolah</span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="asal-sekolah-content" class="collapse accordion__body" aria-labelledby="asal-sekolah-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row">
                    {if ($profilsiswa.sekolah_id!="")}
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <p>(<b>{$profilsiswa.npsn}</b>) {$profilsiswa.sekolah}</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/{$profilsiswa.sekolah_id}" target="_blank" class="btn btn-primary">Profil Sekolah Asal</a>
                    </div>
                    {else}
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <p>Tidak bersekolah sebelumnya.</p>
                    </div>
                    {/if}
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item" id="nomer-hp" data-editor-id="{$peserta_didik_id}">
        <div class="accordion-header rounded-lg collapsed" id="nomer-hp-header" data-bs-toggle="collapse" data-bs-target="#nomer-hp-content" aria-controls="nomer-hp-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Nomor Handphone Aktif <span class='status'></span></span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="nomer-hp-content" class="collapse accordion__body" aria-labelledby="nomer-hp-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <table class="table table-striped" style="margin-bottom: 0px !important;">
                            <tr>
                                <td colspan="1">Sebagai bagian dari proses verifikasi dokumen secara dalam jaringan (daring), kami membutuhkan nomor handphone aktif yang bisa dihubungi sebagai media komunikasi apabila ada dokumen yang perlu diperbaiki ataupun persyaratan tambahan yang perlu dilengkapi.  
                                </td>
                            </tr>
                            <tr id="kontak-ubah-row">
                                <td colspan="1">Nomor handphone aktif: <input class="form-control input-default" id="nomor_kontak" type="text" value="{$profilsiswa.nomor_kontak}"></input> <button class="btn btn-primary" id="btn_nomor_kontak">Simpan</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item" id="profil" data-editor-id="{$peserta_didik_id}">
        <div class="accordion-header rounded-lg collapsed" id="identitas-header" data-bs-toggle="collapse" data-bs-target="#identitas-content" aria-controls="identitas-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Identitas Siswa <span class='status'></span></span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="identitas-content" class="collapse accordion__body" aria-labelledby="identitas-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <table class="table table-striped">
                            <tr>
                                <td style="width: 45%;"><b>NIK</b></td>
                                <td>:</td>
                                <td style="width: 50%;">{$profilsiswa.nik}</td>
                            </tr>
                            <tr>
                                <td><b>NISN</b></td>
                                <td>:</td>
                                <td>{$profilsiswa.nisn}</td>
                            </tr>
                            <tr>
                                <td><b>Nomor Ujian</b></td>
                                <td>:</td>
                                <td>{$profilsiswa.nomor_ujian}</td>
                            </tr>
                            <tr>
                                <td><b>Nama</b></td>
                                <td>:</td>
                                <td>{$profilsiswa.nama}</td>
                            </tr>
                            <tr>
                                <td><b>Jenis Kelamin</b></td>
                                <td>:</td>
                                <td>{if ($profilsiswa.jenis_kelamin=="L")}Laki-laki{else}Perempuan{/if}</td>
                            </tr>
                            <tr>
                                <td><b>Tempat Lahir</b></td>
                                <td>:</td>
                                <td>{$profilsiswa.tempat_lahir}</td>
                            </tr>
                            <tr>
                                <td><b>Tanggal Lahir</b></td>
                                <td>:</td>
                                <td>{$profilsiswa.tanggal_lahir}</td>
                            </tr>
                            <tr>
                                <td style="width: 45%;"><b>Nama Ibu Kandung</b></td>
                                <td>:</td>
                                <td style="width: 50%;">{$profilsiswa.nama_ibu_kandung}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <table class="table table-striped" style="margin-bottom: 0px !important;">
                            <tr>
                                <td colspan="3"><b>Alamat (Sesuai Kartu Keluarga)</b></td>
                            </tr>
                            {if  (!empty($padukuhan))}
                            <tr>
                                <td><b>Padukuhan</b></td>
                                <td>:</td>
                                <td>{$profilsiswa.padukuhan}</td>
                            </tr>
                            {/if}
                            <tr>
                                <td><b>Desa/Kelurahan</b></td>
                                <td>:</td>
                                <td>{if ($profilsiswa.desa_kelurahan!="")}{$profilsiswa.desa_kelurahan}{else}<b>Desa/Kelurahan masih kosong</b>{/if}</td>
                            </tr>
                            <tr>
                                <td><b>Kecamatan</b></td>
                                <td>:</td>
                                <td>{$profilsiswa.kecamatan}</td>
                            </tr>
                            <tr>
                                <td><b>Kabupaten/Kota</b></td>
                                <td>:</td>
                                <td>{$profilsiswa.kabupaten}</td>
                            </tr>
                            <tr>
                                <td><b>Provinsi</b></td>
                                <td>:</td>
                                <td>{$profilsiswa.provinsi}</td>
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
                                        <img id="dokumen-5" class="img-view-thumbnail" 
                                                src="{(empty($dokumen[5])) ? '' : $dokumen[5]['thumbnail_path']}" 
                                                img-path="{(empty($dokumen[5])) ? '' : $dokumen[5]['web_path']}" 
                                                img-id="{(empty($dokumen[5])) ? '' : $dokumen[5]['dokumen_id']}" 
                                                img-title="Akte Kelahiran"
                                                style="display:none; "/> 
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="5" id="unggah-profil-5" hidden/>
                                        <label for="unggah-profil-5" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
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
                                        <img id="dokumen-6" class="img-view-thumbnail" 
                                            src="{(empty($dokumen[6])) ? '' : $dokumen[6]['thumbnail_path']}" 
                                            img-path="{(empty($dokumen[6])) ? '' : $dokumen[6]['web_path']}" 
                                            img-id="{(empty($dokumen[6])) ? '' : $dokumen[6]['dokumen_id']}" 
                                            img-title="Kartu Keluarga"
                                                style="display:none; "/> 
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="6" id="unggah-profil-6" hidden/>
                                        <label for="unggah-profil-6" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
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
                                <select class="form-control input-default " id="profil-konfirmasi" name="profil-konfirmasi"
                                    tcg-edit-action='submit' tcg-submit-tag='profil'>
                                <option value="0" {if ($profilsiswa.konfirmasi_profil==0 || $profilsiswa.konfirmasi_profil == 2)}selected{/if}>BELUM Benar</option>
                                <option value="1" {if ($profilsiswa.konfirmasi_profil==1)}selected{/if}>SUDAH Benar</option>
                                </select>
                            </td>
                        </tr>
                        <tr id="profil-error-row" class="box-red" tcg-submit-tag='profil' tcg-edit-true='hide' tcg-edit-true='none'>
                            <td colspan="1">
                                <span style="color: red; " id="profil-error-msg"></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item" id="lokasi" data-editor-id="{$peserta_didik_id}">
        <div class="accordion-header rounded-lg collapsed" id="lokasi-header" data-bs-toggle="collapse" data-bs-target="#lokasi-content" aria-controls="lokasi-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Lokasi Rumah <span class='status'></span></span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="lokasi-content" class="collapse accordion__body" aria-labelledby="lokasi-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div id="profil-peta" style="position:relative; width: 100%; height: 100%; min-height: 400px; z-index: 1;"></div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
                            <tr>
                                <td><span id="row-span-lintang"><i class="glyphicon glyphicon-edit"></i> </span><b>Lintang</b></td>
                                <td>:</td>
                                <td id="row-lintang-value" style="width: 50%;">{$profilsiswa.lintang}</td>
                            </tr>
                            <tr>
                                <td><span id="row-span-bujur"><i class="glyphicon glyphicon-edit"></i> </span><b>Bujur</b></td>
                                <td>:</td>
                                <td id="row-bujur-value">{$profilsiswa.bujur}</td>
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
                                        <img id="dokumen-19" class="img-view-thumbnail" 
                                                src="{(empty($dokumen[19])) ? '' : $dokumen[19]['thumbnail_path']}" 
                                                img-path="{(empty($dokumen[19])) ? '' : $dokumen[19]['web_path']}" 
                                                img-id="{(empty($dokumen[19])) ? '' : $dokumen[19]['dokumen_id']}" 
                                                img-title="Surat Keterangan Domisili"
                                                style="display:none; "/>  
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="19" id="unggah-profil-19" hidden/>
                                        <label for="unggah-profil-19" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
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
                                    <img id="dokumen-26" class="img-view-thumbnail" 
                                            src="{(empty($dokumen[26])) ? '' : $dokumen[26]['thumbnail_path']}" 
                                            img-path="{(empty($dokumen[26])) ? '' : $dokumen[26]['web_path']}" 
                                            img-id="{(empty($dokumen[26])) ? '' : $dokumen[26]['dokumen_id']}" 
                                            img-title="Rapor Kelas 6"
                                            style="display:none; "/>  
                                    <span>
                                    <input type="file" class="upload-file" tcg-doc-id="26" id="unggah-profil-26" hidden/>
                                    <label for="unggah-profil-26" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
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
                                    <select class="form-control input-default " id="lokasi-konfirmasi" name="lokasi-konfirmasi"
                                        tcg-edit-action='submit' tcg-submit-tag='lokasi'>
                                    <option value="0" {if ($profilsiswa.konfirmasi_lokasi==0 || $profilsiswa.konfirmasi_lokasi==2)}selected{/if}>BELUM Benar</option>
                                    <option value="1" {if ($profilsiswa.konfirmasi_lokasi==1)}selected{/if}>SUDAH Benar</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="lokasi-error-row" class="box-red" tcg-edit-true='hide' tcg-edit-false='none' tcg-submit-tag='lokasi'>
                                <td colspan="1">
                                    <span style="color: red; " id="lokasi-error-msg"></span>
                                </td>
                            </tr>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="accordion-item" id="nilai" data-editor-id="{$peserta_didik_id}">
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
                                    <span><input class="form-control" id="nilai-rapor-input" type="number" tcg-min=0 tcg-max=100 onkeyup=impose_min_max(this) value="{$profilsiswa.nilai_semester}" 
                                        tcg-input-tag='nilai' tcg-input-true='hide' tcg-input-false='show' tcg-field='nilai_semester' style="display: none;"></input></span>
                                    <span id="nilai-rapor" tcg-input-tag='nilai' tcg-input-true='show' tcg-input-false='hide' tcg-field='nilai_semester'>{$profilsiswa.nilai_semester}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 45%;"><b>Nilai Rata-rata Ujian Sekolah</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <span><input class="form-control" id="nilai-lulus-input" type="number" tcg-min=0 tcg-max=100 onkeyup=impose_min_max(this) value="{$profilsiswa.nilai_kelulusan}"
                                        tcg-input-tag='nilai' tcg-input-true='hide' tcg-input-false='show' tcg-field='nilai_kelulusan' style="display: none;"></input></span>
                                    <span id="nilai-kelulusan" tcg-input-tag='nilai' tcg-input-true='show' tcg-input-false='hide' tcg-field='nilai_kelulusan'>{$profilsiswa.nilai_kelulusan}</span>
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
                                        tcg-input-tag='nilai' tcg-input-true='disable' tcg-input-false='enable' tcg-field='punya_nilai_un'
                                        tcg-edit-action='toggle' tcg-toggle-tag='punya_nilai_un'>
                                    <option value="0" {if empty($profilsiswa.punya_nilai_un)}selected{/if}>Tidak</option>
                                    <option value="1" {if ($profilsiswa.punya_nilai_un==1)}selected{/if}>YA</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="row-un-bin" tcg-visible-tag='punya_nilai_un' tcg-field='nilai_bin'>
                                <td style="width: 45%;"><b>Bahasa Indonesia</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <span><input class="form-control" id="nilai-bin-input" type="number" tcg-min=0 tcg-max=100 onkeyup=impose_min_max(this) value="{$profilsiswa.nilai_bin}"
                                        tcg-input-tag='nilai' tcg-input-true='hide' tcg-input-false='show' tcg-field='nilai_bin' style="display: none;"></input></span>
                                    <span id="nilai-bin" tcg-input-tag='nilai' tcg-input-true='show' tcg-input-false='hide' tcg-field='nilai_bin'>{$profilsiswa.nilai_bin}</span>
                                </td>
                            </tr>
                            <tr id="row-un-mat" tcg-visible-tag='punya_nilai_un' tcg-field='nilai_mat'>
                                <td><b>Matematika</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <span><input class="form-control" id="nilai-mat-input" type="number" tcg-min=0 tcg-max=100 onkeyup=impose_min_max(this) value="{$profilsiswa.nilai_mat}"
                                        tcg-input-tag='nilai' tcg-input-true='hide' tcg-input-false='show' tcg-field='nilai_mat' style="display: none;"></input></span>
                                    <span id="nilai-mat" tcg-input-tag='nilai' tcg-input-true='show' tcg-input-false='hide' tcg-field='nilai_mat'>{$profilsiswa.nilai_mat}</span>
                                </td>
                            </tr>
                            <tr id="row-un-ipa" tcg-visible-tag='punya_nilai_un' tcg-field='nilai_ipa'>
                                <td><b>IPA</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <span><input class="form-control" id="nilai-ipa-input" type="number" tcg-min=0 tcg-max=100 onkeyup=impose_min_max(this) value="{$profilsiswa.nilai_ipa}"
                                        tcg-input-tag='nilai' tcg-input-true='hide' tcg-input-false='show' tcg-field='nilai_ipa' style="display: none;"></input></span>
                                    <span id="nilai-ipa" tcg-input-tag='nilai' tcg-input-true='show' tcg-input-false='hide' tcg-field='nilai_ipa'>{$profilsiswa.nilai_ipa}</span>
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
                                        <img id="dokumen-2" class="img-view-thumbnail" 
                                                src="{(empty($dokumen[2])) ? '' : $dokumen[2]['thumbnail_path']}" 
                                                img-path="{(empty($dokumen[2])) ? '' : $dokumen[2]['web_path']}" 
                                                img-id="{(empty($dokumen[2])) ? '' : $dokumen[2]['dokumen_id']}" 
                                                img-title="Ijazah / Surat Keterangan Lulus"
                                                style="display:none; "/>  
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="2" id="unggah-profil-2" hidden/>
                                        <label for="unggah-profil-2" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                        </span>
                                        <div id="msg-dokumen-2" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                    {/if}
                                </td>
                            </tr>
                            <tr id="row-dokumen-un" tcg-visible-tag='punya_nilai_un'>
                                <td><span id="row-span-dokumen-skhun"><b>Hasil Ujian Nasional</b></td>
                                <td>:</td>
                                <td>
                                    {if !($flag_upload_dokumen)}
                                    Dicocokkan di sekolah tujuan
                                    {else}
                                        <img id="dokumen-3" class="img-view-thumbnail" 
                                                src="{(empty($dokumen[3])) ? '' : $dokumen[3]['thumbnail_path']}" 
                                                img-path="{(empty($dokumen[3])) ? '' : $dokumen[3]['web_path']}" 
                                                img-id="{(empty($dokumen[3])) ? '' : $dokumen[3]['dokumen_id']}" 
                                                img-title="Surat Keterangan Hasil Ujian Nasional"
                                                style="display:none; "/>  
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="3" id="unggah-profil-3" hidden/>
                                        <label for="unggah-profil-3" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
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
                                    <select class="form-control input-default " id="nilai-konfirmasi" name="nilai-konfirmasi"
                                        tcg-edit-action='submit' tcg-submit-tag='nilai'>
                                    <option value="0" {if ($profilsiswa.konfirmasi_nilai==0 || $profilsiswa.konfirmasi_nilai == 2)}selected{/if}>BELUM Benar</option>
                                    <option value="1" {if ($profilsiswa.konfirmasi_nilai==1)}selected{/if}>SUDAH Benar</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="nilai-error-row" class="box-red" style="display: none;">
                                <td colspan="1">
                                    <span style="color: red; " id="nilai-error-msg"></span>
                                </td>
                            </tr>
                        </table>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item" id="prestasi" data-editor-id="{$peserta_didik_id}">
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
                                        tcg-input-tag='prestasi' tcg-input-true='disable' tcg-input-false='enable' tcg-field='punya_prestasi'
                                        tcg-edit-action='toggle' tcg-toggle-tag='punya_prestasi'>
                                    <option value="0" {if empty($profilsiswa.punya_prestasi)}selected{/if}>Tidak</option>
                                    <option value="1" {if ($profilsiswa.punya_prestasi==1)}selected{/if}>YA</option>
                                    </select>
                                </td>
                            </tr>
                            <tr tcg-visible-tag='punya_prestasi'>
                                <td style="width: 45%;"><b>Jenjang Prestasi</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <select class="form-control" tcg-input-tag='prestasi' tcg-input-true='hide' tcg-input-false='show' tcg-field='prestasi_skoring_id' style="display: none;">
                                    <option value="0" {if empty($profilsiswa.prestasi_skoring_id)}selected{/if}>Tidak ada</option>
                                    {foreach $daftarskoring as $skor}
                                    <option value="{$skor.value}" {if ($profilsiswa.prestasi_skoring_id==$skor.value)}selected{/if}>{$skor.label}</option>
                                    {/foreach}
                                    </select>
                                    <span tcg-input-tag='prestasi' tcg-input-true='show' tcg-input-false='hide' tcg-field='prestasi_skoring_id'>{$profilsiswa.prestasi_skoring_label}</span>
                                </td>
                            </tr>
                            <tr tcg-visible-tag='punya_prestasi'>
                                <td style="width: 45%;"><b>Beri Uraian Tentang Prestasi Tersebut</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <textarea class="form-control" tcg-input-tag='prestasi' tcg-input-true='hide' tcg-input-false='show' tcg-field='uraian_prestasi' 
                                        style="display: none; width: 100%; height: 100px;">{$profilsiswa.uraian_prestasi}</textarea>
                                    <span tcg-input-tag='prestasi' tcg-input-true='show' tcg-input-false='hide' tcg-field='uraian_prestasi'>{$profilsiswa.uraian_prestasi}</span>
                               </td>
                            </tr>
                         </table>
                         <table class="table table-striped dokumen-pendukung" style="margin-bottom: 0px !important;" tcg-visible-tag='prestasi'>
                            <tr id="row-dokumen-prestasi">
                                <td colspan="3"><b>Dokumen Pendukung</b></td>
                            </tr>
                            <tr id="row-dokumen-prestasi">
                                <td style="width: 45%;"><b>Bukti Pendukung Prestasi yang Dilegalisir</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    {if !($flag_upload_dokumen)}
                                    Dicocokkan di sekolah tujuan
                                    {else}
                                        <img id="dokumen-8" class="img-view-thumbnail" 
                                                src="{(empty($dokumen[8])) ? '' : $dokumen[8]['thumbnail_path']}" 
                                                img-path="{(empty($dokumen[8])) ? '' : $dokumen[8]['web_path']}" 
                                                img-id="{(empty($dokumen[8])) ? '' : $dokumen[8]['dokumen_id']}" 
                                                img-title="Kartu Indonesia Pintar"
                                                style="display:none; "/>  
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="8" id="unggah-profil-8" hidden/>
                                        <label for="unggah-profil-8" class="btn btn-primary" tcg-input-tag='prestasi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                        </span>
                                        <div id="msg-dokumen-8" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="box-footer" tcg-visible-tag="dikunci">
                        <table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
                            <tr id="prestasi-konfirmasi-row">
                                <td colspan="1">
                                    <b>Apakah data prestasi di atas sudah benar? </b>
                                    <select class="form-control input-default " id="prestasi-konfirmasi" name="prestasi-konfirmasi"
                                        tcg-edit-action='submit' tcg-submit-tag='prestasi'>
                                    <option value="0" {if ($profilsiswa.konfirmasi_prestasi==0 || $profilsiswa.konfirmasi_prestasi == 2)}selected{/if}>BELUM Benar</option>
                                    <option value="1" {if ($profilsiswa.konfirmasi_prestasi==1)}selected{/if}>SUDAH Benar</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="prestasi-error-row" class="box-red" style="display: none;">
                                <td colspan="1">
                                    <span style="color: red; " id="prestasi-error-msg"></span>
                                </td>
                            </tr>
                        </table>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item" id="afirmasi" data-editor-id="{$peserta_didik_id}">
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
                                        tcg-input-tag='afirmasi' tcg-input-true='disable' tcg-input-false='enable' tcg-field='punya_kip'
                                        tcg-edit-action='toggle' tcg-toggle-tag='punya_kip'>
                                    <option value="0" {if empty($profilsiswa.punya_kip)}selected{/if}>Tidak</option>
                                    <option value="1" {if ($profilsiswa.punya_kip==1)}selected{/if}>YA</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="row-kip" tcg-visible-tag='punya_kip'>
                                <td style="width: 45%;"><b>Nomor Kartu Indonesia Pintar</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <span><input class="form-control" id="nomor-kip-input" type="text" value="{$profilsiswa.no_kip}" 
                                        tcg-input-tag='afirmasi' tcg-input-false='show' tcg-input-true='hide' tcg-field='no_kip' style="display: none;"></input></span>
                                    <span id="nomor-kip" tcg-input-tag='afirmasi' tcg-input-false='hide' tcg-input-true='show' tcg-field='no_kip'>{$profilsiswa.no_kip}</span>
                                </td>
                            </tr>
                            <tr tcg-visible-tag='punya_kip'>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <b>Masuk di dalam Basis Data Terpadu dalam kategori Keluarga Miskin? </b>
                                    <select class="form-control input-default " id="bdt" name="bdt" 
                                        tcg-input-tag='afirmasi' tcg-input-true='disable' tcg-input-false='enable' tcg-field='masuk_bdt'
                                        tcg-edit-action='toggle' tcg-toggle-tag='masuk_bdt'>
                                    <option value="0" {if empty($profilsiswa.masuk_bdt)}selected{/if}>Tidak</option>
                                    <option value="1" {if ($profilsiswa.masuk_bdt==1)}selected{/if}>YA</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="row-bdt" tcg-visible-tag='masuk_bdt'>
                                <td style="width: 45%;"><b>Nomor PKH/KJS/BDT</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <span><input class="form-control" id="nomor-bdt-input" type="text" value="{$profilsiswa.no_bdt}"
                                        tcg-input-tag='afirmasi' tcg-input-false='show' tcg-input-true='hide' tcg-field='no_bdt' style="display: none;"></input></span>
                                    <span id="nomor-bdt" tcg-input-tag='afirmasi' tcg-input-false='hide' tcg-input-true='show' tcg-field='no_bdt'>{$profilsiswa.no_bdt}</span>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-striped dokumen-pendukung" style="margin-bottom: 0px !important;" tcg-visible-tag='afirmasi'>
                            <tr id="row-dokumen-afirmasi">
                                <td colspan="3"><b>Dokumen Pendukung</b></td>
                            </tr>
                            <tr id="row-dokumen-kip" tcg-visible-tag='punya_kip'>
                                <td style="width: 45%;"><b>Kartu Indonesia Pintar</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    {if !($flag_upload_dokumen)}
                                    Dicocokkan di sekolah tujuan
                                    {else}
                                        <img id="dokumen-16" class="img-view-thumbnail" 
                                                src="{(empty($dokumen[16])) ? '' : $dokumen[16]['thumbnail_path']}" 
                                                img-path="{(empty($dokumen[16])) ? '' : $dokumen[16]['web_path']}" 
                                                img-id="{(empty($dokumen[16])) ? '' : $dokumen[16]['dokumen_id']}" 
                                                img-title="Kartu Indonesia Pintar"
                                                style="display:none; "/>  
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="16" id="unggah-profil-16" hidden/>
                                        <label for="unggah-profil-16" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                        </span>
                                        <div id="msg-dokumen-16" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                    {/if}
                                </td>
                            </tr>
                            <tr id="row-dokumen-bdt" tcg-visible-tag='masuk_bdt'>
                                <td style="width: 45%;"><b>Kartu PKH / Kartu KJS / Surat Keterangan masuk BDT dari Desa/Kelurahan</b></td>
                                <td>:</td>
                                <td>
                                    {if !($flag_upload_dokumen)}
                                    Dicocokkan di sekolah tujuan
                                    {else}
                                        <img id="dokumen-20" class="img-view-thumbnail" 
                                                src="{(empty($dokumen[20])) ? '' : $dokumen[20]['thumbnail_path']}" 
                                                img-path="{(empty($dokumen[20])) ? '' : $dokumen[20]['web_path']}" 
                                                img-id="{(empty($dokumen[20])) ? '' : $dokumen[20]['dokumen_id']}" 
                                                img-title="Surat Keterangan Basis Data Terpadu"
                                                style="display:none; "/>  
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="20" id="unggah-profil-20" hidden/>
                                        <label for="unggah-profil-20" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
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
                                    <select class="form-control input-default " id="afirmasi-konfirmasi" name="afirmasi-konfirmasi"
                                        tcg-edit-action='submit' tcg-submit-tag='afirmasi'>
                                    <option value="0" {if ($profilsiswa.konfirmasi_afirmasi==0 || $profilsiswa.konfirmasi_afirmasi == 2)}selected{/if}>BELUM Benar</option>
                                    <option value="1" {if ($profilsiswa.konfirmasi_afirmasi==1)}selected{/if}>SUDAH Benar</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="afirmasi-error-row" class="box-red" style="display: none;">
                                <td colspan="1">
                                    <span style="color: red; " id="afirmasi-error-msg"></span>
                                </td>
                            </tr>
                        </table>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item" id="inklusi" data-editor-id="{$peserta_didik_id}">
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
                                        tcg-input-tag='inklusi' tcg-input-true='disable' tcg-input-false='enable' tcg-field=''
                                        tcg-edit-action='toggle' tcg-toggle-tag='kebutuhan_khusus'>
                                    <option value="0" {if ($profilsiswa.kebutuhan_khusus == 'Tidak ada')}selected{/if}>Tidak</option>
                                    <option value="1" {if ($profilsiswa.kebutuhan_khusus != 'Tidak ada')}selected{/if}>YA</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="row-kebutuhan-khusus" tcg-visible-tag='kebutuhan_khusus'>
                                <td style="width:45%;"><b>Kebutuhan Khusus</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    <span>
                                    <select class="form-control" id="kebutuhan-khusus-input" name="inklusi" 
                                            tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide' tcg-field='kebutuhan_khusus' style="display: none;">
                                        <option value="Tidak ada" {if ($profilsiswa.kebutuhan_khusus == 'Tidak ada')}selected{/if}>Tidak ada</option>
                                        <option value="A - Tuna netra" {if ($profilsiswa.kebutuhan_khusus == 'A - Tuna netra')}selected{/if}>A - Tuna netra</option>
                                        <option value="B - Tuna rungu" {if ($profilsiswa.kebutuhan_khusus == 'B - Tuna rungu')}selected{/if}>B - Tuna rungu</option>
                                        <option value="C - Tuna grahita ringan" {if ($profilsiswa.kebutuhan_khusus == 'C - Tuna grahita ringan')}selected{/if}>C - Tuna grahita ringan</option>
                                        <option value="C1 - Tuna grahita sedang" {if ($profilsiswa.kebutuhan_khusus == 'C1 - Tuna grahita sedang')}selected{/if}>C1 - Tuna grahita sedang</option>
                                        <option value="D - Tuna daksa ringan" {if ($profilsiswa.kebutuhan_khusus == 'D - Tuna daksa ringan')}selected{/if}>D - Tuna daksa ringan</option>
                                        <option value="D1 - Tuna daksa sedang" {if ($profilsiswa.kebutuhan_khusus == 'D1 - Tuna daksa sedang')}selected{/if}>D1 - Tuna daksa sedang</option>
                                        <option value="E - Tuna laras" {if ($profilsiswa.kebutuhan_khusus == 'E - Tuna laras')}selected{/if}>E - Tuna laras</option>
                                        <option value="F - Tuna wicara" {if ($profilsiswa.kebutuhan_khusus == 'F - Tuna wicara')}selected{/if}>F - Tuna wicara</option>
                                        <option value="K - Kesulitan Belajar" {if ($profilsiswa.kebutuhan_khusus == 'K - Kesulitan Belajar')}selected{/if}>K - Kesulitan Belajar</option>
                                        <option value="P - Down Syndrome" {if ($profilsiswa.kebutuhan_khusus == 'P - Down Syndrome')}selected{/if}>P - Down Syndrome</option>
                                        <option value="Q - Autis" {if ($profilsiswa.kebutuhan_khusus == 'Q - Autis')}selected{/if}>Q - Autis</option>
                                    </select>
                                    </span>
                                    <span id="kebutuhan-khusus" tcg-input-tag='inklusi' tcg-input-false='hide' tcg-input-true='show' tcg-field='kebutuhan_khusus'>{$profilsiswa.kebutuhan_khusus}</span>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-striped dokumen-pendukung" style="margin-bottom: 0px !important;" tcg-visible-tag='kebutuhan_khusus'>
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
                                        <img id="dokumen-9" class="img-view-thumbnail" 
                                                src="{(empty($dokumen[9])) ? '' : $dokumen[9]['thumbnail_path']}" 
                                                img-path="{(empty($dokumen[9])) ? '' : $dokumen[9]['web_path']}" 
                                                img-id="{(empty($dokumen[9])) ? '' : $dokumen[9]['dokumen_id']}" 
                                                img-title="Surat Keterangan Berkebutuhan Khusus"
                                                style="display:none; "/>  
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="9" id="unggah-profil-9" hidden/>
                                        <label for="unggah-profil-9" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide' >Unggah</label>
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
                                    <select class="form-control input-default " id="inklusi-konfirmasi" name="inklusi-konfirmasi"
                                        tcg-edit-action='submit' tcg-submit-tag='inklusi'>
                                    <option value="0" {if ($profilsiswa.konfirmasi_inklusi==0 || $profilsiswa.konfirmasi_inklusi == 2)}selected{/if}>BELUM Benar</option>
                                    <option value="1" {if ($profilsiswa.konfirmasi_inklusi==1)}selected{/if}>SUDAH Benar</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="inklusi-error-row" class="box-red" style="display: none;">
                                <td colspan="1">
                                    <span style="color: red; " id="inklusi-error-msg"></span>
                                </td>
                            </tr>
                        </table>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item" id="surat-pernyataan" data-editor-id="{$peserta_didik_id}">
        <div class="accordion-header rounded-lg collapsed" id="surat-pernyataan-header" data-bs-toggle="collapse" data-bs-target="#surat-pernyataan-content" aria-controls="surat-pernyataan-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Surat Pernyataan Kebenaran Dokumen <span class='status'></span></span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="surat-pernyataan-content" class="collapse accordion__body" aria-labelledby="surat-pernyataan-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p>
                    Silahkan unduh Surat Pernyataan Kebenaran Dokumen di sini: <a href="{site_url('ppdb/siswa/suratpernyataan')}" class="btn btn-primary" target="_blank">Unduh</a>
                </p>
                </div></div>
                {if !($flag_upload_dokumen)}
                <div class="box-footer">
                <p>Setelah dicetak dan ditandatangani, silahkan bawa surat pernyataan tersebut ketika verifikasi data di sekolah tujuan.</p>
                </div>
                {else}
                <div class="box-footer" tcg-visible-tag="dikunci">
                    <div tcg-input-tag='surat-pernyataan' tcg-input-true='hide' tcg-input-false='show'>
                        Setelah dicetak dan ditandatangan, silahkan unggah kembali Surat Pernyataan tersebut di sini: 
                        <input type="file" class="upload-file" tcg-doc-id="21" id="unggah-profil-21-1" hidden/><label for="unggah-profil-21-1" class="editable btn btn-primary">Unggah</label>
                    </div>
                    <div tcg-input-tag='surat-pernyataan' tcg-input-true='show' tcg-input-false='hide'>
                        <a id="dokumen-21" href="{$pernyataan_file}" target="_blank"><b>Surat Pernyataan Kebenaran Dokumen</b></a> diunggah pada <b><span id="tanggal-dokumen-21">{$pernyataan_tanggal}</span></b>. 
                        <input type="file" class="upload-file" tcg-doc-id="21" id="unggah-profil-21-2" hidden/><label for="unggah-profil-21-2" class="editable btn btn-primary">Unggah Ulang</label>
                    </div>
                    <div class="box-red">
                        <span style="color: red; " id="dokumen-21-error-msg"></span>
                    </div>
                </div>
                {/if}
            </div>
        </div>
    </div>    
    <div class="accordion-item" id="surat-kesanggupan-akte" data-editor-id="{$peserta_didik_id}">
        <div class="accordion-header rounded-lg collapsed" id="surat-kesanggupan-akte-header" data-bs-toggle="collapse" data-bs-target="#surat-kesanggupan-akte-content" aria-controls="surat-kesanggupan-akte-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Surat Pernyataan Kesanggupan Melengkapi Akte</span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="surat-kesanggupan-akte-content" class="collapse accordion__body" aria-labelledby="surat-kesanggupan-akte-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p>Calon peserta didik yang pada saat pendaftaran belum memiliki akte kelahiran, dapat diganti dengan surat pernyataan kesanggupan dari orang tua/wali untuk melengkapi paling lambat pada semester 2 (dua) di tahun ajaran yang sama.
                <a href="{$site_url}ppdb/siswa/suratkesanggupan" class="btn btn-primary" target="_blank"><i class="glyphicon glyphicon-print"></i> Unduh Surat Pernyataan</a>
                </p>
                </div></div>
                <div class="box-footer">
                {if !($flag_upload_dokumen)}
                <p>Setelah dicetak dan ditandatangani, silahkan bawa surat pernyataan tersebut sebagai ganti Akte Kelahiran ketika verifikasi data di sekolah tujuan.</p>
                {else}
                <p>Setelah dicetak dan ditandatangani, silahkan unggah surat pernyataan tersebut sebagai ganti Akte Kelahiran di atas.</p>
                {/if}
                </div>
            </div>
        </div>
    </div>
    {if  ($jml_dokumen_tambahan > 0)}
    <div class="accordion-item" id="dokumen" data-editor-id="{$peserta_didik_id}">
        <div class="accordion-header rounded-lg collapsed" id="dokumen-header" data-bs-toggle="collapse" data-bs-target="#dokumen-content" aria-controls="dokumen-content" aria-expanded="true" role="button">
            <span class="accordion-header-icon"></span>
        <span class="accordion-header-text">Dokumen Pendukung Tambahan</span>
        <span class="accordion-header-indicator"></span>
        </div>
        <div id="dokumen-content" class="collapse accordion__body" aria-labelledby="dokumen-header" data-bs-parent="#profil-siswa" style="">
            <div class="accordion-body-text">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
                            {foreach $dokumen as $fields}
                            {if ($fields.tambahan != 1)}{continue}{/if}
                            <tr>
                                <td><span id="row-span-dokumen-{$fields.daftar_kelengkapan_id}"><i class="glyphicon glyphicon-edit"></i> </span><b>{$fields.nama}</b></td>
                                <td>:</td>
                                <td style="width: 50%;">
                                    {if !($flag_upload_dokumen)}
                                    Dicocokkan di sekolah tujuan
                                    {else}
                                        <img id="dokumen-{$fields.daftar_kelengkapan_id}" class="img-view-thumbnail" 
                                                src="{$fields['thumbnail_path']}" 
                                                img-path="{$fields['web_path']}" 
                                                img-title="{$fields['nama']}"
                                                img-id="{$fields['dokumen_id']}" 
                                                style="display:none; "/>  
                                        <button id="unggah-dokumen-{$fields.daftar_kelengkapan_id}" class="img-view-button" data-editor-field="dokumen_{$fields.daftar_kelengkapan_id}" data-editor-value="{$fields.dokumen_id}" >Unggah</button>
                                        <div id="msg-dokumen-{$fields.daftar_kelengkapan_id}" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                    {/if}
                                </td>
                            </tr>
                            {/foreach}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {/if}
    <div class="accordion-item" id="riwayat" data-editor-id="{$peserta_didik_id}">
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

