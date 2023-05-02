<?php

namespace App\Http\Requests\api\v1\auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
    public function rules(): array
    {        $this->merge(['mobile' => ltrim( $this->get('mobile') , '0')]);

        return [
            'country_code' => 'required',
            'mobile' => 'required|numeric',
        ];
    }



    public function authorize(): bool
    {
        return true;
    }
}
