<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLiveLocationRequest extends FormRequest
{
    public function rules(): array
    {
        $this->merge(['user_id' => auth()->id()]);
        return [
            'user_id' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ];
    }
}
