<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\LoyaltyTransactionsDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LoyaltyTransactionController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        $userId = $request->filled('user_id') ? (int) $request->get('user_id') : null;
        $filteredUser = $userId ? User::find($userId) : null;

        return (new LoyaltyTransactionsDataTable($type, $userId))
            ->render('admin.loyalty.transactions.index', compact('type', 'userId', 'filteredUser'));
    }
}
