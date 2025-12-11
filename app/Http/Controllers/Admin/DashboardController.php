<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Office;
use App\Exports\AttendancesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
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

        // Statistik Bulan Ini
        $monthStats = [
            'total_attendances' => Attendance::whereMonth('date', Carbon::now('Asia/Jakarta')->month)
                ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
                ->count(),
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'on_time_percentage' => $this->calculateOnTimePercentage(),
        ];

        // Statistik per Kantor
        $offices = Office::where('is_active', true)->get();
        $officeStats = [];
        foreach ($offices as $office) {
            $officeUsers = User::where('office_id', $office->id)->where('role', '!=', 'admin')->pluck('id');
            $officeStats[$office->id] = [
                'name' => $office->name,
                'code' => $office->code,
                'total_users' => $officeUsers->count(),
                'today_checkin' => Attendance::whereDate('date', $today)
                    ->whereIn('user_id', $officeUsers)
                    ->whereNotNull('check_in_time')
                    ->count(),
                'today_checkout' => Attendance::whereDate('date', $today)
                    ->whereIn('user_id', $officeUsers)
                    ->whereNotNull('check_out_time')
                    ->count(),
                'month_attendances' => Attendance::whereMonth('date', Carbon::now('Asia/Jakarta')->month)
                    ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
                    ->whereIn('user_id', $officeUsers)
                    ->count(),
                'month_on_time' => Attendance::whereMonth('date', Carbon::now('Asia/Jakarta')->month)
                    ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
                    ->whereIn('user_id', $officeUsers)
                    ->where('status', 'present')
                    ->count(),
                'month_late' => Attendance::whereMonth('date', Carbon::now('Asia/Jakarta')->month)
                    ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
                    ->whereIn('user_id', $officeUsers)
                    ->where('status', 'late')
                    ->count(),
            ];
        }

        // Statistik per Role
        $roles = ['karyawan_pusat', 'karyawan_cabang', 'call_center', 'security'];
        $roleStats = [];
        foreach ($roles as $role) {
            $roleUsers = User::where('role', $role)->pluck('id');
            $totalRoleUsers = $roleUsers->count();
            $roleStats[$role] = [
                'display_name' => ucfirst(str_replace('_', ' ', $role)),
                'total_users' => $totalRoleUsers,
                'today_checkin' => Attendance::whereDate('date', $today)
                    ->whereIn('user_id', $roleUsers)
                    ->whereNotNull('check_in_time')
                    ->count(),
                'today_checkout' => Attendance::whereDate('date', $today)
                    ->whereIn('user_id', $roleUsers)
                    ->whereNotNull('check_out_time')
                    ->count(),
                'month_attendances' => Attendance::whereMonth('date', Carbon::now('Asia/Jakarta')->month)
                    ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
                    ->whereIn('user_id', $roleUsers)
                    ->count(),
                'month_on_time' => Attendance::whereMonth('date', Carbon::now('Asia/Jakarta')->month)
                    ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
                    ->whereIn('user_id', $roleUsers)
                    ->where('status', 'present')
                    ->count(),
                'month_late' => Attendance::whereMonth('date', Carbon::now('Asia/Jakarta')->month)
                    ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
                    ->whereIn('user_id', $roleUsers)
                    ->where('status', 'late')
                    ->count(),
                'month_early_out' => Attendance::whereMonth('date', Carbon::now('Asia/Jakarta')->month)
                    ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
                    ->whereIn('user_id', $roleUsers)
                    ->where('status', 'early_out')
                    ->count(),
            ];
        }

        // User yang belum check-in hari ini
        $allUsers = User::where('role', '!=', 'admin')->get();
        $checkedInUserIds = Attendance::whereDate('date', $today)
            ->whereNotNull('check_in_time')
            ->pluck('user_id')
            ->toArray();
        $usersNotCheckedIn = $allUsers->filter(function($user) use ($checkedInUserIds) {
            return !in_array($user->id, $checkedInUserIds);
        })->take(10);

        // User yang sudah check-in tapi belum check-out
        $checkedInButNotOut = Attendance::with('user')
            ->whereDate('date', $today)
            ->whereNotNull('check_in_time')
            ->whereNull('check_out_time')
            ->orderBy('check_in_time', 'desc')
            ->get();

        // Absensi Hari Ini (Latest)
        $todayAttendances = Attendance::with('user')
            ->whereDate('date', $today)
            ->orderBy('check_in_time', 'desc')
            ->limit(10)
            ->get();

        // Grafik Absensi 7 Hari Terakhir
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

        // Top 5 Users dengan Absensi Terbanyak Bulan Ini
        $topUsers = Attendance::select('user_id', DB::raw('count(*) as total'))
            ->whereMonth('date', Carbon::now('Asia/Jakarta')->month)
            ->whereYear('date', Carbon::now('Asia/Jakarta')->year)
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->with('user')
            ->get();

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

        return view('admin.dashboard', compact(
            'todayStats',
            'monthStats',
            'officeStats',
            'roleStats',
            'usersNotCheckedIn',
            'checkedInButNotOut',
            'todayAttendances',
            'last7Days',
            'topUsers',
            'statusStats'
        ));
    }

    public function userAttendances(Request $request)
    {
        $query = User::where('role', '!=', 'admin')
            ->with(['office', 'attendances' => function($q) {
                $q->orderBy('date', 'desc')->limit(30);
            }]);

        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        // Filter by office
        if ($request->has('office_id') && $request->office_id) {
            $query->where('office_id', $request->office_id);
        }

        $users = $query->orderBy('name')->paginate(15);

        // Get offices for filter
        $offices = \App\Models\Office::where('is_active', true)->get();

        // Get attendance stats for each user
        $userStats = [];
        foreach ($users as $user) {
            $thisMonth = Carbon::now()->startOfMonth();
            $userStats[$user->id] = [
                'total_this_month' => $user->attendances()
                    ->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year)
                    ->count(),
                'present' => $user->attendances()
                    ->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year)
                    ->where('status', 'present')
                    ->count(),
                'late' => $user->attendances()
                    ->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year)
                    ->where('status', 'late')
                    ->count(),
                'early_out' => $user->attendances()
                    ->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year)
                    ->where('status', 'early_out')
                    ->count(),
            ];
        }

        return view('admin.user-attendances', compact('users', 'offices', 'userStats'));
    }

    public function exportAttendances(Request $request)
    {
        $query = Attendance::with(['user.office']);

        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        // Filter by office
        if ($request->has('office_id') && $request->office_id) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('office_id', $request->office_id);
            });
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Default: bulan ini jika tidak ada filter tanggal
        if (!$request->has('start_date') && !$request->has('end_date')) {
            $query->whereMonth('date', Carbon::now()->month)
                  ->whereYear('date', Carbon::now()->year);
        }

        $attendances = $query->orderBy('date', 'desc')
                            ->orderBy('check_in_time', 'desc')
                            ->get();

        $fileName = 'absensi_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new AttendancesExport($attendances), $fileName);
    }

    private function calculateOnTimePercentage()
    {
        $total = Attendance::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->count();
        
        if ($total == 0) return 0;
        
        $onTime = Attendance::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->where('status', 'present')
            ->count();
        
        return round(($onTime / $total) * 100, 1);
    }
}
