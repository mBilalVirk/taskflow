<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'team_id',
        'created_by',
        'name',
        'slug',
        'description',
        'color',
        'visibility',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function tasksByStatus($status = null)
    {
        $query = $this->tasks();
        
        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('order_in_status');
    }



    // notification 
    public function created_by_user()
{
    return $this->belongsTo(User::class, 'created_by');
}
}