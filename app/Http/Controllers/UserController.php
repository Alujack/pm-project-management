<?php

namespace App\Http\Controllers;

use App\Models\User;
use ErrorException;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Invitation;

use function Laravel\Prompts\error;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ]);

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            if (!$user) {
                return response()->json('error', status: 300);
            }

            return $user;

        } catch (ErrorException $error) {

            return response()->json($error, status: 500);

        }


    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->only(['name', 'email']));
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    // for user mentions
    /**
     * Get users that can be mentioned in a specific project
     */
    public function getUsersForMention(Request $request, $projectId)
    {
        $search = $request->query('search', '');

        // Get the project
        $project = Project::findOrFail($projectId);


        // Get invited users who have accepted the invitation
        $users = Invitation::where('project_id', $projectId)
            ->where('accepted', true)
            ->where(function ($query) use ($search) {
                $query->where('email', 'like', "%{$search}%");
            })
            ->select('email', 'username','user_id', 'project_id', 'accepted')
            ->get()
            ->map(function ($invitation) {
                return [
                    'id' => $invitation->user_id, // or use a different identifier
                    'name' => $invitation->username,
                    'email' => $invitation->email
                ];
            });

        return response()->json($users);
    }

    /**
     * Get invited users that could be added to a project
     * make it search projectId in invitations table with field project_id if match select that row from invitations table
     */
    public function getInvitedUsers($projectId)
    {

        $inviatedUsers = Invitation::where('project_id', $projectId)
            ->where('accepted', false)
            ->select('email', 'username', 'project_id', 'accepted')
            ->get();

        return response()->json($inviatedUsers);
    }
}
