<x-layouts.guest :title="__('Reset password')">
    <h1 class="text-lg font-semibold mb-6">Set a new password</h1>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <x-input label="Email" name="email" type="email" :value="old('email', $request->email)" required autofocus />
        <x-input label="New password" name="password" type="password" required />
        <x-input label="Confirm new password" name="password_confirmation" type="password" required />
        <x-button type="submit" class="w-full">Reset password</x-button>
    </form>
</x-layouts.guest>
