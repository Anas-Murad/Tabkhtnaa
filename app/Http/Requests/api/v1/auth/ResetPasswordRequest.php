<?php

namespace App\Http\Requests\api\v1\auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|numeric',
            'new_password' => 'required|min:6|confirmed',
            'reset_password_token' => 'required|string|min:64|max:64',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
