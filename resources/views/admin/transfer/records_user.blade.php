@extends('admin.layouts.app')
@section('content')
    <div class="content">

{{--        <!-- Collapse/expand card -->
        <div class="card  card-collapsed">
            <div class="card-header d-flex align-items-center">
                <h6 class="mb-0">فلتره</h6>
                <div class="d-inline-flex ms-auto">
                    <a class="text-body" data-card-action="collapse">
                        فلتره
                        <i class="ph-caret-down"></i>
                    </a>
                </div>
            </div>

            <div class="collapse ">
                <div class="card-body">
                    <form action="#" id="filter_form">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <div class="row">

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">من تاريخ:</label>
                                                <input type="date" placeholder="From Date" class="form-control"
                                                       name="from_date"
                                                       value=""
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">الى تاريخ:</label>
                                                <input type="date" placeholder="From Date" class="form-control"
                                                       name="to_date"
                                                       value=""
                                                >
                                            </div>
                                        </div>


                                        <div class="col">
                                            <label class="form-label" style="visibility: hidden">From Date:</label>
                                            <div class="text-left">

                                                <button type="button"
                                                        onclick="window.LaravelDataTables['data-table'].ajax.reload() "
                                                        class="btn btn-secondary">ابحث <i
                                                        class="ph-file-search ms-2"></i></button>


                                                <button type="button"
                                                        onclick="location.reload() "
                                                        class="btn btn-warning">اعادة <i
                                                        class="ph-key-return ms-2"></i></button>


                                            </div>
                                        </div>


                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
        <!-- /collapse/expand card -->--}}

        <div class="row">

            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-indigo text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h4 class="mb-0">{{$orders}}</h4>
                            الاوردرات المكتمله
                        </div>
                        <i class="ph-list-checks ph-2x opacity-75 ms-3"></i>
                    </div>
                </div>
            </div>



            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h4 class="mb-0">{{$prices}}</h4>
                                اجمالي الايرادات
                        </div>

                        <i class="ph-money ph-2x opacity-75 ms-3"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-danger text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h4 class="mb-0">{{$pending_prices}}</h4>
                            المستحقات غير المدفوعه
                        </div>

                        <i class="ph-coin-vertical ph-2x opacity-75 ms-3"></i>
                    </div>
                </div>
            </div>


            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-success text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <h4 class="mb-0">{{$done_prices}}</h4>
                            المستحقات المدفوعه
                        </div>

                        <i class="ph-coin ph-2x opacity-75 ms-3"></i>
                    </div>
                </div>
            </div>



            </div>


        <div class="card">

            @include('admin.layouts.alert-area')


            <div class="card-header">
                <h5 class="mb-0">
                    {{$pageTitle}}
                </h5>
            </div>


            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table'] ) !!}
            </div>
        </div>
    </div>
@endsection
@section('jscript')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script>

        function adminChecked(url) {
            swalInit.fire({
                title: 'هل انت متأكد',
                text:  "هل تريد بالفعل تأكيد الاستحقاق , لن تتمكن ابدامن الرجوع عن هذا الخيار ",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم , تأكيد ',
                cancelButtonText: 'لا, الغاء ',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                } ,

                input: "textarea",
                inputLabel: "ضع ملاحظاتك هنا",
                inputPlaceholder: "يرجى كتابه ملاحظات تدل على عمليه التحقق التي تمت",
                inputAttributes: {
                    "aria-label": "يرجى كتابه ملاحظات تدل على عمليه التحقق التي تمت"
                },
            }).then(function (result) {



                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: "POST",
                        data: {
                            '_token': "{{csrf_token()}}",
                            'admin_notes':result.value,
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
                                    'تم الـاكيد',
                                    'تم الـاكيد بنجاح',
                                    'success'
                                );
                                window.LaravelDataTables["data-table"].ajax.reload()
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
@endsection
