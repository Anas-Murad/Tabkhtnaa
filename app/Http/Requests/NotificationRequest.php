<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['nullable'],
            'body' => ['nullable'],
            'order_id' => ['nullable'],
        ];
    }
}
