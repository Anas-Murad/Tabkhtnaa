<?php

namespace App\Http\Requests\api\v1\bank;

use Illuminate\Foundation\Http\FormRequest;

class UpdatebankInfoRequest extends FormRequest
{
    public function rules(): array
    {
        $this->merge(['user_id' => auth()->id()]);
        return [
            'bankInfo_id' => 'required',
            'user_id' => 'required',
            'country_id' => ['required', 'integer'],
            'city_id' => ['required' , 'integer'],
            'type' => ['required','in:bank,shop_exchange,wallet'],
            'bank_name' => [
                'required_with:iban',
                'required_with:swift_code',
//                'required_if:wallet_name,==,null',
//                'required_if:wallet_number,==,null',
//                'required_if:shop_exchange_name,==,null',
            ],
            'iban' => [
                'required_with:bank_name',
                'required_with:swift_code',
            ],
            'swift_code' => [
                'required_with:bank_name',
                'required_with:iban',
                'integer'
            ],
            'details' => [
                'nullable'
            ],
            'shop_exchange_name' => [
                'nullable'
            ],
            'wallet_name' => [
                'required_with:wallet_number',
            ],
            'wallet_number' => [
                'required_with:wallet_name',
                'integer'
            ],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
