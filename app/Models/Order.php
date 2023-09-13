<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

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

    public  function scopeFilter($query , $request  , $userIdFlag=true){


        if ($request->filled('user_id') && $userIdFlag)
            $query->where('orders.user_id' , $request->user_id);

        if ($request->filled('order_user_id'))
            $query->where('orders.user_id' , $request->order_user_id);


        if ($request->filled('chef_id'))
            $query->where('orders.chef_id' , $request->chef_id);


        if ($request->filled('order_id'))
            $query->where('orders.id' , $request->order_id);


        if ($request->filled('payment_method'))
            $query->where('orders.payment_method' , $request->payment_method);

        if ($request->filled('delivery_type'))
            $query->where('orders.delivery_type' , $request->delivery_type);


        if ($request->filled('status'))
            $query->where('orders.status' , $request->status);

        if ($request->filled('transaction_status'))
            $query->where('orders.transaction_status' , $request->transaction_status);

        if ($request->filled('delivery_id'))
            $query->where('orders.delivery_id' , $request->delivery_id);

    }




    public  function orderMeal(){
        return $this->hasMany(OrderMeal::class , 'order_id') ;
    }


    public  function orderStatus(){
        return $this->hasMany(OrderHistoryStatus::class , 'order_id') ;
    }

    public  function address(){
        return $this->hasOne(OrderAddress::class , 'order_id') ;
    }


    public  function user(){
        return $this->belongsTo(User::class , 'user_id' ) ;
    }

    public  function delivery(){
        return $this->belongsTo(User::class , 'delivery_id' ) ;
    }


    public  function chef(){
        return $this->belongsTo(User::class , 'chef_id' ) ;
    }



    public function Transaction(){
        return $this->belongsTo(Transaction::class , 'transaction_id') ;

    }

    public function TransactionHistory(){
        return $this->hasMany(Transaction::class , 'order_id');
    }
}
