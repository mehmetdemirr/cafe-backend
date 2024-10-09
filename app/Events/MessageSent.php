<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow //TODO sonrasında now kaldırırız
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $text;
    public int $sender_id;
    public int $receiver_id;
    /**
     * Create a new event instance.
     */
    public function __construct(
        int $sender_id, 
        int $receiver_id, 
        string $text, 
        )
    {
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->text = $text;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel( "chat.$this->sender_id.$this->receiver_id");
    }

    public function broadcastWith(): array
    {
        return [
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'text' => $this->text,
        ];
    }
}
