<?php

namespace App\Http\Requests\api\v1\addresses;

use Illuminate\Foundation\Http\FormRequest;

class CreateAddressRequest extends FormRequest
{
    public function rules(): array
    {

        $this->merge(['user_id' =>auth()->id() , null ]);
        return [
            'user_id' => 'required',
            'name' => 'required|string',
            'place' => 'required|string',
            'country_id' => 'required|exists:countries,id|integer',
            'city_id' => 'required|exists:cities,id|integer',
            'neighborhood' => 'required|string',
            'build_address' => 'required|string',
            'floor' => 'required|string',
            'apartment_address' => 'required|string',
            'details' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
        ];


    /*
            mobile_verified
            'online_status'  ,['online','busy','unavailable','available']
            'account_status'  ,['pending','active','rejected','blocked']
            account_comment
            email_verified_at
     */
    }

    public function authorize(): bool
    {
        return true;
    }
}
