<?php

namespace App\Http\Requests\api\v1\orders;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChefUpdateOrdersStatusRequest extends FormRequest
{
    public function rules(): array
    {
        $this->merge(['user_id' => auth()->id()]);
        return [
            'user_id' => ['required', 'integer'],
            'order_id' => ['required', 'integer',
                Rule::exists('orders', 'id')->where(function (Builder $query) {
                    return $query
                        ->where('chef_id', $this->user_id) ;
                }),
            ],
            'status' => ['required', 'in:on_way,rejected,confirmed,prepare,prepared'],
            'rejected_reason' => ['required_if:status,rejected', 'string','min:50'],
            'expected_order_time' => ['required_if:status,confirmed', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
