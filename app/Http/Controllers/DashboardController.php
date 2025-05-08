<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sprint;
use App\Models\Issue;
use App\Models\Project;
use Psy\Command\WhereamiCommand;
use App\Models\Status;

class DashboardController extends Controller
{
    public function dashboardSummary()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'user not found',
                'success' => false
            ], 404);
        }

        $userID = $user->id;

        // Get projects owned by user
        $projects = Project::where('ownerID', $userID)->get();
        $countProjects = $projects->count();

        // Get project IDs for the user
        $projectIDs = $projects->pluck('id');

        // Get issues related to user's projects
        $issues = Issue::whereIn('projectID', $projectIDs)->get();
        $countIssues = $issues->count();

        $summaryData = [
            'allProjects' => $projects,
            'projectCount' => $countProjects,
            'allIssues' => $issues,
            'issuesCount' => $countIssues,
        ];

        return response()->json([
            'message' => 'Fetched successfully',
            'success' => true,
            'summaryData' => $summaryData
        ], 200);
    }

    public function dashboardRecentActivity()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'success' => false
            ], 404);
        }

        $userID = Auth::id();
        $projects = Project::where('ownerID', $userID)->pluck('id');

        $recentActivity = Issue::with(['status', 'project'])->whereIn('projectID', $projects)->where('updated_at', '>=', Carbon::now()->subDays(30))->orderBy('updated_at', 'desc')->limit(10)->get(['id', 'statusID', 'projectID', 'updated_at']);

        return response()->json([
            'message' => 'successfully',
            'success' => true,
            'data' => $recentActivity
        ], 201);


    }

    protected function combineDeadlines($sprintDeadlines, $issueDeadlines)
    {
        $combined = collect();

        // Add sprint deadlines
        foreach ($sprintDeadlines as $sprint) {
            $combined->push([
                'type' => 'sprint',
                'id' => $sprint->id,
                'name' => $sprint->name,
                'endDate' => $sprint->endDate,
                'goal' => $sprint->sprintGoal,
                'open_issues_count' => $sprint->issues->count(),
                'related_to' => null
            ]);
        }

        // Add issue deadlines
        foreach ($issueDeadlines as $issue) {
            $combined->push([
                'type' => 'issue',
                'id' => $issue->id,
                'title' => $issue->title,
                'endDate' => $issue->endDate,
                'priority' => $issue->priority,
                'status' => $issue->status->name,
                'assignee' => $issue->assignee ? $issue->assignee->name : null,
                'related_to' => [
                    'sprint' => $issue->sprint ? $issue->sprint->name : null,
                    'project' => $issue->project ? $issue->project->name : null
                ]
            ]);
        }

        // Sort all deadlines by date
        return $combined->sortBy('endDate')->values();
    }

    public function dashboardUpcomingDeadlines()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'success' => false
            ], 404);
        }

        $userID = Auth::id();

        // Get active sprints owned by the user with upcoming deadlines
        $sprintDeadlines = Sprint::with([
            'owner',
            'issues' => function ($query) {
                $query->whereIn('statusID', Status::whereIn('name', ['Open', 'In Progress'])->pluck('id'));
            }
        ])
            ->where('ownerID', $userID)
            ->whereNotNull('endDate')
            ->where('endDate', '>=', Carbon::now())
            ->where('endDate', '<=', Carbon::now()->addDays(7))
            ->orderBy('endDate', 'asc')
            ->get(['id', 'name', 'endDate', 'sprintGoal']);

        // Get issues with upcoming deadlines from user's projects
        $projects = Project::where('ownerID', $userID)->pluck('id');

        $issueDeadlines = Issue::with(['status', 'assignee', 'sprint', 'project'])
            ->whereIn('projectID', $projects)
            ->whereNotNull('endDate')
            ->where('endDate', '>=', Carbon::now())
            ->where('endDate', '<=', Carbon::now()->addDays(7))
            ->whereIn('statusID', Status::whereIn('name', ['Open', 'In Progress'])->pluck('id'))
            ->orderBy('endDate', 'asc')
            ->get(['id', 'title', 'endDate', 'priority', 'statusID', 'assigneeID', 'sprintID', 'projectID']);

        return response()->json([
            'message' => 'Upcoming deadlines fetched successfully',
            'success' => true,
            'data' => [
                'sprint_deadlines' => $sprintDeadlines,
                'issue_deadlines' => $issueDeadlines,
                'combined_deadlines' => $this->combineDeadlines($sprintDeadlines, $issueDeadlines)
            ]
        ], 200);
    }

}
