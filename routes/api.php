<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MentionController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\SprintsController;
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


// Protected Routes (require authentication)
Route::middleware('auth:api')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'getUserInfo']);

    Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':admin,user')->group(function () {
        // Route::get('/projects', [ProjectsController::class, 'index']);
        // Route::post('/projects', [ProjectsController::class, 'store']);
        // Route::get('/projects/{id}', [ProjectsController::class, 'show']);
        // Route::put('/projects/{id}', [ProjectsController::class, 'update']);
        // Route::delete('/projects/{id}', [ProjectsController::class, 'destroy']);

        // Project relationships
        Route::get('/projects/{id}/issues', [ProjectsController::class, 'getProjectIssues']);
        Route::get('/projects/{id}/sprints', [ProjectsController::class, 'getProjectSprints']);
        Route::get('/projects/{id}/members', [ProjectsController::class, 'getProjectMembers']);
        Route::post('/projects/{id}/members', [ProjectsController::class, 'addProjectMember']);
        Route::delete('/projects/{id}/members/{userId}', [ProjectsController::class, 'removeProjectMember']);

        // notofication and @mention
        // User search for mentions
        Route::get('/users/search', [UserController::class, 'search']);

        // Mentions
        Route::post('/mentions', [MentionController::class, 'store']);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
        // routes/api.php - Add this route
        Route::get('/content/render-mentions', [ContentController::class, 'renderMentions']);

        // api dashboard
        Route::get('/dashboard/summary', [DashboardController::class, 'dashboardSummary']);
        Route::get('/dashboard/recent-activity', [DashboardController::class, 'dashboardRecentActivity']);
        Route::get('/dashboard/upcomming-deadlines', [DashboardController::class, 'dashboardUpcomingDeadlines']);
    });

    // Sprints api 
 
});

// status
Route::apiResource('statuses', StatusController::class);

// issue
Route::apiResource('issues', IssueController::class);

// Additional custom routes
Route::post('/issues/{issue}/assign/{user}', [IssueController::class, 'assign']);
Route::post('/issues/{issue}/status/{status}', [IssueController::class, 'updateStatus']);
Route::get('/issues/{issue}/comments', [IssueController::class, 'comments']);

// sprints 
Route::get('/sprints', [SprintsController::class, 'index']);
Route::post('/sprints', [SprintsController::class, 'store']);
Route::get('/sprints/{id}', [SprintsController::class, 'show']);
Route::put('/sprints/{id}', [SprintsController::class, 'update']);
Route::delete('/sprints/{id}', [SprintsController::class, 'destroy']);
Route::get('/sprints/{id}/issues', [SprintsController::class, 'issues']);
Route::post('/sprints/{id}/issues/{issueId}', [SprintsController::class, 'addIssue']);
Route::delete('/sprints/{id}/issues/{issueId}', [SprintsController::class, 'removeIssue']);

//projects

 Route::get('/projects', [ProjectsController::class, 'index']);
        Route::post('/projects', [ProjectsController::class, 'store']);
        Route::get('/projects/{id}', [ProjectsController::class, 'show']);
        Route::put('/projects/{id}', [ProjectsController::class, 'update']);
        Route::delete('/projects/{id}', [ProjectsController::class, 'destroy']);



//comment
Route::apiResource('/comments', CommentController::class);

// search 

Route::get('/search/issues', [SearchController::class, 'searchIssues']);
Route::get('/search/projects', [SearchController::class, 'searchProjects']);
Route::get('/search/users', [SearchController::class, 'searchUsers']);



