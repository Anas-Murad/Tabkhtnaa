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
}
