
{if $impersonasi_sekolah|default: FALSE} 
<div class="row page-titles">
    <ol class="breadcrumb">
        {if $impersonasi_sekolah|default: FALSE} 
        <li class="breadcrumb-item active">[{$profilsekolah['nama']}]</li>
        {/if}
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Sekolah</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Ubah Profil</a></li>
    </ol>
</div>
{/if}

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card box-solid">
				<div class="card-header with-border">
					<h3 class="box-title"><b>Perubahan Data</b></h3>
				</div>
				<div class="card-body">
					<div class="row" style='margin-bottom: 18px;'>
						<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> -->
						<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="nisn">NPSN</label>
									<input id="nisn" name="nisn" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required" placeholder="NPSN" minlength="10" maxlength="10" value="{$profil.npsn}" disabled>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="inklusi">Inklusi</label>
									<select id="inklusi" name="inklusi" class="form-control select2" data-validation="required">
										<option value="0" {if (0==$profil.inklusi)}selected="true"{/if}>Tidak</option>
										<option value="1" {if (1==$profil.inklusi)}selected="true"{/if}>Ya</option>
									</select>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group has-feedback">
									<label for="alamat">Alamat</label>
									<input id="alamat_jalan" name="alamat_jalan" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required" value="{$profil.alamat_jalan}">
								</div>
							</div>
						<!-- </div> -->
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div id="peta-profil" style="width: 100%; height: 400px; z-index: 1;"></div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-info" style="margin-top: 10px; margin-bottom: 10px;">NB : Silahkan klik di peta <b>(<i class="fa fa-map-marker"></i>)</b> untuk perubahan data koordinat lokasi sekolah.</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group has-feedback">
								<label for="lintang">Lintang</label>
								<input type="text" class="form-control" id="lintang" name="lintang" placeholder="Lintang" data-validation="required" value="{$profil.lintang}">
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group has-feedback">
								<label for="bujur">Bujur</label>
								<input type="text" class="form-control" id="bujur" name="bujur" placeholder="Bujur" data-validation="required" value="{$profil.bujur}">
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<button onclick=simpan_profil() class="btn btn-primary btn-flat">Simpan Perubahan</button>
				</div>
			</div>
		</div>