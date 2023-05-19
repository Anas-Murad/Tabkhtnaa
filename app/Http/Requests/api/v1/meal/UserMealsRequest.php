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
            'lat' => 'required',
            'long' => 'required',
            'radius' => 'required',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
