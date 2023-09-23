<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Limitless - Responsive Web Application Kit by Eugene Kopyov</title>
    @include('layouts.header')
    @yield('cssStyle')




</head>
<body>
@include('layouts.navbar')
<div class="page-content">
    @include('layouts.sidebar')
    <div class="content-wrapper">
        <div class="content-inner">
            @yield('content')
            @include('layouts.footer')
        </div>
    </div>
</div>
@include('layouts.notifications')
@include('layouts.config')

<script type="module">

    /* ------------------------------------------------------------------------------
 *
 *  # Buttons extension for Datatables. HTML5 examples
 *
 *  Demo JS code for datatable_extension_buttons_html5.html page
 *
 * ---------------------------------------------------------------------------- */
    // Setup module
    // ------------------------------
    const DatatableButtonsHtml5 = function() {


        //
        // Setup module components
        //

        // Basic Datatable examples
        const _componentDatatableButtonsHtml5 = function() {
            if (!$().DataTable) {
                console.warn('Warning - datatables.min.js is not loaded.');
                return;
            }
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                dom: '<"datatable-header justify-content-start"f<"ms-sm-auto"l><"ms-sm-3"B>><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
                }
            });
        };
        return {
            init: function() {
                _componentDatatableButtonsHtml5();
            }
        }
    }();
    // Initialize module
    // ------------------------------
    document.addEventListener('DOMContentLoaded', function() {
        DatatableButtonsHtml5.init();
    });
</script>
@yield('jscript')


</body>
</html>
