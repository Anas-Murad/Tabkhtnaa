<?php

namespace App\Models;

use App\HelperClasses\BusinessSettingHelper;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'user_id',
        'maker_id',
    ];

    public function meals()
    {
        return $this->hasMany(CartMeal::class, 'cart_id');
    }

    function getSubTotalAttribute()
    {
        $total = $this->meals->sum(function ($CardMeal) {
            $meal = $CardMeal->meal;
            $additionsTotal = $CardMeal->additions->sum('price');
            return ($meal->price + $additionsTotal) * $CardMeal->quantity;
        });
        return round($total, 2);
    }

    function getTaxAttribute()
    {
        return round($this->sub_total * BusinessSettingHelper::taxRate(), 2);
    }

    function getDeliveryFeesAttribute()
    {
        return 0;
    }

    function getTotalAttribute()
    {
        return round($this->sub_total + $this->tax + $this->delivery_fees, 2);
    }

    public function totalsForDeliveryType(?string $deliveryType = null): array
    {
        $deliveryFees = $deliveryType
            ? BusinessSettingHelper::deliveryFeesFor($deliveryType)
            : 0;

        $subTotal = $this->sub_total;
        $tax = $this->tax;
        $grandTotal = round($subTotal + $tax + $deliveryFees, 2);

        return [
            'sub_total' => $subTotal,
            'tax' => $tax,
            'delivery_fees' => $deliveryFees,
            'total' => $grandTotal,
            'tax_percentage' => (float) BusinessSettingHelper::get('tax_percentage', 15),
            'delivery_fee' => BusinessSettingHelper::deliveryFee(),
        ];
    }

    function getUpdatedData(?string $deliveryType = null)
    {
        $this->loadMissing([
            'meals' => function ($q) {
                $q->with('meal');
                $q->with('accessories');
                $q->with('additions');
            }
        ]);

        $totals = $this->totalsForDeliveryType($deliveryType);

        $this->setAppends([]);
        $data = $this->toArray();
        $data = array_merge($data, $totals);

        return $data;
    }
}
