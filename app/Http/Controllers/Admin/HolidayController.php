<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::orderBy('date', 'desc')->get();
        return view('admin.holidays.index', compact('holidays'));
    }

    public function create()
    {
        return view('admin.holidays.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
            'role' => ['nullable', 'string', 'in:karyawan_pusat,karyawan_cabang,call_center,security'],
        ]);

        // Cek apakah sudah ada hari libur di tanggal yang sama untuk role yang sama
        $existingHoliday = Holiday::where('date', $request->date)
            ->where(function($query) use ($request) {
                if ($request->role) {
                    $query->where('role', $request->role)
                          ->orWhereNull('role');
                } else {
                    $query->whereNull('role');
                }
            })
            ->first();

        if ($existingHoliday) {
            return back()
                ->withInput()
                ->withErrors(['date' => 'Hari libur pada tanggal ini sudah ada untuk role yang dipilih.']);
        }

        Holiday::create([
            'name' => $request->name,
            'date' => $request->date,
            'description' => $request->description,
            'role' => $request->role ?: null, // null = untuk semua role
            'is_active' => true,
        ]);

        $roleText = $request->role ? ucfirst(str_replace('_', ' ', $request->role)) : 'Semua Role';
        return redirect()->route('admin.holidays.index')
            ->with('success', "Hari libur '{$request->name}' untuk {$roleText} berhasil ditambahkan!");
    }

    public function destroy(Holiday $holiday)
    {
        $name = $holiday->name;
        $holiday->delete();

        return redirect()->route('admin.holidays.index')
            ->with('success', "Hari libur '{$name}' berhasil dihapus!");
    }

    public function toggle(Holiday $holiday)
    {
        $holiday->is_active = !$holiday->is_active;
        $holiday->save();

        $status = $holiday->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.holidays.index')
            ->with('success', "Hari libur '{$holiday->name}' berhasil {$status}!");
    }
}
