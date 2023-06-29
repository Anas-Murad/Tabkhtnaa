<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartMealAccessory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'cart_meal_id',
        'accessory_id',
    ];
}
