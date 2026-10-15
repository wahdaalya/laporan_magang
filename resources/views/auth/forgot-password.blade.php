<x-guest-layout>
    <div class="mb-4 text-sm text-slate-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="!text-slate-700" />
            <x-text-input id="email" class="block mt-1 w-full !rounded-xl !border-slate-200 !bg-white !text-slate-800 placeholder:!text-slate-400 focus:!border-blue-500 focus:!ring-blue-500" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="!rounded-xl !bg-blue-700 !text-white !shadow-md !shadow-blue-900/15 !transition !duration-200 hover:!bg-blue-800 focus:!bg-blue-800 focus:!ring-blue-500 active:!bg-blue-900">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
