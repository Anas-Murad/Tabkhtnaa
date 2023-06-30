<?php

namespace App\Http\Controllers\api\v1;

use App\Events\ChatEvent;
use App\Events\LiveLocationEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\chat\ConversationMessageRequest;
use App\Http\Requests\api\v1\chat\ConversationRequest;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Traits\FCMTrait;
use App\Traits\HelperTrait;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConversationController extends Controller
{
    use HelperTrait, FCMTrait;

    public function list()
    {
        $user = auth()->user();
        $conversation = Conversation::where([
            'user1_id' => $user->id,
            'user1_type' => $user->type,
            'user1_deleted_at' =>null,
        ])
            ->orWhere(function ($query) use ($user) {
                $query->where([
                    'user2_id' => $user->id,
                    'user2_type' => $user->type,
                    'user2_deleted_at' =>null,
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

        $request->validate(['conversation_id' => ['required', 'integer'],]);
        $user = auth()->user();

        if ($request->filled('page') && $request->page > 1) {
            $messages = ConversationMessage::with('files')
                ->where('conversation_id', $request->conversation_id)
                ->simplePaginate(10);
            return $this->returnPaginateData($messages,);
        } else {
            $conversation = Conversation::
            where(function ($query) use ($user) {
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
                ->findOrFail($request->conversation_id);
            $messages = ConversationMessage::with('files')
                ->where('conversation_id', $request->conversation_id)
                ->simplePaginate(10);
            return $this->returnPaginateDataWithOther(
                $messages,
                $conversation,
                'conversation',
            );
        }
    }

    public function store(ConversationRequest $request)
    {
        abort_if($request->user1_type == $request->user2_type, 403, 'لايمكن فتح محادثه من نفس مستوى المستخدم');
        $conversation = Conversation::where([
            'user1_id' => $request->user1_id,
            'user1_type' => $request->user1_type,
            'order_id' => $request->order_id,
            'user1_deleted_at' =>null,
            'user2_deleted_at' =>null,
        ])->orWhere(function ($query) use ($request) {
            $query->where([
                'user2_id' => $request->user2_id,
                'user2_type' => $request->user2_type,
                'order_id' => $request->order_id,
                'user1_deleted_at' =>null,
                'user2_deleted_at' =>null,
            ]);
        })
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create($request->safe(['user1_id', 'user1_type', 'user2_id', 'user2_type', 'user1_deleted_at', 'user2_deleted_at', 'order_id',]));
        }


        if ($request->filled('message') || $request->hasFile('image')) {
            $messages = $conversation->messages()->create([
                "user_id" => $request->user1_id,
                "message" => $request->message ?? null,
            ]);

            if ($request->hasFile('image')) {
                $path = $this->saveImage($request->image, 'uploads/conversations');
                $messages->files()->create([
                    'conversation_id' => $conversation->id,
                    'path' => $path,
                ]);
            }

            $messages->loadMissing('files');
            $conversation->update(['last_message_id' => $messages->id]);
            // $conversation->loadMissing(['user1' ,'user2','order']);
            event(new ChatEvent(
                $conversation->id,
                $conversation->user1_id,
                $conversation->user2_id,
                $conversation->order_id,
                $messages,
                $conversation
            ));
            $this->PushNotification($conversation->user2_id, [
                'title' => "لديك رساله جديدة",
                'body' => $messages->message,
                'order_id' => $conversation->order_id,
                'user1_id' => $conversation->user1_id,
                'user2_id' => $conversation->user2_id,
                'screen' => 'order_chat',
            ]);
        }

        return $this->returnDataArray([
            'conversation_id' => $conversation->id,
            'messages' => $messages ?? null,
        ]);
    }

    public function send_message(ConversationMessageRequest $request)
    {


        $conversation = Conversation::find($request->conversation_id);
        if ($request->filled('message') || $request->hasFile('image')) {
            $messages = $conversation->messages()->create([
                "user_id" => $request->user1_id,
                "message" => $request->message ?? null,
            ]);
            if ($request->hasFile('image')) {
                $path = $this->saveImage($request->image, 'uploads/conversations');
                $messages->files()->create([
                    'conversation_id' => $conversation->id,
                    'path' => $path,
                ]);
            }

            $messages->loadMissing('files');
            $conversation->update(['last_message_id' => $messages->id]);
            // $conversation->loadMissing(['user1' ,'user2']);
            event(new ChatEvent(
                $conversation->id,
                $conversation->user1_id,
                $conversation->user2_id,
                $conversation->order_id,
                $messages,
                $conversation
            ));
            $this->PushNotification($conversation->user2_id, [
                'title' => "لديك رساله جديدة",
                'body' => $messages->message,
                'order_id' => $conversation->order_id,
                'user1_id' => $conversation->user1_id,
                'user2_id' => $conversation->user2_id,
                'screen' => 'order_chat',
            ]);
        }
        return $this->returnDataArray([
            'conversation_id' => $conversation->id,
            'messages' => $messages ?? null,
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate(['conversation_id' => ['required', 'integer'],]);
        $user = auth()->user();
        $conversation = Conversation::
        where(function ($query) use ($user) {
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
            ->findOrFail($request->conversation_id);


        if ($conversation->user2_id == $user->id) {
            $conversation->user2_deleted_at = now();
        } elseif ($conversation->user1_id == $user->id) {
            $conversation->user1_deleted_at = now();
        }
        $conversation->save() ;

        if (!is_null($conversation->user1_deleted_at) && !is_null($conversation->user2_deleted_at)){
            $conversation->delete();
        }

        return $this->returnSuccess('تم حذف المحادثه بنجاح');
    }


}
