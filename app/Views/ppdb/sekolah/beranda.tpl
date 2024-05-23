<style>
    /* .leaflet-bottom {
        z-index: 1 !important;
    } */
    
    .leaflet-control-attribution.leaflet-control {
        margin-right: 32px;
    }

</style>

<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
		<div class="card box-solid">
			<div class="card-header with-border">
				<h3 class="box-title"><b>Profil Sekolah</b></h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="table table-striped" style="margin-bottom: 0px;">
							<tr>
								<td><b>NPSN</b></td>
								<td>:</td>
								<td>{$profil.npsn}</td>
							</tr>
							<tr>
								<td><b>Nama</b></td>
								<td>:</td>
								<td>{$profil.nama}<span class="pull-right"><a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/{$profil.dapodik_id}" target="_blank" class="btn btn-xs btn-primary">Profil Sekolah Kita</a></span></td>
							</tr>
							<tr>
								<td><b>Jenjang</b></td>
								<td>:</td>
								<td>{$profil.bentuk_pendidikan}</td>
							</tr>
							<tr>
								<td><b>Status Sekolah</b></td>
								<td>:</td>
								<td>{if $profil.status=='N'}NEGERI{else}SWASTA{/if}</td>
							</tr>
							<tr>
								<td><b>Inklusi</b></td>
								<td>:</td>
								<td>{if $profil.inklusi==0}TIDAK{else}YA{/if}</td>
							</tr>
							<tr>
								<td><b>Alamat</b></td>
								<td>:</td>
								<td>{$profil.alamat_jalan}</td>
							</tr>
							<tr>
								<td><b>Desa/Kelurahan</b></td>
								<td>:</td>
								<td>{$profil.desa_kelurahan}</td>
							</tr>
							<tr>
								<td><b>Kecamatan</b></td>
								<td>:</td>
								<td>{$profil.kecamatan}</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
		<div class="card box-solid">
			<div class="card-header with-border">
				<h3 class="box-title text-info"><b>Daftar Kuota</b></h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="table table-striped" style="margin-top: -8px;">
                            <thead>
							<tr>
								<th class="text-center">Jalur</th>
								<th class="text-center">Kuota</th>
							</tr>
                            </thead>
                            <tbody>
                            {assign var="totalkuota" value=0}
                            {foreach $daftarkuota as $row}
							<tr>
								<td><b>{$row.penerapan}</b></td>
								<td class="text-end">{$row.kuota}</td>
							</tr>
                            {$totalkuota = $totalkuota+$row.kuota}
                            {/foreach}
                            <tbody>
                            <tfoot>
							<tr>
								<td class="text-end"><b>Total Kuota</b></td>
								<td class="text-end">{$totalkuota}</td>
							</tr>
                            </tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card box-solid">
			<div class="card-header with-border">
				<h3 class="box-title text-info"><b>Lokasi Sekolah</b></h3>
			</div>
			<div class="card-body" style="padding: 0px;">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="peta" style="width: 100%; height: 400px; z-index: 1; border-bottom-left-radius: 32px; border-bottom-right-radius: 32px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>