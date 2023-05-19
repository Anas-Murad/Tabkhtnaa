<?php

namespace App\Http\Requests\api\v1\chef;

use Illuminate\Foundation\Http\FormRequest;

class ChefIDRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
