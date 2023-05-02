<?php

namespace App\Http\Requests\api\v1\auth;

use Illuminate\Foundation\Http\FormRequest;

class MobileVerifiedRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'user_id' => 'required|numeric',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
