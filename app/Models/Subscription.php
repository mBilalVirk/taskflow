<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'stripe_subscription_id',
        'stripe_customer_id',
        'status',
        'plan',
        'amount',
        'current_period_start',
        'current_period_end',
        'trial_ends_at',
    ];

    protected $casts = [
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isTrialing()
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }
}