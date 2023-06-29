<?php

namespace App\Http\Requests\api\v1\orders;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserMyOrdersRequest extends FormRequest
{
    public function rules(): array
    {
        $this->merge(['user_id' => auth()->id()]);
        return [
            'user_id' => ['required', 'integer'],
            'chef_id' => ['nullable', 'integer',
                Rule::exists('orders', 'chef_id ')->where(function (Builder $query) {
                    return $query
                        ->where('user_id', $this->user_id) ;
                }),
            ],
            'payment_method' => ['nullable'],
            'delivery_type' => ['nullable', 'in:delivery,pick_up,chef_delivery'],
            'status' => ['nullable', 'in:pending,confirmed,prepare,prepared,on_way,delivered,not_delivered,rejected,cancel'],
            'transaction_status' => ['nullable', 'in:pending,success,cancel'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
