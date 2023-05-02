<?php

namespace App\Http\Requests\api\v1\auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function rules()
    {
        $this->merge(['mobile' => ltrim( $this->get('mobile') , '0')]);
        return [
            'email' => 'required|unique:users|email',
            'residence_country_id' => 'required|exists:countries,id|integer',
            'country_code' => 'required|exists:countries,phonecode',
            'name' => 'required|string',
            'dob' => 'required|date',
            'mobile' =>['required','numeric' ,
                Rule::unique('users')->where('country_code' ,request()->country_code)] ,
            'gender' => 'required|in:male,female,other',
            'profile_image' => 'required|image',
            'type' => 'required|in:client,delivery,chef',
            'password' => 'required|min:6|confirmed',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
