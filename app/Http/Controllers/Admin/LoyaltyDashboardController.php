<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyTransaction;
use App\Models\User;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class LoyaltyDashboardController extends Controller
{
    public function index(LoyaltyService $loyalty)
    {
        $stats = $loyalty->summaryStats();
        $expiringSoon = $loyalty->expiringSoonUsers(30);
        $topUsers = User::where('type', 'client')
            ->orderByDesc('total_points')
            ->limit(10)
            ->get(['id', 'name', 'mobile', 'total_points', 'current_tier']);
        $recentTransactions = LoyaltyTransaction::with('user:id,name')
            ->latest()
            ->limit(15)
            ->get();

        return view('admin.loyalty.dashboard.index', compact('stats', 'expiringSoon', 'topUsers', 'recentTransactions'));
    }
}
