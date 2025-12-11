<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4 md:py-12 pb-20 md:pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 md:space-y-6">
            @if(Auth::user()->isAdmin())
                <!-- Welcome Card Admin -->
                <div class="bg-gradient-to-r from-purple-500 via-purple-600 to-indigo-600 text-white p-4 md:p-6 rounded-xl shadow-lg mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg md:text-xl font-bold mb-1">👑 Selamat Datang, {{ Auth::user()->name }}!</h3>
                            <p class="text-sm opacity-90">Dashboard Admin</p>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="bg-white text-purple-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition text-sm">
                            📊 Detail
                        </a>
                    </div>
                </div>

                <!-- Statistik Hari Ini -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4">
                    <div class="bg-gradient-to-br from-blue-400 to-blue-600 text-white p-4 rounded-xl shadow-lg">
                        <p class="text-xs md:text-sm opacity-90 mb-1">Check-in Hari Ini</p>
                        <p class="text-2xl md:text-3xl font-bold">{{ $todayStats['total_checkin'] }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-400 to-blue-600 text-white p-4 rounded-xl shadow-lg">
                        <p class="text-xs md:text-sm opacity-90 mb-1">Check-out Hari Ini</p>
                        <p class="text-2xl md:text-3xl font-bold">{{ $todayStats['total_checkout'] }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 text-white p-4 rounded-xl shadow-lg">
                        <p class="text-xs md:text-sm opacity-90 mb-1">Tepat Waktu</p>
                        <p class="text-2xl md:text-3xl font-bold">{{ $todayStats['on_time'] }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-red-400 to-red-600 text-white p-4 rounded-xl shadow-lg">
                        <p class="text-xs md:text-sm opacity-90 mb-1">Terlambat</p>
                        <p class="text-2xl md:text-3xl font-bold">{{ $todayStats['late'] }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-orange-400 to-orange-600 text-white p-4 rounded-xl shadow-lg">
                        <p class="text-xs md:text-sm opacity-90 mb-1">Pulang Cepat</p>
                        <p class="text-2xl md:text-3xl font-bold">{{ $todayStats['early_out'] }}</p>
                    </div>
                </div>

            @else
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white p-4 md:p-6 rounded-xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg md:text-xl font-bold mb-1">Selamat Datang, {{ Auth::user()->name }}!</h3>
                            <p class="text-sm opacity-90">
                                @if(isset($todayAttendance) && $todayAttendance->check_in_time)
                                    ✓ Sudah check-in hari ini
                                @else
                                    Belum check-in hari ini
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('attendance.index') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition text-sm">
                            📋 Absensi
                        </a>
                    </div>
                </div>

                <!-- Statistik Ringkas -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                    <div class="bg-white p-4 rounded-xl shadow-sm">
                        <p class="text-xs md:text-sm text-gray-600 mb-1">Total Absensi</p>
                        <p class="text-xl md:text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            {{ $summaryStats['total_this_month'] }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Bulan ini</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm">
                        <p class="text-xs md:text-sm text-gray-600 mb-1">Tepat Waktu</p>
                        <p class="text-xl md:text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                            {{ $summaryStats['on_time_percentage'] }}%
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Rata-rata</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm">
                        <p class="text-xs md:text-sm text-gray-600 mb-1">Tingkat Kehadiran</p>
                        <p class="text-xl md:text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                            {{ $summaryStats['attendance_rate'] }}%
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Bulan ini</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm">
                        <p class="text-xs md:text-sm text-gray-600 mb-1">Hari Kerja</p>
                        <p class="text-xl md:text-2xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                            {{ $summaryStats['total_days'] }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Total hari</p>
                    </div>
                </div>

            @endif
        </div>
    </div>

</x-app-layout>
