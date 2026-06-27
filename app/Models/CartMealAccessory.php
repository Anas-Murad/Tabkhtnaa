<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class CartMealAccessory extends Model
{
    use Auditable;
    public $timestamps = false;

    protected $fillable = [
        'cart_meal_id',
        'accessory_id',
    ];
}
