<div class="alert alert-secondary" role='alert'>
    Periode daftar ulang adalah dari tanggal <b><span class='tgl-indo'>{$waktudaftarulang.tanggal_mulai_aktif}</span></b> sampai dengan tanggal <b><span class='tgl-indo'>{$waktudaftarulang.tanggal_selesai_aktif}</span></b>.      
</div>

{if $pendaftaranditerima && ((!$cek_waktupendaftaran && !$cek_waktusosialisasi) || $cek_waktudaftarulang)}
<div class="row">
    <div class="col-12"> 
        <div class="card box-default box-solid">
            <div class="card-body">
                <p><i class="icon glyphicon glyphicon-info-sign"></i>Selamat! Anda diterima di <b>{$pendaftaranditerima.label_masuk_pilihan}</b> di <b>{$pendaftaranditerima.sekolah}</b> melalui jalur <b>{$pendaftaranditerima.jalur}</b>.</p>
                Silahkan melakukan daftar ulang di sekolah tujuan dengan membawa dokumen-dokumen pendukung di bawah.
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="box-title">Dokumen Pendukung Yang Dibawa</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
                            <tbody>
                                {foreach $dokumen as $row}
                                {if $row.daftar_kelengkapan_id==$smarty.const.DOCID_PRESTASI && !$profilsiswa.punya_prestasi}{continue}{/if}
                                {if $row.daftar_kelengkapan_id==$smarty.const.DOCID_HASIL_UN && !$profilsiswa.punya_nilai_un}{continue}{/if}
                                {if $row.daftar_kelengkapan_id==$smarty.const.DOCID_KIP && !$profilsiswa.punya_kip}{continue}{/if}
                                {if $row.daftar_kelengkapan_id==$smarty.const.DOCID_SUKET_BDT && !$profilsiswa.masuk_bdt}{continue}{/if}
                                {if $row.daftar_kelengkapan_id==$smarty.const.DOCID_SUKET_INKLUSI && $profilsiswa.kebutuhan_khusus=='Tidak ada'}{continue}{/if}

                                    {assign var='dok' value=$row}
                                    <tr>
                                        <td>
                                            <div style="display: block;"><b>{$dok.nama}</b></div>
                                            {if $flag_upload_dokumen}
                                            {if !(empty($dok.thumbnail_path))}
                                            <img id="dokumen-{$dok.dokumen_id}" class="img-view-thumbnail" 
                                                src="{$dok.thumbnail_path}"
                                                img-path="{$dok.web_path}" 
                                                img-title="{$dok.nama}"/> 
                                            {/if}
                                            {if !(empty($dok.web_path))}
                                            <a href="{$dok.web_path}" target="_blank" class="btn btn-primary" style="margin-left: 10px;">
                                                Unduh
                                            </a>
                                            {/if}
                                            {/if}
                                        </td>
                                        <td style="width: 50%;">
                                            {if ($dok.verifikasi==3)}Tidak Ada
                                            {else if ($dok.berkas_fisik==1)}Asli
                                            {else if ($dok.berkas_fisik==2)}Fotokopi Dilegalisir (Dokumen asli dibawa untuk dicocokkan)</i>
                                            {else}Fotokopi (Dokumen asli dibawa untuk dicocokkan)
                                            {/if}
                                        </td>
                                    </tr>

                                {/foreach}
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}

