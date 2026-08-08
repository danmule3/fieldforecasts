<x-layouts.guest :title="__('Forgot password')">
    <h1 class="text-lg font-semibold mb-2">Reset your password</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
        Enter your email and we'll send you a reset link.
    </p>

    @session('status')
        <x-alert type="success" class="mb-4">{{ session('status') }}</x-alert>
    @endsession

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <x-input label="Email" name="email" type="email" :value="old('email')" required autofocus />
        <x-button type="submit" class="w-full">Send reset link</x-button>
    </form>
</x-layouts.guest>
