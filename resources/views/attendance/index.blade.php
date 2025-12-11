<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('Absensi') }}
        </h2>
    </x-slot>

    <div class="py-4 md:py-12 pb-32 md:pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Jam Real-time WIB -->
            <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 text-white rounded-lg p-4 md:p-5 mb-4 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs md:text-sm opacity-90 mb-1">Waktu Indonesia Barat</p>
                        <p id="realtimeClock" class="text-2xl md:text-3xl font-bold font-mono">
                            {{ now()->setTimezone('Asia/Jakarta')->format('H:i:s') }}
                        </p>
                        <p id="realtimeDate" class="text-xs md:text-sm opacity-90 mt-1">
                            {{ now()->setTimezone('Asia/Jakarta')->format('d F Y') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-4xl md:text-5xl">🕐</p>
                    </div>
                </div>
            </div>

            <!-- Info Kantor -->
            @if($office)
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-3 md:p-4 mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">🏢 {{ $office->name }}</p>
                            <p class="text-xs text-gray-600 mt-1">Radius absensi: {{ $office->radius_km }} km</p>
                            @if(isset($shiftTimes))
                                <p class="text-xs text-gray-600 mt-1">
                                    ⏰ Jam Kerja: 
                                    @if(Auth::user()->role === 'security')
                                        {{ $shiftTimes['check_in']->format('H:i') }} - {{ $shiftTimes['check_out']->format('H:i') }} WIB (Malam)
                                    @else
                                        {{ $shiftTimes['check_in']->format('H:i') }} - {{ $shiftTimes['check_out']->format('H:i') }} WIB
                                    @endif
                                </p>
                            @endif
                            @if(isset($isHoliday) && $isHoliday)
                                <p class="text-xs text-red-600 mt-1 font-semibold">🎉 Hari ini adalah hari libur: {{ $holidayName }}</p>
                            @elseif(isset($isWorkDay) && !$isWorkDay && in_array(Auth::user()->role, ['karyawan_pusat', 'karyawan_cabang']))
                                <p class="text-xs text-red-600 mt-1 font-semibold">⚠️ Hari ini bukan hari kerja (hanya Senin-Jumat)</p>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 md:p-4 mb-4">
                    <p class="text-sm text-yellow-800">⚠️ Anda belum terdaftar di kantor. Silakan hubungi admin.</p>
                </div>
            @endif

            <!-- Status Absensi Hari Ini -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-4 md:mb-6">
                <div class="p-4 md:p-6 text-gray-900">
                    <h3 class="text-base md:text-lg font-semibold mb-3 md:mb-4">Absensi Hari Ini</h3>
                    
                    @if($todayAttendance && $todayAttendance->check_in_time)
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-gray-50 p-3 md:p-4 rounded-lg border border-gray-200">
                                <p class="text-xs md:text-sm text-gray-600 mb-1">Check-in</p>
                                <p class="text-lg md:text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                    {{ $todayAttendance->check_in_time->format('H:i') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <span class="inline-block px-2 py-0.5 rounded {{ $todayAttendance->status == 'present' ? 'bg-green-100 text-green-800 border border-green-200' : ($todayAttendance->status == 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-orange-100 text-orange-800 border border-orange-200') }}">
                                        {{ $todayAttendance->status == 'present' ? 'Tepat Waktu' : ($todayAttendance->status == 'late' ? 'Terlambat' : 'Pulang Mendahului') }}
                                    </span>
                                </p>
                            </div>
                            
                            @if($todayAttendance->check_out_time)
                                <div class="bg-gray-50 p-3 md:p-4 rounded-lg border border-gray-200">
                                    <p class="text-xs md:text-sm text-gray-600 mb-1">Check-out</p>
                                    <p class="text-lg md:text-xl font-bold bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent">
                                        {{ $todayAttendance->check_out_time->format('H:i') }}
                                    </p>
                                    @if($todayAttendance->status == 'early_out')
                                        <p class="text-xs text-orange-600 mt-1 font-semibold">⚠️ Pulang Mendahului</p>
                                        @if($todayAttendance->notes)
                                            <p class="text-xs text-gray-600 mt-1 italic">"{{ $todayAttendance->notes }}"</p>
                                        @endif
                                    @endif
                                </div>
                            @else
                                <div class="bg-gray-50 p-3 md:p-4 rounded-lg">
                                    <p class="text-xs md:text-sm text-gray-600 mb-1">Check-out</p>
                                    <p class="text-lg md:text-xl text-gray-400">--:--</p>
                                    <p class="text-xs text-gray-400 mt-1">Belum</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-yellow-50 p-3 md:p-4 rounded-lg mb-4">
                            <p class="text-sm md:text-base text-yellow-800">📋 Anda belum melakukan check-in hari ini.</p>
                        </div>
                    @endif

                    <!-- Tombol Check-in/Check-out -->
                    <div class="grid grid-cols-1 gap-3">
                        @if(!$todayAttendance || !$todayAttendance->check_in_time)
                            <button type="button" id="checkInBtn" class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 hover:from-blue-600 hover:via-blue-700 hover:to-indigo-700 active:from-blue-700 active:via-blue-800 active:to-indigo-800 text-white font-bold py-4 px-6 rounded-xl transition-all shadow-lg text-lg">
                                📍 Check-in Sekarang
                            </button>
                        @elseif(!$todayAttendance->check_out_time)
                            <button type="button" id="checkOutBtn" class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 hover:from-blue-600 hover:via-blue-700 hover:to-indigo-700 active:from-blue-700 active:via-blue-800 active:to-indigo-800 text-white font-bold py-4 px-6 rounded-xl transition-all shadow-lg text-lg">
                                🏠 Check-out Sekarang
                            </button>
                        @else
                            <div class="bg-gray-100 text-gray-600 font-semibold py-4 px-6 rounded-xl text-center text-sm md:text-base">
                                ✅ Absensi hari ini sudah selesai
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Preview Kamera dan Lokasi -->
            <div id="cameraSection" class="bg-white overflow-hidden shadow-sm rounded-lg mb-4 md:mb-6 hidden">
                <div class="p-4 md:p-6">
                    <h3 class="text-base md:text-lg font-semibold mb-3 md:mb-4">📸 Verifikasi Absensi</h3>
                    
                    <div class="space-y-4">
                        <!-- Preview Kamera -->
                        <div>
                            <h4 class="font-semibold mb-2 text-sm md:text-base">Foto Anda</h4>
                            <video id="video" width="100%" autoplay playsinline class="rounded-lg border w-full aspect-video object-cover bg-black"></video>
                            <canvas id="canvas" class="hidden"></canvas>
                            <button id="captureBtn" class="mt-3 bg-indigo-500 hover:bg-indigo-600 active:bg-indigo-700 text-white font-bold py-3 md:py-2 px-4 rounded-lg w-full disabled:opacity-50 disabled:cursor-not-allowed transition-all text-base md:text-sm">
                                📷 Ambil Foto
                            </button>
                            <img id="capturedImage" class="mt-3 rounded-lg border hidden w-full" />
                        </div>

                        <!-- Lokasi -->
                        <div>
                            <h4 class="font-semibold mb-2 text-sm md:text-base">📍 Lokasi Anda</h4>
                            @if($office)
                                <div class="bg-gray-50 p-3 md:p-4 rounded-lg text-sm mb-3 border border-gray-200">
                                    <p class="font-semibold text-gray-800 mb-1">Kantor Anda:</p>
                                    <p class="text-gray-700">{{ $office->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Radius: {{ $office->radius_km }} km</p>
                                </div>
                            @endif
                            <div id="locationInfo" class="bg-gray-50 p-3 md:p-4 rounded-lg text-sm border border-gray-200">
                                <p class="text-gray-600">📡 Mendeteksi lokasi...</p>
                            </div>
                            <div id="distanceInfo" class="mt-2 text-xs text-gray-500 hidden"></div>
                        </div>

                        <!-- Catatan (Hanya untuk Check-out) -->
                        <div id="noteSection" class="hidden">
                            <h4 class="font-semibold mb-2 text-sm md:text-base">📝 Catatan (Wajib jika pulang mendahului)</h4>
                            <textarea 
                                id="noteInput" 
                                rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                placeholder="Isi catatan/keterangan jika Anda pulang mendahului jam pulang yang ditentukan..."
                            ></textarea>
                            <p class="text-xs text-gray-500 mt-1">Catatan wajib diisi jika Anda pulang sebelum jam pulang yang ditentukan.</p>
                        </div>

                        <!-- Tombol Kirim Fixed di Bottom pada Mobile -->
                        <div class="fixed md:relative bottom-16 md:bottom-0 left-0 right-0 p-4 md:p-0 bg-white md:bg-transparent border-t md:border-t-0 md:mt-4 z-40">
                            <button id="submitAttendanceBtn" class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 hover:from-blue-600 hover:via-blue-700 hover:to-indigo-700 active:from-blue-700 active:via-blue-800 active:to-indigo-800 text-white font-bold py-4 md:py-3 px-6 rounded-xl md:rounded-lg w-full disabled:opacity-50 disabled:cursor-not-allowed shadow-lg text-lg md:text-base transition-all" disabled>
                                ✅ Kirim Absensi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Absensi -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-4 md:p-6 text-gray-900">
                    <h3 class="text-base md:text-lg font-semibold mb-3 md:mb-4">📅 Riwayat Bulan Ini</h3>
                    
                    <!-- Mobile Card Layout -->
                    <div class="block md:hidden space-y-3">
                        @forelse($attendances as $attendance)
                            <div class="border rounded-lg p-3 bg-gray-50">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $attendance->date->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $attendance->date->translatedFormat('l') }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $attendance->status == 'present' ? 'bg-green-100 text-green-800 border border-green-200' : ($attendance->status == 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-orange-100 text-orange-800 border border-orange-200') }}">
                                        {{ $attendance->status == 'present' ? '✓ Tepat' : ($attendance->status == 'late' ? '⚠ Telat' : '⚠ Pulang Cepat') }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div>
                                        <p class="text-xs text-gray-500">Check-in</p>
                                        <p class="font-medium bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                            {{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Check-out</p>
                                        <p class="font-medium bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent">
                                            {{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p class="text-4xl mb-2">📋</p>
                                <p class="text-sm">Belum ada data absensi bulan ini</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Desktop Table Layout -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Check-in
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Check-out
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($attendances as $attendance)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $attendance->date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i:s') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i:s') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $attendance->status == 'present' ? 'bg-green-100 text-green-800 border border-green-200' : ($attendance->status == 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-orange-100 text-orange-800 border border-orange-200') }}">
                                                {{ $attendance->status == 'present' ? 'Tepat Waktu' : ($attendance->status == 'late' ? 'Terlambat' : 'Pulang Mendahului') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                            Belum ada data absensi bulan ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Real-time Clock WIB - Sinkron dengan server
        function updateClock() {
            // Gunakan waktu dari server untuk sinkronisasi
            const serverTime = new Date('{{ now()->setTimezone("Asia/Jakarta")->toIso8601String() }}');
            const now = new Date();
            
            // Hitung offset antara server time dan client time
            const serverTimestamp = serverTime.getTime();
            const clientTimestamp = now.getTime();
            const offset = serverTimestamp - clientTimestamp;
            
            // Apply offset untuk sinkronisasi
            const syncedTime = new Date(now.getTime() + offset);
            
            // Convert to WIB (UTC+7) - pastikan menggunakan timezone yang benar
            const wibOffset = 7 * 60; // 7 hours in minutes
            const utc = syncedTime.getTime() + (syncedTime.getTimezoneOffset() * 60000);
            const wibTime = new Date(utc + (wibOffset * 60000));
            
            const hours = String(wibTime.getHours()).padStart(2, '0');
            const minutes = String(wibTime.getMinutes()).padStart(2, '0');
            const seconds = String(wibTime.getSeconds()).padStart(2, '0');
            
            const clockElement = document.getElementById('realtimeClock');
            if (clockElement) {
                clockElement.textContent = `${hours}:${minutes}:${seconds}`;
            }
            
            // Update date
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                          'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[wibTime.getDay()];
            const day = wibTime.getDate();
            const month = months[wibTime.getMonth()];
            const year = wibTime.getFullYear();
            
            const dateElement = document.getElementById('realtimeDate');
            if (dateElement) {
                dateElement.textContent = `${dayName}, ${day} ${month} ${year}`;
            }
        }
        
        // Update clock every second
        setInterval(updateClock, 1000);
        updateClock(); // Initial call
    </script>
    <script>
        let currentLatitude = null;
        let currentLongitude = null;
        let capturedPhotoData = null;
        let attendanceType = null; // 'checkin' or 'checkout'
        let stream = null;
        
        // Office data from server
        const office = @json($office);

        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const capturedImage = document.getElementById('capturedImage');
        const cameraSection = document.getElementById('cameraSection');
        const locationInfo = document.getElementById('locationInfo');
        const submitBtn = document.getElementById('submitAttendanceBtn');
        const captureBtn = document.getElementById('captureBtn');

        // Event listener untuk tombol Check-in
        const checkInBtn = document.getElementById('checkInBtn');
        const checkOutBtn = document.getElementById('checkOutBtn');
        const noteSection = document.getElementById('noteSection');
        const noteInput = document.getElementById('noteInput');
        
        if (checkInBtn) {
            checkInBtn.addEventListener('click', () => {
                attendanceType = 'checkin';
                // Sembunyikan input catatan untuk check-in
                if (noteSection) {
                    noteSection.classList.add('hidden');
                    if (noteInput) noteInput.value = '';
                }
                startAttendanceProcess();
            });
        }

        // Event listener untuk tombol Check-out
        if (checkOutBtn) {
            checkOutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Check-out button clicked');
                attendanceType = 'checkout';
                // Tampilkan input catatan untuk check-out
                if (noteSection) {
                    noteSection.classList.remove('hidden');
                }
                try {
                    startAttendanceProcess();
                } catch (error) {
                    console.error('Error in startAttendanceProcess:', error);
                    alert('Terjadi error saat memulai proses check-out: ' + error.message);
                }
            });
        } else {
            console.warn('checkOutBtn element not found');
        }

        function startAttendanceProcess() {
            // Reset state
            capturedPhotoData = null;
            currentLatitude = null;
            currentLongitude = null;
            
            // Reset submit button
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = '✅ Kirim Absensi';
            }
            
            // Reset captured image dan video
            if (capturedImage) {
                capturedImage.classList.add('hidden');
            }
            if (video) {
                video.classList.remove('hidden');
            }
            
            // Stop previous stream jika ada
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            
            if (cameraSection) {
                cameraSection.classList.remove('hidden');
                cameraSection.scrollIntoView({ behavior: 'smooth' });
            }
            
            // Disable tombol capture sampai video ready
            if (captureBtn) {
                captureBtn.disabled = true;
                captureBtn.textContent = 'Menunggu kamera...';
            }
            
            // Aktifkan kamera dengan constraint untuk mobile
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                const constraints = {
                    video: {
                        facingMode: 'user', // Kamera depan untuk selfie
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }
                };
                
                navigator.mediaDevices.getUserMedia(constraints)
                    .then(mediaStream => {
                        stream = mediaStream;
                        video.srcObject = stream;
                        
                        // Enable tombol capture setelah video ready
                        video.onloadedmetadata = () => {
                            captureBtn.disabled = false;
                            captureBtn.textContent = '📷 Ambil Foto';
                        };
                    })
                    .catch(error => {
                        alert("Akses kamera ditolak atau tidak tersedia: " + error.message);
                        captureBtn.disabled = false;
                        captureBtn.textContent = '📷 Ambil Foto';
                    });
            } else {
                alert("Browser Anda tidak mendukung akses kamera.");
                captureBtn.disabled = false;
                captureBtn.textContent = '📷 Ambil Foto';
            }

            // Dapatkan lokasi
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        currentLatitude = position.coords.latitude;
                        currentLongitude = position.coords.longitude;
                        
                        let locationHtml = `
                            <p class="text-sm"><span class="font-semibold">Latitude:</span> ${currentLatitude.toFixed(6)}</p>
                            <p class="text-sm"><span class="font-semibold">Longitude:</span> ${currentLongitude.toFixed(6)}</p>
                        `;
                        
                        // Hitung jarak dari kantor jika ada
                        if (office) {
                            const distance = calculateDistance(
                                office.latitude,
                                office.longitude,
                                currentLatitude,
                                currentLongitude
                            );
                            
                            const isWithinRadius = distance <= office.radius_km;
                            
                            locationHtml += `
                                <p class="text-xs ${isWithinRadius ? 'text-green-600' : 'text-red-600'} mt-2 font-semibold">
                                    ${isWithinRadius ? '✓' : '✗'} Jarak: ${distance.toFixed(2)} km dari ${office.name}
                                </p>
                                <p class="text-xs ${isWithinRadius ? 'text-green-600' : 'text-red-600'}">
                                    ${isWithinRadius ? 'Anda berada dalam radius kantor' : `Anda berada di luar radius (max ${office.radius_km} km)`}
                                </p>
                            `;
                            
                            // Update distance info
                            const distanceInfo = document.getElementById('distanceInfo');
                            if (distanceInfo) {
                                distanceInfo.classList.remove('hidden');
                                distanceInfo.innerHTML = `<span class="${isWithinRadius ? 'text-green-600' : 'text-red-600'}">📍 ${distance.toFixed(2)} km dari kantor</span>`;
                            }
                        } else {
                            locationHtml += `<p class="text-xs text-blue-600 mt-2">✓ Lokasi berhasil didapatkan</p>`;
                        }
                        
                        locationInfo.innerHTML = locationHtml;
                        checkSubmitButtonState();
                    },
                    error => {
                        locationInfo.innerHTML = `<p class="text-sm text-red-600">Gagal mendapatkan lokasi: ${error.message}</p>`;
                    }
                );
            } else {
                locationInfo.innerHTML = `<p class="text-sm text-red-600">Browser tidak mendukung Geolocation.</p>`;
            }
        }

        // Ambil foto atau ambil ulang
        captureBtn.addEventListener('click', () => {
            // Jika sudah ada foto, reset untuk ambil ulang
            if (capturedPhotoData) {
                // Reset state
                video.classList.remove('hidden');
                capturedImage.classList.add('hidden');
                capturedPhotoData = null;
                captureBtn.disabled = true;
                captureBtn.textContent = 'Menunggu kamera...';
                captureBtn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
                captureBtn.classList.add('bg-indigo-500', 'hover:bg-indigo-600');
                
                // Restart camera
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    const constraints = {
                        video: {
                            facingMode: 'user',
                            width: { ideal: 1280 },
                            height: { ideal: 720 }
                        }
                    };
                    
                    navigator.mediaDevices.getUserMedia(constraints)
                        .then(mediaStream => {
                            stream = mediaStream;
                            video.srcObject = stream;
                            
                            // Enable tombol setelah video ready
                            video.onloadedmetadata = () => {
                                captureBtn.disabled = false;
                                captureBtn.textContent = '📷 Ambil Foto';
                            };
                        })
                        .catch(error => {
                            alert("Gagal mengaktifkan kamera: " + error.message);
                            captureBtn.disabled = false;
                            captureBtn.textContent = '📷 Ambil Foto';
                        });
                }
                
                checkSubmitButtonState();
            } else {
                // Capture foto baru
                if (video.videoWidth === 0 || video.videoHeight === 0) {
                    alert('Kamera belum siap. Tunggu sebentar.');
                    return;
                }
                
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0);
                
                capturedPhotoData = canvas.toDataURL('image/png');
                capturedImage.src = capturedPhotoData;
                capturedImage.classList.remove('hidden');
                video.classList.add('hidden');
                captureBtn.textContent = 'Ambil Ulang';
                captureBtn.classList.remove('bg-indigo-500', 'hover:bg-indigo-600');
                captureBtn.classList.add('bg-gray-500', 'hover:bg-gray-600');
                
                // Stop video stream untuk menghemat resource
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                
                checkSubmitButtonState();
            }
        });

        // Fungsi untuk menghitung jarak menggunakan Haversine formula
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radius bumi dalam kilometer
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = 
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        function checkSubmitButtonState() {
            if (!currentLatitude || !currentLongitude || !capturedPhotoData) {
                submitBtn.disabled = true;
                return;
            }
            
            // Validasi jarak jika ada office
            if (office) {
                const distance = calculateDistance(
                    office.latitude,
                    office.longitude,
                    currentLatitude,
                    currentLongitude
                );
                
                if (distance > office.radius_km) {
                    submitBtn.disabled = true;
                    return;
                }
            }
            
            submitBtn.disabled = false;
        }

        // Submit absensi
        submitBtn.addEventListener('click', async () => {
            if (!currentLatitude || !currentLongitude || !capturedPhotoData) {
                alert('Pastikan lokasi dan foto sudah diambil!');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengirim...';

            const endpoint = attendanceType === 'checkin' ? '/attendance/check-in' : '/attendance/check-out';

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        latitude: currentLatitude,
                        longitude: currentLongitude,
                        photo: capturedPhotoData,
                        note: attendanceType === 'checkout' ? (noteInput && noteInput.value ? noteInput.value.trim() : '') : null
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Kirim Absensi';
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Kirim Absensi';
            }
        });
    </script>
    @endpush
</x-app-layout>

