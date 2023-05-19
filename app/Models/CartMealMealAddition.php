<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartMealMealAddition extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'cart_meal_id',
        'addition_id',
    ];
}
