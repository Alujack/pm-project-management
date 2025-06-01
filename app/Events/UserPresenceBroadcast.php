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
 * Event for real-time user presence in projects
 */
class UserPresenceBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $projectId;
    public $status; // 'online', 'offline', 'typing'

    public function __construct($user, int $projectId, string $status = 'online')
    {
        $this->user = $user;
        $this->projectId = $projectId;
        $this->status = $status;
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('project.' . $this->projectId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.presence';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'status' => $this->status,
            'timestamp' => now(),
        ];
    }
}