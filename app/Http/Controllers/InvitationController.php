<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Project;
use App\Notifications\ProjectInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

class InvitationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'project_id' => 'required|exists:projects,id',
        ]);

        // Check if an invitation already exists for this email & project
        $existingInvitation = Invitation::where('email', $request->email)
            ->where('project_id', $request->project_id)
            ->first();

        // get username from User model than add to invitation table
        $user = User::where('email', $request->email)->first();
        $username = $user ? $user->username : null;
        

        if ($existingInvitation) {
            // If invitation is already accepted
            if ($existingInvitation->accepted) {
                return response()->json([
                    'error' => 'This user has already accepted an invitation to this project.'
                ], 409); // HTTP 409 Conflict
            }

            // Update token and resend notification
            $existingInvitation->update([
                'token' => Str::random(60),
            ]);

            $project = Project::find($request->project_id);
            $existingInvitation->notify(new ProjectInvitation($existingInvitation, $project));

            return response()->json(['message' => 'Invitation updated and resent successfully'], 200);
        }

        // Create a new invitation if none exists
        // add user username to invitation table
        $invitation = Invitation::create([
            'email' => $request->email,
            'username' => $username,
            'project_id' => $request->project_id,
            'token' => Str::random(60),
            'accepted' => false,
        ]);

        $project = Project::find($request->project_id);
        $invitation->notify(new ProjectInvitation($invitation, $project));

        return response()->json(['message' => 'Invitation sent successfully'], 200);
    }

    public function verify($token)
    {
        $invitation = Invitation::where('token', $token)->first();

        if (!$invitation) {
            return redirect(config('app.frontend_url') . '/invalid-invitation');
        }

        // Mark as accepted
        $invitation->accepted = true;
        $invitation->save();

        // Add user to project team (assuming user exists)
        $user = User::where('email', $invitation->email)->first();

        if ($user) {
            // Add user to project team
            $project = Project::find($invitation->project_id);
            $project->members()->attach($user->id);
        }

        return response()->json([
            'success'=>true
        ],200);
    }

    // get all invitations for a user include field id, email
    public function getInvitationsForUser(Request $request)
    {
        $user = $request->user();

        // Get all invitations for the user
        $invitations = Invitation::where('email', $user->email)
            ->where('accepted', true)
            ->get(['id', 'email','username', 'project_id']);

        return response()->json($invitations);
    } 
}