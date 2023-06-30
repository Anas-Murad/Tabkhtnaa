<?php

namespace App\Http\Requests\api\v1\chat;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConversationRequest extends FormRequest
{
    public function rules(): array
    {
        $this->merge(['user1_id' => auth()->id()]);
        $this->merge(['user1_type' => auth()->user()->type]);
        $this->merge(['user2_type' => User::find($this->user2_id)->type ??  null ]);
        return [
            'user1_id' => ['required'],
            'user1_type' => ['required'],
            'user2_id' => ['required', 'exists:users,id'],
            'user2_type' => ['required_if_accepted:user2_id'],
            'order_id' => ['required', 'integer',
                Rule::exists('orders', 'id')->where(function (Builder $query) {
                    if ($this->user1_type == 'chef') {
                        return $query->where('chef_id', $this->user1_id);
                    } elseif ($this->user1_type == 'client') {
                        return $query->where('user_id', $this->user1_id);
                    } elseif ($this->user1_type == 'delivery') {
                        return $query->where('delivery_id', $this->user1_id);
                    }
                }),
            ],
            'message' => ['nullable'],
            'image' => ['nullable' ,'image'],
        ];
    }
}
