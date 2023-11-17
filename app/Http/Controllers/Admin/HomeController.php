<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class HomeController extends Controller
{
    public function index()
    {
        $orders = Order::with('address')->get();
        return view('admin.dashboard', ['orders' => $orders]);
    }
}
