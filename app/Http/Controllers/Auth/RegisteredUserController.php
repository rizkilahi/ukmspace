<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UKM;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi awal untuk role
        $request->validate([
            'role' => ['required', 'in:user,ukm'],
        ]);

        if ($request->role === 'user') {
            // Validasi untuk User
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['required', 'string', 'max:15'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Buat User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);
            
        } elseif ($request->role === 'ukm') {
            // Validasi untuk UKM
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:ukms'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'phone' => ['required', 'string', 'max:15'],
                'address' => ['required', 'string', 'max:255'],
                'website' => ['nullable', 'string', 'url', 'max:255'],
            ]);

            // Buat UKM
            $ukm = UKM::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'verification_status' => 'active', // Default status UKM
            ]);

            // Buat User dan hubungkan ke UKM
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'ukm',
                'ukm_id' => $ukm->id,
            ]);
        }

        // Event Registrasi
        event(new Registered($user));

        // Login Otomatis
        Auth::login($user);

        // Redirect ke dashboard
        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }
}
