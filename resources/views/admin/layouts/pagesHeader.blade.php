<div class="page-header page-header-light shadow">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                @yield('page', 'لوحة التحكم') - <span class="fw-normal">@yield('title', 'Tabkhtnaa')</span>
            </h4>
        </div>
    </div>

    <div class="page-header-content d-lg-flex border-top">
        <div class="d-flex">
            <div class="breadcrumb py-2">
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i class="ph-house"></i></a>
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">الرئيسية</a>
                <span class="breadcrumb-item active">@yield('title', 'لوحة التحكم')</span>
            </div>
        </div>
    </div>
</div>
