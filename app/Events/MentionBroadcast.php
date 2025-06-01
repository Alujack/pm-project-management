<?php

namespace App\Events;

use App\Models\Mention;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MentionBroadcast implements ShouldBroadcast
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
    public function broadcastOn(): array
    {
        $channels = [
            new PresenceChannel('project.' . $this->mention->project_id),
        ];

        // Only add user channel if mentioned_user_id exists (not for invited users)
        if ($this->mention->mentioned_user_id) {
            $channels[] = new PrivateChannel('user.' . $this->mention->mentioned_user_id);
        }

        return $channels;
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

    /**
     * Determine if this event should be broadcast.
     */
    public function shouldBroadcast(): bool
    {
        // Add any conditional logic here if needed
        // For example, only broadcast if the mention is not read
        return true;
    }
}