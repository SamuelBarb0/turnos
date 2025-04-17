<x-guest-layout>
    <div class="flex flex-col items-center pt-6">
        <h2 class="mt-2 text-xl font-semibold text-gray-900">
            Accede a tu cuenta
        </h2>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo electrónico')" class="text-gray-700" />
            <x-text-input id="email" 
                class="block mt-1 w-full border-gray-300 focus:border-[#3161DD] focus:ring-[#3161DD] rounded-md shadow-sm" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" class="text-gray-700" />

            <x-text-input id="password" 
                class="block mt-1 w-full border-gray-300 focus:border-[#3161DD] focus:ring-[#3161DD] rounded-md shadow-sm"
                type="password"
                name="password"
                required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#3161DD] shadow-sm focus:ring-[#3161DD]" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3161DD]" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif

            <x-primary-button class="ms-3 bg-[#3161DD] hover:bg-[#2050C0]">
                {{ __('Iniciar sesión') }}
            </x-primary-button>
        </div>
        
        <!-- Separator -->
        <div class="relative flex items-center justify-center mt-6">
            <div class="border-t border-gray-300 w-full"></div>
            <div class="text-sm text-gray-500 bg-white px-3 absolute">O continúa con</div>
        </div>
        
        <!-- Social Login - Google Button with Logo -->
        <div class="mt-6">
            @if (Route::has('login.google'))
                <a href="{{ route('login.google') }}" class="w-full flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#3161DD] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Google
                </a>
            @endif
        </div>
        
        <!-- Register Link -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                ¿No tienes una cuenta? 
                <a href="{{ route('register') }}" class="font-medium text-[#3161DD] hover:text-[#2050C0]">
                    Regístrate ahora
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>