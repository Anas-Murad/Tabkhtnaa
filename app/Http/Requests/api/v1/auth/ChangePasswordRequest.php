<?php

namespace App\Http\Requests\api\v1\auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'password' => 'required|min:6|confirmed',
            'current_password' => 'required|min:6|current_password:sanctum',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
