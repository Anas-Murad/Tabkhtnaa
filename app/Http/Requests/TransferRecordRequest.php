<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRecordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'to_type' => ['required'],
            'to_id' => ['nullable', 'integer'],
            'order_id' => ['required', 'integer'],
            'amount' => ['required', 'numeric'],
            'remainder' => ['required', 'numeric'],
            'transaction_id' => ['required', 'integer'],
            'percent' => ['required', 'numeric'],
            'user_driver_cash_id' => ['required', 'integer'],
            'admin_checked' => ['required'],
            'admin_notes' => ['nullable'],
            'transfer_status' => ['nullable'],
            'transfer_id' => ['nullable', 'integer'],
            'transfer_date' => ['nullable', 'date'],
        ];
    }
}
