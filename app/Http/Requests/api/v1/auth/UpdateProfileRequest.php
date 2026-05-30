<?php

namespace App\Http\Requests\api\v1\auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        $this->merge(['mobile' => ltrim( $this->get('mobile') , '0')]);
        return [
            'email' =>[
                Rule::unique('users')->where('source' ,$this->user()->source)
                ->whereNotNull('email') ->ignore($this->user()->id)],
            'residence_country_id' => 'required|exists:countries,id|integer',
            'country_code' => 'required|exists:countries,phonecode',
            'name' => 'required|string',
            'dob' => 'required|date',
            'def_lang' => 'nullable|in:ar,en,fr',
            'mobile' =>['required','numeric' ,Rule::unique('users')->where('country_code' ,request()->country_code) ->ignore($this->user()->id)] ,
            'gender' => 'required|in:male,female,other',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
