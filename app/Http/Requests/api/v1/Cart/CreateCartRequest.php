<?php

namespace App\Http\Requests\api\v1\Cart;

use Couchbase\Role;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCartRequest extends FormRequest
{
    public function rules(): array
    {

        $this->merge(['user_id' => auth()->id()]);
        return [

            'user_id' => ['required', 'integer'],

            'maker_id' => ['required', 'integer',
                Rule::exists('users', 'id')->where(function (Builder $query) {
                    return $query
                        ->where('type', 'chef')
                        ->where('account_status', 'active');
                }),
            ],

            'meal_id' => ['required', 'integer',
                Rule::exists('meals' , 'id')->where(function (Builder $query) {
                    return $query
                        ->where('user_id', $this->maker_id)
                        ->where('is_active', true)
                        ->where('admin_status', 'confirmed');
                }),
            ],


            'quantity' => ['required', 'integer' ,'min:1'],




            'note' => ['string', 'nullable'],

            'accessories' => ['array'],
            "accessories.*" => [
                'exists:accessories,id',

            ],
            'additions' => ['array'],
            "additions.*" => [
                Rule::exists('meal_additions' , 'addition_id')->where(function (Builder $query) {
                    return $query
                        ->where('meal_id', $this->meal_id)
                        ;
                }),
            ],
        ];
    }






    public function messages()
    {
        return [
            'maker_id.exists' => "maker id is invalid, it must be associated with an account that meets the following conditions : 1- To be a chef (DB:type) , 2- The account must be active (DB:account_status)",
            'meal_id.exists' => 'meal id is invalid, It must be related to a meal that meets the following conditions: 1- It must be linked to the chef account through the same maker_id field (DB:user_id)  , 2- The meal must be active (DB:is_active) , 3- To be approved by the admin (DB:admin_status)',
            'additions.*.exists' => 'additions is invalid, It must be related to a meal that meets the following conditions: 1- It must be linked to the meal account through the same meal_id field (DB:meal_id)  ',
        ];
    }

}
