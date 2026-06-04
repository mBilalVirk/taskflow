<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{
    AuthController,
    TeamController,
    ProjectController,
    TaskController,
    UserController,
    WebhookController,
};

Route::prefix('v1')->group(function () {
    // Public routes (no auth required)
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Protected routes (API token required)
    Route::middleware('api-token')->group(function () {
        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/tokens', [AuthController::class, 'createToken']);
        Route::get('/auth/tokens', [AuthController::class, 'listTokens']);
        Route::delete('/auth/tokens/{token}', [AuthController::class, 'deleteToken']);

        // Teams
        //Route::apiResource('teams', TeamController::class);

        // Projects
        //Route::apiResource('projects', ProjectController::class);

        // Tasks
        //Route::apiResource('tasks', TaskController::class);
        Route::post('tasks/{task}/assign', [TaskController::class, 'assign']);
        Route::post('tasks/{task}/complete', [TaskController::class, 'complete']);
        Route::post('tasks/{task}/comment', [TaskController::class, 'addComment']);

        // Users
        Route::get('users/me', [UserController::class, 'me']);
        Route::get('teams/{team}/members', [UserController::class, 'teamMembers']);

        // Webhooks
        Route::get('webhooks', [WebhookController::class, 'index']);
        Route::post('webhooks', [WebhookController::class, 'store']);
        Route::delete('webhooks/{webhook}', [WebhookController::class, 'destroy']);
    });

    // Webhook endpoints (public, with signature verification)
    Route::post('/webhooks/github', [WebhookController::class, 'handleGithub']);
    Route::post('/webhooks/slack', [WebhookController::class, 'handleSlack']);
});