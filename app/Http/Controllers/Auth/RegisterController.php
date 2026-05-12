<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'company_name' => 'required|string|max:255',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Create team for user
        $team = Team::create([
            'user_id' => $user->id,
            'name' => $validated['company_name'],
            'slug' => str($validated['company_name'])->slug(),
            'subscription_plan' => 'free',
        ]);

        // Add user as team admin
        $user->teams()->attach($team->id, ['role' => 'admin']);

        // Create subscription
        $team->subscription()->create([
            'plan' => 'free',
            'status' => 'active',
        ]);

        // Login user
        auth()->login($user);

        return redirect('/dashboard');
    }
}