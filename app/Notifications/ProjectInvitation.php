<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectInvitation extends Notification 
{
    protected $invitation;
    protected $project;

    public function __construct($invitation, $project)
    {
        $this->invitation = $invitation;
        $this->project = $project;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // $url = url('/api/invitations/verify/' . $this->invitation->token);
        $url = config('app.frontend_url') . '/invitations/verify/' . $this->invitation->token;

        return (new MailMessage)
            ->subject('You\'ve Been Invited to Join a Project')
            ->line('You have been invited to join the project: ' . $this->project->name)
            ->action('Accept Invitation', $url)
            ->line('If you did not expect this invitation, you can ignore this email.');
    }
}