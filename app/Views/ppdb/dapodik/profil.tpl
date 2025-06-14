
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card box-solid">
				<div class="card-header with-border">
					<h3 class="box-title"><b>Profil Pengguna</b></h3>
				</div>
				<div class="card-body">
                    <div class="form-group has-feedback">
                        <div class="row" style='margin-bottom: 18px;'>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label for="user_name">Username</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                <input id="user_name" name="user_name" type="text" class="form-control" aria-describedby="basic-addon1" 
                                data-validation="required" placeholder="Username" minlength="10" maxlength="10" value="{$profil.user_name}" disabled>
                            </div>
                        </div>
                        <div class="row" style='margin-bottom: 18px;'>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label for="nama">Nama Pengguna</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                <input id="nama" name="nama" type="text" class="form-control" aria-describedby="basic-addon1" 
                                data-validation="required" placeholder="" value="{$profil.nama}">
                            </div>
                        </div>
                        <div class="row" style='margin-bottom: 18px;'>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label for="email">Alamat Email</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                <input id="email" name="email" type="text" class="form-control" aria-describedby="basic-addon1" 
                                data-validation="required" placeholder="" value="{$profil.email}">
                            </div>
                        </div>
                        <div class="row" style='margin-bottom: 0px;'>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label for="handphone">Nomor Handphone</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                <input id="handphone" name="handphone" type="text" class="form-control" aria-describedby="basic-addon1" 
                                data-validation="required" placeholder="" minlength="8" maxlength="15" value="{$profil.handphone}">
                            </div>
                        </div>
					</div>
				</div>
				<div class="card-footer">
					<button onclick=simpan_profil() class="btn btn-primary btn-flat">Simpan Perubahan</button>
				</div>
			</div>
		</div>