<?php

namespace App\Http\Requests\api\v1\auth;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        $this->merge(['mobile' => ltrim( $this->get('mobile') , '0')]);
        $this->merge(['source' => 'normal' , '0' ]);

        return [
            'password' => 'required',
            'country_code' => 'required',
            'mobile' => 'required|numeric',
            'source' => 'in:normal',

        ];

    }

    public function authorize()
    {
        return true;
    }
}
