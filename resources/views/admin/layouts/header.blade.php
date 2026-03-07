<link href="{{asset('assets/fonts/inter/inter.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/icons/phosphor/styles.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/rtl/all.min.css')}}" id="stylesheet" rel="stylesheet" type="text/css">


<script src="{{asset('assets/demo/demo_configurator.js')}}"></script>
<script src="{{asset('assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
<!-- /core JS files -->

<!-- Theme JS files -->
<script src="{{asset('assets/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/forms/validation/validate.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>


<script src="{{asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/tables/datatables/extensions/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/tables/datatables/extensions/pdfmake/vfs_fonts.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/tables/datatables/extensions/buttons.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/tables/datatables/extensions/select.min.js')}}"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/0.10.0/lodash.min.js"></script>


<script src="{{asset('assets/js/vendor/notifications/sweet_alert.min.js')}}"></script>



<!-- Theme JS files -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbF9O9Ks9_-QNWHi2SFxLqLUBOwrMyzXk"></script>


<script src="{{asset('assets/js/app.js')}}"></script>
<script src="{{asset('assets/demo/pages/form_layouts.js')}}"></script>




<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">

<style>
    *:not(i)
    ,h1
    ,h2
    ,h3
    ,h4
    ,h5
    ,h6
    ,b
    ,div
    {
        font-family: 'Cairo', sans-serif;
    }
    .relative {
        position: relative;
    }
    .trash_link {
        position: absolute;
        right: 15px;
        left: auto;
        width: fit-content;
        display: block;
        /* background: red; */
        text-align: center;
        color: red;
        border: 1px solid;
        border-radius: 5px;
        padding: 2px;
    }
    .channel_row.row.relative:first-child .trash_link {
        display: none;
    }
    .lead_actors_row.row.relative:first-child .trash_link {
        display: none;
    }
    tr:last-child td{
        padding-bottom: 190px;
    }
    .validation-invalid-label, .validation-valid-label {
        display: inline-block;
        margin-left: 1px;
        padding-left: 2px;
    }

    .validation-invalid-label, .validation-valid-label {
        display: inline-block;
        margin-left: 8px;
    }

    .validation-invalid-label:before, .validation-valid-label:before {
        display: none;
    }


    .loader {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: inline-block;
        border-top: 4px solid #FFF;
        border-right: 4px solid #c0bdbd;
        box-sizing: border-box;
        animation: rotation 1s linear infinite;
    }
    .loader::after {
        content: '';
        box-sizing: border-box;
        position: absolute;
        left: 0;
        top: 0;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border-left: 4px solid #e31d1b;
        border-bottom: 4px solid #252b36;
        animation: rotation 0.5s linear infinite reverse;
    }
    @keyframes rotation {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    div#loader10 {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        top: 0;
        background: #ffffffd9;
        z-index: 999;
        display: flex;
        align-items: center;
        justify-content: center;
        align-content: center;
    }

    img.cell2 {
        width: 150px;
        height: 100px;
        object-fit: cover;
    }


    [dir=rtl] .nav-item:not([class*=mega-menu]) .navbar-nav-link~.dropdown-menu, [dir=rtl] .navbar-nav>.nav-item:not([class*=mega-menu])>.dropdown-menu {
        left: 0;
        right: auto !important;
        width: 308px!important;
    }


    .swal2-textarea {
        padding: 9px 15px;
        width: 100%;
        margin-top: 8px;
    }

    label.swal2-input-label {
        font-weight: bold;
        margin-top: 15px;
    }


    .contented_div {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 68vh;
    }
    /*



    .page-header-light {
        --page-header-bg: #018355;
        color: #FFF !important;
    }

    .breadcrumb-item+.breadcrumb-item ,.breadcrumb-item i {
        color: #FFF !important;
    }
    */
    /*

    .nav-item-submenu>.nav-link i {
        color: #fa5c98;
    }

    .sidebar-dark {--sidebar-bg: #254476;}
    span.badge.bg-primary {
        background: #00a8d9 !important;
    }




    input.form-control[type=color] {
        height: 51px;
        width: 90px;
    }

    .is_correct_class:checked:after {
        content: 'هذه الاجابة الصحيحة';
        position: absolute;
        top: -30px;
        right: 100px;
        color: #059669;
    }


    .nav-sidebar .nav-link i {
        color: #fa5c98;
    }


    span.logoSpan {
        font-size: 26px;
        color: #254476;
        font-weight: bold;
        text-shadow: 2px 2px 1px #af497e;
        font-family: cursive;
    }

    */


    img.logo50 {
        width: 250px;
        height: 42px;
        object-fit: contain;
        /* background: #f36f0f; */
    }

    .sidebar-dark .nav-sidebar {
        --nav-sidebar-divider-color: #f3690d;
        --nav-link-color: rgba(255, 255, 255, 0.85);
        --nav-link-hover-color: #fff;
        --nav-link-hover-bg: #f3690d;
        --nav-link-hover-active-bg: rgba(var(--white-rgb), 0.15);
        --nav-link-active-color: var(--white);
        --nav-link-active-bg: #f3690d;
        --nav-link-disabled-color: rgba(var(--white-rgb), 0.5);
    }

</style>


{{--<script src="{{asset('assets/demo/pages/datatables_extension_buttons_html5.js')}}"></script>--}}


