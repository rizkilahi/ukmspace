<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UKM;
use Illuminate\Support\Facades\Storage;

class UKMController extends Controller
{
    /**
     * Display a listing of all UKMs (Admin).
     */
    public function index()
    {
        // Eager load counts to prevent N+1 queries
        $ukms = UKM::withCount(['events', 'users'])
            ->latest()
            ->paginate(15);

        return view('admin.ukms.index', compact('ukms'));
    }

    /**
     * Show the form for creating a new UKM (Admin).
     */
    public function create()
    {
        return view('admin.ukms.create');
    }

    /**
     * Store a newly created UKM (Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'required|email|unique:ukms,email',
            'logo' => 'nullable|image|max:2048',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'website' => 'nullable|url|max:255',
            'established_date' => 'nullable|date',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $ukm = UKM::create($validated);
        $ukm->password = bcrypt('password'); // Default password
        $ukm->verification_status = $request->input('verification_status', 'active');
        $ukm->save();

        return redirect()->route('admin.ukms.index')->with('success', 'UKM created successfully.');
    }

    /**
     * Display the specified UKM (Admin).
     */
    public function show(UKM $ukm)
    {
        $ukm->load('events', 'users');
        return view('admin.ukms.show', compact('ukm'));
    }

    /**
     * Show the form for editing the specified UKM (Admin).
     */
    public function edit(UKM $ukm)
    {
        return view('admin.ukms.edit', compact('ukm'));
    }

    /**
     * Update the specified UKM (Admin).
     */
    public function update(Request $request, UKM $ukm)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'required|email|unique:ukms,email,' . $ukm->id,
            'logo' => 'nullable|image|max:2048',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'website' => 'nullable|url|max:255',
            'established_date' => 'nullable|date',
            'verification_status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($ukm->logo) {
                Storage::disk('public')->delete($ukm->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // Update base fields
        $ukm->update($validated);

        // Update verification status separately (guarded field)
        if ($request->filled('verification_status')) {
            $ukm->verification_status = $request->input('verification_status');
            $ukm->save();
        }

        return redirect()->route('admin.ukms.index')->with('success', 'UKM updated successfully.');
    }

    /**
     * Remove the specified UKM (Admin).
     */
    public function destroy(UKM $ukm)
    {
        // Delete logo if exists
        if ($ukm->logo) {
            Storage::disk('public')->delete($ukm->logo);
        }

        $ukm->delete();

        return redirect()->route('admin.ukms.index')->with('success', 'UKM deleted successfully.');
    }
}
