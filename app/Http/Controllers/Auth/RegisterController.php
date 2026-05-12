<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],

                'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],

                'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],

                'company_name' => ['required', 'string', 'max:255'],
            ],
            [
                // 🔥 Custom Messages

                'name.required' => 'Your name is required.',
                'name.max' => 'Name cannot exceed 255 characters.',

                'email.required' => 'Email is required.',
                'email.email' => 'Enter a valid email address.',
                'email.unique' => 'This email is already registered.',
                'email.max' => 'Email is too long.',

                'password.required' => 'Password is required.',
                'password.confirmed' => 'Passwords do not match.',
                'password.min' => 'Password must be at least 8 characters.',

                'company_name.required' => 'Company name is required.',
                'company_name.max' => 'Company name is too long.',
            ],
        );

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
