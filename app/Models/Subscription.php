<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasUuids;

    protected $fillable = [
        'team_id',
        'stripe_subscription_id',
        'stripe_customer_id',
        'stripe_price_id',
        'status',
        'plan',
        'price',
        'members_limit',
        'trial_ends_at',
        'current_period_start',
        'current_period_end',
        'canceled_at',
        'ended_at',
        'payment_method',
        'last_four',
        'metadata',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'canceled_at' => 'datetime',
        'ended_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Relationships
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'team_id', 'team_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', 'canceled');
    }

    /**
     * Status Checks
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCanceled(): bool
    {
        return $this->status === 'canceled';
    }

    public function isPastDue(): bool
    {
        return $this->status === 'past_due';
    }

    public function isFree(): bool
    {
        return $this->plan === 'free';
    }

    public function isPro(): bool
    {
        return $this->plan === 'pro';
    }

    public function isEnterprise(): bool
    {
        return $this->plan === 'enterprise';
    }

    /**
     * Price formatting
     */
    public function getPriceFormatted(): string
    {
        return '$' . number_format($this->price / 100, 2);
    }

    /**
     * Trial check
     */
    public function onTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Billing period
     */
    public function renewsAt()
    {
        return $this->current_period_end;
    }

    /**
     * Plans pricing
     */
    public static function plans()
    {
        return [
            'free' => [
                'name' => 'Free',
                'price' => 0,
                'members_limit' => 1,
                'features' => [
                    'Unlimited projects',
                    'Up to 1 team member',
                    'Basic task management',
                    'Email support',
                ],
            ],
            'pro' => [
                'name' => 'Pro',
                'price' => 2900, // $29.00
                'members_limit' => 5,
                'features' => [
                    'Unlimited projects',
                    'Up to 5 team members',
                    'Advanced analytics',
                    'Real-time notifications',
                    'Priority support',
                ],
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'price' => 9900, // $99.00
                'members_limit' => 15, // unlimited
                'features' => [
                    'Unlimited everything',
                    'Unlimited team members',
                    'Advanced analytics',
                    'API access',
                    'Custom integrations',
                    'Dedicated support',
                ],
            ],
        ];
    }

    public static function planDetails($plan)
    {
        return self::plans()[$plan] ?? null;
    }
}