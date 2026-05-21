<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasUuids;

    protected $fillable = [
        'team_id',
        'stripe_invoice_id',
        'stripe_customer_id',
        'amount',
        'currency',
        'status',
        'plan',
        'pdf_url',
        'hosted_invoice_url',
        'paid_at',
        'due_date',
        'description',
        'metadata',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'due_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Relationships
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Scopes
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['open', 'draft']);
    }

    /**
     * Status Checks
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isPending(): bool
    {
        return in_array($this->status, ['open', 'draft']);
    }

    /**
     * Amount formatting
     */
    public function getAmountFormatted(): string
    {
        return '$' . number_format($this->amount / 100, 2);
    }

    /**
     * Status badge color
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'paid' => 'green',
            'open' => 'blue',
            'draft' => 'gray',
            default => 'gray',
        };
    }
}