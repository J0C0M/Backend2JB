<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'player1_id',
        'player2_id',
        'target_word',
        'status',
        'winner_id',
        'player1_guesses',
        'player2_guesses',
        'max_attempts',
        'is_multiplayer',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'player1_guesses' => 'array',
            'player2_guesses' => 'array',
            'is_multiplayer' => 'boolean',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    // Relationships
    public function player1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player1_id');
    }

    public function player2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player2_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    // Helper methods
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isWaiting(): bool
    {
        return $this->status === 'waiting';
    }
}
