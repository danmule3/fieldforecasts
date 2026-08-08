<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_premium' => false,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => ['email_verified_at' => null]);
    }

    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_premium' => true,
            'premium_expires_at' => now()->addMonth(),
        ]);
    }

    /**
     * Guarantees the "registered-user" role exists before assigning it,
     * rather than assuming every test/environment has run
     * RolesAndPermissionsSeeder first — assignRole() throws
     * RoleDoesNotExist otherwise, which would break any test using
     * this factory in isolation.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            \Spatie\Permission\Models\Role::firstOrCreate([
                'name' => User::ROLE_REGISTERED,
                'guard_name' => 'web',
            ]);

            $user->assignRole(User::ROLE_REGISTERED);
        });
    }
}
