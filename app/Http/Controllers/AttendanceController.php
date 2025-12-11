<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Admin tidak bisa akses halaman absensi
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin tidak dapat mengakses halaman absensi. Silakan gunakan dashboard admin.');
        }
        
        $today = Carbon::today('Asia/Jakarta');
        
        // Cek apakah sudah absen hari ini
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();
        
        // Ambil riwayat absensi bulan ini
        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->orderBy('date', 'desc')
            ->get();
        
        // Load office untuk validasi lokasi
        $office = $user->office;
        
        // Get shift times untuk ditampilkan
        $shiftTimes = $this->getShiftTimes($user->role);
        
        // Cek apakah hari ini hari kerja untuk karyawan
        $isWorkDay = true;
        $isHoliday = false;
        $holidayName = null;
        
        if (in_array($user->role, ['karyawan_pusat', 'karyawan_cabang'])) {
            $today = Carbon::now('Asia/Jakarta');
            $dayOfWeek = $today->dayOfWeek; // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu
            
            // Cek apakah hari libur
            if (Holiday::isHoliday($today)) {
                $holiday = Holiday::getByDate($today);
                $isHoliday = true;
                $holidayName = $holiday->name;
                $isWorkDay = false;
            } else {
                $isWorkDay = $dayOfWeek >= 1 && $dayOfWeek <= 5; // Senin-Jumat
            }
        }
        
        return view('attendance.index', compact('todayAttendance', 'attendances', 'office', 'shiftTimes', 'isWorkDay', 'isHoliday', 'holidayName'));
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        
        // Admin tidak bisa check-in
        if ($user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Admin tidak dapat melakukan absensi.'
            ], 403);
        }

        // Validasi user punya office
        if (!$user->office_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum terdaftar di kantor manapun. Silakan hubungi admin.'
            ], 400);
        }

        $today = Carbon::now('Asia/Jakarta');
        
        // Cek apakah hari ini hari libur untuk role user
        if (Holiday::isHoliday($today, $user->role)) {
            $holiday = Holiday::getByDate($today, $user->role);
            return response()->json([
                'success' => false,
                'message' => "Hari ini adalah hari libur: {$holiday->name}. Anda tidak dapat absen pada hari libur."
            ], 400);
        }
        
        // Validasi hari kerja untuk karyawan pusat dan cabang (hanya Senin-Jumat)
        if (in_array($user->role, ['karyawan_pusat', 'karyawan_cabang'])) {
            $dayOfWeek = $today->dayOfWeek; // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu
            // Cek apakah hari ini Sabtu atau Minggu
            if ($dayOfWeek == 0 || $dayOfWeek == 6) { // Minggu atau Sabtu
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan hanya dapat absen pada hari kerja (Senin-Jumat).'
                ], 400);
            }
        }
        
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required|string',
        ]);

        $latitude = (float) $request->latitude;
        $longitude = (float) $request->longitude;
        $office = $user->office;

        // Validasi lokasi dalam radius
        if (!$office->isWithinRadius($latitude, $longitude)) {
            $distance = round($office->distanceFrom($latitude, $longitude), 2);
            return response()->json([
                'success' => false,
                'message' => "Anda berada di luar radius kantor. Jarak Anda: {$distance} km dari {$office->name}. Radius maksimal: {$office->radius_km} km."
            ], 400);
        }

        // Handle date untuk security (shift malam)
        $shiftTimes = $this->getShiftTimes($user->role);
        $checkInTime = Carbon::now('Asia/Jakarta');
        $today = Carbon::today('Asia/Jakarta');
        
        // Untuk security, jika check-in sebelum 07:00, berarti masih shift malam dari kemarin
        if ($user->role === 'security' && $checkInTime->hour < 7) {
            // Masih shift malam dari kemarin
            $today = Carbon::yesterday('Asia/Jakarta');
            $shiftCheckIn = Carbon::yesterday('Asia/Jakarta')->setTime(23, 0, 0)->setTimezone('Asia/Jakarta');
        } else {
            $shiftCheckIn = $shiftTimes['check_in'];
        }

        // Cek apakah sudah check-in hari ini
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($existingAttendance && $existingAttendance->check_in_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-in hari ini.'
            ], 400);
        }

        // Simpan foto dari base64
        $photo = $request->photo;
        $photo = str_replace('data:image/png;base64,', '', $photo);
        $photo = str_replace(' ', '+', $photo);
        $photoName = 'check-in-' . $user->id . '-' . time() . '.png';
        Storage::disk('public')->put('attendances/' . $photoName, base64_decode($photo));

        // Pastikan semua waktu menggunakan timezone WIB
        $checkInTime->setTimezone('Asia/Jakarta');
        $shiftCheckIn->setTimezone('Asia/Jakarta');
        
        // Tentukan status berdasarkan waktu check-in dan role
        // Maksimal telat 5 menit dari jam shift
        $maxLateTime = $shiftCheckIn->copy()->addMinutes(5);
        $status = $checkInTime->lte($maxLateTime) ? 'present' : 'late';

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'check_in_time' => $checkInTime,
            'check_in_latitude' => $request->latitude,
            'check_in_longitude' => $request->longitude,
            'check_in_photo' => $photoName,
            'date' => $today,
            'status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil!',
            'data' => $attendance
        ]);
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        
        // Admin tidak bisa check-out
        if ($user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Admin tidak dapat melakukan absensi.'
            ], 403);
        }
        
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required|string',
        ]);

        // Validasi user punya office
        if (!$user->office_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum terdaftar di kantor manapun. Silakan hubungi admin.'
            ], 400);
        }

        // Validasi hari kerja untuk karyawan pusat dan cabang (hanya Senin-Jumat)
        if (in_array($user->role, ['karyawan_pusat', 'karyawan_cabang'])) {
            $dayOfWeek = Carbon::now('Asia/Jakarta')->dayOfWeek; // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu
            if ($dayOfWeek == 0 || $dayOfWeek == 6) { // Minggu atau Sabtu
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan hanya dapat absen pada hari kerja (Senin-Jumat).'
                ], 400);
            }
        }

        $latitude = (float) $request->latitude;
        $longitude = (float) $request->longitude;
        $office = $user->office;

        // Validasi lokasi dalam radius
        if (!$office->isWithinRadius($latitude, $longitude)) {
            $distance = round($office->distanceFrom($latitude, $longitude), 2);
            return response()->json([
                'success' => false,
                'message' => "Anda berada di luar radius kantor. Jarak Anda: {$distance} km dari {$office->name}. Radius maksimal: {$office->radius_km} km."
            ], 400);
        }

        // Handle date untuk security (shift malam)
        $checkOutTime = Carbon::now('Asia/Jakarta');
        $today = Carbon::today('Asia/Jakarta');
        
        // Untuk security, jika check-out sebelum 07:00, berarti masih shift malam dari kemarin
        if ($user->role === 'security' && $checkOutTime->hour < 7) {
            // Masih shift malam dari kemarin
            $today = Carbon::yesterday('Asia/Jakarta');
        }

        $todayCheck = Carbon::now('Asia/Jakarta');
        
        // Cek apakah hari ini hari libur untuk role user
        if (Holiday::isHoliday($todayCheck, $user->role)) {
            $holiday = Holiday::getByDate($todayCheck, $user->role);
            return response()->json([
                'success' => false,
                'message' => "Hari ini adalah hari libur: {$holiday->name}. Anda tidak dapat absen pada hari libur."
            ], 400);
        }
        
        // Validasi hari kerja untuk karyawan pusat dan cabang (hanya Senin-Jumat)
        if (in_array($user->role, ['karyawan_pusat', 'karyawan_cabang'])) {
            $dayOfWeek = $todayCheck->dayOfWeek; // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu
            // Cek apakah hari ini Sabtu atau Minggu
            if ($dayOfWeek == 0 || $dayOfWeek == 6) { // Minggu atau Sabtu
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan hanya dapat absen pada hari kerja (Senin-Jumat).'
                ], 400);
            }
        }

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan check-in hari ini.'
            ], 400);
        }

        if ($attendance->check_out_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-out hari ini.'
            ], 400);
        }

        // Cek apakah pulang mendahului dan tentukan status
        $shiftTimes = $this->getShiftTimes($user->role);
        $minCheckOutTime = $shiftTimes['check_out'];
        
        // Untuk security, adjust min check-out time jika check-out sebelum 07:00
        if ($user->role === 'security' && $checkOutTime->hour < 7) {
            // Check-out sebelum 07:00, berarti min check-out adalah 07:00 hari ini
            $minCheckOutTime = $today->copy()->addDay()->setTime(7, 0, 0)->setTimezone('Asia/Jakarta');
        }
        
        // Tentukan status berdasarkan waktu check-out
        $isEarlyOut = $checkOutTime->lt($minCheckOutTime);
        
        // Jika pulang mendahului, wajib ada catatan
        if ($isEarlyOut && empty($request->note)) {
            $minTimeFormatted = $minCheckOutTime->format('H:i');
            return response()->json([
                'success' => false,
                'message' => "Anda pulang mendahului jam pulang ({$minTimeFormatted} WIB). Harap isi catatan/keterangan."
            ], 400);
        }
        
        // Update status jika pulang mendahului
        $status = $attendance->status; // Pertahankan status check-in (present/late)
        if ($isEarlyOut) {
            $status = 'early_out'; // Status baru untuk pulang mendahului
        }

        // Simpan foto dari base64
        $photo = $request->photo;
        $photo = str_replace('data:image/png;base64,', '', $photo);
        $photo = str_replace(' ', '+', $photo);
        $photoName = 'check-out-' . $user->id . '-' . time() . '.png';
        Storage::disk('public')->put('attendances/' . $photoName, base64_decode($photo));

        $attendance->update([
            'check_out_time' => Carbon::now('Asia/Jakarta'),
            'check_out_latitude' => $request->latitude,
            'check_out_longitude' => $request->longitude,
            'check_out_photo' => $photoName,
            'status' => $status,
            'notes' => $request->note ?: null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil!',
            'data' => $attendance
        ]);
    }

    /**
     * Get shift times based on user role
     */
    private function getShiftTimes(string $role): array
    {
        $today = Carbon::today('Asia/Jakarta');
        $dayOfWeek = $today->dayOfWeek; // 0 = Minggu, 1 = Senin, ..., 5 = Jumat, 6 = Sabtu
        
        return match($role) {
            'karyawan_pusat', 'karyawan_cabang' => [
                // Jumat: 07:00 - 16:00, Senin-Kamis: 08:00 - 17:00
                'check_in' => $dayOfWeek == 5 
                    ? $today->copy()->setTime(7, 0, 0)->setTimezone('Asia/Jakarta') // Jumat: 07:00
                    : $today->copy()->setTime(8, 0, 0)->setTimezone('Asia/Jakarta'), // Senin-Kamis: 08:00
                'check_out' => $dayOfWeek == 5 
                    ? $today->copy()->setTime(16, 0, 0)->setTimezone('Asia/Jakarta') // Jumat: 16:00
                    : $today->copy()->setTime(17, 0, 0)->setTimezone('Asia/Jakarta'), // Senin-Kamis: 17:00
            ],
            'call_center' => [
                'check_in' => $today->copy()->setTime(7, 0, 0)->setTimezone('Asia/Jakarta'), // 07:00 WIB
                'check_out' => $today->copy()->setTime(15, 0, 0)->setTimezone('Asia/Jakarta'), // 15:00 WIB
            ],
            'security' => [
                // Shift malam: 23:00 - 07:00 (hari berikutnya)
                'check_in' => $today->copy()->setTime(23, 0, 0)->setTimezone('Asia/Jakarta'), // 23:00 WIB
                'check_out' => $today->copy()->addDay()->setTime(7, 0, 0)->setTimezone('Asia/Jakarta'), // 07:00 WIB (hari berikutnya)
            ],
            default => [
                'check_in' => $today->copy()->setTime(8, 0, 0)->setTimezone('Asia/Jakarta'),
                'check_out' => $today->copy()->setTime(17, 0, 0)->setTimezone('Asia/Jakarta'),
            ],
        };
    }
}
