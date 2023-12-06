<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class HomeController extends Controller
{
    public function index()
    {
        $orders = Order::with('address')->get();
        $orders_count = Order::count();
        $orders_pending = Order::where('status' , 'pending')->count();
        $orders_confirmed = Order::where('status' , 'confirmed')->count();
        $orders_prepare = Order::where('status' , 'prepare')->count();
        $orders_prepared = Order::where('status' , 'prepared')->count();
        $orders_on_way = Order::where('status' , 'on_way')->count();
        $orders_success = Order::where('status' , 'delivered')->count();
        $orders_not_delivered = Order::where('status' , 'not_delivered')->count();
        $orders_rejected = Order::where('status' , 'rejected')->count();
        $orders_cancel = Order::where('status' , 'cancel')->count();
        $orders_transaction_status = Order::where('transaction_status' , 'success')->count();
        return view('admin.dashboard', [
            'orders' => $orders,
            'orders_count' => $orders_count,
            'orders_pending' => $orders_pending,
            'orders_confirmed' => $orders_confirmed,
            'orders_prepare' => $orders_prepare,
            'orders_prepared' => $orders_prepared,
            'orders_on_way' => $orders_on_way,
            'orders_success' => $orders_success,
            'orders_not_delivered' => $orders_not_delivered,
            'orders_rejected' => $orders_rejected,
            'orders_cancel' => $orders_cancel,
            'orders_transaction_status' => $orders_transaction_status,
        ]);
    }
}
