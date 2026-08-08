<x-layouts.app :title="__('Profile')">
    <div class="max-w-2xl mx-auto px-4 py-10 space-y-10">
        <h1 class="text-2xl font-bold">Profile settings</h1>

        @session('status')
            <x-alert type="success">
                @switch(session('status'))
                    @case('profile-updated') Profile updated successfully. @break
                    @case('password-updated') Password updated successfully. @break
                    @default {{ session('status') }}
                @endswitch
            </x-alert>
        @endsession

        <section class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
            <h2 class="font-semibold mb-4">Profile information</h2>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PATCH')

                <x-input label="Full name" name="name" :value="old('name', $user->name)" required />
                <x-input label="Username" name="username" :value="old('username', $user->username)" required />
                <x-input label="Email" name="email" type="email" :value="old('email', $user->email)" required />

                @if (! $user->hasVerifiedEmail())
                    <x-alert type="info">
                        Your email is unverified.
                        <form method="POST" action="{{ route('verification.send') }}" class="inline">
                            @csrf
                            <button class="underline font-medium">Resend verification email</button>
                        </form>
                    </x-alert>
                @endif

                <div>
                    <label class="block text-sm font-medium mb-1">Avatar</label>
                    <input type="file" name="avatar" accept="image/*" class="text-sm">
                    @error('avatar') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Timezone</label>
                    <select name="timezone" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 px-3 py-2 text-sm">
                        @foreach (timezone_identifiers_list() as $tz)
                            <option value="{{ $tz }}" @selected(old('timezone', $user->timezone) === $tz)>{{ $tz }}</option>
                        @endforeach
                    </select>
                </div>

                <x-button type="submit">Save changes</x-button>
            </form>
        </section>

        <section class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-slate-900/5 dark:ring-white/10 p-6">
            <h2 class="font-semibold mb-4">Change password</h2>
            <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <x-input label="Current password" name="current_password" type="password" required />
                <x-input label="New password" name="password" type="password" required />
                <x-input label="Confirm new password" name="password_confirmation" type="password" required />

                <x-button type="submit">Update password</x-button>
            </form>
        </section>

        <section class="bg-white dark:bg-slate-900 rounded-2xl ring-1 ring-red-600/20 p-6">
            <h2 class="font-semibold text-red-600 mb-2">Delete account</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                This permanently deletes your account. This action cannot be undone.
            </p>
            <form method="POST" action="{{ route('profile.destroy') }}"
                  onsubmit="return confirm('Are you sure you want to delete your account?');" class="space-y-4">
                @csrf
                @method('DELETE')
                <x-input label="Confirm password" name="password" type="password" required />
                <x-button type="submit" variant="danger">Delete account</x-button>
            </form>
        </section>
    </div>
</x-layouts.app>
