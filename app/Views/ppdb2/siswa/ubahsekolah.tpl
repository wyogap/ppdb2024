<?php 
	$kode_zona = "";
	$lintang_rumah = 0;
	$bujur_rumah = 0;
	foreach($profilsiswa->getResult() as $row) {
		$kode_zona = $row->kode_kecamatan;
		$lintang_rumah = $row->lintang;
		$bujur_rumah = $row->bujur;
	}

	$jalurid_dalam_zonasi = 0;
	$namajalur_dalam_zonasi = "";
	$zona_eksklusi = array();

	if ($satu_zonasi_satu_jalur==1) {
		foreach($pendaftaran_dalam_zonasi->getResult() as $row) {
			$jalurid_dalam_zonasi = $row->jalur_id;
			$namajalur_dalam_zonasi = $row->jalur;
		}

		foreach($pendaftaran_dalam_zonasi->getResult() as $row) {
			if ($row->jalur_id != $jalurid) {
				array_push($zona_eksklusi,$row->kode_zona);
			}
		}

		foreach($pendaftaran_luar_zonasi->getResult() as $row) {
			if ($row->jalur_id != $jalurid) {
				array_push($zona_eksklusi,$row->kode_zona);
			}
		}
	}

	$lintang_sekolah = 0;
	$bujur_sekolah = 0;
	$nama_sekolah = '';
	foreach($detailpilihan->getResult() as $row) {
		$lintang_sekolah = $row->lintang_sekolah;
		$bujur_sekolah = $row->bujur_sekolah;
		$nama_sekolah = "(" .$row->npsn. ") " .$row->sekolah;
	}
?>

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
							<i class="glyphicon glyphicon-edit"></i> Ubah Pilihan Sekolah
						</h1>
						<ol class="breadcrumb">
							<li class="active"><a href="<?php echo site_url('siswa/pendaftaran');?>"><i class="glyphicon glyphicon-remove"></i> Batal </a></li>
						</ol>

					</section>
					<section class="content">
					    <?php if ($maxubahsekolah > $ubahsekolahsiswa) { ?>
                            <div class="alert alert-danger alert-dismissable">
                                <i class="icon glyphicon glyphicon-info-sign"></i>
                                Anda hanya bisa melakukan perubahan <b>"Sekolah"</b> sebanyak <b><?php echo $maxubahsekolah-$ubahsekolahsiswa;?> kali</b>.
                            </div>
						<?php } else { ?>
							<div class="alert alert-danger alert-dismissable">
                                <i class="icon glyphicon glyphicon-info-warning"></i>
                                Anda sudah tidak bisa melakukan perubahan <b>"Sekolah"</b> karena sudah melebihi batasan.</b>.
                            </div>
						<?php } ?>

                        <?php if ($jalurswasta != 1 && $satu_zonasi_satu_jalur == 1) { ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <p><i class="icon glyphicon glyphicon-exclamation-sign"></i>Anda hanya bisa mendaftar menggunakan satu jalur pada satu zonasi. Mohon berhati-hati dalam menentukan jalur pendaftaran.</p>             
                            </div>

                            <?php foreach($pendaftaran_dalam_zonasi->getResult() as $row) { ?>
                                <?php if ($jalurid != $row->jalur_id) { ?>
                                <div class="alert alert-warning alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <p><i class="icon glyphicon glyphicon-info-sign"></i>Anda sudah mendaftar menggunakan jalur <?php echo $row->jalur; ?> di dalam zonasi anda. Anda hanya bisa mendaftar dengan jalur <?php echo $namajalur; ?> ke sekolah di luar jalur zonasi anda.</p>             
                                </div>
                                <?php } ?>
                            <?php } ?>

                            <?php foreach($pendaftaran_luar_zonasi->getResult() as $row) { ?>
                                <?php if ($jalurid != $row->jalur_id) { ?>
                                <div class="alert alert-info alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <p><i class="icon glyphicon glyphicon-info-sign"></i>Anda sudah mendaftar menggunakan jalur <?php echo $row->jalur; ?> di zonasi Kec. <?php echo $row->nama; ?>. Anda tidak bisa mendaftar dengan jalur <?php echo $namajalur; ?> ke sekolah di zonasi ini.</p>             
                                </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>

                        <?php if ($daftarpendaftaran->num_rows()>0) { ?>
                            <div class="alert alert-info alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <p>Anda sudah melakukan pendaftaran di sekolah berikut:
                                <ul>
                                    <?php foreach($daftarpendaftaran->getResult() as $row): ?>
                                        <li><?php echo "($row->npsn) $row->sekolah"; ?> </li>
                                    <?php endforeach ?>
                                </ul>
                                </p>
                                <p>Anda tidak bisa melakukan pendaftaran di sekolah yang sama.
                            </div>

                        <?php } ?>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-map-marker"></i>
                                        <h3 class="box-title text-info"><b>Peta Sebaran Sekolah</b></h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div id="peta" style="width: 100%; height: 400px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-search"></i>
                                        <h3 class="box-title text-info"><b>Detail Pilihan</b></h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <?php foreach($detailpilihan->getResult() as $row):?>
                                                <table class="table">
                                                    <tr <?php if($row->jenis_pilihan==0){?>class="bg-red"<?php }else{?>class="bg-warning"<?php }?>>
                                                        <td><b>Jenis Pilihan</b></td>
                                                        <td>:</td>
                                                        <td><?php if($row->jenis_pilihan!=0){?><?php echo $row->label_jenis_pilihan;?><?php }else{?>Belum diperbaharui<?php }?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Sekolah</b></td>
                                                        <td>:</td>
                                                        <td>(<?php echo $row->npsn;?>) <?php echo $row->sekolah;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Jalur</b></td>
                                                        <td>:</td>
                                                        <td><?php echo $row->jalur;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Waktu Pendaftaran</b></td>
                                                        <td>:</td>
                                                        <td><?php echo $row->create_date;?></td>
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
                                                            <span class="pull-right"><a href="javascript:void(0)" target="_blank"><i class="glyphicon glyphicon-search"></i></a></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Status Penerimaan</b></td>
                                                        <td>:</td>
                                                        <?php
                                                            $data['status_penerimaan']=$row->status_penerimaan;
                                                            $data['masuk_jenis_pilihan']=$row->masuk_jenis_pilihan;
                                                            view('dropdown/statuspendaftaran',$data);
                                                        ?>
                                                    </tr>
                                                </table>
                                            <?php endforeach;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box box-solid">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group has-feedback">
                                                    <label for="sekolah_id">Ubah sekolah menjadi:</label>
                                                    <select id="sekolah_id" name="sekolah_id" class="form-control select2" data-validation="required" <?php if($this->session->userdata("tutup_akses")==1||($maxubahsekolah<=$ubahsekolahsiswa)){?>disabled="true"<?php }?>>
                                                        <option value="">-- Silahkan Pilih Sekolah --</option>
                                                        <?php foreach($daftarsekolah->getResult() as $row):
                                                            if ($jalurswasta!=1 && $satu_zonasi_satu_jalur==1 && in_array($row->kode_zona,$zona_eksklusi)) {
                                                                continue;
                                                            }
                                                        ?>
                                                            <option value="<?php echo $row->sekolah_id;?>">(<?php echo $row->npsn;?>) <?php echo $row->nama;?> <?php if($row->jarak!=""){?>(<?php echo round($row->jarak/1000,2);?> Km)<?php }?> <?php if($row->keterangan!=""){?>(<?php echo $row->keterangan;?>)<?php }?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div id="detailsekolah"></div>
                                            </div>
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

    <script>
        //Dropdown Select
        $(function () {
            $(".select2").select2();
        });
        //Event On Change Dropdown
        $(document).ready(function () {
            $('select[name="sekolah_id"]').on('change', function() {
                var data = {sekolah_id:$("#sekolah_id").val(),pendaftaran_id:<?php echo $this->input->get("pendaftaran_id");?>};
                $.ajax({
                    type: "POST",
                    url : "<?php echo site_url('siswa/pendaftaran/detailubahsekolah')?>",
                    data: data,
                    success: function(msg){
                        $('#detailsekolah').html(msg);
                    }
                });
            });
        });
        //Peta

        var greenMarker = new L.Icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var lintang_rumah = <?php echo $lintang_rumah;?>;
        var bujur_rumah = <?php echo $bujur_rumah;?>;
        var lintang_sekolah = <?php echo $lintang_sekolah;?>;
        var bujur_sekolah = <?php echo $bujur_sekolah;?>;
        var nama_sekolah = "<?php echo $nama_sekolah;?>";

        var lintang_aktif = <?php echo ($lintang_rumah!=0) ? $lintang_rumah : $lintang_aktif ;?>;
        var bujur_aktif = <?php echo ($bujur_rumah!=0) ? $bujur_rumah : $bujur_aktif ;?>;

        var map = L.map('peta',{zoomControl:false}).setView([lintang_aktif,bujur_aktif],16);

        var streetview = L.tileLayer(
            '<?php echo $servis_peta_aktif;?>',{maxZoom: 18,attribution: 'PPDB <?php echo $wilayah_aktif;?>',id: 'mapbox.streets'}
        );
        map.addLayer(streetview);

        var rumahsiswa = null;
        if (lintang_rumah!=0 && bujur_rumah!=0) {
            rumahsiswa = L.marker([lintang_rumah,bujur_rumah], {icon: greenMarker}).bindPopup("Lokasi Rumah").openPopup();
            map.addLayer(rumahsiswa);
        }

        if (lintang_sekolah!=0 && bujur_sekolah!=0) {
            L.marker([lintang_sekolah,bujur_sekolah]).addTo(map).bindPopup(nama_sekolah).openPopup();
        }

        new L.Control.Zoom({position:'bottomright'}).addTo(map);
        
        var markers = L.layerGroup();
        map.addLayer(markers);
        
        var bounds = [];

        bounds.push([lintang_rumah,bujur_rumah]);
        bounds.push([lintang_sekolah,bujur_sekolah]);

        <?php foreach($daftarsekolah->getResult() as $row):?>
            L.marker([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>]).addTo(map).bindPopup("(<?php echo $row->npsn;?>) <?php echo $row->nama;?>");
            bounds.push([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>]);
        <?php endforeach;?>
            
        map.fitBounds(bounds);
        
        new L.Control.Zoom({position:'bottomright'}).addTo(map);
    </script>

</html>
