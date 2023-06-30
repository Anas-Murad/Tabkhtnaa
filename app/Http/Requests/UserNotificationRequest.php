<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserNotificationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required'],
            'notification_id' => ['required'],
            'seen' => ['required'],
        ];
    }
}
