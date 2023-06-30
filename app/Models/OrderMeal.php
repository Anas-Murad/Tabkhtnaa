<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMeal extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'user_id',
        'meal_id',
        'meal_name',
        'quantity',
        'price',
        'discount',
        'additions_price',
        'total',
    ];

    public  function orderMealAccessories(){
        return $this->hasMany(OrderMealAccessory::class , 'order_meal_id');
    }
    public  function orderMealAdditions(){
        return $this->hasMany(OrderMealAddition::class , 'order_meal_id');
    }
    public function accessories()
    {
        return $this->belongsToMany(Accessories::class,
            'order_meal_accessories' ,
            'order_meal_id' ,
            'accessory_id' ,
        );
    }
    public function additions()
    {
        return $this->belongsToMany(Addition::class, 'order_meal_additions' ,
            'order_meal_id' ,
            'addition_id' ,
        );
    }
}
