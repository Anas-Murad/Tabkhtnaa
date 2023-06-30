<?php

namespace App\Http\Controllers\api\v1;

use App\Events\LiveLocationEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\UserLiveLocationRequest;
use App\Models\Order;
use App\Models\UserLiveLocation;
use App\Traits\HelperTrait;

class UserLiveLocationController extends Controller
{
use HelperTrait ;
    public function __invoke(UserLiveLocationRequest $request)
    {

        $data = $request->all();
        $location = UserLiveLocation::updateOrCreate(
            ['user_id' => $data['user_id']],
            $data
        );
        $latitude = $request->latitude ;
        $longitude = $request->longitude ;
        $userId =$request ->user_id;


           $order =Order::whereDeliveryId($request->user_id)->whereStatus('on_way')
             ->with('address')
            ->with('user:id,mobile,name,country_code')
            ->with('chef:id,mobile,name,country_code')
            ->selectRaw('id,user_id,chef_id,delivery_id,total,payment_method,estimated_delivery_time')
            ->first()->id ??  null ;

        event(new LiveLocationEvent($latitude, $longitude ,$userId ,$order));
        return $this->returnSuccess($location);
    }

}
