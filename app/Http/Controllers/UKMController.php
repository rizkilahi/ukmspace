<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UKM;

class UKMController extends Controller
{
    /**
     * Display a listing of the UKMs.
     */
    public function index()
    {
        $ukms = UKM::latest()->paginate(7);
        return view('ukms.index', compact('ukms'));
    }

    /**
     * Show the form for creating a new UKM.
     */
    public function create()
    {
        return view('ukms.create');
    }

    /**
     * Store a newly created UKM in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'required|email|unique:ukms',
            'password' => 'required|string|min:8',
            'logo' => 'nullable|image',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['verification_status'] = 'inactive';

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        UKM::create($validated);

        return redirect()->route('ukms.index')->with('success', 'UKM created successfully.');
    }

    /**
     * Display the specified UKM.
     */
    public function show(UKM $ukm)
    {
        return view('ukms.show', compact('ukm'));
    }

    /**
     * Show the form for editing the specified UKM.
     */
    public function edit(UKM $ukm)
    {
        return view('ukms.edit', compact('ukm'));
    }

    /**
     * Update the specified UKM in storage.
     */
    public function update(Request $request, UKM $ukm)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $ukm->update($validated);

        return redirect()->route('ukms.index')->with('success', 'UKM updated successfully.');
    }

    /**
     * Remove the specified UKM from storage.
     */
    public function destroy(UKM $ukm)
    {
        $ukm->delete();

        return redirect()->route('ukms.index')->with('success', 'UKM deleted successfully.');
    }
}
