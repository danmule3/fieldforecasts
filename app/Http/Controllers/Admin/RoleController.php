<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Restricted to Super Administrator only, via the
 * `role:super-administrator` route middleware (see routes/admin.php)
 * — no other role should reach role/permission management at all,
 * so this hard-checks the role directly rather than relying on a
 * permission that could accidentally be granted to a lesser role later.
 */
class RoleController extends AdminController
{
    public function index(): View
    {
        return view('admin.roles.index', ['roles' => Role::withCount('permissions')->orderBy('name')->get()]);
    }

    public function edit(Role $role): View
    {
        return view('admin.roles.edit', [
            'role' => $role,
            'permissions' => Permission::orderBy('name')->get(),
            'assigned' => $role->permissions->pluck('name')->all(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        // Super Admin's permission set is intentionally not editable
        // here — it always has every permission via Gate::before,
        // and editing this list would just create confusing drift.
        abort_if($role->name === User::ROLE_SUPER_ADMIN, 403, 'Super Administrator permissions cannot be edited.');

        $data = $request->validate(['permissions' => ['array']]);

        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('status', 'Permissions updated.');
    }
}
