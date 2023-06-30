<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatEvent implements ShouldBroadcast
{
    //use Dispatchable, InteractsWithSockets, SerializesModels;
    use SerializesModels;


    public
$conversation_id,
$user1_id,
$user2_id,
$order_id,
$messages,
$conversation ;

    /**
     * @param $conversation_id
     * @param $user1_id
     * @param $user2_id
     * @param $order_id
     * @param $messages
     * @param $conversation
     */
    public function __construct($conversation_id, $user1_id, $user2_id, $order_id, $messages, $conversation)
    {
        $this->conversation_id = $conversation_id;
        $this->user1_id = $user1_id;
        $this->user2_id = $user2_id;
        $this->order_id = $order_id;
        $this->messages = $messages;
        $this->conversation = $conversation;
    }




    public function broadcastOn(): array
    {
        return  ['chat-channel.' . $this->conversation_id] ;
        return [
            new PrivateChannel('channel-name')
        ];
    }

    public function broadcastAs()
    {
        return 'chat';
    }

}
