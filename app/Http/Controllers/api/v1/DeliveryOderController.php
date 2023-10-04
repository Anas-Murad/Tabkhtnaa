<?php

namespace App\Http\Controllers\api\v1;

use App\HelperClasses\PointsAndDistinctionProcess;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TransactionController;
use App\Models\Order;
use App\Models\OrderHistoryDelivery;
use App\Traits\FCMTrait;
use App\Traits\HelperTrait;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeliveryOderController extends Controller
{

    use HelperTrait, FCMTrait;

    public function get(Request $request)
    {
        $user = auth()->user();
        $order = Order::where('delivery_id', $user->id)
            ->with([
                'orderMeal' => function ($q) {
                    $q->with('accessories', 'additions');
                }
                , 'address', 'chef', 'user'
            ],
            )
            ->findOrFail($request->id);
        return $this->returnSuccess($order);

    }

    public function list(Request $request)
    {
        $user = auth()->user();
        $query = Order::query();
        $query->Filter($request);
        $order = $query->where('delivery_id', $user->id)
            ->with('address')
            ->with('user:id,mobile,name,country_code')
            ->with('chef:id,mobile,name,country_code')
            ->selectRaw('id,user_id,chef_id,delivery_id,total,payment_method,estimated_delivery_time,status,transaction_status')
            ->simplePaginate(10);
        return $this->returnPaginateData($order);

    }

    public function requested(Request $request)
    {
        $delivery = auth()->user();
        $delivery = OrderHistoryDelivery::
        where('delivery_id', $delivery->id)
            ->where('status', 'pending')
            ->with([
                'order' => function ($q) {
                    $q->with('address');
                    $q->with('user:id,mobile,name,country_code');
                    $q->with('chef:id,mobile,name,country_code');
                    $q->selectRaw('id,user_id,chef_id,delivery_id,total,payment_method,estimated_delivery_time');
                }
            ])
            ->simplePaginate(10);
        return $this->returnPaginateData($delivery);


    }


    public function update_request(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'status' => ['required', 'in:accepted,rejected'],
            'rejected_reason' => ['required_if:status,rejected', 'string', 'min:50'],
        ]);

        $delivery = OrderHistoryDelivery::
        where('delivery_id', $user->id)
            ->where('order_id', $request->order_id)
            ->firstOrFail();


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
        if ($delivery->status == 'accepted') {
            return $this->returnError('تم قبول توصيل الطلب في وقت سابق , شكرا لك');
        }

        if ($delivery->status == 'rejected') {
            return $this->returnError('تم رفض توصيل الطلب في وقت سابق , شكرا لك');
        }


        $order = Order::find($request->order_id);
        switch ($request->status) {
            case "accepted":
                $delivery->status = "accepted";
                $delivery->save();

                $order->delivery_id = $user->id;

                $this->PushNotification($order->chef_id, [
                    'title' => "تم قبول طلب التوصيل",
                    'body' => "قام {$user->name} بقبول توصيل الطلب رقم {$order->id}",
                    'order_id' => $order->id,
                    'delivery_id' => $user->id,
                    'chef_id' => $order->chef_id,
                    'delivery_status' => 'accepted',
                ]);
                $order->save();

                break;
            case "rejected":
                $this->PushNotification($order->chef_id, [
                    'title' => "تم رفض طلب التوصيل",
                    'body' => "قام {$user->name} برفض توصيل الطلب رقم {$order->id}",
                    'order_id' => $order->id,
                    'delivery_id' => $user->id,
                    'chef_id' => $order->chef_id,
                    'delivery_status' => 'rejected',
                ]);

                break;


        }


        return $this->returnDataArray($order);


    }



    public function update_status(Request $request)
    {

        $user = auth()->user();
        $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'status' => ['required', 'in:on_way,delivered,not_delivered'],
        ]);

//        'pending','confirmed','prepare','prepared','on_way','delivered','not_delivered','rejected','cancel'


        $order = Order::whereDeliveryId($user->id)
            ->findOrFail($request->order_id);
        PointsAndDistinctionProcess::Process($order);

        if ($order->status == $request->status) {
            return $this->returnError('تم تعيين حاله الطلب في وقت سابق');
        }

        if ($order->status == 'delivered') {
            return $this->returnError('تم توصيل الطلب في وقت سابق , شكرا لك');
        }

        switch ($request->status) {

            case  "delivered":
                if ($order->status != 'on_way') {
                    return $this->returnError('يجب الانتقال الى مرحله (في الطريق ) الطلب اولا');
                }

                $this->PushNotification($order->chef_id, [
                    'title' => "تم توصيل طلبك بنجاح",
                    'body' => "قام {$user->name}بتوصيل طلبك رقم {$order->id} :   ",
                    'order_id' => $order->id,
                    'delivery_id' => $user->id,
                    'chef_id' => $order->chef_id,
                    'user_id' => $order->user_id,
                    'order_status' => 'delivered',
                ]);
                $this->PushNotification($order->user_id, [
                    'title' => "تم توصيل طلبك بنجاح",
                    'body' => "قام {$user->name}بتوصيل طلبك رقم {$order->id} :   ",
                    'order_id' => $order->id,
                    'delivery_id' => $user->id,
                    'chef_id' => $order->chef_id,
                    'user_id' => $order->user_id,
                    'order_status' => 'delivered',
                ]);





                break;






            case  "not_delivered":
                if ($order->status != 'on_way') {
                    return $this->returnError('يجب الانتقال الى مرحله (في الطريق ) الطلب اولا');
                }


            $this->PushNotification($order->chef_id, [
                'title' => "لم يتم تسليم الطلب",
                'body' => "قام {$user->name} باعلام عن الطلب رقم  : {$order->id} بانه لم يتم التسليم ",
                'order_id' => $order->id,
                'delivery_id' => $user->id,
                'chef_id' => $order->chef_id,
                'user_id' => $order->user_id,
                'order_status' => 'on_way',
            ]);


            $this->PushNotification($order->user_id, [
                'title' => "لم تقم باستلام طلبك !",
                'body' => "الكابتن {$user->name} يقول انه لم يتم تسليم الطلب : {$order->id}",
                'order_id' => $order->id,
                'delivery_id' => $user->id,
                'chef_id' => $order->chef_id,
                'user_id' => $order->user_id,
                'order_status' => 'not_delivered',
            ]);
            break;
        }
        $order->status = $request->status ;
        $order->save();
        $order->orderStatus()->create([
            'status'=>$request->status,
            'action_by_type'=>'delivery',
            'action_by_id'=>$user->id,
        ]);


        if($request->status =='delivered'){
            (new TransactionController)->distribution($order);
            PointsAndDistinctionProcess::Process($order);
        }
        return $this->returnSuccess("تم تغيير حالة الطلب رقم {$order->id} بنجاح");
    }




}
