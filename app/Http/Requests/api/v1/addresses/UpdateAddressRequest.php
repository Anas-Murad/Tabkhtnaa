<?php

namespace App\Http\Requests\api\v1\addresses;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
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
            'id' => 'required|numeric',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
