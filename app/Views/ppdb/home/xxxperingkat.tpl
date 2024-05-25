<!DOCTYPE html>
<html>
    {include file='../header.tpl'}

    <style>
        .dataTable .sorting_1 {
            background-color: transparent !important;
        }
    </style>

	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
            {include file='../navigation.tpl'}
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<h1 class="text-white">
							<i class="glyphicon glyphicon-th-list"></i> Peringkat<small>Pendaftaran</small>
						</h1>
					</section>
					<section class="content">
                        {if empty($sekolah_id)}
                        <div class="box box-primary">
                            <div class="box-body text-center">
                            Tidak ada sekolah yang dipilih
                            </div>
                        </div>
                        {else}
                        <div class="row">
                            {if $show_profile_sekolah == 1}
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-education"></i>
                                        <h3 class="box-title text-info"><b>({$profilsekolah.npsn}) {$profilsekolah.nama}</b></h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <table class="table table-striped">
                                                    <tr>
                                                        <td><b>Jenjang</b></td>
                                                        <td>:</td>
                                                        <td>{$profilsekolah.bentuk_pendidikan}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Status Sekolah</b></td>
                                                        <td>:</td>
                                                        <td>{if $profilsekolah.status == 'N'}NEGERI{else}SWASTA{/if}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Alamat</b></td>
                                                        <td>:</td>
                                                        <td>{$profilsekolah.alamat_jalan}, {$profilsekolah.desa_kelurahan}, {$profilsekolah.kecamatan}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {/if}

                            {if !$final_ranking|default: FALSE}
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="alert alert-info alert-dismissable">
                                <i class="icon glyphicon glyphicon-info-sign"></i>
                                Perhitungan peringkat dilakukan oleh system secara otomotis pada: <b><span id="last_execution_date">{$last_execution_date}</span></b>. 
                                {if $cek_waktupendaftaran==1 && !empty($next_execution_date)}
                                    Perhitungan selanjutnya pada: <b><span id="next_execution_date">{$next_execution_date}</span></b>
                                {/if}
                            </div>	
                            </div>
                            {/if}

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="nav-tabs-custom" id="tabs">
                                    <ul class="nav nav-pills nav-justified" id="tabNames">
                                        {if $show_all_pendaftar|default: FALSE}
                                        <li class="active"><a href="#daftarpendaftar" data-toggle="tab">Pendaftar<br><small>&nbsp;</small></a></li>
                                        {/if}

                                        {assign var="idx" value=0}
                                        {foreach $daftarpenerapan as $row}
                                        {if $row.jalur_id == $smarty.const.JALURID_INKLUSI && !$inklusi}{continue}{/if}
                                        <li {if $idx==0 && empty($show_all_pendaftar)}class="active"{/if}>
                                            <a href="#p{$row.penerapan_id}" data-toggle="tab">
                                                <span {if !empty($row.tooltip)}data-toggle="tooltip" title="{$row.tooltip}" data-placement="top" data-html="true"{/if}>{$row.jalur}</span><br>
                                                <small class="label bg-blue" data-toggle="tooltip" title="Jumlah Kuota" data-placement="bottom">{$row.kuota}</small>
                                                {if $row.tambahan_kuota>0}<small class='label bg-yellow' data-toggle="tooltip" title="Tambahan Kuota" data-placement="bottom">{$row.tambahan_kuota}</small>{/if}
                                                <small class="label bg-green" data-toggle="tooltip" title="Jumlah Diterima" data-placement="bottom">{$row.diterima}</small>
                                                <small class="label bg-gray" data-toggle="tooltip" title="Total Pendaftar" data-placement="bottom">{$row.total_pendaftar}</small>
                                            </a>
                                        </li>
                                        {$idx = $idx+1}
                                        {/foreach}
                                    </ul>
                                    <div class="tab-content">
                                        {if $show_all_pendaftar|default: FALSE}
                                        <div class="tab-pane active" id="daftarpendaftar">
                                            <!-- <div class="table-responsive"> -->
                                            <table class="display" id="tdaftarpendaftar" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" data-priority="1">No</th>
                                                        <th class="text-center" data-priority="4">Nomor Pendaftaran</th>
                                                        <th class="text-center">NISN</th>
                                                        <th class="text-center" data-priority="2">Nama</th>
                                                        <th class="text-center">Sekolah Asal</th>
                                                        <th class="text-center" data-priority="3">Jalur</th>
                                                        <th class="text-center">Jenis Pilihan</th>
                                                        <th class="text-center">Skor</th>
                                                        <th class="text-center">Tanggal Pembukuan</th>
                                                        <th class="text-center">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {foreach $semuapendaftar as $row}
                                                    <tr>
                                                        <td class="text-center">{$row.idx}</td>
                                                        <td class="text-center">{$row.nomor_pendaftaran}</td>
                                                        <td class="text-center">{$row.nisn}</td>
                                                        <td><a href="{$base_url}home/detailpendaftaran?peserta_didik_id={$row.peserta_didik_id}" target="_blank">{$row.nama}</a></td>
                                                        <td>{$row.sekolah_asal}</td>
                                                        <td class="text-center">{$row.jalur}</td>
                                                        <td class="text-center">{$row.label_jenis_pilihan}</td>
                                                        <td class="text-end">{$row.skor}</td>
                                                        <td class="text-center">{$row.created_on}</td>
                                                        {*
                                                            status_penerimaan == 0  => belum diperingkat
                                                            status_penerimaan == 1  => masuk kuota
                                                            status_penerimaan == 2  => tidak masuk kuota
                                                            status_penerimaan == 3  => daftar tunggu
                                                            status_penerimaan == 4  => diterima pilihan1
                                                        *}
                                                        {if $final_ranking|default: FALSE}
                                                            <td class="text-center 
                                                                {if $row.status_penerimaan_final==1 || $row.status_penerimaan_final==3}bg-green
                                                                {elseif $row.status_penerimaan_final==4}bg-gray
                                                                {elseif $row.status_penerimaan_final==2 && $row.status_penerimaan!=2 && $row.masuk_jenis_pilihan != 0}bg-gray
                                                                {elseif $row.status_penerimaan_final==2}bg-red
                                                                {elseif $row.status_penerimaan_final==0}bg-gray
                                                                {else}bg-gray{/if}
                                                            ">
                                                                {if $row.status_penerimaan_final==1 || $row.status_penerimaan_final==3}Diterima
                                                                {elseif $row.status_penerimaan_final==4}{$row.label_masuk_pilihan}
                                                                {elseif $row.status_penerimaan_final==2 && $row.status_penerimaan!=2 && $row.masuk_jenis_pilihan!=0 && $row.masuk_jenis_pilihan!=$row.jenis_pilihan}{$row.label_masuk_pilihan}
                                                                {elseif $row.status_penerimaan_final==2}Tidak Diterima
                                                                {elseif $row.kelengkapan_berkas==2}Berkas Tidak Lengkap
                                                                {else}Berkas Tidak Lengkap
                                                                {/if}
                                                            </td>
                                                        {else}
                                                            <td class="text-center 
                                                                {if $row.status_penerimaan_final==1 || $row.status_penerimaan_final==3}bg-green
                                                                {elseif $row.status_penerimaan_final==4}bg-gray
                                                                {elseif $row.status_penerimaan_final==2 && $row.status_penerimaan!=2 && $row.masuk_jenis_pilihan != 0}bg-gray
                                                                {elseif $row.status_penerimaan_final==2}bg-red
                                                                {elseif $row.status_penerimaan_final==0}bg-gray
                                                                {else}bg-gray{/if}
                                                            ">
                                                                {if $row.status_penerimaan_final==1 || $row.status_penerimaan_final==3}Diterima
                                                                {elseif $row.status_penerimaan_final==4}{$row.label_masuk_pilihan}
                                                                {elseif $row.status_penerimaan_final==2 && $row.status_penerimaan!=2 && $row.masuk_jenis_pilihan!=0 && $row.masuk_jenis_pilihan!=$row.jenis_pilihan}{$row.label_masuk_pilihan}
                                                                {elseif $row.status_penerimaan_final==2}Tidak Diterima
                                                                {elseif $row.kelengkapan_berkas==2}Berkas Belum Lengkap
                                                                {else}Belum Diperingkat
                                                                {/if}
                                                            </td>
                                                        {/if}
                                                    </tr>
                                                    {/foreach}
                                                </tbody>
                                            </table>
                                            <!-- </div> -->
                                        </div>
                                        {/if}
                                        {assign var="idx" value=0}
                                        {foreach $daftarpenerapan as $row}
                                        {if $row.jalur_id == $smarty.const.JALURID_INKLUSI && !$inklusi}{continue}{/if}
                                        <div class="tab-pane {if $idx==0 && empty($show_all_pendaftar)}active{/if}" id="p{$row.penerapan_id}">
                                            <!-- <div class="table-responsive"> -->
                                                <table class="display" id="t{$row.penerapan_id}" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" data-priority="1">#</th>
                                                            <th class="text-center" data-priority="1">Peringkat</th>
                                                            <th class="text-center" data-priority="10001">Nomor Pendaftaran</th>
                                                            <th class="text-center">NISN</th>
                                                            <th class="text-center" data-priority="2">Nama</th>
                                                            <th class="text-center">Jenis Pilihan</th>
                                                            <th class="text-center">Tanggal Pembukuan</th>
                                                            <th class="text-center">Skor</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {foreach $pendaftar[$row.penerapan_id] as $row2}
                                                        <tr>
                                                            <td class="text-center">{$row2.idx}</td>
                                                            {if $final_ranking|default: FALSE}
                                                                {if ($row2.status_penerimaan==4)}
                                                                <td class="text-center bg-gray">{$row2.label_masuk_pilihan}</td>
                                                                {elseif ($row2.masuk_jenis_pilihan != $row2.jenis_pilihan && $row2.masuk_jenis_pilihan != 0 && $row2.status_penerimaan != 2)}
                                                                <td class="text-center bg-gray">{$row2.label_masuk_pilihan}</td>
                                                                {elseif ($row2.kelengkapan_berkas==2)}
                                                                <td class="text-center bg-gray">Berkas Tidak Lengkap</td>
                                                                {elseif ($row2.status_penerimaan==0)}
                                                                <td class="text-center bg-gray">Berkas Tidak Lengkap</td>
                                                                {elseif ($row2.status_penerimaan==2)}
                                                                <td class="text-center bg-red">Tidak Diterima</td>
                                                                {elseif ($row2.status_penerimaan==3)}
                                                                <td class="text-center bg-green">{$row2.peringkat_final}</td>
                                                                {else}
                                                                <td class="text-center bg-green">{$row2.peringkat_final}</td>
                                                                {/if}
                                                            {else}
                                                                {if ($row2.status_penerimaan==4)}
                                                                <td class="text-center bg-gray">{$row2.label_masuk_pilihan}</td>
                                                                {elseif ($row2.masuk_jenis_pilihan != $row2.jenis_pilihan && $row2.masuk_jenis_pilihan != 0 && $row2.status_penerimaan != 2)}
                                                                <td class="text-center bg-gray">{$row2.label_masuk_pilihan}</td>
                                                                {elseif ($row2.kelengkapan_berkas==2)}
                                                                <td class="text-center bg-gray">Berkas Belum Lengkap</td>
                                                                {elseif ($row2.status_penerimaan==0)}
                                                                <td class="text-center bg-gray">Belum Diperingkat</td>
                                                                {elseif ($row2.status_penerimaan==2)}
                                                                <td class="text-center bg-red">Tidak Diterima</td>
                                                                {elseif ($row2.status_penerimaan==3)}
                                                                <td class="text-center bg-yellow">{$row2.peringkat_final}</td>
                                                                {else}
                                                                <td class="text-center bg-green">{$row2.peringkat_final}</td>
                                                                {/if}
                                                            {/if}
                                                            <td class="text-center">{$row2.nomor_pendaftaran}</td>
                                                            <td class="text-center">{$row2.nisn}</td>
                                                            <td>
                                                            <a href="{$base_url}home/detailpendaftaran?peserta_didik_id={$row2.peserta_didik_id}" target="_blank">
                                                                {$row2.nama}
                                                            </a>
                                                            </td>
                                                            <td class="text-center hala">{$row2.label_jenis_pilihan}</td>
                                                            <td class="text-center">{$row2.created_on}</td>
                                                            <td class="text-end">{$row2.skor}</td>
                                                        </tr>
                                                        {/foreach}

                                                    </tbody>
                                                </table>
                                            <!-- </div> -->
                                        </div>
                                        {/foreach}
                                    </div>
                                </div>
                            </div>
                        </div>
                        {/if}
					</section>
				</div>
			</div>
			{include file='../footer.tpl'}
		</div>
	</body>

    <script type="text/javascript">
        $.extend( $.fn.dataTable.defaults, { responsive: true } );

        $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
            $.fn.dataTable.tables( { visible: true, api: true } ).columns.adjust().responsive.recalc();
        } );
        
        // Tabel
        $(document).ready(function() {
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })

            {foreach $daftarpenerapan as $row}
            $('#t{$row.penerapan_id}').dataTable({
                "responsive": true,
                "pageLength": 25,
                "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
                "paging": true,
                "pagingType": "numbers",
                "dom": 'Bfrtpil',
                "buttons": [
                    'copyHtml5',
                    'excelHtml5',
                    'pdfHtml5',
                    'print'
                ],
                "language": {
                    "sProcessing":   "Sedang proses...",
                    "sLengthMenu":   "Tampilan _MENU_ baris",
                    "sZeroRecords":  "Tidak ditemukan data yang sesuai",
                    "sInfo":         "Tampilan _START_ - _END_ dari _TOTAL_ baris",
                    "sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 baris",
                    "sInfoFiltered": "(disaring dari _MAX_ baris keseluruhan)",
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
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                } ],
                "order": [[ 7, 'desc' ]]
            });
            {/foreach}

            $('#tdaftarpendaftar').dataTable({
                "responsive": true,
                "pageLength": 25,
                "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
                "paging": true,
                "pagingType": "numbers",
                "dom": 'Bfrtpil',
                "buttons": [
                    'copyHtml5',
                    'excelHtml5',
                    'pdfHtml5',
                    'print'
                ],
                "language": {
                    "sProcessing":   "Sedang proses...",
                    "sLengthMenu":   "Tampilan _MENU_ baris",
                    "sZeroRecords":  "Tidak ditemukan data yang sesuai",
                    "sInfo":         "Tampilan _START_ - _END_ dari _TOTAL_ baris",
                    "sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 baris",
                    "sInfoFiltered": "(disaring dari _MAX_ baris keseluruhan)",
                    "sInfoPostFix":  "",
                    "sSearch":       "Cari:",
                    "sUrl":          "",
                    "oPaginate": {
                        "sFirst":    "Awal",
                        "sPrevious": "Balik",
                        "sNext":     "Lanjut",
                        "sLast":     "Akhir"
                    }
                }		
            });
        });

        /* Recalculates the size of the resposive DataTable */
        function recalculateDataTableResponsiveSize() {
            $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust()
            .responsive.recalc(); 
        }
        
        // $('#tabs').tabs({
        // 	activate: recalculateDataTableResponsiveSize,
        // 	create: recalculateDataTableResponsiveSize
        // });

    </script>
</html>
