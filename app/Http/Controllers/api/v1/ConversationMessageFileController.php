<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\chat\ConversationMessageFileRequest;
use App\Models\ConversationMessageFile;

class ConversationMessageFileController extends Controller
{
    public function index()
    {
        return ConversationMessageFile::all();
    }

    public function store(ConversationMessageFileRequest $request)
    {
        return ConversationMessageFile::create($request->validated());
    }

    public function show(ConversationMessageFile $conversationMessageFile)
    {
        return $conversationMessageFile;
    }

    public function update(ConversationMessageFileRequest $request, ConversationMessageFile $conversationMessageFile)
    {
        $conversationMessageFile->update($request->validated());

        return $conversationMessageFile;
    }

    public function destroy(ConversationMessageFile $conversationMessageFile)
    {
        $conversationMessageFile->delete();

        return response()->json();
    }
}
