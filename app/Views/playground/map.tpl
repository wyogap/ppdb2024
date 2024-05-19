<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Hello, {$name|default: 'Fulan'}!</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}

    .leaflet-layer,
    .leaflet-control-zoom-in,
    .leaflet-control-zoom-out,
    .leaflet-control-attribution {
    filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%);
    }

	</style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="{$site_url}assets/leaflet/leaflet.css"/>
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet'/>

  	<!-- FAVICONS ICON -->
	<link href="{$base_url}/themes/dompet/vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
    <link href="{$base_url}/themes/dompet/css/style.css" rel="stylesheet">

</head>
<body>

<div id="container">
	<h1>Welcome to CodeIgniter!</h1>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card box-solid">
			<div class="card-header with-border">
				<h3 class="box-title text-info"><b>Lokasi Sekolah</b></h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="peta" style="width: 100%; height: 400px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<p class="footer">Page rendered in <strong>{literal}{elapsed_time}{/literal}</strong> seconds. {$version|default: '0.0'}</p>
</div>

    <!-- Required vendors -->
    <script src="{$base_url}/themes/dompet/vendor/global/global.min.js"></script>
	<script src="{$base_url}/themes/dompet/vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="{$site_url}assets/leaflet/leaflet.js"></script>
    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>

    <script>
        var dezSettingsOptions = {};

        (function($) {
            //update the theme setting. must be before dlabnav-init.js
            dezSettingsOptions = {
                typography: "cairo",
                version: "dark",
                layout: "horizontal",
                primary: "color_1",
                navheaderBg: "color_1",
                sidebarBg: "color_1",
                sidebarStyle: "compact",
                sidebarPosition: "fixed",
                headerPosition: "fixed",
                containerLayout: "boxed",
            };
            
        })(jQuery);

    </script>

    <script src="{$base_url}/themes/dompet/js/custom.min.js"></script>
    <script src="{$base_url}/themes/dompet/js/dlabnav-init.js"></script>
    
    <script>
		//Peta
		var map = L.map('peta',{ zoomControl:false }).setView([{$map_lintang},{$map_bujur}],16);
		// L.tileLayer(
		// 	'{$map_streetmap}',{ maxZoom: 18,attribution: 'PPDB {$nama_wilayah}',id: 'mapbox.streets' }
		// ).addTo(map);

		L.marker([{$map_lintang},{$map_bujur}]).addTo(map)
            .bindPopup("Lokasiku");

		var streetmap   = L.tileLayer('{$map_streetmap}', { id: 'mapbox.light', attribution: '' }),
			satelitemap  = L.tileLayer('{$map_satelitemap}', { id: 'mapbox.streets', attribution: '' });

		var baseLayers = {
			"Streets": streetmap,
			"Satelite": satelitemap
		};

		var overlays = {};
		L.control.layers(baseLayers,overlays).addTo(map);

		new L.control.fullscreen({ position:'bottomleft' }).addTo(map);
		new L.Control.Zoom({ position:'bottomright' }).addTo(map);

    </script>


</body>
</html>