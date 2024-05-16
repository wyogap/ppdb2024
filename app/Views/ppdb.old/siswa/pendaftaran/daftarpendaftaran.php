<?php 
    global $kelengkapan_data;
    global $maxcabutberkas, $maxhapuspendaftaran, $maxubahjenispilihan, $maxubahsekolah, $maxubahjalur, $cabutberkassiswa, $hapuspendaftaransiswa, $ubahjenispilihansiswa, $ubahsekolahsiswa, $ubahjalursiswa;

    global $bisa_perubahan;
?>

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
                    <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php
                        $jumlahpendaftaran=0;
                        foreach($daftarpendaftaran->getResult() as $row):
                            $jumlahpendaftaran++;

                        //echo "$row->kelengkapan_berkas, $ubahjenispilihansiswa, $maxubahjenispilihan, $row->jenis_pilihan, " . $this->session->userdata('tutup_akses') . ", $row->penerapan_id <br>";

                        //1, 0, 1, 1, 0, 25
                        //if(($row->kelengkapan_berkas!=0||$ubahjenispilihansiswa>=$maxubahjenispilihan)&($row->jenis_pilihan!=0)||$this->session->userdata("tutup_akses")==1||$row->penerapan_id==46)

                    ?>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <p>(<b><?php echo $row->npsn;?></b>) <b><?php echo $row->sekolah;?></b></p>
                                        
                                        <?php if (($cek_waktupendaftaran == 1 || $cek_waktusosialisasi == 1) && $this->session->userdata("tutup_akses")!=1 && $bisa_perubahan == 1) { ?>

                                            <div style="min-height: 38px;">
                                            <?php if ($row->pendaftaran==1) { ?>
                                            <?php if ($maxubahjenispilihan > 0) { ?>
                                            <a href="<?php echo base_url();?>index.php/siswa/pendaftaran/ubahjenispilihan?pendaftaran_id=<?php echo $row->pendaftaran_id;?>" class="btn btn-warning 
                                            <?php if(
                                                ($ubahjenispilihansiswa>=$maxubahjenispilihan&&$row->jenis_pilihan!=0)||$row->pendaftaran!=1 
                                                ){?>disabled<?php }?>" style="margin-top: 4px;">
                                                <i class="glyphicon glyphicon-edit"></i> Ubah Pilihan
                                            </a>
                                            <?php } ?>

                                            <?php if ($maxubahjalur > 0) { ?>
                                            <a href="<?php echo base_url();?>index.php/siswa/pendaftaran/ubahjalur?pendaftaran_id=<?php echo $row->pendaftaran_id;?>" class="btn btn-warning 
                                            <?php if(
                                                ($ubahjalursiswa>=$maxubahjalur)||$row->pendaftaran!=1
                                                ){?>disabled<?php }?>" style="margin-top: 4px;">
                                                <i class="glyphicon glyphicon-sort"></i> Ubah Jalur
                                            </a>
                                            <?php } ?>

                                            <?php if($row->jenis_pilihan!=0) { ?>

                                                <?php if ($maxubahsekolah > 0) { ?>
                                                <a href="<?php echo base_url();?>index.php/siswa/pendaftaran/ubahsekolah?pendaftaran_id=<?php echo $row->pendaftaran_id;?>&penerapan_id=<?php echo $row->penerapan_id;?>" class="btn bg-olive 
                                                <?php if($ubahsekolahsiswa>=$maxubahsekolah||$row->pendaftaran!=1
                                                    ){?>disabled<?php }?>" style="margin-top: 4px;">
                                                    <i class="glyphicon glyphicon-home"></i> Ubah Sekolah
                                                </a>
                                                <?php } ?>

                                                <?php if ($maxhapuspendaftaran > 0) { ?>
                                                <a href="<?php echo base_url();?>index.php/siswa/pendaftaran/hapus?pendaftaran_id=<?php echo $row->pendaftaran_id;?>" class="btn btn-danger 
                                                <?php if($hapuspendaftaransiswa>=$maxhapuspendaftaran||$row->pendaftaran!=1
                                                    ){?>disabled<?php }?>" style="margin-top: 4px;">
                                                    <i class="glyphicon glyphicon-remove"></i> Hapus Pendaftaran
                                                </a>
                                                <?php } ?>

                                            <?php }?>
                                            <?php }?>
                                            </div>

                                        <?php }?>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped">
                                        <?php if($row->jenis_pilihan==0){?>
                                        <tr>
                                            <td colspan="3" class="text-danger">Jenis pilihan belum diperbaharui, silahkan lakukan perbaikan melalui menu <b><i class="glyphicon glyphicon-edit"></i> Ubah Pilihan</b> diatas (<i class="glyphicon glyphicon-arrow-up"></i>) ini</td>
                                        </tr>
                                        <?php }?>
                                        <tr <?php if($row->jenis_pilihan==0){?>class="bg-red"<?php }?>>
                                            <td><b>Jenis Pilihan</b></td>
                                            <td>:</td>
                                            <td><?php if($row->jenis_pilihan!=0){?><?php echo $row->label_jenis_pilihan;?><?php }else{?>Belum diperbaharui<?php }?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Jalur</b></td>
                                            <td>:</td>
                                            <td><?php echo $row->jalur;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Waktu Pendaftaran</b></td>
                                            <td>:</td>
                                            <td><?php echo $row->created_on;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Nomor Pendaftaran</b></td>
                                            <td>:</td>
                                            <td><?php echo $row->nomor_pendaftaran;?></td>
                                        </tr>
                                        <?php if ($cek_waktupendaftaran == 1) { ?>
                                            <tr>
                                                <td><b>Peringkat</b></td>
                                                <td>:</td>
                                                <td>
                                                <?php if($row->peringkat==0){?>Belum Ada
                                                <?php } else if($row->peringkat==9999 && $row->status_penerimaan==4){?>Tidak Dihitung
                                                <?php } else if($row->peringkat==9999 && $row->status_penerimaan==2){?>Tidak Dihitung
                                                <?php } else if($row->peringkat==9999){?>Belum Ada
                                                <?php } else if($row->peringkat==-1){?>Tidak Ada
                                                <?php } else{?><?php echo $row->peringkat_final;?>
                                                <?php }?>
                                                <span class="pull-right"><a href="<?php echo base_url();?>index.php/home/peringkat?sekolah_id=<?php echo $row->sekolah_id;?>&bentuk=<?php echo $row->bentuk;?>" target="_blank"><i class="glyphicon glyphicon-search"></i> Lihat Peringkat</a></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Status Pendaftaran</b></td>
                                                <td>:</td>
                                                <td class="
                                                <?php if($row->status_penerimaan==0){?>bg-gray
                                                <?php }else if($row->status_penerimaan==1){?>bg-green
                                                <?php }else if($row->status_penerimaan==2){?>bg-red
                                                <?php }else if($row->status_penerimaan==3){?>bg-yellow
                                                <?php }else if($row->status_penerimaan==4){?>bg-gray
                                                <?php }else {?>bg-gray
                                                <?php }?>">
                                                <?php if($row->status_penerimaan==0){?><i class="glyphicon glyphicon-search"></i> Dalam Proses Seleksi
                                                <?php }else if($row->status_penerimaan==1){?><i class="glyphicon glyphicon-check"></i> Masuk Kuota
                                                <?php }else if($row->status_penerimaan==2){?><i class="glyphicon glyphicon-info-sign"></i> Tidak Masuk Kuota
                                                <?php }else if($row->status_penerimaan==3){?><i class="glyphicon glyphicon-check"></i> Daftar Tunggu
                                                <?php }else if($row->status_penerimaan==4){?><i class="glyphicon glyphicon-info-sign"></i> Masuk Kuota <?php echo $row->label_masuk_pilihan; ?>
                                                <?php }else {?><i class="glyphicon glyphicon-search"></i> Dalam Proses Seleksi
                                                <?php }?></td>
                                            </tr>
                                        <?php } else { ?>
                                            <!-- Bukan waktu pendaftaran -->
                                            <tr>
                                                <td><b>Peringkat</b></td>
                                                <td>:</td>
                                                <td>
                                                <?php if($row->status_penerimaan_final==0){?>Tidak Dihitung
                                                <?php }else if($row->status_penerimaan_final==1){?><?php echo $row->peringkat_final;?>
                                                <?php }else if($row->status_penerimaan_final==2){?>Tidak Dihitung
                                                <?php }else if($row->status_penerimaan_final==3){?><?php echo $row->peringkat_final;?>
                                                <?php }else if($row->status_penerimaan_final==4){?>Tidak Dihitung
                                                <?php }else {?>Tidak Dihitung
                                                <?php }?>
                                                <span class="pull-right"><a href="<?php echo base_url();?>index.php/home/peringkatfinal?sekolah_id=<?php echo $row->sekolah_id;?>&bentuk=<?php echo $row->bentuk;?>" target="_blank"><i class="glyphicon glyphicon-search"></i> Lihat Peringkat</a></span>
                                                </td>
                                            </tr>
                                            <tr>
                                            <td><b>Status Pendaftaran</b></td>
                                            <td>:</td>
                                            <td class="
                                            <?php if($row->status_penerimaan_final==0){?>bg-gray
                                            <?php }else if($row->status_penerimaan_final==1){?>bg-green
                                            <?php }else if($row->status_penerimaan_final==2 && $row->masuk_jenis_pilihan!=0 && $row->masuk_jenis_pilihan!=$row->jenis_pilihan){?>bg-gray
                                            <?php }else if($row->status_penerimaan_final==2){?>bg-red
                                            <?php }else if($row->status_penerimaan_final==3){?>bg-green
                                            <?php }else if($row->status_penerimaan_final==4){?>bg-gray
                                            <?php }else {?>bg-gray
                                            <?php }?>">
                                            <?php if($row->kelengkapan_berkas!=1){?><i class="glyphicon glyphicon-search"></i> Berkas Tidak Lengkap
                                            <?php }else if($row->status_penerimaan_final==1){?><i class="glyphicon glyphicon-check"></i> Masuk Kuota
                                            <?php }else if($row->status_penerimaan_final==2 && $row->masuk_jenis_pilihan!=0 && $row->masuk_jenis_pilihan!=$row->jenis_pilihan){?><i class="glyphicon glyphicon-info-sign"></i> Masuk Kuota <?php echo $row->label_masuk_pilihan; ?>
                                            <?php }else if($row->status_penerimaan==2){?><i class="glyphicon glyphicon-info-sign"></i> Tidak Masuk Kuota
                                            <?php }else if($row->status_penerimaan==3){?><i class="glyphicon glyphicon-check"></i> Masuk Kuota
                                            <?php }else if($row->status_penerimaan==4){?><i class="glyphicon glyphicon-info-sign"></i> Masuk Kuota <?php echo $row->label_masuk_pilihan; ?>
                                            <?php }else {?><i class="glyphicon glyphicon-search"></i> Tidak Diperingkat
                                            <?php }?></td>
                                            </tr>
                                        <?php } ?>
                                        <!-- <tr>
                                            <td><b>Peringkat</b></td>
                                            <td>:</td>
                                            <td>
                                            <?php if($row->peringkat_final==0){?>Belum Ada
                                            <?php } else if($row->peringkat_final==9999 && $row->status_penerimaan==4){?>Tidak Dihitung
                                            <?php } else if($row->peringkat_final==9999 && $row->status_penerimaan==2){?>Tidak Dihitung
                                            <?php } else if($row->peringkat_final==9999){?>Belum Ada
                                            <?php } else if($row->peringkat_final==-1){?>Tidak Ada
                                            <?php } else{?><?php echo $row->peringkat_final;?>
                                            <?php }?>
                                            <span class="pull-right"><a href="<?php echo base_url();?>index.php/Chome/peringkat?sekolah_id=<?php echo $row->sekolah_id;?>&bentuk=<?php echo $row->bentuk;?>" target="_blank"><i class="glyphicon glyphicon-search"></i> Lihat Peringkat</a></span>
                                        </tr>
                                        <tr>
                                            <td><b>Status Pendaftaran</b></td>
                                            <td>:</td>
                                            <?php
                                                $data['status_penerimaan']=$row->status_penerimaan;
                                                $data['masuk_jenis_pilihan']=$row->masuk_jenis_pilihan;
                                                view('dropdown/statuspendaftaran',$data);
                                            ?>
                                        </tr> -->
                                    </table><br>
                                    <table class="table table-bordered">
                                        <tr class="bg-primary">
                                            <th>Daftar Kelengkapan Berkas</th>
                                            <th class="text-center">Verifikasi</th>
                                        </tr>
                                        <tr>
                                            <td>Data Profil</td>
                                            <td class="text-center"><?php if($kelengkapan_data==1){?><i class="text-blue glyphicon glyphicon-ok"></i><?php }else {?><i class="text-red glyphicon glyphicon-remove"></i><?php }?></td>
                                        </tr>
                                        <?php
                                            $kelengkapanpendaftaran = $this->Msiswa->kelengkapanpendaftaran($row->pendaftaran_id);
                                            foreach($kelengkapanpendaftaran->getResult() as $row2):
                                        ?>
                                        <tr <?php if($row2->kondisi_khusus>0){?>class="bg-warning"<?php }?>>
                                            <td><?php echo $row2->kelengkapan;?></td>
                                            <td class="text-center">
                                            <?php if($row2->verifikasi==1){?><i class="text-blue glyphicon glyphicon-ok"></i>
                                            <?php }else if($row2->verifikasi==2){?><i class="text-red glyphicon glyphicon-remove"></i>
                                            <?php }else if($row2->verifikasi==3 || ($row2->verifikasi==0 && $row2->wajib==0)){?>Tidak Ada
                                            <?php }else{?>Dalam Proses<?php }?></td>
                                        </tr>
                                        <?php endforeach;?>
                                        <?php
                                            $kelengkapanpendaftaran = $this->Msiswa->kelengkapanpendaftaran_berkasfisik($row->pendaftaran_id);
                                            foreach($kelengkapanpendaftaran->getResult() as $row2):
                                        ?>
                                        <tr <?php if($row2->kondisi_khusus>0){?>class="bg-warning"<?php }?>>
                                            <td><?php echo $row2->kelengkapan;?> (Berkas Fisik)</td>
                                            <td class="text-center">
                                            <?php if($row2->berkas_fisik==1){?><i class="text-blue glyphicon glyphicon-ok"></i>
                                            <?php }else{?><i class="text-red glyphicon glyphicon-remove"></i><?php }?></td>
                                        </tr>
                                        <?php endforeach;?>
                                        <tr>
                                            <td colspan="2" class="text-warning"><b>Note</b> : Jika ada kelengkapan yang bertanda <i class="text-red glyphicon glyphicon-remove"></i> mohon untuk memperbaiki di halaman <a href="<?php echo base_url();?>index.php/siswa/profil">Profil Siswa</a>.</td>
                                        </tr>
                                    </table><br>
                                    
                                    <?php if ($row->jalur_id=2) { ?>
                                    <table class="table table-bordered">
                                        <tr class="bg-black">
                                            <th>Daftar Skoring</th>
                                            <th class="text-center">Nilai</th>
                                        </tr>
                                        <?php
                                            $jumlah_nilai = 0;
                                            $nilaiskoring = $this->Msiswa->nilaiskoring($row->pendaftaran_id);
                                            foreach($nilaiskoring->getResult() as $row3):
                                        ?>
                                        <tr>
                                            <td><?php echo $row3->keterangan;?></td>
                                            <td class="text-right"><?php echo $row3->nilai;?></td>
                                        </tr>
                                        <?php $jumlah_nilai = $jumlah_nilai+$row3->nilai; endforeach;?>
                                        <tr class="bg-gray">
                                            <th>Total</th>
                                            <th class="text-right"><?php echo $jumlah_nilai;?></th>
                                        </tr>
                                    </table>
                                    <?php } else { ?>
                                    <table class="table table-bordered">
                                        <tr class="bg-black">
                                            <th>Daftar Skoring</th>
                                            <th class="text-center">Nilai</th>
                                        </tr>
                                        <?php
                                            $jumlah_nilai = 0;
                                            $nilaiskoring = $this->Msiswa->nilaiskoring($row->pendaftaran_id);
                                            foreach($nilaiskoring->getResult() as $row3):
                                        ?>
                                        <tr>
                                            <td><?php echo $row3->keterangan;?></td>
                                            <td class="text-right"><?php echo $row3->nilai;?></td>
                                        </tr>
                                        <?php $jumlah_nilai = $jumlah_nilai+$row3->nilai; endforeach;?>
                                        <tr class="bg-gray">
                                            <th>Total</th>
                                            <th class="text-right"><?php echo $jumlah_nilai;?></th>
                                        </tr>
                                    </table>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php if ($jumlahpendaftaran % 2 == 0 && $daftarpendaftaran->num_rows() > $jumlahpendaftaran) { ?>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php } ?>
                    <?php endforeach;?>
                    </div>
                    </div>
                </div>

                <?php if (1==0) { ?>
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
                            <?php if($jumlahpendaftaran>0){?>
                            <a href="<?php echo base_url();?>index.php/siswa/pendaftaran/buktipendaftaran" class="btn bg-navy" target="_blank"><h4><i class="glyphicon glyphicon-print"></i> Cetak Bukti Pendaftaran</h4></a>
                            <?php }?>
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
                            <a href="<?php echo base_url();?>index.php/siswa/pendaftaran/kesanggupankelengkapanakte" class="btn bg-orange" target="_blank"><h4><i class="glyphicon glyphicon-print"></i> Cetak Surat Pernyataan</h4></a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
