<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Menampilkan daftar event berdasarkan role (user atau UKM).
     */
    public function index()
    {
        // Eager load UKM to prevent N+1 queries
        $events = Event::with('ukm:id,name,logo')
            ->latest()
            ->paginate(10);

        // Determine view based on user role
        $view = 'user.events.index';
        if (Auth::check() && Auth::user()->role === 'ukm') {
            $view = 'ukms.events.index';
        }

        return view($view, compact('events'));
    }


    /**
     * Fitur daftar event untuk role user.
     */
    public function register(Request $request, Event $event)
    {
        $user = $request->user();

        // Use firstOrCreate to check and create in one query
        $registration = EventRegistration::firstOrCreate(
            [
                'event_id' => $event->id,
                'user_id' => $user->id,
            ],
            ['status' => 'pending']
        );

        if (!$registration->wasRecentlyCreated) {
            return back()->with('error', 'You have already registered for this event.');
        }

        return back()->with('success', 'Successfully registered for the event.');
    }

    /**
     * Menampilkan halaman untuk mengelola event (role UKM).
     */
    public function manage()
    {
        // Eager load registrations count for better performance
        $events = Event::where('ukm_id', Auth::user()->ukm_id)
            ->withCount('registrations')
            ->latest()
            ->paginate(10);

        return view('ukms.events.manage', compact('events'));
    }

    /**
     * Form untuk membuat event baru (role UKM).
     */
    public function create()
    {
        return view('ukms.events.create');
    }

    /**
     * Menyimpan event baru (role UKM).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'image_url' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image_url')) {
            $validated['image_url'] = $request->file('image_url')->store('events', 'public');
        }

        $validated['ukm_id'] = Auth::user()->ukm_id;

        Event::create($validated);

        return redirect()->route('ukm.events.index')->with('success', 'Event created successfully.');
    }

    /**
     * Menampilkan detail event.
     */
    public function show(Event $event)
    {
        // Eager load relationships to prevent N+1 queries
        $event->load('ukm:id,name,logo,email,phone');

        return view('show', compact('event'));
    }

    /**
     * Form untuk mengedit event (role UKM).
     */
    public function edit(Event $event)
    {
        // Pastikan event tersebut milik UKM yang sedang login
        if ($event->ukm_id !== Auth::user()->ukm_id) {
            return redirect()->route('ukm.events.index')->with('error', 'You are not authorized to edit this event.');
        }

        return view('ukms.events.edit', compact('event'));
    }


    /**
     * Memperbarui event (role UKM).
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
        ]);

        // Jika ada file baru yang diupload
        if ($request->hasFile('image')) {
            // Hapus file lama jika ada
            if ($event->image_url) {
                Storage::delete('public/' . $event->image_url);
            }

            // Simpan file baru
            $validated['image_url'] = $request->file('image')->store('events', 'public');
        }

        // Update event
        $event->update($validated);

        return redirect()->route('ukm.events.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Menghapus event (role UKM).
     */
    public function destroy(Event $event)
    {
        if ($event->image_url) {
            Storage::delete('public/' . $event->image_url);
        }

        $event->delete();

        return redirect()->route('ukm.events.index')->with('success', 'Event deleted successfully.');
    }
}
