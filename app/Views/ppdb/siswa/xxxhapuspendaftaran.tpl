<!DOCTYPE html>
<html>
    {include file='../header.tpl'}
	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
            {include file='../navigation.tpl'}
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<h1 class="text-white">
							<i class="glyphicon glyphicon-remove"></i> Hapus Pendaftaran<small>Siswa</small>
						</h1>
						<ol class="breadcrumb">
							<li class="active"><a href="<?php echo site_url('siswa/pendaftaran');?>"><i class="glyphicon glyphicon-remove"></i> Batal </a></li>
						</ol>

					</section>
					<section class="content">
					<?php if ($maxhapuspendaftaran > $hapuspendaftaransiswa) { ?>
						<div class="alert alert-danger alert-dismissable">
							<i class="icon glyphicon glyphicon-info-sign"></i>
							Anda hanya bisa <b>menghapus</b> pendaftaran sebanyak <b><?php echo $maxhapuspendaftaran-$hapuspendaftaransiswa;?> kali</b>. Jika salah satu pilihan sekolah diperbaharui/dihapus, sistem mungkin akan menyesuikan jenis pilihan lain secara otomatis.
						</div>
						<?php } else { ?>
							<div class="alert alert-danger alert-dismissable">
							<i class="icon glyphicon glyphicon-info-warning"></i>
							Anda sudah tidak bisa <b>menghapus</b> pendaftaran karena sudah melebihi batasan.</b>.
						</div>
						<?php } ?>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="box box-solid">
                                            <div class="box-header with-border">
                                                <i class="glyphicon glyphicon-user"></i>
                                                <h3 class="box-title"><b>Pendaftaran</b></h3>
                                            </div>
                                            <div class="box-body">
                                                <table class="table table-striped">
                                                    <?php foreach($detailpendaftaran->getResult() as $row):?>
                                                    <tr <?php if($row->jenis_pilihan==0){?>class="bg-red"<?php }else{?>class="bg-warning"<?php }?>>
                                                        <td><b>Jenis Piihan</b></td>
                                                        <td>:</td>
                                                        <td><?php echo $row->jenis_pilihan;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Sekolah Pilihan</b></td>
                                                        <td>:</td>
                                                        <td>(<b><?php echo $row->npsn;?></b>) <a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/<?php echo $row->sekolah_id;?>" target="_blank"><?php echo $row->sekolah;?></a></td>
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
                                                                            <span class="pull-right"><a href="{$base_url}index.php/Chome/peringkat?sekolah_id=<?php echo $row->sekolah_id;?>" target="_blank"><i class="glyphicon glyphicon-search"></i> Lihat Peringkat</a></span></td>
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
                                                                            <?php }else if($row->status_penerimaan==4){?><i class="glyphicon glyphicon-info-sign"></i> Diterima Pilihan 1
                                                                            <?php }else {?><i class="glyphicon glyphicon-search"></i> Dalam Proses Seleksi
                                                                            <?php }?></td>
                                                                        </tr>
                                                    <?php endforeach;?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="box box-solid">
                                            <?php foreach($detailpendaftaran->getResult() as $row):?>
                                            <form role="form" enctype="multipart/form-data" id="proseshapuspendaftaran" action="{$base_url}index.php/siswa/pendaftaran/proseshapus" method="post">
                                                <input type="hidden" id="peserta_didik_id" name="peserta_didik_id" value="<?php echo $row->peserta_didik_id;?>">
                                                <input type="hidden" id="pendaftaran_id" name="pendaftaran_id" value="<?php echo $row->pendaftaran_id;?>">
                                                <div class="box-header with-border">
                                                    <i class="glyphicon glyphicon-remove text-danger"></i>
                                                    <h3 class="box-title text-info"><b>Formulir Hapus Pendaftaran</b></h3>
                                                </div>
                                                <div class="box-body">
                                                    <!-- <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> -->
                                                            <div class="form-group has-feedback">
                                                                <label for="keterangan">Alasan Hapus Pendaftaran</label>
                                                                <textarea id="keterangan" name="keterangan" placeholder="Penjelasan terkait Hapus Pendaftaran ..." data-validation="required" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                                            </div>
                                                        <!-- </div>
                                                    </div> -->
                                                </div>
                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-danger btn-flat" <?php if($maxhapuspendaftaran<=$hapuspendaftaransiswa||$this->session->userdata("tutup_akses")==1){?>disabled="true"<?php }?>>Hapus Pendaftaran</button>
                                                </div>
                                            </form>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        

                    </section>
				</div>
			</div>
			{include file='../footer.tpl'}
		</div>
	</body>

    <script src="{$base_url}assets/formvalidation/form-validator/jquery.form-validator.js"></script>
    <script>
        //Validasi
        var $messages = $('#error-message-wrapper');
        $.validate({
            modules: 'security',
            errorMessagePosition: $messages,
            scrollToTopOnError: false
        });
    </script>

</html>
