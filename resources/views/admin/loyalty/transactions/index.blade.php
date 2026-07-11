@extends('admin.layouts.app')
@section('content')
<div class="content">
    @include('admin.layouts.alert-area')
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" id="loyaltyTransactionsFilter" class="row g-2">
                <div class="col-md-4">
                    <select name="type" class="form-select">
                        <option value="">كل الأنواع</option>
                        @foreach(['earn','redeem','bonus','expiry','welcome','birthday','referral'] as $t)
                            <option value="{{ $t }}" @selected($type===$t)>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4"><input type="number" name="user_id" class="form-control" placeholder="معرف العميل" value="{{ $userId }}"></div>
                <div class="col-md-4"><button class="btn btn-primary">تصفية</button></div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h5 class="mb-0">حركات النقاط</h5></div>
        <div class="table-responsive">{!! $dataTable->table(['class' => 'table']) !!}</div>
    </div>
</div>
@endsection
@section('jscript'){{ $dataTable->scripts(attributes: ['type' => 'module']) }}@endsection
