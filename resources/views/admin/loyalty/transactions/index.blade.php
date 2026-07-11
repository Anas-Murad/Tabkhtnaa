@extends('admin.layouts.app')
@section('title') حركات النقاط @endsection
@section('page') نظام الولاء @endsection
@section('content')
<div class="content">
    @include('admin.layouts.alert-area')
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center">
            <h6 class="mb-0">فلترة</h6>
        </div>
        <div class="card-body">
            <form action="#" id="filter_form" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">نوع الحركة</label>
                    <select name="type" class="form-select">
                        <option value="">كل الأنواع</option>
                        @foreach(\App\Support\LoyaltyLabels::transactionTypes() as $value => $label)
                            <option value="{{ $value }}" @selected($type===$value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">معرف العميل</label>
                    <input type="number" name="user_id" class="form-control" placeholder="معرف العميل" value="{{ $userId }}">
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-primary" onclick="window.LaravelDataTables['data-table'].ajax.reload()">تصفية</button>
                    <a href="{{ route('admin.loyalty.transactions.index') }}" class="btn btn-light">إعادة ضبط</a>
                </div>
            </form>
            @if($filteredUser)
                <div class="alert alert-info mt-3 mb-0 py-2">
                    عرض حركات العميل:
                    <strong>{{ $filteredUser->name }}</strong>
                    (#{{ $filteredUser->id }})
                    @if($filteredUser->type !== 'client')
                        <span class="text-warning">— هذا المستخدم ليس عميلاً، وقد لا توجد حركات ولاء.</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h5 class="mb-0">حركات النقاط</h5></div>
        <div class="table-responsive">{!! $dataTable->table(['class' => 'table']) !!}</div>
    </div>
</div>
@endsection
@section('jscript'){{ $dataTable->scripts(attributes: ['type' => 'module']) }}@endsection
