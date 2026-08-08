<x-layouts.admin :title="'Edit role: ' . $role->name">
    <h2 class="text-lg font-semibold mb-6">Permissions for {{ $role->name }}</h2>

    <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="max-w-lg bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
        @csrf @method('PUT')

        <div class="space-y-2 mb-6">
            @foreach ($permissions as $permission)
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                           @checked(in_array($permission->name, $assigned)) class="rounded border-slate-300 text-indigo-600">
                    {{ $permission->name }}
                </label>
            @endforeach
        </div>

        <div class="flex gap-3">
            <x-button type="submit">Save</x-button>
            <a href="{{ route('admin.roles.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
        </div>
    </form>
</x-layouts.admin>
