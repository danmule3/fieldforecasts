<x-layouts.guest :title="__('Log in')">
    <h1 class="text-lg font-semibold mb-6">Log in to Field Forecast</h1>

    @session('status')
        <x-alert type="success" class="mb-4">{{ session('status') }}</x-alert>
    @endsession

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <x-input label="Email" name="email" type="email" :value="old('email')" required autofocus />
        <x-input label="Password" name="password" type="password" required />

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <span>Remember me</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-indigo-600 dark:text-indigo-400 font-medium">Forgot password?</a>
        </div>

        <x-button type="submit" class="w-full">Log in</x-button>
    </form>

    <p class="mt-6 text-sm text-slate-500 dark:text-slate-400">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-medium text-indigo-600 dark:text-indigo-400">Sign up</a>
    </p>
</x-layouts.guest>
