<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $conversation_id,
        public int $user1_id,
        public int $user2_id,
        public ?int $order_id,
        public $messages,
        public $conversation,
    ) {
    }

    public function broadcastOn(): array
    {
        return [new Channel('chat-channel.' . $this->conversation_id)];
    }

    public function broadcastAs(): string
    {
        return 'chat';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversation_id,
            'order_id' => $this->order_id,
            'message' => $this->messages,
        ];
    }
}
