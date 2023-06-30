<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LiveLocationEvent implements ShouldBroadcast
{
//    use Dispatchable, InteractsWithSockets, SerializesModels;
    use SerializesModels;

    public $latitude;
    public $longitude;
    public $userId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(float $latitude, float $longitude ,  int $userId)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {

        return ['user-channel.' . $this->userId];
        return new PrivateChannel('user-channel.' . $this->userId);

    }

    public function broadcastAs()
    {
        return 'live_location';
    }
}
