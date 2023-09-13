<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'to_type' => ['required'],
            'to_id' => ['required'],
            'amount' => ['required'],
        ];
    }
}
