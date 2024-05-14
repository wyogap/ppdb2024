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
							<i class="glyphicon glyphicon-th-list"></i> Daftar Ulang <small>Pendaftaran</small>
						</h1>
						<!-- <ol class="breadcrumb">
							<li class="active"><a href="<?php echo site_url('sekolah/daftarulang');?>"><i class="glyphicon glyphicon-th-list"></i> Kembali</a></li>
						</ol> -->
						<div class="tahun-selection">
							<div class="tahun-selection-label">
							Periode: 
							</div>
								<?php if (1==0) { ?>
								<select id="putaran" name="putaran" class="tahun-selection-control" data-validation="required">
								<?php foreach($daftarputaran->getResult() as $row2): ?>
									<option value="<?php echo $row2->putaran; ?>" <?php if($row2->putaran==$putaran_aktif){?>selected="true"<?php }?>><?php echo $row2->nama; ?></option>
								<?php endforeach;?>
								</select>
								<?php } ?>
								<select id="tahun_ajaran" name="tahun_ajaran" class="tahun-selection-control" data-validation="required">
								<?php foreach($daftartahunajaran->getResult() as $row2): ?>
									<option value="<?php echo $row2->tahun_ajaran_id; ?>" <?php if($row2->tahun_ajaran_id==$tahun_ajaran_aktif){?>selected="true"<?php }?>><?php echo $row2->nama; ?></option>
								<?php endforeach;?>
								</select>

						</div>
					</section>
					<section class="content">
						
                        <span><?php if(isset($info)){echo $info;}?></span>

                        <div class="row">
                            <form role="form" name="prosesdaftarulang" id="prosesdaftarulang" action="{$base_url}index.php/Csekolah/prosesdaftarulang/" method="post">
                                <?php if ($daftarpenerapan->num_rows() == 0) { ?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="box box-solid">
                                        <div class="box-body text-center">
                                            Tidak Ada Data
                                        </div>
                                    </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="nav-tabs-custom" id="tabs">
                                            <ul class="nav nav-pills nav-justified" id="tabNames">
                                                <?php $i=1; foreach($daftarpenerapan->getResult() as $row):
                                                    //skip jalur inklusi kalau sekolah bukan sekolah inklusi
                                                    if ($row->jalur_id==7 && !$inklusi) {
                                                        continue;
                                                    }
                                                ?>
                                                <li <?php if($i==1){?>class="active"<?php }?>>
                                                <a href="#p<?php echo $row->penerapan_id;?>" data-toggle="tab"><?php echo $row->jalur;?><br>
                                                    <small class="label bg-blue"><?php echo $row->kuota;?></small>
                                                    <small class="label bg-green"><?php echo $row->diterima;?></small>
                                                </a></li>
                                                <?php $i++; endforeach;?>
                                            </ul>
                                            <div class="tab-content">
                                                <?php $i=1;$kuota=0; foreach($daftarpenerapan->getResult() as $row):?>
                                                <div class="tab-pane <?php if($i==1){?>active<?php }?>" id="p<?php echo $row->penerapan_id;?>">
                                                    <!-- <div class="table-responsive"> -->
                                                        <table class="display" id="t<?php echo $row->penerapan_id;?>" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center" data-priority="1"></th>
                                                                    <th class="text-center" data-priority="3">#</th>
                                                                    <th class="text-center">No. Pendaftaran</th>
                                                                    <th class="text-center">NISN</th>
                                                                    <th class="text-center" data-priority="2">Nama</th>
                                                                    <th class="text-center">Jenis Kelamin</th>
                                                                    <th class="text-center">Tanggal Pendaftaran</th>
                                                                    <th class="text-center">Skor</th>
                                                                    <th class="text-center">Jenis Pilihan</th>
                                                                    <th class="text-center" data-priority="4">Tanggal Daftar Ulang</th>
                                                                    <th class="none">Nilai Kelulusan</th>
                                                                    <th class="none">Nilai USBN</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php 
                                                                    $kuota=$row->kuota;
                                                                    $i=1;

                                                                    $this->load->model('Msekolah');
                                                                    $sekolah_id = $this->session->userdata("sekolah_id");
                                                                    $pendaftar = $this->Msekolah->tcg_pendaftarditerima($sekolah_id, $row->penerapan_id);

                                                                    foreach($pendaftar->getResult() as $row2):
                                                                ?>
                                                                <tr>
                                                                    <!-- <td class="text-center"><input class="cekall" name="pdid_<?php echo $row2->pendaftaran_id;?>" id="pdid_<?php echo $row2->pendaftaran_id;?>" type="checkbox" onclick="$(this).attr('value', this.checked ? 1 : 0)" <?php if($row2->status_daftar_ulang==1){?>checked="true"<?php }?> <?php if ($waktudaftarulang == 0) { ?>disabled<?php } ?>></td> -->
                                                                    <td class="text-left">
                                                                        <a href="{$base_url}index.php/sekolah/daftarulang/siswa?pendaftaran_id=<?php echo $row2->pendaftaran_id;?>" class="btn btn-xs btn-primary">Daftar Ulang</a><br>
                                                                            <?php if ($row2->status_daftar_ulang==1) { ?>
                                                                                <a href="{$base_url}index.php/sekolah/daftarulang/buktipendaftaran?peserta_didik_id=<?php echo $row2->peserta_didik_id;?>" class="btn btn-xs bg-orange">Bukti Pendaftaran</a>
                                                                            <?php } ?>
                                                                    </td>
                                                                    <td class="text-center"><?php echo $row2->peringkat;?></td>
                                                                    <td class="text-center"><?php echo $row2->nomor_pendaftaran;?></td>
                                                                    <td class="text-center"><?php echo $row2->nisn;?></td>
                                                                    <td><a href="{$base_url}index.php/Chome/detailpendaftaran?peserta_didik_id=<?php echo $row2->peserta_didik_id;?>" target="_blank"><?php echo $row2->nama;?></a></td>
                                                                    <td class="text-center"><?php echo $row2->jenis_kelamin;?></td>
                                                                    <td class="text-center"><?php echo $row2->create_date;?></td>
                                                                    <td class="text-center"><?php echo $row2->skor;?></td>
                                                                    <td class="text-center"><?php echo $row2->jenis_pilihan;?></td>
                                                                    <td class="text-center"><?php echo $row2->tanggal_daftar_ulang;?></td>
                                                                    <td class="text-center"><?php echo number_format($row2->nilai_kelulusan, 2, '.', ',');?></td>
                                                                    <td class="text-center"><?php echo number_format($row2->nilai_usbn, 2, '.', ',');?></td>
                                                                </tr>
                                                                <?php $i++; endforeach;?>
                                                            </tbody>
                                                        </table>
                                                    <!-- </div> -->
                                                </div>
                                                <?php $i++; endforeach;?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </form>
                        </div>

					</section>
				</div>
			</div>
			{include file='../footer.tpl'}
		</div>
	</body>
</html>

<script type="text/javascript">

    $(document).ready(function() {

        $('select[name="putaran"]').on('change', function() {
            window.location.replace("<?php echo site_url('sekolah/daftarulang'); ?>?tahun_ajaran_id=<?php echo $tahun_ajaran_aktif; ?>&putaran=" + $("#putaran").val());
        });

        $('select[name="tahun_ajaran"]').on('change', function() {
            window.location.replace("<?php echo site_url('sekolah/daftarulang'); ?>?tahun_ajaran_id=" + $("#tahun_ajaran").val());
        });

    });

</script>

<script type="text/javascript">

	$.extend( $.fn.dataTable.defaults, { responsive: true } );

    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().responsive.recalc();
    } );
     
	// Tabel
	$(document).ready(function() {
		<?php foreach($daftarpenerapan->getResult() as $row):?>
		$('#t<?php echo $row->penerapan_id;?>').dataTable({
			"responsive": true,
			"pageLength": 50,
			"lengthMenu": [ [50, 100, 200, -1], [50, 100, 200, "All"] ],
			"paging": true,
			"pagingType": "numbers",
			"dom": 'Bfrtpil',
			"buttons": [
				'copyHtml5',
				'excelHtml5',
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'A4',
					exportOptions: {
						columns: [ 1,2,3,4,5,6,7,8,9,10,11 ]
					}
				},
				{
					extend: 'print',
					orientation: 'landscape',
					pageSize: 'A4',
					exportOptions: {
						columns: [ 1,2,3,4,5,6,7,8,9,10,11 ]
					}
				},
			],
			"language": {
				"sProcessing":   "Sedang proses...",
				"sLengthMenu":   "Tampilan _MENU_ entri",
				"sZeroRecords":  "Tidak ditemukan data yang sesuai",
				"sInfo":         "Tampilan _START_ - _END_ dari _TOTAL_ entri",
				"sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 entri",
				"sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
				"sInfoPostFix":  "",
				"sSearch":       "Cari:",
				"sUrl":          "",
				"oPaginate": {
					"sFirst":    "Awal",
					"sPrevious": "Balik",
					"sNext":     "Lanjut",
					"sLast":     "Akhir"
				}
			},		
			"columnDefs": [
				{
					// The `data` parameter refers to the data for the cell (defined by the
					// `data` option, which defaults to the column being worked with, in
					// this case `data: 0`.
					"render": function ( data, type, row ) {
						if (type=='display') {
							return $.fn.dataTable.render.number(',', '.', 2, '').display(data);
						}
						return data;
					},
					"targets": [10,11]
				},
			],
			order: [ [9, 'asc'] ],
 		});
		<?php endforeach;?>
	});

</script>