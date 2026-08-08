<x-layouts.guest :title="__('Create account')">
    <h1 class="text-lg font-semibold mb-6">Create your account</h1>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <x-input label="Full name" name="name" :value="old('name')" required autofocus />
        <x-input label="Username" name="username" :value="old('username')" required />
        <x-input label="Email" name="email" type="email" :value="old('email')" required />
        <x-input label="Password" name="password" type="password" required />
        <x-input label="Confirm password" name="password_confirmation" type="password" required />

        <x-button type="submit" class="w-full">Create account</x-button>
    </form>

    <p class="mt-6 text-sm text-slate-500 dark:text-slate-400">
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 dark:text-indigo-400">Log in</a>
    </p>
</x-layouts.guest>
