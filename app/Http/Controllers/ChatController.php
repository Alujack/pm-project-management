<?php

// Updated ChatController.php
namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Models\Issue;
use App\Models\Project;
use Auth;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getMessages(Project $project, Issue $issue)
    {
        // Check if the issue belongs to the project
        if ($issue->projectID !== $project->id) {
            return response()->json(['message' => 'Issue does not belong to this project'], 403);
        }

        // Check if user has access to the project
        if (!Auth::user()->projects->contains($project->id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($issue->chats()->with('user')->latest()->get());
    }

    public function sendMessage(Request $request, Project $project, Issue $issue)
    {
        // Check if the issue belongs to the project
        if ($issue->projectID !== $project->id) {
            return response()->json(['message' => 'Issue does not belong to this project'], 403);
        }

        // Check if user has access to the project
        if (!Auth::user()->projects->contains($project->id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $chat = $issue->chats()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Load the user relationship for broadcasting
        $chat->load('user');

        // Broadcast to everyone including sender
        broadcast(new NewChatMessage($chat));

        return response()->json($chat, 201);
    }
}