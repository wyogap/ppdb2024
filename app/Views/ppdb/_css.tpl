<style>

.header {
    padding: 0px;
    width: 100% !important;
    max-width: 1199px;
}

.header .header-content {
    padding-left: 0px !important;
    padding-right: 0px !important;
}

.header-profile {
    /* padding: 0 15px; */
    -webkit-transition: all 0.5s;
    -ms-transition: all 0.5s;
    transition: all 0.5s;
    margin-top: 5.5px;
}

.header-profile > a.nav-link {
    border: 1px solid #f5f5f5;
    border-radius: 1rem;
    padding: 10px 15px !important;
    display: flex;
    align-items: center;
    background-color: var(--sidebar-bg);
    transition: all .2s ease;
    box-shadow: 0px 15px 30px 0px rgba(0, 0, 0, 0.02);
}

.header-profile > a {
    font-weight: 400;
    display: inline-block;
    font-size: 18px;
    color: #9fa4a6;
    position: relative;
    padding: 0.625rem 1.875rem;
    outline-width: 0;
    text-decoration: none;
}

.header-profile img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}

.header-profile > a.nav-link .header-info {
    /* padding-left: 10px; */
    text-align: left;
}

[data-theme-version="light"] .header-profile > a.nav-link .header-info span {
    color: #000;
}

[data-theme-version="dark"] .header-profile > a.nav-link .header-info span {
    color: #fff;
}

.header-profile > a.nav-link .header-info span {
    font-size: 16px;
    display: block;
    margin-bottom: 5px;
    margin-top: -5px;
    font-weight: 600;
}

.header-profile > a.nav-link .header-info small {
    display: block;
    font-size: 12px;
    color: #89879f;
    font-weight: 400;
    line-height: 1.2;
}

/* .header-profile > .dropdown-menu {
    position: static !important;
} */

.content-body > .container-fluid {
    padding-top: 16px !important;
}

/* .dlabnav {
    left: 0px !important;
    padding-left: 40px !important;
    padding-right: 40px !important;
    height: 100% !important;
    width: 100% !important;
    max-width: 1199px !important;
} */

.header {
    right: 0px !important;
    padding-left: 40px !important;
    padding-right: 40px !important;
    /* height: 100% !important; */
    width: 100% !important;
    max-width: 1199px !important;
}

.nav-header {
    left: 0px !important;
    padding-left: 40px !important;
    padding-right: 40px !important;
    height: 114px !important;
    width: 75% !important;
    max-width: 800px !important;
}

.accordion-header {
    border-radius: 1rem;
    
}

.accordion-header-bg .accordion-header {
    background-color: white;
}

.accordion-header.collapsed {
    border-radius: 1rem;
}

.accordion-primary-solid .accordion-header.collapsed, 
.accordion-bordered .accordion-header.collapsed {
    border-radius: 1rem;
}        

.accordion-bordered .accordion__body {
    border: 1px solid #f5f5f5;
    border-top: none;
    border-bottom-left-radius: 1rem;
    border-bottom-right-radius: 1rem;
}        


.accordion-header-text {
    font-size: 20px;
    font-weight: 500;
}

.ai-icon {
    color: rgb(150, 155, 160);
    background-color: #fff;
    border-radius: 16px;
}

[data-theme-version="dark"] .ai-icon {
    color: #fff;
    background-color: #fff var(--bs-gray);;
}


.ai-icon.active {
    color: #fff !important;
    background-color: #5BCFC5 !important;
    border-radius: 16px;
}

.ai-icon.active i {
    color: #fff !important;
    background-color: #5BCFC5 !important;
}

[data-sidebar-position="fixed"][data-layout="vertical"] .dlabnav .dlabnav-scroll {
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
}

.nav-header .brand-logo img {
    width: 64px;
    height: 64px;
}

.header-profile .dropdown-menu {
    /* width: 250px; 
    z-index: 1035;   */
    left: inherit;
    right: 0px;
}

.accordion__body .box-footer {
    border-top: solid var(--primary-hover);
    padding-top: 16px;
    margin-top: 16px;
}

table.dokumen-pendukung {
    margin-top: 24px;
}

table.dokumen-pendukung tbody tr:first-child {
    background-color: var(--primary) !important;
}

table.dokumen-pendukung tbody tr:first-child td {
    color: #fff;
    border-top: 1px solid var(--primary-hover); 
    border-bottom: 1px solid var(--primary-hover);
}

.accordion__body .form-control {
    display: inline-block;
    width: auto;
    border-color: var(--primary-hover);
    margin-bottom: 0px !important;
}

.accordion-header .status {
    display: block;
    font-size: 12px;
}

.accordion-primary-solid .accordion-item.status-danger .accordion-header {
    background: #f72b50 !important;
    border-color: #f72b50 !important;
    color: #fff;
}

.accordion-primary-solid .accordion-item.status-danger .accordion-header.collapsed {
    background: #fee6ea !important;
    border-color: #fee6ea !important;
    color: rgb(33, 28, 55);
}

.accordion-primary-solid .accordion-item.status-danger .accordion__body {
    border-color: #f72b50 !important;
}

.accordion-primary-solid .accordion-item.status-warning .accordion-header {
    background: var(--bs-gray);
    border-color: var(--bs-gray);
    color: #fff;
}

.accordion-primary-solid .accordion-item.status-warning .accordion-header.collapsed {
    background: var(--bs-light);
    border-color: var(--bs-light);
    color: var(--bs-gray-dark);
}

.accordion-primary-solid .accordion-item.status-warning .accordion__body {
    border-color: var(--bs-gray) !important;
}

.form-control {
    border-color: var(--bs-light);
}

td > button {
    display: inline-block;
}

table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>td:first-child:before, table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>th:first-child:before {
    top: calc((100% / 2) - 10px);
}

table.dataTable>tbody>tr.child span.dtr-title {
    color: #000;    
}    

/* table.dataTable.display tbody td {
    color: var(--bs-gray-dark);
} */

/* table.dataTable.stripe tbody>tr.odd.selected, table.dataTable.stripe tbody>tr.odd>.selected, table.dataTable.display tbody>tr.odd.selected, table.dataTable.display tbody>tr.odd>.selected {
    background-color: var(--rgba-primary-7);
}

table.dataTable.stripe tbody>tr.even.selected, table.dataTable.stripe tbody>tr.even>.selected, table.dataTable.display tbody>tr.even.selected, table.dataTable.display tbody>tr.even>.selected {
    background-color: var(--rgba-primary-5);
}


table.dataTable.hover tbody>tr.selected:hover, table.dataTable.hover tbody>tr>.selected:hover, table.dataTable.display tbody>tr.selected:hover, table.dataTable.display tbody>tr>.selected:hover {
    background-color: var(--rgba-primary-3);
} */

/* table.dataTable.display tbody tr.odd>.sorting_1, table.dataTable.order-column.stripe tbody tr.odd>.sorting_1 {
    background-color: #f9f9f9;
}

table.dataTable.display tbody tr.even>.sorting_1, table.dataTable.order-column.stripe tbody tr.even>.sorting_1 {
    background-color: #fff;
} */

table.dataTable.order-column tbody tr>.sorting_1, table.dataTable.order-column tbody tr>.sorting_2, table.dataTable.order-column tbody tr>.sorting_3, table.dataTable.display tbody tr>.sorting_1, table.dataTable.display tbody tr>.sorting_2, table.dataTable.display tbody tr>.sorting_3 {
    background-color: unset !important;
}

/* light theme */
table.dataTable thead th, 
table.dataTable thead td {
    border-top: solid 2px #000;
    border-bottom: solid 2px #000;
    color: #000;
}

[data-theme-version="dark"] table.dataTable thead th, 
[data-theme-version="dark"] table.dataTable thead td {
    border-top: solid 2px #fff;
    border-bottom: solid 2px #fff;
    border-color: var(--bs-white) !important;
    color: #fff;
}

/* light theme */
table.dataTable.stripe tbody tr.odd, 
table.dataTable.display tbody tr.odd {
    background-color: #f9f9f9;
}

[data-theme-version="dark"] table.dataTable.stripe tbody tr, 
[data-theme-version="dark"] table.dataTable.display tbody tr {
    background-color: var(--sidebar-bg);
}

/* [data-theme-version="dark"] table.dataTable.stripe tbody tr.even, 
[data-theme-version="dark"] table.dataTable.display tbody tr.even {
    background-color: var(--sidebar-bg);
} */

[data-theme-version="light"] table.dataTable tbody tr {
    background-color: #ffffff;
}

[data-theme-version="light"] table.dataTable.display tbody td {
    color: var(--bs-gray-dark);
}

[data-theme-version="dark"] table.dataTable.display tbody td {
    color: var(--bs-light);
}

[data-theme-version="dark"] table.dataTable.display tbody td.bg-gray {
    color: var(--nav-headbg);
}

[data-theme-version="dark"] table.dataTable.display tbody td.bg-red {
    color: #fff;
}

[data-theme-version="dark"] table.dataTable.display tbody td.bg-green {
    color: #fff;
}

[data-theme-version="light"] .dataTables_wrapper .dataTables_paginate .paginate_button {
    color: #333 !important;
}

[data-theme-version="dark"] .dataTables_wrapper .dataTables_paginate .paginate_button {
    color: var(--bs-light) !important;
}

[data-theme-version="light"] .dataTables_wrapper .dataTables_length, 
[data-theme-version="light"] .dataTables_wrapper .dataTables_filter, 
[data-theme-version="light"] .dataTables_wrapper .dataTables_info, 
[data-theme-version="light"] .dataTables_wrapper .dataTables_processing, 
[data-theme-version="light"] .dataTables_wrapper .dataTables_paginate {
    color: #333;
}

[data-theme-version="dark"] .dataTables_wrapper .dataTables_length, 
[data-theme-version="dark"] .dataTables_wrapper .dataTables_filter, 
[data-theme-version="dark"] .dataTables_wrapper .dataTables_info, 
[data-theme-version="dark"] .dataTables_wrapper .dataTables_processing, 
[data-theme-version="dark"] .dataTables_wrapper .dataTables_paginate {
    color: var(--bs-light);
}

/* [data-theme-version="dark"] .form-control:focus,
[data-theme-version="dark"] .form-control:hover
{
    background-color: #fff;
} */

[data-theme-version="dark"] .form-control:disabled,
[data-theme-version="dark"] .form-control:disabled:hover
{
    background-color: var(--bs-dark);
    color: #fff
}

[data-theme-version="dark"] .jconfirm-box {
    background-color: var(--bs-gray);
    color: white;
}

[data-theme-version="dark"] .jconfirm-box .form-control {
    background-color: var(--bs-light);
    color: var(--bs-dark);
}

[data-theme-version="dark"] .jconfirm-box .form-control:focus {
    background-color: #fff;
}

[data-theme-version="dark"] .jconfirm-box .table {
    color: #fff;
}

[data-theme-version="dark"] .jconfirm-box .alert-secondary {
    color: #fff;
}

[data-theme-version="dark"] .header-right .dropdown-menu {
    box-shadow: none;
    background-color: var(--bs-gray);
}

[data-theme-version="dark"] .dropdown-menu .dropdown-item {
    color: var(--bs-white);
}

/* [data-theme-version="dark"] .table.table-striped tbody tr {
    color: var(--nav-headbg);
}

[data-theme-version="dark"] .table.table-striped tbody tr:nth-of-type(odd), [data-theme-version="dark"] .table.table-hover tr:hover {
    background-color: var(--bs-light);
    color: var(--nav-headbg);
} */


[data-theme-version="dark"] table.dataTable.hover tbody tr:hover, 
[data-theme-version="dark"] table.dataTable.display tbody tr:hover {
    background-color: var(--bs-gray);
}

[data-theme-version="dark"] table.dataTable.stripe tbody tr.odd, 
[data-theme-version="dark"] table.dataTable.display tbody tr.odd {
    background-color: var(--sidebar-bg);
}

[data-theme-version="light"] .accordion__body {
    background-color: white;
}

[data-theme-version="light"] .accordion__body {
    background-color: var(--nav-headbg);
}

[data-theme-version="dark"] .accordion-primary-solid .accordion-header {
    color: #fff;
    border-color: var(--primary);
}

/* table.dataTable.display tbody td a {
    color: inherit;
} */

[data-theme-version="dark"] table.dataTable.display tbody td a.btn-primary {
    color: #000;
}

table.dataTable.display tbody td:has(a):hover {
    color: var(--primary);
}

table.dataTable.display tbody td:has(a.btn):hover {
    color: #000;
}

table.dataTable.display tbody td a.btn:hover {
    color: #fff;
}

table.dataTable.hover tbody tr:hover, table.dataTable.display tbody tr:hover {
    background-color: var(--rgba-primary-1);
}

table.dataTable tbody tr:last-child td {
    border-bottom: solid 2px #000;
}

table.dataTable {
    padding-top: 8px;
    padding-bottom: 8px;
}

table.dataTable.no-footer {
    border-bottom: 0px;
}

.dataTables_wrapper .dataTables_length {
    float: right;
    padding-top: 0.755em;
}

.dt-button {
    border-radius: 12px !important  ;
}

.paginate_button {
    border-radius: 12px !important;
}

.custom-tab-1 .nav-item {
    padding-bottom: 3px;
}

.custom-tab-1 .nav-item .nav-link {
    border: 0px !important;
}

.custom-tab-1 .nav-item:hover {
    border-bottom: 3px solid var(--primary);;
}

.custom-tab-1 .nav-item:has(.nav-link.active) {
    border-bottom: 3px solid var(--primary);;
}

.form-control {
    margin-bottom: 16px;
}

.header-left .app-name-long .app-name {
    display: block;
    font-size: 28px;
    margin-top: -4px;
}

.header-left .app-name-long .app-desc {
    display: block;
    font-size: 18px;
    margin-top: -8px;
}

.accordion__body textarea.catatan-verifikasi {
    width: 100% !important; 
    height: 80px;
    padding: 12px;
}

.border-red {
    border-color: var(--bs-danger) !important;
}

.accordion__body select:disabled, .accordion__body input:disabled {
    background-color: var(--bs-yellow);
}

[data-theme-version="light"] .custom-tab-1 {
    background-color: #fff;
}

[data-theme-version="dark"] .custom-tab-1 {
    background-color: var(--nav-headbg);;
}

.custom-tab-1 {
    border-radius: 16px;
    padding-top: 16px;
    padding-bottom: 16px;
    margin-bottom: 16px;
}

.custom-tab-1 .tab-pane {
    padding-left: 30px;
    padding-right: 30px;
    /* padding-top: 16px; */
    /* padding-bottom: 16px; */
}

[data-theme-version="dark"] .custom-tab-1 .nav-link:focus, 
[data-theme-version="dark"] .custom-tab-1 .nav-link:hover, 
[data-theme-version="dark"] .custom-tab-1 .nav-link.active {
    background-color: rgb(91 207 197 / 0%);
}

.badge {
    border-radius: 8px !important;
}

table.dataTable tbody td.bg-gray {
    color: #000;
    background-color: #d2d6de !important;
}

table.dataTable tbody td.bg-red {
    color: #fff;
    background-color: red !important;
}

table.dataTable tbody td.bg-orange {
    color: #fff;
    background-color: orange !important;
}

table.dataTable tbody td.bg-green {
    color: #fff;
    background-color: #00a65a !important;
}

table.dataTable tbody td.bg-yellow {
    color: #000;
    background-color: yellow !important;
}

.loading {
    width: 100%;
    height: 100px;
    position: fixed;
    top: calc((100% / 2) - 50px);
    left: 0px;
    background-color: #6c757d29;
    z-index: 1000;
    display: none;
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

.dataTables_wrapper .dataTables_filter input {
    margin-left: 0.5em;
    padding: 4px 8px;
    border: 1px solid #999;
    border-radius: 8px;
}

.dataTables_wrapper .dataTables_length select {
    margin-left: 0.25em;
    margin-right: 0.25em;
    padding: 0px 8px;
    border: 1px solid #999;
    border-radius: 8px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading.show {
    display: block;
}

.select2-container--default .select2-selection--single {
    border-radius: 1rem;
}

[data-theme-version="dark"] .leaflet-layer,
[data-theme-version="dark"] .leaflet-control-zoom-in,
[data-theme-version="dark"] .leaflet-control-zoom-out,
[data-theme-version="dark"] .leaflet-control-attribution {
    filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%);
}

[data-theme-version="dark"] .leaflet-control a {
    color: var(--sidebar-bg);
}

[data-theme-version="dark"] .leaflet-control-zoom a  {
    color: var(--bs-white);
    background-color: var(--title);
}

.card .leaflet-control-attribution, .leaflet-control-scale-line {
    margin-right: 28px !important;
}

/* .card .leaflet-container {
    border-bottom-right-radius: 1.75rem;
    border-bottom-left-radius: 1.75rem;
} */

/* .card .card-body:has(.leaflet-container) {
    padding: 0px;
} */

@media only screen and (max-width: 784px) {
    /* .header .header-content {
        padding-left: 25px !important;
        padding-right: 25px !important;
    } */

    .dataTables_wrapper .dataTables_length {
        float: none;
        padding-top: 0.755em;
    }

    .accordion__body .form-control {
        margin-bottom: 4px !important;
    }

    td > button {
        display: block;
        margin-bottom: 4px;
        width: 100%;
    }

    .header-profile .dropdown-menu {
        margin-top: -8px;
    }

    .page-greeting {
        display: block;
        margin-left: 0;
        margin-right: 0;
        margin-bottom: 30px;
        padding: 15px 20px;
        margin-top: -15px;
        border-radius: 16px;
        background-color: #fff;
    }

    .page-greeting .breadcrumb {
        margin-bottom: 0px !important;
    }

    .page-titles {
        margin-top: 0px !important;
    }

    .nav-header .brand-logo {
        display: none;
    }

    .header-profile img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .header-profile > a.nav-link .header-info {
        display: none;
    }

    .navbar-expand .navbar-nav .nav-link {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        border: 1px solid #000000;
        padding: 2px !important;
        /* background-color: var(--primary); */
    }

    /* .dlabnav {
        padding-left: 15px !important;
        padding-right: 15px !important;
    } */

    .header {
        padding-left: 70px !important;
        padding-right: 20px !important;
    }

    .nav-header {
        padding-left: 15px !important;
        padding-right: 15px !important;
        top: 0px;
        background-color: transparent;
        height: 5rem !important;
    }

    .nav-control {
        position: absolute;
        right: unset !important;
        left: 20px !important;
        top: 48px !important;
    }

    /* .header-left {
        margin-top: 10px;
    } */

    .header-left .app-name-short {
        display: block !important;
        color: #000000;
        font-size: 34px;
        margin-top: 10px;
    }

    [data-theme-version="dark"] .header-left .app-name-short {
        color: #fff;
    }

    .header-left .app-name-long {
        display: none;
    }
}

@media only screen and (min-width: 785px) {
    .page-greeting {
        display: none;
    }

    .nav-header {
        width: 6.25rem !important;
        left: 1.25rem !important;
        top: 0.75rem;
        /* top: 0px;
        background-color: transparent; */
    }

    .header {
        padding-left: 150px !important;
        padding-right: 30px !important;
    }

    .header-left .app-name-short {
        display: block !important;
        color: #000000;
        font-size: 38px;
        font-weight: 600;
        margin-top: 10px;
    }

    [data-theme-version="dark"] .header-left .app-name-short {
        color: #fff;
    }

    .header-left .app-name-long {
        display: none !important;
    }
}            

@media only screen and (min-width: 768px) {

    /* .header {
        padding-left: 150px !important;
        padding-right: 30px !important;            
    } */

    .nav-header {
        padding-left: 30px !important;
        padding-right: 30px !important;
    }

    .nav-control {
        left: 35px !important;
    }
}

@media (min-width: 1041px) {
    .nav-header {
        width: 75% !important;
        max-width: 800px !important;
        left: 0px !important;
        padding-left: 40px !important;
    }

    .nav-header .brand-logo {
        padding-left: 0px !important;
        padding-right: 0px !important;
        margin-top: 4px; 
    }

    .header {
        padding-left: 40px !important;
        padding-right: 40px !important;
    }

    .header .header-content {
        padding-left: 80px !important;
    }

    .header-left .app-name-short {
        display: none !important;
    }

    .header-left .app-name-long {
        display: block !important;
        color: #000000;
        font-size: 38px;
        font-weight: 600;
        margin-top: 4px;
    }
}

@media (min-width: 1200px) {
    .dlabnav {
        width: 1199px !important;
        left: calc((100% - 1199px) / 2) !important;
    }

    .nav-header {
        width: auto !important;
        left: calc((100% - 1199px) / 2) !important;
        padding-left: 40px !important;
    }

    .header {
        width: 1199px !important;
        right: calc((100% - 1199px) / 2) !important;
        padding-right: 40px !important;
    }
}
</style>
