<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends AdminController
{
    public function __construct(private readonly ActivityLogger $activityLogger)
    {
    }

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $users = User::query()
            ->with('roles')
            ->when($request->search, fn ($q, $v) => $q->where(fn ($q2) => $q2->where('name', 'like', "%{$v}%")->orWhere('email', 'like', "%{$v}%")))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', ['users' => $users]);
    }

    public function edit(User $user): View
    {
        Gate::authorize('view', $user);

        return view('admin.users.edit', ['user' => $user, 'roles' => Role::orderBy('name')->get()]);
    }

    /** Role changes go through UserPolicy::changeRole() — Super Admin only, and never on your own account (privilege-escalation guard). */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('changeRole', $user);

        $data = $request->validate([
            'role' => ['required', Rule::exists('roles', 'name')],
        ]);

        $user->syncRoles([$data['role']]);
        $this->activityLogger->log('user.role_changed', $request->user(), $user, ['role' => $data['role']]);

        return back()->with('status', 'Role updated.');
    }

    public function toggleActive(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $user->update(['is_active' => ! $user->is_active]);
        $this->activityLogger->log('user.active_toggled', $request->user(), $user, ['is_active' => $user->is_active]);

        return back()->with('status', $user->is_active ? 'User reactivated.' : 'User suspended.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $user->delete();
        $this->activityLogger->log('user.deleted', $request->user(), $user);

        return redirect()->route('admin.users.index')->with('status', 'User deleted.');
    }
}
