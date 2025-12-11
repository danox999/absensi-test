<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            📊 {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-4 md:py-12 pb-20 md:pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 md:space-y-6">
            
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            
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

            <!-- Statistik Bulan Ini -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm">
                    <p class="text-xs md:text-sm text-gray-600 mb-1">Total Absensi</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-900">{{ $monthStats['total_attendances'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Bulan ini</p>
                </div>
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm">
                    <p class="text-xs md:text-sm text-gray-600 mb-1">Total User</p>
                    <p class="text-xl md:text-2xl font-bold text-blue-600">{{ $monthStats['total_users'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Aktif</p>
                </div>
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm">
                    <p class="text-xs md:text-sm text-gray-600 mb-1">Admin</p>
                    <p class="text-xl md:text-2xl font-bold text-purple-600">{{ $monthStats['total_admins'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total</p>
                </div>
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm">
                    <p class="text-xs md:text-sm text-gray-600 mb-1">Tepat Waktu</p>
                    <p class="text-xl md:text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ $monthStats['on_time_percentage'] }}%</p>
                    <p class="text-xs text-gray-500 mt-1">Rata-rata</p>
                </div>
            </div>

            <!-- Statistik per Kantor -->
            @if(count($officeStats) > 0)
            <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm">
                <h3 class="text-base md:text-lg font-semibold mb-4">🏢 Statistik per Kantor</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($officeStats as $officeId => $stats)
                        <div class="border border-gray-200 rounded-lg p-4 bg-gradient-to-br from-gray-50 to-white">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-900">{{ $stats['name'] }}</h4>
                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full">{{ $stats['code'] }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-gray-600 text-xs">Total User</p>
                                    <p class="font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-xs">Check-in Hari Ini</p>
                                    <p class="font-bold text-blue-600">{{ $stats['today_checkin'] }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-xs">Check-out Hari Ini</p>
                                    <p class="font-bold text-green-600">{{ $stats['today_checkout'] }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-xs">Absensi Bulan Ini</p>
                                    <p class="font-bold text-purple-600">{{ $stats['month_attendances'] }}</p>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-600">Tepat Waktu</span>
                                    <span class="font-semibold text-green-600">{{ $stats['month_on_time'] }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    @php
                                        $total = $stats['month_on_time'] + $stats['month_late'];
                                        $percentage = $total > 0 ? ($stats['month_on_time'] / $total) * 100 : 0;
                                    @endphp
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Statistik per Role -->
            <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm">
                <h3 class="text-base md:text-lg font-semibold mb-4">👥 Statistik per Role</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($roleStats as $role => $stats)
                        <div class="border border-gray-200 rounded-lg p-4 bg-gradient-to-br from-gray-50 to-white">
                            <h4 class="font-semibold text-gray-900 mb-3 text-sm">{{ $stats['display_name'] }}</h4>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total User</span>
                                    <span class="font-bold text-gray-900">{{ $stats['total_users'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Check-in Hari Ini</span>
                                    <span class="font-bold text-blue-600">{{ $stats['today_checkin'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Check-out Hari Ini</span>
                                    <span class="font-bold text-green-600">{{ $stats['today_checkout'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Absensi Bulan Ini</span>
                                    <span class="font-bold text-purple-600">{{ $stats['month_attendances'] }}</span>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-200 space-y-1">
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Tepat Waktu</span>
                                    <span class="font-semibold text-green-600">{{ $stats['month_on_time'] }}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Terlambat</span>
                                    <span class="font-semibold text-yellow-600">{{ $stats['month_late'] }}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Pulang Cepat</span>
                                    <span class="font-semibold text-orange-600">{{ $stats['month_early_out'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- User yang Belum Check-in / Check-out -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <!-- User yang Belum Check-in -->
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-l-4 border-red-500">
                    <h3 class="text-base md:text-lg font-semibold mb-4 text-red-700">⚠️ Belum Check-in Hari Ini</h3>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @forelse($usersNotCheckedIn as $user)
                            <div class="flex items-center space-x-3 p-2 bg-red-50 rounded-lg border border-red-100">
                                @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    @if($user->office)
                                        <p class="text-xs text-gray-400">{{ $user->office->name }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-gray-500">
                                <p class="text-4xl mb-2">✅</p>
                                <p class="text-sm">Semua user sudah check-in</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- User yang Sudah Check-in Tapi Belum Check-out -->
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
                    <h3 class="text-base md:text-lg font-semibold mb-4 text-yellow-700">⏰ Belum Check-out Hari Ini</h3>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @forelse($checkedInButNotOut as $attendance)
                            <div class="flex items-center space-x-3 p-2 bg-yellow-50 rounded-lg border border-yellow-100">
                                @if($attendance->user->photo)
                                    <img src="{{ asset('storage/' . $attendance->user->photo) }}" alt="{{ $attendance->user->name }}" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">{{ $attendance->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $attendance->user->email }}</p>
                                    <p class="text-xs text-blue-600 font-medium">
                                        Check-in: {{ $attendance->check_in_time->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-gray-500">
                                <p class="text-4xl mb-2">✅</p>
                                <p class="text-sm">Semua user sudah check-out</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <!-- Grafik 7 Hari Terakhir (Line Chart) -->
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm">
                    <h3 class="text-base md:text-lg font-semibold mb-4">📈 Absensi 7 Hari Terakhir</h3>
                    <canvas id="last7DaysChart" height="200"></canvas>
                </div>

                <!-- Grafik Status Bulan Ini (Doughnut Chart) -->
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm">
                    <h3 class="text-base md:text-lg font-semibold mb-4">📊 Status Kehadiran Bulan Ini</h3>
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Grafik Per Kantor (Bar Chart) -->
            @if(count($officeStats) > 0)
            <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm">
                <h3 class="text-base md:text-lg font-semibold mb-4">🏢 Perbandingan Absensi per Kantor</h3>
                <canvas id="officeChart" height="100"></canvas>
            </div>
            @endif

            <!-- Grafik Per Role (Bar Chart) -->
            <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm">
                <h3 class="text-base md:text-lg font-semibold mb-4">👥 Perbandingan Absensi per Role</h3>
                <canvas id="roleChart" height="100"></canvas>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <!-- Absensi Hari Ini -->
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm">
                    <h3 class="text-base md:text-lg font-semibold mb-4">📋 Absensi Hari Ini</h3>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @forelse($todayAttendances as $attendance)
                            <div class="border rounded-lg p-3 bg-gray-50">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-2">
                                        @if($attendance->user->photo)
                                            <img src="{{ asset('storage/' . $attendance->user->photo) }}" alt="{{ $attendance->user->name }}" class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold">
                                                {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $attendance->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $attendance->user->email }}</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $attendance->status == 'present' ? 'bg-green-100 text-green-800 border border-green-200' : ($attendance->status == 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-orange-100 text-orange-800 border border-orange-200') }}">
                                        {{ $attendance->status == 'present' ? '✓' : ($attendance->status == 'late' ? '⚠' : '⚠') }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <p class="text-gray-500">Check-in</p>
                                        <p class="font-medium text-blue-700">
                                            {{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Check-out</p>
                                        <p class="font-medium text-blue-700">
                                            {{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p class="text-4xl mb-2">📭</p>
                                <p class="text-sm">Belum ada absensi hari ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Top 5 Users & Statistik Status -->
                <div class="space-y-4 md:space-y-6">
                    <!-- Lihat Absensi Per User -->
                    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-2 border-blue-200">
                        <h3 class="text-base md:text-lg font-semibold mb-2">👥 Absensi Per User</h3>
                        <p class="text-xs md:text-sm text-gray-600 mb-4">Lihat detail absensi setiap user dengan statistik lengkap</p>
                        <a href="{{ route('admin.users.attendances') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:via-blue-700 hover:to-indigo-700 transition shadow-lg font-semibold text-center">
                            👥 Lihat Absensi Per User
                        </a>
                    </div>

                    <!-- Top 5 Users -->
                    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm">
                        <h3 class="text-base md:text-lg font-semibold mb-4">🏆 Top 5 Absensi Bulan Ini</h3>
                        <div class="space-y-2">
                            @forelse($topUsers as $index => $attendance)
                                <div class="flex items-center space-x-3 p-2 bg-gray-50 rounded-lg">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white font-bold text-sm">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">{{ $attendance->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $attendance->total }} absensi</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">Belum ada data</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Statistik Status -->
                    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm">
                        <h3 class="text-base md:text-lg font-semibold mb-4">📊 Statistik Status Bulan Ini</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Tepat Waktu</span>
                                    <span class="font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ $statusStats['present'] }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    @php
                                        $totalStatus = $statusStats['present'] + $statusStats['late'] + $statusStats['early_out'];
                                        $presentPercentage = $totalStatus > 0 ? ($statusStats['present'] / $totalStatus) * 100 : 0;
                                    @endphp
                                    <div class="bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 h-3 rounded-full" style="width: {{ $presentPercentage }}%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Terlambat</span>
                                    <span class="font-semibold text-yellow-600">{{ $statusStats['late'] }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    @php
                                        $latePercentage = $totalStatus > 0 ? ($statusStats['late'] / $totalStatus) * 100 : 0;
                                    @endphp
                                    <div class="bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500 h-3 rounded-full" style="width: {{ $latePercentage }}%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Pulang Mendahului</span>
                                    <span class="font-semibold text-orange-600">{{ $statusStats['early_out'] }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    @php
                                        $earlyOutPercentage = $totalStatus > 0 ? ($statusStats['early_out'] / $totalStatus) * 100 : 0;
                                    @endphp
                                    <div class="bg-gradient-to-r from-orange-400 via-orange-500 to-red-500 h-3 rounded-full" style="width: {{ $earlyOutPercentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            // Grafik 7 Hari Terakhir (Line Chart)
            const last7DaysData = @json($last7Days);
            const last7DaysCtx = document.getElementById('last7DaysChart');
            if (last7DaysCtx) {
                new Chart(last7DaysCtx, {
                    type: 'line',
                    data: {
                        labels: last7DaysData.map(d => d['day'] + '\n' + d['date']),
                        datasets: [
                            {
                                label: 'Check-in',
                                data: last7DaysData.map(d => d['checkin']),
                                borderColor: 'rgb(139, 92, 246)',
                                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointRadius: 5,
                                pointHoverRadius: 7
                            },
                            {
                                label: 'Check-out',
                                data: last7DaysData.map(d => d['checkout']),
                                borderColor: 'rgb(20, 184, 166)',
                                backgroundColor: 'rgba(20, 184, 166, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointRadius: 5,
                                pointHoverRadius: 7
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }

            // Grafik Status Bulan Ini (Doughnut Chart)
            const statusStats = @json($statusStats);
            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Tepat Waktu', 'Terlambat', 'Pulang Mendahului'],
                        datasets: [{
                            data: [statusStats.present, statusStats.late, statusStats.early_out],
                            backgroundColor: [
                                'rgb(34, 197, 94)',
                                'rgb(234, 179, 8)',
                                'rgb(249, 115, 22)'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Grafik Per Kantor (Bar Chart)
            @if(count($officeStats) > 0)
            const officeStats = @json($officeStats);
            const officeCtx = document.getElementById('officeChart');
            if (officeCtx) {
                const officeNames = Object.values(officeStats).map(s => s.name);
                const officeCheckins = Object.values(officeStats).map(s => s.today_checkin);
                const officeCheckouts = Object.values(officeStats).map(s => s.today_checkout);
                
                new Chart(officeCtx, {
                    type: 'bar',
                    data: {
                        labels: officeNames,
                        datasets: [
                            {
                                label: 'Check-in Hari Ini',
                                data: officeCheckins,
                                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                                borderColor: 'rgb(59, 130, 246)',
                                borderWidth: 2
                            },
                            {
                                label: 'Check-out Hari Ini',
                                data: officeCheckouts,
                                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                                borderColor: 'rgb(34, 197, 94)',
                                borderWidth: 2
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
            @endif

            // Grafik Per Role (Bar Chart)
            const roleStats = @json($roleStats);
            const roleCtx = document.getElementById('roleChart');
            if (roleCtx) {
                const roleNames = Object.values(roleStats).map(s => s.display_name);
                const roleCheckins = Object.values(roleStats).map(s => s.today_checkin);
                const roleCheckouts = Object.values(roleStats).map(s => s.today_checkout);
                
                new Chart(roleCtx, {
                    type: 'bar',
                    data: {
                        labels: roleNames,
                        datasets: [
                            {
                                label: 'Check-in Hari Ini',
                                data: roleCheckins,
                                backgroundColor: 'rgba(139, 92, 246, 0.8)',
                                borderColor: 'rgb(139, 92, 246)',
                                borderWidth: 2
                            },
                            {
                                label: 'Check-out Hari Ini',
                                data: roleCheckouts,
                                backgroundColor: 'rgba(20, 184, 166, 0.8)',
                                borderColor: 'rgb(20, 184, 166)',
                                borderWidth: 2
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>

