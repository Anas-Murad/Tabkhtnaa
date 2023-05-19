<?php

namespace App\Http\Requests\api\v1\meal;

use Illuminate\Foundation\Http\FormRequest;

class CreateMealRequest extends FormRequest
{
    public function rules(): array
    {

        $this->merge(['user_id' => auth()->id()]);
        return [
            'name' => ['required'],
            'code' => ['required', 'unique:meals'],
            'description' => ['nullable','string'],
            'image' => ['nullable' , 'image'],
            'category_id' => ['required', 'integer' ,'exists:categories,id'],
            'type' => ['required' ,  'in:pre-order,ready'],
            'is_active' => ['boolean'],
            'days' => ['required' , 'array' ],
            "days.*"  => [
                'required',
                'string',
                'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            ],


            'accessories' => [ 'array' ],
            "accessories.*"  => [
                'exists:accessories,id',
            ],

            'additions' => [ 'array' ],
            "additions.*"  => [
                'exists:additions,id',
            ],




            'images' => [ 'array' ],
            "images.*"  => [
                'image',
            ],



            'preparation_time' => ['nullable' ],
            'user_id' => ['required'],
            'price' => [ 'numeric' , 'between:0,999', 'regex:/^\d+(\.\d{1,2})?$/'],
        ];
    }


    public function messages()
    {
        return [
            'days.required' => 'The days field is required',
            'days.array' => 'The days field must be an array',
            'days.*' => 'The days field must in (saturday,sunday,monday,tuesday,wednesday,thursday,friday)',
            'type.in' => 'The  type field must in (pre-order,ready)',
        ];
    }

}
