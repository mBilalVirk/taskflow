<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

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