<?php if($detailpilihan!=null) {?>
<table class="table">
	<tr <?php if($detailpilihan['jenis_pilihan']==0){?>class="bg-red"<?php }else{?>class="bg-warning"<?php }?>>
		<td><b>Jenis Pilihan</b></td>
		<td>:</td>
		<td>Pilihan <?php if($detailpilihan['jenis_pilihan']!=0){?><?php echo $detailpilihan['jenis_pilihan'];?><?php }else{?>Belum diperbaharui<?php }?></td>
	</tr>
	<tr>
		<td><b>Sekolah</b></td>
		<td>:</td>
		<td>(<?php echo $detailpilihan['npsn'];?>) <?php echo $detailpilihan['sekolah'];?></td>
	</tr>
	<tr>
		<td><b>Jalur</b></td>
		<td>:</td>
		<td><?php echo $detailpilihan['jalur'];?></td>
	</tr>
	<tr>
		<td><b>Waktu Pendaftaran</b></td>
		<td>:</td>
		<td><?php echo $detailpilihan['create_date'];?></td>
	</tr>
	<tr>
		<td><b>Nomor Pendaftaran</b></td>
		<td>:</td>
		<td><?php echo $detailpilihan['nomor_pendaftaran'];?></td>
	</tr>
	<tr>
		<td><b>Peringkat</b></td>
		<td>:</td>
		<td>
			<?php if($detailpilihan['peringkat']==0 || $detailpilihan['peringkat']==9999){?>Belum Ada Peringkat
			<?php } else if($detailpilihan['peringkat']==-1){?>Tidak Ada Peringkat
			<?php } else{?><?php echo $detailpilihan['peringkat'];?>
			<?php }?>
			<span class="pull-right"><a href="javascript:void(0)" target="_blank"><i class="glyphicon glyphicon-search"></i></a></span>
		</td>
	</tr>
	<tr>
		<td><b>Status Penerimaan</b></td>
		<td>:</td>
		<?php
			$data['status_penerimaan']=$detailpilihan['status_penerimaan'];
			$data['masuk_jenis_pilihan']=$detailpilihan['masuk_jenis_pilihan'];
			view('dropdown/statuspendaftaran',$data);
		?>
	</tr>
</table>
<table class="table table-bordered">
	<tr class="bg-blue">
		<th>Daftar Skoring</th>
		<th class="text-center">Nilai</th>
	</tr>
	<?php
		$jumlah_nilai = 0;
		$nilaiskoring = $this->Msiswa->nilaiskoring($detailpilihan['pendaftaran_id']);
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
<form role="form" enctype="multipart/form-data" action="<?php echo base_url();?>index.php/siswa/pendaftaran/prosesubahjalur" method="post">
	<input type="hidden" id="pendaftaran_id" name="pendaftaran_id" value="<?php echo $detailpilihan['pendaftaran_id'];?>" data-validation="required">
	<input type="hidden" id="jalur_penerapan_awal" name="jalur_penerapan_awal" value="<?php echo $detailpilihan['penerapan_id'];?>" data-validation="required">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="form-group has-feedback">
				<label for="jalur_penerapan">Ubah jalur pendaftaran menjadi :</label>
				<select id="jalur_penerapan_baru" name="jalur_penerapan_baru" class="form-control select2" data-validation="required" <?php if($maxubahjalur<=$ubahjalursiswa&&$detailpilihan['jenis_pilihan']!=0){?>disabled="true"<?php }?>>
					<option value="">-- Jalur Pendaftaran --</option>
					<?php foreach($daftarpenerapan as $penerapan):?>
					<option value="<?php echo $penerapan['penerapan_id'];?>"><?php echo $penerapan['nama'];?></option>
					<?php endforeach;?>
				</select>
			</div>
		</div>
		<div id="dokumen_pendukung" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		</div>
		<?php if (count($daftarpenerapan) > 0) { ?>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<button type="submit" class="btn btn-warning btn-flat" <?php if($maxubahjalur==$ubahjalursiswa&&$row->jenis_pilihan!=0){?>disabled="true"<?php }?>>Ubah Jalur Pendaftaran</button>
		</div>
		<?php } ?>
	</div>
</form>
<?php }?>
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

	//Event On Change Dropdown
	$(document).ready(function () {
		$('select[name="jalur_penerapan_baru"]').on('change', function() {
			if ($("#jalur_penerapan_baru").val() == '') {
				$('#dokumen_pendukung').html("");
				return;
			}
			
			var data = {penerapan_id:$("#jalur_penerapan_baru").val()};
			$.ajax({
				type: "POST",
				url : "<?php echo site_url('siswa/pendaftaran/dokumentpendukungjalur')?>",
				data: data,
				success: function(msg){
					$('#dokumen_pendukung').html(msg);
				}
			});
		});

	});
</script>