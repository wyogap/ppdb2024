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

.header-profile > a.nav-link .header-info span {
    font-size: 16px;
    color: #000;
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
    height: 104px !important;
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

.accordion__body {
    background-color: white;
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

table.dataTable.display tbody td {
    color: var(--bs-gray-dark);
}

table.dataTable.display tbody td a {
    color: inherit;
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

table.dataTable thead th, table.dataTable thead td {
    border-top: solid 2px #000;
    border-bottom: solid 2px #000;
    color: #000;
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

@media only screen and (max-width: 784px) {
    /* .header .header-content {
        padding-left: 25px !important;
        padding-right: 25px !important;
    } */

    .dataTables_wrapper .dataTables_length {
        float: none;
        padding-top: 0.755em;
    }

    .form-control {
        margin-bottom: 4px;
    }

    td > button {
        display: block !important;
        margin-bottom: 4px;
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
        margin-top: 10px;
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
