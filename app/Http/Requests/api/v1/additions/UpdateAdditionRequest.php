<?php

namespace App\Http\Requests\api\v1\additions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdditionRequest extends FormRequest
{


    public function rules(): array
    {
        $this->merge(['user_id' => auth()->id()]);
        return [
            'addition_category_id' => ['required' ,
                Rule::exists('addition_categories' , 'id')
                    ->where('user_id' ,auth()->id())
            ],
            'name' => ['required'],
            'user_id' => ['required'],
            'price' => [ 'numeric' , 'between:0,999', 'regex:/^\d+(\.\d{1,2})?$/'],
        ];
    }



}
