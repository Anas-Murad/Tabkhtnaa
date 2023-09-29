<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\orders\ChefMyOrdersRequest;
use App\Http\Requests\api\v1\orders\ChefUpdateOrdersStatusRequest;
use App\Models\Order;
use App\Models\OrderHistoryDelivery;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserLiveLocation;
use App\Traits\FCMTrait;
use App\Traits\HelperTrait;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChefController extends Controller
{
    use HelperTrait,  FCMTrait ;

     public function gat_delivery(Request $request)
    {


        /*  this api
            Conditionally return all drivers
            The account must be active and active
            That he does not have another delivery request
            That the request is not actually related to a delivery driver
            To be in the same circle the neutral distance
         */


        $chef_id = auth()->id();
        $chef = auth()->user();
        $request->validate([
            'order_id' => ['required', 'integer',
                Rule::exists('orders', 'id')->where(function (Builder $query) use ($chef_id) {
                    return $query
                        ->where('chef_id', $chef_id)
                        ;
                }),
            ],
        ]);
        $chef->load('userAddress');

        $latitude = $chef->userAddress[0]->latitude;
        $longitude = $chef->userAddress[0]->longitude;
        $distance = 100;

        $check =  OrderHistoryDelivery::where([
            'order_id' =>$request->order_id,
        ])
            ->whereIn('status' , [   'pending','accepted'])
            ->first();
        if ($check){
            if ($check->status =='pending')
                return   $this->returnError('تم ارسال طلب  في وقت سابق  الى السائق بالفعل') ;
            if ($check->status =='accepted')
                return   $this->returnError('تم قبول طلب التوصيل من السائق في وقت سابق بالفعل') ;
        }



        $users = User::
        select(
            'id' ,
            'profile_image',
            'mobile',
            'name',
            'online_status',
        )->
        where([
            'account_status' => 'active',
            'type' => 'delivery',
            'online_status' => 'online',
        ])->with([
            'liveLocation' => function ($q) use ($latitude, $longitude, $distance) {
                $q->select(
                    'id',
                    'user_id',
                    'latitude',
                    'longitude',
                );
                $q->selectRaw("
            ('6371' * ACOS(
                COS(RADIANS($latitude)) * COS(RADIANS(user_live_locations.latitude)) *
                COS(RADIANS(user_live_locations.longitude) - RADIANS($longitude)) +
                SIN(RADIANS($latitude)) * SIN(RADIANS(user_live_locations.latitude))
            )) AS distance");
                $q ->selectRaw("  ($longitude) AS order_longitude");
                $q ->selectRaw("  ($latitude) AS order_latitude");
            }
        ])
            ->whereHas('liveLocation', function ($q) use ($latitude, $longitude, $distance) {
                $q->selectRaw("
            ('6371' * ACOS(
                COS(RADIANS($latitude)) * COS(RADIANS(user_live_locations.latitude)) *
                COS(RADIANS(user_live_locations.longitude) - RADIANS($longitude)) +
                SIN(RADIANS($latitude)) * SIN(RADIANS(user_live_locations.latitude))
            )) AS distance")
                    ->having('distance', '<', $distance)
                    ->orderBy('distance', 'ASC');
            })
            ->get();
            if ($users){
                return  $this->returnDataArray($users);
            }else{
                return   $this->returnError('لم يتم العثور على موصل قريب منك , اعد المحاوله ') ;
            }
    }

    public function assign_delivery(Request $request)
    {
        /*  this api Linking the request to a delivery driver with the previous conditions  */

        $chef_id = auth()->id();
        $chef = auth()->user();
        $request->validate([
            'order_id' => ['required', 'integer',
                Rule::exists('orders', 'id')->where(function (Builder $query) use ($chef_id) {
                    return $query
                        ->where('chef_id', $chef_id)    ;
                }),
            ],  'delivery_id' => ['required', 'integer', ],
        ]);
        $chef->load('userAddress');
        $latitude = $chef->userAddress[0]->latitude;
        $longitude = $chef->userAddress[0]->longitude;
        $distance = 50;



        //  check  if already sent request ?

          $check =  OrderHistoryDelivery::where([
                'order_id' =>$request->order_id,
//                'delivery_id'=>$request->delivery_id,
            ])
          ->whereIn('status' , [   'pending','accepted'])
          ->first();
          if ($check){
              if ($check->status =='pending')
              return   $this->returnError('تم ارسال طلب  في وقت سابق  الى السائق بالفعل') ;
              if ($check->status =='accepted')
                  return   $this->returnError('تم قبول طلب التوصيل من السائق في وقت سابق بالفعل') ;
          }

        $user = User::
        select(
            'id' ,
            'profile_image',
            'mobile',
            'name',
            'online_status',
        )->
        where([
            'account_status' => 'active',
            'type' => 'delivery',
            'online_status' => 'online',

        ])->with([
            'liveLocation' => function ($q) use ($latitude, $longitude, $distance) {
                $q->select(
                    'id',
                    'user_id',
                    'latitude',
                    'longitude',
                );
                $q->selectRaw("
            ('6371' * ACOS(
                COS(RADIANS($latitude)) * COS(RADIANS(user_live_locations.latitude)) *
                COS(RADIANS(user_live_locations.longitude) - RADIANS($longitude)) +
                SIN(RADIANS($latitude)) * SIN(RADIANS(user_live_locations.latitude))
            )) AS distance");
                $q ->selectRaw("  ($longitude) AS order_longitude");
                $q ->selectRaw("  ($latitude) AS order_latitude");
            }
        ])
            ->whereHas('liveLocation', function ($q) use ($latitude, $longitude, $distance) {
                $q->selectRaw("
            ('6371' * ACOS(
                COS(RADIANS($latitude)) * COS(RADIANS(user_live_locations.latitude)) *
                COS(RADIANS(user_live_locations.longitude) - RADIANS($longitude)) +
                SIN(RADIANS($latitude)) * SIN(RADIANS(user_live_locations.latitude))
            )) AS distance")
                    ->having('distance', '<', $distance)
                    ->orderBy('distance', 'ASC');
            })
            ->find($request->delivery_id);
            if ($user){
                OrderHistoryDelivery::create([
                    'order_id' =>$request->order_id,
                    'delivery_id'=>$request->delivery_id,
                    'status'=>'pending',
                ]);
                $delevary = User::findOrFail($request->delivery_id);
                //Notification
                $this->PushNotification($user->id,[
                    'title' =>" تم تعيين سائق للطلب ",
                    'body' =>"تم تعيين السائق {$delevary->name}  للطلب ",
                    'order_id' =>$request->order_id,
                    'delivery_id' =>$request->delivery_id,
                    'chef_id' =>$chef_id,
                    'delivery_status' =>'pending',
                ]);
                return  $this->returnDataArray($user);
            }else{
                return   $this->returnError('السائق لم يعد متاحا الان') ;
            }
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

   /*     if ($order->status == 'delivered') {
            return $this->returnError('لايمكن تعديل الطلب في الوقت الحالي , تم توصيل الطلب في وقت سابق');
        }*/

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
                $order->loadMissing('delivery');

                if (! $order->delivery){
                    return  $this->returnError('لم يتم تعيين سائق ');
                }
                $user = $order->delivery;
                $this->PushNotification($order->chef_id, [
                    'title' => "تم استلام الطلب من سائق التوصيل",
                    'body' => "قام {$user->name} باستلام الطلب , اصبح في الطريق , طلب رقم : {$order->id}",
                    'order_id' => $order->id,
                    'delivery_id' => $user->id,
                    'chef_id' => $order->chef_id,
                    'user_id' => $order->user_id,
                    'order_status' => 'on_way',
                ]);

                $this->PushNotification($order->user_id, [
                    'title' => "طلبك في الطريق اليك",
                    'body' => "الكابتن {$user->name} في الطريق اليك , طلب رقم : {$order->id}",
                    'order_id' => $order->id,
                    'delivery_id' => $user->id,
                    'chef_id' => $order->chef_id,
                    'user_id' => $order->user_id,
                    'order_status' => 'on_way',
                ]);
                break;


        }




//        "expected_order_time": null,
//            "estimated_delivery_time": null,
//            "estimated_time": null,

        $data = $request->safe()->only('status', 'rejected_reason', 'expected_order_time',);
        if ($request->status == 'confirmed') {
            $calcDeliveryTime = $this->calcDeliveryTime($request->expected_order_time);
            $data['estimated_delivery_time'] = $calcDeliveryTime['estimated_delivery_time'];
            $data['estimated_time'] = $calcDeliveryTime['estimated_time'];

        }
        $order->update($data);
        $chef = User::findOrFail($order->chef_id);
        //Notification
        $this->PushNotification($order->user_id,[
            'title' =>" تم تغير حالة الطلب ",
            'body' =>"قام {$chef->name} تم تغير حالة الطلب لتصبح {$order->status}",
            'order_id' =>$order->id,
            'delivery_id' =>$order->delivery_id,
            'chef_id' =>$order->chef_id,
            'delivery_status' =>'pending',
        ]);

        $order->orderStatus()->create([
            'status'=>$request->status,
            'action_by_type'=>'chef',
            'action_by_id'=>$request->user_id,
        ]);



        return $this->returnSuccess("تم تغيير حالة الطلب رقم {$order->id} بنجاح");
    }

    public function list(ChefMyOrdersRequest $request)
    {
        $query = Order::query();
        $query->whereChefId($request->user_id);
        $query->Filter( $request ,false);
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
        $order = Order::where('chef_id' , auth()->id())->findOrFail($request->order_id);
        $order->load(['orderMeal' => function($q){
            $q->with('accessories' , 'additions');
        }], 'address');
        return $this->returnSuccess($order);
    }


}
