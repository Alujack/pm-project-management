<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\SprintsController;
use Illuminate\Support\Facades\Route;

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
    });

    // Sprints api 
    Route::get('/sprints', [SprintsController::class, 'index']);
    Route::post('/sprints', [SprintsController::class, 'store']);
    Route::get('/sprints/{id}', [SprintsController::class, 'show']);
    Route::put('/sprints/{id}', [SprintsController::class, 'update']);
    Route::delete('/sprints/{id}', [SprintsController::class, 'destroy']);
    Route::get('/sprints/{id}/issues', [SprintsController::class, 'issues']);
    Route::post('/sprints/{id}/issues/{issueId}', [SprintsController::class, 'addIssue']);
    Route::delete('/sprints/{id}/issues/{issueId}', [SprintsController::class, 'removeIssue']);
});

