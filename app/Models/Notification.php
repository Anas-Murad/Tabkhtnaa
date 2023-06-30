<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'body',
        'order_id',
        'data',
    ];
    protected $casts = [
        'data' => 'array'
    ];

    public function users()
    {
        return $this->hasMany(UserNotification::class, 'notification_id');
    }


    public  function  scopeSeen($q){
        $q->leftJoin('user_notifications' ,  function ($join){
            $join->on('user_notifications.notification_id' , '=' , 'notifications.id')
            ->where('user_notifications.user_id' ,  auth()->id());
        })
        ->select('notifications.*')
            ->selectRaw('user_notifications.notification_id')
            ->selectRaw('COALESCE(user_notifications.seen, 0) as seen')
        ;
    }
}
