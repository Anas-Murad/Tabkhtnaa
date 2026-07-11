@extends('admin.layouts.app')
@section('title') إعدادات الولاء @endsection
@section('page') نظام الولاء @endsection
@section('page') نظام الولاء — الإعدادات @endsection
@section('content')
<div class="content">
    @include('admin.layouts.alert-area')
    <div class="card">
        <div class="card-header"><h5 class="mb-0">إعدادات نظام الولاء والنقاط</h5></div>
        <div class="card-body border-top">
            <form action="{{ route('admin.loyalty.settings.update') }}" method="POST">
                @csrf @method('PUT')
                <h6 class="mb-3">إعدادات النقاط الأساسية</h6>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label">نقاط مقابل كل دينار</label>
                    <div class="col-lg-9"><input type="number" min="1" name="points_per_dinar" class="form-control" value="{{ $settings->points_per_dinar }}"></div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label">الحد الأدنى للاستبدال</label>
                    <div class="col-lg-9"><input type="number" min="0" name="min_redemption_points" class="form-control" value="{{ $settings->min_redemption_points }}"></div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label">مدة انتهاء الصلاحية (شهر)</label>
                    <div class="col-lg-9"><input type="number" min="1" max="60" name="points_expiry_months" class="form-control" value="{{ $settings->points_expiry_months }}"></div>
                </div>
                <hr>
                <h6 class="mb-3">المكافآت</h6>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label">مكافأة الترحيب</label>
                    <div class="col-lg-9"><input type="number" min="0" name="welcome_bonus_points" class="form-control" value="{{ $settings->welcome_bonus_points }}"></div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label">مكافأة عيد الميلاد</label>
                    <div class="col-lg-9"><input type="number" min="0" name="birthday_bonus_points" class="form-control" value="{{ $settings->birthday_bonus_points }}"></div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-form-label">نقاط المُحيل / المُحال</label>
                    <div class="col-lg-4"><input type="number" min="0" name="referral_referrer_points" class="form-control" value="{{ $settings->referral_referrer_points }}" placeholder="المُحيل"></div>
                    <div class="col-lg-5"><input type="number" min="0" name="referral_referred_points" class="form-control" value="{{ $settings->referral_referred_points }}" placeholder="المُحال"></div>
                </div>
                <hr>
                <h6 class="mb-3">تفعيل الميزات</h6>
                @foreach([
                    'enable_points_system' => 'تفعيل نظام النقاط',
                    'enable_min_redemption' => 'الحد الأدنى للاستبدال',
                    'enable_expiry' => 'انتهاء الصلاحية',
                    'enable_auto_redemption' => 'الاستبدال التلقائي',
                    'enable_double_points' => 'حملات المضاعفة',
                    'enable_tiers' => 'مستويات الولاء',
                    'enable_welcome_bonus' => 'مكافأة الترحيب',
                    'enable_birthday_bonus' => 'مكافأة عيد الميلاد',
                    'enable_referral' => 'برنامج الإحالة',
                ] as $key => $label)
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="{{ $key }}" value="1" id="{{ $key }}" @checked($settings->$key)>
                    <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
                </div>
                @endforeach
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">حفظ الإعدادات</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
