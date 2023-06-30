<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'chef_id',
        'delivery_id',
        'payment_method',
        'delivery_type',
        'delivery_fees',
        'tax',
        'sub_total',
        'discount',
        'total',
        'coupon_id',
        'coupon',
        'details',
        'transaction_id',
        'transaction_status',
        'expected_order_time',
        'estimated_delivery_time',
        'estimated_time',
        'status',
        'rejected_reason',
        'refund_id',
    ];


    public  function orderMeal(){
        return $this->hasMany(OrderMeal::class , 'order_id') ;
    }

    public  function address(){
        return $this->hasOne(OrderAddress::class , 'order_id') ;
    }


    public  function user(){
        return $this->belongsTo(User::class , 'user_id' ) ;
    }


    public  function chef(){
        return $this->belongsTo(User::class , 'chef_id' ) ;
    }


}
