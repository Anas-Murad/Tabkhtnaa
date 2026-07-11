<?php

namespace App\Http\Controllers\api\v1;

use App\Events\ChatEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\chat\ConversationMessageRequest;
use App\Http\Requests\api\v1\chat\ConversationRequest;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Sanction;
use App\Traits\FCMTrait;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    use HelperTrait, FCMTrait;

    public function list()
    {
        $user = auth()->user();
        $conversation = Conversation::where([
            'user1_id' => $user->id,
            'user1_type' => $user->type,
            'user1_deleted_at' => null,
        ])
            ->orWhere(function ($query) use ($user) {
                $query->where([
                    'user2_id' => $user->id,
                    'user2_type' => $user->type,
                    'user2_deleted_at' => null,
                ]);
            })
            ->with('user1:id,name,country_code,mobile,profile_image')
            ->with('user2:id,name,country_code,mobile,profile_image')
            ->with('lastMessage.user:id,name,country_code,mobile,profile_image')
            ->latest()
            ->simplePaginate(10);

        return $this->returnPaginateData($conversation);
    }

    public function get(Request $request)
    {
        $request->validate(['conversation_id' => ['required', 'integer']]);
        $user = auth()->user();
        $conversation = $this->findParticipantConversation($user, $request->conversation_id);

        if ($request->filled('page') && $request->page > 1) {
            $messages = ConversationMessage::with('files')
                ->where('conversation_id', $conversation->id)
                ->orderBy('created_at')
                ->simplePaginate(10);

            return $this->returnPaginateData($messages);
        }

        $messages = ConversationMessage::with('files')
            ->where('conversation_id', $conversation->id)
            ->orderBy('created_at')
            ->simplePaginate(10);

        return $this->returnPaginateDataWithOther(
            $messages,
            $conversation,
            'conversation',
        );
    }

    public function store(ConversationRequest $request)
    {
        abort_if($request->user1_type == $request->user2_type, 403, 'لايمكن فتح محادثه من نفس مستوى المستخدم');
        $this->assertCanChat($request->user1_id);
        $this->assertCanChat($request->user2_id);

        $conversation = $this->findExistingConversation(
            $request->user1_id,
            $request->user2_id,
            $request->order_id
        );

        if (!$conversation) {
            $conversation = Conversation::create($request->safe([
                'user1_id',
                'user1_type',
                'user2_id',
                'user2_type',
                'user1_deleted_at',
                'user2_deleted_at',
                'order_id',
            ]));
        }

        $messages = null;
        if ($request->filled('message') || $request->hasFile('image')) {
            $messages = $this->persistMessage($conversation, $request->user1_id, $request);
            $this->notifyRecipient($conversation, $messages, $request->user1_id);
        }

        return $this->returnDataArray([
            'conversation_id' => $conversation->id,
            'messages' => $messages,
        ]);
    }

    public function send_message(ConversationMessageRequest $request)
    {
        $user = auth()->user();
        $conversation = $this->findParticipantConversation($user, $request->conversation_id);
        $this->assertCanChat($user->id);

        $messages = null;
        if ($request->filled('message') || $request->hasFile('image')) {
            $messages = $this->persistMessage($conversation, $user->id, $request);
            $this->notifyRecipient($conversation, $messages, $user->id);
        }

        return $this->returnDataArray([
            'conversation_id' => $conversation->id,
            'messages' => $messages,
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate(['conversation_id' => ['required', 'integer']]);
        $user = auth()->user();
        $conversation = $this->findParticipantConversation($user, $request->conversation_id);

        if ($conversation->user2_id == $user->id) {
            $conversation->user2_deleted_at = now();
        } elseif ($conversation->user1_id == $user->id) {
            $conversation->user1_deleted_at = now();
        }
        $conversation->save();

        if (!is_null($conversation->user1_deleted_at) && !is_null($conversation->user2_deleted_at)) {
            $conversation->delete();
        }

        return $this->returnSuccess('تم حذف المحادثه بنجاح');
    }

    private function findParticipantConversation($user, int $conversationId): Conversation
    {
        return Conversation::where(function ($query) use ($user) {
            $query->where([
                'user1_id' => $user->id,
                'user1_type' => $user->type,
            ])->orWhere(function ($query) use ($user) {
                $query->where([
                    'user2_id' => $user->id,
                    'user2_type' => $user->type,
                ]);
            });
        })
            ->with('user1:id,name,country_code,mobile,profile_image')
            ->with('user2:id,name,country_code,mobile,profile_image')
            ->with('lastMessage.user:id,name,country_code,mobile,profile_image')
            ->findOrFail($conversationId);
    }

    private function findExistingConversation(int $user1Id, int $user2Id, ?int $orderId): ?Conversation
    {
        return Conversation::query()
            ->where(function ($query) use ($user1Id, $user2Id) {
                $query->where(function ($query) use ($user1Id, $user2Id) {
                    $query->where('user1_id', $user1Id)->where('user2_id', $user2Id);
                })->orWhere(function ($query) use ($user1Id, $user2Id) {
                    $query->where('user1_id', $user2Id)->where('user2_id', $user1Id);
                });
            })
            ->when(
                $orderId,
                fn ($query) => $query->where('order_id', $orderId),
                fn ($query) => $query->whereNull('order_id')
            )
            ->whereNull('user1_deleted_at')
            ->whereNull('user2_deleted_at')
            ->first();
    }

    private function persistMessage(Conversation $conversation, int $userId, Request $request): ConversationMessage
    {
        $message = $conversation->messages()->create([
            'user_id' => $userId,
            'message' => $request->message ?? null,
        ]);

        if ($request->hasFile('image')) {
            $path = $this->saveImage($request->image, 'uploads/conversations');
            $message->files()->create([
                'conversation_id' => $conversation->id,
                'path' => $path,
            ]);
        }

        $message->loadMissing('files');
        $conversation->update(['last_message_id' => $message->id]);
        event(new ChatEvent(
            $conversation->id,
            $conversation->user1_id,
            $conversation->user2_id,
            $conversation->order_id,
            $message,
            $conversation
        ));

        return $message;
    }

    private function notifyRecipient(Conversation $conversation, ConversationMessage $message, int $senderId): void
    {
        $recipientId = $conversation->user1_id == $senderId
            ? $conversation->user2_id
            : $conversation->user1_id;

        $this->PushNotification($recipientId, [
            'title' => 'لديك رساله جديدة',
            'body' => $message->message,
            'order_id' => $conversation->order_id,
            'user1_id' => $conversation->user1_id,
            'user2_id' => $conversation->user2_id,
            'conversation_id' => $conversation->id,
            'screen' => 'order_chat',
        ]);
    }

    private function assertCanChat(int $userId): void
    {
        $blocked = Sanction::query()
            ->where('user_id', $userId)
            ->where('type', 'no_chat')
            ->where(function ($query) {
                $query->whereNull('end_time')->orWhere('end_time', '>', now());
            })
            ->exists();

        abort_if($blocked, 403, __('messages.sanction_type_no_chat'));
    }
}
