<!-- Top Navigation - Desktop Only -->
<nav class="bg-white border-b border-gray-100 hidden md:block shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="transform transition-all duration-300 hover:scale-110 active:scale-95">
                        <x-application-logo class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-2 border-b-2 {{ request()->routeIs('dashboard') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out relative group">
                        {{ __('Home') }}
                        @if(request()->routeIs('dashboard'))
                            <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 transform scale-x-100 transition-transform duration-300"></span>
                        @else
                            <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        @endif
                    </a>
                    @if(!Auth::user()->isAdmin())
                        <a href="{{ route('attendance.index') }}" class="inline-flex items-center px-3 py-2 border-b-2 {{ request()->routeIs('attendance.*') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out relative group">
                            {{ __('Absensi') }}
                            @if(request()->routeIs('attendance.*'))
                                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 transform scale-x-100 transition-transform duration-300"></span>
                            @else
                                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                            @endif
                        </a>
                    @endif
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-3 py-2 border-b-2 {{ request()->routeIs('admin.dashboard') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out relative group">
                            📊 {{ __('Admin') }}
                            @if(request()->routeIs('admin.dashboard'))
                                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 transform scale-x-100 transition-transform duration-300"></span>
                            @else
                                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                            @endif
                        </a>
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-3 py-2 border-b-2 {{ request()->routeIs('admin.users.create') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out relative group">
                            ➕ {{ __('Tambah User') }}
                            @if(request()->routeIs('admin.users.create'))
                                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 transform scale-x-100 transition-transform duration-300"></span>
                            @else
                                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                            @endif
                        </a>
                        <a href="{{ route('admin.holidays.index') }}" class="inline-flex items-center px-3 py-2 border-b-2 {{ request()->routeIs('admin.holidays.*') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out relative group">
                            📅 {{ __('Hari Libur') }}
                            @if(request()->routeIs('admin.holidays.*'))
                                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 transform scale-x-100 transition-transform duration-300"></span>
                            @else
                                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                            @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-500 bg-white hover:text-gray-700 hover:bg-gray-50 focus:outline-none transition-all duration-300 transform hover:scale-105 active:scale-95">
                            <div class="flex items-center space-x-2">
                                @if(Auth::user()->photo)
                                    <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover border-2 border-blue-200">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span>{{ Auth::user()->name }}</span>
                            </div>

                            <div class="ms-2 transform transition-transform duration-300 group-hover:rotate-180">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Top Bar - Simple -->
<nav class="bg-white border-b border-gray-100 md:hidden sticky top-0 z-30 shadow-sm">
    <div class="px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                @if(Auth::user()->photo)
                    <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Profile" class="w-10 h-10 rounded-full object-cover border-2 border-transparent bg-gradient-to-br from-blue-500 via-indigo-500 to-blue-600 p-0.5 transform transition-all duration-300 hover:scale-110">
                @else
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 via-indigo-500 to-blue-600 flex items-center justify-center text-white text-sm font-bold border-2 border-white transform transition-all duration-300 hover:scale-110 hover:rotate-6">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">
                        @if(request()->routeIs('dashboard'))
                            Home
                        @elseif(request()->routeIs('attendance.*'))
                            Absensi
                        @elseif(request()->routeIs('admin.dashboard'))
                            Admin Dashboard
                        @elseif(request()->routeIs('admin.users.*'))
                            Tambah User
                        @elseif(request()->routeIs('admin.holidays.*'))
                            Kelola Hari Libur
                        @elseif(request()->routeIs('profile.*'))
                            Profile
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Bottom Navigation - Mobile Only -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 safe-bottom shadow-lg">
    <div class="grid {{ Auth::user()->isAdmin() ? 'grid-cols-5' : 'grid-cols-3' }} h-16">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-600' }} hover:text-blue-600 transition-all duration-300 transform hover:scale-110 active:scale-95 relative group">
            <svg class="w-6 h-6 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="text-xs font-medium transition-all duration-300">{{ __('Home') }}</span>
            @if(request()->routeIs('dashboard'))
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-12 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-b-full animate-pulse"></div>
            @endif
        </a>

        <!-- Absensi (Hidden for Admin) / Admin Dashboard (For Admin) -->
        @if(Auth::user()->isAdmin())
            <!-- Tambah User (Admin Only) - Dipindah ke posisi Admin -->
            <a href="{{ route('admin.users.create') }}" class="flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('admin.users.create') ? 'text-blue-600' : 'text-gray-600' }} hover:text-blue-600 transition-all duration-300 transform hover:scale-110 active:scale-95 relative group">
                <svg class="w-6 h-6 transition-transform duration-300 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-xs font-medium transition-all duration-300">{{ __('Tambah') }}</span>
                @if(request()->routeIs('admin.users.create'))
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-12 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-b-full animate-pulse"></div>
                @endif
            </a>
            
            <!-- Item Tengah Menonjol - Admin Dashboard (Admin Only) -->
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center justify-center -mt-8 w-16 h-16 mx-auto bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 text-white rounded-full shadow-xl hover:from-blue-600 hover:via-blue-700 hover:to-indigo-700 transition-all transform hover:scale-110 hover:rotate-6 active:scale-95 border-4 border-white {{ request()->routeIs('admin.dashboard') ? 'ring-2 ring-blue-300 animate-pulse' : '' }}">
                <svg class="w-7 h-7 transition-transform duration-300 hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </a>
            
            <!-- Kelola Hari Libur (Admin Only) -->
            <a href="{{ route('admin.holidays.index') }}" class="flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('admin.holidays.*') ? 'text-blue-600' : 'text-gray-600' }} hover:text-blue-600 transition-all duration-300 transform hover:scale-110 active:scale-95 relative group">
                <svg class="w-6 h-6 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-xs font-medium transition-all duration-300">{{ __('Libur') }}</span>
                @if(request()->routeIs('admin.holidays.*'))
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-12 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-b-full animate-pulse"></div>
                @endif
            </a>
        @else
            <!-- Item Tengah Menonjol - Absensi (User) -->
            <a href="{{ route('attendance.index') }}" class="flex flex-col items-center justify-center -mt-8 w-16 h-16 mx-auto bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 text-white rounded-full shadow-xl hover:from-blue-600 hover:via-blue-700 hover:to-indigo-700 transition-all transform hover:scale-110 hover:rotate-6 active:scale-95 border-4 border-white {{ request()->routeIs('attendance.*') ? 'ring-2 ring-blue-300 animate-pulse' : '' }}">
                <svg class="w-7 h-7 transition-transform duration-300 hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </a>
        @endif

        <!-- Profile -->
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('profile.*') ? 'text-blue-600' : 'text-gray-600' }} hover:text-blue-600 transition-all duration-300 transform hover:scale-110 active:scale-95 relative group">
            <svg class="w-6 h-6 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-xs font-medium transition-all duration-300">{{ __('Profile') }}</span>
            @if(request()->routeIs('profile.*'))
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-12 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-b-full animate-pulse"></div>
            @endif
        </a>
    </div>
</nav>
