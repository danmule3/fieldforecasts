<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    /**
     * Roles (registered-user, premium-user, editor, administrator,
     * super-administrator) are foundational to almost every feature —
     * auth, predictions, subscriptions, the admin panel, comments —
     * so they're seeded once here for every test with a migrated
     * database, rather than requiring each test file to remember to
     * seed them (or worse, silently failing with RoleDoesNotExist).
     * firstOrCreate-based, so re-seeding in a test that already did
     * it explicitly is harmless.
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (Schema::hasTable('roles')) {
            $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
        }
    }
}
