<?php
    $this->load->model('Mdinas');
    $rekapitulasismp = $this->Mdinas->rekapitulasismp();
    $rekapitulasismpswasta = $this->Mdinas->rekapitulasismpswasta();
?>

<div class="container">
    <div class="title-call-action another text-center">
        Rekapitulasi PPDB
    </div>
    <div class="flat-tabs border clearfix" data-effect ="fadeIn">
        <ul class="menu-tab inline clearfix"> 
        <!-- <ul class="nav nav-tabs"> -->
            <!-- <li class="active"><a href="#">Jenjang SMP</a></li> -->
            <li class="active"><a href="#smp" data-toggle="tab">SMP NEGERI</a></li>
			<li><a href="#swasta" data-toggle="tab">SMP SWASTA</a></li>
        </ul><!-- /.menu-tab -->
        <div class="tab-content">
				<div class="tab-pane active" id="smp">
					<div class="table-responsive">
						<table class="table table-hover table-bordered" id="tsmp">
							<thead>
								<tr>
									<th rowspan="2" class="text-center">&nbsp;</th>
									<th rowspan="2" class="text-center">NPSN</th>
									<th rowspan="2" class="text-center">Nama</th>
									<th colspan="3" class="text-center">Zonasi</th>
									<th colspan="3" class="text-center">Prestasi</th>
									<th colspan="3" class="text-center">Afirmasi</th>
									<th colspan="3" class="text-center">Perpindahan Orang Tua</th>
									<!-- <th colspan="3" class="text-center">Swasta</th> -->
								</tr>
								<tr>
									<th class="text-center">Masuk Kuota</th>
									<th class="text-center">Tidak Masuk Kuota</th>
									<th class="text-center">Jumlah</th>
									<th class="text-center">Masuk Kuota</th>
									<th class="text-center">Tidak Masuk Kuota</th>
									<th class="text-center">Jumlah</th>
									<th class="text-center">Masuk Kuota</th>
									<th class="text-center">Tidak Masuk Kuota</th>
									<th class="text-center">Jumlah</th>
									<th class="text-center">Masuk Kuota</th>
									<th class="text-center">Tidak Masuk Kuota</th>
									<th class="text-center">Jumlah</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; foreach($rekapitulasismp->getResult() as $row):?>
								<tr>
									<td class="text-center"><a href="<?php echo base_url();?>index.php/Chome/peringkat?sekolah_id=<?php echo $row->sekolah_id;?>"><i class="glyphicon glyphicon-list-alt"></i></a></td>
									<td class="text-center"><?php echo $row->npsn;?></td>
									<td><?php echo $row->nama;?></td>
									<td class="text-end"><?php echo $row->zonasi_diterima;?></td>
									<td class="text-end"><?php echo $row->zonasi_tidak_diterima;?></td>
									<td class="text-end"><?php echo $row->pendaftar_zonasi;?></td>
									<td class="text-end"><?php echo $row->prestasi_diterima;?></td>
									<td class="text-end"><?php echo $row->prestasi_tidak_diterima;?></td>
									<td class="text-end"><?php echo $row->pendaftar_prestasi;?></td>
									<td class="text-end"><?php echo $row->afirmasi_diterima;?></td>
									<td class="text-end"><?php echo $row->afirmasi_tidak_diterima;?></td>
									<td class="text-end"><?php echo $row->pendaftar_afirmasi;?></td>
									<td class="text-end"><?php echo $row->perpindahan_orang_tua_diterima;?></td>
									<td class="text-end"><?php echo $row->perpindahan_orang_tua_tidak_diterima;?></td>
									<td class="text-end"><?php echo $row->pendaftar_perpindahan_orang_tua;?></td>
								</tr>
								<?php $i++; endforeach;?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="tab-pane" id="swasta">
					<div class="table-responsive">
						<table class="table table-hover table-bordered" id="tswasta">
							<thead>
								<tr>
									<th rowspan="2" class="text-center">&nbsp;</th>
									<th rowspan="2" class="text-center">NPSN</th>
									<th rowspan="2" class="text-center">Nama</th>
									<th colspan="3" class="text-center">Swasta</th>
								</tr>
								<tr>
									<th class="text-center">Masuk Kuota</th>
									<th class="text-center">Tidak Masuk Kuota</th>
									<th class="text-center">Jumlah</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; foreach($rekapitulasismpswasta->getResult() as $row):?>
								<tr>
									<td class="text-center"><a href="<?php echo base_url();?>index.php/Chome/peringkat?sekolah_id=<?php echo $row->sekolah_id;?>"><i class="glyphicon glyphicon-list-alt"></i></a></td>
									<td class="text-center"><?php echo $row->npsn;?></td>
									<td><?php echo $row->nama;?></td>
                                    <td class="text-end"><?php echo $row->swasta_diterima;?></td>
                                    <td class="text-end"><?php echo $row->swasta_tidak_diterima;?></td>
                                    <td class="text-end"><?php echo $row->pendaftar_swasta;?></td>
								</tr>
								<?php $i++; endforeach;?>
							</tbody>
						</table>
					</div>
				</div>
        </div>
    </div>
</div>