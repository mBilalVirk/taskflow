<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Webhook extends Model
{
    use HasUuids;

    protected $fillable = ['team_id', 'url', 'events', 'secret', 'is_active'];
    protected $casts = ['events' => 'array'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Trigger webhook
     */
    public static function trigger(string $event, $data): void
    {
        $webhooks = self::where('is_active', true)
            ->whereJsonContains('events', $event)
            ->get();

        foreach ($webhooks as $webhook) {
            dispatch(new \App\Jobs\TriggerWebhook($webhook, $event, $data));
        }
    }
}