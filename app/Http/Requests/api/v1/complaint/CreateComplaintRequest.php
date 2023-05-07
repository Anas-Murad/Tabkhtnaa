<?php

namespace App\Http\Requests\api\v1\complaint;

use Illuminate\Foundation\Http\FormRequest;

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
            'type' => 'required',
            'photo' => 'nullable|image',
            'description' => 'nullable',
            'status' => 'nullable',
            'note' => 'nullable',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
