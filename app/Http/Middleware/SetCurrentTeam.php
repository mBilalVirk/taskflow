<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetCurrentTeam
{
    public function handle(Request $request, Closure $next)
    {
        // For authenticated users, set current team
        if (auth()->check()) {
            $teamId = $request->route('team') 
                ?? session('current_team_id') 
                ?? auth()->user()->teams()->first()?->id;

            if ($teamId) {
                // Verify user has access to this team
                $team = auth()->user()
                    ->teams()
                    ->where('teams.id', $teamId)
                    ->first();

                if ($team) {
                    session(['current_team_id' => $team->id]);
                    app()->singleton('current_team', fn() => $team);
                }
            }
        }

        return $next($request);
    }
}