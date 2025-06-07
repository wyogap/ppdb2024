<style>
    /** LOADER */

    .loading {
        width: 100%;
        height: 100px;
        position: absolute;
        top: calc((100% / 2) - 50px);
        left: 0px;
        background-color: #6c757d29;
        z-index: 1000;
        /* display: none; */
    }

    .loading-circle {
        border: 16px solid #f3f3f3;
        border-top: 16px solid #3498db;
        border-radius: 50%;
        width: 80px;
        height: 80px;
        animation: spin 2s linear infinite;
        margin: auto;
        margin-top: 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading.show {
        display: block;
    }

</style>

<div class="content-header">
    <div class="container-fluid">

    <div class="card box-solid">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-3">
                    <select id="jenjang" name="jenjang" class="form-control select2" style="width:100%;">
                        <option value="">-- Jenjang --</option>
                        <option value="smp-negeri">SMP Negeri</option>
                        <option value="smp-swasta">SMP Swasta</option>
                        <option value="sd-negeri">SD Negeri</option>
                        <option value="sd-swasta">SD Swasta</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select id="putaran" name="putaran" class="form-control select2" style="width:100%;">
                        <option value="" selected>-- Putaran --</option>
                    </select>
                </div>
                <!-- <div class="col-12 col-md-3">
                    <select id="penerapan" name="penerapan" class="form-control select2" style="width:100%;">
                        <option value="" selected>-- Jalur Penerimaan --</option>
                    </select>
                </div> -->
                <div class="col-12 col-md-3">
                    <select id="kode_wilayah" name="kode_wilayah" class="form-control select2" style="width:100%;">
                        <option value="" selected>-- Kecamatan --</option>
                        {foreach $daftarkecamatan as $row} 
                        <option value="{$row.kode_wilayah}">{$row.nama}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <a id='btn_search' href="javascript:void(0)" onclick="show_dashboard(); return false;" class="btn btn-sm btn-primary btn-flat" style="">Tampilkan</a>
                </div>
            </div>
        </div>
        <!-- <div class="card-footer">
            <div class="row">
                <div class="col-12 col-md-3">
                    <a id='btn_search' href="javascript:void(0)" onclick="show_dashboard(); return false;" class="btn btn-sm btn-primary btn-flat" style="">Tampilkan</a>
                </div>
            </div>
        </div> -->
    </div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="info-box bg-gray">
                    <!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
                    <div class="info-box-content" style="margin-left: 0px;">
                        <span class="info-box-text">Kuota Zonasi : <b><span id="kuota_zonasi">0</span></b></span>
                        <span class="info-box-number">Pendaftar : <span id="pendaftar_zonasi">0</span> (<span id="pendaftar_zonasi_persen">0</span>)</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">Diterima : <span id="zonasi_diterima">0</span></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="info-box bg-blue">
                    <!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
                    <div class="info-box-content" style="margin-left: 0px;">
                        <span class="info-box-text">Kuota Prestasi : <b><span id="kuota_prestasi">0</span></b></span>
                        <span class="info-box-number">Pendaftar : <span id="pendaftar_prestasi">0</span> (<span id="pendaftar_prestasi_persen">0</span>)</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">Diterima : <span id="prestasi_diterima">0</span></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="info-box bg-orange">
                    <!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
                    <div class="info-box-content" style="margin-left: 0px;">
                        <span class="info-box-text">Kuota Afirmasi : <b><span id="kuota_afirmasi">0</span></b></span>
                        <span class="info-box-number">Pendaftar : <span id="pendaftar_afirmasi">0</span> (<span id="pendaftar_afirmasi_persen">0</span>)</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">Diterima : <span id="afirmasi_diterima">0</span></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="info-box bg-purple">
                    <!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
                    <div class="info-box-content" style="margin-left: 0px;">
                        <span class="info-box-text">Kuota Perpindahan : <b><span id="kuota_perpindahan">0</span></b></span>
                        <span class="info-box-number">Pendaftar : <span id="pendaftar_perpindahan">0</span> (<span id="pendaftar_perpindahan_persen">0</span>)</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">Diterima : <span id="perpindahan_diterima">0</span></span>
                    </div>
                </div>
            </div>
		</div>
	</div>
    <div class="col-12">

    </div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="loading" id="loader" style="display: none;">
            <div class="loading-circle"></div>
        </div>
		<div id="peta" style="width: 100%; height: 400px;"></div><br>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="card box-solid">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="dashboard-progress-gauge" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="card box-solid">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="dashboard-summary-bar" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="card box-solid">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="dashboard-harian-line" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="card box-solid">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="dashboard-penerapan-pie" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

    </div>
</div>

<script type="text/javascript">

    var hari_ini = '';
    var daftar_putaran = {$daftarputaran|json_encode};
    var daftar_penerapan = {$daftarpenerapan|json_encode};
    var summary = {};
    var pendaftar_harian = {};

    var hharian = hsummary = hpenerapan = hprogress = null;
    $(document).ready(function() {    
        theme = 'grid-light';
        hari_ini = moment(new Date()).format("YYYY-MM-DD");

        $("select").select2();

        //Line Chart
        hharian = Highcharts.chart('dashboard-harian-line', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Progres Harian Pendaftaran'
            },
            xAxis: {
                categories: ['H-7','H-6','H-5','H-4','H-3','H-2','H-1','Hari Ini (' +hari_ini+ ')']
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
                    0,0,0,0,0,0,0,0
                ]
            }]
        });

        //Bar Chart
        hsummary = Highcharts.chart('dashboard-summary-bar', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Pendaftaran Berdasarkan Status'
            },
            xAxis: {
                categories: [''],
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
                valueSuffix: null
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
                data: [0]
            },{
                name: 'Pendaftar',
                color: 'rgba(13, 121, 182, 0.7)',
                data: [0]
            },
            {
                name: 'Terverifikasi',
                color: 'rgba(254, 158, 0, 0.7)',
                data: [0]
            },
            {
                name: 'Diterima',
                color: 'rgba(41, 232, 44, 0.7)',
                data: [0]
            },
            {
                name: 'Tidak Diterima',
                color: 'rgba(215, 44, 44, 0.7)',
                data: [0]
            }]
        });

        //Pie Chart
        hpenerapan = Highcharts.chart('dashboard-penerapan-pie', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Pendaftaran Berdasarkan Jalur'
            },
            tooltip: {
                {literal}
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Jumlah {point.y}'
                {/literal}
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
                name: 'Jalur Penerapan',
                colorByPoint: true,
                data: [
                    { name: '',y:0,sliced:true,selected:true },
                ]
            }]
        });

        //Gauge Chart
        hprogress = Highcharts.chart('dashboard-progress-gauge', {  
            chart: {
                type: 'gauge',
                plotBackgroundColor: null,
                plotBackgroundImage: null,
                plotBorderWidth: 0,
                plotShadow: false
            },
            title: {
                text: 'Progres Penerimaan'
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
                //name: 'Prosentase',
                data: [
                    0
                ],
                tooltip: {
                    valueSuffix: ' %'
                }
            }]
        });

        //var refresh_dashboard = debounce(show_dashboard, 1000);

        $("#jenjang").on("change", function() {
            let jenjang = $("#jenjang").val();
            if (jenjang == 'smp-negeri' || jenjang == 'smp-swasta') {
                jenjang = 3;
            }
            else if (jenjang == 'sd-negeri' || jenjang == 'sd-swasta') {
                jenjang = 2;
            }

            let select = $("#putaran");
            let val = select.val();
            if (val === undefined || val == null) {
                val = select.attr('DefaultValue');
            }
            select.empty();

            let opt = $("<option>").val("").text("-- Putaran --");
            select.append(opt);

            daftar_putaran.forEach(function(row) {
                if (jenjang != '' && jenjang != row['jenjang_id']) return;

                opt = $("<option>").val( row['putaran'] ).text( row['nama'] );
                select.append(opt);
            });

            select.val(val).trigger("change");

            //refresh_dashboard();
        })

        // $("#putaran").on("change", function() {
        //     let putaran = $("#putaran").val();

        //     // let select = $("#penerapan");
        //     // let val = select.val();
        //     // if (val === undefined || val == null) {
        //     //     val = select.attr('DefaultValue');
        //     // }
        //     // select.empty();

        //     // let opt = $("<option>").val("").text("-- Jalur Penerimaan --");
        //     // select.append(opt);

        //     // daftar_penerapan.forEach(function(row) {
        //     //     if (putaran != '' && putaran != row['putaran']) return;

        //     //     opt = $("<option>").val( row['penerapan_id'] ).text( row['nama'] );
        //     //     select.append(opt);
        //     // });

        //     // select.val(val).trigger("change");

        //    //refresh_dashboard();
        // })

        // $("#penerapan").on("change", function() {
        //     refresh_dashboard();
        // });

        // $("#kode_wilayah").on("change", function() {
        //     refresh_dashboard();
        // });

    });

</script>
<script type="text/javascript">

    var map;
    var overlays;
    var baseLayers;
    var layerscontrol;

    $(document).ready(function() {
        map = L.map('peta',{ zoomControl:false }).setView([{$map_lintang},{$map_bujur}],11);
		L.tileLayer(
			'{$map_streetmap}',{ maxZoom: 18,attribution: '{$app_short_name} {$nama_wilayah}',id: 'mapbox.streets' }
		).addTo(map);

		var streetmap   = L.tileLayer('{$map_streetmap}', { id: 'mapbox.light', attribution: '' }),
			satelitemap  = L.tileLayer('{$map_satelitemap}', { id: 'mapbox.streets', attribution: '' });

		baseLayers = {
			"Streets": streetmap,
			"Satelite": satelitemap
		};

		overlays = {};
        let j = new L.LayerGroup();
        j.addTo(map);
        overlays['Zonasi'] = j;
        
        j = new L.LayerGroup();
        j.addTo(map);
        overlays['Prestasi'] = j;
        
        j = new L.LayerGroup();
        j.addTo(map);
        overlays['Afirmasi'] = j;
        
        j = new L.LayerGroup();
        j.addTo(map);
        overlays['Perpindahan Orang Tua'] = j;

        layerscontrol = L.control.layers(baseLayers,overlays).addTo(map);

		new L.control.fullscreen({ position:'bottomleft' }).addTo(map);
		new L.Control.Zoom({ position:'bottomright' }).addTo(map);

        new L.Control.EasyButton( '<span class="map-button" style="font-size: 30px;">&curren;</span>', function(){
            map.setView([{$map_lintang},{$map_bujur}],10);
        }, { position: 'topleft' }).addTo(map);
    });

    function show_dashboard() {
        //alert("Show Dashboard");

        putaran = $("#putaran").val();
        kode_wilayah = $("#kode_wilayah").val();
        jenjang = $("#jenjang").val();
        //penerapan = $("#penerapan").val();
        penerapan = 0;

        $.post("{$site_url}ppdb/dinas/dashboard",
		{ "jenjang_id":jenjang, "putaran":putaran, "penerapan_id":penerapan, "kode_wilayah":kode_wilayah },
		function(data, status){
            summary = data.dashboardsummary;
            pendaftar_harian = data.dashboardharian;      
            
            $('#kuota_zonasi').html(summary['kuota_zonasi'] ?? 0);
            $('#pendaftar_zonasi').html(summary['pendaftar_zonasi'] ?? 0);
            let persen = (parseInt(summary['pendaftar_zonasi']) / parseInt(summary['kuota_zonasi'])) * 100;
            if (isNaN(persen))  persen = 0;
            $('#pendaftar_zonasi_persen').html(persen.toFixed(2) +"%");
            $('#zonasi_diterima').html(summary['zonasi_diterima'] ?? 0);
            
            $('#kuota_prestasi').html(summary['kuota_prestasi'] ?? 0);
            $('#pendaftar_prestasi').html(summary['pendaftar_prestasi'] ?? 0);
            persen = (parseInt(summary['pendaftar_prestasi']) / parseInt(summary['kuota_prestasi'])) * 100;
            if (isNaN(persen))  persen = 0;
            $('#pendaftar_prestasi_persen').html(persen.toFixed(2) +"%");
            $('#prestasi_diterima').html(summary['prestasi_diterima'] ?? 0);
            
            $('#kuota_afirmasi').html(summary['kuota_afirmasi'] ?? 0);
            $('#pendaftar_afirmasi').html(summary['pendaftar_afirmasi'] ?? 0);
            persen = (parseInt(summary['pendaftar_afirmasi']) / parseInt(summary['kuota_afirmasi'])) * 100;
            if (isNaN(persen))  persen = 0;
            $('#pendaftar_afirmasi_persen').html(persen.toFixed(2) +"%");
            $('#afirmasi_diterima').html(summary['afirmasi_diterima'] ?? 0);
            
            $('#kuota_perpindahan').html(summary['kuota_perpindahan_orang_tua'] ?? 0);
            $('#pendaftar_perpindahan').html(summary['pendaftar_perpindahan_orang_tua'] ?? 0);
            persen = (parseInt(summary['pendaftar_perpindahan_orang_tua']) / parseInt(summary['kuota_perpindahan_orang_tua'])) * 100;
            if (isNaN(persen))  persen = 0;
            $('#pendaftar_perpindahan_persen').html(persen.toFixed(2) +"%");
            $('#perpindahan_diterima').html(summary['perpindahan_orang_tua_diterima'] ?? 0);

            data = [ parseInt(pendaftar_harian['day_7'] ?? 0), parseInt(pendaftar_harian['day_6'] ?? 0), 
                        parseInt(pendaftar_harian['day_5'] ?? 0), parseInt(pendaftar_harian['day_4'] ?? 0), 
                        parseInt(pendaftar_harian['day_3'] ?? 0), parseInt(pendaftar_harian['day_2'] ?? 0), 
                        parseInt(pendaftar_harian['day_1'] ?? 0), parseInt(pendaftar_harian['day_0'] ?? 0) ];
            hharian.series[0].setData(data);
            hharian.redraw();

            hsummary.series[0].setData( [ summary['kuota_total'] ?? 0 ] );
            hsummary.series[1].setData( [ summary['total_pendaftar'] ?? 0 ] );
            hsummary.series[2].setData( [ summary['verifikasi_berkas_lengkap'] ?? 0 ] );
            hsummary.series[3].setData( [ summary['masuk_kuota'] ?? 0 ] );
            hsummary.series[4].setData( [ summary['tidak_masuk_kuota'] ?? 0 ] );
            hsummary.redraw();

            data = [];
            data.push( { name: "Zonasi", y: summary['pendaftar_zonasi'] ?? 0 });
            data.push( { name: "Prestasi", y: summary['pendaftar_prestasi'] ?? 0 });
            data.push( { name: "Afirmasi", y: summary['pendaftar_afirmasi'] ?? 0 });
            data.push( { name: "Perpindahan Orang Tua", y: summary['pendaftar_perpindahan_orang_tua'] ?? 0 });
            hpenerapan.series[0].setData( data );
            hpenerapan.redraw();

            progress = (parseInt(summary['masuk_kuota']) / parseInt(summary['kuota_total'])) * 100;
            if (isNaN(progress))    progress = 0;
            hprogress.series[0].name = "Kuota Terpenuhi"
            hprogress.series[0].setData( [ { y:progress.toFixed(2) } ] );
            hprogress.redraw();
            
            map_pendaftaran();
        },
		"json");

    }

	function map_pendaftaran() {
        $("#loader").show();

        overlays['Zonasi'].clearLayers();
        overlays['Prestasi'].clearLayers();
        overlays['Afirmasi'].clearLayers();
        overlays['Perpindahan Orang Tua'].clearLayers();

        putaran = $("#putaran").val();
        kode_wilayah = $("#kode_wilayah").val();
        jenjang = $("#jenjang").val();
        //penerapan = $("#penerapan").val();
        penerapan = 0;

		$.post("{$site_url}ppdb/dinas/pendaftaran",
		{ "jenjang_id":jenjang, "putaran":putaran, "penerapan_id":penerapan, "kode_wilayah":kode_wilayah },
		function(data, status){
			data.data.forEach(function(value, index, array) {
				if (value.lintang != "" && value.lintang != null 
						& value.bujur != "" && value.bujur != null) {
					var marker = L.circle([parseFloat(value.lintang), parseFloat(value.bujur)], 60, {
						color: '#1f618d',
						fillColor: '#1f618d',
						fillOpacity: 0.8
					});
                    layer = overlays[value.jalur];
                    if (layer == null)  layer = overlays['Zonasi'];
					marker.addTo(layer);
				}
			});

            $("#loader").hide();
		},
		"json");
	}

</script>

<script type="text/javascript">

$(document).ready(function() {
    //set default value
    var jenjang = 'smp-negeri';
    $("#jenjang").val(jenjang);
    $("#jenjang").attr("DefaultValue", jenjang);

    var putaran = {$putaran};
    $("#putaran").val(putaran);
    $("#putaran").attr("DefaultValue", putaran);

    $("#jenjang").trigger('change');

	show_dashboard();
});

</script>