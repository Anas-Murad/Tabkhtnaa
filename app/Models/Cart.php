<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'maker_id',
    ];

    public function meals()
    {
        return $this->hasMany(CartMeal::class, 'cart_id');
    }


    function getDeliveryFeesAttribute()
    {
        return 0.15;
    }

    function getTaxAttribute()
    {
        return $this->total * 0.15;
    }

    function getTotalAttribute()
    {
        $total = $this->meals->sum(function ($CardMeal) {
            $meal = $CardMeal->meal;
            $additions = $meal->additions;
            $additionsTotal = $additions->sum(function ($addition) use ($CardMeal) {
                return $addition->price;
            });
            return ($meal->price + $additionsTotal ) * $CardMeal->quantity;
        });
        return round($total, 2);
    }

    function getUpdatedData()
    {
        $this->loadMissing([
            'meals' => function ($q) {
                $q->with('meal');
                $q->with('accessories');
                $q->with('additions');
            }
        ]);

        $this->setAppends([
            'total',
            'tax',
            'delivery_fees',
        ]);
        return $this;
    }
}
