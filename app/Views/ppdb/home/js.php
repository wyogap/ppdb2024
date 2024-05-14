    <!-- <script type="text/javascript" src="assets/podes/javascript/jquery.min.js"></script>
    <script type="text/javascript" src="assets/podes/javascript/bootstrap.min.js"></script>  -->
    <script src="<?php echo base_url();?>assets/adminlte/plugins/select2/select2.full.min.js"></script>
    <!-- 
    -->

    <script type="text/javascript" src="assets/podes/javascript/jquery.easing.js"></script>      
    <script type="text/javascript" src="assets/podes/javascript/jquery-validate.js"></script>
    <script type="text/javascript" src="assets/podes/javascript/imagesloaded.min.js"></script>
    <script type="text/javascript" src="assets/podes/javascript/jquery.isotope.min.js"></script>
    <script type="text/javascript" src="assets/podes/javascript/owl.carousel.js"></script>
    <script type="text/javascript" src="assets/podes/javascript/jquery-countTo.js"></script>
    <script type="text/javascript" src="assets/podes/javascript/jquery.cookie.js"></script>
    <script type="text/javascript" src="assets/podes/javascript/jquery.tweet.min.js"></script>
    <script type="text/javascript" src="assets/podes/javascript/parallax.js"></script>
    <script type="text/javascript" src="assets/podes/javascript/main.js"></script>
    <script type="text/javascript" src="assets/podes/javascript/TimeCircles.js"></script>
    <script type="text/javascript" src="assets/podes/revolution/js/jquery.themepunch.tools.min.js"></script>
    <script type="text/javascript" src="assets/podes/revolution/js/jquery.themepunch.revolution.min.js"></script>
    <script type="text/javascript" src="assets/podes/revolution/js/slider2.js"></script>
    <script type="text/javascript" src="assets/podes/revolution/js/extensions/revolution.extension.actions.min.js"></script>
    <script type="text/javascript" src="assets/podes/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
    <script type="text/javascript" src="assets/podes/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
    <script type="text/javascript" src="assets/podes/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
    <script type="text/javascript" src="assets/podes/revolution/js/extensions/revolution.extension.migration.min.js"></script>
    <script type="text/javascript" src="assets/podes/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
    <script type="text/javascript" src="assets/podes/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
    <script type="text/javascript" src="assets/podes/revolution/js/extensions/revolution.extension.slideanims.min.js"></script> 
    <script type="text/javascript" src="assets/podes/javascript/jquery-waypoints.js"></script> 
    <script type="text/javascript" src="assets/podes/javascript/jquery.magnific-popup.min.js"></script>  
    <script type="text/javascript" src="assets/podes/javascript/countdown.js"></script>
   
<?php if (1==0)  { ?>
<script>
        
        $(document).ready(function() {
            $('#tsmp').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
                "bPaginate": false,
                "dom": 'Bfrtip',
                "buttons": [
                    { extend: 'copy', footer: true, messageTop: "Penerimaan Peserta Didik Baru"},
                    { extend: 'excel', footer: true, messageTop: "Penerimaan Peserta Didik Baru"},
                    { extend: 'pdf', footer: true, messageTop: "Penerimaan Peserta Didik Baru"},
                    { extend: 'print', footer: true, messageTop: "Penerimaan Peserta Didik Baru"}
                ]
            });
        });
        $(document).ready(function() {
            $('#tswasta').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
                "bPaginate": false,
                "dom": 'Bfrtip',
                "buttons": [
                    { extend: 'copy', footer: true, messageTop: "Penerimaan Peserta Didik Baru"},
                    { extend: 'excel', footer: true, messageTop: "Penerimaan Peserta Didik Baru"},
                    { extend: 'pdf', footer: true, messageTop: "Penerimaan Peserta Didik Baru"},
                    { extend: 'print', footer: true, messageTop: "Penerimaan Peserta Didik Baru"}
                ]
            });
        });

    <?php
        $hari_ini = date("Y-m-d") ;
        
        $this->load->model('Mdinas');
        $dashboardline = $this->Mdinas->dashboardline();
        $dashboardsummary = $this->Mdinas->dashboardsummary();
        $dashboardpendaftar = $this->Mdinas->chartpendaftar();
        $dashboardwilayah = $this->Mdinas->tcg_dashboardwilayah();

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
<?php } ?>