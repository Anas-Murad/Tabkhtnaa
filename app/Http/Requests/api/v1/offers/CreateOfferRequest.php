<?php

namespace App\Http\Requests\api\v1\offers;

use Illuminate\Foundation\Http\FormRequest;

class CreateOfferRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {

        $this->merge(['user_id' => auth()->id()]);
        return [
            'meal_id' => 'required',
            'image' => 'nullable',
            'number' => 'numeric|nullable|required_if:type,offers',
            'get_free' => 'string|nullable|required_if:type,offers',
            'percent' => 'nullable|required_if:type,discount',
            'type' => 'required|in:offers,discount',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }

    public function messages()
    {
        return [
            'meal_id.required' => 'The is meal field is required.',
            'number.required' => 'The is number field is required.',
            'percent.required' => 'The is percent field is required.',
            'get_free.required' => 'The is get_free field is required.',
            'type.required' => 'The is type field is required.',
            'start_date.required' => 'The is start_date field is required.',
            'end_date.required' => 'The is end_date field is required.',
        ];
    }
}
