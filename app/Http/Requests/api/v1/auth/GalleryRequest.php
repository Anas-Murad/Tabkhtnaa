<?php

namespace App\Http\Requests\api\v1\auth;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
{

    // reviewed
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'images.*' => 'required|image',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
