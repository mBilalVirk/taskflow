<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalAccessToken extends Model
{
    protected $fillable = ['user_id', 'name', 'token', 'abilities', 'last_used_at', 'expires_at'];

    protected $hidden = ['token'];

    protected $casts = [
        'abilities' => 'array',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a new API token
     */
    public static function generateToken(User $user, string $name, ?array $abilities = null): self
    {
        $token = hash('sha256', $name . time() . random_bytes(32));

        return self::create([
            'user_id' => $user->id,
            'name' => $name,
            'token' => $token,
            'abilities' => $abilities ?? ['read', 'write'],
        ]);
    }
}