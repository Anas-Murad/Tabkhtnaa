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
                                <h5 class="mb-0">تفاصيل الحركة الماليه</h5>
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
                                            <th class="text-nowrap" scope="row"> طريقه الدفع</th>
                                            <td>{{__('messages.payment_method_'.$transaction->payment_method)}}</td>
                                        </tr>
                                    @endif


                                    @if($transaction->order_id)
                                        <tr>
                                            <th class="text-nowrap" scope="row">رقم الطلب</th>
                                            <td>{{$transaction->order_id}}</td>
                                        </tr>
                                    @endif

                                    @if($transaction->payment_id)
                                        <tr>
                                            <th class="text-nowrap" scope="row">معرف عمليه الدفع</th>
                                            <td>{{$transaction->payment_id}}</td>
                                        </tr>
                                    @endif


                                    @if($transaction->service_type)
                                        <tr>
                                            <th class="text-nowrap" scope="row">رقم الطلب</th>
                                            <td>{{__('messages.service_type_'.$transaction->service_type)}}</td>
                                        </tr>
                                    @endif




                                    @if($transaction->amount)
                                        <tr>
                                            <th class="text-nowrap" scope="row">المبلغ</th>
                                            <td>{{$transaction->amount}}{{$transaction->currency}}</td>
                                        </tr>
                                    @endif


                                    @if($transaction->status)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> حاله الحركه</th>
                                            <td>{{__('messages.transaction_'.$transaction->status)}}</td>
                                        </tr>
                                    @endif

                                    @if($transaction->admin_status)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> تأكيد الادمن</th>
                                            <td>{{__('messages.transaction_admin_status_'.$transaction->admin_status)}}</td>
                                        </tr>
                                    @endif


                                    @if($transaction->admin_notes)
                                        <tr>
                                            <th class="text-nowrap" scope="row">ملاحظات الادمن</th>
                                            <td>{{$transaction->admin_notes}}</td>
                                        </tr>
                                    @endif

                                    @if($transaction->tried_again)
                                        <tr>
                                            <th class="text-nowrap" scope="row">تم اعادة المحاوله</th>
                                            <td>{{$transaction->tried_again ? "نعم" : "لا"}}</td>
                                        </tr>
                                    @endif







                                    @if($transaction->created_at)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> تاريخ الحركة</th>
                                            <td>{{$transaction->created_at}}</td>
                                        </tr>
                                    @endif
                                    @if($transaction->updated_at)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> تاريخ اخر تحديث</th>
                                            <td>{{$transaction->updated_at}}</td>
                                        </tr>
                                    @endif



                                    @if($transaction->tracking_data)
                                        <tr>
                                            <th class="pt-2 pb-2 text-nowrap" scope="row">تتبع العمليه #Log</th>
                                            <td class="pt-2 pb-2">
                                                <textarea rows="4"
                                                          class="form-control">{{json_encode($transaction->tracking_data)}}</textarea>
                                            </td>
                                        </tr>
                                    @endif



                                    @if($transaction->webhook)
                                        <tr>
                                            <th class=" pt-2 pb-2 text-nowrap" scope="row">#Webhook Log</th>
                                            <td class="pt-2 pb-2">
                                                <textarea rows="4"
                                                          class="form-control">{!! $transaction->webhook !!}</textarea>
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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">تفاصيل العميل</h5>
                            </div>
                            <div class="table-responsive">
                                @php
                                    $user = $transaction->user;
                                @endphp

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
                    </div>

                </div>
            </div>


            <div class="col-md-12">

                <div class="row">

                    @if(!$transaction->transferRecords->isEmpty())

                        @foreach($transaction->transferRecords as $transferRecords)

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">حصة #
                                        {{$transferRecords->to_type}}
                                        </h5>
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


                                            @if($transferRecords->to_type)
                                                <tr>
                                                    <th class="text-nowrap" scope="row">المستحق</th>
                                                    <td>{{$transferRecords->to_type}} </td>
                                                </tr>
                                            @endif
                                            @if($transferRecords->amount)
                                                <tr>
                                                    <th class="text-nowrap" scope="row">المبلغ المستحق</th>
                                                    <td>{{$transferRecords->amount}} </td>
                                                </tr>
                                            @endif

                                            @if($transferRecords->remainder)
                                                <tr>
                                                    <th class="text-nowrap" scope="row">المبلغ المتبقي</th>
                                                    <td>{{$transferRecords->remainder}} </td>
                                                </tr>
                                            @endif

                                            @if($transferRecords->percent)
                                                <tr>
                                                    <th class="text-nowrap" scope="row">النسبه من الاجمالي</th>
                                                    <td>{{$transferRecords->percent}} </td>
                                                </tr>
                                            @endif

                                            @if($transferRecords->admin_notes)
                                                <tr>
                                                    <th class="text-nowrap" scope="row"> ملاحظات الادمن</th>
                                                    <td>{{$transferRecords->admin_notes}} </td>
                                                </tr>
                                            @endif



                                            @if($transferRecords->transfer_status)
                                                <tr>
                                                    <th class="text-nowrap" scope="row">حالة التحويل الى الحساب</th>
                                                    <td>{{$transferRecords->transfer_status}} </td>
                                                </tr>
                                            @endif

                                                <tr>
                                                    <th class="text-nowrap" scope="row">تاريخ التحويل الى الحساب</th>
                                                    <td>{{$transferRecords->transfer_date}} </td>
                                                </tr>




                                            @if($transferRecords->created_at)
                                                <tr>
                                                    <th class="pt-2 pb-2 text-nowrap" scope="row"> تاريخ الحركة</th>
                                                    <td class="pt-2 pb-2">{{$transferRecords->created_at}}</td>
                                                </tr>
                                            @endif
                                            @if($transferRecords->updated_at)
                                                <tr>
                                                    <th class="pt-2 pb-2 text-nowrap" scope="row"> تاريخ اخر تحديث</th>
                                                    <td class="pt-2 pb-2">{{$transferRecords->updated_at}}</td>
                                                </tr>
                                            @endif





                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                        @endforeach

                    @endif


                </div>
            </div>
        </div>
    </div>
@endsection
@section('jscript')
@endsection
