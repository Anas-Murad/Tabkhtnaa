<?php

namespace App\Http\Requests\api\v1\orders;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChefMyOrdersRequest extends FormRequest
{
    public function rules(): array
    {
        $this->merge(['user_id' => auth()->id()]);
        return [
            'user_id' => ['required', 'integer'],
            'order_user_id' => ['nullable', 'integer',
                Rule::exists('orders', 'user_id ')->where(function (Builder $query) {
                    return $query
                        ->where('chef_id', $this->user_id)   ;
                }),
            ],

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
