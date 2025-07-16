<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UKM;
use Illuminate\Support\Facades\Auth;

class UKMController extends Controller
{

    /**
     * Display the list of all UKMs.
     */
    public function indexEvent()
    {
        // Ambil semua data UKM dengan pagination
        $ukms = UKM::paginate(10);

        // Tampilkan view untuk semua UKM
        return view('user.ukms', compact('ukms'));
    }

    /**
     * Display the profile of the authenticated UKM.
     */
    public function index()
    {
        $ukm = Auth::user()->ukm; // Gunakan Auth::user() untuk mengambil UKM

        if (!$ukm) {
            return redirect()->route('dashboard')->with('error', 'You do not have a UKM profile.');
        }

        return view('ukms.profile', compact('ukm'));
    }

    /**
     * Show the form for editing the authenticated UKM profile.
     */
    public function edit()
    {
        $ukm = Auth::user()->ukm; // Gunakan Auth::user()

        if (!$ukm) {
            return redirect()->route('dashboard')->with('error', 'You do not have a UKM profile.');
        }

        return view('ukms.edit', compact('ukm'));
    }

    /**
     * Update the authenticated UKM profile.
     */
    public function update(Request $request)
    {
        $ukm = Auth::user()->ukm; // Gunakan Auth::user()

        if (!$ukm) {
            return redirect()->route('dashboard')->with('error', 'You do not have a UKM profile.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'website' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $ukm->update($validated);

        return redirect()->route('ukms.profile')->with('success', 'UKM profile updated successfully.');
    }

    public function show($id)
    {
        // Temukan UKM berdasarkan ID
        $ukm = UKM::with('events')->findOrFail($id);

        // Ambil semua event dari UKM
        $events = $ukm->events()->paginate(10);

        // Kirim data ke view
        return view('user.ukmevents', compact('ukm', 'events'));
    }
}
