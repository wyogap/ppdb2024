<link rel="stylesheet" href="<?php echo base_url();?>assets/leaflet/leaflet.css"/>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet'/>
<link rel="stylesheet" href="<?php echo base_url();?>assets/ionicons/css/ionicons.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ppdb.css">
<script src="<?php echo base_url();?>assets/leaflet/leaflet.js"></script>
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>
<script src="<?php echo base_url();?>assets/highcharts/code/highcharts.js"></script>
<script src="<?php echo base_url();?>assets/highcharts/code/highcharts-more.js"></script>
<script src="<?php echo base_url();?>assets/highcharts/code/themes/grid-light.js"></script>

<?php
	$this->load->model('Mdinas');
	$kuota = 0;
	$total_pendaftar = 0;
	$diterima = 0;
?>

<div class="row">
	<?php foreach($dashboardwilayah->getResult() as $row):?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="info-box bg-gray">
                    <!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
                    <div class="info-box-content" style="margin-left: 0px;">
                        <span class="info-box-text">Kuota Zonasi : <b><?php echo $row->kuota_zonasi;?></b></span>
                        <span class="info-box-number">Pendaftar : <?php echo $row->pendaftar_zonasi;?> (<?php if($row->pendaftar_zonasi||$row->kuota_zonasi!="0"){?><?php echo round(($row->pendaftar_zonasi/$row->kuota_zonasi)*100,2);?>%<?php }else{?>0%<?php }?>)</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">Diterima : <?php echo $row->zonasi_diterima;?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="info-box bg-blue">
                    <!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
                    <div class="info-box-content" style="margin-left: 0px;">
                        <span class="info-box-text">Kuota Prestasi : <b><?php echo $row->kuota_prestasi;?></b></span>
                        <span class="info-box-number">Pendaftar : <?php echo $row->pendaftar_prestasi;?> (<?php if($row->pendaftar_prestasi||$row->kuota_prestasi!="0"){?><?php echo round(($row->pendaftar_prestasi/$row->kuota_prestasi)*100,2);?>%<?php }else{?>0%<?php }?>)</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">Diterima : <?php echo $row->prestasi_diterima;?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="info-box bg-orange">
                    <!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
                    <div class="info-box-content" style="margin-left: 0px;">
                        <span class="info-box-text">Kuota Afirmasi : <b><?php echo $row->kuota_afirmasi;?></b></span>
                        <span class="info-box-number">Pendaftar : <?php echo $row->pendaftar_afirmasi;?> (<?php if($row->pendaftar_afirmasi||$row->kuota_afirmasi!="0"){?><?php echo round(($row->pendaftar_afirmasi/$row->kuota_afirmasi)*100,2);?>%<?php }else{?>0%<?php }?>)</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">Diterima : <?php echo $row->afirmasi_diterima;?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="info-box bg-purple">
                    <!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
                    <div class="info-box-content" style="margin-left: 0px;">
                        <span class="info-box-text">Kuota Perpindahan : <b><?php echo $row->kuota_perpindahan_orang_tua;?></b></span>
                        <span class="info-box-number">Pendaftar : <?php echo $row->pendaftar_perpindahan_orang_tua;?> (<?php if($row->pendaftar_perpindahan_orang_tua||$row->kuota_perpindahan_orang_tua!="0"){?><?php echo round(($row->pendaftar_perpindahan_orang_tua/$row->kuota_perpindahan_orang_tua)*100,2);?>%<?php }else{?>0%<?php }?>)</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">Diterima : <?php echo $row->perpindahan_orang_tua_diterima;?></span>
                    </div>
                </div>
            </div>
		</div>
	</div>
	<?php endforeach;?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div id="peta" style="width: 100%; height: 400px;"></div><br>
	</div>
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
<script type="text/javascript">

<?php
	$hari_ini = date("Y-m-d") ;
?>

theme = 'grid-light';
    //Line Chart
    Highcharts.chart('line', {
        chart: {
            type: 'line'
        },
        title: {
            text: false
        },
        xAxis: {
            categories: ['H-7','H-6','H-5','H-4','H-3','H-2','H-1',<?php echo "'Hari Ini ($hari_ini)'"; ?>]
        },
        yAxis: {
            title: {
                text: false
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: 'Progres Pendaftar',
            data: [
                <?php foreach($dashboardline->getResult() as $row):?>
                    <?php echo "$row->day_7,$row->day_6,$row->day_5,$row->day_4,$row->day_3,$row->day_2,$row->day_1,$row->day_0";?>
                <?php endforeach;?>
            ]
        }]
    });

 	//Bar Chart
    Highcharts.chart('bar', {
		<?php 

			$this->load->model('Mdinas');
			$dashboardsummary = $this->Mdinas->dashboardsummary();
			foreach($dashboardsummary->getResult() as $row):
			?>
		chart: {
			type: 'column'
		},
		title: {
			text: false
		},
		xAxis: {
			categories: ['<?php echo $row->nama;?>'],
			title: {
				text: null
			}
		},
		yAxis: {
			min: 0,
			title: false,
			labels: {
				overflow: 'justify'
			}
		},
		tooltip: {
			valueSuffix: ' Siswa'
		},
		plotOptions: {
	        bar: {
	            dataLabels: {
	                enabled: true
	            },
	            enableMouseTracking: false
	        }
	    },
		legend: {
			enabled: true
		},
		credits: {
			enabled: true
		},
		series: [{
			name: 'Kuota',
			color: 'rgba(0, 0, 0, 0.4)',
			data: [<?php echo $row->kuota;?>]
		},{
			name: 'Pendaftar',
			color: 'rgba(13, 121, 182, 0.7)',
			data: [<?php echo $row->total_pendaftar;?>]
		},
		{
			name: 'Terverifikasi',
			color: 'rgba(254, 158, 0, 0.7)',
			data: [<?php echo $row->berkas_lengkap;?>]
		},
		{
			name: 'Diterima',
			color: 'rgba(41, 232, 44, 0.7)',
			data: [<?php echo $row->masuk_kuota+$row->daftar_tunggu;?>]
		},
		{
			name: 'Tidak Diterima',
			color: 'rgba(215, 44, 44, 0.7)',
			data: [<?php echo $row->tidak_masuk_kuota;?>]
		}]
		<?php endforeach;?>
	});

	//Pie Chart
	Highcharts.chart('pie', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: false
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Jumlah {point.y}'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true
				},
				showInLegend: true
			}
		},
		series: [{
			name: 'Prosentase',
			colorByPoint: true,
			data: [
				<?php 
					$this->load->model('Mdinas');
					$dashboardpendaftar = $this->Mdinas->chartpendaftar();
					foreach($dashboardpendaftar->getResult() as $row): ?>
					<?php if($row->total_pendaftar > 0) { ?>
						{name: '<?php echo $row->jalur;?>',y:<?php echo $row->total_pendaftar;?>,sliced:true,selected:true},
					<?php } ?>
				<?php endforeach;?>
			]
		}]
	});

    //Gauge Chart
    Highcharts.chart('gauge', {
        chart: {
            type: 'gauge',
            plotBackgroundColor: null,
            plotBackgroundImage: null,
            plotBorderWidth: 0,
            plotShadow: false
        },
        title: {
            text: false
        },
        pane: {
            startAngle: -150,
            endAngle: 150,
            background: [{
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#FFF'],
                        [1, '#333']
                    ]
                },
                borderWidth: 0,
                outerRadius: '109%'
            }, {
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#333'],
                        [1, '#FFF']
                    ]
                },
                borderWidth: 1,
                outerRadius: '107%'
            }, {
            }, {
                backgroundColor: '#DDD',
                borderWidth: 0,
                outerRadius: '105%',
                innerRadius: '103%'
            }]
        },
        yAxis: {
            min: 0,
            max: 100,

            minorTickInterval: 'auto',
            minorTickWidth: 1,
            minorTickLength: 10,
            minorTickPosition: 'inside',
            minorTickColor: '#666',

            tickPixelInterval: 30,
            tickWidth: 2,
            tickPosition: 'inside',
            tickLength: 10,
            tickColor: '#666',
            labels: {
                step: 2,
                rotation: 'auto'
            },
            title: {
                text: '%'
            },
            plotBands: [{
                from: 90,
                to: 100,
                color: '#55BF3B' // green
            }, {
                from: 30,
                to: 90,
                color: '#DDDF0D' // yellow
            }, {
                from: 0,
                to: 30,
                color: '#DF5353' // red
            }]
        },
        series: [{
            name: 'Prosentase',
            data: [
                <?php foreach($dashboardwilayah->getResult() as $row):?>
                <?php echo round(($row->masuk_kuota/$row->kuota_total)*100,2);?>
                <?php endforeach;?>
            ],
            tooltip: {
                valueSuffix: ' %'
            }
        }]
    });

</script>
<script type="text/javascript">
	//Peta
	var map = L.map('peta',{zoomControl:false}).setView([<?php echo $lintang_aktif;?>,<?php echo $bujur_aktif;?>],10);
	L.tileLayer(
		'<?php echo $servis_peta_aktif;?>',{maxZoom: 18,attribution: 'PPDB <?php echo $wilayah_aktif;?>',id: 'mapbox.streets'}
	).addTo(map);

    var streetmap   = L.tileLayer('<?php echo $streetmap_aktif;?>', {id: 'mapbox.light', attribution: ''}),
        satelitemap  = L.tileLayer('<?php echo $satelitemap_aktif;?>', {id: 'mapbox.streets',   attribution: ''});
    var baseLayers = {
        "Satelite": satelitemap,
        "Streets": streetmap
    };

	<?php foreach($daftarpenerapan->getResult() as $row):?>
		//Layer Group
		var j<?php echo $row->jalur_id;?> = new L.LayerGroup();
	<?php endforeach;?>

	<?php if (1 == 0) { ?>
    <?php foreach($daftarpendaftar->getResult() as $row):
        if(!empty($row->lintang) && !empty($row->bujur)) {?>
	var marker = L.circle([<?php echo $row->lintang;?>,<?php echo $row->bujur;?>], 60, {
		color: '#1f618d',
		fillColor: '#1f618d',
		fillOpacity: 0.8
	});
	marker.addTo(j<?php echo $row->jalur_id;?>);
    <?php } endforeach;?>
	<?php } ?>

	<?php foreach($daftarpenerapan->getResult() as $row):?>
		//Adding Layer Group
		j<?php echo $row->jalur_id;?>.addTo(map);
	<?php endforeach;?>

	var overlays = {
		<?php foreach($daftarpenerapan->getResult() as $row):?>
		"<?php echo $row->jalur;?>":j<?php echo $row->jalur_id;?>,
		<?php endforeach;?>
	};
	L.control.layers(baseLayers,overlays).addTo(map);

	new L.control.fullscreen({position:'bottomleft'}).addTo(map);
	new L.Control.Zoom({position:'bottomright'}).addTo(map);

	function map_pendaftaran() {
		$.post("<?php echo site_url('admin/dashboard/pendaftaran')?>",
		{},
		function(data, status){
			data.data.forEach(function(value, index, array) {
				if (value.lintang != "" && value.lintang != null 
						& value.bujur != "" && value.bujur != null) {
					var marker = L.circle([parseFloat(value.lintang), parseFloat(value.bujur)], 60, {
						color: '#1f618d',
						fillColor: '#1f618d',
						fillOpacity: 0.8
					});
					marker.addTo(overlays[value.jalur]);
				}
			});
		},
		"json");
	}

</script>

<script type="text/javascript">

$(document).ready(function() {
	map_pendaftaran();
});

</script>