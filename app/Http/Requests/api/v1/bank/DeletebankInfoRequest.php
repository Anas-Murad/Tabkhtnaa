<?php

namespace App\Http\Requests\api\v1\bank;

use Illuminate\Foundation\Http\FormRequest;

class DeletebankInfoRequest extends FormRequest
{
    public function rules(): array
    {
        $this->merge(['user_id' => auth()->id()]);
        return [
            'bankInfo_id' => 'required',
            'user_id' => 'required',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
