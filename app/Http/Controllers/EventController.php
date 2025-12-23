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
    public function index(Request $request)
    {
        // Build query with filters
        $query = Event::with('ukm:id,name,logo');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // UKM filter
        if ($request->filled('ukm_id')) {
            $query->where('ukm_id', $request->input('ukm_id'));
        }

        // Date filter
        if ($request->filled('date_from')) {
            $query->where('event_date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('event_date', '<=', $request->input('date_to'));
        }

        // Sort
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'date_asc':
                $query->orderBy('event_date', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('event_date', 'desc');
                break;
            default:
                $query->latest();
        }

        $events = $query->paginate(12)->withQueryString();
        $ukms = \App\Models\UKM::where('verification_status', 'active')
                    ->select('id', 'name')
                    ->orderBy('name')
                    ->get();

        // Determine view based on user role
        $view = 'user.events.index';
        if (Auth::check() && Auth::user()->role === 'ukm') {
            $view = 'ukms.events.index';
        }

        return view($view, compact('events', 'ukms'));
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

    /**
     * View registrations for an event (UKM role).
     */
    public function registrations(Event $event)
    {
        // Ensure the event belongs to the logged-in UKM
        abort_if($event->ukm_id !== Auth::user()->ukm_id, 403, 'Unauthorized access.');

        // Get registrations with user details
        $registrations = $event->registrations()
            ->with('user:id,name,email,phone')
            ->latest()
            ->paginate(15);

        return view('ukms.events.registrations', compact('event', 'registrations'));
    }

    /**
     * Update registration status (approve/reject).
     */
    public function updateRegistrationStatus(Request $request, EventRegistration $registration)
    {
        // Ensure the event belongs to the logged-in UKM
        $event = $registration->event;
        abort_if($event->ukm_id !== Auth::user()->ukm_id, 403, 'Unauthorized access.');

        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected,pending'
        ]);

        // Store old status before update
        $oldStatus = $registration->status;

        $registration->update($validated);

        // Send email notification if status changed
        if ($oldStatus !== $validated['status']) {
            $registration->user->notify(new \App\Notifications\RegistrationStatusChanged(
                $registration,
                $oldStatus,
                $validated['status']
            ));
        }

        $statusText = ucfirst($validated['status']);
        return back()->with('success', "Registration {$statusText} successfully. Email notification sent to user.");
    }

    /**
     * Export event registrations to CSV
     */
    public function exportRegistrations(Event $event)
    {
        // Ensure the event belongs to the logged-in UKM
        abort_if($event->ukm_id !== Auth::user()->ukm_id, 403, 'Unauthorized access.');

        $registrations = $event->registrations()
            ->with('user:id,name,email,phone')
            ->get();

        $filename = 'registrations_' . \Str::slug($event->title) . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($registrations) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($file, ['No', 'Name', 'Email', 'Phone', 'Status', 'Registration Date']);

            // Data rows
            foreach ($registrations as $index => $registration) {
                fputcsv($file, [
                    $index + 1,
                    $registration->user->name,
                    $registration->user->email,
                    $registration->user->phone ?? '-',
                    ucfirst($registration->status),
                    $registration->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display analytics dashboard for UKM
     */
    public function analytics()
    {
        $ukmId = Auth::user()->ukm_id;

        // Total events
        $totalEvents = Event::where('ukm_id', $ukmId)->count();

        // Events by status
        $upcomingEvents = Event::where('ukm_id', $ukmId)
            ->where('event_date', '>', now())
            ->count();
        $ongoingEvents = Event::where('ukm_id', $ukmId)
            ->whereDate('event_date', today())
            ->count();
        $completedEvents = Event::where('ukm_id', $ukmId)
            ->where('event_date', '<', today())
            ->count();

        // Total registrations
        $totalRegistrations = EventRegistration::whereHas('event', function($query) use ($ukmId) {
            $query->where('ukm_id', $ukmId);
        })->count();

        // Registrations by status
        $registrationStats = EventRegistration::whereHas('event', function($query) use ($ukmId) {
            $query->where('ukm_id', $ukmId);
        })
        ->selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status')
        ->toArray();

        $pendingCount = $registrationStats['pending'] ?? 0;
        $acceptedCount = $registrationStats['accepted'] ?? 0;
        $rejectedCount = $registrationStats['rejected'] ?? 0;

        // Monthly registration trend (last 6 months)
        $monthlyData = EventRegistration::whereHas('event', function($query) use ($ukmId) {
            $query->where('ukm_id', $ukmId);
        })
        ->where('created_at', '>=', now()->subMonths(6))
        ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Top events by registrations
        $topEvents = Event::where('ukm_id', $ukmId)
            ->withCount('registrations')
            ->orderBy('registrations_count', 'desc')
            ->take(5)
            ->get();

        // Recent registrations
        $recentRegistrations = EventRegistration::whereHas('event', function($query) use ($ukmId) {
            $query->where('ukm_id', $ukmId);
        })
        ->with(['user:id,name,email', 'event:id,title'])
        ->latest()
        ->take(10)
        ->get();

        // Approval rate
        $approvalRate = $totalRegistrations > 0
            ? round(($acceptedCount / $totalRegistrations) * 100, 1)
            : 0;

        return view('ukms.analytics', compact(
            'totalEvents',
            'upcomingEvents',
            'ongoingEvents',
            'completedEvents',
            'totalRegistrations',
            'pendingCount',
            'acceptedCount',
            'rejectedCount',
            'monthlyData',
            'topEvents',
            'recentRegistrations',
            'approvalRate'
        ));
    }

    /**
     * Show QR code for event check-in
     */
    public function showQRCode(Event $event)
    {
        // Only UKM coordinators can view
        abort_if($event->ukm_id !== Auth::user()->ukm_id, 403, 'Unauthorized access.');

        // Get accepted registrations for this event
        $registrations = $event->registrations()
            ->with('user')
            ->where('status', 'accepted')
            ->orderBy('checked_in_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate attendance stats
        $totalRegistrations = $registrations->count();
        $checkedInCount = $registrations->whereNotNull('checked_in_at')->count();
        $attendanceRate = $totalRegistrations > 0 ? round(($checkedInCount / $totalRegistrations) * 100, 1) : 0;

        // Generate secure check-in token
        $checkInToken = hash('sha256', config('app.key') . $event->id);
        $checkInUrl = route('events.check-in', ['event' => $event->id, 'token' => $checkInToken]);

        return view('ukms.events.qr-code', compact('event', 'registrations', 'totalRegistrations', 'checkedInCount', 'attendanceRate', 'checkInUrl'));
    }

    /**
     * Handle QR code check-in
     */
    public function checkIn($eventId, $token)
    {
        // Verify token matches event
        $expectedToken = hash('sha256', config('app.key') . $eventId);
        if ($token !== $expectedToken) {
            abort(403, 'Invalid QR code');
        }

        $event = Event::findOrFail($eventId);

        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info', 'Please log in to check in to this event.');
        }

        // Check if user is registered
        $registration = EventRegistration::where('event_id', $eventId)
            ->where('user_id', Auth::id())
            ->where('status', 'accepted')
            ->first();

        if (!$registration) {
            return redirect()->route('events.show', $event)
                ->with('error', 'You are not registered for this event or your registration is not accepted.');
        }

        // Mark as checked in if not already
        if (!$registration->checked_in_at) {
            $registration->update([
                'checked_in_at' => now(),
                'check_in_method' => 'qr'
            ]);

            return redirect()->route('events.show', $event)
                ->with('success', 'Successfully checked in! Welcome to ' . $event->title);
        }

        return redirect()->route('events.show', $event)
            ->with('info', 'You have already checked in at ' . $registration->checked_in_at->format('g:i A, M j'));
    }

    /**
     * Manual check-in toggle
     */
    public function manualCheckIn(Event $event, EventRegistration $registration)
    {
        // Only UKM coordinators can manually check in
        abort_if($event->ukm_id !== Auth::user()->ukm_id, 403, 'Unauthorized access.');

        if ($registration->event_id !== $event->id) {
            abort(403, 'Invalid registration');
        }

        // Toggle check-in status
        if ($registration->checked_in_at) {
            $registration->update([
                'checked_in_at' => null,
                'check_in_method' => null
            ]);
            $message = 'Check-in removed for ' . $registration->user->name;
        } else {
            $registration->update([
                'checked_in_at' => now(),
                'check_in_method' => 'manual'
            ]);
            $message = $registration->user->name . ' marked as checked in';
        }

        return redirect()->back()->with('success', $message);
    }
}
