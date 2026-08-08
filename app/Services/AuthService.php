<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class AuthService
{
    public function __construct(private readonly ActivityLogger $activityLogger)
    {
    }

    /**
     * Register a new user and assign the default "registered-user" role.
     * Wrapped in a transaction so a role-assignment failure never leaves
     * a roleless user in the database.
     */
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole(User::ROLE_REGISTERED);

            event(new Registered($user));

            $this->activityLogger->log('auth.registered', $user);

            return $user;
        });
    }

    public function recordSuccessfulLogin(User $user): void
    {
        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => Request::ip(),
        ])->save();

        $this->activityLogger->log('auth.login', $user);
    }

    public function logout(): void
    {
        $user = Auth::user();
        Auth::guard('web')->logout();

        if ($user) {
            $this->activityLogger->log('auth.logout', $user);
        }
    }

    public function changePassword(User $user, string $newPassword): void
    {
        $user->forceFill(['password' => Hash::make($newPassword)])->save();
        $this->activityLogger->log('auth.password_changed', $user);
    }

    public function deleteAccount(User $user): void
    {
        $this->activityLogger->log('auth.account_deleted', $user);
        $user->delete(); // soft delete — see User migration
    }
}
