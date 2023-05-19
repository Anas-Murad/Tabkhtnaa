<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\orders\CreateOrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Traits\HelperTrait;

class UserOrderController extends Controller
{
    use HelperTrait ;
    public function index()
    {
        return Order::all();
    }

    public function store(CreateOrderRequest $request)
    {


        $cart = Cart::whereUserId( $request->user_id )
            ->whereMakerId($request->chef_id)
            ->find($request->cart_id);
        $cart->getUpdatedData();

        return false; //2023
        $data =[
            'user_id' =>$request->user_id,
            'chef_id' => $request->chef_id,

            'payment_method' =>$request->payment_method,
            'delivery_type' =>$request->delivery_type,
            'delivery_fees' =>$cart->delivery_fees,
            'tax' =>$cart->delivery_fees,
            'sub_total' =>$cart->total,
            'discount' => 0,
            'total' =>$cart->total ,
            'coupon_id' =>$request->coupon_id,
            'coupon' =>$request->coupon,
            'details' =>$request->details,
            'transaction_id' => null,
            'transaction_status' => null,
        ];

        return Order::create($request->validated());
    }



    public function show(Order $order)
    {
        return $order;
    }

    public function update(CreateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());

        return $order;
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json();
    }
}
