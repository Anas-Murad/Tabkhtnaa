<?php

namespace App\Http\Requests\api\v1\chat;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConversationMessageRequest extends FormRequest
{
    public function rules(): array
    {

        $this->merge(['user_id' => auth()->id()]);
        return [
            'conversation_id' => ['required', 'integer',
                Rule::exists('conversations', 'id')->where(function (Builder $query) {
                    $query->where('user1_id', $this->user_id);
                    $query->orWhere('user2_id', $this->user_id);
                }),
            ],
            'user_id' => ['required'],
            'message' => ['nullable'],
            'image' => ['required_without:message' ,'image'],
        ];
    }
}
