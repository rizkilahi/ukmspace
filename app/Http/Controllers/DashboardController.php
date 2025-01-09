<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth; // Pastikan namespace Auth digunakan
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil role pengguna yang sedang login
        $user = Auth::user(); // atau gunakan auth()->user();
        $role = $user->role;

        if ($role === 'user') {
            return view('dashboard.user');
        } elseif ($role === 'ukm') {
            return view('dashboard.ukm');
        } else {
            // Jika ada role lain, kembalikan view default atau redirect
            return redirect('/dashboard');
        }
    }
}
