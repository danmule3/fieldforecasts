<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    public const PERIOD_WEEKLY = 'weekly';
    public const PERIOD_MONTHLY = 'monthly';

    protected $fillable = [
        'name', 'slug', 'billing_period', 'duration_days',
        'price_cents', 'currency', 'features', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function priceFormatted(): string
    {
        return number_format($this->price_cents / 100, 2) . ' ' . $this->currency;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
