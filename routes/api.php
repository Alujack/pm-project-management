<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\MentionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\SprintsController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;

// Auth Routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);
Route::post('/auth/password/email', [AuthController::class, 'sendPasswordResetEmail']);
Route::post('/auth/password/reset', [AuthController::class, 'resetPassword']);
Route::post('/auth/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail']);
Route::post('/auth/email/resend', [AuthController::class, 'resendVerificationEmail']);
Route::post('/api/reset-password', [AuthController::class, 'resetPassword']);

// social login
Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);



// pusher mension api websucket
// User routes for mentions
Route::get('/projects/{projectId}/users-for-mention', [UserController::class, 'getUsersForMention']);
Route::get('/projects/{projectId}/invited-users', [UserController::class, 'getInvitedUsers']);
Route::get('/all-users', [UserController::class, 'index']);

// Mention routes
Route::post('/mentions', [MentionController::class, 'store']);
Route::patch('/mentions/{mention}/read', [MentionController::class, 'markAsRead']);
Route::get('/mentions/unread', [MentionController::class, 'getUnreadMentions']);


// Protected Routes (require authentication)
Route::middleware('auth:api')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'getUserInfo']);

    Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':admin,user')->group(function () {
        Route::get('/projects', [ProjectsController::class, 'index']);
        Route::post('/projects', [ProjectsController::class, 'store']);
        Route::get('/projects/{id}', [ProjectsController::class, 'show']);
        Route::put('/projects/{id}', [ProjectsController::class, 'update']);
        Route::delete('/projects/{id}', [ProjectsController::class, 'destroy']);

        // Project relationships
        Route::get('/projects/{id}/issues', [ProjectsController::class, 'getProjectIssues']);
        Route::get('/projects/{id}/sprints', [ProjectsController::class, 'getProjectSprints']);
        Route::get('/projects/{id}/members', [ProjectsController::class, 'getProjectMembers']);
        Route::post('/projects/{id}/members', [ProjectsController::class, 'addProjectMember']);
        Route::delete('/projects/{id}/members/{userId}', [ProjectsController::class, 'removeProjectMember']);
        Route::get('/user/projects', [ProjectsController::class, 'getUserProjects']);
        Route::get('/projects/{projectId}/full', [ProjectsController::class, 'showWithRelations']);

        // api dashboard
        Route::get('/dashboard/summary', [DashboardController::class, 'dashboardSummary']);
        Route::get('/dashboard/recent-activity', [DashboardController::class, 'dashboardRecentActivity']);
        Route::get('/dashboard/upcomming-deadlines', [DashboardController::class, 'dashboardUpcomingDeadlines']);

        // user profile
        Route::get('/users/{user}', [ProfileController::class, 'show']); // Add this route for GET
        Route::patch('/users/{user}', [ProfileController::class, 'updateProfile']); // Add this route for PATCH
        Route::post('/users/{user}/avatar', [ProfileController::class, 'updateAvatar']); // Changed from patch to post
        Route::post('/users/{user}/cover-photo', [ProfileController::class, 'updateCoverPhoto']); // Changed from patch to post
        Route::patch('/users/{user}/bio', [ProfileController::class, 'updateBio']);
    });




    // Sprints api 
    // Route::get('/sprints', [SprintsController::class, 'index']);
    // Route::post('/sprints', [SprintsController::class, 'store']);
    // Route::get('/sprints/{id}', [SprintsController::class, 'show']);
    // Route::put('/sprints/{id}', [SprintsController::class, 'update']);
    // Route::delete('/sprints/{id}', [SprintsController::class, 'destroy']);
    // Route::get('/sprints/{id}/issues', [SprintsController::class, 'issues']);
    // Route::post('/sprints/{id}/issues/{issueId}', [SprintsController::class, 'addIssue']);
    // Route::delete('/sprints/{id}/issues/{issueId}', [SprintsController::class, 'removeIssue']);
    // Route::get('/projects/{projectID}/sprints', [SprintsController::class, 'getSprintsByProject']);
});
Route::get('/sprints', [SprintsController::class, 'index']);
Route::post('/sprints', [SprintsController::class, 'store']);
Route::get('/sprints/{id}', [SprintsController::class, 'show']);
Route::put('/sprints/{id}', [SprintsController::class, 'update']);
Route::delete('/sprints/{id}', [SprintsController::class, 'destroy']);
Route::get('/sprints/{id}/issues', [SprintsController::class, 'issues']);
Route::post('/sprints/{id}/issues/{issueId}', [SprintsController::class, 'addIssue']);
Route::delete('/sprints/{id}/issues/{issueId}', [SprintsController::class, 'removeIssue']);
Route::get('/sprints/project/{projectID}', [SprintsController::class, 'getSprintsByProject']);

// status
Route::apiResource('statuses', StatusController::class);

// issue
Route::apiResource('issues', IssueController::class);
Route::get('/projects/{projectId}/issues', [IssueController::class, 'getByProject']);


//notification
Route::post('/invitations', [InvitationController::class, 'store']);
Route::get('/invitations/verify/{token}', [InvitationController::class, 'verify']);
// getInvitationsForUser
Route::get('/invitations', [InvitationController::class, 'getInvitationsForUser']);


// file attachment
Route::post('/attachments', [AttachmentController::class, 'store']);
Route::get('/attachments/{attachment}', [AttachmentController::class, 'show'])
    ->name('attachments.show');
Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy']);
Route::get('/projects/{project}/attachments', [AttachmentController::class, 'index']);