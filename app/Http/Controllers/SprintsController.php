<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Issue;
use Illuminate\Support\Facades\Validator;

class SprintsController extends Controller
{

    // use to get all sprint or get by projectID
    public function index(Request $request)
    {
        try {
            $projectID = $request->query('project_id');

            if ($projectID) {
                $sprints = Sprint::where('ownerID', $projectID)->get();
            } else {
                $sprints = Sprint::all();
            }

            return response()->json([
                'success' => true,
                'message' => 'Sprints retrieved successfully!',
                'data' => $sprints
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve sprints: ' . $e->getMessage()
            ], 500);
        }
    }

    // create sprint
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'startDate' => 'required|date',
                'endDate' => 'required|date|after_or_equal:startDate',
                'sprintGoal' => 'nullable|string',
                'ownerID' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $sprint = Sprint::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Sprint created successfully!',
                'data' => $sprint
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create sprint: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $sprint = Sprint::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Sprint retrieved successfully!',
                'data' => $sprint
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sprint not found'
            ], 404);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $sprint = Sprint::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'startDate' => 'sometimes|required|date',
                'endDate' => 'sometimes|required|date|after_or_equal:startDate',
                'sprintGoal' => 'nullable|string',
                'ownerID' => 'sometimes|required|exists:projects,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $sprint->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Sprint updated successfully!',
                'data' => $sprint
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sprint: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $sprint = Sprint::findOrFail($id);
            $sprint->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sprint deleted successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete sprint: ' . $e->getMessage()
            ], 500);
        }
    }


    public function issues($id)
    {
        try {
            $sprint = Sprint::findOrFail($id);
            $issues = $sprint->issues;

            return response()->json([
                'success' => true,
                'message' => 'Issues retrieved successfully!',
                'data' => $issues
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve issues: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addIssue($id, $issueId)
    {
        try {
            $sprint = Sprint::findOrFail($id);
            $issue = Issue::findOrFail($issueId);

            // Check if the issue already belongs to another sprint
            if ($issue->sprintID && $issue->sprintID != $id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Issue already belongs to another sprint'
                ], 400);
            }

            $issue->sprintID = $id;
            $issue->save();

            return response()->json([
                'success' => true,
                'message' => 'Issue added to sprint successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add issue to sprint: ' . $e->getMessage()
            ], 500);
        }
    }


    public function removeIssue($id, $issueId)
    {
        try {
            $sprint = Sprint::findOrFail($id);
            $issue = Issue::where('id', $issueId)
                        ->where('sprintID', $id)
                        ->firstOrFail();

            $issue->sprintID = null;
            $issue->save();

            return response()->json([
                'success' => true,
                'message' => 'Issue removed from sprint successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove issue from sprint: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSprintsByProject($projectID)
{
    try {
        $project = Project::with(['sprints.issues.assignee', 'sprints.issues.status'])->findOrFail($projectID);

        $sprints = $project->sprints->map(function ($sprint) {
            return [
                'id' => 'ssp' . $sprint->id,
                'name' => $sprint->name,
                'dateRange' => $sprint->startDate->format('d M') . ' - ' . $sprint->endDate->format('d M'),
                'issueCount' => $sprint->issues->count(),
                'sprintDetails' => $sprint->sprintGoal,
                'isComplete' => now()->gt($sprint->endDate),
                'issues' => $sprint->issues->map(function ($issue) {
                    return [
                        'id' => 'SCRUM-' . $issue->id,
                        'title' => $issue->title,
                        'status' => $issue->status ? strtoupper($issue->status->name) : 'TO DO',
                        'assignee' => $issue->assignee ? $issue->assignee->name : null,
                    ];
                }),
            ];
        });

        return response()->json([
            'projectName' => $project->name,
            'projectPath' => $project->name,
            'sprints' => $sprints,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve sprints by project: ' . $e->getMessage(),
        ], 500);
    }
}

}