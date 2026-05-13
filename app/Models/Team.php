<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'logo',
        'description',
        'subscription_plan',
        'subscription_status',
        'members_limit',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // Helper methods
    public function memberCount()
    {
        return $this->members()->count();
    }

    public function canAddMembers()
    {
        return $this->memberCount() < $this->members_limit;
    }

    public function taskCount()
    {
        return $this->projects()
            ->withCount('tasks')
            ->get()
            ->sum('tasks_count');
    }


    
    
}