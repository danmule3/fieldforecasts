<x-layouts.guest :title="__('Verify email')">
    <h1 class="text-lg font-semibold mb-2">Verify your email</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
        Thanks for signing up! Please check your inbox and click the verification link before continuing.
    </p>

    @session('status')
        @if (session('status') === 'verification-link-sent')
            <x-alert type="success" class="mb-4">A new verification link has been sent.</x-alert>
        @endif
    @endsession

    <div class="flex items-center gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-button type="submit">Resend verification email</x-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-button type="submit" variant="secondary">Log out</x-button>
        </form>
    </div>
</x-layouts.guest>
