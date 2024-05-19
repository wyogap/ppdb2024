						
{literal}
<script id="detail-pendaftaran" type="text/template">

    <div class="accordion accordion-primary-solid" id="profil-siswa">
        <div class="accordion-item" id="asal-sekolah" data-editor-id="{{profil.peserta_didik_id}}">
            <div class="accordion-header rounded-lg collapsed" id="asal-sekolah-header" data-bs-toggle="collapse" data-bs-target="#asal-sekolah-content" aria-controls="asal-sekolah-content" aria-expanded="true" role="button">
                <span class="accordion-header-icon"></span>
            <span class="accordion-header-text">Asal Sekolah</span>
            <span class="accordion-header-indicator"></span>
            </div>
            <div id="asal-sekolah-content" class="collapse accordion__body" aria-labelledby="asal-sekolah-header" data-bs-parent="#profil-siswa" style="">
                <div class="accordion-body-text">
                    <div class="row">
                        {{#profil.sekolah_asal}}
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <p>(<b>{{{profil.npsn}}</b>) {{profil.sekolah}}</p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/{{profil.sekolah_id}}" target="_blank" class="btn btn-primary">Profil Sekolah Asal</a>
                        </div>
                        {{else}}
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <p>Tidak bersekolah sebelumnya.</p>
                        </div>
                        {{/if}}
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item" id="nomer-hp" data-editor-id="{{profil.peserta_didik_id}}">
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
                                    <td colspan="1">Nomor handphone aktif: <input class="form-control input-default" id="nomor_kontak" type="text" value="{{profil.nomor_kontak}}"></input> <button class="btn btn-primary" id="btn_nomor_kontak">Simpan</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item" id="profil" data-editor-id="{{profil.peserta_didik_id}}">
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
                                    <td style="width: 50%;">{{profil.nik}}</td>
                                </tr>
                                <tr>
                                    <td><b>NISN</b></td>
                                    <td>:</td>
                                    <td>{{profil.nisn}}</td>
                                </tr>
                                <tr>
                                    <td><b>Nomor Ujian</b></td>
                                    <td>:</td>
                                    <td>{{profil.nomor_ujian}}</td>
                                </tr>
                                <tr>
                                    <td><b>Nama</b></td>
                                    <td>:</td>
                                    <td>{{profil.nama}}</td>
                                </tr>
                                <tr>
                                    <td><b>Jenis Kelamin</b></td>
                                    <td>:</td>
                                    <td>{{profil.jenis_kelamin_label}}</td>
                                </tr>
                                <tr>
                                    <td><b>Tempat Lahir</b></td>
                                    <td>:</td>
                                    <td>{{profil.tempat_lahir}}</td>
                                </tr>
                                <tr>
                                    <td><b>Tanggal Lahir</b></td>
                                    <td>:</td>
                                    <td>{{profil.tanggal_lahir}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 45%;"><b>Nama Ibu Kandung</b></td>
                                    <td>:</td>
                                    <td style="width: 50%;">{{profil.nama_ibu_kandung}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <table class="table table-striped" style="margin-bottom: 0px !important;">
                                <tr>
                                    <td colspan="3"><b>Alamat (Sesuai Kartu Keluarga)</b></td>
                                </tr>
                                {{#profil.has_padukuhan}}
                                <tr>
                                    <td><b>Padukuhan</b></td>
                                    <td>:</td>
                                    <td>{{profil.padukuhan}}</td>
                                </tr>
                                {{/profil.has_padukuhan}}
                                <tr>
                                    <td><b>Desa/Kelurahan</b></td>
                                    <td>:</td>
                                    <td>{{profil.desa_kelurahan_label}}</td>
                                </tr>
                                <tr>
                                    <td><b>Kecamatan</b></td>
                                    <td>:</td>
                                    <td>{{profil.kecamatan}}</td>
                                </tr>
                                <tr>
                                    <td><b>Kabupaten/Kota</b></td>
                                    <td>:</td>
                                    <td>{{profil.kabupaten}}</td>
                                </tr>
                                <tr>
                                    <td><b>Provinsi</b></td>
                                    <td>:</td>
                                    <td>{{profil.provinsi}}</td>
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
                                        {{#flag_upload_dokumen}}
                                        Dicocokkan di sekolah tujuan
                                        {{else}}
                                            <img id="dokumen-5" class="img-view-thumbnail" 
                                                    src="" 
                                                    img-path="" 
                                                    img-id="" 
                                                    img-title="Akte Kelahiran"
                                                    style="display:none; "/> 
                                            <span>
                                            <input type="file" class="upload-file" tcg-doc-id="5" id="unggah-profil-5" hidden/>
                                            <label for="unggah-profil-5" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                            </span>
                                            <div id="msg-dokumen-5" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                        {{/flag_upload_dokumen}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:45%;"><b>Kartu Keluarga</b></td>
                                    <td>:</td>
                                    <td>
                                        {{#flag_upload_dokumen}}
                                        Dicocokkan di sekolah tujuan
                                        {{else}}
                                            <img id="dokumen-6" class="img-view-thumbnail" 
                                                src="" 
                                                img-path="" 
                                                img-id="" 
                                                img-title="Kartu Keluarga"
                                                    style="display:none; "/> 
                                            <span>
                                            <input type="file" class="upload-file" tcg-doc-id="6" id="unggah-profil-6" hidden/>
                                            <label for="unggah-profil-6" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                            </span>
                                            <div id="msg-dokumen-6" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                        {{/flag_upload_dokumen}}
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
                                    <option value="0">BELUM Benar</option>
                                    <option value="1">SUDAH Benar</option>
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
        <div class="accordion-item" id="lokasi" data-editor-id="{{profil.peserta_didik_id}}">
            <div class="accordion-header rounded-lg collapsed" id="lokasi-header" data-bs-toggle="collapse" data-bs-target="#lokasi-content" aria-controls="lokasi-content" aria-expanded="true" role="button">
                <span class="accordion-header-icon"></span>
            <span class="accordion-header-text">Lokasi Rumah <span class='status'></span></span>
            <span class="accordion-header-indicator"></span>
            </div>
            <div id="lokasi-content" class="collapse accordion__body" aria-labelledby="lokasi-header" data-bs-parent="#profil-siswa" style="">
                <div class="accordion-body-text">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div id="peta" style="width: 100%; height: 400px;"></div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
                                <tr>
                                    <td><span id="row-span-lintang"><i class="glyphicon glyphicon-edit"></i> </span><b>Lintang</b></td>
                                    <td>:</td>
                                    <td id="row-lintang-value" style="width: 50%;">{{profil.lintang}}</td>
                                </tr>
                                <tr>
                                    <td><span id="row-span-bujur"><i class="glyphicon glyphicon-edit"></i> </span><b>Bujur</b></td>
                                    <td>:</td>
                                    <td id="row-bujur-value">{{profil.bujur}}</td>
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
                                        {{#flag_upload_dokumen}}
                                        Dicocokkan di sekolah tujuan
                                        {{else}}
                                            <img id="dokumen-19" class="img-view-thumbnail" 
                                                    src="" 
                                                    img-path="" 
                                                    img-id="" 
                                                    img-title="Surat Keterangan Domisili"
                                                    style="display:none; "/>  
                                            <span>
                                            <input type="file" class="upload-file" tcg-doc-id="19" id="unggah-profil-19" hidden/>
                                            <label for="unggah-profil-19" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                            </span>
                                            <div id="msg-dokumen-19" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                        {{/flag_upload_dokumen}}
                                    </td>
                                </tr>
                                <tr>
                                    <td id="row-dokumen-kelas6" style="width: 45%;"><b>Rapor Kelas 6</b></td>
                                    <td>:</td>
                                    <td style="width: 50%;">
                                        {{#flag_upload_dokumen}}
                                        Dicocokkan di sekolah tujuan
                                        {{else}}
                                        <img id="dokumen-26" class="img-view-thumbnail" 
                                                src="" 
                                                img-path="" 
                                                img-id="" 
                                                img-title="Rapor Kelas 6"
                                                style="display:none; "/>  
                                        <span>
                                        <input type="file" class="upload-file" tcg-doc-id="26" id="unggah-profil-26" hidden/>
                                        <label for="unggah-profil-26" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                        </span>
                                        <div id="msg-dokumen-26" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                        {{/flag_upload_dokumen}}
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
                                        <option value="0">BELUM Benar</option>
                                        <option value="1">SUDAH Benar</option>
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
        <div class="accordion-item" id="nilai" data-editor-id="{{profil.peserta_didik_id}}">
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
                                        <span><input class="form-control" id="nilai-rapor-input" type="number" tcg-min=0 tcg-max=100 onkeyup=impose_min_max(this) value="{{profil.nilai_semester}}" 
                                            tcg-input-tag='nilai' tcg-input-true='hide' tcg-input-false='show' tcg-field='nilai_semester' style="display: none;"></input></span>
                                        <span id="nilai-rapor" tcg-input-tag='nilai' tcg-input-true='show' tcg-input-false='hide' tcg-field='nilai_semester'>{{profil.nilai_semester}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 45%;"><b>Nilai Rata-rata Ujian Sekolah</b></td>
                                    <td>:</td>
                                    <td style="width: 50%;">
                                        <span><input class="form-control" id="nilai-lulus-input" type="number" tcg-min=0 tcg-max=100 onkeyup=impose_min_max(this) value="{{profil.nilai_lulus}}"
                                            tcg-input-tag='nilai' tcg-input-true='hide' tcg-input-false='show' tcg-field='nilai_lulus' style="display: none;"></input></span>
                                        <span id="nilai-lulus" tcg-input-tag='nilai' tcg-input-true='show' tcg-input-false='hide' tcg-field='nilai_lulus'>{{profil.nilai_lulus}}</span>
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
                                        <span><input class="form-control" id="nilai-bin-input" type="number" tcg-min=0 tcg-max=100 onkeyup=impose_min_max(this) value="{{profil.nilai_bin}}"
                                            tcg-input-tag='nilai' tcg-input-true='hide' tcg-input-false='show' tcg-field='nilai_bin' style="display: none;"></input></span>
                                        <span id="nilai-bin" tcg-input-true='show' tcg-input-false='hide' tcg-field='nilai_bin'>{{profil.nilai_bin}}</span>
                                    </td>
                                </tr>
                                <tr id="row-un-mat" tcg-visible-tag='nilai-un' tcg-field='nilai_mat'>
                                    <td><b>Matematika</b></td>
                                    <td>:</td>
                                    <td style="width: 50%;">
                                        <span><input class="form-control" id="nilai-mat-input" type="number" tcg-min=0 tcg-max=100 onkeyup=impose_min_max(this) value="{{profil.nilai_mat}}"
                                            tcg-input-tag='nilai' tcg-input-true='hide' tcg-input-false='show' tcg-field='nilai_mat' style="display: none;"></input></span>
                                        <span id="nilai-mat" tcg-input-true='show' tcg-input-false='hide' tcg-field='nilai_mat'>{{profil.nilai_mat}}</span>
                                    </td>
                                </tr>
                                <tr id="row-un-ipa" tcg-visible-tag='nilai-un' tcg-field='nilai_ipa'>
                                    <td><b>IPA</b></td>
                                    <td>:</td>
                                    <td style="width: 50%;">
                                        <span><input class="form-control" id="nilai-ipa-input" type="number" tcg-min=0 tcg-max=100 onkeyup=impose_min_max(this) value="{{profil.nilai_ipa}}"
                                            tcg-input-tag='nilai' tcg-input-true='hide' tcg-input-false='show' tcg-field='nilai_ipa' style="display: none;"></input></span>
                                        <span id="nilai-ipa" tcg-input-true='show' tcg-input-false='hide' tcg-input-action='hide' tcg-field='nilai_ipa'>{{profil.nilai_ipa}}</span>
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
                                        {{#flag_upload_dokumen}}
                                        Dicocokkan di sekolah tujuan
                                        {{else}}
                                            <img id="dokumen-2" class="img-view-thumbnail" 
                                                    src="" 
                                                    img-path="" 
                                                    img-id="" 
                                                    img-title="Ijazah / Surat Keterangan Lulus"
                                                    style="display:none; "/>  
                                            <span>
                                            <input type="file" class="upload-file" tcg-doc-id="2" id="unggah-profil-2" hidden/>
                                            <label for="unggah-profil-2" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                            </span>
                                            <div id="msg-dokumen-2" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                        {{/flag_upload_dokumen}}
                                    </td>
                                </tr>
                                <tr id="row-dokumen-un" tcg-visible-tag='nilai-un'>
                                    <td><span id="row-span-dokumen-skhun"><b>Hasil Ujian Nasional</b></td>
                                    <td>:</td>
                                    <td>
                                        {{#flag_upload_dokumen}}
                                        Dicocokkan di sekolah tujuan
                                        {{else}}
                                            <img id="dokumen-3" class="img-view-thumbnail" 
                                                    src="" 
                                                    img-path="" 
                                                    img-id="" 
                                                    img-title="Surat Keterangan Hasil Ujian Nasional"
                                                    style="display:none; "/>  
                                            <span>
                                            <input type="file" class="upload-file" tcg-doc-id="3" id="unggah-profil-3" hidden/>
                                            <label for="unggah-profil-3" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                            </span>
                                            <div id="msg-dokumen-3" class="box-red" style="padding-left: 5px; padding-right: 5px; display: none;"></div>
                                        {{/flag_upload_dokumen}}
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
                                        <option value="0">BELUM Benar</option>
                                        <option value="1">SUDAH Benar</option>
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
        <div class="accordion-item" id="prestasi" data-editor-id="{{profil.peserta_didik_id}}">
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
                                            tcg-edit-action='toggle' tcg-toggle-tag='prestasi'>
                                        <option value="0">Tidak</option>
                                        <option value="1">YA</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="tbl-prestasi-kontainer" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px; margin-bottom: 20px;" 
                                tcg-visible-tag='prestasi'>
                            <table id="tbl-prestasi" class="display" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center" data-priority="1">#</th>
                                        <th class="text-center" data-priority="2">Prestasi</th>
                                        <th class="text-center" style="width: 30%;" data-priority="4">Uraian</th>
                                        <th class="text-center" data-priority="3">Dokumen Pendukung</th>
                                        <th class="text-center" data-priority="5">Catatan</th>
                                    </tr>
                                </thead>
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
                                        <option value="0">BELUM Benar</option>
                                        <option value="1">SUDAH Benar</option>
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
        <div class="accordion-item" id="afirmasi" data-editor-id="{{profil.peserta_didik_id}}">
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
                                        <span><input class="form-control" id="nomor-kip-input" type="text" value="{{profil.nomor_kip}}" 
                                            tcg-input-tag='afirmasi' tcg-input-false='show' tcg-input-true='hide' tcg-field='nomor_kip' style="display: none;"></input></span>
                                        <span id="nomor-kip" tcg-input-tag='afirmasi' tcg-input-false='hide' tcg-input-true='show' tcg-field='nomor_kip'>{{profil.nomor_kip}}</span>
                                    </td>
                                </tr>
                                <tr tcg-visible-tag='kip'>
                                    <td colspan="3"></td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <b>Masuk di dalam Basis Data Terpadu dalam kategori Keluarga Miskin? </b>
                                        <select class="form-control input-default " id="bdt" name="bdt" 
                                            tcg-input-tag='afirmasi' tcg-input-true='disable' tcg-input-false='enable' tcg-field='masuk_bdt'
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
                                        <span><input class="form-control" id="nomor-bdt-input" type="text" value="{{profil.nomor_bdt}}"
                                            tcg-input-tag='afirmasi' tcg-input-false='show' tcg-input-true='hide' tcg-field='nomor_bdt' style="display: none;"></input></span>
                                        <span id="nomor-bdt" tcg-input-tag='afirmasi' tcg-input-false='hide' tcg-input-true='show' tcg-field='nomor_bdt'>{{profil.nomor_bdt}}</span>
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
                                        {{#flag_upload_dokumen}}
                                        Dicocokkan di sekolah tujuan
                                        {{else}}
                                            <img id="dokumen-16" class="img-view-thumbnail" 
                                                    src="" 
                                                    img-path="" 
                                                    img-id="" 
                                                    img-title="Kartu Indonesia Pintar"
                                                    style="display:none; "/>  
                                            <span>
                                            <input type="file" class="upload-file" tcg-doc-id="16" id="unggah-profil-16" hidden/>
                                            <label for="unggah-profil-16" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                            </span>
                                            <div id="msg-dokumen-16" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                        {{/flag_upload_dokumen}}
                                    </td>
                                </tr>
                                <tr id="row-dokumen-bdt" tcg-visible-tag='bdt'>
                                    <td style="width: 45%;"><b>Kartu PKH / Kartu KJS / Surat Keterangan masuk BDT dari Desa/Kelurahan</b></td>
                                    <td>:</td>
                                    <td>
                                        {{#flag_upload_dokumen}}
                                        Dicocokkan di sekolah tujuan
                                        {{else}}
                                            <img id="dokumen-20" class="img-view-thumbnail" 
                                                    src="" 
                                                    img-path="" 
                                                    img-id="" 
                                                    img-title="Surat Keterangan Basis Data Terpadu"
                                                    style="display:none; "/>  
                                            <span>
                                            <input type="file" class="upload-file" tcg-doc-id="20" id="unggah-profil-20" hidden/>
                                            <label for="unggah-profil-20" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide'>Unggah</label>
                                            </span>
                                            <div id="msg-dokumen-20" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                        {{/flag_upload_dokumen}}
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
                                        <option value="0">BELUM Benar</option>
                                        <option value="1">SUDAH Benar</option>
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
        <div class="accordion-item" id="inklusi" data-editor-id="{{profil.peserta_didik_id}}">
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
                                        <span>
                                        <select class="form-control" id="kebutuhan-khusus-input" name="inklusi" 
                                                tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide' tcg-field='kebutuhan_khusus' style="display: none;">
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
                                        </span>
                                        <span id="kebutuhan-khusus" tcg-input-tag='inklusi' tcg-input-false='hide' tcg-input-true='show' tcg-field='kebutuhan_khusus'>{{profil.kebutuhan_khusus}}</span>
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
                                        {{#flag_upload_dokumen}}
                                        Dicocokkan di sekolah tujuan
                                        {{else}}
                                            <img id="dokumen-9" class="img-view-thumbnail" 
                                                    src="" 
                                                    img-path="" 
                                                    img-id="" 
                                                    img-title="Surat Keterangan Berkebutuhan Khusus"
                                                    style="display:none; "/>  
                                            <span>
                                            <input type="file" class="upload-file" tcg-doc-id="9" id="unggah-profil-9" hidden/>
                                            <label for="unggah-profil-9" class="btn btn-primary" tcg-input-tag='inklusi' tcg-input-false='show' tcg-input-true='hide' >Unggah</label>
                                            </span>
                                            <div id="msg-dokumen-9" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                        {{/flag_upload_dokumen}}
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
                                        <option value="0">BELUM Benar</option>
                                        <option value="1">SUDAH Benar</option>
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
        <div class="accordion-item" id="surat-pernyataan" data-editor-id="{{profil.peserta_didik_id}}">
            <div class="accordion-header rounded-lg collapsed" id="surat-pernyataan-header" data-bs-toggle="collapse" data-bs-target="#surat-pernyataan-content" aria-controls="surat-pernyataan-content" aria-expanded="true" role="button">
                <span class="accordion-header-icon"></span>
            <span class="accordion-header-text">Surat Pernyataan Kebenaran Dokumen <span class='status'></span></span>
            <span class="accordion-header-indicator"></span>
            </div>
            <div id="surat-pernyataan-content" class="collapse accordion__body" aria-labelledby="surat-pernyataan-header" data-bs-parent="#profil-siswa" style="">
                <div class="accordion-body-text">
                    <div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p>
                        Silahkan unduh Surat Pernyataan Kebenaran Dokumen di sini: <a href="{{url_unduh_surat_pernyataan}}" class="btn btn-primary" target="_blank">Unduh</a>
                    </p>
                    </div></div>
                    {{#flag_upload_dokumen}}
                    <div class="box-footer">
                    <p>Setelah dicetak dan ditandatangani, silahkan bawa surat pernyataan tersebut ketika verifikasi data di sekolah tujuan.</p>
                    </div>
                    {{else}}
                    <div class="box-footer" tcg-visible-tag="dikunci">
                        <div tcg-input-tag='surat-pernyataan' tcg-input-true='hide' tcg-input-false='show'>
                            Setelah dicetak dan ditandatangan, silahkan unggah kembali Surat Pernyataan tersebut di sini: 
                            <input type="file" class="upload-file" tcg-doc-id="21" id="unggah-profil-21-1" hidden/><label for="unggah-profil-21-1" class="editable btn btn-primary">Unggah</label>
                        </div>
                        <div tcg-input-tag='surat-pernyataan' tcg-input-true='show' tcg-input-false='hide'>
                            <a id="dokumen-21" href="{{profil.pernyataan_file}}" target="_blank"><b>Surat Pernyataan Kebenaran Dokumen</b></a> diunggah pada <b><span id="tanggal-dokumen-21">{{profil.pernyataan_tanggal}}</span></b>. 
                            <input type="file" class="upload-file" tcg-doc-id="21" id="unggah-profil-21-2" hidden/><label for="unggah-profil-21-2" class="editable btn btn-primary">Unggah Ulang</label>
                        </div>
                        <div class="box-red">
                            <span style="color: red; " id="dokumen-21-error-msg"></span>
                        </div>
                    </div>
                    {{/flag_upload_dokumen}}
                </div>
            </div>
        </div>    
        <div class="accordion-item" id="surat-kesanggupan-akte" data-editor-id="{{profil.peserta_didik_id}}">
            <div class="accordion-header rounded-lg collapsed" id="surat-kesanggupan-akte-header" data-bs-toggle="collapse" data-bs-target="#surat-kesanggupan-akte-content" aria-controls="surat-kesanggupan-akte-content" aria-expanded="true" role="button">
                <span class="accordion-header-icon"></span>
            <span class="accordion-header-text">Surat Pernyataan Kesanggupan Melengkapi Akte</span>
            <span class="accordion-header-indicator"></span>
            </div>
            <div id="surat-kesanggupan-akte-content" class="collapse accordion__body" aria-labelledby="surat-kesanggupan-akte-header" data-bs-parent="#profil-siswa" style="">
                <div class="accordion-body-text">
                    <div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p>Calon peserta didik yang pada saat pendaftaran belum memiliki akte kelahiran, dapat diganti dengan surat pernyataan kesanggupan dari orang tua/wali untuk melengkapi paling lambat pada semester 2 (dua) di tahun ajaran yang sama.
                    <a href="{{unduh_surat_kesanggupan}}" class="btn btn-primary" target="_blank"><i class="glyphicon glyphicon-print"></i> Unduh Surat Pernyataan</a>
                    </p>
                    </div></div>
                    <div class="box-footer">
                    {{#flag_upload_dokumen}}
                    <p>Setelah dicetak dan ditandatangani, silahkan bawa surat pernyataan tersebut sebagai ganti Akte Kelahiran ketika verifikasi data di sekolah tujuan.</p>
                    {{else}}
                    <p>Setelah dicetak dan ditandatangani, silahkan unggah surat pernyataan tersebut sebagai ganti Akte Kelahiran di atas.</p>
                    {{/flag_upload_dokumen}}
                    </div>
                </div>
            </div>
        </div>
        {{#has_dokumen_tambahan}}
        <div class="accordion-item" id="dokumen" data-editor-id="{{profil.peserta_didik_id}}">
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
                                {{#dokumen_tambahan}}
                                <tr>
                                    <td><span id="row-span-dokumen-{{daftar_kelengkapan_id}}"><b>{{nama}}</b></td>
                                    <td>:</td>
                                    <td style="width: 50%;">
                                        {{#flag_upload_dokumen}}
                                        Dicocokkan di sekolah tujuan
                                        {{else}}
                                            <img id="dokumen-{{daftar_kelengkapan_id}}" class="img-view-thumbnail" 
                                                    src="{{thumbnail_path}}" 
                                                    img-path="{{web_path}}" 
                                                    img-title="{{nama}}"
                                                    img-id="{{dokumen_id]}" 
                                                    style="display:none; "/>  
                                            <button id="unggah-dokumen-{{daftar_kelengkapan_id}}" class="img-view-button" data-editor-field="dokumen_{{daftar_kelengkapan_id}}" data-editor-value="{{dokumen_id}" >Unggah</button>
                                            <div id="msg-dokumen-{{daftar_kelengkapan_id}" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
                                        {{/flag_upload_dokumen}}
                                    </td>
                                </tr>
                                {{/dokumen_tambahan}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{/has_dokumen_tambahan}}
        {{#has_berkas_fisik}}
        <div class="accordion-item" id="berkas-fisik" data-editor-id="{{profil.peserta_didik_id}}">
            <div class="accordion-header rounded-lg collapsed" id="berkas-fisik-header" data-bs-toggle="collapse" data-bs-target="#berkas-fisik-content" aria-controls="berkas-fisik-content" aria-expanded="true" role="button">
                <span class="accordion-header-icon"></span>
            <span class="accordion-header-text">Berkas Fisik</span>
            <span class="accordion-header-indicator"></span>
            </div>
            <div id="berkas-fisik-content" class="collapse accordion__body" aria-labelledby="berkas-fisik-header" data-bs-parent="#profil-siswa" style="">
                <div class="accordion-body-text">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <table id="tberkas" class="table table-striped" style="margin-bottom:0px !important;">
                                {{#berkas_fisik}}
                                <input type="hidden" id="orig_berkas_{{dokumen_id}}" name="orig_berkas_{{dokumen_id}}" value="{{berkas_fisik}}">
                                <tr >
                                    <td>
                                        <div style="display: block; margin-bottom: 15px;"><b>{{nama}}</b></div>
                                    </td>
                                    <td style="width: 50%">
                                        {{status_berkas_fisik}}
                                            <div id="catatan_berkas_{{dokumen_id}}">Diterima oleh {{penerima_berkas}} di {{sekolah}} tanggal {{tanggal_berkas}}</div>
                                        {{else}}
                                            <div id="catatan_berkas_{{dokumen_id}}">Belum diserahkan ke sekolah tujuan</div>
                                        {{/status_berkas_fisik}}
                                    </td>
                                </tr>
                                {{/berkas_fisik}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{/has_berkas_fisik}}
        <div class="accordion-item" id="riwayat" data-editor-id="{{profil.peserta_didik_id}}">
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

</script>
{/literal}

<script>
    var tags = ['profil', 'lokasi', 'nilai', 'prestasi', 'afirmasi', 'inklusi', 'surat-pernyataan'];
    var flags = ['nilai-un', 'prestasi', 'kip', 'bdt', 'inklusi'];

    var konfirmasi = {{konfirmasiprofil|json_encode};
    var verifikasi = {{verifikasiprofil|json_encode};
    var profilflag = {{profilflag|json_encode};
    var profildikunci = {{profildikunci};
    var kelengkapan_data = {{kelengkapan_data};
    var tutup_akses = {{profil.tutup_akses};

    function update_profile_layout() {
        //dikunci: editability
        elements = $("[tcg-visible-tag='dikunci']");
        if (profildikunci) {
            elements.hide();
            $("#profil-dikunci-notif").show();
        }
        else {
            elements.show();
            $("#profil-dikunci-notif").hide();
        }

        //flag: visibility
        flags.forEach(function(key) {
            value = profilflag[key];
            if (value) { $("[tcg-visible-tag='" +key+ "']").show(); }
            else { $("[tcg-visible-tag='" +key+ "']").hide(); }
        });

        //special case: afirmasi
        elements = $("[tcg-visible-tag='afirmasi']");
        if (!profilflag['kip'] && !profilflag['bdt']) {
            elements.hide();
        }
        else {
            elements.show();
        }

        //konfirmasi: editability
        tags.forEach(function(key) {
            value = verifikasi[key];
            if (value == 2) {
                konfirmasi[key] = 0;
            }

            value = konfirmasi[key];
            elements = $("[tcg-input-tag='" +key+ "']");
            elements.each(function(idx) {
                el = $(this);
                if (value) {
                    action = el.attr('tcg-input-true');
                }
                else {
                    action = el.attr('tcg-input-false');
                }
                if (action == 'show') el.show();
                else if (action == 'hide') el.hide();
                else if (action == 'enable') el.attr("disabled",false);
                else if (action == 'disable') el.attr("disabled",true);
            });

            let card = $("#" +key);
            if (value) {
                card.removeClass("status-danger");
                card.find(".accordion-header-text .status").html('');
            }
            else {
                card.addClass("status-danger");
                card.find(".accordion-header-text .status").html('*Belum Benar*');
            }

        });
        
        //special case: nomor-hp
        let flagval = konfirmasi['nomer-hp'];
        let card = $("#nomer-hp");
        if (flagval) {
            card.removeClass("status-danger");
            card.find(".accordion-header-text .status").html('');
        }
        else {
            card.addClass("status-danger");
            card.find(".accordion-header-text .status").html('*Belum Diisi*');
        }

        //special case: surat pernyataan
        flagval = konfirmasi['surat-pernyataan'];
        card = $("#surat-pernyataan");
        {if !($flag_upload_dokumen)}
        {* no upload -> always OK *}
        card.removeClass("status-danger");
        card.find(".accordion-header-text .status").html('');
        {else}
        if (flagval) {
            card.removeClass("status-danger");
            card.find(".accordion-header-text .status").html('');
        }
        else {
            card.addClass("status-danger");
            card.find(".accordion-header-text .status").html('*Belum Unggah Dokumen*');
        }
        {/if}

        //special case
        flagval = konfirmasi['prestasi'];
        if (flagval) {
            dtprestasi.buttons( 0, null ).container().hide();
        }
        else {
            dtprestasi.buttons( 0, null ).container().show();
        }

    }       

    function impose_min_max(el){
        if(el.value != ""){
            val = parseFloat(el.value);
            if (isNaN(val)) val = 0;

            min = parseFloat(el.getAttribute('tcg-min'));
            max = parseFloat(el.getAttribute('tcg-max'));
            if(!isNaN(min) && val < min){
                el.value = min;
            }
            else if(!isNaN(max) && val > max){
                el.value = max;
            }
        }
    }

</script>


