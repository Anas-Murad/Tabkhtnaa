@if($user->type === 'client')
<div class="col-md-12 mt-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">نقاط الولاء</h5>
            <a href="{{ route('admin.loyalty.transactions.index', ['user_id' => $user->id]) }}" class="btn btn-sm btn-outline-primary">سجل الحركات</a>
        </div>
        <div class="card-body">
            <div class="row mb-3 text-center">
                <div class="col-md-3"><strong>{{ $user->total_points ?? 0 }}</strong><br><small>رصيد النقاط</small></div>
                <div class="col-md-3"><strong>{{ $user->current_tier ?? 'Regular' }}</strong><br><small>المستوى</small></div>
                <div class="col-md-3"><strong>{{ number_format($user->lifetime_spending ?? 0, 2) }}</strong><br><small>الإنفاق التراكمي</small></div>
                <div class="col-md-3"><strong>{{ $user->referral_code ?? '-' }}</strong><br><small>كود الإحالة</small></div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <form method="POST" action="{{ route('admin.loyalty.customers.add-points', $user->id) }}">
                        @csrf
                        <h6>إضافة نقاط</h6>
                        <input type="number" name="points" class="form-control mb-2" min="1" placeholder="عدد النقاط" required>
                        <input type="text" name="description" class="form-control mb-2" placeholder="السبب" required>
                        <button class="btn btn-success btn-sm">إضافة</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <form method="POST" action="{{ route('admin.loyalty.customers.deduct-points', $user->id) }}">
                        @csrf
                        <h6>خصم نقاط</h6>
                        <input type="number" name="points" class="form-control mb-2" min="1" placeholder="عدد النقاط" required>
                        <input type="text" name="description" class="form-control mb-2" placeholder="السبب" required>
                        <button class="btn btn-warning btn-sm">خصم</button>
                    </form>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.loyalty.customers.check-tier', $user->id) }}" class="mt-3">
                @csrf
                <button class="btn btn-indigo btn-sm">تحقق من ترقية المستوى</button>
            </form>
        </div>
    </div>
</div>
@endif
