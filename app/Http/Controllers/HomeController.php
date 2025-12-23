<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\UKM;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Eager load UKM relationship to prevent N+1 queries
        $events = Event::with('ukm:id,name,logo')
            ->latest()
            ->take(4)
            ->get();

        $ukms = UKM::where('verification_status', 'active')
            ->take(4)
            ->get();

        return view('home', compact('events', 'ukms'));
    }

    /**
     * Search events and UKMs.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $ukmId = $request->input('ukm');
        $eventId = $request->input('event');

        // If specific event selected, redirect to event details
        if ($eventId) {
            return redirect()->route('events.show', $eventId);
        }

        // Build events query
        $eventsQuery = Event::with('ukm:id,name,logo');

        if ($query) {
            $eventsQuery->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('location', 'like', "%{$query}%");
            });
        }

        if ($ukmId) {
            $eventsQuery->where('ukm_id', $ukmId);
        }

        $events = $eventsQuery->latest()->paginate(12);
        $ukms = UKM::where('verification_status', 'active')->get();

        return view('search-results', compact('events', 'ukms', 'query', 'ukmId'));
    }

    /**
     * Display calendar view of events.
     */
    public function calendar()
    {
        return view('calendar');
    }

    /**
     * Get events data for calendar (API endpoint).
     */
    public function calendarEvents(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $query = Event::with('ukm:id,name,logo');

        if ($start && $end) {
            $query->whereBetween('event_date', [$start, $end]);
        }

        $events = $query->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->event_date->format('Y-m-d'),
                'url' => route('events.show', $event->id),
                'backgroundColor' => $this->getEventColor($event),
                'borderColor' => $this->getEventColor($event),
                'extendedProps' => [
                    'location' => $event->location,
                    'ukm' => $event->ukm->name,
                    'status' => $event->status,
                    'registrations' => $event->registrations()->count(),
                ],
            ];
        });

        return response()->json($events);
    }

    /**
     * Get color based on event status.
     */
    private function getEventColor($event)
    {
        return match($event->status) {
            'upcoming' => '#0d6efd', // Blue
            'ongoing' => '#198754',   // Green
            'completed' => '#6c757d', // Gray
            default => '#0d6efd',
        };
    }
}
