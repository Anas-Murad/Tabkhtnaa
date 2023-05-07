<?php

namespace App\Http\Requests\api\v1\auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompleteRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'front_id_image' => 'nullable|image',
            'background_id_photo' => 'nullable|image',
            'no_criminal_record' => 'nullable|image',
            'leave_diseases' => 'nullable|image',
            'practicing_profession' => 'nullable|image',
            'stool_examination' => 'nullable|image',
            'urine_test' => 'nullable|image',
            'blood_test' => 'nullable|image',
            'driving_license' => 'nullable|image',
            'car_trunk_image' => 'nullable|image',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
