@extends('admin.layouts.app')
@section('content')
    <div class="content">

        <!-- Collapse/expand card -->
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
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    ابحث عن اسم , ايميل , رقم هاتف , اسم مستخدم ,
                                                    ..الخ:</label>
                                                <input type="text" class="form-control" name="search_key"
                                                       id="search_key" placeholder="كلمة البحث ... ">
                                            </div>
                                        </div>

                                        @includeIf('admin.components.countries' , [
                                             'col_size'=>'col-md-2',
                                             'country_name'=>'country_id',
                                             'city_name'=>'city_id',
                                             'required'=>false,
                                            'with_cities'=>true,
                                        ])

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">الجنس</label>
                                                <select name="gender" id="gender"
                                                        class="select2 form-control form-control-select2" data-fouc>
                                                    <option value="">الكل</option>
                                                    <option value="male">ذكر</option>
                                                    <option value="female">انثى</option>
                                                    <option value="other">اخرى</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">مصدر التسجيل</label>
                                                <select name="source" id="source"
                                                        class="select2 form-control form-control-select2"
                                                        data-fouc>
                                                    <option value="">الكل</option>
                                                    <option value="facebook">facebook</option>
                                                    <option value="google">google</option>
                                                    <option value="apple">apple</option>
                                                    <option value="normal">عادي /  التطبيق</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">online_status</label>
                                                <select name="online_status" id="online_status"
                                                        class="select2 form-control form-control-select2"
                                                        data-fouc>
                                                    <option value="">الكل</option>
                                                    <option value="online">{{__('messages.online')}}</option>
                                                    <option value="busy">{{__('messages.busy')}}</option>
                                                    <option value="unavailable">{{__('messages.unavailable')}}</option>
                                                    <option value="available">{{__('messages.available')}}</option>
                                                </select>
                                            </div>
                                        </div>

                                        @if(!isset($type) || !$type)
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label">type</label>
                                                    <select name="type" id="type"
                                                            class="select2 form-control form-control-select2"
                                                            data-fouc>
                                                        <option value="">الكل</option>
                                                        <option value="client">client</option>
                                                        <option value="delivery">delivery</option>
                                                        <option value="chef">chef</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                        @if(isset($type) && $type =='chef')
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label">امكانيه التوصيل</label>
                                                    <select name="can_delivery" id="can_delivery" class="select2 form-control form-control-select2"
                                                            data-fouc>
                                                        <option value="">الكل</option>
                                                        <option value="no">{{__('messages.no')}}</option>
                                                        <option value="request">{{__('messages.request')}}</option>
                                                        <option value="yes">{{__('messages.yes')}}</option>
                                                        <option value="rejected">{{__('messages.rejected')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif

                                        @if(!isset($status) || !$status)
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label">حالة الحساب</label>
                                                    <select name="account_status" id="account_status"
                                                            class="select2 form-control form-control-select2"
                                                            data-fouc>
                                                        <option value="">الكل</option>
                                                        <option value="pending">{{__('messages.pending')}}</option>
                                                        <option value="active">{{__('messages.active')}}</option>
                                                        <option value="rejected">{{__('messages.rejected')}}</option>
                                                        <option value="blocked">{{__('messages.blocked')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif

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
        <!-- /collapse/expand card -->


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
        function myCustomAction(){
            alert(1)
        }
    </script>
@endsection
