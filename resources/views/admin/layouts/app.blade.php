<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Limitless - Responsive Web Application Kit by Eugene Kopyov</title>
    @include('admin.layouts.header')
    @yield('cssStyle')


</head>
<body>
<!-- Main navbar -->
@auth('admin')
    @include('admin.layouts.navbar')
@endauth
<!-- /main navbar -->
<!-- Page content -->
<div class="page-content">
    <!-- Main sidebar -->
    @auth('admin')
        @include('admin.layouts.sidebar')
    @endauth
    <!-- /main sidebar -->
    <!-- Main content -->
    <div class="content-wrapper">
        <!-- Inner content -->
        <div class="content-inner">
            <!-- Page header -->
            @auth('admin')
                @include('admin.layouts.pagesHeader')


                @yield('content')


                @include('admin.layouts.footer')
            @endauth
        </div>
        <!-- /inner content -->
    </div>
    <!-- /main content -->
</div>
<!-- /page content -->
@include('admin.layouts.notifications')
<!-- Demo config -->
@include('admin.layouts.config')



<script>
    let table;
    $.extend($.fn.dataTable.defaults, {
        autoWidth: true,
        fixedHeader: true,
        responsive: true,
        // dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        dom: '<"datatable-header justify-content-start"f<"ms-sm-auto"l><"ms-sm-3"B>><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span class="me-3">فلتره:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span class="me-3">عرض:</span> _MENU_',
            paginate: {
                'first': 'First',
                'last': 'Last',
                'next': document.dir == "rtl" ? '&larr;' : '&rarr;',
                'previous': document.dir == "rtl" ? '&rarr;' : '&larr;'
            }
        }
    });

    const swalInit = swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-light',
            denyButton: 'btn btn-light',
            input: 'form-control'
        }
    });

    function showNote(Note) {
        swalInit.fire(
            'التفاصيل',
            Note,
            'info'
        )
    }

    document.addEventListener('DOMContentLoaded', function () {
        $('#loader10').hide();
    });


    function DeleteFunction(url, message = null, reload = false) {
        swalInit.fire({
            title: 'هل انت متأكد',
            text: message ?? "هل تريد الحذف؟ سوف يتم حذف جميع المعلومات المرتبطة بما في ذلك المعلومات الفرعية ولن تتمكن من الاستعادة",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم , تأكيد الحذف',
            cancelButtonText: 'لا, الغاء الحذف',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            }
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: url,
                    method: "DELETE",
                    data: {
                        '_token': "{{csrf_token()}}",
                    },
                    success: function (result) {

                        if(result !== true)
                            if ("status" in result && result.status == false) {
                                swalInit.fire(
                                    'حدث خطا ما',
                                    result.error_msg,
                                    'error'
                                );
                                return;
                            }


                        if (result) {
                            swalInit.fire(
                                'تم الحذف',
                                'تم حذف السجل بنجاح',
                                'success'
                            );

                            if (reload) {
                                location.reload();
                            } else {
                                window.LaravelDataTables["data-table"].ajax.reload()
                            }
                        } else {
                            swalInit.fire(
                                'حدث خطا ما',
                                'لم يتم الحذف بسبب مشكله في الخادم !',
                                'error'
                            );
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        swalInit.fire(
                            'حدث خطا ما',
                            'لم يتم الحذف بسبب مشكله في الخادم او لم يعد لديك صلاحيات كافيه !',
                            'error'
                        );
                        return;
                    }
                });
            } else if (result.dismiss === swal.DismissReason.cancel) {
                swalInit.fire(
                    'تم الالغاء',
                    'تم الغاء الحذف ',
                    'error'
                );
            }
        });
    }


</script>


<!-- /demo config -->
@yield('jscript')
@stack('scripts')

</body>
</html>
