<?php

namespace App\Http\Requests\api\v1\orders;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserMyReOrdersRequest extends FormRequest
{
    public function rules(): array
    {
        $this->merge(['user_id' => auth()->id()]);
        return [
            'user_id' => ['required', 'integer'],
            'order_id' => ['nullable', 'integer'],
            'address_id' => ['nullable', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
