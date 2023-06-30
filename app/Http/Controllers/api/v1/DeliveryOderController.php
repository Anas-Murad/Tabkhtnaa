<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderHistoryDelivery;
use App\Traits\FCMTrait;
use App\Traits\HelperTrait;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeliveryOderController extends Controller
{

    use HelperTrait,  FCMTrait ;

    public function requested(Request $request)
    {
        $delivery= auth()->user();
        $delivery=  OrderHistoryDelivery::
            where('delivery_id' ,$delivery->id)
            ->where('status' , 'pending')
            ->with([
                'order'=>function($q){
                    $q->with('address');
                    $q->with('user:id,mobile,name,country_code');
                    $q->with('chef:id,mobile,name,country_code');
                    $q->selectRaw('id,user_id,chef_id,delivery_id,total,payment_method,estimated_delivery_time');
                }
            ])
            ->simplePaginate(10);
        return  $this->returnPaginateData($delivery) ;


    }




    public function update_request(Request $request)
    {
        $user= auth()->user();
        $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'  ],
            'status' => ['required', 'in:accepted,rejected' ],
            'rejected_reason' => ['required_if:status,rejected', 'string','min:50'],
        ]);

           $delivery=  OrderHistoryDelivery::
        where('delivery_id' ,$user->id)
        -> where('order_id' ,$request->order_id)
        ->first();



           if ( $delivery->status =='accepted'){
               return  $this->returnError('تم قبول توصيل الطلب في وقت سابق , شكرا لك');
           }

            if ( $delivery->status =='rejected'){
                return  $this->returnError('تم رفض توصيل الطلب في وقت سابق , شكرا لك');
            }


            $order = Order::find($request->order_id);
             switch ($request->status){
                 case "accepted":
                     $delivery->status ="accepted";
                     $delivery->save() ;

                     $order->delivery_id =$user->id ;

                     $this->PushNotification($order->chef_id,[
                         'title' =>"تم قبول طلب التوصيل",
                         'body' =>"قام {$user->name} بقبول توصيل الطلب رقم {$order->id}",
                         'order_id' =>$order->id,
                         'delivery_id' =>$user->id,
                         'chef_id' =>$order->chef_id,
                         'delivery_status' =>'accepted',
                     ]);
                     $order->save();

             break;
             case "rejected":
                 $this->PushNotification($order->chef_id,[
                     'title' =>"تم رفض طلب التوصيل",
                     'body' =>"قام {$user->name} برفض توصيل الطلب رقم {$order->id}",
                     'order_id' =>$order->id,
                     'delivery_id' =>$user->id,
                     'chef_id' =>$order->chef_id,
                     'delivery_status' =>'rejected',
                 ]);

             break;


         }



        return  $this->returnDataArray($order) ;


    }


}
