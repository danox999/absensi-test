<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
                👥 {{ __('Absensi Per User') }}
            </h2>
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('admin.users.attendances.export', request()->all()) }}" class="px-4 py-2 bg-gradient-to-r from-green-500 via-green-600 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:via-green-700 hover:to-emerald-700 text-sm font-semibold shadow-lg transition text-center">
                    📥 Export ke Excel
                </a>
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 text-sm text-center">
                    ← Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 md:py-12 pb-20 md:pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Mobile Header (Visible on Mobile Only) -->
            <div class="md:hidden mb-4">
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">👥 Absensi Per User</h3>
                    <a href="{{ route('admin.users.attendances.export', request()->all()) }}" class="block w-full px-4 py-2 bg-gradient-to-r from-green-500 via-green-600 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:via-green-700 hover:to-emerald-700 text-sm font-semibold shadow-lg transition text-center">
                        📥 Export ke Excel
                    </a>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 mb-4 md:mb-6">
                <form method="GET" action="{{ route('admin.users.attendances') }}" class="space-y-3 md:space-y-0 md:grid md:grid-cols-6 md:gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Cari User</label>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Nama atau email..."
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>

                    <!-- Role Filter -->
                    <div>
                        <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select 
                            name="role" 
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">Semua Role</option>
                            <option value="karyawan_pusat" {{ request('role') == 'karyawan_pusat' ? 'selected' : '' }}>Karyawan Pusat</option>
                            <option value="karyawan_cabang" {{ request('role') == 'karyawan_cabang' ? 'selected' : '' }}>Karyawan Cabang</option>
                            <option value="call_center" {{ request('role') == 'call_center' ? 'selected' : '' }}>Call Center</option>
                            <option value="security" {{ request('role') == 'security' ? 'selected' : '' }}>Security</option>
                        </select>
                    </div>

                    <!-- Office Filter -->
                    <div>
                        <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Kantor</label>
                        <select 
                            name="office_id" 
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">Semua Kantor</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}" {{ request('office_id') == $office->id ? 'selected' : '' }}>
                                    {{ $office->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Start Date Filter -->
                    <div>
                        <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input 
                            type="date" 
                            name="start_date" 
                            value="{{ request('start_date') }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>

                    <!-- End Date Filter -->
                    <div>
                        <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <input 
                            type="date" 
                            name="end_date" 
                            value="{{ request('end_date') }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-2">
                        <button 
                            type="submit" 
                            class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:via-blue-700 hover:to-indigo-700 text-sm font-semibold shadow-lg transition"
                        >
                            🔍 Filter
                        </button>
                        <a 
                            href="{{ route('admin.users.attendances') }}" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition"
                        >
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Users List -->
            <div class="space-y-4">
                @forelse($users as $user)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <!-- User Header -->
                        <div class="p-4 md:p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                <div class="flex items-center space-x-3">
                                    @if($user->photo)
                                        <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-blue-200">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-lg font-bold border-2 border-blue-200">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="text-base md:text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                                        <p class="text-xs md:text-sm text-gray-500">{{ $user->email }}</p>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                {{ $user->role_display }}
                                            </span>
                                            @if($user->office)
                                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
                                                    📍 {{ $user->office->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Stats -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4">
                                    <div class="text-center p-2 bg-blue-50 rounded-lg">
                                        <p class="text-xs text-gray-600">Total</p>
                                        <p class="text-lg font-bold text-blue-600">{{ $userStats[$user->id]['total_this_month'] ?? 0 }}</p>
                                    </div>
                                    <div class="text-center p-2 bg-green-50 rounded-lg">
                                        <p class="text-xs text-gray-600">Tepat Waktu</p>
                                        <p class="text-lg font-bold text-green-600">{{ $userStats[$user->id]['present'] ?? 0 }}</p>
                                    </div>
                                    <div class="text-center p-2 bg-yellow-50 rounded-lg">
                                        <p class="text-xs text-gray-600">Terlambat</p>
                                        <p class="text-lg font-bold text-yellow-600">{{ $userStats[$user->id]['late'] ?? 0 }}</p>
                                    </div>
                                    <div class="text-center p-2 bg-orange-50 rounded-lg">
                                        <p class="text-xs text-gray-600">Pulang Cepat</p>
                                        <p class="text-lg font-bold text-orange-600">{{ $userStats[$user->id]['early_out'] ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance History -->
                        <div class="p-4 md:p-6">
                            <h4 class="text-sm md:text-base font-semibold text-gray-900 mb-3">Riwayat Absensi (30 Terakhir)</h4>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @forelse($user->attendances->take(30) as $attendance)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $attendance->date->format('d F Y') }}
                                                </p>
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                                    {{ $attendance->status == 'present' ? 'bg-green-100 text-green-800 border border-green-200' : 
                                                       ($attendance->status == 'late' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($attendance->status == 'early_out' ? 'bg-orange-100 text-orange-800 border border-orange-200' : 'bg-gray-100 text-gray-800')) }}">
                                                    {{ $attendance->status == 'present' ? 'Tepat Waktu' : 
                                                       ($attendance->status == 'late' ? 'Terlambat' : 
                                                       ($attendance->status == 'early_out' ? 'Pulang Cepat' : 'Tidak Hadir')) }}
                                                </span>
                                            </div>
                                            <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                                                <div>
                                                    <span class="font-medium">Check-in:</span>
                                                    <span class="ml-1">{{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}</span>
                                                </div>
                                                <div>
                                                    <span class="font-medium">Check-out:</span>
                                                    <span class="ml-1">{{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}</span>
                                                </div>
                                            </div>
                                            @if($attendance->notes)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <span class="font-medium">Catatan:</span> {{ $attendance->notes }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-gray-500">
                                        <p class="text-sm">Belum ada riwayat absensi</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                        <p class="text-4xl mb-2">📭</p>
                        <p class="text-gray-500">Tidak ada user yang ditemukan</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

