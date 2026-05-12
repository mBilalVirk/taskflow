<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class TeamController extends Controller
{
    use AuthorizesRequests;
    

    // ===== TEAM DASHBOARD =====
    /**
     * Show current team dashboard
     */
    public function dashboard()
    {
        $team = auth()->user()->currentTeam();
        
        if (!$team) {
            return redirect('/create-team');
        }

        $members = $team->members()->count();
        $projects = $team->projects()->count();
        $tasks = $team->projects()
            ->withCount('tasks')
            ->get()
            ->sum('tasks_count');

        return view('team.dashboard', compact('team', 'members', 'projects', 'tasks'));
    }

    // ===== TEAM SETTINGS =====
    /**
     * Show team settings page
     */
    public function settings(Team $team)
    {
        $this->authorize('view', $team);

        return view('team.settings', compact('team'));
    }

    /**
     * Update team settings
     */
    public function updateSettings(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($team->logo) {
                \Storage::disk('public')->delete($team->logo);
            }
            $validated['logo'] = $request->file('logo')->store('team-logos', 'public');
        }

        $team->update($validated);

        return back()->with('success', 'Team settings updated successfully!');
    }

    // ===== TEAM MEMBERS =====
    /**
     * Show team members list
     */
    public function members(Team $team)
    {
        $this->authorize('view', $team);

        $members = $team->members()->paginate(15);
        
        return view('team.members.index', compact('team', 'members'));
    }

    /**
     * Show invite member form
     */
    public function inviteForm(Team $team)
    {
        $this->authorize('manage-members', $team);

        return view('team.members.invite', compact('team'));
    }

    /**
     * Invite new member to team
     */
    public function inviteMember(Request $request, Team $team)
    {
        $this->authorize('manage-members', $team);

        $validated = $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:member,manager,admin',
        ]);

        // Check member limit
        if (!$team->canAddMembers()) {
            return back()->with('error', 'You have reached the member limit for your plan. Please upgrade.');
        }

        // Find user by email
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return back()->with('error', 'User not found. Ask them to sign up first.');
        }

        // Check if already member
        if ($team->members()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User is already a member of this team.');
        }

        // Add member
        $team->members()->attach($user->id, ['role' => $validated['role']]);

        // TODO: Send email notification to user

        return back()->with('success', 'Member invited successfully!');
    }

    /**
     * Update member role
     */
    public function updateMemberRole(Request $request, Team $team, User $user)
    {
        $this->authorize('manage-members', $team);

        // Prevent removing owner
        if ($team->user_id === $user->id) {
            return back()->with('error', 'Cannot change team owner role.');
        }

        $validated = $request->validate([
            'role' => 'required|in:member,manager,admin',
        ]);

        $team->members()
            ->where('user_id', $user->id)
            ->update(['role' => $validated['role']]);

        return back()->with('success', 'Member role updated.');
    }

    /**
     * Remove member from team
     */
    public function removeMember(Request $request, Team $team, User $user)
    {
        $this->authorize('manage-members', $team);

        // Prevent removing owner
        if ($team->user_id === $user->id) {
            return back()->with('error', 'Cannot remove team owner.');
        }

        // Remove all tasks assigned to this user
        $team->projects()
            ->with('tasks')
            ->get()
            ->each(function ($project) use ($user) {
                $project->tasks()
                    ->where('assigned_to', $user->id)
                    ->update(['assigned_to' => null]);
            });

        $team->members()->detach($user->id);

        return back()->with('success', 'Member removed from team.');
    }

    // ===== TEAM CREATION =====
    /**
     * Show create team form
     */
    public function createForm()
    {
        return view('team.create');
    }

    /**
     * Create new team
     */
    public function store(Request $request)
    {
        // Check how many teams user already owns
        $ownedTeams = auth()->user()->createdTeams()->count();
        
        if ($ownedTeams >= 5) {
            return back()->with('error', 'You can only create 5 teams. Please upgrade or delete an existing team.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Create team
        $team = Team::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'slug' => str($validated['name'])->slug() . '-' . uniqid(),
            'description' => $validated['description'] ?? null,
            'subscription_plan' => 'free',
            'members_limit' => 5, // Free plan limit
        ]);

        // Add creator as admin member
        $team->members()->attach(auth()->id(), ['role' => 'admin']);

        // Create subscription
        $team->subscription()->create([
            'plan' => 'free',
            'status' => 'active',
        ]);

        return redirect("/team/{$team->id}/dashboard")->with('success', 'Team created successfully!');
    }

    // ===== TEAM SWITCHING =====
    /**
     * Switch to different team
     */
    public function switchTeam(Request $request, Team $team)
    {
        $this->authorize('view', $team);

        session(['current_team_id' => $team->id]);

        return redirect("/team/{$team->id}/dashboard");
    }

    /**
     * List all user's teams
     */
    public function listTeams()
    {
        $teams = auth()->user()->teams()->get();
        $ownedTeams = auth()->user()->createdTeams()->get();

        return view('team.list', compact('teams', 'ownedTeams'));
    }

    // ===== TEAM DELETION =====
    /**
     * Delete team (only owner)
     */
    public function destroy(Request $request, Team $team)
    {
        $this->authorize('delete', $team);

        // Verify password
        $validated = $request->validate([
            'password' => 'required|current_password',
        ]);

        $team->delete();

        return redirect('/teams')->with('success', 'Team deleted successfully.');
    }
}