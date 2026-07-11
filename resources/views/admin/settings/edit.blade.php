@extends('admin.layouts.app')
@section('title')
    إعدادات النظام
@endsection
@section('page')
    إعدادات الشركة والضريبة
@endsection
@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إعدادات الشركة والضريبة</h5>
            </div>
            @if(session()->has('Success'))
                <div class="alert alert-success m-3" role="alert">
                    {{ session('Success') }}
                </div>
            @endif
            <div class="card-body border-top">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <h6 class="mb-3">تواصل معنا</h6>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label">الهاتف</label>
                        <div class="col-lg-9">
                            <input type="text" name="company_phone" class="form-control" value="{{ $settings['company_phone'] }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label">البريد الإلكتروني</label>
                        <div class="col-lg-9">
                            <input type="email" name="company_email" class="form-control" value="{{ $settings['company_email'] }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label">واتساب</label>
                        <div class="col-lg-9">
                            <input type="text" name="company_whatsapp" class="form-control" value="{{ $settings['company_whatsapp'] }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label">العنوان</label>
                        <div class="col-lg-9">
                            <textarea name="company_address" class="form-control" rows="3">{{ $settings['company_address'] }}</textarea>
                        </div>
                    </div>
                    <hr>
                    <h6 class="mb-3">تسعير الطلبات</h6>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label">نسبة الضريبة %</label>
                        <div class="col-lg-9">
                            <input type="number" step="0.01" min="0" max="100" name="tax_percentage" class="form-control" value="{{ $settings['tax_percentage'] }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label">رسوم التوصيل</label>
                        <div class="col-lg-9">
                            <input type="number" step="0.01" min="0" name="delivery_fee" class="form-control" value="{{ $settings['delivery_fee'] }}">
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">حفظ الإعدادات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
