<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_requires_auth(): void
    {
        $this->get('/profile')->assertRedirect('/login');
    }

    public function test_user_can_update_profile_information(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Updated Name',
            'username' => $user->username,
            'email' => $user->email,
            'timezone' => 'UTC',
            'locale' => 'en',
        ]);

        $response->assertRedirect();
        $this->assertSame('Updated Name', $user->fresh()->name);
    }

    public function test_changing_email_resets_verification(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'username' => $user->username,
            'email' => 'new-email@example.com',
            'timezone' => 'UTC',
            'locale' => 'en',
        ]);

        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_user_can_delete_own_account_with_correct_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete('/profile', ['password' => 'password']);

        $response->assertRedirect('/');
        $this->assertGuest();
        $this->assertSoftDeleted($user);
    }

    public function test_account_deletion_requires_correct_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete('/profile', ['password' => 'wrong-password']);

        $response->assertSessionHasErrors('password');
        $this->assertNotSoftDeleted($user);
    }
}
