@extends('admin.layouts.app')
@section('content')
<div class="content">
    @include('admin.layouts.alert-area')
    <div class="row">
        @foreach([
            ['label' => 'إجمالي النقاط الحالية', 'value' => number_format($stats['total_points_outstanding']), 'color' => 'primary'],
            ['label' => 'إجمالي النقاط الممنوحة', 'value' => number_format($stats['total_points_awarded']), 'color' => 'success'],
            ['label' => 'إجمالي المستبدل', 'value' => number_format($stats['total_points_redeemed']), 'color' => 'warning'],
            ['label' => 'الأعضاء النشطون', 'value' => $stats['active_members'], 'color' => 'info'],
        ] as $card)
        <div class="col-md-3 mb-3">
            <div class="card bg-{{ $card['color'] }} text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $card['value'] }}</h3>
                    <small>{{ $card['label'] }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">أكثر 10 عملاء نقاطاً</h5></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead><tr><th>العميل</th><th>النقاط</th><th>المستوى</th></tr></thead>
                        <tbody>
                        @forelse($topUsers as $u)
                            <tr>
                                <td><a href="{{ route('users.show', $u->id) }}">{{ $u->name }}</a></td>
                                <td>{{ $u->total_points }}</td>
                                <td>{{ $u->current_tier }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center">لا توجد بيانات</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">نقاط على وشك الانتهاء (30 يوم)</h5></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead><tr><th>العميل</th><th>النقاط</th><th>باقي</th></tr></thead>
                        <tbody>
                        @forelse($expiringSoon as $row)
                            <tr>
                                <td><a href="{{ route('users.show', $row['user_id']) }}">{{ $row['name'] }}</a></td>
                                <td>{{ $row['total_points'] }}</td>
                                <td>{{ $row['days_remaining'] }} يوم</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center">لا يوجد</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">آخر الحركات</h5>
            <span class="badge bg-secondary">نسبة الاستبدال: {{ $stats['redemption_rate'] }}%</span>
        </div>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead><tr><th>#</th><th>العميل</th><th>النقاط</th><th>النوع</th><th>الوصف</th><th>التاريخ</th></tr></thead>
                <tbody>
                @foreach($recentTransactions as $t)
                    <tr>
                        <td>{{ $t->id }}</td>
                        <td>{{ $t->user?->name }}</td>
                        <td class="{{ $t->points >= 0 ? 'text-success' : 'text-danger' }}">{{ $t->points }}</td>
                        <td>{{ $t->type }}</td>
                        <td>{{ $t->description }}</td>
                        <td>{{ $t->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
