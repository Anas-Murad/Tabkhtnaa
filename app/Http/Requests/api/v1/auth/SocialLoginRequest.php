<?php

namespace App\Http\Requests\api\v1\auth;

use Illuminate\Foundation\Http\FormRequest;


class SocialLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'source' => 'required|in:facebook,google,apple',
            'udid' => 'required',
            'profile_image_url' => 'required_if:source,facebook,google,apple|url',
            'type' => 'required|in:client,delivery,chef',

        ];

    }

    public function authorize(): bool
    {
        return true;
    }
}
