<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentTeam
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {

            $teamId = $this->getTeamId($request);

            if ($teamId) {
                // Get the team with pivot data (important for teams)
                $team = auth()->user()
                    ->teams()
                    ->where('teams.id', $teamId)
                    ->first();

                if ($team) {
                    session(['current_team_id' => $team->id]);
                    app()->singleton('current_team', fn() => $team);
                } else {
                    // Optional: Clear session if user no longer has access
                    session()->forget('current_team_id');
                }
            }
        }

        return $next($request);
    }

    /**
     * Safely extract team ID whether it's passed as ID or bound model
     */
    private function getTeamId(Request $request): string|null
    {
        $team = $request->route('team');

        // Case 1: Route Model Binding → Team object
        if ($team instanceof \App\Models\Team) {
            return $team->id;
        }

        // Case 2: Raw UUID string
        if (is_string($team) || is_numeric($team)) {
            return (string) $team;
        }

        // Case 3: From session
        $sessionTeamId = session('current_team_id');
        if ($sessionTeamId) {
            return (string) $sessionTeamId;
        }

        // Case 4: User's first team as fallback
        return auth()->user()->teams()->first()?->id;
    }
}