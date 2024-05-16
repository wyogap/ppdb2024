<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css"/>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

    <?php foreach($detailpilihan->getResult() as $row):?>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="glyphicon glyphicon-search"></i>
                        <h3 class="box-title text-info"><b>Detail Pilihan</b></h3>
                    </div>
                    <div class="box-body">
                        <table class="table">
                            <tr <?php if($row->jenis_pilihan==0){?>class="bg-red"<?php }else{?>class="bg-warning"<?php }?>>
                                <td><b>Jenis Pilihan</b></td>
                                <td>:</td>
                                <td><?php if($row->jenis_pilihan!=0){?><?php echo $row->label_jenis_pilihan;?><?php }else{?>Belum diperbaharui<?php }?></td>
                            </tr>
                            <tr>
                                <td><b>Sekolah</b></td>
                                <td>:</td>
                                <td>(<?php echo $row->npsn;?>) <?php echo $row->sekolah;?></td>
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
                            <tr>
                                <td><b>Peringkat</b></td>
                                <td>:</td>
                                <td>
                                    <?php if($row->peringkat==0 || $row->peringkat==9999){?>Belum Ada Peringkat
                                    <?php } else if($row->peringkat==-1){?>Tidak Ada Peringkat
                                    <?php } else{?><?php echo $row->peringkat;?>
                                    <?php }?>
                                    <span class="pull-right"><a href="javascript:void(0)" target="_blank"><i class="glyphicon glyphicon-search"></i></a></span>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Status Penerimaan</b></td>
                                <td>:</td>
                                <?php
                                    $data['status_penerimaan']=$row->status_penerimaan;
                                    $data['masuk_jenis_pilihan']=$row->masuk_jenis_pilihan;
                                    view('dropdown/statuspendaftaran',$data);
                                ?>
                            </tr>
                        </table>

                        <?php if (false) { ?>
                        <table class="table table-bordered">
                            <tr class="bg-blue">
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
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="box box-solid">
                    <div class="box-body">

                        <form role="form" enctype="multipart/form-data" action="<?php echo base_url();?>index.php/siswa/pendaftaran/prosesubahjenispilihan" method="post">
                            <input type="hidden" id="pendaftaran_id" name="pendaftaran_id" value="<?php echo $row->pendaftaran_id;?>" data-validation="required">
                            <input type="hidden" id="jenis_pilihan_awal" name="jenis_pilihan_awal" value="<?php echo $row->jenis_pilihan;?>" data-validation="required">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group has-feedback">
                                        <label for="jenis_pilihan">Ubah jenis pilihan menjadi :</label>
                                        <select id="jenis_pilihan_baru" name="jenis_pilihan_baru" class="form-control select2" data-validation="required" <?php if(($maxubahjenispilihan<=$ubahjenispilihansiswa&&$row->jenis_pilihan!=0)||$this->session->userdata("tutup_akses")==1){?>disabled="true"<?php }?>>
                                            <option value="">-- Jenis Pilihan --</option>
                                            <?php foreach($jenispilihan->getResult() as $row2):?>
                                            <option value="<?php echo $row2->jenis_pilihan;?>"><?php echo $row2->keterangan;?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="submit" class="btn btn-warning btn-flat" <?php if(($maxubahjenispilihan<=$ubahjenispilihansiswa&&$row->jenis_pilihan!=0)||$this->session->userdata("tutup_akses")==1){?>disabled="true"<?php }?>>Ubah Jenis Pilihan</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;?>

	</div>
</div>

<script>
	//Dropdown Select
	$(function () {
		$(".select2").select2();
	});

	//Validasi
	var $messages = $('#error-message-wrapper');
	$.validate({
		modules: 'security',
		errorMessagePosition: $messages,
		scrollToTopOnError: false
	});

</script>