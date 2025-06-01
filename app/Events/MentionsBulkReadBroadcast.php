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
 * Event for when multiple mentions are marked as read
 */
class MentionsBulkReadBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $mentions;

    public function __construct(int $userId, Collection $mentions)
    {
        $this->userId = $userId;
        $this->mentions = $mentions;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'mentions.bulk-read';
    }

    public function broadcastWith(): array
    {
        return [
            'mention_ids' => $this->mentions->pluck('id')->toArray(),
            'count' => $this->mentions->count(),
            'read_at' => now(),
        ];
    }
}
