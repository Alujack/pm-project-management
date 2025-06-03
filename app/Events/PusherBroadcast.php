<?php

namespace App\Events;

use App\Models\Mention;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PusherBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mention;

    /**
     * Create a new event instance.
     */
    public function __construct(Mention $mention)
    {
        $this->mention = $mention;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    // In PusherBroadcast.php
    public function broadcastOn(): array
    {
        return [
            new Channel('project.' . $this->mention->project_id),
            new Channel('user.' . $this->mention->mentioned_user_id),
        ];
    }


    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'user.mentioned';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->mention->id,
            'project_id' => $this->mention->project_id,
            'message' => $this->mention->message,
            'read' => $this->mention->read,
            'mentioned_user_id' => $this->mention->mentioned_user_id,
            'mentioned_email' => $this->mention->mentioned_email,
            'is_invited_user' => $this->mention->isForInvitedUser(),
            'mentioning_user' => [
                'id' => $this->mention->mentioningUser->id,
                'name' => $this->mention->mentioningUser->name,
            ],
            'created_at' => $this->mention->created_at,
        ];
    }
}