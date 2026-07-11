<?php

namespace App\Http\Requests\api\v1\rating;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRatingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $this->merge(['user_id' => auth()->id()]);

        return [
            'user_id' => 'required',
            'chef_id' => 'required|numeric',
            'order_id' => [
                'required',
                'numeric',
                Rule::unique('ratings', 'order_id'),
            ],
            'rating_chef' => 'nullable|numeric|min:1|max:5',
            'rating_delivery' => 'nullable|numeric|min:1|max:5',
            'rating_speed_chef' => 'nullable|numeric|min:1|max:5',
            'rating_speed_delivery' => 'nullable|numeric|min:1|max:5',
            'note' => 'nullable|string|max:500',
            'photo' => 'nullable|image',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $order = Order::where('user_id', auth()->id())
                ->where('id', $this->order_id)
                ->first();

            if (!$order) {
                $validator->errors()->add('order_id', 'الطلب غير موجود');
                return;
            }

            if ($order->status !== 'delivered') {
                $validator->errors()->add('order_id', 'لا يمكن تقييم طلب غير مكتمل');
            }
        });
    }

    public function messages()
    {
        return [
            'order_id.unique' => 'تم تقييم هذا الطلب مسبقاً',
        ];
    }
}
