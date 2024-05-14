<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title>PPDB ONLINE {$nama_wilayah} - {$page_title}</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="{$base_url}assets/image/tutwuri.png" rel="shortcut icon">

    <link rel="stylesheet" href="{$base_url}assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{$base_url}assets/adminlte/plugins/select2-4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="{$base_url}assets/ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="{$base_url}assets/adminlte/dist/css/AdminLTE.css">
    <link rel="stylesheet" href="{$base_url}assets/adminlte/dist/css/skins/_all-skins.css">

	<link rel="stylesheet" href="{$base_url}assets/jquery-confirm/jquery-confirm.min.css">
	<link rel="stylesheet" href="{$base_url}assets/css/ppdb.css">

    <script src="{$base_url}assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
	<script src="{$base_url}assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
	<script src="{$base_url}assets/adminlte/plugins/select2/select2.full.min.js"></script>
	<script src="{$base_url}assets/adminlte/dist/js/app.min.js"></script>

	<script src="{$base_url}assets/jquery-confirm/jquery-confirm.min.js"></script>

    {if !empty($use_datatable)}
    <link rel="stylesheet" href="{$base_url}assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{$base_url}assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">
  
    <script src="{$base_url}assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="{$base_url}assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>

    {if !empty($use_datatable_button)}
    <link rel="stylesheet" href="{$base_url}assets/datatables/Buttons-1.6.1/css/buttons.dataTables.min.css">
    <script src="{$base_url}assets/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.flash.min.js"></script>
    <script src="{$base_url}assets/datatables/JSZip-2.5.0/jszip.min.js"></script>
    <script src="{$base_url}assets/datatables/pdfmake-0.1.36/pdfmake.min.js"></script>
    <script src="{$base_url}assets/datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
    <script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.html5.min.js"></script>
    <script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.print.min.js"></script>
    {/if}

    {if !empty($use_datatable_editor)}
    <link rel="stylesheet" href="{$base_url}assets/datatables/Editor-1.9.2/css/editor.dataTables.min.css">
    <script src="{$base_url}assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js"></script>
    {/if}
    {/if}

    <script src="{$base_url}assets/adminlte/plugins/moment/moment-with-locales.min.js"></script>
    
    {if !empty($use_leaflet)}
    <!-- Load Leaflet from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
    integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
    crossorigin=""></script>


    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@2.4.1/dist/esri-leaflet.js"
    integrity="sha512-xY2smLIHKirD03vHKDJ2u4pqeHA7OQZZ27EjtqmuhDguxiUvdsOuXMwkg16PQrm9cgTmXtoxA6kwr8KBy3cdcw=="
    crossorigin=""></script>


    <!-- Load Esri Leaflet Geocoder from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.css"
        integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
        crossorigin="">
    <script src="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.js"
        integrity="sha512-HrFUyCEtIpxZloTgEKKMq4RFYhxjJkCiF5sDxuAokklOeZ68U2NPfh4MFtyIVWlsKtVbK5GD2/JzFyAfvT5ejA=="
        crossorigin=""></script>

    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
    <script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>    
    {/if}

    <link rel="stylesheet" href="{$base_url}assets/adminlte/plugins/iCheck/all.css">
    <script src="{$base_url}assets/adminlte/plugins/iCheck/icheck.min.js"></script>

    <link rel="stylesheet" href="{$base_url}assets/adminlte/plugins/datepicker/datepicker3.css">
    <script src="{$base_url}assets/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>

    {if !empty($use_highcharts)}
    <script src="{$base_url}assets/highcharts/code/highcharts.js"></script>
    <script src="{$base_url}assets/highcharts/code/highcharts-more.js"></script>
    <script src="{$base_url}assets/highcharts/code/themes/grid-light.js"></script>
    {/if}

</head> 