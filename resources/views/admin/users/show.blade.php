@extends('admin.layouts.app')
@section('content')

    <!-- Content area -->
    <div class="content">
        <!-- Scrollable datatable -->
        @include('admin.layouts.alert-area')
        @if ($user->type != 'user')
            @include('admin.users.processes')
        @endif
        <div class="row">

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">تفاصيل الحساب</h5>
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
                                    @if($user->email)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> البريد الالكتروني</th>
                                            <td><a href="mailto:{{$user->email}}"><{{$user->email}}/a></td>
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

                                    @if($user->dob)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> تاريخ الميلاد</th>
                                            <td>{{$user->dob}}</td>
                                        </tr>
                                    @endif

                                    @if($user->gender)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> الجنس</th>
                                            <td>{{__('messages.'.$user->gender)}}</td>
                                        </tr>
                                    @endif

                                    @if($user->source)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> مصدر التسجيل</th>
                                            <td>{{__('messages.'.$user->source)}}</td>
                                        </tr>
                                    @endif


                                    @if($user->def_lang)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> اللغه الافتراضيه</th>
                                            <td>{{__('messages.def_lang_'.$user->def_lang)}}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th class="text-nowrap" scope="row"> التحقق من رقم الجوال</th>
                                        <td>
                                            @if($user->mobile_verified)
                                                <span class="text-success">تم التحقق</span>
                                            @else
                                                <span class="text-danger">لم يتم التحقق</span>
                                            @endif


                                        </td>
                                    </tr>

                                    @if($user->mobile_verified)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> الحالة</th>
                                            <td>


                                                @switch($user->online_status)

                                                    @case('online')
                                                        <span class="text-success">اونلاين </span>
                                                        @break


                                                    @case('busy')
                                                        <span class="text-warning">مشغول </span>
                                                        @break


                                                    @case('unavailable')
                                                        <span class="text-danger">غير متاح </span>
                                                        @break

                                                    @case('available')
                                                        <span class="text-success"> متاح </span>
                                                        @break

                                                @endswitch

                                            </td>
                                        </tr>
                                    @endif




                                    @if($user->can_delivery)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> امكانيه التوصيل</th>
                                            <td>

                                                @switch($user->can_delivery)
                                                    @case('no')
                                                        <span
                                                            class="text-danger">{{__('messages.' . $user->can_delivery) }} </span>
                                                        @break

                                                    @case('request')
                                                        <span
                                                            class="text-warning"> {{__('messages.' . $user->can_delivery) }}</span>
                                                        @break


                                                    @case('yes')
                                                        <span
                                                            class="text-success"> {{__('messages.' . $user->can_delivery) }}  </span>
                                                        @break

                                                    @case('rejected')
                                                        <span
                                                            class="text-danger"> {{__('messages.' . $user->can_delivery) }} </span>
                                                        @break

                                                @endswitch

                                            </td>
                                        </tr>
                                    @endif



                                    @if($user->account_status)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> حالة الحساب</th>
                                            <td>

                                                @switch($user->account_status)
                                                    @case('pending')
                                                        <span
                                                            class="text-warning">{{__('messages.' . $user->account_status) }} </span>
                                                        @break

                                                    @case('active')
                                                        <span
                                                            class="text-success"> {{__('messages.' . $user->account_status) }}</span>
                                                        @break


                                                    @case('rejected')
                                                        <span
                                                            class="text-danger"> {{__('messages.' . $user->account_status) }}  </span>
                                                        @break

                                                    @case('blocked')
                                                        <span
                                                            class="text-dark"> {{__('messages.' . $user->account_status) }} </span>
                                                        @break

                                                @endswitch

                                            </td>
                                        </tr>
                                    @endif




                                    @if($user->account_comment)
                                        <tr>
                                            <th class="text-nowrap" scope="row">تعليق / ملاحظه</th>
                                            <td>{{$user->account_comment}}</td>
                                        </tr>
                                    @endif
                                    @if($user->created_at)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> تاريخ التسجيل</th>
                                            <td>{{$user->created_at}}</td>
                                        </tr>
                                    @endif
                                    @if($user->updated_at)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> تاريخ اخر تحديث</th>
                                            <td>{{$user->updated_at}}</td>
                                        </tr>
                                    @endif




                                    @if($user->sms_verify)
                                        <tr>
                                            <th class="text-nowrap" scope="row">اخر كود SMS</th>
                                            <td>{{$user->sms_verify}}</td>
                                        </tr>
                                    @endif





                                    @if($user->profile_image)
                                        <tr>
                                            <th class="text-nowrap" scope="row">الصوره الشخصيه</th>
                                            <td>

                                                <a target="_blank" href="{{asset($user->profile_image)}}">
                                                    <img src="{{asset($user->profile_image)}}" width="50">
                                                </a>

                                            </td>
                                        </tr>
                                    @endif


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    @if(!$user->documents->isEmpty())
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"> الملفات المرفقه</h5>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>اسم الملف</th>
                                            <th> حالة الملف</th>
                                            <th> ملاحظات</th>
                                            <th class="text-center">رابط الاستعراض</th>
                                        </tr>
                                        </thead>
                                        <tbody>


                                        @foreach($user->documents as $document)
                                            <tr>
                                                <th class="text-nowrap" scope="row">
                                                    {{__('messages.'.$document->type)}}
                                                </th>
                                                <th class="text-nowrap" scope="row">
                                                    {{__('messages.'.$document->status)}}
                                                </th>
                                                <th class="text-nowrap" scope="row">
                                                    {{ $document->note  }}
                                                </th>
                                                <td>
                                                    <a href="{{asset($document->file)}}"
                                                       target="_blank">
                                                        عرض الملف المرفق
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


            <div class="col-md-6">

                <div class="row">
                    <div class="col-md-12">


                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"> حالة الحساب</h5>
                            </div>

                            <div class="card-body">
                                حاله الحساب الان هي

@switch($user->account_status)


        @case('pending')
        <span class="text-bg-yellow"> جديد</span>
        @break

        @case('active')
        <span class="text-bg-success"> نشط</span>
        @break

        @case('rejected')
        <span class="text-bg-danger"> مرفوض</span>
        @break

        @case('blocked')
        <span class="text-bg-dark">محظور</span>
        @break


@endswitch


                            </div>



                            <div class="card-body border-top">
                                <form action="{{ route('users.update.status' ,  $user) }}"
                                      method="post">
                                   @csrf
                                 @method('PUT')


                                    <div class="row mb-3">
                                        <label class="form-label">اختر حاله الحساب</label>
                                        <select data-placeholder="اختر حاله الحساب" name="account_status"   required  class="form-control form-control-select2 ">
                                            <option value="pending" @selected("pending" == $user->account_status)>{{__('messages.pending')}}</option>
                                            <option value="active" @selected("active" == $user->account_status)>{{__('messages.active')}}</option>
                                            <option value="rejected" @selected("rejected" == $user->account_status)>{{__('messages.rejected')}}</option>
                                            <option value="blocked" @selected("blocked" == $user->account_status)>{{__('messages.blocked')}}</option>
                                        </select>
                                    </div>


                                    @if($user->type =='chef' && "no" != $user->can_delivery )
                                        <div class="row mb-3">
                                            <label class="form-label">اختر امكانيه التوصيل</label>
                                            <select data-placeholder="اختر امكانيه التوصيل" name="can_delivery"   required  class="form-control form-control-select2 ">
                                                <option value="no" @selected("no" == $user->can_delivery) >{{__('messages.no')}}</option>
                                                <option value="request" @selected("request" == $user->can_delivery )>{{__('messages.request')}}</option>
                                                <option value="yes" @selected("yes" == $user->can_delivery)>{{__('messages.yes')}}</option>
                                                <option value="rejected" @selected("rejected" == $user->can_delivery)>{{__('messages.rejected')}}</option>
                                            </select>
                                        </div>
                                    @endif


                                    <div class="row mb-3">
                                        <label class="form-label"> رساله للمستخدم :</label>
                                        <textarea rows="3" cols="3" class="form-control"
                                                  name="account_comment"
                                                  placeholder="يمكنك ادخال رساله تظهر للمستخدم في حاله الرفض او طلب التحديث"
                                        ></textarea>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-teal">
                                            حفظ التغييرات
                                            <i class="ph-floppy-disk ms-2"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>



                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">تفاصيل العناوين</h5>
                            </div>


                            @foreach($user->userAddress as $userAddress)

                                <h5 class="mt-5 mb-2 text-center">


                                    {{$loop->iteration}}العنوان #
                                    -
                                    {{$userAddress->name}}
                                </h5>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>الحقل</th>
                                            <th class="text-center">القيمه</th>
                                        </tr>
                                        </thead>
                                        <tbody>


                                        @if($userAddress->name)

                                            <tr>
                                                <th class="text-nowrap" scope="row"> اسم العنوان</th>
                                                <td>{{$userAddress->name}}</td>
                                            </tr>
                                        @endif
                                        @if($userAddress->place)

                                            <tr>
                                                <th class="text-nowrap" scope="row"> المكان</th>
                                                <td>{{$userAddress->place}}</td>
                                            </tr>
                                        @endif
                                        @if($userAddress->country)
                                            <tr>
                                                <th class="text-nowrap" scope="row"> الدولة</th>
                                                <td>{{$userAddress->country->name}}</td>
                                            </tr>
                                        @endif
                                        @if($userAddress->city)
                                            <tr>
                                                <th class="text-nowrap" scope="row"> المدينه</th>
                                                <td>{{$userAddress->city->name}}</td>
                                            </tr>
                                        @endif
                                        @if($userAddress->neighborhood)
                                            <tr>
                                                <th class="text-nowrap" scope="row"> الحي / المنطقه</th>
                                                <td>{{$userAddress->neighborhood}}</td>
                                            </tr>
                                        @endif
                                        @if($userAddress->build_address)
                                            <tr>
                                                <th class="text-nowrap" scope="row"> عنوان البنايه</th>
                                                <td>{{$userAddress->build_address}}</td>
                                            </tr>
                                        @endif
                                        @if($userAddress->floor)
                                            <tr>
                                                <th class="text-nowrap" scope="row"> الطابق</th>
                                                <td>{{$userAddress->floor}}</td>
                                            </tr>
                                        @endif
                                        @if($userAddress->apartment_address)
                                            <tr>
                                                <th class="text-nowrap" scope="row"> عنوان الشقه</th>
                                                <td>{{$userAddress->apartment_address}}</td>
                                            </tr>
                                        @endif
                                        @if($userAddress->details)
                                            <tr>
                                                <th class="text-nowrap" scope="row"> التفاصيل</th>
                                                <td>{{$userAddress->details}}</td>
                                            </tr>
                                        @endif
                                        @if($userAddress->latitude && $userAddress->longitude)
                                            <tr class="pt-1 pb-1">
                                                <th class="text-nowrap pt-1 pb-1" scope="row"> العرض على الخريطه</th>
                                                <td class="pt-1 pb-1 ">
                                                    <a target="_blank" href="https://www.google.com/maps/?q={{$userAddress->latitude}},{{$userAddress->longitude}}">{{$userAddress->latitude}}
                                                        ,{{$userAddress->longitude}}</a>
                                                </td>
                                            </tr>
                                        @endif


                                        </tbody>
                                    </table>
                                </div>

                            @endforeach


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('jscript')
@endsection
