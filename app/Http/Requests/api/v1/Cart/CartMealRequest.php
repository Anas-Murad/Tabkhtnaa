<?php

namespace App\Http\Requests\api\v1\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CartMealRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cart_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'meal_id' => ['required'],
            'quantity' => ['required','numeric','min:1'],
        ];
    }
}
