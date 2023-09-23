<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateProfileRequest extends FormRequest
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
        return [
            'email' =>[
                Rule::unique('users')->where('source' ,$this->user()->source)
                    ->whereNotNull('email') ->ignore($this->user()->id)],
            'name' => 'required|string',
            'dob' => 'required|date',
            'def_lang' => 'in:ar,en',
            'mobile' =>['required','numeric' ,Rule::unique('users')->where('country_code' ,request()->country_code) ->ignore($this->user()->id)] ,
            'gender' => 'required|in:male,female,other',
        ];
    }
}
