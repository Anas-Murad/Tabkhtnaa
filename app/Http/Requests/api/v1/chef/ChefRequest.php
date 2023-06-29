<?php

namespace App\Http\Requests\api\v1\chef;

use Illuminate\Foundation\Http\FormRequest;

class ChefRequest extends FormRequest
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
