<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * Canonical role slugs. Kept as constants (rather than a DB lookup)
     * so authorization logic in Policies/Gates has a single source of
     * truth for role names and typos fail at compile time, not runtime.
     */
    public const ROLE_REGISTERED = 'registered-user';
    public const ROLE_PREMIUM = 'premium-user';
    public const ROLE_EDITOR = 'editor';
    public const ROLE_ADMIN = 'administrator';
    public const ROLE_SUPER_ADMIN = 'super-administrator';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar_path',
        'timezone',
        'locale',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'premium_expires_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function favouriteTeams(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Team::class, 'team_user')->withTimestamps();
    }

    public function savedPredictions(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Prediction::class, 'prediction_user')->withTimestamps();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(\App\Models\Subscription::class);
    }

    public function currentSubscription(): ?\App\Models\Subscription
    {
        return $this->subscriptions()->active()->latest('ends_at')->first();
    }

    /**
     * Fast-path premium check. Subscriptions module (Module 4) is
     * responsible for keeping `is_premium` / `premium_expires_at` in
     * sync via an observer/scheduled job — this method never queries
     * the subscriptions table directly to keep it cheap for use in
     * Blade views and middleware on every request.
     */
    public function hasActivePremiumAccess(): bool
    {
        return $this->is_premium
            && $this->premium_expires_at !== null
            && $this->premium_expires_at->isFuture();
    }

    public function isStaff(): bool
    {
        return $this->hasAnyRole([self::ROLE_EDITOR, self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN]);
    }
}
