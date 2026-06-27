<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class CartMealAddition extends Model
{
    use Auditable;
    public $timestamps = false;

    protected $fillable = [
        'cart_meal_id',
        'addition_id',
    ];
}
