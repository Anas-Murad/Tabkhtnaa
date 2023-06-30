<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\orders\ChefMyOrdersRequest;
use App\Http\Requests\api\v1\orders\ChefUpdateOrdersStatusRequest;
use App\Models\Order;
use App\Models\UserAddress;
use App\Traits\HelperTrait;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChefController extends Controller
{
    use HelperTrait;

    public function gat_delivery(Request $request)
    {
        $chef_id = auth()->id();
        $chef = auth()->user();
        $request->validate([
            'order_id' => ['required', 'integer',
                Rule::exists('orders', 'id')->where(function (Builder $query)  use($chef_id){
                    return $query
                        ->where('chef_id', $chef_id) ;
                }),
            ],
        ]);
        $chef->load('userAddress');
        $latitude = $chef->userAddress[0]->latitude ;
        $longitude = $chef->userAddress[0]->longitude ;
        //  get users  delivery type
        return UserAddress::count() ;
        $query_addresses = UserAddress::query();
        $query_addresses->select('*');
        $query_addresses->selectRaw("
            ('6371' * ACOS(
                COS(RADIANS($latitude)) * COS(RADIANS(user_addresses.latitude)) *
                COS(RADIANS(user_addresses.longitude) - RADIANS($longitude)) +
                SIN(RADIANS($latitude)) * SIN(RADIANS(user_addresses.latitude))
            )) AS distance")
//            ->having('distance', '<', $distance)
//            ->orderBy('distance', 'ASC')
        ;
        return $query_addresses->get() ;
        $order = Order::whereChefId($chef_id)
            ->findOrFail($request->order_id);
    }

    public function update_status(ChefUpdateOrdersStatusRequest $request)
    {
        $order = Order::whereChefId($request->user_id)
            ->findOrFail($request->order_id);
            if ($order->status == $request->status) {
                return $this->returnError('تم تعيين حاله الطلب في وقت سابق');
            }
        if ($order->status == 'cancel') {
            return $this->returnError('تم الغاء الطلب في وقت سابق , شكرا لك');
        }
        if ($order->status == 'rejected') {
            return $this->returnError('تم رفض الطلب في وقت سابق , شكرا لك');
        }
        if ($order->status == 'delivered') {
            return $this->returnError('تم توصيل الطلب الى الزبون في وقت سابق , شكرا لك');
        }
        if ($order->status == 'on_way') {
            return $this->returnError('لايمكن تعديل الطلب في الوقت الحالي , اصبح طلبك في الطريق الزبون');
        }
        if ($order->status == 'delivered') {
            return $this->returnError('لايمكن تعديل الطلب في الوقت الحالي , تم توصيل الطلب في وقت سابق');
        }
            switch ($request->status) {
                // notification
                case  "confirmed":
                    break;
                case  "prepare":
                    if ($order->status != 'confirmed') {
                        return $this->returnError('يجب قبول الطلب اولا');
                    }
                    break;
                case  "prepared":
                    if ($order->status != 'prepare') {
                        return $this->returnError('يجب تجهيز الطلب اولا');
                    }
                    break;
                case  "on_way":
                    if ($order->status != 'prepared') {
                        return $this->returnError('يجب اكتمال تجهيز الطلب اولا');
                    }
                    break;
                case  "rejected":

                    break;

            }
        $data = $request->safe()->only('status', 'rejected_reason', 'expected_order_time',);
        if ($request->status == 'confirmed') {
            $calcDeliveryTime = $this->calcDeliveryTime($request->expected_order_time);
            $data['estimated_delivery_time'] = $calcDeliveryTime['estimated_delivery_time'];
            $data['estimated_time'] = $calcDeliveryTime['estimated_time'];
        }
        $order->update($data);
        return $this->returnSuccess("تم تغيير حالة الطلب رقم {$order->id} بنجاح");
    }
    public function list(ChefMyOrdersRequest $request)
    {
        $query = Order::query();
        $query->whereChefId($request->user_id);
        if ($request->order_user_id)
            $query->whereUserId($request->order_user_id);

        if ($request->payment_method)
            $query->wherePaymentMethod($request->payment_method);

        if ($request->delivery_type)
            $query->whereDeliveryType($request->delivery_type);

        if ($request->status)
            $query->whereStatus($request->status);

        if ($request->transaction_status)
            $query->whereTransactionStatus($request->transaction_status);
        $query->withCount('orderMeal');
        $query->with('user:name,email,mobile,id');
        $query->with('address.cities');
        $query->with('address.country');
        $orders = $query->simplePaginate(10);
        return $this->returnPaginateData($orders);
    }
    private function calcDeliveryTime(mixed $expected_order_time)
    {
        $DeliveryTime = 15;
        $TotalTime = $DeliveryTime + $expected_order_time;
        return [
            "estimated_delivery_time" => $DeliveryTime,
            "estimated_time" => $TotalTime,
        ];
    }

    public function get(Request $request)
    {
        $order = Order::where('chef_id' , auth()->id())->find($request->order_id);
        if (empty($order))
            return $this->returnError('Not Found Order');
        $order->load(['orderMeal' => function($q){
            $q->with('accessories' , 'additions');
        }], 'address');
        return $this->returnSuccess($order);
    }

}
