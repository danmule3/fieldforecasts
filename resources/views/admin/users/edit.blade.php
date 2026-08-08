<x-layouts.admin :title="'Manage user'">
    <div class="max-w-lg space-y-6">
        <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
            <h2 class="font-semibold mb-1">{{ $user->name }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ $user->email }}</p>

            <dl class="text-sm space-y-1 mb-4">
                <div class="flex justify-between"><dt class="text-slate-500">Current role</dt><dd>{{ $user->roles->pluck('name')->join(', ') }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Status</dt><dd>{{ $user->is_active ? 'Active' : 'Suspended' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Premium</dt><dd>{{ $user->hasActivePremiumAccess() ? 'Yes, until ' . $user->premium_expires_at->format('d M Y') : 'No' }}</dd></div>
            </dl>

            @can('changeRole', $user)
                <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="flex items-end gap-3 mb-4">
                    @csrf @method('PATCH')
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1">Change role</label>
                        <select name="role" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected($user->hasRole($role->name))>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-button type="submit" variant="secondary">Update</x-button>
                </form>
            @endcan

            <div class="flex gap-3">
                @can('update', $user)
                    <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                        @csrf @method('PATCH')
                        <x-button type="submit" variant="secondary">{{ $user->is_active ? 'Suspend user' : 'Reactivate user' }}</x-button>
                    </form>
                @endcan

                @can('delete', $user)
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?');">
                        @csrf @method('DELETE')
                        <x-button type="submit" variant="danger">Delete user</x-button>
                    </form>
                @endcan
            </div>
        </div>

        <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400">&larr; Back to users</a>
    </div>
</x-layouts.admin>
