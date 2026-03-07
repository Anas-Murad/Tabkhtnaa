@extends('admin.layouts.app')
@section('content')

    <!-- Content area -->
    <div class="content">
        <!-- Scrollable datatable -->
        @include('admin.layouts.alert-area')
        <div class="row">

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">تفاصيل فترة التمييز</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>الحقل</th>
                                        <th class="text-center">القيمه</th>
                                    </tr>
                                    </thead>
                                    <tbody>


                                    <tr>
                                        <th class="text-nowrap" scope="row">الفتره</th>
                                        <td>
                                            <b>من</b>
                                            {{$prev_distinction_at ?? $user->created_at->toDateString()}}
                                            <br>
                                            <b>الى</b>
                                            {{now()->toDateString()}}
                                        </td>
                                    </tr>


                                    @if($user->PeriodTotalOrders)
                                        <tr>
                                            <th class="text-nowrap" scope="row">طلبات خلال الفتره</th>
                                            <td>{{$user->PeriodTotalOrders}}</td>
                                        </tr>
                                    @endif




                                    @if($user->PeriodTotalSanctions)
                                        <tr>
                                            <th class="text-nowrap" scope="row">عقوبات خلال الفتره</th>
                                            <td>{{$user->PeriodTotalSanctions}}</td>
                                        </tr>
                                    @endif


                                    @if($revenues)
                                        <tr>
                                            <th class="text-nowrap" scope="row">الايرادات خلال الفتره</th>
                                            <td>{{$revenues}}</td>
                                        </tr>
                                    @endif



                                    @if($user->TotalOrders)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> كل الطلبات</th>
                                            <td>{{$user->TotalOrders}}</td>
                                        </tr>
                                    @endif




                                    @if($user->TotalSanctions)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> كل العقوبات</th>
                                            <td>{{$user->TotalSanctions}}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th class="pb-2 pt-2 text-nowrap" scope="row"> كل الايرادات</th>
                                        <td class="pb-2 pt-2">{{$revenuesTotal}}</td>
                                    </tr>

                                    <tr>
                                        <th class="pb-2 pt-2 text-nowrap" scope="row"> الحالة</th>
                                        <td class="pb-2 pt-2">{{__('messages.'.$UserDistinction->status)}}</td>
                                    </tr>

                                    <tr>
                                        <th class="pb-2 pt-2 text-nowrap" scope="row"> تبدأ فتره التمييز من</th>
                                        <td class="pb-2 pt-2">{{$UserDistinction->from_date}}</td>
                                    </tr>

                                    <tr>
                                        <th class="pb-2 pt-2 text-nowrap" scope="row"> تنتهي فتره التمييز في</th>
                                        <td class="pb-2 pt-2">{{$UserDistinction->to_date}}</td>
                                    </tr>


                                    @if(false)

                                        <tr>
                                            <th class="text-nowrap" colspan="2">


                                                <div class="card-header">
                                                    <h5 class="mb-0"> تتبع حاله الطلب</h5>
                                                </div>
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>الحدث</th>
                                                        <th>بواسطة</th>
                                                        <th class="text-center">الوقت والتاريخ</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    @foreach($order->orderStatus as $orderStatus)

                                                        <tr>
                                                            <th class="text-nowrap">{{__('messages.status_'.$orderStatus->status)}}</th>
                                                            <th class="text-nowrap">{{__('messages.'.$orderStatus->action_by_type)}}
                                                                ({{$orderStatus->actionBy->name}})
                                                            </th>
                                                            <th class="text-nowrap">{{$orderStatus->created_at->toDateTimeString()}}</th>

                                                        </tr>
                                                    @endforeach

                                                    </tbody>
                                                </table>


                                            </th>
                                        </tr>
                                    @endif


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">العمليات</h5>
                            </div>
                            <div class="card-body border-top">
                                <div class="row gx-1 gy-1">

                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-info d-block w-100 border-0">
                                            عرض ملف الشخصي للعميل
                                        </button>
                                    </div>

                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-yellow d-block w-100 border-0">
                                            عرض طلبات للعميل
                                        </button>
                                    </div>


                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-dark d-block w-100 border-0">
                                            عرض كل فترات التمييز للعميل
                                        </button>
                                    </div>


                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary d-block w-100 border-0">
                                            عرض كل ايرادات العميل
                                        </button>
                                    </div>


                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-info d-block w-100 border-0">
                                            مراسله العميل
                                            <i class="ph-chat ms-2"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-warning d-block w-100 border-0">
                                            ارسال اشعار للعميل
                                            <i class="ph-chat ms-2"></i>
                                        </button>
                                    </div>


                                    @if($UserDistinction->status =='new')

                                        <div class="col-md-6 mt-3">
                                            <button type="submit" class="btn btn-success d-block w-100 border-0"
                                                    onclick="Approved()"
                                            >
                                                الموافقه على حاله التمييز

                                            </button>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <button type="submit" class="btn btn-danger d-block w-100 border-0"
                                                    onclick="Reject()"
                                            >
                                                رفض حاله التمييز
                                            </button>
                                        </div>


                                        <div class="col-md-12 mt-3" style="display: none" id="approvedArea">

                                            <form method="post"  action="{{ route('admin.distinction.approved' , $UserDistinction) }}" >
                                            @csrf
                                            <div class="row g-1">
                                                <div class="col-md-4">


                                                    <label class="form-label">من تاريخ:</label>
                                                    <input type="date" placeholder="From Date" class="form-control"
                                                           name="from_date" required
                                                           value="{{$UserDistinction->from_date}}"
                                                    >


                                                </div>
                                                <div class="col-md-4">


                                                    <label class="form-label">الى تاريخ:</label>
                                                    <input type="date" placeholder="From Date" class="form-control"
                                                           name="to_date" required
                                                           value="{{$UserDistinction->to_date}}"
                                                    >


                                                </div>
                                                <div class="col-md-4">

                                                    <button type="submit"
                                                            class="mt-4 btn btn-success d-block w-100 border-0">
                                                        تاكيد منح التمييز
                                                    </button>


                                                </div>
                                            </div>
                                            </form>
                                        </div>

                                    @endif


                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="col-md-6">

                <div class="row">
                    @if($user)
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">تفاصيل العميل</h5>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>الحقل</th>
                                            <th class="text-center">القيمه</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($user->type)
                                            <tr>
                                                <th class="text-nowrap" scope="row">نوع الحساب</th>
                                                <td>{{__('messages.'.$user->type)}}</td>
                                            </tr>
                                        @endif
                                        @if($user->name)
                                            <tr>
                                                <th class="text-nowrap" scope="row"> الاسم</th>
                                                <td>{{$user->name}}</td>
                                            </tr>
                                        @endif
                                        @if($user->residenceCountry)
                                            <tr>
                                                <th class="text-nowrap" scope="row"> بلد الاقامة</th>
                                                <td>{{$user->residenceCountry->name}}</td>
                                            </tr>
                                        @endif
                                        @if($user->country_code || $user->mobile)
                                            <tr>
                                                <th class="text-nowrap" scope="row"> رقم الهاتف</th>
                                                <td>
                                                    <a href="tel:{{$user->country_code}}{{$user->mobile}}">{{$user->country_code}}{{$user->mobile}}</a>
                                                </td>
                                            </tr>
                                        @endif
                                        @if($user->gender)
                                            <tr>
                                                <th class="text-nowrap" scope="row"> الجنس</th>
                                                <td>{{__('messages.'.$user->gender)}}</td>
                                            </tr>
                                        @endif
                                        @if($user->def_lang)
                                            <tr>
                                                <th class="pb-2 pt-2 text-nowrap" scope="row"> اللغه الافتراضيه</th>
                                                <td class="pb-2 pt-2 ">{{__('messages.def_lang_'.$user->def_lang)}}</td>
                                            </tr>
                                        @endif


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">تفاصيل اعدادات الدوله </h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>الحقل</th>
                                        <th class="text-center">القيمه</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr>
                                        <th class="text-nowrap" scope="row"> اسم الدولة</th>
                                        <td>{{ $country->name  }}</td>
                                    </tr>

                                    <tr>
                                        <th class="text-nowrap" scope="row"> معدل بلوغ حد استبدال النقاط</th>
                                        <td>{{ $client_points_limit }}</td>
                                    </tr>


                                    <tr>
                                        <th class="text-nowrap" scope="row"> معدل بلوغ حد الطلبات للسائق</th>
                                        <td>{{ $distinction_delivery_orders }}</td>
                                    </tr>


                                    <tr>
                                        <th class="text-nowrap" scope="row"> معدل بلوغ حد الايرادات للسائق</th>
                                        <td>{{ $distinction_delivery_revenues }}</td>
                                    </tr>

                                    <tr>
                                        <th class="text-nowrap" scope="row"> اقصى معدل بلوغ حد عقوبات للسائق</th>
                                        <td>{{ $distinction_delivery_sanctions }}</td>
                                    </tr>


                                    <tr>
                                        <th class="text-nowrap" scope="row"> معدل بلوغ حد الطلبات للطاهي</th>
                                        <td>{{ $distinction_chef_orders }}</td>
                                    </tr>


                                    <tr>
                                        <th class="text-nowrap" scope="row"> معدل بلوغ حد الايرادات للطاهي</th>
                                        <td>{{ $distinction_chef_revenues }}</td>
                                    </tr>

                                    <tr>
                                        <th class="pb-2 pt-2text-nowrap" scope="row"> اقصى معدل بلوغ حد عقوبات للطاهي
                                        </th>
                                        <td class="pb-2 pt-2 ">{{ $distinction_chef_sanctions }}</td>
                                    </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">بلوغ مرحله التميز اخر 10 سجلات</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>من تاريخ</th>
                                <th>الى تاريخ</th>
                                <th> الحاله / القرار</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($OldUserDistinctionList as $Distinction)
                                <tr>
                                    <th class="text-nowrap">
                                        {{ $Distinction->from_date  }}
                                    </th>
                                    <th class="text-nowrap">
                                        {{ $Distinction->to_date  }}
                                    </th>
                                    <th class="text-nowrap">
                                        {{ __('messages.'.$Distinction->status)  }}
                                    </th>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
@section('jscript')

    <script>

        function Approved() {
            $('#approvedArea').slideDown()
        }


        function Reject() {
            swalInit.fire({
                title: 'هل انت متأكد',
                text: "لن تتمكن من التراجع ابدا عن هذا الاختيار",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم , تأكيد الرفض',
                cancelButtonText: 'لا, الغاء الرفض',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                }
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: "{{route('admin.distinction.Reject' , $UserDistinction)}}",
                        method: "POST",
                        data: {
                            '_token': "{{csrf_token()}}",
                        },
                        success: function (result) {
                            if (result !== true)
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
                                    'تم الرفض',
                                    'تم الرفض  بنجاح',
                                    'success'
                                );
                                location.reload();
                            } else {
                                swalInit.fire(
                                    'حدث خطا ما',
                                    'لم يتم الرفض بسبب مشكله في الخادم !',
                                    'error'
                                );
                            }
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            swalInit.fire(
                                'حدث خطا ما',
                                'لم يتم الرفض بسبب مشكله في الخادم او لم يعد لديك صلاحيات كافيه !',
                                'error'
                            );
                            return;
                        }
                    });
                } else if (result.dismiss === swal.DismissReason.cancel) {
                    swalInit.fire(
                        'تم الرفض',
                        'تم الغاء الرفض ',
                        'error'
                    );
                }
            });
        }
    </script>
@endsection
