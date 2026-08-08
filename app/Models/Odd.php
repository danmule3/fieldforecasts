<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Display-only odds information. Never exposes any "place bet" action —
 * see class-level note in MatchController/views. Purely informational.
 */
class Odd extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id', 'market_id', 'selection', 'price', 'provider', 'external_ref', 'fetched_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'fetched_at' => 'datetime',
        ];
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }
}
