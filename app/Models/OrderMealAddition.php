<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMealAddition extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'order_meal_id',
        'addition_id',
    ];
}
