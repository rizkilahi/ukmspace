<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UKM;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UKMController extends Controller
{

    /**
     * Display the list of all UKMs.
     */
    public function indexEvent()
    {
        // Select only needed columns for better performance
        $ukms = UKM::select('id', 'name', 'description', 'logo', 'verification_status')
            ->where('verification_status', 'active')
            ->paginate(10);

        return view('user.ukms', compact('ukms'));
    }

    /**
     * Display the profile of the authenticated UKM.
     */
    public function index()
    {
        $ukm = Auth::user()->ukm;

        abort_if(!$ukm, 404, 'You do not have a UKM profile.');

        return view('ukms.profile', compact('ukm'));
    }

    /**
     * Show the form for editing the authenticated UKM profile.
     */
    public function edit()
    {
        $ukm = Auth::user()->ukm;

        abort_if(!$ukm, 404, 'You do not have a UKM profile.');

        return view('ukms.edit', compact('ukm'));
    }

    /**
     * Update the authenticated UKM profile.
     */
    public function update(Request $request)
    {
        $ukm = Auth::user()->ukm;

        abort_if(!$ukm, 404, 'You do not have a UKM profile.');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'website' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo to save storage space
            if ($ukm->logo) {
                Storage::disk('public')->delete($ukm->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $ukm->update($validated);

        return redirect()->route('ukms.profile')->with('success', 'UKM profile updated successfully.');
    }

    public function show($id)
    {
        // Find UKM or fail with 404
        $ukm = UKM::select('id', 'name', 'description', 'logo', 'email', 'phone', 'website', 'address')
            ->findOrFail($id);

        // Paginate events separately for better performance
        $events = Event::where('ukm_id', $id)
            ->select('id', 'title', 'description', 'event_date', 'location', 'image_url', 'ukm_id')
            ->latest()
            ->paginate(10);

        return view('user.ukmevents', compact('ukm', 'events'));
    }
}
