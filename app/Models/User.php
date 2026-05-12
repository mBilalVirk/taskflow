<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'country',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    // Relationships
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function createdTeams()
    {
        return $this->hasMany(Team::class);
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function taskComments()
    {
        return $this->hasMany(TaskComment::class);
    }

    // Helper methods
    public function currentTeam()
    {
        return $this->teams()->find(session('current_team_id')) 
            ?? $this->teams()->first();
    }

    public function hasTeamAccess(Team $team)
    {
        return $this->teams()->where('teams.id', $team->id)->exists();
    }

    public function isTeamAdmin(Team $team)
    {
        return $this->teams()
            ->where('teams.id', $team->id)
            ->wherePivot('role', 'admin')
            ->exists();
    }
}
