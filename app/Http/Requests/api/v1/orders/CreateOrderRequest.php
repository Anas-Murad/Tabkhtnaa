<?php

namespace App\Http\Requests\api\v1\orders;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        $this->merge(['user_id' => auth()->id()]);
        return [
            'user_id' => ['required', 'integer'],
            'delivery_type' => ['required', 'in:delivery,pick_up'],

            'order_id' => ['nullable', 'integer',
                Rule::exists('orders', 'id')->where(function (Builder $query) {
                    return $query
                        ->where('user_id', 'user_id')
                        ->where('status', 'not_ordered') ;
                }),
            ],




            'chef_id' => ['required', 'integer',
                Rule::exists('users', 'id')->where(function (Builder $query) {
                    return $query
                        ->where('type', 'chef')
                        ->where('account_status', 'active');
                }),
            ],
            'cart_id' => ['required', 'integer',
                Rule::exists('carts', 'id')->where(function (Builder $query) {
                    return $query
                        ->where('maker_id', $this->chef_id)
                        ->where('user_id', $this->user_id)
                        ->whereNull('deleted_at');
                }),
            ],
            'coupon_id' => ['nullable', 'integer','required_with:coupon' ,
                Rule::exists('coupons', 'id')->where(function (Builder $query) {
                    return $query
                        ->where('coupon', $this->coupon)
                        ->where('validity_date', '>=' , now())
                        ->where('status', 'active');
                }),
            ],
            'coupon' => ['nullable', 'required_with:coupon_id' ,
                Rule::exists('coupons', 'coupon')->where(function (Builder $query) {
                    return $query
                        ->where('id', $this->coupon_id)
                        ->where('validity_date', '>=' , now())
                        ->where('status', 'active');
                }),
            ],

            'address_id' => ['required', 'integer' ,
                Rule::exists('user_addresses', 'id')->where(function (Builder $query) {
                    return $query
                        ->where('user_id', $this->user_id)
                      ;
                }),
            ],
            'details' => ['nullable'],
            'payment_method' => ['required'  , 'in:wallet,cash,cards'],

        ];
    }
}
