<?php

namespace App\Http\Requests\api\v1\auth;

use Illuminate\Foundation\Http\FormRequest;

class OnlineStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|numeric',
            'online_status' => 'required|in:online,busy,unavailable,available',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
