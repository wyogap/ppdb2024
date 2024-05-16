<!DOCTYPE html>
<?php 
		$nama = "";
		$nisn = "";
		foreach($profilsiswa->getResult() as $row):
			$nama = $row->nama;
			$nisn = $row->nisn;
		endforeach;
	?>

<html>
    {include file='../header.tpl'}
	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
            {include file='../navigation.tpl'}
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<h1 class="text-white">
							<i class="glyphicon glyphicon-user"></i> <?php if($nisn!=""){?>(<?php echo $nisn;?>) <?php }?><?php echo $nama;?></small>
						</h1>
						<ol class="breadcrumb">
							<li class="active"><a href="#" onclick="window.history.back();"><i class="glyphicon glyphicon-remove"></i> Batal </a></li>
						</ol>
					</section>
					<section class="content">
						
                        <div class="row">
                            <?php 
                                $peserta_didik_id = "";
                                foreach($profilsiswa->getResult() as $row):
                            ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-user"></i>
                                        <h3 class="box-title"><b>Data Identitas</b></h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <table class="table table-striped" style="margin-bottom: 0px;">
                                                    <tr>
                                                        <td><b>Sekolah Asal</b></td>
                                                        <td>:</td>
                                                        <?php if($row->sekolah_id!=""){?>
                                                            <td>(<b><?php echo $row->npsn;?></b>) <a href="http://sekolah.data.kemdikbud.go.id/index.php/chome/profil/<?php echo $row->sekolah_id;?>" target="_blank"><?php echo $row->sekolah;?></a></td>
                                                        <?php }else{?>
                                                            <td><p>Tidak bersekolah sebelumnya.</p></td>
                                                        <?php }?>
                                                    </tr>
                                                    <tr>
                                                        <td><b>NIK</b></td>
                                                        <td>:</td>
                                                        <td><?php echo $row->nik;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Jenis Kelamin</b></td>
                                                        <td>:</td>
                                                        <td><?php if($row->jenis_kelamin=="L"){echo "Laki-laki";}else{echo "Perempuan";}?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Nama Ibu Kandung</b></td>
                                                        <td>:</td>
                                                        <td><?php echo $row->nama_ibu_kandung;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Alamat</b></td>
                                                        <td>:</td>
                                                        <td><?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Sumber Data</b></td>
                                                        <td>:</td>
                                                        <td><?php if ($row->asal_data == 0) { echo 'DAPODIK'; } else if ($row->asal_data == 1) { echo "REGISTRASI"; } else if ($row->asal_data == 5) { echo "API Tarik Data"; } else { echo "Lain"; }?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <form role="form" enctype="multipart/form-data" id="prosesperubahandatasiswa" action="{$base_url}index.php/dapodik/ubahdata/simpan?redirect=<?php echo $redirect; ?>" method="post">
                                <input type="hidden" id="peserta_didik_id" name="peserta_didik_id" value="<?php echo $row->peserta_didik_id;?>">
                                <input type="hidden" id="kebutuhan_khusus_lama" name="kebutuhan_khusus_lama" value="<?php echo $row->kebutuhan_khusus;?>">
                                <input type="hidden" id="jenis_kelamin_lama" name="jenis_kelamin_lama" value="<?php echo $row->jenis_kelamin;?>">
                                <input type="hidden" id="tempat_lahir_lama" name="tempat_lahir_lama" value="<?php echo $row->tempat_lahir;?>">
                                <input type="hidden" id="rt_lama" name="rt_lama" value="<?php echo $row->rt;?>">
                                <input type="hidden" id="rw_lama" name="rw_lama" value="<?php echo $row->rw;?>">
                                <input type="hidden" id="nama_lama" name="nama_lama" value="<?php echo $row->nama;?>">
                                <input type="hidden" id="nama_ibu_lama" name="nama_ibu_lama" value="<?php echo $row->nama_ibu_kandung;?>">
                                <input type="hidden" id="nama_ayah_lama" name="nama_ayah_lama" value="<?php echo $row->nama_ayah;?>">
                                <input type="hidden" id="approval" name="approval" value="1">

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="box box-solid">
                                        <div class="box-header with-border">
                                            <i class="glyphicon glyphicon-edit text-info"></i>
                                            <h3 class="box-title text-info"><b>Perubahan Data</b></h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="form-group has-feedback">
                                                            <label for="nama">Nama</label>
                                                            <input id="nama" name="nama" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required" placeholder="Nama" value="<?php echo $row->nama;?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group has-feedback">
                                                            <label for="nisn">NISN</label>
                                                            <input id="nisn" name="nisn" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required" placeholder="NISN" minlength="10" maxlength="10" value="<?php echo $row->nisn;?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group has-feedback">
                                                            <label for="nik">NIK</label>
                                                            <input id="nik" name="nik" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required" placeholder="NIK" minlength="16" maxlength="16" value="<?php echo $row->nik;?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group has-feedback">
                                                            <label for="jenis_kelamin">Jenis Kelamin</label>
                                                            <select id="jenis_kelamin" name="jenis_kelamin" class="form-control select2" style="width:100%;" data-validation="required">
                                                                <option value="">--</option>
                                                                <option value="L" <?php if($row->jenis_kelamin=="L"){?>selected="true"<?php }?>>Laki-laki</option>
                                                                <option value="P" <?php if($row->jenis_kelamin=="P"){?>selected="true"<?php }?>>Perempuan</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group has-feedback">
                                                            <label for="kebutuhan_khusus">Kebutuhan Khusus</label>
                                                            <select id="kebutuhan_khusus" name="kebutuhan_khusus" class="form-control select2" style="width:100%;" data-validation="required">
                                                                <option value="Tidak ada" <?php if($row->kebutuhan_khusus=="Tidak ada"){?>selected="true"<?php }?>>Tidak ada</option>
                                                                <option value="A - Tuna netra" <?php if($row->kebutuhan_khusus=="A - Tuna netra"){?>selected="true"<?php }?>>A - Tuna netra</option>
                                                                <option value="B - Tuna rungu" <?php if($row->kebutuhan_khusus=="B - Tuna rungu"){?>selected="true"<?php }?>>B - Tuna rungu</option>
                                                                <option value="C - Tuna grahita ringan" <?php if($row->kebutuhan_khusus=="C - Tuna grahita ringan"){?>selected="true"<?php }?>>C - Tuna grahita ringan</option>
                                                                <option value="C1 - Tuna grahita sedang" <?php if($row->kebutuhan_khusus=="C1 - Tuna grahita sedang"){?>selected="true"<?php }?>>C1 - Tuna grahita sedang</option>
                                                                <option value="D - Tuna daksa ringan" <?php if($row->kebutuhan_khusus=="D - Tuna daksa ringan"){?>selected="true"<?php }?>>D - Tuna daksa ringan</option>
                                                                <option value="D1 - Tuna daksa sedang" <?php if($row->kebutuhan_khusus=="D1 - Tuna daksa sedang"){?>selected="true"<?php }?>>D1 - Tuna daksa sedang</option>
                                                                <option value="E - Tuna laras" <?php if($row->kebutuhan_khusus=="E - Tuna laras"){?>selected="true"<?php }?>>E - Tuna laras</option>
                                                                <option value="F - Tuna wicara" <?php if($row->kebutuhan_khusus=="F - Tuna wicara"){?>selected="true"<?php }?>>F - Tuna wicara</option>
                                                                <option value="K - Kesulitan Belajar" <?php if($row->kebutuhan_khusus=="K - Kesulitan Belajar"){?>selected="true"<?php }?>>K - Kesulitan Belajar</option>
                                                                <option value="P - Down Syndrome" <?php if($row->kebutuhan_khusus=="P - Down Syndrome"){?>selected="true"<?php }?>>P - Down Syndrome</option>
                                                                <option value="Q - Autis" <?php if($row->kebutuhan_khusus=="Q - Autis"){?>selected="true"<?php }?>>Q - Autis</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group has-feedback">
                                                            <label for="tempat_lahir">Tempat Lahir</label>
                                                            <input id="tempat_lahir" name="tempat_lahir" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required" value="<?php echo $row->tempat_lahir;?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group has-feedback">
                                                            <label for="tanggal_lahir">Tanggal Lahir</label>
                                                            <input id="tanggal_lahir" name="tanggal_lahir" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required" value="<?php echo $row->tanggal_lahir;?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group has-feedback">
                                                            <label for="nama_ibu">Nama Ibu Kandung</label>
                                                            <input id="nama_ibu" name="nama_ibu" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required" value="<?php echo $row->nama_ibu_kandung;?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group has-feedback">
                                                            <label for="nama_ayah">Nama Ayah</label>
                                                            <input id="nama_ayah" name="nama_ayah" type="text" class="form-control" aria-describedby="basic-addon1" data-validation="required" value="<?php echo $row->nama_ayah;?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group has-feedback">
                                                            <label for="kode_kabupaten">Kabupaten/Kota</label>
                                                            <select id="kode_kabupaten" name="kode_kabupaten" class="form-control select2" data-validation="required">
                                                                <option value="">-- Pilih Kabupaten/Kota --</option>
                                                                <?php foreach($kabupaten->getResult() as $kab):?>
                                                                    <option value="<?php echo $kab->kode_wilayah;?>" <?php if($kab->kode_wilayah==$row->kode_kabupaten){?>selected="true"<?php }?>><?php echo $kab->kabupaten;?> (<?php echo $kab->provinsi;?>)</option>
                                                                <?php endforeach;?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group has-feedback">
                                                            <label for="kode_kecamatan">Kecamatan</label>
                                                            <select id="kode_kecamatan" name="kode_kecamatan" class="form-control select2" data-validation="required">
                                                                <option value="">-- Pilih Kecamatan --</option>
                                                                <?php
                                                                    $kecamatanubahdatasiswa = $this->Mdropdown->tcg_kecamatan($row->kode_kabupaten);
                                                                    foreach($kecamatanubahdatasiswa->getResult() as $kec):
                                                                ?>
                                                                <option value="<?php echo $kec->kode_wilayah;?>" <?php if($kec->kode_wilayah==$row->kode_kecamatan){?>selected="true"<?php }?>><?php echo $kec->nama;?></option>
                                                                <?php endforeach;?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group has-feedback">
                                                            <label for="kode_desa">Desa/Kelurahan</label>
                                                            <select id="kode_desa" name="kode_desa" class="form-control select2" data-validation="required">
                                                                <option value="">-- Pilih Desa/Kelurahan --</option>
                                                                <?php
                                                                    $desaubahdatasiswa = $this->Mdropdown->tcg_desa($row->kode_kecamatan);
                                                                    foreach($desaubahdatasiswa->getResult() as $desa):
                                                                ?>
                                                                <option value="<?php echo $desa->kode_wilayah;?>" <?php if($desa->kode_wilayah==$row->kode_desa){?>selected="true"<?php }?>><?php echo $desa->nama;?></option>
                                                                <?php endforeach;?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group has-feedback">
                                                            <label for="kode_wilayah">Padukuhan</label>
                                                            <select id="kode_wilayah" name="kode_wilayah" class="form-control select2">
                                                                <option value="">-- Pilih Padukuhan --</option>
                                                                <?php
                                                                    $padukuhanubahdatasiswa = $this->Mdropdown->tcg_padukuhan($row->kode_desa);
                                                                    foreach($padukuhanubahdatasiswa->getResult() as $dukuh):
                                                                ?>
                                                                <option value="<?php echo $dukuh->kode_wilayah;?>" <?php if($dukuh->kode_wilayah==$row->kode_padukuhan){?>selected="true"<?php }?>><?php echo $dukuh->nama;?></option>
                                                                <?php endforeach;?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <div class="form-group has-feedback">
                                                            <label for="rt">RT</label>
                                                            <input id="rt" name="rt" type="text" class="form-control" aria-describedby="basic-addon1" placeholder="RT" value="<?php echo $row->rt;?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <div class="form-group has-feedback">
                                                            <label for="rw">RW</label>
                                                            <input id="rw" name="rw" type="text" class="form-control" aria-describedby="basic-addon1" placeholder="RW" value="<?php echo $row->rw;?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="box box-solid">
                                        <div class="box-header with-border">
                                            <i class="glyphicon glyphicon-map-marker"></i>
                                            <h3 class="box-title text-info"><b>Lokasi Rumah</b></h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div id="peta" style="width: 100%; height: 400px;"></div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-info" style="margin-top: 10px; margin-bottom: 10px;">NB : Silahkan klik di peta <b>(<i class="glyphicon glyphicon-map-marker"></i>)</b> untuk perubahan data koordinat lokasi rumah siswa.</div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group has-feedback">
                                                        <label for="lintang">Lintang</label>
                                                        <input type="text" class="form-control" id="lintang" name="lintang" placeholder="Lintang" data-validation="required" value="<?php echo $row->lintang;?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group has-feedback">
                                                        <label for="bujur">Bujur</label>
                                                        <input type="text" class="form-control" id="bujur" name="bujur" placeholder="Bujur" data-validation="required" value="<?php echo $row->bujur;?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-primary btn-flat">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                            </div>
                            <?php endforeach;?>
                        </div>

					</section>
				</div>
			</div>
			{include file='../footer.tpl'}
		</div>
	</body>
</html>

<script>
	//Dropdown Select
	$(function () {
		$(".select2").select2();
	});
	//Datepicker
	$("#tanggal_lahir").datepicker({ 
		format: 'yyyy-mm-dd'
	});
	//Event On Change Dropdown
	$(document).ready(function () {
		$('select[name="kode_kabupaten"]').on('change', function() {
			var data = {kode_wilayah:$("#kode_kabupaten").val()};
			$.ajax({
				type: "POST",
				url : "<?php echo site_url('Cdropdown/kecamatan')?>",
				data: data,
				success: function(msg){
					$('#kode_kecamatan').html(msg);
				}
			});
		});
		$('select[name="kode_kecamatan"]').on('change', function() {
			var data = {kode_wilayah:$(kode_kecamatan).val()};
			$.ajax({
				type: "POST",
				url : "<?php echo site_url('Cdropdown/desa')?>",
				data: data,
				success: function(msg){
					$('#kode_desa').html(msg);
				}
			});
		});
		$('select[name="kode_desa"]').on('change', function() {
			var data = {kode_wilayah:$(kode_desa).val()};
			$.ajax({
				type: "POST",
				url : "<?php echo site_url('Cdropdown/Padukuhan')?>",
				data: data,
				success: function(msg){
					$('#kode_wilayah').html(msg);
				}
			});
		});

		$('#lintang').on('change', onChangeCoordinate);
		$('#bujur').on('change', onChangeCoordinate);

	});
	//Validasi
	//Validasi
	var myLanguage = {
        errorTitle: 'Gagal mengirim data!',
        requiredFields: 'Belum mengisi semua data wajib',
    };
	
	var $messages = $('#error-message-wrapper');
	$.validate({
		language : myLanguage,
		ignore: [],
		modules: 'security',
		errorMessagePosition: "top",
		scrollToTopOnError: true,
		validateHiddenInputs: true
	});
	
	//Peta
	<?php foreach($profilsiswa->getResult() as $row):?>

	var map;
	<?php if (!empty($row->lintang) && !empty($row->bujur)) { ?>
		map = L.map('peta',{zoomControl:false}).setView([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>],15);
	<?php } else { ?>
		map = L.map('peta',{zoomControl:false}).setView([<?php echo $lintang_aktif;?>,<?php echo $bujur_aktif;?>],10);
	<?php } ?>

	L.tileLayer(
		'<?php echo $servis_peta_aktif;?>',{maxZoom: 18,attribution: 'PPDB <?php echo $wilayah_aktif;?>',id: 'mapbox.streets'}
	).addTo(map);

    var streetmap   = L.tileLayer('<?php echo $streetmap_aktif;?>', {id: 'mapbox.light', attribution: ''}),
        satelitemap  = L.tileLayer('<?php echo $satelitemap_aktif;?>', {id: 'mapbox.streets',   attribution: ''});
    var baseLayers = {
        "Satelite": satelitemap,
        "Streets": streetmap
    };
    var overlays = {};
	L.control.layers(baseLayers,overlays).addTo(map);

	var layerGroup = L.layerGroup().addTo(map);
	function onMapClick(e) {
		if (confirm("Ubah lokasi rumah siswa?")) {
			layerGroup.clearLayers();
			var lintang = e.latlng.lat;
			var bujur = e.latlng.lng;
			new L.marker(e.latlng).addTo(layerGroup).bindPopup("Lokasi :<br>"+lintang+" , "+bujur).openPopup();
			document.getElementById("lintang").value=lintang;
			document.getElementById("bujur").value=bujur;
		}
	}
	map.on('click', onMapClick);
	var searchControl = L.esri.Geocoding.geosearch().addTo(map);
	searchControl.on('layerGroup', function(data){
		layerGroup.clearLayers();
	});

	<?php if (!empty($row->lintang) && !empty($row->bujur)) { ?>
		L.marker([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>]).addTo(layerGroup).bindPopup("<?php echo $row->desa_kelurahan;?>, <?php echo $row->kecamatan;?>, <?php echo $row->kabupaten;?>, <?php echo $row->provinsi;?>").openPopup();
	<?php } ?>

	new L.control.fullscreen({position:'bottomleft'}).addTo(map);
	new L.Control.Zoom({position:'bottomright'}).addTo(map);

	new L.Control.EasyButton( '<span class="map-button">&curren;</span>', function(){
				map.setView([<?php echo $lintang_aktif;?>,<?php echo $bujur_aktif;?>],10);;
			}, {position: 'topleft'}).addTo(map);

	function onChangeCoordinate() {
		layerGroup.clearLayers();
		var lintang = document.getElementById("lintang").value;
		var bujur = document.getElementById("bujur").value;
		new L.marker([lintang,bujur]).addTo(layerGroup).bindPopup("Lokasi :<br>"+lintang+" , "+bujur).openPopup();
	}

	<?php endforeach;?>
</script>
