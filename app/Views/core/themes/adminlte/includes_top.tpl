<link rel="shortcut icon" href="{$base_url}{$app_icon}">

<!-- <link rel="shortcut icon" href="http://localhost/academy/uploads/system/favicon.png"> -->

<!-- bootstrap -->
<link href="{$base_url}assets/bootstrap-4.6.2/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<link href="{$base_url}assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />

{if $use_geo|default: FALSE}
<!-- leaflet -->
{if $smarty.const.USE_CDN|default: 1} 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.2/leaflet.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/2.4.0/Control.FullScreen.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.EasyButton/2.1.0/easy-button.min.css">
<link href="{$base_url}assets/leaflet/esri/esri-leaflet-geocoder.css" rel="stylesheet" />
{else}
<link href="{$base_url}assets/leaflet/leaflet/leaflet.css" rel="stylesheet" />
<link href="{$base_url}assets/leaflet/fullscreen/leaflet.fullscreen.css" rel="stylesheet" />
<link href="{$base_url}assets/leaflet/easybutton/easy-button.css" rel="stylesheet" />
<link href="{$base_url}assets/leaflet/esri/esri-leaflet-geocoder.css" rel="stylesheet" />
{/if}
{/if}

{if !empty($use_select2)}
<!-- select2 -->
<link href="{$base_url}assets/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}assets/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
{/if}

{if !empty($use_datatable)}
<!-- datatables -->
<!-- <link href="{$base_url}assets/datatables/datatables.min.css" rel="stylesheet" type="text/css" /> -->

<link href="{$base_url}assets/datatables/DataTables-1.10.20/css/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}assets/datatables/Responsive-2.2.3/css/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}assets/datatables/Buttons-1.6.1/css/buttons.bootstrap4.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}assets/datatables/Select-1.3.1/css/select.bootstrap4.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}assets/datatables/KeyTable-2.5.1/css/keyTable.bootstrap4.css" rel="stylesheet" type="text/css" >

<link href="{$base_url}assets/datatables/RowReorder-1.2.6/css/rowReorder.bootstrap4.css" rel="stylesheet" type="text/css" >
<link href="{$base_url}assets/datatables/SearchBuilder-1.3.0/css/searchBuilder.bootstrap4.css" rel="stylesheet" type="text/css" >
<link href="{$base_url}assets/datatables/SearchPanes-1.4.0/css/searchPanes.bootstrap4.css" rel="stylesheet" type="text/css" >

{if !empty($use_editor)}
<!-- <link href="{$base_url}assets/datatables/editor.min.css" rel="stylesheet" type="text/css" /> -->

<link href="{$base_url}assets/datatables/Editor-1.9.2/css/editor.bootstrap4.css" rel="stylesheet" type="text/css" >

<link href="{$base_url}assets/datatables/tcg/dt-editor-mask.css" rel="stylesheet" />
<link href="{$base_url}assets/datatables/tcg/dt-editor-toggle.bootstrap4.css" rel="stylesheet" />
<link href="{$base_url}assets/datatables/tcg/dt-editor-checkbox.css" rel="stylesheet" />
<link href="{$base_url}assets/datatables/tcg/dt-editor-cascade.bootstrap4.css" rel="stylesheet" />
<link href="{$base_url}assets/datatables/tcg/dt-editor-unitprice.css" rel="stylesheet" />

{if !empty($use_select2)}
<link href="{$base_url}assets/datatables/tcg/dt-editor-select2.bootstrap4.css" rel="stylesheet" />
{/if}

<link href="{$base_url}assets/datatables/tcg/dt-editor-text.css" rel="stylesheet" />
<link href="{$base_url}assets/datatables/tcg/dt-editor-number.css" rel="stylesheet" />
<link href="{$base_url}assets/datatables/tcg/dt-editor-readonly.css" rel="stylesheet" />
<link href="{$base_url}assets/datatables/tcg/dt-editor-date.css" rel="stylesheet" />
<link href="{$base_url}assets/datatables/tcg/dt-editor-textarea.css" rel="stylesheet" />

{if !empty($use_editor_table)}
<link href="{$base_url}assets/datatables/tcg/dt-editor-table.bootstrap4.css" rel="stylesheet" />
{/if} 

{if !empty($use_editor_rowgroup)}
<link href="{$base_url}assets/datatables/tcg/dt-plugin-rowgroup.css" rel="stylesheet" />
{/if} 

{if !empty($use_geo)}
<link href="{$base_url}assets/datatables/tcg/dt-editor-geolocation.bootstrap4.css" rel="stylesheet" />
{/if}

{if !empty($use_upload)}
<!-- dropzone file upload -->
<link href="{$base_url}assets/dropzone/dropzone.min.css" rel="stylesheet" />
<link href="{$base_url}assets/datatables/tcg/dt-editor-upload.bootstrap4.css" rel="stylesheet" />

<!-- dragula drag-n-drop component -->
<!-- <link href="{$base_url}assets/dragula/dragula.min.css" rel="stylesheet" type="text/css" /> -->
{/if}

{if !empty($use_wysiwyg)}
<!-- WYSIWYG editor -->
<!-- <link href="{$base_url}assets/backend/css/vendor/summernote-bs4.css" rel="stylesheet" type="text/css" /> -->
<link href="{$base_url}assets/datatables/tcg/dt-editor-editor.css" rel="stylesheet" />
{/if}
{/if}
{/if}

{if !empty($use_calendar)}
<!-- full calendar -->
<link href="{$base_url}assets/fullcalendar/core/main.min.css" rel="stylesheet" type="text/css" />
{/if} 

<!-- toastr toast popup -->
<link href="{$base_url}assets/jquery-confirm/jquery-confirm.min.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}assets/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

<!-- icons -->
<link href="{$base_url}assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css" />
<!-- <link href="{$base_url}assets/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css" rel="stylesheet" type="text/css" /> -->
<link href="{$base_url}assets/dripicons/icons.min.css" rel="stylesheet" type="text/css" />

<!-- many of the 3rd-party library (including datatables) require older version of materialdesign icon -->
{if !empty($use_google_fonts)}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.4.93/css/materialdesignicons.min.css" integrity="sha512-9hrcuHFRJBsyfJiotGL1U+zraOkuI5fzlo0X0C8s7gkkgV1wLkmiP1JbUjVAws4Wo8FcSK82Goj64vT8ERocgg==" crossorigin="anonymous" />
{/if}

<!-- materialdesignicon v5 -->
<!-- <link href="{$base_url}assets/materialdesignicons/css/icons.min.css" rel="stylesheet" type="text/css" /> -->

<!-- utilities -->
<link href="{$base_url}assets/utilities.css" rel="stylesheet" type="text/css" />

<!-- jquery plugins -->
<link href="{$base_url}assets/jquery-jvectormap/jquery-jvectormap.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}assets/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

<!-- jquery js. must be loaded first before other js -->
<script src="{$base_url}assets/jquery-3.4.1/jquery.min.js"></script>

<!-- IMPORTANT: using jquery-3.6.0 screw up bubble editor layout! -->
<!-- <script src="{$base_url}assets/jquery/jquery-3.6.0.min.js"></script> -->

<!-- App css -->
<link href="{$base_url}themes/{$theme}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}themes/{$theme}/dist/css/adminlte.min.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}themes/{$theme}/app.css" rel="stylesheet" type="text/css" />
<!-- <link href="{$base_url}themes/{$theme}/css/main.css" rel="stylesheet" type="text/css" /> -->

{if !empty($use_google_fonts)}
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet">
{/if}

<style>

    /* change bootstrap default font size */
    html {
        font-size: 14px;
    }

    /* Hack: editor popup must be over the popover modal */
    .modal.DTED.show {
        z-index: 1070 !important;
    }

    /* Hack: set remove popup to center */
    .modal-content.DTE_Action_Remove {
        margin-left: auto;
        margin-right: auto;
    }

    #toast-container>div {
        opacity: 1;
    }

    .toast {
        background-color: var(--primary) !important;
    }

    .toast-success {
        background-color: var(--success) !important;
    }

    .toast-error {
        background-color: var(--danger) !important;
    }

    .toast-info {
        background-color: var(--info) !important;
    }

    .toast-warning {
        background-color: var(--warning) !important;
    }

    .form-group label .required {
        color: var(--danger);
    }

    /* hack */
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #ddd;
        color: #313a46;
    }

    .nav-tabs .nav-item {
        min-width: 100px;
        text-align: center;
        margin-bottom: -1px;
    }

    table.dataTable.nowrap th, table.dataTable.nowrap td {
        white-space: normal;
    }

    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 500px;
            margin: 1.75rem auto;
        }    

        .modal-content.DTE_Action_Remove {
            max-width: 500px;
        }
    }

    @media (min-width: 768px) { 
        .modal-dialog {
            max-width: 700px;
            margin: 1.75rem auto;
        }    
    }

    @media (min-width: 992px) { 
        .modal-dialog {
            max-width: 900px;
            margin: 1.75rem auto;
        }    
    }

    /* Extra large devices (large desktops, 1200px and up) */
    @media (min-width: 1200px) { 

    }

    @media only screen and (max-width: 767px) {

        .container-fluid {
            padding-right: 0px;
            padding-left: 0px;
        }

        .container-fluid.header {
            padding-right: 8px;
            padding-left: 8px;
        }

        .nav-tabs .nav-item {
            width: 100%;
            text-align: center;
            margin-bottom: -1px;
        }

        .nav-tabs .nav-item .nav-link {
            border-color: #dee2e6;
            border-radius: .25rem;
        }

        .btn-icon-circle {
            width: 28px !important;
            height: 28px !important;
            margin: 0px 2px 2px !important;
        }

        .btn-icon-circle .fa {
            font-size: .75em !important;
        }

        .btn-dropdown {
            width: 28px !important;
            height: 28px !important;
        }

        .btn-dropdown .fa {
            font-size: .75em !important;
        }

        div.dataTables_wrapper div.dataTables_info {
            white-space: normal;
        }
    }

</style>

<style>
    /* 
    .os-theme-light>.os-scrollbar>.os-scrollbar-track>.os-scrollbar-handle {
        background-color: rgba(0, 0, 0, 0.15) !important;
    } 
    */

    .os-theme-dark>.os-scrollbar>.os-scrollbar-track>.os-scrollbar-handle, 
    .os-theme-light>.os-scrollbar>.os-scrollbar-track>.os-scrollbar-handle, 
    .os-theme-dark>.os-scrollbar>.os-scrollbar-track, 
    .os-theme-light>.os-scrollbar>.os-scrollbar-track  {
        background-color: transparent !important;
    }  

    .os-padding {
        margin-bottom: 16px;
    }

    .main-sidebar {
        border-bottom-right-radius: 15px;
        border-top-right-radius: 15px;
    }

    .os-padding .os-viewport {
        overflow-y: auto;
    }

    .wrapper {
        overflow-x: hidden;
    }

    /** OPTION DROPDOWN **/
    .select2-container .select-option-level-1 {
        padding-left: 0px !important;
    }

    .select2-container .select-option-level-2 {
        padding-left: 12px !important;
    }

    .select2-container .select-option-level-3 {
        padding-left: 24px !important;
    }

    .select2-container .select-option-level-4 {
        padding-left: 36px !important;
    }

    .select2-container .select-option-level-5 {
        padding-left: 48px !important;
    }

    .select2-container .select-option-group {
        font-weight: bold !important;
        color: black;
    }
    /** END OPTION-DROPDOWN **/
</style>