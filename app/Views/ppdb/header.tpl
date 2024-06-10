<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="robots" content="" />
	<meta name="description" content="PPDB ONLINE {$nama_wilayah}" />
	<meta property="og:title" content="PPDB ONLINE {$nama_wilayah}" />
	<meta property="og:description" content="PPDB ONLINE {$nama_wilayah}" />
	<meta property="og:image" content="{$base_url}assets/image/tutwuri.png" />
	<meta name="format-detection" content="telephone=no">
	
	<!-- PAGE TITLE HERE -->
    <title>PPDB ONLINE {$nama_wilayah}{if $page_title|default: FALSE} - {$page_title}{/if}</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="{$base_url}assets/image/tutwuri.png" rel="shortcut icon">

    {if $use_select2|default: FALSE}
    <link href="{$base_url}assets/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{$base_url}assets/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    {/if}

    {if $smarty.const.__USE_CDN__|default: 1}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css" rel="stylesheet" type="text/css" />
    {else}
    <link href="{$base_url}cdn/fontawesome/css/all.min.css" rel="stylesheet" type="text/css" />
    {/if}

    {if $use_leaflet|default: FALSE}
    {if $smarty.const.__USE_CDN__|default: 1} 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.2/leaflet.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.EasyButton/2.1.0/easy-button.min.css"/>
    <link rel="stylesheet" href="{$site_url}cdn/leafletfullscreen/leaflet.fullscreen.css"/>
    {else}
    <link rel="stylesheet" href="{$site_url}cdn/leaflet/leaflet.css"/>
    <link rel="stylesheet" href="{$site_url}cdn/leafletfullscreen/leaflet.fullscreen.css"/>
    <link rel="stylesheet" href="{$base_url}cdn/leaflet/easybutton/easy-button.css"/>
    {/if}
    {/if}

    {if $use_datatable|default: FALSE} 
    <link rel="stylesheet" href="{$base_url}assets/datatables/DataTables-1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{$base_url}assets/datatables/Select-1.3.4/css/select.dataTables.min.css">
    <link rel="stylesheet" href="{$base_url}assets/datatables/Responsive-2.2.3/css/responsive.dataTables.min.css">
	<link rel="stylesheet" href="{$base_url}assets/datatables/Buttons-1.6.1/css/buttons.dataTables.min.css">

    {if $use_datatable_editor|default: FALSE}
    <link rel="stylesheet" href="{$base_url}assets/datatables/Editor-1.9.2/css/editor.dataTables.min.css" type="text/css" >   
    {/if} 
    {/if}

    <!-- <link href="{$base_url}assets/flaticon/flaticon.css" rel="stylesheet" type="text/css" />
    <link href="{$base_url}assets/flaticon/flaticon.css" rel="stylesheet" type="text/css" /> -->

    <!-- toastr toast popup -->
    <link href="{$base_url}assets/jquery-confirm/jquery-confirm.min.css" rel="stylesheet" type="text/css" />
    <link href="{$base_url}assets/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{$base_url}assets/bootstrap-datepicker/css/bootstrap-datepicker3.css">

    <!-- theme css -->
    <link href="{$base_url}themes/dompet/css/style.css" rel="stylesheet">
	<link href="{$base_url}themes/dompet/vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">

    <link href="{$base_url}ppdb/ppdb.css" rel="stylesheet">

</head>