<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // Search for issues
    public function searchIssues(Request $request)
    {
        $query = Issue::query();

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('projectID')) {
            $query->where('projectID', $request->projectID);
        }

        if ($request->has('assigneeID')) {
            $query->where('assigneeID', $request->assigneeID);
        }

        if ($request->has('assignerID')) {
            $query->where('assignerID', $request->assignerID);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('statusID')) {
            $query->where('statusID', $request->statusID);
        }

        return response()->json($query->with(['project', 'assignee', 'assigner', 'status'])->get());
    }

    // Search for projects
    public function searchProjects(Request $request)
    {
        $query = Project::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('key')) {
            $query->where('key', 'like', '%' . $request->key . '%');
        }

        if ($request->has('ownerID')) {
            $query->where('ownerID', $request->ownerID);
        }

        if ($request->has('accessibility')) {
            $query->where('accessibility', $request->accessibility);
        }

        return response()->json($query->with('owner')->get());
    }

    // Search for users
    public function searchUsers(Request $request)
    {
        $query = User::query();

        if ($request->has('username')) {
            $query->where('username', 'like', '%' . $request->username . '%');
        }

        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->has('systemRole')) {
            $query->where('systemRole', $request->systemRole);
        }

        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }

        return response()->json($query->get());
    }
}
