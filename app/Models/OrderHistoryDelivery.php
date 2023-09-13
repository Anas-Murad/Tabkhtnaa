<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistoryDelivery extends Model
{
    protected $fillable = [
        'order_id',
        'delivery_id',
        'status',
        'rejected_reason',
    ];

    public function  order()
    {
        return  $this->belongsTo(Order::class , 'order_id');
    }



}
