<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;

use App\Models\Notification;
use App\Models\UserNotification;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use  HelperTrait ;
    public function list()
    {
        $notifications =  Notification:: seen()->whereHas('users' ,  function ($q){
            $q->where('user_id' , auth()->id()) ;
        })
        ->latest()
        ->simplePaginate(10);
        return  $this->returnPaginateData($notifications) ;
    }

    public function seen(Request $request)
    {

        $request->validate(['notification_id' => ['required', 'integer'],]);
        $user = auth()->user();
        $notifications =  Notification::whereHas('users' ,  function ($q){
            $q->where('user_id' , auth()->id()) ;
        })
        ->findOrFail($request->notification_id);
        UserNotification::updateOrCreate([
            'user_id'=>$user->id,
            'notification_id'=>$notifications->id,
        ],[
            'seen'=>1,
            'notification_id'=>$notifications->id,
        ]);
        return $this->returnSuccess('تم تنفيذ الطلب');
    }




    public function seen_all(Request $request)
    {
        UserNotification::
        whereUserId(auth()->id())->
        update([
            'seen'=>1,
        ]);
        return $this->returnSuccess('تم تنفيذ الطلب');
    }


    public function delete_all(Request $request)
    {
        UserNotification::
        whereUserId(auth()->id())->delete();
        return $this->returnSuccess('تم تنفيذ الطلب');
    }



}
