<x-layouts.admin :title="'Roles & permissions'">
    <h2 class="text-lg font-semibold mb-6">Roles &amp; permissions</h2>

    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                <tr><th class="px-4 py-3">Role</th><th class="px-4 py-3">Permissions</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($roles as $role)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $role->name }}</td>
                        <td class="px-4 py-3">{{ $role->name === \App\Models\User::ROLE_SUPER_ADMIN ? 'All (implicit)' : $role->permissions_count }}</td>
                        <td class="px-4 py-3 text-right">
                            @if ($role->name !== \App\Models\User::ROLE_SUPER_ADMIN)
                                <a href="{{ route('admin.roles.edit', $role) }}" class="text-indigo-600 dark:text-indigo-400">Edit permissions</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.admin>
