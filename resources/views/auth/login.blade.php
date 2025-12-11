<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-3 sm:mb-4 text-sm" :status="session('status')" />

    <div class="mb-3 sm:mb-6 text-center">
        <h2 class="text-base sm:text-xl md:text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-1 sm:mb-2">
            Selamat Datang Kembali
        </h2>
        <p class="text-xs sm:text-sm text-gray-600">Silakan masuk ke akun Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-2.5 sm:space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium text-sm sm:text-base" />
            <x-text-input 
                id="email" 
                class="block mt-1 sm:mt-2 w-full text-sm sm:text-base py-2.5 sm:py-3 px-3 sm:px-4 transition-all focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-lg border-gray-300" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="nama@email.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1 sm:mt-2 text-xs sm:text-sm" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium text-sm sm:text-base" />
            <x-text-input 
                id="password" 
                class="block mt-1 sm:mt-2 w-full text-sm sm:text-base py-2.5 sm:py-3 px-3 sm:px-4 transition-all focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-lg border-gray-300"
                type="password"
                name="password"
                required 
                autocomplete="current-password"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-1 sm:mt-2 text-xs sm:text-sm" />
        </div>

        <!-- Remember Me -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-0">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="w-4 h-4 sm:w-5 sm:h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 transition-all cursor-pointer" 
                    name="remember"
                >
                <span class="ms-2 text-xs sm:text-sm text-gray-600 group-hover:text-gray-900 transition-colors">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 hover:underline transition-colors font-medium py-1 sm:py-0" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2 sm:pt-4">
            <x-primary-button class="w-full justify-center py-2.5 sm:py-3.5 text-sm sm:text-base font-semibold bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 hover:from-blue-600 hover:via-blue-700 hover:to-indigo-700 transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg rounded-lg">
                {{ __('Masuk') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
