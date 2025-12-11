<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Holiday extends Model
{
    protected $fillable = [
        'name',
        'date',
        'description',
        'role', // null = semua role, atau spesifik: karyawan_pusat, karyawan_cabang, call_center, security
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Check if a date is a holiday for specific role
     */
    public static function isHoliday($date, $role = null): bool
    {
        if ($date instanceof Carbon) {
            $date = $date->format('Y-m-d');
        } elseif (is_string($date)) {
            // Already a string
        } else {
            $date = Carbon::parse($date)->format('Y-m-d');
        }

        $query = self::where('date', $date)
            ->where('is_active', true);

        if ($role) {
            // Cek hari libur untuk role spesifik atau untuk semua role (role = null)
            $query->where(function($q) use ($role) {
                $q->whereNull('role')
                  ->orWhere('role', $role);
            });
        } else {
            // Jika tidak ada role, cek hari libur untuk semua
            $query->whereNull('role');
        }

        return $query->exists();
    }

    /**
     * Get holiday by date and role
     */
    public static function getByDate($date, $role = null)
    {
        if ($date instanceof Carbon) {
            $date = $date->format('Y-m-d');
        } elseif (is_string($date)) {
            // Already a string
        } else {
            $date = Carbon::parse($date)->format('Y-m-d');
        }

        $query = self::where('date', $date)
            ->where('is_active', true);

        if ($role) {
            // Cek hari libur untuk role spesifik atau untuk semua role (role = null)
            $query->where(function($q) use ($role) {
                $q->whereNull('role')
                  ->orWhere('role', $role);
            });
        } else {
            // Jika tidak ada role, cek hari libur untuk semua
            $query->whereNull('role');
        }

        return $query->first();
    }
}
