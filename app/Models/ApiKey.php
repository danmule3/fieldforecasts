<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $fillable = ['provider', 'label', 'key_value', 'is_active', 'last_used_at'];

    protected $hidden = ['key_value'];

    protected function casts(): array
    {
        return [
            'key_value' => 'encrypted',
            'is_active' => 'boolean',
            'last_used_at' => 'datetime',
        ];
    }

    public function scopeActiveForProvider(Builder $query, string $provider): Builder
    {
        return $query->where('provider', $provider)->where('is_active', true);
    }

    /** Never expose the real key in the admin UI — last 4 characters only. */
    public function masked(): string
    {
        $value = $this->key_value;

        return $value ? str_repeat('•', max(0, strlen($value) - 4)) . substr($value, -4) : '';
    }
}
