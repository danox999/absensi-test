<?php

namespace App\Console\Commands;

use App\Models\Office;
use App\Models\User;
use Illuminate\Console\Command;

class AssignUserToOffice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-office {email} {office} {role?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign user to office and set role (karyawan_pusat, karyawan_cabang, call_center, security)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $officeCode = $this->argument('office');
        $role = $this->argument('role');

        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User dengan email {$email} tidak ditemukan!");
            return 1;
        }

        $office = Office::where('code', $officeCode)->first();
        
        if (!$office) {
            $this->error("Kantor dengan code '{$officeCode}' tidak ditemukan! (pusat/cabang)");
            return 1;
        }

        // Set role jika diberikan
        if ($role) {
            $validRoles = ['karyawan_pusat', 'karyawan_cabang', 'call_center', 'security'];
            if (!in_array($role, $validRoles)) {
                $this->error("Role tidak valid! Pilih: " . implode(', ', $validRoles));
                return 1;
            }
            $user->role = $role;
        }

        $user->office_id = $office->id;
        $user->save();

        $this->info("✅ User {$user->name} ({$user->email}) berhasil di-assign ke {$office->name}");
        $this->info("   Role: {$user->role}");
        $this->info("   Office: {$office->name} (Radius: {$office->radius_km} km)");
        
        return 0;
    }
}
