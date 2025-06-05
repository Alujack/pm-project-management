<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use App\Models\Mention;
use App\Models\Project;
use App\Models\User;
use App\Models\Invitation;
use App\Notifications\UserMentionedNotification;
use Illuminate\Http\Request;

class MentionController extends Controller
{
    /**
     * Store a new mention
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'mentioned_user_id' => 'exists:users,id',
            'message' => 'required|string|max:500',
            'is_invited_user' => 'boolean',
        ]);

        // Get the project
        $project = Project::findOrFail($validated['project_id']);

        // Find the mentioned user
        $mentionedUser = User::findOrFail($validated['mentioned_user_id']);

        // Check if the user was invited and accepted the invitation
        $isValidInvitation = Invitation::where('project_id', $validated['project_id'])
            ->where('email', $mentionedUser->email)
            ->where('accepted', true)
            ->exists();

        if ($validated['is_invited_user'] && !$isValidInvitation) {
            return response()->json([
                'message' => 'The mentioned user has not accepted the invitation for this project'
            ], 403);
        }

        // Create mention
        $mention = Mention::create([
            'project_id' => $validated['project_id'],
            'mentioning_user_id' => auth()->id(),
            'mentioned_user_id' => $validated['mentioned_user_id'],
            'mentioned_email' => $mentionedUser->email,
            'message' => $validated['message'],
            'read' => false,
        ]);

        // Load relationships
        $mention->load('mentioningUser', 'project');

        // notfitication after user mentioned
        $mentionedUser->notify(new UserMentionedNotification($mention));

        // Broadcast the mention event
        event(new PusherBroadcast($mention));

        return response()->json([
            'mention' => $mention,
            'message' => 'Mention created successfully'
        ], 201);
    }

    /**
     * Mark a mention as read
     */
    public function markAsRead(Mention $mention)
    {
        // Ensure the authenticated user is the mentioned user
        if (auth()->id() !== $mention->mentioned_user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $mention->update(['read' => true]);

        // Optionally broadcast read event
        // event(new MentionRead($mention));

        return response()->json([
            'mention' => $mention,
            'message' => 'Mention marked as read'
        ]);
    }

    /**
     * Get all unread mentions for the authenticated user
     */
    public function getUnreadMentions()
    {
        $unreadMentions = Mention::where('mentioned_user_id', auth()->id())
            ->where('read', false)
            ->with(['mentioningUser:id,email,avatar,username', 'project:id'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'mentions' => $unreadMentions,
            'count' => $unreadMentions->count()
        ]);
    }

    /**
     * Get all mentions for the authenticated user (read and unread)
     */
    public function getAllMentions()
    {
        $mentions = Mention::where('mentioned_user_id', auth()->id())
            ->with(['mentioningUser:id,email,avatar,username', 'project:id'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($mentions);
    }

    /**
     * Mark all mentions as read for the authenticated user
     */
    public function markAllAsRead()
    {
        $updatedCount = Mention::where('mentioned_user_id', auth()->id())
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json([
            'message' => 'All mentions marked as read',
            'updated_count' => $updatedCount
        ]);
    }
}