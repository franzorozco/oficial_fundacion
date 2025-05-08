<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        
        @csrf

       <!-- Email Address -->
    <div>
        <x-input-label for="email" :value="__('Email')" class="text-white" />
        <x-text-input id="email" class="block mt-1 w-full bg-transparent border-white border-opacity-40 text-white placeholder-white focus:border-[#db7fb5] focus:ring-[#cf1d86] rounded-md shadow-sm" placeholder="Ingresa tu correo" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-200" />
    </div>

    <!-- Password -->
    <div class="mt-4">
        <x-input-label for="password" :value="__('Password')" class="text-white" />
        <x-text-input id="password" class="block mt-1 w-full bg-transparent border-white border-opacity-40 text-white placeholder-white focus:border-[#db7fb5] focus:ring-[#cf1d86] rounded-md shadow-sm"
                    placeholder="••••••••"
                    type="password"
                    name="password"
                    required autocomplete="current-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-200" />
    </div>

    <!-- Remember Me -->
    <div class="block mt-4">
        <label for="remember_me" class="inline-flex items-center text-white">
            <input id="remember_me" type="checkbox"
                class="rounded bg-transparent border-white border-opacity-30 text-[#db7fb5] focus:ring-[#cf1d86] shadow-sm"
                name="remember">
            <span class="ms-2 text-sm">{{ __('Remember me') }}</span>
        </label>
    </div>

    <!-- Footer -->
    <div class="flex items-center justify-between mt-6">
        @if (Route::has('password.request'))
            <a class="text-sm text-white underline hover:text-[#db7fb5]" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
        @endif

        <x-primary-button class="ms-3 bg-[#cf1d86] hover:bg-[#db7fb5] text-white font-bold py-2 px-4 rounded-md transition duration-300">
            {{ __('Log in') }}
        </x-primary-button>
    </div>

    </form>
</x-guest-layout>
