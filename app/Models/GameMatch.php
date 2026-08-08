<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * Named `GameMatch` (table: `matches`) rather than `Match` — `match` is a
 * reserved expression in PHP 8+, and while class names technically
 * escape it, keeping it out of the class namespace avoids IDE/tooling
 * friction across the whole team.
 */
class GameMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_LIVE = 'live';
    public const STATUS_FINISHED = 'finished';
    public const STATUS_POSTPONED = 'postponed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'sport_id', 'league_id', 'home_team_id', 'away_team_id',
        'kickoff_at', 'venue', 'status', 'home_score', 'away_score',
        'minute', 'statistics', 'is_featured', 'external_provider', 'external_ref',
    ];

    protected function casts(): array
    {
        return [
            'kickoff_at' => 'datetime',
            'is_featured' => 'boolean',
            'statistics' => 'array',
        ];
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function predictions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Prediction::class, 'match_id');
    }

    public function odds(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Odd::class, 'match_id');
    }

    public function scopeLive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_LIVE);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SCHEDULED)->where('kickoff_at', '>=', now());
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereBetween('kickoff_at', [now()->startOfDay(), now()->endOfDay()]);
    }

    public function isLive(): bool
    {
        return $this->status === self::STATUS_LIVE;
    }
}
