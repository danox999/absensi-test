<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Office;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Jika admin, tampilkan chart dan statistik admin
        if (Auth::user()->isAdmin()) {
            $today = Carbon::today('Asia/Jakarta');
            $thisMonth = Carbon::now('Asia/Jakarta')->startOfMonth();
            
            // Statistik Hari Ini
            $todayStats = [
                'total_checkin' => Attendance::whereDate('date', $today)->whereNotNull('check_in_time')->count(),
                'total_checkout' => Attendance::whereDate('date', $today)->whereNotNull('check_out_time')->count(),
                'on_time' => Attendance::whereDate('date', $today)->where('status', 'present')->count(),
                'late' => Attendance::whereDate('date', $today)->where('status', 'late')->count(),
                'early_out' => Attendance::whereDate('date', $today)->where('status', 'early_out')->count(),
            ];

            // Grafik 7 Hari Terakhir
            $last7Days = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today('Asia/Jakarta')->subDays($i);
                $last7Days[] = [
                    'date' => $date->format('d M'),
                    'day' => $date->format('D'),
                    'checkin' => Attendance::whereDate('date', $date)->whereNotNull('check_in_time')->count(),
                    'checkout' => Attendance::whereDate('date', $date)->whereNotNull('check_out_time')->count(),
                ];
            }

            // Statistik per Status
            $statusStats = [
                'present' => Attendance::whereMonth('date', Carbon::now('Asia/Jakarta')->month)
                    ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
                    ->where('status', 'present')
                    ->count(),
                'late' => Attendance::whereMonth('date', Carbon::now('Asia/Jakarta')->month)
                    ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
                    ->where('status', 'late')
                    ->count(),
                'early_out' => Attendance::whereMonth('date', Carbon::now('Asia/Jakarta')->month)
                    ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
                    ->where('status', 'early_out')
                    ->count(),
            ];

            // Statistik per Kantor
            $offices = Office::where('is_active', true)->get();
            $officeStats = [];
            foreach ($offices as $office) {
                $officeUsers = User::where('office_id', $office->id)->where('role', '!=', 'admin')->pluck('id');
                $officeStats[$office->id] = [
                    'name' => $office->name,
                    'code' => $office->code,
                    'today_checkin' => Attendance::whereDate('date', $today)
                        ->whereIn('user_id', $officeUsers)
                        ->whereNotNull('check_in_time')
                        ->count(),
                    'today_checkout' => Attendance::whereDate('date', $today)
                        ->whereIn('user_id', $officeUsers)
                        ->whereNotNull('check_out_time')
                        ->count(),
                ];
            }

            // Statistik per Role
            $roles = ['karyawan_pusat', 'karyawan_cabang', 'call_center', 'security'];
            $roleStats = [];
            foreach ($roles as $role) {
                $roleUsers = User::where('role', $role)->pluck('id');
                $roleStats[$role] = [
                    'display_name' => ucfirst(str_replace('_', ' ', $role)),
                    'today_checkin' => Attendance::whereDate('date', $today)
                        ->whereIn('user_id', $roleUsers)
                        ->whereNotNull('check_in_time')
                        ->count(),
                    'today_checkout' => Attendance::whereDate('date', $today)
                        ->whereIn('user_id', $roleUsers)
                        ->whereNotNull('check_out_time')
                        ->count(),
                ];
            }

            return view('dashboard', compact(
                'todayStats',
                'last7Days',
                'statusStats',
                'officeStats',
                'roleStats'
            ));
        }

        $user = Auth::user();
        $today = Carbon::today('Asia/Jakarta');
        $thisMonth = Carbon::now('Asia/Jakarta')->startOfMonth();
        $now = Carbon::now('Asia/Jakarta');

        // Statistik Hari Ini
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        // Grafik 7 Hari Terakhir
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today('Asia/Jakarta')->subDays($i);
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->first();
            
            $last7Days[] = [
                'date' => $date->format('d M'),
                'day' => $date->format('D'),
                'full_date' => $date->format('Y-m-d'),
                'has_attendance' => $attendance ? 1 : 0,
                'status' => $attendance ? $attendance->status : null,
                'check_in' => $attendance && $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : null,
                'check_out' => $attendance && $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : null,
            ];
        }

        // Grafik Bulan Ini (per hari)
        $daysInMonth = $now->daysInMonth;
        $monthData = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($now->year, $now->month, $day, 0, 0, 0, 'Asia/Jakarta');
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->first();
            
            $monthData[] = [
                'day' => $day,
                'date' => $date->format('d M'),
                'day_name' => $date->format('D'),
                'has_attendance' => $attendance ? 1 : 0,
                'status' => $attendance ? $attendance->status : null,
            ];
        }

        // Statistik Status Bulan Ini
        $statusStats = [
            'present' => Attendance::where('user_id', $user->id)
                ->whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->where('status', 'present')
                ->count(),
            'late' => Attendance::where('user_id', $user->id)
                ->whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->where('status', 'late')
                ->count(),
            'early_out' => Attendance::where('user_id', $user->id)
                ->whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->where('status', 'early_out')
                ->count(),
        ];

        // Grafik Per Minggu dalam Bulan Ini
        $weeksInMonth = [];
        $startOfMonth = $thisMonth->copy();
        $endOfMonth = $now->copy()->endOfMonth();
        
        $currentWeek = 1;
        $weekStart = $startOfMonth->copy();
        
        while ($weekStart->lte($endOfMonth)) {
            $weekEnd = $weekStart->copy()->endOfWeek();
            if ($weekEnd->gt($endOfMonth)) {
                $weekEnd = $endOfMonth->copy();
            }
            
            $weekAttendances = Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
                ->count();
            
            $weeksInMonth[] = [
                'week' => 'Minggu ' . $currentWeek,
                'start' => $weekStart->format('d M'),
                'end' => $weekEnd->format('d M'),
                'count' => $weekAttendances,
            ];
            
            $weekStart = $weekEnd->copy()->addDay();
            $currentWeek++;
        }

        // Statistik Ringkas
        $summaryStats = [
            'total_this_month' => Attendance::where('user_id', $user->id)
                ->whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->count(),
            'on_time_percentage' => $this->calculateOnTimePercentage($user->id, $now),
            'total_days' => $daysInMonth,
            'attendance_rate' => $daysInMonth > 0 ? round((Attendance::where('user_id', $user->id)
                ->whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->count() / $daysInMonth) * 100, 1) : 0,
            'month_name' => $now->format('F Y'),
        ];

        return view('dashboard', compact(
            'todayAttendance',
            'last7Days',
            'monthData',
            'statusStats',
            'weeksInMonth',
            'summaryStats'
        ));
    }

    private function calculateOnTimePercentage($userId, $now)
    {
        $total = Attendance::where('user_id', $userId)
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->count();
        
        if ($total == 0) return 0;
        
        $onTime = Attendance::where('user_id', $userId)
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->where('status', 'present')
            ->count();
        
        return round(($onTime / $total) * 100, 1);
    }
}

