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


    protected $casts =[
        'data'=>'json'
    ];
    public  function users(){
        return  $this->hasMany(UserNotification::class ,'notification_id' );
    }



}
