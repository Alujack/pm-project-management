<?php 

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{userId}', function($user, $userId){
    return (int) $user->id === (int)$userId;
});

Broadcast::channel('project.{projectId}', function($user, $projectId){
    return true;
});

Broadcast::channel('issue.{issueId}', function ($user, $issueId) {
    return true;
});

