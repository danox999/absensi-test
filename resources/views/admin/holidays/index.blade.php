<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
                📅 {{ __('Kelola Hari Libur') }}
            </h2>
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('admin.holidays.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:via-blue-700 hover:to-indigo-700 text-sm font-semibold shadow-lg transition text-center active:scale-95">
                    ➕ Tambah Hari Libur
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 md:py-12 pb-20 md:pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Mobile Header with Button (Visible on Mobile Only) -->
            <div class="md:hidden mb-4">
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">📅 Kelola Hari Libur</h3>
                    <a href="{{ route('admin.holidays.create') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:via-blue-700 hover:to-indigo-700 text-sm font-semibold shadow-lg transition text-center active:scale-95">
                        ➕ Tambah Hari Libur
                    </a>
                </div>
            </div>

            <!-- Mobile Card Layout -->
            <div class="block md:hidden space-y-4">
                @forelse($holidays as $holiday)
                    <div class="bg-white shadow-sm rounded-lg p-4 border border-gray-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-gray-900 mb-1">{{ $holiday->name }}</h3>
                                <div class="text-sm text-gray-600">
                                    <p class="font-medium">{{ $holiday->date->format('d F Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $holiday->date->format('l') }}</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $holiday->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $holiday->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        
                        <div class="space-y-2 mb-3">
                            <div>
                                <span class="text-xs font-medium text-gray-500">Berlaku Untuk:</span>
                                <p class="text-sm text-gray-900">
                                    @if($holiday->role)
                                        {{ ucfirst(str_replace('_', ' ', $holiday->role)) }}
                                    @else
                                        <span class="text-blue-600 font-semibold">Semua Role</span>
                                    @endif
                                </p>
                            </div>
                            @if($holiday->description)
                                <div>
                                    <span class="text-xs font-medium text-gray-500">Deskripsi:</span>
                                    <p class="text-sm text-gray-900">{{ $holiday->description }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex gap-2 pt-3 border-t border-gray-200">
                            <form action="{{ route('admin.holidays.toggle', $holiday) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full px-3 py-2 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 font-medium transition">
                                    {{ $holiday->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.holidays.destroy', $holiday) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus hari libur ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-3 py-2 text-sm bg-red-50 text-red-600 rounded-lg hover:bg-red-100 font-medium transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow-sm rounded-lg p-8 text-center">
                        <p class="text-sm text-gray-500">Belum ada hari libur yang ditambahkan.</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table Layout -->
            <div class="hidden md:block bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Hari Libur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berlaku Untuk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($holidays as $holiday)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $holiday->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $holiday->date->format('d F Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $holiday->date->format('l') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if($holiday->role)
                                                {{ ucfirst(str_replace('_', ' ', $holiday->role)) }}
                                            @else
                                                <span class="text-blue-600 font-semibold">Semua Role</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $holiday->description ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $holiday->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $holiday->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex gap-2">
                                            <form action="{{ route('admin.holidays.toggle', $holiday) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-blue-600 hover:text-blue-900">
                                                    {{ $holiday->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                            <span class="text-gray-300">|</span>
                                            <form action="{{ route('admin.holidays.destroy', $holiday) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus hari libur ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                        Belum ada hari libur yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
