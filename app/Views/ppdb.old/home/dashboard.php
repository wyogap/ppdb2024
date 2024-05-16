<div class="container">
    <div class="title-call-action another text-white text-center">
        Dashboard PPDB
    </div>
    <div class="row">
        <?php foreach($dashboardwilayah->getResult() as $row):?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="info-box bg-gray">
                        <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Kuota Zonasi : <b><?php echo $row->kuota_zonasi;?></b></span>
                            <span class="info-box-number">Pendaftar : <?php echo $row->pendaftar_zonasi;?> (<?php if($row->pendaftar_zonasi||$row->kuota_zonasi!="0"){?><?php echo round(($row->pendaftar_zonasi/$row->kuota_zonasi)*100,2);?>%<?php }else{?>0%<?php }?>)</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">Diterima : <?php echo $row->zonasi_diterima;?></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="info-box bg-blue">
                        <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Kuota Prestasi : <b><?php echo $row->kuota_prestasi;?></b></span>
                            <span class="info-box-number">Pendaftar : <?php echo $row->pendaftar_prestasi;?> (<?php if($row->pendaftar_prestasi||$row->kuota_prestasi!="0"){?><?php echo round(($row->pendaftar_prestasi/$row->kuota_prestasi)*100,2);?>%<?php }else{?>0%<?php }?>)</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">Diterima : <?php echo $row->prestasi_diterima;?></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="info-box bg-orange">
                        <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Kuota Afirmasi : <b><?php echo $row->kuota_afirmasi;?></b></span>
                            <span class="info-box-number">Pendaftar : <?php echo $row->pendaftar_afirmasi;?> (<?php if($row->pendaftar_afirmasi||$row->kuota_afirmasi!="0"){?><?php echo round(($row->pendaftar_afirmasi/$row->kuota_afirmasi)*100,2);?>%<?php }else{?>0%<?php }?>)</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">Diterima : <?php echo $row->afirmasi_diterima;?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach;?>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <i class="glyphicon glyphicon-dashboard"></i>
                    <h3 class="box-title"><b>Progres Penerimaan</b></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div id="gauge" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <i class="glyphicon glyphicon-dashboard"></i>
                    <h3 class="box-title"><b>Grafik Pendaftar</b></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div id="bar" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <i class="glyphicon glyphicon-dashboard"></i>
                    <h3 class="box-title"><b>Progres Harian Pendaftaran</b></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div id="line" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <i class="glyphicon glyphicon-dashboard"></i>
                    <h3 class="box-title"><b>Prosentase Jalur Pendaftaran</b></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div id="pie" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

