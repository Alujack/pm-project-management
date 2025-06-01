<?php

namespace App\Events;

use App\Models\Mention;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

/**
 * Event for when a mention is marked as read
 */
class MentionReadBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mention;

    public function __construct(Mention $mention)
    {
        $this->mention = $mention;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->mention->mentioned_user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'mention.read';
    }

    public function broadcastWith(): array
    {
        return [
            'mention_id' => $this->mention->id,
            'read' => true,
            'read_at' => now(),
        ];
    }
}
