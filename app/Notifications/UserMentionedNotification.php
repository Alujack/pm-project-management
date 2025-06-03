<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Tymon\JWTAuth\Claims\Subject;

class UserMentionedNotification extends Notification
{
    protected $mention;

    public function __construct($mention)
    {
        $this->mention = $mention;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('You were mentioned in a project')
            ->line('You have been mentioned by ' . $this->mention->mentioningUser->name . ' in the project: ' . $this->mention->project->name)
            ->line('Message: ' . $this->mention->message)
            ->line('Thank you!');
    }
}

