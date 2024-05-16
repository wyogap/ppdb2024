
<?php if($status_penerimaan==0){?>
    <td class="bg-gray"><i class="glyphicon glyphicon-search"></i> Dalam Proses Seleksi</td>
<?php }else if($status_penerimaan==1){?>
    <td class="bg-green"><i class="glyphicon glyphicon-check"></i> Masuk Kuota</td>
<?php }else if($status_penerimaan==2){?>
    <td class="bg-red"><i class="glyphicon glyphicon-info-sign"></i> Tidak Masuk Kuota</td>
<?php }else if($status_penerimaan==3){?>
    <td class="bg-yellow"><i class="glyphicon glyphicon-check"></i> Daftar Tunggu</td>
<?php }else if($status_penerimaan==4){?>
    <td class="bg-gray"><i class="glyphicon glyphicon-info-sign"></i> Masuk Kuota Pilihan <?php echo $masuk_jenis_pilihan ?></td>
<?php }else {?>
    <td class="bg-gray"><i class="glyphicon glyphicon-search"></i> Dalam Proses Seleksi</td>
<?php }?>