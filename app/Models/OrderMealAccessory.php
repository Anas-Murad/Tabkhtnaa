<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMealAccessory extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'order_meal_id',
        'accessory_id',
    ];
}
