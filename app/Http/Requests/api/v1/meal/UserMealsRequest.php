<?php

namespace App\Http\Requests\api\v1\meal;

use Illuminate\Foundation\Http\FormRequest;

class UserMealsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'radius' => 'nullable|numeric',
            'category_id' => 'nullable|integer',
            'search' => 'nullable|string',
            'is_offer' => 'nullable|boolean',
            'min_price' => 'nullable|numeric',
            'max_price' => 'nullable|numeric',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
