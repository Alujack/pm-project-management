<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\User;
use App\Models\Status;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    // Get all issues
    public function index()
    {
        return Issue::with(['status', 'sprint', 'project', 'creator', 'assignee', 'comments'])
                   ->get();
    }

    // Create a new issue
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'startDate' => 'required|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'duration' => 'nullable|integer',
            'statusID' => 'required|exists:statuses,id',
            'sprintID' => 'nullable|exists:sprints,id',
            'projectID' => 'required|exists:projects,id',
            'userID' => 'required|exists:users,id',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        return Issue::create($validated);
    }

    // Get specific issue
    public function show(Issue $issue)
    {
        return $issue->load(['status', 'sprint', 'project', 'creator', 'assignee', 'comments', 'attachments']);
    }

    // Update an issue
    public function update(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'startDate' => 'sometimes|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'duration' => 'nullable|integer',
            'statusID' => 'sometimes|exists:statuses,id',
            'sprintID' => 'nullable|exists:sprints,id',
            'projectID' => 'sometimes|exists:projects,id',
            'priority' => 'sometimes|in:low,medium,high,critical',
        ]);

        $issue->update($validated);
        return $issue;
    }

    // Delete an issue
    public function destroy(Issue $issue)
    {
        $issue->delete();
        return response()->noContent();
    }

    // Assign issue to user
    public function assign(Issue $issue, $userId)
    {
        $user = User::findOrFail($userId);
        
        $issue->update([
            'assigneeID' => $user->id,
            'assignerID' => auth()->id() ?? $issue->userID // Use current user or issue creator
        ]);

        return $issue->load('assignee');
    }

    // Update issue status
    public function updateStatus(Issue $issue, $statusId)
    {
        $status = Status::findOrFail($statusId);
        
        $issue->update(['statusID' => $status->id]);
        
        return $issue->load('status');
    }

    // Get issue comments
    public function comments(Issue $issue)
    {
        return $issue->comments()->with('user')->get();
    }
}