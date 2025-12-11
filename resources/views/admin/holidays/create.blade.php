<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
                ➕ {{ __('Tambah Hari Libur') }}
            </h2>
            <a href="{{ route('admin.holidays.index') }}" class="text-sm text-blue-600 hover:text-blue-800 text-center md:text-left">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-4 md:py-12 pb-20 md:pb-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-4 md:p-6">
                <form method="POST" action="{{ route('admin.holidays.store') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Hari Libur <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name') }}"
                            required 
                            autofocus
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                            placeholder="Contoh: Hari Raya Idul Fitri"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div class="mb-4">
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            name="date" 
                            id="date" 
                            value="{{ old('date') }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('date') border-red-500 @enderror"
                        >
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Berlaku Untuk
                        </label>
                        <select 
                            name="role" 
                            id="role" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror"
                        >
                            <option value="">Semua Role (Karyawan, Call Center, Security)</option>
                            <option value="karyawan_pusat" {{ old('role') == 'karyawan_pusat' ? 'selected' : '' }}>Karyawan Pusat</option>
                            <option value="karyawan_cabang" {{ old('role') == 'karyawan_cabang' ? 'selected' : '' }}>Karyawan Cabang</option>
                            <option value="call_center" {{ old('role') == 'call_center' ? 'selected' : '' }}>Call Center</option>
                            <option value="security" {{ old('role') == 'security' ? 'selected' : '' }}>Security</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Pilih role spesifik atau biarkan kosong untuk semua role</p>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi (Opsional)
                        </label>
                        <textarea 
                            name="description" 
                            id="description" 
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                            placeholder="Keterangan tambahan tentang hari libur ini"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                        <a href="{{ route('admin.holidays.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition text-center">
                            Batal
                        </a>
                        <button 
                            type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:via-blue-700 hover:to-indigo-700 font-semibold transition shadow-lg"
                        >
                            Simpan Hari Libur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
