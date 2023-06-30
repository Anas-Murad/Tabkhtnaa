<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\chat\ConversationMessageRequest;
use App\Models\ConversationMessage;

class ConversationMessageController extends Controller
{
    public function index()
    {
        return ConversationMessage::all();
    }

    public function store(ConversationMessageRequest $request)
    {
        return ConversationMessage::create($request->validated());
    }

    public function show(ConversationMessage $conversationMessage)
    {
        return $conversationMessage;
    }

    public function update(ConversationMessageRequest $request, ConversationMessage $conversationMessage)
    {
        $conversationMessage->update($request->validated());

        return $conversationMessage;
    }

    public function destroy(ConversationMessage $conversationMessage)
    {
        $conversationMessage->delete();

        return response()->json();
    }
}
