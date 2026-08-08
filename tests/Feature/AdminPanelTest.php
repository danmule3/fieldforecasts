<?php

namespace Tests\Feature;

use App\Models\Sport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_registered_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_editor_can_access_dashboard_and_manage_sports(): void
    {
        $editor = User::factory()->create();
        $editor->assignRole(User::ROLE_EDITOR);

        $this->actingAs($editor)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($editor)->get(route('admin.sports.index'))->assertOk();

        $response = $this->actingAs($editor)->post(route('admin.sports.store'), [
            'name' => 'Darts',
            'display_order' => 10,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.sports.index'));
        $this->assertDatabaseHas('sports', ['name' => 'Darts']);
    }

    public function test_editor_cannot_access_user_management(): void
    {
        $editor = User::factory()->create();
        $editor->assignRole(User::ROLE_EDITOR);

        // Editor passes the admin-panel gate but UserPolicy::viewAny
        // requires the users.view permission, which Editor lacks.
        $this->actingAs($editor)->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_administrator_can_manage_users_but_not_roles(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(User::ROLE_ADMIN);

        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.roles.index'))->assertForbidden();
    }

    public function test_administrator_cannot_change_roles(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(User::ROLE_ADMIN);
        $target = User::factory()->create();

        // UserPolicy::changeRole is Super Admin only.
        $this->actingAs($admin)
            ->patch(route('admin.users.update-role', $target), ['role' => User::ROLE_EDITOR])
            ->assertForbidden();
    }

    public function test_administrator_cannot_promote_self_or_others_to_super_admin_via_policy(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(User::ROLE_ADMIN);

        $this->assertFalse($admin->can('changeRole', $admin));
    }

    public function test_super_admin_can_manage_roles(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(User::ROLE_SUPER_ADMIN);

        $this->actingAs($superAdmin)->get(route('admin.roles.index'))->assertOk();

        $role = Role::where('name', User::ROLE_EDITOR)->first();

        $response = $this->actingAs($superAdmin)->put(route('admin.roles.update', $role), [
            'permissions' => ['users.view'],
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $this->assertTrue($role->fresh()->hasPermissionTo('users.view'));
    }

    public function test_super_admin_role_permissions_cannot_be_edited(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(User::ROLE_SUPER_ADMIN);

        $superAdminRole = Role::where('name', User::ROLE_SUPER_ADMIN)->first();

        $this->actingAs($superAdmin)
            ->put(route('admin.roles.update', $superAdminRole), ['permissions' => []])
            ->assertForbidden();
    }

    public function test_admin_can_delete_a_sport(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(User::ROLE_ADMIN);
        $sport = Sport::factory()->create();

        $this->actingAs($admin)->delete(route('admin.sports.destroy', $sport))->assertRedirect();
        $this->assertDatabaseMissing('sports', ['id' => $sport->id]);
    }
}
