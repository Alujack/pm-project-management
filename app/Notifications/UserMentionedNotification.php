<?php

namespace App\Notifications;

use App\Models\Mention;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class UserMentionedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $mention;

    /**
     * Create a new notification instance.
     */
    public function __construct(Mention $mention)
    {
        $this->mention = $mention;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'mention_id' => $this->mention->id,
            'project_id' => $this->mention->project_id,
            'message' => $this->mention->message,
            'mentioning_user_id' => $this->mention->mentioning_user_id,
            'mentioning_user_name' => $this->mention->mentioningUser->name,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @return BroadcastMessage
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'mention_id' => $this->mention->id,
            'project_id' => $this->mention->project_id,
            'message' => $this->mention->message,
            'mentioning_user_id' => $this->mention->mentioning_user_id,
            'mentioning_user_name' => $this->mention->mentioningUser->name,
        ]);
    }
}