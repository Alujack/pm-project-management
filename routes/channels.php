<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// Private user channel for individual notifications
Broadcast::channel('user.{userId}', function ($user, $userId) {
    \Log::info("Auth attempt", ['user' => $user->id, 'channel' => $userId]);
    return (int) $user->id === (int) $userId;
});

// Presence channel for project-wide activities
Broadcast::channel('project.{projectId}', \App\Broadcasting\MentionChannel::class);