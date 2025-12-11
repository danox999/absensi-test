<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kantor Pusat (contoh koordinat Jakarta)
        Office::create([
            'name' => 'Kantor Pusat',
            'code' => 'pusat',
            'latitude' => -6.2088, // Contoh: Jakarta
            'longitude' => 106.8456,
            'radius_km' => 5,
            'address' => 'Jl. Contoh No. 123, Jakarta Pusat',
            'is_active' => true,
        ]);

        // Kantor Cabang (contoh koordinat Bandung)
        Office::create([
            'name' => 'Kantor Cabang',
            'code' => 'cabang',
            'latitude' => -6.9175, // Contoh: Bandung
            'longitude' => 107.6191,
            'radius_km' => 5,
            'address' => 'Jl. Contoh No. 456, Bandung',
            'is_active' => true,
        ]);
    }
}
