<x-layouts.admin :title="'Countries'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold">Countries</h2>
        <a href="{{ route('admin.countries.create') }}"><x-button>+ Add country</x-button></a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl ring-1 ring-slate-900/5 dark:ring-white/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 text-left text-xs text-slate-500 dark:text-slate-400">
                <tr><th class="px-4 py-3">Name</th><th class="px-4 py-3">ISO</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($countries as $country)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $country->name }}</td>
                        <td class="px-4 py-3">{{ $country->iso_code }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.countries.edit', $country) }}" class="text-indigo-600 dark:text-indigo-400">Edit</a>
                            <form method="POST" action="{{ route('admin.countries.destroy', $country) }}" class="inline" onsubmit="return confirm('Delete this country?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 dark:text-red-400">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.admin>
