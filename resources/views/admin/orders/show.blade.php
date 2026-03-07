@extends('admin.layouts.app')
@section('content')

    <!-- Content area -->
    <div class="content">
        <!-- Scrollable datatable -->
        @include('admin.layouts.alert-area')
        <div class="row">

            <div class="col-md-6">
                <div class="row">
                    @if(session()->has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">تفاصيل الطلب</h5>
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


                                    @if($order->id)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> رقم الطلب</th>
                                            <td>{{$order->id}}</td>
                                        </tr>
                                    @endif


                                    @if($order->delivery_fees)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> عموله التوصيل</th>
                                            <td>{{$order->delivery_fees}}</td>
                                        </tr>
                                    @endif

                                    @if($order->tax)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> الضريبه</th>
                                            <td>{{$order->tax}}</td>
                                        </tr>
                                    @endif
                                    @if($order->sub_total)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> مجموع</th>
                                            <td>{{$order->sub_total}}</td>
                                        </tr>
                                    @endif
                                    @if($order->discount)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> خصم</th>
                                            <td>{{$order->discount}}</td>
                                        </tr>
                                    @endif


                                    @if($order->total)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> اجمالي</th>
                                            <td>{{$order->total}}</td>
                                        </tr>
                                    @endif


                                    @if($order->delivery_type)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> نوع التوصيل</th>
                                            <td>{{  __('messages.delivery_type_' . $order->delivery_type)  }}</td>
                                        </tr>
                                    @endif

                                    @if($order->expected_order_time)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> الوقت المتوقع للطلب</th>
                                            <td>{{$order->expected_order_time}}</td>
                                        </tr>
                                    @endif

                                    @if($order->estimated_delivery_time)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> الوقت المتوقع للتوصيل</th>
                                            <td>{{$order->estimated_delivery_time}}</td>
                                        </tr>
                                    @endif

                                    @if($order->estimated_time)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> اجمالب الوقت المتوقع</th>
                                            <td>{{$order->estimated_time}}</td>
                                        </tr>
                                    @endif

                                    @if($order->status)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> حالة الطلب</th>
                                            <td>{{__('messages.status_'.$order->status)}}</td>
                                        </tr>
                                    @endif


                                    @if($order->created_at)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> تاريخ / وقت انشاء الطلب</th>
                                            <td>{{$order->created_at->toDateTimeString()}}</td>
                                        </tr>
                                    @endif

                                    @if($order->updated_at)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> تاريخ / وقت اخر تحديث</th>
                                            <td>{{$order->updated_at->toDateTimeString()}}</td>
                                        </tr>
                                    @endif


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


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"> العنوان</h5>
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
                                    @if($order->name)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> اسم العنوان</th>
                                            <td>{{$order->name}}</td>
                                        </tr>
                                    @endif

                                    @if($order->address->place)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> المكان</th>
                                            <td>{{$order->address->place}}</td>
                                        </tr>
                                    @endif


                                    @if($order->address->country)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> الدولة</th>
                                            <td>{{$order->address->country->name}}</td>
                                        </tr>
                                    @endif


                                    @if($order->address->city)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> المدينة</th>
                                            <td>{{$order->address->city->name}}</td>
                                        </tr>
                                    @endif




                                    @if($order->address->neighborhood)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> الحي / المنطقه</th>
                                            <td>{{$order->address->neighborhood}}</td>
                                        </tr>
                                    @endif
                                    @if($order->address->build_address)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> عنوان البنايه</th>
                                            <td>{{$order->address->build_address}}</td>
                                        </tr>
                                    @endif
                                    @if($order->address->floor)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> الطابق</th>
                                            <td>{{$order->address->floor}}</td>
                                        </tr>
                                    @endif
                                    @if($order->address->apartment_address)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> عنوان الشقه</th>
                                            <td>{{$order->address->apartment_address}}</td>
                                        </tr>
                                    @endif
                                    @if($order->address->details)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> التفاصيل</th>
                                            <td>{{$order->address->details}}</td>
                                        </tr>
                                    @endif
                                    @if($order->address->latitude && $order->address->longitude)
                                        <tr class="pt-1 pb-1">
                                            <th class="text-nowrap pt-1 pb-1" scope="row"> العرض على الخريطه</th>
                                            <td class="pt-1 pb-1 ">
                                                <a target="_blank"
                                                   href="https://www.google.com/maps/?q={{$order->address->latitude}},{{$order->address->longitude}}">{{$order->address->latitude}}
                                                    ,{{$order->address->longitude}}</a>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>


                        </div>

                    </div>


                </div>
            </div>


            <div class="col-md-6">

                <div class="row">
                    @if($order->user)
                        @php
                            $user = $order->user;
                        @endphp
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



                    @if($order->chef)

                        @php
                            $user = $order->chef;
                        @endphp
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">تفاصيل الطاهي</h5>
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


                    @if($order->delivery)

                        @php
                            $user = $order->delivery;
                        @endphp
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">تفاصيل الموصل</h5>
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
                    @if($order->transaction)
                        @php
                            $transaction = $order->transaction;
                        @endphp
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"> الحاله المالية</h5>
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


                                        @if($transaction->payment_method)
                                            <tr>
                                                <th class="text-nowrap" scope="row">طريقة الدفع</th>
                                                <td>{{$transaction->payment_method}}</td>
                                            </tr>
                                        @endif



                                        @if($transaction->amount)
                                            <tr>
                                                <th class="text-nowrap" scope="row">المبلغ</th>
                                                <td>{{$transaction->amount}} {{$transaction->currency}}</td>
                                            </tr>
                                        @endif





                                        @if($transaction->status)
                                            <tr>
                                                <th class="text-nowrap" scope="row">حالة المدفوعات</th>
                                                <td>{{__('messages.'.$transaction->status)}}</td>
                                            </tr>
                                        @endif




                                        @if($transaction->admin_status)
                                            <tr>
                                                <th class="text-nowrap" scope="row">تأكيد الادمن على العملية</th>
                                                <td>{{__('messages.'.$transaction->admin_status)}}</td>
                                            </tr>
                                        @endif


                                        @if($transaction->created_at)
                                            <tr>
                                                <th class="pb-2 pt-2 text-nowrap" scope="row"> تاريخ / وقت انشاء العملية</th>
                                                <td  class="pb-2 pt-2" >{{ $transaction->created_at->toDateTimeString()  }}</td>
                                            </tr>
                                        @endif

                                        @if($transaction->updated_at)
                                            <tr>
                                                <th class="pb-2 pt-2 text-nowrap" scope="row"> تاريخ / وقت اخر تحديث</th>
                                                <td class="pb-2 pt-2"> {{ $transaction->updated_at->toDateTimeString()  }}</td>
                                            </tr>
                                        @endif


                                        @if($transaction->admin_notes)
                                            <tr>
                                                <th class="text-nowrap" scope="row">ملاحظة الادمن</th>
                                                <td>{{ $transaction->admin_notes  }}</td>
                                            </tr>
                                        @endif


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    @endif
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">العمليات</h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row gx-1 gy-1">
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-info d-block w-100 border-0">
                                    مراسله الطاهي
                                    <i class="ph-chat ms-2"></i>
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-warning d-block w-100 border-0">
                                    مراسله السائق
                                    <i class="ph-chat ms-2"></i>
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-dark d-block w-100 border-0">
                                    مراسله العميل
                                    <i class="ph-chat ms-2"></i>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-danger d-block w-100 border-0">
                                    الغاء الطلب واعاده المبلغ المالي
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-yellow d-block w-100 border-0">
                                    اعادة مبلغ مالي في محفظه العميل
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" onclick="sanctionModal({{$order->chef->id}} , '{{$order->chef->name}}' , 'chef')" class="btn btn-primary d-block w-100 border-0">
                                ادراج عقوبه الطاهي
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" onclick="sanctionModal({{$order->delivery->id}} , '{{$order->delivery->name}}' , 'delivery')"  class="btn btn-yellow d-block w-100 border-0">
                                    ادراج عقوبه السائق
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button type="button" onclick="sanctionModal({{$order->user->id}} , '{{$order->user->name}}' , 'user' )" class="btn btn-warning d-block w-100 border-0">
                                    ادراج عقوبه العميل
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">الوجبات المطلوبه</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>اسم الوجبه</th>
                                <th> الكمية</th>
                                <th> السعر</th>
                                <th> خصم على الوجبه</th>
                                <th> سعر الاضافات</th>
                                <th> اجمالي</th>
                                <th> الاضافات</th>
                                <th> الاكسسوارات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->orderMeal as $orderMeal)
                                <tr>
                                    <th class="text-nowrap">
                                        {{ $orderMeal->meal_name  }}
                                    </th>
                                    <th class="text-nowrap">
                                        {{ $orderMeal->quantity  }}
                                    </th>
                                    <th class="text-nowrap">
                                        {{ $orderMeal->price  }}
                                    </th>
                                    <th class="text-nowrap">
                                        {{ $orderMeal->discount  }}
                                    </th>
                                    <th class="text-nowrap">
                                        {{ $orderMeal->additions_price  }}
                                    </th>
                                    <th class="text-nowrap">
                                        {{ $orderMeal->total  }}
                                    </th>
                                    <th class="text-nowrap">
                                        {{ $orderMeal->accessories->pluck('name')->join(' , ') }}
                                    </th>
                                    <th class="text-nowrap">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>الاسم</th>
                                                <th>السعر</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($orderMeal->additions as $addition)
                                                <tr>
                                                    <th class="text-nowrap">{{$addition->name}}</th>
                                                    <th class="text-nowrap">{{$addition->price}}</th>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </th>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if($order->TransactionHistory)

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">محاولات الدفع المرتبطه بالطلب</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>

                                    <th >طريقة الدفع</th>
                                    <th >المبلغ</th>
                                    <th >حالة المدفوعات</th>
                                    <th >تأكيد الادمن على العملية</th>
                                    <th > تاريخ / وقت انشاء العملية</th>
                                    <th > تاريخ / وقت اخر تحديث</th>
                                    <th >-</th>


                                </tr>
                                </thead>
                                <tbody>

                                @foreach($order->TransactionHistory as $transaction )
                                    <tr>
                                        <td>{{$transaction->payment_method}}</td>
                                        <td>{{$transaction->amount}} {{$transaction->currency}}</td>
                                        <td>{{__('messages.'.$transaction->status)}}</td>
                                        <td>{{__('messages.'.$transaction->admin_status)}}</td>
                                        <td>{{ $transaction->created_at->toDateTimeString()  }}</td>
                                        <td> {{ $transaction->updated_at->toDateTimeString()  }}</td>
                                        <td>

                                            <a  class="btn btn-yellow   border-0">
                                                تفاصيل / Log
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            @endif
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="createSanctionModal" tabindex="-1" aria-labelledby="CreateSanctionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CreateSanctionModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('sanctions.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input class="form-control" type="hidden"  name="order_id" value="{{$order->id}}" >
                        <input class="form-control" type="hidden"  id="user_id" name="user_id" >
                        <div class="p-1">
                            <label>User Name: </label>
                            <input class="form-control" type="text" id="nameM" value="" readonly>
                        </div>
                        <div class="p-1">
                            <label>Sanction Type :</label>
                            <select class="form-control"  name="type" required>
                                <option value="">Select Sanction Type</option>
                                <option value="financial_violation">financial_violation</option>
                                <option value="make_block">make_block</option>
                                <option value="no_order_request">no_order_request</option>
                                <option value="no_chat">no_chat</option>
                            </select>
                        </div>
                        <div class="p-1">
                            <label>Note :</label>
                            <textarea class="form-control" type="text"  name="note"></textarea>
                        </div>
                        <div class="p-1">
                            <label>Photo :</label>
                            <input class="form-control" type="file"  name="photo">
                        </div>
                        <div class="p-1">
                            <label>Start Time :</label>
                            <input class="form-control" type="datetime-local"  name="start_time" required>
                        </div>
                        <div class="p-1">
                            <label>End Time :</label>
                            <input class="form-control" type="datetime-local"  name="end_time" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('jscript')
    <script>
        function sanctionModal(id, name, type) {
            $('#user_id').val(id);

            if (type == 'user') {
                $('#CreateSanctionModalLabel').html('ادراج عقوبه العميل');
                $('#nameM').val(name);
            } else if (type == 'delivery') {
                $('#CreateSanctionModalLabel').html('ادراج عقوبه السائق');
                $('#nameM').val(name);
            } else if (type == 'chef') {
                $('#CreateSanctionModalLabel').html('ادراج عقوبه الطاهي');
                $('#nameM').val(name);
            }

            $('#createSanctionModal').modal("show");
        }
    </script>
@endsection
