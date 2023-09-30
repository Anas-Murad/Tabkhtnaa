<?php

namespace App\Http\Requests\api\v1\rating;

use Illuminate\Foundation\Http\FormRequest;

class StoreRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $this->merge(['user_id' => auth()->id()]);
        return [
            'user_id' => 'required',
            'chef_id' => 'required|numeric',
            'order_id' => 'required|numeric',
            'delivery_id' => 'nullable|numeric',
            'rating_chef' => 'nullable|numeric',
            'rating_delivery' => 'nullable|numeric',
            'rating_speed_chef' => 'nullable|numeric',
            'rating_speed_delivery' => 'nullable|numeric',
            'note' => 'nullable',
            'photo' => 'nullable',
        ];
    }
}
