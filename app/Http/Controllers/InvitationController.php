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

        // Create invitation with unique token
        $invitation = Invitation::create([
            'email' => $request->email,
            'project_id' => $request->project_id,
            'token' => Str::random(60),
            'accepted' => false,
        ]);

        // Send notification with the token
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

        // Redirect to frontend with success parameter
        return redirect(config('app.frontend_url') . '/projects/' . $invitation->project_id . '?invitation=accepted');
    }
}