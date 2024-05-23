<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="glyphicon glyphicon-th-list"></i>
                <h3 class="box-title text-info"><b>Daftar Pendaftaran</b></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p><b>"Daftar Pendaftaran"</b> akan muncul jika sudah melakukan pendaftaran</b>.</p>
                    </div>
                    {foreach $daftarpendaftaran as $row}
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <p>(<b>{$row.npsn}</b>) <b>{$row.sekolah}</b>)</p>
                                        
                                        {if ($cek_waktupendaftaran == 1 || $cek_waktusosialisasi == 1) && (!$tutup_akses|default: FALSE)}

                                            <div style="min-height: 38px;">
                                            {if $batasanperubahan.ubah_pilihan > 0}
                                            <a href="{base_url()}siswa/pendaftaran/ubahjenispilihan?pendaftaran_id={$row.pendaftaran_id}" class="btn btn-warning 
                                            {if ($batasansiswa.ubah_pilihan>=$batasanperubahan.ubah_pilihan&&$row.jenis_pilihan!=0)||$row.pendaftaran!=1}disabled{/if}" style="margin-top: 4px;">
                                                <i class="glyphicon glyphicon-edit"></i> Ubah Pilihan
                                            </a>
                                            {/if}

                                            {if $batasanperubahan.ubah_jalur > 0}
                                            <a href="{base_url()}index.php/siswa/pendaftaran/ubahjalur?pendaftaran_id={$row.pendaftaran_id}" class="btn btn-warning 
                                            {if ($batasansiswa.ubah_jalur>=$batasanperubahan.ubah_jalur)||$row.pendaftaran!=1}disabled{/if}" style="margin-top: 4px;">
                                                <i class="glyphicon glyphicon-sort"></i> Ubah Jalur
                                            </a>
                                            {/if}

                                            {if ($row.jenis_pilihan!=0)}
                                                {if $batasanperubahan.ubah_sekolah > 0}
                                                <a href="{base_url()}index.php/siswa/pendaftaran/ubahsekolah?pendaftaran_id={$row.pendaftaran_id}&penerapan_id={$row.penerapan_id}" class="btn bg-olive 
                                                {if $batasansiswa.ubah_sekolah>=$batasanperubahan.ubah_sekolah||$row.pendaftaran!=1}disabled{/if}" style="margin-top: 4px;">
                                                    <i class="glyphicon glyphicon-home"></i> Ubah Sekolah
                                                </a>
                                                {/if}

                                                {if $batasanperubahan.hapus_pendaftaran > 0}
                                                <a href="{base_url()}index.php/siswa/pendaftaran/hapus?pendaftaran_id={$row.pendaftaran_id}" class="btn btn-danger 
                                                {if $batasansiswa.hapus_pendaftaran>=$batasanperubahan.hapus_pendaftaran||$row.pendaftaran!=1}disabled{/if}" style="margin-top: 4px;">
                                                    <i class="glyphicon glyphicon-remove"></i> Hapus Pendaftaran
                                                </a>
                                                {/if}
                                            {/if}
                                            </div>

                                        {/if}
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped">
                                        {if ($row.jenis_pilihan==0)}
                                        <tr>
                                            <td colspan="3" class="text-danger">Jenis pilihan belum diperbaharui, silahkan lakukan perbaikan melalui menu <b><i class="glyphicon glyphicon-edit"></i> Ubah Pilihan</b> diatas (<i class="glyphicon glyphicon-arrow-up"></i>) ini</td>
                                        </tr>
                                        {/if}
                                        <tr {if ($row.jenis_pilihan==0)}class="bg-red"{/if}>
                                            <td><b>Jenis Pilihan</b></td>
                                            <td>:</td>
                                            <td>{if ($row.jenis_pilihan!=0)}{$row.label_jenis_pilihan}{else}Belum diperbaharui{/if}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Jalur</b></td>
                                            <td>:</td>
                                            <td>{$row.jalur}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Waktu Pendaftaran</b></td>
                                            <td>:</td>
                                            <td>{$row.created_on}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Nomor Pendaftaran</b></td>
                                            <td>:</td>
                                            <td>{$row.nomor_pendaftaran}</td>
                                        </tr>
                                        {if ($cek_waktupendaftaran == 1)}
                                            <tr>
                                                <td><b>Peringkat</b></td>
                                                <td>:</td>
                                                <td>
                                                {if ($row.peringkat==0)}Belum Ada
                                                {elseif ($row.peringkat==9999 && $row.status_penerimaan==4)}Tidak Dihitung
                                                {elseif ($row.peringkat==9999 && $row.status_penerimaan==2)}Tidak Dihitung
                                                {elseif ($row.peringkat==9999)}Belum Ada
                                                {elseif ($row.peringkat==-1)}Tidak Ada
                                                {else}{$row.peringkat_final}
                                                {/if}
                                                <span class="pull-right"><a href="{base_url()}index.php/home/peringkat?sekolah_id={$row.sekolah_id}&bentuk={$row.bentuk}" target="_blank"><i class="glyphicon glyphicon-search"></i> Lihat Peringkat</a></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Status Pendaftaran</b></td>
                                                <td>:</td>
                                                <td class="{if ($row.status_penerimaan==0)}bg-gray
                                                            {elseif ($row.status_penerimaan==1)}bg-green
                                                            {elseif ($row.status_penerimaan==2)}bg-red
                                                            {elseif ($row.status_penerimaan==3)}bg-yellow
                                                            {elseif ($row.status_penerimaan==4)}bg-gray
                                                            {else}bg-gray
                                                            {/if}">
                                                    {if ($row.status_penerimaan==0)}<i class="glyphicon glyphicon-search"></i> Dalam Proses Seleksi
                                                    {elseif ($row.status_penerimaan==1)}<i class="glyphicon glyphicon-check"></i> Masuk Kuota
                                                    {elseif ($row.status_penerimaan==2)}<i class="glyphicon glyphicon-info-sign"></i> Tidak Masuk Kuota
                                                    {elseif ($row.status_penerimaan==3)}<i class="glyphicon glyphicon-check"></i> Daftar Tunggu
                                                    {elseif ($row.status_penerimaan==4)}<i class="glyphicon glyphicon-info-sign"></i> Masuk Kuota {$row.label_masuk_pilihan}
                                                    {else}<i class="glyphicon glyphicon-search"></i> Dalam Proses Seleksi
                                                    {/if}
                                                </td>
                                            </tr>
                                        {else}
                                            <!-- Bukan waktu pendaftaran -->
                                            <tr>
                                                <td><b>Peringkat</b></td>
                                                <td>:</td>
                                                <td>
                                                {if ($row.status_penerimaan_final==0)}Tidak Dihitung
                                                {elseif ($row.status_penerimaan_final==1)}{$row.peringkat_final}
                                                {elseif ($row.status_penerimaan_final==2)}Tidak Dihitung
                                                {elseif ($row.status_penerimaan_final==3)}{$row.peringkat_final}
                                                {elseif ($row.status_penerimaan_final==4)}Tidak Dihitung
                                                {else}Tidak Dihitung
                                                {/if}
                                                <span class="pull-right"><a href="{base_url()}home/peringkat?sekolah_id={$row.sekolah_id}&bentuk={$row.bentuk}" target="_blank"><i class="glyphicon glyphicon-search"></i> Lihat Peringkat</a></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Status Pendaftaran</b></td>
                                                <td>:</td>
                                                <td class="{if ($row.status_penerimaan_final==0)}bg-gray
                                                            {elseif ($row.status_penerimaan_final==1)}bg-green
                                                            {elseif ($row.status_penerimaan_final==2 && $row.masuk_jenis_pilihan!=0 && $row.masuk_jenis_pilihan!=$row.jenis_pilihan)}bg-gray
                                                            {elseif ($row.status_penerimaan_final==2)}bg-red
                                                            {elseif ($row.status_penerimaan_final==3)}bg-green
                                                            {elseif ($row.status_penerimaan_final==4)}bg-gray
                                                            {else}bg-gray
                                                            {/if}">
                                                    {if ($row.kelengkapan_berkas==2)}<i class="glyphicon glyphicon-search"></i> Berkas Tidak Lengkap
                                                    {elseif ($row.status_penerimaan_final==1)}<i class="glyphicon glyphicon-check"></i> Masuk Kuota
                                                    {elseif ($row.status_penerimaan_final==2 && $row.masuk_jenis_pilihan!=0 && $row.masuk_jenis_pilihan!=$row.jenis_pilihan)}<i class="glyphicon glyphicon-info-sign"></i> Masuk Kuota {$row.label_masuk_pilihan}
                                                    {elseif ($row.status_penerimaan==2)}<i class="glyphicon glyphicon-info-sign"></i> Tidak Masuk Kuota
                                                    {elseif ($row.status_penerimaan==3)}<i class="glyphicon glyphicon-check"></i> Masuk Kuota
                                                    {elseif ($row.status_penerimaan==4)}<i class="glyphicon glyphicon-info-sign"></i> Masuk Kuota {$row.label_masuk_pilihan}
                                                    {else}<i class="glyphicon glyphicon-search"></i> Tidak Diperingkat
                                                    {/if}
                                                </td>
                                            </tr>
                                        {/if}
                                    </table><br>
                                    <table class="table table-bordered">
                                        <tr class="bg-primary">
                                            <th>Daftar Kelengkapan Berkas</th>
                                            <th class="text-center">Verifikasi</th>
                                        </tr>
                                        <tr>
                                            <td>Data Profil</td>
                                            <td class="text-center">{if ($kelengkapan_data==1)}<i class="text-blue glyphicon glyphicon-ok"></i>{else}<i class="text-red glyphicon glyphicon-remove"></i>{/if}</td>
                                        </tr>
                                        {foreach $kelengkapan[$row.pendaftaran_id] as $row2}
                                        <tr {if ($row2.kondisi_khusus)}class="bg-warning"{/if}>
                                            <td>{$row2.kelengkapan}</td>
                                            <td class="text-center">
                                                {if ($row2.verifikasi==1)}<i class="text-blue glyphicon glyphicon-ok"></i>
                                                {elseif ($row2.verifikasi==2)}<i class="text-red glyphicon glyphicon-remove"></i>
                                                {elseif ($row2.verifikasi==3 || ($row2.verifikasi==0 && $row2.wajib==0))}Tidak Ada
                                                {else}Dalam Proses{/if}
                                            </td>
                                        </tr>
                                        {/foreach}

                                        {foreach $berkasfisik[$row.pendaftaran_id] as $row2}
                                        <tr {if ($row2.kondisi_khusus>0)}class="bg-warning"{/if}>
                                            <td>{$row2.kelengkapan} (Berkas Fisik)</td>
                                            <td class="text-center">
                                            {if ($row2.berkas_fisik==1)}<i class="text-blue glyphicon glyphicon-ok"></i>
                                            {else}<i class="text-red glyphicon glyphicon-remove"></i>{/if}</td>
                                        </tr>
                                        {/foreach}
                                        <tr>
                                            <td colspan="2" class="text-warning"><b>Note</b> : Jika ada kelengkapan yang bertanda <i class="text-red glyphicon glyphicon-remove"></i> mohon untuk memperbaiki di halaman <a href="{base_url()}siswa/profil">Profil Siswa</a>.</td>
                                        </tr>
                                    </table><br>
                                    
                                    <table class="table table-bordered">
                                        <tr class="bg-black">
                                            <th>Daftar Skoring</th>
                                            <th class="text-center">Nilai</th>
                                        </tr>

                                        {assign var='totalnilai' value=0}
                                        {foreach $nilaiskoring[$row.pendaftaran_id] as $row2}
                                        <tr>
                                            <td>{$row2.keterangan}</td>
                                            <td class="text-end">{$row2.nilai}</td>
                                        </tr>
                                        {$totalnilai = $totalnilai + $row2.nilai}
                                        {/foreach}
                                        <tr class="bg-gray">
                                            <th>Total</th>
                                            <th class="text-end">{$totalnilai}</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>

                {if (1==0)}
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="box-header with-border">
                            <i class="glyphicon glyphicon-print"></i>
                            <h3 class="box-title text-info"><b>Cetak Bukti</b></h3>
                        </div>
                        <div class="box-body">
                            <p><b>"Bukti Pendaftaran PPDB"</b> digunakan sebagai lampiran pada saat penyerahan berkas di sekolah tujuan dan wajib disertakan tanda tangan Orang Tua/Siswa pendaftar.</p>
                        </div>
                        <div class="box-footer">
                            {if ($jumlahpendaftaran>0)}
                            <a href="{base_url()}siswa/pendaftaran/buktipendaftaran" class="btn bg-navy" target="_blank"><h4><i class="glyphicon glyphicon-print"></i> Cetak Bukti Pendaftaran</h4></a>
                            {/if}
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="box-header with-border">
                            <i class="glyphicon glyphicon-print"></i>
                            <h3 class="box-title text-info"><b>Cetak Surat Pernyataan Kesanggupan</b></h3>
                        </div>
                        <div class="box-body">
                            <p>Calon peserta didik yang pada saat pendaftaran belum memiliki akte kelahiran, dapat diganti dengan surat pernyataan kesanggupan dari orang tua/wali untuk melengkapi paling lambat pada semester 2 (dua) di tahun ajaran yang sama.</p>
                        </div>
                        <div class="box-footer">
                            <a href="{base_url()}siswa/pendaftaran/kesanggupankelengkapanakte" class="btn bg-orange" target="_blank"><h4><i class="glyphicon glyphicon-print"></i> Cetak Surat Pernyataan</h4></a>
                        </div>
                    </div>
                </div>
                {/if}
            </div>
        </div>
    </div>
</div>
