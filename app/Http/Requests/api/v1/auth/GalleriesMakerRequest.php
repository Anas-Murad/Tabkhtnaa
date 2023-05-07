<?php

namespace App\Http\Requests\api\v1\auth;

use Illuminate\Foundation\Http\FormRequest;

class GalleriesMakerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'photo.*' => 'required|image',
            'type' => 'required',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
