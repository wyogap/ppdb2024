
<!-- bootstrap. bundle includes popper.js -->
{if $smarty.const.__USE_CDN__|default: 1} 
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
{else}
<script src="{$base_url}cdn/bootstrap-4.6.2/js/bootstrap.bundle.min.js"></script>
{/if}

<script src="{$base_url}assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js" defer></script>

{if $use_geo|default: FALSE}
<!-- leaflet -->
{if $smarty.const.__USE_CDN__|default: 1} 
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.2/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/2.4.0/Control.FullScreen.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.EasyButton/2.1.0/easy-button.min.js"></script>
<script src="{$base_url}cdn/leaflet/esri/esri-leaflet.js" defer></script>
<!-- <script src="{$base_url}cdn/leaflet/esri/esri-leaflet-geocoder.js"></script> -->
<script src="{$base_url}cdn/leaflet/markercluster/leaflet.markercluster.js" defer></script>
<script src="{$base_url}cdn/leaflet/oms/oms.min.js" defer></script>
{else}
<script src="{$base_url}cdn/leaflet/leaflet/leaflet.js"></script>
<script src="{$base_url}cdn/leaflet/fullscreen/leaflet.fullscreen.js"></script>
<script src="{$base_url}cdn/leaflet/easybutton/easy-button.js"></script>
<script src="{$base_url}cdn/leaflet/esri/esri-leaflet.js" defer></script>
<!-- <script src="{$base_url}cdn/leaflet/esri/esri-leaflet-geocoder.js"></script> -->
<script src="{$base_url}cdn/leaflet/markercluster/leaflet.markercluster.js" defer></script>
<script src="{$base_url}cdn/leaflet/oms/oms.min.js" defer></script>
{/if}
{/if}

{if !empty($use_select2)}
<!-- select2 -->
<script src="{$base_url}assets/select2/js/select2.min.js"></script>
{/if}

{if !empty($use_datatable)}
<!-- datatables -->
<!-- <script src="{$base_url}assets/datatables/datatables.min.js" defer></script> -->

<script src="{$base_url}assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js" defer></script>
<script src="{$base_url}assets/datatables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js" defer></script>
<script src="{$base_url}assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js" defer></script>
<script src="{$base_url}assets/datatables/Responsive-2.2.3/js/responsive.bootstrap4.min.js" defer></script>
<script src="{$base_url}assets/datatables/Select-1.3.4/js/dataTables.select.min.js" defer></script>
<script src="{$base_url}assets/datatables/Select-1.3.4/js/select.bootstrap4.min.js" defer></script>

<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js" defer></script>
<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.bootstrap4.min.js" defer></script>
<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.html5.min.js" defer></script>
<script src="{$base_url}assets/datatables/JSZip-2.5.0/jszip.min.js" defer></script>

<script src="{$base_url}assets/datatables/KeyTable-2.5.1/js/dataTables.keyTable.min.js" defer></script>
<script src="{$base_url}assets/datatables/KeyTable-2.5.1/js/keyTable.bootstrap4.min.js" defer></script>

<script src="{$base_url}assets/datatables/RowReorder-1.2.6/js/dataTables.rowReorder.min.js" defer></script>
<script src="{$base_url}assets/datatables/RowReorder-1.2.6/js/rowReorder.bootstrap4.min.js" defer></script>

<!-- <script src="{$base_url}assets/datatables/SearchBuilder-1.3.0/js/dataTables.searchBuilder.min.js" defer></script>
<script src="{$base_url}assets/datatables/SearchBuilder-1.3.0/js/searchBuilder.bootstrap4.min.js" defer></script>

<script src="{$base_url}assets/datatables/SearchPanes-1.4.0/js/dataTables.searchPanes.min.js" defer></script>
<script src="{$base_url}assets/datatables/SearchPanes-1.4.0/js/searchPanes.bootstrap4.min.js" defer></script> -->

{if !empty($use_editor)}
<!-- <script src="{$base_url}assets/datatables/editor.min.js" defer></script> -->

<script src="{$base_url}assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js" defer></script>
<script src="{$base_url}assets/datatables/Editor-1.9.2/js/editor.bootstrap4.min.js" defer></script>

{if !empty($use_select2)}
<script src="{$base_url}assets/datatables/tcg/dt-editor-select2.js" defer></script>
{/if} 

<script src="{$base_url}assets/jquery-mask/jquery.mask.min.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-mask.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-toggle.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-checkbox.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-cascade.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-unitprice.js" defer></script>

<script src="{$base_url}assets/datatables/tcg/dt-editor-text.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-number.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-readonly.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-date.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-textarea.js" defer></script>

{if !empty($use_editor_table)}
<script src="{$base_url}assets/datatables/tcg/dt-editor-table.js" defer></script>
{/if}

{if !empty($use_editor_rowgroup)}
<script src="{$base_url}assets/datatables/tcg/dt-plugin-rowgroup.js" defer></script>
{/if}

{if !empty($use_geo)}
<script src="{$base_url}assets/datatables/tcg/dt-editor-geolocation.js" defer></script>
{/if}

{if !empty($use_upload)}
<!-- dropzone file upload -->
<script src="{$base_url}assets/dropzone/dropzone.min.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-upload.js" defer></script>

<!-- dragula drag-n-drop component -->
<!-- <script src="{$base_url}assets/dragula/dragula.min.js" defer></script> -->
{/if} 

{if !empty($use_wysiwyg)}
<!-- WYSIWYG editor -->
{if $smarty.const.__USE_CDN__|default: 1} 
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor5/29.1.0/ckeditor.min.js"></script>
{else}
<script src="{$base_url}cdn/ckeditor5/ckeditor.js"></script>
<!-- <script src="{$base_url}assets/ckeditor/adapters/jquery.js" defer></script> -->
{/if}
<script src="{$base_url}assets/datatables/tcg/dt-editor-editor.js" defer></script>
{/if} 
{/if}
{/if}

<!-- full calendar -->
<!-- {if !empty($use_calendar)}
<script src="{$base_url}assets/fullcalendar/core/main.min.js" defer></script>
{/if}  -->

<!-- mustache templating -->
<script src="{$base_url}assets/mustache/mustache.min.js" defer></script>

<!-- toastr toast popup -->
<script src="{$base_url}assets/jquery-confirm/jquery-confirm.min.js"></script>
<script src="{$base_url}assets/toastr/toastr.min.js"></script>

<!-- fontawesome -->
{if $smarty.const.__USE_CDN__|default: 1} 
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" defer charset="utf-8"></script>
{else}
<script src="{$base_url}cdn/fontawesome/js/fontawesome.min.js" defer charset="utf-8"></script>
<!-- <script src="{$base_url}assets/fontawesome-iconpicker/js/fontawesome-iconpicker.min.js" defer charset="utf-8"></script> -->
{/if}

{if !empty($use_highchart)}
<script src="{$base_url}cdn/highcharts/code/highcharts.js"></script>
<script src="{$base_url}cdn/highcharts/code/highcharts-more.js"></script>
<script src="{$base_url}cdn/highcharts/code/themes/grid-light.js"></script>
{/if}

<!-- jquery plugins -->
<!-- <script src="{$base_url}assets/jquery-jvectormap/jquery-jvectormap.min.js" defer></script> -->
<!-- <script src="{$base_url}assets/backend/js/vendor/jquery-jvectormap-world-mill-en.js"></script> -->
<script src="{$base_url}assets/bootstrap-tagsinput/bootstrap-tagsinput.min.js" defer charset="utf-8"></script>

<!--- moment -->
<script src="{$base_url}assets/moment/moment-with-locales.min.js" defer></script>

<!-- accounting -->
<script src="{$base_url}assets/accounting/accounting.min.js" defer></script>

<!-- third party js -->
<!-- <script src="{$base_url}assets/backend/js/vendor/Chart.bundle.min.js"></script>
<script src="{$base_url}assets/backend/js/pages/demo.dashboard.js"></script>
<script src="{$base_url}assets/backend/js/pages/datatable-initializer.js"></script>
<script src="{$base_url}assets/backend/js/pages/demo.form-wizard.js"></script> -->

<!-- app -->
<script src="{$base_url}themes/{$theme}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js" defer></script>
<script src="{$base_url}themes/{$theme}/dist/js/adminlte.min.js" defer></script>
<script src="{$base_url}themes/{$theme}/app.js" defer></script>

<!-- <script src="{$base_url}themes/{$theme}/js/custom.js" defer></script> -->

<!-- some basic vars commonly used in scriptings !-->
<script type="text/javascript">
    var controller = "{$controller|default: ''}";
    var page_role = "{$page_role|default: ''}";

</script>

<!-- Toastr and alert notifications scripts -->
<script type="text/javascript">

    //select2 default theme
    $.fn.select2.defaults.set( "theme", "bootstrap" );

    $(function () {
        {if !empty($use_datatable)}
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            $.fn.dataTable.tables({
                visible: true,
                api: true
            }).columns.adjust().responsive.recalc();
        });
        {/if}

        $('[data-toggle="tooltip"]').tooltip();
    });

    //Dropzone.autoDiscover = false;

    $(document).ready(function() {

        //default toastr options
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        {if isset($flashdata) && !empty($flashdata['info_message'])}
        toastr.info("{__('Success')}!", '{$flashdata["info_message"]}');
        {/if}

        {if isset($flashdata) && !empty($flashdata['error_message'])}
        toastr.error("{__('Oh Snap')}!", '{$flashdata["error_message"]}');
        {/if}

        {if isset($flashdata) && !empty($flashdata['flash_message'])}
        toastr.success("{__('Congratulations')}!", '{$flashdata["flash_message"]}');
        {/if}    
    });

    function notify(message) {
        toastr.info(message, "{__('Heads Up')}!");
    }

    function success_notify(message) {
        toastr.success(message, "{__('Congratulations')}!");
    }

    function error_notify(message) {
        toastr.error(message, "{__('Oh Snap')}!");
    }

    function error_required_field() {
        toastr.error("{__('Please fill all the required fields')}", "{__('Oh Snap')}!");
    }

</script>