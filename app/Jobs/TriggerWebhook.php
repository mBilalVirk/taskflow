<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use App\Models\Webhook;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TriggerWebhook implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Webhook $webhook,
        private string $event,
        private $data
    ) {}

    public function handle(): void
    {
        try {
            $payload = [
                'event' => $this->event,
                'data' => $this->data,
                'timestamp' => now()->toIso8601String(),
            ];

            // Create signature
            $signature = hash_hmac('sha256', json_encode($payload), $this->webhook->secret);

            // Send webhook
            $response = Http::withHeaders([
                'X-Webhook-Signature' => $signature,
                'Content-Type' => 'application/json',
            ])->post($this->webhook->url, $payload);

            // Update last triggered
            if ($response->successful()) {
                $this->webhook->update(['last_triggered_at' => now()]);
            }
        } catch (\Exception $e) {
            Log::error('Webhook trigger failed: ' . $e->getMessage());
            $this->fail($e);
        }
    }
}