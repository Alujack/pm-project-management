<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\User;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class IssueController extends Controller
{
   

    protected array $updateRules = [
        'title' => 'sometimes|string|max:255',
        'description' => 'sometimes|string',
        'startDate' => 'sometimes|date',
        'endDate' => 'nullable|date|after_or_equal:startDate',
        'duration' => 'nullable|integer',
        'statusID' => 'sometimes|exists:statuses,id',
        'sprintID' => 'nullable|exists:sprints,id',
        'projectID' => 'sometimes|exists:projects,id',
        'priority' => 'sometimes|in:low,medium,high,critical',
    ];

    // Relationships to eager load
    protected array $withRelations = [
        'status',
        'sprint',
        'project',
        'creator',
        'assignee',
        'comments',
        'attachments'
    ];

    /**
     * Get all issues
     */
    public function index(): JsonResponse
    {
        $issues = Issue::with($this->withRelations)->get();
        return response()->json($issues);
    }

    /**
     * Create a new issue
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->only([
            'title',
            'description',
            'startDate',
            'endDate',
            'duration',
            'statusID',
            'sprintID',
            'projectID',
            'userID',
            'assigneeID',
            'assignerID',
            'priority',
        ]);
    
        $issue = DB::transaction(function () use ($data) {
            return Issue::create($data);
        });
    
        return response()->json($issue->load($this->withRelations), 201);
    }
    

    /**
     * Get specific issue
     */
    public function show(Issue $issue): JsonResponse
    {
        return response()->json($issue->load($this->withRelations));
    }

    /**
     * Update an issue
     */
    public function update(Request $request, Issue $issue): JsonResponse
    {
        $validated = $request->validate($this->updateRules);

        DB::transaction(function () use ($issue, $validated) {
            $issue->update($validated);
        });

        return response()->json($issue->load($this->withRelations));
    }

    /**
     * Delete an issue
     */
    public function destroy(Issue $issue): JsonResponse
    {
        DB::transaction(function () use ($issue) {
            $issue->delete();
        });

        return response()->json(null, 204);
    }

    /**
     * Assign issue to user
     */
    public function assign(Issue $issue, int $userId): JsonResponse
    {
        $user = User::findOrFail($userId);

        DB::transaction(function () use ($issue, $user) {
            $issue->update([
                'assigneeID' => $user->id,
                'assignerID' => auth()->id() ?? $issue->userID
            ]);
        });

        return response()->json($issue->load('assignee'));
    }

    /**
     * Update issue status
     */
    public function updateStatus(Issue $issue, int $statusId): JsonResponse
    {
        $status = Status::findOrFail($statusId);

        DB::transaction(function () use ($issue, $status) {
            $issue->update(['statusID' => $status->id]);
        });

        return response()->json($issue->load('status'));
    }

    /**
     * Get issue comments
     */
    public function comments(Issue $issue): JsonResponse
    {
        $comments = $issue->comments()->with('user')->get();
        return response()->json($comments);
    }

    /**
     * Get issues by project ID
     */
    public function getByProject(int $projectId): JsonResponse
    {
        $issues = Issue::with($this->withRelations)
            ->where('projectID', $projectId)
            ->get();

        return response()->json($issues);
    }
}