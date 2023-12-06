<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $orders = Order::with('address')->get();
        $orders_count = Order::count();
        $orders_count_new = Order::where('status' , 'delivered')->count();
        $startDate = Carbon::now()->subMonth()->startOfMonth();
        $endDate = Carbon::now()->subMonth()->endOfMonth();
        $ordersCountLastMonth = Order::where('status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $ordersLastMonth = Order::where('status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        $expectedProfit = $ordersLastMonth->sum(function ($order) {
            return  $order->total;
        });
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
        $user_delivery = User::where('type' , 'delivery')->count();
        $user_chef = User::where('type' , 'chef')->count();
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
            'orders_count_new' => $orders_count_new,
            'ordersCountLastMonth' => $ordersCountLastMonth,
            'expectedProfit' => $expectedProfit,
            'user_delivery' => $user_delivery,
            'user_chef' => $user_chef,
        ]);
    }
}
