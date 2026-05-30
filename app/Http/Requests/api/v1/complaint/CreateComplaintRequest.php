<?php

namespace App\Http\Requests\api\v1\complaint;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateComplaintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'type' => ['required', Rule::in(['user', 'maker', 'driver', 'management'])],
            'order_id' => 'required|numeric|exists:orders,id',
            'photo' => 'nullable|image',
            'description' => 'required|string|max:2000',
            'note' => 'required|string|max:255',
            'status' => 'nullable',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
