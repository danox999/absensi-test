<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function create()
    {
        $offices = Office::where('is_active', true)->get();
        return view('admin.users.create', compact('offices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:karyawan_pusat,karyawan_cabang,call_center,security'],
            'office_id' => ['required', 'exists:offices,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'office_id' => $request->office_id,
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', "User {$user->name} berhasil ditambahkan!");
    }
}
