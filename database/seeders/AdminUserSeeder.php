<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seeds one Super Administrator for initial access. Credentials are
     * pulled from env so a real deploy never ships this seeder with a
     * hardcoded password; falls back to a random password printed once
     * to the console if the env vars are absent.
     */
    public function run(): void
    {
        $email = env('SEED_ADMIN_EMAIL');
        $password = env('SEED_ADMIN_PASSWORD');

        // env()'s default parameter only applies when the key is
        // entirely absent from .env — a present-but-blank `KEY=` line
        // (e.g. copied straight from .env.example) returns an empty
        // string instead, silently bypassing that default. Checking
        // emptiness explicitly here closes that gap regardless of
        // which way the key is missing.
        if (empty($email)) {
            $email = 'admin@fieldforecasts.test';
        }

        $generated = false;
        if (empty($password)) {
            $password = str()->random(16);
            $generated = true;
        }

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Administrator',
                'username' => 'superadmin',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles([User::ROLE_SUPER_ADMIN]);

        if ($generated) {
            $this->command?->warn("Seeded super admin {$email} with generated password: {$password}");
        } else {
            $this->command?->info("Seeded super admin {$email} (password from SEED_ADMIN_PASSWORD).");
        }
    }
}
