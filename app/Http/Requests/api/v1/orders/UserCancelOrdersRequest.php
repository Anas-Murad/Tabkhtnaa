<?php

namespace App\Http\Requests\api\v1\orders;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCancelOrdersRequest extends FormRequest
{
    public function rules(): array
    {
        $this->merge(['user_id' => auth()->id()]);
        return [
            'user_id' => ['required', 'integer'],
            'order_id' => ['required', 'integer',
                Rule::exists('orders', 'id')->where(function (Builder $query) {
                    return $query
                        ->where('user_id', $this->user_id) ;
                }),
            ],
            'canceled_reason' => ['required', 'string','min:50'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
