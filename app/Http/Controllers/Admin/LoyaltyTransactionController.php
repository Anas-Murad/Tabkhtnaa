<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\LoyaltyTransactionsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoyaltyTransactionController extends Controller
{
    public function index(Request $request, LoyaltyTransactionsDataTable $dataTable)
    {
        $type = $request->get('type');
        $userId = $request->get('user_id');

        return $dataTable->render('admin.loyalty.transactions.index', compact('type', 'userId'));
    }
}
