<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderMealRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => ['required'],
            'user_id' => ['required'],
            'meal_id' => ['required'],
            'meal_name' => ['required'],
            'quantity' => ['required'],
            'price' => ['required'],
            'discount' => ['required'],
            'additions_price' => ['required'],
            'total' => ['required'],
        ];
    }
}
