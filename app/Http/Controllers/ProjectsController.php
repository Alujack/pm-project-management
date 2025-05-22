<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Sprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Issue;

class ProjectsController extends Controller
{
    // Basic CRUD operations
    public function index(Request $request)
    {
        $projects = Project::select('id', 'name')->get();

        return response()->json([
            'success' => true,
            'message' => 'data retrieve successfully!',
            'data' => $projects
        ], 200);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:projects,key',
            'accessibility' => 'required|string',
            'teamID' => 'integer',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'key' => $validated['key'],
            'accessibility' => $validated['accessibility'],
            'teamID' => $validated['teamID'] ?? null,
            'ownerID' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }      // POST /api/projects

    public function show(Request $request, $id)
    {
        $project = Project::find($id);

        // check if the data exist or not 
        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Data does not exist'
            ], 404);
        }

        // after checking everything successfully then send back to frontend with json data
        return response()->json([
            'success' => true,
            'message' => 'Data retrieve successfully!',
            'data' => $project
        ], 200);
    }    // GET /api/projects/{id}

    public function update(Request $request, $id)
    {
        // get the data from frontend and validate
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'accessibility' => 'required|string'
        ]);

        // find the project
        $project = Project::find($id);

        // check whether the data exist or not
        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'the data does not exist'
            ], 404);
        }

        // update the data in the database
        $project->name = $validated['name'];
        $project->accessibility = $validated['accessibility'];
        $project->save();

        // now response back to the frontend 
        return response()->json([
            'success' => true,
            'message' => 'the data being updated successfully',
            'data' => $project
        ], 200);
    }  // PUT /api/projects/{id}

    public function destroy($id)
    {
        // find the data in database
        $project = Project::find($id);

        // check whether it exist or not
        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'the data does not exist'
            ], 404);
        }

        // delete the data from database
        $project->delete();

        // return the response to frontend 
        return response()->json([
            'success' => true,
            'message' => 'data deleted successfully'
        ], 200);
    } // DELETE /api/projects/{id}

    // Relationship methods
    public function getProjectIssues($projectId)
    {
        // Validate project exists
        $project = Project::find($projectId);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        // Find the issues
        $issues = Issue::where('projectID', $projectId)->get();

        // Check whether issues exist
        if ($issues->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No issues found for this project'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $issues
        ], 200);
    }       // GET /api/projects/{id}/issues

    public function getProjectSprints($projectId)
    {
        // Validate project exists
        $project = Project::find($projectId);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        // Get sprints related to issues in this project
        $sprints = Sprint::whereHas('issues', function ($query) use ($projectId) {
            $query->where('projectID', $projectId);
        })->get();

        if ($sprints->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No sprints found for this project'
            ], 404);
        }

        // Return the sprints
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $sprints
        ], 200);
    }      // GET /api/projects/{id}/sprints

    public function getProjectMembers($projectId)
    {
        // Validate project exists
        $project = Project::find($projectId);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        $projectMembers = Member::where('projectID', $projectId)->get();

        // Optional: check if no members found
        if ($projectMembers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No members found for this project'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved project members',
            'data' => $projectMembers
        ], 200);
    }       // GET /api/projects/{id}/members

    public function addProjectMember(Request $request, $projectId)
    {
        // Validate project exists
        $project = Project::find($projectId);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        $validated = $request->validate([
            'userID' => 'required|integer|exists:users,id',
            'role' => 'required|string'
        ]);

        // Check if member already exists
        $existingMember = Member::where('projectID', $projectId)
            ->where('userID', $validated['userID'])
            ->first();

        if ($existingMember) {
            return response()->json([
                'success' => false,
                'message' => 'User is already a member of this project'
            ], 409);
        }

        // Create new member
        $member = Member::create([
            'userID' => $validated['userID'],
            'projectID' => $projectId,
            'role' => $validated['role']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Member added successfully',
            'data' => $member
        ], 201);
    }       // POST /api/projects/{id}/members

    public function removeProjectMember($projectId, $userId)
    {
        // Validate project exists
        $project = Project::find($projectId);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        // Find the member
        $member = Member::where('projectID', $projectId)
            ->where('userID', $userId)
            ->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found in this project'
            ], 404);
        }

        // Delete the member
        $member->delete();

        return response()->json([
            'success' => true,
            'message' => 'Member removed successfully'
        ], 200);
    } // DELETE /api/projects/{id}/members/{userId}

    public function getUserProjects(Request $request)
    {
        // Get the authenticated user's ID and email
        $userId = Auth::id();
        $userEmail = Auth::user()->email;

        // Retrieve projects where user is the owner
        $ownedProjects = Project::where('ownerID', $userId)->get();

        // Retrieve projects where user has accepted invitations
        $invitedProjects = Project::whereHas('invitations', function ($query) use ($userEmail) {
            $query->where('email', $userEmail)
                ->where('accepted', true);
        })->get();

        // Merge both collections
        $allProjects = $ownedProjects->merge($invitedProjects)->unique('id');

        // Check if the user has any projects
        if ($allProjects->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No projects found for the user'
            ], 200);
        }

        // Return the projects
        return response()->json([
            'success' => true,
            'message' => 'Projects retrieved successfully',
            'data' => $allProjects
        ], 200);
    }

    public function showWithRelations($projectId)
    {
        $project = Project::with([
            'owner',
            'issues.status',
            'issues.sprint',
            'issues.user',
            'issues.assignee',
            'issues.assigner',
        ])->find($projectId);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        return response()->json($project);
    }
}