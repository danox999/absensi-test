<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UpdateAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:fix-password {email?} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix admin password to use Bcrypt algorithm';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->option('password');

        if (!$email) {
            // Find first admin user
            $admin = User::where('role', 'admin')->first();
            
            if (!$admin) {
                $this->error('No admin user found!');
                return 1;
            }
            
            $email = $admin->email;
        } else {
            $admin = User::where('email', $email)->first();
            
            if (!$admin) {
                $this->error("User with email {$email} not found!");
                return 1;
            }
        }

        if (!$password) {
            $password = $this->secret('Enter new password (min 8 characters):');
            
            if (strlen($password) < 8) {
                $this->error('Password must be at least 8 characters!');
                return 1;
            }
        }

        // Update password dengan Bcrypt
        $admin->password = Hash::make($password);
        $admin->save();

        $this->info("✅ Password updated successfully for {$admin->name} ({$admin->email})");
        $this->info("Role: {$admin->role}");
        
        return 0;
    }
}
