<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\orders\CreateOrderRequest;
use App\Http\Requests\api\v1\orders\UserCancelOrdersRequest;
use App\Http\Requests\api\v1\orders\UserMyOrdersRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderMeal;
use App\Models\OrderMealAccessory;
use App\Models\OrderMealAddition;
use App\Models\UserAddress;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    use HelperTrait;

    public function cancel(UserCancelOrdersRequest $request)
    {

        $order = Order::whereUserId($request->user_id)
        ->findOrFail($request->order_id);

        if ( $order->status =='cancel'){
            return  $this->returnError('تم الغاء الطلب في وقت سابق , شكرا لك');
        }

        if ( $order->status =='rejected'){
            return  $this->returnError('تم رفض الطلب في وقت سابق , شكرا لك');
        }


        if ( $order->status =='delivered'){
            return  $this->returnError('تم توصيل الطلب اليك في وقت سابق , شكرا لك');
        }

        if ( $order->status =='on_way'){
            return  $this->returnError('لايمكن الغاء الطلب في الوقت الحالي , اصبح طلبك في الطريق اليك');
        }




        if ( $order->status =='prepare'){
            return  $this->returnError('لايمكن الغاء الطلب في الوقت الحالي , طلبك قيد التحضير حاليا');
        }



        if ( $order->status =='prepared'){
            return  $this->returnError('لايمكن الغاء الطلب في الوقت الحالي , تم تجهيز طلبك بالفعل');
        }



        $order->update(['status' => 'cancel']);



        $order->orderStatus()->create([
            'status'=>'cancel',
            'action_by_type'=>'client',
            'action_by_id'=>$request->user_id,
        ]);
        return   $this->returnSuccess("تم الغاء الطلب رقم {$order->id} بنجاح");
    }

    public function list(UserMyOrdersRequest $request)
    {

        $query=Order::query();
        $query->Filter( $request);
        $orders = $query->simplePaginate(10);
        return   $this->returnPaginateData($orders) ;
    }


    public function store(CreateOrderRequest $request)
    {


        $cart = Cart::whereUserId($request->user_id)
            ->whereMakerId($request->chef_id)
            ->with([
                'meals.meal',
                'meals.mealAccessories',
                'meals.additions',
            ])
            ->find($request->cart_id);
        $cart->getUpdatedData();


        $data = [
            'user_id' => $request->user_id,
            'chef_id' => $request->chef_id,
            'payment_method' => $request->payment_method,
            'delivery_type' => $request->delivery_type,
            'delivery_fees' => $cart->delivery_fees,
            'tax' => $cart->delivery_fees,
            'sub_total' => $cart->total,
            'discount' => 0,
            'total' => $cart->total,
            'coupon_id' => $request->coupon_id,
            'coupon' => $request->coupon,
            'details' => $request->details,
//            'transaction_id' => null,
//            'transaction_status' => null,
        ];


        $address = UserAddress::find($request->address_id);

        $Order = Order::create($data);
        $OrderAddress = OrderAddress::create([
            'order_id' => $Order->id,
            'address_id' => $request->address_id,
            'name' => $address->name,
            'place' => $address->place,
            'country_id' => $address->country_id,
            'city_id' => $address->city_id,
            'neighborhood' => $address->neighborhood,
            'build_address' => $address->build_address,
            'floor' => $address->floor,
            'apartment_address' => $address->apartment_address,
            'details' => $address->details,
            'latitude' => $address->latitude,
            'longitude' => $address->longitude,
            'user_id' => $address->user_id,
        ]);


        foreach ($cart->meals as $mealItem) {

            $accessory_ids = $mealItem->mealAccessories->pluck('accessory_id')->toArray();
            $addition_ids = $mealItem->additions->pluck('id')->toArray();
            $sum_addition = $mealItem->additions->sum('price');
            $quantity = $mealItem->quantity;
            $price = $mealItem->meal->calcPrice();
            $discount = $mealItem->meal->calcDiscount();

            $total = ($price -$discount  ) ;
            $total = $total * $quantity ;
            $total = $total  + ($sum_addition * $quantity );

          $OrderMeal =   OrderMeal::create([
                'order_id' =>$Order->id,
                'user_id' =>$Order->user_id,
                'meal_id' =>$mealItem->meal_id,
                'meal_name'=>$mealItem->meal->name,
                'quantity' =>$quantity,
                'price'=>$price,
                'discount' =>$discount,
                'additions_price'=>$sum_addition,
                'total' =>$total,
            ]);


          foreach ($accessory_ids as $accessory_id)
            OrderMealAccessory::create([
                'order_meal_id' =>$OrderMeal->id,
                'accessory_id' =>$accessory_id,
            ]) ;
            foreach ($addition_ids as $addition_id)
                OrderMealAddition::create([
                    'order_meal_id' =>$OrderMeal->id,
                    'addition_id' =>$addition_id,
                ]) ;
        }
        $Order->load([
            'address',
            'orderMeal'=>function($q){
                $q->with([
                    'additions',
                    'accessories',
                ]) ;
            },
        ]);
        $cart->delete() ;
        return $this->returnDataArray($Order);
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

    public function get(Request $request)
    {
        $order = Order::where('user_id' , auth()->id())->find($request->order_id);
        if (empty($order))
            return $this->returnError('Not Found Order');
        $order->load(['orderMeal' => function($q){
            $q->with('accessories' , 'additions');
        }], 'address');
        return $this->returnSuccess($order);
    }
}
