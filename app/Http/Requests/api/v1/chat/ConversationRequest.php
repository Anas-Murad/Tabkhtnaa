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
        $this->merge(['user2_type' => User::find($this->user2_id)->type ?? null]);

        return [
            'user1_id' => ['required'],
            'user1_type' => ['required'],
            'user2_id' => ['required', 'exists:users,id'],
            'user2_type' => ['required'],
            'order_id' => array_values(array_filter([
                'nullable',
                'integer',
                $this->filled('order_id')
                    ? Rule::exists('orders', 'id')->where(function (Builder $query) {
                        if ($this->user1_type == 'chef') {
                            $query->where('chef_id', $this->user1_id);
                        } elseif ($this->user1_type == 'client') {
                            $query->where('user_id', $this->user1_id);
                        } elseif ($this->user1_type == 'delivery') {
                            $query->where('delivery_id', $this->user1_id);
                        }
                    })
                    : null,
            ])),
            'message' => ['nullable'],
            'image' => ['nullable', 'image'],
        ];
    }
}
