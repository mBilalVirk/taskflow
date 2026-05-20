<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;


// BROADCASTING AUTHENTICATION - ADD THIS FIRST!
// Broadcast::routes(['middleware' => ['auth:sanctum']]);
// Public routes
Route::get('/', function () {
    return view('welcome');
});

// ✅ SHOW FORMS (GET routes)
Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');

Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');


Route::post('/register', [RegisterController::class, 'store'])->name('register');
Route::post('/login', [LoginController::class, 'store'])->name('login');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile Settings Route
    Route::get('/user/profile', function () {
        return view('profile.index');
    })->name('profile.show');
     Route::get('/user/password', function () {
        return view('profile.password');
    })->name('profile.password');
});

// ===== TEAM ROUTES =====
Route::middleware('auth')->prefix('team')->name('team.')->group(function () {
    // Team management
    Route::get('/{team}/dashboard', [TeamController::class, 'dashboard'])->name('dashboard');
    Route::get('/{team}/settings', [TeamController::class, 'settings'])->name('settings');
    Route::put('/{team}/settings', [TeamController::class, 'updateSettings'])->name('update-settings');
    
    // Team members
    Route::get('/{team}/members', [TeamController::class, 'members'])->name('members');
    Route::get('/{team}/invite', [TeamController::class, 'inviteForm'])->name('invite-form');
    Route::post('/{team}/invite', [TeamController::class, 'inviteMember'])->name('invite-member');
    Route::post('/{team}/members/{user}/role', [TeamController::class, 'updateMemberRole'])->name('update-member-role');
    Route::delete('/{team}/members/{user}', [TeamController::class, 'removeMember'])->name('remove-member');
    
    // Team CRUD
    Route::get('/create', [TeamController::class, 'createForm'])->name('create-form');
    Route::post('/', [TeamController::class, 'store'])->name('store');
    Route::post('/{team}/switch', [TeamController::class, 'switchTeam'])->name('switch');
    Route::delete('/{team}', [TeamController::class, 'destroy'])->name('destroy');
    
    // List teams
    Route::get('/', [TeamController::class, 'listTeams'])->name('list');
});

// ===== PROJECT ROUTES =====
Route::middleware('auth')->prefix('team/{team}/projects')->name('projects.')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/create', [ProjectController::class, 'createForm'])->name('create');
    Route::post('/', [ProjectController::class, 'store'])->name('store');
    Route::get('{project}', [ProjectController::class, 'show'])->name('show');
    Route::get('{project}/edit', [ProjectController::class, 'editForm'])->name('edit');
    Route::put('{project}', [ProjectController::class, 'update'])->name('update');
    Route::delete('{project}', [ProjectController::class, 'destroy'])->name('destroy');
});

// Task routes
Route::middleware('auth')->prefix('team/{team}/projects/{project}/tasks')
    ->name('tasks.')->group(function () {
    
    Route::get('/', [TaskController::class, 'index'])->name('index');
    Route::get('/create', [TaskController::class, 'createForm'])->name('create');
    Route::post('/', [TaskController::class, 'store'])->name('store');
    Route::get('{task}', [TaskController::class, 'show'])->name('show');
    Route::get('{task}/edit', [TaskController::class, 'editForm'])->name('edit');
    Route::put('{task}', [TaskController::class, 'update'])->name('update');
    Route::put('{task}/status', [TaskController::class, 'updateStatus'])->name('update-status');
    Route::delete('{task}', [TaskController::class, 'destroy'])->name('destroy');
    Route::get('{task}/modal', [TaskController::class, 'getModal'])->name('modal');
});

// Task comments routes
Route::middleware('auth')->prefix('team/{team}/projects/{project}/tasks/{task}/comments')
    ->name('comments.')->group(function () {
    
    Route::post('/', [TaskCommentController::class, 'store'])->name('store');
    Route::delete('{comment}', [TaskCommentController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/count', [NotificationController::class, 'count'])->name('count');
    Route::get('/recent', [NotificationController::class, 'recent'])->name('recent');
    Route::put('{notification}/read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::delete('{notification}', [NotificationController::class, 'destroy'])->name('destroy');
});
// Analytics routes - Using Livewire components
Route::middleware('auth')->prefix('team/{team}')->group(function () {
    Route::get('/analytics', function (Team $team) {
        return view('analytics.dashboard', ['team' => $team]);
    })->name('analytics.team');
    
    Route::prefix('projects/{project}')->group(function () {
        Route::get('/analytics', function (Team $team, Project $project) {
            return view('analytics.project', ['team' => $team, 'project' => $project]);
        })->name('analytics.project');
    });
});