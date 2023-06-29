<?php

namespace App\Http\Requests\api\v1\complaint;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComplaintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'user_id' =>'required',
            'type' => 'required',
            'photo' => 'nullable|image',
            'note' => 'nullable',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
